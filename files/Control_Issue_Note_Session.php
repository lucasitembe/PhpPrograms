<?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['Storage_Info'])){
        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['New_Issue_Note']) && isset($_GET['Requisition_ID'])){
            unset($_SESSION['Purchase_Order_ID']);
            $_SESSION['Issue_Note']['Requisition_ID'] = $_GET['Requisition_ID'];
            header("Location: ./issuenotes.php?Status=NewIssueNote&IssueNote=IssueNoteThisPage");
        } else if (isset($_GET['Edit_Issue_Note']) && isset($_GET['Issue_Note_ID'])){
            unset($_SESSION['Issue_Note_ID']);
            $_SESSION['Issue_Note']['Issue_Note_ID'] = $_GET['Issue_Note_ID'];
            header("Location: ./issuenotes_edit.php?Status=EditIssueNote&IssueNote=IssueNoteThisPage");
        } else {
            echo 'Something was wrong!!';
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>