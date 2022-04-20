<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href="revenuecenterworkpage.php" class="art-button-green">BACK</a>
<br/>
<br/>
<br/>
<fieldset>
    <legend align='center'><b>PATIENT PAYMENT</b></legend>
    <br/>

    <center>
          <center><table  width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a href="patient_sent_to_cashier.php">
                        <button style="width:100%">Patient Sent To Cashier</button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href="pending_transactions.php">
                        <button style="width:100%">Pending Transaction</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' class='hide'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a title='REVENUE FROM OTHER SOURCES' href='revenue_from_other_sources.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Revenue From Other Sources
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' class='hide'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a title='SUPPLIER PAYMETS' href='supplier_payments_list.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Supplier Payments
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 50%;'>
                <a href="complete_transaction_with_no_receipts.php">
                        <button style="width:100%">Receipt With Duplicate Reference Number</button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href="cashiercollection.php">
                        <button style="width:100%">Cashier Collections</button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' class='hide'>
                    <a href='./epaymentadhocsearch.php?AdhocSearch=AdhocSearchThisPage' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            ePayment AdHOC SEARCH
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
            <?php if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes'){ $colspaner = ''; }else{ $colspaner = 'colspan="2"'; } ?>
                <td style='text-align: center; height: 40px;' <?php echo $colspaner; ?>>
                    <a href="patient_direct_item_payment.php">
                        <button style="width:100%">Direct To departments Payment</button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' colspan="2">
                    <a href="cash_deposit.php">
                        <button style="width:100%">Cash Deposit</button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' <?php echo $colspaner; ?>>
                    <a href="patient_payment_receipt.php">
                        <button style="width:100%">Payment Receipts / Reconciliation Reporting</button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' colspan="2">
                    <a href="employee_print_out_receipt.php">
                        <button style="width:100%">Employee Print OUT Receipt</button>
                    </a>
                </td>
            </tr>
            <tr class="hide">
                <td style='text-align: center; height: 40px;' colspan="2">
                    <a href='./pricelist.php?PriceList=PriceListThisPage' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            Multi-Sponsors Price List
                        </button>
                    </a>
                </td>
            </tr>
                <tr>
                </tr> 

                <tr class="hide">
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <?php if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' && $_SESSION['userinfo']['Patient_Transfer'] == 'yes') { ?>
                            <a href='transferdoctor.php?section=revenuecenter' style="width:150px;">
                                <button style='width: 100%; height: 100%'>
                                    Patient Transfer
                                </button>
                            </a>
                        <?php } else { ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Transfer
                            </button>

                        <?php } ?>
                    </td>
                </tr>


            </table>
  
    </center>
    <br/>
    <br/>
    <br/>
</fieldset>

<?php
    include("./includes/footer.php");
?>
