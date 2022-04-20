<center>
<table width="100%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>PATIENT NAME</b></td>
		<td width="13%" style="text-align: left;"><b>SPONSOR</b></td>
		<td width="7%" style="text-align: center;"><b>RECEIPT#</b></td>
		<td width="7%" style="text-align: right;"><b>QUANTITY</b></td>
		<td style="text-align: right;" width="12%"><b>CASH</b></td>
		 
	</tr>
	<tr><td colspan="8"><hr></td></tr>
<?php
	$Grand_Quantity = 0;
	$Grand_Total_Cash = 0;
	 
	$get_transactions = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pr.Patient_Name, pr.Date_Of_Birth, sp.Guarantor_Name, pp.Billing_Type, pp.payment_type, pp.Fast_Track, pp.Pre_Paid
	                                from tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr where
	                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                pp.Registration_ID = pr.Registration_ID and
	                                pp.Sponsor_ID = sp.Sponsor_ID and
	                                $filter
	                                i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));

	$num_items = mysqli_num_rows($get_transactions);
	if($num_items > 0){
		$coun = 0;
		while($data = mysqli_fetch_array($get_transactions)){
			$Total_Cash = 0;
			 
?>
			<tr>
				<td><?php echo ++$coun; ?></td>
				<td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
				<td><?php echo $data['Guarantor_Name']; ?></td>
				<td style="text-align: center;"><?php echo $data['Patient_Payment_ID']; ?></td>
<?php
			$Quantity = $data['Quantity'];
			$Grand_Quantity = $Grand_Quantity + $data['Quantity'];
			$Total = (($data['Price'] - $data['Discount']) * $data['Quantity']);
			if((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '0') or (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'pre')){
		        if($data['Fast_Track'] == '1'){
		        	 
		        }else{
		            $Total_Cash += $Total;
		            $Grand_Total_Cash += $Total;
		        }
	        }
?>
				<td style="text-align: right;"><?php echo $Quantity; ?></td>
				<td style="text-align: right;">
<?php
					if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cash, 2); }else{ echo number_format($Total_Cash); }
?>
				</td>
		 
			 
			</tr>
<?php
		}
	}
?>
	<tr><td colspan="8"><hr></td></tr>
	<tr>
		<td colspan="4"><b>GRAND TOTAL</b></td>
		<td style="text-align: right;"><b><?php echo $Grand_Quantity; ?></b></td>
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cash, 2); } else{ echo number_format($Grand_Total_Cash); } ?></b>
		</td>
	
	</tr>
</table>