<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
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
<a href="new_payment_method.php" class="art-button-green">BACK</a>
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
<br/>
<fieldset>
    <legend align='center'><b>PATIENT PAYMENT RECEIPT CENTER / RECONCILIATION REPORT</b></legend>
    <center>
        <table>
            <tr>
                <td>Transaction Mode</td>
                <td>
                    <select style="height:30px" id="transaction_mode" onchange="filter_patient_payment_receipt()">
                        <option value="all_transaction_mode">All Transaction  Mode</option>
                        <option value="'Mobile Online','GEPG Online'">Mobile/GEPG</option>
                        <option value="'Afya Card Online'">Afya Card</option>
<!--                    <option value="manual">manual</option>
                        <option value="offline">offline</option>
                        <option value="waiting_ack">waiting_ack</option>
                        <option value="online">Online</option>-->
                    </select>
                </td>
            </tr>
        </table>
    </center>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align: center" id="start_date" readonly="readonly" placeholder="Start Date" value="<?= $Start_Date ?>"/></td>
                <td><input type="text" style="text-align: center" id="end_date" readonly="readonly" placeholder="End Date" value="<?= $End_Date ?>"/></td>
                <td><input type="text" style="text-align: center" id="Patient_Name"  onkeyup="filter_patient_payment_receipt()" placeholder="Patient Name"  /></td>
                <td><input type="text" style="text-align: center" id="Registration_ID" onkeyup="filter_patient_payment_receipt()" placeholder="Patient Number"  /></td>
                <td><input type="text" style="text-align: center" id="receipt_number"  onkeyup="filter_patient_payment_receipt()"placeholder="Receipt Number"  /></td>
                <td><input type="text" style="text-align: center" id="reference_number" onkeyup="filter_patient_payment_receipt()" placeholder="Reference Number"  /></td>
                <td><input type="button" value="FILTER" onclick="filter_patient_payment_receipt()" class="art-button-green"/></td>
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-hover">
            <tr>
                <td width="50px"><b>S/No.</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Reg#</b></td>
                <td><b>Mobile# / Card# Used For Transaction</b></td>
                <td><b>eHMS Receipt#</b></td>
                <td><b>Sangira/Control Number</b></td>
                <td><b>Payment Reference</b></td>
                <td><b>Payment Date</b></td>
                <td><b>Receipt Amount</b></td>
                <td><b>Operator</b></td>
                <td><b>Transaction Mode</b></td>
            </tr>
            <tbody id="list_of_receipt_body">
                
            </tbody>
        </table>
    </div>
</fieldset>
<script>
    function Print_Receipt_Payment(Patient_Payment_ID){
        var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
    }
    function popupwindow(url, title, w, h) {
      var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
       var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          return mypopupWindow;
    }
    function filter_patient_payment_receipt(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       var Patient_Name= $('#Patient_Name').val();
       var Registration_ID= $('#Registration_ID').val();
       var receipt_number= $('#receipt_number').val();
       var reference_number= $('#reference_number').val();
       var transaction_mode= $('#transaction_mode').val();
       document.getElementById('list_of_receipt_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_filter_patient_payment_receipt.php',
           data:{start_date:start_date,end_date:end_date,Patient_Name:Patient_Name,Registration_ID:Registration_ID,receipt_number:receipt_number,reference_number:reference_number,transaction_mode:transaction_mode},
           success:function(data){
               $("#list_of_receipt_body").html(data); 
           }
       });
    }
     
</script>
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
<?php
    include("./includes/footer.php");
?>
<script>
$(document).ready(function () {
        filter_patient_payment_receipt();
})
</script>
