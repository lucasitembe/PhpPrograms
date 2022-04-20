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

    $from = "";

    if(isset($_GET['from']) && $_GET['from'] == "smsConfig") {
        $from = $_GET['from'];
    } else {
        $from = "";
    }

    if(isset($_GET['SMSConfiguration']) && $_GET['SMSConfiguration'] == "SMSConfiguration") {
        if($from != "" && $from == "smsConfig") {
?>
<a href="setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage&from=<?php echo $from; ?>" class="art-button-green">BACK</a>
<?php
    } else {
?>
<a href="receptionsetup.php?ReceptionSetup=ReceptionSetupThisPage&from=<?php echo $from; ?>" class="art-button-green">BACK</a>

<?php
    }
?>
<?php
    } else {
?>
<a href="receptionsetup.php?ReceptionSetup=ReceptionSetupThisPage" class="art-button-green">BACK</a>
<?php
    }   
?>

<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>SMS ALERT CONFIGURATION</b></legend>
        <center><table width = 60%>
			<tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='SMSConfiguration_Template.php?AddNewCompany=AddNewCompanyThisPage&from=<?php echo $from; ?>'>
                        <button style='width: 100%; height: 100%'>
                            Add or Edit SMS Template
                        </button>
                    </a>
                </td>
            </tr> 
            <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Add or Edit SMS Alert
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