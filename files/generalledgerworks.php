<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
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
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>GENERAL LEDGER WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='timerangesummaryreport.php?TimeRangeSummaryReport=TimeRangeSummaryReportThisForm'>
                        <button style='width: 100%; height: 100%'>
                            <b>Revenue Collection By Receipt Number</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Work Forms</b> 
                        </button>
                  
                    <?php } ?>
                </td>
                </tr><tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ ?>
                    <a href='revenuecollectionbyfolio.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage'>
                        <button style='width: 100%; height: 100%'>
                            <b>Revenue Collection By Folio Number</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Revenue Collection By Folio Number</b> 
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