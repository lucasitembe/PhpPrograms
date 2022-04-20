<?php
	session_start();
	include("./includes/connection.php");
	$Control = 'yes';

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = 0;
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = 0;
	}

	if(isset($_GET['clinic_location_id'])){
		$clinic_location_id = $_GET['clinic_location_id'];
	}else{
		$clinic_location_id = 0;
	}


	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = 0;
	}

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

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = 0;
	}

	if($Transaction_Type == 'Credit_Bill_Details'){
		$Billing_Type = 'Outpatient Cash';
	}else if($Transaction_Type == 'Cash_Bill_Details'){
		$Billing_Type = 'Outpatient Cash';
	}else{
		$Billing_Type = 'Outpatient Cash';
	}
$slct_Billing_Type = mysqli_query($conn,"select Billing_Type from tbl_inpatient_items_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'");
if(mysqli_num_rows($slct_Billing_Type)>0){
    $Billing_Type=mysqli_fetch_assoc($slct_Billing_Type)['Billing_Type'];
}	

//get guarantor name
	$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Guarantor_Name = $row['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	//get Claim Form number
	$select = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' and Check_In_ID = '$Check_In_ID' and Patient_Bill_ID = '$Patient_Bill_ID' limit 1") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($dt = mysqli_fetch_array($select)) {
			$Claim_Form_Number = $dt['Claim_Form_Number'];
		}
	}else{
		$Claim_Form_Number = '';
	}


	//insert data into tbl_patient_payments
	$insert = mysqli_query($conn,"insert into tbl_patient_payments(
							Registration_ID, Supervisor_ID, Employee_ID, 
							Payment_Date_And_Time, Folio_Number, Check_In_ID,
							Claim_Form_Number, Sponsor_ID, Sponsor_Name, 
							Billing_Type, Receipt_Date, Branch_ID, Patient_Bill_ID,Pre_Paid) 

						VALUES ('$Registration_ID','$Employee_ID','$Employee_ID',
							(select now()),'$Folio_Number','$Check_In_ID',
							'$Claim_Form_Number','$Sponsor_ID','$Guarantor_Name',
							'$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID','1')") or die(mysqli_error($conn));
	if($insert){
		//get last Patient_Payment_ID
		$get = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments where 
							Registration_ID = '$Registration_ID' and 
							Employee_ID = '$Employee_ID' and 
							Patient_Bill_ID = '$Patient_Bill_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
		$nmz = mysqli_num_rows($get);
		if($nmz > 0){
			while ($data = mysqli_fetch_array($get)) {
				$Patient_Payment_ID = $data['Patient_Payment_ID'];
			}

			//select data from cache
			$slct = mysqli_query($conn,"select * from tbl_inpatient_items_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
			$numb = mysqli_num_rows($slct);
			if($numb > 0){
				while ($rw = mysqli_fetch_array($slct)) {
					$Item_Cache_ID = $rw['Item_Cache_ID'];
					$Check_In_Type = $rw['Check_In_Type'];
					$Item_ID = $rw['Item_ID'];
					$Price = $rw['Price'];
					$Quantity = $rw['Quantity'];
					$Discount = $rw['Discount'];
					$Clinic_ID = $rw['Clinic_ID'];

					$insert2 = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
												Check_In_Type, Item_ID, Discount, Price, Quantity, 
												Patient_Direction, Consultant, Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,Sub_Department_ID) 

											VALUES ('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity',
												'others','$Employee_Name','$Employee_ID','$Patient_Payment_ID',(select now()),'$Clinic_ID','$clinic_location_id')") or die(mysqli_error($conn));
					if($insert2){
						mysqli_query($conn,"delete from tbl_inpatient_items_cache where Item_Cache_ID = '$Item_Cache_ID'") or die(mysqli_error($conn));
					}
				} echo 'yes';
			}
		}else{
			echo 'no';
		}
	}else{
		echo 'no';
	}
?>