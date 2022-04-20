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
        
        
$lookup='tbl_departmental_items_list_cache';
if(isset($_GET['src']) && $_GET['src']=='drinp'){
  $lookup='tbl_inpatient_items_list_cache';  
}

	//verify items
	$select = mysqli_query($conn,"select Price, Quantity from $lookup where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			if($row['Price'] < 1){
				$Control = 'no';
			}
			
			if($row['Quantity'] < 1){
				$Control = 'no';
			}
		}
	}
	echo $Control;
?>