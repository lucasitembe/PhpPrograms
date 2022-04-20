<?php
	session_start();
	include("./includes/connection.php");
	$counter = 0;
	
	//get employee name
   if(isset($_SESSION['userinfo']['Employee_ID'])){
      $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
   }else{
      $Employee_ID = '';
   }

   	
   	//get employee name
   if(isset($_SESSION['userinfo']['Branch_ID'])){
      $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
   }else{
      $Branch_ID = '';
   }

	$select = mysqli_query($conn,"select Registration_ID, Payment_Date_And_Time from tbl_payment_cache") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select);
	if($no > 0){
		while($row = mysqli_fetch_array($select)){
			$Registration_ID = $row['Registration_ID'];
			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];

			//check check in table
			$check = mysqli_query($conn,"select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($check);
			if($num <= 0){
				//insert into check in table
				$result = mysqli_query($conn,"INSERT INTO tbl_check_in(
                                    Registration_ID, Visit_Date, Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
                                    Check_In_Date, Type_Of_Check_In) 

                                    VALUES ('$Registration_ID','$Payment_Date_And_Time','$Employee_ID',
                                            '$Payment_Date_And_Time','pending','$Branch_ID',
                                            '$Payment_Date_And_Time','Afresh')") or die(mysqli_error($conn));
				if($result){
					$counter = $counter + 1;
				}
			}
		}
	}
?>