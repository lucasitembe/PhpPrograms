<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
	if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo']['Branch_ID'])){
    	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
    	$Branch_ID = 0;
    }
?>
<a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>BACK</a>
<br/>
<br/>
<br/>
<br/>

<?php
	//Get current settup
	$select = mysqli_query($conn,"select DHIS_Source_Report from tbl_system_configuration where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$DHIS_Source_Report = $data['DHIS_Source_Report'];
		}
	}else{
		$DHIS_Source_Report = 'Final Diagnosis';
	}


	if(isset($_POST['Submit_Form'])){
		if(isset($_POST['DHIS_Source_Report'])){
			$DHIS_Source_Report = $_POST['DHIS_Source_Report'];
		}else{
			$DHIS_Source_Report = 'Final Diagnosis';
		}
		$update = mysqli_query($conn,"update tbl_system_configuration set DHIS_Source_Report = '$DHIS_Source_Report' where Branch_ID = '$Branch_ID'");

		if($update){
			echo '<script type="text/javascript">
					alert("DHIS2 setting updates successfully");
					document.location = "dhisconfiguration.php?Dhis2Configuration=Dhis2ConfigurationThisPage";
				</script>';
		}else{
			echo '<script type="text/javascript">
					alert("Process fail!!. Please try again");
				</script>';
		}
	}
?>

<center>
<fieldset>
	<legend align="left" ><b>DHIS2 CONFIGURATION</b></legend>
	<form action="" method="POST">
		<table width="90%">
	        <tr>
				<td style="text-align: right;">DHIS2 Source Report</td>
				<td>
					<select name="DHIS_Source_Report" id="DHIS_Source_Report">
						<option <?php if($DHIS_Source_Report == 'Final Diagnosis'){ echo "selected='selected'"; } ?>>Final Diagnosis</option>
						<option <?php if($DHIS_Source_Report == 'Provisional Diagnosis'){ echo "selected='selected'"; } ?>>Provisional Diagnosis</option>
					</select>
				</td>
				<td width="10%" style="text-align: center;">
					<input type="submit" name="UPDATE" value="UPDATE" class="art-button-green">
					<input type="hidden" name="Submit_Form" value="true">
				</td>
			</tr>
	    </table>
    </form>
</fieldset>  
</center>
<?php
    include("./includes/footer.php");
?>