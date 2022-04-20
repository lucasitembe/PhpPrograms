<?php
	include("./includes/connection.php");
	$select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from tbl_stock_balance_sub_departments sb, tbl_sub_department sd where
                            sd.Sub_Department_ID = sb.Sub_Department_ID order by Sub_Department_Name") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 50){
    	echo "no";
    }else{
    	echo "yes";
    }
?>