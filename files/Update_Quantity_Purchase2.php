<?php
    @session_start();
    include("./includes/connection.php");
    
    //get price
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    
    //get Purchase_Cache_ID
    if(isset($_GET['Purchase_Cache_ID'])){
        $Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
    }else{
        $Purchase_Cache_ID = 0;
    }
    
    //update price
    
    $update = mysqli_query($conn,"update tbl_purchase_cache set Quantity_Required = '$Quantity', Container_Qty = 0, Items_Per_Container = 0 where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
    if($update){
        //select the last payment
        
        $select = mysqli_query($conn,"select Quantity_Required from tbl_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
        while($data = mysqli_fetch_array($select)){
            $Quantity_Required = $data['Quantity_Required'];
        }
    }else{
        $Quantity_Required = 0;
    }
    echo $Quantity_Required;
?>