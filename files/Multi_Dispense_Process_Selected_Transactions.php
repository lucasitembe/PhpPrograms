<?php
	session_start();
	include("./includes/connection.php");

	//Get Employee_ID & Branch_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
	
    //Get Sub_Department_ID
    if (isset($_SESSION['Pharmacy_ID'])) {
	    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	} else {
	    $Sub_Department_ID = 0;
	}

	//---get supervisor id 
	if (isset($_SESSION['Pharmacy_Supervisor'])) {
		$Supervisor_ID = $_SESSION['Pharmacy_Supervisor']['Employee_ID'];
	}else{
		$Supervisor_ID = $Employee_ID;
	}
	//end of fetching supervisor id


	$select = mysqli_query($conn,"select Dispense_Cache_ID, Transaction_Type, mdc.Registration_ID, Payment_Cache_ID, pr.Sponsor_ID from
							tbl_multi_dispense_cache mdc, tbl_patient_registration pr where
							mdc.Employee_ID = '$Employee_ID' and
							mdc.Sub_Department_ID = '$Sub_Department_ID' and
							mdc.Registration_ID = pr.Registration_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Dispense_Cache_ID = $data['Dispense_Cache_ID'];
			$Transaction_Type = $data['Transaction_Type'];
			$Registration_ID = $data['Registration_ID'];
			$Payment_Cache_ID = $data['Payment_Cache_ID'];
			$Sponsor_ID = $data['Sponsor_ID'];

			//Get patient details
			$slct = mysqli_query($conn,"select pc.Billing_Type, ilc.Status from tbl_item_list_cache ilc, tbl_payment_cache pc where
									pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
									ilc.Transaction_Type = '$Transaction_Type' and
									pc.Payment_Cache_ID = '$Payment_Cache_ID' and
									pc.Registration_ID = '$Registration_ID' and
									ilc.Status in('paid', 'approved') and
									ilc.Sub_Department_ID = '$Sub_Department_ID' limit 1") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($slct);
			if($nm > 0){
				while ($row = mysqli_fetch_array($slct)) {
					$Status = $row['Status'];
			        //Generate Billing_Type
			        if(strtolower(substr($row['Billing_Type'], 0, 3)) == 'inp'){
			        	$B_Type = 'Inpatient '.$Transaction_Type;
			        }else{
			        	$B_Type = 'Outpatient '.$Transaction_Type;
			        }
					
					if((strtolower($B_Type) == 'outpatient credit' || strtolower($B_Type) == 'inpatient credit') && strtolower($Status) == 'approved'){
						$P_Process = 'yes'; //Payments allowed
					}else{
						$P_Process = 'no'; //Payment Not allowed
					}

					$get_details = mysqli_query($conn,"select ilc.Quantity, ilc.Edited_Quantity, ilc.Item_ID, 
												ilc.Payment_Item_Cache_List_ID, Consultant, Consultant_ID, Price, Discount
												from tbl_item_list_cache ilc where
												ilc.Sub_Department_ID = '$Sub_Department_ID' and
												ilc.Status = '$Status' and
												ilc.check_In_type = 'Pharmacy' and
												ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
												ilc.Transaction_Type = '$Transaction_Type'") or die(mysqli_error($conn));
					$nmz = mysqli_num_rows($get_details);
					if($nmz > 0){
						while ($detail = mysqli_fetch_array($get_details)) {
							$Item_ID = $detail['Item_ID'];
							$Payment_Item_Cache_List_ID = $detail['Payment_Item_Cache_List_ID'];
							$Consultant = $detail['Consultant'];
							$Consultant_ID = $detail['Consultant_ID'];
							$Price = $detail['Price'];
							$Discount = $detail['Discount'];

							if($detail['Edited_Quantity'] > 0){
								$Qty = $detail['Edited_Quantity'];
							}else{
								$Qty = $detail['Quantity'];
							}

							//Get Item Balance
							$bl = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
							$nn = mysqli_num_rows($bl);
							if($nn > 0){
								$result =  mysqli_fetch_assoc($bl);
								$Balance = $result['Item_Balance'];
							}else{
								$Balance = 0;
								mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
							}

							//Dispense process
							if($Balance >= $Qty || $_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance'] == 'yes'){
								//Payment process if required
								if($P_Process == 'yes'){
									include("./includes/Get_Patient_Check_In_Id.php");
									include("./includes/Get_Patient_Transaction_Number.php");
									$slc = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where 
								                            Registration_ID = '$Registration_ID' and 
								                            Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
									$no_slc = mysqli_num_rows($slc);
									if($no_slc > 0){
										while($rw = mysqli_fetch_array($slc)){
											$Folio_Number = $rw['Folio_Number'];
											$Claim_Form_Number = $rw['Claim_Form_Number'];
										}
										if($Folio_Number == null || $Folio_Number == ''){
											include("./includes/Folio_Number_Generator.php");
										}

									}else{
										$Claim_Form_Number = '';
										include("./includes/Folio_Number_Generator.php");
									}

									$insert = mysqli_query($conn,"insert into tbl_patient_payments(
															Registration_ID,Supervisor_ID,Employee_ID,
															Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
															Sponsor_ID,Billing_Type,
															Receipt_Date,Branch_ID,Check_In_ID,Patient_Bill_ID)
                                    
                											values('$Registration_ID','$Supervisor_ID','$Employee_ID',
															(select now()),'$Folio_Number','$Claim_Form_Number',
															'$Sponsor_ID','$B_Type',
															(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID')") or die(mysqli_error($conn));
									if($insert){
										$get_det = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where
																Registration_ID = '$Registration_ID' and Supervisor_ID = '$Supervisor_ID' and
																Employee_ID = '$Employee_ID' order by patient_payment_id desc limit 1") or die(mysqli_error($conn));
										$nm_det = mysqli_num_rows($get_det);
										if($nm_det > 0){
											while ($dte = mysqli_fetch_array($get_det)) {
												$Patient_Payment_ID = $dte['Patient_Payment_ID'];
												$Payment_Date_And_Time = $dte['Payment_Date_And_Time'];
											}
										}else{
											$Patient_Payment_ID = NULL;
											$Payment_Date_And_Time = '0000/00/00';
										}
									}else{
										$Patient_Payment_ID = NULL;
										$Payment_Date_And_Time = '0000/00/00';
									}
									$P_Process = 'nop'; //Terminate Payment Process
								}
						        //end of payment process if done

						        //Update status
								if($P_Process == 'nop'){
						        	$result = mysqli_query($conn,"update tbl_item_list_cache set status = 'dispensed', Dispensor='$Employee_ID',
				                                            Patient_Payment_ID = '$Patient_Payment_ID', Dispense_Date_Time = (select now()),
				                                            Payment_Date_And_Time = '$Payment_Date_And_Time' 
				                                            where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
								}else{
									$result = mysqli_query($conn,"update tbl_item_list_cache set status = 'dispensed', Dispensor = '$Employee_ID',
                                            				Dispense_Date_Time = (select now()) where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
									//Get receipt number to support ledger
									if($result){
										$sl = mysqli_query($conn,"select Patient_Payment_ID from tbl_item_list_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
										$mn = mysqli_num_rows($sl);
										if($mn > 0){
											while ($mnz = mysqli_fetch_array($sl)) {
												$Patient_Payment_ID = $mnz['Patient_Payment_ID'];
											}
										}else{
											$Patient_Payment_ID = NULL;
										}
									}
								}
						       	if($result){
						       		if($P_Process == 'nop'){
						       			$insert_Item = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
																	Check_In_Type, Item_ID, Discount,
																	Price, Quantity, Patient_Direction,
																	Consultant, Consultant_ID, Patient_Payment_ID,Transaction_Date_And_Time)
																	values(
																	'Pharmacy','$Item_ID','$Discount',
																	'$Price','$Qty','others',
																	'$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()))") or die(mysqli_error($conn));
						       		}

						       		//get last balance
				                    $slct = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
				                    $nm = mysqli_num_rows($slct);
				                    if($nm > 0){
				                        while ($dt = mysqli_fetch_array($slct)) {
				                            $Pre_Balance = $dt['Item_Balance'];
				                        }
				                    }else{
				                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
				                        $Pre_Balance = 0;
				                    }

						       		//update sub department balance
			                        $upd = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance - '$Qty') where
			                                        	Item_ID = '$Item_ID' and
			                                        	Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                        			if($upd){
			                            //insert data into tbl_stock_ledger_controler for auditing
			                            $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
			                                                            Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,
			                                                            Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
			                                                    values('$Item_ID','$Sub_Department_ID','Dispensed','$Registration_ID',
			                                                                '$Pre_Balance',($Pre_Balance - $Qty),(select now()),(select now()),'$Patient_Payment_ID')") or die(mysqli_error($conn));
			                        }
						       }
							}
						}				
					}
				}
			}
		}
		mysqli_query($conn,"delete from tbl_multi_dispense_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	}
?>