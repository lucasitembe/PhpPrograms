<?php
	session_start();
	include("./includes/connection.php");
	$Grand_Total = 0;
	$temp = 0;
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{	
		$Payment_Item_Cache_List_ID = 0;
	}

	//get Payment_Cache_ID
	$select = mysqli_query($conn,"select Payment_Cache_ID from tbl_item_list_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Payment_Cache_ID = $data['Payment_Cache_ID'];
		}
	}else{
		$Payment_Cache_ID = 0;
	}

	//delete selected item via updating ePayment_Status
	$update = mysqli_query($conn,"update tbl_item_list_cache set ePayment_Status = 'removed' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
?>

<center>
        <table width=100%>
            <tr>
                <td><b>SN</b></td>
                <td><b>ITEM NAME</b></td>
                <td><b>CHECK IN TYPE</b></td>
                <td style="text-align: right;"><b>PRICE</b></td>
                <td style="text-align: right;"><b>DISCOUNT</b></td>
                <td style="text-align: right;"><b>QUANTITY</b></td>
                <td style="text-align: right;"><b>SUB TOTAL</b></td>
                <td width="5%" style="text-align: center;"><b>ACTION</b></td>
            </tr>
            <tr><td colspan="8"><hr></td></tr>
<?php
    $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID
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
        while ($data = mysqli_fetch_array($select_items)) {
            //generate Quantity
            if($data['Edited_Quantity'] != 0){
                $Qty = $data['Edited_Quantity'];
            }else{
                $Qty = $data['Quantity'];
            }
            $Total = (($data['Price'] - $data['Discount']) * $Qty);
            $Grand_Total += $Total;
?>
            <tr>
                <td><?php echo ++$temp; ?></td>
                <td><?php echo $data['Product_Name']; ?></td>
                <td><?php echo $data['Check_In_Type']; ?></td>
                <td style="text-align: right;"><?php echo number_format($data['Price']); ?></td>
                <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                <td style="text-align: right;"><?php if($data['Edited_Quantity'] != 0){ echo $data['Edited_Quantity']; }else{ echo $data['Quantity']; } ?></td>
                <td style="text-align: right;"><?php echo number_format($Total); ?></td>
            <?php if($no > 1){ ?>
                <td style="text-align: center;"><input type="button" name="remove" id="remove" value="X" onclick="Remove_Item('<?php echo $data['Payment_Item_Cache_List_ID']; ?>','<?php echo $data['Product_Name']; ?>')"></td>
            <?php }else{ ?>
            	<td style="text-align: center;"><input type="button" name="remove" id="remove" value="X" onclick="Warning_Remove_Item()"></td>
            <?php } ?>
            </tr>            
<?php
        }
    }
?>
            <tr><td colspan="8"><hr></td></tr>
            <tr><td colspan="6"><b>GRAND TOTAL</b></td><td style="text-align: right;"><?php echo number_format($Grand_Total); ?></td><td></td></tr>
        </table>
    </center>