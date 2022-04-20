<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Status'])){
		$Status = $_GET['Status'];

		if(strtolower($Status) == 'enabled'){
			$Enable_Splash_Index = 'yes';
		}else{
			$Enable_Splash_Index = 'no';
		}

		$result = mysqli_query($conn,"update tbl_system_configuration set Enable_Splash_Index = '$Enable_Splash_Index'") or die(mysqli_error($conn));
		if($result){
			$_SESSION['systeminfo']['Enable_Splash_Index'] = $Enable_Splash_Index;
			header("Location: ./companypage.php?CompanyConfiguration=CompanyConfigurationThisPage");
		}
	}
?>