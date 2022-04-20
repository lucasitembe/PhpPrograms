<?php

// 1. Create a database connection
        $host = "localhost";
        $username = "root";
		$database = "ehms_database";
        $password = "root";
	$conn= mysqli_connect($host,$username,$password,$database);
	if (!$conn) {
		die("Database connection failed: Include " . mysqli_connect_error($conn));
	}
	
	// 2. Select a database to use  acc_fresh
	
        $database_select = mysqli_select_db($conn,$database);
        //$database_select = mysql_select_db("final_one",$connection);
         
	if (!$database_select) {
	   die("Database selection failed: " . mysqli_error($conn));
	}
?>
<!-- https://www.youtube.com/watch?v=kiQ8Zohrr48 -->