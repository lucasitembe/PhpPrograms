<?php
    @session_start();
    include("./includes/connection.php");
    include_once("./functions/purchaseorder.php");

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

    if(isset($_GET['Store_Order_ID'])){
        $Store_Order_ID = $_GET['Store_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }
    
    
    if(isset($_GET['Order_Description'])){
        $Order_Description = $_GET['Order_Description'];
    }else{
        $Order_Description = 0;
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

    if($Store_Need != 0 && $Store_Need != null && $Store_Order_ID != 0 && $Store_Order_ID != null){

        $Multi_Supplier_List = Get_Supplier_List_From_PO_Cache($Employee_ID, $Store_Need, $Store_Order_ID);
        if (!empty($Multi_Supplier_List)) {
            foreach($Multi_Supplier_List as $Multi_Supplier) {
                $Supplier_ID = $Multi_Supplier['Supplier_ID'];

                //process all items with price & quantity
                $Purchase_Cache_SQL = mysqli_query($conn,"SELECT * FROM tbl_purchase_cache WHERE
                                                          Quantity_Required <> 0 and Price <> 0 AND
                                                          Supplier_ID = '$Supplier_ID' AND
                                                          Store_Order_Id = '$Store_Order_ID' AND
                                                          Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                $Purchase_Cache_Num = mysqli_num_rows($Purchase_Cache_SQL);
                if($Purchase_Cache_Num > 0){
                    $Purchase_Order_SQL = mysqli_query($conn,"INSERT INTO tbl_purchase_order(
                                            Order_Description, Created_Date, Sent_Date,
                                            Sub_Department_ID, Supplier_ID, Employee_ID,
                                            Order_Status, Branch_ID, Approvals)

                                        VALUES ('$Order_Description',(select now()),(select now()),
                                            '$Store_Need','$Supplier_ID','$Employee_ID',
                                            'submitted','$Branch_ID','$Approval_Levels')") or die(mysqli_error($conn));
                if($Purchase_Order_SQL){
                    $Purchase_Order_ID_SQL = mysqli_query($conn,"SELECT Purchase_Order_ID FROM tbl_purchase_order
                                                    WHERE Employee_ID = '$Employee_ID' AND
                                                    Supplier_ID = '$Supplier_ID' ORDER BY Purchase_Order_ID DESC limit 1") or die(mysqli_error($conn));

                    $Purchase_Order_ID_Num = mysqli_num_rows($Purchase_Order_ID_SQL);
                    if($Purchase_Order_ID_Num > 0){
                        $Purchase_Order_ID = 0;
                        while($Purchase_Order_ID_Data = mysqli_fetch_array($Purchase_Order_ID_SQL)){
                            $Purchase_Order_ID = $Purchase_Order_ID_Data['Purchase_Order_ID'];
                        }

                            while($Purchase_Cache_Data = mysqli_fetch_array($Purchase_Cache_SQL)){
                                $Quantity_Required = $Purchase_Cache_Data['Quantity_Required'];
                                $Item_Remark = $Purchase_Cache_Data['Item_Remark'];
                                $Item_ID = $Purchase_Cache_Data['Item_ID'];
                                $Price = $Purchase_Cache_Data['Price'];
                                $Containers_Required = $Purchase_Cache_Data['Container_Qty'];
                                $Requisition_ID = $Purchase_Cache_Data['Requisition_ID'];
                                $Items_Per_Container_Required = $Purchase_Cache_Data['Items_Per_Container'];
                                $Is_Store_Order = $Purchase_Cache_Data['is_store_order'];
                                $Store_Order_ID = $Purchase_Cache_Data['Store_Order_Id'];

                                //insert data from cache table
                                $insert = mysqli_query($conn,"INSERT INTO tbl_purchase_order_items(
                                        Purchase_Order_ID, Quantity_Required, Price,
                                        Remark, Item_ID, Containers_Required, Items_Per_Container_Required)
                                        VALUES ('$Purchase_Order_ID','$Quantity_Required','$Price',
                                        '$Item_Remark','$Item_ID','$Containers_Required','$Items_Per_Container_Required')") or die(mysqli_error($conn));

                                if ($Is_Store_Order == 'yes') {
                                    mysqli_query($conn,"update tbl_store_order_items set Procurement_Status = 'processed' where Store_Order_Id = '$Store_Order_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                                }
                            }

                            if($insert){
                                mysqli_query($conn,"update tbl_purchase_order set Store_Order_ID = '$Store_Order_ID' where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));

                                //delete all data from cache table
                                mysqli_query($conn,"DELETE FROM tbl_purchase_cache WHERE
                                             Employee_ID = '$Employee_ID' AND
                                             Supplier_ID = '$Supplier_ID' AND
                                             Store_Order_Id = '$Store_Order_ID'") or die(mysqli_error($conn));
                            }
                        } else {
                            echo 'Something was wrong!! There was no purchase order created';
                        }
                    }
                } else {
                    echo 'Something was wrong!! Supplier ID : '.$Supplier_ID." has no items to make purchase order!!";
                }
            }

            header("Location: ./previousorders.php?PreviousOrder=PreviousOrderThisPage");
        }else{
            echo 'Something was wrong!! Unable to find Suppliers or All Items did not have price or quantity';
        }



        /*$insert = mysqli_query($conn,"insert into tbl_purchase_order(
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
        }*/
    } else {
        echo 'Something was wrong!! Please provide Store and Store Order ID!!';
    }
    

?>