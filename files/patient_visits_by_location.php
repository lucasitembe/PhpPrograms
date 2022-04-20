<?php
include("./includes/connection.php");
include("./includes/header.php");
include './includes/constants.php';
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
<?php
if (isset($_SESSION['userinfo'])) {
    ?>
   <!-- <a href='departmentpatientbillingpage.php' class='art-button-green'>
        BACK
    </a> -->
    <button class='art-button-green' onclick="history.go(-1)">BACK</button>
<?php } ?>

 
<br/><br/>
<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $Today = $row['today'];
}
?>
<!-- end of the function -->
<fieldset>
    <center>
        <table width = 100%>
            <tr>
                <td  style='text-align: center;'>
                    <select name="Payment_Mode" id="Payment_Mode">
                        <option selected="selected" value="">~~~~ Select mode ~~~~</option>
                        <option value="Bank_Payment">Bank Payment</option>
                        <option value="Mobile_Payemnt">Mobile Payment</option>
                    </select>
                    <select name="Report_Type" id="Report_Type">
                        <option selected="selected" value="">~~~~ Select type ~~~~</option>
                        <option>Pending Transactions</option>
                        <option>Completed Transactions</option> 
                        <option>Waiting ACK</option>
                        <option>Offline Transactions</option>
                        <option>Others</option>
                    </select>
                    <select id='Terminal_ID' class="select2-default" style='text-align: center;width:7%;display:inline'>
                        <option selected value="all">All Terminals</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT DISTINCT(Terminal_ID)  FROM tbl_bank_api_payments_details ORDER BY Terminal_ID ASC
                                ") or die(mysqli_error($conn));

                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Terminal_ID']; ?>"><?php echo $data['Terminal_ID']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <!-- cashier filter -->
                        <select name="cashier_id" id="cashier_id">
                            <option value="All">All Cashiers</option>
                            <?php
                            $emp_query = "select DISTINCT(emp.Employee_ID), emp.Employee_Name from 
                            tbl_bank_transaction_cache tc
                            JOIN tbl_patient_registration pr ON tc.Registration_ID = pr.Registration_ID 
                            JOIN tbl_sponsor sp ON pr.Sponsor_ID = sp.Sponsor_ID
                            JOIN tbl_employee emp ON emp.Employee_ID = tc.Employee_ID
                            JOIN tbl_bank_api_payments_details ba ON ba.Payment_Code=tc.Payment_Code where
                            tc.Transaction_Status = 'Completed' order by ba.Transaction_Date";

                                $selectEmp = mysqli_query($conn,$emp_query) or die(mysqli_error($conn));

                                while ($data = mysqli_fetch_array($selectEmp)) {
                                    $selected="";
                                    if($data['Employee_ID']==$_SESSION['userinfo']['Employee_ID']){
                                        $selected="selected='selected'";
                                    }
                                    ?>
                            
                                    <option value="<?php echo $data['Employee_ID']; ?>" <?= $selected ?>><?php echo $data['Employee_Name']; ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    <!-- end of cashier filter -->
                    <select name="trans_mode" id="trans_mode" title="Select Transaction Mode" style='text-align: center;width:9%;display:inline'>
                        <option value="All">All Transactions Mode</option>
                        <option value="offline">Offline</option>
                        <option value="normal">Online</option>
                    </select>
                    <input type='text' name='Date_From' id='Date_From' style='text-align: center;width:15%;display:inline' placeholder='Start Date' readonly='readonly' value='<?php echo date('Y-m-d') . ' 00:00:00'; ?>'>
                    <input type='text' name='Date_To' id='Date_To'  style='text-align: center;width:15%;display:inline' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
                    <select id="number_recordes">
                        <option value="All">All Records</option>
                        <option selected value="500_Rec">500 Records</option>
                        <option value="300_Rec">300 Records</option>
                        <option value="100_Rec">100 Records</option>
                        <option value="50_Rec">50 Records</option>
                    </select>
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Update_Patient_List()">
                    <a href='#' class='art-button-green' id='print_filter' style='' target='_blank'>PRINT REPORT</a>
                   <!-- <input type="button" value="RE-SYNCHRONIZE" class="art-button-green" onclick="sync_epayments('<?php echo EPAY_SERVER_URL; ?>', '0')">&nbsp;&nbsp;-->
                    <input type="button" value="RE-SYNCHRONIZE" class="art-button-green" onclick="sync_epayments('<?php echo EPAY_SERVER_URL; ?>', '0','0','0','no')">&nbsp;&nbsp;
                    <img id="progressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none ">

                </td>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white;' id='Patient_List'>
    <legend align=right><b>Patient Visits By Location</b></legend>
    <center>
        <table width = 100%>
            <tr><td colspan="8"><hr></td></tr>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="10%"><b>PATIENT NUMBER</b></td>
                <td width="12%"><b>SPONSOR</b></td>
                <td width="15%" style="text-align: right;"><b>PREPARED DATE</b></td>
                <td width="15%" style="text-align: right;"><b>EMPLOYEE PREPARED</b></td>
                <td width="10%" style="text-align: right;"><b>BILL NUMBER</b></td>
                <td width="10%" style="text-align: right;"><b>AMOUNT REQUIRED</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
        </table>
    </center>
</fieldset>
<center><span style='color: #037CB0;'><i>Click transaction detail to view more information</i></span></center>

<div id="DisplayTransactionDetails" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="print_receipt_msg" style="width:50%;" >
    <center id='Msg_Area'>

        <p>Payment is not completed!!!</b><br><br>Click RE-SYCHRONIZE button then <br><u><b>try re-printing </b></u>the receipt again</p>
    </center>
</div>
<div id="force_synchronization_message">
    force_move_data_for_receipt_print(Registration_ID,Transaction_ID,Payment_Code)
    
</div>
<div id="synchronize_msg" style="width:50%;" >
    <center id='synchronize_msg_Area'>
        <p>Synchronization Successful!.</p>
    </center>
</div>
<script>
    function Update_Patient_List() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Report_Type = document.getElementById("Report_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Terminal_ID = document.getElementById("Terminal_ID").value;
        var trans_mode  = document.getElementById("trans_mode").value;
        var cashier_id = document.getElementById("cashier_id").value;
        var number_recordes = document.getElementById("number_recordes").value;
        var datastring='Date_From=' + Date_From + '&Date_To=' + Date_To + '&Report_Type=' + Report_Type + '&Payment_Mode=' + Payment_Mode + '&Terminal_ID=' + Terminal_ID + '&number_recordes=' + number_recordes+'&trans_mode='+trans_mode+'&cashier_id='+cashier_id;

        if (Report_Type == null || Report_Type == '' || Payment_Mode == null || Payment_Mode == '') {
            if (Report_Type == null || Report_Type == '') {
                document.getElementById("Report_Type").focus();
                document.getElementById("Report_Type").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Report_Type").style = 'border: 3px solid white;';
            }
            if (Payment_Mode == null || Payment_Mode == '') {
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red;';
            } else {
                document.getElementById("Payment_Mode").style = 'border: 3px solid white;';
            }
        } else {
            document.getElementById("Report_Type").style = 'border: 3px solid white;';
            document.getElementById("Payment_Mode").style = 'border: 3px solid white;';
            document.getElementById('Patient_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Patient_List').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            if (Payment_Mode == 'Bank_Payment' && Report_Type == 'Pending Transactions') {
                $('#print_filter').attr('href','print_epay_report.php?src=pending&'+datastring);
                myObject.open('GET', 'ePayment_Update_Patients_List.php?'+datastring, true);//number_recordes
                myObject.send();
            } else if (Payment_Mode == 'Bank_Payment' && Report_Type == 'Completed Transactions') {
                $('#print_filter').attr('href','print_epay_report.php?src=complete&'+datastring);
                myObject.open('GET', 'ePayment_Paid_Patients_List.php?'+datastring, true);
                myObject.send();
            }else if(Payment_Mode == 'Bank_Payment' && Report_Type == 'Offline Transactions'){
                $('#print_filter').attr('href','ePayment_Offline_transaction_Patients_List_report.php?src=offline&'+datastring);
                myObject.open('GET', 'ePayment_Offline_transaction_Patients_List.php?'+datastring, true);
                myObject.send();
            } else if(Payment_Mode == 'Bank_Payment' && Report_Type =="Waiting ACK"){
                 $('#print_filter').attr('href','ePayment_Online_forced_transaction_Patients_List_report.php?src=offline_forced&'+datastring);
                myObject.open('GET', 'ePayment_Online_forced_transaction_Patients_List.php?'+datastring, true);
                myObject.send();
            }else {
                myObject.open('GET', 'ePayment_Update_Patients_List2.php', true);
                myObject.send();
            }
        }
    }
</script>


<script>
    function open_Dialog(Transaction_ID, Source) {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#DisplayTransactionDetails").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'Patient_Transaction_Details.php?Transaction_ID=' + Transaction_ID + '&Source=' + Source, true);
        myObjectGetDetails.send();
    }
</script>

<script>
    $(document).ready(function () {
        $("#DisplayTransactionDetails").dialog({autoOpen: false, width: '90%', height: 400, title: 'PATIENT TRANSACTION DETAILS', modal: true});
    });
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
$('#Date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    //startDate:    'now'
});
$('#Date_From').datetimepicker({value: '', step: 1});
$('#Date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    //startDate:'now'
});
$('#Date_To').datetimepicker({value: '', step: 1});
</script>
<!--End datetimepicker-->

<script type="text/javascript">
    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script type="text/javascript" src="js/ecr_pmnt.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<script src="css/jquery.datetimepicker.js"></script>
<?php
include("./includes/footer.php");
?>
