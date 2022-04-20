<?php
include('includes/connection.php');

$employee_id = $_SESSION['employee_id'];

$today = Date("Y-m-d");

$sql = "SELECT pc.Registration_ID,ilc.Item_ID,ilc.Approved_By,ilc.Approval_Date_Time FROM tbl_payment_cache pc JOIN `tbl_item_list_cache` ilc ON pc.Payment_Cache_ID = ilc.Payment_Cache_ID WHERE ilc.Category='indirect cash' ORDER BY `ilc`.`Approved_By` DESC LIMIT 20";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$i=1;

$data =  "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$data .= '<table style="width:100%">';
$data.= '<tr style="width:1px solid grey"><td><b>SN</b></td><td style="text-align:center"><b>Patientt Name</b></td><td style="text-align:center;"><b>Reg#</b></td><td style="text-align:center"><b>Approved Time</b></td><td><b>Items Approved</b></td><td><b>Approved By</b></td></tr>';
while ($row = mysqli_fetch_assoc($result)) {
	$registration_id = $row['Registration_ID'];
	$patient_name = getPAtientName($row['Registration_ID']) . '<br />';
	$approver_name = getApprovalName($row['Approved_By']);
	$approve_date_and_time = $row['Approval_Date_Time'];
	$item_name = getItemName($row['Item_ID']);

	$data.= '<tr ><td style="text-align:center;width:10%;">'.$i++.'</td><td style="width:15%">'.$patient_name.'</td><td>'.$registration_id.'</td><td style="text-align:center">'.$approve_date_and_time.'</td><td style="width:25%">'.$item_name.'</td><td>'.$approver_name.'</td></tr>';
}

$data .= '</table>';
function getPAtientName($registration_id){
	$sql = "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$registration_id'";
	$result = mysqli_query($conn,$sql);
	while ($row = mysqli_fetch_assoc($result)) {
		extract($row);
	}

	return $Patient_Name;
}

function getItemName($item_id){
	$sql = "SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id'";
	$result = mysqli_query($conn,$sql);
	while ($row = mysqli_fetch_assoc($result)) {
		extract($row);
	}

	return $Product_Name;	
}

function getApprovalName($Approved_By){
	$sql = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Approved_By'";
	$result = mysqli_query($conn,$sql);
	while ($row = mysqli_fetch_assoc($result)) {
		extract($row);
	}

	return $Employee_Name;
}




include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4-L');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();
?>