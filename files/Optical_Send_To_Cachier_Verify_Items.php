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
		$Registration_ID = 0;
	}

	$select_Transaction_Items = mysqli_query($conn,"select Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Comment,Sub_Department_ID
                                                from tbl_items t, tbl_departmental_items_list_cache alc
                                                where alc.Item_ID = t.Item_ID and
                                                alc.Employee_ID = '$Employee_ID' and
                                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$no_of_items = mysqli_num_rows($select_Transaction_Items);
	if($no_of_items > 0){
		echo "yes";
	}else{
		echo "no";
	}
?>