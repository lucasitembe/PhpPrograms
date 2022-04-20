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
	    } else {
	        header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	} else {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
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

	//get sponsor id
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	//get Guarantor name
	$slct = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while ($rw = mysqli_fetch_array($slct)) {
			$Sponsor_Name = $rw['Guarantor_Name'];
		}
	}else{
		$Sponsor_Name = '';
	}

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}

	//get Branch ID
	if (isset($_SESSION['userinfo']['Branch_ID'])) {
	    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
	    $Branch_ID = '0';
	}

	//get today date
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Date = $row['today'];
    }

	//get last check in id and check in date
	$select = mysqli_query($conn,"select Check_In_Date_And_Time, Check_In_ID from tbl_check_in where
							Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Check_In_ID = $data['Check_In_ID'];
			$Check_In_Date_And_Time = $data['Check_In_Date_And_Time'];
		}
	}else{
		$Check_In_ID = '';
		$Check_In_Date_And_Time = '';
	}

	//verify items
	$select = mysqli_query($conn,"select Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		//check if is within 48HRS ..... If not provide another check in id
		$year = 100; $month = 0; $day = 0; 
		if($Check_In_Date_And_Time != '' && $Check_In_ID != ''){
			$date1 = new DateTime($Date);
			$date2 = new DateTime($Check_In_Date_And_Time);
			$diff = $date1 -> diff($date2);
			$year = $diff->y;
			$month = $diff->m;
			$day = $diff->d;

		}
			
		if($month != 0 || $day > 2 || $year != 0){
			//create new check in
			$inserts = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
										Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
										Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
									values ('$Registration_ID',(select now()),'$Employee_ID',
										(select now()),'saved','$Branch_ID',
										(select now()),(select now()),'Afresh','pending')") or die(mysqli_error($conn));
	    	if($inserts){
	    		$select2 = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
	    		while($rows = mysqli_fetch_array($select2)) {
		    		$Check_In_ID = $rows['Check_In_ID'];
		    	}
	    	}

	    	//insert data to tbl_patient_bill......
			$insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
			if($insert){
				$select3 = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
				$nums = mysqli_num_rows($select3);
				while ($row = mysqli_fetch_array($select3)) {
					$Patient_Bill_ID = $row['Patient_Bill_ID'];
				}
			}else{
				$Patient_Bill_ID = '';
			}
			include("./includes/Folio_Number_Generator.php");
		}else{
			//select Patient_Bill_ID
			$select3 = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
			$nums = mysqli_num_rows($select3);
			if($nums > 0){
				while ($row = mysqli_fetch_array($select3)) {
					$Patient_Bill_ID = $row['Patient_Bill_ID'];
				}
			}else{
				$insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
				$select3 = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
				while ($row = mysqli_fetch_array($select3)) {
					$Patient_Bill_ID = $row['Patient_Bill_ID'];
				}
			}

			//select folio last number
			$get_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' and Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
			$get_number = mysqli_num_rows($get_folio);
			if($get_number > 0){
				while ($dt = mysqli_fetch_array($get_folio)) {
					$Folio_Number = $dt['Folio_Number'];
				}
			}else{
				include("./includes/Folio_Number_Generator.php");
			}
		}
		
		//check if patient has pending pre/ post paid bill
        $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending' order by Prepaid_ID desc limit 1") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slct);
        if($nm > 0){
        	while ($dtz = mysqli_fetch_array($slct)) {
        		$Patient_Bill_ID = $dtz['Patient_Bill_ID'];
        	}
        }
		//insert data into tbl_patient_payments
			$Insert_Data = mysqli_query($conn,"insert into tbl_patient_payments(
        								Registration_ID, Supervisor_ID, Employee_ID,
										Payment_Date_And_Time, Folio_Number, Sponsor_ID,Sponsor_Name,
										Billing_Type, Receipt_Date, Transaction_Type, Branch_ID, Check_In_ID, Patient_Bill_ID,auth_code,terminal_id,manual_offline)
									values(
										'$Registration_ID','$Supervisor_ID','$Employee_ID',
										(select now()),'$Folio_Number','$Sponsor_ID','$Sponsor_Name',
										'Outpatient Cash',(select now()),'Direct cash','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$auth_code','$terminal_id','$manual_offline')") or die(mysqli_error($conn));
			if($Insert_Data){
				$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       							where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

                                
		        while ($row = mysqli_fetch_array($sql_Select_date)) {
		            $Patient_Payment_ID = $row['Patient_Payment_ID'];
		            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		        }
                        
                       // echo $Patient_Payment_ID;exit;

		        //select items from the cache
		        $get_items = mysqli_query($conn,"select * from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		        $num_items = mysqli_num_rows($get_items);
		        if($num_items > 0){
		        	while ($dts = mysqli_fetch_array($get_items)) {
		        		$Item_ID = $dts['Item_ID'];
		        		$Amount = $dts['Amount'];
                                        $Quantity = (!empty($data['Quantity']) && $data['Quantity'] >0)? $data['Quantity']:1;
                                        $Item_Description = $dts['Item_Description'];
		        		$Cache_ID = $dts['Cache_ID'];
		        		$Clinic_ID = $dts['Clinic_ID'];
		        		$finance_department_id = $dts['finance_department_id'];
		        		$clinic_location_id = $dts['clinic_location_id'];

		        		$Insert_items = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
			    										Check_In_Type, Item_ID, Item_Name, Category, Discount, Price, Quantity,
				    									Patient_Direction, Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
							    					values('Direct Cash','$Item_ID','$Item_Description','direct cash','0','$Amount','$Quantity',
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
?>