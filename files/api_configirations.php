<?php
include("./includes/connection.php");
include("./includes/header.php");
include("./includes/cleaninput.php");
include_once("./functions/items.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$employeeid = $_SESSION['userinfo']['Employee_ID'];
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>BACK</a>

<br/><br/>
<?php
	if(isset($_POST['form_submit'])){
		
		$price_update = trim($_POST['price_update']);
		$claim_submission = trim($_POST['claim_submission']);
		$excluded_products = trim($_POST['excluded_products']);
		$member_verification = trim($_POST['member_verification']);
		$member_registration = trim($_POST['member_registration']);
		$service_verification = trim($_POST['service_verification']);
		$claim_submission_token = trim($_POST['claim_submission_token']);
		

mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$price_update' WHERE API_Name =  'price_update'") or die(mysqli_error($conn));

mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$claim_submission' WHERE API_Name =  'claim_submission'") or die(mysqli_error($conn));
mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$claim_submission_token' WHERE API_Name =  'claim_submission_token'") or die(mysqli_error($conn));
mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$excluded_products' WHERE API_Name =  'excluded_products'") or die(mysqli_error($conn));

mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$member_verification' WHERE API_Name =  'member_verification'") or die(mysqli_error($conn));

mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$member_registration' WHERE API_Name =  'member_registration'") or die(mysqli_error($conn));

mysqli_query($conn, "UPDATE tbl_sponsor_api SET API_Value = '$service_verification' WHERE API_Name =  'service_verification'") or die(mysqli_error($conn));

	}
	$api_values = [];
	$api_file_contents = file_get_contents('api_list.txt','rb');
	$results = mysqli_query($conn, "SELECT * FROM tbl_sponsor_api WHERE Status = 'Applicable'");
	while($row = mysqli_fetch_assoc($results)){
		$api_values[$row['API_Name']] = $row['API_Value'];
	}
 
	extract($api_values);
	//die(var_dump(($api_values)));
	//$api_values = (json_decode($api_file_contents));
	
?>
<fieldset>
<legend>API CONFIGURATION SETUP</legend>
<form action='#' method='post'>
	<div class=' '>
		<table   class='table '>
			<tr><th><label>API NAME</label></th><th>VALUE</th></tr>
			<tr>
				<td>
					<label>Price Update:</label>
				</td>
				<td>
					<textarea name='price_update' class='form-control'>
<?=trim($price_update);?>
        </textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label>Online Claim Submition:</label>
				</td>
				<td>
					<textarea name='claim_submission' class='form-control'>
            <?=trim($claim_submission);?>
</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label>Claim Submition Token:</label>
				</td>
				<td>
					<textarea name='claim_submission_token' class='form-control'>
            <?=trim($claim_submission_token);?>
</textarea>
				</td>
			</tr>
<tr>
				<td>
					<label>Exluded Products:</label>
				</td>
				<td>
					<textarea name='excluded_products' class='form-control'>
            <?=trim($excluded_products);?>
</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label>Member Verification:</label>
				</td>
				<td>
					<textarea name='member_verification' class='form-control'>
            <?=trim($member_verification);?></textarea>
				</td>
			</tr>
<tr>
				<td>
					<label>Member Registration:</label>
				</td>
				<td>
					<textarea name='member_registration' class='form-control'>
            <?=trim($member_registration);?></textarea>
				</td>
			</tr>
<tr>
				<td>
					<label>Service Verification:</label>
				</td>
				<td>
					<textarea name='service_verification' class='form-control'>
            <?=trim($service_verification);?></textarea>
				</td>
			</tr>
      <tr>
        <td colspan="2">

        </td>
      </tr>
		</table>
	</div>
	<div class=' '>
    <input type='hidden' name='form_submit' value='form_submit'>
    <input type='submit' style='height:50px; font-size:18px;' name='submit' class='btn-primary btn-block btn-bg' value='Update API'>

			</div>
</form>
</fieldset>
<br>
<br>
<br>
<br>
<br>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>

<?php
include("./includes/footer.php");
?>
