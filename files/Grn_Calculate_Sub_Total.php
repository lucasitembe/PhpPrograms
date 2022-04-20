<?php
	if(isset($_GET['Quantity_Received'])){
		$Quantity_Received = $_GET['Quantity_Received'];
	}else{
		$Quantity_Received = 0;
	}
	if(isset($_GET['Last_Buying_Price'])){
		$Last_Buying_Price = str_replace(",", "", $_GET['Last_Buying_Price']);
	}else{
		$Last_Buying_Price = 0;
	}
	echo number_format($Quantity_Received * $Last_Buying_Price);
?>