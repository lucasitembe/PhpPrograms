<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Employee_ID = 0;
		$Branch_ID = 0;
	}

	if (isset($_SESSION['supervisor'])) {
	    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
	} else {
	    $Supervisor_ID = $Employee_ID;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}

	//get sponsor id && name
	$get_spo = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name from tbl_sponsor sp, tbl_patient_registration pr where
	                            sp.Sponsor_ID = pr.Sponsor_ID and
	                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($get_spo);
	if ($nm > 0) {
	    while ($dtails = mysqli_fetch_array($get_spo)) {
	        $Sponsor_ID = $dtails['Sponsor_ID'];
	        $Guarantor_Name = $dtails['Guarantor_Name'];
	    }
	} else {
	    $Sponsor_ID = '';
	    $Guarantor_Name = '';
	}

	if(isset($_GET['Consultant_ID'])){
		$Consultant_ID = $_GET['Consultant_ID'];
	}else{
		$Consultant_ID = 0;
	}

	//Get last check in
	include('./includes/Get_Patient_Check_In_Id.php');

    //get last folio number, Claim form number
    $get_folio = mysqli_query($conn,"select Claim_Form_Number, Folio_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' and Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $num_get = mysqli_num_rows($get_folio);
    if ($num_get > 0) {
        while ($dd = mysqli_fetch_array($get_folio)) {
            $Claim_Form_Number = $dd['Claim_Form_Number'];
            $Folio_Number = $dd['Folio_Number'];
        }
    } else {
        //create folio number
        include("./includes/Folio_Number_Generator_temp.php");
        $Claim_Form_Number = '';
    }

	//get Patient_Bill_ID
	$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending' order by Prepaid_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while($row = mysqli_fetch_array($select)){
			$Patient_Bill_ID = $row['Patient_Bill_ID'];
		}

		if($Registration_ID != null && $Registration_ID != 0){ 
			$select_cache = mysqli_query($conn,"select * from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
	        $num_rows = mysqli_num_rows($select_cache);
	        if ($num_rows > 0) {
	            while ($data = mysqli_fetch_array($select_cache)) {
	            	$Sponsor_ID = $data['Sponsor_ID'];
	                $Sponsor_Name = $data['Sponsor_Name'];
	                $Billing_Type = $data['Billing_Type'];
	                $Sponsor_Name = $data['Sponsor_Name'];
	                $Fast_Track = $data['Fast_Track'];
	            }

	            //generate transaction type
	            if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
	                $Transaction_Type = 'Cash';
	                $Pre_Paid = 1;
	            } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
	                $Transaction_Type = 'Credit';
	                $Pre_Paid = 0;
	            }

	            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
	            if($insert_data){
                    //get the last Payment_Cache_ID (foreign key)
                    $selector = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        		Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                    $nor = mysqli_num_rows($selector);
                    if ($nor > 0) {
                    	while ($dt = mysqli_fetch_array($selector)) {
                    		$Payment_Cache_ID = $dt['Payment_Cache_ID'];
                    	}

                    	$select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    	where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        $numrows = mysqli_num_rows($select_details);
                        if ($numrows > 0) {
                            while ($dt = mysqli_fetch_array($select_details)) {
                                $Item_ID = $dt['Item_ID'];
                                $Price = $dt['Price'];
                                $Discount=$dt['Discount'];
                                $Quantity = $dt['Quantity'];
                                $Comment = $dt['Comment'];
                                $Sub_Department_ID = $dt['Sub_Department_ID'];
                                $Type_Of_Check_In = $dt['Type_Of_Check_In'];

                                $insert_items = mysqli_query($conn,"insert into tbl_item_list_cache(
				                                                Check_In_Type, Item_ID, Price, Discount,
				                                                Quantity, Patient_Direction, Consultant_ID,
				                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
				                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Status
			                                                ) values(
			                                                    '$Type_Of_Check_In','$Item_ID','$Price','$Discount',
			                                                    '$Quantity','others','$Consultant_ID',
			                                                    '$Payment_Cache_ID',(select now()),'$Comment',
																$Sub_Department_ID,'$Transaction_Type',(select now()),'paid')") or die(mysqli_error($conn));
                            }

                            if($insert_items){
                            	$insert_details = mysqli_query($conn,"insert into tbl_patient_payments(Registration_ID, Supervisor_ID, Employee_ID,
		                                                        Payment_Date_And_Time, Folio_Number, Claim_Form_Number, Sponsor_ID, Sponsor_Name,
		                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,Fast_Track,Pre_Paid)
                                                        
		                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
		                                                            (select now()),'$Folio_Number', '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
		                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Fast_Track','$Pre_Paid')") or die(mysqli_error($conn));
                            	if($insert_details){
                            		$slct = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date, Payment_Date_And_Time from tbl_patient_payments
	                                                    where Registration_ID = '$Registration_ID' and
	                                                    Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                            		$mn = mysqli_num_rows($slct);
                            		if($mn > 0){
                            			while ($dtz = mysqli_fetch_array($slct)) {
                            				$Patient_Payment_ID = $dtz['Patient_Payment_ID'];
                            				$Receipt_Date = $dtz['Receipt_Date'];
                                        	$Payment_Date_And_Time = $dtz['Payment_Date_And_Time'];
                            			}

                            			//insert data to tbl_patient_payment_item_list
	                                    $select_dts = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
	                                                    where Registration_ID = '$Registration_ID' and
	                                                    Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

	                                    while ($dt = mysqli_fetch_array($select_dts)) {
	                                        $Item_ID = $dt['Item_ID'];
	                                        $Price = $dt['Price'];
	                                        $Discount=$dt['Discount'];
	                                        $Quantity = $dt['Quantity'];
	                                        $Consultant_ID = $Employee_ID;
	                                        $Comment = $dt['Comment'];
	                                        $Type_Of_Check_In = $dt['Type_Of_Check_In'];
											
	                                        $insert_itm = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
	                                                            Check_In_Type, Item_ID, Price,Discount,
	                                                            Quantity, Patient_Direction, Consultant,
	                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time)
	                                                            
	                                                            VALUES ('$Type_Of_Check_In','$Item_ID','$Price','$Discount',
	                                                            '$Quantity','others','others',
	                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()))") or die(mysqli_error($conn));
										
	                                    }

	                                    if ($insert_itm) {
	                                        //update tbl_item_list_cache
	                                        mysqli_query($conn,"update tbl_item_list_cache set
	                                                    Patient_Payment_ID = '$Patient_Payment_ID',
	                                                        Payment_Date_And_Time = '$Receipt_Date' where
	                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));

	                                        //delete all data from cache
	                                        mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	                                        header("Location: ./previewprepaidtransaction.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Section=$Section&PreviewPrePaidTransaction=PreviewPrePaidTransactionThisPage");
	                                    } else {
	                                        header("Location: ./departmentalothersworkspage.php?Registration_ID=$Registration_ID&Section=$Section&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
	                                    }
                            		}
                            	}
                            }
                        }
                    }
	            }
	        }
		}
	}
?>