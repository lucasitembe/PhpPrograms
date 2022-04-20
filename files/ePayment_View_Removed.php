<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;

	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}
?>

<fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Items_Fieldset_List'>
	<table width="100%">
		<tr><td colspan="5"><hr></td></tr>
		<tr>
			<td width="4%"><b>SN</b></td>
			<td><b>ITEM NAME</b></td>
			<td width="15%"><b>CHECK IN TYPE</b></td>
			<td width="9%" style="text-align: right;"><b>AMOUNT&nbsp;&nbsp;&nbsp;</b></td>
			<td width="9%" style="text-align: center;"><b>ACTION</b></td>
		</tr>
		<tr><td colspan="5"><hr></td></tr>
<?php
	$select = mysqli_query($conn,"select ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, i.Product_Name, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID
                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items i where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                ilc.Item_ID = i.Item_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'removed'") or die(mysqli_error($conn));

	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
		<tr>
			<td width="4%"><b><?php echo ++$temp; ?>.</b></td>
			<td><?php echo $data['Product_Name']; ?></td>
			<td width="15%"><?php echo $data['Check_In_Type']; ?></td>
			<td width="9%" style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']); ?>&nbsp;&nbsp;&nbsp;</td>
			<td width="9%" style="text-align: center;">
				<input type="button" value="Re-Add" onclick="Re_Add_Item('<?php echo $data['Product_Name']; ?>','<?php echo $data['Payment_Item_Cache_List_ID']; ?>')">
			</td>
		</tr>
<?php
		}
	}
?>
		<tr><td colspan="5"><hr></td></tr>
	</table>
</fieldset>