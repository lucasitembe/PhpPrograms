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
<?php
	session_start();
	include("./includes/connection.php");
	$Amount = 0;
	$Total = 0;
	$Control = 'true';

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	//get patient details
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, sp.Guarantor_Name, pr.Phone_Number from
							tbl_patient_registration pr, tbl_payment_cache pc, tbl_sponsor sp where
							pr.Registration_ID = pc.Registration_ID and
							pc.Payment_Cache_ID = '$Payment_Cache_ID' and
							pr.Sponsor_ID = sp.Sponsor_ID") or die(mysqli_error($conn));
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


	//calculate amount
	$get_amount = mysqli_query($conn,"select ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount
                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn));

	$no = mysqli_num_rows($get_amount);
	if($no > 0){
		while ($data = mysqli_fetch_array($get_amount)) {
            //generate Quantity
            if($data['Edited_Quantity'] != 0){
                $Qty = $data['Edited_Quantity'];
            }else{
                $Qty = $data['Quantity'];
            }

            //check for non zero price & quantity 
            if($data['Price'] < 1 || $Qty < 1){
            	$Control = 'false';
            }

            $Total = (($data['Price'] - $data['Discount']) * $Qty);
            $Amount += $Total;
        }
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
				<input type="text" name="Amount_Required" id="Amount_Required" value="<?php echo number_format($Amount); ?>">
			</td>
			<td style="text-align: right;"><b>Payment Mode</b></td>
			<td>
				<select name="Payment_Mode" id="Payment_Mode">
					<option selected="selected" value=""></option>
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
		<?php if($Control == 'true'){ ?>
				<input type="button" value="CREATE eBILL" class="art-button-green" onclick="Create_ePayment_Bill();">
		<?php }else{ ?>
				<input type="button" name="Warning" id="Warning" value="CREATE eBILL" class="art-button-green" onclick="Warning_Create_ePayment_Bill()">
		<?php } ?>
			</td>
		</tr>
	</table>
</fieldset>