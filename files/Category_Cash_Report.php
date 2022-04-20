<?php
	$htm .= '<br/><center>
				<table width="100%" border=1 style="border-collapse: collapse;">
					<thead><tr>
						<td width="5%"><span style="font-size: x-small;">SN</span></td>
						<td><span style="font-size: x-small;">SUB CATEGORY NAME</span></td>
						<td width="8%" style="text-align: center;"><span style="font-size: x-small;">NO OF PATIENTS</span></td>
						<td width="10%" style="text-align: center;"><span style="font-size: x-small;">NO OF SERVICES</span></td>
						<td style="text-align: right;" width="12%"><span style="font-size: x-small;">CASH</span></td>
						 
					</tr></thead>';
	$Grand_Total_Cash = 0;
	 
	$Grand_Quantity = 0;

	//select sub categories
		$get_sub_cat = mysqli_query($conn,"select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name from tbl_item_subcategory isu where 
								Item_Category_ID = '$Item_Category_ID' group by isu.Item_Subcategory_ID order by isu.Item_Subcategory_Name") or die(mysqli_error($conn));

	$num_sub_cat = mysqli_num_rows($get_sub_cat);
	if($num_sub_cat > 0){
		$tem = 0;
		while($rowz = mysqli_fetch_array($get_sub_cat)){
			$Quantity = 0;
			$Total_Cash = 0;
			 
			$Item_Subcategory_ID = $rowz['Item_Subcategory_ID'];

			$htm .= '<tr><td><span style="font-size: x-small;">'.++$tem.'</span></td>
						<td><span style="font-size: x-small;">'.strtoupper($rowz['Item_Subcategory_Name']).'</span></td>';

			//get quantity, cash & credit transactions
			$get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type, pp.Fast_Track, pp.Pre_Paid from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
			$num_quantity = mysqli_num_rows($get_Quantiry);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantiry)){
					$Quantity = $Quantity + $Det['Quantity'];
					$Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
					$Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
					if((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '0') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'pre')){
				        if($Det['Fast_Track'] == '1'){
				        	 
				        }else{
				            $Total_Cash += $Total;
				            $Grand_Total_Cash += $Total;
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
	                                        isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			$htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>".$Patient_No."</span></td>";
			$htm .= '<td style="text-align: center;"><span style="font-size: x-small;">'.$Quantity.'</span></td><td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cash).'</span>'; }
			 
	 
			$htm .= '</td></tr>';
		}
	}
		$htm .= '<tr>
					<td colspan="3"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
					<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Quantity.'</span></td>
					<td style="text-align: right;"><b>';
					if($_SESSION['systeminfo']['price_precision']=='yes'){
						$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash, 2).'</span>'; 
					} else{ 
						$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash).'</span>'; 
					}
		 
//					if($_SESSION['systeminfo']['price_precision']=='yes'){
//						$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash, 2).'</span>'; 
//					} else{ 
//						$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cash).'</span>'; 
//					}
		$htm .= '</b></td></tr></table></center>';