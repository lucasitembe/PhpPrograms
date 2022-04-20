<?php
    session_start();
    include("./includes/connection.php");

  
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['New_Process_Requisition']) && isset($_GET['Requisition_ID'])){
            unset($_SESSION['Engineering_Works']);
            $_SESSION['Process_Requisition']['Requisition_ID'] = $_GET['Requisition_ID'];
            header("Location: ./assign_engineering_requisition.php?Status=New_Process_Requisition&Process_Requisition=Process_RequisitionThisPage");
        } else {
            echo 'Something was wrong!';
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>