<?php 
require_once('includes/connection.php');

	$price = 0;
	$amount = 0;
	$itemID = 0;
	$qty = 0;
	$reg_ID = 0;
	$emp_ID = 0;
	$thedate = date('Y-m-d H:i:s');
	$cons_ID = 0;
	$folio = 0;
	$spID = 0;
	$branch_id = 0;
	$spName = '';
	$billType = '';


if(isset($_GET['price'])){
	$price = $_GET['price'];
}
if(isset($_GET['itemID'])){
	$itemID = $_GET['itemID'];
}
if(isset($_GET['amount'])){
	$amount = $_GET['amount'];
}
if(isset($_GET['qty'])){
	$qty = $_GET['qty'];
}
if(isset($_GET['reg_ID'])){
	$reg_ID = $_GET['reg_ID'];
}
if(isset($_GET['emp_ID'])){
	$emp_ID = $_GET['emp_ID'];
}
if(isset($_GET['cons_ID'])){
	$cons_ID = $_GET['cons_ID'];
}
if(isset($_GET['folio'])){
	$folio = $_GET['folio'];
}
if(isset($_GET['spID'])){
	$spID = $_GET['spID'];
}
if(isset($_GET['spName'])){
	$spName = $_GET['spName'];
}
if(isset($_GET['billType'])){
	$billType = $_GET['billType'];
}
if(isset($_GET['branchID'])){
	$branch_id = $_GET['branchID'];
}
	
	$insert_docservices = "INSERT INTO 
		tbl_inpatient_doctorservices_cache(
			Item_ID,
			Price,
			Quantity,
			Amount,
			Registration_ID,
			Employee_ID,
			consultation_id,
			Date_Time,
			Folio_Number,
			Sponsor_ID,
			Sponsor_Name,
			Billing_Type,
			branch_id)
			VALUES(
				'$itemID',
				'$price',
				'$qty',
				'$amount',
				'$reg_ID',
				'$emp_ID',
				'$cons_ID',
				'$thedate',
				'$folio',
				'$spID',
				'$spName',
				'$billType',
				'$branch_id'
			)
		";	
		
		$insert_docservices_qry = mysqli_query($conn,$insert_docservices) or die(mysqli_error($conn));
		
		if($insert_docservices_qry){
			echo 1;
		} else {
			echo 0;		
		}
		
/* 	echo "Price: ".$price."<br/>";
	echo "itemID: ".$itemID."<br/>";
	echo "Amount: ".$amount."<br/>";
	echo "Cons_ID: ".$cons_ID."<br/>";
	echo "Folio: ".$folio."<br/>";
	echo "Sponsor: ".$spName."<br/>";
	echo "Bill: ".$billType."<br/>"; */

?>