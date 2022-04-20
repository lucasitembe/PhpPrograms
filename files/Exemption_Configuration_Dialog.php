<?php
	include("./includes/connection.php");
?>
<table widtH="100%">
	<tr>
		<td width="50%"><b>LIST OF CATEGORIES</b></td>
		<td width="50%"><b>SELECTED CATEGORIES</b></td>
	</tr>
	<tr>
		<td>
			<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='List_Of_Categories_Area'>
				<table>
					<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%"></td></tr>
				<?php
					$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category where Item_Category_ID NOT IN(select Item_Category_ID from tbl_exemption_categories) order by Item_Category_Name") or die(mysqli_error($conn));
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
			</fieldset>
		</td>
		<td>
			<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Selected_Categories_Area'>
				<table>
					<tr><td width="9%"><b>SN</b>&nbsp;&nbsp;&nbsp;</td><td><b>CATEGORY NAME</b></td><td width="15%"></td></tr>
				<?php
					$select = mysqli_query($conn,"select Exemption_Category_ID, ic.Item_Category_Name from tbl_item_category ic, tbl_exemption_categories ec where
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
			</fieldset>
		</td>
	</tr>
</table>