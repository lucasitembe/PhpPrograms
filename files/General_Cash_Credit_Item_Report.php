<?php
	$htm .= '<center><table width="100%" border=1 style="border-collapse: collapse;">';
	$Title = '<thead><tr>
				<td width="5%"><b><span style="font-size: x-small;">SN</span></b></td>
				<td><b><span style="font-size: x-small;">PATIENT NAME</span></b></td>
				<td width="8%" style="text-align: left;"><b><span style="font-size: x-small;">PATIENT#</span></b></td>
				<td width="13%" style="text-align: left;"><b><span style="font-size: x-small;">SPONSOR</span></b></td>
				<td width="7%" style="text-align: right;"><b><span style="font-size: x-small;">RECEIPT#</span></b></td>
				<td width="7%" style="text-align: right;"><b><span style="font-size: x-small;">QUANTITY</span></b></td>
				<td style="text-align: right;" width="9%"><b><span style="font-size: x-small;">CASH</span></b></td>
		 
				<td style="text-align: right;" width="9%"><b><span style="font-size: x-small;">CREDIT</span></b></td>
                <td style="text-align: right;" width="9%"><b><span style="font-size: x-small;">MSAMAHA</span></b></td>
				
			</tr></thead>';
	$htm .= $Title;
	$Grand_Quantity = 0;
	$Grand_Total_Cash = 0;
	 
	$Grand_Total_Credit = 0;
    $Grand_Total_Msamaha=0;
	$get_transactions = mysqli_query($conn,"select pp.Registration_ID, ppl.Price, ppl.Discount, ppl.Quantity, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pr.Patient_Name,sp.Exemption, pr.Date_Of_Birth, sp.Guarantor_Name, pp.Billing_Type, pp.payment_type, pp.Fast_Track, pp.Pre_Paid
	                                from tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor sp, tbl_patient_registration pr where
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
            $Total_Msamaha=0;
			$htm .=	'<tr>
						<td><span style="font-size: x-small;">'.++$coun.'</span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($data['Patient_Name'])).'</span></td>
						<td><span style="font-size: x-small;">'.$data['Registration_ID'].'</span></td>
						<td><span style="font-size: x-small;">'.$data['Guarantor_Name'].'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">'.$data['Patient_Payment_ID'].'</span></td>';

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
                                
                
				$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.$Quantity.'</span></td><td style="text-align: right;">';
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash).'</span>'; }

				$htm .=	'</td><td style="text-align: right;">';
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit).'</span>'; }
				
                                $htm .=	'</td><td style="text-align: right;">';
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Msamaha).'</span>'; }
				$htm .=	'</td>';
			//if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit + $Total_Cash+$Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit + $Total_Cash+$Total_Msamaha).'</span>'; }
				$htm .= '</tr>';
		}
	}
	$htm .= '<tr>
				<td colspan="5"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
				<td style="text-align: right;"><b><span style="font-size: x-small;">'.$Grand_Quantity.'</span></b></td>
				<td style="text-align: right;"><b>';
			$htm .= '</b></td><td style="text-align: right;"><b>';
			if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit, 2).'</span>'; } else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span>'; }
				$htm .= '</b></td><td style="text-align: right;"><b>';
                                
                        if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Msamaha, 2).'</span>'; } else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Msamaha).'</span>'; }
				$htm .= '</b></td>';
			//if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash + $Grand_Total_Credit+$Grand_Total_Msamaha, 2).'</span>'; } else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash + $Grand_Total_Credit+$Grand_Total_Msamaha).'</span>'; }
				$htm .= '</tr></table>';