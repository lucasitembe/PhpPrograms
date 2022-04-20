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
		
		$account_name = trim($_POST['account_name']);
		$account_number = trim($_POST['account_number']);
		$branch_location = trim($_POST['branch_location']);
		$branch_name = trim($_POST['branch_name']);
		$bank_name = trim($_POST['bank_name']);
		$staus = trim($_POST['Status']);
		
		$results = mysqli_query($conn, "SELECT * FROM tbl_hospital_bank_accounts WHERE Status = 'Applicable'");

		if(mysqli_num_rows($results) > 0){
			mysqli_query($conn, "UPDATE tbl_hospital_bank_accounts SET account_name = '$account_name',account_number = '$account_number',branch_location = '$branch_location',branch_name = '$branch_name', bank_name = '$bank_name' WHERE 1") or die(mysqli_error($conn));
		}else{
		
		mysqli_query($conn, "INSERT INTO tbl_hospital_bank_accounts(account_name, account_number, branch_location, branch_name, bank_name) VALUES('$account_name', '$account_number', '$branch_location','$branch_name', '$bank_name')") or die(mysqli_error($conn));
		}

		



	}

	$results = mysqli_query($conn, "SELECT * FROM tbl_hospital_bank_accounts WHERE Status = 'Applicable'");

		$account_name = '';
		$account_number = '';
		$branch_location = '';
		$branch_name = '';
		$bank_name = '';
		$status = '';
	while($row = mysqli_fetch_assoc($results)){
		$account_name = $row['account_name'];
		$account_number = $row['account_number'];
		$branch_location = $row['branch_location'];
		$branch_name = $row['branch_name'];
		$bank_name = $row['bank_name'];
		$status = $row['Status'];
	}
 

	//die(var_dump(($api_values)));
	//$api_values = (json_decode($api_file_contents));
	
?>
<fieldset>
<legend>HOSPITAL BANK DETAILS</legend>
<form action='#' method='post'>
	<div class=' '>
		<table   class='table '>
			<tr><th><label>API NAME</label></th><th>VALUE</th></tr>
			<tr>
				<td>
					<label>ACCOUNT NAME:</label>
				</td>
				<td>
<textarea name='account_name' class='form-control'><?=trim($account_name);?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label>ACCOUNT NUMBER:</label>
				</td>
				<td>
<textarea name='account_number' class='form-control'><?=trim($account_number);?></textarea>
				</td>
			</tr>
<tr>
				<td>
					<label>BANK NAME:</label>
				</td>
				<td>
<textarea name='bank_name' class='form-control'><?=trim($bank_name);?></textarea>
				</td>
			</tr>
<tr>
				<td>
					<label>BRANCH NAME:</label>
				</td>
				<td>
<textarea name='branch_name' class='form-control'><?=trim($branch_name);?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label>BRANCH LOCATION:</label>
				</td>
				<td>
<textarea name='branch_location' class='form-control'><?=trim($branch_location);?></textarea>
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
    <input type='submit' style='height:50px; name='submit' font-size:18px;' class='btn-primary btn-block btn-bg' value='Update API'>

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
