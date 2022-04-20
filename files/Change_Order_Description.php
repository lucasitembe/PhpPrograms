<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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
    
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }

    if(isset($_GET['Order_Description'])){
        $Order_Description = $_GET['Order_Description'];
    }else{
        $Order_Description = '';
    }

    if($Purchase_Order_ID != 0 && $Purchase_Order_ID != '' && $Purchase_Order_ID != null){
        //update requisition description automatically
        $update_details = mysqli_query($conn,"update tbl_purchase_order set Order_Description = '$Order_Description' where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        if(!$update_details){
            die(mysqli_error($conn));
        }
    }
?>