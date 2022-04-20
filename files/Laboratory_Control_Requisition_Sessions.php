<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){

            if(isset($_GET['New_Requisition'])){
                unset($_SESSION['Laboratory_Requisition_ID']);
                header("Location: ./laboratoryrequisition.php?Status=New&Requisition=RequisitionThisPage");
            }
            elseif(isset($_GET['Pending_Requisition']) && isset($_GET['Requisition_ID'])){
                $_SESSION['Laboratory_Requisition_ID'] = $_GET['Requisition_ID'];
                header("Location: ./laboratoryrequisition.php?Status=New&Requisition=RequisitionThisPage");
            }

            /*elseif(isset($_GET['Quick_Requisition'])){
                unset($_SESSION['Laboratory_Requisition_ID']);
                header("Location: ./quickrequisitioninsert.php?Status=QuickRequisition&QuickRequisition=QuickRequisitionThisPage");
            }elseif(isset($_GET['Self_Quick_Requisition'])){
                unset($_SESSION['Laboratory_Requisition_ID']);
                header("Location: ./quickpharmacyrequisition.php?Status=QuickRequisition&QuickRequisition=QuickRequisitionThisPage");
            }else{
                echo 'Something was wrong!!';
            }*/

        }else{
            header("Location: ./index.php?InvalidPrivilege=True");
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>