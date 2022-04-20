<?php
    @session_start();
    include("./includes/connection.php");
    
    
    //get Purchase_Order_ID
    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
    
    $update_order = mysqli_query($conn,"update tbl_requisition set Sent_Date_Time = (select now()), Requisition_Status = 'submited'
                                    where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    
    if($update_order){
        echo "<script>
                document.location = 'previousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage';
                </script>";
    }    
?>