<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['Pending_Disposal']) && isset($_GET['Disposal_ID'])){
            $_SESSION['Disposal_ID'] = $_GET['Disposal_ID'];
            header("Location: ./itemsdisposal.php?Disposal=DisposalThisPage");
        }elseif(isset($_GET['New_Disposal'])){ 
            if(isset($_SESSION['Disposal_ID'])){
                unset($_SESSION['Disposal_ID']);
            }
            header("Location: ./itemsdisposal.php?Disposal=DisposalThisPage");
        }else{
            echo 'Something was wrong!!';
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>