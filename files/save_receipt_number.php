<?php
include("./includes/connection.php"); 
if(isset($_POST['receiptNumber']) && isset($_POST['employee_id'])){
 $max_receipt=$_POST['receiptNumber'];  
 $Employee_ID = $_POST['employee_id'];
 // $Passsword=$_POST['Passsword'];
 // check if there is any data
$selecet_max_number_of_receipt = "SELECT max_receipt FROM tbl_receipt_config";
if ($select_result = mysqli_query($conn,$selecet_max_number_of_receipt)) {
	
	if (mysqli_num_rows($select_result) > 0) {
$audit_reason = $Employee_ID . " Changed the maximum receipt print number to ". $max_receipt ;
 $update = "UPDATE tbl_receipt_config SET max_receipt ='$max_receipt', employee_id='$Employee_ID'";
 if ($update_result = mysqli_query($conn,$update)) {
 	$insert_audit = "INSERT INTO tbl_receipt_update_audit(employee_id,audit_reasons,date_time) 
 	VALUES('$Employee_ID','$audit_reason',NOW())";
 	if ($insert_result = mysqli_query($conn,$insert_audit)) {
 		echo "Successfully updated";	
 	}else{
 		echo "Fail to update . " . mysqli_error($conn);
 	}
 	
 }else{
 	echo "fail to update";
 }

}else{

$audit_reason = $Employee_ID . " Changed the maximum receipt print number to ". $max_receipt ;
 $update = "INSERT INTO  tbl_receipt_config(max_receipt,employee_id) VALUES('$max_receipt','$Employee_ID') ";

 if ($update_result = mysqli_query($conn,$update)){
 	$insert_audit = "INSERT INTO tbl_receipt_update_audit(employee_id,audit_reasons,date_time) 
 	VALUES('$Employee_ID','$audit_reason',NOW())";
 	if ($insert_result = mysqli_query($conn,$insert_audit)) {
 		echo "Successfully updated";	
 	}else{
 		echo "Fail to update . " . mysqli_error($conn);
 	}
 	
 }else{
 	echo "fail to update";
 }

}

}



 
}




 ?>
