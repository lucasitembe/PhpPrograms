<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

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

    //get Store_Order_ID
    if(isset($_SESSION['Edit_General_Order_ID'])){
        $Store_Order_ID = $_SESSION['Edit_General_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }

    //get sub department id
    $select = mysqli_query($conn,"select Sub_Department_ID from tbl_store_orders where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_ID = $data['Sub_Department_ID'];
        }
    }else{
        $Sub_Department_ID = 0;
    }

    if($Store_Order_ID != 0 && $Store_Order_ID != '' && $Store_Order_ID != null){
        $update_sql = mysqli_query($conn,"update tbl_store_orders set Order_Status = 'Submitted', Control_Status = 'available', Sent_Date_Time = (select now()) where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
        if($update_sql){
            $_SESSION['Order_Preview'] = $Store_Order_ID;
            if(isset($_SESSION['Edit_General_Order_ID'])){
                unset($_SESSION['Edit_General_Order_ID']);
            }
            header("Location: ./storesubmittedorders.php?PendingOrders=PendingOrdersThisPage");
        }else{
            header("Location: ./storeordering.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");
        }
    }
?>