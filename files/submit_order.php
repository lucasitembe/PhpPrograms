<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_SESSION['Purchase_Order_ID'])){
        unset($_SESSION['Purchase_Order_ID']);
    }
    
    //get employee id
    if(isset($_SESSION['userinfo'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    if(isset($_GET['Store_Need'])){
        $Store_Need = $_GET['Store_Need'];
    }else{
        $Store_Need = 0;
    }
    
    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = $_GET['Supplier_ID'];
    }else{
        $Supplier_ID = 0;
    }
    
    
    if(isset($_GET['Order_Description'])){
        $Order_Description = $_GET['Order_Description'];
    }else{
        $Order_Description = '';
    }
    
    //get approval setup value
    $get_setup = mysqli_query($conn,"select Approval_Levels from tbl_system_configuration") or die(mysqli_error($conn));
    $num = mysqli_num_rows($get_setup);
    if($num > 0){
        while ($data = mysqli_fetch_array($get_setup)) {
            $Approval_Levels = $data['Approval_Levels'];
        }
    }else{
        $Approval_Levels = 2;
    }

    if($Store_Need != 0 && $Store_Need != null && $Supplier_ID != '' && $Supplier_ID != null){
        $insert = mysqli_query($conn,"insert into tbl_purchase_order(
                                Order_Description, Created_Date, Sent_Date,
                                Sub_Department_ID, Supplier_ID, Employee_ID,
                                Order_Status, Branch_ID, Approvals)
                                
                            VALUES ('$Order_Description',(select now()),(select now()),
                                '$Store_Need','$Supplier_ID','$Employee_ID',
                                'submitted','$Branch_ID','$Approval_Levels')") or die(mysqli_error($conn));
        if($insert){
            //get the last Purchase_Order_ID
            $select = mysqli_query($conn,"select Purchase_Order_ID from tbl_purchase_order
                                    where Employee_ID = '$Employee_ID' and
                                        Supplier_ID = '$Supplier_ID' order by Purchase_Order_ID desc limit 1") or die(mysqli_error($conn));
            $num_row = mysqli_num_rows($select);
            if($num_row > 0){
                while($data = mysqli_fetch_array($select)){
                    $Purchase_Order_ID = $data['Purchase_Order_ID'];
                }
            }else{
                $Purchase_Order_ID = 0;
            }
        }
    }
    
    if($Purchase_Order_ID != 0){
        //process all items with price & quantity
        $select = mysqli_query($conn,"select * from tbl_purchase_cache where  
                                Quantity_Required <> 0 and Price <> 0 and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        
        $no = mysqli_num_rows($select);
        if($no > 0){
            $Store_Order_ID = 0;
            while($row = mysqli_fetch_array($select)){
                $Quantity_Required = $row['Quantity_Required'];
                $Item_Remark = $row['Item_Remark'];
                $Item_ID = $row['Item_ID'];
                $Price = $row['Price'];
                $Containers_Required = $row['Container_Qty'];
                $Requisition_ID = $row['Requisition_ID'];
                $Items_Per_Container_Required = $row['Items_Per_Container'];
                $Is_Store_Order = $row['is_store_order'];
                $Store_Order_ID = $row['Store_Order_Id'];

                //insert data from cache table
                $insert = mysqli_query($conn,"INSERT INTO tbl_purchase_order_items(
                                        Purchase_Order_ID, Quantity_Required, Price,
                                        Remark, Item_ID, Containers_Required, Items_Per_Container_Required)
                                        
                                        VALUES ('$Purchase_Order_ID','$Quantity_Required','$Price',
                                        '$Item_Remark','$Item_ID','$Containers_Required','$Items_Per_Container_Required')") or die(mysqli_error($conn));

                if ($Is_Store_Order == 'no') {
                    //update requisition items table
                    mysqli_query($conn,"update tbl_requisition_items set Procurement_Status = 'processed' where Requisition_ID = '$Requisition_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                } else if ($Is_Store_Order == 'yes') {
                    mysqli_query($conn,"update tbl_store_order_items set Procurement_Status = 'processed' where Store_Order_Id = '$Store_Order_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                }

            }
            if($insert){
                mysqli_query($conn,"update tbl_purchase_order set Store_Order_ID = '$Store_Order_ID' where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));

                //delete all data from cache table
                mysqli_query($conn,"delete from tbl_purchase_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                $_SESSION['Purchase_Order_ID'] = $Purchase_Order_ID;
                header("Location: ./purchaseorderpreview.php?PurchaseOrderPreview=PurchaseOrderPreviewThisPage");
            }else{
                header("Location: ./purchaseorder.php?PurchaseOrder=PurchaseOrderThisPage");
            }
        }else{
            header("Location: ./purchaseorder.php?PurchaseOrder=PurchaseOrderThisPage");
        }
    }else{
        header("Location: ./purchaseorder.php?PurchaseOrder=PurchaseOrderThisPage");
    }
?>