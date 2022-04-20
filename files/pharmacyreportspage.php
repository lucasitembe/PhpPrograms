<?php
include("./includes/header.php");
if(isset($_GET['frompage']) && $_GET['frompage'] == "DIHSREPORT"){ ?>
<a href="governmentReports.php?GovernmentReports=GovernmentReportsThisPage"  class="art-button-green">BACK</a>
<?php }else{ 
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Pharmacy'])){
            if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Pharmacy_Supervisor'])){
                    header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                }
            }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
        echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
    }
}
?>

    <script type='text/javascript'>
        function access_Denied(){
            alert("Access Denied");
            document.location = "./index.php";
        }
    </script>
<?php

?>
    <br/><br/>
    <fieldset>
        <legend align=center><b><span style='color: #FFFF00; font-weight:500;font-size:14px'><?php if(isset($_SESSION['Pharmacy'])){ echo strtoupper($_SESSION['Pharmacy']); } ?> REPORTS</span></b></legend>
        <center><table width = 35%>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php // if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='stockdetails.php?StockDetails=StockDetailsThisPage&From=PhrmacyReports'>
                                <button style='width: 100%; height: 100%'>
                                    Stock Details Summary
                                </button>
                            </a>
                        <?php //}else{ ?>

                            <button style='width: 100%; height: 100%;display: none' onclick="return access_Denied();">
                                Stock Details Summary
                            </button>

                        <?php //} ?>
                    </td>
</tr>
<tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                            <a href='stockledgerreport.php?StockLedgerReport=StockLedgerReportThisPage&From=PhrmacyReports'>
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
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                            <a href='dispense_medication_pharmacy_report.php?Section=Report&Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm'>
                                <button style='width: 100%; height: 100%'>
                                    Dispensed Medication Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Dispensed Medication Report
                            </button>

                        <?php } ?>
                    </td>
              	</tr>

                    <tr>
                        <?php if(!(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes')){ 
                          $colspan="colspan='2'";  
                        }else{
                            $colspan="";
                        } ?>
                        <td style='text-align: center; height: 40px; width: 33%;' <?= $colspan ?>>
                            <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                                <a href='quantity_dispense_report.php?DispenseReport=DispenceReportThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Quantity Dispensed Reports
                                    </button>
                                </a>
                            <?php }else{ ?>

                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Quantity Dispensed Reports
                                </button>

                            <?php } ?>
                        </td>
                    </tr>
                
                    <?php if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
                        <tr>
                            <td style='text-align: center; height: 40px; width: 33%;'>
                                <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                                    <a href='hospital_medication_report.php?Location=Pharmacy'>
                                        <button style='width: 100%; height: 100%;'>
                                            <span>Medication Report</span>
                                        </button>
                                    </a>
                                <?php }else{ ?>     

                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                        Medication Report
                                    </button>

                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
            <tr>
                    <td colspan="2" style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                            <a href='notdispensedlist.php?Section=Report&Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm'>
                                <button style='width: 100%; height: 100%'>
                                    Not Dispensed Medication Report
                                </button>
                            </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                NotDispensed Medication Report
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
