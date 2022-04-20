<?php
	session_start();
	include("./includes/connection.php");
	$Transaction_Status = '';
	if(isset($_GET['Invoice_Number'])){
		$Invoice_Number = $_GET['Invoice_Number'];
	}else{
		$Invoice_Number = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age = '';
    }

	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Phone_Number, pr.Date_Of_Birth, pr.Occupation,
							Payment_Code, Amount_Required
							from tbl_patient_registration pr, tbl_bank_transaction_cache tc where
							tc.Registration_ID = pr.Registration_ID and
							Payment_Code = '$Invoice_Number'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Registration_ID = $data['Registration_ID'];
			$Gender = $data['Gender'];
			$Phone_Number = $data['Phone_Number'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Amount_Required = $data['Amount_Required'];
			$Payment_Code = $data['Payment_Code'];
			$Occupation = $data['Occupation'];

			//calculate patient age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
		}
	}else{
		$Patient_Name = '';
		$Registration_ID = '';
		$Gender = '';
		$Phone_Number = '';
		$Date_Of_Birth = '';
		$Amount_Required = 0;
		$Payment_Code = '';
		$Occupation = '';
	}

	//check if invoice number already paid
	$check = mysqli_query($conn,"select Payment_Code, Patient_Payment_ID from tbl_patient_payments where Payment_Code = '$Invoice_Number'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($check);
	if($no > 0){
		$Transaction_Status = 'Paid';
		while ($row = mysqli_fetch_array($check)) {
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
		}

		//get epayment details required
		$get_details = mysqli_query($conn,"select Transaction_Ref, Transaction_Date from tbl_bank_api_payments_details where Payment_Code = '$Invoice_Number'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($get_details);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($get_details)) {
				$Transaction_Ref = $dt['Transaction_Ref'];
				$Transaction_Date = $dt['Transaction_Date'];
			}
		}else{
			$Transaction_Ref = '';
			$Transaction_Date = '';
		}
	}else{
		$Transaction_Ref = '';
		$Transaction_Date = '';
		if($num > 0){
			$Transaction_Status = 'Not Paid';
		}
	}
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="20%"><b>Patient Name</b></td>
            <td width="25%"><input type="text" name="P_Name" id="P_Name" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" placeholder="Patient Name"></td>
            <td style="text-align: right;" width="22%"><b>Patient Number</b></td>
            <td><input type="text" name="Patient_No" id="Patient_No" readonly="readonly" value="<?php echo ucwords(strtolower($Registration_ID)); ?>" placeholder="Patient Number"></td>
        </tr>
        <tr>
            <td width="20%"><b>Gender</b></td>
            <td width="25%"><input type="text" name="P_Gender" id="P_Gender" readonly="readonly" value="<?php echo ucwords(strtolower($Gender)); ?>" placeholder="Gender"></td>
            <td style="text-align: right;"><b>Phone Number</b></td>
            <td><input type="text" name="Phone_No" id="Phone_No" readonly="readonly" value="<?php echo $Phone_Number; ?>" placeholder="Phone Number"></td>
        </tr>
        <tr>
            <td width="20%"><b>Age</b></td>
            <td width="25%"><input type="text" name="Patient_Age" id="Patient_Age" readonly="readonly" value="<?php echo $age; ?>" placeholder="Patient Age"></td>
            <td style="text-align: right;"><b>Occupation</b></td>
            <td><input type="text" name="Patient_Occupation" id="Patient_Occupation" readonly="readonly" value="<?php echo $Occupation; ?>" placeholder="Patient Occupation"></td>
        </tr>
        
    </table>
</fieldset>
<fieldset>
    <table width="100%">
    	<tr>
            <td width="20%"><b>Invoice Number</b></td>
            <td width="25%"><input type="text" name="Invoice_No" id="Invoice_No" readonly="readonly" value="<?php echo $Payment_Code; ?>" placeholder="Invoice Number"></td>
            <td style="text-align: right;" width="22%"><b>Amount Required</b></td>
            <td><input type="text" name="Amount_Required" id="Amount_Required" readonly="readonly" value="<?php echo number_format($Amount_Required); ?>" placeholder="Amount"></td>
        </tr>
    	<tr>
            <td width="20%"><b>Transaction Reference</b></td>
            <td width="25%"><input type="text" name="Transaction_Ref" id="Transaction_Ref" readonly="readonly" value="<?php echo $Transaction_Ref; ?>" placeholder="Invoice Number"></td>
            <td style="text-align: right;" width="22%"><b>Reference Date</b></td>
            <td><input type="text" name="Reference_Date" id="Reference_Date" readonly="readonly" value="<?php echo $Transaction_Date; ?>" placeholder="Amount"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
	<?php if($num > 0){ ?>
    <table width="100%">
        <tr>
       		<td width="20%"><b>Transaction Status</b></td>
            <td width="25%"><input type="text" name="Transaction_Status" id="Transaction_Status" readonly="readonly" value="<?php echo $Transaction_Status; ?>" placeholder="Transaction Status"></td>
       		<td style="text-align: center;"  id="ePayment_Button_Area">
       		<?php if($num > 0){ ?>
       			<input type="button" value="PRINT INVOICE" class="art-button-green" onclick="Print_Payment_Code('<?php echo $Payment_Code; ?>')">
       			<?php
       				//display receipt button if invoice already paid
       				if(strtolower($Transaction_Status) == 'paid'){
       			?>
       					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       					<input type="button" value="PRINT RECEIPT" class="art-button-green" onclick="Print_Receipt_Payment(<?php echo $Patient_Payment_ID; ?>)">
       			<?php
       				}
       			}
       		?>
       		</td>
        </tr>
    </table>
    <?php }else{ ?>
    	<input type="hidden" name="Transaction_Status" id="Transaction_Status" value="">
    	<center id="ePayment_Button_Area">
    		<span style="color: #037CB0;"><h3><b>NO RECORD FOUND</b></h3></span>
    	</center>
    <?php } ?>
</fieldset>