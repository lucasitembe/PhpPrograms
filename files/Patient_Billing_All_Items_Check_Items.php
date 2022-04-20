<?php
	include("./includes/connection.php");
	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	if($Payment_Cache_ID != null && $Payment_Cache_ID != '' && $Payment_Cache_ID != 0){
		$select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type
                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                ilc.Item_ID = itm.Item_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
    	$no = mysqli_num_rows($select_items);
    	if($no > 0){
    		echo 'yes';
    	}else{
    		echo 'no';
    	}
	}
?>