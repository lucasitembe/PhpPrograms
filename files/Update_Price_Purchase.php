<?php
    @session_start();
    include("./includes/connection.php");
    
    //get price
    if(isset($_GET['Price'])){
        $Price = $_GET['Price'];
    }else{
        $Price = 0;
    }
    
    //get Purchase_Cache_ID
    if(isset($_GET['Purchase_Cache_ID'])){
        $Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
    }else{
        $Purchase_Cache_ID = 0;
    }
    
    //update price
    
    $update = mysqli_query($conn,"update tbl_purchase_cache set Price = '$Price' where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
    if($update){
        //select the last payment
        
        $select = mysqli_query($conn,"select Price from tbl_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
        while($data = mysqli_fetch_array($select)){
            $Price = $data['Price'];
        }
    }else{
        $Price = 0;
    }
    
    echo $Price;
?>