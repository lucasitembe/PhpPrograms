<?php
include './includes/constants.php';
include("./includes/connection.php");
include("./includes/connection_epayment.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
if (isset($_GET['Clinic_ID'])) {
    $Clinic_ID = $_GET['Clinic_ID'];
} else {
    $Clinic_ID = '0';
}
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '0';
}
if (isset($_GET['kutokaphamacy'])) {
    $kutokaphamacy = $_GET['kutokaphamacy'];
    if($kutokaphamacy!="yes")$kutokaphamacy="no";
} else {
    $kutokaphamacy = '0';
}
if (isset($_GET['from_revenue_phamacy'])) {
    $from_revenue_phamacy = $_GET['from_revenue_phamacy'];
    if($from_revenue_phamacy!="yes")$from_revenue_phamacy="no";
} else {
    $from_revenue_phamacy = '0';
}
?>
<a href='epaymentcollectionrevenuecenter.php' class='art-button-green'>
    ePAYMENT
</a>
<?php
if (isset($_SESSION['userinfo'])) {
    if (strtolower($Section) == 'revenue') {
        echo "<a href='paymentsallitems.php?PaymentsAllItems=PaymentsAllItemsThisForm' class='art-button-green'>BACK</a>";
    } else if (strtolower($Section) == 'departmental') {
        if (isset($_GET['olc']) && $_GET['olc'] == 'Pharmacy') {
            echo "<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
        }
    } else if (strtolower($Section) == 'reception') {
        echo "<a href='visitorform.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='#setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<br/><br/>

<?php
if (isset($_SESSION['Transaction_ID'])) {
    $Transaction_ID = $_SESSION['Transaction_ID'];
} else {
    $Transaction_ID = 0;
}

$pay_code = '';

$epaycode = '';

//echo $Transaction_ID;
//get patient details
/* $select = mysqli_query($conn,"select pr.Registration_ID, Patient_Name, Payment_Code, Guarantor_Name, Amount_Required, pr.Phone_Number from 
  tbl_bank_transaction_cache tc, tbl_sponsor sp, tbl_patient_registration pr where
  tc.Registration_ID = pr.Registration_ID and
  pr.Sponsor_ID = sp.Sponsor_ID and
  Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn)); */

$select = mysqli_query($conn,"select pr.Registration_ID, bt.Payment_Code, bt.Amount_Required,Guarantor_Name, bt.Employee_ID, bt.Transaction_Status, bt.Transaction_ID,
							bt.Transaction_Date_Time, bt.Transaction_Date, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Registration_Date_And_Time
							from tbl_bank_transaction_cache bt,  tbl_sponsor sp, tbl_patient_registration pr where
							pr.Registration_ID = bt.Registration_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
							Transaction_ID = '$Transaction_ID' ") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);

$remoteTransID = 0;
$Payment_Code = 0;
$Registration_ID = 0;

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Transaction_ID = $data['Transaction_ID'];
        $Registration_ID = $data['Registration_ID'];
        $Payment_Code = $data['Payment_Code'];
        $Amount_Required = $data['Amount_Required'];
        $Employee_ID = $data['Employee_ID'];
        $Transaction_Date_Time = $data['Transaction_Date_Time'];
        $Transaction_Date = $data['Transaction_Date'];
        $Transaction_Status = $data['Transaction_Status'];
        $Patient_Name = htmlspecialchars($data['Patient_Name'],ENT_QUOTES);
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Gender = $data['Gender'];
        $Phone_Number = $data['Phone_Number'];
        $Registration_Date_And_Time = $data['Registration_Date_And_Time'];
        $Guarantor_Name = $data['Guarantor_Name'];

        $epaycode = $Payment_Code;

        if ($Transaction_Status == 'pending') {
            $sql_epay = "insert into tbl_bank_transaction_cache(P_Name, Registration_ID, Payment_Code, 
										Amount_Required, Employee_ID, Transaction_Date_Time, 
										Transaction_Date, Transaction_Status, Phone_Number)
										values('$Patient_Name','$Registration_ID','$Payment_Code',
												'$Amount_Required','$Employee_ID','$Transaction_Date_Time',
												'$Transaction_Date','$Transaction_Status','$Phone_Number')";

            if (saveInfo($sql_epay)) {

                $results = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'uploaded' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));

                $pay_code = $Payment_Code;

                $sqlTrans = "SELECT Transaction_ID FROM tbl_bank_transaction_cache ORDER BY Transaction_ID DESC LIMIT 1";
                $rs = getRecord($sqlTrans)[0];
                $remoteTransID = $rs['Transaction_ID'];
            } else {
                die("An error has occurred");
            }
        }
    }
} else {
    $Patient_Name = '';
    $Payment_Code = '';
    $Guarantor_Name = '';
    $Amount_Required = '';
    $Phone_Number = '';
    $Registration_ID = '';
}

if ($remoteTransID == 0) {
    $sqlTrans = "SELECT Transaction_ID FROM tbl_bank_transaction_cache WHERE Payment_Code='$Payment_Code' ORDER BY Transaction_ID DESC LIMIT 1";
    $rs = getRecord($sqlTrans)[0];
    $remoteTransID = $rs['Transaction_ID'];
}
?>

<fieldset>  <br/>
    <legend align=right><b>ePayment Transaction Details</b></legend>
    <center><br/><br/><br/>
        <table width = 70%>
            <tr>
                <td colspan="4">
                    <button class="art-button-green" style="display: none" style="float: right" type="button" onclick="Make_Payment('<?php echo $Transaction_ID ?>', '<?php echo $remoteTransID ?>');">MANUAL MAKE PAYMENT</button>
                </td>
            </tr>
            <tr>
                <td width="20%" style="text-align: right;">Patient Name</td>
                <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
                <td width="20%" style="text-align: right;">Sponsor Name</td>
                <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td style="text-align: right;">Registration Number</td>
                <td><input type="text" value="<?php echo $Registration_ID; ?>" readonly="readonly"></td>
                <td style="text-align: right;">Phone Number</td>
                <td><input type="text" value="<?php echo $Phone_Number; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td style="text-align: right;">Amount Required</td>
                <td><input type="text" value="<?php echo number_format($Amount_Required); ?>" readonly="readonly"></td>
                <td style="text-align: right;">Bill Number</td>
                <td><input type="text" value="<?php echo $Payment_Code; ?>" readonly="readonly"></td>
            </tr>
        </table><br/><br/>
        <table width="70%">
            <tr>
                <td colspan="4" >
                    <input type="button" value="WASHA POS TENA / TURN ON POS" class="art-button-green" onclick="ecr_paycode('<?php echo $epaycode ?>')">
                    <?php 
                        $sql_check_for_offline_status="SELECT configname FROM tbl_config WHERE configname='showMakePaymentButton' AND configvalue='show'";
                        $sql_check_for_offline_status_result=mysqli_query($conn,$sql_check_for_offline_status) or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_check_for_offline_status_result)>0){
                            ?>
                              <input type="button" value="MANUAL / OFFLINE TRANSACTION" class="art-button-green" onclick="offline_transaction('<?php echo $epaycode ?>','<?php echo $Amount_Required ?>','<?php echo $Patient_Name ?>','<?php echo $Registration_ID ?>')">
                      <?php
                        }else{
                            ?> 
                              <input type="button" value="MANUAL / OFFLINE TRANSACTION" class="art-button-green" onclick="alert('The CRDB System is Currently Online.Please use Button WASHA POS TENA / TURN ON POS')">
                                <?php
                        }
                        ?>
                    &nbsp;&nbsp;
                    <img id="offlineProgressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none;">&nbsp;&nbsp;
					<!--<input type="button" value="CREATE eBILL AGAIN" class="art-button-green" onclick="ecr_paycode('<?php echo $epaycode ?>')">-->
                    <!--<input type="button" value="SEND SMS" class="art-button-green">-->
                    <!--<input type="button" value="PRINT INVOICE" class="art-button-green" onclick="Print_Payment_Code('<?php echo $Payment_Code; ?>', '<?php echo $Transaction_ID; ?>')">&nbsp;&nbsp;-->
                    <!--<input type="button" value="PRINT ePAYMENT RECEIPT" onclick="print_epayment('<?php // $epaycode   ?>', 0)" class="art-button-green">-->
                    <input type="button" id='sync' value="RE-SYNCHRONIZE"  style="float:right" class="art-button-green" onclick="sync_epayments('<?php echo EPAY_SERVER_URL; ?>', '<?php echo $remoteTransID; ?>',<?= $Payment_Cache_ID ?>,<?= $Registration_ID?>,'<?= $kutokaphamacy ?>','<?= $from_revenue_phamacy ?>')"/>&nbsp;&nbsp;
                    <img id="progressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none;float: right">&nbsp;&nbsp;
                    <input type="button"   style="float:right"  class="art-button-green" value="PRINT DETAIL RECEIPT" onclick="Print_Receipt_Payment_new('<?php echo $Payment_Code ?>')">
                    <!--<a style="" class="print_epayment_details" href="invidualsummaryreceiptprint.php?Patient_Payment_ID=<?php echo $Payment_Code ?>&IndividualSummaryReport=IndividualSummaryReportThisForm" target="_blank">Print</a>-->
                </td>
            </tr>
        </table>
    </center><br/><br/><br/>
</fieldset>
<div id="myDiaglog" style="display:none;">
   <!-- <table  width="100%" cellpadding="6" cellspacing="0" border="0">
    <form id="offlineTransactionForm" method="POST">
    <!-- hidden fields -->
   <!-- <input type="text" hidden="hidden"  name="amount" value="<?php echo $Amount_Required; ?>">
    <input type="text" hidden="hidden"  name="patient_name" value="<?php echo $Patient_Name; ?>">
    <input type="text" hidden="hidden"  name="registration_id" value="<?php echo $Registration_ID; ?>">

    <!-- end of hidden fields -->
        <!--<tbody>
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
                    <input type="text" id="terminal_id" name="terminal_id">
                </td>
            </tr>
            
            <tr>
                <td><b>Bill Number</b></td>
                <td>
                    <input type="text" name="bill_num" value="<?php echo $Payment_Code; ?>">
                </td>
            </tr>
            <tr>
                <td><b>Amount Required</b></td>
                <td>
                
                    <input type="text"  name="amount1" value="<?php echo number_format($Amount_Required); ?>">
                </td>
            </tr>
            <tr>
                <td><b>Authorization Code</b></td>
                <td>
                    <input type="text" name="auth_code">
                </td>
            </tr>
            <tr>
               
                <td colspan="2" align="right">
                    <input type="submit" class="art-button-green" id="saveOfflinePaymentBtn" value="SAVE TRANSACTION" name="saveTransaction">
                </td>
            </tr>
        </tbody>
        </form>
    </table>-->
</div>
<script type="text/javascript">
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    
    function offline_transaction(payment_code,amount_required,p_name,reg_id){
        var uri = '../epay/offline_payment_form.php';
        var EPAY_SERVER_URL = '<?php echo EPAY_SERVER_URL; ?>';
        var remoteTransID = '<?php echo $remoteTransID; ?>';
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            $.ajax({
                type: 'GET',
                url: uri,
                data: {payment_code : payment_code,amount_required:amount_required,patient_name:p_name,registration_id:reg_id,EPAY_SERVER_URL:EPAY_SERVER_URL,remoteTransID:remoteTransID},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 320,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
    function get_terminals(trans_type){
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        $.ajax({
            type: 'GET',
            url: uri,
            data: {trans_type : trans_type.value},
            success: function(data){
                $("#terminal_name").html(data);
            },
            error: function(){

            }
        });
    }

    function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function Print_Payment_Code(Payment_Code) {
        var winClose = popupwindow('paymentcodepreview.php?Payment_Code=' + Payment_Code + '&PaymentCodePreview=PaymentCodePreviewThisPage', 'INVOICE NUMBER', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>
<script type='text/javascript'>
    function Make_Payment(trans_id, trans_id_rem) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        //var Payment_Code = '<?php echo $Payment_Code; ?>';
        var Sponsor_ID = '<?php echo $_GET['Sponsor_ID']; ?>';
        

        var r = confirm("Are you sure you want to make payment?\nClick OK to proceed or Cancel to stop process");
        if (r == true) {
            document.location = 'Direct_Cash_Make_Payment.php?Registration_ID=' + Registration_ID + '&Sponsor_ID=' + Sponsor_ID;
           // document.location = 'Departmental_Make_Payment.php?src=manualepay&Registration_ID=' + Registration_ID + '&Payment_Code=' + Payment_Code + '&trans_id=' + trans_id + '&trans_id_rem=' + trans_id_rem;
        }
    }
</script>
<script>
   function Print_Receipt_Payment_new(Payment_Code){
        $.ajax({
            type:'POST',
            url:'ajax_Print_Receipt_Payment_new.php',
            data:{Payment_Code:Payment_Code},
            success:function(data){
                if(data=="not_paid"){
                    $("#print_receipt_msg").dialog("open");
                }else{
                    window.open("invidualsummaryreceiptprint.php?Patient_Payment_ID=" + data + "&IndividualSummaryReport=IndividualSummaryReportThisForm");
                }
            }
        });
    }
    $(document).ready(function () {
        var pay_code = '<?php echo $pay_code ?>';
        ecr_paycode(pay_code);
        //alert("allll");
    });
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> 
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->

<div id="print_receipt_msg" style="width:50%;" >
    <center id='Msg_Area'>
        <p><b>Payment is not completed!!!</b><br><br>Click RE-SYCHRONIZE button then <br><u><b>try re-printing </b></u>the receipt again</p>
    </center>
</div>
<div id="synchronize_msg" style="width:50%;" >
    <center id='synchronize_msg_Area'>
        <p>Synchronization Successful!.</p>
    </center>
</div>

<script type="text/javascript" src="js/ecr_pmnt.js"></script>
<?php
include("./includes/footer.php");
?>
