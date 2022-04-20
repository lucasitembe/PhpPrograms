<?php 
include("./includes/connection.php");
	if (isset($_POST['payment_id'])) {
		$payment_id = $_POST['payment_id'];
		$receipt_count = 0;
		$select_receipt_count = "SELECT receipt_count FROM tbl_patient_payments 
		WHERE Patient_Payment_ID = '$payment_id'";

		if ($select_result = mysqli_query($conn,$select_receipt_count)) {
			while ($row = mysqli_fetch_assoc($select_result)) {
				$receipt_count = $row['receipt_count'];
			}

			//update receipt count 
			$receipt_max_count = 0;

			$query = "SELECT max_receipt FROM tbl_receipt_config";
			
			$select_receipt_max_count = mysqli_query($conn,$query);

			if ($select_receipt_max_count) {

				while ($rowc = mysqli_fetch_assoc($select_receipt_max_count)) {
					$receipt_max_count = $rowc['max_receipt'];
				}


			 	if ($receipt_count < $receipt_max_count) {
			 		echo "true";
			 	}
			 	else{
			 		echo "false";
			 	}
			 	}else{
			 		echo mysqli_error($conn);
			 	}
			 }else{
			 	echo mysqli_error($conn);
			 } 

			}

 ?>