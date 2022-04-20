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
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    <a href='unknownDeadBody.php?DeadbodyPage=OutPatientAndInpatient' class='art-button-green'>
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
            <legend align="center"><b>DEAD BODY REGISTRATION - INPATIENT & OUTPATIENT</b> </legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='#?RegistrationDeadBody=RegistrationPage'>
                        <button style='width: 100%; height: 100%'>
                            Register Dead Body - Inpatient 
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Register Dead Body - Inpatient  
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
					
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='#.php'>
                        <button style='width: 100%; height: 100%'>
                             Register Dead Body - Outpatient
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Register Dead Body - Outpatient 
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