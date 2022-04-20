<?php
	session_start();

	if(isset($_GET['Transaction_Mode'])){
		$Transaction_Mode = $_GET['Transaction_Mode'];
	}else{
		$Transaction_Mode = 'Normal Transaction';
	}

	if(isset($_GET['Controler'])){
		$Controler = $_GET['Controler'];
	}else{
		$Controler = 'not checked';
	}

	if($Controler == 'not checked'){
		unset($_SESSION['Transaction_Mode']);
	}else{
		$_SESSION['Transaction_Mode'] = $Transaction_Mode;
	}
?>