<?php
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])) {
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
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
    }else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
//
//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && strtolower($_SESSION['userinfo']['Approval_Orders']) == 'yes'){
            echo "<a href='Control_Order_Sessions.php?New_Order=True' class='art-button-green'>NEW ORDER</a>";
//        }
//    }

//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storesubmittedorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>VIEW / EDIT</a>";
//        }
//    }

//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storepreviousorders.php?PreviousOrders=PreviousOrdersThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
//        }
//    }

//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storeordering_outstandingorders.php?PreviousOrders=PreviousOrdersThisPage' class='art-button-green'>OUTSTANDING ORDERS</a>";
//        }
//    }


//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storeordering_purchaseorder.php?PurchaseOrders=PurchaseOrdersThisPage' class='art-button-green'>PURCHASE ORDERS</a>";
//        }
//    }

    //redirect to list of submitted order if and only if current employee is chief pharmacist
//    if (isset($_SESSION['userinfo']['Approval_Orders']) && strtolower($_SESSION['userinfo']['Approval_Orders']) == 'yes') {
        // header("Location: ./storesubmittedorders.php?PendingOrders=PendingOrdersThisPage");
echo "<br /><br />";
        echo "<a href='storesubmittedorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>APPROVE ORDER</a>";
//    }


//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
//        }
//    }
?>
