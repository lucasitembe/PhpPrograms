<?php
    @session_start();
    include("./includes/connection.php");
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //select all items with no price & quantity
    
    $select = mysqli_query($conn,"select Purchase_Cache_ID from tbl_purchase_cache where
                            Quantity_Required = 0 or Price = 0 and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    
    $num = mysqli_num_rows($select);
    
    if($num > 0){
        echo 'yes';
    }else{
        echo 'no';
    }
?>