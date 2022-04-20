<?php 
    include 'store.interface.php';
    $Store = new StoreInterface();

    if($_GET['search_search']){
        $output = "";
        $Current_Store_ID = $_GET['Sub_Department_ID'];
        $Product_Name = $_GET['product_name'];
        $classification = $_GET['classification'];
        $result = $Store->getItems($Current_Store_ID,$Product_Name,$classification);

        $output .= "
            <table style='background-color: #fff;'>
            <tr style='background-color: #eee;'>
                <td style='padding: 8px;' colspan='2'><b>ITEM NAME</b></td>
                <td style='padding: 8px;' width='20%'><b>UOM</b></td>
                <td style='padding: 8px;' width='10%'><b>BALANCE</b></td>
            </tr><tbody>";
            
            if(sizeof($result) > 0){
                foreach($result as $Item):
                    $output .= "  <tr>
                        <td style='padding: 5px;text-align:center'><input name='items' onclick='addItems($Current_Store_ID,{$Item['Item_ID']})' type='radio'></td>
                        <td style='padding: 8px;'>{$Item['Product_Name']}</td>
                        <td style='padding: 8px;'>{$Item['Unit_Of_Measure']}</td>
                        <td style='padding: 8px;'>{$Item['Item_Balance']}</td>
                    </tr>";
                endforeach;
            }else{
                $output .= "  <tr><td colspan='4' style='padding: 5px;text-align:center;color:red'><b>ITEM WITH NAME</b> ~ $Product_Name <b>NOT FOUND</b></td></tr>";
            }
        $output .= "</tbody></table>";
        echo $output;
    }

    if($_POST['create_new_adjustment']){
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Employee_ID = $_POST['Employee_ID'];
        $adjustment_date = $_POST['adjustment_date'];
        $adjustment_reason = $_POST['adjustment_reason'];
        $adjustment_description = $_POST['adjustment_description'];
        $Branch_ID = $_POST['Branch_ID'];

        echo $Store->createNewAdjustmentDocument($Sub_Department_ID,$Employee_ID,$adjustment_description,$Branch_ID,$adjustment_reason); 
    }

    if($_POST['add_new_item_to_adjust']){
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Item_ID = $_POST['Item_ID'];
        $adjustment_number = $_POST['adjustment_number'];

        echo $Store->addItemsToAdjust($Item_ID,$adjustment_number);
    }

    if($_GET['load_adjusted_items']){
        $output = "";
        $adjustment_number = $_GET['adjustment_number'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $result = $Store->getAdjustedItems($adjustment_number,$Sub_Department_ID);
        
        $count = 1;

        if(sizeof($result) > 0){
            foreach($result as $items){
                $adjust = ($_GET['adjust'] == "minus") ? $items['Item_Balance'] - $items['Quantity_Disposed'] : $items['Item_Balance'] + $items['Quantity_Disposed'];
                $output .= "
                    <tr>
                        <td style='text-align: center;padding:7px'>".$count++."</td>
                        <td style='padding:7px'>{$items['Product_Name']}</td>
                        <td style='text-align: center;'><input type='text' class='batch' id='batch{$items['Item_ID']}' onkeyup='batchSave({$items['Item_ID']},{$items['Disposal_Item_ID']})' value='{$items['batch_no']}' placeholder='Batch Number' style='text-align: center;'></td>
                        <td style='text-align: center;'><input type='date' class='expire_date' id='expire_date{$items['Item_ID']}' onchange='saveExpireDates({$items['Item_ID']},{$items['Disposal_Item_ID']})' value='{$items['expire_date']}' style='text-align: center;'></td>
                        <td style='text-align: center;'><input type='text' id='balance{$items['Item_ID']}' style='text-align: center;' readonly=readonly value='{$items['Item_Balance']}'></td>
                        <td style='text-align: center;'><input type='text' class='qty_adjusted' id='qty_adjusted{$items['Item_ID']}' onkeyup='adjustQuantity({$items['Item_ID']},{$items['Disposal_Item_ID']})' value='{$items['Quantity_Disposed']}' style='text-align: center;'></td>
                        <td style='text-align: center;'><input type='text' id='balance_after{$items['Item_ID']}' style='text-align: center;' value='{$adjust}'></td>
                        <td style='text-align: center;'><input type='text' id='remark{$items['Item_ID']}' onkeyup='saveRemarks({$items['Item_ID']},{$items['Disposal_Item_ID']})' value='{$items['Item_Remark']}' style='text-align: center;'></td>
                        <td style='text-align: center;'>
                            <input type='hidden' id='items_id' value='{$items['Item_ID']}' style='text-align: center;'>
                            <a href='#' class='art-button-green' onclick='removeItem($Sub_Department_ID,{$items['Item_ID']})' style='border-radius: 5px;color:#fff'>X</a>
                        </td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr><td style='text-align: center;padding:7px' colspan='9'>NO ITEM FOUND PLEASE ADD TO ADJUST</td></tr>";
        }
        echo $output;
    }

    if($_POST['remove_item_from_adjustment']){
        $adjustment_number = $_POST['adjustment_number'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Item_ID = $_POST['Item_ID'];

        echo $Store->removeItemFromAdjustList($adjustment_number,$Item_ID);
    }

    if($_POST['autosave_adjust']){
        $Adjusted_ID = $_POST['Adjusted_ID'];
        $qty_adjusted = $_POST['qty_adjusted'];

        echo $Store->updateAdjustmentQty($qty_adjusted,$Adjusted_ID);
    }

    if($_POST['update_adjustment_remark']){
        $item_remark = $_POST['item_remark'];
        $Adjusted_ID = $_POST['Adjusted_ID'];

        echo $Store->saveItemRemarkForAdjustment($item_remark,$Adjusted_ID);
    }

    if($_POST['batch_auto_save']){
        $batch = $_POST['batch'];
        $Adjusted_ID = $_POST['Adjusted_ID'];

        echo $Store->saveItemBatchForAdjustment($batch,$Adjusted_ID);
    }

    if($_POST['expire_date_auto_save']){
        $expire_date = $_POST['expire_date'];
        $Adjusted_ID = $_POST['Adjusted_ID'];

        echo $Store->saveItemExpireDateForAdjustment($expire_date,$Adjusted_ID);
    }

    if($_POST['submit_adjustment']){
        $Item_IDs = $_POST['Item_IDs'];
        $Item_ID_Qty = $_POST['Item_ID_Qty'];
        $adjustment_number = $_POST['adjustment_number'];
        $Employee_ID = $_POST['Employee_ID'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $adjust = $_POST['adjust'];

        echo $Store->SubmittedAdjustmentList($Item_IDs,$Item_ID_Qty,$adjustment_number,$Employee_ID,$Sub_Department_ID,$adjust,'ADJUSTMENT');
    }

    if($_GET['load_adjustment_document_']){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $status = $_GET['document_status'];
        $output = "";
        $Current_Store_Name = $_GET['Current_Store_Name'];
        $Start_Date = $_GET['Start_Date'];
        $End_Date = $_GET['End_Date'];
        $counter = 1;
        $Document_Number = "all";
        $result = $Store->getReadAdjustments($Sub_Department_ID,$Document_Number,$status,$Start_Date,$End_Date);

        if(sizeof($result) > 0){
            foreach($result as $data){
                $date_status = ($data['Disposed_Date'] == "0000-00-00") ? "<span style='color:#5b5baa'><b>NOT SUBMITTED</b></span>" : $data['Disposed_Date'];
                $status_check = ($data['Disposal_Status'] == "pending") ? "<a href='adjustment.php?adjustment={$data['Disposal_ID']}&description={$data['Disposal_Description']}&date={$data['Created_Date']}&reason={$data['name']} ~ {$data['nature']}' class='art-button-green'>PROCEED</a>" : "";
                $output .= "
                    <tr style='background-color: #fff;'>
                        <td style='padding: 8px;text-align:center'>".$counter++."</td>
                        <td style='padding: 8px;text-align:center'>{$data['Disposal_ID']}</td>
                        <td style='padding: 8px;'>{$date_status}</td>
                        <td style='padding: 8px;'>{$data['Employee_Name']}</td>
                        <td style='padding: 8px;'>".ucwords($Current_Store_Name)."</td>
                        <td style='padding: 5px;text-align:center'>
                            {$status_check}
                            <a href='adjustment_preview.php?adjustment={$data['Disposal_ID']}&Sub_Department_ID={$Sub_Department_ID}' target='_blank' class='art-button-green'>PREVIEW</a>
                        </td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr style='background-color: #fff;'><td style='padding: 8px;text-align:center' colspan='6'>NO RECORD FOUND</td></tr>";
        }

        echo $output;
    }

    if($_GET['load_to_display_adjustment_reason']){
        $Reasons = $Store->getAdjustmentReasons("");
        $count = 1;
        $output = "";

        foreach($Reasons as $reason) :
            $output .= "
                <tr style='background-color: #fff;'>
                    <td style='padding: 6px;'><center>".$count++."</center></td>
                    <td style='padding: 6px;'>{$reason['name']}</td>
                    <td style='padding: 6px;'>
                        <select style='width: 100%;'>
                            <option value=''>{$reason['nature']}</option>
                        </select>
                    </td>
                    <td style='padding: 6px;'>
                        <select style='width: 100%;' id='{$reason['id']}' onchange='disableEnable({$reason['id']})'>
                            <option value='{$reason['enable_disable']}'>{$reason['enable_disable']}</option>
                            <option value='enable'>enable</option>
                            <option value='disable'>disable</option>
                        </select>
                    </td>
                </tr>
            ";
        endforeach;
        echo $output;
    }

    if($_POST['add_new_reason']){
        $reason = $_POST['reason'];
        $nature = $_POST['nature'];
        $Employee_ID = $_POST['Employee_ID'];

        echo $Store->createAdjReason($reason,$nature,$Employee_ID);
    }

    if($_POST['enable_disable_reasons']){
        $status = $_POST['status'];
        $param = $_POST['param'];
        echo $Store->disableEnableAdjReasons($param,$status);
    }

    if($_POST['create_new_store_order']){
        $CreateNewOrderObject = $_POST['CreateNewOrderObject'];
        echo $Store->createNewOrder($CreateNewOrderObject);
    }

    if($_POST['add_new_item_in_order']){
        $addNewItemInOrder = $_POST['addNewItemInOrder'];
        echo $Store->addNewItemInOrder_($addNewItemInOrder);
    }

    if($_GET['load_added_items']){
        $Order_Number = $_GET['Order_Number'];
        $Current_Store_ID = $_GET['Current_Store_ID'];
        $Output = "";
        $Count = 1;
        $Load_Items = $Store->getItemFromOrder($Order_Number,$Current_Store_ID);

        if(sizeof($Load_Items) > 0){
            foreach($Load_Items as $Item){
                $Output .= "
                    <tr style='background-color:#fff'>
                        <td style='padding:8px'><center>".$Count++."</center></td>
                        <td style='padding:8px'>{$Item['Product_Name']}</td>
                        <td style='padding:8px'><center>{$Item['Unit_Of_Measure']}</center></td>
                        <td style='padding: 2px;'><input type='text' onkeyup='calculateQty({$Item['Item_ID']})' style='text-align: center;' id='item_unit_{$Item['Item_ID']}' value='{$Item['Items_Qty']}' ></td>
                        <td style='padding: 2px;'><input type='text' onkeyup='calculateQty({$Item['Item_ID']})' id='item_per_unit_{$Item['Item_ID']}' value='{$Item['Container_Qty']}' style='text-align: center;'></td>
                        <td style='padding: 2px;'><input type='text' id='total_quantity_{$Item['Item_ID']}' readonly style='text-align: center;' class='total_qty' value='{$Item['Quantity_Required']}'></td>
                        <td style='padding: 2px;'><input type='text' style='text-align: center;' readonly value='{$Item['Item_Balance']}'></td>
                        <td style='padding: 2px;'><input type='text' style='text-align: center;'></td>
                        <td style='padding: 2px;'><center><a href='#' onclick='removeItem({$Current_Store_ID},{$Item['Item_ID']})'  class='art-button-green'>X</a></center></td>
                    </tr>
                ";
            }
        }else{
            $Output .= "<tr><td>NO DATA FOUND</td></tr>";
        }
        echo $Output;
    }

    if($_POST['update_order_qty']){
        $QuantityObject = $_POST['QuantityObject'];
        echo $Store->quantityUpdateForStoreOrder($QuantityObject);
    }

    if($_POST['submitStoreOrder']){
        $Order_Number = $_POST['Order_Number'];
        echo $Store->submitOrderFromStore($Order_Number);
    }

    if($_POST['remove_item_from_order']){
        $Remove_Object = $_POST['Remove_Object'];
        echo $Store->removeItemFromOrder_($Remove_Object);
    }
?>
