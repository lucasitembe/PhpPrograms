<?php
    include("./includes/connection.php");
    
    if(isset($_GET['Product_Name'])&&($_GET['Product_Name']!='')){
        $Product_Name = $_GET['Product_Name'];
        $Billing_Type = $_GET['Billing_Type'];
        $Guarantor_Name = $_GET['Guarantor_Name'];
        
        //run the query to select item status
	$Item_Status="SELECT * FROM tbl_items WHERE Item_ID = '$Product_Name' ";
                $result = exit($Item_Status);
                $row = mysqli_fetch_assoc($result);
        echo $row['Status'];
    }
    
?>