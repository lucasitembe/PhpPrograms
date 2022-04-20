<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){

            if(isset($_GET['New_Consumption'])){
                unset($_SESSION['Laboratory_Consumption_ID']);
                header("Location: ./employeeconsumptionnote.php?EmployeeeConsumptionNote=EmployeeeConsumptionNoteThisPage");
            }
            elseif(isset($_GET['Pending_Consumption']) && isset($_GET['Consumption_ID'])){
                $_SESSION['Laboratory_Consumption_ID'] = $_GET['Consumption_ID'];
                header("Location: ./employeeconsumptionnote.php?EmployeeeConsumptionNote=EmployeeeConsumptionNoteThisPage");
            }
            
        }else{
            header("Location: ./index.php?InvalidPrivilege=True");
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>