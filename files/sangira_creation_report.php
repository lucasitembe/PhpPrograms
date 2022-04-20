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
<a href="generalledgercenter.php" class="art-button-green">BACK</a>
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
    <legend align='center'><b>LIST OF CREATED SANGIRA CODES</b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Patient Number" id="Registration_ID"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="SANGIRA Number" id="sangira_code"/></td>
                <td><select width="20%"  name='sangira_status' style='text-align: center;width:100%;display:inline' onchange="filter_list_of_patient_sent_to_cashier()" id="sangira_status">
                        <option value="All">All SANGIRAs</option>
                        <option value="completed">Completed SANGIRA</option>
                        <option value="pending">Pending SANGIRA</option>
                    </select></td>
                <td><select width="20%"  name='payment_direction' id='payment_direction' style='text-align: center;width:100%;display:inline' onchange="filter_list_of_patient_sent_to_cashier()" id="sangira_status">
                        <option value="All">All Banks</option>
                        <option value="to_nmb">NMB Bank</option>
                        <option value="to_crdb">CRDB Bank</option>
                    </select></td>
                <td><input type="button" value="FILTER" onclick="filter_list_of_patient_sent_to_cashier()" class="art-button-green"/></td>
                <!--<td><input type="button" value="Card/Mobile CONFIRM PAYMENT" onclick="confirm_mobile_payment()" class="art-button-green"/></td>-->
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <tr style='background: #dedede;'>
                <td style="width:50px"><b>S/No</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Reg#</b></td>
                <td><b>Phone Number</b></td>
                <td><b>Gender</b></td>
                <td><b>Sponsor</b></td>
                <td><b>Employee Created</b></td>
                <td><b>Created Date</b></td>
                <td><b>Payment Direction</b></td>
                <td><b>Created SANGIRA Number</b></td>
                <td><b>SANGIRA Status</b></td>
                <td><b>Amount</b></td>
            </tr>
            <tbody id='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
</fieldset>
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
    function filter_list_of_patient_sent_to_cashier(){
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var sangira_code=$('#sangira_code').val();
        var sangira_status=$('#sangira_status').val();
        var payment_direction=$('#payment_direction').val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'ajax_sangira_creation_report.php',
            data:{start_date:start_date,end_date:end_date,Registration_ID:Registration_ID,Patient_Name:Patient_Name,sangira_code:sangira_code,sangira_status:sangira_status,payment_direction:payment_direction},
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
            }
        });
    }
     $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<?php
    include("./includes/footer.php");
?>
