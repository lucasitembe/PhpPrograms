<?php
	if(isset($_GET['Location'])){
		$Location = $_GET['Location'];
	}else{
		$Location = '';
	}
	session_start();
	if(isset($_SESSION['userinfo']) && strtolower($_SESSION['userinfo']['Management_Works']) == 'yes'){
		if($Location == 'Pharmacy'){
			$_SESSION['Location'] = 'Pharmacy';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}else if($Location == 'Radiology'){
			$_SESSION['Location'] = 'Radiology';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}else if($Location == 'Laboratory'){
			$_SESSION['Location'] = 'Laboratory';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}else if($Location == 'Procedure'){
			$_SESSION['Location'] = 'Procedure';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}else if($Location == 'Surgery'){
			$_SESSION['Location'] = 'Surgery';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}else{
			$_SESSION['Location'] = 'Others';
	    	header("Location: ./generalperformance.php?GeneralPerformance=GeneralPerformanceThisPage");
		}
	}else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
?>