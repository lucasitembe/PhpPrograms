<?php
  include("./includes/connection.php");


if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
$sql =mysqli_query($conn,"INSERT INTO tbl_laboratory_results_validation ( Patient_Payment_Result_ID, Validation_Datetime, Employee_ID,Remarks,Laboratory_Parameter_ID)
							 VALUES ('".filter_input(INPUT_GET , 'Patient_Payment_Result_ID')."', (SELECT NOW()), '".filter_input(INPUT_GET, 'Employee_ID')."','','".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."' ) ");

if($sql){
	echo 'Validated';
}
}else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){

	$sql =mysqli_query($conn,"INSERT INTO  tbl_laboratory_results_validation_cache ( Patient_Cache_Results_ID, Validation_Datetime, Employee_ID,Remarks,Laboratory_Parameter_ID)
							 VALUES ('".filter_input(INPUT_GET , 'Patient_Payment_Result_ID')."', (SELECT NOW()), '".filter_input(INPUT_GET, 'Employee_ID')."','','".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."' ) ");

if($sql){
	echo 'Validated';
}
	}