<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Control = 'yes';

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

    //get Outward_ID
    if(isset($_SESSION['General_Outward_ID'])){
        $Outward_ID = $_SESSION['General_Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    //get sub_department_id
    $select = mysqli_query($conn,"select Sub_Department_ID from tbl_return_outward where Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Sub_Department_ID = $data['Sub_Department_ID'];
        }
    }else{
        $Sub_Department_ID = 0;
    }


    //if sub department found update balance
    if($Sub_Department_ID != null && $Sub_Department_ID != 0 && $Outward_ID){
        $select_items = mysqli_query($conn,"select * from tbl_return_outward_items where Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($select_items);
        if($nm > 0){
            while ($dt = mysqli_fetch_array($select_items)) {
                $Quantity_Returned = $dt['Quantity_Returned'];
                $Item_ID = $dt['Item_ID'];
                $update = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance - $Quantity_Returned) where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                if(!$update){
                    $Control = 'no';
                }
            }
            if($Control == 'yes'){
                $update_sql = mysqli_query($conn,"update tbl_return_outward set Outward_Status = 'Submitted', Sent_Date_Time = (select now()) where Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
                if($update_sql){
                    $_SESSION['Order_Return_Order'] = $_SESSION['General_Outward_ID'];
                    unset($_SESSION['General_Outward_ID']);
                    header("Location: ./returnoutwardpreview.php?Outward_ID=$Outward_ID&ReturnOutwardPreview=ReturnOutwardPreviewThisPage");
                }else{
                    header("Location: ./returnoutwards.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");
                }
            }else{
                header("Location: ./returnoutwards.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");
            }
        }
    }else{
        header("Location: ./returnoutwards.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");
    }
?>