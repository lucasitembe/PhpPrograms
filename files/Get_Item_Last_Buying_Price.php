<?php
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	//get the last buy price
    $get_price = mysqli_query($conn,"select Buying_Price from tbl_purchase_order_items where Item_ID = '$Item_ID' order by Order_Item_ID desc limit 1") or die(mysqli_error($conn));
    $get_num = mysqli_num_rows($get_price);
    if($get_num > 0){
        while ($dt = mysqli_fetch_array($get_price)) {
            $Buying_Price = $dt['Buying_Price'];
        }
    }else{
        $Buying_Price = 0;
    }
    echo $Buying_Price;
?>