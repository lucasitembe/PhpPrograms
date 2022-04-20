<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['New_Requisition'])){
        unset($_SESSION['Requisition_ID']);
        header("Location: ./requisition.php?Status=New&Requisition=RequisitionThisPage");
    }else if(isset($_GET['New_Requisition_General'])){
        unset($_SESSION['General_Requisition_ID']);
        header("Location: ./generalrequisition.php?status=new&NPO=True&Requisition=RequisitionThisPage");
    }elseif(isset($_GET['Pending_Requisition']) && isset($_GET['Requisition_ID'])){
        $_SESSION['Requisition_ID'] = $_GET['Requisition_ID'];
        header("Location: ./requisition.php?Status=PendingRequisition&Requisition=RequisitionThisPage");
    }elseif(isset($_GET['Pending_Requisition_General']) && isset($_GET['Requisition_ID'])){
        $_SESSION['General_Requisition_ID'] = $_GET['Requisition_ID'];
        header("Location: ./generalrequisition.php?Status=PendingRequisition&Requisition=RequisitionThisPage");
    }else{
        echo 'Something was wrong!!';
    }
?>