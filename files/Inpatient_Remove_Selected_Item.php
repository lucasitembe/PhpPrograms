<?php
	session_start();
	include("./includes/connection.php");

	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Item_Cache_ID'])){
		$Item_Cache_ID = $_GET['Item_Cache_ID'];
	}else{
		$Item_Cache_ID = 0;
	}

	if($Item_Cache_ID != 0){
		$delete = mysqli_query($conn,"delete from tbl_inpatient_items_cache where Item_Cache_ID = '$Item_Cache_ID'") or die(mysqli_error($conn));
	}
?>
<table width="100%">
	<tr><td colspan="8"><hr></td></tr>
	<tr>
		<td width="4%"><b>Sn</b></td>
		<td width="12%"><b>Check In Type</b></td>
		<td><b>Item Name</b></td>
		<td width="8%" style="text-align: right;"><b>Price</b>&nbsp;&nbsp;</td>
		<td width="8%" style="text-align: right;"><b>Discount</b>&nbsp;&nbsp;</td>
		<td width="8%" style="text-align: right;"><b>Quantity</b>&nbsp;&nbsp;</td>
		<td width="10%" style="text-align: right;"><b>Sub Total</b>&nbsp;&nbsp;</td>
		<td width="4%"></td>
	</tr>
	<tr><td colspan="8"><hr></td></tr>

<?php
	$temp = 0;
	$select = mysqli_query($conn,"select i.Product_Name, iic.Price, iic.Quantity, iic.Discount, iic.Check_In_Type, iic.Item_Cache_ID from tbl_items i, tbl_inpatient_items_cache iic where
							i.Item_ID = iic.Item_ID and
							iic.Employee_ID = '$Employee_ID' and
							iic.Registration_ID = '$Registration_ID' order by Item_Cache_ID desc") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['Check_In_Type']; ?></td>
				<td><?php echo $data['Product_Name']; ?></td>
				<td style="text-align: right;"><?php echo number_format($data['Price']); ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo number_format($data['Discount']); ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo $data['Quantity']; ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']);?>&nbsp;&nbsp;</td>
				<td>
					<input type="button" name="Remove" id="Remove" value="X" onclick="Remove_Item('<?php echo $data['Item_Cache_ID']; ?>','<?php echo $data['Product_Name']; ?>')">
				</td>
			</tr>
<?php			
		}
	}
?>
</table>