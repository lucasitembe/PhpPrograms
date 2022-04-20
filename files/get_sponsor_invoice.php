<?php
include("./includes/connection.php");

$result=mysqli_query($conn,"SELECT `sponsor`,`Invoice_ID`,`Invoice_Number`, date(transaction_date) AS trans_date, SUM(amount) AS amount FROM `tbl_invoice` GROUP BY `sponsor`, date(transaction_date) ORDER BY `amount` DESC LIMIT 10");
//$result = mysqli_query($conn,"SELECT * FROM `tbl_invoice` ");

$data=array();



while ($jibu = mysqli_fetch_array($result)) {

	$data2=array(
		'id'=>$jibu['Invoice_ID'],
		'number'=>$jibu['Invoice_Number'],
		'amount'=>$jibu['amount'],
		'trans_date'=>$jibu['trans_date'],
		'sponsor'=>$jibu['sponsor'],
		);

	array_push($data,$data2);

		

	}

	echo json_encode($data);













