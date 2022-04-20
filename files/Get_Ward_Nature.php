<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Hospital_Ward'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward'];
	}else{
		$Hospital_Ward_ID = 0;
	} 
     
        $wardQuery=  mysqli_query($conn,"SELECT ward_nature FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'");
        $row=  mysqli_fetch_assoc($wardQuery);
        echo $row['ward_nature'];
        exit();
 
?>