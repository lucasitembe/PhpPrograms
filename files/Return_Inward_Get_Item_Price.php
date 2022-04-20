<?php
	include("./includes/connection.php");
	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	if($Item_ID != 0 && $Item_ID != null && $Item_ID != null){
        $sql = "SELECT Buying_Price FROM tbl_purchase_order_items".
               " WHERE Item_ID = '$Item_ID'".
               " ORDER BY Order_Item_ID DESC".
               " LIMIT 1";
		$select = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Item_Price = $data['Buying_Price'];
			}
		}else{
			//mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
            $Item_Price = 0;
		}
		echo $Item_Price;
	}
?>