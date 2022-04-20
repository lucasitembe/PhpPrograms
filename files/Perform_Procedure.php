<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	//update procedures if not saved
	$select = mysqli_query($conn,"select Payment_Item_Cache_List_ID from tbl_item_list_cache ilc, tbl_payment_cache pc where
							pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
							ilc.Check_In_Type = 'Procedure' and
							pc.consultation_ID = '$consultation_ID' and
							pc.Employee_ID = '$Employee_ID' and
							ilc.Status = 'notsaved' and
							pc.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while($data = mysqli_fetch_array($select)){
			$Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
			mysqli_query($conn,"update tbl_item_list_cache set Status = 'active' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Status = 'notsaved'") or die(mysqli_error($conn));
		}
	}
?>