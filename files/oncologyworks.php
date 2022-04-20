<?php
    include("./includes/header.php");
	
	/* ****************************SESSION CONTROL****************************** */
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['can_acess_oncology_button'])){
	    if($_SESSION['userinfo']['can_acess_oncology_button'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    } else {
                if($_SESSION['userinfo']['can_acess_oncology_button'] == 'yes'){   
					@session_start();
                    // if(!isset($_SESSION['Oncology_Supervisor'])){ 
                    //     header("Location: ./deptsupervisorauthentication.php?SessionCategory=Oncology&InvalidSupervisorAuthentication=yes");
                    // }
				}
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	/* **************************** SESSION ********************************** */
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Oncology&ChangeLocationOncology=ChangeLocationOncologyThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/>
<fieldset>  
            <legend align=center><b>ONCOLOGY WORKS</b></legend>
            <center>
        <table width = 60%>

            <tr>
               <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php //if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='Nuclearmedicineworks.php'>
                        <button style='width: 100%; height: 100%'>
                            NUCLEAR MEDICINE
                        </button>
                    </a>
                </td>

            </tr>
           
           
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php //if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='chemotherapytreatment.php'>
                                <button style='width: 100%; height: 100%'>
                                   CHEMOTHERAPY
                                </button>
                            </a>
                        <?php //}else{ ?>
                            <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Management Report
                            </button> -->
                        <?php //} ?>
                    </td>
                </tr>
				
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php //if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='radiotherapy_treatment.php?radiotherapyThisPage=radiotherapyThisPage'>
                                <button style='width: 100%; height: 100%'>
                                   RADIOTHERAPY
                                </button>
                            </a>
                        <?php //}else{ ?>
                            <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Radiology Setup
                            </button> -->
                        <?php // } ?>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php //if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='brachytherapy_Menu.php?radiotherapyThisPage=radiotherapyThisPage'>
                                <button style='width: 100%; height: 100%'>
                                   BRACHYTHERAPY
                                </button>
                            </a>
                        <?php //}else{ ?>
                            <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Radiology Setup
                            </button> -->
                        <?php // } ?>
                    </td>
                </tr>
		
        
        </table>
    </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>