<?php
	include("./includes/connection.php");
	$temp = 0;
	
	if(isset($_GET['disease_group_id_value'])){
		$disease_group_id_value = $_GET['disease_group_id_value'];
	}else{
		$disease_group_id_value = 0;
	}

	if(isset($_GET['Search_Disease_Unassigned'])){
		$Search_Disease_Unassigned = str_replace(" ", "%", $_GET['Search_Disease_Unassigned']);
	}else{
		$Search_Disease_Unassigned = '';
	}

	//get diseases
	$select = mysqli_query($conn,"select disease_name, d.disease_id from tbl_disease d, tbl_disease_group_mapping dgm where
							dgm.disease_id = d.disease_id and
							dgm.disease_group_id <> '$disease_group_id_value' and 
							disease_name like '%$Search_Disease_Unassigned%' order by d.disease_name limit 200") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	echo "<table width='100%'>";
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
		<tr>
			<td>
				<input type="button" name="Add" id="Add" value="<<" onclick="Add_Disease('<?php echo $data['disease_id']; ?>')">
			</td>
			<!-- <td width="5%"><?php //echo ++$temp; ?></td> -->
			<td width="95%"><?php echo $data['disease_name']; ?></td>
		</tr>
<?php
		}
	}

	//get new diseases
		$select = mysqli_query($conn,"select disease_id, disease_name from tbl_disease where disease_id not in (select disease_id from tbl_disease_group_mapping) and disease_name like '%$Search_Disease_Unassigned%' order by disease_name limit 200") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($row = mysqli_fetch_array($select)) {
?>
			<tr>
				<td>
					<input type="button" name="Add" id="Add" value="<<" onclick="Add_Disease('<?php echo $row['disease_id']; ?>')">
				</td>
				<!-- <td width="5%"><?php //echo ++$temp; ?></td> -->
				<td width="95%"><?php echo $row['disease_name']; ?></td>
			</tr>
<?php
			}
		}
	echo "</table>";
?>