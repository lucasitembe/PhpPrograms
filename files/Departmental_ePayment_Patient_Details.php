<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	//get patient details
	$select = mysqli_query($conn,"select Patient_Name, Guarantor_Name, pr.Phone_Number, pr.Gender from tbl_patient_registration pr, tbl_sponsor sp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Phone_Number = $data['Phone_Number'];
			$Gender = $data['Gender'];
		}
	}else{
		$Patient_Name = '';
		$Guarantor_Name = '';
		$Phone_Number = '';
		$Gender = '';
	}
?>

<?php
//

$lookup='tbl_departmental_items_list_cache';
if(isset($_GET['src']) && $_GET['src']=='drinp'){
  $lookup='tbl_inpatient_items_list_cache';  
}

	//calculate total
	$get_total = mysqli_query($conn,"select sum((Price - Discount) * Quantity) as Amount from $lookup where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nums = mysqli_num_rows($get_total);
	if($nums > 0){
		while ($data = mysqli_fetch_array($get_total)) {
			$Amount = number_format($data['Amount']);
		}
	}else{
		$Amount = 0;
	}
?>

<fieldset style="background-color:white;">
	<table width="100%">
		<tr>
			<td width="18%" style="text-align: right;"><b>Patient Name</b></td>
			<td style="text-align: left;">
				<input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly">
			</td>
			<td width="20%" style="text-align: right;"><b>Sponsor</b></td>
			<td style="text-align: left; width: 30%;">
				<input type="text" value="<?php echo strtoupper($Guarantor_Name); ?>" readonly="readonly">
			</td>
		</tr>
		<tr>
			<td width="20%" style="text-align: right;"><b>Gender</b></td>
			<td style="text-align: left; width: 30%;">
				<input type="text" value="<?php echo $Gender; ?>" id='Gender' readonly="readonly">
			</td>
			<td width="18%" style="text-align: right;"><b>Phone Number</b></td>
			<td style="text-align: left;">
				<input type="text" name="Patient_Phone_Number" id="Patient_Phone_Number" value="<?php echo $Phone_Number; ?>" oninput="Update_Phone_Number()" onkeyup="Update_Phone_Number()">
			</td>
		</tr>
		<tr>
			<td style="text-align: right;"><b>Amount</b></td>
			<td style="text-align: left;">
				<input type="text" name="Amount_Required" id="Amount_Required" value="<?php echo $Amount; ?>">
			</td>
			<td style="text-align: right;"><b>Payment Mode</b></td>
			<td>
				<select name="Payment_Mode" id="Payment_Mode">
					<option value="Bank_Payment">Bank Payment</option>
					<option value="Mobile_Payemnt">Mobile Payment</option>
				</select>
			</td>
		</tr>
		
	</table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: right;">
				<!-- <input type="button" value="CREATE eBILL" class="art-button-green" onclick="Create_ePayment_Bill();"> -->
				<input type="button" value="CREATE eBILL" class="art-button-green" onclick="Verify_ePayment_Bill();">
			</td>
		</tr>
	</table>
</fieldset>
