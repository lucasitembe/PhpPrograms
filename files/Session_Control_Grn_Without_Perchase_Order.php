<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Status']) && $_GET['Status'] == 'New'){
		if(isset($_SESSION['Grn_ID'])){
			unset($_SESSION['Grn_ID']);
			header("Location: ./grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage");
		}else{
			header("Location: ./grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage");
		}
	}else if(isset($_GET['Status']) && $_GET['Status'] == 'pending'){
		if(isset($_SESSION['Grn_ID'])){
			unset($_SESSION['Grn_ID']);
			header("Location: ./grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage");
		}else{
			header("Location: ./grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage");
		}
	}else if(isset($_GET['Status']) && $_GET['Status'] == 'Previous'){
		if(isset($_SESSION['Grn_ID'])){ unset($_SESSION['Grn_ID']); }
		if(isset($_GET['Grn_ID'])){ $Grn_ID = $_GET['Grn_ID']; }else{ $Grn_ID = 0; }
		header("Location: ./previewgrnwithoutpurchaseorderreport.php?Grn_ID=$Grn_ID");		
	}else{
		header("Location: ./grnwithoutpurchaseorder.php?GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage");		
	}
?>