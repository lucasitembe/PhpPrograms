<?php
	include("./includes/connection.php");
	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
	}else{
		$Item_Category_ID = '';
	}
	echo '<select name="Item_Subcategory_ID" id="Item_Subcategory_ID" onchange="Get_Items_Filtered();">';
	echo '<option selected="selected" value="0">All</option>';
	$select = mysqli_query($conn,"select Item_Subcategory_ID, Item_Subcategory_Name from tbl_item_subcategory where Item_category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
		<option value="<?php echo $data['Item_Subcategory_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Subcategory_Name'])); ?></option>
<?php
		}
	}
?>
</select>
