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
	if(isset($_GET['customerType'])){
		$customerType = $_GET['customerType'];
	}else{
		$customerType = '';
	}

	//get customer details
	if($customerType=='CUSTOMER'){
        $select =mysqli_query($conn,"SELECT Patient_Name AS customer_name, Registration_ID AS customerID,Phone_Number FROM tbl_patient_registration WHERE Registration_ID= $Registration_ID");
    }
    if($customerType=='SPONSOR'){
        $select =mysqli_query($conn,"SELECT Sponsor_ID AS customerID, Guarantor_Name AS customer_name,Phone_Number FROM tbl_sponsor WHERE Sponsor_ID=$Registration_ID");
    }
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$customer_name = $data['customer_name'];
			$Phone_Number = $data['Phone_Number'];
		}
	}else{
		$customer_name = '';
		$Phone_Number = '';
	}
?>

<?php
	//calculate total
	$get_total = mysqli_query($conn,"select sum(Amount * IF(Quantity IS NULL,1,Quantity)) as Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nums = mysqli_num_rows($get_total);
	if($nums > 0){
            while ($data = mysqli_fetch_array($get_total)) {
                    $Amount = number_format($data['Amount']);
            }
	} else {
		$Amount = 0;
	}
?>

<fieldset style="background-color:white;">
	<table width="100%">
		<tr>
			<td width="18%" style="text-align: right;"><b><?=($customerType=='SPONSOR')?"Sponsor Name":"Customer Name";?></b></td>
			<td style="text-align: left;">
				<input type="text" value="<?php echo ucwords(strtolower($customer_name)); ?>" readonly="readonly">
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
					<option value="Bank_Payment" selected="selected" >Bank Payment</option>
					<option value="Mobile_Payemnt">Mobile Payment</option>
				</select>
				<!-- <select name="Payment_Mode" id="Payment_Mode">
					<option selected="selected" value=""></option>
					<option>Airtel Money</option>
					<option>CRDB Bank</option>
					<option>UMOJA switch</option>
					<option>M-Pesa</option>
					<option>Tigo Pesa</option>
				</select> -->
			</td>
		</tr>
		
	</table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: right;">
				<!-- <input type="button" value="CREATE eBILL" class="art-button-green" onclick="Create_ePayment_Bill();"> -->
				<input type="button" value="CREATE eBILL" class="art-button-green" onclick="Verify_Customer_ePayment_Bill();">
			</td>
		</tr>
	</table>
</fieldset>
