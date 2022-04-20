<?php

	require_once('./includes/connection.php');
//
	isset($_GET['Brand_name']) ? $Brand_name = mysqli_real_escape_string($conn,$_GET['Brand_name']) : $Brand_name != '';

//	$cached_data = '';
          if(!empty($Brand_name)){
		$insert_cache = "INSERT INTO  tbl_Brand_name(brand_name) VALUES('$Brand_name')";
		$insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));

          }


?>
