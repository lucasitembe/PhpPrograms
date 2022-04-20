<?php
	$Total = 0;
	if(isset($_GET['Price'])){
		$Price = $_GET['Price'];
	}else{
		$Price = 0;
	}

	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = 0;
	}

	if(isset($_GET['Discount'])){
		$Discount = $_GET['Discount'];
	}else{
		$Discount = 0;
	}

	$Total = ($Price - $Discount) * $Quantity;
	
	echo number_format($Total);
?>