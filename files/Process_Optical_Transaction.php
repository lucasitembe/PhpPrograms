<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
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

    if(isset($_GET['Consultant_ID'])){
    	$Consultant_ID = $_GET['Consultant_ID'];
    }else{
    	$Consultant_ID = 0;
    }

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	//get Patient_cache_d
	$slct = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_id = '$consultation_ID'") or die(mysqli_error($conn));
	$nms = mysqli_num_rows($slct);
	if($nms > 0){
		while ($dts = mysqli_fetch_array($slct)) {
			$Payment_Cache_ID = $dts['Payment_Cache_ID'];
		}
	}else{
		$Payment_Cache_ID = 0;
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

    if(isset($_GET['Registration_ID']) && isset($_GET['Consultant_ID']) && $Consultant_ID != 0 && $Registration_ID != 0){
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
	    												(select now()), '$Branch_ID',(select now()),'Afresh')") or die(mysqli_error($conn));

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
    												(select now()), '$Branch_ID',(select now()),'Afresh')") or die(mysqli_error($conn));

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
		


		$select_items = mysqli_query($conn,"select * from tbl_optical_items_list_cache
										where Registration_ID = '$Registration_ID' and
										Consultant_ID = '$Consultant_ID'") or die(mysqli_error($conn));
    	$no = mysqli_num_rows($select_items);
    	if($no > 0){
    		//insert data into tbl_item_list_cache
    		$insert_data = mysqli_query($conn,"insert into tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, consultation_ID)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                'Outpatient Cash',(select now()),'$Branch_ID','$consultation_ID')") or die(mysqli_error($conn));
    		if($insert_data){
    			$selectz = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $noz = mysqli_num_rows($selectz);
                if($noz > 0){
                	while ($dtz = mysqli_fetch_array($selectz)) {
                		$Payment_Cache_ID = $dtz['Payment_Cache_ID'];
                	}

                	//insert data into tbl_item_list_cache
                	while ($ins = mysqli_fetch_array($select_items)) {
                		$Item_ID = $ins['Item_ID'];
                        $Price = $ins['Price'];
                        $Quantity = $ins['Quantity'];
                        $Comment = $ins['Comment'];
                        $Sub_Department_ID = $ins['Sub_Department_ID'];
                        $Type_Of_Check_In = $ins['Type_Of_Check_In'];
                        $insert = mysqli_query($conn,"insert into tbl_item_list_cache(
	                                            Check_In_Type, Item_ID,Price,
	                                            Quantity, Patient_Direction, Consultant_ID,
	                                            Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
	                                            Sub_Department_ID, Transaction_Type, Service_Date_And_Time
	                                            ) 
                        					values('$Type_Of_Check_In','$Item_ID','$Price',
                                                '$Quantity','others','$Consultant_ID',
                                                '$Payment_Cache_ID',(select now()),
                                                '$Comment','$Sub_Department_ID','Cash',(select now()))") or die(mysqli_error($conn));
                	}
                }else{
                	$Payment_Cache_ID = 0;
                }
    		}

    		//generate receipt
		    $create_receipt = mysqli_query($conn,"insert into tbl_patient_payments(
											Registration_ID,Supervisor_ID,Employee_ID,
											Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
											Sponsor_ID,Sponsor_Name,Billing_Type,
											Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)
	                                    
									values('$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Claim_Form_Number',
										'$Sponsor_ID','$Sponsor_Name','Outpatient Credit',
										(select now()),'$Branch_ID','$Patient_Bill_ID','$Check_In_ID')") or die(mysqli_error($conn));

		    if($create_receipt){
		    	$get_receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
		    	$num_rec = mysqli_num_rows($get_receipt);
		    	if($num_rec > 0){
		    		while ($row = mysqli_fetch_array($get_receipt)) {
		    			$Patient_Payment_ID = $row['Patient_Payment_ID'];
		    			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		    		}

				$select_prepared_items = mysqli_query($conn,"select * from tbl_optical_items_list_cache
														where Registration_ID = '$Registration_ID' and
														Consultant_ID = '$Consultant_ID'") or die(mysqli_error($conn));
		    		//insert data into tbl_patient_payment_item_list
		    		while ($rm = mysqli_fetch_array($select_prepared_items)) {
		    			$sql = "insert into tbl_patient_payment_item_list(Check_In_Type, Item_ID, Discount, Price, Quantity,
		    											Patient_Direction, Consultant_ID, Patient_Payment_ID,Transaction_Date_And_Time,ItemOrigin) 

		    										values('Optical','".$rm['Item_ID']."','".$rm['Discount']."','".$rm['Price']."','".$rm['Quantity']."',
		    											'Others','".$rm['Consultant_ID']."',".$Patient_Payment_ID.",(select now()),'Doctor')";
		    			$insert_item = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		    			if($insert_item){
		    				$update = mysqli_query($conn,"update tbl_item_list_cache set Status = 'paid', 
		    										Patient_Payment_ID = '$Patient_Payment_ID', 
		    										Payment_Date_And_Time = '$Payment_Date_And_Time' where
		    										Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
		    			}
		    		}
		    	}
		    	$delete = mysqli_query($conn,"delete from tbl_optical_items_list_cache where Consultant_ID = '$Consultant_ID' and Registration_ID='$Registration_ID' and consultation_ID ='$consultation_ID'") or die(mysqli_error($conn));
		    }
		    header("Location: ./departmentalothersworks.php?Section=Optical&Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
		}else{
		    header("Location: ./opticalpayments.php?Registration_ID=$Registration_ID&Consultant_ID=$Consultant_ID&OpticalPayments=OpticalPaymentsThisPage");
		}
	}else{
		header("Location: ./opticalpayments.php?Registration_ID=$Registration_ID&Consultant_ID=$Consultant_ID&OpticalPayments=OpticalPaymentsThisPage");
	}
?>