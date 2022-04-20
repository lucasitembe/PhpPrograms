<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}
	
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	$delete = mysqli_query($conn,"DELETE FROM tbl_item_list_cache where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Status <> 'Sample Collected'") or die(mysqli_error($conn));

	//display other items
	$select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Payment_Cache_ID = $data['Payment_Cache_ID'];
		}
	}else{
		$Payment_Cache_ID = '';
	}

	//display Laboratorys ordered
	$select = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
							i.Item_ID = ilc.Item_ID and
							ilc.Payment_Cache_ID = '$Payment_Cache_ID' and i.Product_Name LIKE '%biopsy%' and
							Check_In_Type = 'Laboratory'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		$temp = 0;
?>
		<table width="100%">
			<tr>
				<td width="5%"><b>SN</b></td>
				<td><b>INVESTIGATION NAME</b></td>
				<td width="10%"><b>TYPE</b></td>
				<td width="10%"><b>PRICE</b></td>
				<td width="10%"><b>QTY</b></td>
				<td width="4%">ACTION</td>
			</tr>
<?php
		while ($row = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $row['Product_Name']; ?></td>
				<td><?php echo $row['Transaction_Type']; ?></td>
				<td><?php echo $row['Price']; ?></td>
				<td><?php echo $row['Quantity']; ?></td>
			 <?php
                if(strtolower($row['Status']) == 'Sample Collected'){
                    echo '<td><input type="button" name="Remove_Investigation" id="Remove_Investigation" value="X" onclick="Remove_Investigation_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                }else{
                    echo '<td><input type="button" name="Remove_Inv" id="Remove_Inv" value="X" onclick="Remove_Investigation_biopsy('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                }
            ?>
			</tr>
<?php
		}
	}else{
		echo "<br/><br/><br/><br/><center><h3>NO INVESTIGATION ORDERED</h3></center>";
	}
?>