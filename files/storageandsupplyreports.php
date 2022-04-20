<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer=$_SESSION['userinfo']['Employee_Name'];

if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
            @session_start();
            if(!isset($_SESSION['Storage_Supervisor'])){
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
    @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
        ?>
        <a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>
            BACK
        </a>
    <?php  } } ?>


    <br/><br/><br/><br/><br/><br/>
    <fieldset>
        <legend align=center><b>STORAGE AND SUPPLY REPORTS</b></legend>
        <center><table width = 60%>
                <!--tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                    <a href='stocksummary.php?StockSummary=StockSummaryThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Stock Summary
                        </button>
                    </a>
                    <?php }else{ ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Stock Summary
                        </button>

                    <?php } ?>
                </td>
            </tr-->
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='stockdetails.php?StockDetails=StockDetailsThisPage&from_to=my_store'>
                                <button style='width: 100%; height: 100%'>
                                    Stock Details Summary
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Stock Details Summary
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='stockledgerreport.php?StockLedgerReport=StockLedgerReportThisPage'>
                                <button style='width: 100%; height: 100%'>
                                    Stock Ledger
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Stock Ledger
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='generalstockbalancereport.php?GeneralStockBalanceReport=GeneralStockBalanceReportThisPage'>
                                <button style='width: 100%; height: 100%'>
                                    Comparison Stock Balance Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Comparison Stock Balance Report
                            </button>

                        <?php } ?>
                    </td>
                </tr>
                <!-- <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                    <a href='stockledgerreport.php?StockLedgerReport=StockLedgerReportThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Stock Ledger Report
                        </button>
                    </a>
                    <?php }else{ ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Stock Ledger Report
                        </button>

                    <?php } ?>
                </td>
            </tr> -->

                <tr>
                    
                     <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='purchasereport.php?PurchaseReport=PurchaseReportThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Purchase Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Purchase Report
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='purchasinghistoryreport.php?PurchasingHistoryReport=PurchasingHistoryReportThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Purchasing History
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Purchasing History
                            </button>

                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='lastpricereport.php?LastPriceReport=LastPriceReportThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Last Price Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Last Price Report
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='ReturnInwardReportList.php?LastPriceReport=LastPriceReportThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Return Inward Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Return Inward Report
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='ReturnOutwardReportList.php?LastPriceReport=LastPriceReportThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Return Outward Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Return Outward Report
                            </button>

                        <?php } ?>
                    </td>
                </tr>

                 <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { ?>
                        <a href='adjustment_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Stock Adjustment Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Stock Adjustment Report
                        </button>

                    <?php } ?>
                    </td>
                     <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='expiredItemsnotification.php?LastPriceReport=LastPriceReportThisPageC'>
                                <button style='width: 100%; height: 100%'>
                                    Expiring Item Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Expiring Item Report Report
                            </button>

                        <?php } ?>
                    </td>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='delivery_note_report.php?DeliveryNoteDetails=DeliveryNoteDetailsThisPage&From=StorageAndSupplyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Delivery Note Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Delivery Note Report
                            </button>

                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table width="100%">
                            <tr>
                                <td style='text-align: center; height: 40px; width: 50%; '>
                                   <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                                       <a href='venderledgerstatement.php?VenderLedgerStatement=VenderLedgerStatementThisPage'>
                                           <button style='width: 100%; height: 100%'>
                                               Grn Report
                                           </button>
                                       </a>
                                   <?php }else{ ?>

                                       <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                           Grn Report
                                       </button>

                                   <?php } ?>
                                </td>
                                <td>
                                   <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                                       <a href='all_issue_note_report.php'>
                                           <button style='width: 100%; height: 100%'>
                                               Issue Note Report
                                           </button>
                                       </a>
                                   <?php }else{ ?>

                                       <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                           Issue Note Report
                                       </button>

                                   <?php } ?> 
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>


            </table>
        </center>
    </fieldset><br/>
<?php
include("./includes/footer.php");
?>
