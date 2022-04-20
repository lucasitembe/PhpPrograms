<?php
	session_start();
	include("./includes/connection.php");
	require_once './functions/items.php';
	if (!isset($_SESSION['userinfo'])) {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
	        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            //@session_start();
	            if (!isset($_SESSION['supervisor'])) {
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

	//get sponsor id
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}
            //get terminal_id if isset
            if($_GET['terminal_id']){
               $terminal_id= $_GET['terminal_id'];
            }else{
                $terminal_id=0;
            }
             //get auth_code if isset
            if($_GET['auth_code']){
               $auth_code1= $_GET['auth_code'];
              $auth_code= mysqli_real_escape_string($conn,$auth_code1);
            }else{
                $auth_code=0;
            }
            
            //get the payment mode 
            $manual_offline=$_GET['manual_offline'];
           
	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}

	//get today date
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Date = $row['today'];
    }

	//get Branch ID
	if (isset($_SESSION['userinfo']['Branch_ID'])) {
	    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
	    $Branch_ID = '0';
	}

	//get supervisor id
	if (isset($_SESSION['supervisor'])) {
       	$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
    }else{
        header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }


	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
include("./includes/Get_Patient_Hospital_Ward_ID.php");


//========================================FOR MORTUARY=======================================================
$morgueDetails=mysqli_query($conn,"SELECT Date_Of_Death,case_type FROM tbl_mortuary_admission WHERE Corpse_ID='$Registration_ID'") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
	 $Payment_Method="cash";
 //============OVERIDDING SPONSOR TO CASH=====================
 $Sponsor_ID=mysqli_fetch_array(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE payment_method='$Payment_Method' ORDER BY Sponsor_ID ASC LIMIT 1"))['Sponsor_ID'];
//get last check in
		$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
		$n_slct = mysqli_num_rows($slct);
		if($n_slct > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$Check_In_ID = $dt['Check_In_ID'];
			}
		}

		//get last patient bill id
		$slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$Patient_Bill_ID = $dt['Patient_Bill_ID'];
			}	
}

		//Get last folio number
				$Folio_Number =0;
			


	if($Check_In_ID != ''  && $Patient_Bill_ID != ''){
		//verify items
		$select = mysqli_query($conn,"select Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			//insert data into tbl_patient_payments
			$Insert_Data = mysqli_query($conn,"insert into tbl_patient_payments(
        								Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number, Sponsor_ID,
										Billing_Type, Receipt_Date, Transaction_Type, Branch_ID, Check_In_ID, Patient_Bill_ID,payment_type,Hospital_Ward_ID,auth_code,terminal_id,manual_offline)
									values(
										'$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Sponsor_ID',
										'Inpatient Cash',(select now()),'Direct cash','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','pre','$Hospital_Ward_ID','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));

			if($Insert_Data){
				$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       							where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

		        while ($row = mysqli_fetch_array($sql_Select_date)) {
		            $Patient_Payment_ID = $row['Patient_Payment_ID'];
		            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		        }

		        //select items from the cache
		        $get_items = mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		        $num_items = mysqli_num_rows($get_items);
		        if($num_items > 0){
		        	while ($dts = mysqli_fetch_array($get_items)) {
		        		$Item_ID = $dts['Item_ID'];
		        		$Amount = $dts['Amount'];
		        		$Item_Description = $dts['Item_Description'];
		        		$Cache_ID = $dts['Cache_ID'];
		        		$Clinic_ID = $dts['Clinic_ID'];
		        		$finance_department_id = $dts['finance_department_id'];
		        		$clinic_location_id = $dts['clinic_location_id'];

		        		$Insert_items = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
			    										Check_In_Type, Item_ID, Item_Name, Category, Discount, Price, Quantity,
				    									Patient_Direction, Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
							    					values('Direct Cash','$Item_ID','$Item_Description','direct cash','0','$Amount','1',
							    						'Others','$Employee_ID','$Patient_Payment_ID','$Payment_Date_And_Time','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
		        		if($Insert_items){
		        			mysqli_query($conn,"delete from tbl_direct_cash_cache where Cache_ID = '$Cache_ID'") or die(mysqli_error($conn));
		        		}
		        	}
		        	/////////////////////////////////////////// Journal entry //////////////////////////////////////////

		                $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);


		                $Product_Array=array();

		                $Product_Name_Array = array(
		                    'source_name' => 'ehms_sales_cash',
		                    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
		                    'debit_entry_ledger' => 'CASH IN HAND',
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
		        if($Insert_items){
		        	header("Location: ./patientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
		        }
			}
		}
	}

}
//==================================END OF MORTUARY SECTION=====================================================
else{
	//get last folio number, patient bill id and check in id
	$select = mysqli_query($conn,"select Folio_Number, Check_In_ID, Patient_Bill_ID from tbl_patient_payments 
							where Registration_ID = '$Registration_ID' and
							Check_In_ID is not null and
							Patient_Bill_ID is not null order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Folio_Number = $data['Folio_Number'];
			$Check_In_ID = $data['Check_In_ID'];
			$Patient_Bill_ID = $data['Patient_Bill_ID'];
		}
	}else{
		//get last check in
		$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
		$n_slct = mysqli_num_rows($slct);
		if($n_slct > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$Check_In_ID = $dt['Check_In_ID'];
			}
		}else{
			//create new check in
			$new_check_in = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date, Branch_ID, Check_In_Date_And_Time, Check_In_Status, Check_In_Date) 
											values('$Registration_ID', (select now()), '$Branch_ID', (select now()), 'pending', (select now())))") or die(mysqli_error($conn));
			if($new_check_in){
				$slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
				$n_slct = mysqli_num_rows($slct);
				if($n_slct > 0){
					while ($dt = mysqli_fetch_array($slct)) {
						$Check_In_ID = $dt['Check_In_ID'];
					}
				}
			}else{
				$Check_In_ID = '1';
			}
		}

		//Get last folio number
		$slct = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$Folio_Number = $dt['Folio_Number'];
			}
		}else{
			include("./includes/Folio_Number_Generator.php");
		}

		
	}

        //get last patient bill id
		$slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$Patient_Bill_ID = $dt['Patient_Bill_ID'];
			}
		}else{
			$new_Patient_Bill_ID = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time, Patient_Status)
												VALUES('$Registration_ID', (select now()), 'Inpatient')") or die(mysqli_error($conn));
			if($new_Patient_Bill_ID){
				$slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
				$nm = mysqli_num_rows($slct);
				if($nm > 0){
					while ($dt = mysqli_fetch_array($slct)) {
						$Patient_Bill_ID = $dt['Patient_Bill_ID'];
					}
				}else{
					$Patient_Bill_ID = 0;
				}
			}
		}



	if($Check_In_ID != '' && $Folio_Number != '' && $Patient_Bill_ID != ''){
		//verify items
		$select = mysqli_query($conn,"select Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			//insert data into tbl_patient_payments
			$Insert_Data = mysqli_query($conn,"insert into tbl_patient_payments(
        								Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number, Sponsor_ID,
										Billing_Type, Receipt_Date, Transaction_Type, Branch_ID, Check_In_ID, Patient_Bill_ID,payment_type,Hospital_Ward_ID,auth_code,terminal_id,manual_offline)
									values(
										'$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Sponsor_ID',
										'Inpatient Cash',(select now()),'Direct cash','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','pre','$Hospital_Ward_ID','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));

			if($Insert_Data){
				$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       							where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

		        while ($row = mysqli_fetch_array($sql_Select_date)) {
		            $Patient_Payment_ID = $row['Patient_Payment_ID'];
		            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		        }

		        //select items from the cache
		        $get_items = mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		        $num_items = mysqli_num_rows($get_items);
		        if($num_items > 0){
		        	while ($dts = mysqli_fetch_array($get_items)) {
		        		$Item_ID = $dts['Item_ID'];
		        		$Amount = $dts['Amount'];
		        		$Item_Description = $dts['Item_Description'];
		        		$Cache_ID = $dts['Cache_ID'];
		        		$Clinic_ID = $dts['Clinic_ID'];
		        		$finance_department_id = $dts['finance_department_id'];
                                        $clinic_location_id = $dts['clinic_location_id'];

		        		$Insert_items = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
			    										Check_In_Type, Item_ID, Item_Name, Category, Discount, Price, Quantity,
				    									Patient_Direction, Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
							    					values('Direct Cash','$Item_ID','$Item_Description','direct cash','0','$Amount','1',
							    						'Others','$Employee_ID','$Patient_Payment_ID','$Payment_Date_And_Time','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
		        		if($Insert_items){
		        			mysqli_query($conn,"delete from tbl_direct_cash_cache where Cache_ID = '$Cache_ID'") or die(mysqli_error($conn));
		        		}
		        	}
		        	/////////////////////////////////////////// Journal entry //////////////////////////////////////////

		                $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);


		                $Product_Array=array();

		                $Product_Name_Array = array(
		                    'source_name' => 'ehms_sales_cash',
		                    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
		                    'debit_entry_ledger' => 'CASH IN HAND',
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
		        if($Insert_items){
		        	header("Location: ./patientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
		        }
			}
		}
	}




	//verify items
	$select = mysqli_query($conn,"select Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		

		

			//insert data into tbl_patient_payments
			$Insert_Data = mysqli_query($conn,"insert into tbl_patient_payments(
        								Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number, Sponsor_ID,
										Billing_Type, Receipt_Date, Transaction_Type, Branch_ID, Check_In_ID, Patient_Bill_ID,payment_type,Hospital_Ward_ID,auth_code,terminal_id,manual_offline)
									values(
										'$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Sponsor_ID',
										'Outpatient Cash',(select now()),'Direct cash','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','pre','$Hospital_Ward_ID','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));
			if($Insert_Data){
				$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       							where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

		        while ($row = mysqli_fetch_array($sql_Select_date)) {
		            $Patient_Payment_ID = $row['Patient_Payment_ID'];
		            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		        }

		        //select items from the cache
		        $get_items = mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		        $num_items = mysqli_num_rows($get_items);
		        if($num_items > 0){
		        	while ($dts = mysqli_fetch_array($get_items)) {
		        		$Item_ID = $dts['Item_ID'];
		        		$Amount = $dts['Amount'];
		        		$Item_Description = $dts['Item_Description'];
		        		$Cache_ID = $dts['Cache_ID'];
		        		$Clinic_ID = $dts['Clinic_ID'];
		        		$finance_department_id = $dts['finance_department_id'];

		        		$Insert_items = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
			    										Check_In_Type, Item_ID, Item_Name, Category, Discount, Price, Quantity,
				    									Patient_Direction, Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id)
							    					values('Direct Cash','$Item_ID','$Item_Description','direct cash','0','$Amount','1',
							    						'Others','$Employee_ID','$Patient_Payment_ID','$Payment_Date_And_Time','$Clinic_ID','$finance_department_id')") or die(mysqli_error($conn));
		        		if($Insert_items){
		        			mysqli_query($conn,"delete from tbl_direct_cash_cache where Cache_ID = '$Cache_ID'") or die(mysqli_error($conn));
		        		}
		        	}
		        }
		        if($Insert_items){
		        	header("Location: ./patientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
		        }
			}
	}
	}
?>