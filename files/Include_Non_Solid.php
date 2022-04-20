<?php
	session_start();
	if(isset($_GET['Controler'])){
		$Controler = $_GET['Controler'];
	}else{
		$Controler = '';
	}

	//check if included then establish session
	if($Controler == 'checked'){
		$_SESSION['Include_Non_Solid_Items'] = 'yes';
	}else{
		unset($_SESSION['Include_Non_Solid_Items']);
	}
?>