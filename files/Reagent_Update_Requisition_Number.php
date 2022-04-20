 <?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['Reagents_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Reagents_Requisition_ID'];
    }else{
        $Requisition_ID = 'new';
    }
    echo $Requisition_ID;    
?>