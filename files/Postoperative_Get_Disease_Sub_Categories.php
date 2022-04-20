<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['disease_category_ID'])){
		$disease_category_ID = $_GET['disease_category_ID'];
	}else{
		$disease_category_ID = 0;
	}
?>
<select name="subcategory_ID" id="subcategory_ID" onchange="Get_Diseases()">
	<option selected="selected" value="0">All</option>
<?php
	$select = mysqli_query($conn,"select * from tbl_disease_subcategory where disease_category_ID = '$disease_category_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<option value="<?php echo $data['subcategory_ID']; ?>"><?php echo $data['subcategory_description']; ?></option>
<?php
		}
	}
?>
</select>