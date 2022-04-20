<?php
    session_start();
    include("./includes/connection.php");
    include("calculate_buying_price.php");
    $filter = '';
    if(isset($_GET['Start_Date'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = '';
    }
    
    if(isset($_GET['End_Date'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = '';
    }
    if(isset($_GET['report_type'])){
        $report_type = mysqli_real_escape_string($conn,$_GET['report_type']);
    }else{
        $report_type = '';
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    $filter_report_type="";
    if($report_type!='All'){
       if($report_type!='Msamaha'){
           $filter_report_type=" AND Transaction_Type='$report_type'";
       } 
    }
   
    
                $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                                $filter_sub_d="";
                                if($can_login_to_high_privileges_department=='yes'){
                                    $filter_sub_d="and sdep.privileges='high'";
                                }
                                if($can_login_to_high_privileges_department!='yes'){
                                    $filter_sub_d="and sdep.privileges='normal'";
                                }
                                
                $sql_select_all_phamacetical_department_result=mysqli_query($conn,"select Sub_Department_Name,Sub_Department_ID from
                                                                                tbl_department dep, tbl_sub_department sdep
                                                                                    where dep.department_id = sdep.department_id and
                                                                                            Department_Location = 'Pharmacy' and
                                                                                            sdep.Sub_Department_Status = 'active' $filter_sub_d
                                                                                        ") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_all_phamacetical_department_result)>0){
                    $count=1;
                    while($department_rows=mysqli_fetch_assoc($sql_select_all_phamacetical_department_result)){
                        $Sub_Department_Name=$department_rows['Sub_Department_Name'];
                        $Sub_Department_ID=$department_rows['Sub_Department_ID'];
                        
                        ///////////////////////////////////////////////////////////////////////////////////////////////////
//        $Start_Date="2018/05/01 09:52";
//        $End_Date="2018-06-10 09:51:06";
//        $Search_Value="";
                $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
//                
//                echo '<pre>';
//                print_r($result);exit;
                //$num = mysqli_num_rows($result); echo $num; 
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $grand_total_total_stock_value=0;
                $grand_total_balance=0;
                $grand_total_dispensed=0;
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
//                  $Last_Buy_Price = $row['Last_Buy_Price'];
                    
                    
                    $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID' $filter_report_type") or die(mysqli_error($conn));
                    
                    $total_selling_price=0;
                    $total_buying_price=0;
                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        $Price = $row2['Price'];
                        
                        $Dispense_Date_Time = $row2['Dispense_Date_Time'];
                        $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time,$Sub_Department_ID);
                         if($Last_Buy_Price=="not_seted"){
                            $Last_Buy_Price = $row2['Last_Buy_Price']; 
                         }

                        
                        $dispenced_quantity=0;
                        if($Edited_Quantity != 0){
                            $total_items = $total_items + $Edited_Quantity;
                            $dispenced_quantity=$Edited_Quantity;
                        }else{
                            $total_items = $total_items + $Quantity;
                            $dispenced_quantity=$Quantity;
                        }
                        $total_buying_price+=($Last_Buy_Price*$dispenced_quantity); 
                        $total_selling_price+=($Price*$dispenced_quantity);
                    }
                    if($total_items<=0){ //if dsipensed quantity is zero then dont display that item
                        continue;
                    }
                    $sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $num_balance = mysqli_num_rows($sql_balance);
                    if($num_balance > 0){
                        while($sd = mysqli_fetch_array($sql_balance)){
                            $Item_Balance = $sd['Item_Balance'];
                        }
                    }else{
                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                        $Item_Balance = 0;
                    }
                    if($Item_Balance < 0){ $Item_Balance = 0; }
                    $grand_total_buying_price+=($total_buying_price);
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_profit_or_loss+=($total_selling_price-$total_buying_price);
                    $grand_total_total_stock_value+=($Item_Balance*$total_buying_price);
                    $temp++;
                    $grand_total_balance+=$Item_Balance;
                    $grand_total_dispensed+=$dispenced_quantity;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0; 
                }
                //$total_items = 0;
                ?> 
<!--            <tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td colspan="5"><b>GRAND TOTAL</b></td>
                        <td><b><?= number_format($grand_total_buying_price) ?></b></td>
                        <td><b><?= number_format($grand_total_selling_price) ?></b></td>
                        <td><b><?= number_format($grand_total_profit_or_loss) ?></b></td>
                        <td><b><?= number_format($grand_total_total_stock_value) ?></b></td>
                    </tr>    -->
            <?php
                    $grand_total_total_dispensed+=$grand_total_dispensed;
                    $grand_total_total_balance+=$grand_total_balance;
                    $grand_total_total_buying_price+=$grand_total_buying_price;
                    $grand_total_total_selling_price+=$grand_total_selling_price;
                    $grand_total_total_profit_or_loss+=$grand_total_profit_or_loss;
                    $grand_total_total_total_stock_value+=$grand_total_total_stock_value;
                        ///////////////////////////////////////////////////////////////////////////////////////////////////
                    $grand_total_balance=number_format($grand_total_balance);
                    $grand_total_buying_price=number_format($grand_total_buying_price);
                    $grand_total_selling_price=number_format($grand_total_selling_price);
                    $grand_total_profit_or_loss=number_format($grand_total_profit_or_loss);
                    $grand_total_total_stock_value=number_format($grand_total_total_stock_value);
                        echo "<tr class='rows_list' onclick='load_selected_dispencing_unit($Sub_Department_ID)'>
                                <td><b>$count.<b></td>
                                <td><b>$Sub_Department_Name</b></td>
                                <td style='text-align:right'><b>$grand_total_dispensed</b></td>
                                <td style='text-align:right'><b>$grand_total_balance</b></td>
                                <td style='text-align:right'><b>$grand_total_buying_price</b></td>
                                <td style='text-align:right'><b>$grand_total_selling_price</b></td>
                                <td style='text-align:right'><b>$grand_total_profit_or_loss</b></td>
                                <td style='text-align:right'><b>$grand_total_total_stock_value</b></td>
                            </tr>";
                        $count++;
                    }
                }
            ?>
            <tr>
                <td colspan="8"><hr/></td>
            </tr>
            <tr>
                <td colspan="2">GRAND TOTAL</td>
                <td style="text-align:right"><?= number_format($grand_total_total_dispensed) ?></td>
                <td style="text-align:right"><?= number_format($grand_total_total_balance) ?></td>
                <td style="text-align:right"><?= number_format($grand_total_total_buying_price) ?></td>
                <td style="text-align:right"><?= number_format($grand_total_total_selling_price) ?></td>
                <td style="text-align:right"><?= number_format($grand_total_total_profit_or_loss) ?></td>
                <td style="text-align:right"><?= number_format($grand_total_total_total_stock_value) ?></td>
            </tr>


