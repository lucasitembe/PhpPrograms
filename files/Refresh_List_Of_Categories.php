<?php
	include("./includes/connection.php");
?>
<table>
	<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%"></td></tr>
<?php
	$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category where Item_Category_ID NOT IN(select Item_Category_ID from tbl_Exemption_Categories) order by Item_Category_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr id="sss">
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['Item_Category_Name']; ?></td>
				<td><input type="button" value="ADD" class="art-button-green" onclick="Add_Category(<?php echo $data['Item_Category_ID']; ?>)"></td>
			</tr>		
<?php
		}
	}
?>
</table>