<?php
	session_start();
    include_once("./includes/connection.php");
    include_once("./functions/grnpurchasecache.php");

	$Temp = 0;
	if(isset($_GET['Purchase_Cache_ID'])){
		$Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
	}else{
		$Purchase_Cache_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	//employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}

	if(isset($_GET['Purchase_Cache_ID'])){
		mysqli_query($conn,"delete from tbl_grn_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
	}
?>
	<center>
	    <table width=100%>
			<tr><td colspan="10"><hr></td></tr>
		    <tr>
				<td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
				<td width=7% style="text-align: center;">Containers</td>
				<td width=10% style="text-align: right;">Items per Container</td>
				<td width=7% style="text-align: right;">Quantity</td>
				<td width=7% style="text-align: right;">Price</td>
				<td width=7% style="text-align: right;">Sub Total</td>
				<td width=10% style="text-align: right;">Expire Date&nbsp;&nbsp;</td>
				<td width=5%>Remove</td>
			</tr>
			<tr><td colspan="10"><hr></td></tr>

            <?php
            $Grand_Total = 0;

            $Purchase_Order_Cache_Items = Get_Purchase_Order_Cache_Items_By_Employee($Employee_ID);
            foreach($Purchase_Order_Cache_Items as $Purchase_Order_Cache_Item){
            ?>
            <tr><td><input type='text' readonly='readonly' value='<?php echo ++$Temp; ?>' style='text-align: center;'></td>
            <td><input type='text' readonly='readonly'
                       value='<?php echo $Purchase_Order_Cache_Item['Product_Name']; ?>'
                       title='<?php echo $Purchase_Order_Cache_Item['Product_Name']; ?>'></td>
            <td>
                <input type='text' id='Container_<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       value='<?php echo $Purchase_Order_Cache_Item['Container_Qty']; ?>' style='text-align: right;'
                       oninput="Update_Quantity('<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>')">
            </td>
            <td>
                <input type='text' id='Items_<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       value='<?php echo $Purchase_Order_Cache_Item['Items_Per_Container']; ?>' style='text-align: right;'
                       oninput="Update_Quantity('<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>')">
            </td>
            <td>
                <input type='text' id='QR<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       value='<?php echo $Purchase_Order_Cache_Item['Quantity_Required']; ?>' style='text-align: right;'
                       oninput="Update_Quantity2(this.value,<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)">
            </td>
            <td>
                <input type='text' id='<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       name='<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>'
                       value='<?php echo $Purchase_Order_Cache_Item['Price']; ?>' style='text-align: right;'
                       oninput="Update_Price(this.value,<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)">
            </td>
            <td><input type='text' name='Sub_Total<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       id='Sub_Total<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly'
                       value='<?php echo number_format($Purchase_Order_Cache_Item['Quantity_Required'] * $Purchase_Order_Cache_Item['Price']); ?>' style='text-align: right;'></td>

            <td><input type='text' value='<?php echo $Purchase_Order_Cache_Item['Expire_Date']; ?>  ' readonly='readonly' style="text-align: right;"></td>
            <td>
                <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                       onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$Purchase_Order_Cache_Item['Product_Name']); ?>",
                       <?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)'>
            </td>
        <?php
        $Grand_Total += ($Purchase_Order_Cache_Item['Quantity_Required'] * $Purchase_Order_Cache_Item['Price']);
        }
        ?>
            <tr><td colspan="10"><hr></td></tr>
            <tr>
                <td colspan=6 style="text-align: right;"><b>GRAND TOTAL</b></td>
                <td colspan=4 style="text-align: right;"><b><?php echo number_format($Grand_Total); ?></b></td>
            </tr>
            <tr><td colspan="10"><hr></td></tr>
		</table>
		</form>   
        </center>