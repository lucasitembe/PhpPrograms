<?php
	$htm .= '<center><table width="100%" border=1 style="border-collapse: collapse;">';
	$Title = '<thead><tr>
				<td width="5%"><b><span style="font-size: x-small;">SN</span></b></td>
				<td><b><span style="font-size: x-small;">PATIENT NAME</span></b></td>
				<td width="8%" style="text-align: left;"><b><span style="font-size: x-small;">PATIENT#</span></b></td>
				<td width="15%" style="text-align: left;"><b><span style="font-size: x-small;">SPONSOR</span></b></td>
				<td width="7%" style="text-align: center;"><b><span style="font-size: x-small;">RECEIPT#</span></b></td>
				<td width="7%" style="text-align: right;"><b><span style="font-size: x-small;">QUANTITY</span></b></td>
				<td style="text-align: right;" width="12%"><b><span style="font-size: x-small;">TOTAL CREDIT</span></b></td>
			</tr></thead>';
	$htm .= $Title;
	$Grand_Quantity = 0;
	$Grand_Total_Credit = 0;
	$get_transactions = mysqli_query($conn,"select pp.Registration_ID, ppl.Price, ppl.Discount, ppl.Quantity, pp.Patient_Payment_ID, pp.Payment_Date_And_Time,sp.Exemption,pr.Patient_Name, pr.Date_Of_Birth, sp.Guarantor_Name, pp.Billing_Type, pp.payment_type, pp.Pre_Paid
	                                from tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_sponsor sp, tbl_patient_registration pr,tbl_sponsor as ts where
	                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                pp.Registration_ID = pr.Registration_ID and
	                                pp.Sponsor_ID = sp.Sponsor_ID and
                                        $filter ts.Exemption='no' and
                                        ts.Sponsor_ID=pp.Sponsor_ID and
	                                i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));

	$num_items = mysqli_num_rows($get_transactions);
	if($num_items > 0){
		$coun = 0;
		while($data = mysqli_fetch_array($get_transactions)){
			$Total_Credit = 0;
			$htm .=	'<tr>
						<td><span style="font-size: x-small;">'.++$coun.'</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($data['Patient_Name'])).'</span></td>
						<td><span style="font-size: x-small;">'.$data['Registration_ID'].'</span></td>
						<td><span style="font-size: x-small;">'.$data['Guarantor_Name'].'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$data['Patient_Payment_ID'].'</span></td>';
                        if ($data['Exemption'] == 'no' || $Sponsor_ID != 0) {
                          $Quantity = $data['Quantity'];
			$Grand_Quantity = $Grand_Quantity + $data['Quantity'];
			$Total = (($data['Price'] - $data['Discount']) * $data['Quantity']);
			if((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') or (strtolower($data['Billing_Type']) == 'inpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'post')){
                        $Total_Credit += $Total;
                        $Grand_Total_Credit += $Total;
                        }  
                        }
			
                
                
				$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.$Quantity.'</span></td><td style="text-align: right;">';
			
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit).'</span>'; }
				$htm .=	'</td></tr>';
		}
	}
	$htm .= '<tr>
				<td colspan="5"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
				<td style="text-align: right;"><b><span style="font-size: x-small;">'.$Grand_Quantity.'</span></b></td>
				<td style="text-align: right;"><b>';
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit, 2).'</span>'; } else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span>'; }
				$htm .= '</b></td></tr></table>';