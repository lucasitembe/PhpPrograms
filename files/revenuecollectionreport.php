<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
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

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    <a href='./managementworkspage.php?ManagementWorksPage=ThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
    

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>REVENUE CENTER REPORTS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Management_Works'] == 'yes'){ ?>
                    <a href='managementtimerangesummaryreport.php?RevenueCollectionReport=RevenueCollectionReportThisForm'>
                        <button style='width: 100%; height: 100%'>
                            Revenue Collection By Receipt Number
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Revenue Collection By Receipt Number
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Management_Works'] == 'yes'){ ?>
                    <a href='employeeperfomancereportsmanagement.php?PerformnaceReportCashCollection=PerformnaceReportCashCollectionThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Employee Performance Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Employee Performance Report 
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