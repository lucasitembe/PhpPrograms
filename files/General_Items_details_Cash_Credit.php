<center>
<table width="100%">
<?php
	$Title = '<tr><td colspan="11"><hr></td></tr>
			<tr>
				<td width="5%"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td width="6%" style="text-align: left;"><b>PATIENT#</b></td>
				<td width="10%" style="text-align: left;"><b>SPONSOR</b></td>
				<td width="7%" style="text-align: right;"><b>RECEIPT#</b></td>
				<td width="7%" style="text-align: right;"><b>QUANTITY</b></td>
				<td style="text-align: right;" width="9%"><b>CASH</b></td>
				 
				<td style="text-align: right;" width="9%"><b>CREDIT</b></td>
                <td style="text-align: right;" width="9%"><b>MSAMAHA</b></td>
				
			</tr>
			<tr><td colspan="11"><hr></td></tr>';
	echo $Title;
	$Grand_Quantity = 0;
	$Grand_Total_Cash = 0;
	 
	$Grand_Total_Credit = 0;
    $Grand_Total_Msamaha=0;
	$get_transactions = mysqli_query($conn,"select pp.Registration_ID, ppl.Price, ppl.Discount, ppl.Quantity, pp.Patient_Payment_ID, pp.Payment_Date_And_Time,sp.Exemption,pr.Patient_Name,pr.Date_Of_Birth,sp.Guarantor_Name, pp.Billing_Type, pp.payment_type, pp.Fast_Track, pp.Pre_Paid
	                                from tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor sp,tbl_patient_registration pr where
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
			 
			$Total_Credit = 0;
            $Total_Msamaha = 0;
?>
			<tr>
				<td><?php echo ++$coun; ?></td>
				<td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
				<td><?php echo $data['Registration_ID']; ?></td>
				<td><?php echo $data['Guarantor_Name']; ?></td>
				<td style="text-align: right;"><?php echo $data['Patient_Payment_ID']; ?></td>
<?php                               
			$Quantity = $data['Quantity'];
			$Grand_Quantity = $Grand_Quantity + $data['Quantity'];
			$Total = (($data['Price'] - $data['Discount']) * $data['Quantity']);
			                                    
                                    if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'post'))){
                                    $Total_Msamaha += $Total;
                                     $Grand_Total_Msamaha += $Total;
                                    }  else {
                                        if((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '0') or (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'pre')){
	                                      	if($data['Fast_Track'] == '1'){
	                                      		 
	                                      	}else{
		                                        $Total_Cash += $Total;
		                                        $Grand_Total_Cash += $Total;
		                                    }
                                         }elseif((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') or (strtolower($data['Billing_Type']) == 'inpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'post')){
                                        $Total_Credit += $Total;
                                        $Grand_Total_Credit += $Total;
                                         } 
                                    }
                                
?>
				<td style="text-align: right;"><?php echo $Quantity; ?></td>
				<td style="text-align: right;">
<?php
					if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cash, 2); }else{ echo number_format($Total_Cash); }
?>
				</td>
				 
				<td style="text-align: right;">
<?php
					if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Credit, 2); }else{ echo number_format($Total_Credit); }
?>
				</td>
                                
                                <td style="text-align: right;">
<?php
					if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Msamaha, 2); }else{ echo number_format($Total_Msamaha); }
?>
				</td>
                                
<!--				<td style="text-align: right;">
<?php
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Credit + $Total_Cash+$Total_Msamaha, 2); }else{ echo number_format($Total_Credit + $Total_Cash+$Total_Msamaha); }
?>				
				</td>-->
			</tr>
<?php
		if($coun%21 == 0){
			echo $Title;
		}
		}
	}
?>
	<tr><td colspan="11"><hr></td></tr>
	<tr>
		<td colspan="5"><b>GRAND TOTAL</b></td>
		<td style="text-align: right;"><b><?php echo $Grand_Quantity; ?></b></td>
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cash, 2); } else{ echo number_format($Grand_Total_Cash); } ?></b>
		</td>
		 
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Credit, 2); } else{ echo number_format($Grand_Total_Credit); } ?></b>
		</td>
                
                <td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Msamaha, 2); } else{ echo number_format($Grand_Total_Msamaha); } ?></b>
		</td>
                
                
<!--		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cash + $Grand_Total_Credit + $Grand_Total_Msamaha,2); } else{ echo number_format($Grand_Total_Cash + $Grand_Total_Credit + $Grand_Total_Msamaha); } ?></b>
		</td>-->
	</tr>
</table>