<?php

/**
* @author Nassor Nassor at GPITG 
* created date: january 7 2017
* modified date: -- march 2017
*/

include '../files/includes/constants.php';
define('DBNAME_REM', EPAY_SERVER_DB);
define('HOST_REM', EPAY_SERVER_HOST);
define('USER_REM', EPAY_SERVER_USER);
define('PASSWORD_REM', EPAY_SERVER_PASS);
define('PORT_REM', EPAY_SERVER_PORT);
include 'dbconfig_remote.php';
//connecting from remote host
$con = new dbconfig_remote();

$invoice = mysql_escape_string($_GET['payment_code']);

$data = $con->getRecord("Select Transaction_ID,P_name,Registration_ID,Payment_Code,Amount_Required,Transaction_Status,Transaction_Date_Time from tbl_bank_transaction_cache where Payment_Code='$invoice' order by Transaction_ID desc limit 1");

if(count($data)>0 && strtolower($data[0]['Transaction_Status'])=='completed'){
    $msg = '<p style="text-align:center;">Patient named <b style="color: blue;">'.$data[0]['P_name'].'</b> with the Registration ID <b style="color: blue;">'.$data[0]['Registration_ID'].'</b>';
    $msg .= ' has already paid this BILL';
    $msg .= '</p>';
    echo $msg;
    //echo "This transaction has already been paid ".$data[0]['Transaction_Status'];
} else {
?>


<table  width="100%" cellpadding="6" cellspacing="0" border="0">
    <form id="offlineTransactionForm1" method="POST">
    <!-- hidden fields -->
    <input type="text" hidden="hidden"  name="amount" id="amount_required" value="<?php echo $_GET['amount_required']; ?>">
    <input type="text" hidden="hidden"  name="patient_name" id="patient_name" value="<?php echo $_GET['patient_name']; ?>">
    <input type="text" hidden="hidden"  name="registration_id" id="registration_id" value="<?php echo $_GET['registration_id']; ?>">

    <!-- end of hidden fields -->
        <tbody>
            <tr>
                <td><b>Transaction Mode</b></td>
                <td>
                    <select id="transaction_mode" onchange="get_terminals(this);" name="transaction_mode">
                        <option></option>
                        <option>Offline</option>
                        <option>Manual</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Select Terminal</b></td>
                <td>
                    <select id="terminal_name" onchange="get_terminal_id(this);" name="terminal_name">
                        <option></option>
                        <option>Reception</option>
                        <option>Pharmacy</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Terminal Id</b></td>
                <td>
                    <input type="text" readonly="readonly" id="terminal_id" name="terminal_id">
                </td>
            </tr>
            
            <tr>
                <td><b>Bill Number</b></td>
                <td>
                    <input type="text" readonly="readonly" name="bill_num" id="bill_num" value="<?php echo $_GET['payment_code']; ?>">
                </td>
            </tr>
            <tr>
                <td><b>Amount Required</b></td>
                <td>
                
                    <input type="text" readonly="readonly"  name="amount1" value="<?php echo number_format($_GET['amount_required']); ?>">
                </td>
            </tr>
            <tr>
                <td><b>Authorization Code</b></td>
                <td>
                    <input type="text" name="auth_code" id="auth_code">
                </td>
            </tr>
            <tr>
               
                <td colspan="2" align="right">
                    <input type="submit" class="art-button-green" id="saveOfflinePaymentBtn" value="SAVE TRANSACTION" name="saveTransaction">
                </td>
            </tr>
        </tbody>
        </form>
    </table>

<script type="text/javascript">
    $("#saveOfflinePaymentBtn").click(function(e){
        e.preventDefault();
        var bill_num = $("#bill_num").val();
        var amount = $("#amount_required").val();
        var patient_name = $("#patient_name").val();
        var registration_id = $("#registration_id").val();
        var auth_code = $("#auth_code").val();
        var terminal_id = $("#terminal_id").val();
        var trans_type = $("#transaction_mode").val();
        if(trans_type==''){
            var txt = 'Please select the \" <b style="color:red;">Transaction Type</b> \"';
            ShowMsgDiag('Field Required!',txt);
            return;
        }
        if(terminal_id==''){
            var txt = 'Please select the \" <b style="color:red;">Terminal</b> \"';
            ShowMsgDiag('Field Required!',txt);
            return;
        }
        if(auth_code==''){
            var txt = 'Please enter \" <b style="color:red;">Authorization Code</b> \" provided by CRDB';
            ShowMsgDiag('Field Required!',txt);
            return;
        }
        //alert('hey');
        $.ajax({
            type: 'POST',
            url : '../epay/addOfflineTransactions.php',
            data: {bill_num:bill_num,amount:amount,patient_name:patient_name,registration_id:registration_id,auth_code:auth_code,terminal_id:terminal_id,trans_type:trans_type},
            success: function(data){
                $("#responseDiag").dialog({
                    title: 'Message',
                    width: '350',
                    buttons: [{
                        text: 'Okay',
                        click: function(){
                            clearPaymentForm();
                            sync_epayments('<?php echo $_GET["EPAY_SERVER_URL"]; ?>', '<?php echo  $_GET["remoteTransID"]; ?>');
                            $("#myDiaglog").dialog('close');
                            $(this).dialog('close');
                        },
                    }],
                    modal: true,
                }).html(data);
              
            },
            error: function(){
                alert('Error Occured');
            },
        });
    });

    function clearPaymentForm(){
        $("#auth_code").val('');
        $("#terminal_id").val('');
        $("#transaction_mode").val('');
        $("#terminal_name").html('<option>--select terminal--</option>');   
    }
    function ShowMsgDiag(dTitle,content){
        $("#errorMsgDiag").dialog({
                    title: dTitle,
                    width: '500',
                    buttons: [{
                        text: 'Okay',
                        click: function(){
                            $(this).dialog('close');
                        },
                    }],
                    modal: true,
                }).html(content);
    }
</script>

<div id="responseDiag" style="display:none;"></div>
<div id="errorMsgDiag" style="display:none;"></div>

<?php } ?>
