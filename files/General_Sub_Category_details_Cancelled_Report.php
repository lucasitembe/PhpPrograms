<?php
	$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
				<thead><tr>
					<td width="5%"><b><span style="font-size: x-small;">SN</span></b></td>
					<td><b><span style="font-size: x-small;">ITEM NAME</span></b></td>
					<td width="10%" style="text-align: center;"><span style="font-size: x-small;"><b>NO OF PATIENTS</b></span></td>
					<td width="10%" style="text-align: center;"><b><span style="font-size: x-small;">NO OF SERVICES</span></b></td>
					<td style="text-align: right;" width="18%"><b><span style="font-size: x-small;">TOTAL CANCELLED</span></b></td>
				</tr></thead>';
	$Grand_Quantity = 0;
	$Grand_Total_Credit = 0;
	$get_items = mysqli_query($conn,"select i.Item_ID, i.Product_Name from
                                tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                ic.Item_Category_ID = isu.Item_Category_ID and
                                isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                $filter
                                i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));

	$num_items = mysqli_num_rows($get_items);
	if($num_items > 0){
		$coun = 0;
		while($data = mysqli_fetch_array($get_items)){
			$Item_ID = $data['Item_ID'];
			$Quantity = 0;
			$Total_Cancelled = 0;
			$htm .= '<tr><td><span style="font-size: x-small;">'.++$coun.'</span></td>';
			$htm .= '<td><span style="font-size: x-small;">'.$data['Product_Name'].'</span></td>';

			//get quantity, cash & credit transactions
			$get_Quantity = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type  from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        i.Item_ID = ppl.Item_ID and
	                                        $filter
	                                        ppl.Item_ID = '$Item_ID' AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
			$num_quantity = mysqli_num_rows($get_Quantity);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantity)){
					$Quantity = $Quantity + $Det['Quantity'];
					$Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
					$Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
					$Total_Cancelled += $Total;
					$Grand_Total_Credit += $Total;
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
	                                        ppl.Item_ID = '$Item_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			$htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>".$Patient_No."</span></td>";
			$htm .= '<td style="text-align: center;"><span style="font-size: x-small;">'.$Quantity.'</span><td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled, 2).'</span>'; }else{ $htm .= '<span style="font-size: x-small;">'.number_format($Total_Cancelled).'</span>'; }
			$htm .= '</td></tr>';
		}
	}
	
	$htm .= '<tr>
				<td colspan="3"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
				<td style="text-align: center;"><b><span style="font-size: x-small;">'.$Grand_Quantity.'</span></b></td>
				<td style="text-align: right;"><b>';
	
	if($_SESSION['systeminfo']['price_precision']=='yes'){ 
		$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit, 2).'</span>'; 
	} else{ 
		$htm .= '<span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span>'; 
	}
	
	$htm .= '</b></td></tr></table>';