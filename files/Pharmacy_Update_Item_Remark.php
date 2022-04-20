<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Pharmacy'])){
                if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    @session_start();
                    if(!isset($_SESSION['Storage_Supervisor'])){
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
    
    if(isset($_GET['Requisition_Item_ID'])){
        $Requisition_Item_ID = $_GET['Requisition_Item_ID'];
    }else{
        $Requisition_Item_ID = 0;
    }

    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }

    if($Requisition_Item_ID != 0 && $Requisition_Item_ID != '' && $Requisition_Item_ID != null){
        //update Item requisition remark automatically
        $update_details = mysqli_query($conn,"update tbl_requisition_items set Item_Remark = '$Item_Remark' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
        if(!$update_details){
            die(mysqli_error($conn));
        }
    }
?>