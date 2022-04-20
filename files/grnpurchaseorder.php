<style>
    .badge {
        position: absolute;
        top: -10px;
        right: -4px;
        padding: 6px 9px;
        border-radius: 50%;
        background: red !important;
        color: white;
        font-family: arial !important
    }
</style>

<?php
include("./includes/header.php");
include("./includes/connection.php");
include("return_unit_of_measure.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

$Grn_Status = '';
$Grn_Purchase_Order_ID = '';

$Insert_Status = 'fasle';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
        if ($_SESSION['userinfo']['can_edit'] != 'yes' && isset($_GET['src']) && $_GET['src'] = 'edit') {
            header("Location: ./previousgrnlist.php?PreviousGrn=PreviousGrnThisPage");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$canPakage = false;
$display = "style='display:none'";


if(isset($_GET['from'])){
    $displayBtn = 'display:none';
}

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //get number of pending request
        if (isset($_SESSION['Storage'])) {
            $Sub_Department_Name = $_SESSION['Storage'];
        } else {
            $Sub_Department_Name = '';
        }
        if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        } else {
            $Sub_Department_ID = '';
        }

        // $select_Order_Number = mysqli_query($conn, "SELECT store_requesting FROM tbl_local_purchase_order WHERE store_requesting='$Sub_Department_ID' AND submitted_for_grn_approval_status='not_submitted'") or die(mysqli_error($conn));
        // $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage' style='font-family:arial;$displayBtn' class='art-button-green'>NEW GRN</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        // $Select_Pending_Order_Number = mysqli_query($conn, "SELECT po.Purchase_Order_ID from  tbl_purchase_order po,tbl_pending_purchase_order_items poi,tbl_sub_department sd where
        //                     po.Purchase_Order_ID = poi.Purchase_Order_ID and
        //                     po.Sub_Department_ID = sd.Sub_Department_ID and
        //                     LOWER(poi.Grn_Status) = 'pending' and
        //                     sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by poi.Purchase_Order_ID") or die(mysqli_error($conn));
        // $number = mysqli_num_rows($Select_Pending_Order_Number);

        echo "<a href='grnpendingpurchaseorderlist.php?PendingPurchaseOrderList=PendingPurchaseOrderListThisPage' style='font-family:arial;$displayBtn' class='art-button-green'>PENDING PURCHASE ORDER</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        echo "<a href='list_of_grn_against_purchase_order.php' class='art-button-green' style='font-family:arial;$displayBtn'>APPROVED GRN</a>";
    }
}

$verify = "";
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='approve_grn_purchase_order.php' style='font-family:arial;$displayBtn' class='art-button-green'>APPROVE GRN AGAINST PURCHASE ORDER</a>";
        if($_GET['from'] == 'procurement'){
            echo "<a href='procurement-recieving.php' class='art-button-green'>BACK</a>
                  <input value='yes' type='hidden' id='from_status'>";
        }else{
            echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage' class='art-button-green'>BACK</a>
                  <input value='no' type='hidden' id='from_status'>";
        }
    }
}
?>

<?php
//get sub department id
if (isset($_SESSION['Storage'])) {
    $Sub_Department_Name = $_SESSION['Storage'];

    $sql_select = mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'") or die(mysqli_error($conn));
    $no_rows = mysqli_num_rows($sql_select);
    if ($no_rows > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            $Sub_Department_ID = $row['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }
}
?>


<?php
$local_purchase_order_id = (isset($_GET['local_purchase_order_id'])) ? $_GET['local_purchase_order_id'] : 0;
$purchase_requisition_id = (isset($_GET['purchase_requisition_id'])) ? $_GET['purchase_requisition_id'] : 0;
$Employee_ID = (isset($_SESSION['userinfo']['Employee_ID'])) ? $_SESSION['userinfo']['Employee_ID'] : 0;
$Employee_Name = (isset($_SESSION['userinfo']['Employee_Name'])) ? $_SESSION['userinfo']['Employee_Name'] : "";

# pull summation of other charges costs to get apportioned, multiplier for each buying price
$other_cost_total = 0;
$sub_total_pkg_mult = 0;
$multiplier = 0;

$get_other_charges_requsition = mysqli_query($conn,"SELECT * FROM tbl_procurement_others_charges WHERE purchase_requisition_id = $purchase_requisition_id") or die(mysqli_errno($conn));
$costs_details = mysqli_fetch_assoc($get_other_charges_requsition);
$discout_cost = $costs_details['discount'];
$other_costs = explode(",",$costs_details['costs']);

foreach($other_costs as $cost){
    $other_cost_total += $cost;
}

$summation_all_delivery = mysqli_query($conn,"SELECT item_per_container,buying_price FROM tbl_purchase_requisition_items WHERE purchase_requisition_id=$purchase_requisition_id") or die(mysqli_errno($conn));
while($sub_total_pkg = mysqli_fetch_assoc($summation_all_delivery)){
    $sub_total_pkg_mult += ($sub_total_pkg['item_per_container'] * $sub_total_pkg['buying_price']);
}


$multiplier =  ($other_cost_total/$sub_total_pkg_mult);

# new code for multiplier
$divider_total = 0;
$sql_items = mysqli_query($conn, "SELECT item_status,purchase_requisition_items_id,Item_ID,container_quantity,item_per_container,quantity_required,buying_price FROM tbl_purchase_requisition_items WHERE purchase_requisition_id='$purchase_requisition_id'") or die(mysqli_error($conn));
while($data = mysqli_fetch_assoc($sql_items)){
    $container_quantity = $data['container_quantity'];
    $item_per_container = $data['item_per_container'];
    $quantity_required = $data['quantity_required'];
    $buying_pricex = $data['buying_price'];
    $purchase_requisition_items_id = $data['purchase_requisition_items_id'];
    $item_status = $data['item_status'];

    $buying_price = ($buying_pricex / $quantity_required)+$multiplier;
    $new_buying_price = ($discout_cost/100)*$buying_price;
    $subtracted_price = $buying_price - $new_buying_price;
    $new = $subtracted_price * $quantity_required;
    $divider_total += $new;
}

$new_multiplier = ($other_cost_total / $divider_total) + 1;


# newer implementation
$new_item_sub_total = $other_cost_total*(20/100);
$new_item_sub_total_with_discount = $sub_total_pkg_mult - $new_item_sub_total;
$new_item_sub_total_with_discount_with_multiplier = ($other_cost_total / $new_item_sub_total_with_discount) + 1;

$percent = $sub_total_pkg_mult * ($discout_cost/100);
$percent_two = $sub_total_pkg_mult - $percent;
$percent_three = ($other_cost_total / $percent_two)+ 1;

// die("$sub_total_pkg_mult => $other_cost_total");

$sql_select_purchase_requisition_result = mysqli_query($conn, "SELECT lpo.Supplier_ID,lpo.local_purchase_order_id,lpo.store_recieving,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.store_requesting=sd.Sub_Department_ID AND lpo.local_purchase_order_id='$local_purchase_order_id'ORDER BY purchase_requisition_id DESC") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_select_purchase_requisition_result) > 0) {
    $count = 1;
    $invioce = "";
    while ($lpo_rows = mysqli_fetch_assoc($sql_select_purchase_requisition_result)) {
        $Store_Order_ID = $lpo_rows['Store_Order_ID'];
        $purchase_requisition_description = $lpo_rows['purchase_requisition_description'];
        $created_date_time = $lpo_rows['created_date_time'];
        $Employee_Name = $lpo_rows['Employee_Name'];
        $Supplier_Name = $lpo_rows['Supplier_Name'];
        $purchase_requisition_id = $lpo_rows['purchase_requisition_id'];
        $local_purchase_order_id = $lpo_rows['local_purchase_order_id'];
        $Sub_Department_Name = $lpo_rows['Sub_Department_Name'];
        // $Sub_Department_ID = $lpo_rows['Sub_Department_ID'];
        $store_recieving = $lpo_rows['store_recieving'];
        $Supplier_ID = $lpo_rows['Supplier_ID'];

        $Purchase_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Purchase_Type FROM tbl_purchase_requisition WHERE purchase_requisition_id = $purchase_requisition_id"))['Purchase_Type'];
    

        if($Purchase_Type == "Cash Purchases"){
            $invioce = "Reciept Number";
        }else{
            $invioce = "Inovice Number";
        }
    }
}


$get_current_mode_ = mysqli_fetch_assoc(mysqli_query($conn,"SELECT default_,currency_name,currency_symbol FROM tbl_purchase_requisition AS tpr,tbl_currency AS tc WHERE tpr.purchase_requisition_id = $purchase_requisition_id AND tpr.currency_id = tc.currency_id "));
$get_current_mode = $get_current_mode_['default_']
?>

<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: none !important;
    }
</style>
<br />
<br />

<fieldset style="padding: 5px;">
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; }?></legend>
    <table width='100%'>
        <tr>
            <td style="padding:5px">LPO Number</td>
            <td><input type='text' name='order_id' id='local_purchase_order_id' value='<?php echo $local_purchase_order_id; ?>' readonly='readonly'></td>

            <td style="padding:5px">GRN Number</td>
            <td><input type='text' name='grn_number' id='grn_number' value='<?php echo $purchase_requisition_id; ?>' readonly='readonly'></td>

            <td style="padding:5px">Order Date</td>
            <td><input type='text' name='grn_date' id='grn_date' readonly='readonly' value='<?=$created_date_time;?>'></td>

            <td style="padding:5px">Supplier</td>
            <td><input type='text' name='Supplier_Name' id='Supplier_Name' value='<?php echo $Supplier_Name; ?>' readonly='readonly'></td>
        </tr>

        <tr>
            <td style="padding:5px">Receiver</td>
            <td><input type='text' name='Receiver_Name' id='Receiver_Name' readonly='readonly' value='<?php if (!empty($Employee_Name_grn)) { echo $Employee_Name_grn;} else { if ($Employee_ID != 0 && $Employee_Name != '') { echo $Employee_Name; } }?>'></td>

            <td style="padding:5px">Delivery Note Number</td>
            <td><input type='text' required name='Debit_Note_Number' oninput="updateinfo('Debit_Note_Number',this.value,<?php echo $Purchase_Order_ID; ?>)" id='Debit_Note_Number' value='<?php echo $Debit_Note_Number; ?>' /></td>

            <td style="padding:5px"><?=$invioce?></td>
            <td>
                <input type='text' <?=$style?> name='Invoice_Number' oninput="updateinfo('Invoice_Number',this.value,<?php echo $Purchase_Order_ID; ?>)" id='Invoice_Number' value='<?php echo $Invoice_Number; ?>' />
                <input type="hidden" id='check_purchases_type' value="<?=$Purchase_Type?>">
            </td>

            <td style="padding:5px">Delivery Date</td>
            <td><input type='text' required name='Delivery_Date' readonly="readonly" onchange="updateinfo('Delivery_Date', this.value,<?php echo $Purchase_Order_ID; ?>)" class='delivery_date' id='Delivery_Date' value='<?php echo $Delivery_Date; ?>' /></td>
        </tr>

        <tr>
            <td style="padding:5px">Delivery Person</td>
            <td>
                <input type='text' name='Delivery_Person' oninput="updateinfo('Delivery_Person',this.value,<?php echo $Purchase_Order_ID; ?>)" id='Delivery_Person' value='<?php echo $Delivery_Person; ?>' />
                <input type='hidden' name='query_string' id='query_string' value='<?php echo $_SERVER['QUERY_STRING']; ?>' />
                <input type='hidden' name='Purchase_Order_ID' id='Purchase_Order_ID' value='<?php echo $Purchase_Order_ID; ?>' />
                <input type='hidden' name='Supplier_ID' id='Supplier_ID' value='<?php echo $Supplier_ID; ?>' />
            </td>

            <td style="padding:5px">RV Number</td>
            <td><input type='text' required name='RV_Number' onchange="updateinfo('RV_Number', this.value,<?php echo $Purchase_Order_ID; ?>)" id='RV_Number' value='<?php echo $RV_Number; ?>' /></td>
                <?php
                    if($_GET['from'] == 'procurement'){
                        echo "
                            <td style='padding:6px'>Receiving Store</td>
                            <td >
                                <select name='' id='recieving_store' style='width: 100%;padding:5px'>
                                    <option value=''>Select Recieving Store</option>
                            ";
                        $select_sub_departments = mysqli_query($conn,"SELECT sdep.Sub_Department_ID,Sub_Department_Name from tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                      WHERE dep.department_id = sdep.department_id AND sdep.Sub_Department_Status = 'active' AND sdep.privileges='normal' AND
                                                                      ed.Sub_Department_ID = sdep.Sub_Department_ID AND dep.Department_Location IN('Pharmacy','Storage And Supply') GROUP BY Sub_Department_Name
                        ");
                        while($row = mysqli_fetch_array($select_sub_departments)){
                            echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
                        }
                        
                        echo "
                            </select></td>
                        ";
                    } 
                ?>

            <td style="padding:5px"><b><?=$get_current_mode_['currency_name'];?> (<?=$get_current_mode_['currency_symbol'];?>)</b></td>
            <td><input type='text' required name='Exchange_Rate' onchange="updateinfo('Exchange_Rate', this.value,<?php echo $Purchase_Order_ID; ?>)" id='Exchange_Rate' value='<?php echo $Exchange_Rate; ?>' placeholder="Exchange Rate" /></td>
        </tr>
        <tr>
            <td style="padding:5px">PR Description</td>
            <td><input type="text" id="" value="<?=$purchase_requisition_description?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 500px;'>
    <center>
        <table width='100%' class='table'>
            <?php
                echo "
                    <tr style='background-color:#ddd'>
                        <td width=3% style='text-align: center;'>S/N</td>
                        <td width=4%><center>Folio No</center></td>
                        <td width=20%>Item Name</td>
                        <td width=5% style='text-align: center;'>U0M</td>
                        <!--<td width=9% style='text-align: center;'><b>Pack Required</b></td>-->
                        <!--<td width=6% style='text-align: center;'>Items required per Pack</td>-->
                        <td width=6% style='text-align: center;font-weight:500'>QTY REQUIRED</td>
                        <td width=6% style='text-align: center;font-weight:500'>UNIT RECIEVED</td>
                        <td width=9% style='text-align: center;font-weight:500'>ITEMS PER UNIT</td>
                        <td width=6% style='text-align: center;font-weight:500'>QTY RECIEVED</td>
                        <td width=6% style='text-align: right;font-weight:500'>BUYING PRICE</td>
                        <td width=6% style='text-align: center;font-weight:500'>BATCH NO.</td>
                        <td width=6% style='text-align: center;font-weight:500'>EXPIRE DATE</td>
                        <td width=10% style='text-align: center;font-weight:500'>BAR CODE</td>
                        <td width=7% style='text-align: center;font-weight:500'>STATUS</td>";
                echo "<td width=7% style='text-align: right;font-weight:500'>AMOUNT</td>";
                echo "<td width=7% style='text-align: center;font-weight:500'>ACTION</td>";
            ?>
            </tr>
            <?php

            $Temp = 1;
            $total = 0;
            $grand_total = 0;
            $array_of_status = array();
            $sql_select_purchase_requisition_items = mysqli_query($conn, "SELECT item_status,purchase_requisition_items_id,Item_ID,container_quantity,item_per_container,quantity_required,buying_price FROM tbl_purchase_requisition_items WHERE purchase_requisition_id='$purchase_requisition_id' AND item_status IN ('active','OUTSTANDING')") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_purchase_requisition_items) > 0) {
                while ($pr_items_rows = mysqli_fetch_assoc($sql_select_purchase_requisition_items)) {
                    $Item_ID = $pr_items_rows['Item_ID'];
                    $container_quantity = $pr_items_rows['container_quantity'];
                    $item_per_container = $pr_items_rows['item_per_container'];
                    $quantity_required = $pr_items_rows['quantity_required'];
                    $buying_pricex = $pr_items_rows['buying_price'];
                    $buying_prices__ = $pr_items_rows['buying_price'];
                    $purchase_requisition_items_id = $pr_items_rows['purchase_requisition_items_id'];
                    $item_status = $pr_items_rows['item_status'];

                    array_push($array_of_status,$item_status);

                    //get item balance
                    $sub_total = 0;
                    $get_balance = mysqli_query($conn, "SELECT Item_Balance from tbl_items_balance where Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                    $n_get = mysqli_num_rows($get_balance);

                    if ($n_get > 0) {
                        while ($nget = mysqli_fetch_array($get_balance)) {
                            $Item_Balance = $nget['Item_Balance'];
                        }
                    } else {
                        $Item_Balance = 0;
                    }

                    # get item name and folio number
                    $sql_select_item_detail_result = mysqli_query($conn, "SELECT Product_Name,item_folio_number FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_item_detail_result) > 0) {
                        while ($items_rows = mysqli_fetch_assoc($sql_select_item_detail_result)) {
                            $Product_Name = $items_rows['Product_Name'];
                            $item_folio_number = $items_rows['item_folio_number'];
                        }
                    }

                    # Buying price extracted from apportioned price
                    $newer_calc = $buying_pricex * $item_per_container;
                    $price_per_one = $newer_calc / $quantity_required;
                    $subtracted_price = (($price_per_one - ($price_per_one * ($discout_cost/100)))) * $percent_three;


                    $Unit_Of_Measure = unitOfMeasure($Item_ID);
                    echo "<tr style='background-color:#fff'>  
                            <input type='text' class='lpo_item_id' hidden='hidden' value='$Item_ID'>
                            <input type='text' id='lpo_id_of_item$Item_ID' hidden='hidden' value='" . $purchase_requisition_items_id . "'>
                            <td><input type='text' style='text-align:center' readonly='readonly'value='$Temp'/></td>
                            <td><input type='text' readonly='readonly'value='$item_folio_number'/></td>
                            <td><input type='text' readonly='readonly'value='$Product_Name'/></td>
                            <td><input type='text' style='text-align:center' readonly='readonly'value='$Unit_Of_Measure'/></td>
                            <input type='hidden' id='container_qty' readonly='readonly' value='$container_quantity' />
                            <input type='hidden' id='item_per_container' style='text-align:center' readonly='readonly' value='$item_per_container'/>
                            <td style='text-align:center'><input type='text' style='text-align:center' id='qty_required$Item_ID' readonly='readonly' value='$quantity_required'/></td>
                            <td style='text-align:center'><input type='text' style='text-align:center' id='container_quantity_$Item_ID' onkeyup='calculate_container_quantity($Item_ID,$buying_pricex)' onkeyup='numberOnly(this);  value=''/></td>
                            <td style='text-align:center'><input type='text' style='text-align:center' id='item_per_container$Item_ID'  onkeyup='calculate_item_per_quantity($Item_ID,".($buying_pricex).")' onkeyup='numberOnly(this); value=''/></td>
                            <td style='text-align:right'><input type='text' style='text-align:center' readonly='readonly'  id='quantity_required_$Item_ID'  onkeyup='calculate_quantity($Item_ID,".($buying_pricex).")' onkeyup='numberOnly(this); value=''/></td>
                            <td><input type='text' value='".($buying_pricex)."' id='buying_price$Item_ID' class='hide'/><input type='text' style='text-align:right' readonly='readonly'value='" . ($buying_prices__) . "'/></td>
                            <td><input type='text' value='' style='text-align:center' id='batch_no$Item_ID' /></td>
                            <td><input type='text' style='text-align:center' readonly='readonly'value='$item_qty_with_discount' onchange='checkDate(this.value,$Item_ID)' id='expiredate$Item_ID' class='item_exipiredate'/></td>
                            <td><input type='text' value='' style='text-align:center' id='bar_code$Item_ID'/></td>
                            <td><select name='Grn_Status' style='padding:5px' class='items_status' id='Grn_Status$Item_ID' onchange='open_rejection_dialog($Item_ID)'>";

                    echo "<option value='RECEIVED'> RECEIVED </option>";
                    echo "<option value='OUTSTANDING'> PARTIAL </option>";
                    echo "<option value='PENDING'> UNDELIVERED </option>";
                    echo "<option value='DISCARD'> DISCARD </option>";
                    echo "<option value='REJECTED'> REJECTED </option>";
                    echo "</select>
                            </td>
                                <td><input type='text' hidden='hidden' id='sub_txt$Item_ID' class='sub_total_txt' /><input style='text-align:right' type='text' readonly='readonly' id='sub$Item_ID' value='" . number_format(0) . "'/></td>
                                <td><input type='button' class='art-button-green' onclick='remove_item($purchase_requisition_items_id)' value='X'
                            </tr>";

                    echo "<tr style='display:none'>
                            <td colspan='16'>
                                <table id='rejection_dialog$Item_ID' width='100%' class='table'>
                                    <tr>
                                        <td>Item name</td>
                                        <td colspan='3'>$Product_Name</td>
                                    </tr>
                                    <tr>
                                        <td>Rejected Quantity</td>
                                        <td><input type='text' value='' id='rejected_quantity$Item_ID' class='form-control'/></td>
                                        <td>Rejection Reason</td>
                                        <td><input type='text' value='' id='rejection_reason$Item_ID' class='form-control'/></td>
                                    </tr>
                                    <tr>
                                        <td colspan='4'>
                                            <input type='button' value='CLOSE' onclick='close_rejection_dialog($Item_ID)' class='art-button pull-right'/>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            </tr>";
                    $Temp++;
                    $grand_total += $sub_total;
                }
            }
            ?>
            <tr style="background-color: #ddd;">
                <td colspan="14" style='text-align: right;'><b>GRAND TOTAL</b>&nbsp;&nbsp;&nbsp;<b id="Grand_Total_Total"><?php echo number_format($Grand_Total, 2); ?></b></td>
                <td></td>
            </tr>
        </table>
    </center>
</fieldset>
<fieldset>
    <table class="table">
        <tr>
            <td>
                <span id='submit_feedback' class="pull-right"></span><input type="button" value="SUBMIT" onclick='submit()' class="art-button-green pull-right" />
                <span id='submit_feedback' class="pull-right"></span><input type="button" value="CREATE NEW ORDER FOR DISCARDED ITEMS" onclick='automatic_create_order_for_discarded_items()' class="art-button-green pull-right" />
            </td>
        </tr>
    </table>
</fieldset>

<!-- create new order for discarded items -->
<script>
    function automatic_create_order_for_discarded_items() {  
        var items_status = $("select[class='items_status']").map(function() { return $(this).val(); }).get();
        var items_id = $("input[class='lpo_item_id']").map(function() { return $(this).val(); }).get();
        var qty_required = $("input[id='qty_required']").map(function() { return $(this).val(); }).get();
        var item_per_container = $("input[id='item_per_container']").map(function() { return $(this).val(); }).get();
        var container_qty = $("input[id='container_qty']").map(function() { return $(this).val(); }).get();
        var itemsId_to_be_created = "";
        var quantity_required = "";
        var employee_ID = '<?=$_SESSION['userinfo']['Employee_ID']?>';
        var purchase_requisition_id = <?=$_GET['purchase_requisition_id']?>;
        var Sub_Department_ID = <?=$Sub_Department_ID?>;
        var auto_create_new_order = "auto_create_new_order";
        var item_per_container_count = "";
        var container_qty_count = "";

        for(var i =0 ;i < items_status.length;i++){
            if(items_status[i] == 'DISCARD'){
                itemsId_to_be_created += items_id[i]+',';
                quantity_required += qty_required[i]+',';
                item_per_container_count += item_per_container[i]+',';
                container_qty_count += container_qty[i]
            }
        }

            if(itemsId_to_be_created.length <= 0){
                alert('No Item With Discard Status');
            }else{
                if(confirm('Are you sure you want create order for items with discarded status')){        
                    $.post('procument-core.php',{
                        itemsId_to_be_created:itemsId_to_be_created.toString(),
                        quantity_required:quantity_required.toString(),
                        item_per_container_count:item_per_container_count.toString(),
                        employee_ID:employee_ID,
                        purchase_requisition_id:purchase_requisition_id,
                        Sub_Department_ID:Sub_Department_ID,
                        auto_create_new_order:auto_create_new_order,
                        container_qty_count:container_qty_count
                    },(response) => {
                        if(response == 1){
                            location.reload(true);
                        }else{
                            alert(response);
                        }
                    })
                }
            }
        }
    
</script>
<!-- create new order for discarded items -->

<script>
    function remove_item(id) {
        var user_id = '<?= $_SESSION['userinfo']['Employee_ID'] ?>';
        if (confirm('Are you sure you want to removed selected item')) {
            $.get(
                'remove_purchase_requisition.php', {
                    id: id,
                    user_id: user_id
                }, (response) => {
                    alert(response);
                    location.reload(true);
                }
            );
        }
    }
</script>

<script>
    function checkDate(date,Item_Id){
        var check_days = "check_days";
        $.ajax({
            type: "POST",
            url: "days_handle.php",
            data: {
                date:date,
                check_days:check_days,
                Item_Id:Item_Id
            },
            cache:false,
            success: (response) => {
                if(response == 2){
                    alert("Expire date out of duration cant be recieved");
                }
            }
        });
    }
</script>

<script>
    function calculate_container_quantity(Item_ID, buying_price) {
        var container_quantity = parseInt($("#container_quantity_" + Item_ID).val());
        var item_per_container = parseInt($("#item_per_container" + Item_ID).val());

        // alert(item_quanity_to_recieve);
        if (!isNaN(container_quantity * item_per_container)) {
            $("#quantity_required_" + Item_ID).val(container_quantity * item_per_container);
            calculate_item_price(buying_price, container_quantity * item_per_container, Item_ID);
        }
    }

    function calculate_item_per_quantity(Item_ID, buying_price) {
        var container_quantity = parseInt($("#container_quantity_" + Item_ID).val());
        var item_per_container = parseInt($("#item_per_container" + Item_ID).val());
        var item_quanity_to_recieve = parseInt($("#qty_required" + Item_ID).val());

        if (container_quantity <= 0) {
            $("#container_quantity_" + Item_ID).val('1');
        }
        if (!isNaN(container_quantity * item_per_container)) {
            if((container_quantity * item_per_container) > item_quanity_to_recieve){
                $("#item_per_container" + Item_ID).val(0);
                $("#quantity_required_" + Item_ID).val(0);
                alert('Youre recieving more than required');
            }else{
                $("#quantity_required_" + Item_ID).val(container_quantity * item_per_container);
                if (!isNaN(container_quantity * item_per_container)) calculate_item_price(buying_price, container_quantity * item_per_container, Item_ID)
            }
        }
    }

    function calculate_quantity(Item_ID, buying_price) {
        var container_quantity = $("#container_quantity_" + Item_ID).val();
        var item_per_container = $("#item_per_container" + Item_ID).val();
        var quantity_required_ = $("#quantity_required_" + Item_ID).val();
        if (container_quantity <= 0) {
            $("#container_quantity_" + Item_ID).val('1');
        }
        if (item_per_container <= 0) {
            $("#item_per_container" + Item_ID).val(quantity_required_);
        }
        if (!isNaN(quantity_required_ / container_quantity)) {
            if (container_quantity == "") {
                $("#item_per_container" + Item_ID).val(quantity_required_)
            } else {
                $("#item_per_container" + Item_ID).val(quantity_required_ / container_quantity);
            }
            if (!isNaN(quantity_required_)) calculate_item_price(buying_price, quantity_required_, Item_ID)
        }
    }

    function calculate_item_price(buying_price, Quantity_Required, Item_ID) {
        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };
        var item_buying_price = 0;
        if (isNaN((buying_price))) {
            item_buying_price = 0;
        } else {
            item_buying_price = (buying_price);
        }


        var Exchange_Rate = $('#Exchange_Rate').val();
        var Rate = 1;

        var get_current_mode = '<?=$get_current_mode?>';
        if(get_current_mode == 'no' && Exchange_Rate == ""){
            alert("Please fill exchange rate");
            $('#Exchange_Rate').css('border','1px solid red');
            exit();
        }

        if(get_current_mode == 'no' && Exchange_Rate != ""){
            Rate = Exchange_Rate;
        }

        var sub_total = (item_buying_price*Rate) * (Quantity_Required);

        if(get_current_mode == 'no' && Exchange_Rate != ""){

        }else{

        }



        $("#sub_txt" + Item_ID).val(sub_total);
        $("#sub" + Item_ID).val(sub_total);
        var grand_total = 0;
        var sub_total_txt = $('.sub_total_txt');
        for (var i = 0; i < sub_total_txt.length; i++) {
            if (!isNaN(parseFloat($(sub_total_txt[i]).val()))) grand_total = grand_total + parseFloat($(sub_total_txt[i]).val());
        }
        console.log('data',grand_total);
        $("#Grand_Total_Total").html("<b>" + grand_total.format() + "</b>");
    }

    function submit() {
        var receiver_Employee_ID = '<?= $Employee_ID ?>';
        var local_purchase_order_id = '<?= $local_purchase_order_id ?>';
        var Supplier_ID = '<?= $Supplier_ID ?>';
        var Delivery_Note_Number = $("#Debit_Note_Number").val();
        var Invoice_Number = $("#Invoice_Number").val();
        var Delivery_Date = $("#Delivery_Date").val();
        var Delivery_Person = $("#Delivery_Person").val();
        var RV_Number = $("#RV_Number").val();
        var purchase = $('#check_purchases_type').val();
        var from_status = $('#from_status').val();
        var Sub_Department_ID = "";

        if(from_status == 'yes'){
            var Sub_Department_ID = $('#recieving_store').val();
        }else{
            var Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        }

        if(Sub_Department_ID == "" || Sub_Department_ID == null){
            alert("Please Select Recieving Store");
            exit();
        }

        if(purchase == "Cash Purchases" && purchase == ""){
            $('#Invoice_Number').css('border','1px solid red');
        }else{
            if (Delivery_Note_Number == "" || Delivery_Note_Number <= 0) {
                $("#Debit_Note_Number").css("border", "2px solid red");
                exit;
            } else {
                $("#Debit_Note_Number").css("border", "")
            }
            if (Delivery_Date == "" || Delivery_Date <= 0) {
                $("#Delivery_Date").css("border", "2px solid red");
                exit;
            } else {
                $("#Delivery_Date").css("border", "")
            }
            // if (Invoice_Number == "" || Invoice_Number <= 0) {
            //     $("#Invoice_Number").css("border", "2px solid red");
            //     exit;
            // } else {
            //     $("#Invoice_Number").css("border", "")
            // }

            var lpo_items_for_grn = [];
            var Item_ID;

            $(".lpo_item_id").each(function() {
                Item_ID = $(this).val();

                var container_quantity = $("#container_quantity_" + Item_ID).val();
                var item_per_container = $("#item_per_container" + Item_ID).val();
                var quantity_required_ = $("#quantity_required_" + Item_ID).val();
                var lpo_id_of_item = $('#lpo_id_of_item' + Item_ID).val();

                var expiredate = $("#expiredate" + Item_ID).val();
                var batch_no = $("#batch_no" + Item_ID).val();
                var Grn_Status = $("#Grn_Status" + Item_ID).val();
                var rejected_quantity = $("#rejected_quantity" + Item_ID).val();
                var rejection_reason = $("#rejection_reason" + Item_ID).val();
                var buying_price = $("#buying_price" + Item_ID).val();
                var bar_code = $("#bar_code" + Item_ID).val();

                if ((quantity_required_ == "" || quantity_required_ <= 0) && ($("#Grn_Status" + Item_ID).val()) != "PENDING") {
                    // alert('0000 = '+ $("#Grn_Status" + Item_ID).val());
                    $("#quantity_required_" + Item_ID).css("border", "2px solid red");
                    exit;
                } else {
                    $("#quantity_required_" + Item_ID).css("border", "")
                }
                if ((rejected_quantity == "" || rejected_quantity <= 0) && ($("#Grn_Status" + Item_ID).val()) == "REJECTED") {
                    $("#rejected_quantity" + Item_ID).css("border", "2px solid red");
                    $("#rejection_dialog" + Item_ID).dialog({
                        title: 'REJECTION DETAILS',
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
                    exit;
                } else {
                    $("#rejected_quantity" + Item_ID).css("border", "")
                }
                if ((rejection_reason == "" || rejection_reason <= 0) && ($("#Grn_Status" + Item_ID).val()) == "REJECTED") {
                    $("#rejection_reason" + Item_ID).css("border", "2px solid red");
                    $("#rejection_dialog" + Item_ID).dialog({
                        title: 'REJECTION DETAILS',
                        width: '50%',
                        height: 250,
                        modal: true,
                    });
                    exit();
                } else {
                    $("#rejection_reason" + Item_ID).css("border", "")
                }

                if ((quantity_required_ == "" || quantity_required_ <= 0) && $("#Grn_Status" + Item_ID).val() != "PENDING") {
                    $("#batch_no" + Item_ID).css("border", "2px solid red");
                    exit;
                } else {
                    $("#batch_no" + Item_ID).css("border", "")
                }

                if ((quantity_required_ == "" || quantity_required_ <= 0) && $("#Grn_Status" + Item_ID).val() != "PENDING") {
                    $("#expiredate" + Item_ID).css("border", "2px solid red");
                    exit;
                } else {
                    $("#expiredate" + Item_ID).css("border", "")
                }

                lpo_items_for_grn.push(Item_ID + "kiunganishi" + container_quantity + "kiunganishi" + item_per_container + "kiunganishi" + quantity_required_ + "kiunganishi" + expiredate + "kiunganishi" + batch_no + "kiunganishi" + Grn_Status + "kiunganishi" + rejected_quantity + "kiunganishi" + rejection_reason + "kiunganishi" + buying_price + "kiunganishi" + lpo_id_of_item + "kiunganishi" + bar_code);
            });
            $("#submit_feedback").html("submiting...please wait");

            var Grn_Statuses = $("select[name='Grn_Status']").map(function() {
                return $(this).val();
            }).get();

            var Grn_Statuses_to_string = Grn_Statuses.toString();

            $.ajax({
                type: 'GET',
                url: 'submit_grn_for_approval.php',
                data: {
                    lpo_items_for_grn: lpo_items_for_grn,
                    receiver_Employee_ID: receiver_Employee_ID,
                    local_purchase_order_id: local_purchase_order_id,
                    Sub_Department_ID: Sub_Department_ID,
                    Supplier_ID: Supplier_ID,
                    Delivery_Note_Number: Delivery_Note_Number,
                    Invoice_Number: Invoice_Number,
                    Delivery_Date: Delivery_Date,
                    Delivery_Person: Delivery_Person,
                    RV_Number: RV_Number,
                    Grn_Statuses_to_string: Grn_Statuses_to_string
                },
                success: (data) => {
                    console.log(data);
                    if (data == 1) {
                        var purchase_requisition_id = '<?= $purchase_requisition_id ?>';
                        document.location = "approve_grn_purchase_order.php?purchase_requisition_id=" + purchase_requisition_id + "&local_purchase_order_id=" + local_purchase_order_id+'&from_procurement='+from_status;
                        $("#submit_feedback").html("");
                    } else {
                        $("#submit_feedback").html("Process Fail...try again");
                    }
                },
                error: function(x, y, z) {
                    console.log(z);
                }
            });
        }
    }

    function open_rejection_dialog(Item_ID) {
        if (($("#Grn_Status" + Item_ID).val()) == "REJECTED") {
            $("#rejection_dialog" + Item_ID).dialog({
                title: 'REJECTION DETAILS',
                width: '50%',
                height: 250,
                modal: true,
            });
        }
    }

    function close_rejection_dialog(Item_ID) {
        $("#rejection_dialog" + Item_ID).dialog("close");
    }

    $(document).ready(function() {
        $('.item_exipiredate').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            dateFormat: "yy-mm-dd"
            // showAnim: "bounce"
        });
    });

    $(document).ready(function() {
        $('.delivery_date').datepicker({
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            dateFormat: "yy-mm-dd"
        });
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php include("./includes/footer.php"); ?>
