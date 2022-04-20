<?php
    include("./includes/header.php");
    $Control = 'yes';
    if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {
        $Control = 'no';
    }
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

<br>
<br>
    <br>
<fieldset>  
            <legend align=center><b>BLOOD BANK WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='donorregistration.php'>
                        <button style='width: 100%; height: 100%'>
                            Registration
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Registration 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Requisition Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisition Note 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
				</tr>
				
				
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='searchdonorslist2.php'>
                        <button style='width: 100%; height: 100%'>
                            Registered Donors
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Registered Donors 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Purchase Order
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Purchase Order 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
                </tr>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='blooddisposal.php'>
                        <button style='width: 100%; height: 100%'>
                            Blood Dispose
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Blood Dispose 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Goods Received Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Goods Received Note 
                        </button>
                  
                    <?php } ?>
                </td>
				<?php } ?>
				</tr>
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='bloodissue.php'>
                        <button style='width: 100%; height: 100%'>
                            Blood Usage
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Blood Usage 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Issue Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Issue Note 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
				</tr>
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='BloodBankReports.php'>
                        <button style='width: 100%; height: 100%'>
                            Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Report 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Consumption Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Consumption Note 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
            </tr> 
				
				
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='EditingBatch.php'>
                        <button style='width: 100%; height: 100%'>
                            Setup
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Setup
							 
                        </button>
                  
                    <?php } ?>
                </td>
				<td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                            Appointments & Schedule
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Appointments & Schedule
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