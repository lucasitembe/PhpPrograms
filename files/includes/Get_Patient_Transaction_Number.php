<?php
	include("./includes/connection.php");
	//get the last bill id
	$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID'  AND Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
	$nums = mysqli_num_rows($select);
	if($nums > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Patient_Bill_ID = $row['Patient_Bill_ID'];
		}
	}else{
		//insert data to tbl_patient_bill
		$insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
		if($insert){
			$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
			$nums = mysqli_num_rows($select);
			while ($row = mysqli_fetch_array($select)) {
				$Patient_Bill_ID = $row['Patient_Bill_ID'];
			}
		}
	}
?>