<?php
	include("./includes/connection.php");
	if(isset($_GET['Search_Disease_Group_Value'])){
		$Search_Disease_Group_Value = $_GET['Search_Disease_Group_Value'];
	}else{
		$Search_Disease_Group_Value = '';
	}
?>

<?php
	//get all disease group
	$select_disease_group = mysqli_query($conn,"SELECT id, disease_name FROM tbl_mtuha_groups  WHERE id BETWEEN 5 AND 98 LIKE '%$Search_Disease_Group_Value%' limit 200") or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($select_disease_group);
	if($num_rows > 0){
?>
		<table width="100%">
<?php
		while ($data = mysqli_fetch_array($select_disease_group)) {
?>
			<tr>
				<td>
					<input type='radio' name='Disease_Group_Selection' id='<?php echo $data['disease_group_id']; ?>' value='<?php echo $data['disease_group_name']; ?>' onclick="Get_Disease_Group_Name(this.value,'<?php echo $data['id']; ?>')">
				</td>
				<td>
					<label for="<?php echo $data['disease_group_id']; ?>"><?php echo ucwords(strtolower($data['disease_group_name'])); ?></label>
				</td>
			</tr>
<?php
		}
		echo "</table>";
	}
?>