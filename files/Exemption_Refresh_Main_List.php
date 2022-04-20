<?php
	include("./includes/connection.php");
?>
<option selected="selected" value="0">All</option>
<?php
	$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where 
							ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
<?php 	}
	}
?>