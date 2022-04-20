<?php
	session_start();
	include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    $check = mysqli_query($conn,"select Item_ID from tbl_edit_price_cache where Employee_ID = '$Employee_ID' limit 1	") or die(mysqli_error($conn));
    $num = mysqli_num_rows($check);
    if($num > 0){
    	echo "yes";
    }else{
    	echo "no";
    }
?>