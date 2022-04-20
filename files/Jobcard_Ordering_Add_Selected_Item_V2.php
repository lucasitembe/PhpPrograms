<?php
session_start();
    include("./includes/connection.php");
    include_once("./functions/items.php");
    include("get_item_balance_for_particular_subdepartment.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];

        $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($Item_ID);
    if (count($last_buying_prices)> 0) {
        @$Last_Buying_Price =$last_buying_prices["Buying_Price"];
    } else {
        $Last_Buying_Price = 0;
    }
    }else{
        $Item_ID=0;
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

    if($Item_ID != 0 && $Item_ID != '' && $Sub_Department_ID != ''){
        //check if there is pending requisition based on store need & store issue
        $select_details = mysqli_query($conn,"SELECT *
                                       FROM tbl_store_orders
                                       WHERE Sub_Department_ID = '$Sub_Department_ID' AND
                                       Employee_ID = '$Employee_ID' AND
                                       Order_Status = 'pending' AND
                                       Control_Status IN ('available','pending')") or die(mysqli_error($conn));

        $num = mysqli_num_rows($select_details);
        if($num > 0){
            while($row = mysqli_fetch_array($select_details)){
                $Store_Order_ID = $row['Store_Order_ID'];
                $_SESSION['Last_Store_Order_ID'] = $Store_Order_ID;
            }

            $_SESSION['General_Order_ID'] = $Store_Order_ID;
            $insert_data2 = mysqli_query($conn,"INSERT INTO tbl_store_order_items
                                         (Store_Order_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Qty,
                                         Last_Buying_Price)
                                         VALUES('$Store_Order_ID','$Quantity','$Item_Remark','$Item_ID',
                                         '$Cont_Quantity','$Items_Quantity','$Last_Buying_Price')") or die(mysqli_error($conn));

            if($insert_data2){
                echo '<center><table width = 100% border=0>';
                echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                        <td style="background-color:silver;color:black">Item Name</td>
                        <td width=1% style="display:none;text-align: center; background-color:silver;color:black">Units</td>
                        <td width=1% style="display:none;text-align: center; background-color:silver;color:black">Items</td>
                        <td width=7% style="text-align: center; background-color:silver;color:black">Quantity now</td>
                        <td width=9% style="text-align: center;"><b>Store Balance</b></td>
                        <td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
                        <td width=14% style="text-align: center; background-color:silver;color:black">Remark</td>
                        <td style="text-align: center; background-color:silver;color:black">Remove</td></tr>';


                $select_Transaction_Items = mysqli_query($conn,"SELECT itm.Product_Name, soi.Last_Buying_Price, soi.Quantity_Required,
                                                                soi.Container_Qty, soi.Items_Qty, soi.Item_Remark,
                                                                soi.Order_Item_ID,itm.Item_ID
                                                         FROM tbl_store_order_items soi, tbl_items itm
                                                         WHERE itm.Item_ID = soi.Item_ID AND
                                                          soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn));

                $Temp=1;
                while($row = mysqli_fetch_array($select_Transaction_Items)){
                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";

                    echo "<td style='display:none;'><input type='text' id='Container_Qty_".$row['Order_Item_ID']."'
                              value='".$row['Container_Qty']."' style='text-align: center;'
                              onkeyup='Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                              onkeyup='Change_Container_And_Items(".$row['Order_Item_ID'].");'
                              onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                    echo "<td style='display:none;'><input type='text' id='Items_Qty_".$row['Order_Item_ID']."'
                              value='".$row['Items_Qty']."' style='text-align: center;'
                              onkeyup='numberOnly(this);Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                              onkeyup='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'
                              onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                    echo "<td><input type='text'class='Quantity_Required_' id='Quantity_Required_".$row['Order_Item_ID']."'
                              value='".$row['Quantity_Required']."' style='text-align: center;'
                              onkeyup='Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                              onkeyup='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'
                              onkeypress='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'></td>";

                    echo "<td><input type='text' readonly value='".number_format(checkItemBalance($row['Item_ID'],$Sub_Department_ID))."'></td>";
                    echo "<td><input type='text' readonly value='".number_format($row['Last_Buying_Price'])."'></td>";
                    echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."'
										            onkeyup='Update_Store_Order_Item_Remark(".$row['Order_Item_ID'].",this.value)'></td>";
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
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_jobcard_orders(
                                            Order_Description,Created_Date_Time,Created_Date,
                                            Sub_Department_ID,Employee_ID,Branch_ID)

                                        values('$Order_Description',(select now()),(select now()),
                                                '$Sub_Department_ID','$Employee_ID','$Branch_ID')") or die(mysqli_error($conn));

            if($insert_data){
                //select the current order id
                $select_Order_ID = mysqli_query($conn,"select Jobcard_Order_ID from tbl_jobcard_orders
                                                        where Employee_ID = '$Employee_ID' and
                                                        Sub_Department_ID = '$Sub_Department_ID' and
                                                        Order_Status = 'pending' order by Jobcard_Order_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_Order_ID);
                if($no > 0 ){
                    while($row = mysqli_fetch_array($select_Order_ID)){
                        $Store_Order_ID = $row['Store_Order_ID'];
                        $_SESSION['General_Order_ID'] = $row['Jobcard_Order_ID'];
                    }
                    $_SESSION['General_Order_ID'] = $Jobcard_Order_ID;
                    $_SESSION['Last_Store_Order_ID'] = $Jobcard_Order_ID;
                }else{
                    $Jobcard_Order_ID = 0;
                }

                if($Jobcard_Order_ID != 0){
                    //insert item into tbl_store_order_Items table
                    $insert_data2 = mysqli_query($conn,"insert into tbl_jobcard_order_items(
                        Jobcard_Order_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Qty,Last_Buying_Price)

                                                values('$Jobcard_Order_ID','$Quantity','$Item_Remark','$Item_ID','$Cont_Quantity','$Items_Quantity','$Last_Buying_Price')") or die(mysqli_error($conn));
                    if($insert_data2){
                        echo '<center><table width = 100% border=0>';
                        echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                                <td style="background-color:silver;color:black">Item Name</td>
                                <td width=1% style="display:none;text-align: center; background-color:silver;color:black">Units</td>
                                <td width=1% style="display:none;text-align: center; background-color:silver;color:black">Items</td>
                                <td width=7% style="text-align: center; background-color:silver;color:black">Quantity</td>
                                <td width=9% style="text-align: center;"><b>Store Balance</b></td>
                                <td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
                                <td width=14% style="text-align: center; background-color:silver;color:black">Remark</td>
                                <td style="text-align: center; background-color:silver;color:black">Remove</td></tr>';


                        $select_Transaction_Items = mysqli_query($conn,"SELECT itm.Product_Name, soi.Last_Buying_Price, soi.Quantity_Required,
                                                                        soi.Item_Remark, soi.Container_Qty, soi.Items_Qty,soi.Order_Item_ID, ib.Item_Balance
                                                                 FROM tbl_jobcard_order_items soi, tbl_items itm, tbl_items_balance ib
                                                                 WHERE itm.Item_ID = soi.Item_ID AND
                                                                       itm.Item_ID = ib.Item_ID AND
                                                                       ib.Sub_Department_ID = '$Sub_Department_ID' AND
                                                                       soi.Jobcard_Order_ID ='$Jobcard_Order_ID'") or die(mysqli_error($conn));

                        $Temp=1;
                        while($row = mysqli_fetch_array($select_Transaction_Items)){
                            echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                            echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";

                            echo "<td style='display:none;'><input type='text' id='Container_Qty_".$row['Order_Item_ID']."'
                                      value='".$row['Container_Qty']."' style='text-align: center;'
                                      onkeyup='Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                                      onkeyup='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'
                                      onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                            echo "<td style='display:none;'><input type='text' id='Items_Qty_".$row['Order_Item_ID']."'
                                      value='".$row['Items_Qty']."' style='text-align: center;'
                                      onkeyup='Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                                      onkeyup='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'
                                      onkeypress='numberOnly(this);Change_Container_And_Items(".$row['Order_Item_ID'].");'></td>";

                            echo "<td><input type='text' id='Quantity_Required_".$row['Order_Item_ID']."'
                                      value='".$row['Quantity_Required']."' style='text-align: center;'
                                      onkeyup='numberOnly(this);Update_Store_Order_Item(".$row['Order_Item_ID'].");'
                                      onkeyup='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'
                                      onkeypress='numberOnly(this);Change_Quantity_Required(".$row['Order_Item_ID'].");'></td>";

                            echo "<td><input type='text' readonly value='".number_format($row['Item_Balance'])."'></td>";
                            echo "<td><input type='text' readonly value='".number_format($row['Last_Buying_Price'])."'></td>";
                            echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."'
										            onkeyup='Update_Store_Order_Item_Remark(".$row['Order_Item_ID'].",this.value)'></td>";
                            ?>
                                <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Order_Item_ID']; ?>)'></td>
                            <?php
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
