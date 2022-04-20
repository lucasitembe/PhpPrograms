 <?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['Storage_Info'])){
        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['New_Issue_Note']) && isset($_GET['Requisition_ID'])){
            unset($_SESSION['Purchase_Order_ID']);
            $_SESSION['Reagent_Issue_Note']['Requisition_ID'] = $_GET['Requisition_ID'];
            header("Location: ./reagentissuenotes.php?Status=NewReagentIssueNote&IssueNote=IssueNoteThisPage");
        }else{
            echo 'Something was wrong!!';
        }
    }else{
        header("Location: ../index.php?InvalidPrivilege=True");
    }
?>