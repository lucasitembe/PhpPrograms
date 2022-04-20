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
        } else {
            @session_start();
            if (!isset($_SESSION['Storage_Supervisor'])) {
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='storagesupervisorauthentication.php?SessionCategory=Storage&ChangeLocationStorage=ChangeLocationStorageThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>
<a href='addnewsupplier.php?AddNewSupplier=AddNewSupplierThisPage&from=store' class='art-button-green'>ADD SUPPLIER</a>
<br /><br />
<fieldset>
    <legend align=center><b>STORAGE AND SUPPLY WORKS</b></legend>
    <center>
        <table width=45%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <!-- <a href='generalrequisition.php?status=new&NPO=True&Requisition=RequisitionThisPage'> -->
                        <a href="storeordering.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage&Store_ID=<?= $_SESSION['Storage_Info'] ?>">
                            <button style='width: 100%; height: 100%'>
                                Store Ordering
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Store Ordering
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
                        $from_status = (isset($_GET['from'])) ? "&from=procurement" : "";
                    ?>
                        <a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage<?= $from_status ?>'>
                            <button style='width: 100%; height: 100%'>
                                Good Receiving Note
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Good Receiving Note
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='listofissuenotes.php?IssueNote=IssueNoteThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Issue Notes ( Electronic )
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Issue Notes ( Electronic )
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='issuenotemanual.php?IssueNoteManual=IssueNoteManualThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Issue Note ( Manual )
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Issue Note ( Manual )
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='requisition.php?Requisition=RequisitionThisPage&From=StorageAndSupply'>
                            <button style='width: 100%; height: 100%'>
                                Requisitions
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisitions
                        </button>
                    <?php } ?>
                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Return Outward / Return Inward
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Return Outward / Return Inward
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='adjustment.php'>
                            <button style='width: 100%; height: 100%'>
                                Adjustment
                            </button>
                        </a>
                    <?php } else { ?>
                        storageandsupply
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Adjustment
                        </button>

                    <?php } ?>
                </td>
                <?php
                //get number of items below the re-order level
                if (isset($_SESSION['Storage'])) {
                    $Sub_Department_Name = $_SESSION['Storage'];
                ?>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                            <a href='reorderlevelnotification.php?ReorderLevel=ReorderLevelthisPage'>
                                <button style='width: 100%; height: 100%'>
                                    Re-Order Level Notification
                                <?php } ?>
                                </button>
                            </a>
                        <?php } else { ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Re-Order Level Notification
                            </button>

                        <?php } ?>
                    </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='itemsconfiguration.php?Section=Storage&ItemsConfiguration=ItemConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Items Configuration
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Items Configuration
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') { ?>
                        <a href='stockreportconfiguration.php?StockReportConfiguration=StockReportConfigurationThisPage&from=StorageAndSupply'>
                            <button style='width: 100%; height: 100%'>
                                Manage Stores with Stock Balance
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Manage Stores with Stock Balance
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <!--<a href='stockmovement.php?Stockmovement=Hospitalstockmovement&motion=move'>-->
                        <a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Reports
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports
                        </button>

                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
                        $Sub_Department_Name = $_SESSION['Storage'];
                        $Sub_Department_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1"))['Sub_Department_ID'] or die(mysqli_error($conn));
                    ?>
                        <a href='cancelled_purchase_requisition.php?from=storage_and_supply&deparmant_id=<?= $Sub_Department_ID ?>'>
                            <button style='width: 100%; height: 100%'>
                                Cancelled Purchase Requisitions
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            SCancelled Purchase Requisitions
                        </button>

                    <?php } ?>
                </td>
        </tr>

        </table>
    </center>
</fieldset>
<?php
include("./includes/footer.php");
?>
