<?php
	include("./includes/connection.php");
?>
<table>
	<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%"></td></tr>
<?php
	$select = mysqli_query($conn,"select Exemption_Category_ID, ic.Item_Category_Name from tbl_item_category ic, tbl_Exemption_Categories ec where
							ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr id="sss">
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['Item_Category_Name']; ?></td>
				<td><input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Category(<?php echo $data['Exemption_Category_ID']; ?>)"></td>
			</tr>		
<?php
		}
	}
?>
</table>