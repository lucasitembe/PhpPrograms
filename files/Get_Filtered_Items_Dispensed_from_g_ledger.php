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
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_GET['sponsorID']) && strtolower($_GET['sponsorID'])!='all'){
        $sponsorID = $_GET['sponsorID'];
        //filter
        //$filter .= ' and ';
    }  else {
        $sponsorID = null;
    }
    //////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($data = mysqli_fetch_array($select)){
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    $Title = '<tr><td colspan="9"><hr></td></tr>
                <tr>
                    <td width=3%><b>SN</b></td>
                    <td width=4%><b>ITEM CODE</b></td>
                    <td width=40%><b>ITEM NAME</b></td>
                    <td width=6%><b>QUANTITY DISPENSED</b></td>
                    <td width=6%><b>BALANCE</b></td>
                    <td width=8.5%><b>TOTAL BUYING PRICE</b></td>
                    <td width=8.5%><b>TOTAL SELLING PRICE</b></td>
                    <td width=6%><b>PROFIT/LOSS</b></td>
                    <td width=7%><b>TOTAL STOCK VALUE</b></td>
                </tr>
                <tr><td colspan="9"><hr></td></tr>';
?>
<legend align='right'><b>DISPENSE SUMMARY ~ <?php /*if(isset($_GET['Pharmacy_ID'])){*/ echo strtoupper($Sub_Department_Name);//  }?></b></legend>
<?php
    if(isset($_GET['Search_Value'])){
        echo '<table width=100%>';
        echo $Title;
                $temp = 1; $total_items = 0;
                if(isset($_GET['Pharmacy'])){
                    $Sub_Department_Name = $_GET['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                if(isset($sponsorID) && $sponsorID!='All'){
                    //filter by sponsor
                    $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and i.Product_Name like '%$Search_Value%' and  ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));

                   //$rows[] = mysqli_fetch_assoc($result);
                   //echo '</pre>';
                   //print_r($rows);
                   //exit; 
                  
                } else {
                $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                }
//                
//                echo '<pre>';
//                print_r($result);exit;
                //$num = mysqli_num_rows($result); echo $num; 
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $grand_total_total_stock_value=0;
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
//                  $Last_Buy_Price = $row['Last_Buy_Price'];
                    
                    if(isset($sponsorID) && $sponsorID!='All'){
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    } else {
                    $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    }
                    $total_selling_price=0;
                    $total_buying_price=0;
                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        $Price = $row2['Price'];
                        
                        $Dispense_Date_Time = $row2['Dispense_Date_Time'];
                        $Sub_Department_ID = $_GET['Sub_Department_ID'];
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
                        $Sub_Department_ID=$_GET['Sub_Department_ID'];
                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                        $Item_Balance = 0;
                    }
                    if($Item_Balance < 0){ $Item_Balance = 0; }

                    
                      
                ?>
                    <tr>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $temp; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $row['Product_Code']; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $Product_Name; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $total_items; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $Item_Balance; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_buying_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_selling_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_selling_price-$total_buying_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($Item_Balance*$total_buying_price); ?></label></td>
                    </tr>
                <?php
                    $grand_total_buying_price+=($total_buying_price);
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_profit_or_loss+=($total_selling_price-$total_buying_price);
                    $grand_total_total_stock_value+=($Item_Balance*$total_buying_price);
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                    if(($temp%31) == 0){
                        echo $Title;
                    }
                    
                }
                //$total_items = 0;
                ?>  <tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td colspan="5"><b>GRAND TOTAL</b></td>
                        <td><b><?= number_format($grand_total_buying_price) ?></b></td>
                        <td><b><?= number_format($grand_total_selling_price) ?></b></td>
                        <td><b><?= number_format($grand_total_profit_or_loss) ?></b></td>
                        <td><b><?= number_format($grand_total_total_stock_value) ?></b></td>
                    </tr>    
            <?php
            
        echo '</table>';
    }else{
        echo '<table width=100%>';
        echo $Title;
                $temp = 1; $total_items = 0;
                if(isset($_GET['Pharmacy'])){
                    $Sub_Department_Name = $_GET['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                if(isset($sponsorID) && $sponsorID!='All'){
                    //filter by sponsor
                    $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Sub_Department_ID = '$Sub_Department_ID' group by i.Item_ID order by i.Product_Name ") or die(mysqli_error($conn));

                   //$rows[] = mysqli_fetch_assoc($result);
                   //echo '</pre>';
                   //print_r($rows);
                   //exit; 
                  
                } else {
                $result = mysqli_query($conn,"select ilc.Dispense_Date_Time,i.Item_ID, i.Product_Name,ilc.price,Last_Buy_Price,i.Product_Code FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                        ilc.Check_In_Type = 'pharmacy' and
                                        ilc.Status = 'dispensed' and
                                        i.Product_Name like '%$Search_Value%' and
                                        ilc.Sub_Department_ID = '$Sub_Department_ID'
                                        group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                }
                //$num = mysqli_num_rows($result); echo $num; 
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $grand_total_total_stock_value=0;
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
//                    $Last_Buy_Price = $row['Last_Buy_Price'];

//                    $Dispense_Date_Time = $row['Dispense_Date_Time'];
//                    $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
//                    if($Last_Buy_Price=="not_seted"){
//                        $Last_Buy_Price = $row['Last_Buy_Price']; 
//                     }
                    //select i.Item_ID, i.Product_Name From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID'
                    if(isset($sponsorID) && $sponsorID!='All'){
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity From tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr, tbl_item_list_cache ilc left join tbl_items i on i.Item_ID=ilc.Item_ID  where pp.Patient_Payment_ID = ilc.Patient_Payment_ID and pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = pp.Registration_ID and 
                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and ilc.Check_In_Type = 'Pharmacy' and ilc.status = 'dispensed' and pr.Sponsor_ID='$sponsorID' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    } else {
                        $Individual_Details = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.Price,i.Product_Name, ilc.Quantity, ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                        where i.Item_ID = ilc.Item_ID and 
                                                        ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                                        ilc.Check_In_Type = 'pharmacy' and
                                                        ilc.Status = 'dispensed' and ilc.Item_ID = '$Item_ID' and ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    }
                    
                    $total_selling_price=0;
                    $total_buying_price=0;
                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        $Price = $row2['Price'];
                        
                         $Dispense_Date_Time = $row2['Dispense_Date_Time'];
                         $Sub_Department_ID = $_GET['Sub_Department_ID'];
                        $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time,$Sub_Department_ID);
                         if($Last_Buy_Price=="not_seted"){
                            $Last_Buy_Price = @$row2['Last_Buy_Price']; 
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
                       $Sub_Department_ID=$_GET['Sub_Department_ID'];
                        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                        $Item_Balance = 0;
                    }
                    if($Item_Balance < 0){ $Item_Balance = 0; }
                ?>
                    <tr>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $temp; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $row['Product_Code']; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $Product_Name; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $total_items; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo $Item_Balance; ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_buying_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_selling_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($total_selling_price-$total_buying_price); ?></label></td>
                        <td style='text-align: left;'><label onclick="View_Details('<?php echo $Start_Date; ?>','<?php echo $End_Date; ?>',<?php echo $Item_ID; ?>,'<?= $sponsorID ?>')"><?php echo number_format($Item_Balance*$total_buying_price); ?></label></td>
                    </tr>
                <?php
                    $grand_total_buying_price+=($total_buying_price);
                    $grand_total_selling_price+=$total_selling_price;
                    $grand_total_profit_or_loss+=($total_selling_price-$total_buying_price);
                    $grand_total_total_stock_value+=($Item_Balance*$total_buying_price);
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                    if(($temp%31) == 0){
                        echo $Title;
                    }
                }
            ?>  <tr><td colspan="9"><hr></td></tr>
                    <tr>
                        <td colspan="5"><b>GRAND TOTAL</b></td>
                        <td><b><?= number_format($grand_total_buying_price) ?></b></td>
                        <td><b><?= number_format($grand_total_selling_price) ?></b></td>
                        <td><b><?= number_format($grand_total_profit_or_loss) ?></b></td>
                        <td><b><?= number_format($grand_total_total_stock_value) ?></b></td>
                    </tr>    
            <?php
        echo '</table>';
    }

?>