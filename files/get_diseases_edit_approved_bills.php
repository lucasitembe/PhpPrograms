<?php 
include("./includes/connection.php");
$InputFrom = $_POST['InputFrom'];
$Value = $_POST['Value'];

$filter = "";

if($InputFrom == 'Name'){
	$filter = " AND d.disease_name LIKE '%$Value%'";
}elseif ($InputFrom == 'Code') {
	$filter = " AND d.Disease_Code LIKE '%$Value%'";
}


	$select_disease = mysqli_query($conn,"SELECT * FROM tbl_disease d WHERE d.disease_version = 'icd_10' $filter  LIMIT 10 ");

	if(mysqli_num_fields($select_disease) > 0){
	
		$count = 1;
		while ($row = mysqli_fetch_assoc($select_disease)) {
			$Disease_ID = $row['disease_ID'];
			echo "<tr><td width=10%>".$count."</td><td width=60%>".$row['disease_name']."</td><td width=15%>".$row['disease_code']."</td><td width=15%><input type='button' onclick='Add_Disease({$Disease_ID})' value='Add'></td></tr>";
			$count++;
		}
	}

 ?>
