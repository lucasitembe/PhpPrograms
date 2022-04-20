<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            if(isset($_GET['New_Grn']) && $_GET['Issue_ID']){
                $_SESSION['Laboratory_Issue_ID'] = $_GET['Issue_ID'];
                header("Location: ./laboratorygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage");
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