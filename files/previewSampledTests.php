<?php

session_start();
include("./includes/connection.php");
$data = '';
if ($_GET['patient_id'] != '') {
    $Registration_ID = $_GET['patient_id'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['filterprev'])) {
    $filter=str_replace("na", "'", $_GET['filterprev']);
    $filter=str_replace("TimeSubmitted", "Transaction_Date_And_Time", $filter);
    //$filter=str_replace(" AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())", " AND DATE(Transaction_Date_And_Time)=DATE(NOW())", $filter);
    
    $filter = $filter . " AND pc.Registration_ID='$Registration_ID'";
} else {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $subcategory_ID = filter_input(INPUT_GET, 'subcategory_ID');


    $backFilter = '';
    $filter = " AND DATE(Transaction_Date_And_Time) = CURDATE() AND pc.Registration_ID='$Registration_ID'";

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'  AND pc.Registration_ID='$Registration_ID'";
        $backFilter = "?filter=  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if ($subcategory_ID != 'All' && !empty($subcategory_ID)) {
        $filter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
        $backFilter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
    }

    if ($Sponsor != 'All' && !empty($Sponsor)) {
        $backFilter .=" AND sp.Sponsor_ID=$Sponsor";
    }
}


//die($filter);

$getPatientDetails = "
          SELECT il.Clinic_ID,il.Priority,il.Doctor_Comment,Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,Date_Of_Birth,Guarantor_Name, Billing_Type,e.Employee_Name,pc.Payment_Date_And_Time, TIME(NOW()) myTime,TimeSubmitted 
           FROM tbl_patient_registration pr 
           JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID 
           JOIN tbl_payment_cache pc ON pr.Registration_ID=pc.Registration_ID 
           JOIN tbl_employee  e on e.Employee_ID =pc.Employee_ID
           JOIN tbl_item_list_cache il ON pc.Payment_Cache_ID=il.Payment_Cache_ID 
           JOIN  tbl_items i ON i.Item_ID=il.Item_ID 
           JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID 
         WHERE il.Check_In_Type='Laboratory' and (il.Status='Sample Collected' ) $filter
        ";

$result = mysqli_query($conn,$getPatientDetails) or die(mysqli_error($conn));

$doctorName = '';
//echo mysqli_num_rows($result);exit;
if (mysqli_num_rows($result) == 0) {
    $data = "<table width ='100%' height = '30px'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td style='text-align: center;font-size: small;'><b>No test availlable.Please submit the test to continue.</b></td>
            </tr></table><br/>";
} else {

    $row = mysqli_fetch_array($result);

    $patientName = $row['Patient_Name'];
    $Registration_ID = $row['Registration_ID'];
    $Gender = $row['Gender'];
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Region = $row['Region'];
    $District = $row['District'];
    $Guarantor_Name = $row['Guarantor_Name'];
    $Billing_Type = $row['Billing_Type']; //
    $doctorName = $row['Employee_Name'];
    $doctorDate = $row['Payment_Date_And_Time'];
    $myTime = $row['myTime'];
    $Doctor_Comment = $row['Doctor_Comment'];
    $Priority = $row['Priority'];
    $Clinic_ID = $row['Clinic_ID'];
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    $age ='';
    }	
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";
		
    $sql_select_clinic_name_result=mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'") or die(mysqli_error($conn));
    $Clinic_Name=mysqli_fetch_assoc($sql_select_clinic_name_result)['Clinic_Name'];
    
    $select_patient_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward hw,tbl_admission ad WHERE hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND ad.Registration_ID='$Registration_ID' AND Admission_Status='Admitted'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_patient_ward_result)){
       $Hospital_Ward_Name=mysqli_fetch_assoc($select_patient_ward_result)['Hospital_Ward_Name'];
    }else{
       $Hospital_Ward_Name=""; 
    }
    $data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>";
            if($Priority == 'Urgent'){
    $data .="<tr>
                <td style='text-align: center; color: red;'><h1><b>URGENT PATIENT</h1></b></td>
            </tr>";
                    }

    $data .="<tr>
                <td style='text-align: center;'><b>PATIENT LABORATORY TESTS</b></td>
            </tr></table><br/>";

    $data .= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;" width="10%"><b>Name:</b></td>
                    <td width="30%">' . $patientName . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Billing Type:</b></td>
                    <td>' . $Billing_Type . '</td>
                    <td style="text-align: right;"><b>Date:</b></td>
                    <td colspan="3">' . date('d, M Y') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . '</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $Region . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $District . '</td>
                </tr>
                
                <tr>
                    <td style="text-align: right;"><b>Priority:</b></td>
                    <td>'.$Priority.'</td>
                    <td style="text-align: right;"><b>Clinic:</b></td>
                    <td colspan="3">'.$Clinic_Name.'</td>
                </tr>
                <tr>
                    <td><b>Ward:</b></td>
                    <td>'.$Hospital_Ward_Name.'</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><b>Doctor Clinical Notes:</b></td>
                    <td colspan="4">'.$Doctor_Comment.'</td>
                </tr>
            </table><br/>';


    $data .= "
      <table width='100%' style='border-collapse: collapse;'  >
      <thead>
        <tr>
            <td><b>S/N</b></td>
            <td><b>TEST NAME</b></td>
            <td><b>SPECIMEN</b></td>
            <td><b>SUBMITTED ON</b></td>
            <td><b>COLLECTED BY</b></td>
            <td><b>SPECIMEN ID</b></td>
        </tr>
      </thead>";

    $sn = 1;
    //  foreach ($ppils as $value) {
    $Query = "SELECT Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected'  $filter  GROUP BY Payment_Item_Cache_List_ID";
    $result2 = mysqli_query($conn,$Query) or die(mysqli_error($conn));
	// echo '<pre>';
	// print_r(mysqli_fetch_array($result2));
    // die($Query);
    while ($row2 = mysqli_fetch_array($result2)) {

        $product_name = $row2['Product_Name'];
        $Payment_Item_Cache_List_ID = $row2['Payment_Item_Cache_List_ID'];
        $TimeSubmitted = $row2['TimeSubmitted'];
        $test_result_ID = $row2['test_result_ID'];
        $Employee_ID = $row2['Employee_ID'];

        $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'") or die();
        $Employee_Name=mysqli_fetch_assoc($sql_select_employee_result)['Employee_Name'];
                
        $row_check_if_exists = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $test_result_ID . "' AND Saved='Yes' ")or die(mysqli_error($conn));

        //echo 'submitted and validated';

        if (mysqli_num_rows($row_check_if_exists) == 0) {
            //get Specimen

            $checkSpecimenCollection = mysqli_query($conn,"SELECT Specimen_Name FROM tbl_laboratory_specimen ls JOIN tbl_specimen_results  sp ON ls.Specimen_ID=sp.Specimen_ID WHERE payment_item_ID='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
            $specimen = '';
            $track = 1;
            while ($row = mysqli_fetch_array($checkSpecimenCollection)) {
                if ($track == 1) {
                    $specimen = $row['Specimen_Name'];
                } else {
                    $specimen .=',' . $row['Specimen_Name'];
                    ;
                }

                $track ++;
            }

            $data .= '<tr>';
            $data .= '<td><span style="font-size: x-small;">' . $sn++ . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $product_name . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $specimen . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $TimeSubmitted . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $Employee_Name . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $Payment_Item_Cache_List_ID . '</td>';

            $data .= '</tr>';
        }
    }
}
$data .= '</table><br/>';

if (!empty($doctorName)) {
	$data .= '<table width="100%" border="0" class="nobordertable">
					<tr>
					<th colspan="4"><center>SAMPLE(s) PROCESSING SHEET</center></th>
				</tr>
				</table></br>';
	
    $data .= '<table width="100%" border="0" class="nobordertable">
                <tr>
					<td width="18%"><b>Collected By:</b></td>
					<td>_____________________________________________</td>
					<td style="text-align:right"><b>&nbsp;&nbsp;&nbsp;&nbsp;Date & Time:</b></td>
					<td>______________________________</td>
				</tr>
				<tr>
					<td width="18%"><b>Submitted By:</b></td>
					<td>_____________________________________________</td>
					<td style="text-align:right"><b>&nbsp;&nbsp;&nbsp;&nbsp;Date & Time:</b></td>
					<td>______________________________</td>
				</tr>
				<tr>
					<td width="18%"><b>Received By:</b></td>
					<td>_____________________________________________</td>
					<td style="text-align:right"><b>&nbsp;&nbsp;&nbsp;&nbsp;Date & Time:</b></td>
					<td>______________________________</td>
				</tr>
				<tr>
					<td width="18%"><b>Processed By:</b></td>
					<td>_____________________________________________</td>
					<td style="text-align:right"><b>&nbsp;&nbsp;&nbsp;&nbsp;Date & Time:</b></td>
					<td>______________________________</td>
				</tr>
				<tr><td><br><br><br></td>
				</tr>
                <tr>
                    <td><b>Consulted By:</b></td>
                    <td>' . $doctorName . ', &nbsp;' . date('d, M y', strtotime($doctorDate)) . '</td>
                    <td style="text-align:right" width="22%"><b>Serviced At:&nbsp;&nbsp;</b>' . $myTime . '</td>
                </tr>
            </table>';
}

include("./MPDF/mpdf.php");
//$mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
////$mpdf->SetFooter('Consulted By ' . strtoupper($_SESSION['userinfo']['Employee_Name']) . '|Page {PAGENO} of {nb}|{DATE d-m-Y}');
//$mpdf->WriteHTML($data);
//$mpdf->Output();
//exit;

$mpdf = new mPDF('s', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
