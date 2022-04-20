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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<fieldset>
            <legend align=center><b>RECEPTION SETUP</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='addnewreason.php?AddNewPfReason=AddNewPfReasonThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Pf3 Reason
                        </button>
                    </a>
                    
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Add New Pf3 Reason
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php 
//                    echo $_SESSION['userinfo']['can_access_appointment_button'];
                    if( $_SESSION['userinfo']['can_access_appointment_button'] == 'yes'){ ?>
				    <a href='SMSConfiguration.php?SMSConfiguration=SMSConfiguration'> 
					<button style='width: 100%; height: 100%'>
					    SMS Configuration
					</button>
				    </a>
				
                    
                    <?php }else{ ?>
                 
					<button style='width: 100%; height: 100%' onclick="return access_Denied();">
					    SMS Configuration
					</button>
				    </a>
				
                  
                    <?php } ?>
                </td>
        
            </tr>
                       <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php 
//                    echo $_SESSION['userinfo']['can_access_appointment_button'];
                    if( $_SESSION['userinfo']['can_access_appointment_button'] == 'yes'){ ?>
				    <a href='all_appointment_configuration.php'> 
					<button style='width: 100%; height: 100%'>
					    Appointment Configuration
					</button>
				    </a>
				
                    
                    <?php }else{ ?>
                 
					<button style='width: 100%; height: 100%' onclick="return access_Denied();">
					     Appointment Configuration
					</button>
				    </a>
				
                  
                    <?php } ?>
                </td>
                
            </tr>
			 <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
				    <a href='tribeconfiguration.php?TribeConfiguration=TribeConfigurationThisPage'> 
					<button style='width: 100%; height: 100%'>
					    Tribe Configuration
					</button>
				    </a>
			
                </td>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
