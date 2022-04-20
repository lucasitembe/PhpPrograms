<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['New_Requisition'])){
        unset($_SESSION['Admission_Requisition_ID']);
        header("Location: ./admissionrequisition.php?status=new&NPO=True&Requisition=RequisitionThisPage");
    }elseif(isset($_GET['Pending_Requisition']) && isset($_GET['Requisition_ID'])){
        $_SESSION['Admission_Requisition_ID'] = $_GET['Requisition_ID'];
        header("Location: ./admissionrequisition.php?Status=PendingRequisition&Requisition=RequisitionThisPage");
    }else{
        echo 'Something was wrong!!';
    }
?>