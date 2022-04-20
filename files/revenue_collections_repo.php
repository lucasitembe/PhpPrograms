<?php
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['from']) &&  $_SESSION['from'] == "ebill") {
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
if (isset($_SESSION['Section_managementworkspage']) && $_SESSION['Section_managementworkspage'] == true) {

    echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage" style=""><button type="button" class="art-button-green">Back</button></a>
';
} else {
    echo '<a href="generalledgercenter.php" style=""><button type="button" style="height:27px!important;color:#FFFFFF!important" class="art-button-green">BACK</button></a>
';
}



///check general ledger button configuration
$sql_check_for_general_ledger_button_configurstion_reqult = mysqli_query($conn, "SELECT general_ledger_button_var,general_ledger_button_status FROM tbl_general_ledger_button") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_for_general_ledger_button_configurstion_reqult) > 0) {
    while ($ledger_rows = mysqli_fetch_assoc($sql_check_for_general_ledger_button_configurstion_reqult)) {
        $general_ledger_button_var = $ledger_rows['general_ledger_button_var'];
        $general_ledger_button_status = $ledger_rows['general_ledger_button_status'];
        $_SESSION['general_ledger_btn']["$general_ledger_button_var"] = $general_ledger_button_status;
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<style>
    .col-md-3 {
        margin-bottom: 15px;
    }
</style>

<fieldset>
    <legend align="center"><b>REVENUE COLLECTION REPORTS</b></legend>
    <center>
        <table width=100%>
        
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_collection_summary_new'] ?>">
                <a href="new_revenue_collection_summary.php">
                    <button style='width: 100%; height: 40px;'>
                        Revenue Collection - Summary
                    </button>
                </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_collection_by_receipt_number'] ?>">
                <a href="managementtimerangesummaryreport.php?Section=GeneralLedger&RevenueCollectionReport=RevenueCollectionReportThisForm">
                    <button style='width: 100%; height: 40px;'>
                        Revenue Collection By Receipt Number
                    </button>
                </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_collection_detail'] ?>">
                <a href="revenuecollectiondetails.php?RevenueCollectionDetails=RevenueCollectionDetailsThisPage">
                    <button style='width: 100%; height: 40px;'>
                        Revenue Collection - Detail
                    </button>
                </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_summary_patients_contributions'] ?>">
                <a href="revenuesummarycontributions.php?RevenueSummaryContributions=RevenueSummaryContributionsThisPage">
                    <button style='width: 100%; height: 40px;'>
                        Revenue Summary Patients Contributions
                    </button>
                </a>
            </div>

            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['revenue_report'] ?>">  
  
  <?php
    if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        <?php
        echo'<a href="general_othersources_revenue_report.php?CUSTOMERNO='.$_GET['CUSTOMERNO'].'&CUSTOMER_TYPE='.$_GET['CUSTOMER_TYPE'].'">
                <button style="width: 100%; height: 40px;">
                           Revenue From Other Sources Report
                        </button>
        </a>';
    }
}
?>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['departmental_collection'] ?>">
                <a href='departmental_collection.php'>
                    <button style='width: 100%; height: 100%'>
                        Departmental Collection
                    </button>
                </a>
            </div>
            <div class="col-md-3 <?= $_SESSION['general_ledger_btn']['audit_trail_collection_report'] ?>">
                <a href="audittrailcollectionreport.php?AuditTrailCollectionReport=AuditTrailCollectionReportThisPage">
                    <button style='width: 100%; height: 40px;'>
                        Audit Trail Collection Report
                    </button>
                </a>
            </div>
            
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>