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

    if(isset($_GET['Order_Item_ID'])){
        $Order_Item_ID = $_GET['Order_Item_ID'];
    }else{
        $Order_Item_ID = '';
    }

    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }

    if ($Order_Item_ID != '' && $Item_Remark != ''  ){
        $UpdateStore_Order_Item = mysqli_query($conn,"UPDATE tbl_store_order_items
                                               SET Item_Remark = '$Item_Remark'
                                               WHERE Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
    }
?>
