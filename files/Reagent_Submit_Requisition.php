 <?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(!isset($_SESSION['userinfo'])) {
        @session_start();
        if(!isset($_SESSION['Departmental_Requisition_Control'])){
            header("Location: ./index.php?InvalidSupervisorAuthentication=yes");
        }
    }
    
    //get requisition id
    if(isset($_SESSION['Reagents_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Reagents_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
    
    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_reagents_requisition set Requisition_Status = 'Submitted', Sent_Date_Time = (select now()) where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        unset($_SESSION['Reagents_Requisition_ID']);
        header("Location: ./reagentspreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
    }
?>