<?php
function displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display) {
    global $conn;
    global $data;
    if ($hasInpatientDetails) {
        //get patient info Dicharged
        
        $result = mysqli_query($conn,"SELECT ad.Admision_ID, Admission_Date_Time,Discharge_Employee_ID,Employee_Name,Admission_Status,Hospital_Ward_Name, ad.Bed_Name,Kin_Name,ToBe_Admitted_Reason,Discharge_Date_Time, Employee_Name, dr.Discharge_Reason FROM tbl_admission ad, tbl_check_in_details cd, tbl_hospital_ward hw, tbl_employee e, tbl_discharge_reason dr WHERE cd.Admission_ID = ad.Admision_ID AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND e.Employee_ID=ad.Admission_Employee_ID AND consultation_ID = '$consultation_ID'  AND ad.Registration_ID='$Registration_ID' AND cd.Admit_Status='admitted'") or die(mysqli_error($conn));
        
        $info = mysqli_fetch_array($result);
        $Admission_Date_Time = $info['Admission_Date_Time'];
        $Admission_Employee_ID = $info['Admission_Employee_ID'];
        $Admission_Status = $info['Admission_Status'];
        $Hospital_Ward_Name = $info['Hospital_Ward_Name'];
        $Bed_Name = $info['Bed_Name'];
        $Admision_ID = $info['Admision_ID'];
        $Kin_Name = $info['Kin_Name'];
        $Admit_Employee_Name = $info['Employee_Name'];
        $continuation_sheet = $info['ToBe_Admitted_Reason'];
        
        $Discharge_Employee_ID = $info['Discharge_Employee_ID'];
        
        if ($Admission_Status == 'pending') {
            $Admission_Status = 'In Discharge State';
        }
        $dischargedata='';
        if($Admission_Status=='Discharged'){
            $selectDischarge = mysqli_query($conn, "SELECT discharge_condition, pending_setter,pending_set_time, Discharge_Reason,Discharge_Date_Time, Employee_Name FROM tbl_employee e, tbl_discharge_reason dr, tbl_admission ad  WHERE ad.Admision_ID='$Admision_ID' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND ad.Registration_ID='$Registration_ID' AND   ad.Discharge_Employee_ID=e.Employee_ID AND  Discharge_Employee_ID = '$Discharge_Employee_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($selectDischarge)>0){
                while($row = mysqli_fetch_assoc($selectDischarge)){
                    $DischrgeEmployee_Name = $row['Employee_Name'];
                    $Discharge_Date_Time = $row['Discharge_Date_Time'];
                    $Discharge_Reason = strtoupper($row['Discharge_Reason']);
                    $pending_setter = $row['pending_setter'];
                    $pending_set_time = $row['pending_set_time'];
                    $discharge_condition = $row['discharge_condition'];
                    $dischargedata .='<tr>
                        <td style="width:10%;text-align:right "><b>Discharge Status</b></td>
                        <td>' . $Discharge_Reason . '</td>
                        <td style="width:10%;text-align:right "><b>Discharged By #</b></td>
                        <td>' . $DischrgeEmployee_Name . '</td>
                        <td style="width:10%;text-align:right "><b>Discharge Time</b></td>
                        <td>' . $Discharge_Date_Time . '</td>
                    </tr>';
                    if($pending_setter != NULL && $pending_setter != ''){
                    $DoctorSugestedDischarge = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROm tbl_employee WHERE Employee_ID='$pending_setter'"))['Employee_Name'];
                    $dischargedata .='<tr>
                        <td style="width:10%;text-align:right " ><b>Doctor Discharged </b></td>
                        <td colspan="2">' . $DoctorSugestedDischarge . '</td>
                        <td style="width:10%;text-align:right " ><b>Doctor Discharge Time</b></td>
                        <td colspan="2">' . $pending_set_time . '</td>
                    </tr>';
                    }
                    if($discharge_condition =='dead'){

                        $dischargedata .="<tr><td></td><td colspan='6'><table width='80%'><tr><td>#</td><td><td><b>Caurse Of death</b></td></tr>";
                        $dnum=1;
                        $selectdisease = mysqli_query($conn, "SELECT disease_name FROM tbl_disease_caused_death WHERE Admision_ID='$Admision_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($selectdisease)>0){
                            while($rw = mysqli_fetch_assoc($selectdisease)){
                                $disease_name = $rw['disease_name'];
                                $dischargedata.="<tr><td>$dnum</td><td>$disease_name</td></tr>";
                            }
                        }
                        $dischargedata.="</table></td></tr>";
                    }
                }
            }else{
                $Discharge_Date_Time ='';
                $Discharge_Reason='';
                $DischrgeEmployee_Name='';
            }
        }else{
            $dischargedata='';
        }
        #---------------------------------------------
        $room_transfer='';
            $transferData='';
            $select_transfer_details=mysqli_query($conn,"SELECT Hospital_Ward_ID FROM  tbl_patient_transfer_details WHERE Admision_ID='$Admision_ID' AND Registration_ID='$Registration_ID'");

            if(mysqli_num_rows($select_transfer_details)>0){
                $room_transfer.="<td style='text-align:right '><b>Transfer Ward</b></td><td colspan='5'>";
                
                $get_ward_names=mysqli_query($conn,"SELECT Hospital_Ward_Name, Received_Date,b.Bed_Name, room_name, reasson_for_tranfer, Employee_Name FROM tbl_employee e, tbl_patient_transfer_details As tptd,tbl_hospital_ward As thw, tbl_beds b, tbl_ward_rooms wr WHERE tptd.Bed_ID =b.Bed_ID AND b.ward_room_id=wr.ward_room_id AND tptd.Admision_ID='$Admision_ID' AND tptd.Registration_ID='$Registration_ID' AND tptd.Hospital_Ward_ID=thw.Hospital_Ward_ID AND Received_By=e.Employee_ID AND Transfer_Status='received'") or die(mysqli_error($conn));
                if(mysqli_num_rows($get_ward_names)>0){
                    while($ward=mysqli_fetch_assoc($get_ward_names)){
                        $room_name = $ward['room_name'];
                        $reasson_for_tranfer = $ward['reasson_for_tranfer'];
                        $Receivedby=$ward['Employee_Name'];
                        $Bed_Name=$ward['Bed_Name'];
                        $room_transfer.=' '.$ward['Hospital_Ward_Name'].' &nbsp;&nbsp;&nbsp; <b>Since:</b> '.$ward['Received_Date'].' &nbsp;&nbsp;&nbsp;<b> Room:</b> '.$room_name.' &nbsp;&nbsp;&nbsp; <b>Bed Name:</b> '.$Bed_Name.' &nbsp;&nbsp;&nbsp;<b> Received By:</b> &nbsp;&nbsp;&nbsp;'.$Receivedby.' <b>Transfer Reason:</b>' .$reasson_for_tranfer.'<br/><br/>';
                    }
                }else{
                    $room_transfer.="No result found";
                }
                $room_transfer.="</td>";
                $transferData="<tr>$room_transfer</tr>";
            }
            #---------------------------------------------


        $data .='
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center " id="inpatient">
        <b align="center">INPATIENT DETAILS</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" width="100%" border="0" style="border:none !important">
            <tr>
                <td style="width:15%;text-align:right "><b>Admission Date</b></td>
                <td>' . $Admission_Date_Time . '</td>
                <td style="width:10%;text-align:right "><b>Admitted By</b></td>
                <td>' . $Admit_Employee_Name . '</td>
                <td style="width:10%;text-align:right "><b>Patient Status</b></td>
                <td>' . $Admission_Status . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Current Ward</b></td>
                <td>' . $Hospital_Ward_Name . '</td>
                <td style="width:10%;text-align:right "><b>Bed #</b></td>
                <td>' . $Bed_Name . '</td>
                <td style="width:10%;text-align:right "><b>Kin Name</b></td>
                <td>' . $Kin_Name . '</td>
            </tr>';
           
            $data .='<tr>
                <td style="width:10%;text-align:right "><b>Continuation Sheet:</b></td>
                <td colspan="5" style="border:1px solid #ccc">' . $continuation_sheet . '</td>
           </tr> ';
           $data.=$transferData;
           $data.=$dischargedata;
           $data.=' 
        </table>
    </div> 
    <br/><br/>
    ';
        $sn = 1;

        $rsDoc = mysqli_query($conn,"SELECT clinical_history,e.Employee_Name,e.Employee_ID,wr.Findings,wr.investigation_comments,wr.Ward_Round_Date_And_Time,wr.remarks FROM tbl_ward_round wr,  tbl_employee e WHERE wr.Employee_ID=e.Employee_ID AND wr.consultation_ID='$consultation_ID' AND wr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        
        if (mysqli_num_rows($rsDoc) > 0) {
            $data .='<div  width="100%" id="findings-remarks">
        <div style="padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  ">
            <b>FINDINGS AND REMARKS</b>
        </div>
        ';
            $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td>#</td><td><b>Doctor\'s Name</b></td><td><b>Findings</b></td><td><b>Clinical History</b></td><td><b>Investigation comments</b></td><td><b>Remarks</b></td><td><b>Date Consulted</b></td>
            </tr>';
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['Employee_ID'];
                $findings = $doctorsInfo['Findings'];
                $remarks = $doctorsInfo['remarks'];
                $clinical_history = $doctorsInfo['clinical_history'];
                $investigation_comments = $doctorsInfo['investigation_comments'];
                $Ward_Round_Date_And_Time = $doctorsInfo['Ward_Round_Date_And_Time'];
                //  if (!empty($findings) || !empty($investigation_comments) || !empty($remarks)) {

                $data .= '<tr>'
                        . '<td>' . $sn++ . '</td>'
                        . '<td>' . $doctorsName . '</td>'
                        . '<td>' . $findings . '</td>'
                        . '<td>' . $clinical_history . '</td>'
                        . '<td>' . $investigation_comments . '</td>'
                        . '<td>' . $remarks . '</td>'
                        . '<td>' . $Ward_Round_Date_And_Time . '</td>'
                        . '</tr>';
                //}
            }
            $data .= '</table></div>';
        }
        
        $results = mysqli_query($conn,"SELECT e.Employee_Name,wr.Employee_ID, e.Employee_Type FROM tbl_ward_round_disease wd, tbl_ward_round wr, tbl_employee e WHERE wr.Round_ID = wd.Round_ID AND e.Employee_ID = wr.Employee_ID AND wr.consultation_ID='$consultation_ID' GROUP BY Employee_Name, wr.consultation_ID  ") or die(mysqli_error($conn));
        $sn = 1;
        if (mysqli_num_rows($results) > 0) {
            $data .="
    <div width='100%' id='diagonsis'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>DIAGNOSIS</b>
        </div>";

            while ($row = mysqli_fetch_array($results)) {

                //selecting diagnosois
                $doctorsName = $row['Employee_Name'];
                $employee_Type = ucfirst(strtolower($row['Employee_Type']));

                $data .= '<br/><table id="diagonsis" border="1" width="100%" style="margin-left:2px">
            <tr><td colspan="8"><b>' . $sn++ . '.</b>&nbsp;&nbsp;&nbsp;<b>' . $employee_Type . '.&nbsp;&nbsp;&nbsp ' . $doctorsName . '</b></td></tr>
            </table>    
            ';

                $diagnosis_qr = "SELECT  diagnosis_type,disease_name,Round_Disease_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d
		    WHERE 
                    wd.disease_ID = d.disease_ID AND
                    wr.Round_ID = wd.Round_ID AND
                    wr.consultation_ID ='$consultation_ID' AND
                    wr.Employee_ID='" . $row['Employee_ID'] . "'";
                $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
                $provisional_diagnosis = '';
                $diferential_diagnosis = '';
                $diagnosis = '';

                $data .= ' <table id="diagonsis" border="1" width="100%" style="margin-left:2px">
                     <tr><td>#</td><td><b>Provisional Diagnosis</b></td><td><b>Differential Diagnosis</b></td><td><b>Final Diagnosis</b></td><td style="text-align:center"><b>Disease Code</b></td><td><b>Date Consulted</b></td></tr>';


                if (@mysqli_num_rows($result) > 0) {
                    $temp = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                            $provisional_diagnosis = ' ' . $row['disease_name'];
                            $dignosis_Date = $row['Round_Disease_Date_And_Time'];
                            $disease_code = $row['disease_code'];
                            $diferential_diagnosis = '';
                            $diagnosis = '';

                            $data .= '<tr>'
                                    . '<td>' . $temp++ . '</td>'
                                    . '<td>' . $provisional_diagnosis . '</td>'
                                    . '<td>' . $diferential_diagnosis . '</td>'
                                    . '<td>' . $diagnosis . '</td>'
                                    . '<td style="text-align:center">' . $disease_code . '</td>'
                                    . '<td>' . $dignosis_Date . '</td>'
                                    . '</tr>';
                        }
                        if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                            $diferential_diagnosis = $row['disease_name'];
                            $dignosis_Date = $row['Round_Disease_Date_And_Time'];
                            $disease_code = $row['disease_code'];
                            $provisional_diagnosis = '';
                            //$diferential_diagnosis = '';
                            $diagnosis = '';
                            $data .= '<tr>'
                                    . '<td>' . $temp++ . '</td>'
                                    . '<td>' . $provisional_diagnosis . '</td>'
                                    . '<td>' . $diferential_diagnosis . '</td>'
                                    . '<td>' . $diagnosis . '</td>'
                                    . '<td style="text-align:center">' . $disease_code . '</td>'
                                    . '<td>' . $dignosis_Date . '</td>'
                                    . '</tr>';
                        }
                        if ($row['diagnosis_type'] == 'diagnosis') {
                            $diagnosis = $row['disease_name'];
                            $dignosis_Date = $row['Round_Disease_Date_And_Time'];
                            $disease_code = $row['disease_code'];
                            $provisional_diagnosis = '';
                            $diferential_diagnosis = '';
                            $data .= '<tr>'
                                    . '<td>' . $temp++ . '</td>'
                                    . '<td>' . $provisional_diagnosis . '</td>'
                                    . '<td>' . $diferential_diagnosis . '</td>'
                                    . '<td>' . $diagnosis . '</td>'
                                    . '<td style="text-align:center">' . $disease_code . '</td>'
                                    . '<td>' . $dignosis_Date . '</td>'
                                    . '</tr>';
                        }
                    }
                }
                $data .= '</table>';
            }
            $data .= " </tr></table></div>";
        }

        $sn = 1;


        $rsDoc = mysqli_query($conn,"SELECT e.Employee_Name,e.Employee_ID,wr.Comment_For_Laboratory,wr.Comment_For_Radiology,wr.Comment_For_Procedure,wr.Comment_For_Surgery,wr.Ward_Round_Date_And_Time FROM tbl_ward_round wr, tbl_employee e WHERE wr.Employee_ID=e.Employee_ID AND wr.consultation_ID='$consultation_ID' AND wr.Registration_ID=$Registration_ID") or die(mysqli_error($conn));

        if (mysqli_num_rows($rsDoc) > 0) {
            $data .= "    
    <div width='100%' id='in-departmental-comment'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left '>
            <b>DEPARTMENTAL DOCTOR'S COMMENTS</b>
        </div>
        <table id='userCemmnts' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td>#</td><td><b>Doctor\'s Name</b></td><td><b>Laboratory</b></td><td><b>Radiology</b></td><td><b>Procedure</b></td><td><b>Surgery</b></td><td><b>Date Consulted</b></td>
            </tr>";
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['Employee_ID'];
                $Comment_For_Laboratory = $doctorsInfo['Comment_For_Laboratory'];
                $Comment_For_Radiology = $doctorsInfo['Comment_For_Radiology'];
                $Comment_For_Procedure = $doctorsInfo['Comment_For_Procedure'];
                $Comment_For_Surgery = $doctorsInfo['Comment_For_Surgery'];
                $Ward_Round_Date_And_Time = $doctorsInfo['Ward_Round_Date_And_Time'];

                //if (!empty($Comment_For_Laboratory) || !empty($Comment_For_Radiology) || !empty($Comment_For_Procedure) || !empty($Comment_For_Surgery)) {
                $data .= '<tr>'
                        . '<td>' . $sn++ . '</td>'
                        . '<td>' . $doctorsName . '</td>'
                        . '<td>' . $Comment_For_Laboratory . '</td>'
                        . '<td>' . $Comment_For_Radiology . '</td>'
                        . '<td>' . $Comment_For_Procedure . '</td>'
                        . '<td>' . $Comment_For_Surgery . '</td>'
                        . '<td>' . $Ward_Round_Date_And_Time . '</td>'
                        . '</tr>';
                //}
            }
            $data .= " </table>
    </div>";
        }

        $qrLab = "SELECT Payment_Item_Cache_List_ID,i.Item_ID,test_result_ID,Employee_Name,Product_Name,ilc.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache ilc, tbl_test_results trs, tbl_payment_cache pc, tbl_employee e, tbl_items i, tbl_consultation tc WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND i.Item_ID=ilc.Item_ID AND e.Employee_ID=ilc.Consultant_ID AND tc.consultation_ID = pc.consultation_id AND tc.Registration_ID='$Registration_ID' AND pc.consultation_id ='$consultation_ID'AND  Payment_Item_Cache_List_ID = payment_item_ID AND Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Laboratory'";


        $qrLabWithoutResult = "SELECT Payment_Item_Cache_List_ID,i.Item_ID,Employee_Name,Product_Name,ilc.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_items i,  tbl_employee e, tbl_consultation tc WHERE pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND i.Item_ID=ilc.Item_ID AND e.Employee_ID=ilc.Consultant_ID AND tc.consultation_ID=pc.consultation_id AND ilc.Status != 'notsaved' AND tc.Registration_ID='$Registration_ID' AND pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Laboratory'";


        $result = mysqli_query($conn,$qrLab) or die(mysqli_error($conn));
        $resultWithout = mysqli_query($conn,$qrLabWithoutResult) or die(mysqli_error($conn));
        $tempIlc = array();
        $temp = 1;
        if (mysqli_num_rows($result) > 0) {
            $data .= "
    <div width='100%' id='Laboratory_Results'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>LABORATORY RESULTS</b>
        </div>";

            while ($row = mysqli_fetch_array($result)) {
                $tempIlc[] = $row['Payment_Item_Cache_List_ID'];
                $st = '';
                $ppil = $row['Payment_Item_Cache_List_ID'];
                $item_ID = $row['Item_ID'];
                $check_result_id = $row['test_result_ID'];

                $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='$check_result_id'")or die(mysqli_error($conn));

                if (mysqli_num_rows($RS) > 0) {
                    $rowSt = mysqli_fetch_assoc($RS);

                    $doctorsName = $row['Employee_Name'];
                    $Submitted = $rowSt['Submitted'];
                    $Validated = $rowSt['Validated'];
                    $remarks = $row['remarks'];
                    $validator = $rowSt['ValidatedBy'];
                    $SavedBy = $rowSt['SavedBy'];

                    $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $validator . "'");

                    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);

                    $submitor_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $SavedBy . "'");
                    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);

                    if ($Validated == 'Yes' && $Submitted == 'Yes') {
                        $resultLab = '<span style="color:blue;text-align:center;">Done</span>';
                    } else if (in_array($Validated, array('No', '')) && in_array($Submitted, array('No', ''))) {
                        $resultLab = "No result";
                    } else {
                        $resultLab = '<span style="text-align:center;color: red;">Provisional</span>';
                    }

                    $number=1;
                    if ($resultLab != "No result") {
                        $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];

                        $getParameters = "SELECT tpr.TimeSubmitted as testResult FROM tbl_tests_parameters_results tpr, tbl_test_results tr WHERE test_result_ID = ref_test_result_ID AND tr.payment_item_ID='$ppil'";
                        $number++;
                        $myparameters1 = mysqli_query($conn,$getParameters);
                        $totalParm = mysqli_num_rows($myparameters1);
                        $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $positive = mysqli_num_rows($postvQry);

                        $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $negative = mysqli_num_rows($negveQry);

                        $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $abnormal = mysqli_num_rows($abnormalQry);

                       $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE  ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $normal = mysqli_num_rows($normalQry);
                        $resultNomal= mysqli_fetch_array($normalQry);

                        $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $high = mysqli_num_rows($highQry);

                        $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $low = mysqli_num_rows($lowQry);


                        $resultQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND  ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $no_results = mysqli_num_rows($lowQry);

                        if ($totalParm > 0) {

                            if ($positive == $totalParm) {
                                $resultLab = "Positive";
                            } elseif ($negative == $totalParm) {
                                $resultLab = "Negative";
                            } elseif ($abnormal == $totalParm) {
                                $resultLab = "Abnormal";
                            }elseif ($no_results == $totalParm) {
                            
                                $resultLab = "Normal";
                                
                                
                            }elseif ($normal == $totalParm) {
                                $resultLab = $resultNomal['result'];
                            
                            } elseif ($high == $totalParm) {
                                $resultLab = "High";
                            } elseif ($low == $totalParm) {
                                $resultLab = "Low";
                            } else {
                                $resultLab = "No result";
                                $get_submitor_Name['Employee_Name'] = '';
                                $get_Validator_Name['Employee_Name'] = '';
                            }
                        }
                    }
                } else {
                    //check if sample rejected or received
                    $get_reject_or_receive_status = "SELECT received_status,Rejection_Status FROM tbl_specimen_results WHERE payment_item_ID='" . $row['Payment_Item_Cache_List_ID'] . "'";

                    $get_reject_or_receive_status_result=mysqli_query($conn,$get_reject_or_receive_status) or die(mysqli_error($conn));
                    if(mysqli_num_rows($get_reject_or_receive_status_result)>0){
                        $rows_rc_rj=mysqli_fetch_assoc($get_reject_or_receive_status_result);
                        $received_status=$rows_rc_rj['received_status'];
                        $Rejection_Status=$rows_rc_rj['Rejection_Status'];
                        if($received_status=="received"){
                            $resultLab = 'Sample Received';
                        }else if($Rejection_Status=="rejected"){
                            $resultLab = 'Sample Rejected';
                        }else{
                            $resultLab = 'No result';
                        }
                    }else{
                       $resultLab = 'No result'; 
                    }
                    $get_submitor_Name['Employee_Name'] = '';
                    $get_Validator_Name['Employee_Name'] = '';
                }

                $image = '';
                $remarks = '';
                //if($resultLab != "No result"){   
                $query = mysqli_query($conn,"SELECT Attachment_Url,Description from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");

                while ($attach = mysqli_fetch_array($query)) {

                    if ($attach['Attachment_Url'] != '') {
                        if ($resultLab == "No result" && empty($attach['Description'])) {
                            
                        } else {
                            $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/attachement.png' width='50' height='50' alt='Not Image File' /></a>&nbsp;&nbsp;";
                        }
                    }

                    if (!empty($attach['Description'])) {
                        $remarks = $attach['Description'];
                    }
                }
                //}

                $srDisp = '';
                if (!empty($resultLab)) {
                    $srDisp = '<td style="text-align:center"><b>Result</b></td>';
                }


                $data .= '<br/> <table id="labresult" border="1" width="100%" style="margin-left:2px">
            <tr><td colspan="8"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>
            <tr>
               <td><b>Doctor\'s Ordered</b></td><td><b>Doctor\'s Notes</b></td><td><b>Lab Remarks</b></td>' . $srDisp . '<td><b>Attachment</b></td>' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '<td><b>Performer</b></td><td><b>Validator</b></td><td><b>Date</b></td><td>Payment</td>
            </tr>';

$check_speciment = mysqli_query($conn,"SELECT Status,Transaction_Type FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID=$ppil");

            //$st = '<span style="color:blue;text-align:center;">Done</span>';
            $check_speciment = mysqli_query($conn,"SELECT Status,Patient_Payment_ID,Transaction_Type FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID=$ppil");
            while($get_specimen_row = mysqli_fetch_assoc($check_speciment)){
                $get_specimen_status = $get_specimen_row['Status'];
                $Patient_Payment_ID = $get_specimen_row['Patient_Payment_ID'];
                $Transaction_Type = $get_specimen_row['Transaction_Type'];
            }

            $check_result_test = mysqli_query($conn,"SELECT Submitted,Validated,Saved FROM tbl_tests_parameters_results WHERE ref_test_result_ID=$check_result_id");
            while($get_result_test_row = mysqli_fetch_assoc($check_result_test)){
                $Submitted = $get_result_test_row['Submitted'];
                $Validated = $get_result_test_row['Validated'];
                $Saved = $get_result_test_row['Saved'];
            }

            if($get_specimen_status == 'active' || $get_specimen_status == 'paid'){
                $st = '<span style="color:blue;text-align:center;">In Progress</span>';  
            }
            else{
                if($Submitted == 'Yes' && $Validated == 'Yes' && $Saved == 'Yes'){
                    $st = '<span style="color:blue;text-align:center;">Done</span>'; 
                }
                else if($Submitted == 'No' && $Validated == 'No' && $Saved == 'Yes'){
                    $st = '<span style="color:blue;text-align:center;">Sample Save</span>'; 
                }
                else{
                    $st = '<span style="color:blue;text-align:center;">'.$get_specimen_status.'</span>'; 
                }
            }

            $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($paymet_check)>0){
                while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                    $payment_type = $patient_check_payament_row['payment_type'];
                    $Billing_Type = $patient_check_payament_row['Billing_Type'];
                }
                }else{
                    $Billing_Type ='';
                    $payment_type ='';
                }
            //PAYMENT STATUS FOR LABORATORY

            if($get_specimen_status == 'active'){
                if($Transaction_Type=="Cash"){
                    $pyd = "<span style='color:red;text-align:center;'>Not Paid</span>";
                 }else{
                     $pyd = '<span style="color:red;text-align:center;">Not Billed</span>';
                 } 
            }
            
            else if($get_specimen_status=='approved'){
                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
            }else if($get_specimen_status=='paid'){
                if($Transaction_Type=="Cash"){
                    $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                 }else{
                     $pyd = '<span style="color:green;text-align:center;">billed</span>';
                } 
            }else if($get_specimen_status=='approved'){
                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
            }else if($get_specimen_status=='served'){
                if($Transaction_Type=="Cash"){
                    $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                 }else{
                     $pyd = '<span style="color:green;text-align:center;">billed</span>';
                 } 
            }
                
                $Product_Name=$row['Product_Name'];
            
            $hide_btn="";
                $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$ppil' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)<=0){
                    $hide_btn="class='hide'";
                }else{
                    $hide_btn="class='art-button-green'";
                }
                    $data .= "<tr>";
                    $data .= "<td>" . $doctorsName . "</td>";
                    $data .= "<td>" . $row['Doctor_Comment'] . "</td>";
                    $data .= "<td>" . $remarks . "</td>";
                    $data .= (!empty($resultLab)) ? "<td style='text-align: center;color: rgb(28, 110, 120);font-weight: bold;'>" . $resultLab . "</td>" : '';
                    $data .= "<td style='text-align:center'>" . $image . "<input type='button' $hide_btn onclick='preview_lab_result(\"$Product_Name\",\"$ppil\")' value='View Result'></td>";
                    $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                    $data .= "<td>" . $get_submitor_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $get_Validator_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $row['Transaction_Date_And_Time'] . "</td>";
                    $data .= "<td>" . $pyd . "</td>";
                    $data .= "</tr></table>";

                // $myparameters=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='".$row['test_result_ID']."'")or die(mysqli_error($conn));
               // if (empty($resultLab)) {
                    $selectQuery = "SELECT * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $item_ID . "' AND tr.payment_item_ID='" . $ppil . "'  AND Registration_ID='" . $Registration_ID . "' AND Validated = 'Yes' AND Parameter_Name !='default_parameter'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
                    //"SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID JOIN tbl_parameters ON parameter_ID=ref_parameter_ID JOIN tbl_tests_parameters_results ON ref_test_result_ID=test_result_ID WHERE Item_ID='".$id."' GROUP BY PARAMETER_NAME";
                    $results = mysqli_query($conn,$selectQuery) or die(mysqli_error($conn));

                    if (mysqli_num_rows($results)) {
                        $data .= "<table class='' style='width:100%;margin-left:2px'>
                <tr>
                <td width='1%'>S/N</td>
                <td width='' style='text-align:left'><b>Parameters</b></td>
                <td width='' style='text-align:left'><b>Results</b></td>
                <td width='' style='text-align:left'><b>UoM</b></td>
                <td width='' style='text-align:left'><b>M</b></td>
                <td width='' style='text-align:left'><b>V</b></td>
                <td width='' style='text-align:left'><b>S</b></td>
		<td width='' style='text-align:left'><b>Normal Value</b></td>
                <td width='' style='text-align:left'><b>Status</b></td>
                <td width='' style='text-align:left'><b>Previous results</b></td>
               </tr>";

                        //  }

                        while ($row2 = mysqli_fetch_assoc($results)) {
                            $testID = $row2['test_result_ID'];
                            $paymentID = $row2['payment_item_ID'];
                            $input = $row2['result'];
                            // $Transaction_Type = $row2['Transaction_Type'];

                            $data .= '<tr>';
                            $data .= '<td>' . $sn++ . '</td>';
                            $data .= '<td>' . $row2['Parameter_Name'] . '</td>';
                            $data .= '<td>' . $input . '</td>';

                            $data .= '<td>' . $row2['unit_of_measure'] . '</td>';
                            if ($row2['modified'] == "Yes") {
                                $data .= '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" paraname="' . $row2['Parameter_Name'] . '" paymentID="' . $testID . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
                            } else {
                                $data .= '<td></td>';
                            }

                            if ($row2['Validated'] == "Yes") {
                                $data .= '<td>&#x2714;</td>';
                                $Validated = true;
                            } else {
                                $data .= '<td></td>';
                            }
                            if ($row2['Submitted'] == "Yes") {
                                $data .= '<td>&#x2714;</td>';
                            } else {
                                $data .= '<td></td>';
                            }

                            $data .= '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

                            $lower = $row2['lower_value'];
                            $upper = $row2['higher_value'];
                            $rowResult = $row2['result'];
                            $Saved = $row2['Saved'];
                            $result_type = $row2['result_type'];
                            if ($result_type == "Quantitative") {
                                if ($rowResult > $upper) {
                                    $data .= '<td><p style="color:rgb(255,0,0)">H</p></td>';
                                } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                                    $data .= '<td><p style="color:rgb(255,0,0)">L</p></td>';
                                } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                                    $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                                } else {
                                    $data .= '<td><p style="color:rgb(0,128,0)"></p></td>';
                                }
                            } else if ($result_type == "Qualitative") {
                                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                            } else {
                                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                            }


                            //Get previous test results
                            $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$Registration_ID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $testID . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                            $Queryhistory = mysqli_query($conn,$historyQ);
                            $myrows = mysqli_num_rows($Queryhistory);
                            if ($myrows > 0) {
                                $id = mysqli_fetch_assoc($Queryhistory);
                                $data .= '<td>
                <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $Registration_ID . '" id="' . $row2['parameter_ID'] . '">' .
                                        $myrows . ' Previous result(s)'
                                        . '</p>
               
                </td>';
                            } else {

                                //$data .= $historyQ;
                                $data .= '<td>No previous results</td>';
                            }
                            $data .= "</tr>";
                        }

                        $data .= "</table><br/>";
                    }
                //}

                $resultLab = '';
            }
        }
        if (mysqli_num_rows($resultWithout) > 0) {

            if (mysqli_num_rows($result) == 0) {
                $data .= "
    
        <div width='100%' id='Laboratory_Results'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>LABORATORY RESULTS</b>
        </div>";
            }

            $data1 = '';
            while ($row = mysqli_fetch_array($resultWithout)) {
                if (!in_array($row['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $st = '';
                    $ppil = $row['Payment_Item_Cache_List_ID'];
                    $item_ID = $row['Item_ID'];

                    $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID=''")or die(mysqli_error($conn));
                    $rowSt = mysqli_fetch_assoc($RS);

                    $doctorsName = $row['Employee_Name'];
                    $Submitted = $rowSt['Submitted'];
                    $Validated = $rowSt['Validated'];
                    $remarks = $row['remarks'];
                    $validator = $rowSt['ValidatedBy'];
                    $SavedBy = $rowSt['SavedBy'];

                    $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $validator . "'");
                    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);

                    $submitor_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $SavedBy . "'");
                    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);

                    $check_speciment = mysqli_query($conn,"SELECT Status,Patient_Payment_ID,Transaction_Type FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID=$ppil");
                    while($get_specimen_row = mysqli_fetch_assoc($check_speciment)){
                        $get_specimen_status = $get_specimen_row['Status'];
                        $Patient_Payment_ID = $get_specimen_row['Patient_Payment_ID'];
                        $Transaction_Type = $get_specimen_row['Transaction_Type'];
                    }
                    if($get_specimen_status == 'active' || $get_specimen_status == 'paid'){
                        $st = '<span style="color:blue;text-align:center;">In Progress</span>';  
                    }
                    else{
                       // $st = $get_specimen_status;
                    }

                    $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($paymet_check)>0){
                    while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                        $payment_type = $patient_check_payament_row['payment_type'];
                        $Billing_Type = $patient_check_payament_row['Billing_Type'];
                    }
                    }else{
                        $Billing_Type ='';
                        $payment_type ='';
                    }
                    // PAYMENT STATUS FOR LABORATORY 
                    if($get_specimen_status == 'active'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:red;text-align:center;'>Not Paid</span>";
                         }else{
                             $pyd = '<span style="color:red;text-align:center;">Not Billed</span>';
                         } 
                    }
                    
                    else if($get_specimen_status=='approved'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                    }else if($get_specimen_status=='paid'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                         } 
                    }else if($get_specimen_status=='approved'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                    }else if($get_specimen_status=='served'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                         } 
                    }
                    
                    //retrieve attachment info
                    $query = mysqli_query($conn,"SELECT * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
                    $attach = mysqli_fetch_assoc($query);
                    $image = 'No';
                    if ($attach['Attachment_Url'] != '') {
                        $image = "<b>Yes</b>";
                    }
                    $data .= '<br/> <table id="labresult" border="1" width="100%" style="margin-left:2px">
            <tr><td colspan="8"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>
            <tr>
               <td><b>Doctor\'s Ordered</b></td><td><b>Doctor\'s Notes</b></td><td><b>Lab Remarks</b></td><td><b>Attachment</b></td>' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '<td><b>Performer</b></td><td><b>Validator</b></td><td><b>Date</b></td><td>Payment</td>
            </tr>';$Product_Name=$row['Product_Name'];
            
            $hide_btn="";
                $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$ppil' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)<=0){
                    $hide_btn="class='hide'";
                }else{
                    $hide_btn="class='art-button-green'";
                }
                    $data .= "<tr>";
                    $data .= "<td>" . $doctorsName . "</td>";
                    $data .= "<td>" . $row['Doctor_Comment'] . "</td>";
                    $data .= "<td>" . $remarks . "</td>";
                    $data .= "<td style='text-align:center'>" . $image . "<input type='button' $hide_btn onclick='preview_lab_result(\"$Product_Name\",\"$ppil\")' value='View Result'></td>";
                    $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                    $data .= "<td>" . $get_submitor_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $get_Validator_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $row['Transaction_Date_And_Time'] . "</td>";
                    $data .= "<td>" . $pyd . "</td>";
                    $data .= "</tr></table>";

                    // $myparameters=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='".$row['test_result_ID']."'")or die(mysqli_error($conn));
                    
                    $selectQuery = "SELECT * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '$item_ID' AND tr.payment_item_ID='$ppil'  AND Registration_ID='$Registration_ID'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
                    //"SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID JOIN tbl_parameters ON parameter_ID=ref_parameter_ID JOIN tbl_tests_parameters_results ON ref_test_result_ID=test_result_ID WHERE Item_ID='".$id."' GROUP BY PARAMETER_NAME";
                    $results = mysqli_query($conn,$selectQuery) or die(mysqli_error($conn));


                    $data .= "<table class='' style='width:100%;margin-left:2px'>
                <tr>
                <td width='1%'>S/N</td>
                <td width='' style='text-align:left'><b>Parameters</b></td>
                <td width='' style='text-align:left'><b>Results</b></td>
                <td width='' style='text-align:left'><b>UoM</b></td>
                <td width='' style='text-align:left'><b>M</b></td>
                <td width='' style='text-align:left'><b>V</b></td>
                <td width='' style='text-align:left'><b>S</b></td>
        <td width='' style='text-align:left'><b>Normal Value</b></td>
                <td width='' style='text-align:left'><b>Status</b></td>
                <td width='' style='text-align:left'><b>Previous results</b></td>
               </tr>";

                    while ($row2 = mysqli_fetch_assoc($results)) {
                        $testID = '';
                        $paymentID = $row2['payment_item_ID'];
                        $input = $row2['result'];

                        $data .= '<tr>';
                        $data .= '<td>' . $sn++ . '</td>';
                        $data .= '<td>' . $row2['Parameter_Name'] . '</td>';
                        $data .= '<td>' . $input . '</td>';

                        $data .= '<td>' . $row2['unit_of_measure'] . '</td>';
                        if ($row2['modified'] == "Yes") {
                            $data .= '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" paraname="' . $row2['Parameter_Name'] . '" paymentID="' . $testID . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
                        } else {
                            $data .= '<td></td>';
                        }

                        if ($row2['Validated'] == "Yes") {
                            $data .= '<td>&#x2714;</td>';
                            $Validated = true;
                        } else {
                            $data .= '<td></td>';
                        }
                        if ($row2['Submitted'] == "Yes") {
                            $data .= '<td>&#x2714;</td>';
                        } else {
                            $data .= '<td></td>';
                        }

                        $data .= '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

                        $lower = $row2['lower_value'];
                        $upper = $row2['higher_value'];
                        $rowResult = $row2['result'];
                        $Saved = $row2['Saved'];
                        $result_type = $row2['result_type'];
                        if ($result_type == "Quantitative") {
                            if ($rowResult > $upper) {
                                $data .= '<td><p style="color:rgb(255,0,0)">H</p></td>';
                            } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                                $data .= '<td><p style="color:rgb(255,0,0)">L</p></td>';
                            } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                                $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                            } else {
                                $data .= '<td><p style="color:rgb(0,128,0)"></p></td>';
                            }
                        } else if ($result_type == "Qualitative") {
                            $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                        } else {
                            $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                        }
                         //check echocardiology result

                        //Get previous test results
                        $parameter_ID = $row2['parameter_ID'];
                        $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$Registration_ID' AND tpr.parameter='" . $parameter_ID . "' AND tilc.Item_ID='" . $testID . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                        $Queryhistory = mysqli_query($conn,$historyQ);
                        $myrows = mysqli_num_rows($Queryhistory);
                        if ($myrows > 0) {
                            //$data .= $historyQ;
                            $id = mysqli_fetch_assoc($Queryhistory);

                            $data .= '<td>
                <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $Registration_ID . '" id="' . $row2['parameter_ID'] . '">' .
                                    $myrows . ' Previous result(s)'
                                    . '</p>
               
                </td>';
                        } else {

                            //$data .= $historyQ;
                            $data .= '<td>No previous results</td>';
                        }
                        $data .= "</tr>";
                    }

                    $data .= "</table><br/>";
                }
            }
            $data .="</div>";
        }//endof withought result 


        $tbrad = " <table id='radiology' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td style='width:3%;'><b>SN</b></td>	
                <td><b>Doctor's Ordered</b></b></td>
                <td><b>Test Name</b></b></td>
                 " . ((empty($display)) ? '<td><b>Status</b></td>' : '') . "
                <td><b>Doctor Comment</b></td>	
                <td><b>Radiology Remarks</b></td>	
                <td><b>Radiologist</b></td>	
                <td><b>Sonographer</b></td>	
                <td><b>Sent Date</b></td>
                <td><b>Served Date</b></td>
                <td><b>Attachments</b></td>
                <td><b>Report</b></td>
                <td><b>Echocardiogram</b></td>
                <td>Payment</td>
            </tr>";

        $qr = "SELECT ilc.Consultant_ID,rpt.Status,pc.Registration_ID,i.Product_Name,rpt.Remarks,Payment_Item_Cache_List_ID,ilc.Transaction_Type,rpt.Date_Time,Radiologist_ID,Sonographer_ID,Patient_Payment_Item_List_ID,i.Item_ID,ilc.Transaction_Date_And_Time FROM tbl_radiology_patient_tests rpt, tbl_items i, tbl_item_list_cache ilc, tbl_payment_cache pc WHERE rpt.Item_ID = i.Item_ID AND ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND rpt.Registration_ID = '$Registration_ID' AND 	pc.consultation_id ='$consultation_ID' AND Billing_Type IN ( 'Inpatient Credit', 'Inpatient Cash') AND ilc.Check_In_Type='Radiology'";

        $qrnotdone = "SELECT ilc.Consultant_ID,ilc.Status,ilc.Transaction_Date_And_Time,ilc.Doctor_Comment,pc.Registration_ID,i.Product_Name, ServedDateTime  ,Payment_Item_Cache_List_ID,i.Item_ID FROM 	tbl_item_list_cache ilc  JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID   INNER JOIN tbl_items i ON ilc.Item_ID = i.Item_ID  WHERE   ilc.Status != 'notsaved' AND  pc.Registration_ID = '$Registration_ID' AND 	pc.consultation_id ='$consultation_ID' AND Billing_Type IN ( 'Inpatient Credit', 'Inpatient Cash') AND ilc.Check_In_Type='Radiology'
			";
        //  die($qrnotdone);

        $select_patients_qry = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        $select_patients_notdone_qry = mysqli_query($conn,$qrnotdone) or die(mysqli_error($conn));
        $tempIlc = array();
        $sn = 1;
        if (mysqli_num_rows($select_patients_qry) > 0) {
            $data .= "
    <div width='100%' id='radiology'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>RADIOLOGY TESTS</b>
        </div>
       ";
            $data .= $tbrad;
            while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
                $status = $patient['Status'];
                $patient_numeber = $patient['Registration_ID'];
                $test_name = $patient['Product_Name'];
                $remarks = '' . $patient['Remarks'] . '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }
                $Registration_ID = $patient['Registration_ID'];
                $served_date = $patient['ServedDateTime'];
                $sent_date = $patient['Transaction_Date_And_Time'];
                $Radiologist = $patient['Radiologist_ID'];
                $Sonographer = $patient['Sonographer_ID'];
                $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                $tempIlc[] = $patient['Payment_Item_Cache_List_ID'];
                $Item_ID = $patient['Item_ID'];
                


                $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

                $ppID = mysqli_fetch_assoc($rs);
                $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
                if (mysqli_num_rows($rs) == 0) {
                    $Patient_Payment_ID = 0;
                }

                if ($status == 'done') {
                    $st = '<span style="color:green;text-align:center;">Done</span>';
                } else {
                    $st = '<span style="text-align:center;color: red;">' . ucfirst($status) . '</span>';
                }

                $check_speciment = mysqli_query($conn,"SELECT Status,Patient_Payment_ID,Transaction_Type FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID=$Patient_Payment_Item_List_ID");
                while($get_specimen_row = mysqli_fetch_assoc($check_speciment)){
                    $get_specimen_status = $get_specimen_row['Status'];
                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                    $Transaction_Type = $get_specimen_row['Transaction_Type'];
                }
                // PAYMENT STATUS FOR RADIOLOGY
                    if($get_specimen_status == 'active'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                        }
                    }else if($get_specimen_status=='paid'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                        } 
                    }else if($get_specimen_status=='approved'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                    }else if($get_specimen_status=='served'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                         } 
                    }


                    $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($paymet_check)>0){
                        while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                            $payment_type = $patient_check_payament_row['payment_type'];
                            $Billing_Type = $patient_check_payament_row['Billing_Type'];
                        }
                        }else{
                            $Billing_Type ='';
                            $payment_type ='';
                        }


                    if($get_specimen_status == 'active' || $status == 'approved'){
                        $pyd; 
                    }
                    else{
                        $pyd;
                    }

                $listtype = '';
                $PatientType = '';
                $Doctor = '';
                $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . "&RI=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=" . $listtype;

                /* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
                $photo = "SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID' AND consultation_ID='$consultation_ID'";
                $result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
                $imaging = '';
                if (mysqli_num_rows($result) > 0) {
                    $list = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $list++;
                        // extract($row);
                        $Radiology_Image = $row['Radiology_Image'];
                         if (preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $Radiology_Image)) {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank'><img height='50' alt=''  src='RadiologyImage/" . $Radiology_Image . "'  alt=''/></a>";
                        } else {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank' ><img height='50' alt=''  src='patient_attachments/attachement.png'  alt=''/></a>";
                        }
                    }
                } else {
                    $imaging .= "<b style='text-align: center;color:red'></b>";
                }

                $comm = "<a class='no_color' href='RadiologyTests_Print.php?RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $patient['Payment_Item_Cache_List_ID'] . "' title='Click to view comment added by radiologist' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";

                $results_url = "radiologyviewimage_Doctor.php?II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&RI=" . $Registration_ID . "&Doctor=" . $Doctor;

                $view_results = $imaging;

                //Getting Radiologist Name
                $select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
                $select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_radi_qry) > 0) {
                    while ($radist = mysqli_fetch_assoc($select_radi_qry)) {
                        $Radiologist_Name = $radist['Employee_Name'];
                    }
                } else {
                    $Radiologist_Name = 'N/A';
                }

                //Getting Sonographer Name
                $select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
                $select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_sono_qry) > 0) {
                    while ($sonog = mysqli_fetch_assoc($select_sono_qry)) {
                        $Sonographer_Name = $sonog['Employee_Name'];
                    }
                } else {
                    $Sonographer_Name = 'N/A';
                }

                //Getting Doctors Comments
                $select_docomments = "SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List_ID'";
                $select_docomments_qry = mysqli_query($conn,$select_docomments) or die(mysqli_error($conn));

                if (mysqli_num_rows($select_docomments_qry) > 0) {
                    while ($docom = mysqli_fetch_assoc($select_docomments_qry)) {
                        $thecomm = $docom['Doctor_Comment'];
                        if ($thecomm == '') {
                            $newcom = 'NONE';
                        } else {
                            $newcom = $thecomm;
                        }
                        $Doctor_Comment = $newcom;
                    }
                } else {
                    // $Doctor_Comment = "<input type='text' style='color:#000;' disabled='disabled' value='NONE' />";
                    $Doctor_Comment = "NONE";
                }
                
           $echo_attch="";
                    $echo_att="";
                    $echo = "SELECT payment_item_cache_list_id FROM tbl_clinical_information WHERE payment_item_cache_list_id='$Patient_Payment_Item_List_ID'";
                    $echo_id = mysqli_query($conn,$echo) or die(mysqli_error($conn));
                    $clinic_info_no=mysqli_num_rows($echo_id);

                    $shot_axis_num=mysqli_num_rows(mysqli_query($conn,"SELECT short_item_cache_list_id FROM `tbl_short_axis_veiw` WHERE short_item_cache_list_id='$Patient_Payment_Item_List_ID'"));
                    $long_axis_num=mysqli_num_rows(mysqli_query($conn,"SELECT long_item_cache_list_id FROM `tbl_long_axis_view` WHERE long_item_cache_list_id='$Patient_Payment_Item_List_ID'"));

                    if($clinic_info_no>0||$shot_axis_num>0||$long_axis_num>0){
                          $echo_att= '<a href="echocardiogram_file.php?patient_item_cache_list_id='.$Patient_Payment_Item_List_ID.'&patient_id='.$Registration_ID.'" target="_blank" class="art-button-green pull-right">Echocardiogram Report </a>';  
                    }
                $style = 'style="text-decoration:none;"';
                $Consultant_ID = $patient['Consultant_ID'];
                
                    $doctor_orderd= mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $Consultant_ID . "'");
                    $doctor_orderd_name = mysqli_fetch_assoc($doctor_orderd)['Employee_Name'];

                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $doctor_orderd_name . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= '<td>' . $remarks . '</td>';
                $data .= '<td>' . $Radiologist_Name . '</td>';
                $data .= '<td>' . $Sonographer_Name . '</td>';
                $data .= '<td>' . $sent_date . '</td>';
                $data .= '<td>' . $served_date . '</td>';
                $data .= '<td>' . $view_results . '</td>';
                $data .= '<td>' . $comm . '</td>';
                $data .= '<td>' . $echo_att . '</td>';
                $data .= '<td>'.$pyd.'</td>';
                $data .= '</tr>';
                ;
                $sn++;
            }
    

            $data .= "</table>";
        }if (mysqli_num_rows($select_patients_notdone_qry) > 0) {
            if (mysqli_num_rows($select_patients_qry) == 0) {
                $data .= "
    <div width='100%' id='radiology'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>RADIOLOGY TESTS</b>
        </div>
         ";
            }
            $data .= $tbrad;
            while ($patient = mysqli_fetch_assoc($select_patients_notdone_qry)) {

                if (!in_array($patient['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $status = $patient['Status'];
                    $patient_numeber = $patient['Registration_ID'];
                    $test_name = $patient['Product_Name'];
                    $Doctor_Comment = $patient['Doctor_Comment'];
                    $remarks = 'NONE';
                    $Transaction_Type = $patient['Transaction_Type'];
                    $Registration_ID = $patient['Registration_ID'];
                    $sent_date = $patient['Transaction_Date_And_Time'];
                    $served_date =$patient['ServedDateTime'];
                    $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                    $Item_ID = $patient['Item_ID'];
                    $comm = '';
                    
                     
                    $echo_attch="";
                    $echo_att="";
                    $echo = "SELECT payment_item_cache_list_id FROM tbl_clinical_information WHERE payment_item_cache_list_id='$Patient_Payment_Item_List_ID'";
                    $echo_id = mysqli_query($conn,$echo) or die(mysqli_error($conn));
                    $clinic_info_no=mysqli_num_rows($echo_id);

                    $shot_axis_num=mysqli_num_rows(mysqli_query($conn,"SELECT short_item_cache_list_id FROM `tbl_short_axis_veiw` WHERE short_item_cache_list_id='$Patient_Payment_Item_List_ID'"));
                    $long_axis_num=mysqli_num_rows(mysqli_query($conn,"SELECT long_item_cache_list_id FROM `tbl_long_axis_view` WHERE long_item_cache_list_id='$Patient_Payment_Item_List_ID'"));

                    if($clinic_info_no>0||$shot_axis_num>0||$long_axis_num>0){
                          $echo_attch= '<a href="echocardiogram_file.php?patient_item_cache_list_id='.$Patient_Payment_Item_List_ID.'&patient_id='.$Registration_ID.'" target="_blank" class="art-button-green pull-right">Echocardiogram Report </a>';  
                    }
                    $st = '<span style="text-align:center;color: blue;">In progress</span>';

                    $check_speciment = mysqli_query($conn,"SELECT Status,Patient_Payment_ID, Transaction_Type FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID=$Patient_Payment_Item_List_ID") or die(mysqli_error($conn));
                    while($get_specimen_row = mysqli_fetch_assoc($check_speciment)){
                        $get_specimen_status = $get_specimen_row['Status'];
                        $Patient_Payment_ID = $get_specimen_row['Patient_Payment_ID'];
                        $Transaction_Type = $get_specimen_row['Transaction_Type'];
                    }
                     // PAYMENT STATUS FOR RADIOLOGY2
                     // PAYMENT STATUS FOR procedure2
                    if($get_specimen_status == 'active'){
                        //$pyd = '<span style="color:red;text-align:center;">Not Paid/Billed</span>';

                       
                        if($Transaction_Type=="Cash"){
                            $pyd = '<span style="color:red;text-align:center;">Not Paid</span>';
                         }else{
                             $pyd = '<span style="color:red;text-align:center;">Not billed</span>';
                         }   
                    }
                    else if($get_specimen_status == 'paid'){

                            // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';

                            if($Transaction_Type == "Cash"){
                                $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                            }
                            else{
                                $pyd = '<span style="color:green;text-align:center;">Billed</span>';
                            } 
                    }
                    else if($get_specimen_status == 'served'){
                            // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                            if($Transaction_Type == "Credit"){
                                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                            }
                            else{
                                $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                            } 
                    }else if($get_specimen_status="approved"){
                            $pyd = '<span style="color:green;text-align:center;">Billed</span>';
                        }
                    $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($paymet_check)>0){
                        while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                            $payment_type = $patient_check_payament_row['payment_type'];
                            $Billing_Type = $patient_check_payament_row['Billing_Type'];
                        }
                        }else{
                            $Billing_Type ='';
                            $payment_type ='';
                        }

                    if($get_specimen_status == 'active' || $status == 'approved'){
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>';
                        // $pyd;
                    }
                    else{
                        if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                            $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                         }else{
                            $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                         }
                    }

                    $imaging = '';

                    $view_results = $imaging;

                    //Getting Radiologist Name

                    $Radiologist_Name = 'N/A';

                    //Getting Sonographer Name

                    $Sonographer_Name = 'N/A';

                    //Getting Doctors Comments
                    $Consultant_ID = $patient['Consultant_ID'];
                
                    $doctor_orderd= mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $Consultant_ID . "'");
                    $doctor_orderd_name = mysqli_fetch_assoc($doctor_orderd)['Employee_Name'];

                    $style = 'style="text-decoration:none;"';

                    $data .= '<tr>';
                    $data .= '<td>' . $sn . '</td>';
                    $data .= '<td>' . $doctor_orderd_name . '</td>';
                    $data .= '<td>' . $test_name . '</td>';
                    $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                    $data .= '<td>' . $Doctor_Comment . '</td>';
                    $data .= '<td>' . $remarks . '</td>';
                    $data .= '<td>' . $Radiologist_Name . '</td>';
                    $data .= '<td>' . $Sonographer_Name . '</td>';
                    $data .= '<td>' . $sent_date . '</td>';
                    $data .= '<td>' . $served_date . '</td>';
                    $data .= '<td>' . $view_results . '</td>';
                    $data .= '<td>' . $comm . '</td>';
                    $data .= '<td>' . $echo_att . '</td>';
                    $data .= '<td>'.$pyd.'</td>';
                    $data .= '</tr>';
                    ;
                    $sn++;
                }
            }
           
            $data .= "</table>";
            if (mysqli_num_rows($select_patients_qry) == 0) {
                $data .= "</div>";
            }
        }

        $qri = "SELECT  ilc.Payment_Item_Cache_List_ID,ilc.Status,ilc.Transaction_Type,ilc.Patient_Payment_ID,Product_Name,Doctor_Comment,ilc.Remarks,i.Item_ID,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.Consultant_ID) AS sentby,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.ServedBy) AS servedby,ilc.Transaction_Date_And_Time AS sentOn,ServedDateTime  AS servedOn FROM    tbl_item_list_cache ilc     JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID  JOIN tbl_items i   ON ilc.Item_ID = i.Item_ID   WHERE ilc.Status != 'notsaved' AND pc.Registration_ID = '$Registration_ID' AND    pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Surgery'";

        $select_qri = mysqli_query($conn,$qri) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_qri) > 0) {

            $data .= "
    
    <div width='100%' id='departments_comments'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>SURGERY</b>
        </div>";

            $data .= ' <table  border="1" width="100%" style="margin-left:2px">
                    <tr>
                        <td style="width:3%;"><b>SN</b></td>    
                        <td><b>Test Name</b></b></td>
                        <td><b>Doctor Comment</b></td>
                        ' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '   
                        <td><b>Ordered By</b></td>
                        <td><b>Ordered Date</b></td>
                        <td><b>Served By</b></td>
                        <td><b>Served Date</b></td>
                        <td><b>Notes</b></td>
                        <td><b>Payment</b></td>
                    </tr>';

            $sn = 1;
            while ($patient = mysqli_fetch_assoc($select_qri)) {

                $test_name = $patient['Product_Name'];
                $Doctor_Comment = $patient['Doctor_Comment'];
                // $data .= $test_name;
                $Consultant = $remarks = '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }

                $Payment_Item_Cache_List_ID = $patient['Payment_Item_Cache_List_ID'];

                $Transaction_Type = $patient['Transaction_Type'];
                $Item_ID = $patient['Item_ID'];
                $served_date = $patient['Transaction_Date_And_Time'];
                $sentby = $patient['sentby'];
                $sentOn = $patient['sentOn'];
                $servedby = $patient['servedby'];
                $servedOn = $patient['servedOn'];
                $Statusi = $patient['Status'];
                $Patient_Payment_ID = $patient['Patient_Payment_ID'];

                $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($paymet_check)>0){
                    while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                        $payment_type = $patient_check_payament_row['payment_type'];
                        $Billing_Type = $patient_check_payament_row['Billing_Type'];
                    }
                    }else{
                        $Billing_Type ='';
                        $payment_type ='';
                    }

                if (strtolower($Statusi) == 'served') {
                    $st = '<span style="color:blue;text-align:center;">Done</span>';
                    if(($Billing_Type == 'Inpatient Cash' && $payment_type=='post') || $Billing_Type == 'Inpatient Credit'){
                       $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                    }else{
                       $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                    }
                    $commi = "<a class='no_color' href='previewpostoperativereport.php?Registration_ID=$Registration_ID&Payment_Item_Cache_List_ID=$Payment_Item_Cache_List_ID' title='Click to view comment added by Post Operative Notes' target='_blank'><img height='20' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";
                } else {
                    $st = '<span style="color:red;text-align:center;">Not done</span>';
                }

                if($Statusi == 'active'){
                    if($Transaction_Type=='Credit'){
                        $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                    }else{
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                    } 
                }else if($Statusi=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusi=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                    }else{
                        $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }
                //if (strtolower($patient['Status'] != 'served')) {
                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                $data .= '<td>' . $sentby . '</td>';
                $data .= '<td>' . $sentOn . '</td>';
                $data .= '<td>' . $servedby . '</td>';
                $data .= '<td>' . $servedOn . '</td>';
                $data .= '<td>' . $commi . '</td>';
                $data .= '<td>' . $pyd . '</td>';
                $data .= '</tr>';
                ;
                $sn++;
                //}
            }
            $data .= '</table>';
        }



        $qry = "SELECT tit.Product_Name,ilc.Status,ilc.Doctor_Comment,ilc.Patient_Payment_ID,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time,ilc.Transaction_Type AS DateTime,'doctor' as origin FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_items tit, tbl_employee em, tbl_consultation tc WHERE ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND tit.Item_ID=ilc.Item_ID AND  em.Employee_ID=ilc.Consultant_ID AND tc.consultation_ID=pc.consultation_id AND  ilc.Status != 'notsaved' AND  pc.consultation_id ='$consultation_ID' AND pc.Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Procedure' 
	
		";


        $rs = mysqli_query($conn,$qry)or die(mysqli_error($conn));
        $sn = 1;

        if (mysqli_num_rows($rs) > 0) {
            $data .= "
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
                <b>PROCEDURE</b>
            </div>";

            $data .= '<table width="100%" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td>Procedure Name</td>';
            $data .= (empty($display)) ? '<td><b>Status</b></td>' : '';
            $data .= '<td>Doctor Ordered</td>';
            $data .= '<td>Doctor Comment</td>';
            $data .= '<td>Performed By</td>';
            $data .= '<td>Proc Remarks</td>';
            $data .= '<td>Title</td>';
            $data .= '<td>Date</td>';
            $data .= '<td>Payment</td>';
            $data .= '</tr>';
            while ($row = mysqli_fetch_assoc($rs)) {
                $test_name = $row['Product_Name'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $remarks = $row['remarks'];
                $Emp_name = $row['Employee_Name'];
                $title = $row['Employee_Type'];
                $served_date = $row['DateTime'];
                $ServedDateTime = $row['ServedDateTime'];
                $ServedBy = $row['ServedBy'];
                $Statusii = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];

                if (strtolower($Statusii) == 'served') {
                    $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                }
//            else{
//                $Statusii = '<span style="color:red;text-align:center;">'.ucfirst($Statusii).'</span>';
//            }
                $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($paymet_check)>0){
                    while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                        $payment_type = $patient_check_payament_row['payment_type'];
                        $Billing_Type = $patient_check_payament_row['Billing_Type'];
                    }
                    }else{
                        $Billing_Type ='';
                        $payment_type ='';
                    }
                 //PAYMENT STATUS FOR PROCEDURE
                if($Statusii == 'active'){
                    // $pyd = '<span style="color:red;text-align:center;">Not Paid</span>';
                    if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                     } 
                }
                else if($Statusii=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusii=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }
                else if($Statusii=='served'){
                    // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                    if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                     }
                }

                $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $ServedBy . "'");
                $employee_served = mysqli_fetch_assoc($validator_Name)['Employee_Name'];

                $data .= '<tr style="text-align:left">';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= (empty($display)) ? '<td>' . $Statusii . '</td>' : '';
                $data .= '<td>' . $Emp_name . '</td>';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= '<td>' . $employee_served . '</td>';
                $data .= '<td>' . $remarks . '</td>';
                $data .= '<td>' . $title . '</td>';
                $data .= '<td>' . $served_date . '</td>';
                $data .= '<td>' .  $pyd . '</td>';

                $data .= '</tr>';
                ;
                $sn++;
            }

            $data .= '</table>';
        }


        //=== Nuclear medicine
        $nuclearmedicine = "SELECT ilc.Patient_Payment_ID,tit.Product_Name,ilc.Status,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin, Payment_Item_Cache_List_ID  FROM tbl_item_list_cache ilc, tbl_payment_cache pc,tbl_items tit, tbl_employee em, tbl_consultation tc WHERE  ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND tit.Item_ID=ilc.Item_ID AND em.Employee_ID=ilc.Consultant_ID	AND tc.consultation_ID=pc.consultation_id AND pc.Registration_ID='$Registration_ID'		AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND  ilc.Status != 'notsaved' AND  pc.consultation_id ='$consultation_ID' AND Billing_Type IN ('Inpatient Credit', 'Inpatient Cash') AND ilc.Check_In_Type='Nuclearmedicine'";

        

        $nuclearmedicinesql = mysqli_query($conn,$nuclearmedicine) or die(mysqli_error($conn));

        $sn = 1;

        if (mysqli_num_rows($nuclearmedicinesql) > 0) {
            $data .= "
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
                <b>Nuclear Medicine</b>
            </div>";

            $data .= '<table width="100%" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td>Scan/Procedure Name</td>';
            $data .= (empty($display)) ? '<td><b>Status</b></td>' : '';
            $data .= '<td>Doctor Ordered</td>';
            $data .= '<td>Doctor Comment</td>';
            $data .= '<td>Performed By</td>';
            $data .= '<td>Proc Remarks</td>';
            $data .= '<td>Title</td>';
            $data .= '<td>Result</td>';
            $data .= '<td>Date</td>';
            $data .= '<td style="text-align:left">Payment</td>';
            $data .= '</tr>';
            while ($row = mysqli_fetch_assoc($nuclearmedicinesql)) {
                $test_name = $row['Product_Name'];
                $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $remarks = $row['remarks'];
                $Emp_name = $row['Employee_Name'];
                $title = $row['Employee_Type'];
                $served_date = $row['DateTime'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $ServedBy = $row['ServedBy'];
                $Status = $row['Status'];

                if (strtolower($Status) == 'served') {
                    $Status = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Status = '<span style="color:red;text-align:center;">Not done</span>';
                }
               // if( $Status =='paid' || $Status =='pending'){
                    $select = mysqli_query($conn,"SELECT created_at, nmr.Payment_Item_Cache_List_ID from tbl_nuclear_medicine_report nmr, tbl_item_list_cache ilc where  nmr.Payment_Item_Cache_List_ID = ilc.Payment_Item_Cache_List_ID and  nmr.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    if($no > 0){
                        while ($dt = mysqli_fetch_array($select)) {
                            $Payment_Item_Cache_List = $dt['Payment_Item_Cache_List_ID'];
                            $Payment_Cache_ID =$dt['Payment_Cache_ID'];
                            $resultform = '<a href="print_nuclear_md_report.php?Payment_Item_Cache_List_ID='.$Payment_Item_Cache_List.'&Payment_Cache_ID='.$Payment_Cache_ID.'&Registration_ID='.$Registration_ID.' " target="blank" class="art-button-green" >RESULT</a>';
                        }
                    }else{
                        $resultform = " ".ucwords($Status)." and Not processed";
                       
                    }
                    if($Patient_Payment_ID!='' || $Patient_Payment_ID != NULL){
                    $paymet_check = mysqli_query($conn,"SELECT Pre_Paid, payment_type,Billing_Type, auth_code FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                        while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                            $payment_type = $patient_check_payament_row['payment_type'];
                            $Billing_Type = $patient_check_payament_row['Billing_Type'];
                        }
                    }else{
                        $Billing_Type ='';
                        $payment_type ='';
                    }
                    if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid </span>'; 
                     }
                // }else{
                //     $resultform = 'Not Done yet';
                   
                // }

                $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $ServedBy . "'");
                $employee_served = mysqli_fetch_assoc($validator_Name)['Employee_Name'];

                $data .= '<tr style="text-align:left">';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= (empty($display)) ? '<td>' . $Status . '</td>' : '';
                $data .= '<td>' . $Emp_name . '</td>';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= '<td>' . $employee_served . '</td>';
                $data .= '<td>' . $remarks . '</td>';
                $data .= '<td>' . $title . '</td>';
                $data .= '<td>' . $resultform . '</td>';
                
                $data .= '<td>' . $served_date . '</td>';
                $data .= '<td>' .  $pyd . '</td>';

                $data .= '</tr>';;
                $sn++;
            }

            $data .= '</table></div> ';
        }
            //=== END of Nuclear medicine
           // ====== Others =====
           $qry = "SELECT tit.Product_Name,ilc.Status,ilc.Doctor_Comment,ilc.Patient_Payment_ID,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time,ilc.Transaction_Type AS DateTime,'doctor' as origin FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_items tit, tbl_employee em, tbl_consultation tc WHERE ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND tit.Item_ID=ilc.Item_ID AND  em.Employee_ID=ilc.Consultant_ID AND tc.consultation_ID=pc.consultation_id AND  ilc.Status != 'notsaved' AND  pc.consultation_id ='$consultation_ID' AND pc.Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Others' 
	
           ";
   
   
           $rs = mysqli_query($conn,$qry)or die(mysqli_error($conn));
           $sn = 1;
   
           if (mysqli_num_rows($rs) > 0) {
               $data .= "
           <div width='100%' id='departments_comments'>
               <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
                   <b>OTHERS</b>
               </div>";
   
               $data .= '<table width="100%" style="margin-left:2px">';
               $data .= '<tr style="font-weight:bold;" id="thead">';
               $data .= '<td style="width:3%;">SN</td>';
               $data .= '<td>Service Name</td>';
               $data .= (empty($display)) ? '<td><b>Status</b></td>' : '';
               $data .= '<td>Doctor Ordered</td>';
               $data .= '<td>Doctor Comment</td>';
               $data .= '<td>Performed By</td>';
               $data .= '<td>Proc Remarks</td>';
               $data .= '<td>Title</td>';
               $data .= '<td>Date</td>';
               $data .= '<td>Payment</td>';
               $data .= '</tr>';
               while ($row = mysqli_fetch_assoc($rs)) {
                   $test_name = $row['Product_Name'];
                   $Doctor_Comment = $row['Doctor_Comment'];
                   $remarks = $row['remarks'];
                   $Emp_name = $row['Employee_Name'];
                   $title = $row['Employee_Type'];
                   $served_date = $row['DateTime'];
                   $ServedDateTime = $row['ServedDateTime'];
                   $ServedBy = $row['ServedBy'];
                   $Statusii = $row['Status'];
                   $Transaction_Type = $row['Transaction_Type'];
                   $Patient_Payment_ID = $row['Patient_Payment_ID'];
   
                   if (strtolower($Statusii) == 'served') {
                       $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                   } else {
                       $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                   }
                   $paymet_check = mysqli_query($conn,"SELECT payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                   if(mysqli_num_rows($paymet_check)>0){
                       while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                           $payment_type = $patient_check_payament_row['payment_type'];
                           $Billing_Type = $patient_check_payament_row['Billing_Type'];
                       }
                       }else{
                           $Billing_Type ='';
                           $payment_type ='';
                       }
                    //PAYMENT STATUS FOR Others
                   if($Statusii == 'active'){
                       // $pyd = '<span style="color:red;text-align:center;">Not Paid</span>';
                       if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                           $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                        }else{
                           $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                        } 
                   }
                   else if($Statusii=='approved'){
                       $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                   }else if($Statusii=='paid'){
                       if($Transaction_Type=="Cash"){
                           $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                        }else{
                            $pyd = '<span style="color:green;text-align:center;">billed</span>';
                       }
                    }else if($Statusii=='served'){
                       // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                       if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                           $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                        }else{
                           $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                        }
                    }
   
                   $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $ServedBy . "'");
                   $employee_served = mysqli_fetch_assoc($validator_Name)['Employee_Name'];
   
                   $data .= '<tr style="text-align:left">';
                   $data .= '<td>' . $sn . '</td>';
                   $data .= '<td>' . $test_name . '</td>';
                   $data .= (empty($display)) ? '<td>' . $Statusii . '</td>' : '';
                   $data .= '<td>' . $Emp_name . '</td>';
                   $data .= '<td>' . $Doctor_Comment . '</td>';
                   $data .= '<td>' . $employee_served . '</td>';
                   $data .= '<td>' . $remarks . '</td>';
                   $data .= '<td>' . $title . '</td>';
                   $data .= '<td>' . $served_date . '</td>';
                   $data .= '<td>' .  $pyd . '</td>';
   
                   $data .= '</tr>';
                   ;
                   $sn++;
               }
   
               $data .= '</table>';
           }
   
               // ====== End of Others =====
              
        $subqr = "SELECT ilc.Employee_Created,ilc.Status,sd.Sub_Department_Name, ilc.dose,ilc.dispensed_quantity,Product_Name,ilc.Patient_Payment_ID,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,ilc.Transaction_Type, Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i, tbl_employee em, tbl_sub_department sd, tbl_consultation tc WHERE  i.Item_ID = ilc.item_ID AND em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND tc.consultation_ID = pc.consultation_id AND ilc.Status != 'notsaved' AND  Check_In_Type = 'Pharmacy' AND pc.Registration_ID='$Registration_ID' AND i.Consultation_Type = 'Pharmacy' AND pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%'";


        $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));

        $sn = 1;

        if (mysqli_num_rows($result) > 0) {
            $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>PHARMACY</b>
            </div>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>ORDERED MEDICINE</b>
            </div>";
            $data .= '<table width="100%" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td style="text-align:left">Medication Name</td>';
            $data .= (empty($display)) ? '<td><b>Status****</b></td>' : '';
            $data .= '<td style="text-align:center">Qty Ordered</td>';
            $data .= '<td style="text-align:center">Qty Issued</td>';
            $data .= '<td style="text-align:center">Rem Qty</td>';
            $data .= '<td style="text-align:left">Dosage</td>';
            $data .= '<td style="text-align:left">Dispensor</td>';
            $data .= '<td style="text-align:left">Date Dispensed</td>';
            $data .= '<td style="text-align:left">Pharmacy</td>';
            $data .= '<td style="text-align:left">Ordered By</td>';
            $data .= '<td style="text-align:left">Date Ordered</td>';
            $data .= '<td style="text-align:left">Payment</td>';
            $data .= '</tr>';

            while ($row = mysqli_fetch_assoc($result)) {
                $qr = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $row['Dispensor'] . "'")or die(mysqli_error($conn));
                $Disponsor = mysqli_fetch_assoc($qr)['Employee_Name'];

                //get employee ordered
                $Employee_Created = $row['Employee_Created'];
                if ($Employee_Created != null) {
                    $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_Created'") or die(mysqli_error($conn));
                    $Employee_Created = mysqli_fetch_assoc($slct)['Employee_Name'];
                } else {
                    $Employee_Created = $row['Employee_Name'];
                }


                $Pharmacy = $row['Product_Name'];
                $orderedQty = $row['Quantity'];
                $disepnsedQty = $row['Edited_Quantity'];
                $Employee_Name = $row['Employee_Name'];
                $status = $row['Status'];
                $dose = $row['dose'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                $Status = $row['Status'];
                $Dispense_Date_Time = $row['Dispense_Date_Time'];
                $Sub_Department_Name = $row['Sub_Department_Name'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Transaction_Type = $row['Transaction_Type'];
                $dispensed_quantity = $row['dispensed_quantity'];

                $qty = 0;

                $paymet_check = mysqli_query($conn,"SELECT Pre_Paid, payment_type,Billing_Type, auth_code FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($paymet_check)>0){
                while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                    $payment_type = $patient_check_payament_row['payment_type'];
                    $Billing_Type = $patient_check_payament_row['Billing_Type'];
                }
                }else{
                    $Billing_Type ='';
                    $payment_type ='';
                }
                // PAYMENT STATUS FOR PHARMACY
                if($Status == 'active' ){
                    if($Transaction_Type=="Cash"){
                       $pyd = "<span style='color:red;text-align:center;'>Not Paid</span>";
                    }else{
                        $pyd = '<span style="color:red;text-align:center;">Not billed</span>';
                    }                  
                }else if($status=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($status=='paid'){
                    $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                }
                else if($status=='dispensed' || $status=='partial dispensed'){

                    if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid </span>'; 
                     }
                }else if($status=='removed'){
                    // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Not Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">Not billed</span>';
                     } 
                }
                
                if ($disepnsedQty > 0) {
                    $qty = $disepnsedQty;
                } else { 
                    $qty = $orderedQty;
                }
                $remain = $dose - $qty;

                if($remain > 0){
                    $remain = $remain;
                }else{
                    $remain = 0;
                }

                // if (strtolower($row['Status']) != 'dispensed') {
                //     $qty = '-';
                // }

                if (strtolower($Status) == 'dispensed') {
                    $Status = '<span style="color:blue;text-align:center;font-size: 14px;">Dispensed</span>';
                    $Sub_Department_Name2= $Sub_Department_Name;
                } else {
                    if (strtolower($Status) == 'removed') {
                        $Status = '<span style="color:red;text-align:center;">Removed</span>';
                    }else if (strtolower($Status) == 'partial dispensed') {
                        $Status = '<span style="color:green;text-align:center;">Partial Dispensed</span>';
                    $Sub_Department_Name2= $Sub_Department_Name;

                    } else if (strtolower($Status) == 'out of stock'){
                        $Status = '<span style="color:#000;text-align:center;">Out of Stock</span>';
                    }else {
                        $Status = '<span style="color:red;text-align:center;">Not given</span>';
                    }
                }

                $data .= '<tr>';
                $data .= '<td style="width:3%;">' . $sn . '</td>';
                $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
                $data .= (empty($display)) ? '<td>' . $Status . '</td>' : '';
                $data .= '<td style="text-align:center">' . $dose . '</td>';
                $data .= '<td style="text-align:center">' . $qty . '</td>';
                $data .= '<td style="text-align:center">' . $remain. '</td>';
                $data .= '<td style="text-align:left">' . $Doctor_Comment . '</td>';
                $data .= '<td style="text-align:left">' . $Disponsor . '</td>';
                $data .= '<td style="text-align:left">' . $Dispense_Date_Time . '</td>';
                $data .= '<td style="text-align:left">' . $Sub_Department_Name2 . '</td>';
                $data .= '<td style="text-align:left">' . ucwords(strtolower($Employee_Created)) . '</td>';
                $data .= '<td style="text-align:left">' . $Transaction_Date_And_Time . '</td>';
                $data .= '<td style="text-align:left">' . $pyd. '</td>';
                $data .= '</tr>';

                $sn++;
            }

            $data .= '</table></div>';
        }


        $subqr = "SELECT ilc.Employee_Created,ilc.Status,sd.Sub_Department_Name, ilc.dose,ilc.dispensed_quantity,Product_Name,ilc.Patient_Payment_ID,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,ilc.Transaction_Type, Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.Item_ID = ilc.item_ID
           	   
           JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
           JOIN tbl_sub_department sd ON sd.Sub_Department_ID=ilc.Sub_Department_ID
	   JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
	   WHERE   ilc.Status != 'notsaved' AND  Check_In_Type='Pharmacy' AND pc.Registration_ID='$Registration_ID' AND i.Consultation_Type = 'Others' AND pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%'";


        $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));

        $sn = 1;

        if (mysqli_num_rows($result) > 0) {
            $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>ORDERED CONSUMABLES</b>
            </div>";
            $data .= '<table width="100%" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td style="text-align:left">Consumable Name</td>';
            $data .= (empty($display)) ? '<td><b>Status****</b></td>' : '';
            $data .= '<td style="text-align:center">Qty Ordered</td>';
            $data .= '<td style="text-align:center">Qty Issued</td>';
            $data .= '<td style="text-align:center">Rem Qty</td>';
            $data .= '<td style="text-align:left">Dosage</td>';
            $data .= '<td style="text-align:left">Dispensor</td>';
            $data .= '<td style="text-align:left">Date Dispensed</td>';
            $data .= '<td style="text-align:left">Location</td>';
            $data .= '<td style="text-align:left">Ordered By</td>';
            $data .= '<td style="text-align:left">Date Ordered</td>';
            $data .= '<td style="text-align:left">Payment</td>';
            $data .= '</tr>';

            while ($row = mysqli_fetch_assoc($result)) {
                $qr = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $row['Dispensor'] . "'")or die(mysqli_error($conn));
                $Disponsor = mysqli_fetch_assoc($qr)['Employee_Name'];

                //get employee ordered
                $Employee_Created = $row['Employee_Created'];
                if ($Employee_Created != null) {
                    $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_Created'") or die(mysqli_error($conn));
                    $Employee_Created = mysqli_fetch_assoc($slct)['Employee_Name'];
                } else {
                    $Employee_Created = $row['Employee_Name'];
                }


                $Pharmacy = $row['Product_Name'];
                $orderedQty = $row['Quantity'];
                $disepnsedQty = $row['Edited_Quantity'];
                $Employee_Name = $row['Employee_Name'];
                $status = $row['Status'];
                $dose = $row['dose'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                $Status = $row['Status'];
                $Dispense_Date_Time = $row['Dispense_Date_Time'];
                $Sub_Department_Name = $row['Sub_Department_Name'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Transaction_Type = $row['Transaction_Type'];
                $dispensed_quantity = $row['dispensed_quantity'];

                $qty = 0;

                $paymet_check = mysqli_query($conn,"SELECT Pre_Paid, payment_type,Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($paymet_check)>0){
                while($patient_check_payament_row = mysqli_fetch_assoc($paymet_check)){
                    $payment_type = $patient_check_payament_row['payment_type'];
                    $Billing_Type = $patient_check_payament_row['Billing_Type'];
                }
                }else{
                    $Billing_Type ='';
                    $payment_type ='';
                }
                // PAYMENT STATUS FOR PHARMACY
                if($Status == 'active' ){
                    if($Transaction_Type=="Cash"){
                       $pyd = "<span style='color:red;text-align:center;'>Not Paid</span>";
                    }else{
                        $pyd = '<span style="color:red;text-align:center;">Not billed</span>';
                    }                  
                }else if($status=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($status=='paid'){
                    $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                }
                else if($status=='dispensed'){
                    // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                    if((strtolower($Billing_Type) == 'inpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'inpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid </span>'; 
                     }
                }else if($status=='removed'){
                    // $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>';
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Not Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">Not billed</span>';
                     } 
                }
                
                if ($disepnsedQty > 0) {
                    $qty = $disepnsedQty;
                } else { 
                    $qty = $orderedQty;
                }
                $remain = $dose - $qty;

                if($remain > 0){
                    $remain = $remain;
                }else{
                    $remain = 0;
                }

                // if (strtolower($row['Status']) != 'dispensed') {
                //     $qty = '-';
                // }

                if (strtolower($Status) == 'dispensed') {
                    $Status = '<span style="color:blue;text-align:center;font-size: 14px;">Dispensed</span>';
                    $Sub_Department_Name2= $Sub_Department_Name;
                } else {
                    if (strtolower($Status) == 'removed') {
                        $Status = '<span style="color:red;text-align:center;">Removed</span>';
                    }else if (strtolower($Status) == 'partial dispensed') {
                        $Status = '<span style="color:green;text-align:center;">Partial Dispensed</span>';
                    $Sub_Department_Name2= $Sub_Department_Name;

                    } else if (strtolower($Status) == 'out of stock'){
                        $Status = '<span style="color:#000;text-align:center;">Out of Stock</span>';
                    }else {
                        $Status = '<span style="color:red;text-align:center;">Not given</span>';
                    }
                }

                $data .= '<tr>';
                $data .= '<td style="width:3%;">' . $sn . '</td>';
                $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
                $data .= (empty($display)) ? '<td>' . $Status . '</td>' : '';
                $data .= '<td style="text-align:center">' . $dose . '</td>';
                $data .= '<td style="text-align:center">' . $qty . '</td>';
                $data .= '<td style="text-align:center">' . $remain. '</td>';
                $data .= '<td style="text-align:left">' . $Doctor_Comment . '</td>';
                $data .= '<td style="text-align:left">' . $Disponsor . '</td>';
                $data .= '<td style="text-align:left">' . $Dispense_Date_Time . '</td>';
                $data .= '<td style="text-align:left">' . $Sub_Department_Name2 . '</td>';
                $data .= '<td style="text-align:left">' . ucwords(strtolower($Employee_Created)) . '</td>';
                $data .= '<td style="text-align:left">' . $Transaction_Date_And_Time . '</td>';
                $data .= '<td style="text-align:left">' . $pyd. '</td>';
                $data .= '</tr>';

                $sn++;
            }

            $data .= '</table></div>';
        }
    }

    $data .="<table class='table'>
                    <tr style='background:#DEDEDE'>
                        <td><b>IPD DRUG SHEET</b></td>
                    </tr>
                </table>";
    $filter = " em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "' ORDER BY sg.Time_Given DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = "  em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "'  ORDER BY sg.Time_Given DESC";
}


$select_services = "SELECT sg.medication_time,sg.given_time, sg.route_type,sg.drip_rate, it.Product_Name,sg.Time_Given,sg.Amount_Given,sg.Nurse_Remarks,sg.Discontinue_Status,sg.Discontinue_Reason,em.Employee_Name 	FROM tbl_inpatient_medicines_given sg, 	tbl_items it,	tbl_employee em 	WHERE  	$filter ";



$selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
// if(mysqli_num_rows($select_services)>0){
$data .="<table width='100%' id='nurse_medicine'>";
$data .="<thead><tr>";
$data .="<th width='5%'> SN </th>";
$data .="<th> Medicine Name </th>";
$data .="<th> Dose </th>";
$data .="<th> Route </th>";
$data .="<th> Amount Given </th>";
$data .="<th width='11%'> saved time</th>";
$data .="<th width='11%'> Time Given</th>";
$data .="<th>Nurse/Significant Events and Interventions </th>";
$data .="<th width='5%'> Discontinued?</th>";
$data .="<th> Given by </th>";
$data .="</tr>";
$data .="</thead>";

$sn = 1;
if(mysqli_num_rows($selected_services)>0){
    while ($service = mysqli_fetch_assoc($selected_services)) {
        $Product_Name = $service['Product_Name'];
        $given_time = $service['given_time'];
        $route_type = $service['route_type'];
        $Time_Given = $service['Time_Given'];
        $medication_time = $service['medication_time'];
        
        $Amount_Given = $service['Amount_Given'];
        $Nurse_Remarks = $service['Nurse_Remarks'];
        $Discontinue_Status = $service['Discontinue_Status'];
        $Discontinue_Reason = $service['Discontinue_Reason'];
        $Employee_Name = $service['Employee_Name'];
        $data .= "<tr>";
        $data .= "<td id='thead'>" . $sn . "</td>";
        $data .= "<td>" . $Product_Name . "</td>";
        $data .= "<td>" . $given_time. "</td>";
        $data .= "<td>" . $route_type . "</td>";
        $data .= "<td>" . $Amount_Given . "</td>";
        $data .= "<td>" . $Time_Given . "</td>";
        $data .= "<td>" . $medication_time . "</td>";
        $data .= "<td>" . $Nurse_Remarks . "</td>";
        $data .= "<td>" . $Discontinue_Status . "</td>";
        $data .= "<td>" . $Employee_Name . "</td>";
        $data .= "</tr>";
        $sn++;
    }
}else{
    $data.="<tr><td colspan='10' style='color:red;'>No any Medication Chart Found</td></tr>";
}
$data .="</table>";
// }
$data .="<table class='table'>
        <tr style='background:#DEDEDE'>
            <td colspan='12'><b>FLUID BALANCE SHEET</b></td>
        </tr>
        ";
        $total_intake = 0;
        $total_output = 0;
        $selecy_data = mysqli_query($conn,"SELECT DATE(time_saved) as date_time FROM tbl_fluid_balance WHERE patient_id = '$Registration_ID' AND consultation_ID='$consultation_ID' GROUP BY DATE(time_saved) ORDER BY fluid_balance_id DESC") or die(mysqli_error($conn));
    if(mysqli_num_rows($selecy_data)>0){

        $data .="
        <tr>
            <th colspan='3'>PRESCRIPTION</th>
            <th colspan='5' >ORAL</th>
            <th colspan='4'>OTHER</th>
        </tr>
        <tr>
            <th colspan='3'></th>
            <th colspan='5'>INTAKE</th>
            <th colspan='4'>OUTPUT</th>
        </tr>
        <tr>
            <th rowspan='2'>time</th>
            <th colspan='2'>Intravenous</th>

            <th colspan='2'>Intravenous</th>
            <th colspan='2'>Oral</th>
            <th>Other</th>

            <th>Urine</th>
            <th>Gastr</th>
            <th>Faeces</th>
            <th>Other</th>
        </tr>
        <tr>
            <th>Fluid type</th>
            <th>ml</th>

            <th>Fluid type</th>
            <th>ml</th>
            <th>fluid type</th>
            <th>ml</th>
            <th>ml</th>

            <th>ml</th>
            <th>ml</th>
            <th>ml</th>
            <th>ml</th>
        </tr>
        ";
        while($date_row = mysqli_fetch_assoc($selecy_data)){
            $date = $date_row['date_time'];
            $data .= "<tr><th colspan='12'>Date :  $date</th></tr>";
         $select_fluid_sheet = mysqli_query($conn,"SELECT * FROM tbl_fluid_balance WHERE patient_id = '$Registration_ID' AND consultation_ID='$consultation_ID' AND DATE(time_saved) = '$date ' ") or die(mysqli_error($conn));
         if((mysqli_num_rows($select_fluid_sheet))>0){
             while($fluid_sheet_row = mysqli_fetch_assoc($select_fluid_sheet)){
             $time = $fluid_sheet_row['time'];
             $prescription_intervenous_fluid = $fluid_sheet_row['prescription_intervenous_fluid'];
             $prescription_intervenous_fluid_amount = $fluid_sheet_row['prescription_intervenous_fluid_amount'];
             $intake_intervenous_fluid = $fluid_sheet_row['intake_intervenous_fluid'];
             $intake_intervenous_fluid_amount = $fluid_sheet_row['intake_intervenous_fluid_amount'];
             $intake_oral_fluid = $fluid_sheet_row['intake_oral_fluid'];
             $intake_oral_fluid_amount = $fluid_sheet_row['intake_oral_fluid_amount'];
             $intake_other_amount = $fluid_sheet_row['intake_other_amount'];
             $urine_amount = $fluid_sheet_row['urine_amount'];
             $gastr_amount = $fluid_sheet_row['gastr_amount'];
             $faeces = $fluid_sheet_row['faeces'];
             $other = $fluid_sheet_row['other'];
             $time_saved = $fluid_sheet_row['time_saved'];

             $data .= "
             <tr>
                <td>$time</td>
                <td>$prescription_intervenous_fluid</td>
                <td>$prescription_intervenous_fluid_amount</td>
                <td>$intake_intervenous_fluid</td>
                <td>$intake_intervenous_fluid_amount</td>
                <td>$intake_oral_fluid</td>
                <td>$intake_oral_fluid_amount</td>
                <td>$intake_other_amount</td>
                <td>$urine_amount</td>
                <td>$gastr_amount</td>
                <td>$faeces</td>
                <td>$other</td>
            </tr>
             ";
             $total_intake = $total_intake + $intake_intervenous_fluid_amount + $intake_oral_fluid_amount + $intake_other_amount;
             $total_output = $total_output + $urine_amount + $gastr_amount + $faeces + $other;
             }
             $data .="
                <tr style='background-color:gray; text-align:left'>
                  <td colspan='3' ></td>
                  <th colspan='5' >Total Intake 24hr = $total_intake  ml</th>
                  <th colspan='4' >Total Output 24hr = $total_output  ml</th>  
                </tr>
             ";
         }
        }
    }else{
        $data.= "<tr><td colspan='12' style='color:red;'>No Fluid found</td></tr>";
    }

$data .="
</table>
";

    return $data;
}
?>
