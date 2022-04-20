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
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<fieldset>  
            <legend align=center><b>BLOOD BANK WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='donorregistration.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Registration</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Registration</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Requisition Note</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Requisition Note</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				
				
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='searchdonorslist2.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Registered Donors</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Registered Donors</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Purchase Order</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Purchase Order</b> 
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='blooddisposal.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Blood Dispose</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Blood Dispose</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Goods Received Note</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Goods Received Note</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				
				</tr>
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='bloodissue.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Blood Usage</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Blood Usage</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Issue Note</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Issue Note</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='BloodBankReports.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Report</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Report</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Consumption Note</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Consumption Note</b> 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
				
				
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='EditingBatch.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Setup</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Setup
							</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Appointments</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Appointments</b> 
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