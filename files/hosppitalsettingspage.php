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
            <legend align=center><b>HOSPITAL CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='addnewhospsettings.php?AddNewSystem=AddNewSystemThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Settings
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='edithospsettings.php?EditReceptient=EditReceptient'> 
                        <button style='width: 100%; height: 100%'>
                            Edit Settings
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