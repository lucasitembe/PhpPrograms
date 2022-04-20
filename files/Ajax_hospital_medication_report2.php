<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterClinic= ' ';
$filterIn = ' ';
$filterSub = ' ';
$filterDiagnosis=' ';
$filterbilltype='';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$diagnosis_time=$_POST['diagnosis_time'];
@$medication_category=$_POST['medication_category'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age'];
@$Medication_ID=$_POST['Medication_ID'];
@$diagnosis_type=$_POST['diagnosis_type'];
@$Clinic_ID=$_POST['Clinic_ID'];
@$Registration_ID=$_POST['Registration_ID'];

@$consultation_id = $_POST['consultation_id'];

   
    $filtermedication=" ";
	if($Medication_ID!='all'){
       $filtermedication =" AND ilc.Item_ID=$Medication_ID ";
    }
   
 
    $filterbyclinic="";
	if($Clinic_ID != 'all'){
     $filterbyclinic=" AND ilc.Clinic_ID='$Clinic_ID'";
    }else {
        $filterbyclinic ="";
    }

$qrLab = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,test_result_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache INNER JOIN tbl_test_results AS trs ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID         JOIN tbl_employee e ON e.Employee_ID=tbl_item_list_cache.Consultant_ID JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id		WHERE tc.Registration_ID='" . $Registration_ID . "' AND Billing_Type LIKE '%Outpatient%' AND tbl_payment_cache.consultation_id ='$consultation_id' AND tbl_item_list_cache.Check_In_Type='Laboratory'";

$qrLabWithoutResult = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache 
INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
JOIN tbl_employee e ON e.Employee_ID=tbl_item_list_cache.Consultant_ID
JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id      
WHERE  tbl_item_list_cache.Status != 'notsaved' AND tc.Registration_ID='" . $Registration_ID . "' AND tbl_item_list_cache.Status !='notsaved'  AND Billing_Type LIKE '%Outpatient%' AND tbl_payment_cache.consultation_id ='$consultation_id' AND tbl_item_list_cache.Check_In_Type='Laboratory'";


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
     while ($row = mysqli_fetch_array($result)) {
        $tempIlc[] = $row['Payment_Item_Cache_List_ID'];
        $st = '';
        $ppil = $row['Payment_Item_Cache_List_ID'];
        $item_ID = $row['Item_ID'];

        $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));

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
                $st = '<span style="color:blue;text-align:center;">Done</span>';
            } else if (in_array($Validated, array('No', '')) && in_array($Submitted, array('No', ''))) {
                $resultLab = "No result";
            } else {
                $st = '<span style="text-align:center;color: red;">Provisional</span>';
            }

            if ($resultLab != "No result") {
                $getParameters = "SELECT tpr.TimeSubmitted as testResult FROM tbl_tests_parameters_results tpr JOIN tbl_test_results tr ON test_result_ID=ref_test_result_ID JOIN tbl_item_list_cache ON Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_employee te ON te.Employee_ID=tsr.specimen_results_Employee_ID JOIN tbl_laboratory_specimen tls ON tls.Specimen_ID=tsr.Specimen_ID WHERE tr.payment_item_ID='" . $row['Payment_Item_Cache_List_ID'] . "'";
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
                //$result= mysqli_fetch_array($normalQry);
                
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
                    } elseif ($no_results == $totalParm) {
                    
                        $resultLab = "Normal";
                        
                        
                    }elseif ($normal == $totalParm) {
                        $resultLab = $resultNomal['result'];
                    
                    }

                     elseif ($high == $totalParm) {
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
            $resultLab = "No result";
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
                    $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='30' height='15' alt='Not Image File' /></a>&nbsp;&nbsp;";
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
       <td><b>Doctor\'s Ordered</b></td><td><b>Doctor\'s Notes</b></td><td><b>Lab Remarks</b></td>' . $srDisp . '<td><b>Attachment</b></td>' . ((empty($display)) ? '<td><b>Status</b></td>' : '') . '<td><b>Performer</b></td><td><b>Validator</b></td><td><b>Date</b></td>
    </tr>';
        $data .= "<tr>";
        $data .= "<td>" . $doctorsName . "</td>";
        $data .= "<td>" . $row['Doctor_Comment'] . "</td>";
        $data .= "<td>" . $remarks . "</td>";
        $data .= (!empty($resultLab)) ? "<td style='text-align: center;color: rgb(28, 110, 120);font-weight: bold;'>" . $resultLab . "</td>" : '';
        $data .= "<td style='text-align:center'>" . $image . "</td>";
        $data .= (empty($display)) ? '<td>' . $st . '</td>' : '';
        $data .= "<td>" . $get_submitor_Name['Employee_Name'] . "</td>";
        $data .= "<td>" . $get_Validator_Name['Employee_Name'] . "</td>";
        $data .= "<td>" . $row['Transaction_Date_And_Time'] . "</td>";
        $data .= "</tr></table>";

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
                        $data .= '<td>
        <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
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

        $resultLab = '';
    }
}else{
    $data .="<table width='100%' border='1'>
    <tr><td style='margin-left:2px;color:red; text-align:center; font-size:12px;'><h4>No  results found</h4></td></tr>
    </table>";
}

//Radiology result
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
    <td><b>Report</b></td>
</tr>";

$qr = "SELECT rpt.Status,pc.Registration_ID,i.Product_Name,rpt.Remarks,
              rpt.Date_Time,Radiologist_ID,Sonographer_ID,Patient_Payment_Item_List_ID,ilc.Payment_Item_Cache_List_ID,i.Item_ID,ilc.Transaction_Date_And_Time FROM
tbl_radiology_patient_tests rpt INNER JOIN tbl_items i
ON rpt.Item_ID = i.Item_ID 
            JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
            JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
WHERE rpt.Registration_ID = '$Registration_ID' AND
pc.consultation_id ='$consultation_id' AND
            Billing_Type LIKE '%Outpatient%' ";
$qrnotdone = "SELECT ilc.Status,ilc.Transaction_Date_And_Time,ilc.Doctor_Comment,pc.Registration_ID,i.Product_Name
              ,Payment_Item_Cache_List_ID,i.Item_ID FROM
tbl_item_list_cache ilc 
            JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
            INNER JOIN tbl_items i ON ilc.Item_ID = i.Item_ID 
WHERE   ilc.Status != 'notsaved' AND pc.Registration_ID = '$Registration_ID' AND
pc.consultation_id ='$consultation_id' AND ilc.Check_In_Type='Radiology' AND
            Billing_Type LIKE '%Outpatient%'  

";
//  die($qrnotdone);

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


    $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

    $ppID = mysqli_fetch_assoc($rs);
    $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
    if (mysqli_num_rows($rs) == 0) {
        $Patient_Payment_ID = 0;
    }

    if ($status == 'done') {
        $st = '<span style="color:blue;text-align:center;">Done</span>';
    } else {
        $st = '<span style="text-align:center;color: red;">' . ucfirst($status) . '</span>';
    }

    $listtype = '';
    $PatientType = '';
    $Doctor = '';
    $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . "&RI=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=" . $listtype;

    /* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
    $photo = "SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID' AND consultation_ID='$consultation_ID'";
    $result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $list = 0;
        while ($row = mysqli_fetch_array($result)) {
            $list++;
            // extract($row);
            $Radiology_Image = $row['Radiology_Image'];
            if (preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $Radiology_Image)) {
                $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank'><img height='20' alt=''  src='RadiologyImage/" . $Radiology_Image . "'  alt=''/></a>";
            } else {
                $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank' ><img height='20' alt=''  src='images/attach_icon.png'  alt=''/></a>";
            }
        }
    } else {
        $imaging .= "<b style='text-align: center;color:red'></b>";
    }

    $comm = "<a class='no_color' href='RadiologyTests_Print.php?RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $patient['Payment_Item_Cache_List_ID'] . "' title='Click to view comment added by radiologist' target='_blank'><img height='20' alt=''  src='images/report-icon.png'  alt='Comments'/></a>";

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

    $data .= '<tr>';
    $data .= '<td>' . $sn . '</td>';
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
    $data .= '</tr>';
    ;
    $sn++;
}
$data .= "</table>";
}


$qry = "SELECT ilc.Status,tit.Product_Name,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin 
FROM tbl_item_list_cache ilc 
LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
JOIN tbl_items tit ON tit.Item_ID=ilc.Item_ID 
JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
WHERE  ilc.Status != 'notsaved'  AND 
Billing_Type LIKE '%Outpatient%'  AND 
pc.consultation_id ='$consultation_id' AND 
ilc.Check_In_Type='Procedure' 

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
$Status = $row['Status'];

if (strtolower($Status) == 'served') {
    $Status = '<span style="color:blue;text-align:center;">Done</span>';
} else {
    $Status = '<span style="color:red;text-align:center;">Not done</span>';
}
//            else{
//                $Status = '<span style="color:red;text-align:center;">'.ucfirst($Status).'</span>';
//            }

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
$data .= '<td>' . $served_date . '</td>';

$data .= '</tr>';
;
$sn++;
}

$data .= '</table></div> ';
}



echo $data;