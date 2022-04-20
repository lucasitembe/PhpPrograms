<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            
            if(isset($_GET['New_Grn_Open_Balance'])){
                    unset($_SESSION['Grn_Open_Balance_ID']);
                header("Location: ./grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage");
            }elseif(isset($_GET['Pending_Grn_Open_Balance']) && isset($_GET['Grn_Open_Balance_ID'])){ //Grn_Open_Balance_ID is the value from the list
                $_SESSION['Grn_Open_Balance_ID'] = $_GET['Grn_Open_Balance_ID'];
                header("Location: ./grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage");
            }elseif(isset($_GET['Previous_Grn_Open_Balance'])){
                $_SESSION['Grn_Open_Balance_ID'] = $_GET['Grn_Open_Balance_ID'];
                header("Location: ./previousopenbalances.php?Status=PreviousOpenBalances&OpenBalance=OpenBalanceThisPage");
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