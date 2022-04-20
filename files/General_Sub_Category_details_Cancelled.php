<center>
<table width="95%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>ITEM NAME</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF PATIENTS</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF SERVICES</b></td>
		<td style="text-align: right;" width="15%"><b>TOTAL CREDIT</b></td>
	</tr>
	<tr><td colspan="5"><hr></td></tr>
<?php
	$Grand_Quantity = 0;
	$Grand_Total_Cancelled = 0;
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
			echo '<tr>
					<td>'.++$coun.'</td>';
?>
					<td style="color: #0079AE;"><b><label onclick="Display_Items_Details('<?php echo $Section; ?>',<?php echo $data['Item_ID']; ?>)"><?php echo $data['Product_Name']; ?></label></b></td>
<?php

			//get quantity, cash & credit transactions
			$get_Quantity = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type, pp.Transaction_status from
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
					if(strtolower($Det['Transaction_status']) == 'cancelled'){
			            $Total_Cancelled += $Total;
			            $Grand_Total_Cancelled += $Total;
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
	                                        ppl.Item_ID = '$Item_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			echo "<td style='text-align: center;'>".$Patient_No."</td>";
			echo '<td style="text-align: center;">'.$Quantity.'</td>';
			echo '<td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cancelled, 2); }else{ echo number_format($Total_Cancelled); }
			echo '</td></tr>';
		}
	}
?>
	<tr><td colspan="5"><hr></td></tr>
	<tr>
		<td colspan="3"><b>GRAND TOTAL</b></td>
		<td style="text-align: center;"><b><?php echo $Grand_Quantity; ?></b></td>
		<td style="text-align: right;">
			<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cancelled, 2); } else{ echo number_format($Grand_Total_Cancelled); } ?></b>
		</td>
	</tr>
</table>