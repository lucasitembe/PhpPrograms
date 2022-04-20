<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Msamaha_Works'])){
	    if($_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href='msamahapanel.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>BACK</a>

<script type='text/javascript'>
    function access_Denied(){
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<br/><br/><br/><br/><br/>
<fieldset>  
    <legend align="center"><b>MSAMAHA REPORTS</b></legend><center>
    <table width = 60%>
		<tr>
			 <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href='msamahalistpatient.php?MsamahaList=MsamahaFormiframe'>
                        <button style='width: 100%; height: 100%'>
                            Demographic Report Details
                        </button>
                    </a>
                <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Demographic Report Details
                        </button>
                <?php } ?>
            </td>
		</tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                <a href='msamahalistpatientgeneral.php?MsamahaList=MsamahaFormiframe'>
                    <button style='width: 100%; height: 100%'>
                        Demographic Report Summary
                    </button>
                </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Demographic Report Summary
                    </button>
              
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href="exemptioncostanalysisreport.php?ExemptionCostAnalysisReport=ExemptionCostAnalysisReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Exemptions Cost Analysis Detail Report
                        </button>
                    </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Exemptions Cost Analysis Detail Report
                    </button>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href="exemptioncostanalysissummaryreport.php?ExemptionCostAnalysisSummaryReport=ExemptionCostAnalysisSummaryReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Exemptions Cost Analysis Summary Report
                        </button>
                    </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Exemptions Cost Analysis Summary Report
                    </button>
                <?php } ?>
            </td>
        </tr>

        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href="exemption_report.php?ExemptionCostAnalysisSummaryReport=ExemptionCostAnalysisSummaryReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Exemption Report
                        </button>
                    </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    Exemption Report
                    </button>
                <?php } ?>
            </td>
        </tr>

        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href="discount_report.php?ExemptionCostAnalysisSummaryReport=ExemptionCostAnalysisSummaryReportThisPage">
                        <button style='width: 100%; height: 40px;'>
                            Discount Report
                        </button>
                    </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    Discount Report
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