<?php 
    include("./includes/header.php");
    include "store/store.interface.php";

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') { header("Location: ./index.php?InvalidPrivilege=yes"); }
    } else {
        @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Store = new StoreInterface();
    $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];

    $adjustment_number = isset($_GET['adjustment']) ? $_GET['adjustment'] : "New";
    $adjustment_description = isset($_GET['description']) ? $_GET['description'] : "";
    $adjustment_date_ = isset($_GET['date']) ? $_GET['date'] : "";
    $adjustment_reason_ = isset($_GET['reason']) ? $_GET['reason'] : "SELECT ADJUSTMENT REASON";

?>

<a href="previous_adjustment.php?StorageAndSupply=StorageAndSupplyThisPage" class="art-button-green">PREVIOUS ADJUSTMENT</a>
<a href="storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage" class="art-button-green">BACK</a>
<br><br>
<input type="hidden" id="Sub_Department_ID" value="<?=$Current_Store_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">
<input type="hidden" id="Branch_ID" value="<?=$Branch_ID?>">
<fieldset>
    <legend style="text-align: right;"><b>ADJUSTMENT ~ <?=strtoupper($Current_Store_Name)?></b></legend>
    <table width='100%' style="background-color: #fff;padding:5px">
        <tr>
            <td style="width:16.6%;padding:6px"><b>Adjustment Number</b></td> 
            <td style="width:16.6%"><input type="text" style="padding: 5px;" id="adjustment_number" value="<?=$adjustment_number?>"></td> 

            <td style="width:16.6%;padding:6px"><b>Adjustment Date</b></td> 
            <td style="width:16.6%"><input type="text" id="adjustment_date" placeholder="Adjustment Date" value="<?=$adjustment_date_?>" style="padding: 5px;"></td> 

            <td style="width:16.6%;padding:6px"><b>Adjustment Officer</b></td> 
            <td style="width:16.6%"><input type="text" style="padding: 5px;" value="<?=$Employee_Name?>"><input type="hidden" style="padding: 5px;" id="Employee_ID" value="<?=$Employee_ID?>"></td> 
        </tr>

        <tr>
            <td style="width:16.6%;padding:6px"><b>Adjustment Location</b></td> 
            <td style="width:16.6%"><input type="text" style="padding: 5px;" id='Sub_Department_ID' value="<?=ucfirst($Current_Store_Name)?>"></td> 

            <td style="width:16.6%;padding:6px"><b>Adjustment Description</b></td> 
            <td style="width:16.6%"><input type="text" placeholder="Description" id="adjustment_description" value="<?=$adjustment_description?>" style="padding: 5px;"></td> 

            <td style="width:16.6%;padding:6px"><b>Reason For Adjustment</b></td> 
            <td style="width:16.6%">
                <select id="adjustment_reason" style="width: 100%;padding:5px">
                    <option value=""><?=$adjustment_reason_?></option>
                    <?php foreach($Store->getAdjustmentReasons('enable') as $reason) : ?>
                        <option value="<?=$reason['id']?>"><?=$reason['name']?> ~ <?=$reason['nature']?></option>
                    <?php endforeach; ?>
                </select>
            </td> 
        </tr>
    </table>
</fieldset>

<fieldset style="display: flex;height:500px">
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

    <fieldset style="flex: 75%;border:1px solid #b9afaf !important">
        <fieldset style="height: 425px;overflow-y:scroll;border:1px solid #b9afaf !important">
            <table width='100%' style="background-color: #fff;">
                <thead>
                    <tr style="background-color: #eee;">
                        <td style="padding: 7px;text-align:center" width='5%'><b>SN</b></td>
                        <td style="padding: 7px;"><b>ITEM NAME</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>BATCH NO</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>EXPIRE DATE</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>STORE BALANCE</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>QTY ADJUSTED</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>BALANCE AFTER</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>REMARK</b></td>
                        <td style="padding: 7px;width:9%;text-align:center"><b>ACTION</b></td>
                    </tr>
                </thead>
                <tbody id="display_">
                    <tr>
                        <td style="text-align: center;padding:10px" colspan="9">ADD ITEMS TO ADJUST</td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <fieldset style="text-align: right;border:1px solid #b9afaf !important">
            <a href="#" onclick="submitAdjustment()" class="art-button-green" >SUBMIT</a>
        </fieldset>
    </fieldset>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(() => {
        var adjustment_number = $('#adjustment_number').val();
        var Sub_Department_ID = $('#Sub_Department_ID').val();
        
        if(adjustment_number != "New"){
            document.getElementById("adjustment_reason").setAttribute("disabled", "disabled");
            loadItems(Sub_Department_ID);
        }
    });

    function saveRemarks(Item_ID,Adjusted_ID){
        var item_remark = $('#remark'+Item_ID).val();
        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                item_remark:item_remark,
                Adjusted_ID:Adjusted_ID,
                update_adjustment_remark:'update_adjustment_remark'
            },
            success: (response) => { console.log(response); }
        });
    }

    function batchSave(Item_ID,Adjusted_ID) {  
        var batch = $('#batch'+Item_ID).val();
        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                batch:batch,
                Adjusted_ID:Adjusted_ID,
                batch_auto_save:'batch_auto_save'
            },
            success: (response) => { console.log(response); }
        });
    }

    function saveExpireDates(Item_ID,Adjusted_ID) {  
        var expire_date = $('#expire_date'+Item_ID).val();
        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                expire_date:expire_date,
                Adjusted_ID:Adjusted_ID,
                expire_date_auto_save:'expire_date_auto_save'
            },
            success: (response) => { console.log(response); }
        });
    }

    function addItems(Sub_Department_ID,Item_ID){
        var adjustment_number = $('#adjustment_number').val();
        var Employee_ID = $('#Employee_ID').val();
        var adjustment_date = $('#adjustment_date').val();
        var adjustment_description = $('#adjustment_description').val();
        var adjustment_reason = $('#adjustment_reason').val();

        if(adjustment_date == ""){
            $('#adjustment_date').css('border','1px solid red');
            exit();
        }
        $('#adjustment_date').css('border','1px solid #ccc');

        if(adjustment_description == ""){
            $('#adjustment_description').css('border','1px solid red');
            exit();
        }
        $('#adjustment_description').css('border','1px solid #ccc');

        if(adjustment_reason == ""){
            $('#adjustment_reason').css('border','1px solid red');
            $('#adjustment_reason').css('padding','5px');
            exit();
        }
        $('#adjustment_reason').css('border','1px solid #ccc');
        $('#adjustment_reason').css('padding','5px');


        if(adjustment_number == "New"){
            var create_new_adjustment_document = createNewAdjustmentDocument(Employee_ID,adjustment_date,Sub_Department_ID,adjustment_reason,adjustment_description);
            $('#adjustment_number').val(create_new_adjustment_document);
        }

        var adjustment_number = $('#adjustment_number').val();

        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            cache:false,
            data: {
                Sub_Department_ID:Sub_Department_ID,
                Item_ID:Item_ID,
                adjustment_number:adjustment_number,
                add_new_item_to_adjust:'add_new_item_to_adjust'
            },
            success: (response) => {
                if(response==300){
                    alert('The selected item is already added');
                }else{
                    loadItems(Sub_Department_ID);
                }
            }
        });
    }


    function createNewAdjustmentDocument(Employee_ID,adjustment_date,Sub_Department_ID,adjustment_reason,adjustment_description) {  
        var Branch_ID = $('#Branch_ID').val();
        document.getElementById("adjustment_reason").setAttribute("disabled", "disabled");
        var response = $.ajax({
            type: "POST",
            url: "store/store.common.php",
            async:false,
            data:{
                Sub_Department_ID:Sub_Department_ID,
                Employee_ID:Employee_ID,
                adjustment_date:adjustment_date,
                adjustment_reason:adjustment_reason,
                adjustment_description:adjustment_description,
                Branch_ID:Branch_ID,
                create_new_adjustment:'create_new_adjustment'
            },
            success: (response) => {
                return response;
            }
        });
        return response.responseText;
    }

    function loadItems(Sub_Department_ID){
        var adjustment_number = $('#adjustment_number').val();
        var e = document.getElementById("adjustment_reason");
        var value=e.options[e.selectedIndex].value;
        var text=e.options[e.selectedIndex].text;
        var adjust = (text.search("adjustment_minus") > 0) ? 'minus' : 'plus';

        $.ajax({
            type: "GET",
            url: "store/store.common.php",
            data: {
                adjustment_number:adjustment_number,
                Sub_Department_ID:Sub_Department_ID,
                adjust:adjust,
                load_adjusted_items:'load_adjusted_items'
            },
            success: (response) => { $('#display_').html(response); }
        });
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

    function removeItem(Sub_Department_ID,Item_ID){
        var adjustment_number = $('#adjustment_number').val();

        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                adjustment_number:adjustment_number,
                Sub_Department_ID:Sub_Department_ID,
                Item_ID:Item_ID,
                remove_item_from_adjustment:'remove_item_from_adjustment'
            },
            success: (response) => {
                if(response){
                    loadItems(Sub_Department_ID);
                }else{
                    alert(response);
                }
            }
        });
    }

    function submitAdjustment() {  
        var adjustment_number = $('#adjustment_number').val();
        var qty_adjusted = $("input[class='qty_adjusted']").map(function() { return $(this).val(); }).get();
        var items_id = $("input[id='items_id']").map(function() { return $(this).val(); }).get();
        var e = document.getElementById("adjustment_reason");
        var value=e.options[e.selectedIndex].value;
        var text=e.options[e.selectedIndex].text;
        var adjust = (text.search("adjustment_minus") > 0) ? 'minus' : 'plus';
        var Employee_ID = $('#Employee_ID').val();
        var Sub_Department_ID = $('#Sub_Department_ID').val();

        var Item_IDs = items_id.toString();
        var Item_ID_Qty = qty_adjusted.toString();
    
        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                Item_IDs:Item_IDs,
                Item_ID_Qty:Item_ID_Qty,
                adjustment_number:adjustment_number,
                Employee_ID:Employee_ID,
                Sub_Department_ID:Sub_Department_ID,
                adjust:adjust,
                submit_adjustment:'submit_adjustment'
            },
            cache:false,
            success: (response) => {
                if(response == 100){
                    alert('Adjustment Done Successful');
                    location.href = "storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage&";
                }else{
                    alert(response);
                }
            }
        });
    }

    function adjustQuantity(Item_ID,Adjusted_ID){
        var qty_adjusted = parseInt($('#qty_adjusted'+Item_ID).val());
        var balance = parseInt($('#balance'+Item_ID).val());
        var e = document.getElementById("adjustment_reason");
        var value=e.options[e.selectedIndex].value;
        var text=e.options[e.selectedIndex].text;
        var adjust = (text.search("adjustment_minus") > 0) ? balance-qty_adjusted : balance+qty_adjusted;

       // if(adjust <= 0){
            //$('#balance_after'+Item_ID).css('border','1px solid red');
           // $('#balance_after'+Item_ID).val(0);
           // $('#qty_adjusted'+Item_ID).val(0);
          //  exit();
        //}

        $('#balance_after'+Item_ID).css('border','1px solid #ccc');
        $('#balance_after'+Item_ID).val(adjust);
        $.post('store/store.common.php',{autosave_adjust:'autosave_adjust',qty_adjusted:qty_adjusted,Adjusted_ID:Adjusted_ID},(response) => { console.log(response) });
    }
</script>

<script>
    $('#adjustment_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#adjustment_date').datetimepicker({value: '', step: 01});
</script>

<?= include("./includes/footer.php"); ?>
