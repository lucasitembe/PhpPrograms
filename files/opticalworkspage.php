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
 
<br/><br/><br/><br/>
<center>
<fieldset>
	<legend align='right'><b>OPTICAL WORKS</b></legend>
	<table width="60%">
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage'>
                    <button style='width: 100%; height: 100%'>
                        PATIENTS LIST
                    </button>
                </a>
                <?php }else{ ?>
                 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        PATIENTS LIST
                    </button>
              
                <?php } ?>
            </td>
        </tr>
        <tr>
            <!-- <td style='text-align: center; height: 40px; width: 33%;'> -->
                <!--?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?-->
                <!-- <a href='opticalpendingtransactions.php?Section=Home&OpticalPendingTransactions=OpticalPendingTransactionsThisPage'>
                    <button style='width: 100%; height: 100%'>
                        PENDING TRANSACTIONS
                    </button>
                </a> -->
                <!--?php }else{ ?-->
                 
                    <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        PENDING TRANSACTIONS
                    </button> -->
              
                <!--?php } ?-->
            <!-- </td> -->
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='glassprocessing.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage'>
                    <button style='width: 100%; height: 100%'>
                        GLASS PROCESSING
                    </button>
                </a>
                <?php }else{ ?>
                 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        GLASS PROCESSING
                    </button>
              
                <?php } ?>
            </td>
        </tr>
		<tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='opticalreports.php?OpticalReports=OpticalReportsThisPage'>
                    <button style='width: 100%; height: 100%'>
                        REPORTS
                    </button>
                </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        REPORTS
                    </button>
                <?php } ?>
            </td>
		</tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Eye_Works'] == 'yes'){ ?>
                <a href='optical_sponsor_setup.php?OpticalReports=OpticalReportsThisPage'>
                    <button style='width: 100%; height: 100%'>
                        OPTICAL SPONSOR SETUP
                    </button>
                </a>
                <?php }else{ ?>
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        OPTICAL SPONSOR SETUP
                    </button>
                <?php } ?>
            </td>
		</tr>
	</table>
</fieldset>
 

<?php
    include("./includes/footer.php");
?>