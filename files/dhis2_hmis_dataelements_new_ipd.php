<?php
include("./includes/connection.php");
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get today's date
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;

if (isset($_GET['dhis2_dataset_name'])) {
    $dhis2_dataset_name = $_GET['dhis2_dataset_name'];
} else {
    $dhis2_dataset_name = "";
}
if (isset($_GET['dataset_id'])) {
    $dataset_id = $_GET['dataset_id'];
} else {
    $dataset_id = "";
}
if (isset($_GET['dhis2_auto_dataset_id'])) {
    $dhis2_auto_dataset_id = $_GET['dhis2_auto_dataset_id'];
} else {
    $dhis2_auto_dataset_id = "";
}
?>
<a href="dhis2_report_configuration_ipd.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class="art-button-green">REPORT CONFIGURATION</a>
<a href="dhis2_api.php" class="art-button-green">BACK</a>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;

    }
    a{
        text-decoration: none;
    }
</style>
<fieldset>
    <legend align='center'><b><?= $dhis2_dataset_name; ?></b></legend>
    <center>
        <table style="width:100%;">
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td><input type="button" value="FILTER" onclick="filter_list_of_patient_sent_to_cashier()" class="art-button-green"/></td>
                <!--<td><input type="button" value="Card/Mobile CONFIRM PAYMENT" onclick="confirm_mobile_payment()" class="art-button-green"/></td>-->
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 600px;overflow-y: scroll;overflow-x: scroll">
        <table class="table">
            <tr>
                <td style="width:50px"><b>S/N</b></td>
                <td><b>Ward Name</b></td>
                <td><b>Number of Beds</b></td>
                <td><b>Admissions</b></td>
                <td><b>Discharges Lives</b></td>
                <td><b>Deaths</b></td>
                <td><b>Inpatients/occupied Beds Days (OBD)</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl_ipd'>

            </tbody>
        </table>
    </div>
    <center>
        <table>
            <tr>
                <td><div id="send_to_dhis2_serverprogressive_div"></div></td>
                <td>Organization Unit</td>
                <td> <?php
                    $organization_unit_id = 0;
                    $sql_select_org_unit_result = mysqli_query($conn, "SELECT orgUnit,organization_unit_id FROM tbl_organization_unit") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_org_unit_result) > 0) {

                        $rows = mysqli_fetch_assoc($sql_select_org_unit_result);
                        $organization_unit_id = $rows['organization_unit_id'];
                        $orgUnit = $rows['orgUnit'];
                    }
                    ?>
                    <input type="text" id="orgUnit" class="form-control" onkeyup="update_organization_unit(<?= $organization_unit_id ?>)" placeholder="Enter Organization Unit" value="<?= $orgUnit ?>"/></td>
                <td>Complete Date</td>
                <td><input type="text" readonly="readonly" id="complete_date" style="background: #FFFFFF"placeholder="Enter Complete Date" class="form-control date"/></td>
                <td>Period</td>
                <td><input type="text" class="form-control" id="period_year" style="background: #FFFFFF" placeholder="Enter Period year"/></td>
                <td><select class="form-control" id="period_type">
                        <option value="">Select Period type</option>
                        <option value="P1D">Daily</option>
                        <option value="P7D">Weekly</option>
                        <option value="01">Monthly-1</option>
                        <option value="02">Monthly-2</option>
                        <option value="03">Monthly-3</option>
                        <option value="04">Monthly-4</option>
                        <option value="05">Monthly-5</option>
                        <option value="06">Monthly-6</option>
                        <option value="07">Monthly-7</option>
                        <option value="08">Monthly-8</option>
                        <option value="09">Monthly-9</option>
                        <option value="10">Monthly-10</option>
                        <option value="11">Monthly-11</option>
                        <option value="12">Monthly-12</option>
                        <option value="Q1">Quarterly</option>
                        <option value="S1">Six-monthly</option>
                        <option value="AprilS1">Six-month April</option>
                        <option value="">Yearly</option>
                        <option value="Oct">Financial October</option>
                        <option value="April">Financial April</option>
                        <option value="July">Financial July</option>
                    </select></td>
                <td>Username</td>
                <td><input type="text" placeholder="Enter Username" id="username" class="form-control"/></td>
                <td>Password</td>
                <td><input type="password" class="form-control" id="password" placeholder="Enter Password"/></td>
                <td><input type='button' value='APPROVE SUBMITION' onclick="verify_user_privileges()" class="art-button-green pull-right"/></td>
            </tr>
        </table>
    </center>
</fieldset>
<div id="dhis2_feedback"></div>
<div id="confirm_dataelement_value_dialog" style="background: #CCCCDD;display: none">
    <div id='confirm_data_element_value_content_area' style="background: #FFFFFF;height: 95%;width: 100%;margin-bottom: 4px"> </div>
    <input type="button" value="CONFIRMED" class="art-button-green pull-right" onclick="close_this_dialog('confirm_dataelement_value_dialog')"/>
</div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
        $('#start_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#start_date').datetimepicker({value: '', step: 01});
        $('#end_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#end_date').datetimepicker({value: '', step: 01});
</script>
<script>
    function verify_user_privileges() {
        var username = $("#username").val();
        var password = $("#password").val();
        var validate = 0;
        if (password == "") {
            $("#password").css("border", "2px solid red");
            validate++;
        } else {
            $("#password").css("border", "");
        }
        if (username == "") {
            $("#username").css("border", "2px solid red");
            validate++;
        } else {
            $("#username").css("border", "");
        }
        if (validate <= 0) {
            $.ajax({
                type: 'POST',
                url: 'verify_user_privileges_for_sending_dhis2.php',
                data: {username: username, password: password},
                success: function (data) {
                    console.log(data);
                    if (data == 'access_granted') {
                       send_data_to_dhis2();
                    } else {
                        alert("Invalid Username or Password or you do not have enough privilage to send data to DHIS2 Server");
                    }
                }
            });
        }
    }

    function confirm_mobile_payment() {
        $.ajax({
            type: 'POST',
            url: 'mobile_processing_payfolder/ajax_confirm_mobile_payment.php',
            data: {data_send: "data_send"},
            success: function (data) {
                console.log(data)
            }
        });
    }
    function open_selected_patient(Payment_Cache_ID, Registration_ID) {
        window.location = "patient_sent_to_cashier_payment.php?Payment_Cache_ID=" + Payment_Cache_ID + "&Registration_ID=" + Registration_ID;
    }
    function filter_list_of_patient_sent_to_cashier() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        document.getElementById('patient_sent_to_cashier_tbl_ipd').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: 'POST',
            url: 'ajax_dhis2_hmis_dataelements_new_ipd.php',
            data: {start_date: start_date, end_date: end_date},
            success: function (data) {
                $("#patient_sent_to_cashier_tbl_ipd").html(data);
            }
        });
    }

    function send_data_to_dhis2() {
        var period_year = $("#period_year").val();
        var period_type = $("#period_type").val();
        var complete_date = $("#complete_date").val();

        var orgUnit = $("#orgUnit").val();
        var dataset_id = '<?= $dataset_id ?>';
        document.getElementById('send_to_dhis2_serverprogressive_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: 'POST',
            url: 'send_data_to_dhis2_server_ipd.php',
            data: {period_year: period_year, period_type: period_type, complete_date: complete_date, dataset_id: dataset_id, orgUnit: orgUnit},
            success: function (data) {
                 console.log(data);
                $("#dhis2_feedback").html(data);
                $("#send_to_dhis2_serverprogressive_div").html("");
                $("#dhis2_feedback").dialog({
                    title: 'DHIS2 FEEDBACK MESSAGE',
                    width: '60%',
                    height: 350,
                    modal: true,
                });
            }
        });
    }

    $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
