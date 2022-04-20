<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ 
?>
    <a href='morguepage.php' class='art-button-green'>
BACK 
    </a>
<?php  } } 
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>REGISTER NEW DEAD BODY</b> </legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='morgueRegistration.php?RegistrationDeadBody=RegistrationPage'>
                        <button style='width: 100%; height: 100%'>
                            Dead Body Not Registered in Hospital
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Dead Body Not Registered in Hospital
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
					
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='KnowDeadBody.php?KnownDeadBody=OutPatientDeadbody'>
                        <button style='width: 100%; height: 100%'>
                            Dead Body Registered in Hospital
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Dead Body Registered in Hospital
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				
                                <tr>
                                    <td>
                                        <a href="list_of_patient_cheked_in_n_from_inpatient.php">
                                            <button style='width: 100%; height: 100%' >Diseased Body Registration</button>
                                        </a>
                                    </td>
                                </tr>	
				
				
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>