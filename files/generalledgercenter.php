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
if (isset($_GET['Section']) && $_GET['Section'] == 'managementworkspage') {

    $_SESSION['Section_managementworkspage'] = true;
}
?>
<!-- <a href="general_ledger_btn_setup_login.php" class="art-button-green">GENERAL LEDGERS BUTTON SETUP</a> -->
<!-- <a href="account_configuration.php" class="art-button-green">ACCOUNTS CONFIGURATION</a> -->
<!-- <a href="#" class="art-button-green">ACCOUNTS CONFIGURATION</a> -->
<?php
if (isset($_SESSION['Section_managementworkspage']) && $_SESSION['Section_managementworkspage'] == true) {

    echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage" style=""><button type="button" class="art-button-green">Back</button></a>
';
} else {
    echo '<a href="index.php?Bashboard=BashboardThisPage" style=""><button type="button" style="height:27px!important;color:#FFFFFF!important" class="art-button-green">BACK</button></a>
';
}



///check general ledger button configuration
$sql_check_for_general_ledger_button_configurstion_reqult=mysqli_query($conn,"SELECT general_ledger_button_var,general_ledger_button_status FROM tbl_general_ledger_button") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_for_general_ledger_button_configurstion_reqult)>0){
   while($ledger_rows=mysqli_fetch_assoc($sql_check_for_general_ledger_button_configurstion_reqult)){
      $general_ledger_button_var=$ledger_rows['general_ledger_button_var'];
      $general_ledger_button_status=$ledger_rows['general_ledger_button_status'];
      $_SESSION['general_ledger_btn']["$general_ledger_button_var"]=$general_ledger_button_status;
   }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function Warning() {
        alert("This report is under construction. Please use the report named REVENUE COLLECTION - DETAIL instead");
    }
</script>
<br/><br/>
<style>
    .col-md-3{
        margin-bottom: 15px;
    }
</style>
<fieldset>
    <legend align=center><b>GENERAL LEDGERS</b></legend>
    <center><div class="row">
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_summary'] ?>">
                                 <a href="revenuecollection.php?RevenueCollection=RevenueCollectionThisPage">
                                    <button style='width: 100%; height: 40px;'>
                                        Revenue Collection - Summary
                                    </button>
                                </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_summary_new'] ?>">
                                 <a href="new_revenue_collection_summary.php">
                                    <button style='width: 100%; height: 40px;'>
                                        Revenue Collection - Summary~<b style="color:red">NEW</b>
                                    </button>
                                </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['account_receivable'] ?>">
                    <a href="#accountsreceivable.php">
                        <button style='width: 100%; height: 40px;'>
                            Accounts Receivable
                        </button>
                    </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['cash_and_credit_collection'] ?>">
                <a href="generalperformancereportall.php?GeneralPerformanceReportAll=GeneralPerformanceReportAllThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Cash And Credit Collection
                        </button>
                    </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['account_payables'] ?>">
                    <a href='accountpayablereport.php?AccountPayableReport=AccountPayableReportThisPage&From=GeneralLedgerCenter'>
                        <button style='width: 100%; height: 40px;'>
                            Accounts Payables
                        </button>
                    </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue collection Reports'] ?>">
                    <a href="revenue_collections_repo.php">
                        <button type="button" style='width: 100%; height: 40px;'>
                            Revenue Collection Reports
                        </button>
                    </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['employee_perfomance_report'] ?>">
                    <a href="performancereports.php?Section=GeneralLedger&PerformaceReport=PerformaceReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Employee Performance Report
                        </button>
                    </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['edit_transaction'] ?>">
                    <a href="edittransaction.php?Section=GeneralLedger&EditTransaction=EditTransactionThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Edit Transaction
                        </button>
                    </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_by_receipt_number'] ?>">
                    <a href="managementtimerangesummaryreport.php?Section=GeneralLedger&RevenueCollectionReport=RevenueCollectionReportThisForm">
                        <button style='width: 100%; height: 40px;'>
                            Revenue Collection By Receipt Number
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_detail'] ?>">
                    <a href="revenuecollectiondetails.php?RevenueCollectionDetails=RevenueCollectionDetailsThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Revenue Collection - Detail
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['audit_trail_collection_report'] ?>">
                    <a href="audittrailcollectionreport.php?AuditTrailCollectionReport=AuditTrailCollectionReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Audit Trail Collection Report
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['daily_collection_report'] ?>">
                    <a href="dailycollectionreport.php?DailyCollectionReport=DailyCollectionReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Daily Collection Report
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_summary_statistics'] ?>">
                     <a href="revenuesummarystats.php">
                        <button style='width: 100%; height: 40px;'>
                            Revenue Summary Statistics
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['vender_ledger_statement'] ?>">
                    <a href="venderledgerstatement.php?VenderLedgerStatement=VenderLedgerStatementThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Vender Ledger Statement
                        </button>
                    </a>
             </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['epayment_collection'] ?>">
                <a href='epaymentcollectionrevenuecenter.php'>
                    <button style='width: 100%; height: 100%'>
                        <b>ePAYMENT Collection</b>
                    </button>
                </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['sponsor_ledger'] ?>">
                <a href='sponsor_ledger.php'>
                    <button style='width: 100%; height: 100%'>
                        Sponsor Ledger
                    </button>
                </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['Daily_weekly_departmental_collection_report'] ?>">
                    <a href='revenue_weekly_collection_report.php'>
                        <button style='width: 100%; height: 100%'>
                           Daily/Weekly Departmental Collection Report
                        </button>
                    </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['gepg_reconsiliation_request'] ?>">
                    <a href='gepg_Reconciliation.php'>
                        <button style='width: 100%; height: 100%'>
                            GePG Reconciliation Request
                        </button>
                    </a>
            </div>
            <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['Purchases'] ?>">
                    <a href='purchasereport.php?PurchaseReport=PurchaseReportThisPage&From=GeneralLedgerCenter'>
                        <button style='width: 100%; height: 40px;'>
                            Purchases
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['disposed_materials'] ?>">
                    <a href="#disposedmaterial.php">
                        <button style='width: 100%; height: 40px;'>
                            Disposed Materials
                        </button>
                    </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['dispensing_and_profit_loss_report'] ?>">
                <td style='text-align: center; height: 40px;'>
                    <a href='dispencing_n_profit_and_loss_report.php'>
                       <button style='width: 100%; height: 100%'>
                           Dispensing And Profit And Loss Report
                       </button>
                   </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['supplier_ledger'] ?>">
                <a href='supplier_ledger.php'>
                    <button style='width: 100%; height: 100%'>
                        Supplier Ledger
                    </button>
                </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['dispensing_actual_quantities_disp_for_aud'] ?>">
                    <a href="pharmacyactualdispensed.php?PharmacyActualDispensed=PharmacyActualDispensedThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Dispensing - ACTUAL Quantities Dispensed For Auditing
                        </button>
                    </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['departmental_collection'] ?>">
                    <a href='departmental_collection.php'>
                       <button style='width: 100%; height: 100%'>
                           Departmental Collection
                       </button>
                   </a>
             </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['cost_of_sale'] ?>">
                    <a href="costofsales.php">
                        <button style='width: 100%; height: 40px;'>
                            Cost of Sale
                        </button>
                    </a>
            </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_summary_patients_contributions'] ?>">
                    <a href="revenuesummarycontributions.php?RevenueSummaryContributions=RevenueSummaryContributionsThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Revenue Summary Patients Contributions
                        </button>
                    </a>
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['patients_registration_perfomance'] ?>">
                    <a href="patientregistrationperformance.php?Section=GeneralLedger&PatientsRegistrationPerformance=PatientsRegistrationPerformanceThisForm">
                        <button style='width: 100%; height: 40px;'>
                            Patients Registration Performance
                        </button>
                    </a>
            </div>
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_report'] ?>">  </td>
               <td>
                 < ?php

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        < ?php
        echo'<a href="general_othersources_revenue_report.php?CUSTOMERNO='.$_GET['CUSTOMERNO'].'&CUSTOMER_TYPE='.$_GET['CUSTOMER_TYPE'].'">
                <button style="width: 100%; height: 40px;">
                           Revenu Report
                        </button>
        </a>';
    }
}
?>
            </div> -->
            <!-- <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['new_daily_report'] ?>">
                <td>
                    <a href="dailyandweeklyreport_new.php?RevenueSummaryContributions=RevenueSummaryContributionsThisPage">
                        <button style='width: 100%; height: 40px;'>
                        Daily Collection Report
                        </button>
                    </a>
             </div> -->
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['pos_collection_report'] ?> hide">
                <td>
                    <a href="pos_collection_report.php?RevenueSummaryContributions=RevenueSummaryContributionsThisPage">
                        <button style='width: 100%; height: 40px;'>
                            POS Collection Report
                        </button>
                    </a>
             </div> -->
            
            <!-- <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['pos_collection_report'] ?>">
                <td>
                    <a href="item_price_report.php">
                        <button style='width: 100%; height: 40px;'>
                            Item Price Report
                        </button>
                    </a>
             </div> -->
            <div class="col-md-3 hide <?= $_SESSION['general_ledger_btn']['revenue_collection_by_item_category'] ?>">
                <td>
                    <a href="revenue_collection_by_item_category.php">
                        <button style='width: 100%; height: 40px;'>
                            <b>Revenue collection By  Item Category</b>
                        </button> 
                    </a> 
             </div>
            
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_collection_by_item_category'] ?>">
                <td>
                    <a href="revenue_general_report.php">
                        <button style='width: 100%; height: 40px;'>
                            Service Ordered Report
                        </button> 
                    </a>
				</td>
             </div>
			 <!-- <div class="col-md-3 hide">
                <td>
                    <a href="receipt_by_sangira.php">
                        <button style='width: 100%; height: 40px;'>
                            <b>Force Paid Reference Number</b>
                        </button> 
                    </a>
			</div>
				<div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_by_item_category'] ?>">
                <td>
                    <a href="pos_transaction_report.php?POSTransactionReport=POSTransactionReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            <b>POS Transaction Report</b>
                        </button> 
                    </a>
				</td>
             </div>
			 <div class="col-md-3 < ?= $_SESSION['general_ledger_btn']['revenue_collection_by_item_category'] ?>">
                <td>
                    <a href="pos_Cancelled_transaction_report.php?POSCancelledTransactionReport=POSCancelledTransactionReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            <b>POS Cancelled Transaction Report</b>
                        </button> 
                    </a>
				</td>
             </div> -->
			 <!-- <div class="col-md-3">
                <td>
                    <a href="cashierposconfiguration.php?cashierposconfiguration=cashierposconfigurationThisPage&gledger=true">
                        <button style='width: 100%; height: 40px;'>
                            <b>Cashier POS Configuration</b>
                        </button> 
                    </a>
				</td>
             </div> -->
			 <div class="col-md-3 hide">
                <td>
                    <a href="revenue_collection_by_item_department.php">
                        <button style='width: 100%; height: 40px;'>
                            <b>Revenue Collection By Department</b>
                        </button> 
                    </a>
				</td>
             </div>
             <!-- <div class="col-md-3">
                    <a href="sangira_creation_report.php?Section=GeneralLedger&PosCancelledTransactionReport=PosCancelledTransactionReportThisForm" style=' color: #fff;'>
                        <button style='width: 100%; height: 40px; background: #b61f07; border-radius: 7px;'>
                            <span  style="color:#fff; font-size:20px;">SANGIRA Creation Report</span>
                        </button> 
                    </a> 
            </div> -->
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['reception_collection_report'] ?>">
                <td>
                    <a href="revenue_collection_report_bypatient.php?Generalreport=GeneralreportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Revenue collection per Patient.
                        </button> 
                    </a> 
             </div>
             <div class="col-md-3">
                    <a href="revenue_collection_by_wards.php?Section=GeneralLedger&PosCancelledTransactionReport=PosCancelledTransactionReportThisForm">
                        <button style='width: 100%; height: 40px;'>
                            Revenue Collection By Wards Report 
                        </button> 
                    </a> 
            </div>
             </div>
			 
        </div>
        </table>
    </center>
</fieldset><br/>
<?php

include("./includes/footer.php");
?>
