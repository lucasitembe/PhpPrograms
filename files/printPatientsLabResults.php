<?php

session_start();
$Employee = $_SESSION['userinfo']['Employee_Name'];
include 'includes/connection.php';
$datastring = $_GET['datastring'];
$patient_ID = $_GET['registration_ID'];
$payment_ID = $_GET['payment_ID'];
$resultLocation = $_GET['resultLocation'];
$result = explode('@', $datastring);


$Query = "SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID WHERE tbl_payment_cache.Registration_ID='" . $patient_ID . "' AND tbl_item_list_cache.Payment_Cache_ID='" . $payment_ID . "' AND Check_In_Type='Laboratory'";
//echo $Query;
$myQuery = mysqli_query($conn,$Query);
$htm = " <table width ='100%' height = '30px' class='nobordertable'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		    <td style='text-align: center;'><b>PATIENT TEST RESULTS</b></td>
		</tr>
               
         </table>
        ";
//$htm.=$Query;
//$htm.= "SELECT * FROM tbl_patient_registration JOIN tbl_payment_cache ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN tbl_item_list_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID WHERE tbl_payment_cache.Registration_ID='".$patient_ID."' AND tbl_item_list_cache.Payment_Cache_ID='".$payment_ID."' AND Check_In_Type='Laboratory'";
$patientData = mysqli_query($conn,"SELECT *,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age FROM tbl_patient_registration 
        JOIN tbl_payment_cache ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID
        JOIN tbl_item_list_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
        JOIN tbl_sponsor ON tbl_sponsor.Sponsor_ID=tbl_patient_registration.Sponsor_ID
        WHERE tbl_payment_cache.Registration_ID='" . $patient_ID . "' AND tbl_item_list_cache.Payment_Cache_ID='" . $payment_ID . "' AND Check_In_Type='Laboratory'");

$myptntData = mysqli_fetch_assoc($patientData);
$Date_Of_Birth = $myptntData['Date_Of_Birth'];
$age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, ";
$age.= $diff->m . " Months, ";
$age .= $diff->d . " Days";

$patientName = $myptntData['Patient_Name'];
$Registration_ID = $myptntData['Registration_ID'];
$Gender = $myptntData['Gender'];
// $age = $myptntData['age'];
$Region = $myptntData['Region'];
$District = $myptntData['District'];
$Country = $myptntData['Country'];
$Guarantor_Name = $myptntData['Guarantor_Name'];
$Billing_Type=$myptntData['Billing_Type'];
if($Billing_Type=='Outpatient Cash' || $Billing_Type=='Outpatient Credit'){
    $status='OPD';
    $ward='none';
}else if($Billing_Type=='Inpatient Cash' || $Billing_Type=='Inpatient Credit'){
   $status='IPD';
   $mysq_ward= mysqli_query($conn,"SELECT * FROM tbl_payment_cache tpc JOIN tbl_check_in_details tcd ON tpc.consultation_id=tcd.consultation_id JOIN tbl_admission ta ON ta.Admision_ID=tcd.Admission_ID JOIN tbl_hospital_ward thw ON ta.Hospital_Ward_ID=thw.Hospital_Ward_ID WHERE Payment_Cache_ID='$payment_ID'");
   $row_ward=  mysqli_fetch_assoc($mysq_ward);
   $ward=$row_ward['Hospital_Ward_Name'];
}
$htm.= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;"><b>Name:</b></td>
                    <td width="30%">' . $patientName . '</td>
                     <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                </tr>
                <tr>
                 <td style="text-align: right;"><b>Requested By:</b></td>
                    <td>' . $myptntData['Consultant'] . '</td>
                    
                     <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Date Requested:</b></td>
                    <td colspan="4">' . $myptntData['Service_Date_And_Time']. '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' </td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>
                
                <tr>
                    <td style="text-align: right;"><b>Status:</b></td>
                    <td>' . $status . '</td>
                    <td style="text-align: right;" ><b></b></td>
                    <td></td>
                    <td style="text-align: right;"><b>Ward:</b></b></td>
                    <td>' . $ward . '</td>
                </tr>

            </table><br/>';

//$sn = 0;
$number=0;
$myresult = mysqli_fetch_assoc($myQuery);

foreach ($result as $value) {
    $getParameters = "SELECT *,tpr.TimeSubmitted as testResult FROM tbl_tests_parameters_results tpr JOIN tbl_test_results tr ON test_result_ID=ref_test_result_ID JOIN tbl_item_list_cache ON Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_employee te ON te.Employee_ID=tsr.specimen_results_Employee_ID JOIN tbl_laboratory_specimen tls ON tls.Specimen_ID=tsr.Specimen_ID WHERE tr.payment_item_ID='" . $value . "'";
    $number++;
    $myparameters = mysqli_query($conn,$getParameters);
    $myparameters1 = mysqli_query($conn,$getParameters);
    $validator = mysqli_fetch_assoc($myparameters1);
    $totalParm=  mysqli_num_rows($myparameters1);

    $validator_Name = mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='" . $validator['ValidatedBy'] . "'");
    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);

    $submitor_Name = mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='" . $validator['SavedBy'] . "'");
    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);
    //$htm.=$getParameters;

    $itemName = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache JOIN tbl_items ON tbl_item_list_cache.Item_ID=tbl_items.Item_ID WHERE Payment_Item_Cache_List_ID='$value'");
    $productNameQuery = mysqli_fetch_assoc($itemName);
    $Specimen_Name=$validator['Specimen_Name'];
    $image = '';
    $allveralComment = '';
    $resultLab='';
    
    $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $patient_ID . "' AND item_list_cache_id='" . $value . "'");
    $image = '';

    $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $positive = mysqli_num_rows($postvQry);

    $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $negative = mysqli_num_rows($negveQry);

    $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $abnormal = mysqli_num_rows($abnormalQry);

    $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $normal = mysqli_num_rows($normalQry);

    $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $high = mysqli_num_rows($highQry);

    $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $validator['test_result_ID'] . "'")or die(mysqli_error($conn));
    $low = mysqli_num_rows($lowQry);

    if ($positive == $totalParm) {
        $resultLab = "Positive";
    } elseif ($negative == $totalParm) {
        $resultLab = "Negative";
    } elseif ($abnormal == $totalParm) {
        $resultLab = "Abnormal";
    } elseif ($normal == $totalParm) {
        $resultLab = "Normal";
    } elseif ($high == $totalParm) {
        $resultLab = "High";
    } elseif ($low == $totalParm) {
        $resultLab = "Low";
    }

     while ($attach = mysqli_fetch_array($query)) {
                if ($attach['Attachment_Url'] != '') {
                    $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='30' height='15' alt='Not Image File' /></a>&nbsp;&nbsp;";
                }
                 if(!empty($attach['Description'])){
                    $allveralComment = $attach['Description'];
                 }
     }
    //echo $image;exit;
    $colspan='';
if($resultLocation=='Outside'){  $colspan='colspan="2"';}

 $spcoll='';
 if($resultLocation=='Local'){ $spcoll=' <b>Specimen Collected: '.$validator['TimeCollected'].'</b> &nbsp;&nbsp;&nbsp;<b>Collected By: '.$validator['Employee_Name'].'</b>'; }

    $htm.='<table style="width:100%;border-spacing:0;border-collapse:collapse;" border="0"><tr><td '.$colspan.'><b>' . ++$sn . '.  ' . $productNameQuery['Product_Name'] .'<b />&nbsp;&nbsp; '.$spcoll.'</td>';
      if($resultLocation=='Local'){    
        $htm.= '<td><b>Reported By:</b> '. $get_submitor_Name['Employee_Name']. ':  '.$validator['testResult'].'<b /></td>'
            . '<td> <b>Validated By:</b> ' . $get_Validator_Name['Employee_Name'] .': '.$validator['TimeValidate'].'<b /></td>'; 
      }
      $htm .='</tr></table>';

    if (!empty($resultLab)) {

        $htm.="<table style='width:100%;border-spacing:0;border-collapse:collapse;'> 
               <tr>
                    <td style='width:20%;text-align:left'><b>Specimen: </b>" . $Specimen_Name . "</td>
                    <td style='width:20%;text-align:left'><b>Result: </b>" . $resultLab . "</td>
                    <td style='width:40%;text-align:left'><b>Comment:</b> " . $allveralComment . "</td>
                    <td style='text-align:center'><b>Attached</b><br/> " . $image . "</td>
                </tr> 
            </table><br /><br />
            ";
    } else {
        $htm.="<table style='width:100%;border-spacing:0;border-collapse:collapse;'> 
               <tr>
                    <td style='width:20%;text-align:left'><b>Specimen: </b>" . $Specimen_Name . "</td>
                    <td style='width:60%;text-align:left'><b>Comment:</b> " . $allveralComment . "</td>
                    <td ><b>Attachment:</b> " . $image . "</td>
                </tr> 
               </table>
            ";

        $htm.="<center><table class='' style='width:100%' border='1' cellspacing='0' cellpadding='0'>
           <tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th width=''>Parameters</th>
                <th width=''>Results</th>
                <th width=''>UoM</th>
                <th width=''>Normal Value</th>
                <th width=''>Status</th>
        </tr>";
//        $sn = 1;
        while ($row = mysqli_fetch_assoc($myparameters)) {
            $parameters = mysqli_query($conn,"SELECT * FROM tbl_parameters WHERE parameter_ID='" . $row['parameter'] . "'");
            $parameterName = mysqli_fetch_assoc($parameters);
            $lower = $parameterName['lower_value'];
            $upper = $parameterName['higher_value'];
            $rowResult = $row['result'];
            $result_type = $parameterName['result_type'];
            if ($result_type == "Quantitative") {
                if ($rowResult > $upper) {
                    $status = '<p style="color:rgb(255,0,0)">High</p>';
                } elseif ($rowResult < $lower) {
                    $status = '<p style="color:rgb(255,0,0)">Low</p>';
                } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                    $status = '<p style="color:rgb(0,128,0)">Normal</p>';
                } else {
                    $status = '<p style="color:rgb(0,128,0)">-</p>';
                }
            } else if ($result_type == "Qualitative") {
                $status = '<p style="color:rgb(0,0,128)">-</p>';
            } else {
                $status = '<p style="color:rgb(0,0,128)">-</p>';
            }
            $Parameter_Name=$parameterName['Parameter_Name'];
         
            $result=$row['result'] ;
            if($result=="")continue;
            $htm.="<tr>
               <td >" .$number++. "</td>
                <td >" .$Parameter_Name. "</td>
                <td style='text-align:;center'>" .$result. "</td>
                <td style='text-align:;center'>" . $parameterName['unit_of_measure'] . "</td>
                <td style='text-align:;center'>" . $parameterName['lower_value'] . ' ' . $parameterName['operator'] . ' ' . $parameterName['higher_value'] . "</td>
                <td style='text-align:;center'>" . $status . "</td>
            </tr>";
//            $htm.='<tr><td></td></tr>';
        }
        
        $htm.=" </table><br /><br />";
    }
}
$htm.="<br/><table style='width:100%' class='nobordertable'>
        <tr>
         <td style='text-align:right;width:20%'><b>Patient's Signature:</b></td>
         <td>...................................................</td>
        </tr> 
        
       
       <tr>
            <td  style='text-align:right'><b>Signature:</b></td>
            <td>...................................................</td>
       </tr>
      </table>";

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'Letter');
$mpdf->showImageErrors = true;
$mpdf->curlAllowUnsafeSslRequests = true;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);
$mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
