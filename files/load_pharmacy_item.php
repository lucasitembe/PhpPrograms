<?php
    include './includes/connection.php';
    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();

    $Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
    $Billing_Type = (isset($_GET['Billing_Type'])) ? $_GET['Billing_Type'] : 0;
    $Payment_Cache_ID = (isset($_GET['Payment_Cache_ID'])) ? $_GET['Payment_Cache_ID'] : 0;
    $Sub_Department_id = (isset($_GET['Sub_Department_id'])) ? $_GET['Sub_Department_id'] : 0;
    $inTransaction_Type = (isset($_GET['Transaction_Type'])) ? $_GET['Transaction_Type'] : 0;
    $Sub_Department_ID = (isset($_GET['balance_new'])) ? $_GET['balance_new'] : 0;
    $sponsor_config = !empty($_GET['sponsor_config']) ? "readonly=readonly" : "";
    $auto_item_update_api = $_GET['auto_item_update_api'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $pharmacist_to_approve = $_GET['pharmacist_to_approve'];

    $sql_date_time = mysqli_fetch_assoc(mysqli_query($conn,"SELECT now() as Date_Time "))['Date_Time'];
    $today_date = strval($sql_date_time);

    $counter = 1;
    $balance_check = "";
    $Consultant_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_id FROM tbl_payment_cache WHERE Payment_Cache_ID = $Payment_Cache_ID LIMIT 1"))['consultation_id'];
    if($Consultant_ID != null){
        $getDiseaseCode = $Interface->getDiseasesCode($Consultant_ID,"diagnosis");
        foreach($getDiseaseCode as $Code){ $disease_code .= $Code['disease_code'].","; }
    }else{
        $disease_code = "No Code";
    }

    $getPharmacyItems = $Interface->displayPharmacyItems($Payment_Cache_ID,$Registration_ID,$Billing_Type,$inTransaction_Type,$Sub_Department_ID);
    if(sizeof($getPharmacyItems)){
        foreach($getPharmacyItems as $itemDetails){
            $previous_dispense = $itemDetails['dispensed_quantity'] == '' ? 0 : $itemDetails['dispensed_quantity'];
            $remain_qty = $itemDetails['dose'] - $itemDetails['dispensed_quantity'];

            if($itemDetails['Status'] == "active"){
                $col = '#9f3833';
                $read = '';
                $display_control = '';
                $remove = '';
            }else if($itemDetails['Status'] == "paid"){
                $col = '#1f6c63';
                $read = 'readonly=readonly';
                $remove = '';
                $display_control = '';
            }else if($itemDetails['Status'] == "approved"){ 
                $col ='#1f6c63'; 
                $read = 'readonly=readonly';
                $display_control = 'display:none';
                $remove = '';
            }else if($itemDetails['Status'] == "partial dispensed"){
                $col ='#2e5f91'; 
                $read = 'readonly=readonly';
                $display_control = '';
                $remove = 'display:none;';
            }else if($itemDetails['Status'] == "free"){
                $col ='#000'; 
                $read = '';
                $display_control = '';
                $remove = '';
            }
            
            $checkDate = $itemDetails['Transaction_Date_And_Time'];
            $stringifyDate = strval($checkDate);

            $date_one = substr($stringifyDate,0,10);
            $date_two = substr($today_date,0,10);

            $row_color = ($date_one === $date_two) ? '#fbe3e0' : '';
            $qty_dosage = ($itemDetails['dose'] > 0) ? $itemDetails['dose'] : $itemDetails['Quantity'];
            
            if($itemDetails['Sub_Department_ID'] != $Sub_Department_ID){
                $balance_check = "<a href='#' class='art-button-green' onclick='change_department_btn(".$itemDetails['Payment_Item_Cache_List_ID'].",".$Sub_Department_ID.",".$itemDetails['Item_ID'].")' style='font-family:arial'>CHANGE</a>";
            }else{
                if((int)$Interface->isRestricted($Sponsor_ID,$itemDetails['Item_ID']) == 1 && (int)$auto_item_update_api == 1 && $itemDetails['Treatment_Authorizer'] == NULL && $itemDetails['Treatment_Authorization_No'] == NULL && $pharmacist_to_approve == 'yes' ){
                    $balance_check = "<a href='#' class='art-button-green' onclick='item_approval({$itemDetails['Item_ID']},{$Registration_ID},{$itemDetails['Payment_Item_Cache_List_ID']})'>APPROVE</a>";
                }else{
                    $balance_check = ($itemDetails['Item_Balance'] > 0) ? $balance_check = "<input style='{$display_control}' onchange='dispensed_qty_function({$itemDetails['Payment_Item_Cache_List_ID']})' onclick='checkDose({$itemDetails['Payment_Item_Cache_List_ID']},{$itemDetails['Item_ID']},$Registration_ID,$Payment_Cache_ID )' type='checkbox' id='{$itemDetails['Payment_Item_Cache_List_ID']}' class='check_item' value='{$itemDetails['Payment_Item_Cache_List_ID']}'>" : "<a href='' onclick='out_of_stock({$itemDetails['Payment_Item_Cache_List_ID']})' class='art-button-green'>OUT OF STOCK</a>";
                }    
            }
            
            echo"
                <tr>
                    <th style='background-color:${row_color}'>".$counter++."</th>
                    <th style='text-align:left;color:{$col};background-color:${row_color}' onclick='substituteMediaction({$itemDetails['Payment_Item_Cache_List_ID']},{$itemDetails['Item_ID']})'>{$itemDetails['Product_Name']}</th>
                    <th style='background-color:${row_color}'>{$itemDetails['Doctor_Comment']}</th>
                    <th style='background-color:${row_color}'>{$disease_code}</th>
                    <th style='background-color:${row_color}'><input {$read} type='text' $sponsor_config class='dose' onkeyup='autosave_dose_qty({$itemDetails['Payment_Item_Cache_List_ID']})' id='dosenqtyid{$itemDetails['Payment_Item_Cache_List_ID']}' value='{$qty_dosage}' style='text-align:center'></th>
                    <th style='background-color:${row_color}'>
                        <input type='text' class='dispensed{$itemDetails['Payment_Item_Cache_List_ID']}' id='dispenseqtyid".$itemDetails['Payment_Item_Cache_List_ID']."' onkeyup='dispensed_qty_function({$itemDetails['Payment_Item_Cache_List_ID']});calculate_subtotal({$itemDetails['Payment_Item_Cache_List_ID']})'  value='{$itemDetails['Edited_Quantity']}' style='text-align:center'>
                        <input type='hidden' id='dispenseqtyid".$itemDetails['Payment_Item_Cache_List_ID']."1' class='dispensed' value='". $itemDetails['Edited_Quantity'] ."' style='text-align:center'>
                        <input type='hidden' id='items_id' value='".$itemDetails['Item_ID']."' style='text-align:center'>
                        <input type='hidden' id='check_status{$itemDetails['Payment_Item_Cache_List_ID']}' value='{$itemDetails['Status']}'>
                    </th>
                    <th style='background-color:${row_color}'><input type='text' value='{$itemDetails['dosage_duration']}' id='dose_duration{$itemDetails['Payment_Item_Cache_List_ID']}' onkeyup='autosave_duration({$itemDetails['Payment_Item_Cache_List_ID']})' style='text-align:center'></th>
                    <th style='background-color:${row_color}'><input type='text' id='previuse{$itemDetails['Payment_Item_Cache_List_ID']}' readonly='readonly' value='{$previous_dispense}' style='text-align:center'></th>
                    <th style='background-color:${row_color}'><input type='text' id='remainqtyid{$itemDetails['Payment_Item_Cache_List_ID']}' readonly='readonly' value='{$remain_qty}' style='text-align:center'></th>
                    <th style='background-color:${row_color}'><input type='text' id='balanceqtyid{$itemDetails['Payment_Item_Cache_List_ID']}' readonly='readonly' value='{$itemDetails['Item_Balance']}' style='text-align:center'></th>
                    <th style='background-color:${row_color}'><input type='text' class='prices' id='item_price{$itemDetails['Payment_Item_Cache_List_ID']}' readonly='readonly' value='{$itemDetails['Price']}' style='text-align:center'></th>
                    <th style='background-color:${row_color}'>
                        <input type='text' class='total' id='subtotal{$itemDetails['Payment_Item_Cache_List_ID']}' readonly='readonly' value='0' style='text-align:center'>
                        <input type='hidden' id='sumTtotal' readonly='readonly' value='{$itemDetails['Payment_Item_Cache_List_ID']}' style='text-align:center'>
                    </th>
                    <th style='background-color:${row_color}'>{$balance_check}</th>
                    <th style='background-color:${row_color}'><span style='font-size:18px;color:red;$remove padding:20px;{$display_control}' onclick='remove_item({$itemDetails['Payment_Item_Cache_List_ID']},{$itemDetails['Item_ID']})'>&#128465;</span></th>
                    <th style='background-color:${row_color}'>{$itemDetails['Transaction_Date_And_Time']}</th>
                    <th style='background-color:${row_color}'>{$itemDetails['Employee_Name']}</th>
                </tr>
            ";
        }
        echo "
            <tr style='background-color: #ddd;'>
                <td colspan='12' style='text-align:end;font-wight:bolder;font-size:16px;font-wight:bolder;font-family:arial;padding-top:0px'><p>TSH</p></td>
                <td >
                    <input type='text' style='text-align:center;font-wight:bolder;font-family:arial;font-size:16px;border:none;background-color:inherit;padding-top:8px' disabled='disabled' id='total_amount' value='0.0'/>
                    <input type ='hidden'style='text-align:center;font-wight:bolder;font-family:arial;font-size:16px;border:none;background-color:inherit;' disabled='disabled' id='total_amount' value='Tshs : 0 /='/>
                </td>
                <td colspan='4'></td>
            </tr>";
    }else{
        echo "<tr><td colspan='16' style='color:red;padding:1em;font-size:16px;font-weight:100;text-align:center'>No Medication Found</td></tr>";
    }
?>
