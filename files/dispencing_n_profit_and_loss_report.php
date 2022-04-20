<?php
include("./includes/header.php");
include("calculate_buying_price.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['from']) &&  $_SESSION['from']=="ebill"){
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
if (isset($_GET['Section']) && $_GET['Section'] == 'managementworkspage') {

    $_SESSION['Section_managementworkspage'] = true;
}
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href="generalledgercenter.php" class="art-button-green">BACK</a>

<br/><br/>
<style>
    table,tr,td,th,tbody{
        border:none!important;
    }
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF;
    }
    .rows_list:hover{
        background: #CCCCCC;
    }
</style>
<fieldset>
    <legend align='center'><b>DISPENSING AND PROFIT AND LOSS REPORT</b></legend>
    <center>
        <table>
            <tr>
                <td>
                    <input type="text" placeholder="Start Date" id='start_date' style="text-align: center" value="<?= $Start_Date ?>"/>
                </td>
                <td>
                    <input type="text" placeholder="End Date" id='end_date' style="text-align: center" value="<?= $End_Date ?>"/>
                </td>
                <td>
                    <label>Report Type</label>
                </td>
                <td>
                    <select id="report_type">
                        <option>All</option>
                        <option>Cash</option>
                        <option>Credit</option>
                        <!--<option>Msamaha</option>-->
                    </select>
                </td>
                <td>
                    <input type="button" value="FILTER" onclick="Filter_Dispense_List()" class="art-button-green"/>
                </td>
            </tr>
        </table>
    </center>
    <div style="height: 350px;overflow-y: scroll;overflow-x: hidden">
        <table class="table" style="background: #FFFFFF">
            <tr>
                <td colspan="8"><hr/></td>
            </tr>
            <tr>
                <th style='text-align:left;width:5%'>S/No.</th>
                <th style='text-align:left'>Dispensing Location Name</th>
                <th style='text-align:right'>Total Dispensed Quantity</th>
                <th style='text-align:right'>Total Stock Balance</th>
                <th style='text-align:right'>Total Buying Price</th>
                <th style='text-align:right'>Total Selling Price</th>
                <th style='text-align:right'>Profit/Loss</th>
                <th style='text-align:right'>total Stock Value</th>
            </tr>
            <tr>
                <td colspan="8"><hr/></td>
            </tr>
            <tbody id='list_of_all_dispensing_unit'>
               
            </tbody>
        </table>
    </div>
</fieldset>
<fieldset>
    <input type="button" value="PREVIEW" class="art-button-green pull-right" onclick="preview_dispensing_n_profit_report()"/>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    function preview_dispensing_n_profit_report(){
        var Start_Date = document.getElementById("start_date").value;
	var End_Date = document.getElementById("end_date").value;
	var report_type = document.getElementById("report_type").value;
        if(Start_Date != null && Start_Date != '' &&  End_Date != null && End_Date != ''){
            document.getElementById("start_date").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("end_date").style = 'border: 2px solid black; text-align: center;';
            window.open("preview_dispensing_n_profit_report.php?&Start_Date="+Start_Date+"&End_Date="+End_Date+"&report_type="+report_type,"_blank");
        }else{
           document.getElementById("start_date").style = 'border: 2px solid red; text-align: center;';
            document.getElementById("end_date").style = 'border: 2px solid red; text-align: center;'; 
        }
    }
    function Filter_Dispense_List(){
        var Start_Date = document.getElementById("start_date").value;
	var End_Date = document.getElementById("end_date").value;
	var report_type = document.getElementById("report_type").value;
        document.getElementById('list_of_all_dispensing_unit').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'GET',
            url:'get_filtered_dispencing_n_profit_and_loss_report.php',
            data:{Start_Date:Start_Date,End_Date:End_Date,report_type:report_type},
            success:function(data){
                $("#list_of_all_dispensing_unit").html(data)
            }
        });
    }
    function load_selected_dispencing_unit(Sub_Department_ID){
        var Start_Date = document.getElementById("start_date").value;
	var End_Date = document.getElementById("end_date").value;
        var report_type = document.getElementById("report_type").value;
        document.location="selected_dispensing_point_for_profit_n_loss.php?Sub_Department_ID="+Sub_Department_ID+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&report_type="+report_type;
    }
 $(document).ready(function () {
        $('select').select2({
        	'width': '250',
        });
        Filter_Dispense_List();
    })
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#start_date').datetimepicker({value: '', step: 1});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#end_date').datetimepicker({value: '', step: 1});
</script>

<?php
include("./includes/footer.php");
?>

