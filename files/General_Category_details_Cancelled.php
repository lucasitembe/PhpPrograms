<center>
<table width="95%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>SUB CATEGORY NAME</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF PATIENTS</b></td>
		<td width="10%" style="text-align: center;"><b>NO OF SERVICES</b></td>
		<td style="text-align: right;"><b>TOTAL CANCELLED</b></td>
	</tr>
	<tr><td colspan="5"><hr></td></tr>
<?php
	$Grand_Total_Cancelled = 0;
	$Grand_Quantity = 0;

	//select sub categories
	$get_sub_cat = mysqli_query($conn,"select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name from tbl_item_subcategory isu where Item_Category_ID = '$Item_Category_ID' group by isu.Item_Subcategory_ID order by isu.Item_Subcategory_Name") or die(mysqli_error($conn));

	$num_sub_cat = mysqli_num_rows($get_sub_cat);
	if($num_sub_cat > 0){
		$tem = 0;
		while($rowz = mysqli_fetch_array($get_sub_cat)){
			$Quantity = 0;
			$Total_Cancelled = 0;
			$Item_Subcategory_ID = $rowz['Item_Subcategory_ID'];
?>
			<tr><td><?php echo ++$tem; ?></td><td style="text-align: left; color: #0079AE;"><label onclick="Preview_Sub_Category_Details('<?php echo $Section; ?>',<?php echo $rowz['Item_Subcategory_ID']; ?>)"><b><?php echo strtoupper($rowz['Item_Subcategory_Name']); ?></b></label></td>
<?php
			//get quantity, cash & credit transactions
			$get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type, pp.payment_type, pp.Transaction_status  from
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
	                                        isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
	                                        $filter
	                                        i.Item_ID = ppl.Item_ID AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
			$Patient_No = mysqli_num_rows($get_patients);
			echo "<td style='text-align: center;'>".$Patient_No."</td>";
			echo '<td style="text-align: center;">'.$Quantity.'</td><td style="text-align: right;">';
				if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Total_Cancelled, 2); }else{ echo number_format($Total_Cancelled); }
			echo '</td></tr>';
?>
<?php
		}
	}
?>
		<tr><td colspan="5"><hr></td></tr>
		<tr>
			<td colspan="3"><b>GRAND TOTAL</b></td>
			<td style="text-align: center;"><?php echo $Grand_Quantity; ?></td>
			<td style="text-align: right;">
				<b><?php if($_SESSION['systeminfo']['price_precision']=='yes'){ echo number_format($Grand_Total_Cancelled, 2); } else{ echo number_format($Grand_Total_Cancelled); } ?></b>
			</td>
		</tr>
		</table>
</center>