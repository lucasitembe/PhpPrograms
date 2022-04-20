<?php 
    include 'common/common.interface.php';
    $Interface = new CommonInterface();

    $Patient_Details_For_Transaction = $_GET['Patient_Details_For_Transaction'];
?>
<div style="display:flex;height:580px">
    <fieldset style="flex:30%;background-color:#fff;">
        <table width='100%'><tr><td colspan="2"><input style="padding: 7px;" type="text" id="Product_Name" onkeyup="searchItems()" placeholder="Product Name"></td></tr></table>
        <div id="Item_List" style="height: 525px;overflow-y:scroll">
            <table width='100%' id="Pharmacy_Item_List">
                <?php foreach($Interface->getItemWithPrice(13,"Pharmacy","","") as $Product ) : ?>
                    <tr><td style="padding: 5px;" width='10%'><center><input name="Items" onclick="selectMedication(<?=$Product['Item_ID']?>)" type="radio"></center></td> <td style="padding: 8px;" width='90%'><?=$Product['Product_Name']?></span></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
    </fieldset>

    <fieldset style="flex:70%">
        <div class="top_section">
            <table width='100%' style="background-color: #fff;">
                <tr>
                    <td width='25%'><input type="text" style="padding: 7px;" id="item_name__" readonly placeholder="Product Name"></td>
                    <td width='15%'>
                        <select id="Sub_Department_ID" onchange="getBalanceItemBalance()" style="width: 100%;padding:7px">
                            <option value="">Select Pharmacy</option>
                            <?php foreach($Interface->getSubdepartmentLocation('Pharmacy') as $location) : ?>
                                <option value="<?=$location['Sub_Department_ID']?>"><?=ucwords($location['Sub_Department_Name'])?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" style="padding: 7px;" id="Item_ID" readonly>
                    </td>
                    <td width='10%'><input type="text" id="item_status_" style="padding: 7px;" readonly placeholder="Status"></td>
                    <td width='10%'><input type="text" id="Item_Balance" style="padding: 7px;text-align:center" readonly placeholder="Balance"></td>
                    <td width='10%'><input type="text" id="item_price" onchange="calculate_amount()" style="padding: 7px;text-align:end" readonly placeholder="Price"></td>
                    <td width='10%'><input type="text" id="quantity" onkeyup="calculate_amount()" style="padding: 7px;text-align:center" placeholder="Quantity"></td>
                    <td width='10%'><input type="text" id="amount_" style="padding: 7px;text-align:end" readonly value="0" placeholder="Amount"></td>
                    <td width='5%' rowspan="2" style="text-align: center;padding:2px"><input type="submit" onclick="addPharmacyItemToPatient()" class="art-button-green" value='ADD'/></td>
                </tr>

                <tr style="background-color: #eee;">
                    <td colspan="7"><textarea style="padding: 5px;" id="Dosage" cols="0.5" rows="1" placeholder="Enter Dosage"></textarea></td>
                </tr>
            </table>
        </div>

        <div>
            <table width='100%'>
                <tr style="background-color: #eee;">
                    <td style="font-weight:500;padding: 8px;text-align:center" width='5%'>S/N</td>
                    <td style="font-weight:500;padding: 8px;" >PRODUCT NAME</td>
                    <td style="font-weight:500;padding: 8px;text-align:center" width='10%'>QUANTITY</td>
                    <td style="font-weight:500;padding: 8px;text-align:end" width='10%'>CASH</td>
                    <td style="font-weight:500;padding: 8px;text-align:end" width='10%'>CREDIT</td>
                    <td style="font-weight:500;padding: 8px;text-align:center" width='10%'>ACTION</td>
                </tr>

                <tbody id="Pharmacy_Items_List">
                    <tr style="background-color: #fff;">
                        <td style="padding: 8px;text-align:center" width='5%'>1</td>
                        <td style="padding: 8px;" >DicylomineHCl + Paracetamol+Chlorzoxazone 20mg/500mg</td>
                        <td style="padding: 8px;text-align:center" width='12%'>QUANTITY</td>
                        <td style="padding: 8px;text-align:end" width='12%'>CASH</td>
                        <td style="padding: 8px;text-align:end" width='12%'>CREDIT</td>
                        <td style="padding: 8px;text-align:center" width='12%'>ACTION</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
</div>

<script>
    $(document).ready(() => {
        loadAddedItems();
    });

    function loadAddedItems(){
        $.ajax({
            type: "GET",
            url: "common/common.php",
            cache:false,
            data: {
                Consultation_ID : "<?=$Patient_Details_For_Transaction['Consultation_ID']?>",
                Check_In_Type : "Pharmacy",
                Registration_ID : "<?=$Patient_Details_For_Transaction['Registration_ID']?>",
                load_already_added_items:"load_already_added_items"
            },
            success: (response) => {
                $('#Pharmacy_Items_List').html(response);
            }
        });
    }

    function removeAddedProcedure(Payment_Item_Cache_List_ID){
        if(confirm("Are you sure you remove the Items")){
            $.ajax({
                type: "POST",
                url: "common/common.php",
                cache:false,
                data: {
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    remove_the_selected_item_from_a_patient:'remove_the_selected_item_from_a_patient'
                },
                success: function (response) {
                    if(response == 100){
                        loadAddedItems();
                    }else{
                        alert('Some please refresh the page and readd '+ response);
                    }
                }
            });
        }
    }

    function searchItems(){
        var Product_Name = $('#Product_Name').val();
        $.get('common/common.php',{ search_pharmacy_items:"search_pharmacy_items",Product_Name:Product_Name },(response) => {
            $('#Pharmacy_Item_List').html(response);
        });
    }

    function getBalanceItemBalance(){
        var Sub_Department_ID = $('#Sub_Department_ID').val();
        var Item_ID = $('#Item_ID').val();

        $.get('common/common.php',{
            get_item_balance:'get_item_balance',
            Sub_Department_ID:Sub_Department_ID,
            Item_ID:Item_ID
        },(response) => { $('#Item_Balance').val(response); });
    }

    function calculate_amount(){
        var quantity = $('#quantity').val();
        var item_price = $('#item_price').val();
        $('#amount_').val((quantity*item_price).toFixed(2));
    }   

    function addPharmacyItemToPatient(){
        var Item_ID = $('#Item_ID').val();
        var Dosage = $('#Dosage').val();
        var Sub_Department_ID = $('#Sub_Department_ID').val();
        var Quantity = $('#quantity').val();

        let Patient_Details = {
            Consultation_ID : '<?=$Patient_Details_For_Transaction['Consultation_ID']?>',
            Registration_ID : '<?=$Patient_Details_For_Transaction['Registration_ID']?>',
            Employee_ID : '<?=$Patient_Details_For_Transaction['Employee_ID']?>',
            Sponsor_ID : '<?=$Patient_Details_For_Transaction['Sponsor_ID']?>',
            Item_ID:Item_ID,
            Dosage:Dosage,
            Sub_Department_ID:Sub_Department_ID,
            Quantity:Quantity,
            Check_In_Type:'Pharmacy'
        }

        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {Patient_Details:Patient_Details,add_pharmacy_item_for_inpatient:'add_pharmacy_item_for_inpatient'},
            cache: false,
            success: (response) => {
                if(response == 100){
                    loadAddedItems();
                }
            }
        });
    }

    function selectMedication(Item_ID) {  
        var Sub_Department_ID = $('#Sub_Department_ID').val();

        $.ajax({
            type: "POST",
            url: "common/common.php",
            cache:false,
            data: {
                param:Item_ID,
                Sub_Department_ID:Sub_Department_ID,
                Sponsor_ID:13,
                get_single_item_details:'get_single_item_details'
            },
            success: (response) => {
                if(response.length > 0){
                    var data = JSON.parse(response);
                    $('#item_name__').val(data[0]['Product_Name']);
                    $('#item_price').val(data[0]['Items_Price']);
                    $('#item_status_').val(data[0]['Status']);
                    $('#Item_ID').val(data[0]['Item_ID']);
                    $('#Item_Balance').val(data[0]['Item_Balance']);
                }
            }
        });
    }
</script>