<?php
	$htm .= '<br/><table width="100%" border=1 style="border-collapse: collapse;">
				<thead><tr>
					<td width="5%"><span style="font-size: x-small;"><b>SN</b></span></td>
					<td><span style="font-size: x-small;"><b>CATEGORY NAME</b></span></td>
					<td style="text-align: right" width="15%"><span style="font-size: x-small;"><b>QUANTITY</b></span></td>
					<td style="text-align: right" width="15%"><span style="font-size: x-small;"><b>CANCELLED</b></span></td>
					<td style="text-align: right" width="15%"><span style="font-size: x-small;"><b>TOTAL</b></span></td>
				</tr></thead>';
	$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                    tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                    ic.Item_Category_ID = isu.Item_Category_ID and
                                    isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                    i.Item_ID = ppl.Item_ID and
                                    $filter
                                    pp.Currency_ID = '$Currency_ID' group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
	$num_cat = mysqli_num_rows($get_categories);
	if($num_cat > 0){
		$temper = 0;
		$Total_Cancelled = 0;
		$Grand_Total_Cancelled = 0;
		$Grand_Quantity = 0;
		while ($cat = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $cat['Item_Category_ID'];
			$Quantity = 0;
			$Total_Cancelled = 0;

			$htm .= '<tr><td><span style="font-size: x-small;">'.++$temper.'</span></td><td><span style="font-size: x-small;">'.strtoupper($cat['Item_Category_Name']).'</span></td>';

			//get quantity, cash & credit transactions
			$get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type  from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        i.Item_ID = ppl.Item_ID and
	                                        $filter
	                                        pp.Currency_ID = '$Currency_ID' and
	                                        ic.Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
			$num_quantity = mysqli_num_rows($get_Quantiry);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantiry)){
					$Quantity = $Quantity + $Det['Quantity'];
					$Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
					$Total_Cancelled = $Total_Cancelled + (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
				}
			}
			$htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".$Quantity."</span></td>";
			$htm .= "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled).'</span>'; }
			$htm .= "</td>";
			$htm .= "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled).'</span>'; }
			$htm .= "</td>";
		}
	$htm .= '<tr><td colspan="2"><span style="font-size: x-small;"><b>TOTAL</b></span></td><td style="text-align: right;"><span style="font-size: x-small;"><b>'.$Grand_Quantity.'</b></span></td>';
	$htm .= '<td style="text-align: right;"><b>';
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cancelled).'</span>'; }
	$htm .= '</b></td><td style="text-align: right;"><b>';
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cancelled + $Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled).'</span>'; }
	$htm .= '</b></td></tr><tr><td colspan="2"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td><td colspan="3" style="text-align: right;"><span style="font-size: x-small;">';
	if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;"><b>'.number_format($Grand_Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Cancelled).'</span>'; }
	$htm .= ' <b>'.$Currency_Code.'</b></b></span></td></tr>';
	}
	$htm .= '</table>';
?>