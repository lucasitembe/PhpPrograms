<?php
include("./includes/header.php");
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
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href='generalledgercenter.php' class='art-button-green'>
    BACK
</a>
<br/>
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
        background: #CCCCCC;
        font-weight:bold;
    }
</style>
<fieldset>  
    <legend align=center><b>Daily/WEEKLY COLLECTIONS</b></legend>
   <center> 
        <table>
            <tr>
                <td><input type="text" class="form-control" placeholder="Start Date" style="background:#FFFFFF!important" value="<?= $Start_Date ?>" readonly="readonly"id="start_date"></td>
                <td><input type="text" class="form-control" placeholder="End Date" style="background:#FFFFFF!important" value="<?= $End_Date ?>"readonly="readonly" id="end_date"></td>
                <td>
                    <select id="report_type">
                        <option value="daily_report">Daily Report</option>
                        <option value="weekly_report">Weekly Report</option>
                    </select>
                </td>
                <td><input type='button' value='FILTER' class='art-button-green' onclick='filter_revenue_weekly_collection()'/></td>
                <td><input type='button' name='Preview_Button' id='Preview_Button' class='art-button-green' value='PREVIEW' onclick="Preview_Report()"></td>
            </tr>
        </table>
   </center>
    <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-hover" >
            <tr>
                <td width="5%" rowspan="2"><b>S/No.</b></td>
                <td rowspan="2">
                    <b>TRANSACTION TIME</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>PHARMACY</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>LABORATORY</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>RADIOLOGY</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>SURGERY</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>PROCEDURE</b>
                </td>
                <td colspan="3" style="text-align:center">
                    <b>CONSULTATION</b>
                </td>
            </tr>
            <tr>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
                <td>CASH</td>
                <td>CREDIT</td>
                <td>TOTAL</td>
            </tr>
            <tbody id='report_body'>
            
            </tbody>
        </table>
    </div>
 </fieldset> 
<div id="selected_amount_div"></div>
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
    function filter_revenue_weekly_collection(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       var report_type= $('#report_type').val();
       document.getElementById('report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_filter_revenue_weekly_collection.php',
           data:{start_date:start_date,end_date:end_date,report_type:report_type},
           success:function(data){
               $("#report_body").html(data); 
           }
       });
    }
     $(document).ready(function () {
        filter_revenue_weekly_collection();
    })
    
    function Preview_Report(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       var report_type= $('#report_type').val();
       window.open("revenue_weekly_collection_report_pdf.php?start_date="+start_date+"&end_date="+end_date+"&report_type="+report_type,"_blank");
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>