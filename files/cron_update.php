<?php
	include("./includes/connection.php");
	$status = '';
	$status1 = '';


	//1. Get Local Payments Data
	$get_local_data = "SELECT * FROM tbl_patient_payments_mobile WHERE Payment_Status = 'pending'";
	$result_local_data = mysqli_query($conn,$get_local_data) or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($result_local_data);

	if($num_rows > 0){
		while($row = mysqli_fetch_assoc($result_local_data)){
			$payment_code = $row['payment_code'];
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$Receipt_Date = $row['Receipt_Date'];
			$Payment_Mode = $row['Payment_Mode'];
			$Payment_Status = $row['Payment_Status'];
			$Registration_ID = $row['Registration_ID'];

			//calculate amount
			$Calculate = mysqli_query($conn,"select sum((Price - Discount)* Quantity) as amount from tbl_patient_payment_item_list_mobile where
										Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($Calculate);
			if($no > 0){
				while($row = mysqli_fetch_array($Calculate)){
					$amount = $row['amount'];
				}
			}


			//2. Get Remote Payments Data
			$remote_payment  = simplexml_load_file("192.168.2.3/api/respond.php?PAYMENTCODE=$payment_code&AMOUNT=$amount");
			$remote_status = $remote_payment->PAYMENTSTATUS;
			$amount_paid = $remote_payment->PAID;

			//3. Compare Remote and Local Data
			if($remote_status == 'paid'){
				$update_local_data = "UPDATE tbl_patient_payments_mobile SET payment_status = 'paid',Amount_Paid = '$amount_paid' 
										WHERE payment_code = '$payment_code'";
				$local_data_updated = mysqli_query($conn,$update_local_data) or die(mysqli_error($conn));

				if($local_data_updated){
					//get transaction details
					$select_transaction = mysqli_query($conn,"select * from tbl_patient_payments_mobile where payment_code = '$payment_code'") or die(mysqli_error($conn));
					$no_of_rows = mysqli_num_rows($select_transaction);
					if($no_of_rows > 0){
						while ($dt = mysqli_fetch_array($select_transaction)) {
							$Registration_ID = $dt['Registration_ID'];
							$Supervisor_ID = $dt['Supervisor_ID'];
							$Employee_ID = $dt['Employee_ID'];
							$Payment_Date_And_Time = $dt['Payment_Date_And_Time'];
							$Claim_Form_Number = $dt['Claim_Form_Number'];
							$Billing_Type = $dt['Billing_Type'];
							$Receipt_Date = $dt['Receipt_Date'];
							$Sponsor_ID = $dt['Sponsor_ID'];
							$Sponsor_Name = $dt['Sponsor_Name'];
							$Folio_Status = $dt['Folio_Status'];
							$Branch_ID = $dt['branch_id'];
						}
					}

					//get the last check in id
					$select_check_in = mysqli_query($conn,"select Check_In_ID from tbl_check_in where
													Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
					$nr = mysqli_num_rows($select_check_in);
					if($nr > 0){
						while ($nrd = mysqli_fetch_array($select_check_in)) {
							$Check_In_ID = $nrd['Check_In_ID'];
						}
					}else{
						$Check_In_ID = 0;
					}
					//if Folio_Status is start, generate folio number
					//start of generate folio number
						$Folio_Number = 1;
					//end of generate folio number

					//update eHMS payment table
					$insert_payment = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
												Registration_ID,Supervisor_ID,Employee_ID,
												Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
												Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,
												branch_id,payment_mode,Check_In_ID)

											VALUES('$Registration_ID','$Supervisor_ID','$Employee_ID',
												(select now()),'$Folio_Number','$Claim_Form_Number',
												'$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),
												'$Branch_ID','mobile','$Check_In_ID')") or die(mysqli_error($conn));
							
					
					if($insert_payment){
						//get patient_payment_id
						$sql_get = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments where 
													Registration_ID = '$Registration_ID' and 
													Employee_ID = '$Employee_ID' 
													order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
						$n_rows = mysqli_num_rows($sql_get);


						if($n_rows > 0){
							while($data = mysqli_fetch_array($sql_get)){
								$Current_Patient_Payment_ID = $data['Patient_Payment_ID'];
							}
							//echo $Current_Patient_Payment_ID; exit();
							//get all items, insert from tbl_patient_payment_item_list_mobile to tbl_patient_payment_item_list
							$select_items_details = mysqli_query($conn,"select * from  tbl_patient_payment_item_list_mobile where
																Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
							$nrows = mysqli_num_rows($select_items_details);
							
							if($nrows > 0){
								while ($data = mysqli_fetch_array($select_items_details)) {
									$Check_In_Type = $data['Check_In_Type'];
									$Item_ID = $data['Item_ID'];
									$Discount = $data['Discount'];
									$Price = $data['Price'];
									$Quantity = $data['Quantity'];
									$Patient_Direction = $data['Patient_Direction'];
									$Consultant = $data['Consultant'];
									$Consultant_ID = $data['Consultant_ID'];
									$Transaction_Date_And_Time = $data['Transaction_Date_And_Time'];

									//insert selected data
									$select_ehms_items = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
															Check_In_Type,Item_ID,Discount,
															Price,Quantity,Patient_Direction,
															Consultant,Consultant_ID,Patient_Payment_ID,
															Transaction_Date_And_Time)
														VALUES('$Check_In_Type','$Item_ID','$Discount',
															'$Price','$Quantity','$Patient_Direction',
															'$Consultant','$Consultant_ID','$Current_Patient_Payment_ID',
															(select now()))") or die(mysqli_error($conn));
								}
							}
						}
						/*if($select_ehms_items){
							$status1 = 'Good';
						} else {
							$status1 = 'NOT OK';
						}*/
						$status = 'OK';
					} else {
						$status = 'ER';			
					}
				}
			}
		}
	} else {
		$status = 'NULL';
	}

	//echo $status1;
	echo $status;
	?>