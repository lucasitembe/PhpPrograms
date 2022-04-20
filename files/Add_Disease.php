<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['disease_id'])){
		$disease_id = $_GET['disease_id'];
	}else{
		$disease_id = 0;
	}

	if(isset($_GET['disease_group_id'])){
		$disease_group_id = $_GET['disease_group_id'];
	}else{
		$disease_group_id = 0;
	}

	if(isset($_GET['Search_Disease_Unassigned'])){
		$Search_Disease_Unassigned = $_GET['Search_Disease_Unassigned'];
	}else{
		$Search_Disease_Unassigned = '';
	}

	//update ......
	if($disease_group_id != 0){
		$update = mysqli_query($conn,"update tbl_disease_group_mapping set disease_group_id = '$disease_group_id'
								where disease_id = '$disease_id'") or die(mysqli_error($conn));
		
		//if disease not found in tbl_disease_group_mapping then add
		$slct = mysqli_query($conn,"select disease_id from tbl_disease_group_mapping where disease_id = '$disease_id'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($slct);
		if($no < 1){
			$insert = mysqli_query($conn,"insert into tbl_disease_group_mapping(disease_id, disease_group_id) values('$disease_id','$disease_group_id')") or die(mysqli_error($conn));
		}
	}


	//get diseases
	$select = mysqli_query($conn,"select disease_name, d.disease_id, d.disease_code from tbl_disease d, tbl_disease_group_mapping dgm where
							dgm.disease_id = d.disease_id and
							dgm.disease_group_id <> '$disease_group_id' and disease_name like '%$Search_Disease_Unassigned%' order by d.disease_name limit 200") or die(mysqli_error($conn));
	
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "<table width='100%'>";
		while ($data = mysqli_fetch_array($select)) {
?>
		<tr>
			<!--<td width="5%"><?php echo ++$temp; ?></td>-->
			<td>
				<input type="button" name="Add" id="Add" value="<<" onclick="Add_Disease('<?php echo $data['disease_id']; ?>')">
			</td>
			<td width="95%"><?php echo ucwords($data['disease_name'].' - (<b>'.$data['disease_code']).')</b>'; ?></td>
		</tr>
<?php
		}
		echo "</table>";
	}
?>