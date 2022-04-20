<?php
    include_once("./includes/connection.php");
    include_once("./functions/items.php");

    //Remove all items cached by this employee
//    $delete = mysqli_query($conn,"delete from tbl_purchase_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
  $sql_check_order_result=mysqli_query($conn,"SELECT Store_Order_Id FROM tbl_purchase_cache WHERE Store_Order_Id='$Selected_Store_Order_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_check_order_result)>0){
            $Store_Order_Items_SQL = mysqli_query($conn,"SELECT soi.Item_ID, soi.Last_Buying_Price, soi.Quantity_Required, soi.Item_Remark,
                                                 soi.Order_Item_ID, so.Sub_Department_ID,
                                                 soi.Items_Qty, soi.Container_Qty

                                          FROM tbl_store_order_items soi, tbl_store_orders so
                                          WHERE soi.Store_Order_ID = '$Selected_Store_Order_ID' AND
                                          soi.Store_Order_ID = so.Store_Order_ID AND
                                          soi.Procurement_Status in ('active', 'selected')") or die(mysqli_error($conn));
    $Store_Order_Items_Num = mysqli_num_rows($Store_Order_Items_SQL);
    $counter = 0;
    if($Store_Order_Items_Num > 0){
        while($data = mysqli_fetch_array($Store_Order_Items_SQL)){
            $Item_ID = $data['Item_ID'];

            $Quantity_Required = $data['Quantity_Required'];
            $Items_Qty = $data['Container_Qty'];
            $Items_Per_Container = $data['Items_Qty'];
            $Item_Remark = $data['Item_Remark'];
            $Order_Item_ID = $data['Order_Item_ID'];
            $Sub_Department_ID = $data['Sub_Department_ID'];

            //$Buying_Price = $data['Last_Buying_Price'];
            //$Buying_Price = $data['Buying_Price'];

            $Last_Buying_Price_With_Supplier = Get_Item_Last_Buying_Price_With_Supplier($Item_ID);

            $Buying_Price = 0;
            $Supplier_ID = 'NULL';
            if (!empty($Last_Buying_Price_With_Supplier)){
                $Buying_Price = $Last_Buying_Price_With_Supplier[0]['Buying_Price'];
                $Supplier_ID = $Last_Buying_Price_With_Supplier[0]['Supplier_ID'];
            }
            /*if ($data['Supplier_ID'] != null) {
                $Supplier_ID = $data['Supplier_ID'];
            }*/

            //insert data to cache table
//            $insert = mysqli_query($conn,"INSERT INTO tbl_purchase_cache(
//                                        Quantity_Required, Container_Qty, Items_Per_Container, Item_Remark, Item_ID,
//                                        Store_Order_Id, Employee_ID, Price, Store_Need, Is_Store_Order, Supplier_ID)
//                                    VALUES ('$Quantity_Required','$Items_Qty','$Items_Per_Container','$Item_Remark','$Item_ID',
//                                            '$Selected_Store_Order_ID','$Employee_ID','$Buying_Price',
//                                            '$Sub_Department_ID', 'yes', $Supplier_ID)") or die(mysqli_error($conn));
//
//            if($insert){
//                mysqli_query($conn,"update tbl_store_order_items set Procurement_Status = 'selected' where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
//                ++$counter;
//            }

        }
    }
    
    }else{
    $Store_Order_Items_SQL = mysqli_query($conn,"SELECT soi.Item_ID, soi.Last_Buying_Price, soi.Quantity_Required, soi.Item_Remark,
                                                 soi.Order_Item_ID, so.Sub_Department_ID,
                                                 soi.Items_Qty, soi.Container_Qty

                                          FROM tbl_store_order_items soi, tbl_store_orders so
                                          WHERE soi.Store_Order_ID = '$Selected_Store_Order_ID' AND
                                          soi.Store_Order_ID = so.Store_Order_ID AND
                                          soi.Procurement_Status in ('active', 'selected')") or die(mysqli_error($conn));
    $Store_Order_Items_Num = mysqli_num_rows($Store_Order_Items_SQL);
    $counter = 0;
    if($Store_Order_Items_Num > 0){
        while($data = mysqli_fetch_array($Store_Order_Items_SQL)){
            $Item_ID = $data['Item_ID'];

            $Quantity_Required = $data['Quantity_Required'];
            $Items_Qty = $data['Container_Qty'];
            $Items_Per_Container = $data['Items_Qty'];
            $Item_Remark = $data['Item_Remark'];
            $Order_Item_ID = $data['Order_Item_ID'];
            $Sub_Department_ID = $data['Sub_Department_ID'];

            //$Buying_Price = $data['Last_Buying_Price'];
            //$Buying_Price = $data['Buying_Price'];

            $Last_Buying_Price_With_Supplier = Get_Item_Last_Buying_Price_With_Supplier($Item_ID);

            $Buying_Price = 0;
            $Supplier_ID = 'NULL';
            if (!empty($Last_Buying_Price_With_Supplier)){
                $Buying_Price = $Last_Buying_Price_With_Supplier[0]['Buying_Price'];
                $Supplier_ID = $Last_Buying_Price_With_Supplier[0]['Supplier_ID'];
            }
            /*if ($data['Supplier_ID'] != null) {
                $Supplier_ID = $data['Supplier_ID'];
            }*/

            //insert data to cache table
            $insert = mysqli_query($conn,"INSERT INTO tbl_purchase_cache(
                                        Quantity_Required, Container_Qty, Items_Per_Container, Item_Remark, Item_ID,
                                        Store_Order_Id, Employee_ID, Price, Store_Need, Is_Store_Order, Supplier_ID)
                                    VALUES ('$Quantity_Required','$Items_Qty','$Items_Per_Container','$Item_Remark','$Item_ID',
                                            '$Selected_Store_Order_ID','$Employee_ID','$Buying_Price',
                                            '$Sub_Department_ID', 'yes', $Supplier_ID)") or die(mysqli_error($conn));

            if($insert){
                mysqli_query($conn,"update tbl_store_order_items set Procurement_Status = 'selected' where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
                ++$counter;
            }

        }
    }
    
            }
?>