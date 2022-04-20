<?php
	@session_start();
	include("/includes/connection.php");


	//get any department
	$select = mysqli_query($conn,"select Department_ID from tbl_department limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Department_ID = $data['Department_ID'];
		}
	}else{
		$Department_ID = 0;
	}

	//generate username and password via date & time
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }


	//get any branch name
	$select = mysqli_query($conn,"select Branch_Name, Branch_ID from tbl_branches limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Branch_Name = $data['Branch_Name'];
			$Branch_ID = $data['Branch_ID'];
		}
	}else{
		$Branch_Name = '';
		$Branch_ID = 0;
	}

	$insert = mysqli_query($conn,"insert into tbl_employee(
							Employee_Name, Employee_Type, Employee_Number,
							Employee_Title, Employee_Job_Code, Employee_Branch_Name, Department_ID)
				    		
				    		values('CRDB','Others','999',
					    		'Accountant','Others','$Branch_Name','$Department_ID')") or die(mysqli_error($conn));

	//get employee id
	$slt = mysqli_query($conn,"select Employee_ID from tbl_employee where Employee_Name = 'CRDB'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($slt);
	if($nm > 0){
		while ($data = mysqli_fetch_array($slt)) {
			$Employee_ID = $data['Employee_ID'];
		}
	}else{
		$Employee_ID = 0;
	}

	$Password = md5('crdb');

	if($insert){
		//add privileges
		$privilege_insert = mysqli_query($conn,"insert into tbl_privileges(
											Employee_ID, Given_Username, Given_Password, Reception_Works, Revenue_Center_Works, Patients_Billing_Works, Procurement_Works, Mtuha_Reports, General_Ledger, Management_Works,
											Nurse_Station_Works, Admission_Works, Laboratory_Works, Radiology_Works, Quality_Assurance, Dressing_Works, Dialysis_Works, Theater_Works, Physiotherapy_Works, Maternity_Works,
											Dental_Works, Eye_Works, Cecap_Works, Modify_Cash_information, Session_Master_Priveleges, Cash_Transactions, Modify_Credit_Information, Setup_And_Configuration, Doctors_Page_Inpatient_Work, Doctors_Page_Outpatient_Work,
											Pharmacy, Storage_And_Supply_Work, Ear_Works, Employee_Collection_Report, Rch_Works, Hiv_Works, Family_Planning_Works, Morgue_Works, Blood_Bank_Works, Food_And_Laundry_Works,
											Appointment_Works, Laboratory_Result_Validation, Patient_Transfer, Patient_Record_Works, Laboratory_Consulted_Patients
										   )
										   values(
											'$Employee_ID','$Today','$Today','no','no','no','no','no','no','no',
											'no','no','no','no','no','no','no','no','no','no',
											'no','no','no','no','no','no','no','no','no','no',
											'no','no','no','no','no','no','no','no','no','no',
											'no','no','no','no','no')") or die(mysqli_error($conn));

		if($privilege_insert){
			$insert_branch = mysqli_query($conn,"insert into tbl_branch_employee(Employee_ID,Branch_ID)
											values('$Employee_ID','$Branch_ID')") or die(mysqli_error($conn));
		}
	}
	$Emp_ID = $Employee_ID;	
?>