<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Quality_Assurance'])){
	    if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
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
<script type='text/javascript'>
    function access_Denied(){
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/>
<fieldset>
            <legend align=center><b>QUALITY ASSUARANCE WORKS</b></legend>
        <center><table width = 60%>
	    <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='revenuecollectionbyfolio.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage'>
                    <button style='width: 100%; height: 100%'>
                        eBilling Process
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        eBilling Process
                    </button>

                <?php } ?>
            </td>
        </tr>
	     <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='eclaimbilling.php?EclaimBilling=EclaimBillingThisPage'>
                    <button style='width: 100%; height: 100%'>
                        e-CLAIM Billing Processing
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        e-CLAIM Billing Processing
                    </button>

                <?php } ?>
            </td>
        </tr> 
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if(($_SESSION['userinfo']['Quality_Assurance'] == 'yes')){  ?>
                <a href='billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Approved Bills
                    </button>
                </a>
                <?php }else{ //onclick="return access_Denied();" onclick="alert('This button is undermantainance')" ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();" >
                        Approved Bills
                    </button>

                <?php } ?>
            </td>
        </tr>
         <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='eclaimworkpage.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage'>
                    <button style='width: 100%; height: 100%'>
                        e-CLAIM Report
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        e-CLAIM Report
                    </button>

                <?php } ?>
            </td>
        </tr> 
         <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='claimsummeryitemtypepage.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Claim Summery By Item Type Report
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                         Claim Summery By Item Type Report
                    </button>

                <?php } ?>
            </td>
        </tr> 
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='revenuecollectionbyfolioreport.php?RevenueCollectionByFolioReport=RevenueCollectionByFolioReportThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Revenue Collection By Folio Number
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Revenue Collection By Folio Number
                    </button>

                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
                <a href='revenuecollectionbypatient.php?RevenueCollectionByFolioReport=RevenueCollectionByFolioReportThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Revenue Collection By Patient
                    </button>
                </a>
                <?php }else{ ?>

                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Revenue Collection By Patient
                    </button>

                <?php } ?>
            </td>
        </tr>
        <tr>
           <td style='text-align: center; height: 40px; width: 33%;'>
               <?php if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ ?>
               <a href='revenuecollectionbyfoliopending.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage'>
                   <button style='width: 100%; height: 100%'>
                       Pending Bill Summary
                   </button>
               </a>
               <?php }else{ ?>

                   <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                       Pending Bill Summary
                   </button>

               <?php } ?>
           </td>
       </tr>
       
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
