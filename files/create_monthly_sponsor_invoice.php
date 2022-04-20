<?php 
	//@session_start();
	include("./includes/connection.php");

	$month = $_POST['month'];
	$Employee_ID = $_POST['Employee_ID'];
	$year = $_POST['year'];
	$sponsor_id = $_POST['sponsor_id'];
	$narration = $_POST['narration'];
	$total_bill_amount = $_POST['total_bill_amount'];

	$message ='';
	if(empty(trim($narration))){
		$message = "WRITE NARRATION";
	}
	if(empty(trim($year))){
		$message = "SELECT YEAR";
	}
	if(empty(trim($month))){
		$message = "SELECT MONTH";
	}
	if(empty(trim($Employee_ID))){
		$message = "NO VALID EMPLOYEE";
	}

	$select_invoice = mysqli_query($conn,"SELECT Invoice_ID FROM tbl_invoice WHERE invoice_month = '$month' AND invoice_year = '$year' AND sponsor_id = '$sponsor_id'");
	if(mysqli_num_rows($select_invoice) > 0){
		$message = "THIS INVOICE ALREADY CREATED";
	}else{
	if(empty($message)){
		$results = mysqli_query($conn,"SELECT sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i, tbl_item_subcategory isub, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b, tbl_item_category ic WHERE i.Item_Subcategory_ID = isub.Item_Subcategory_ID AND ic.Item_Category_ID = isub.Item_Category_ID AND i.Item_ID = ppl.Item_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$month' AND YEAR(ci.Visit_Date) = '$year' AND b.e_bill_delivery_status = 1 ") or die(mysqli_error($conn));
		$amount = mysqli_fetch_assoc($results)['Total_Amount'];
		//echo $employee_id.', '.$month.', '.$year.', '.$sponsor_id.', '.$narration;
		//echo $amount;

		if($amount > 0){
		$sql = "INSERT INTO tbl_invoice (invoice_date,amount,sponsor_id,employee_id,invoice_month,invoice_year,narration) VALUES ( CURDATE(),'$total_bill_amount', '$sponsor_id', '$Employee_ID','$month','$year','$narration')";
		$query = mysqli_query($conn,$sql) or die(mysqli_error($conn));

		if(mysql_affected_rows()>0){
			$message = 'INVOICE CREATED SUCCESSIFULLY';
		}else{
			$message = 'FAIL TO CREATE INVOICE';
		}
		}else{
			$message = "NO TRANSACTION IN THIS MONTH";
		}
	}

}
echo $message;
 ?>
