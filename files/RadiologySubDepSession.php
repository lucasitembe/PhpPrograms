<?php
	$back = $_SERVER["HTTP_REFERER"];
	@session_start();
	if(isset($_GET['Radiology_Sub_Dep_ID'])){
		//if(isset($_SESSION['Radiology_Sub_Dep_ID'])) unset($_SESSION['Radiology_Sub_Dep_ID']);
		$_SESSION['Radiology_Sub_Dep_ID'] = $_GET['Radiology_Sub_Dep_ID'];
	}
	
	header("Location: $back");
	
?>