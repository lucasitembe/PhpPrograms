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
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>PROCUREMENT CONFIGURATION</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='configureapprovallevel.php?ConfigureApprovalLevel=ConfigureApprovalLevelThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Configure Approval Level
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='assignemployeeapprovallevel.php?AssignEmployeeApprovalLevel=AssignEmployeeApprovalLevelThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Assign Employee Approval Level
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='procurementotherconfiguration.php?ProcurementOtherConfiguration=ProcurementOtherConfigurationThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Stock Movement Configurations
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