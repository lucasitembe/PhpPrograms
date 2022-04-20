<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	//get details
	$select = mysqli_query($conn,"select * from tbl_change_medication_location_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
			$Sub_Department_ID = $data['Sub_Department_ID'];
			mysqli_query($conn,"update tbl_item_list_cache set Sub_Department_ID = '$Sub_Department_ID' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		}
	}
?>