<?php
    @session_start();
    include("../includes/connection.php");
    
    
    //get order item id
    
    if(isset($_GET['mobile_payment_id'])){
        $mobile_payment_id = $_GET['mobile_payment_id'];
    }else{
        $mobile_payment_id = 0;
    }
    
    $delete_item = mysqli_query($conn,"delete from tbl_mobile_payment where mobile_payment_id = '$mobile_payment_id' ") or die(mysqli_error($conn));
    
    if($delete_item){
        echo 'DONE';
    }
    
?>