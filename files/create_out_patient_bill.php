<?php
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




