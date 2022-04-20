<?php
	session_start();
	include("./includes/connection.php");
	$Sponsor_filter = '';

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}
	
	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}
	
	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = 'All';
	}
	
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = '0';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor = $_GET['Sponsor_ID'];
	}else{
		$Sponsor = '0';
	}
	
	if(isset($_GET['Cash_Option'])){
		$Cash_Option = $_GET['Cash_Option'];
	}else{
		$Cash_Option = 'no';
	}

	//check if cash details not needed
	$C_Msamaha = '';
	$C_Cash = '';
	if($Cash_Option == 'yes'){
		//get Cash Sponsor id
		$slct = mysqli_query($conn,"select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'CASH'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$C_Cash = $dt['Sponsor_ID'];
			}
		}
	}

	if(isset($_GET['Msamaha_Option'])){
		$Msamaha_Option = $_GET['Msamaha_Option'];
	}else{
		$Msamaha_Option = 'no';
	}

	//check if msamaha not needed
	if($Msamaha_Option == 'yes'){
		$C_Msamaha = 'no';
	}

	$filter = " pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and pp.Transaction_status <> 'cancelled' and ";

	if($Employee_ID != null && $Employee_ID != '' && $Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and ";
	}
?>

<legend><b>REVENUE COLLECTION BY SPONSOR</b></legend>
<table width="100%">
<?php
	$Title = "<tr><td colspan='5'><hr></td></tr>
				<tr>
				    <td width=5%><b>SN</b></td>
				    <td><b>SPONSOR NAME</b></td>
				    <td style='text-align: right;' width='15%'><b>CASH</b></td>
				    <td style='text-align: right;' width='15%'><b>CREDIT</b></td>
				    <td style='text-align: right;' width='15%'><b>MSAMAHA</b>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr><td colspan='5'><hr></td></tr>";
	echo $Title;
	$temp = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Msamaha = 0;
	//get sponsors
	if($Sponsor != null && $Sponsor != '' && $Sponsor != '0'){
		$get_sponsors = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID, Exemption from tbl_sponsor where Sponsor_ID = '$Sponsor' order by Guarantor_Name") or die(mysqli_error($conn));
	}else{
		$get_sponsors = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID, Exemption from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
	}

	$num_sponsors = mysqli_num_rows($get_sponsors);
	if($num_sponsors > 0){
		while ($sp_details = mysqli_fetch_array($get_sponsors)) {
			$Sponsor_ID = $sp_details['Sponsor_ID'];
			$Exemption = $sp_details['Exemption'];
			$Total_Cash = 0;
			$Total_Credit = 0;
			$Total_Msamaha = 0;

			if($Billing_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
										$filter
										(pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}else if($Billing_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
										$filter
										(pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}else{
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
										$filter
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}

			while ($row = mysqli_fetch_array($select)) {
				$Amount = (($row['Price'] - $row['Discount']) * $row['Quantity']);
				if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
					if($C_Cash != ''){
						if(strtolower($row['Billing_Type']) == 'outpatient cash' && strtolower($row['Billing_Type']) != 'inpatient cash' && strtolower($row['payment_type']) != 'pre' && $C_Cash != $row['Sponsor_ID']){
							$Total_Cash += $Amount;
				        	$Grand_Total_Cash += $Amount;
						}
					}else{
				        $Total_Cash += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }
			    }else if(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) && strtolower($Exemption) == 'no'){
					$Total_Credit += $Amount;
					$Grand_Total_Credit += $Amount;
			    }else if(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) && strtolower($Exemption) == 'yes'){
			        if($C_Msamaha == ''){
				        $Total_Msamaha += $Amount;
				        $Grand_Total_Msamaha += $Amount;
				    }
			    }
			}
?>
			<tr>
			    <td><?php echo ++$temp; ?></td>
			    <td><?php echo strtoupper($sp_details['Guarantor_Name']); ?></td>

			    <td style='text-align: right;'><?php echo number_format($Total_Cash); ?></td>
			    <td style='text-align: right;'><?php echo number_format($Total_Credit); ?></td>
			    <td style='text-align: right;'><?php echo number_format($Total_Msamaha); ?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
<?php
			if($temp%20 == 0){
				echo $Title;
			}
		}
	}
?>
<tr><td colspan="5"><hr></td></tr>
<tr>
    <td colspan="2"><b>GRAND TOTAL</b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Cash); ?></b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Credit); ?></b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Msamaha); ?>&nbsp;&nbsp;&nbsp;</b></td>
</tr>
<tr><td colspan="5"><hr></td></tr>
</table>