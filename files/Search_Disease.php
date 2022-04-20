<?php
	include("includes/connection.php");
    $temp = 0;
    $temp2 = 0;
	if(isset($_GET['Search_Disease_Value'])){
		$Search_Disease_Value = mysqli_real_escape_string($conn,$_GET['Search_Disease_Value']);
	}else{
		$Search_Disease_Value = '';
	}

	if($Search_Disease_Value != '' && $Search_Disease_Value != null){
?>

<legend align="right" style="background-color:#006400;color:white;padding:5px;">LIST OF GROUPS AND ASSIGNED DISEASES</legend>
	<?php
		//get all disease group
		$select_disease_group = mysqli_query($conn,"select dg.disease_group_name, dg.disease_group_id from
												tbl_disease_group dg, tbl_disease_group_mapping dgm, tbl_disease d where 
												d.disease_ID = dgm.disease_ID and
												dg.disease_group_id = dgm.disease_group_id and
												d.disease_name like '%$Search_Disease_Value%' group by dg.disease_group_id order by disease_group_name limit 200 ") or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($select_disease_group);
		if($num_rows > 0){
	?>
			
	<?php
			while ($data = mysqli_fetch_array($select_disease_group)) {
				$disease_group_id = $data['disease_group_id'];
				echo '<h5><b>'.++$temp.'. '.ucwords(strtolower($data['disease_group_name'])).'</b></h5>';
				//get all diseases assigned to selected group
				$select_diseases = mysqli_query($conn,"select disease_name, disease_code from 
												tbl_disease d, tbl_disease_group_mapping dgm, tbl_disease_group dg where
												d.disease_ID = dgm.disease_ID and
												dgm.disease_group_id = dg.disease_group_id and
												d.disease_name like '%$Search_Disease_Value%' and
												dgm.disease_group_id = '$disease_group_id' order by disease_name") or die(mysqli_error($conn));
				$num_diseases = mysqli_num_rows($select_diseases);
				if($num_diseases > 0){
				echo '<table width="100%">';
				echo "<tr id='thea'><td width='5%'>SN</td><td width='10%'>CODE</td><td width='35%'>DISEASE NAME</td></tr>";
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					while ($row = mysqli_fetch_array($select_diseases)) {
?>
						<tr>
							<td width="5%">
								<?php echo ++$temp2; ?>
							</td>
							<td>
								<?php echo $row['disease_code']; ?>
							</td>
							<td>
								<?php echo $row['disease_name']; ?>
							</td>
						</tr>	
<?php
					}
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					$temp2 = 0;
				echo '</table><br/>';
				}else{
					echo "<b><ul><li>No Disease Assigned</li></ul></b><br/>";
				}
			}
		}
	}else{
?>
<legend align="right" style="background-color:#006400;color:white;padding:5px;">LIST OF GROUPS AND ASSIGNED DISEASES</legend>
	<?php
		//get all disease group
		$select_disease_group = mysqli_query($conn,"select disease_group_name, disease_group_id from tbl_disease_group group by disease_group_id order by disease_group_name limit 200 ") or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($select_disease_group);
		if($num_rows > 0){
	?>
			
	<?php
			while ($data = mysqli_fetch_array($select_disease_group)) {
				$disease_group_id = $data['disease_group_id'];
				echo '<h5><b>'.++$temp.'. '.ucwords(strtolower($data['disease_group_name'])).'</b></h5>';
				//get all diseases assigned to selected group
				$select_diseases = mysqli_query($conn,"select disease_name, disease_code from 
												tbl_disease d, tbl_disease_group_mapping dgm, tbl_disease_group dg where
												d.disease_ID = dgm.disease_ID and
												dgm.disease_group_id = dg.disease_group_id and
												dgm.disease_group_id = '$disease_group_id' order by disease_name") or die(mysqli_error($conn));
				$num_diseases = mysqli_num_rows($select_diseases);
				if($num_diseases > 0){
				echo '<table width="100%">';
				echo "<tr id='thea'><td width='5%'>SN</td><td width='10%'>CODE</td><td width='35%'>DISEASE NAME</td></tr>";
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					while ($row = mysqli_fetch_array($select_diseases)) {
?>
						<tr>
							<td width="5%">
								<?php echo ++$temp2; ?>
							</td>
							<td>
								<?php echo $row['disease_code']; ?>
							</td>
							<td>
								<?php echo $row['disease_name']; ?>
							</td>
						</tr>	
<?php
					}
				echo "<tr id='thea'><td colspan='4'><hr></td></tr>";
					$temp2 = 0;
				echo '</table><br/>';
				}else{
					echo "<b><ul><li>No Disease Assigned</li></ul></b><br/>";
				}
			}
		}
	}
	?>