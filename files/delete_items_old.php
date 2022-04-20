<?php
	include("./includes/connection.php");
	$Control = 0;
	$select = mysqli_query($conn,"select Item_ID from tbl_items where Item_Type = 'Pharmacy'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Item_ID = $data['Item_ID'];


/*			//delete tbl_general_item_price
			$result = mysqli_query($conn,"delete from tbl_general_item_price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_items_balance
			$result = mysqli_query($conn,"delete from tbl_items_balance where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_item_list_cache
			$result = mysqli_query($conn,"delete from tbl_item_list_cache where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_item_price
			$result = mysqli_query($conn,"delete from tbl_item_price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_patient_payment_item_list
			$result = mysqli_query($conn,"delete from tbl_patient_payment_item_list where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_requisition_items
			$result = mysqli_query($conn,"delete from tbl_requisition_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_sponsor_non_supported_items
			$result = mysqli_query($conn,"delete from tbl_sponsor_non_supported_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_purchase_order_items
			$result = mysqli_query($conn,"delete from tbl_purchase_order_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}

			//delete tbl_inpatient_doctorservices_cache
			$result = mysqli_query($conn,"delete from tbl_inpatient_doctorservices_cache where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
			if($result){
				$Control++;
			}	*/
		}

	}
	
	//delete empty receipts
	$select = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments") or die(mysqli_error($conn));
	while($data = mysqli_fetch_array($select)){
		$Patient_Payment_ID = $data['Patient_Payment_ID'];
		$delete = mysqli_query($conn,"delete from tbl_patient_payments where Patient_Payment_ID = '$Patient_Payment_ID'");
		$Control++;
	}

	//delete empty receipts
	$select = mysqli_query($conn,"select Requisition_ID from tbl_requisition") or die(mysqli_error($conn));
	while($data = mysqli_fetch_array($select)){
		$Requisition_ID = $data['Requisition_ID'];
		$delete = mysqli_query($conn,"delete from tbl_requisition where Requisition_ID = '$Requisition_ID'");
		$Control++;
	}


	//tbl_patient_payment
	//tbl_requisition
	//
	echo $Control;
?>