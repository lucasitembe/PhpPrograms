<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
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
<br/><br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center" style="background-color:#006400;color:white;padding:5px;text"><b>REVENUE CENTER WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ ?>
                    <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Outpatient And Inpatient Payments
                        </button
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Payments 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ ?>
                    <a href='performancereports.php?Section=RevenueCenter&PerformanceReport=PerformanceReportThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Reports
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
			
		<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' && $_SESSION['userinfo']['Patient_Transfer']=='yes'){ ?>
                    <a href='transferdoctor.php?section=revenuecenter'>
                        <button style='width: 100%; height: 100%'>
                            Patient Transfer
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Transfer
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