<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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

<a href='bloodworkpage.php?Appointments=AppointmentsThisPage' class='art-button-green'>
        BACK
    </a>
	
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<br>
<br>
    <br>

<fieldset>  
            <legend align=center><b>BLOOD BANK REPORTS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='BloodRegisterStockReport.php'>
                        <button style='width: 100%; height: 100%'>
                            Blood Registered Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Blood Registered Report
                        </button>
                  
                    <?php } ?>
                </td>
				
				</tr>
				
				
				
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='BloodUsageReport.php'>
                        <button style='width: 100%; height: 100%'>
                            Blood Usage Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Blood Usage Report 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				
		
				
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Surgery Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Surgery Report 
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