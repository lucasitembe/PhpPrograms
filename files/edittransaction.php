<?php
include("./includes/header.php");
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (($_SESSION['userinfo']['Modify_Cash_information'] != 'yes') && ($_SESSION['userinfo']['Modify_Credit_Information'] != 'yes')) {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

    $Current_Username = $_SESSION['userinfo']['Given_Username'];
    
    $sql_check_prevalage="SELECT edit_transaction FROM tbl_privileges WHERE edit_transaction='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
?>

<?php
if (isset($_GET['from']) && $_GET['from'] == "ebill") {
    $_SESSION['from'] = "ebill";
}
if (isset($_SESSION['userinfo'])) {
    //if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
    if (isset($_SESSION['from'])) {
        ?>
        <a href='revenuecollectionbyfolio.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    } else {
        if (isset($_GET['Section'])) {
            if (strtolower($_GET['Section']) == 'generalledger') {
                ?>
                <a href='generalledgercenter.php?Section=GeneralLedger&GeneralLedger=GeneralLedgerThisPage' class='art-button-green'>
                    BACK
                </a>
                <?php
            } else {
                ?>
                <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
                    BACK
                </a>
                <?php
            }
        }
    }
}
?>



<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/> 
<fieldset>
    <legend align=center><b>CANCEL TRANSACTIONS</b></legend>
    <center>
        <table width = 60%>
<?php if ($_SESSION['userinfo']['Modify_Cash_information'] == 'yes' && !isset($_SESSION['from'])) { ?>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'> 
                        <a href='cancelcashlist.php?Section=GeneralLedger&CancelCashTransaction=CancelCashTransactionThisPage'>
                            <button style='width: 100%; height: 100%'>
                                CANCEL CASH INFORMATION / TRANSACTION
                            </button>
                        </a> 
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'> 
                    <a href='edittransactionlist.php?Section=GeneralLedger&ModifyTransaction=ModifyTransactionThisPage'>
                        <button style='width: 100%; height: 100%'>
                          EDIT CREDIT/CASH TRANSACTIONS INFORMATION
                        </button>
                    </a> 
                </td>
                </tr>
            <?php } ?>
<?php if ($_SESSION['userinfo']['Modify_Credit_Information'] == 'yes') { ?>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'> 
                        <a href='cancelcreditlist.php?Section=GeneralLedger&CancelCreditTransaction=CancelCreditTransactionThisPage'>
                            <button style='width: 100%; height: 100%'>
                                CANCEL CREDIT INFORMATION / TRANSACTIONS
                            </button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
<?php if ($_SESSION['userinfo']['Modify_Cash_information'] == 'yes' && !isset($_SESSION['from'])) { ?>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'> 
                        <a href='canceldirectcashlist.php?Section=GeneralLedger&CancelDirectCashTransaction=CancelDirectCashTransactionThisPage'>
                            <!--<a href='#'>-->
                            <button style='width: 100%; height: 100%'>
                                CANCEL DIRECT CASH INFORMATION / TRANSACTIONS
                            </button>
                        </a>
                    </td>
                </tr>
            <?php } ?>

<?php if ($_SESSION['userinfo']['Modify_Cash_information'] == 'yes') { ?>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'> 
                        <a href='canceldirectTransactionsReport.php?Section=GeneralLedger&CancelDirectCashTransaction=CancelDirectCashTransactionThisPage'>
                            <!--<a href='#'>-->
                            <button style='width: 100%; height: 100%'>
                                VIEW CANCELLED TRANSACTIONS REPORTS
                            </button>
                        </a>
                    </td>
                </tr>
<?php } ?>
        </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>