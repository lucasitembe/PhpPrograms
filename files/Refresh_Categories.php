<?php
	include("./includes/connection.php");
?>
<legend align="right"><b>REMOVE ITEMS CATEGORY</b></legend>
	<table width="100%">
		<tr><td colspan="3"><hr></td></tr>
		<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%" style="text-align: center;"><b>ACTION</b></td></tr>
		<tr><td colspan="3"><hr></td></tr>
	<?php
		$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			$temp = 0;
			while ($data = mysqli_fetch_array($select)) {
	?>
				<tr id="sss">
					<td><?php echo ++$temp; ?></td>
					<td><?php echo strtoupper($data['Item_Category_Name']); ?></td>
					<td style="text-align: center;"><input type="button" value="REMOVE CATEGORY" class="art-button-green" onclick="Remove_Category_Verify(<?php echo $data['Item_Category_ID']; ?>)"></td>
				</tr>		
	<?php
			}
		}
	?>
</table>