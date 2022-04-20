<?php
	include("./includes/connection.php");
	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	$select = mysqli_query($conn,"select Buying_Price, po.Sent_Date from tbl_purchase_order po, tbl_purchase_order_items poi where 
							Item_ID = '$Item_ID' and
							po.Purchase_Order_ID = poi.Purchase_Order_ID and
							po.Order_Status = 'Served'
							order by po.Purchase_Order_ID desc limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Buying_Price1 = $data['Buying_Price'];
			$Comparison_Date1 = $data['Sent_Date'];
		}
	}else{
		$Buying_Price1 = 0;
		$Comparison_Date1 = '';
	}

	$select = mysqli_query($conn,"select Price, Grn_Date_And_Time from tbl_grn_without_purchase_order po, tbl_grn_without_purchase_order_items poi where
							poi.Item_ID = '$Item_ID' and
							po.Grn_ID = poi.Grn_ID
							order by poi.Purchase_Order_Item_ID desc limit 1") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Buying_Price2 = $data['Price'];
			$Comparison_Date2 = $data['Grn_Date_And_Time'];
		}
	}else{
		$Buying_Price2 = 0;
		$Comparison_Date2 = '';
	}

	if(strtotime($Comparison_Date1) >= strtotime($Comparison_Date2)){
		echo $Buying_Price1;
	}else{
		echo $Buying_Price2;
	}
?>