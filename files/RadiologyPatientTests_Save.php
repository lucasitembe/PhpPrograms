<?php 
	require_once('includes/connection.php');
        @session_start();
	$II = 0;
	$RI = 0;
	$PPI = 0;
	$PPILI = 0;
	$theinput = '';
	$inputname = '';
	$sn = 0;
	if(isset($_GET['II'])) $II = $_GET['II'];
	if(isset($_GET['RI'])) $RI = $_GET['RI'];
	if(isset($_GET['PPI'])) $PPI = $_GET['PPI'];
	if(isset($_GET['PPILI'])) $PPILI = $_GET['PPILI'];
	if(isset($_GET['sn'])) $sn = $_GET['sn'];
	if(isset($_GET['theinput'])) $theinput = $_GET['theinput'];
	if(isset($_GET['inputname'])) $inputname = $_GET['inputname'];
	
	//Get the Column name
	switch($inputname){
		case "radi":
			$column = "Radiologist_ID";
			break;
		case "sono":
			$column = "Sonographer_ID";
			break;
		case "clas":
			$column = "Classification";
			break;
		case "rema":
			$column = "Remarks";
			break;
		case "stat":
			$column = "Status";
			break;
		default:
			$column = '';
	}
	
	//Check if the Data exists
	$select_rpt = "SELECT * FROM tbl_radiology_patient_tests WHERE Item_ID = '$II' AND Registration_ID = '$RI'";
	$select_rpt_qry = mysqli_query($conn,$select_rpt) or die(mysqli_error($conn));
	if(mysqli_num_rows($select_rpt_qry) > 0){
		//Row Exists
		$update_rpt = "UPDATE tbl_radiology_patient_tests SET $column = '$theinput', Date_Time = NOW() WHERE Item_ID = '$II' AND Registration_ID = '$RI'";
		$update_rpt_qry = mysqli_query($conn,$update_rpt) or die(mysqli_error($conn));
		if($update_rpt_qry){
			$respond = 'Data updated';
		} else {
			$respond = 'Error';
		}
		
		if($column == 'Status'){
			if($theinput == 'done'){
				//Update Item Status For Payments
				$update_ppil = "
					UPDATE 
						tbl_patient_payment_item_list SET Status = 'Served' ,ServedDateTime=NOW(),ServedBy='".$_SESSION
                                        ['userinfo']['Employee_ID']."'
						WHERE 
							Item_ID = '$II' AND 
							Patient_Payment_ID = '$PPI' AND
							Patient_Payment_Item_List_ID = '$PPILI'
							";
				$update_ppil_qry = mysqli_query($conn,$update_ppil) or die(mysqli_error($conn));
				
				if($update_ppil_qry) {
					$respond .=' - Served'; 
				} else {
					$respond .=' - Not Served';
				}
			}
		}
		
	} else {
		//Row doesn't Exist
		$insert_rpt = "INSERT INTO 
			tbl_radiology_patient_tests($column, Item_ID, Registration_ID, Date_Time, Patient_Payment_Item_List_ID, PatientOrigin) 
				VALUES('$theinput', '$II', '$RI', NOW(), '$PPILI', 'Reception')";
		$insert_rpt_qry = mysqli_query($conn,$insert_rpt) or die(mysqli_error($conn));
		//Check if Data added
		if($insert_rpt_qry){
			$respond = 'Data added';
		} else {
			$respond = 'Error';
		}	

		if($column == 'Status'){
			//$respond .=' - n1';
			if($theinput == 'done'){
				//Update Item Status For Payments
				$update_ppil = "
					UPDATE 
						tbl_patient_payment_item_list SET Status = 'Served' ,ServedDateTime=NOW(),ServedBy='".$_SESSION
                                        ['userinfo']['Employee_ID']."'
						WHERE 
							Item_ID = '$II' AND 
							Patient_Payment_ID = '$PPI' AND
							Patient_Payment_Item_List_ID = '$PPILI'
							";
				$update_ppil_qry = mysqli_query($conn,$update_ppil) or die(mysqli_error($conn));
				if($update_ppil_qry) {
					$respond .=' - Served'; 
				} else {
					$respond .=' - Not Served';
				}
			}
		}
		
	}
	
	echo $respond;
?>