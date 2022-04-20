<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['disease_group_id_value'])){
		$disease_group_id_value = $_GET['disease_group_id_value'];
	}else{
		$disease_group_id_value = 0;
	}

	if(isset($_GET['Search_Disease'])){
		$Search_Disease = $_GET['Search_Disease'];
	}else{
		$Search_Disease = '';
	}

	//get diseases
	$select = mysqli_query($conn,"select disease_name, d.disease_id from tbl_disease d, tbl_disease_group_mapping dgm where
							dgm.disease_id = d.disease_id and
							dgm.disease_group_id = '$disease_group_id_value' and disease_name like '%$Search_Disease%' order by d.disease_name limit 200") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "<table width='100%'>";
		while ($data = mysqli_fetch_array($select)) {
?>
		<tr>
			<td width="5%"><?php echo ++$temp; ?></td>
			<td width="80%"><?php echo $data['disease_name']; ?></td>
			<td>
				<input type="button" name="Remove" id="Remove" value=">>" onclick="Remove_Disease('<?php echo $data['disease_id']; ?>','<?php echo $disease_group_id_value; ?>')">
			</td>
		</tr>
<?php
		}
		echo "</table>";
	}
?>