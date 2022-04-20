<?php
include "./includes/header.php";
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['from']) && $_SESSION['from'] == "ebill") {
    unset($_SESSION['from']);
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;
?>
<a href='generalledgercenter.php' class='art-button-green'>
    BACK
</a>
<br />
<style>
.rows_list {
    cursor: pointer;
}

.rows_list:active {
    color: #328CAF !important;
    font-weight: normal !important;
}

.rows_list:hover {
    color: #00416a;
    background: #CCCCCC;
    font-weight: bold;
}
</style>
<br>
<br>
<fieldset>
    <legend align=center><b>ACCOUNTS CONFIGURATION</b></legend>
    <center>
        <table>
            <tr>
                <td colspan="2"><select name="configurationtype" id="configurationtype"
                        onchange="filter_revenue_weekly_collection_new()">
                        <option value="1">Income Ledger Configuration</option>
                        <option value="2">Inventory Ledger Configuration</option>
                        <option value="3">Supplier</option>
                        <option value="4">Sponsor</option>
                        <option value="5">Banck Ledger</option>
                    </select></td>
                <td colspan="2"><input type="text" class="form-control" placeholder="Enter Category Name"
                        style="background:#FFFFFF!important" id="category_id"
                        onkeyup="filter_revenue_weekly_collection_new()"></td>
            </tr>

        </table>
    </center>
    <div id='report_body_new'>

    </div>

    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({
        value: '',
        step: 01
    });
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({
        value: '',
        step: 01
    });
    </script>
    <script>
    function filter_revenue_weekly_collection_new() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var category_id = $('#category_id').val();
        var configurationtype = $('#configurationtype').val();
        document.getElementById('report_body_new').innerHTML =
            '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: 'POST',
            url: 'ajax_accounts_configuration.php',
            data: {
                category_id: category_id,
                configurationtype: configurationtype
            },
            success: function(data) {
                $("#report_body_new").html(data);
            }
        });
    }
    $(document).ready(function() {
        filter_revenue_weekly_collection_new();
    })

    function update_account_ledger(obj, id) {
        var configurationtype = $('#configurationtype').val();
        $.ajax({
            type: 'POST',
            url: 'ajax_ledger_update.php',
            data: {
                category_id: id,
                configurationtype: configurationtype,
                ledger_id: obj.value
            },
            success: function(data) {
                // $("#report_body_new").html(data);
            }
        });
    }

    function update_expense_ledger(obj, id) {
        var configurationtype = $('#configurationtype').val();
        $.ajax({
            type: 'POST',
            url: 'ajax_ledger_update.php',
            data: {
                category_id: id,
                exp_ledger_id: obj.value,
                expense:"expense"
            },
            success: function(data) {
                // $("#report_body_new").html(data);
            }
        });
    }
    </script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <?php
include "./includes/footer.php";
?>