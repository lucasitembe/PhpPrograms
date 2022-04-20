<?php
	include("../includes/connection.php");
	session_start();

	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
		$code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
		$code = '000000';
	}
	$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
	$Employee_ID = $_SESSION['supervisor']['Employee_ID'];
	$facility_qr = "SELECT 	hospital_code FROM tbl_mobile_payment_code";
	$facility_result = mysqli_query($conn,$facility_qr);
	$facility_row = mysqli_fetch_assoc($facility_result);
	$hospital_code = $facility_row['hospital_code'];
	$payment_code =$hospital_code.$payment_code;

	$amount = 0;

	$qr = "SELECT * FROM tbl_mobile_payment mp, tbl_items i,tbl_patient_registration pr,tbl_sponsor s WHERE 
											i.Item_ID = mp.item_ID AND mp.payment_code = '$code'
											AND mp.Registration_ID = pr.Registration_ID
											AND s.Sponsor_ID = pr.Sponsor_ID";

	$result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$amount = 0;
	while($row = mysqli_fetch_assoc($result)){
		$amount+= (($row['Price']*$row['Quantity'])-$row['Discount']);
		$Registration_ID = $row['Registration_ID'];
		$Sponsor_ID = $row['Sponsor_ID'];
		$Sponsor_Name = $row['Guarantor_Name'];
		$visit_status = $row['visit_status'];
		$Supervisor_ID = $row['Supervisor_ID'];
	}
	
	$handle = json_decode(file_get_contents("http://127.0.0.1/ehms_ndogo/getCodeStatus.php?code=$payment_code&ammount=$amount",'r'));

	
	/* Structure of $handle as an object and sample output
		stdClass Object
			(
			    [payment_id] => 40
			    [payment_code] => 00100004
			    [payment_date] => 2014-12-01 11:37:17
			    [payment_mode] => 
			    [ammount] => 34000
			    [payment_status] => not paid
			) 
	*/

	if($handle->payment_status == 'paid' ){
		$online_payment_date_and_time = $handle->payment_date;
		$payment_mode = $handle->payment_mode;
		$payment_status = $handle->payment_status;

		//1. Get A Folio Number & Sponsor
				$branch_id = $_SESSION['userinfo']['Branch_ID'];
				
				//CHECK IF VISIT STATUS IS REALY NEW
				//$visit_status

				if($visit_status == 'new'){
					//get new folio number
					$Folio_Branch_ID = $branch_id;
	    
					//get the current date		
					$Today_Date = mysqli_query($conn,"select now() as today");
					while($Dayrow = mysqli_fetch_array($Today_Date)){
					    $original_Date = $Dayrow['today'];
					    $new_Date = date("Y-m-d", strtotime($original_Date));
					    $Today = $new_Date; 
					}
			                //check if the current date and the last folio number are in the same month and year
			                //select the current folio number to check the month and year
					$current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
									from tbl_folio where sponsor_id = '$Sponsor_ID' and
									    Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
			                $no = mysqli_num_rows($current_folio); 
			                if($no > 0){
			                    while($Foliorow = mysqli_fetch_array($current_folio)){
			                        $Folio_Number = $Foliorow['Folio_Number'];
			                        $Folio_date = $Foliorow['Folio_date'];
			                    } 
			                }else{
			                    $Folio_Number = 1;
			                    $Folio_date = 0;
			                }
					
					//check the current month and year (Remove the day part of the date)
					$Current_Month_and_year = substr($Today,0,7); 
					
					//check month and year of the last folio number (Remove the day part of the date)
					$Last_Folio_Month_and_year = substr($Folio_date,0,7); 
					
					//compare month and year    
					if($Last_Folio_Month_and_year == $Current_Month_and_year){
					    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
							values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
					    $Folio_Number = $Folio_Number + 1;
					}else{
					    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
							values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
					    $Folio_Number = 1;
					}
				}else{
					//select last folio
					$folio_qr = "SELECT Folio_Number FROM tbl_patient_payments 
												WHERE Registration_ID = '$Registration_ID' 
												ORDER BY Patient_Payment_ID DESC LIMIT 1";
					$folioNumberResult = mysqli_query($conn,$folio_qr) or die(mysqli_error($conn));
					$Folio_Number = mysqli_fetch_assoc($folioNumberResult)['Folio_Number'];
				}
		//2. insert to revenue center
			 // A. tbl_patient_payments
				$payment_qr = "INSERT INTO tbl_patient_payments( Registration_ID,
																 Supervisor_ID,
																 Employee_ID,
																 Payment_Date_And_Time,
																 Folio_Number,
																 Sponsor_ID,
																 Sponsor_Name,
																 Billing_Type,
																 Receipt_Date,
																 branch_id,
																 payment_mode) 
											SELECT Registration_ID,
												   Supervisor_ID,
												   Employee_ID,
												   '$online_payment_date_and_time',
												   '$Folio_Number',
												   '$Sponsor_ID',
												   '$Sponsor_Name',
												   bill_type,
												   (SELECT NOW()),
												   '$branch_id',
												   '$payment_mode'
												   FROM tbl_mobile_payment WHERE payment_code = '$code' LIMIT 1";
				mysqli_query($conn,$payment_qr) or die("mysqli_error($conn)");

				// A. 1 Get Payment_ID
					$paymentID_qr = "SELECT Patient_Payment_ID FROM tbl_patient_payments 
													WHERE Registration_ID='$Registration_ID' 
													AND Supervisor_ID = '$Supervisor_ID'
													AND Payment_Date_And_Time='$online_payment_date_and_time' 
													AND Folio_Number = '$Folio_Number' 
													ORDER BY Patient_Payment_ID DESC
													LIMIT 1";
					$paymentIDresult = mysqli_query($conn,$paymentID_qr) or die(mysqli_error($conn));

					$Patient_Payment_ID = mysqli_fetch_assoc($paymentIDresult)['Patient_Payment_ID'];

			 // B. tbl_patient_payment_item_list
					$paymentList_qr = "INSERT INTO tbl_patient_payment_item_list(	Check_In_Type,
																					Item_ID,
																					Item_Name,
																					Discount, 
																					Price, 
																					Quantity,
																					Category,
																					Patient_Direction, 
																					Consultant, 
																					Consultant_ID,
																					Patient_Payment_ID, 
																					Transaction_Date_And_Time)
													SELECT Check_In_Type,
														   item_ID,
														   Item_Name,
														   Discount,
														   Price,
														   Quantity,
														   Category,
														   Patient_Direction,
														   Consultant,
														   Consultant_ID,
														   '$Patient_Payment_ID',
														   '$online_payment_date_and_time'
														   FROM tbl_mobile_payment WHERE payment_code = '$code'";
					mysqli_query($conn,$paymentList_qr) or die(mysqli_error($conn));
		//3. update Mobile payment table
			$updatePayment = "UPDATE tbl_mobile_payment SET 
											Patient_Payment_ID = '$Patient_Payment_ID',
											online_payment_date_and_time = '$online_payment_date_and_time',
											payment_status = '$payment_status' 
											WHERE payment_code = '$code'";

			mysqli_query($conn,$updatePayment) or die(mysqli_error($conn));

		//update last checkin status for new visit
			if($visit_status == 'new'){
				$ch_qr = "SELECT MAX(Check_In_ID) as Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID'";

				$ch_result = mysqli_query($conn,$ch_qr) or die(mysqli_error($conn));

				$Check_In_ID = mysqli_fetch_assoc($ch_result)['Check_In_ID'];

				$updateCheckin = "UPDATE tbl_check_in SET Check_In_Status = 'saved'
								  WHERE Check_In_ID = $Check_In_ID";

				mysqli_query($conn,$updateCheckin) or die(mysqli_error($conn));
			}
			echo "Transaction Complete: $Patient_Payment_ID";
	}else{
		echo "Transaction Incomplete";
	}
?>