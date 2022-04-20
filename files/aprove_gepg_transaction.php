<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
        //get today's date
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
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<fieldset>
    <legend align='center'><b>APROVE GePG BILLS</b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Number" id="Registration_ID"/></td>
                <td><input type="button" value="FILTER" onclick="filter_list_of_patient_sent_to_cashier()" class="art-button-green"/></td>
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <tr>
                <td style="width:50px"><b>S/No</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Reg#</b></td>
                <td><b>Item Name</b></td>
                <td><b>Bill ID</b></td>
                <td><b>Control Number</b></td>
                <td><b>Amount</b></td>
                <td><b>Paid Amount</b></td>
                <td><b>Transaction Date</b></td>
                <td><b>Action</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<script>
    function Print_Receipt_Payment(amount,paidamount,billID,auth_code,REG){
        console.log("amount="+amount+"paidamount=>"+paidamount+"billID==>"+billID+"auth_code==>"+auth_code+"REG==>"+REG);
        $.ajax({
            type:'POST',
            url:'aprove_gepg.php',
            data:{amount:amount,paidamount:paidamount,billID:billID,auth_code:auth_code,REG:REG},
            success:function(data){
                console.log("returned feedback"+data);
                if(isNaN(data)){
                    alert("Fail");
                }else{
                    var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID='+data+'&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
                    $(document).ready(function () {
                        filter_list_of_patient_sent_to_cashier();
                    });
                } 
            },error:function(x,y,z){
                console.log(x+y+z)
            }
        });
    }

    function popupwindow(url, title, w, h) {
      var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
       var wTop = window.screenTop ? window.screenTop : window.screenY;
        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          return mypopupWindow;
    }
    function filter_list_of_patient_sent_to_cashier(){
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_aprove_gepg_transaction.php',
            data:{start_date:start_date,end_date:end_date,Registration_ID:Registration_ID,Patient_Name:Patient_Name},
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
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
        filter_list_of_patient_sent_to_cashier();
    });
</script>