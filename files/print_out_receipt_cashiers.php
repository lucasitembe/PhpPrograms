<?php
include("includes/connection.php");
$employee_id = $_SESSION['employee_id'];

//$start_data = $_GET['start_date'];
//$end_date = $_GET['end_date'];
$Receipt_Number = $_GET['Receipt_Number'];
$today = Date("Y-m-d");

$sql = "SELECT * FROM tbl_cashier_print_receipt WHERE Patient_Payment_ID='$Receipt_Number'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

$i=1;

$data = '<table style="width:100%; background:#fff">';

while ($row = mysqli_fetch_assoc($result)) {
	$Patient_Payment_ID = $row['Patient_Payment_ID'];
	$Employee_ID=$row['Employee_ID'];
	$Transaction_date=$row['Transaction_date'];

	$data.= '<tr><td style="text-align:center;width:5%;">'.$i++.'</td><td style="text-align:Center;width:7.2%;">'.$Patient_Payment_ID.'</td><td style="text-align:center;width:15.2%;">'.$Transaction_date.'</td><td style="width:44.3%">'.$Employee_ID.'</td>';

	$data.= '</tr>';
}

$data .= '</table>';





echo $data
?>