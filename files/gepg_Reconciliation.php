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
    <legend align=center><b>GePG Reconciliation</b></legend>
   <center> 
        <table>
            <tr>
            <td>
                <input type="text" name="date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" autocomplete="off" style="text-align: center;" readonly="readonly"></td>
                <td><select id='option_ID' class="select2-default" style='text-align: center;width:100px;display:inline'>
                        <option selected value="1">1</option>
                        <option selected value="2">2</option>
                    </select></td>
                <td><input type='button' value='Request Reconciliation' class='art-button-green' onclick='request_reconciliation()'/></td>
                <td></td>
                <td><input type="tex" class="form-control" placeholder="Start Date" style="background:#FFFFFF!important" value="<?= $Start_Date ?>" readonly="readonly"id="start_date"></td>
                <td><input type="tex" class="form-control" placeholder="End Date" style="background:#FFFFFF!important" value="<?= $End_Date ?>"readonly="readonly" id="end_date"></td>
                <td><input type='button' value='FILTER' class='art-button-green' onclick='filter_Reconsiliation_Request()'/></td>
            </tr>
        </table>
   </center>
    <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-hover">
            <tr>
                <td width="5%"><b>S/No.</b></td>
                <td>
                    <b>Patient Name</b>
                </td>
                <td>
                    <b>Patient Number</b>
                </td>
                <td>
                    <b>Reconciliation ID</b>
                </td>
                <td>
                    <b>Bill ID</b>
                </td>
                <td>
                    <b>Bill Control Number</b>
                </td>
                <td>
                    <b>PayRefID</b>
                </td>
                <td>
                    <b>Paid Amount</b>
                </td>
                <td>
                    <b>CCy</b>
                </td>
                <td>
                    <b>Acount Number</b>
                </td>
                <td>
                    <b>Pay Channel</b>
                </td>
                <td>
                    <b>Transaction Date</b>
                </td>
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
    $('#rec_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#rec_date').datetimepicker({value: '', step: 01});
</script>
<script>
    function filter_Reconsiliation_Request(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_filter_gepg_reconciliation.php',
           data:{start_date:start_date,end_date:end_date},
           success:function(data){
               $("#report_body").html(data); 
           }
       });
    }

    function request_reconciliation(){
       var rec_date= $('#date').val();
       var option_ID= $('#option_ID').val();
       document.getElementById('report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'gepg_processing_payfolder/bill/gepgReconciliation.php',
           data:{TnxDt:rec_date,ReconcOpt:option_ID},
           success:function(data){
                $.ajax({
                    type:'POST',
                    url:'ajax_filter_gepg_reconciliation.php',
                    data:{start_date:rec_date,end_date:rec_date},
                    success:function(data){
                        $("#report_body").html(data); 
                    }
                });
            //     alert(data);
            //    if (data === "7241"){
            //        alert("Successfull Reconciliation Requested");
            //        $("#report_body").html(data);
            //    }else{
            //      alert("Fail To Requested Reconciliation");
            //    } 
           }
       });
    }
     $(document).ready(function () {
        filter_departmental_collection();
    })
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
