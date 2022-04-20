<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['disease_id'])){
		$disease_id = $_GET['disease_id'];
	}else{
		$disease_id = 0;
	}

	//get disease_group_id
	$get = mysqli_query($conn,"select disease_group_id from tbl_disease_group_mapping where disease_id = '$disease_id' limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($get);
	if($nm > 0){
		while ($row = mysqli_fetch_array($get)) {
			$temp_disease_group_id = $row['disease_group_id'];
		}
	}else{
		$temp_disease_group_id = 0;
	}

	//get unspecified disease_group_id
	$select = mysqli_query($conn,"select disease_group_id from tbl_disease_group where disease_group_name = 'unspecified'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($row = mysqli_fetch_array($select)) {
			$disease_group_id = $row['disease_group_id'];
		}
	}else{
		$disease_group_id = 0;
	}

	if($disease_group_id != 0){
		//remove disease
		$update = mysqli_query($conn,"update tbl_disease_group_mapping set disease_group_id = '$disease_group_id'
								where disease_id = '$disease_id'") or die(mysqli_error($conn));
	}


	//get diseases
	$select = mysqli_query($conn,"select disease_name, d.disease_id, d.disease_code from tbl_disease d, tbl_disease_group_mapping dgm where
							dgm.disease_id = d.disease_id and
							dgm.disease_group_id <> '$temp_disease_group_id' order by d.disease_name limit 200") or die(mysqli_error($conn));

	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "<table width='100%'>";
		while ($data = mysqli_fetch_array($select)) {
?>
		<tr>
			<td>
			<!-- <td width="5%"><?php //echo ++$temp; ?></td> -->
				<input type="button" name="Add" id="Add" value="<<" onclick="Add_Disease('<?php echo $data['disease_id']; ?>')">
			</td>
			<td width="95%"><?php echo ucwords($data['disease_name'].' - (<b>'.$data['disease_code']).')</b>'; ?></td>
		</tr>
<?php
		}
		echo "</table>";
	}
?>