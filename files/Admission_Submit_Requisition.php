<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Admission_Works'])){
            if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Admission_Supervisor'])){
                    header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
                }
            }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get requisition id
    if(isset($_SESSION['Admission_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Admission_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Submitted', Sent_Date_Time = (select now()) where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        header("Location: ./admissionpreviousrequisitions.php?PreviousRequisitions=PreviousRequisitionsThisPage");
    }
?>