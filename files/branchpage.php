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
            <legend align=center><b>BRANCHES CONFIGURATION</b></legend>
        <center><table width = 60%>
	    <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='addnewbranch.php?AddNewBranch=AddNewBranchThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Add New Company Branch
                        </button>
                    </a>
                </td>
            </tr> 
            <tr>
		<td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Edit Company Branch
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