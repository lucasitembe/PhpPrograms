<?php
	include("./includes/connection.php");
	if(isset($_GET['Disease_Category_ID'])){
		$Disease_Category_ID = $_GET['Disease_Category_ID'];
	}else{
		$Disease_Category_ID = 0;
	}

	if($Disease_Category_ID != 0){
?>

<select name="Disease_Category_ID2" id="Disease_Category_ID2" onchange="Filter_Diseases2(); Display_Sub_Categories2();">
	<option value="">Select Category</option>
	    <?php
	        //select disease categories
	        $select = mysqli_query($conn,"select * from tbl_disease_category where disease_category_ID <> '$Disease_Category_ID' order by category_discreption") or die(mysqli_error($conn));
	        $num = mysqli_num_rows($select);
	        if($num > 0){
	            while ($data = mysqli_fetch_array($select)) {
	    ?>
	                <option value="<?php echo $data['disease_category_ID']; ?>"><?php echo ucwords(strtolower($data['category_discreption'])); ?></option>
	    <?php
	            }
	        }
	    ?>
</select>

<?php }else{ ?>
	<select>
		<option value="0">Select Category</option>
	</select>
<?php } ?>