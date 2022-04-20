<?php
	session_start();
	include("./includes/connection.php");
	require_once './functions/items.php';
	
    if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = "";
    }

	if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./supervisorauthentication.php?{$Section_Link}InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Billing_Type'])){
		$B_Type = $_GET['Billing_Type'];
	}else{
		$B_Type = 'out';
	}

	//generate Billing_Type
	if(strtolower($B_Type) == 'inp'){
		$Billing_Type = 'Inpatient Credit';
	}else{
		$Billing_Type = 'Outpatient Credit';
	}

	//get employee details
	$select = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nums = mysqli_num_rows($select);
	if($nums > 0){
		while ($dz = mysqli_fetch_array($select)) {
			$Sponsor_ID = $dz['Sponsor_ID'];
			$Sponsor_Name = $dz['Guarantor_Name'];
		}
	}else{
		$Sponsor_ID = '';
		$Sponsor_Name = '';
	}

    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['Registration_ID']) && isset($_GET['Payment_Cache_ID']) && $Payment_Cache_ID != 0 && $Registration_ID != 0){
    	//get folio number, claim form number, check in id & Patient_Bill_ID
    	$select = mysqli_query($conn,"select ppl.Patient_Payment_Item_List_ID
    							from tbl_payment_cache pc, tbl_patient_payment_item_list ppl, tbl_consultation c where
    							pc.consultation_id = c.consultation_ID and
    							c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
    							pc.Registration_ID = '$Registration_ID' and
    							pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));

    	$num = mysqli_num_rows($select);
    	if($num > 0){
    		while ($data = mysqli_fetch_array($select)) {
    			$Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
    		}

    		//get check_in_id, Folio_number, patient bill id
    		$get_details = mysqli_query($conn,"select Folio_Number, Patient_Bill_ID, Check_In_ID, Claim_Form_Number from 
    									tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
    									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
    									ppl.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    		$num_get = mysqli_num_rows($get_details);
    		if($num_get > 0){
    			while ($row = mysqli_fetch_array($get_details)) {
    				$Folio_Number = $row['Folio_Number'];
    				$Patient_Bill_ID = $row['Patient_Bill_ID'];
    				$Check_In_ID = $row['Check_In_ID'];
    				$Claim_Form_Number = $row['Claim_Form_Number'];
    			}
    		}else{
				//create new patient_bill
	    		$insert = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
	    		if($insert){
	    			$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
	    			$nm = mysqli_num_rows($select);
	    			if($nm > 0){
	    				while ($data = mysqli_fetch_array($select)) {
	    					$Patient_Bill_ID = $data['Patient_Bill_ID'];
	    				}
	    			}else{
	    				$Patient_Bill_ID = 0;
	    			}
	    		}

	    		//Get last check in
	    		$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
	    		$nmz = mysqli_num_rows($slct);
	    		if($nmz > 0){
	    			while ($dt = mysqli_fetch_array($slct)) {
	    				$Check_In_ID = $dt['Check_In_ID'];
	    			}
	    		}else{
	    			//create new check in 
	    			$insert_check = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
	    											Check_In_Date_And_Time, Branch_ID, Check_In_Date, Type_Of_Check_In)
	    										values('$Registration_ID', (select now()), '$Employee_ID',
	    												(select now()), '$Branch_ID',(select now())),'Afresh'") or die(mysqli_error($conn));

	    			if($insert_check){
	    				$select_check_in = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
	    				$nmz = mysqli_num_rows($select_check_in);
	    				if($nmz > 0){
	    					while ($data = mysqli_fetch_array($select_check_in)) {
	    						$Check_In_ID = $data['Check_In_ID'];
	    					}
	    				}else{
	    					$Check_In_ID = 0;
	    				}
	    			}else{
	    				$Check_In_ID = 0;
	    			}
	    		}

	    		//declare Claim_Form_Number
	    		$Claim_Form_Number = '';

	    		//GENERATE FOLIO NUMBER
	    		include("./includes/Folio_Number_Generator.php");
    		}
    	}else{
    		//create new patient_bill
    		$insert = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
    		if($insert){
    			$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
    			$nm = mysqli_num_rows($select);
    			if($nm > 0){
    				while ($data = mysqli_fetch_array($select)) {
    					$Patient_Bill_ID = $data['Patient_Bill_ID'];
    				}
    			}else{
    				$Patient_Bill_ID = 0;
    			}
    		}

    		//Get last check in
    		$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    		$nmz = mysqli_num_rows($slct);
    		if($nmz > 0){
    			while ($dt = mysqli_fetch_array($slct)) {
    				$Check_In_ID = $dt['Check_In_ID'];
    			}
    		}else{
    			//create new check in 
    			$insert_check = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
    											Check_In_Date_And_Time, Branch_ID, Check_In_Date, Type_Of_Check_In)
    										values('$Registration_ID', (select now()), '$Employee_ID',
    												(select now()), '$Branch_ID',(select now())),'Afresh'") or die(mysqli_error($conn));

    			if($insert_check){
    				$select_check_in = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    				$nmz = mysqli_num_rows($select_check_in);
    				if($nmz > 0){
    					while ($data = mysqli_fetch_array($select_check_in)) {
    						$Check_In_ID = $data['Check_In_ID'];
    					}
    				}else{
    					$Check_In_ID = 0;
    				}
    			}else{
	    				$Check_In_ID = 0;
	    		}
    		}
    		
    		//declare Claim_Form_Number
    		$Claim_Form_Number = '';

    		//GENERATE FOLIO NUMBER
    		include("./includes/Folio_Number_Generator.php");
    	}
    }else{

    }

    if($Sponsor_ID != '' && $Sponsor_Name != ''){
    	//select items and attach into selected receipt
		$select_items = mysqli_query($conn,"select ilc.Quantity,ilc.Clinic_ID,ilc.finance_department_id,ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, Consultant, 
										ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status, ilc.Item_ID, ilc.Consultant_ID
	                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
	                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
	                                    ilc.Item_ID = itm.Item_ID and
	                                    ilc.Status = 'active' and
	                                    ilc.Transaction_Type = 'Credit' and
	                                    ilc.Check_In_Type = 'Optical' and
	                                    pc.Payment_Cache_ID = '$Payment_Cache_ID' and
	                                    pc.Registration_ID = '$Registration_ID' and
	                                    ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
    	$no = mysqli_num_rows($select_items);
    	if($no > 0){
			//generate receipt
		    $create_receipt = mysqli_query($conn,"insert into tbl_patient_payments(
											Registration_ID,Supervisor_ID,Employee_ID,
											Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
											Sponsor_ID,Sponsor_Name,Billing_Type,
											Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)
	                                    
									values('$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Claim_Form_Number',
										'$Sponsor_ID','$Sponsor_Name','$Billing_Type',
										(select now()),'$Branch_ID','$Patient_Bill_ID','$Check_In_ID')") or die(mysqli_error($conn));

		    if($create_receipt){
		    	$get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
		    	$num_rec = mysqli_num_rows($get_receipt);
		    	if($num_rec > 0){
		    		while ($row = mysqli_fetch_array($get_receipt)) {
		    			$Patient_Payment_ID = $row['Patient_Payment_ID'];
		    			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		    		}

		    		//insert data into tbl_patient_payment_item_list
		    		while ($rm = mysqli_fetch_array($select_items)) {
		    			//generate quantity
		    			if($rm['Edited_Quantity'] > 0){
		    				$Quantity = $rm['Edited_Quantity'];
		    			}else{
		    				$Quantity = $rm['Quantity'];
		    			}
                                        $Clinic_ID=$rm['Clinic_ID'];
                                        $finance_department_id=$rm['finance_department_id'];
		    			$Payment_Item_Cache_List_ID = $rm['Payment_Item_Cache_List_ID'];
		    			$sql = "insert into tbl_patient_payment_item_list(Check_In_Type, Item_ID, Discount, Price, Quantity,
		    											Patient_Direction, Consultant, Consultant_ID, Patient_Payment_ID,
		    											Transaction_Date_And_Time,Clinic_ID,finance_department_id) 

		    										values('".$rm['Check_In_Type']."','".$rm['Item_ID']."','".$rm['Discount']."','".$rm['Price']."','".$rm['Quantity']."',
		    											'Others','".$rm['Consultant']."','".$rm['Consultant_ID']."',".$Patient_Payment_ID.",(select now()),'$Clinic_ID','$finance_department_id')";
		    			$insert_item = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		    			if($insert_item){
		    				$update = mysqli_query($conn,"update tbl_item_list_cache set Status = 'paid', 
		    										Patient_Payment_ID = '$Patient_Payment_ID', 
		    										Payment_Date_And_Time = '$Payment_Date_And_Time' where
		    										Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		    			}
		    		}

		    		/////////////////////////////////////////// Journal entry //////////////////////////////////////////

                $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);

                $Product_Array=array();

                $Product_Name_Array = array(
                    'source_name' => 'ehms_sales_cash',
                    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
                    'debit_entry_ledger' => $Sponsor_Name, //spopnsor name as ledger name
                    'credit_entry_ledger' => 'SALES',
                    'sub_total' => $payDetails['TotalAmount'],
                    'source_id' => $Patient_Payment_ID,
                    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
                );

                array_push($Product_Array, $Product_Name_Array);



               $acc = gAccJournalEntry(json_encode($Product_Array));
              
               //////////////////////////////////////////////////////////////////////////////////////////

		    	}
		    }
		    header("Location: ./previewapprovedtransaction.php?{$Section_Link}Section=Optical&Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&PreviewApprovedTransaction=PreviewApprovedTransactionThisPage");
		}else{
		    header("Location: ./approveoptical.php?{$Section_Link}Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&ApproveInvestigation=ApproveInvestigationThisPage");
		}
	}else{
		header("Location: ./approveoptical.php?{$Section_Link}Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&ApproveInvestigation=ApproveInvestigationThisPage");
	}
?>