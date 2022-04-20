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

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	//check if available
	$Payment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID' AND Receipt_Date = CURDATE() ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
		if($Payment_Cache_ID > 0){
			$select = mysqli_query($conn,"SELECT Item_ID from tbl_item_list_cache where Payment_Cache_ID = '$Payment_Cache_ID' and	Item_ID = '$Item_ID' AND status = 'active'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				echo "no";
			}else{
				echo "yes";
			}
		}else{
			echo "yes";
		}
		
?>