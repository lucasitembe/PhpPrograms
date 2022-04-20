<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['Edit_General_Order_ID'])){
		$Store_Order_ID  = $_SESSION['Edit_General_Order_ID'];
	}else{
		$Store_Order_ID = 0;
	}


	//get created date
	$select = mysqli_query($conn,"select Created_Date_Time from tbl_store_orders where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Created_Date_Time = $data['Created_Date_Time'];
		}
	}else{
		$Created_Date_Time = '';
	}
	echo $Created_Date_Time;
?>