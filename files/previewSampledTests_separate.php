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
//    $filter=str_replace("TimeSubmitted", "Transaction_Date_And_Time", $filter);
    $filter=str_replace("TimeSubmitted", "sr.time_received", $filter);
    //$filter=str_replace(" AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())", " AND DATE(Transaction_Date_And_Time)=DATE(NOW())", $filter);
    
//    $filter = "AND DATE(sr.time_received) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) AND pc.Registration_ID='$Registration_ID'";
//    if(strpos($filter, "")){
//        
//    }
    $filter .="AND pc.Registration_ID='$Registration_ID'";
  
     
} else {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $subcategory_ID = filter_input(INPUT_GET, 'subcategory_ID');


    $backFilter = '';
    $filter = " AND DATE(sr.time_received)=CURDATE() AND pc.Registration_ID='$Registration_ID'";
//    $filter = " AND DATE(Transaction_Date_And_Time)=DATE(NOW()) AND pc.Registration_ID='$Registration_ID'";

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'  AND pc.Registration_ID='$Registration_ID'";
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

//select all category under this user
$sql_select_all_category_under_this_patient_result=mysqli_query($conn,"SELECT form_number,its.Item_Subcategory_ID,Item_Subcategory_Name FROM tbl_items it,tbl_item_list_cache ilc,tbl_item_subcategory its,tbl_payment_cache pc,tbl_specimen_results sr WHERE ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID AND ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND it.Item_ID=ilc.Item_ID AND it.Item_Subcategory_ID=its.Item_Subcategory_ID AND ilc.Check_In_Type='Laboratory' AND (ilc.Status='Sample Collected' ) $filter GROUP BY its.Item_Subcategory_ID") or die(mysqli_error($conn));

$data_array=[];
if(mysqli_num_rows($sql_select_all_category_under_this_patient_result)>0){
    while($cat_rows=mysqli_fetch_assoc($sql_select_all_category_under_this_patient_result)){
        $Item_Subcategory_ID=$cat_rows['Item_Subcategory_ID'];
        $Item_Subcategory_Name=$cat_rows['Item_Subcategory_Name'];
        $form_number=$cat_rows['form_number'];

//die($filter);

$getPatientDetails = "
          SELECT pr.Phone_Number,il.Clinic_ID,il.Priority,il.Doctor_Comment,Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,Guarantor_Name, Billing_Type,e.Employee_Name,pc.Payment_Date_And_Time, TIME(NOW()) myTime,TimeSubmitted 
           FROM tbl_patient_registration pr 
           JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID 
           JOIN tbl_payment_cache pc ON pr.Registration_ID=pc.Registration_ID 
           JOIN tbl_employee  e on e.Employee_ID =pc.Employee_ID
           JOIN tbl_item_list_cache il ON pc.Payment_Cache_ID=il.Payment_Cache_ID 
           JOIN  tbl_items i ON i.Item_ID=il.Item_ID 
           JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID 
           JOIN tbl_specimen_results sr ON il.Payment_Item_Cache_List_ID=sr.payment_item_ID 
         WHERE il.Check_In_Type='Laboratory' and (il.Status='Sample Collected' ) AND i.Item_Subcategory_ID='$Item_Subcategory_ID' $filter
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
    $Phone_Number = $row['Phone_Number'];
    $Registration_ID = $row['Registration_ID'];
    $Gender = $row['Gender'];
    $age = $row['age'];
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

    $sql_select_clinic_name_result=mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'") or die(mysqli_error($conn));
    $Clinic_Name=mysqli_fetch_assoc($sql_select_clinic_name_result)['Clinic_Name'];
    
    $select_patient_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward hw,tbl_admission ad WHERE hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND ad.Registration_ID='$Registration_ID' AND Admission_Status='Admitted'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_patient_ward_result)){
       $Hospital_Ward_Name=mysqli_fetch_assoc($select_patient_ward_result)['Hospital_Ward_Name'];
    }else{
       $Hospital_Ward_Name=""; 
    }
    
    $effective_date="";
        $rev_no="";
            $sql_select_last_saved_details=mysqli_query($conn,"SELECT effective_date,rev_no FROM tbl_laboratory_request_form_config ORDER BY laboratory_request_form_config_id DESC LIMIT 1") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_last_saved_details)>0){
                $detail_rows= mysqli_fetch_assoc($sql_select_last_saved_details);
               $effective_date= $detail_rows['effective_date'];
               $rev_no= $detail_rows['rev_no'];
            }
    $data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td style='text-align: center;'>&nbsp;CLINICAL LABORATORY </td></tr>
            <tr>
                <td style='text-align: center;'><b>Patient Request Form &nbsp;(".strtoupper($Item_Subcategory_Name).")</b></td>
            </tr>
            <tr>
                <td style='text-align: center;'><b>Form Number :-F".strtoupper($form_number)."</b></td>
            </tr>
            <tr>
                
                <td style='text-align: center;'><b>Revision Number :-".strtoupper($rev_no)."</b></td>
               
            </tr>
            <tr>
               
                <td style='text-align: center;'><b>Effective Date :-".strtoupper($effective_date)."</b></td>
            </tr>
            
</table><br/>";
    if($Priority=="Normal")$Priority="Routine";

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
                    <td>' . $age . ' years</td>
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
                    <td  style="text-align: right;"><b>Phone No.:</b></td>
                    <td  style="text-align: left;" colspan="2">'.$Phone_Number.'</td>
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
            <td><b style='font-size: x-small;'>S/N</b></td>
            <td><b style='font-size: x-small;'>TEST NAME</b></td>
            <td><b style='font-size: x-small;'>SPECIMEN</b></td>
            <td><b style='font-size: x-small;'>COLLECTED&nbsp;AT</b></td>
            <td><b style='font-size: x-small;'>COLLECTED&nbsp;BY</b></td>
            <td><b style='font-size: x-small;'>RECEIVED&nbsp;AT</b></td>
            <td><b style='font-size: x-small;'>RECEIVED&nbsp;BY</b></td>
            <td><b style='font-size: x-small;'>STATUS</b></td>
        </tr>
      </thead>";

    $sn = 1;
    //  foreach ($ppils as $value) {
    $Query = "SELECT Priority,Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID,tr.Employee_ID_receive FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_specimen_results sr ON il.Payment_Item_Cache_List_ID=sr.payment_item_ID  WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected' AND i.Item_Subcategory_ID='$Item_Subcategory_ID'  $filter  GROUP BY Payment_Item_Cache_List_ID";
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
        
        $Priority = $row2['Priority'];
        if($Priority=="Normal")$Priority="Routine";
        $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'") or die();
        $Employee_Name=mysqli_fetch_assoc($sql_select_employee_result)['Employee_Name'];
        
        $sql_select_employee_result2=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID_receive'") or die();
        $Employee_Name_receive=mysqli_fetch_assoc($sql_select_employee_result2)['Employee_Name'];
                
        $row_check_if_exists = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $test_result_ID . "' AND Saved='Yes' ")or die(mysqli_error($conn));

        //echo 'submitted and validated';
            $Employee_ID_receive = 0;
            $time_received = ""; 
            $sample_quality = ""; 
            $explaination = ""; 
        if (mysqli_num_rows($row_check_if_exists) == 0) {
            //get Specimen

            $checkSpecimenCollection = mysqli_query($conn,"SELECT explaination,sample_quality,Specimen_Name,Employee_ID_receive,time_received,received_status FROM tbl_laboratory_specimen ls JOIN tbl_specimen_results  sp ON ls.Specimen_ID=sp.Specimen_ID WHERE payment_item_ID='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
            $specimen = '';
            $track = 1;
            while ($row = mysqli_fetch_array($checkSpecimenCollection)) {
                if ($track == 1) {
                    $specimen = $row['Specimen_Name'];
                } else {
                    $specimen .=',' . $row['Specimen_Name'];
                    ;
                }
                $received_status = $row['received_status'];
                if($received_status=="received"){
                  $Employee_ID_receive = $row['Employee_ID_receive'];
                  $time_received = $row['time_received'];  
                  $sample_quality = $row['sample_quality'];  
                  $explaination = $row['explaination'];  
                }
                
                $track ++;
            }
            $sql_select_employee_result2=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID_receive'") or die();
            $Employee_Name_receive=mysqli_fetch_assoc($sql_select_employee_result2)['Employee_Name'];
            $data .= '<tr>';
            $data .= '<td rowspan="2"><span style="font-size: x-small;">' . $sn++ . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $product_name . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $specimen . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $TimeSubmitted . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $Employee_Name . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $time_received . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $Employee_Name_receive . '</td>';
//            $data .= '';
//            $data .= '<td><span style="font-size: x-small;">' .  . '</td>';
           // $data .= '<td><span style="font-size: x-small;">' . $Payment_Item_Cache_List_ID . '</td>';
            $data .= '<td><span style="font-size: x-small;">' . $Priority . '</td>';

            $data .= '</tr>';
            $data .= '<tr>';
            $data .= "
                      <td colspan='7'><b style='font-size: x-small;'>SAMPLE QUALITY :-</b><span>$sample_quality</span><b style='color:#FFFFFF'>----</b><b style='font-size: x-small;'>EXPLANATION :-</b><b style='color:#FFFFFF'>----</b><span>$explaination</span></td>
                    ";
//            $data .= "
//                      <td colspan='5'><b>COLLECTED BY :-</b> <span style='font-size: x-small;'>$Employee_Name</span><b style='color:#FFFFFF'>----</b><b>SAMPLE QUALITY :-</b><span>$sample_quality</span><b style='color:#FFFFFF'>----</b><b>EXPLANATION :-</b><b style='color:#FFFFFF'>----</b><span>$explaination</span><b style='color:#FFFFFF'>----</b><br/><br/> <b>RECEIVED BY :-</b> <span style='font-size: x-small;'>$Employee_Name_receive</span> <br/><br/><b>RECEIVED TIME:-</b>$time_received</td>
//                    ";
            $data .= '</tr>';
        }
    }
}
$data .= '</table><br/>';

if (!empty($doctorName)) {
    $data .= '<table width="100%" border="0" class="nobordertable">
                
                <tr>
                    <td width="14%"><b>Consulted By</b></td>
                    <td>' . $doctorName . ', &nbsp;&nbsp;&nbsp;' . date('d, M y', strtotime($doctorDate)) . '</td>
                    <td style="text-align:right"><b>Printed At:</b>&nbsp;&nbsp;&nbsp;' . $myTime . '</td>
                </tr>
            </table>';
}
$my_array=array(
    "category_data"=>$data,
);
array_push($data_array, $my_array);
    }
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
//$mpdf->WriteHTML($data, 2);
foreach ($data_array as $lb_data){
    $category_data=$lb_data['category_data'];
    $mpdf->AddPage();
    $mpdf->WriteHTML($category_data);
}

$mpdf->Output();
exit;
