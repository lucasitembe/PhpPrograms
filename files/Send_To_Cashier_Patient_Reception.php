<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Patient_Payment_Cache_ID'])){
        $Patient_Payment_Cache_ID = $_GET['Patient_Payment_Cache_ID'];
    }else{
        $Patient_Payment_Cache_ID = 0;
    }
    
    
    $sql_send = mysqli_query($conn,"update tbl_patient_payments_cache set Transaction_status = 'submitted'
                                where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID' and Transaction_status = 'pending'") or die(mysqli_error($conn));
    
    if($sql_send){ 
        header("Location: ./visitorform.php?VisitorForm=VisitorFormThisPage");
    } 
?>