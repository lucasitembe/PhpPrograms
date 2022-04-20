<script src='js/functions.js'></script>
<?php
# Configuratios
include './includes/header.php';
include './includes/connection.php';
require_once './includes/ehms.function.inc.php';
include 'pharmacy-repo/interface.php';
$Interface = new PharmacyInterface();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Pharmacy_Supervisor'])) {
                header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

# Link Configurations
if (
    isset($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) &&
    strtolower($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) == 'yes' &&
    $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != null &&
    $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != ''
) {
    $Additional_Instruction = '(' . strtoupper($_SESSION['systeminfo']['Pharmacy_Additional_Instruction']) . ')';
} else {
    $Additional_Instruction = '';
}

# Get Configurations
$configResult = mysqli_query($conn, "SELECT * FROM tbl_config") or die(mysqli_error($conn));
while ($data = mysqli_fetch_assoc($configResult)) {
    $configname = $data['configname'];
    $configvalue = $data['configvalue'];
    $_SESSION['configData'][$configname] = strtolower($configvalue);
}

# Basic Details
$Employee_Name = (isset($_SESSION['userinfo']['Employee_Name'])) ? $_SESSION['userinfo']['Employee_Name'] : '';
$Employee_ID = (isset($_SESSION['userinfo']['Employee_ID'])) ? $_SESSION['userinfo']['Employee_ID'] : '';
$Sub_Department_ID = ($_SESSION['Pharmacy_ID']) ? $_SESSION['Pharmacy_ID'] : 0;
$Registration_ID = $_GET['Registration_ID'] ;
$Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'] or die(mysqli_error($conn));

# Transactions
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
    $_SESSION['Transaction_Type'] = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

# Get current date and time
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

    $clinicList = $Interface->getClinicList();
    $working_departments = $Interface->getWorkingDepartment();
    $consultants = $Interface->getConsultants();
    $Patient_Details = $Interface->getPatientDemographicDetails($Registration_ID);



    $age = floor((strtotime(date('Y-m-d')) - strtotime($Patient_Details[0]['Date_Of_Birth'])) / 31556926) . " Years";
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Patient_Details[0]['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";


if (strtolower($Payment_Method) != 'cash') {
    # Get available last folio number if available
    $get_folio = mysqli_query($conn, "SELECT Folio_Number, Claim_Form_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' 
                                         AND Receipt_Date = '$Today' AND Sponsor_ID = '$Sponsor_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($get_folio);
    if ($numrow > 0) {
        while ($data = mysqli_fetch_array($get_folio)) {
            $Folio_Number = $data['Folio_Number'];
            $Claim_Form_Number = $data['Claim_Form_Number'];
        }
    } else {
        $Folio_Number = "";
        $Claim_Form_Number = '';
    }
} else {
    $Folio_Number = '';
    $Claim_Form_Number = '';
}

#patient billing types
$sp_name_payment_mode = mysqli_fetch_array(mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Guarantor_Name  = '".$Patient_Details[0]['Guarantor_Name']."' LIMIT 1"))['payment_method'];
// die($sp_name_payment_mode);
?>

<!-- Custom Style -->
<style>
    .items-data td {
        padding: 10px;
    }

    .items-data {
        border-collapse: collapse;
    }

    #items tr,
    td {
        border: 1px solid #ccc !important;
    }
</style>
<!-- Custom Style -->

<!-- Back Button -->
<a href="new_pharmacy_customer_list.php" class="art-button-green">BACK</a>
<!-- Back Button -->

<fieldset>
    <legend style='background-color:#006400;color:white;padding:5px;font-size:16px' align=right>
        <b> <?php if (isset($_SESSION['Pharmacy_ID'])) {
                echo $Sub_Department_Name;
            } ?> </b>
    </legend>

    <table width=100% border="0" class="information" style="align-items: center;">
        <tr>
            <td width='12.5%' style='text-align: right;' class="data">Costumer Name</td>
            <td width='12.5%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?=$Patient_Details[0]['Patient_Name'];?>'></td>
            <td style='text-align: right;' width='12.5%' class="data">Claim Form Number</td>
            <td width='16.6%'><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' value="1<?php echo $Claim_Form_Number; ?>"></td>
            <td width='12.5%' style='text-align: right;' class="data">Gender</td>
            <td width='12.5%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?=$Patient_Details[0]['Gender']?>'></td>
            <td style='text-align: right;' class="data"><b style="color:green"><span style="color:red">*</span>Billing Type</b></td>
            <td>
                <select name='Billing_Type' id='Billing_Type' style="width:100%;padding:4px" onchange="check_sponsor(this)">
                    <?=($sp_name_payment_mode == 'cash') ? "<option>Outpatient Cash</option>":"<option>Outpatient Credit</option><option>Outpatient Cash</option>"; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td style='text-align: right;' class="data">Costumer Age</td>
            <td><input type='text' name='Patient_Age' id='Patient_Age' disabled='disabled' value='<?php echo $age; ?>'></td>
            <td style='text-align: right;' class="data">Folio Number</td>
            <td><input type='text' name='Folio_Number' id='Folio_Number' readonly="readonly" autocomplete='off' placeholder='Folio Number' value="0<?php echo $Folio_Number; ?>"></td>
            <td style='text-align: right;' class="data">Type Of Check In</td>
            <td><input type="text" name='Type_Of_Check_In' id="Type_Of_Check_In" value="Pharmacy"></td>
            <td style='text-align: right;' class="data">Sponsor Name</td>
            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?=$Patient_Details[0]['Guarantor_Name']; ?>'></td>
        </tr>

        <tr>
            <td style='text-align: right;' class="data">Registered Date</td>
            <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?=$Patient_Details[0]['Registration_Date'];?>'></td>
            <td style='text-align: right;' class="data">Costumer Direction</td>
            <td><input type="text" id='direction' name='direction' readonly value="Others"></td>
            <td style='text-align: right;' class="data">Registration Number</td>
            <td><input type='text' name='Registration_Number' id='Registration_Number' readonly value='<?=$Patient_Details[0]['Registration_ID'];?>'></td>
            <td style='text-align: right;' class="data">Phone Number</td>
            <td><input type='text' name='Phone_Number' id='Phone_Number' readonly value='<?=$Patient_Details[0]['Phone_Number'];?>'></td>
        </tr>

        <tr>
            <td style='text-align: right;' class="data">Consultant</td>
            <td>
                <select name='Consultant_ID' id='Consultant_ID' style="width:100%;padding:5px">
                    <option value="">SELECT CONSULTANT</option>
                    <?php foreach($consultants as $consultant){ echo "<option value ='{$consultant['Employee_ID']}'>{$consultant['Employee_Name']}</option>"; } ?>
                </select>
            </td>
            <td style='text-align: right;' class="data">Transaction Mode</td>
            <td id="Transaction_Area">
                <select id="Transaction_Mode" name="Transaction_Mode" style="width:100%;padding:4px" onchange="Validate_Transaction_Mode()">
                    <?php
                    $select_Transaction_type = mysqli_query($conn, "SELECT Fast_Track from tbl_pharmacy_items_list_cache alc
                                                                where alc.Employee_ID = '$Employee_ID' and
                                                                Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
                    $no_of_items = mysqli_num_rows($select_Transaction_type);
                    if ($no_of_items > 0) {
                        while ($data = mysqli_fetch_array($select_Transaction_type)) {
                            $Fast_Track = $data['Fast_Track'];
                        }
                        if ($Fast_Track == '1') {
                            echo "<option selected='selected'>Fast Track Transaction</option>";
                        } else {
                            echo "<option selected='selected'>Normal Transaction</option>";
                        }
                    } else {
                    ?>
                        <option selected="selected">Normal Transaction</option>
                        <option <?php if (isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction' && $B_Type == 'Outpatient Cash') {
                                    echo "selected='selected'";
                                } ?>>Fast Track Transaction</option>
                    <?php } ?>
                </select>
            </td>
            <td style='text-align: right;' class="data">Member Number</td>
            <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
            <td style="text-align:right" class="data">Select Clinic</td>
            <td>
                <select style='width: 100%;padding:5px' name='Clinic_ID' id='Clinic_ID'>
                    <option selected value="">SELECT CLINIC</option>
                    <?php foreach($clinicList as $clinic){ echo "<option value ='{$clinic['Sub_Department_ID']}'>".$clinic['Sub_Department_Name']."</option>"; } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td style="text-align:right;color:green" id="sponsor_for_this_trans">
                <b><span style="color:red">*</span>SELECT TEMPORARY SPONSOR</b>
            </td>
            <td>
                <select id="new_sponsor_to_bill" style='width:100%;padding:5px'></select>

                <!-- commented -->
                <select id="" style='width:100%;padding:4px;display:none'>
                    <?php
                    $filter_sponsor = "WHERE";
                    $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
                    if ($can_login_to_high_privileges_department == "yes") {
                        $filter_sponsor .= " auto_item_update_api<>'1' AND ";
                    ?>
                        <option value=""></option>
                    <?php
                    } else {
                    ?>
                        <option value="<?= $Sponsor_ID ?>"><?= $Guarantor_Name ?></option>
                    <?php
                    }
                    if ($Billing_Type_filter == "Outpatient Credit") {
                        $filter_sponsor .= " payment_method='credit'";
                    } else {
                        $filter_sponsor .= " payment_method='cash'";
                    }
                    $sql_select_sponsor_result = mysqli_query($conn, "SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor  ") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_sponsor_result) > 0) {
                        while ($sponsor_rows = mysqli_fetch_assoc($sql_select_sponsor_result)) {
                            $Sponsor_ID_ch = $sponsor_rows['Sponsor_ID'];
                            $Guarantor_Name_ch = $sponsor_rows['Guarantor_Name'];
                            //this hardcoded for temporary due to time limit... next version should create setup to control this
                            if ($Guarantor_Name_ch == "Pharmacy Project Cash" || ($Guarantor_Name_ch == "Pharmacy Project Credit" && strtolower($Guarantor_Name) == "nhif")) {
                            } else {
                                if ($Billing_Type_filter == "Outpatient Credit" && strtolower($Guarantor_Name_ch) == strtolower($Guarantor_Name)) {
                                    $selected = "selected='selected'";
                                } else {
                                    $selected = "";
                                }
                            }
                            echo "<option value='$Sponsor_ID_ch' $selected>$Guarantor_Name_ch</option>";
                        }
                    }
                    ?>
                </select>
                <!-- commented -->
            </td>

            <td style="text-align:right">Select Clinic Location</td>
            <td>
                <select style='width: 100%;padding:5px' name='clinic_location_id' id='clinic_location_id' required='required'>
                    <option selected value="">SELECT CLINIC LOCATION</option>
                    <?php foreach($clinicList as $clinic){ echo "<option value ='{$clinic['Sub_Department_ID']}'>".$clinic['Sub_Department_Name']."</option>"; } ?>
                </select>
            </td>
            <td style="text-align:right">Select Working Department</td>
            <td>
                <select id='working_department' name='working_department' style="width:100%;padding:5px">
                    <option selected value="">SELECT WORKING DEPARTMENT</option>
                    <?php foreach($working_departments as $working_department){ echo "<option value ='{$working_department['finance_department_id']}'>".$working_department['finance_department_name']." ~ ".$working_department['finance_department_code']."</option>"; } ?>
                </select>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <table width=100% id="Process_Buttons_Area">
        <tr>
            <td style="text-align: right;">
                <a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" id="add_pharmarcy_items">ADD ITEMS</a>
                <a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" id="dispense_items">DISPENSE ITEMS</a>
                <a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" id="bill_and_dispense_items" onclick="bill_and_dispense_items()">BILL AND DISPENSE ITEM</a>
                <a href="#" class="art-button-green" style="font-family:arial;" id='mobile_cards_transactions' onclick="trial()"> GO TO MOBILE/CARD PAYMENT </a>
                <a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" id="btn_send_to_cashier" onclick='send_patient_to_cashier()'>SEND TO CASHIER</a>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <table style="width: 100%;">
        <thead>
            <tr style="background-color: #ddd;">
                <td width='3%' style="padding: 8px;"><center>S/N</center></td>
                <td style="padding: 8px;text-align:left">ITEM NAME</td>
                <td width='6%' style="padding: 8px;"><center>DOSE</center></td>
                <td width='6%' style="padding: 8px;"><center>DISEASE CODE</center></td>
                <td width='6%' style="padding: 8px;"><center>DOSE QTY</center></td>
                <td width='6%' style="padding: 8px;"><center>DISPENSE QTY</center></td>
                <td width='6%' style="padding: 8px;"><center>DURATION</center></td>
                <td width='6%' style="padding: 8px;"><center>PREV DISPENSE</center></td>
                <td width='6%' style="padding: 8px;"><center>REMAIN QTY</center></td>
                <td width='6%' style="padding: 8px;"><center>BALANCE</center></td>
                <td width='6%' style="padding: 8px;"><center>PRICE</center></td>
                <td width='6%' style="padding: 8px;text-align:center">SUBTOTAL</td>
                <td width='6%' style="padding: 8px;"><center><input type="checkbox" onclick="check_uncheck_checkbox(this.checked);"></center></td>
                <td width='6%' style="padding: 8px;"><center>ACTION</center></td>
            </tr>
        </thead>

        <tbody id="incoming_data" style="background-color: #fff;"></tbody>

        <tbody style="background-color: #ddd;">
            <tr>
                <td style="padding: 8px;" colspan="11"></td>
                <td style="padding: 8px;"><center><input type="text" style="width: 100%;text-align:end;padding:3px" id='total' value="0.0"/></center></td>
                <td colspan="2" style="padding: 8px;"><center></center></td>
            </tr>
        </tbody>
    </table>
</fieldset>

<div id='removed_item_section'></div>

<script>
    $(document).ready(() => {
        $("#pharmacy_items").dialog({
            autoOpen: false,
            width: 950,
            height: 400,
            title: 'ADD PHARMACY ITEMS',
            modal: true
        });
    })
</script>

<script>
    function check_uncheck_checkbox(isChecked) {
        var date_values = $("input[class='total']").map(function() { return $(this).val(); }).get();
        var sum = 0;
        for (var el in date_values) {
            if (date_values.hasOwnProperty(el)) {
                sum += parseFloat(date_values[el]);
            }
        }


        if (isChecked) {
            $('input[type="checkbox"]').each(function() {
                this.checked = true;
                document.getElementById('total').value = sum;
            });
        } else {
            $('input[type="checkbox"]').each(function() {
                this.checked = false;
                document.getElementById('total').value = 0;
            });
        }
    }

    // function to remove items
    function remove_item(Id) {
        if (confirm('Are you want to remove the clicked item')) {
            $.post(
                'remove_items.php', {
                    Id: Id
                }, (response) => {
                    console.log(response);
                    load_current_items();
                }
            );
        }
    }
</script>

<script>
    $(document).ready(() => {
        load_current_items();

        // add pharmacy button clicked 
        $('#add_pharmarcy_items').click(() => {
            var Consultant_ID = document.getElementById("Consultant_ID").value;
            var new_sponsor_to_bill = document.getElementById("new_sponsor_to_bill").value;
            var Clinic_ID = document.getElementById("Clinic_ID").value;
            var clinic_location_id = document.getElementById("clinic_location_id").value;
            var working_department = document.getElementById("working_department").value;

            if (Consultant_ID == '') {
                alert("Please select consultant to continue");
                document.getElementById("Consultant_ID").style = 'border-color:red;width:100%;padding:4px;border-radius:2px';
            } else if (new_sponsor_to_bill == '') {
                alert("Please select temporary sponsor for this transaction");
                document.getElementById("new_sponsor_to_bill").style = 'border-color:red;width:100%;padding:4px;border-radius:2px';
            } else if (Clinic_ID == '' || Clinic_ID == null) {
                alert("Select clinic to continue")
            } else if (clinic_location_id == '' || clinic_location_id == null) {
                alert("Select clinic location to continue")
            } else if (working_department == '' || working_department == null) {
                alert("Select Department to continue");
            } else {
                var Transaction_Title = document.getElementById("Transaction_Mode").value;
                document.getElementById("Transaction_Mode_Area").innerHTML = '<span style="color: #037CB0;"><h4>TRANSACTION MODE : <b>' + Transaction_Title + '</b></h4></span>';
                $("#pharmacy_items").dialog("open");
            }
        });



        //dispense items
        $('#dispense_items').click(() => {
            var checked_item = $(".item").is(':checked');
            var Payment_Cache_ID = $('#Payment_Cache').val();
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Transaction_Type = 'Cash';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var Check_In_Type = "Pharmacy";

            if (checked_item) {
                var selectedItem = [];
                $(".item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dspQty" + Idd;
                    var id_for_dispense = "dispensed_qty" + Idd;
                    var id_for_dose_duration = "item_duration" + Idd;
                    selectedItem.push({
                        id: Idd,
                        doseqty: $('#' + id_for_dose_qty).val(),
                        dispensedqty: $('#' + id_for_dispense).val(),
                        dose_duration: $('#' + id_for_dose_duration).val()
                    });
                });
                if (confirm("You are dispensing selected items.")) {
                    $.ajax({
                        url: 'new_medication_pharmacy.php',
                        type: 'GET',
                        data: {
                            Payment_Cache_ID: Payment_Cache_ID,
                            Transaction_Type: Transaction_Type,
                            Sub_Department_Name: Sub_Department_Name,
                            Registration_ID: Registration_ID,
                            Check_In_Type: Check_In_Type,
                            selectedItem: selectedItem
                        },
                        success: (data) => {
                            alert(data);
                            load_current_items();

                        }
                    });
                }
            } else {
                alert('Select Item to dispense')
            }
        })
    });

    // Load current items 
    function load_current_items() {
        var Sponsor_ID = $("#new_sponsor_to_bill").val();
        $.ajax({
            type: 'GET',
            url: 'get_current_pharmacy_items_new.php',
            data: {
                Registration_ID: <?= $_GET['Registration_ID'] ?>,
                Sponsor_ID: Sponsor_ID
            },
            cache: false,
            success: (response) => {
                $('#incoming_data').html(response);
            }
        });
    }

    function view_clicked_item(Payment_Cache_ID, Reg_No) {
        $.post(
            '   .php', {
                Payment_Cache_ID: Payment_Cache_ID,
                Reg_No: Reg_No
            }, (response) => {
                $("#removed_item_section").dialog({
                    autoOpen: false,
                    width: '80%',
                    height: 800,
                    title: 'eHMS 4.0 : Items Removed',
                    modal: true
                });
                $("#removed_item_section").html(response);
                $("#removed_item_section").dialog("open");
            }
        );
    }
</script>


<script>
    function dispensed_qty_function(Id) {
        var dispensed_qty = "dispensed_qty" + Id;
        var get_price = "get_price" + Id;
        var subtotal = "subtotal" + Id;
        var check_if_element_is_checked = $('#' + Id).is(":checked");

        var dispensed_qty = $('#' + dispensed_qty).val();
        var get_price = $('#' + get_price).val();

        if (check_if_element_is_checked == true) {
            $('#' + subtotal).val(get_price * dispensed_qty);
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
        } else {
            $('#' + subtotal).val("0");
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

        $('#total').val(sum);
    }
</script>

<script>
    function bill_and_dispense_items() {
        var Payment_Cache_ID = $('#Payment_Cache').val();
        var Billing_Type = $('#Billing_Type').val();
        var checked_item = $(".item").is(':checked');
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Transaction_Type = 'Credit';
        var Sub_Department_ID = '<?= $Sub_Department_ID; ?>';
        var Check_In_Type = "Pharmacy";

        if (checked_item) {
            var selectedItem = [];
            $(".item:checked").each(function() {
                var Idd = $(this).val();
                var id_for_dose_qty = "dspQty" + Idd;
                var id_for_dispense = "dispensed_qty" + Idd;
                var id_for_dose_duration = "item_duration" + Idd;
                selectedItem.push({
                    id: Idd,
                    doseqty: $('#' + id_for_dose_qty).val(),
                    dispensedqty: $('#' + id_for_dispense).val(),
                    dose_duration: $('#' + id_for_dose_duration).val()
                });
            });

            if (confirm("Are you sure you want to bill and dispense the selected medications")) {
                $.ajax({
                    url: 'new_dispense_credit.php',
                    type: 'GET',
                    data: {
                        Payment_Cache_ID: Payment_Cache_ID,
                        Transaction_Type: Transaction_Type,
                        Registration_ID: Registration_ID,
                        Check_In_Type: Check_In_Type,
                        Sub_Department_ID: Sub_Department_ID,
                        selectedItem: selectedItem,
                        Billing_Type: Billing_Type
                    },
                    success: function(data) {
                        alert("Dispense Successful");
                        load_current_items();
                    }
                });
            }
        } else {
            alert('Select Item to bill and dispense');
        }
    }
</script>

<script type="text/javascript">
    function close_dialog_drug_duration_alert() {
        $("#dossage_feedback_message").dialog("close");
    }

    function varidate_dosage_duration(drug_item_id) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var check = "false";
        after_bill = "after_bill";
        $.ajax({
            type: 'GET',
            url: 'varidate_dosage_duration.php',
            data: {
                drug_item_id: drug_item_id,
                Registration_ID: Registration_ID
            },
            success: (data) => {
                if (data == 1) {

                } else {
                    $("#dossage_feedback_message").dialog({
                        autoOpen: false,
                        width: '75%',
                        height: 450,
                        title: 'PATIENT MEDICINE DOSSAGE DURATION ALERT',
                        modal: true
                    });
                    $("#dossage_feedback_message").dialog("open").html(data);
                }
            },
            error: function() {}
        });
    }
</script>

<script type="text/javascript">
    function Get_Item_Name(Item_Name, Item_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        Sponsor_ID = $("#new_sponsor_to_bill").val();
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
                        Get_Details_Verify(Item_Name, Item_ID);
                    } else {
                        document.getElementById("Price").value = 0;
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Item_Balance").value = '';
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Total").value = '';
                        document.getElementById("Discount").value = '';
                        document.getElementById("Item_Balance").value = '';
                        $("#Non_Supported_Item").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            My_Object_Verify_Item.open('GET', 'Verify_Non_Supported_Item.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID, true);
            My_Object_Verify_Item.send();
        } else {
            Get_Details_Verify(Item_Name, Item_ID);
        }
    }
</script>

<script type="text/javascript">
    function Get_Details_Verify(Item_Name, Item_ID) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObjectVerify.onreadystatechange = function() {
            dataVer = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedbak = dataVer;
                if (feedbak == 'yes') {
                    Get_Details(Item_Name, Item_ID);
                } else {
                    document.getElementById('Quantity').value = '';
                    document.getElementById("Price").value = '';
                    document.getElementById('Dosage').value = '';
                    document.getElementById('Discount').value = '';
                    document.getElementById('Total').value = '';
                    document.getElementById('Item_Balance').value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Item_Name").value = '';
                    $("#Item_Already_Added").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Pharmacy_Get_Details_Verify.php?Item_ID=' + Item_ID + '&Registration_ID=' + Registration_ID, true);
        myObjectVerify.send();
    }
</script>

<script>
    function Get_Item_Price(Item_ID, Sponsor_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Transaction_Mode = document.getElementById("Transaction_Mode").value;
        //alert(Item_ID);
        Sponsor_ID = $("#new_sponsor_to_bill").val();
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObject.onreadystatechange = function() {
            data = myObject.responseText;

            if (myObject.readyState == 4) {
                document.getElementById('Price').value = data;
                //alert(data);
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type + '&Transaction_Mode=' + Transaction_Mode, true);
        myObject.send();
    }
</script>

<script>
    function Get_Details(Item_Name, Item_ID) {
        document.getElementById('Quantity').value = '';
        document.getElementById('Dosage').value = '';
        document.getElementById('Item_Balance').value = '';
        document.getElementById('Discount').value = '';
        document.getElementById('Total').value = '';
        var Temp = '';
        if (window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        Get_Item_Balance(Item_ID);
    }
</script>

<script type="text/javascript">
    function Get_Item_Balance(Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectGetBalance = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetBalance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetBalance.overrideMimeType('text/xml');
        }
        myObjectGetBalance.onreadystatechange = function() {
            Data_Balance = myObjectGetBalance.responseText;
            if (myObjectGetBalance.readyState == 4) {
                document.getElementById('Item_Balance').value = Data_Balance;
            }
        }; //specify name of function that will handle server response........
        myObjectGetBalance.open('GET', 'Pharmacy_Get_Item_Balance.php?Item_ID=' + Item_ID, true);
        myObjectGetBalance.send();
    }
</script>

<script type="text/javascript">
    function Calculate_Total() {
        var Price = document.getElementById("Price").value;
        var Discount = document.getElementById("Discount").value;
        var Quantity = document.getElementById("Quantity").value;

        if (Price != null && Price != '' && Quantity != null && Quantity != '') {
            if (window.XMLHttpRequest) {
                My_Object_Calc = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                My_Object_Calc = new ActiveXObject('Micrsoft.XMLHTTP');
                My_Object_Calc.overrideMimeType('text/xml');
            }
            My_Object_Calc.onreadystatechange = function() {
                dataCalc = My_Object_Calc.responseText;
                if (My_Object_Calc.readyState == 4) {
                    document.getElementById("Total").value = dataCalc;
                }
            }; //specify name of function that will handle server response........
            My_Object_Calc.open('GET', 'Pharmacy_Calculate_Total.php?&Price=' + Price + '&Discount=' + Discount + '&Quantity=' + Quantity, true);
            My_Object_Calc.send();
        }
    }
</script>

<script type="text/javascript">
    function Verify_Balance() {
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;

        if (window.XMLHttpRequest) {
            myObjectVerifyBalance = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerifyBalance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerifyBalance.overrideMimeType('text/xml');
        }

        myObjectVerifyBalance.onreadystatechange = function() {
            dataVerBalance = myObjectVerifyBalance.responseText;
            if (myObjectVerifyBalance.readyState == 4) {
                var feedback = dataVerBalance;
                if (feedback == 'yes') {
                    Get_Selected_Item();
                } else if (feedback == 'no') {
                    alert("Balance is less than quantity");
                } else {
                    if (Quantity != null && Quantity != '') {
                        $("#Balance_Error").dialog("open");
                    }
                }
            }

        }; //specify name of function that will handle server response........
        myObjectVerifyBalance.open('GET', 'Pharmacy_Verify_Balance.php?Quantity=' + Quantity + '&Item_ID=' + Item_ID, true);
        myObjectVerifyBalance.send();
    }
</script>

<script>
    function getItemsListFiltered(Item_Name) {
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Dosage").value = '';
        document.getElementById("Quantity").value = '';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        Sponsor_ID = $("#new_sponsor_to_bill").val();
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
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data135;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Pharmacy_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
        myObject.send();
    }
</script>

<!-- Pharmacy Items -->
<div id="pharmacy_items">
    <div id="sec" style="display: grid;grid-template-columns:.8fr 1fr;gap:.5em">
        <div>
            <table style="width: 100%;background-color:#fff" border="none" id="items">
                <tr>
                    <td>
                        <span style="font-size: 15px;">Category</span>
                        <select name='Item_Category_ID' id='Item_Category_ID' style="width: 82%;" onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                            <option selected='selected'></option>
                            <?php
                            $data = mysqli_query($conn, "SELECT Item_Category_Name, Item_Category_ID
                                                                FROM tbl_item_category WHERE Category_Type = 'Pharmacy'
                                                                ") or die(mysqli_error($conn));
                            while ($row = mysqli_fetch_array($data)) {
                                echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                            <table width=100% border="none">
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID where ip.Sponsor_ID='$Sponsor_ID' AND Item_Type = 'Pharmacy' and Status = 'Available' order by Product_Name limit 150");
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>"; ?>
                                    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="varidate_dosage_duration('<?php echo $row['Item_ID']; ?>');Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                    
                                    Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Sponsor_ID; ?>');">
                                <?php
                                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                                }
                                ?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table width=100% border=0>
                <tr>
                    <td colspan="2" id="Transaction_Mode_Area" style="text-align: center;padding:1em"></td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em' width=30%>Item Name</td>
                    <td>
                        <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
                        <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'>Item Price</td>
                    <td><input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'></td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'>Price Discount</td>
                    <td>
                        <input type='text' name='Discount' id='Discount' placeholder='Discount' value="0" onkeypress="numberOnly(this);
                        Calculate_Total();" oninput="numberOnly(this); Calculate_Total();" onkeyup="numberOnly(this);Calculate_Total();">
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'>Item Balance</td>
                    <td>
                        <input type='text' name='Item_Balance' id='Item_Balance' readonly='readonly' placeholder='Item Balance'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'>Quantity</td>
                    <td>
                        <input type='text' name='Quantity' id='Quantity' autocomplete='off' placeholder='Quantity' onkeypress="numberOnly(this);
                                    Calculate_Total();" oninput="numberOnly(this); Calculate_Total();" onkeyup="numberOnly(this);
                                            Calculate_Total();">
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'>Total</td>
                    <td>
                        <input type='text' name='Total' id='Total' readonly='readonly' placeholder='Total' value="0">
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;padding:.5em'></td>
                    <td colspan=2>
                        <textarea name='Dosage' rows='1' id='Dosage' placeholder='Dosage'></textarea>
                    </td>
                </tr>
                <tr style="padding:.5em">
                    <td></td>
                    <td colspan=2 style="padding:.5em">
                        <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEMS' onclick='Verify_Balance()'>
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            </table>
        </div>
    </div>
</div>
<!-- Pharmacy Items -->

<script>
    function Get_Selected_Item() {
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Item_Name = document.getElementById("Item_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Discount = document.getElementById("Discount").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Dosage = document.getElementById("Dosage").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        Guarantor_Name = $("#new_sponsor_to_bill").text();
        var Consultant_ID = document.getElementById("Consultant_ID").value;
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Transaction_Mode = document.getElementById("Transaction_Mode").value;
        var Clinic_ID = document.getElementById("Clinic_ID").value;
        var clinic_location_id = document.getElementById("clinic_location_id").value;
        var working_department = document.getElementById("working_department").value;
        var Price = document.getElementById("Price").value;

        var Sponsor_ID = document.getElementById('new_sponsor_to_bill').value;
        var Item_Balance = document.getElementById('Item_Balance').value;
        var sub_department_id = '<?= $Sub_Department_ID ?>';

        var blc = Item_Balance;
        var check_dose_string = /[a-z]+$/;


        if (parseInt(Price) == 0) {
            alert('Items is missing price.')
        } else {
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").focus();
                document.getElementById("Quantity").style = 'border: 2px solid red';
            } else if (Dosage == '' || Dosage == null) {
                document.getElementById("Dosage").focus();
                document.getElementById("Dosage").style = 'border: 2px solid red';
            } else {
                $.ajax({
                    type: 'GET',
                    url: 'add_pharmacy_items_other.php',
                    data: {
                        Registration_ID: Registration_ID,
                        Item_ID: Item_ID,
                        Price: Price,
                        Quantity: Quantity,
                        Consultant_ID: Consultant_ID,
                        Item_Name: Item_Name,
                        Billing_Type: Billing_Type,
                        Guarantor_Name: Guarantor_Name,
                        Sponsor_ID: Sponsor_ID,
                        Claim_Form_Number: Claim_Form_Number,
                        Dosage: Dosage,
                        Discount: Discount,
                        Transaction_Mode: Transaction_Mode,
                        Clinic_ID: Clinic_ID,
                        working_department: working_department,
                        clinic_location_id: clinic_location_id,
                        $Brand_Id : <?=$_SESSION['userinfo']['Branch_ID']?>,
                        $Employee_ID : <?=$_SESSION['userinfo']['Employee_ID']?>
                    },
                    success: (response) => {
                        load_current_items();
                        clearContent();
                    }
                });
            }
        }
    }
</script>

<script>
    function clearContent() {
        document.getElementById("Quantity").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Price").value = '';
        document.getElementById("Dosage").value = '';
        document.getElementById("Discount").value = '';
        document.getElementById("Total").value = '';
        document.getElementById("Item_Balance").value = '';
        document.getElementById("Search_Value").value = '';
    }
</script>

<script>
    //check inputs
    function check_dispensed_qty(Id) {
        var filled_dose = 'dispensed_qty' + Id;
        var exact_dose = 'dspQty' + Id;
        var subtotal = 'subtotal' + Id;
        var dsp_price = 'get_price' + Id;

        var dspQty = $('#' + filled_dose).val();
        var exact = $('#' + exact_dose).val();
        var get_price = $('#' + dsp_price).val();
        var sum = 0;

        $('#' + subtotal).val(dspQty * get_price);
        var date_values = $("input[class='total']")
            .map(function() {
                return $(this).val();
            }).get();

        for (var el in date_values) {
            if (date_values.hasOwnProperty(el)) {
                sum += parseFloat(date_values[el]);
            }
        }

        perfom_edit(Id, dspQty);

        document.getElementById('total').value = sum + " /=";

        if (parseInt(dspQty) > parseInt(exact)) {
            alert('Sorry dispensed can be greater than dose quantity.');
            $('#' + filled_dose).css('background-color', 'yellow');
        } else {
            $('#' + filled_dose).css('background-color', 'white');
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
            }, (response) => {
                console.log(response)
            }
        );
    }

    function send_patient_to_cashier() {
        var Payment_Cache_ID = document.getElementById('Payment_Cache').value;
        var checked_item = $(".item").is(':checked');
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Transaction_Type = 'Cash';
        var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
        var Check_In_Type = "Pharmacy";
        var sub_department_id = '<?= $_SESSION['Pharmacy_ID']; ?>';

        if (checked_item) {
            var selectedItem = [];
            $(".item:checked").each(function() {
                var Idd = $(this).val();
                var id_for_dose_qty = "dspQty" + Idd;
                var id_for_dispense = "dispensed_qty" + Idd;
                selectedItem.push({
                    id: Idd,
                    doseqty: $('#' + id_for_dose_qty).val(),
                    dispensedqty: $('#' + id_for_dispense).val()
                });
            });
            console.clear();
            console.log(selectedItem);
            console.log(Payment_Cache_ID);
            if (confirm("Send This patient to Cashier")) {
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
                        alert(data);
                        load_current_items();
                    }
                });
            }
        } else {
            alert('No item selected');
        }
    }
</script>


<!-- button control -->
<script>
    $(document).ready(() => {
        var Billing_Type = $('#Billing_Type').val();
        if (Billing_Type == 'Outpatient Cash') {
            $('#bill_and_dispense_items').css('display', 'none');
        } else {
            $('#btn_send_to_cashier').css('display', 'none');
        }
    });

    function check_sponsor(selected_object) {
        var value = selected_object.value;
        $.ajax({
            type: 'GET',
            url: "cash_sponsor.php",
            data: {
                value: value
            },
            cache: false,
            success: function(response) {
                $('#new_sponsor_to_bill').html(response);
            }
        });
        if (value == 'Outpatient Cash') {
            $('#bill_and_dispense_items').css('display', 'none');
            $('#btn_send_to_cashier').css('display', '');
        } else {
            $('#btn_send_to_cashier').css('display', 'none');
            $('#bill_and_dispense_items').css('display', '');
        }
    }
</script>
<!-- button control -->

<script>
    $(document).ready(() => {
        var value = $('#Billing_Type').val();
        $.ajax({
            type: 'GET',
            url: "cash_sponsor.php",
            data: {
                value: value
            },
            cache: false,
            success: function(response) {
                $('#new_sponsor_to_bill').html(response);
            }
        });

    });
</script>

<script>
    function trial() {
        if (confirm('Are you sure you want to go to Mobile/Card Payment')) {
            var Payment_Cache_ID = document.getElementById('Payment_Cache').value;
            var checked_item = $(".item").is(':checked');
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Transaction_Type = 'Cash';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var Check_In_Type = "Pharmacy";
            var sub_department_id = '<?= $_SESSION['Pharmacy_ID'];?>';

            if (checked_item) {
                var selectedItem = [];
                $(".item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dspQty" + Idd;
                    var id_for_dispense = "dispensed_qty" + Idd;
                    selectedItem.push({
                        id: Idd,
                        doseqty: $('#' + id_for_dose_qty).val(),
                        dispensedqty: $('#' + id_for_dispense).val()
                    });
                });
                console.clear();
                console.log(selectedItem);
                console.log(Payment_Cache_ID);
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
                            location.href = "patient_sent_to_cashier_payment.php?Payment_Cache_ID=" + Payment_Cache_ID + "&Registration_ID=" + Registration_ID;
                        }
                    });
                }
            } else {
                alert('No item selected');
            }
        }
    }
</script>

<!-- <script>
    function trial(){
        if(confirm('Are you sure you want to go to Mobile/Card Payment')){
            var Payment_Cache_ID = document.getElementById('Payment_Cache').value;
            var checked_item = $(".item").is(':checked');
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Transaction_Type = 'Cash';
            var Sub_Department_Name = '<?php echo $Sub_Department_Name; ?>';
            var Check_In_Type = "Pharmacy";


            if (checked_item) {
                var selectedItem = [];
                $(".item:checked").each(function() {
                    var Idd = $(this).val();
                    var id_for_dose_qty = "dspQty" + Idd;
                    var id_for_dispense = "dispensed_qty" + Idd;
                    selectedItem.push({
                        id: Idd,
                        doseqty: $('#' + id_for_dose_qty).val(),
                        dispensedqty: $('#' + id_for_dispense).val()
                    });
                });
                console.clear();
                console.log(selectedItem);
                console.log(Payment_Cache_ID);
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
                            selectedItem: selectedItem
                        },
                        success: (data) => {
                            location.href = "patient_sent_to_cashier_payment.php?Payment_Cache_ID="+<?= $Payment_Cache_ID ?>+"&Registration_ID="+<?= $Registration_ID ?>;
                        }
                    });
                }
            }else{
                alert('No item selected');
            }
        }
    }
</script> -->

<?php include './includes/footer.php'; ?>