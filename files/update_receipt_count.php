<?php 
include("./includes/connection.php");
	if (isset($_POST['payment_id'])) {
		$payment_id = $_POST['payment_id'];

		$Sselect_receipt_count = "SELECT receipt_count FROM tbl_patient_payments 
		WHERE Patient_Payment_ID = '$payment_id'";

		if ($select_result = mysqli_query($conn,$Sselect_receipt_count)) {
			while ($row = mysqli_fetch_assoc($select_result)) {
				$receipt_count = $row['receipt_count'];
			}

			//update receipt count 
			$receipt_count = $receipt_count + 1;
			if ( ($update_receipt_count = mysqli_query($conn,"UPDATE tbl_patient_payments 
						SET receipt_count='$receipt_count' WHERE Patient_Payment_ID ='$payment_id' "))){
			 	echo "successfullt updated " .$receipt_count;
			 	}else{ 
			 		echo mysqli_error($conn);
			 	}
			 } else{
			 	echo mysqli_error($conn);
			 }

		}

 ?>