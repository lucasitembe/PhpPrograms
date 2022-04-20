<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['General_Order_ID'])){
		$Store_Order_ID = $_SESSION['General_Order_ID'];
	}else{
		$Store_Order_ID = 0;
	}

	if(isset($_SESSION['General_Order_ID'])){
		$select = mysqli_query($conn,"select Control_Status from tbl_store_orders where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Control_Status = $data['Control_Status'];
			}
		}else{
			$Control_Status = '';
		}

		if($Control_Status == 'available'){
			echo "yes";
		}else if($Control_Status == 'editing'){
			echo "editing";
		}else{
			echo "no";
		}
	}else{
		echo "not";
	}
?>