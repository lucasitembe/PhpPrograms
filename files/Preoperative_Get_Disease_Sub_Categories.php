<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Pre_disease_category_ID'])){
		$Pre_disease_category_ID = $_GET['Pre_disease_category_ID'];
	}else{
		$Pre_disease_category_ID = 0;
	}
?>
<select name="Pre_disease_category_ID" id="Pre_disease_category_ID" onchange="Get_Preoperative_Diseases()">
	<option selected="selected" value="0">All</option>
<?php
	$select = mysqli_query($conn,"select * from tbl_disease_subcategory where disease_category_ID = '$Pre_disease_category_ID'") or die(mysqli_error($conn));
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