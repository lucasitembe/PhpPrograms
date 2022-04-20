<?php
	include("./includes/connection.php");
	if(isset($_GET['Approval_Level'])){
		$Approval_Level = $_GET['Approval_Level'];
	}else{
		$Approval_Level = '';
	}

	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	//get Approval id
	$select = mysqli_query($conn,"select Approval_ID from tbl_approval_level where Approval_Title = '$Approval_Level'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Approval_ID = $data['Approval_ID'];
		}
	}else{
		$Approval_ID = 0;
	}

	if($Approval_ID != 0){
		//check if selected employee already assigned another level
		$check = mysqli_query($conn,"select * from tbl_approval_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);
		if($num > 0){
			//update role level
			$update = mysqli_query($conn,"update tbl_approval_employee set Approval_ID = '$Approval_ID' where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
			echo "yes";
		}else{
			$insert = mysqli_query($conn,"insert into tbl_approval_employee(Employee_ID,Approval_ID) values('$Employee_ID','$Approval_ID')") or die(mysqli_error($conn));
			if($insert){
				echo "yes";
			}else{
				echo "no";
			}
		}
	}else{
		echo "no";
	}
?>