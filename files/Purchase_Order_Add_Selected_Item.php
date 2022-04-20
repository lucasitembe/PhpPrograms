<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 0;
    }
    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = '';
    }
    
    if(isset($_GET['Price'])){
        $Price = $_GET['Price'];
    }else{
        $Price = '';
    }

	if(isset($_GET['Container'])){
        $Container = $_GET['Container'];
    }else{
        $Container = '';
    }

    if(isset($_GET['Store_Need'])){
        $Store_Need = $_GET['Store_Need'];
    }else{
        $Store_Need = '';
    }

	if(isset($_GET['Items_per_Container'])){
        $Items_per_Container = $_GET['Items_per_Container'];
    }else{
        $Items_per_Container = '';
    }    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if($Item_ID != 0 && $Item_ID != ''){
	
	$sql_select = mysqli_query($conn,"select Item_ID from tbl_purchase_cache where
                                    Item_ID = '$Item_ID' and
                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	
	$num = mysqli_num_rows($sql_select);
	if($num == 0){
	    //insert data to tbl_purchase_cache
	    $insert = mysqli_query($conn,"insert into tbl_purchase_cache(
				Quantity_Required,Item_Remark,Item_ID,
				Price,Employee_ID,Container_Qty,Items_Per_Container,Store_Need)
				
				VALUES ('$Quantity','$Item_Remark','$Item_ID',
				    '$Price','$Employee_ID','$Container','$Items_per_Container','$Store_Need')") or die(mysqli_error($conn).'One');
    
    
	}
	$select_order_items = mysqli_query($conn,"select itm.Product_Name, pc.Quantity_Required, pc.Item_Remark, pc.Purchase_Cache_ID, pc.Price, pc.Container_Qty, pc.Items_Per_Container
										from tbl_purchase_cache pc, tbl_items itm where
									    itm.Item_ID = pc.Item_ID and
										pc.Employee_ID ='$Employee_ID'") or die(mysqli_error($conn)); 
	$no = mysqli_num_rows($select_order_items);

	echo '<center><table width = 100% border=0>';
	echo '<tr><td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
				<td width=7% style="text-align: center;">Containers</td>
				<td width=10% style="text-align: right;">Items per Container</td>
				<td width=10% style="text-align: right;">Quantity</td>
				<td width=7% style="text-align: right;">Price</td>
				<td width=10% style="text-align: right;">Sub Total</td>
				<td width=5%>Remove</td></tr>';
	echo "<tr><td colspan='8'><hr></td></tr>";
	
	$Temp=1; $total = 0;
	while($row = mysqli_fetch_array($select_order_items)){ 
	    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
	    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
?>
	    <td>
			<input type='text' id='Container_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Container_Qty']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
		</td>
		<td>
			<input type='text' id='Items_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Items_Per_Container']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
		</td>
	    <td>
			<input type='text' id='QR<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
	    </td>
	    <td>
			<input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
	    </td>
<?php
	    echo "<td><input type='text' name='Sub_Total".$row['Purchase_Cache_ID']."' id='Sub_Total".$row['Purchase_Cache_ID']."' readonly='readonly' value='".number_format($row['Quantity_Required'] * $row['Price'])."' style='text-align: right;'></td>";
	    //echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
?>
	    <td width=6%>
		<input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Purchase_Cache_ID']; ?>)'>
	    </td>
<?php	
	    echo "</tr>";
	    $Temp++;
	}
	echo '</table>';
    }
?>