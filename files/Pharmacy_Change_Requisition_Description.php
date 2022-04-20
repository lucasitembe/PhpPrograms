<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Pharmacy'])){
                if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    //@session_start();
                    if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
                        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
                }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['Pharmacy_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    if(isset($_GET['Requisition_Description'])){
        $Requisition_Description = $_GET['Requisition_Description'];
    }else{
        $Requisition_Description = '';
    }

    if($Requisition_ID != 0 && $Requisition_ID != '' && $Requisition_ID != null){
        //update requisition description automatically
        $update_details = mysqli_query($conn,"update tbl_requisition set Requisition_Description = '$Requisition_Description' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        if(!$update_details){
            die(mysqli_error($conn));
        }
    }
?>