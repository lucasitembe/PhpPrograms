<?php
	session_start();
	//include("./includes/connection.php");
	//include("C:\xampp\htdocs\Final_One\files\includes\connection.php");
	// 1. Create a database connection
	$connection = mysql_connect("127.0.0.1","root","");
	if (!$connection) {
		die("Database connection failed: " . mysqli_error($conn));
	}
	
	// 2. Select a database to use
	//$db_select = mysql_select_db("amana_new_2015_07_27",$connection);
	$db_select = mysql_select_db("final_one",$connection);
	//$db_select = mysql_select_db("amana_new_2015_08_31",$connection);
	if (!$db_select) {
		die("Database selection failed: " . mysqli_error($conn));
	}

	//get employee
	$select = mysqli_query($conn,"select emp.Employee_ID, be.Branch_ID from tbl_employee emp, tbl_branch_employee be where emp.Employee_Name = 'CRDB' and emp.Employee_ID = be.Employee_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Emp_ID = $row['Employee_ID']; //ONLINE PAYMENT USER i.e CRDB
			$Branch_ID = $row['Branch_ID'];
		}
	}else{
		include("C:/xampp/htdocs/Final_One/files/includes/create_online_user.php"); //CRDB USER
	}
	$doc = new DOMDocument;
	$doc->load("http://127.0.0.1/api/croner.php");

	$Patient_Name = $doc->getElementsByTagName('PATIENTNAME');
	$Amount_Paid = $doc->getElementsByTagName('AMOUNTPAID');
	$Payment_Code = $doc->getElementsByTagName('PAYMENTCODE');
	$Registration_ID = $doc->getElementsByTagName('REGISTRATION_ID');
	$Status = $doc->getElementsByTagName('STATUS');

	for ($i = 0; $i < $Patient_Name->length; $i++) {
		$Control = 1;
	    $P_Name =  $Patient_Name->item($i)->nodeValue;
	    $A_Paid = $Amount_Paid->item($i)->nodeValue;
	    $P_Code = $Payment_Code->item($i)->nodeValue;
	    $R_ID = $Registration_ID->item($i)->nodeValue;
	    $T_Status = $Status->item($i)->nodeValue;

	    if($T_Status == 200){
		    //get check in id
		    $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$R_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn).'1');
		    $nums = mysqli_num_rows($select);
		    if($nums > 0){
		    	while($rows = mysqli_fetch_array($select)) {
		    		$Check_In_ID = $rows['Check_In_ID'];
		    	}
		    }else{
		    	//generate check in id
		    	$inserts = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
		    								Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
		    								Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
		    							VALUES ('$R_ID',(select now()),'$Emp_ID',
		    									(select now()),'saved','$Branch_ID',
		    									(select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn).'2');
		    	if($inserts){
		    		$select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$R_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn).'3');
		    		while($rows = mysqli_fetch_array($select)) {
			    		$Check_In_ID = $rows['Check_In_ID']; //new check in id
			    	}
		    	}
		    }

		    //get Last Patient Bill Id
		    $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$R_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn).'4');
		    $nm = mysqli_num_rows($slct);
		    if($nm > 0){
		    	while ($dts = mysqli_fetch_array($slct)) {
		    		$Patient_Bill_ID = $dts['Patient_Bill_ID'];
		    	}
		    }else{
		    	$insert_bill = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID) values('$R_ID')") or die(mysqli_error($conn).'5');
		    	if($insert_bill){
		    		//get inserted Patient Bill Id
		    		$slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$R_ID' order by Patient_Bill_ID limit 1") or die(mysqli_error($conn).'6');
		    		$nm = mysqli_num_rows($slct);
		    		if($nm > 0){
		    			while ($dts = mysqli_fetch_array($slct)) {
				    		$Patient_Bill_ID = $dts['Patient_Bill_ID'];
				    	}
		    		}
		    	}
		    }
		    //details for referencing
		    $select = mysqli_query($conn,"select Transaction_ID, Amount_Required, Source from tbl_bank_transaction_cache where
		    						Payment_Code = '$P_Code'") or die(mysqli_error($conn).'7');
		    $num = mysqli_num_rows($select);
		    if($num > 0){
		    	while ($data = mysqli_fetch_array($select)) {
		    		$Transaction_ID = $data['Transaction_ID'];
		    		$Amount_Required = $data['Amount_Required'];
		    		$Source = $data['Source'];
		    	}
		    }else{
		    	$Transaction_ID = 0;
		    	$Amount_Required = 0;
		    	$Source = '';
		    }
	    
		    //check if amount paid exceed amount required
		    if($A_Paid < 0){
		    	$Amount_exceed = substr($A_Paid, 1);
		    	//insert to reconsile transaction table
		    	$insert2 = mysqli_query($conn,"insert into tbl_bank_payment_reconcile(Transaction_ID,Amount) values('$Transaction_ID','$Amount_exceed')") or die(mysqli_error($conn).'8');
		    }

		    if(strtolower($Source) == 'reception'){
				$get_items = mysqli_query($conn,"select * from tbl_bank_items_detail_cache where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn).'9');
		    	$no = mysqli_num_rows($get_items);

		    	//check if folio generated
		    	$check_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn).'10');
		    	$num_folio = mysqli_num_rows($check_folio);
		    	
		    	if($num_folio > 0){
		    		while($dt = mysqli_fetch_array($check_folio)){
		    			$Folio_Number = $dt['Folio_Number'];
		    		}
		    	}else{
		    		//include("./includes/Folio_Number_Generator_ePayment.php");
		    		include("C:/xampp/htdocs/Final_One/files/includes/Folio_Number_Generator_ePayment.php");
		    	}

		    	if($no > 0){
		    		while($row = mysqli_fetch_array($get_items)){
		    			if($Control == 1){
		    				$sql = "INSERT INTO tbl_patient_payments(
										Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number,
										Sponsor_ID, Billing_Type, 
										Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,Patient_Bill_ID)

										VALUES ('".$R_ID."','".$Emp_ID."','".$Emp_ID."',
										(select now()),'".$Folio_Number."',
										'".$row['Sponsor_ID']."','".$row['Billing_Type']."',
										(select now()),'".$Branch_ID."','".$Check_In_ID."','CRDB','$P_Code','$Patient_Bill_ID')";
							$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		                    if($result){
		                    	//get receipt number
		                    	$get_receipt = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments where 
		                    								Employee_ID = '$Emp_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn).'11');
	                    		if($get_receipt){
	                    			while ($ddt = mysqli_fetch_array($get_receipt)) {
	                    				$Patient_Payment_ID = $ddt['Patient_Payment_ID'];
	                    			}
	                    		}else{
	                    			$Patient_Payment_ID = 0;
	                    		}
		                    }
		                    $Control = 2;
		                	$insert_items = "INSERT INTO tbl_patient_payment_item_list(
		                						Check_In_Type, Item_ID, Discount, Price, 
		                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
		                						Patient_Payment_ID, Transaction_Date_And_Time) 
											values('".$row['Check_In_Type']."','".$row['Item_ID']."','".$row['Discount']."','".$row['Price']."'
												,'".$row['Quantity']."','".$row['Patient_Direction']."','".$row['Consultant']."','".$row['Consultant_ID']."'
												,'".$Patient_Payment_ID."',(select now()))";
							$result2 = mysqli_query($conn,$insert_items);
		    			}else{
		                	$insert_items = "INSERT INTO tbl_patient_payment_item_list(
		                						Check_In_Type, Item_ID, Discount, Price, 
		                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
		                						Patient_Payment_ID, Transaction_Date_And_Time) 
											values('".$row['Check_In_Type']."','".$row['Item_ID']."','".$row['Discount']."','".$row['Price']."'
												,'".$row['Quantity']."','".$row['Patient_Direction']."','".$row['Consultant']."','".$row['Consultant_ID']."'
												,'".$Patient_Payment_ID."',(select now()))";
							$result2 = mysqli_query($conn,$insert_items);
		    			}
		    		}
		    		if($result && $result2){
		    			//update transaction details
		    			$update = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'Completed' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn).'12');
		    			if($update){
			    			//update remote
			    			$update_remote  = simplexml_load_file("http://127.0.0.1/api/update_remote.php?Payment_Code=$P_Code");
			    			$remote_status = $update_remote->STATUS;
			    		}
		    		}
		    	}
		    }else if(strtolower($Source) == 'revenue center'){
		    	//get pending items (unpaid items)
		    	$get_total = mysqli_query($conn,"select Price, Discount, Quantity, Edited_Quantity from tbl_item_list_cache 
	    								where Transaction_ID = '$Transaction_ID' and
	    								(Status = 'active' or Status = 'approved') and
	    								Transaction_Type = 'Cash'") or die(mysqli_error($conn).'13');
		    	$num_p = mysqli_num_rows($get_total);
		    	if($num_p > 0){
		    		$Total_pending = 0; $Qty = 0;
		    		while ($th = mysqli_fetch_array($get_total)) {
		    			if($th['Edited_Quantity'] > 0){
		    				$Qty = $th['Edited_Quantity'];
		    			}else{
		    				$Qty = $th['Quantity'];
		    			}
		    			$Total_pending += (($th['Price'] - $th['Discount']) * $Qty);
		    		}

	    		//compare with paid amount then proceeed
	    		if($Total_pending == $A_Paid || $A_Paid > $Total_pending){
	    			//payment process
	    			//get items
	    			$get_items = mysqli_query($conn,"select p.Registration_ID, p.Folio_Number, p.Sponsor_ID, p.Sponsor_Name, p.Billing_Type, ilc.Price, ilc.Discount, ilc.Edited_Quantity, ilc.Quantity, ilc.Item_ID
	    										ilc.Check_In_Type, ilc.Consultant, ilc.Consultant_ID, ilc.Payment_Item_Cache_List_ID from 
	    										tbl_payment_cache p, tbl_item_list_cache ilc where
	    										ilc.Payment_Cache_ID = p.Payment_Cache_ID and
	    										ilc.Transaction_ID = '$Transaction_ID' and
	    										(ilc.Status = 'active' or ilc.Status = 'approved') and
	    										ilc.Transaction_Type = 'Cash'") or die(mysqli_error($conn).'14');
	    			$numz = mysqli_num_rows($get_items);
	    			if($numz > 0){
	    				while ($row = mysqli_fetch_array($get_items)) {
	    					$Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
	    					//get quantity
	    					if($row['Edited_Quantity'] > 0){
	    						$Qty = $row['Edited_Quantity'];
	    					}else{
	    						$Qty = $row['Quantity'];
	    					}

	    					if($Control == 1){
			    				$sql = "INSERT INTO tbl_patient_payments(
											Registration_ID, Supervisor_ID, Employee_ID,
											Payment_Date_And_Time, Folio_Number,
											Sponsor_ID, Billing_Type, 
											Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,Patient_Bill_ID)

											VALUES ('".$row['Registration_ID']."','".$Emp_ID."','".$Emp_ID."',
											(select now()),'".$row['Folio_Number']."',
											'".$row['Sponsor_ID']."','".$row['Billing_Type']."',
											(select now()),'".$Branch_ID."','".$Check_In_ID."','CRDB','$P_Code','$Patient_Bill_ID')";
								$result = mysqli_query($conn,$sql) or die(mysqli_error($conn).'15');
			                    if($result){
			                    	//get receipt number
			                    	$get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where 
			                    								Employee_ID = '$Emp_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn).'16');
		                    		if($get_receipt){
		                    			while ($ddt = mysqli_fetch_array($get_receipt)) {
		                    				$Patient_Payment_ID = $ddt['Patient_Payment_ID'];
		                    				$Payment_Date_And_Time = $ddt['Payment_Date_And_Time'];
		                    			}
		                    		}else{
		                    			$Patient_Payment_ID = 0;
		                    			$Payment_Date_And_Time = '';
		                    		}
			                    }
			                    $Control = 2;
			                	$insert_items = "INSERT INTO tbl_patient_payment_item_list(
			                						Check_In_Type, Item_ID, Discount, Price, 
			                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
			                						Patient_Payment_ID, Transaction_Date_And_Time) 
												values('".$row['Check_In_Type']."','".$row['Item_ID']."','".$row['Discount']."','".$row['Price']."'
													,'".$Qty."','others','".$row['Consultant']."','".$row['Consultant_ID']."'
													,'".$Patient_Payment_ID."',(select now()))";
								$result2 = mysqli_query($conn,$insert_items);
								if($result2){
									mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time' where 
													Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
								}
			    			}else{
			                	$insert_items = "INSERT INTO tbl_patient_payment_item_list(
			                						Check_In_Type, Item_ID, Discount, Price, 
			                						Quantity, Patient_Direction, Consultant, Consultant_ID, 
			                						Patient_Payment_ID, Transaction_Date_And_Time) 
												values('".$row['Check_In_Type']."','".$row['Item_ID']."','".$row['Discount']."','".$row['Price']."'
													,'".$Qty."','others','".$row['Consultant']."','".$row['Consultant_ID']."'
													,'".$Patient_Payment_ID."',(select now()))";
								$result2 = mysqli_query($conn,$insert_items);
								if($result2){
									mysqli_query($conn,"update tbl_item_list_cache set 
													Patient_Payment_ID = '$Patient_Payment_ID', 
													Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid' where 
													Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
								}
			    			}
	    				}
	    				if($result && $result2){
	    					
	    					mysqli_query($conn,"update tbl_item_list_cache set ePayment_Status = 'Served' where 
			    								Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));

	    					if($A_Paid > $Total_pending){
	    						$Remainder = ($A_Paid - $Total_pending);
	    						//refund patient
	    						$refund = mysqli_query($conn,"insert into (Transaction_ID, Amount)
	    												values('$Transaction_ID','$Remainder')") or die(mysqli_error($conn));
	    					}
	    					//update transaction details
			    			$update = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'Completed' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
			    			if($update){
				    			//update remote
				    			$update_remote  = simplexml_load_file("http://127.0.0.1/api/update_remote.php?Payment_Code=$P_Code");
				    			$remote_status = $update_remote->STATUS;
				    		}
			    		}
	    			}
		    		}else{
		    			//code.....................
		    		}

		    	}else{
		    		//code.....................
		    	}
		    }
		}else{
			//code.......................
		}
	}
?>