<?php
	$htm .= '<br/><table width="100%" border=1 style="border-collapse: collapse;">
				<thead><tr>
					<td width="5%"><span style="font-size: x-small;"><b>SN</b></span></td>
					<td><span style="font-size: x-small;"><b>CATEGORY NAME</b></span></td>
					<td style="text-align: center" width="8%"><span style="font-size: x-small;"><b>NO OF PATIENTS</b></span></td>
					<td style="text-align: center" width="10%"><span style="font-size: x-small;"><b>NO OF SERVICES</b></span></td>
					<td style="text-align: right" width="10%"><span style="font-size: x-small;"><b>CASH</b></span></td>
					 
					<td style="text-align: right" width="10%"><span style="font-size: x-small;"><b>CREDIT</b></span></td>
                    <td style="text-align: right" width="10%"><span style="font-size: x-small;"><b>MSAMAHA</b></span></td>
					';//<td style="text-align: right" width="10%"><span style="font-size: x-small;"><b>TOTAL</b></span></td>
				$htm .='</tr></thead>';
	$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                    tbl_item_category ic order by ic.Item_Category_Name") or die(mysqli_error($conn));
	$num_cat = mysqli_num_rows($get_categories);
	if($num_cat > 0){
		$temper = 0;
		$Grand_Total_Cash = 0;
		  
		$Grand_Total_Credit = 0;
		$Grand_Quantity = 0;
		while ($cat = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $cat['Item_Category_ID'];
			$Quantity = 0;
			$Total_Cash = 0;
			 
			$Total_Credit = 0;
                        $Total_Msamaha=0;
			$htm .= '<tr><td><span style="font-size: x-small;">'.++$temper.'</span></td><td><span style="font-size: x-small;">'.strtoupper($cat['Item_Category_Name']).'</span></td>';

			//get quantity, cash & credit transactions
			$get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type,ts.Exemption, pp.payment_type, Fast_Track, pp.Pre_Paid from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor ts where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        i.Item_ID = ppl.Item_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and
	                                        $filter
	                                        ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
			$num_quantity = mysqli_num_rows($get_Quantiry);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantiry)){
                    $Quantity = $Quantity + $Det['Quantity'];
				    $Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
                        $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                        if(($Det['Exemption']=='yes') && ((strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'post'))){
                        $Total_Msamaha += $Total;
			            $Grand_Total_Msamaha += $Total; 
                                    }  else {
                                      	$Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
					if((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '0') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'pre')){
						if($Det['Fast_Track']){
							 
				            
						}else{
				            $Total_Cash += $Total;
				            $Grand_Total_Cash += $Total;
				        }
			        }elseif((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '1') or (strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'post')){
			            $Total_Credit += $Total;
			            $Grand_Total_Credit += $Total;
			        }  
                                        
                                        
                                    }
                          
                                
                                
				}
			}
			//get total patients
			$get_patients = mysqli_query($conn,"select pp.Registration_ID from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        i.Item_ID = ppl.Item_ID and
	                                        $filter
	                                        ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			$htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>".$Patient_No."</span></td>";
			$htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>".$Quantity."</span></td>";
			$htm .= "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash).'</span>'; }
			$htm .= "</td>";
			 
			$htm .= "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Credit).'</span>'; }
			$htm .= "</td>";
                        
                        $htm .= "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Msamaha).'</span>'; }
			$htm .= "</td>";

//                        $htm .= "<td style='text-align: right;'>";
//				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash + $Total_Credit+$Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash + $Total_Credit+$Total_Msamaha).'</span>'; }
//			$htm .= "</td>";

                        
			
		}
	$htm .= '<tr><td colspan="3"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td><td style="text-align: center;"><span style="font-size: x-small;"><b>'.$Grand_Quantity.'</b></span></td>
				<td style="text-align: right;"><b>';
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash).'</span></td>'; }
	 
        $htm .= '</b></td>  <td style="text-align: right;"><b>';
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span>'; }
	$htm .= '</b></td>  <td style="text-align: right;"><b>';
        
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Msamaha).'</span>'; }
	$htm .= '</b></td>';
                //. '<td style="text-align: right;"><b>';
    
//	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit + $Grand_Total_Cash+$Grand_Total_Msamaha, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit + $Grand_Total_Cash+$Grand_Total_Msamaha).'</span>'; }
//	$htm .= '</b></td></tr>';
	}
	$htm .= '</table>';
?>