<?php
	include("./includes/connection.php");
	
	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
	}else{
		$Item_Category_ID = 0;
	}
	if($Item_Category_ID == '0'){
?>
		<select name="subcategory_ID" id="subcategory_ID">
            <option value="0">All</option>
        </select>
<?php
	}else{
		//get all sub categoried based on Sub category id 
		$select = mysqli_query($conn,"select * from tbl_item_subcategory where Item_category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			echo '<select name="subcategory_ID" id="subcategory_ID" onchange="Filter_Items_From_Sub_Category();">';
			echo '<option value="0">All</option>';
			while ($data = mysqli_fetch_array($select)) {
?>
				<option value="<?php echo $data['Item_Subcategory_ID']; ?>"><?php echo $data['Item_Subcategory_Name']; ?></option>
<?php
			}
			echo "</select>";
		}else{
?>
			<select name="subcategory_ID" id="subcategory_ID">
                <option value="0">All</option>
            </select>
<?php
		}
	}
?>

