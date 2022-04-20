<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get requisition id
    if(isset($_SESSION['Laboratory_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Laboratory_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Submitted', Sent_Date_Time = (select now()) where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        if($update_sql){
            unset($_SESSION['Laboratory_Requisition_ID']);
            header("Location: ./laboratorypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
        }else{
            header("Location: ./laboratorypreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
        }
    }
?>