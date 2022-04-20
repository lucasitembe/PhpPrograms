<?php
	session_start();
	include("./includes/connection.php");
	require_once './functions/items.php';
	/**
	*   Process Responce Code
	*   100 refund required
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
	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}
// echo $Folio_Number;
// exit;


	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get Sponsor name
	$select = mysqli_query($conn,"select Guarantor_Name,payment_method from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Guarantor_Name = $data['Guarantor_Name'];
            $Payment_Method = $data['payment_method'];
		}
	}else{
		$Guarantor_Name = '';
        $Payment_Method='';
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}


	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}


	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = 0;
	}
	if(isset($_GET['ward_type'])){
		$ward_type = $_GET['ward_type'];
	}else{
		$ward_type='';
	}
	if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
    	$payments_filter = "payment_type = 'post' and ";
    }else{
    	$payments_filter = '';
    }

	//all status
	$select = mysqli_query($conn,"select Admission_Status, Cash_Bill_Status, Credit_Bill_Status, Discharge_Clearance_Status from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Admission_Status = $data['Admission_Status'];
			$Discharge_Clearance_Status = $data['Discharge_Clearance_Status'];
			$Cash_Bill_Status = $data['Cash_Bill_Status'];
			$Credit_Bill_Status = $data['Credit_Bill_Status'];
		}
	}else{
		$Admission_Status = '';
		$Discharge_Clearance_Status = '';
		$Cash_Bill_Status = '';
		$Credit_Bill_Status = '';
	}
//===========================MORGUE SECTION====================================================================
if($ward_type=='mortuary_ward'){
$morgueDetails=mysqli_query($conn,"SELECT Date_Of_Death,case_type FROM tbl_mortuary_admission WHERE Corpse_ID='$Registration_ID'") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
	if(isset($_GET['Grand_Total'])){
    $Grand_Total=$_GET['Grand_Total'];
	}

		//2.get direct cash payments
		$cal = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount from
							tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where
							pp.Transaction_type = 'direct cash' and
							pp.Transaction_status <> 'cancelled' and
                                                        pp.Billing_Type<>'Outpatient Cash' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							 ppl.Item_ID=i.Item_ID and
                                                         i.Visible_Status='Others' and
							pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		$nms = mysqli_num_rows($cal);
		if($nms > 0){
			while ($cls = mysqli_fetch_array($cal)) {
				$Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
			}
			// echo $Grand_Total;
			// exit;


		 }
	// echo $Grand_Total_Direct_Cash;
	// exit;
		if(isset($_SESSION['systeminfo']['Imbalance_Discharge']) && strtolower($_SESSION['systeminfo']['Imbalance_Discharge']) == 'yes'){
			if(($Grand_Total_Direct_Cash >= $Grand_Total) || ($Grand_Total > $Grand_Total_Direct_Cash)){
				$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					echo "100";
										mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                                        mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					echo "300";
				}
			}else{
				echo "300";
			}
		}else{
			if($Grand_Total_Direct_Cash >= $Grand_Total){
				$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					echo "100";
					mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					echo "300";
				}
			}else if($Grand_Total > $Grand_Total_Direct_Cash){
				echo "200"; //more money needed
			}else{
				echo "300";
			}
		}
	echo $clear_status;
}}
//===========================END OF MORGUE SECTION==================================
else
{
	if($Transaction_Type == 'Cash_Bill_Details'){
		//process cash patient
		//1.check if figure match or exceed amount required
		$get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_patient_payments pp where	Registration_ID = '$Registration_ID' and 	pp.Transaction_status <> 'cancelled' and 	pp.Transaction_type = 'indirect cash' and	$payments_filter 	(pp.Billing_Type = 'Inpatient Cash') and pp.Patient_Bill_ID = '$Patient_Bill_ID' 	  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
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
		$cal = mysqli_query($conn,"SELECT ppl.Price, ppl.Quantity, ppl.Discount from 	tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where pp.Transaction_type = 'Direct cash' and 	pp.Transaction_status <> 'cancelled' and   	pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 	pp.Patient_Bill_ID = '$Patient_Bill_ID' and 	ppl.Item_ID=i.Item_ID and   i.Visible_Status='Others' and 	pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		$nms = mysqli_num_rows($cal);
		
		if($nms > 0){
			while ($cls = mysqli_fetch_array($cal)) {
				$Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
			}
			// echo $Grand_Total;
			// exit;
		}

		if(isset($_SESSION['systeminfo']['Imbalance_Discharge']) && strtolower($_SESSION['systeminfo']['Imbalance_Discharge']) == 'yes'){
			if(($Grand_Total_Direct_Cash >= $Grand_Total) || ($Grand_Total > $Grand_Total_Direct_Cash)){
				$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					echo "100";
						mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					echo "300";
				}
			}else{
				echo "300";
			}
		}else{
			if($Grand_Total_Direct_Cash >= $Grand_Total){
				$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' , Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					echo "100";
						mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					echo "300";
				}
			}else if($Grand_Total > $Grand_Total_Direct_Cash){
				echo "200"; //more money needed
			}else{
				echo "300";
			}
		}

	}else{
		//process depends on transaction type
		if($Transaction_Type == 'Cash_Bill_Details'){
			$get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_patient_payments pp where
								Registration_ID = '$Registration_ID' and
								pp.Transaction_status <> 'cancelled' and
								pp.Transaction_type = 'indirect cash' and

								$payments_filter
								(pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post') and
								pp.Patient_Bill_ID = '$Patient_Bill_ID'
								  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
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
								tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_items i where
								pp.Transaction_type = 'direct cash' and
								pp.Transaction_status <> 'cancelled' and
                                                                pp.Billing_Type<>'Outpatient Cash' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                                                ppl.Item_ID=i.Item_ID and
                                                                i.Visible_Status='Others' and
								pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$nms = mysqli_num_rows($cal);
			if($nms > 0){
				while ($cls = mysqli_fetch_array($cal)) {
					$Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
				}
			}

			if(isset($_SESSION['systeminfo']['Imbalance_Discharge']) && strtolower($_SESSION['systeminfo']['Imbalance_Discharge']) == 'yes'){
				if(($Grand_Total_Direct_Cash >= $Grand_Total) || ($Grand_Total > $Grand_Total_Direct_Cash)){
					if(strtolower($Credit_Bill_Status) == 'cleared'){
						$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
						echo "100";
						mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
					}else{
						$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Cash_Clearer_ID = '$Employee_ID', Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
					}
				}else{
					echo "300";
				}
			}else{
				if($Grand_Total_Direct_Cash >= $Grand_Total){
					if(strtolower($Credit_Bill_Status) == 'cleared'){
						$update = mysqli_query($conn,"UPDATE tbl_admission set Cash_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID', Clearance_Date_Time = NOW(), Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
						echo "100";
						mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
					}else{
						$update = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Cash_Clearer_ID = '$Employee_ID', Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
					}
				}else if($Grand_Total > $Grand_Total_Direct_Cash){
					echo "200";
				}else{
					echo "300";
				}
			}
		}else{
			//credit patient


			//clear cash bill if no cash transactios found
			//Start
			$get_dets = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_patient_payments pp where Registration_ID = '$Registration_ID' and
										pp.Transaction_status <> 'cancelled' and
										pp.Transaction_type = 'indirect cash' and
										pp.payment_type = 'post' and
										pp.Billing_Type = 'Inpatient Cash' and
										pp.Patient_Bill_ID = '$Patient_Bill_ID'
										  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
			$nmz = mysqli_num_rows($get_dets);
			if($nmz < 1 && strtolower($Cash_Bill_Status) != 'cleared'){
				$upd = mysqli_query($conn,"update tbl_admission set Cash_Bill_Status = 'cleared', Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID', Cash_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($upd){
					$Cash_Bill_Status = 'cleared';
					mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}
			}

			//End
			$clear_status ="";
			if(strtolower($Cash_Bill_Status) == 'cleared'){
				$update = mysqli_query($conn,"update tbl_admission set Credit_Bill_Status = 'cleared', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Credit_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					$clear_status = "400";
					mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					$clear_status = "300";
				}
			}else{
				$update = mysqli_query($conn,"update tbl_admission set Credit_Bill_Status = 'cleared', Credit_Clearer_ID = '$Employee_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
				if($update){
					$clear_status = "400";
					mysqli_query($conn,"UPDATE tbl_prepaid_details SET Status='cleared', Cleared_By = '$Employee_ID', Cleared_Date_Time = NOW() WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
				}else{
					$clear_status = "300";
				}
			}
			if($clear_status == "400"){
				//calulating bill grand total

				$get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time from tbl_patient_payments pp where
									Registration_ID = '$Registration_ID' and
									pp.Transaction_status <> 'cancelled' and
									pp.Transaction_type = 'indirect cash' and
									$payments_filter
									pp.Billing_Type = 'Inpatient Credit'  and
									pp.Patient_Bill_ID = '$Patient_Bill_ID'
									  order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
				$nms = mysqli_num_rows($get_details);
				if($nms > 0){
					$Grand_Total = 0;
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
					//posting data to accounting system
					$Product_Array=array();
	                $Product_Name_Array = array(
	                   'ref_no'=> $Patient_Bill_ID,
	                    'source_name' => 'ehms_sales_cash',
	                    'comment' => 'Bill # ' . $Patient_Bill_ID . ", Date:" . date('Y-m-d') . ", Trans_Type: , Amount:  " . number_format($_GET['Grand_Total'],2) . " Tsh.",
	                    'debit_entry_ledger' => $Guarantor_Name,
	                    'credit_entry_ledger' => 'SALES',
	                    'sub_total' => $_GET['Grand_Total'],
	                    'source_id' => $Patient_Bill_ID,
	                    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
	                    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
	                );

	                array_push($Product_Array, $Product_Name_Array);

	               $acc = gAccJournalEntry(json_encode($Product_Array));
					//end of posting data to accounting system
				}
				//end of calculating bill grand total
			}

			echo $clear_status;
		}//end of check credit patient

	}
}
?>
