<?php

function displayOutpatientInfos($hasOutpatientDetails, $hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display)
{
    global $conn;
    $data='';
    if ($hasOutpatientDetails) {
        $sn = 1;
        
       
        
        if (mysqli_num_rows($rsDoc) > 0) {
            $data .= '
                     <div width="100%" id="mainComplain">
                    <div style="padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  ">
                        <b>MAIN COMPLAINS</b>
                    </div>';

            $data .= '<table id="userCemmnts" border="0" width="100%" style="margin-left:2px">
                <tr>
                  <td>#</td><td><b>Doctor\'s Name</b></td><td><b>Notes</b></td><td><b>Course of injury</b></td><td><b>First Date Of Symptom</b></td><td><b>Date Consulted</b></td>
                  <td><b>Review of other system</b></td>
                  <td><b>Past Medical Record</b></td>
                </tr>';
             $doctors_ID=[];
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['employee_ID'];
                $maincomplain = $doctorsInfo['maincomplain'];
                $firstsymptom_date = $doctorsInfo['firstsymptom_date'];
                $cons_hist_Date = $doctorsInfo['cons_hist_Date'];
                $course_injury = $doctorsInfo['course_injury'];
                $past_medical_history = $doctorsInfo['past_medical_history'];
                $review_of_other_systems = $doctorsInfo['review_of_other_systems'];
                $family_social_history = $doctorsInfo['family_social_history'];
                $doctor_plan_suggestion = $doctorsInfo['doctor_plan_suggestion'];
                $Gynocological_history = $doctorsInfo['Gynocological_history'];
                array_push($doctors_ID, $doctorsID);
                $data .= '<tr>'
                    . '       <td rowspan="3">' . $sn++ . '</td>'
                    . '<td rowspan="3">' . $doctorsName . '</td>'
                    . '<td>' . $maincomplain . '</td>'
                    . '<td>' . $course_injury . '</td>'
                    . '<td>' . $firstsymptom_date . '</td>'
                    . '<td>' . $cons_hist_Date . '</td>'
                    . '<td>' . $review_of_other_systems . '</td>'
                    . '<td>' . $past_medical_history . '</td>'
                    . '</tr>';
                $data .= '<tr><th colspan="2">Family and Social History</th><th colspan="2">Gynocological History</th><th colspan="2">Doctor`s Plan / Suggestion</th></tr>';
                $data .= '<tr>'
                        . '<td colspan="2">' . $family_social_history . '</td>'
                        . '<td colspan="2">' . $Gynocological_history . '</td>'
                        . '<td colspan="2">' . $doctor_plan_suggestion . '</td>'
                        . '</tr>';
//                $data .= "<tr><td colspan='6'><b>Review of other system :</b> $review_of_other_systems</td></tr>";
//                $data .= "<tr><td colspan='6'><b>Past Medical Record :</b> $past_medical_history</td></tr>";
            }
            $data .= "</table>";
            
             foreach($doctors_ID as $doctorsID) {
               
            $data .= "</table><table class='table table-condensed'>";
                    
            //select detail from patient file
             //select previous main complain
                                $sql_select_main_complain_detail_result=mysqli_query($conn,"SELECT main_complain,duration FROM tbl_main_complain WHERE consultation_ID='$consultation_ID' AND consultant_id='$doctorsID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_main_complain_detail_result)>0){
                                   $data .=  "<tr style='background:#DEDEDE'><td style='width:50%'><b>Complain</b></td><td><b>Duration</b></td></tr>";
                                    while($previous_mai_complain_rows=mysqli_fetch_assoc($sql_select_main_complain_detail_result)){
                                        $main_complain=$previous_mai_complain_rows['main_complain'];
                                        $duration=$previous_mai_complain_rows['duration'];
                                        $data .="<tr><td>$main_complain</td><td>$duration</td></tr>";
                                    }
                                }
           
             $sql_select_previous_hpi_history_result=mysqli_query($conn,"SELECT complain,duration,onset,periodicity,aggrevating_factor,relieving_factor,associated_with FROM tbl_history_of_present_illiness WHERE consultation_ID='$consultation_ID' AND consultant_id='$doctorsID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_previous_hpi_history_result)>0){
                                     $data .="</table><table class='table table-condensed'><caption><b>HISTORY OF  PRESENT ILLNESS<b></caption>";
            $data .="<tr style='background:#DEDEDE'>
                                <td><b>
                                    Complain</b>
                                </td>
                                <td><b>
                                    Duration
                                </td>
                                <td><b>
                                    Onset</b>
                                </td>
                                <td><b>
                                    Periodicity</b>
                                </td>
                                <td><b>
                                    Aggravating Factor</b>
                                </td>
                                <td><b>
                                    Relieving Factor</b>
                                </td>
                                <td><b>Associated with</b></td>
                            </tr>";
                                    while($hpi_rows=mysqli_fetch_assoc($sql_select_previous_hpi_history_result)){
                                        $complain=$hpi_rows['complain'];
                                        $duration=$hpi_rows['duration'];
                                        $onset=$hpi_rows['onset'];
                                        $periodicity=$hpi_rows['periodicity'];
                                        $aggrevating_factor=$hpi_rows['aggrevating_factor'];
                                        $relieving_factor=$hpi_rows['relieving_factor'];
                                        $associated_with=$hpi_rows['associated_with'];
                                       
                                         $data .="<tr>
                                            <td>
                                                $complain
                                            </td>
                                            <td>
                                                $duration
                                            </td>
                                            <td>
                                                 $onset
                                            </td>
                                            <td>
                                               $periodicity
                                            </td>
                                            <td>
                                                $aggrevating_factor
                                            </td>
                                            <td>
                                                $relieving_factor
                                            </td>
                                            <td>$associated_with</td>
                                        </tr> ";  
                                      
                                    }
             }}
            $data .="</table></div>";
        }
       
        $rsDoc = mysqli_query($conn,"SELECT Employee_Name,cons_hist_Date,ch.employee_ID,ch.remarks FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='" . $consultation_ID . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
        $sn = 1;
        if (mysqli_num_rows($rsDoc)) {
            $data .= "
            <div width='100%' id='patient_status_remarks'>
                <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
            <b>PATIENT STATUS & REMARKS</b>
            </div>
       ";
            $data .= " <table id='userCemmnts' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td>#</td><td><b>Doctor's Name</b></td><td><b>Patient Status</b></td><td><b>Remarks</b></td><td><b>Date Consulted</b></td>
            </tr>";
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['employee_ID'];
                $remarks = $doctorsInfo['remarks'];
                $cons_hist_Date = $doctorsInfo['cons_hist_Date'];

                $data .= '<tr>'
                    . '<td>' . $sn++ . '</td>'
                    . '<td>' . $doctorsName . '</td>'
                    . '<td>Outpatient</td>'
                    . '<td>' . $remarks . '</td>'
                    . '<td>' . $cons_hist_Date . '</td>'
                    . '</tr>';
            }
            $data .= "</table></div>";
        }
        


        $rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.history_present_illness,ch.review_of_other_systems,ch.general_observation,ch.systemic_observation, ch.local_examination, ch.provisional_diagnosis,ch.diferential_diagnosis,ch.employee_ID,ch.history_present_illness,ch.review_of_other_systems,ch.general_observation,ch.systemic_observation,ch.provisional_diagnosis,ch.diferential_diagnosis,ch.cons_hist_Date FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='" . $consultation_ID . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
        $sn = 1;

        if (mysqli_num_rows($rsDoc) > 0) {
            $data .= "
        
        <div width='100%' id='departments_comments'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
            <b>REVIEWS</b>
        </div>
       ";
            $data .= " <table id='userCemmnts' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td>#</td><td><b>Doctor's Name</b></td><td><b>History Of Illness</b></td><td>Others Reviews</td><td><b>General Observation</b></td><td><b>Systematic Observation</b></td><td><b>Local Examination</b></td><td><b>Date Consulted</b></td>
            </tr>";
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['employee_ID'];
                $history_present_illness = $doctorsInfo['history_present_illness'];
                $review_of_other_systems = $doctorsInfo['review_of_other_systems'];
                $general_observation = $doctorsInfo['general_observation'];
                $systemic_observation = $doctorsInfo['systemic_observation'];
                $local_examination = $doctorsInfo['local_examination'];
                $provisional_diagnosis = $doctorsInfo['provisional_diagnosis'];
                $diferential_diagnosis = $doctorsInfo['diferential_diagnosis'];
                $cons_hist_Date = $doctorsInfo['cons_hist_Date'];

                $data .= '<tr>'
                    . '       <td>' . $sn++ . '</td>'
                    . '<td>' . $doctorsName . '</td>'
                    . '<td>' . $history_present_illness . '</td>'
                    . '<td>' . $review_of_other_systems . '</td>'
                    . '<td>' . $general_observation . '</td>'
                    . '<td>' . $systemic_observation . '</td>'
                    . '<td>' . $local_examination . '</td>'
                    . '<td>' . $cons_hist_Date . '</td>'
                    . '</tr>';
            }

            $data .= "</table></div>";
        }
       

        $results = mysqli_query($conn,"SELECT DISTINCT(dc.employee_ID),e.Employee_Name,e.Employee_Type FROM tbl_disease_consultation dc, tbl_employee e WHERE e.Employee_ID = dc.Employee_ID AND dc.consultation_ID='$consultation_ID' GROUP BY e.Employee_Name") or die(mysqli_error($conn));
        $sn = 1;
        if (mysqli_num_rows($results) > 0) {
            $data .= "
    
    <div width='100%' id='diagonsis'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>DIAGNOSIS</b>
        </div>";
            while ($row = mysqli_fetch_array($results)) {

                //selecting diagnosois
                $doctorsName = $row['Employee_Name'];
                $employee_Type = ucfirst(strtolower($row['Employee_Type']));

                $data .= '<br/><table id="diagonsis" border="1" width="100%" style="margin-left:2px">
            <tr><td colspan="9"><b>' . $sn++ . '.</b>&nbsp;&nbsp;&nbsp;<b>' . $employee_Type . '.&nbsp;&nbsp;&nbsp; ' . $doctorsName . '</b></td></tr>
            </table>    
            ';
                $diagnosis_qr = "SELECT diagnosis_type,disease_name,Disease_Consultation_Date_And_Time,disease_code FROM tbl_disease_consultation dc,tbl_disease d
		    WHERE dc.consultation_ID =$consultation_ID AND dc.employee_ID='" . $row['employee_ID'] . "' AND 
		    dc.disease_ID = d.disease_ID";
                $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
                $provisional_diagnosis = '';
                $diferential_diagnosis = '';
                $diagnosis = '';



        $round_ID_specific = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admission_ID FROM tbl_check_in_details WHERE consultation_ID= '$consultation_ID' AND Admit_Status = 'admitted'"))['Admission_ID'];

        if($round_ID_specific < 1){
            $In_Payment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE consultation_id = '$consultation_ID' AND Billing_Type LIKE '%Inpatient%'AND Round_ID IS NULL LIMIT 1"))['Payment_Cache_ID'];
            if($In_Payment_Cache_ID > 0){
                $change_patient_status = mysqli_query($conn, "UPDATE tbl_payment_cache SET Billing_Type = 'Outpatient Credit' WHERE Payment_Cache_ID = '$In_Payment_Cache_ID' AND Billing_Type='Inpatient Credit'");
                $change_patient_status = mysqli_query($conn, "UPDATE tbl_payment_cache SET Billing_Type = 'Outpatient Cash' WHERE Payment_Cache_ID = '$In_Payment_Cache_ID' AND Billing_Type='Inpatient Cash'");
            }
        }


                if (@mysqli_num_rows($result) > 0) {
                    $data .= ' <table id="diagonsis" border="1" width="100%" style="margin-left:2px">
                     <tr><td>#</td><td><b>Provisional Diagnosis</b></td><td><b>Differential Diagnosis</b></td><td><b>Final Diagnosis</b></td><td style="text-align:center"><b>Disease Code</b></td><td><b>Date Consulted</b></td></tr>';

                    $temp = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                            $provisional_diagnosis = ' ' . $row['disease_name'];
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
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
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
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
                            $dignosis_Date = $row['Disease_Consultation_Date_And_Time'];
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
        
        $rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.Comment_For_Laboratory,ch.Comment_For_Radiology,ch.Comments_For_Procedure,ch.investigation_comments,ch.Comments_For_Surgery,ch.employee_ID,ch.Comment_For_Laboratory,ch.Comment_For_Radiology,ch.Comments_For_Procedure,ch.investigation_comments,ch.Comments_For_Surgery FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID  WHERE ch.consultation_ID='" . $consultation_ID . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
        $sn = 1;
        if (mysqli_num_rows($rsDoc) > 0) {
            $data .= "
                    <div width='100%' id='departments_comments'>
                        <div style='padding:5px; width:99.3%;font-size:larger;background:#ccc;text-align:left'>
                            <b>DEPARTMENTAL DOCTOR'S COMMENTS</b>
                        </div>";
            $data .= "<table id='userCemmnts' border='1' width='100%' style='margin-left:2px'>
                <tr>
                    <td>#</td><td><b>Doctor's Name</b></td><td><b>Comments For Laboratory</b></td><td><b>Comments For Radiology</b></td><td><b>Comments For Procedure</b></td><td><b>Comments For Surgery</b></td><td><b>Doctor's Investigation Comments</b></td><td><b>Date Consulted</b></td>
                </tr>";
            while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
                $doctorsName = $doctorsInfo['Employee_Name'];
                $doctorsID = $doctorsInfo['employee_ID'];
                $Comment_For_Laboratory = $doctorsInfo['Comment_For_Laboratory'];
                $Comment_For_Radiology = $doctorsInfo['Comment_For_Radiology'];
                $Comments_For_Procedure = $doctorsInfo['Comments_For_Procedure'];
                $investigation_comments = $doctorsInfo['investigation_comments'];
                $Comments_For_Surgery = $doctorsInfo['Comments_For_Surgery'];

                $data .= '<tr>'
                    . '       <td>' . $sn++ . '</td>'
                    . '<td>' . $doctorsName . '</td>'
                    . '<td>' . $Comment_For_Laboratory . '</td>'
                    . '<td>' . $Comment_For_Radiology . '</td>'
                    . '<td>' . $Comments_For_Procedure . '</td>'
                    . '<td>' . $Comments_For_Surgery . '</td>'
                    . '<td>' . $investigation_comments . '</td>'
                    . '<td>' . $cons_hist_Date . '</td>'
                    . '</tr>';
            }
            $data .= " </table></div>";
        }

          $qrLab = "SELECT Payment_Item_Cache_List_ID,ilc.Transaction_Type, ilc.Status, i.Item_ID,Product_Name,ilc.remarks,Doctor_Comment,Transaction_Date_And_Time, Patient_Payment_ID,Billing_Type,test_result_ID FROM tbl_item_list_cache ilc, tbl_test_results  trs,tbl_payment_cache,tbl_items i,tbl_consultation tc	  WHERE tc.consultation_ID=tbl_payment_cache.consultation_id AND i.Item_ID=ilc.Item_ID AND tbl_payment_cache.Payment_Cache_ID=ilc.Payment_Cache_ID AND Billing_Type IN ('Outpatient Cash', 'Outpatient Credit') AND Payment_Item_Cache_List_ID=payment_item_ID AND tc.Registration_ID='" . $Registration_ID . "' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND ilc.Check_In_Type='Laboratory' ";

        $qrLabWithoutResult = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time, Patient_Payment_ID,Billing_Type FROM tbl_item_list_cache,tbl_payment_cache,tbl_items ,tbl_employee e ,tbl_consultation tc       
                    WHERE tc.consultation_ID=tbl_payment_cache.consultation_id AND e.Employee_ID=tbl_item_list_cache.Consultant_ID AND tbl_items.Item_ID=tbl_item_list_cache.Item_ID AND tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID AND tbl_item_list_cache.Status != 'notsaved' AND tc.Registration_ID='" . $Registration_ID . "' AND tbl_item_list_cache.Status !='notsaved'  AND Billing_Type LIKE '%Outpatient%' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND tbl_item_list_cache.Check_In_Type='Laboratory'";




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


            $data1 = '';
            $number=1;
            while ($row = mysqli_fetch_array($result)) {
                $tempIlc[] = $row['Payment_Item_Cache_List_ID'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii = $row['Status'];
                $Statusii1 = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
         
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
          //PAYMENT STATUS FOR Nuclear medicine
            if($Statusii == 'active'){
                if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                    $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                }else{
                    $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                } 
            }else if($Statusii=='approved'){
                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
            }else if($Statusii=='paid'){
                if($Transaction_Type=="Cash"){
                    $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                }else{
                    $pyd = '<span style="color:green;text-align:center;">billed</span>';
                }
            }else if($Statusii=='served'){
                if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else{
                    $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                }
            }else if(strtolower($Statusii)=='sample collected'){
                if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else{
                    $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                }
            }


                if (strtolower($Statusii) == 'served') {
                    $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                }
                $st = '';
                $ppil = $row['Payment_Item_Cache_List_ID'];
                $item_ID = $row['Item_ID'];

                $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));


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
                        $resultLab = "No result 1";
                    } else {
                        $resultLab = '<span style="text-align:center;color: red;">Provisional</span>';
                    }


                    if ($resultLab != "No result") {


                        $getParameters = "SELECT tpr.TimeSubmitted as testResult FROM tbl_tests_parameters_results tpr , tbl_test_results tr , tbl_item_list_cache , tbl_specimen_results tsr , tbl_employee te , tbl_laboratory_specimen tls "   . "         WHERE tls.Specimen_ID=tsr.Specimen_ID AND te.Employee_ID=tsr.specimen_results_Employee_ID AND tsr.payment_item_ID=Payment_Item_Cache_List_ID AND Payment_Item_Cache_List_ID=tr.payment_item_ID AND test_result_ID=ref_test_result_ID AND tr.payment_item_ID='" . $row['Payment_Item_Cache_List_ID'] . "'";
                        $number++;
                        $myparameters1 = mysqli_query($conn,$getParameters);
                        $totalParm = mysqli_num_rows($myparameters1);
                        $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $positive = mysqli_num_rows($postvQry);
                        $get_submitor_Name3 = mysqli_fetch_assoc($postvQry);


                        $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $negative = mysqli_num_rows($negveQry);

                        $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $abnormal = mysqli_num_rows($abnormalQry);


                        $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE  ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $normal = mysqli_num_rows($normalQry);
                        //$result= mysqli_fetch_array($normalQry);


                        

                        $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $high = mysqli_num_rows($highQry);

                        $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                        $low = mysqli_num_rows($lowQry);

                        $resultQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE   ref_test_result_ID='" . $row['test_result_ID'] . "'") or die(mysqli_error($conn));
                    
                        while($resultNomal=mysqli_fetch_assoc($resultQry)){
                            $resultLab=$resultNomal['result'];
                        }
                        
                        $no_results = mysqli_num_rows($lowQry);
                        
                        if ($totalParm > 0) {

                            // $resultLab = $resultNomal['result'];
                            // if ($positive == $totalParm) {
                            //     $resultLab = "positive";
                            // } elseif ($negative == $totalParm) {

                            //     $resultLab = "Negative";
                            // } elseif ($normal == $totalParm) {

                            //     $resultLab = "Normal";
                            // } elseif ($abnormal == $totalParm) {
                            //     $resultLab = "Abnormal";
                            // } elseif ($normal == $totalParm) {
                            //     $resultLab = $resultNomal['result'];
                            // } elseif ($high == $totalParm) {
                            //     $resultLab = "High";
                            // } elseif ($low == $totalParm) {
                            //     $resultLab = "Low";
                            // } else {
                            //     $resultLab = "No Result 2";
                            //     $get_submitor_Name['Employee_Name'] = '';
                            //     $get_Validator_Name['Employee_Name'] = '';
                            // }
                        }
                       
                    }
                } else {


                    $resultLab = 'No result ';

                    $get_submitor_Name['Employee_Name'] = '';
                    $get_Validator_Name['Employee_Name'] = '';
                }

                $image = '';
                $remarks = '';
                //if($resultLab != "No result"){   
                $query = mysqli_query($conn,"select Attachment_Url,Description from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");

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
            <tr><td colspan="9"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>
            <tr>
               <td><b>Doctor\'s Ordered</b></td><td><b>Doctor\'s Notes</b></td><td><b>Lab Remarks</b></td>' . $srDisp . '<td><b>Attachment</b></td>' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '<td><b>Performer</b></td><td><b>Validator</b></td><td><b>Date</b></td><td>Payment Status</td>';

            $Biopsy_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Biopsy_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$ppil'"))['Biopsy_ID'];
                if($Biopsy_ID > 0){
                    $data .="<th>Biopsy Report</th>";
                }

            echo '</tr>';
                
                $hide_btn="";
                $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$ppil' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)<=0){
                    $hide_btn="class='hide'";
                }else{
                    $hide_btn="class='art-button-green'";
                }
                $Product_Name=$row['Product_Name'];
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
                if($Biopsy_ID > 0){
                    $data .="<td><a href='biopsy_result_report.php?Biopsy_ID=".$Biopsy_ID."&Registration_ID=".$Registration_ID."&ppil=".$ppil."' target='_blank'><center><img src='patient_attachments/report.png' width='50px' height='50px'></center></a></td>";
                }
                $data .= "<td>$pyd</td></tr></table>";
                
                
                

                // $myparameters=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='".$row['test_result_ID']."'")or die(mysqli_error($conn));
                if (empty($resultLab)) {
                    $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $item_ID . "' AND tr.payment_item_ID='" . $ppil . "'  AND Registration_ID='" . $Registration_ID . "' AND Validated = 'Yes' AND Parameter_Name !='default_parameter'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";

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
                <td width='' style='text-align:left'><b>Previous results</b></td>
               </tr>";

                        //  }

                        while ($row2 = mysqli_fetch_assoc($results)) {
                            $testID = $row2['test_result_ID'];
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


                            //Get previous test results
                            $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$Registration_ID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $testID . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                            $Queryhistory = mysqli_query($conn,$historyQ);
                            $myrows = mysqli_num_rows($Queryhistory);
                            if ($myrows > 0) {
                                

                                //$data .= $historyQ;
                                while($detaied=mysqli_fetch_array($Queryhistory)){
                                    $patientID=$detaied['Registration_ID'];
                                    $id=$detaied['Item_ID'];
                                    $data .= '<td>
                            <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                                                $myrows . ' Previous result(s)'
                                                . '</p>
                        
                            </td>';
                                }
                            } else {

                                //$data .= $historyQ;
                                $data .= '<td>No previous results</td>';
                            }
                            $data .= "</tr>";
                        }

                        $data .= "</table><br/>";
                    }
                }

                $resultLab = '';
            }
        }
        //without result
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
                    $sttsss = '';
                    $ppil = $row['Payment_Item_Cache_List_ID'];
                    $item_ID = $row['Item_ID'];

                    $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID=''") or die(mysqli_error($conn));
                    $rowSt = mysqli_fetch_assoc($RS);

                    $doctorsName = $row['Employee_Name'];
                    $Submitted = $rowSt['Submitted'];
                    $Validated = $rowSt['Validated'];
                    $remarks = $rowSt['remarks'];
                    $validator = $rowSt['ValidatedBy'];
                    $SavedBy = $rowSt['SavedBy'];
                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
         $Statusii = $row['Status'];
         $Transaction_Type = $row['Transaction_Type'];
        
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
          //PAYMENT STATUS FOR Nuclear medicine
         if($Statusii == 'active'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
              }else{
                 $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
              } 
         }else if($Statusii=='approved'){
             $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
         }else if($Statusii=='paid'){
             if($Transaction_Type=="Cash"){
                 $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
              }else{
                  $pyd = '<span style="color:green;text-align:center;">billed</span>';
             }
         }else if($Statusii=='served'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
              }else{
                 $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
              }
         }else if(strtolower($Statusii)=='sample collected'){
            if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
             }else{
                $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
             }
        }
        if (strtolower($Statusii) == 'served') {
            $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
        } else {
            $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
        }
                    $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $validator . "'");
                    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);

                    $submitor_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $SavedBy . "'");
                    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);

                    if ($Validated == 'Yes') {
                        $sttsss = '<span style="color:blue;text-align:center;">Done</span>';
                    } else {
                        $sttsss = '<span style="text-align:center;color: red;">No Results</span>';
                    }

                    //retrieve attachment info
                    $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
                    $attach = mysqli_fetch_assoc($query);
                    $image = 'No';
                    if ($attach['Attachment_Url'] != '') {
                        $image = "<b>Yes</b>";
                    }
                    $data .= '<br/> <table id="labresult" border="1" width="100%" style="margin-left:2px">
            <tr><td colspan="9"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>
            <tr>
               <td><b>Doctor\'s Ordered</b></td><td><b>Doctor\'s Notes</b></td><td><b>Lab Remarks</b></td><td><b>Attachment</b></td>' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '<td><b>Performer</b></td><td><b>Validator</b></td><td><b>Date</b></td><td>Payment Status</td>
            </tr>';
                    $data .= "<tr>";
                    $data .= "<td>" . $doctorsName . "</td>";
                    $data .= "<td>" . $row['Doctor_Comment'] . "</td>";
                    $data .= "<td>" . $remarks . "</td>";
                    $data .= "<td style='text-align:center'>" . $image . "</td>";
                    $data .= (empty($display)) ? '<td>' . $sttsss . '</td>' : '';
                    $data .= "<td>" . $get_submitor_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $get_Validator_Name['Employee_Name'] . "</td>";
                    $data .= "<td>" . $row['Transaction_Date_And_Time'] . "</td>";
                    $data .= "<td>$pyd</td></tr></table>";

                    // $myparameters=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='".$row['test_result_ID']."'")or die(mysqli_error($conn));

                    $selectQuery = "SELECT * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
      ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $item_ID . "' AND tr.payment_item_ID='" . $ppil . "'  AND Registration_ID='" . $Registration_ID . "'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";

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
                        $Patient_Payment_ID = $row2['Patient_Payment_ID'];
                        $Statusii = $row2['Status'];
                        $Transaction_Type = $row2['Transaction_Type'];
                        
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
                         //PAYMENT STATUS FOR Nuclear medicine
                        if($Statusii == 'active'){
                            if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                                $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                             }else{
                                $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                             } 
                        }else if($Statusii=='approved'){
                            $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                        }else if($Statusii=='paid'){
                            if($Transaction_Type=="Cash"){
                                $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                             }else{
                                 $pyd = '<span style="color:green;text-align:center;">billed</span>';
                            }
                        }else if($Statusii=='served'){
                            if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                                $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                             }else{
                                $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                             }
                        }

                        if (strtolower($Statusii) == 'served') {
                            $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                        } else {
                            $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                        }
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
                            $data .= '<td>&#x2714;</td>'; $get_specimen_status =$row['status'];
                            $Transaction_Type=$row['Transaction_Type'];
            
                            
                           
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
                            //$data .= $historyQ;
                            while($detaied=mysqli_fetch_array($Queryhistory)){
                                $patientID=$detaied['Registration_ID'];
                                $id=$detaied['Item_ID'];
                                $data .= '<td>
                <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                                $myrows . ' Previous result(s)'
                                . '</p>
               
                </td>';
                            }
                            
                        } else {

                            //$data .= $historyQ;
                            $data .= '<td>No previous results</td>';
                        }
                        $data .= "</tr>";
                    }

                    $data .= "</table><br/>";
                }
            }
            $data .= "</div>";
        }//endof withought result 

        $tbrad = " <table id='radiology' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td style='width:3%;'><b>SN</b></td>	
                <td><b>Test Name</b></b></td>
               " . ((empty($display)) ? '<td><b>Status</b></td>' : '') . "
                <td><b>Doctor Comment</b></td>	
                <td><b>Radiology Remarks</b></td>	
                <td><b>Radiologist</b></td>	
                <td><b>Sonographer</b></td>	
                <td><b>Sent Date</b></td>
                <td><b>Served Date</b></td>
                <td><b>Attachments</b></td>
                <td><b>Images</b></td>
                <td><b>Report</b></td>
            </tr>";
        
        $qr = "SELECT Patient_Payment_ID,Billing_Type,Check_In_ID, rpt.Status,pc.Registration_ID,i.Product_Name,rpt.Remarks,  rpt.Date_Time,Radiologist_ID,Sonographer_ID,Patient_Payment_Item_List_ID,ilc.Payment_Item_Cache_List_ID,i.Item_ID,ilc.Transaction_Date_And_Time FROM
			tbl_radiology_patient_tests rpt , tbl_items i, tbl_item_list_cache ilc , tbl_payment_cache pc 
			WHERE ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND rpt.Item_ID = i.Item_ID AND rpt.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND   Billing_Type LIKE '%Outpatient%' AND ilc.Check_In_Type='Radiology'";
        $qrnotdone = "SELECT ilc.Status,ilc.Transaction_Date_And_Time,ilc.Doctor_Comment,pc.Registration_ID,i.Product_Name
                          ,Payment_Item_Cache_List_ID,i.Item_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc , tbl_items i  
			WHERE ilc.Item_ID = i.Item_ID AND ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND ilc.Status != 'notsaved' AND pc.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND ilc.Check_In_Type='Radiology' AND
                        Billing_Type LIKE '%Outpatient%'  
			
			";

        $select_patients_qry = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        $select_patients_notdone_qry = mysqli_query($conn,$qrnotdone) or die(mysqli_error($conn));

        $sn = 1;
        $tempIlc = array();
        if (mysqli_num_rows($select_patients_qry) > 0) {
            $data .= "
    <div width='100%' id='radiology'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>RADIOLOGY TESTS</b>
        </div>
       ";
            $data .= $tbrad;
            while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
                $Patient_Payment_ID =$patient['Patient_Payment_ID'];
                $Billing_Type =$patient['Billing_Type'];
                $Check_In_ID =$patient['Check_In_ID'];
                
                $status = $patient['Status'];
                $patient_numeber = $patient['Registration_ID'];
                $test_name = $patient['Product_Name'];
                $remarks = '' . $patient['Remarks'] . '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }
                $Registration_ID = $patient['Registration_ID'];
                $sent_date = $patient['Transaction_Date_And_Time'];
                $served_date = $patient['Date_Time'];
                $Radiologist = $patient['Radiologist_ID'];
                $Sonographer = $patient['Sonographer_ID'];
                $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                $tempIlc[] = $patient['Payment_Item_Cache_List_ID'];
                $Item_ID = $patient['Item_ID'];

                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii = $patient['Status'];
                $Transaction_Type = $row['Transaction_Type'];
                
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
          //PAYMENT STATUS FOR Nuclear medicine
         if($Statusii == 'active'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
              }else{
                 $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
              } 
         }else if($Statusii=='approved'){
             $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
         }else if($Statusii=='paid'){
             if($Transaction_Type=="Cash"){
                 $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
              }else{
                  $pyd = '<span style="color:green;text-align:center;">billed</span>';
             }
         }else if($Statusii=='served'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
              }else{
                 $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
              }
         }
                $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

                $ppID = mysqli_fetch_assoc($rs);
                $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
                if (mysqli_num_rows($rs) == 0) {
                    $Patient_Payment_ID = 0;
                }

                if (strtolower($Statusii) == 'served') {
                    $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                }

                $listtype = '';
                $PatientType = '';
                $Doctor = '';
                $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . "&RI=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=" . $listtype;

                /* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
                $photo = "SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID' AND consultation_ID='$consultation_ID'";
                $result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
                $imaging='';
                if (mysqli_num_rows($result) > 0) {
                    $list = 0;
                    
                    while ($row = mysqli_fetch_array($result)) {
                        $list++;
                        // extract($row);
                        $Radiology_Image = $row['Radiology_Image'];
                        if (preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $Radiology_Image)) {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank'><img height='20' alt=''  src='RadiologyImage/" . $Radiology_Image . "'  alt=''/></a>";
                        } else {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank' ><img height='20' alt=''  src='patient_attachments/attachement.png'  alt=''/></a>";
                        }
                    }
                } else {
                    $imaging .= "<b style='text-align: center;color:red'></b>";
                }

                $comm = "<a class='no_color' href='RadiologyTests_Print.php?RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $patient['Payment_Item_Cache_List_ID'] . "' title='Click to view comment added by radiologist' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";

//                              $imaging='<button style="width:74%;" class="art-button-green" onclick="radiologyviewimage(\''.$href.'\',\''.$test_name.'\')">Imaging</button>';
//				$commentsDescription='<button style="width:74%;" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">Report</button>';
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

                $style = 'style="text-decoration:none;"';
                $href="II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&PPI=".$Patient_Payment_ID."&RI=".$Registration_ID;

                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= (empty($display)) ? '<td>' . $Statusii . '</td>' : '';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= '<td>' . $remarks . '</td>';
                $data .= '<td>' . $Radiologist_Name . '</td>';
                $data .= '<td>' . $Sonographer_Name . '</td>';
                $data .= '<td>' . $sent_date . '</td>';
                $data .= '<td>' . $served_date . '</td>';
                $data .= '<td>' . $view_results . '</td>';
                $data .= '<td><a href="#" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">View Image</a></td>';
                $data .= '<td>' . $comm . '</td><td>'.$pyd.'</td>';
                $data .= '</tr>';;
                $sn++;
            }
            $data .= "</table>";
        }
        if (mysqli_num_rows($select_patients_notdone_qry) > 0) {
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
                    $Registration_ID = $patient['Registration_ID'];
                    $sent_date = $patient['Transaction_Date_And_Time'];
                    $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                    $Item_ID = $patient['Item_ID'];
                    $comm = '';
                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                    $Statusii = $row['Status'];
                    $Transaction_Type = $row['Transaction_Type'];
                  
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
                     //PAYMENT STATUS FOR Nuclear medicine
                    if($Statusii == 'active'){
                        if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                            $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                         }else{
                            $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                         } 
                    }else if($Statusii=='approved'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                    }else if($Statusii=='paid'){
                        if($Transaction_Type=="Cash"){
                            $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                         }else{
                             $pyd = '<span style="color:green;text-align:center;">billed</span>';
                        }
                    }else if($Statusii=='served'){
                        if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                            $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                         }else{
                            $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                         }
                    }

                    if (strtolower($Statusii) == 'served') {
                        $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                    } else {
                        $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                    }
                 

                    $imaging = '';

                    $view_results = $imaging;

                    //Getting Radiologist Name

                    $Radiologist_Name = 'N/A';

                    //Getting Sonographer Name

                    $Sonographer_Name = 'N/A';

                    //Getting Doctors Comments


                    $style = 'style="text-decoration:none;"';

                    $data .= '<tr>';
                    $data .= '<td>' . $sn . '</td>';
                    $data .= '<td>' . $test_name . '</td>';
                    $data .= (empty($display)) ? '<td>' . $Statusii . '</td>' : '';
                    $data .= '<td>' . $Doctor_Comment . '</td>';
                    $data .= '<td>' . $remarks . '</td>';
                    $data .= '<td>' . $Radiologist_Name . '</td>';
                    $data .= '<td>' . $Sonographer_Name . '</td>';
                    $data .= '<td>' . $sent_date . '</td>';
                    $data .= '<td>&nbsp;</td>';
                    $data .= '<td>&nbsp;</td>';
                    $data .= '<td>&nbsp;</td><td>'.$pyd.'</td>';
                    $data .= '</tr>';
                    $sn++;
                }
            }
            $data .= "</table>";
            if (mysqli_num_rows($select_patients_qry) == 0) {
                $data .= "</div>";
            }
        }


         $qr = "SELECT Patient_Payment_ID,Billing_Type, Check_In_ID, ilc.Payment_Item_Cache_List_ID,ilc.Status,Product_Name,Doctor_Comment,ilc.Remarks,i.Item_ID,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.Consultant_ID) AS sentby,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.ServedBy) AS servedby,ilc.Transaction_Date_And_Time AS sentOn,ServedDateTime  AS servedOn FROM
                tbl_item_list_cache ilc, tbl_payment_cache pc , tbl_items i 
                WHERE ilc.Item_ID = i.Item_ID AND ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND pc.Registration_ID = '$Registration_ID' AND
                Billing_Type LIKE '%Outpatient%' AND ilc.Status !='notsaved'  AND
                pc.consultation_id ='$consultation_ID' AND ilc.Check_In_Type='Surgery'
            ";
         
        $select_qr = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_qr) > 0) {
            $data .= " <div width='100%' id='departments_comments'>
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
                        <td>Payment Status</td>
                    </tr>';

            // die();

            $sn = 1;
            while ($patient = mysqli_fetch_assoc($select_qr)) {
                $Patient_Payment_ID =$patient['Patient_Payment_ID'];
                $Billing_Type =$patient['Billing_Type'];
                $Check_In_ID =$patient['Check_In_ID'];
                
                $test_name = $patient['Product_Name'];
                $Doctor_Comment = $patient['Doctor_Comment'];
                // $data .= $test_name;
                $Consultant = $remarks = '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
               
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
                 //PAYMENT STATUS FOR Nuclear medicine
                if($Statusii == 'active'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                     } 
                }else if($Statusii=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusii=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }else if($Statusii=='served'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                     }
                }
                if (strtolower($Statusii) == 'served') {
                    $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                }
                $Payment_Item_Cache_List_ID = $patient['Payment_Item_Cache_List_ID'];


                $Item_ID = $patient['Item_ID'];
                $served_date = $patient['Transaction_Date_And_Time'];
                $sentby = $patient['sentby'];
                $sentOn = $patient['sentOn'];
                $servedby = $patient['servedby'];
                $servedOn = $patient['servedOn'];
                $Status = $patient['Status'];

                if (strtolower($Status) == 'served') {
                    $st = '<span style="color:blue;text-align:center;">Done</span>';
                    $comm = "<a class='no_color' href='previewpostoperativereport.php?Registration_ID=$Registration_ID&Payment_Item_Cache_List_ID=$Payment_Item_Cache_List_ID' title='Click to view comment added by Post Operative Notes' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";
                } else {
                    $st = '<span style="color:red;text-align:center;">Not done</span>';
                }


                // if (strtolower($patient['Status'] != 'served')) {
                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= '<td>' . $Doctor_Comment . '</td>';


                $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
                $data .= '<td>' . $sentby . '</td>';
                $data .= '<td>' . $sentOn . '</td>';
                $data .= '<td>' . $servedby . '</td>';
                $data .= '<td>' . $servedOn . '</td>';
                $data .= '<td>' . $comm . '</td><td>'.$pyd.'</td>';
                $data .= '</tr>';;
                $sn++;
                // }
            }
            $data .= '</table></div>';
        }

        $qry = "SELECT Check_In_ID,ilc.Patient_Payment_ID, ilc.Status,tit.Product_Name,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin 
                FROM tbl_item_list_cache ilc 
                LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
		JOIN tbl_items tit ON tit.Item_ID=ilc.Item_ID 
		JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
		JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
		WHERE  ilc.Status != 'notsaved'  AND 
                Billing_Type LIKE '%Outpatient%'  AND 
                pc.consultation_id ='$consultation_ID' AND 
                ilc.Check_In_Type='Procedure' 
		";


        $rs = mysqli_query($conn,$qry) or die(mysqli_error($conn));

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
            $data .= '<td>Date</td><td>Payment Status</td>';
            $data .= '</tr>';
            while ($row = mysqli_fetch_assoc($rs)) {
                $Patient_Payment_ID =$row['Patient_Payment_ID'];
                $Billing_Type =$row['Billing_Type'];
                $Check_In_ID = $row['Check_In_ID'];
                
                $test_name = $row['Product_Name'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $remarks = $row['remarks'];
                $Emp_name = $row['Employee_Name'];
                $title = $row['Employee_Type'];
                $served_date = $row['DateTime'];
                $ServedDateTime = $row['ServedDateTime'];
                $ServedBy = $row['ServedBy'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
               
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
                 //PAYMENT STATUS FOR Nuclear medicine
                if($Statusii == 'active'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                     } 
                }else if($Statusii=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusii=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }else if($Statusii=='served'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                     }
                }

                if (strtolower($Statusii) == 'served') {
                    $Statusii = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Statusii = '<span style="color:red;text-align:center;">Not done</span>';
                }
//            else{
//                $Status = '<span style="color:red;text-align:center;">'.ucfirst($Status).'</span>';
//            }

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
                $data.="<td>$pyd</td>";
                $data .= '</tr>';;
                $sn++;
            }

            $data .= '</table></div> ';
        }
       

 //Nuclear medicine
 $qry = "SELECT Patient_Payment_ID,Billing_Type,Check_In_ID, ilc.Status,tit.Product_Name,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin,Payment_Item_Cache_List_ID, pc.Payment_Cache_ID  FROM tbl_item_list_cache ilc,   tbl_items tit,tbl_employee em, tbl_payment_cache pc   WHERE Billing_Type IN('Outpatient Credit', 'Outpatient Cash')  AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND
 pc.consultation_id ='$consultation_ID' AND ilc.Status != 'notsaved'   AND ilc.Check_In_Type='Nuclearmedicine' AND tit.Item_ID=ilc.Item_ID  AND em.Employee_ID=ilc.Consultant_ID GROUP BY Payment_Item_Cache_List_ID";
// die($qry);
 $rs = mysqli_query($conn,$qry) or die(mysqli_error($conn));

 $sn = 1;

 if (mysqli_num_rows($rs) > 0) {
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
     $data .= '<td>Date</td><td>Payment Status</td>';
     $data .= '</tr>';
     while ($row = mysqli_fetch_assoc($rs)) {
       
         $test_name = $row['Product_Name'];
         $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
         $Doctor_Comment = $row['Doctor_Comment'];
         $remarks = $row['remarks'];
         $Emp_name = $row['Employee_Name'];
         $title = $row['Employee_Type'];
         $served_date = $row['DateTime'];
         $ServedDateTime = $row['ServedDateTime'];
         $ServedBy = $row['ServedBy'];
         $Status = $row['Status'];
         $Payment_Cache_ID = $row['Payment_Cache_ID'];
         $Patient_Payment_ID = $row['Patient_Payment_ID'];
         $Statusii = $row['Status'];
         $Transaction_Type = $row['Transaction_Type'];
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
          //PAYMENT STATUS FOR Nuclear medicine
         if($Statusii == 'active'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
              }else{
                 $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
              } 
         }else if($Statusii=='approved'){
             $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
         }else if($Statusii=='paid'){
             if($Transaction_Type=="Cash"){
                 $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
              }else{
                  $pyd = '<span style="color:green;text-align:center;">billed</span>';
             }
         }else if($Statusii=='served'){
             if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                 $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
              }else{
                 $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
              }
         }
         if (strtolower($Status) == 'served') {
             $Status = '<span style="color:blue;text-align:center;">Done</span>';
         } else {
             $Status = '<span style="color:red;text-align:center;">Not done</span>';
         }
        
             $select = mysqli_query($conn,"SELECT created_at, nmr.Payment_Item_Cache_List_ID from tbl_nuclear_medicine_report nmr, tbl_item_list_cache ilc where  nmr.Payment_Item_Cache_List_ID = ilc.Payment_Item_Cache_List_ID and  nmr.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
             $no = mysqli_num_rows($select);
             if($no > 0){
                 while ($dt = mysqli_fetch_array($select)) {
                     $Payment_Item_Cache_List = $dt['Payment_Item_Cache_List_ID'];
                     $resultform = '<a href="print_nuclear_md_report.php?Payment_Item_Cache_List_ID='.$Payment_Item_Cache_List.'&Payment_Cache_ID='.$Payment_Cache_ID.'&Registration_ID='.$Registration_ID.'" target="blank" class="art-button-green" >RESULT</a>';
                 }
             }else{
                 $resultform = " ".ucwords($Status)." and Not processed";
                
             }
         

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
         
         $data .= '<td>' . $served_date . '</td><td>'.$pyd.'</td>';

         $data .= '</tr>';;
         $sn++;
     }

     $data .= '</table></div> ';
 }
 ////==== End Nuclear medicine
        
        // ====== Others =====
        $qry = "SELECT tit.Product_Name,ilc.Status,ilc.Doctor_Comment,ilc.Patient_Payment_ID,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time,ilc.Transaction_Type AS DateTime,'doctor' as origin FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_items tit, tbl_employee em, tbl_consultation tc WHERE ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND tit.Item_ID=ilc.Item_ID AND  em.Employee_ID=ilc.Consultant_ID AND tc.consultation_ID=pc.consultation_id AND  ilc.Status != 'notsaved' AND  pc.consultation_id ='$consultation_ID' AND pc.Billing_Type LIKE '%Outpatient%' AND ilc.Check_In_Type='Others' 
	
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
                $served_date = $row['Transaction_Date_And_Time'];
                $ServedDateTime = $row['ServedDateTime'];
                $ServedBy = $row['ServedBy'];
                
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
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
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                     } 
                }else if($Statusii=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusii=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }else if($Statusii=='served'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
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
           
           
        
            $subqr = "SELECT Patient_Payment_ID,Billing_Type,Check_In_ID, ilc.Employee_Created,ilc.Status,Product_Name,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.item_ID = ilc.item_ID    JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID    JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id    WHERE  ilc.Status != 'notsaved' AND Check_In_Type='Pharmacy' AND     Billing_Type LIKE '%Outpatient%'AND    pc.Registration_ID='$Registration_ID' AND "    . "pc.consultation_id ='$consultation_ID'";
        


        

        $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));



        $sn = 1;

        if (mysqli_num_rows($result) > 0) {
            $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>PHARMACY</b>
            </div>";
            $data .= '<table width="100%" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td style="text-align:left">Medication Name</td>';
            $data .= (empty($display)) ? '<td><b>Status</b></td>' : '';
            $data .= '<td style="text-align:center">Qty Ordered</td>';
            $data .= '<td style="text-align:center">Qty Issued</td>';
            $data .= '<td style="text-align:left">Dosage</td>';
            $data .= '<td style="text-align:left">Dispensor</td>';
            $data .= '<td style="text-align:left">Date Dispensed</td>';
            $data .= '<td style="text-align:left">Ordered By</td>';
            $data .= '<td style="text-align:left">Date Ordered</td><td>Payment Status</td>';
            $data .= '</tr>';

            while ($row = mysqli_fetch_assoc($result)) {
                
                $qr = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $row['Dispensor'] . "'") or die(mysqli_error($conn));
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
                $Doctor_Comment = $row['Doctor_Comment'];
                $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                
                $Dispense_Date_Time = $row['Dispense_Date_Time'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Statusii1 = $row['Status'];
                $Transaction_Type = $row['Transaction_Type'];
                
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
                if($Statusii1 == 'active'){
                    if((strtolower($Billing_Type) == 'outpatient cash' || $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:red;text-align:center;">Not Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:red;text-align:center;">Not Paid</span>'; 
                     } 
                }else if($Statusii1=='approved'){
                    $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                }else if($Statusii1=='paid'){
                    if($Transaction_Type=="Cash"){
                        $pyd = "<span style='color:green;text-align:center;'>Cash Paid</span>";
                     }else{
                         $pyd = '<span style="color:green;text-align:center;">billed</span>';
                    }
                }else if(strtolower($Statusii1)=='dispensed'){
                    if((strtolower($Billing_Type) == 'outpatient cash' && $payment_type=='post') || strtolower($Billing_Type) == 'outpatient credit'){
                        $pyd = '<span style="color:green;text-align:center;">Billed</span>'; 
                     }else{
                        $pyd = '<span style="color:green;text-align:center;">Cash Paid</span>'; 
                     }
                }
                $qty = 0;

                if ($disepnsedQty > 0) {
                    $qty = $disepnsedQty;
                } else {
                    $qty = $orderedQty;
                }

                if (strtolower($Statusii1) == 'dispensed') {
                    $Status = '<span style="color:blue;text-align:center;font-size: 14px;">Dispensed</span>';
                } else {
                    if (strtolower($Statusii1) == 'removed') {
                        $Status = '<span style="color:red;text-align:center;">Removed</span>';
                    } else {
                        $Status = '<span style="color:red;text-align:center;">Not given</span>';
                    }
                }

                $data .= '<tr>';
                $data .= '<td style="width:3%;">' . $sn . '</td>';
                $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
                $data .= (empty($display)) ? '<td>' . $Status . '</td>' : '';
                $data .= '<td style="text-align:center">' . $orderedQty . '</td>';
                $data .= '<td style="text-align:center">' . $qty . '</td>';
                $data .= '<td style="text-align:left">' . $Doctor_Comment . '</td>';
                $data .= '<td style="text-align:left">' . $Disponsor . '</td>';
                $data .= '<td style="text-align:left">' . $Dispense_Date_Time . '</td>';
                $data .= '<td style="text-align:left">' . ucwords(strtolower($Employee_Created)) . '</td>';
                $data .= '<td style="text-align:left">' . $Transaction_Date_And_Time . '</td><td>'.$pyd.'</td>';
                $data .= '</tr>';

                $sn++;
            }

            $data .= '</table></div>';
        }
        if ($hasInpatientDetails) {
            $data .= "
            <br/><br/>
            <hr width='100%'/>";
        } else {
            $data .= '<br/>';
        }
    }
 
    return $data;
   
}


#malopa added: display tb info in function in tb file
function displayTbInfo($registreationId, $date)
{
    global $conn;
    $today = Date('Y-m-d');
    $sql = "SELECT * FROM tbl_bt_diagnosis WHERE Registration_ID='$registreationId' AND date_time=DATE('$date')";
    $result = mysqli_query($conn,$sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        echo "<table id='table' style='width:100%;'>
    <th style='text-align:left; font-weight:bold; background:#C0C0C0;' colspan='2'>TB SCREENING</th>";
        while ($row = mysqli_fetch_assoc($result)) {
            extract($row);

            echo '
        <tr>
        <td>Cough for two weeks or more.</td>
        <td style="text-align:center;">' . $cough . '</td>
        </tr>
        
        <tr>
        <td>Cough for less than two weeks.</td>
        <td style="text-align:center">' . $household_history . '</td>
        
        </tr>
        <tr>
        <td>Sputum production.</td>
        <td style="text-align:center">' . $fever . '</td>
        
        </tr>
        <tr>
        <td>Coughing up blood.</td>
        <td style="text-align:center">' . $irritability . '</td>
        
        </tr>
        <tr>
        <td>History for household contact with TB.</td>
        <td style="text-align:center">' . $weight_change . '</td>
        
        </tr>
        <tr>
        <td>Fever of any duration.</td>
        <td style="text-align:center">' . $past_treatment . '</td>
        
        </tr>
        <tr>
        <td>Radical activities or irritability for two weeks or more.</td>
        <td style="text-align:center">' . $excessive_sweat . '</td>
        
        </tr>
        <tr>
        <td>Inadequate weight gain,faltering or loss.</td>
        <td style="text-align:center">' . $other_symptoms . '</td>
        
        </tr>
        <tr>
        <td>Past history of TB treatment.</td>
        <td style="text-align:center">' . $cough_less . '</td>
        
        </tr>
        <tr>
        <td>Excessive night sweats.</td>
        <td style="text-align:center">' . $sputum_yes . '</td>
        </tr>
        <tr>
        <td>Any other symptoms (chest pain, chest tightness).</td>
        <td style="text-align:center">' . $cough_blood . '</td>
        </tr>
        <tr>
        <td style="text-align:center"><b>Total Score.</b></td>
        <td style="text-align:center; background:#C0C0C0;"><b>' . $total_score . '</b></td>
        </tr>
        <tr>
        ';
        }
        echo "</table>";
    }
}
?>



