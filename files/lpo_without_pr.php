<?php
    include './includes/header.php';
    include 'procurement/procure.interface.php';
    $Procure = new ProcureInterface();
?>

<a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green">BACK</a>
<br><br>

<fieldset>
    <table width='100%'>
        <tr>
        <td width='15%' style="padding: 8px;font-weight:500">Document Number</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" readonly id="Document_Number" value="<?=$_GET['status']?>"></td>
            
            <td width='15%' style="padding: 8px;font-weight:500">Supplier</td>
            <td width='17%' style="padding: 3px;font-weight:500">
            <select id="Supplier_ID" style='width:100%;padding:6px'>
                    <option value="">Select Supplier</option>
                    <?php foreach($Procure->getAllSuppliers() as $suppliers) :  ?>
                        <option value="<?=$suppliers['Supplier_ID']?>"><?=ucwords($suppliers['Supplier_Name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td width='15%' style="padding: 8px;font-weight:500">Created Date</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" placeholder="Created Date" style="padding: 6px;" id="Created_Date"></td>
        </tr>

        <tr>
            <td width='15%' style="padding: 8px;font-weight:500">Store</td>
            <td width='17%' style="padding: 3px;font-weight:500">
            <select id="Sub_Department_ID" style='width:100%;padding:6px'>
                    <option value="">Select Supplier</option>
                    <?php foreach($Procure->getStoreSubDepartments() as $Sud_Department) :  ?>
                        <option value="<?=$Sud_Department['Sub_Department_ID']?>"><?=ucwords($Sud_Department['Sub_Department_Name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td width='15%' style="padding: 8px;font-weight:500">Account Number</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" placeholder="Account Number" style="padding: 6px;" id="Account_Number"></td>

            <td width='15%' style="padding: 8px;font-weight:500">Created By</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" readonly id="Employee_Name" value="<?=$_SESSION['userinfo']['Employee_Name']?>"></td>
        </tr>
        <tr>
            <td width='15%' style="padding: 8px;font-weight:500">LPO Description</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" id="LPO_Description" placeholder="LPO Description"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 500px;display:flex">
    <fieldset style="flex: 25%;">
        <table width='100%'>
            <tr>
                <td><input type="text" style="text-align: center;" placeholder="Item Name"></td>
            </tr>
        </table>
        <fieldset style="height: 430px;overflow-y:scroll">
            <table width='100%'>
                <tr style="background-color: #ddd;font-weight:500">
                    <td colspan="2" style="padding: 8px;">ITEM NAME</td>
                    <td style="padding: 8px;">UOM</td>
                </tr>
                <tbody>
                    <?php foreach($Procure->getServiceItems() as $Items) : ?>
                        <tr style="background-color: #fff;">
                            <td width='10%' style="padding: 5px;text-align:center"><input type="radio" name="item" onclick="addItem(<?=$Items['Item_ID']?>)"></td>
                            <td style="padding: 6px;"><?=$Items['Product_Name']?></td>
                            <td width='15%' style="padding: 6px;"><?=$Items['Unit_Of_Measure']?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </fieldset>
    </fieldset>

    <fieldset style="flex: 75%;">
        <fieldset style="height:420px;overflow-y:scroll">
            <table width='100%'>
                <tr style="background-color: #ddd;">
                    <td width='5%' style='padding:8px;font-weight:500'><center>S/N</center></td>
                    <td style='padding:8px;font-weight:500'>ITEM NAME</td>
                    <td width='12%' style='padding:8px;font-weight:500'><center>UNIT OF MEASURE</center></td>
                    <td width='12%' style='padding:8px;font-weight:500'><center>QUANTITY</center></td>
                    <td width='12%' style='padding:8px;font-weight:500;text-align:end'>BUYING PRICE</td>
                    <td width='12%' style='padding:8px;font-weight:500;text-align:end'>SUB TOTAL</td>
                    <td width='12%' style='padding:8px;font-weight:500'><center>ACTION</center></td>
                </tr>
                <tbody id="Item_List"></tbody>
            </table>
        </fieldset>
        <fieldset>
            <table width='100%'>
                <tr>
                    <td></td>
                    <td width='12%' style="text-align: start;padding:6px;font-weight:500">GRAND TOTAL</td>
                    <td width='12%' ><input type="text" style="padding:5px;text-align:end" id="Grand_Total"></td>
                    <td width='12%' style="text-align: center;"><a href="#" onclick="submitLPOWithoutPR()" class="art-button-green">SUBMIT</a></td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
    $(document).ready(() => {
        var Document_Number = $('#Document_Number').val();
        if(Document_Number != "new"){ loadAlreadyAddedItems(Document_Number); }
    })

    function addItem(Item_ID){
        var Document_Number = $('#Document_Number').val();
        var Supplier_ID = $('#Supplier_ID').val();
        var Account_Number = $('#Account_Number').val();
        var Employee_ID = '<?=$_SESSION['userinfo']['Employee_ID']?>';
        var LPO_Description = $('#LPO_Description').val();
        var Created_Date = $('#Created_Date').val();
        var Sub_Department_ID = $('#Sub_Department_ID').val();

        if(Supplier_ID == "" || Supplier_ID == "" || Account_Number == "" || Employee_ID == 0 || LPO_Description == "" || Created_Date == "" || Sub_Department_ID == ""){
            alert('Please fill the required details to continue');
            exit(1);
        }

        let documentObject = {
            Supplier_ID:Supplier_ID,
            Account_Number:Account_Number,
            Employee_ID:Employee_ID,
            LPO_Description:LPO_Description,
            Created_Date:Created_Date,
            Sub_Department_ID:Sub_Department_ID
        } 

        if(Document_Number == "new"){
            new_Document_no = createNewDocument(documentObject);
            $('#Document_Number').val(new_Document_no);
        }

        var Doc_Number = $('#Document_Number').val()
        let itemObject = { Doc_Number:Doc_Number,Item_ID:Item_ID }

        $.ajax({
            type: "POST",
            url: "procurement/procure.common.php",
            data: {itemObject:itemObject,add_item_lpo_without_pr:'add_item_lpo_without_pr'},
            cache:false,
            success: (response) => {
                loadAlreadyAddedItems(Doc_Number);
            }
        });
    }

    function submitLPOWithoutPR(){
        var Document_Number = $('#Document_Number').val();
        $.ajax({
            type: "POST",
            url: "procurement/procure.common.php",
            data: {Document_Number:Document_Number,submit_lpo_without_purchase_order:'submit_lpo_without_purchase_order'},
            success: (response) => {
                if(response == 100){
                    location.href = "purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage";
                }else{
                    alert(response);
                }
            }
        });
    }
    
    function calculate(Item_ID){
        var Document_Number = $('#Document_Number').val();
        var item_qty_ = $('#item_qty_'+Item_ID).val();
        var buying_price = $('#item_buying_price_'+Item_ID).val();
        var calc = (buying_price == "") ? item_qty_ * 1 : item_qty_ * buying_price ;
        var grand_total = 0;

        $('#item_sub_total_'+Item_ID).val(calc)

        var sub_total = $("input[class='sub_total']").map(function() { return $(this).val(); }).get();
        sub_total.forEach(element => {
            if(element != "") { grand_total = grand_total + parseInt(element) }
        });
        $('#Grand_Total').val((grand_total).toLocaleString('en'));

        let updateDetails = {
            item_qty_:item_qty_,
            buying_price:buying_price,
            Item_ID:Item_ID,
            Document_Number:Document_Number
        }

        $.ajax({
            type: "POST",
            url: "procurement/procure.common.php",
            data: {
                updateDetails:updateDetails,update_details:'update_details'
            },
            success: (response) => {
                console.log(response);
            }
        });

    }

    function loadAlreadyAddedItems(Document_Number){
        $.ajax({
            type: "GET",
            url: "procurement/procure.common.php",
            data: {Document_Number:Document_Number,load_item_in_lpo_without_pr:'load_item_in_lpo_without_pr'},
            success: function (response) {
                $('#Item_List').html(response);
            }
        });
    }

    function createNewDocument(documentObject){
        var response = $.ajax({
            type: "POST",
            url: "procurement/procure.common.php",
            data: {
                create_new_lpo_without_pr:'create_new_lpo_without_pr',documentObject:documentObject
            },
            async:false,
            cache:false,
            success: (response) => {
                return response;
            }
        });
        return response.responseText;
    }
</script>

<script>
    $('#Created_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#Created_Date').datetimepicker({value: '', step: 01});
</script>

<?php
    include './includes/footer.php';
?>