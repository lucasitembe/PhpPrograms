<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    if(isset($_GET['Order_Description'])){
        $Order_Description = $_GET['Order_Description'];
    }else{
        $Order_Description = '';
    }

    if(isset($_GET['Items_Quantity'])){
        $Items_Quantity = $_GET['Items_Quantity'];
    }else{
        $Items_Quantity = '';
    }

    if(isset($_GET['Cont_Quantity'])){
        $Cont_Quantity = $_GET['Cont_Quantity'];
    }else{
        $Cont_Quantity = '';
    }
    
    if(isset($_GET['Last_Buying_Price'])){
        $Last_Buying_Price = $_GET['Last_Buying_Price'];
    }else{
        $Last_Buying_Price = 0;
    }

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    if($Item_ID != 0 && $Item_ID != '' && $Sub_Department_ID != '' && $Quantity != 0){
        //check if there is pending requisition based on store need & store issue
        $select_details = mysqli_query($conn,"select * from tbl_store_orders
                                        where Sub_Department_ID = '$Sub_Department_ID' and
                                        Employee_ID = '$Employee_ID' and
                                        Order_Status = 'pending' and (Control_Status = 'available' or Control_Status = 'pending')") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Store_Order_ID = $row['Store_Order_ID'];
                }
                $_SESSION['General_Order_ID'] = $Store_Order_ID;
                $insert_data2 = mysqli_query($conn,"insert into tbl_store_order_Items(
                                                Store_Order_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Qty,Last_Buying_Price)
                                                            
                                            values('$Store_Order_ID','$Quantity','$Item_Remark','$Item_ID','$Cont_Quantity','$Items_Quantity','$Last_Buying_Price')") or die(mysqli_error($conn));
                        if($insert_data2){
                                echo '<center><table width = 100% border=0>';
                                echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                            <td><b>Item Name</b></td>
                                            <td width=7% style="text-align: center;"><b>Units</b></td>
                                            <td width=7% style="text-align: center;"><b>Items Per Unit</b></td>
                                            <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                            <td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
                                            <td width=25% style="text-align: center;"><b>Remark</b></td><td><b>Remove</b></td></tr>';
                                
                                
                                $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, soi.Last_Buying_Price, soi.Quantity_Required, soi.Container_Qty, soi.Items_Qty, soi.Item_Remark, soi.Order_Item_ID
                                                                            from tbl_store_order_Items soi, tbl_items itm where
                                                                            itm.Item_ID = soi.Item_ID and
                                                                            soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn)); 
                            
                                $Temp=1;
                                while($row = mysqli_fetch_array($select_Transaction_Items)){
                                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                    echo "<td><input type='text' value='".$row['Container_Qty']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Items_Qty']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
                                    echo "<td><input type='text' value='".number_format($row['Last_Buying_Price'])."'></td>";
                                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                ?>
                                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Order_Item_ID']; ?>)'></td>
                                <?php
                                    echo "</tr>";
                                    $Temp++;
                                }
                                echo '</table>';
                        }
            }else{
                //insert data as a new requisition
                $insert_data = mysqli_query($conn,"insert into tbl_store_orders(
                                                Order_Description,Created_Date_Time,Created_Date,
                                                Sub_Department_ID,Employee_ID,Branch_ID)
                                            
                                            values('$Order_Description',(select now()),(select now()),
                                                    '$Sub_Department_ID','$Employee_ID','$Branch_ID')") or die(mysqli_error($conn));
                
                if($insert_data){
                    //select the current order id
                    $select_Order_ID = mysqli_query($conn,"select Store_Order_ID from tbl_store_orders
                                                            where Employee_ID = '$Employee_ID' and
                                                            Sub_Department_ID = '$Sub_Department_ID' and
                                                            Order_Status = 'pending' order by Store_Order_ID desc limit 1") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_Order_ID);
                    if($no > 0 ){
                        while($row = mysqli_fetch_array($select_Order_ID)){
                            $Store_Order_ID = $row['Store_Order_ID'];
                            $_SESSION['General_Order_ID'] = $row['Store_Order_ID'];
                        }
                        $_SESSION['General_Order_ID'] = $Store_Order_ID;
                    }else{
                        $Store_Order_ID = 0;
                    }
                    
                    if($Store_Order_ID != 0){
                        //insert item into tbl_store_order_Items table
                        $insert_data2 = mysqli_query($conn,"insert into tbl_store_order_Items(
                                                        Store_Order_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Qty,Last_Buying_Price)
                                                            
                                                    values('$Store_Order_ID','$Quantity','$Item_Remark','$Item_ID','$Cont_Quantity','$Items_Quantity','$Last_Buying_Price')") or die(mysqli_error($conn));
                        if($insert_data2){
                            echo '<center><table width = 100% border=0>';
                            echo '<tr><td width=4% style="text-align: center;"><b>Sn</b></td>
                                    <td><b>Item Name</b></td>
                                    <td width=7% style="text-align: center;"><b>Units</b></td>
                                    <td width=7% style="text-align: center;"><b>Items Per Unit</b></td>
                                    <td width=7% style="text-align: center;"><b>Quantity</b></td>
                                    <td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
                                    <td width=25% style="text-align: center;"><b>Remark</b></td><td><b>Remove</b></td></tr>';

                            
                            $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, soi.Last_Buying_Price, soi.Quantity_Required, soi.Item_Remark, soi.Container_Qty, soi.Items_Qty
                                                                        from tbl_store_order_Items soi, tbl_items itm where
                                                                        itm.Item_ID = soi.Item_ID and
                                                                        soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn)); 
                        
                            $Temp=1;
                            while($row = mysqli_fetch_array($select_Transaction_Items)){
                                echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                                echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                                echo "<td><input type='text' value='".$row['Container_Qty']."' style='text-align: center;'></td>";
                                echo "<td><input type='text' value='".$row['Items_Qty']."' style='text-align: center;'></td>";
                                echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
                                echo "<td><input type='text' value='".number_format($row['Last_Buying_Price'])."'></td>";
                                echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
                                echo "<td width=6%><a href='#' class='art-button-green'><b>X</b></a></td>";
                                echo "</tr>";
                                $Temp++;
                            }
                            echo '</table>';
                        }
                        
                    }
                }
                
                
                
            }
    }
?>