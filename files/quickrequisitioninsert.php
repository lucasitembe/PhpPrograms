<?php
    session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_SESSION['Pharmacy']) && $Employee_ID != 0){
        $Sub_Department_Name = $_SESSION['Pharmacy'];
        
        //get sub department id
        $sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0 ){
            while($row = mysqli_fetch_array($sql_select)){
                $Sub_Department_ID = $row['Sub_Department_ID'];
            }
        }else{
            $Sub_Department_ID = 0;
        }
        
        $select_order_items = mysqli_query($conn,"select ib.Item_ID, i.Product_Name, ib.Item_Balance, ib.Reorder_Level from tbl_items_balance ib, tbl_Items i where
                        i.Item_ID = ib.Item_ID and
                        ib.Item_Balance < ib.Reorder_Level and
                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    }
    
    while($row = mysqli_fetch_array($select_order_items)){
        $Item_ID = $row['Item_ID'];
        mysqli_query($conn,"insert into tbl_quick_requisition(Sub_Department_ID,Item_ID,Employee_ID) values('$Sub_Department_ID','$Item_ID','$Employee_ID')") or die(mysqli_error($conn));
    }
    header("Location: ./quickrequisition.php?Status=QuickRequisition&Requisition=RequisitionThisPage");
    //header("Location: ./quickpurchaseorder.php?Status=QuickPurchaseOrder&PurchaseOrder=PurchaseOrderThisPage");
?>