<?php 
	require_once('includes/connection.php');
	isset($_GET['Save']) ? $Save = $_GET['Save'] : $Save = '';
	
	//$data_saved = '';
		
		$select_cache = "SELECT * FROM tbl_radiology_parameter_cache";
		$select_cache_qry = mysqli_query($conn,$select_cache) or die(mysqli_error($conn));
		
		while($cached = mysqli_fetch_assoc($select_cache_qry)){
			$Parameter_Name = $cached['Parameter_Name'];
			$Parameter_ID = $cached['Parameter_ID'];
			$Item_ID = $cached['Item_ID'];
			
			$insert_cache = "INSERT INTO tbl_radiology_parameter(Parameter_Name, Item_ID) VALUES('$Parameter_Name', '$Item_ID')";
			$insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
			
			if($insert_cache_qry){
				$DeleteRow = "DELETE FROM tbl_radiology_parameter_cache WHERE Parameter_ID = '$Parameter_ID'";
				$DeleteParam_qry = mysqli_query($conn,$DeleteRow) or die(mysqli_error($conn));
			}
			
			if($DeleteParam_qry){
				$data_saved = 'OK';
			}
		}

	echo $data_saved;
?>