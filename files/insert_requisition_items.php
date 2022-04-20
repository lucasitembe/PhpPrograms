<?php
    include("./includes/connection.php");
    
    $select = mysqli_query($conn,"select * from tbl_requisition_items where Requisition_ID = '$Requisition_selected' and Procurement_Status = 'active'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    $counter = 0;
    if($num > 0){
        while($data = mysqli_fetch_array($select)){
            $Item_ID = $data['Item_ID'];
            
            //check it if is already available
            $check = mysqli_query($conn,"select Item_ID from tbl_purchase_cache where
                                    Item_ID = '$Item_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($check);
            if($no == 0){
                //get the last buy price
                $get_price = mysqli_query($conn,"select Buying_Price from tbl_purchase_order_items where Item_ID = '$Item_ID' order by Order_Item_ID desc limit 1") or die(mysqli_error($conn));
                $get_num = mysqli_num_rows($get_price);
                if($get_num > 0){
                    while ($dt = mysqli_fetch_array($get_price)) {
                        $Buying_Price = $dt['Buying_Price'];
                    }
                }else{
                    $Buying_Price = 0;
                }

                $Quantity_Required = $data['Quantity_Required'];
                $Item_Remark = $data['Item_Remark'];
                $Requisition_Item_ID = $data['Requisition_Item_ID'];

                //insert data to cache table
                $insert = mysqli_query($conn,"insert into tbl_purchase_cache(
                                        Quantity_Required, Item_Remark, Item_ID,
                                            Requisition_ID, Employee_ID, Price, Store_Need)
                                        
                                        VALUES ('$Quantity_Required','$Item_Remark','$Item_ID',
                                                '$Requisition_selected','$Employee_ID','$Buying_Price','$Purchase_Order_Store_Need')") or die(mysqli_error($conn));
                
                if($insert){
                    mysqli_query($conn,"update tbl_requisition_items set Procurement_Status = 'selected' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
                    ++$counter;
                }
            }
        }
    }
    
    

    
?>