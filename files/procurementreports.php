<?php
include("./includes/header.php");
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Procurement_Works'])){
        if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
            @session_start();
            if(!isset($_SESSION['Procurement_Supervisor'])){
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
            }
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if($_SESSION['userinfo']['Procurement_Works'] == 'yes') {
    echo "<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>";
}

?>

    <script type='text/javascript'>
        function access_Denied(){
            alert("Access Denied");
            document.location = "./index.php";
        }
    </script>
    <br/><br/><br/><br/>
    <fieldset>
        <legend align=center><b>PROCUREMENT REPORTS</b></legend>
        <center>
            <table width = 60%>

                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                            <a href='purchasinghistoryreport.php?PurchasingHistoryReport=PurchasingHistoryReportThisPage&From=ProcurementReports'>
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
                        <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                            <a href='lastpricereport.php?LastPriceReport=LastPriceReportThisPage&From=ProcurementReports'>
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
                </tr>
            </table>
        </center>
    </fieldset><br/>

<?php include("./includes/footer.php"); ?>