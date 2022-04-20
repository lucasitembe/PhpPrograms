<?php
	include("./includes/connection.php");
	if(isset($_GET['country'])){
		$country = $_GET['country'];
	}else{
		$country = 'Tanzania';
	}

	$data = mysqli_query($conn,"select Region_ID, Region_Name from tbl_regions where Country_ID = (select Country_ID from tbl_country where Country_Name = '$country' limit 1) order by Region_Name");
		while($row = mysqli_fetch_array($data)){
			echo "<option value='".$row['Region_Name']."'>".$row['Region_Name']."</option>";
		}
?>