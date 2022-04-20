<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
            
            if(isset($_GET['New_Grn_Open_Balance'])){
                    unset($_SESSION['Pharmacy_Grn_Open_Balance_ID']);
                header("Location: ./pharmacygrnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage");
            }elseif(isset($_GET['Pending_Grn_Open_Balance']) && isset($_GET['Pharmacy_Grn_Open_Balance_ID'])){ //Pharmacy_Grn_Open_Balance_ID is the value from the list
                $_SESSION['Pharmacy_Grn_Open_Balance_ID'] = $_GET['Pharmacy_Grn_Open_Balance_ID'];
                header("Location: ./pharmacygrnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage");
            }elseif(isset($_GET['Previous_Grn_Open_Balance'])){
                $_SESSION['Pharmacy_Grn_Open_Balance_ID'] = $_GET['Pharmacy_Grn_Open_Balance_ID'];
                header("Location: ./pharmacypreviousopenbalances.php?Status=PreviousOpenBalances&OpenBalance=OpenBalanceThisPage");
            }else{
                echo 'Something was wrong!!';
            }
            
        }else{
            header("Location: ./index.php?InvalidPrivilege=True");
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>