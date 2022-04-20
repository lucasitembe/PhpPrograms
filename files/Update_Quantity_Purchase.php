<?php
    @session_start();
    include("./includes/connection.php");
    
    //get containers
    if(isset($_GET['Containers'])){
        $Containers = $_GET['Containers'];
    }else{
        $Containers = 0;
    }

    //get items per containers
    if(isset($_GET['Items_Per_Container'])){
        $Items_Per_Container = $_GET['Items_Per_Container'];
    }else{
        $Items_Per_Container = 0;
    }
    
    //get Purchase_Cache_ID
    if(isset($_GET['Purchase_Cache_ID'])){
        $Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
    }else{
        $Purchase_Cache_ID = 0;
    }
    
    $Quantity_Required = ($Containers * $Items_Per_Container);
    $update = mysqli_query($conn,"update tbl_purchase_cache set Items_Per_Container = '$Items_Per_Container', Container_Qty = '$Containers', Quantity_Required = '$Quantity_Required' where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
    echo $Quantity_Required;
?>