<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = null;
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = 0;
	}

		$update = mysqli_query($conn,"UPDATE tbl_check_in_details set ToBe_Admitted = 'no', 	Employee_Cancel = '$Employee_ID',	Date_Cancelled = (select now()) where 	ToBe_Admitted = 'yes' and 	Admit_Status = 'not admitted' and 	Registration_ID = '$Registration_ID' and 	Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
							
        //delete incomplete admsion for this patient
        $sql_delete_incomplete_result=mysqli_query($conn,"DELETE   FROM tbl_admission  WHERE Registration_ID='$Registration_ID' AND ward_room_id='0' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if($update && $sql_delete_incomplete_result){
            echo "success";
        }else{
			echo "failed";
		}
?>