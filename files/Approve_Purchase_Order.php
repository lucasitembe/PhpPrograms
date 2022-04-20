<?php
	session_start();
	include("./includes/connection.php");
	$Control = 'no';

	if(isset($_GET['Purchase_Order_ID'])){
		$Purchase_Order_ID = $_GET['Purchase_Order_ID'];
	}else{
		$Purchase_Order_ID = '';
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_SESSION['Procurement_Autentication_Level'])){
		$Approval_ID = $_SESSION['Procurement_Autentication_Level'];
	}else{
		$Approval_ID = '';
	}

	if(isset($_GET['Username'])){
		$Username = $_GET['Username'];
	}else{
		$Username = '';
	}

	if(isset($_GET['Password'])){
		$Password = md5($_GET['Password']);
	}else{
		$Password = md5('');
	}

	//Validate username & password
	if(isset($_SESSION['userinfo'])){

		if((isset($_SESSION['userinfo']['Given_Username'])) && (strtolower($_SESSION['userinfo']['Given_Username']) == strtolower($Username))
            && (isset($_SESSION['userinfo']['Given_Password'])) && ($_SESSION['userinfo']['Given_Password'] == $Password)){
			//check if not approved
			$check = mysqli_query($conn,"select Purchase_Approval_ID from tbl_purchase_order_approval_process where Purchase_Order_ID = '$Purchase_Order_ID' and Approval_ID = '$Approval_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($check);
			if($num < 1){
				//insert tbl_purchase_order_approval_process
				$insert = mysqli_query($conn,"insert into tbl_purchase_order_approval_process(Purchase_Order_ID, Employee_ID, Approval_ID, Approval_Date_Time)
										values('$Purchase_Order_ID','$Employee_ID','$Approval_ID',(select now()))") or die(mysqli_error($conn));
				if($insert){
					//update Approval_Level
					$update = mysqli_query($conn,"update tbl_purchase_order set Approval_Level = '$Approval_ID' where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
					if($update){
						$Control = 'yes';
					}
				}else{
					$Control = 'no';
				}
			}else{
				$Control = 'approved';
			}
		}else{
			$Control = 'invalid';
		}
	}else{
		$Control = 'no';
	}
	echo $Control;
?>