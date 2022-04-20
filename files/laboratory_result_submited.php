<?php
  include("./includes/connection.php");
$Submission_Date_And_Time = date("Y-m-d H:i:s");
if(filter_input(INPUT_GET, 'Status_From') == 'payment'){

$sql =mysqli_query($conn,"INSERT INTO tbl_laboratory_results_submition ( Patient_Payment_Result_ID, Submition_Datetime, Employee_ID,Remarks,Laboratory_Parameter_ID)
							 VALUES ('".filter_input(INPUT_GET , 'Patient_Payment_Result_ID')."', '$Submission_Date_And_Time', '".filter_input(INPUT_GET, 'Employee_ID')."','','".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."' ) ");


if($sql){
	echo 'Submited';
}

}else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){

$sql =mysqli_query($conn,"INSERT INTO tbl_laboratory_results_submition_cache ( Patient_Cache_Results_ID, Submition_Datetime, Employee_ID,Remarks,Laboratory_Parameter_ID)
							 VALUES ('".filter_input(INPUT_GET , 'Patient_Payment_Result_ID')."', '$Submission_Date_And_Time', '".filter_input(INPUT_GET, 'Employee_ID')."','','".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."' ) ");


if($sql){
	echo 'Submited';
}

}



//run the query to update the item status
if($_GET['Registration_ID'] != ''){
	$Registration_ID=$_GET['Registration_ID'];
}else{
	$Registration_ID='';
}

if($_GET['Patient_Payment_ID'] != ''){
	$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}else{
	$Patient_Payment_ID='';
}

if($_GET['Item_ID'] != ''){
	$Item_ID=$_GET['Item_ID'];
}else{
	$Item_ID='';
}
if($_GET['Item_ID'] != ''){
	$Item_ID=$_GET['Item_ID'];
}else{
	$Item_ID='';
}

//GET Patient_Payment_Item_List_ID FOR UPDATE
if($_GET['Patient_Payment_Item_List_ID'] != ''){
	$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
	$Patient_Payment_Item_List_ID='';
}





if($_GET['Status_From'] == 'payment'){
	$qr=mysqli_query($conn,"UPDATE tbl_patient_payment_item_list ppl SET ppl.Process_Status='Result' WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");
}else{
	$qr=mysqli_query($conn,"UPDATE tbl_item_list_cache ilc SET ilc.Process_Status='Result' WHERE Payment_Item_Cache_List_ID='$Patient_Payment_Item_List_ID'");
}