<?php 
    @session_start();
    include("./includes/connection.php");

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    } else { 
        $Registration_ID = 0;
    } 

    # dates
    date_default_timezone_set("Africa/Nairobi");
    $start_date = date('Y-m-d'." 00:00:00");
    $end_date = date('Y-m-d'. " 23:59:59");

    $Sponsor_ID = (isset($_GET['Sponsor_ID'])) ? $_GET['Sponsor_ID'] : 0;

    $count = 1;

    $get_added_medication = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Sponsor_ID = '$Sponsor_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1");
    
    $drquantity = 0;

    if(mysqli_num_rows($get_added_medication) > 0){
        while($get_payment_cache_id = mysqli_fetch_assoc($get_added_medication)){
            $Payment_Cache_ID = $get_payment_cache_id['Payment_Cache_ID'];

            # Get pharmacy added items
            $select_items = mysqli_query($conn,"SELECT Price,Item_Name,Item_ID,dose,Edited_Quantity,Quantity,Doctor_Comment,Status,Payment_Item_Cache_List_ID,dispensed_quantity,Payment_Cache_ID FROM tbl_item_list_cache WHERE Check_In_Type = 'Pharmacy' AND Payment_Cache_ID = '$Payment_Cache_ID' AND Status != 'dispensed' AND Status != 'removed'") or die($conn);
            if(mysqli_num_rows($select_items) > 0){
                while($items = mysqli_fetch_assoc($select_items)){
                    $Item_Name = $items['Item_Name'];
                    $Item_ID = $items['Item_ID'];
                    $dose = $items['dose'];
                    $Edited_Quantity = $items['Edited_Quantity'];
                    $Quantity = $items['Quantity'];
                    $Status = $items['Status'];
                    $Doctor_Comment = $items['Doctor_Comment'];
                    $Payment_Item_Cache_List_ID = $items['Payment_Item_Cache_List_ID'];
                    $dispensed_quantity = $items['dispensed_quantity'];
                    $Payment_Cache_ID = $items['Payment_Cache_ID'];
                    $Price = $items['Price'];

                    #Remaing dose
                    $remain = $dose - $dispensed_quantity;
                    $balance_new = $_SESSION['Pharmacy_ID'];

                    $color = '';
                    $controll = '';
                    $dispense_checkbox_control = '';
                    if($Status == 'active'){
                        $color = '#9f3833';
                        $controll = '';
                        $dispense_checkbox_control = '';
                    }else if($Status == 'approved'){
                        $color = 'green';
                        $controll = 'display:none';
                        $dispense_checkbox_control = 'disabled';
                    }else if($Status == 'paid'){
                        $color = 'green';
                        $controll = '';
                        $dispense_checkbox_control = 'disabled';
                    }else if($Status == 'partial_dispensed'){
                        $color = 'blue';
                        $controll = '';
                        $dispense_checkbox_control = '';
                    } 

                    if($Edited_Quantity > 0){
                        $sub_total = $Quantity * $Price;
                    }else{
                        $sub_total = 0;
                    }

                    $get_balance_now = '';
                    $get_balance = mysqli_query($conn, "SELECT til.Item_Balance,ti.Product_Name FROM tbl_items_balance as til, tbl_items as ti WHERE til.Item_ID = '$Item_ID' AND til.Sub_Department_ID = '$balance_new' AND ti.Item_ID = '$Item_ID'");
                    while ($balance_row = mysqli_fetch_assoc($get_balance)) {
                        $get_balance_now = $balance_row['Item_Balance'];
                        $Product_Name = $balance_row['Product_Name'];
            }?>

            <tr>
                <td style="display: none;" ><input type="hidden" id='Payment_Cache' value="<?=$Payment_Cache_ID?>"></td>
                <td id="data" style="padding:8px;text-align: center;"><?=$count++?></td> 
                <td id="data" style="padding:8px;text-align: left;color:<?=$color?>" ><b><?=$Product_Name?></b></td>
                <td id="data" style="padding:8px;text-align: center;"><b><?=$Doctor_Comment?></b></td>
                <td id="data" style="padding:8px;text-align: center;"><b>No Code</b></td>
                <td id="data" style="padding:8px;text-align: center;"><input type="text" id="dspQty<?=$Payment_Item_Cache_List_ID?>" style="text-align: center;" value="<?=$dose?>"></td>
                <td id="data" style="padding:8px;text-align: center;"><input type="text" id="dispensed_qty<?=$Payment_Item_Cache_List_ID?>" <?=$dispense_checkbox_control?> onkeyup="check_dispensed_qty(<?=$Payment_Item_Cache_List_ID?>)" value='<?=$Edited_Quantity?>' style="text-align: center;"></td>
                <td id="data" style="padding:8px;text-align: center;"><input type="text" id="item_duration<?=$Payment_Item_Cache_List_ID?>" style="text-align: center;" value="0"></td>
                <td id="data" style="padding:8px;text-align: center;"><input disabled type="text" style="text-align: center;" value='<?=$dispensed_quantity?>'></td>
                <td id="data" style="padding:8px;text-align: center;"><input disabled type="text" style="text-align: center;" value='<?=$remain?>' ></td>
                <td id="data" style="padding:8px;text-align: center;"><input disabled type="text" style="text-align: center;" value='<?=$get_balance_now?>' ></td>
                <td id="data" style="padding:8px;text-align: center;"><input disabled type="text" id="get_price<?=$Payment_Item_Cache_List_ID?>" style="text-align: center;" value='<?=$Price?>' ></td>
                <td id="data" style="padding:8px;text-align: center;"><input disabled type="text" class="total" id="subtotal<?=$Payment_Item_Cache_List_ID?>" style="text-align: end;" value="<?=$sub_total?>"></td>
                <td id="data" style="padding:8px;text-align: center;" style="text-align: center;">
                    <input class="item" style="<?=$controll?>" id='<?=$Payment_Item_Cache_List_ID?>' value='<?=$Payment_Item_Cache_List_ID?>' onchange="dispensed_qty_function(<?=$Payment_Item_Cache_List_ID?>)" type="checkbox" style="text-align: center;">
                </td>
                <td id="data" style="text-align: center;font-size:20px;padding:15px;cursor:pointer" onclick="remove_item(<?=$Payment_Item_Cache_List_ID?>)" width="4%"><center>&#128465;</center></td>
            </tr>
    <?php
            }
        }else{ ?>
            <tr>
                <td class="text-align:center" colspan="14"><p style="color:red;text-align:center;font-size:16px">No Items Found</p></td>
            </tr>
    <?php
        }
    }
}else{ ?>
    <tr>
        <td class="text-align:center" colspan="14"><p style="color:red;text-align:center;font-size:16px">No Items Found</p></td>
    </tr>
<?php 
    }
?>