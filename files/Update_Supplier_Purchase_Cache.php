<?php
    @session_start();
    include("./includes/connection.php");
    
    //get price
    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = $_GET['Supplier_ID'];
    }else{
        $Supplier_ID = 0;
    }
    
    //get Purchase_Cache_ID
    if(isset($_GET['Purchase_Cache_ID'])){
        $Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
    }else{
        $Purchase_Cache_ID = 0;
    }
    
    //update price
    
    $update = mysqli_query($conn,"update tbl_purchase_cache set Supplier_ID = '$Supplier_ID' where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
    if($update){
        //select the last payment
        
        $select = mysqli_query($conn,"select Supplier_ID from tbl_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
        while($data = mysqli_fetch_array($select)){
            $Supplier_ID = $data['Supplier_ID'];
        }
    }else{
        $Supplier_ID = 0;
    }
    
    echo $Supplier_ID;
?>