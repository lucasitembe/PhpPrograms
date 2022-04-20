<?php
	include("./includes/connection.php");

	if(isset($_GET['Disease_Category_ID'])){
		$Disease_Category_ID = $_GET['Disease_Category_ID'];
	}else{
		$Disease_Category_ID = 0;
	}
?>
<select name="subcategory_ID" id="subcategory_ID" onchange="Filter_Sub_Category_Diseases();">
	<option value="0">All</option>
<?php
    //select disease categories
    $select = mysqli_query($conn,"select * from tbl_disease_subcategory where disease_category_ID = '$Disease_Category_ID' order by subcategory_description") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
?>
            <option value="<?php echo $data['subcategory_ID']; ?>"><?php echo ucwords(strtolower($data['subcategory_description'])); ?></option>
<?php
        }
    }
?>
</select>