<?php
	include("../includes/connection.php");
	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
	}
	$qr = "SELECT * FROM tbl_mobile_payment mp, tbl_items i WHERE i.Item_ID = mp.item_ID AND mp.payment_code = '$payment_code'";
	$result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$amount = 0;
	$i = 1;
	while($row = mysqli_fetch_assoc($result)){
		$amount+= (($row['Price']*$row['Quantity'])-$row['Discount']);
		?>
		<tr>
			<td width="5%"><?php echo $i; ?></td>
            <td><?php 
            if($row['Item_Name'] !=''){
            	echo $row['Item_Name']; 
            }else{
            	$Item_ID = $row['item_ID'];
            	$select_item_name = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
            	$item_name_result = mysqli_query($conn,$select_item_name) or die(mysqli_error($conn));
            	$Item_Name = mysqli_fetch_assoc($item_name_result)['Product_Name'];
            	echo $Item_Name;
            }

            ?></td>
            <td width="10%"><?php echo $row['Discount']; ?></td>
            <td width="10%"><?php echo $row['Price']; ?></td>
            <td width="10%"><?php echo $row['Quantity']; ?></td>
            <td width="10%"><?php echo (($row['Price']*$row['Quantity'])-$row['Discount']); ?></td>
        </tr>
		<?php
		$i++;
	}
	?>
		<tr>
			<td width="5%"></td>
            <td></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"><b><?php echo $amount; ?></b></td>
        </tr>
    <?php
?>