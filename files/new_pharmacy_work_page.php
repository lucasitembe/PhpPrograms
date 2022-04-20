<?php
    include './includes/connection.php';
    include("./includes/header.php");
    include("./button_configuration.php");

    if (!isset($_SESSION['userinfo'])) { @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Pharmacy'])) {
            if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                if (!isset($_SESSION['Pharmacy_Supervisor'])) { header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes"); }
            }
        } else { header("Location: ./index.php?InvalidPrivilege=yes"); }
    } else {
        @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes");
    }

    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();
    $Employee_Name = (isset($_SESSION['userinfo']['Employee_Name'])) ? $_SESSION['userinfo']['Employee_Name'] : 'Unknown Employee';
    $Check_In_Type = (isset($_GET['Check_In_Type'])) ? $_GET['Check_In_Type'] : '';
    $Payment_Cache_ID = (isset($_GET['Payment_Cache_ID'])) ? $_GET['Payment_Cache_ID'] : '';
    $Employee_ID = isset($_SESSION['userinfo']['Employee_ID']) ? $_SESSION['Pharmacy_ID'] : '';
    $Sub_Department_ID = isset($_SESSION['Pharmacy_ID']) ? $_SESSION['Pharmacy_ID'] : 0;
?>

<input type="button" value="MEDICATION HISTORY" style="font-family:arial;" class='art-button' onclick="viewPatientMedicationHistory()">
<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' style="font-family:arial;" class='art-button'>PHARMACY WORKS</a>
<a href='pharmacyworkspage_new.php' style="font-family:arial;" class='art-button'>BACK</a>

<!-- colors description -->
<span style='background-color:#2e5f91;color:#fff;padding:3px;border-radius:2px;float:right;'>Partially Dispensed</span>
<span style='background-color:#1f6c63;color:#fff;padding:3px;border-radius:2px;float:right;margin-right:5px'>Sent To Cashier</span>
<span style='background-color:#9f3833;color:#fff;padding:3px;border-radius:2px;float:right;margin-right:5px'>Not Dispensed</span>
<span style='background-color:#041d20;color:#fff;padding:3px;border-radius:2px;float:right;margin-right:5px'>Free Item</span>
<span style='background-color:#fbe3e0;color:#041d20;padding:3px;border-radius:2px;float:right;margin-right:5px'>Ordered Today</span>
<!-- colors description -->

<?php
    if (isset($_GET['Payment_Cache_ID'])) {
        $button_configuration = $Interface->pharmacyConfigurations();
        $change_medication_location = ($button_configuration[0]['Change_Medication_Location'] == 'no') ? 'display:none' : '';
        $add_medication = ($button_configuration[0]['Enable_Add_More_Medication'] == 'no') ? 'display:none' : '';
        $display_send_to_cashier_btn = ($button_configuration[0]['Display_Send_To_Cashier_Button'] == 'no') ? 'display:none' : '';
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $patientDetails = $Interface->getPatientDetailsPharmacy($_GET['Payment_Cache_ID']);
        die(print_r($patientDetails));
        $sponsor_config = ($patientDetails[0]['allow_pharmacy_sponsor'] == 'yes') ? "" : "display:none";
    }
    $Registration_ID = $patientDetails[0]['Registration_ID'];
    $Billing_Type = $patientDetails[0]['Billing_Type'];
    $Sponsor_ID = $patientDetails[0]['Sponsor_ID'];
    $payment_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' LIMIT 1"))['payment_method'] or die(mysqli_error($conn));
?>

<style>
    table,tr,td {
        border-collapse: collapse !important;
        border: 1px solid #ccc !important;
    }

    tr:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }

    .medication_table thead td {
        border: 1px solid #ccc !important;
        padding: 10px;
    }
</style>

<fieldset>
    <legend style='background-color:#0079AE;color:white;padding:8px;' align=right><b> <span style="color: yellow;"><?=strtoupper($_SESSION['Pharmacy']);?> </span></b></legend>
    <center>
        <table width="100%">
            <tr>
                <td width='10%' style='text-align: right;'>Customer Name</td>
                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?= $patientDetails[0]["Patient_Name"]; ?>'></td>
                <td width='12%' style='text-align: right;'>Card Expiry Date</td>
                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?= $patientDetails[0]["Member_Card_Expire_Date"]; ?>'></td>
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?= $patientDetails[0]['Gender'] ?>'></td>
                <td style='text-align: right;'>Receipt Number</td>
                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?=$getReceipt[0]['Patient_Payment_ID']?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Billing Type</td>
                <td>
                    <select name='Billing_Type' id='Billing_Type' style='width:100%;padding:5px' onclick="changeBillType()">
                        <option selected='selected'><?= $patientDetails[0]['Billing_Type'] ?></option>
                        <?= $Billing_Type === 'Inpatient Credit' ? '<option>Inpatient Cash</option>' : ''; ?>
                    </select>
                </td>
                <td style='text-align: right;'>Claim Form Number</td>
                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='<?php echo $Claim_Form_Number; ?>'></td>
                <td style='text-align: right;'>Occupation</td>
                <td><input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?= $patientDetails[0]['Occupation'] ?>'></td>
                <td style='text-align: right;'>Receipt Date & Time</td>
                <td><input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?= $patientDetails[0]['Payment_Date_And_Time'] ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Type Of Check In</td>
                <td><input type="text" name="Type_Of_Check_In" readonly id="Type_Of_Check_In" value="Pharmacy"></td>
                <td style='text-align: right;'>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age' disabled='disabled' value='<?= $Interface->getCurrentPatientAge($patientDetails[0]['Date_Of_Birth']); ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?= $patientDetails[0]['Registration_Date'] ?>'></td>
                <td style='text-align: right;'>Folio Number</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Customer Direction</td>
                <td><input type="text" id='direction' name='direction' readonly value="Others"></td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?= $patientDetails[0]['Guarantor_Name']; ?>'></td>
                <td style='text-align: right;'>Phone Number</td>
                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?= $patientDetails[0]["Phone_Number"] ?>'></td>
                <td style='text-align: right;'>Prepared By</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?= $patientDetails[0]['Employee_Name'] ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Consultant</td>
                <td><input type="text" name='Consultant' id='Consultant' value="<?= $patientDetails[0]['Employee_Name'] ?>" readonly></td>
                <td style='text-align: right;'>Registration Number</td>
                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?= $patientDetails[0]['Registration_ID'] ?>'></td>
                <td style='text-align: right;'>Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?= $patientDetails[0]['Member_Number'] ?>'></td>
                <td style='text-align: right;'>Supervised By</td>
                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?=$_SESSION['Pharmacy_Supervisor']['Employee_Name']; ?>'></td>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset>
    <?php if(strtolower($payment_type) == 'credit') {  ?>
        <div>
            <div>
                <input class="art-button " style="font-family:arial;<?=$sponsor_config?>" type="button" onclick="openItemDialog()" value="ADD MORE ITEM"> 
                <input class="art-button " style="font-family:arial;<?=$addMoreItemsConfigurations?>" type="button" onclick="viewPatientPrescription()" value="PRESCRIPTION">
                <input class="art-button " style="font-family:arial;" type="button" onclick="viewsSummaryPatientFile()" value="SUMMARY PATIENT FILE">
                <input onclick="make_bill_and_dispense()" class="art-button pull-right" id="bill_and_dispense_btn" style="font-family:arial;font-weight:100" type="button" value="BILL AND DISPENSE MEDICATION">                
            </div>
        </div>
    <?php } else if(strtolower($payment_type) == "cash") {  ?>
        <?php if($Billing_Type == "Inpatient Cash" || $Billing_Type == "Inpatient") { ?>
            <input class="art-button pull-right" style="font-family:arial;" type="button" onclick="go_mobile_card_transaction()" value="GO TO MOBILE/CARD PAYMENT">
            <input onclick="cash_inpatient_bill()" class="art-button pull-right" style="font-family:arial;" type="button" value="BILL AND DISPENSE MEDICATION">
            <input onclick="send_patient_to_cashier()" style="font-family:arial;<?=$display_send_to_cashier_btn ?>" class="art-button pull-right" type="button" value="SEND TO CASHIER">
            <input onclick="dispense_item()" style="font-family:arial;" class="art-button pull-right" type="button" value="DISPENSE MEDICATION">
            <input class="art-button pull-left" style="font-family:arial;<?=$sponsor_config?>" type="button" onclick="openItemDialog()" value="ADD MORE ITEM">
            <input class="art-button " style="font-family:arial;<?=$addMoreItemsConfigurations?>" type="button" onclick="viewPatientPrescription()" value="PRESCRIPTION">-
            <input class="art-button pull-right" style="font-family:arial;<?=$displayMakePaymentbtn?>" onclick="createManualPayment()" type="button" value="MAKE PAYMENT AND DISPENSE">
        <?php } else { ?>
            <input onclick="send_patient_to_cashier()" style="font-family:arial;<?=$display_send_to_cashier_btn?>" class="art-button pull-right" type="button" value="SEND TO CASHIER">
            <input onclick="dispense_item()" style="font-family:arial;" class="art-button pull-right" type="button" value="DISPENSE MEDICATION">
            <input class="art-button pull-right" style="font-family:arial;<?=$goToMobilePayment?>" type="button" onclick="go_mobile_card_transaction()" value="GO TO MOBILE/CARD PAYMENT">
            <input class="art-button pull-left" style="font-family:arial;<?=$sponsor_config?>" type="button" onclick="openItemDialog()" value="ADD MORE ITEM">
            <input class="art-button pull-left" style="font-family:arial;<?=$addMoreItemsConfigurations?>" type="button" onclick="viewPatientPrescription()" value="PRESCRIPTION">
            <input class="art-button pull-left" style="font-family:arial;" type="button" onclick="viewsSummaryPatientFile()" value="SUMMARY PATIENT FILE">
            <input class="art-button pull-right" style="font-family:arial;<?=$displayMakePaymentbtn?>" onclick="createManualPayment()" type="button" value="MAKE PAYMENT AND DISPENSE">
            <div id="displayPrintReceipt"></div>
        <?php } ?>
    <?php } ?>
</fieldset>

<fieldset style="height: 350px !important;overflow-y:scroll">
    <table class="medication_table" style="background-color: white;overflow-x:scroll;width:100%">
        <thead style="background-color: #ddd;font-size:13px">
            <tr>
                <td width='1%'><center>SN</center></td>
                <td width='15%;text-align:left'>ITEM NAME</td>
                <td width='7%'><center>DOSE</center></td>
                <td width='6%'><center>DISEASE CODE</center></td>
                <td width='5.5%'><center>DOSE QTY</center></td>
                <td width='6.5%'><center>DISPENSE QTY</center></td>
                <td width='5.5%'><center>DURATION</center></td>
                <td width='6.5%'><center>PREV DISPENSE</center></td>
                <td width='5.5%'><center>REMAIN QTY</center></td>
                <td width='6%'><center>BALANCE</center></td>
                <td width='5.5%'><center>PRICE</center></td>
                <td width='5.5%'><center>SUBTOTAL</center></td>
                <td width='5%'><center><input type="checkbox" onclick="check_uncheck_checkbox(this.checked);"></center></td>
                <td width='4.5%'><center>ACTION</center></td>
                <td width='5.5%'><center>DATE SENT</center></td>
                <td width='5.5%'><center>Order By</center></td>
            </tr>
        </thead>
        <tbody style="height: auto !important;" id="getItemsFromFilter"></tbody>
    </table>
</fieldset>

<fieldset>
    <table width='100%'>
        <tr>
            <td><a href="#" class='art-button pull-right' style="font-family: arial;" onclick="viewRemovedItems(<?=$_GET['Payment_Cache_ID']?>,<?=$_GET['Registration_ID']?>)">REMOVED ITEMS</a></td>
        </tr>
    </table>
</fieldset>

    <div id="Add_New_Item" style='background-color:#eee'><span id='Add_New_Items_Area'></span></div>
    <div id='pharmacy_medication_substitute'></div>

    <!-- section space -->
    <div id="Previous_History"></div>
    <div id="removed_item_section"></div>
    <div id="change_department"></div>
    <div id="viewPatientPrescription"></div>
    <div id="viewPatientMedicationHistory"></div>
    <div id="viewPatientFileSummary"></div>
    <div id="substituteMedication"></div>
    <div id="removal_space"></div>
    <div id="duplicate_space"></div>
    <div id="item_approval"></div>
    <!-- section space -->

    <script>
        $(document).ready(() => {
            getItemsFromPharmacy();
        });

        function getItemsFromPharmacy() {
            var Registration_ID = '<?= $_GET['Registration_ID'] ?>';
            var Billing_Type = '<?= $Billing_Type ?>';
            var pharmacist_to_approve = '<?=$patientDetails[0]['allow_dispense_control']?>';
            var Guarantor_Name = $('#Guarantor_Name').val();

            $('#getItemsFromFilter').html("<tr><td style='text-align:center;colspan='16'><center><img src='./images/ajax-loader_1.gif' style='text-align:center'/></center></td></tr>");

            $.ajax({
                type: "GET",
                url: "load_pharmacy_item.php",
                cache: false,
                data: {
                    Registration_ID: Registration_ID,
                    Billing_Type: Billing_Type,
                    Payment_Cache_ID: '<?= $_GET['Payment_Cache_ID'] ?>',
                    Sub_Department_id: '<?= $Sub_Department_ID ?>',
                    Transaction_Type: '<?= $_GET['Transaction_Type'] ?>',
                    start_date: '<?= $_GET['Start_Date'] ?>',
                    balance_new: '<?= $_SESSION['Pharmacy_ID'] ?>',
                    sponsor_config:'<?=$sponsor_config?>',
                    auto_item_update_api : <?=$patientDetails[0]['auto_item_update_api']?>,
                    pharmacist_to_approve : pharmacist_to_approve,
                    Sponsor_ID: <?=$Sponsor_ID?>
                },
                cache: false,
                success: (response) => {
                    $('#getItemsFromFilter').html(response);
                }
            });
        }
    </script>

    <script>
        function item_approval(Item_ID,Registration_ID,Payment_Item_Cache_List_ID){
            $.get('pharmacy-repo/common.php',{
                Item_ID:Item_ID,
                Registration_ID:Registration_ID,
                Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                item_approval:'item_approval'
            },(response)=>{
                $("#item_approval").dialog({
                    autoOpen: false,
                    width: '30%',
                    height: 250,
                    title: 'eHMS ITEM APPROVAL',
                    modal: true
                });
                $("#item_approval").html(response);
                $("#item_approval").dialog("open");
            });
        }
        
        function verify_service(Item_ID,Registration_ID,Payment_Item_Cache_List_ID){
            var treatment_authorization_no = $('#treatment_authorization_no'+Payment_Item_Cache_List_ID).val();
            if(treatment_authorization_no == ""){
                document.getElementById('treatment_authorization_no').style.border = "1px solid red";
                exit();
            }

            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).value = 'PLEASE WAIT';
            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).disabled = true;
            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).style.cursor = "progress";

            $.ajax({
                type: "POST",
                url: "ajax_service_verication.php",
                data: {
                    Item_ID:Item_ID,
                    Registration_ID:Registration_ID,
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    treatment_authorization_no:treatment_authorization_no
                },
                success: (response) => {
                    if(response['code'] == 200){
                        var store_details = store_verification_details(Item_ID,Registration_ID,Payment_Item_Cache_List_ID,treatment_authorization_no);
                        if(store_details == 200){
                            alert("Details updated successful");
                            $("#item_approval").dialog("close");
                            getItemsFromPharmacy();
                        }else{
                            alert("Details not updated : " + store_details);
                            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).value = 'NHIF-VERIFY NO';
                            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).disabled = false;
                            document.getElementById('authorize_'+Payment_Item_Cache_List_ID).style.cursor = "cursor";
                        }
                    }else if(response['code'] == 500){
                        alert("Please check your internet connection and try again");
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).value = 'NHIF-VERIFY NO';
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).disabled = false;
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).style.cursor = "cursor";
                    }else if(response['code'] == 'INVALID'){
                        alert("VERIFICATION NUMBER IS INVALID");
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).value = 'NHIF-VERIFY NO';
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).disabled = false;
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).style.cursor = "cursor";
                    }else{
                        alert('Please check your internet connection and try again');
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).value = 'NHIF-VERIFY NO';
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).disabled = false;
                        document.getElementById('authorize_'+Payment_Item_Cache_List_ID).style.cursor = "cursor";
                    }
                }
            });
        }

        function store_verification_details(Item_ID,Registration_ID,Payment_Item_Cache_List_ID,treatment_authorization_no){
            var object = $.ajax({
                type: "POST",
                url: "pharmacy-repo/common.php",
                async: false,
                cache:false,
                data:{
                    Item_ID : Item_ID,
                    Registration_ID : Registration_ID,
                    Payment_Item_Cache_List_ID : Payment_Item_Cache_List_ID,
                    treatment_authorization_no : treatment_authorization_no,
                    Employee_ID : '<?=$Employee_ID?>',
                    store_verification_data : 'store_verification_data'
                },
                success: (response) => {
                    return response;
                }
            });
            return object.responseText;
        }
    </script>

    <script>
        function check_uncheck_checkbox(isChecked) {
            var date_values = $("input[class='total']").map(function() { return $(this).val(); }).get();
            var items_id = $("input[id='items_id']").map(function() { return $(this).val(); }).get();

            var Payment_Cache_ID = '<?= $_GET['Payment_Cache_ID'] ?>';
            var Registration_ID = '<?= $_GET['Registration_ID'] ?>';
            var summation = 0;
            var getTotal = 0;

            var getDispensedQuantity = $("input[class='dispensed']").map(function() { return $(this).val(); }).get();
            var prices = $("input[class='prices']").map(function() { return $(this).val(); }).get();
            var sumTotal = $("input[id='sumTtotal']").map(function() { return $(this).val(); }).get();

            for (var i = 0; i < getDispensedQuantity.length; i++) {
                getTotal += getDispensedQuantity[i] * prices[i];
                summation = getDispensedQuantity[i] * prices[i];
                document.getElementById('subtotal' + sumTotal[i]).value = summation;
            }

            var dose = $("input[class='dose']").map(function() { return $(this).val(); }).get();
            var checkDose = dose.includes('0');
            var checkDosage = dose.includes('');
            var items_id_string = items_id.toString();

            var sum = 0;
            for (var el in date_values) {
                if (date_values.hasOwnProperty(el)) {
                    sum += parseFloat(date_values[el]);
                }
            }

            if (isChecked) {
                var medication_response = checkMedication(Registration_ID, Payment_Cache_ID, items_id_string);

                if (checkDose === true) {
                    alert('Please Fill in Dosage Quantity to Continue..!');
                    $('input[type="checkbox"]').each(function() {
                        this.checked = false;
                    });
                    exit();
                }

                if (checkDosage === true) {
                    alert('Please Fill in Dosage Quantity to Continue..!');
                    $('input[type="checkbox"]').each(function() {
                        this.checked = false;
                    });
                    exit();
                }

                if (medication_response.length > 0) {
                    if (confirm(medication_response)) {
                        $('input[type="checkbox"]').each(function() {
                            this.checked = true;
                        });
                        alert("yes");
                        document.getElementById('total_amount').value = sum.toLocaleString('en');
                    } else {
                        $('input[type="checkbox"]').each(function() {
                            this.checked = false;
                        });
                        for (var i = 0; i < getDispensedQuantity.length; i++) {
                            getTotal += getDispensedQuantity[i] * prices[i];
                            summation = getDispensedQuantity[i] * prices[i];
                            document.getElementById('subtotal' + sumTtotal[i]).value = 0;
                        }
                        document.getElementById('total_amount').value = 0;
                    }
                } else {
                    $('input[type="checkbox"]').each(function() {
                        this.checked = true;
                    });
                    document.getElementById('total_amount').value = getTotal.toLocaleString('en');
                }
            } else {
                $('input[type="checkbox"]').each(function() {
                    this.checked = false;
                });
                for (var i = 0; i < getDispensedQuantity.length; i++) {
                    getTotal += getDispensedQuantity[i] * prices[i];
                    summation = getDispensedQuantity[i] * prices[i];
                    document.getElementById('subtotal' + sumTtotal[i]).value = 0;
                }
                document.getElementById('total_amount').value = 0;
            }
        }

        function checkMedication(Registration_ID, Payment_Cache_ID, Items_Ids_String) {
            var check_dose_multipole = "check_dose_multipole";

            var object = $.ajax({
                type: "GET",
                url: "phamacy_handle.php",
                async: false,
                data: {
                    Registration_ID: Registration_ID,
                    Payment_Cache_ID: Payment_Cache_ID,
                    check_dose_multipole: check_dose_multipole,
                    Items_Ids_String: Items_Ids_String
                },
                cache: false,
                success: (response) => {
                    return response;
                }
            });
            return object.responseText;
        }
    </script>

    <script>
        function out_of_stock(itemListCacheId) {
            var outOfStockRequest = "outOfStockRequest";
            var Employee_ID = '<?= $Employee_ID ?>';

            if (confirm("This medication will State as OUT OF STOCK in patient file, Are you sure you want to continue?")) {
                $.ajax({
                    type: "POST",
                    url: "phamacy_handle.php",
                    data: {
                        itemListCacheId: itemListCacheId,
                        outOfStockRequest: outOfStockRequest,
                        Employee_ID: Employee_ID
                    },
                    success: (response) => {
                        getItemsFromPharmacy();
                    }
                });
            }
        }
    </script>

    <script>
        function autosave_duration(Id) {
            var dose_duration = "dose_duration" + Id;
            var get_value = document.getElementById(dose_duration).value;
            var update_duration = "update_duration";

            $.post(
                'pharmacy_auto_save.php', {
                    Id: Id,
                    get_value: get_value,
                    update_duration: update_duration
                }, (response) => {
                    console.log(response)
                }
            );
        }
    </script>

    <script>
        function autosave_dose_qty(id) {
            var get_value = "dosenqtyid" + id;
            var get_value_in_dispensity = "dispenseqtyid" + id;
            var id_for_balance = "balanceqtyid" + id;
            var remainqtyid = "remainqtyid" + id;

            var item_price = "item_price" + id;
            var subtotal = "subtotal" + id;
            var balance_qty = $('#' + id_for_balance).val();

            var value = document.getElementById(get_value).value;
            var item_price = document.getElementById(item_price).value;
            var request = 'request';

            document.getElementById(get_value_in_dispensity).value = value;
            document.getElementById(get_value_in_dispensity+"1").value = value;
            document.getElementById(remainqtyid).value = value;
            document.getElementById(subtotal).value = value * item_price;

            var new_dispensed_quantity = document.getElementById(get_value_in_dispensity).value;

            // check if despensed quantity is greater than item balance
            if (parseInt(new_dispensed_quantity) > parseInt(balance_qty)) {
                alert("Dispensed quantity is greater than item balance");
                $('#' + get_value_in_dispensity).css("background-color", 'red');
                $('#'+id).css('display','none');
                exit();
            }

            $('#'+id).css('display','inherit');
            $('#' + get_value_in_dispensity).css("background-color", 'white');

            if (parseInt() > parseInt()) {
                alert("Dispense quantity is greater than item balance");
                exit();
            }

            $.get(
                'pharmacy_auto_save.php', {
                    id: id,
                    value: value,
                    request: request
                }, (response) => {
                    console.log(response)
                }
            );
        }

        function autosave_edit(id) {
            var get_value = "dosenqtyid" + id;
            var value = document.getElementById(get_value).value;
            var request_one = 'request_one';

            $.get(
                'pharmacy_auto_save.php', {
                    id: id,
                    value: value,
                    request_one: request_one
                }, (response) => {
                    console.log(response)
                }
            );
        }

        function returnMedication(Patient_Payment_Item_List_Cache_ID){
            $.post('pharmacy-repo/common.php',{
                Patient_Payment_Item_List_Cache_ID:Patient_Payment_Item_List_Cache_ID,
                update_item_status:'update_item_status'
            },(response) => {
                response == 1 ? getItemsFromPharmacy() : alert(response);
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            console.clear();
            $("#Add_New_Item").dialog({
                autoOpen: false,
                width: '70%',
                height: 450,
                title: 'ADD NEW ITEMS',
                modal: true
            });

            $("#Non_Supported_Item").dialog({
                autoOpen: false,
                width: '40%',
                height: 150,
                title: 'NON SUPPORTED ITEM',
                modal: true
            });
        });
    </script>

    <script>
        function checkDose(Payment_Item_Cache_List_ID, Item_Id, Registration_Id, Payment_Cache_Id) {
            let get_checkbox_id = Payment_Item_Cache_List_ID;
            let response = '';
            $.post(
                'validate_dosage.php', {
                    Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
                    Item_Id: Item_Id,
                    Registration_Id: Registration_Id,
                    Payment_Cache_Id: Payment_Cache_Id
                }, (response) => {
                    if (response === 'Yes') {
                        console.log('Good to go');
                    } else {
                        if (confirm(response)) {
                            console.log('Checkbox checked');
                            $('#' + get_checkbox_id).prop('checked', true);
                        } else {
                            console.log('checkbox unchecked');
                            $('#' + get_checkbox_id).prop('checked', false);
                        }
                    }
                }
            );
        }

        // view and returned removed items pharmacy
        function viewRemovedItems(Payment_Cache_ID, Reg_No) {
            $.get('pharmacy-repo/common.php', {
                    Payment_Cache_ID: Payment_Cache_ID,
                    Reg_No: Reg_No,
                    return_medication_view:'return_medication_view'
                }, (response) => {
                    $("#removed_item_section").dialog({
                        autoOpen: false,
                        width: '50%',
                        height: 500,
                        title: 'REMOVED ITEMS',
                        modal: true
                    });
                    $("#removed_item_section").html(response);
                    $("#removed_item_section").dialog("open");
                }
            );
        }
    </script>

    <script>
        function substituteMedication(ItemListCacheId, Item_Id) {
            var Employee_ID = '<?= $Employee_ID ?>';
            var Item_Id = Item_Id;
            var subdepartmentID = '<?= $_SESSION['Pharmacy_ID'] ?>';

            var check_medication_if_available = CheckMedicationIfavailable(ItemListCacheId, Item_Id);

            if (check_medication_if_available == 1) {
                if (confirm("Are you sure you want to return substituted medication ?")) {
                    var return_medication = "return_medication";
                    $.ajax({
                        type: "POST",
                        url: "phamacy_handle.php",
                        data: {
                            ItemListCacheId: ItemListCacheId,
                            Item_Id: Item_Id,
                            return_medication: return_medication
                        },
                        success: (response) => {
                            alert(response);
                            getItemsFromPharmacy();
                        }
                    });
                } else {
                    showItemToSubstitute(Item_Id, Employee_ID, subdepartmentID, ItemListCacheId);
                }
            } else {
                showItemToSubstitute(Item_Id, Employee_ID, subdepartmentID, ItemListCacheId);
            }
        }
    </script>

    <script>
        function showItemToSubstitute(Item_Id, Employee_ID, subdepartmentID, ItemListCacheId) {
            $.ajax({
                type: "GET",
                url: "pharmacy_medication_substitute.php",
                data: {
                    Item_Id: Item_Id,
                    Employee_ID: Employee_ID,
                    subdepartmentID: subdepartmentID,
                    ItemListCacheId: ItemListCacheId
                },
                success: (response) => {
                    $("#pharmacy_medication_substitute").dialog({
                        autoOpen: false,
                        width: '50%',
                        height: 400,
                        title: 'eHMS 4.0 : SUBSTITUTES',
                        modal: true
                    });
                    $("#pharmacy_medication_substitute").html(response);
                    $("#pharmacy_medication_substitute").dialog("open");
                }
            });
        }
    </script>

    <script>
        function CheckMedicationIfavailable(Payment_Item_List_Cache, Item_Id) {
            var check_if_item_is_substituted = "check_if_item_is_substituted";
            var CheckItemObject = $.ajax({
                type: "GET",
                url: "phamacy_handle.php",
                async: false,
                data: {
                    Payment_Item_List_Cache: Payment_Item_List_Cache,
                    Item_Id: Item_Id,
                    check_if_item_is_substituted: check_if_item_is_substituted
                },
                success: (response) => {
                    return response;
                }
            });

            return CheckItemObject.responseText;
        }
    </script>

    <script>
        // remove item from active list to removed status
        function remove_item(id_param, Item_ID) {
            var check_if_reasons_configured = checkIfReasonsForRemovalConfigured();
            if(check_if_reasons_configured == 1){
                $.get('pharmacy-repo/common.php',{remove_item_using_reasons:'remove_item_using_reasons',Item_ID:Item_ID,id_param:id_param},(response) => {
                    $("#removal_space").dialog({
                        autoOpen: false,
                        width: '40%',
                        height: 350,
                        title: 'REASONS REMOVAL',
                        modal: true
                    });
                    $("#removal_space").html(response);
                    $("#removal_space").dialog("open");
                });
            }else{
                if (confirm('Are you sure you want remove Item ?')) {
                    $.post('pharmacy-repo/common.php',{
                        reason_for_remove:'none',
                        Patient_Payment_Item_List:id_param,
                        Item_ID:Item_ID,
                        remove_pharmacy_item:'remove_pharmacy_item'
                    },(response) => {
                        getItemsFromPharmacy();
                    });
                }
            }
        }

        function checkIfReasonsForRemovalConfigured() { 
            var reasons_response = $.ajax({
                type: "GET",
                url: "pharmacy-repo/common.php",
                data: {remove_items_reasons_configured:'remove_items_reasons_configured'},
                async:false,
                success: (response) => {
                    return response;
                }
            });
            return reasons_response.responseText;
        }

        function removeWithReasons(Patient_Payment_Item_List,Item_ID){
            if(confirm('Are you sure you want remove Item ?')){
                var reason_for_remove = $('#reason_for_remove').val();
                $.post('pharmacy-repo/common.php',{
                    reason_for_remove:reason_for_remove,
                    Patient_Payment_Item_List:Patient_Payment_Item_List,
                    Item_ID:Item_ID,
                    remove_pharmacy_item:'remove_pharmacy_item'
                },(response) => {
                    $("#removal_space").dialog("close");
                    getItemsFromPharmacy();
                });
            }
        }

        function change_department_btn(Payment_Item_Cache_List_ID,Sub_Department_ID,Item_ID){
            $.get('phamacy_handle.php',{
                Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                Sub_Department_ID:Sub_Department_ID,
                Item_ID:Item_ID,
                change_department:'change_department'
            },(response) => {getItemsFromPharmacy()});
        }

        // function to bill and dispense for credit patients
        function make_bill_and_dispense() {
            var checked_item = $(".check_item").is(':checked');

            if (checked_item) {
                var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
                var Transaction_Type = '<?php echo $Transaction_Type; ?>';
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>'
                var Billing_Type = document.getElementById('Billing_Type').value;
                var Check_In_Type = "Pharmacy";
                var selectedItem = [];
                var ids = [];
                var Transaction_Type = '<?= $_GET['Transaction_Type'] ?>';

                var verify_qty_before_dispense = checkBalanceForDuplicateItems(Payment_Cache_ID);
                if(verify_qty_before_dispense.length > 2){
                    $("#duplicate_space").dialog({
                        autoOpen: false,
                        width: '40%',
                        height: 350,
                        title: 'eHMS 2.0 NOTIFICATION',
                        modal: true
                    });
                    $("#duplicate_space").html(verify_qty_before_dispense);
                    $("#duplicate_space").dialog("open");
                    exit();
                }
   
                $(".check_item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dosenqtyid" + Idd;
                    var id_for_dispense = "dispenseqtyid" + Idd;
                    var id_for_dose_duration = "dose_duration" + Idd;
                    var balanceqtyid = "balanceqtyid"+Idd;
                    

                    var dose = $('#' + id_for_dose_duration).val();
                    var balance = parseInt($('#'+balanceqtyid).val());
                    var id_for_dispense_qty = parseInt($('#'+id_for_dispense).val());

                    if (dose == 0) {
                        $('#' + id_for_dose_duration).css('background-color', 'red');
                        exit();
                    }else{
                        if(id_for_dispense_qty <= balance){
                            ids.push({id:Idd});
                            selectedItem.push({
                                id: Idd,
                                doseqty: $('#' + id_for_dose_qty).val(),
                                dispensedqty: $('#' + id_for_dispense).val(),
                                dose_duration: $('#' + id_for_dose_duration).val()
                            });
                        }else{
                            alert('Quantity is greater than balance');
                            exit();
                        }
                    }
                });
            } else {
                alert("Choose Item to Dispense");
                exit();
            }



            if (confirm("Are you sure you want to bill and dispense the selected medications")) {
                document.getElementById('bill_and_dispense_btn').style.background = '#543232';
                document.getElementById('bill_and_dispense_btn').value = 'Please Wait';
                document.getElementById('bill_and_dispense_btn').disabled = true;
                document.getElementById('bill_and_dispense_btn').style.cursor = "progress";

                $.ajax({
                    url: 'pharmacy-repo/common.php',
                    type: 'GET',
                    data: {
                        Payment_Cache_ID: Payment_Cache_ID,
                        Folio_Branch_ID: <?=$_SESSION['userinfo']['Branch_ID']?>,
                        Transaction_Type: Transaction_Type,
                        Billing_Type: Billing_Type,
                        Registration_ID: Registration_ID,
                        Check_In_Type: Check_In_Type,
                        Sub_Department_ID: Sub_Department_ID,
                        selectedItem: selectedItem,
                        Sponsor_ID: <?=$Sponsor_ID?>,
                        Employee_ID: <?=$Employee_ID?>,
                        dispense_credit:'dispense_credit'
                    },
                    success: function(data) {
                        alert(data);
                        getItemsFromPharmacy();
                        document.getElementById('bill_and_dispense_btn').style.background = 'linear-gradient(to bottom, rgba(3,125,176,1) 0%,rgba(52,141,175,1) 50%,rgba(3,125,176,1) 100%)';
                        document.getElementById('bill_and_dispense_btn').value = 'BILL AND DISPENSE MEDICATION';
                        document.getElementById('bill_and_dispense_btn').disabled = false;
                        document.getElementById('bill_and_dispense_btn').style.cursor = "pointer";
                    }
                });
            }
        }

        function checkBalanceForDuplicateItems(Payment_Cache_ID){
            var res = $.ajax({
                type: "GET",
                url: "pharmacy-repo/common.php",
                data: {Payment_Cache_ID:Payment_Cache_ID,check_balance_for_duplicate_items:'check_balance_for_duplicate_items'},
                async:false,
                success: (response) => {
                    return response;
                }
            });
            return res.responseText;
        }
    </script>

    <script>
        function calculate_subtotal(Id) {
            var item_sub_total = "subtotal" + Id;
            var id_for_dispense = "dispenseqtyid" + Id;
            var item_price = "item_price" + Id;

            var dispensed_qty = $('#' + id_for_dispense).val();
            var item_price = $('#' + item_price).val();
            var itemSubtotal = dispensed_qty * item_price;

            $('#' + item_sub_total).val(itemSubtotal.toLocaleString('en'));
        }

        function dispensed_qty_function(Id) {
            var id_for_dose_qty = "dosenqtyid" + Id;
            var id_for_dispense = "dispenseqtyid" + Id;
            var id_for_remained = "remainqtyid" + Id;
            var id_for_balance = "balanceqtyid" + Id;
            var id_for_previus = "previuse" + Id;
            var item_sub_total = "subtotal" + Id;
            var item_price = "item_price" + Id;
            var new_subtotal = "subtotal" + Id;

            var balance_qty = $('#' + id_for_balance).val();
            var dosen_qty = $('#' + id_for_dose_qty).val();
            var dispensed_qty = $('#' + id_for_dispense).val();
            var previus = $('#' + id_for_previus).val();
            var idforremained = $('#' + id_for_remained).val();
            var get_item_price = $('#' + item_price).val();

            if (parseInt(dispensed_qty) > parseInt(dosen_qty)) {
                alert("Dispensed quantity should not be greater than dose quantity");
                $('#' + id_for_dispense).css("background-color", 'red');
                $('#'+Id).css('display','none');
                $('#' + id_for_dispense).val(0);
                document.getElementById(Id).disabled = true;
                exit();
            }

            if (parseInt(dispensed_qty) > parseInt(balance_qty)) {
                alert('Dispensed Quantity is greater than dose quantity');
                $('#' + id_for_dispense).css("background-color", 'red');
                $('#'+Id).css('display','none');
                $('#' + id_for_dispense).val(0);
                exit();
            }

            if (parseInt(dosen_qty) < 1) {
                alert('Please Fill in Dosage Quantity to Continue..!');
                $('#' + id_for_dose_qty).css("background-color", '#9f3833');
                $('#'+Id).css('display','none');
                exit();
            }

            if (dosen_qty == '') {
                alert('Please fill dose quantity');
                $('#'+Id).css('display','none');
                exit();
            }

            $('#'+Id).css('display','inherit');
            document.getElementById(new_subtotal).value = get_item_price * dispensed_qty;

            if (parseInt(remain_to_complete) < 0) {
                alert('Remaining quantity is less than 0');
                $('#' + id_for_dispense).css("background-color", 'green');
                document.getElementById(Id).disabled = true;
                exit();
            }

            if (parseInt(balance_qty) <= 0) {
                $('#' + id_for_dispense).css("background-color", "red");
                alert("balance it is not enough");
            } else {
                $('#' + id_for_dispense).css("background-color", "white");

                perfom_edit(Id, dispensed_qty);

                var check_if_element_is_checked = $('#' + Id).is(":checked");

                if (check_if_element_is_checked == true) {
                    $('#' + item_sub_total).val(get_item_price * dispensed_qty);
                    var date_values = $("input[class='total']")
                        .map(function() {
                            return $(this).val();
                        }).get();

                    var sum = 0;
                    for (var el in date_values) {
                        if (date_values.hasOwnProperty(el)) {
                            sum += parseFloat(date_values[el]);
                        }
                    }

                    var remain_to_complete = idforremained - dispensed_qty;

                    $('#' + id_for_remained).val(remain_to_complete);
                    var difference = balance_qty - dispensed_qty;
                } else {
                    $('#' + item_sub_total).val(get_item_price * dispensed_qty);
                    $('#' + id_for_remained).val(dosen_qty);
                    var date_values = $("input[class='total']")
                        .map(function() {
                            return $(this).val();
                        }).get();

                    var sum = 0;
                    for (var el in date_values) {
                        if (date_values.hasOwnProperty(el)) {
                            sum += parseFloat(date_values[el]);
                        }
                    }
                }
                document.getElementById('total_amount').value = sum.toLocaleString('en');

                $('#total').val('Tshs : ' + sum + "oo");
                var total_amount = sum;
                document.getElementById(Id).disabled = false;
            }
        }

        function perfom_edit(id, dispensed_qty) {
            var get_value = "dispensed_qty" + id;
            var value = dispensed_qty;
            var request_one = 'request_one';

            $.get(
                'pharmacy_auto_save.php', {
                    id: id,
                    value: value,
                    request_one: request_one
                }, (response) => { console.log(response) }
            );
        }
    </script>
    <script>
        function send_patient_to_cashier() {
            var checked_item = $(".check_item").is(':checked');
            var Payment_Cache_ID = '<?=$Payment_Cache_ID;?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Check_In_Type = "Pharmacy";
            var sub_department_id = '<?= $Sub_Department_ID ?>';

            if (checked_item) {
                var selectedItem = [];
                $(".check_item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dosenqtyid" + Idd;
                    var id_for_dispense = "dispenseqtyid" + Idd;
                    var check_status = "check_status" + Idd;
                    var status = $('#' + check_status).val();
                    
                    if (status == "paid") {
                        alert("Sorry, you cant send paid item to cashier");
                        exit();
                    }else{
                        selectedItem.push({
                            id: Idd,
                            doseqty: $('#' + id_for_dose_qty).val(),
                            dispensedqty: $('#' + id_for_dispense).val()
                        });
                    }
                });

                if (confirm("Send This patient to Cashier")) {
                    $.ajax({
                        url: 'pharmacy-repo/common.php',
                        type: 'GET',
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            Transaction_Type: Transaction_Type,
                            Check_In_Type: Check_In_Type,
                            selectedItem: selectedItem,
                            sub_department_id: sub_department_id,
                            send_to_cashier:'send_to_cashier'
                        },
                        success: function(data) {
                            alert(data);
                            getItemsFromPharmacy();
                        }
                    });
                }
            } else { alert("Choose Item to Send to Cashier"); }
        }
    </script>

    <script>
        function dispense_item() {
            var checked_item = $(".check_item").is(':checked');
            var Payment_Cache_ID = '<?=$Payment_Cache_ID;?>';
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var sub_department_id = '<?= $Sub_Department_ID ?>';
            var Check_In_Type = "Pharmacy";

            if (checked_item) {
                var selectedItem = [];
                var dose_durations = [];

                $(".check_item:checked").each(function() {
                    let Idd = $(this).val();
                    check_if_paid(Idd);
                    let id_for_dose_qty = "dosenqtyid" + Idd;
                    let id_for_dispense = "dispenseqtyid" + Idd;
                    let id_for_dose_duration = "dose_duration" + Idd;
                    let balanceqtyid = "balanceqtyid"+Idd;

                    var dose = $('#' + id_for_dose_duration).val();
                    var balance = parseInt($('#'+balanceqtyid).val());
                    var id_for_dispense_qty = parseInt($('#'+id_for_dispense).val());

                    if(dose === 0){
                        $('#' + id_for_dose_duration).css('background-color', 'red');
                        exit();
                    }else{
                        if(id_for_dispense_qty <= balance){
                            selectedItem.push({
                                id: Idd,
                                doseqty: $('#' + id_for_dose_qty).val(),
                                dispensedqty: $('#' + id_for_dispense).val(),
                                dose_duration: $('#' + id_for_dose_duration).val()
                            });
                        }else{
                            alert('Quantity is greater than balance');
                            exit();
                        }
                    }
                });

                if (confirm("Are you sure, you want to dispense selected items ?")) {
                    $.ajax({
                        url: 'pharmacy-repo/common.php',
                        type: 'GET',
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            Registration_ID: Registration_ID,
                            selectedItem: selectedItem,
                            sub_department_id:sub_department_id,
                            Employee_ID:<?=$_SESSION['userinfo']['Employee_ID']?>,
                            dispense_cash_medicaion:'dispense_cash_medicaion'
                        },
                        success: (data) => {
                            alert(data);
                            getItemsFromPharmacy();
                        }
                    });
                }
            } else { alert("Choose Item to Dispense"); }
        }
    </script>

    <script>
        function check_if_paid(idd) {
            let checked_item = $(".check_item").is(':checked');
            if (checked_item) {
                $(".check_item:checked").each(function() {
                    var check_status = "check_status" + idd;
                    var status = $('#' + check_status).val();
                    if (status === "active" || status === "partial dispensed") {
                        alert("you can't dispense unpaid item");
                        exit();
                    }
                });
            }
        }

        function openItemDialog() {
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Sub_Department_ID = '<?php echo $_SESSION['Pharmacy_ID'] ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID = '<?= $Sponsor_ID ?>';

            if (window.XMLHttpRequest) {
                myObjectAddItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddItem.overrideMimeType('text/xml');
            }

            myObjectAddItem.onreadystatechange = function() {
                data_Add_New = myObjectAddItem.responseText;
                if (myObjectAddItem.readyState == 4) {
                    document.getElementById("Add_New_Items_Area").innerHTML = data_Add_New;
                    $("#Add_New_Item").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectAddItem.open('GET', 'Patient_Billing_Pharmacy_Add_New_Item.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObjectAddItem.send();
        }

        function Get_Item_Name(Item_Name, Item_ID) {
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
            if (Billing_Type == 'Outpatient Credit') {
                if (window.XMLHttpRequest) {
                    My_Object_Verify_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    My_Object_Verify_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    My_Object_Verify_Item.overrideMimeType('text/xml');
                }

                My_Object_Verify_Item.onreadystatechange = function() {
                    dataVerify = My_Object_Verify_Item.responseText;
                    if (My_Object_Verify_Item.readyState == 4) {
                        var feedback = dataVerify;
                        if (feedback == 'yes') {
                            Get_Details(Item_Name, Item_ID);
                        } else {
                            document.getElementById("Price").value = 0;
                            document.getElementById("Quantity").value = '';
                            document.getElementById("Item_Name").value = '';
                            $("#Non_Supported_Item").dialog("open");
                        }
                    }
                }; //specify name of function that will handle server response........
                My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID, true);
                My_Object_Verify_Item.send();
            } else {
                Get_Details(Item_Name, Item_ID);
            }
        }



        function Get_Details(Item_Name, Item_ID) {
            document.getElementById('Comment').value = '';

            if (window.XMLHttpRequest) {
                myObjectGetItemName = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItemName.overrideMimeType('text/xml');
            }

            item_balance(Item_ID)

            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
            document.getElementById("Quantity").value = 0;
        }

        function item_balance(Item_ID) {
            let Sub_Department_ID = '<?= $Sub_Department_ID ?>';
            $.get(
                'Pharmacy_Get_Item_Balance.php', {
                    Item_ID: Item_ID,
                }, (response) => {
                    document.getElementById('balance').value = response;
                }
            );
        }

        function Get_Item_Price(Item_ID, Sponsor_ID) {
            let Billing_Type = document.getElementById("Billing_Type").value;
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
            myObject.onreadystatechange = function() {
                dataPrice = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Price').value = dataPrice;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Patient_Billing_Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
            myObject.send();
        }

        function Check_Dosage_Time() {
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Item_ID = document.getElementById("Item_ID").value;
            $.ajax({
                type: "GET",
                url: "Check_Dosage_Time.php",
                data: {
                    Item_ID: Item_ID,
                    Registration_ID: Registration_ID
                },
                success: function(result) {
                    if (result === "endelea") {
                        check_for_duplicate();
                    } else {
                        if (confirm(result)) {
                            check_for_duplicate();
                        }
                    }
                }
            });
        }

        function getItemsListFiltered(Item_Name) {
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            document.getElementById("Quantity").value = '';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

            var Item_Category_ID = document.getElementById("Item_Category_ID").value;
            if (Item_Category_ID == '' || Item_Category_ID == null) {
                Item_Category_ID = 'All';
            }

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function() {
                data135 = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('New_Items_Fieldset').innerHTML = data135;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject.send();
        }

        function getItemsList(Item_Category_ID) {
            document.getElementById("Search_Value").value = '';
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject.onreadystatechange = function() {
                data265 = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('New_Items_Fieldset').innerHTML = data265;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Pharmacy_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID, true);
            myObject.send();
        }



        function check_for_duplicate() {
            var Item_ID = document.getElementById("Item_ID").value;
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';

            $.ajax({
                type: 'GET',
                url: 'checkforduplicatepharmacy.php',
                data: 'Item_ID=' + Item_ID + '&Payment_Cache_ID=' + Payment_Cache_ID,
                cache: false,
                success: function(html) {
                    if (html == '1') {
                        alertMsgSimple("Process the current item or remove and re-add it again.", "Duplicate Items", "error", 400, false, 'Ok');
                        exit;
                    } else {
                        Get_Selected_Item();
                    }
                }
            });
        }

        function Get_Selected_Item() {
            var dosade_duration = document.getElementById("dosade_duration").value;
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Item_ID = document.getElementById("Item_ID").value;
            var Quantity = document.getElementById("Quantity").value;
            var Registration_ID = <?php echo $Registration_ID; ?>;
            var Comment = document.getElementById("Comment").value;
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Price = document.getElementById("Price").value;
            var Balance = document.getElementById("balance").value;
            var Sub_Department_ID = '<?php echo $_SESSION['Pharmacy_ID'] ?>';
            var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
            var Check_In_Type = '<?= $_GET['Check_In_Type'] ?>';
            var check_dose_string = /[a-z]+$/;

            if (Balance <= 0) {
                alert('Item selected has is 0 or less balance left.');
                exit();
            }

            if (Registration_ID != '' && Registration_ID != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null) {
                if (parseInt(Price) == 0) {
                    alert('Process fail!. Item missing price.');
                    return false;
                }

                if (window.XMLHttpRequest) {
                    myObject2 = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject2.overrideMimeType('text/xml');
                }
                myObject2.onreadystatechange = function() {
                    dataadd = myObject2.responseText;
                    console.log(Sub_Department_ID);

                    if (myObject2.readyState == 4) {
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Price").value = '';
                        document.getElementById("Comment").value = '';
                        getItemsFromPharmacy();
                    }
                }; //specify name of function that will handle server response........


                myObject2.open('GET', 'Patient_Billing_Pharmacy_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Claim_Form_Number=' + Claim_Form_Number + '&Comment=' + Comment + '&Sub_Department_ID=' + Sub_Department_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Price=' + Price + '&Transaction_Type=' + Transaction_Type + '&dosade_duration=' + dosade_duration + '&Sponsor_ID=' + Sponsor_ID + "&Check_In_Type=" + Check_In_Type, true);
                myObject2.send();

            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
                alertMessage();
            } else {
                if (Quantity == '' || Quantity == null) {
                    document.getElementById("Quantity").focus();
                    document.getElementById("Quantity").style = 'border: 3px solid red';
                }
            }
        }
    </script>

    <script>
        // inpatient bill for cash 
        function cash_inpatient_bill() {
            var selected_item = [];
            var Registration_ID = '<?php echo $_GET['Registration_ID'] ?>';
            var Sponsor_ID = '<?= $Sponsor_ID ?>';
            var Guarantor_Name = '<?= $Guarantor_Name ?>';
            var check_in_type = 'Pharmacy';
            var Transaction_Type = '<?= $Transaction_Type ?>';
            var Payment_Cache_ID = '<?= $Payment_Cache_ID ?>';
            var Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
            var Employee_Name = '<?= $Employee_Name ?>';

            $(".check_item:checked").each(function() {
                var Idd = $(this).val();
                var id_for_dose_qty = "dosenqtyid" + Idd;
                var id_for_dispense = "dispenseqtyid" + Idd;
                var id_for_dose_duration = "dose_duration" + Idd;
                var Payment_Item_Cache_List_ID = Idd;
                selected_item.push({
                    id: Idd,
                    doseqty: $('#' + id_for_dose_qty).val(),
                    dispensedqty: $('#' + id_for_dispense).val(),
                    dose_duration: $('#' + id_for_dose_duration).val(),
                    Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID });
            });

            if (confirm("Are you sure you want dispense medication ?")) {
                $.get(
                    'test_credit_biling.php', {
                        Registration_ID: Registration_ID,
                        Sponsor_ID: Sponsor_ID,
                        Guarantor_Name: Guarantor_Name,
                        check_in_type: check_in_type,
                        Transaction_Type: Transaction_Type,
                        Payment_Cache_ID: Payment_Cache_ID,
                        selected_item: selected_item,
                        Employee_Name: Employee_Name
                    }, (response) => {
                        alert(response);
                        getItemsFromPharmacy();
                    }
                );
            }
        }
    </script>

    <script>
        function go_mobile_card_transaction() {
            if (confirm('Are you sure you want to go to Mobile/Card Payment')) {
                var checked_item = $(".check_item").is(':checked');
                var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
                var Transaction_Type = '<?php echo $Transaction_Type; ?>';
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
                var Check_In_Type = "Pharmacy";
                var sub_department_id = '<?= $Sub_Department_ID ?>';

                if (checked_item) {
                    var selectedItem = [];
                    $(".check_item:checked").each(function() {
                        var Idd = $(this).val();
                        var id_for_dose_qty = "dosenqtyid" + Idd;
                        var id_for_dispense = "dispenseqtyid" + Idd;
                        selectedItem.push({
                            id: Idd,
                            doseqty: $('#' + id_for_dose_qty).val(),
                            dispensedqty: $('#' + id_for_dispense).val()
                        });
                    });

                    if (confirm("Are you sure ?")) {
                        $.ajax({
                            url: 'Send_To_Cashier.php',
                            type: 'GET',
                            data: {
                                Payment_Cache_ID: Payment_Cache_ID,
                                Transaction_Type: Transaction_Type,
                                Sub_Department_Name: Sub_Department_Name,
                                Registration_ID: Registration_ID,
                                Check_In_Type: Check_In_Type,
                                selectedItem: selectedItem,
                                sub_department_id: sub_department_id
                            },
                            success: (data) => {
                                location.href = "patient_sent_to_cashier_payment.php?Payment_Cache_ID=" + <?= $Payment_Cache_ID ?> + "&Registration_ID=" + <?= $Registration_ID ?>;
                            }
                        });
                    }
                } else {
                    alert("Choose Item to Send to Cashier");
                }
            }
        }
    </script>

    <script>
        function createManualPayment() {
            var Payment_Cache_ID = '<?= $_GET['Payment_Cache_ID'] ?>';
            var selected_Payment_Item_Cache_List_ID = [];
            var Registration_ID = '<?= $_GET['Registration_ID'] ?>';
            var grand_total_price = parseInt(document.getElementById('total_amount').value.replace(',', ''));

            $(".check_item:checked").each(function() {
                selected_Payment_Item_Cache_List_ID.push($(this).val());
            });

            if (selected_Payment_Item_Cache_List_ID.length > 0) {
                if (confirm('Are sure you need to create manual payment')) {
                    $.ajax({
                        type: "POST",
                        url: "ajax_manual_create_epayment_bill.php",
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            selected_Payment_Item_Cache_List_ID: selected_Payment_Item_Cache_List_ID,
                            grand_total_price: grand_total_price,
                            Registration_ID: Registration_ID
                        },
                        success: (response) => {
                            manual_payment_process_transaction(response)
                        }
                    });
                }
            } else {
                alert('Select items to create manual payments')
            }
        }

        function manual_payment_process_transaction(Transaction_ID){
            var Payment_Cache_ID = '<?= $_GET['Payment_Cache_ID'] ?>';
            var Registration_ID = '<?= $_GET['Registration_ID'] ?>';
            console.log("hii  ya manual");
            $.ajax({
                type:'POST',
                url:'ajax_new_manualtransactiondetails.php',
                data:{Payment_Cache_ID:Payment_Cache_ID},
                success:function(data){
                    if(data!="fail"){
                        var Payment_Code=data;
                        manual_payment_complete_payment_process(Payment_Code,Transaction_ID);
                    }else{
                        alert("Process Fail!...Try again");
                    }
                    $("#progress_dialog").hide();
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }

        function manual_payment_complete_payment_process(Payment_Code,Transaction_ID){
            var Payment_Cache_ID = '<?= $_GET['Payment_Cache_ID'] ?>';
            var Registration_ID = '<?= $_GET['Registration_ID'] ?>';
            console.log("receipt==="+Payment_Code+"trans_id"+Transaction_ID);
            $.ajax({
                type:'GET',
                url:'force_move_data_for_manual_receipt_print.php',
                data:{Payment_Code:Payment_Code,Registration_ID:Registration_ID,Transaction_ID:Transaction_ID},
                success:function(data){
                    dispense_item();
                    getItemsFromPharmacy();
                    var html="<input type='button' class='art-button' style='font-family:arial' value='PRINT DETAIL RECEIPT' onclick='Print_Receipt_Payment_new(\""+Payment_Code+"\")'>";
                    $("#displayPrintReciept").html(html);
                },
                error:function(x,n,m){
                    console.log(x+n+m)
                }
            }); 
        }

        function Print_Receipt_Payment_new(Payment_Code){
            $.ajax({
                type:'POST',
                url:'ajax_Print_Receipt_Payment_new.php',
                data:{Payment_Code:Payment_Code},
                success:function(data){
                    window.open("invidualsummaryreceiptprint.php?Patient_Payment_ID=" + data + "&IndividualSummaryReport=IndividualSummaryReportThisForm");
                }
            });
        }
    </script>

    <script>
        function changeBillType() {
            var Billing_Type = document.getElementById('Billing_Type').value;
            var Registration_Number = document.getElementById('Registration_Number').value;
            var Transaction_Type = '<?= $_GET['Transaction_Type'] ?>';
            var Payment_Cache_ID = '<?= $_GET['Payment_Cache_ID'] ?>';

            if (Billing_Type === 'Inpatient Cash' && Transaction_Type === 'Credit') {
                if (confirm("You are preparing cash temporary transaction for patient, are you sure ?")) {
                    location.replace('change_sponsor_to_cash_inpatient.php?Registration_ID=' + Registration_Number + '&Payment_Cache_ID=' + Payment_Cache_ID);
                }
            }
        }
    </script>

    <script>
        function viewPatientMedicationHistory() {
            var Registration_ID = <?=$_GET['Registration_ID']?>;
            $.ajax({
                type: "GET",
                url: "view-patient-medication-history.php",
                data: {Registration_ID:Registration_ID},
                success: (data) => {
                    $("#viewPatientMedicationHistory").dialog({
                        autoOpen: false,
                        width: '80%',
                        height: 600,
                        title: 'eHMS 4.0 : PATIENT HISTORY',
                        modal: true
                    });
                    $("#viewPatientMedicationHistory").html(data);
                    $("#viewPatientMedicationHistory").dialog("open");
                }
            });
        }

        function viewPatientPrescription(){
            var Registration_ID = <?=$_GET['Registration_ID']?>;
            $.ajax({
                type: "GET",
                url: "view-patient-medication-prescription.php",
                data: {Registration_ID:Registration_ID},
                success: (data) => {
                    $("#viewPatientPrescription").dialog({
                        autoOpen: false,
                        width: '80%',
                        height: 600,
                        title: 'eHMS 4.0 : PATIENT HISTORY',
                        modal: true
                    });
                    $("#viewPatientPrescription").html(data);
                    $("#viewPatientPrescription").dialog("open");
                }
            });
        }

        function viewsSummaryPatientFile() {
            var Registration_ID = <?=$_GET['Registration_ID']?>;
            $.ajax({
                type: "GET",
                url: "view-summary-patient-file.php",
                data: {Registration_ID:Registration_ID},
                success: (data) => {
                    $("#viewPatientFileSummary").dialog({
                        autoOpen: false,
                        width: '80%',
                        height: 600,
                        title: 'eHMS 4.0 : PATIENT FILE SUMMARY',
                        modal: true
                    });
                    $("#viewPatientFileSummary").html(data);
                    $("#viewPatientFileSummary").dialog("open");
                }
            });
        }

        function substituteMediaction(Payment_Item_Cache_List_ID,Item_ID) { 
            var Registration_ID = <?=$_GET['Registration_ID']?>;
            var Payment_Cache_Id = <?=$_GET['Payment_Cache_ID']?>;
            var Sub_Department_id = <?=$Sub_Department_ID?>;
            $.ajax({
                type: "GET",
                url: "substitute-medication.php",
                data: {
                    Registration_ID:Registration_ID,
                    Payment_Cache_Id:Payment_Cache_Id,
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    Item_ID:Item_ID,
                    Sub_Department_id:Sub_Department_id
                },
                success: (data) => {
                    $("#substituteMedication").dialog({
                        autoOpen: false,
                        width: '70%',
                        height: 600,
                        title: 'eHMS 4.0 : SUBSTITUTE MEDICATION',
                        modal: true
                    });
                    $("#substituteMedication").html(data);
                    $("#substituteMedication").dialog("open");
                }
            });
        }

        function substitute(Payment_Item_Cache_List_ID,old_item_Id,new_item_price,new_item_id) { 
            if(confirm('are you sure want to substitute')){
                $.post('pharmacy-core.php',{
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    old_item_Id:old_item_Id,
                    new_item_id:new_item_id,
                    new_item_price:new_item_price,
                    action:'substitute',
                    Employee_ID:<?=$Employee_ID?>
                },(response) => {
                    if(response == "ok"){
                        $("#substituteMedication").dialog("close");
                        getItemsFromPharmacy();
                    }else{
                        alert('Something went wrong contact system administrator for support :: '+ response);
                    }
                });
            }
        }
    </script>

    <?php include("./includes/footer.php"); ?>
