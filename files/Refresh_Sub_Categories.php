<?php
	include("./includes/connection.php");
?>
<legend align="right"><b>REMOVE ITEMS SUB CATEGORY</b></legend>
<table width="100%">
<?php
	$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
			echo "<table width='100%'>";
			$Item_Category_ID = $data['Item_Category_ID'];
?>
			<tr>
				<td><b>CATEGORY NAME : <?php echo strtoupper($data['Item_Category_Name']); ?></b></td>
			</tr>
			<tr><td><hr></td></tr>
			<tr>
				<td>
			<?php
				$slct = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
				$no = mysqli_num_rows($slct);
				if($no > 0){
					$counter = 0;
					echo "<table width='100%'>";
					echo "<tr><td><b>SN</b></td><td><b>SUB CATEGORY NAME</b></td><td><b>ACTION</b></td></tr><tr><td colspan='3'><hr></td></tr>";
					while ($dt = mysqli_fetch_array($slct)) {
			?>
						<tr id="sss">
							<td width="5%"><?php echo ++$counter; ?></td>
							<td><?php echo ucwords(strtolower($dt['Item_Subcategory_Name'])); ?></td>
							<td width="10%">
								<input type="button" class="art-button-green" onclick="Remove_Sub_Category_Verify(<?php echo $dt['Item_Subcategory_ID']; ?>)" value="REMOVE SUB CATEGORY">
							</td>
						</tr>
			<?php
					}
					echo "</table>";
				}else{
					echo "<b>NO SUB CATEGORIES FOUND</b>";
				}
			?>
				</td>
			</tr>
			</table><br/><br/>
<?php
		}
	}
?>
</table>