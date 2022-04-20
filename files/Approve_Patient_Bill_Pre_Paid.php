<?php
	session_start();
	include("./includes/connection.php");
	/**
	*   Process Responce Code
	*   100 Refund required
	*   200 No enough payments to discharge
	*   300 Error occur during the process
	*   400 Credit Bill - No complication
	**/
	$Grand_Total = 0;
	$Grand_Total_Direct_Cash = 0;

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Prepaid_ID'])){
		$Prepaid_ID = $_GET['Prepaid_ID'];
	}else{
		$Prepaid_ID = '';
	}


	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//check if status allows bill to be approved
	$select = mysqli_query($conn,"select Status from tbl_prepaid_details where Prepaid_ID = '$Prepaid_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Status = $data['Status'];
		}
	}else{
		$Status = '';
	}


	
	//process cash patient
	//1.check if figure match or exceed amount required
	$get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_patient_payments pp where 
								Registration_ID = '$Registration_ID' and
								pp.Transaction_status <> 'cancelled' and
								pp.Transaction_type = 'indirect cash' and
								pp.Billing_Type = 'Outpatient Cash' and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Pre_Paid = 1 order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
	$nms = mysqli_num_rows($get_details);
	if($nms > 0){
		while ($row = mysqli_fetch_array($get_details)) {
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount from 
									tbl_items i, tbl_patient_payment_item_list ppl where
									i.Item_ID = ppl.Item_ID and
									ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($items);
			if($nm > 0){
				while ($data = mysqli_fetch_array($items)) {
					$Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
				}
			}
		}
	}

	//2.get direct cash payments
	$cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
						tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
						pp.Transaction_type = 'direct cash' and
						pp.Transaction_status <> 'cancelled' and
						pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						pp.Patient_Bill_ID = '$Patient_Bill_ID' and
						pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nms = mysqli_num_rows($cal);
	if($nms > 0){
		while ($cls = mysqli_fetch_array($cal)) {
			$Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
		}
	}

	if($Grand_Total_Direct_Cash >= $Grand_Total){
		$update = mysqli_query($conn,"update tbl_prepaid_details set Status = 'cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = (select now()) where Prepaid_ID = '$Prepaid_ID'") or die(mysqli_error($conn));
		if($update){
			echo "100";
		}else{
			echo "300";
		}
	}else if($Grand_Total > $Grand_Total_Direct_Cash){
		echo "200"; //more money needed
	}else{
		echo "300";
	}
?>