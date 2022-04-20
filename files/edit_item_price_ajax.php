<?php
    include("./includes/connection.php");
    $Item_ID = $_GET['Item_ID'];
    $Selling_Price_Cash = $_GET['Selling_Price_Cash'];
    $Selling_Price_Credit = $_GET['Selling_Price_Credit'];
    $Selling_Price_NHIF = $_GET['Selling_Price_NHIF'];
    
    $sql= "UPDATE tbl_items SET Selling_Price_Cash='$Selling_Price_Cash',
            Selling_Price_Credit='$Selling_Price_Credit',Selling_Price_NHIF='$Selling_Price_NHIF'
            WHERE Item_ID = $Item_ID";
    if(mysqli_query($conn,$sql)){
       echo 'Item Updated'; 
    }else{
        echo 'Item Not Updated';
    }
?>