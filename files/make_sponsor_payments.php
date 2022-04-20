<?php
include("./includes/connection.php");
session_start();
if($_POST['datas']==='yes'){
$customer_ID=mysqli_real_escape_string($conn,$_POST['customerID']);
$Employee_ID=mysqli_real_escape_string($conn,$_POST['Supervisor']);
$cheque_no=mysqli_real_escape_string($conn,$_POST['cheque_no']);
$narration=mysqli_real_escape_string($conn,$_POST['narration']);
//$Amount=mysqli_real_escape_string($conn,$_POST['Amount']);
//$Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
//$Item_Name=mysqli_real_escape_string($conn,$_POST['Item_Name']);
//$Item_ID=mysqli_real_escape_string($conn,$_POST['Item_ID']);
//$Item_Description=mysqli_real_escape_string($conn,$_POST['Item_Description']);
$customerType=mysqli_real_escape_string($conn,$_POST['customerType']);
$Receipt_Date=date('Y-m-d');
$branch_id=$_SESSION['supervisor']['Branch_ID'];
$Supervisor_ID=$_SESSION['supervisor']['Employee_ID'];
$remarks='';
if($cheque_no===''){
	$cheque_no='';
}
if($customerType == 'SPONSOR'){
	$remarks='voucher_payment';
}
//$test_payments=mysqli_query($conn,"SELECT Payment_ID FROM tbl_other_sources_payments WHERE Customer_ID=$customer_ID AND Employee_ID=$Employee_ID AND Receipt_Date='$Receipt_Date'")
$no = mysqli_num_rows(mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$customer_ID'"));
if($no >0){
$query=mysqli_query($conn,"INSERT INTO tbl_other_sources_payments 
	(Customer_ID,Supervisor_ID,Employee_ID,
	Payment_Date_And_Time,Billing_Type,Receipt_Date,
	Transaction_status,	Transaction_type,payment_type,
	Fast_Track,branch_id,Billing_Process_Status,customer_type,cheque_number,narration) 
	VALUES($customer_ID,$Supervisor_ID,$Employee_ID,
	(SELECT NOW()),'Cash','$Receipt_Date',
	'active','Direct cash','pre',
	'0',$branch_id,'','$customerType','$cheque_no','$narration'

	)")or die(mysqli_error($conn));
if($query){
	$select_cache = mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$customer_ID'") or die(mysqli_error($conn));
	while($row=mysqli_fetch_assoc($select_cache)){
		extract($row);
		$result=mysqli_query($conn,"INSERT INTO tbl_other_sources_payment_item_list
		(Category,Item_ID,Item_Name,Item_Description,Discount,Price,Quantity,Status,Payment_ID,Transaction_Date_And_Time,Sub_Department_ID,remarks)
		VALUES('direct cash',$Item_ID,(SELECT Product_Name FROM tbl_items WHERE Item_ID=$Item_ID),'$Item_Description',0,$Amount,$Quantity,' ',(SELECT Payment_ID FROM tbl_other_sources_payments ORDER BY Payment_ID DESC LIMIT 1),(SELECT NOW()),' ','$remarks') ") or die(mysqli_error($conn));
		if($result){
			mysqli_query($conn,"DELETE FROM tbl_direct_cash_cache WHERE  Employee_ID = '$Employee_ID' and Registration_ID = '$customer_ID' AND Item_ID=$Item_ID AND Quantity=$Quantity");
		}
	
	}
	$payment=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_ID FROM tbl_other_sources_payments ORDER BY Payment_ID DESC LIMIT 1"))['Payment_ID'];
	echo $payment;
}
}else{
	echo "Add Item First";
}
}else{
	echo 'here';
}
?>