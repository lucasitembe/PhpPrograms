<table width="100%">
	<tr><td colspan="5"><hr></td></tr>
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>CATEGORY NAME</b></td>
		<td style="text-align: center;" width="15%"><b>NO OF COSTUMER</b></td>
		<td style="text-align: center" width="15%"><b>NO OF SERVICES</b></td>
		<td style="text-align: right" width="15%"><b>TOTAL CANCELLED</b>&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr><td colspan="5"><hr></td></tr>
<?php
	$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                    tbl_item_category ic order by ic.Item_Category_Name") or die(mysqli_error($conn));
	$num_cat = mysqli_num_rows($get_categories);
	if($num_cat > 0){
		$temper = 0;
		$Grand_Total_Cancelled = 0;
		$Grand_Quantity = 0;
		while ($cat = mysqli_fetch_array($get_categories)) {
			$Item_Category_ID = $cat['Item_Category_ID'];
			$Quantity = 0;
			$Total_Cancelled = 0;
?>
			<tr><td><b><?php echo ++$temper; ?></b></td>
			<td style='color: #0079AE;'><b><label onclick="Preview_Category_Details('<?php echo $Section; ?>',<?php echo $cat['Item_Category_ID']; ?>)"><?php echo strtoupper($cat['Item_Category_Name']); ?></label></b></td>
<?php
			//get quantity, cash & credit transactions
			$get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type  from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID and
	                                        ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id' ") or die(mysqli_error($conn));
			$num_quantity = mysqli_num_rows($get_Quantiry);
			if($num_quantity > 0){
				while($Det = mysqli_fetch_array($get_Quantiry)){
					$Quantity = $Quantity + $Det['Quantity'];
					$Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
					$Total_Cancelled = $Total_Cancelled + (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
					$Grand_Total_Cancelled += (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
				}
			}
			//get total patients
			$get_patients = mysqli_query($conn,"select pp.Registration_ID from
	                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
	                                        ic.Item_Category_ID = isu.Item_Category_ID and
	                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID and
	                                        ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			echo "<td style='text-align: center;'>".$Patient_No."</td>";
			echo "<td style='text-align: center;'>".$Quantity."</td>";
			echo "<td style='text-align: right;'>";
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cancelled, 2); }else{ echo number_format($Total_Cancelled); }
			echo "&nbsp;&nbsp;&nbsp;</td>";
		}
?>
	<tr><td colspan="5"><hr></td></tr>
	<tr>
		<td colspan="3"><b>GRAND TOTAL</b></td>
		<td style="text-align: center;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Grand_Quantity; }else{ echo $Grand_Quantity; } ?></b></td>
		<td style="text-align: right;"><b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cancelled, 2); }else{ echo $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cancelled); } ?></b>&nbsp;&nbsp;&nbsp;</td>
	</tr>
<?php
	}
?>
</table>