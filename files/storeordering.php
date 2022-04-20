<?php 
    include './includes/header.php'; 
    include "store/store.interface.php";

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])) {
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
            if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Storage_Supervisor'])){
                    header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                }
            }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    $Employee_Name = isset($_SESSION['userinfo']['Employee_Name']) ? $_SESSION['userinfo']['Employee_Name'] : "Unknown Officer";
    $Employee_ID = isset($_SESSION['userinfo']['Employee_ID']) ? $_SESSION['userinfo']['Employee_ID'] : 0;
    $Branch_ID = isset($_SESSION['userinfo']['Branch_ID']) ? $_SESSION['userinfo']['Branch_ID'] : 0;
    $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $status = (isset($_GET['status'])) ? $_GET['status'] : ""; 

    $Store = new StoreInterface();
?>

<a href="previous_store_order.php" class="art-button-green">PREVIOUS STORE ORDERS</a>
<a href="storesubmittedorders.php?PendingOrders=PendingOrdersThisPage" class="art-button-green">APPROVE ORDERS</a>
<a href="storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage" class="art-button-green">BACK</a>

<br><br>

<fieldset>
    <legend style="font-weight: 500;"><?=strtoupper($_SESSION['Storage'])?></legend>
    <table width='100%' style="background-color: #fff;">
        <tr>
            <td width='15%' style="font-weight: 500;padding:8px">Order Number</td>
            <td width='17%' style="font-weight: 500;padding:2px"><input style="padding: 5px;" placeholder="Order Number" type="text" id="Order_Number" value='<?=$status?>'></td>
            <td width='15%' style="font-weight: 500;padding:8px">Order Date</td>
            <td width='17%' style="font-weight: 500;padding:2px"><input style="padding: 5px;" placeholder="Order Date" type="text" id="Order_Date"></td>
            <td width='15%' style="font-weight: 500;padding:8px">Prepared By</td>
            <td width='17%' style="font-weight: 500;padding:2px"><input style="padding: 5px;" type="text" id="" placeholder="Prepared By" value="<?=ucwords($Employee_Name)?>"></td>
        </tr>
        <tr>
            <td width='15%' style="font-weight: 500;padding:8px">Store Order</td>
            <td width='17%' style="font-weight: 500;padding:2px"><input type="text" style="padding: 5px;" value="<?=strtoupper($_SESSION['Storage'])?>"></td>
            <td width='15%' style="font-weight: 500;padding:8px">Order Description</td>
            <td width='17%' style="font-weight: 500;padding:2px" colspan="3"><input style="padding: 5px;" placeholder="Order Description" type="text" id="Order_Description"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 500px;display:flex">
    <fieldset style="flex: 25%;">
        <table width='100%'>
            <tr>
                <td>
                    <select onchange="searchItems(<?=$Current_Store_ID?>)" id="classification" style="padding: 5px;width:100%;text-align:center">
                        <option value="all">SELECT CLASSIFICATION</option>
                        <?php foreach($Store->getItemClassification() as $classification) : ?>
                            <option value="<?=$classification['Name']?>"><?=$classification['Description']?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="text" onkeyup="searchItems(<?=$Current_Store_ID?>)" id="product_name" placeholder="Search Item" style="text-align:center"></td>
            </tr>
        </table>

        <fieldset style="height: 400px;overflow-y:scroll;border:1px solid #b9afaf !important" id="display_data">
            <table style="background-color: #fff;">
                <tr style="background-color: #eee;">
                    <td style="padding: 8px;" colspan="2"><b>ITEM NAME</b></td>
                    <td style="padding: 8px;" width='20%'><b>UOM</b></td>
                    <td style="padding: 8px;" width='10%'><b>BALANCE</b></td>
                </tr>
                
                <tbody style="height: 100px;">
                    <input type="hidden" id="Sud_Department_ID" value="<?=$Current_Store_ID?>">
                    <?php foreach($Store->getItems($Current_Store_ID,NULL,'all') as $Item) : ?>
                        <tr>
                            <td style="padding: 5px;text-align:center"><input name="items" onclick="addItems(<?=$Current_Store_ID?>,<?=$Item['Item_ID']?>)" type="radio"></td>
                            <td style="padding: 8px;"><?=$Item['Product_Name']?></td>
                            <td style="padding: 8px;"><?=$Item['Unit_Of_Measure']?></td>
                            <td style="padding: 8px;"><?=$Item['Item_Balance']?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </fieldset>
    </fieldset>

    <fieldset style="flex: 75%;" >
        <fieldset style="height: 418px;overflow-y:scroll;border:1px solid #b9afaf !important">
        <table width='100%'>
            <tr style="background-color: #ddd;font-weight:500">
                <td width='5%' style="padding: 7px;text-align:center">S/N</td>
                <td style="padding: 7px;">ITEM NAME</td>
                <td width='9.5%' style="padding: 7px;text-align:center">UNIT OF MEASURE</td>
                <td width='9.5%' style="padding: 7px;text-align:center">UNIT</td>
                <td width='9.5%' style="padding: 7px;text-align:center">ITEM PER UNIT</td>
                <td width='9.5%' style="padding: 7px;text-align:center">QUANTITY</td>
                <td width='9.5%' style="padding: 7px;text-align:center">STORE BALANCE</td>
                <td width='9.5%' style="padding: 7px;text-align:center">REMARK</td>
                <td width='6%' style="padding: 7px;text-align:center">ACTION</td>
            </tr>

            <tbody id="display_added_items">
                <tr><td colspan="9" style="padding: 8px;background-color:#fff"><center>NO DATA FOUND</center></td></tr>
            </tbody>
        </table>
        </fieldset>
        <fieldset>
            <table width='100%'>
                <tr><td style="float: right;"><a href="#" onclick="submitOrder()" class="art-button-green">SUBMIT ORDER</a></td></tr>
            </table>
        </fieldset>
    </fieldset>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
    $(document).ready(() => {
        var Order_Number = $('#Order_Number').val();
        
        if(Order_Number != "new"){
            loadAddedItems(Order_Number);
        }
    });

    function submitOrder(){
        var total_qty = $("input[class='total_qty']").map(function() { return $(this).val(); }).get();
        var qty_values = Object.values(total_qty);
        var Order_Number = $('#Order_Number').val();

        if(qty_values.includes('0') == true){
            alert("Please fill the item with 0 Quantity");
        }else{
            $.post('store/store.common.php',{submitStoreOrder:'submitStoreOrder',Order_Number:Order_Number},(response) => {
                if(response == 100){
                    location.href = "storesubmittedorders.php?PendingOrders=PendingOrdersThisPage";
                }else{
                    alert(response);
                }
            });
        }   
    }

    function searchItems(Sub_Department_ID) { 
        var product_name = $('#product_name').val();
        var classification = $('#classification').val();

        $.ajax({
            type: "GET",
            url: "store/store.common.php",
            cache:false,
            data: {
                Sub_Department_ID:Sub_Department_ID,
                product_name:product_name,
                classification:classification,
                search_search:'search_search'
            },
            success: (response) => { $('#display_data').html(response); }
        });
    }

    function loadAddedItems(Order_Number){
        var Current_Store_ID = <?=$Current_Store_ID?>;
        $.ajax({
            type: "GET",
            url: "store/store.common.php",
            data: {load_added_items:'load_added_items',Order_Number:Order_Number,Current_Store_ID:Current_Store_ID},
            success: (response) => {
                $('#display_added_items').html(response);
            }
        });
    }

    function calculateQty(Item_ID){
        var Order_Number = $('#Order_Number').val();
        var item_per_unit_ = $('#item_per_unit_'+Item_ID).val();
        var item_unit_ = $('#item_unit_'+Item_ID).val();
        var total_unit = item_per_unit_ * item_unit_;
        $('#total_quantity_'+Item_ID).val(total_unit);

        let QuantityObject = {
            Order_Number:Order_Number,
            item_per_unit_:item_per_unit_,
            item_unit_:item_unit_,
            total_unit:total_unit,
            Item_ID:Item_ID
        }

        $.post('store/store.common.php',{QuantityObject:QuantityObject,update_order_qty:'update_order_qty'},(response) => {
            if(response == 200){ alert("Fail to update store quantity"); }
        })
    }

    function addItems(Current_Store_ID,Item_ID){
        var Order_Number = $('#Order_Number').val();
        var Order_Description = $('#Order_Description').val();
        var Order_Date = $('#Order_Date').val();
        var Employee_ID = <?=$Employee_ID?>;
        var Branch_ID = <?=$Branch_ID?>;

        if(Order_Description == ""){
            $('#Order_Description').css('border','1px solid red');
            exit();
        }
        $('#Order_Description').css('border','1px solid #ccc');

        if(Order_Date == ""){
            $('#Order_Date').css('border','1px solid red');
            exit();
        }
        $('#Order_Date').css('border','1px solid #ccc');

        if(Order_Number == "new"){
            let CreateNewOrderObject = {
                Employee_ID:Employee_ID,
                Order_Description:Order_Description,
                Branch_ID:Branch_ID,
                Order_Date:Order_Date,
                Current_Store_ID:Current_Store_ID
            }
            var Create_New_Store_Order = createNewStoreOrder(CreateNewOrderObject);
            $('#Order_Number').val(Create_New_Store_Order);
        }

        var New_Order_Number = $('#Order_Number').val();
        let addNewItemInOrder = { New_Order_Number:New_Order_Number,Item_ID:Item_ID }

        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {add_new_item_in_order:'add_new_item_in_order',addNewItemInOrder:addNewItemInOrder},
            success: (response) => {
                if(response == 100){
                    loadAddedItems(New_Order_Number);
                }else if(response == 300){
                    alert("Item is already Added");
                }else{
                    alert(response);
                }
            }
        });
    }

    function createNewStoreOrder(CreateNewOrderObject){
	console.log(CreateNewOrderObject);
        var response = $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {create_new_store_order:'create_new_store_order',CreateNewOrderObject:CreateNewOrderObject},
            async:false,
            success: (response) => {
		console.log(response);
                return response;
            }
        });
        return response.responseText;
    }

    function removeItem(Sub_Department_ID,Item_ID){
        var Order_Number = $('#Order_Number').val();
        let Remove_Object = { Sub_Department_ID:Sub_Department_ID,Item_ID:Item_ID,Order_Number:Order_Number }

        if(confirm("ARE YOU SURE TO REMOVE ITEM FROM THE ORDER")){
            $.post('store/store.common.php',{remove_item_from_order:'remove_item_from_order',Remove_Object:Remove_Object},(response) => {
                if(response==100){
                    loadAddedItems(Order_Number);
                }else{
                    alert(response);
                }
            })
        }
    }
</script>

<script>
    $('#Order_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#Order_Date').datetimepicker({value: '', step: 01});
</script>
<?php include './includes/footer.php'; ?>
