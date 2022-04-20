<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            if(isset($_GET['New_Grn']) && $_GET['Issue_ID']){
                if(isset($_SESSION['Grn_Issue_ID'])){
                    unset($_SESSION['Grn_Issue_ID']);
                }
                
                $_SESSION['Grn_Issue_ID'] = $_GET['Issue_ID'];
                $_SESSION['Requisition_ID'] = $_GET['Requisition_ID'];
                header("Location: ./grnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage");
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