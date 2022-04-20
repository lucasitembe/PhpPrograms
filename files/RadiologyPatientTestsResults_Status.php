<?php 
	include('includes/connection.php');
	
	isset($_GET['II']) ? $Item_ID =  $_GET['II'] : $Item_ID = 0;
	isset($_GET['Stat']) ? $Status =  $_GET['Stat'] : $Status = '';
	isset($_GET['PPILI']) ? $PPILI =  $_GET['PPILI'] : $PPILI = 0;
	isset($_GET['RI']) ? $RegistrationID =  $_GET['RI'] : $RegistrationID = 0;
	
	$select_extist = "
		SELECT * 
			FROM tbl_radiology_patient_tests
			WHERE
				Registration_ID = '$RegistrationID' AND
				Item_ID = '$Item_ID' AND 
				Patient_Payment_Item_List_ID = '$PPILI'
				";
				
	$select_extist_qry = mysqli_query($conn,$select_extist) or die(mysqli_error($conn));
	
	$count = mysqli_num_rows($select_extist_qry);
	
	if($count > 0){
		while($existing = mysqli_fetch_assoc($select_extist_qry)){
			$RowID = $existing['Radiology_Test_ID'];
		}
		$update_status = "UPDATE tbl_radiology_patient_tests SET Results_Status = '$Status' WHERE Radiology_Test_ID = '$RowID'";
		$update_status_qry = mysqli_query($conn,$update_status) or die(mysqli_error($conn));
		if($update_status_qry){
			$response = "Status Changed";
		} else {
			$response = "Error";
		}
	} else {
		$insert_status = "
		INSERT INTO 
			tbl_radiology_patient_tests(Registration_ID, Item_ID, Patient_Payment_Item_List_ID, Results_Status) 
				VALUES('$RegistrationID', '$Item_ID', '$PPILI', '$Status')";
		$insert_status_qry = mysqli_query($conn,$insert_status) or die(mysqli_error($conn));
		if($insert_status_qry){
			$response = "Status Changed";
		} else {
			$response = "Error";			
		}		
	}
	
	echo $response;
	
?>