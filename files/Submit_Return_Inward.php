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

    //get Inward_ID
    if(isset($_SESSION['General_Inward_ID'])){
        $Inward_ID = $_SESSION['General_Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    //get store
    $Select_Store_Sub_Department_ID = mysqli_query($conn,"SELECT Store_Sub_Department_ID FROM tbl_return_inward WHERE Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
    $Store_Sub_Department_ID_Rows = mysqli_num_rows($Select_Store_Sub_Department_ID);
    if($Store_Sub_Department_ID_Rows > 0){
        while ($data = mysqli_fetch_array($Select_Store_Sub_Department_ID)) {
            $Store_Sub_Department_ID = $data['Store_Sub_Department_ID'];
        }
    }else{
        $Store_Sub_Department_ID = 0;
    }

    //get returning department
    $Select_Return_Sub_Department_ID = mysqli_query($conn,"SELECT Return_Sub_Department_ID FROM tbl_return_inward WHERE Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
    $Return_Sub_Department_ID_Rows = mysqli_num_rows($Select_Return_Sub_Department_ID);
    if($Return_Sub_Department_ID_Rows > 0){
        while ($data = mysqli_fetch_array($Select_Return_Sub_Department_ID)) {
            $Return_Sub_Department_ID = $data['Return_Sub_Department_ID'];
        }
    }else{
        $Return_Sub_Department_ID = 0;
    }

    //if sub department found update balance
    if($Store_Sub_Department_ID != null && $Store_Sub_Department_ID != 0 && $Return_Sub_Department_ID != null && $Return_Sub_Department_ID != 0 && $Inward_ID){
        $select_items = mysqli_query($conn,"SELECT * FROM tbl_return_inward_items WHERE Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($select_items);
        if($nm > 0){
            while ($dt = mysqli_fetch_array($select_items)) {
                $Quantity_Returned = $dt['Quantity_Returned'];
                $Item_ID = $dt['Item_ID'];
                $updateStore = mysqli_query($conn,"UPDATE tbl_items_balance SET Item_Balance = (Item_Balance + $Quantity_Returned) WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = '$Store_Sub_Department_ID'") or die(mysqli_error($conn));
                if(!$updateStore){
                    $Control = 'no';
                }
                $updateReturned = mysqli_query($conn,"UPDATE tbl_items_balance SET Item_Balance = (Item_Balance - $Quantity_Returned) WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = '$Return_Sub_Department_ID'") or die(mysqli_error($conn));
                if(!$updateReturned){
                    $Control = 'no';
                }
            }
            if($Control == 'yes'){
                $update_sql = mysqli_query($conn,"UPDATE tbl_return_inward SET Inward_Status = 'Submitted', Sent_Date_Time = (SELECT now()) WHERE Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
                if($update_sql){
                    $_SESSION['Order_Return_Order'] = $_SESSION['General_Inward_ID'];
                    unset($_SESSION['General_Inward_ID']);
                    header("Location: ./returninwardpreview.php?ReturnInwardPreview=ReturnInwardPreviewThisPage&Inward_ID=$Inward_ID");
                }else{
                    header("Location: ./returninwards.php?status=new&NPO=True&ReturnInward=ReturnInwardThisPage");
                }
            }else{
                header("Location: ./returninwards.php?status=new&NPO=True&ReturnInward=ReturnInwardThisPage");
            }
        }
    }else{
        header("Location: ./returninwards.php?status=new&NPO=True&ReturnInward=ReturnInwardThisPage");
    }
?>