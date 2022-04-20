<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisForm' class='art-button-green'>BACK</a>";
    }
}
?>
<br /><br /><br />
<script>
    function access_Denied() {
        alert("Access Denied");
    }
</script>
<fieldset>
    <legend align=center><b>GOOD RECEIVING NOTE</b></legend>
    <center>
        <table width=40%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='grnissuenotelist.php?GrnIssueNoteList=GrnIssueNoteListThisPage'>
                            <button style='width: 100%; height: 100%'>
                                GRN Against Issue Note
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            GRN Against Issue Note
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
                        $from_status = (isset($_GET['from'])) ? "procurement-recieving.php" : "approve_grn_purchase_order.php";
                    ?>
                        <a href='<?= $from_status ?>'>
                            <button style='width: 100%; height: 100%'>
                                GRN Against Purchase Orders
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            GRN Against Purchase Orders
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage'>
                            <button style='width: 100%; height: 100%'>
                                GRN Without Purchase Orders
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            GRN Without Purchase Orders
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    $sql_check_for_bakup_privilage = "SELECT can_have_access_to_grn_physical_counting FROM tbl_privileges WHERE can_have_access_to_grn_physical_counting='yes' AND Employee_ID='$Employee_ID'";
                    $sql_check_for_privilage_result = mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error($conn));

                    ?>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && mysqli_num_rows($sql_check_for_privilage_result) > 0) { ?>
                        <a href='grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage'>
                            <button style='width: 100%; height: 100%'>
                                GRN As Open Balance / Physical Counting
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            GRN As Open Balance / Physical Counting
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <!--                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                            <a href='stocktaking.php?status=new&GrnStockTaking=GrnStockTakingThisPage'>
                                <button style='width: 100%; height: 100%'>
                                    Stock Taking
                                </button>
                            </a>
                        <?php } else { ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Stock Taking
                            </button>
                        <?php } ?>
                    </td>
                </tr>-->
        </table>
    </center>
</fieldset><br />
<?php
include("./includes/footer.php");
?>