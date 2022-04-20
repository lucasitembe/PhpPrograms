<?php
session_start();
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}else{
    $Registration_ID="";
}       
if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID="";
}       
if(isset($_GET['Sponsor_ID'])){
    $Sponsor_ID=$_GET['Sponsor_ID'];
}else{
    $Sponsor_ID="";
}       
$Patient_Bill_ID=0;
$Patient_Payment_ID=0;
//Get employee id & branch id
	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Employee_ID = 0;
		$Branch_ID = 0;
	}

       $Today_Date = mysqli_query($conn,"select now() as today");
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	}

	if($Registration_ID != null && $Registration_ID != '' && $Registration_ID != 0 && isset($_SESSION['userinfo'])){
		//check if no pending bill
		$check = mysqli_query($conn,"select Registration_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($check);
        if($no == 0){
        	//Create check in
        	$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = '$Today'") or die(mysqli_error($conn));
        	$nm = mysqli_num_rows($slct);
        	if($nm == 0){
        		$inserts = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date, Employee_ID, Check_In_Date_And_Time, Check_In_Status,
        								Branch_ID, Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In)
    									values ('$Registration_ID',(select now()),'$Employee_ID', (select now()),'saved',
    									'$Branch_ID',(select now()),(select now()),'Afresh')") or die(mysqli_error($conn));
        	}

        	//Create Patient_Bill_ID
        	$insert = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID,Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        	if($insert){
        		$select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        		$no = mysqli_num_rows($select);
        		if($no > 0){
        			while ($data = mysqli_fetch_array($select)) {
        				$Patient_Bill_ID = $data['Patient_Bill_ID'];
        			}
        			
        			//insert into tbl_prepaid_details table
        			$insert2 = mysqli_query($conn,"insert into tbl_prepaid_details(Registration_ID,Employee_ID,Date_Time,Patient_Bill_ID)
        									values('$Registration_ID','$Employee_ID',(select now()),'$Patient_Bill_ID')") or die(mysqli_error($conn));
        		}
        	}
            }
	}

//////////////////////now make payment for the created bill
//
//declare Claim_Form_Number
        ///get the patient bill id
        
        $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        		$no = mysqli_num_rows($select);
        		if($no > 0){
        			while ($data = mysqli_fetch_array($select)) {
        				$Patient_Bill_ID = $data['Patient_Bill_ID'];
        			}
                        }
                                ////////////////
        $select_check_in = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    			$nmz = mysqli_num_rows($select_check_in);
    			if($nmz > 0){
                            while ($data = mysqli_fetch_array($select_check_in)) {
    				$Check_In_ID = $data['Check_In_ID'];
                            }
    			}else{
    					$Check_In_ID = 0;
    		}
    		$Claim_Form_Number = '';

    		//GENERATE FOLIO NUMBER
    		//include("./includes/Folio_Number_Generator.php");
        //select items and attach into selected receipt
		$select_items = mysqli_query($conn,"select ilc.Sub_Department_ID,ilc.Quantity,ilc.Clinic_ID, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, Consultant, 
										ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status, ilc.Item_ID, ilc.Consultant_ID, pc.Billing_Type
	                                    from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
	                                    ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
	                                    ilc.Item_ID = itm.Item_ID and
	                                    ilc.Status = 'active' and
	                                    
	                                    (ilc.Check_In_Type = 'Laboratory' or ilc.Check_In_Type = 'Radiology') and
	                                    pc.Payment_Cache_ID = '$Payment_Cache_ID' and
	                                    pc.Registration_ID = '$Registration_ID' and
	                                    ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
    	$no = mysqli_num_rows($select_items);
    	if($no > 0){
			//generate receipt
		    $create_receipt = mysqli_query($conn,"insert into tbl_patient_payments(
											Registration_ID,Supervisor_ID,Employee_ID,
											Payment_Date_And_Time,
											Sponsor_ID,Billing_Type,
											Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID,Pre_Paid)
	                                    
									values('$Registration_ID','$Employee_ID','$Employee_ID',
										(select now()),
										'$Sponsor_ID','Outpatient Cash',
										(select now()),'$Branch_ID','$Patient_Bill_ID','$Check_In_ID','1')") or die(mysqli_error($conn));

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
		    			//update if and only if selecte$select_itemsd bill is inpatient bill
		    			if(strtolower(substr($rm['Billing_Type'], 0, 3)) == 'inp'){
		    				//mysqli_query($conn,"update tbl_patient_payments set Billing_Type = 'Inpatient Credit' where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
		    			}

		    			//generate quantity
		    			if($rm['Edited_Quantity'] > 0){
		    				$Quantity = $rm['Edited_Quantity'];
		    			}else{
		    				$Quantity = $rm['Quantity'];
		    			}
                                        $Clinic_ID=$rm['Clinic_ID'];
                                        $Sub_Department_ID=$rm['Sub_Department_ID'];
                                        $consultant= mysqli_real_escape_string($conn,$rm['Consultant']);
		    			$Payment_Item_Cache_List_ID = $rm['Payment_Item_Cache_List_ID'];
		    			$sql = "insert into tbl_patient_payment_item_list(Check_In_Type, Item_ID, Discount, Price, Quantity,
		    											Patient_Direction, Consultant, Consultant_ID, Patient_Payment_ID,
		    											Transaction_Date_And_Time,Clinic_ID,Sub_Department_ID) 

		    										values('".$rm['Check_In_Type']."','".$rm['Item_ID']."','".$rm['Discount']."','".$rm['Price']."','".$Quantity."',
		    											'Others','".$consultant."','".$rm['Consultant_ID']."',".$Patient_Payment_ID.",(select now()),'$Clinic_ID','$Sub_Department_ID')";
		    			$insert_item = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		    			if($insert_item){
		    				$update = mysqli_query($conn,"update tbl_item_list_cache set Status = 'paid', 
		    										Patient_Payment_ID = '$Patient_Payment_ID', 
		    										Payment_Date_And_Time = '$Payment_Date_And_Time' where
		    										Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		    			}
		    		}

		    	/////////////////////////////////////////// Journal entry //////////////////////////////////////////

//                $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);
//
//                $Product_Array=array();
//
//                $Product_Name_Array = array(
//                    'source_name' => 'ehms_sales_cash',
//                    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
//                    'debit_entry_ledger' => $Sponsor_Name,
//                    'credit_entry_ledger' => 'SALES',
//                    'sub_total' => $payDetails['TotalAmount'],
//                    'source_id' => $Patient_Payment_ID,
//                    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
//                    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
//                );
//
//                array_push($Product_Array, $Product_Name_Array);
//
//
//
//               $acc = gAccJournalEntry(json_encode($Product_Array));
              
               //////////////////////////////////////////////////////////////////////////////////////////


		    	}
		    }
		   echo $Patient_Payment_ID;
		}else{
		    echo "fail";
		}



