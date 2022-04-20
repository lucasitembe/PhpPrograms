<?php

    include("./includes/connection.php");
    $Select_Price='';
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
	//$Billing_Type = $_GET['Billing_Type'];
	//$Guarantor_Name = $_GET['Guarantor_Name'];
	
        $Select_Price="SELECT Status  FROM tbl_items WHERE Item_ID='$Item_ID'";
        $result = mysqli_query($conn,$Select_Price);
        $row = mysqli_fetch_assoc($result);
        echo $row['Status'];
    }else{
	echo 'Not Available';
    }
    
    
?>