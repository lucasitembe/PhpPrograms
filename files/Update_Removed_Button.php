<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	$select = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
            from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
            ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
            ilc.Item_ID = itm.Item_ID and
            (ilc.Status = 'active' or ilc.Status = 'approved') and
            ilc.Transaction_Type = 'Cash' and
            pc.Payment_Cache_ID = '$Payment_Cache_ID' and
            pc.Billing_Type = 'Outpatient Cash' and
            ilc.ePayment_Status = 'removed' order by ilc.Check_In_Type") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
?>
	<tr>
        <td style="text-align: right;">
            <input type="button" value="View Removed Items" class="art-button-green" onclick="View_Removed_Items('<?php echo $Payment_Cache_ID; ?>')">
        </td>
    </tr>
<?php
	}
?>