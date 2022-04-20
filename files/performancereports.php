<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<?php
//get section value
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

    if(isset($_SESSION['userinfo'])){
        if($_GET['Section']){
	    if(strtolower($Section) == 'revenuecenter'){
?>
		<a href='./revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>BACK</a>
<?php
	    }else if(strtolower($Section) == 'reception'){
?>
		<a href='./receptionReports.php?Section=Reception&ReceptionReportThisPage' class='art-button-green'>BACK</a>
<?php
	    }else if(strtolower($Section) == 'management'){
?>
		<a href='./receptionReports.php?Section=Management&ReceptionReport=ReceptionReportThisPage' class='art-button-green'>BACK</a>
<?php
	    }else if(strtolower($Section) == 'generalledger'){
?>
		<a href='./generalledgercenter.php?Section=GeneralLedger&GeneralLedger=GeneralLedgerThisPage' class='art-button-green'>BACK</a>
<?php
	    }else{
?>
		<a href='./index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>
<?php
	    }
	}else{
?>
	    <a href='./receptionreports.php?Reception=ReceptionThisPage' class='art-button-green'>BACK</a>
<?php
	}
    }
?>




<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>PERFORMANCE REPORTS</b></legend>
    <center><table width = 60% style="border: 0">
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']) { ?>
                        <a href='employeeperfomancereport.php?Section=<?php echo $Section; ?>&PerformnaceReportCashCollection=PerformnaceReportCashCollectionThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Performance Reports - Revenue Collections
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Performance Reports - Revenue Collections
                        </button>

                    <?php } ?>
                </td>
            </tr>
             <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']) { ?>
                        <a href='allemployeeperfomancereport.php?Section=<?php echo $Section; ?>&PerformnaceReportCashCollection=PerformnaceReportCashCollectionThisPage'>
                            <button style='width: 100%; height: 100%'>
                               All Cashiers Performance Reports
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            All Cashiers Performance Reports
                        </button>

                    <?php } ?>
                </td>
                <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']) { ?>
                        <a href='all_employee_perfomance_report_new.php?Section=<?php echo $Section; ?>&PerformnaceReportCashCollection=PerformnaceReportCashCollectionThisPage'>
                            <button style='width: 100%; height: 100%'>
                               All Cashiers Performance Reports <label style='color:red'>New</label>
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            All Cashiers Performance Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <?php /* <tr>
              <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
              <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
              <a href='employeeworkperformancesummary.php'>
              <button style='width: 100%; height: 100%'>
              Work Performance Report
              </button>
              </a>
              <?php }else{ ?>

              <button style='width: 100%; height: 100%' onclick="return access_Denied();">
              Work Performance Report
              </button>

              <?php } ?>
              </td>
              </tr> */ ?>
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>reports