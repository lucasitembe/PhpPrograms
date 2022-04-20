<?php 
    include 'common/common.interface.php';
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $Consultation_ID = $_GET['Consultation_ID'];
    $Employee_ID = $_GET['Employee_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $Locations = new CommonInterface();
?>

<input type="hidden" id="Sponsor_ID" value="<?=$Sponsor_ID?>">
<input type="hidden" id="Consultation_ID" value="<?=$Consultation_ID?>">
<input type="hidden" id="Registration_ID" value="<?=$Registration_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">
<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?=$Patient_Payment_Item_List_ID?>">

<fieldset style="display: flex;border:1px solid #ccc !important">
    <fieldset style="flex: 30%;border:1px solid #ccc !important">
        <table width='100%'>
            <tr>
                <td><input type="text" id="procedure_name_" style="padding: 8px;" placeholder="Procedure Name" onkeyup="loadProcedures()"></td>
            </tr>
        </table>

        <fieldset style="flex: 30%;border:1px solid #ccc !important;height:420px;overflow-y:scroll">
            <table width='100%'>
                <tr><td colspan="2" style="font-weight: 500;padding:8px">PROCEDURE</td></tr>

                <tbody id="display_procedure">
                    
                </tbody>
            </table>
        </fieldset>
    </fieldset>

    <fieldset style="flex: 70%;border:1px solid #ccc !important">
        <fieldset style="border:1px solid #ccc !important;background-color:#fff">
            <table style="background-color: #fff;" width='100%'>
                <tr>
                    <td width='40%'><input type="text" id="procedure_name__" readonly style="padding: 8px;" placeholder="Procedure Name"></td>
                    <td width='15%'>
                        <select id="sub_department_id" style="width: 100%;padding:8px">
                            <?php foreach($Locations->getSubdepartmentLocation('procedure') as $location) : ?>
                            <option value="<?=$location['Sub_Department_ID']?>"><?=$location['Sub_Department_Name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" width='15%' style="padding: 8px;text-align:end" readonly id="item_price" placeholder="Price"><input type="hidden" id="item_id" value="0"></td>
                    <td><input type="text" width='15%' style="padding: 8px;" readonly id="item_status_" placeholder="Status"></td>
                    <td><input type="text" width='15%' style="padding: 8px;text-align:center" id="quantity" onkeyup="calculate_amount()" placeholder="Quantity"></td>
                    <td><input type="text" width='5%' style="padding: 8px;text-align:end" id="amount_" placeholder="Amount"></td>
                    <td style="padding: 0px;" width='5%'>
                        <input type="button" onclick="addItemToPatient()" class="art-button-green" value='ADD' />
                    </td>
                </tr>
		<tr><td colspan='7'><textarea placeholder='Procedure Comment'></textarea></td></tr>
            </table>
        </fieldset>
        
        <fieldset style="background-color: #fff;border:1px solid #ccc !important;">
            <table width='100%'>
                <tr style="background-color: #eee;">
                    <td style="font-weight:500;padding: 8px;text-align:center" width='5%'>S/N</td>
                    <td style="font-weight:500;padding: 8px;" >PRODUCT NAME</td>
                    <td style="font-weight:500;padding: 8px;text-align:center" width='10%'>QUANTITY</td>
                    <td style="font-weight:500;padding: 8px;text-align:end" width='10%'>CASH</td>
                    <td style="font-weight:500;padding: 8px;text-align:end" width='10%'>CREDIT</td>
                    <td style="font-weight:500;padding: 8px;text-align:center" width='10%'>ACTION</td>
                </tr>
                <tbody id="display_added_procedure"></tbody>
            </table>
        </fieldset>
    </fieldset>
</fieldset>

<script>
    $(document).ready(() => {
        loadProcedures();
        loadAlreadyAddedProcedure();
    });

    function loadAlreadyAddedProcedure(){
        var Consultation_ID = <?=$Consultation_ID?>;
        var Registration_ID = <?=$Registration_ID?>;

        $.ajax({
            type: "GET",
            url: "common/common.php",
            cache:false,
            data: {
                Consultation_ID:Consultation_ID,
                Registration_ID:Registration_ID,
                Check_In_Type:'Procedure',
                load_already_added_items:'load_already_added_items'
            },
            success: (response) => {
                $('#display_added_procedure').html(response);
            }
        });
    }

    function loadProcedures(){
        var Sponsor_ID = <?=$Sponsor_ID?>;
        var procedure_name_ = $('#procedure_name_').val();

        $.ajax({
            type: "GET",
            url: "common/common.php",
            data: {
                Sponsor_ID:Sponsor_ID,
                procedure_name_:procedure_name_,
                load_items:'load_items',
                consultation_type:'Procedure'
            },
            success: function (response) {
                $('#display_procedure').html(response);
            }
        });
    }

    function removeAddedProcedure(Payment_Item_Cache_List_ID){
        if(confirm("Are you sure you remove the Procedure")){
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
                        loadAlreadyAddedProcedure();
                    }else{
                        alert('Some please refresh the page and readd '+ response);
                    }
                }
            });
        }
    }

    function calculate_amount(){
        var quantity = $('#quantity').val();
        var item_price = $('#item_price').val();
        $('#amount_').val((quantity*item_price).toFixed(2));
    }   

    function getItemDetails(param){
        var Consultation_ID = <?=$Consultation_ID?>;
        var Sponsor_ID = <?=$Sponsor_ID?>;

        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                param:param,
                Sponsor_ID:Sponsor_ID,
                get_single_item_details:'get_single_item_details'
            },
            success: (response) => {
                if(response.length > 0){
                    var data = JSON.parse(response);
                    $('#procedure_name__').val(data[0]['Product_Name']);
                    $('#item_price').val(data[0]['Items_Price']);
                    $('#item_status_').val(data[0]['Status']);
                    $('#item_id').val(data[0]['Item_ID']);
                }
            }
        });
    }

    function addItemToPatient(){
        var Consultation_ID = <?=$Consultation_ID?>;
        var Sponsor_ID = <?=$Sponsor_ID?>;
        var item_id = $('#item_id').val();
        var Registration_ID = <?=$Registration_ID?>;
        var Employee_ID = <?=$Employee_ID?>;
        var quantity = $('#quantity').val();
        var Sub_Department_ID = $('#sub_department_id').val();

        if(item_id == "" || item_id == 0){
            alert('No Item Selected');
            exit();
        }

        if(quantity == ""){
            $('#quantity').css('border','1px solid red');
            exit(1);
        }
        $('#quantity').css('border','1px solid #ccc');

        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                Consultation_ID:Consultation_ID,
                Sponsor_ID:Sponsor_ID,
                Check_In_Type:'Procedure',
                Employee_ID:Employee_ID,
                item_id:item_id,
                Registration_ID:Registration_ID,
                Sub_Department_ID:Sub_Department_ID,
                quantity:quantity,
                add_item_to_patient:'add_item_to_patient'
            },
            cache:false,
            success: (response) => {
                if(response == 100){
                    $('#procedure_name__').val("");
                    $('#item_price').val("");
                    $('#item_status_').val("");
                    $('#item_id').val("");
                    $('#quantity').val("");
                    $('#amount_').val("");
                    loadAlreadyAddedProcedure();
                }else{
                    alert("Something went wrong contact administrator for support");
                }
            }
        });
    }
</script>
