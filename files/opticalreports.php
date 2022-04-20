<?php
	include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			@session_start();
			if(!isset($_SESSION['Optical_Supervisor'])){ 
			    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
			}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href="opticalworkspage.php?OpticalWorks=OpticalWorksThisPage" class="art-button-green">BACK</a>
<br/><br/>
<br/><br/>
<br/><br/>
<center>
<fieldset>
	<table width="60%">
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='spectaclesalesreport.php?SpectacleSalesReport=SpectacleSalesReportThisPage'>
                    <button style='width: 100%; height: 100%'>
                        SPECTACLES SALES REPORT
                    </button>
                </a>
                <?php }else{ ?>
                 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        SPECTACLES SALES REPORT
                    </button>
              
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='patientspectaclereport.php?SpectacleSalesReport=PatientSpectacleReportThisPage'>
                    <button style='width: 100%; height: 100%'>
                        PATIENT  SPECTACLES REPORT
                    </button>
                </a>
                <?php }else{ ?>
                 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    PATIENT  SPECTACLES REPORT
                    </button>
              
                <?php } ?>
            </td>
        </tr>
	</table>
</fieldset>
 

<?php
    include("./includes/footer.php");
?>