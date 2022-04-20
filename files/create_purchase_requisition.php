<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procurement_Works'])) {
        if ($_SESSION['userinfo']['Procurement_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Store_Order_ID = (isset($_GET['Store_Order_ID'])) ? $_GET['Store_Order_ID'] : "";
$Sub_Department_Name = (isset($_GET['Sub_Department_Name'])) ? $_GET['Sub_Department_Name'] : "";
$Sub_Department_ID = (isset($_GET['Sub_Department_ID'])) ? $_GET['Sub_Department_ID'] : "";

include 'procurement/procure.interface.php';
$Procure = new ProcureInterface();$counter = 1;

?>
<a href="procurementstoreorderlist.php?StoreOrderList=StoreOrderListThisPage" class="art-button-green">BACK</a>
<br />
<br />
<style>
    table, tr, td { border: none !important; }
    tr { border: 1px solid #ccc !important; }
</style>

<fieldset>
    <legend>CREATE PURCHASE REQUISITION</legend>
    <table width='100%' >
        <tr>
            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Store Requesting</b></td>
            <td style="padding: 8px;" width='25%'>
                <select name='Store_Need' id='Store_Need' style='width:100%'>
                    <option value="<?= $Sub_Department_ID ?>"><?= ucwords($Sub_Department_Name) ?></option>
                </select>
            </td>
        
            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Purchase Type</b></td>
            <td style="padding: 8px;" width='25%'>
                <select id="local_foreign" style="width:100%">
                    <option value="">Select Purchase Type</option>
                    <option value="local">Local</option>
                    <option value="foreign">Foreign</option>
                </select>
            </td>

            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Mode Of Payment</b></td>
            <td style="padding: 8px;" width='25%'>
                <select id="Purchase_Type" class="Purchase_Type" style='width:100%'>
                    <option value="">Select Mode Of Payment</option>
                    <option value="Credit Purchases">Credit Purchases</option>
                    <option value="Cheque Purchases">Cheque Purchases</option>
                    <option value="Medical Store Dept">Medical Store Dept</option>
                    <option value="Cash Purchases">Cash Purchases</option>
                </select>
            </td>
        </tr>

        <tr>
            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Supplier</b></td>
            <td style="padding: 8px;" width='25%'>
                <select id="Supplier_ID" style='width:100%'>
                    <option value="">Select Supplier</option>
                    <?php foreach($Procure->getAllSuppliers() as $suppliers) :  ?>
                        <option value="<?=$suppliers['Supplier_ID']?>"><?=ucwords($suppliers['Supplier_Name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Currency</b></td>
            <td style="padding: 8px;" width='25%'>
                <select id="Currency_ID" class="Currency_ID" style='width:100%'>
                    <option value="none">Select Currency</option>
                    <?php foreach($Procure->getCurrency() as $currency) :  ?>
                        <option value="<?=$currency['currency_id']?>"><?=ucwords($currency['currency_name']) ?> ~ <?=$currency['currency_symbol']?></option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td style="padding: 8px;padding-top: 12px" width='7%'><b>Order Description</b></td>
            <td colspan="1" style="padding: 8px;" width='25%'>
                <?php
                    $sql_select_description_result = mysqli_query($conn, "SELECT Order_Description FROM tbl_store_orders WHERE Store_Order_ID='$Store_Order_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_description_result) > 0) {
                        while ($description_rows = mysqli_fetch_assoc($sql_select_description_result)) {
                            $Order_Description = $description_rows['Order_Description'];
                        }
                    }
                ?>
                <input type="text" style="padding: 5px;background-color:#fff" readonly name="" id="" class='form-control' value="<?= $Order_Description ?>">
            </td>
        </tr>
    </table>
</fieldset>



<fieldset style='overflow-y: scroll; height: auto;background: #FFFFFF' id='Items_Fieldset_List'>
    <?php
    echo '<center><table width=100% class="table" id="table" border=0>';
    echo '<tr style="background-color:#eee"><td width=4% style="text-align: center;"><b>SN</b></td>
				<td><b>ITEM NAME</b></td>
				<td width=8% style="text-align: center;"><b>UOM</b></td>
				<td width=8% style="text-align: center;"><b>UNIT</b></td>
                <td width=8% style="text-align: center;"><b>ITEM PER UNIT</b></td>
				<td width=8% style="text-align: center;"><b>QUANTITY</b></td>
				<td width=8% style="text-align: center;"><b>BALANCE IN STOCK</b></td>
				<td width=8% style="text-align: right;"><b>PRICE</b></td>
				<td width=8% style="text-align: right;"><b>SUB TOTAL</b></td>
				<td width=8% style="text-align: right;"><b id="select_option"><input type="checkbox" id="select_all_checkbox"/></b></td>
			</tr>';


    $Temp = 1;
    $total = 0;
    $Store_Order_Items_SQL = mysqli_query($conn, "SELECT soi.Item_ID, soi.Last_Buying_Price, soi.Quantity_Required, soi.Item_Remark,
                                                    soi.Order_Item_ID, so.Sub_Department_ID,
                                                    soi.Items_Qty, soi.Container_Qty
                                                FROM tbl_store_order_items soi, tbl_store_orders so
                                                WHERE soi.Store_Order_ID = '$Store_Order_ID' AND
                                                    soi.Store_Order_ID = so.Store_Order_ID AND
                                                    soi.Item_Status != 'removed' AND
                                                    soi.Procurement_Status in ('active', 'selected')") or die(mysqli_error($conn));
    $Store_Order_Items_Num = mysqli_num_rows($Store_Order_Items_SQL);
    $counter = 0;
    if ($Store_Order_Items_Num > 0) {
        while ($data = mysqli_fetch_array($Store_Order_Items_SQL)) {
            $Item_ID = $data['Item_ID'];

            $Quantity_Required = $data['Quantity_Required'];
            $Container_Qty = $data['Container_Qty'];
            $Items_Per_Container = $data['Items_Qty'];
            $Item_Remark = $data['Item_Remark'];
            $Order_Item_ID = $data['Order_Item_ID'];
            $Sub_Department_ID = $data['Sub_Department_ID'];

            //get item balance
            $get_balance = mysqli_query($conn, "select Item_Balance from tbl_items_balance where Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
            $n_get = mysqli_num_rows($get_balance);
            if ($n_get > 0) {
                while ($nget = mysqli_fetch_array($get_balance)) {
                    $Item_Balance = $nget['Item_Balance'];
                }
            } else {
                $Item_Balance = 0;
            }
            //get item name and folio number
            $sql_select_item_detail_result = mysqli_query($conn, "SELECT Unit_Of_Measure,Product_Name,item_folio_number FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_select_item_detail_result) > 0) {
                while ($items_rows = mysqli_fetch_assoc($sql_select_item_detail_result)) {
                    $Product_Name = $items_rows['Product_Name'];
                    $item_folio_number = $items_rows['item_folio_number'];
                    $Unit_Of_Measure = $items_rows['Unit_Of_Measure'];
                }
            }
            echo "<tr>
                    <td><center>$Temp</center></td>
                    <td>$Product_Name</td>
                    <td style='text-align:center'>$Unit_Of_Measure </td>
                    <td style='text-align:center'>$Items_Per_Container <input type='hidden' id='item_container_qty$Item_ID' value='$Items_Per_Container'/></td>
                    <td style='text-align:center'>$Container_Qty <input type='hidden' id='container_qty$Item_ID' value='$Container_Qty'/></td>                   
                    <td style='text-align:center'>$Quantity_Required <input type='text' id='item_quantity$Item_ID' value='$Quantity_Required'hidden='hidden' value='0'/></td>
                    <td style='text-align:center'>$Item_Balance</td>
                    <td><input type='text' style='text-align:right' onkeyup='calculate_item_price(this,$Container_Qty,$Item_ID)' id='buying_price_txt$Item_ID'/></td>
                    <td  style='text-align:right'><input type='text' id='sub$Item_ID' hidden='hidden' class='sub_total_txt' value='0'/><span id='$Item_ID'></span></td>
                    <td style='text-align:right'><input type='checkbox' class='select_item_for_pr' value='$Item_ID' /></td>
                </tr>";
            $Temp++;
        }
    }
    echo "
    <tr style='background-color:#eee'>
        <td colspan='7'><b>SUB TOTAL :</b></td>
        <td id='grand_total_price' style='text-align:right'></td>
        <td></td>
    </tr>
";
    ?>

    <tr style="background-color: #eee;">
        <td colspan="9" style="padding: 8px;font-weight:bold">OTHER CHARGES</td>
    </tr>

    <?php 
        $get_charges_items = mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Item_Type = 'Procurement Charges'");
        $counts = 1;
        while($data = mysqli_fetch_assoc($get_charges_items)){
            echo "
                <tr>
                    <td style='padding: 8px;'><center>{$counts}</center></td>
                    <td style='padding: 8px;' colspan='5'>{$data['Product_Name']}</td>
                    <td style='padding: 8px;'><input type='text' id='{$data['Item_ID']}' class='charges_total' value='0' onkeyup='calcalatute_additional_charges({$data['Item_ID']})' style='text-align: end;'></td>
                    <td style='padding: 8px;'><input type='text' id='calc_{$data['Item_ID']}' style='text-align: right;border:none' value='0'></td>
                </tr>
            ";
            $counts++;
        }
   
        echo "
            <tr>
                <td style='padding: 8px;'><center></center></td>
                <td style='padding: 8px;' colspan='5'>Discount</td>
                <td style='padding: 8px;'><input type='text' id='discount' onkeyup='calculate_discount()' style='text-align: end;border:none;background-color:#eee'/></td>
                <td></td>
                <td></td>
            </tr>
        ";

        echo "
            <tr style='background-color:#eee'>
                <td colspan='5'><b>SUB TOTAL :</b></td>
                <td id='grand_total_price' style='text-align:right'></td>
                <td></td>
                <td><input type='text' id='additional_charges_subtotal' value=''  style='border:none;text-align:end;'></td>
                <td></td>
            </tr>
        ";

        echo "
                <tr style='background-color:#eee'>
                    <td colspan='6'><b>GRAND TOTAL :</b></td>
                    <td id='grand_total_price' style='text-align:right'></td>
                    <td><input type='text' id='gTotal' style='border:none;text-align:end;'></td>
                    <td></td>
                </tr>
            </table>
        ";
    ?>
</fieldset>
<fieldset>
    <table class="table" width='100%'>
        <tr>
            <td width="25%">
                <b>Purchases Requisition Description</b>
            </td>
            <td width="25%">
                <textarea placeholder="Enter Purchases Requisition Description" id="Purchases_Requisition_Description"></textarea>
            </td>
            <td width="25%">
                <form method="POST" enctype="multipart/form-data" id="myform">
                    <label>Reference Document</label>
                    <input type="file" multiple id="reference_document">
                </form>
            </td>
            <td width="40%">
                <input type="button" value="Create Purchase Requisition" onclick="upload_refference_document()" class="art-button-green" />
                <input type="button" value="Remove Selected Items" onclick="removeSelectedItems(<?=$_GET['Store_Order_ID']?>)" class="art-button-green" />
                <input type="button" value="REJECT" onclick="reject(<?=$_GET['Store_Order_ID']?>)" class="art-button-green" />
            </td>
        </tr>
    </table>
</fieldset>


<script>
    $("#select_all_checkbox").click(function(e) {
        $(".select_item_for_pr").not(this).prop('checked', this.checked);
    });

    function reject(Order_No){
        if(confirm('Your about to reject this Order')){
            $.post('procurement/procure.common.php',{
                Order_No:Order_No,
                request:'reject_order'
            },(response) => {
                if(response==100){
                    location.href = 'procurementstoreorderlist.php?StoreOrderList=StoreOrderListThisPage';
                }
            });
        }
    }

    function removeSelectedItems(Order_No){
        var selected_item_for_pr = [];
        var count_empty = 0;
        var count_selected = 0;
        var Employee_ID = '<?=$_SESSION['userinfo']['Employee_ID']?>';

        $(".select_item_for_pr:checked").each(function() {
            selected_item_for_pr.push(
                $(this).val());
            count_selected++;
        });

        if(selected_item_for_pr.length == 0){
            alert('Item not selected to be removed');
            exit();
        }else{
            var items = selected_item_for_pr.toString();
            
            if(confirm('Are you sure you want remove selected items ?')){
                $.post('procurement/procure.common.php',{
                    items:items,
                    Employee_ID:Employee_ID,
                    Order_No:Order_No,
                    request:'remove_item_from_order'
                },(response)=>{
                    if(response == 100){
                        location.reload();
                    }
                })
            }
        }
    }

    function upload_refference_document() {
        var Supplier_ID = $("#Supplier_ID").val();
        var Store_Need = $("#Store_Need").val();
        var Purchase_Type = $("#Purchase_Type").val();
        var Purchases_Requisition_Description = $("#Purchases_Requisition_Description").val();
        var reference_document = $("#reference_document").val();
        var Store_Order_ID = '<?= $Store_Order_ID ?>';

        if ($("#Supplier_ID").val() == "") {
            alert("SELECT SUPPLIER");
            return false;
        }
        if ($("#Purchase_Type").val() == "") {
            alert("SELECT PURCHASE TYPE");
            return false;
        }
        if ($("#Purchases_Requisition_Description").val() == "") {
            $("#Purchases_Requisition_Description").css("border", "2px solid red");
            return false;
        } else {
            $("#Purchases_Requisition_Description").css("border", "");
        }

        var fd = new FormData();
        var files = $('#reference_document')[0].files[0];
        fd.append('file', files);

        $.ajax({
            url: 'ajax_upload_refference_document.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response != 0) {
                    create_purchase_requisition(response)
                } else {
                    create_purchase_requisition(response)
                }
            },
        });
    }

    function calcalatute_additional_charges(Items_ID){
        var Item_Calc = parseInt($('#'+Items_ID).val());
        $("#calc_"+Items_ID).val(Item_Calc.toLocaleString('en'));
        
        var sum = 0;
        $('.charges_total').each(function(){
            sum += parseInt(this.value);
        });
        $("#additional_charges_subtotal").val(sum.toLocaleString('en'));
    }

    function calculate_discount() {
        var discount = $('#discount').val();
        
        if(discount > 100){
            alert('Discount should less than 100')
        }else{
            var grand_total = 0;
            var sub_total_txt = $('.sub_total_txt');
            for (var i = 0; i < sub_total_txt.length; i++) {
                grand_total = grand_total + parseFloat($(sub_total_txt[i]).val());
            }

            var sum = 0;
            $('.charges_total').each(function(){
                sum += parseInt(this.value);
            });

            var grand_total = grand_total - (discount / 100 * grand_total);
            var total = grand_total + sum;
            $('#gTotal').val(total.toLocaleString('en'));
        }
    }

    function create_purchase_requisition(reference_document) {
        var Supplier_ID = $("#Supplier_ID").val();
        var Store_Need = $("#Store_Need").val();
        var Purchase_Type = $("#Purchase_Type").val();
        var Purchases_Requisition_Description = $("#Purchases_Requisition_Description").val();
        var Store_Order_ID = '<?= $Store_Order_ID ?>';
        var local_foreign = $('#local_foreign').val();
        var Currency_ID = $('#Currency_ID').val();

        if (local_foreign == "none") {
            alert('Please select Requisition type');
            exit();
        }

        if (Currency_ID == "none") {
            alert('Please select Currency');
            exit();
        }

        if ($("#Supplier_ID").val() == "") {
            alert("SELECT SUPPLIER");
            return false;
        }

        if ($("#Purchases_Requisition_Description").val() == "") {
            $("#Purchases_Requisition_Description").css("border", "2px solid red");
            return false;
        } else {
            $("#Purchases_Requisition_Description").css("border", "");
        }

        var charges_total = $("input[class='charges_total']").map(function() { return $(this).val(); }).get();
        var discount = $('#discount').val();
        var charges_to_string = charges_total.toString();

        var selected_item_for_pr = [];
        var count_empty = 0;
        var count_selected = 0;

        $(".select_item_for_pr:checked").each(function() {
            selected_item_for_pr.push(
                $(this).val() + "item_id_buying_price_separator" +
                $("#buying_price_txt" + $(this).val()).val() + "item_id_buying_price_separator" +
                $("#item_quantity" + $(this).val()).val() + "item_id_buying_price_separator" +
                $("#item_container_qty" + $(this).val()).val() + "item_id_buying_price_separator" +
                $("#container_qty" + $(this).val()).val());
                
            if ($("#buying_price_txt" + $(this).val()).val() == "" && $("#buying_price_txt" + $(this).val()).val() <= 0) {
                $("#buying_price_txt" + $(this).val()).css("border", "2px solid red");
                count_empty++;
            }
            count_selected++;
        });

        if(selected_item_for_pr.length == 0){
            alert('Item not selected');
            exit();
        }

        if (count_empty > 0)
            if (count_selected <= 0) {
                alert("Select Items to create Purchase Requisition");
                $('#select_option').css("border", "2px solid red");
                $('#select_option').css("padding", "4px");
                exit();
            } else {
                $('#select_option').css("border", "");
            }
        if (confirm("Are You Sure You Want To Create Purchases Requisition for the selected items ?")) {
            $.ajax({
                type: 'POST',
                url: 'ajax_create_purchase_requisition_for_selected_items.php',
                data: {
                    Supplier_ID: Supplier_ID,
                    Store_Need: Store_Need,
                    Store_Order_ID: Store_Order_ID,
                    selected_item_for_pr: selected_item_for_pr,
                    local_foreign: local_foreign,
                    Purchases_Requisition_Description: Purchases_Requisition_Description,
                    Purchase_Type: Purchase_Type,
                    reference_document: reference_document,
                    discount:discount,
                    charges_to_string:charges_to_string,
                    Currency_ID:Currency_ID
                },
                success: function(data) {
                    console.log(data);
                    if (data == "success") {
                        alert("Purchase Requisition Created Successfully");
                        document.location = "procurementstoreorderlist.php?StoreOrderList=StoreOrderListThisPage";
                    }
                }
            });
        }
    }

    function calculate_item_price(buying_price, Container_Qty, Order_Item_ID) {
        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };
        var item_buying_price = 0;
        if (isNaN(parseFloat(buying_price.value))) {
            item_buying_price = 0;
        } else {
            item_buying_price = parseFloat(buying_price.value);
        }
        var sub_total = item_buying_price * parseFloat(Container_Qty);


        $("#" + Order_Item_ID).html(sub_total.format(2));
        $("#sub" + Order_Item_ID).val(sub_total);
        var grand_total = 0;
        var sub_total_txt = $('.sub_total_txt');
        for (var i = 0; i < sub_total_txt.length; i++) {
            grand_total = grand_total + parseFloat($(sub_total_txt[i]).val());
        }
        calculate_discount();
        $("#grand_total_price").html("<b>" + grand_total.format(2) + "</b>");
    }

    $(document).ready(function() {
        $('select').select2();
    });

    function calculate_exchange_rate(Store_Order_ID) {
        var requisition = $("#requisition_type").val();
        var curency_name = $("#curency_name").val();
        var exchange_rate_amount = $("#exchange_rate_amount").val();
        $.ajax({
            type: 'POST',
            url: 'ajax_store_oder.php',
            data: {
                update_curency: "update_curency",
                Store_Order_ID: Store_Order_ID,
                requisition: requisition,
                curency_name: curency_name,
                exchange_rate_amount: exchange_rate_amount
            },
            success: function(data) {
                console.log(data);
            }
        });
    }
</script>
<?php
include('./includes/footer.php');
?>