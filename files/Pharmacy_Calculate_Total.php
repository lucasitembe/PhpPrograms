<?php
	if(isset($_GET['Price'])){
		$Price = str_replace(",", "", $_GET['Price']);
	}else{
		$Price = 0;
	}

	if(isset($_GET['Discount'])){
		$Discount = $_GET['Discount'];
	}else{
		$Discount = 0;
	}

	if($Discount == null || $Discount == ''){
		$Discount = 0;
	}

	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = 0;
	}
	echo number_format(($Price - $Discount) * $Quantity);
?>