<?php
	include("./includes/connection.php");
	$count = 0;
	$select = mysqli_query($conn,"select disease_ID from tbl_disease") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$disease_ID = $data['disease_ID'];

			//check if available
			$check = mysqli_query($conn,"select disease_id from tbl_disease_group_mapping where disease_id = '$disease_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($check);
			if($num < 1){
				mysqli_query($conn,"insert into tbl_disease_group_mapping(disease_group_id,disease_id) values(92,'$disease_ID')");
				$count++;
			}
		}
	}
	echo $count;
?>