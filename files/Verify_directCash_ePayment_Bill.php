<?php
	session_start();
	include("./includes/connection.php");
	$Control = 'yes';

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	}

	//verify items
	$select = mysqli_query($conn,"select sum(Amount * IF(Quantity IS NULL,1,Quantity)) as Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			if($row['Amount'] < 1){
				$Control = 'no';
			}
//			
//			if($row['Quantity'] < 1){
//				$Control = 'no';
//			}
		}
	}
	echo $Control;
?>