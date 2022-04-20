<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}
	
	if(isset($_GET['Sub_Dep_ID'])){
		$Sub_Dep_ID = $_GET['Sub_Dep_ID'];
	}else{
		$Sub_Dep_ID = '';
	}

	//generate sql
	if($Sub_Department_ID == $Sub_Dep_ID){
		mysqli_query($conn,"delete from tbl_change_medication_location_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	}else{
		mysqli_query($conn,"insert into tbl_change_medication_location_cache(
						Payment_Item_Cache_List_ID, Sub_Department_ID, Employee_ID)
					values('$Payment_Item_Cache_List_ID','$Sub_Dep_ID','$Employee_ID')") or die(mysqli_error($conn));
	}
?>