<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/><br/><br/><br/><br/><br/>
<fieldset>
            <legend align=center><b>DOCTOR CONSULTATION CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='doctor_consult_arch.php?setarchitecture=setarchitectureThisPage'>
                        <button style='width: 100%; height: 100%' class="">
                            Doctor Consultation architecture
                        </button>
                    </a>
                </td>
              	
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='item_alert_control.php'>
                        <button style='width: 100%; height: 100%' class="">
                            Item Odering Duration Control
                        </button>
                    </a>
                </td>
              	
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>