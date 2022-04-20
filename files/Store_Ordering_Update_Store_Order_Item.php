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

    if(isset($_GET['Container_Qty'])){
        $Container_Qty = $_GET['Container_Qty'];
    }else{
        $Container_Qty = '';
    }

    if(isset($_GET['Items_Qty'])){
        $Items_Qty = $_GET['Items_Qty'];
    }else{
        $Items_Qty = '';
    }

    if(isset($_GET['Quantity_Required'])){
        $Quantity_Required = $_GET['Quantity_Required'];
    }else{
        $Quantity_Required = '';
    }

    if ($Order_Item_ID != '' && $Container_Qty != '' && $Items_Qty != '' && $Quantity_Required != ''  ){
        $UpdateStore_Order_Item = mysqli_query($conn,"UPDATE tbl_store_order_items
                                               SET Container_Qty = $Container_Qty,
                                                   Items_Qty = $Items_Qty,
                                                   Quantity_Required = $Quantity_Required
                                               WHERE Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
    }
?>
