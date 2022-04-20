<?php
    session_start();
    include("./includes/connection.php");
?>
<table width='100%' border='1'>
    <tr style='background-color: #1e90ff;'>
	<td align='center' width='2%' >SN</td>
	<td>Particular</td>
	<td width='10%'>Opening Balance</td>
	<td width='5%'>Inward</td>
	<td width='5%'>Outward</td>
	<td width='5%'>Balance</td>
	<td width='5%'>Average</td>
	<td width='5%'>Total</td>
    </tr>
    <?php
	$qr = "SELECT * FROM tbl_items";
	if(isset($_GET['item_name'])){
	    $Product_Name = $_GET['item_name'];
	    $Sub_Department_ID = $_GET['Sub_Department_ID'];
	    $Branch_ID = $_GET['Branch_ID'];
	    $from_date = $_GET['from_date'];
	    $to_date = $_GET['to_date'];
	    
	    $qr.=" WHERE Product_Name LIKE '%$Product_Name%'";
	}
	$result = mysqli_query($conn,$qr);
	$i = 1;
	
	while($row = mysqli_fetch_assoc($result)){
	    $Item_ID = $row['Item_ID'];
	    //Get Opening Balance
	    $opening_qr1 = "SELECT SUM(ri.Quantity_Received) as opening FROM 
		tbl_requisition_items as ri
		join tbl_items  as i on ri.Item_ID = i.Item_ID 
		join tbl_requisition as r on r.Requisition_ID = ri.Requisition_ID
		join tbl_issued as iss on iss.req_id = r.Requisition_ID
		join tbl_grnissue as gi on  gi.issue_id = iss.issue_id
		WHERE r.Store_Need = $Sub_Department_ID
		AND iss.isReceived=1
		AND i.Item_ID = $Item_ID
		AND iss.date_issued < $from_date
		";
		
	    $opening_qr2 = "SELECT SUM(poi.Quantity_Received) as opening FROM 
		tbl_purchase_order_items poi,
		tbl_Items i,
		tbl_purchase_order po,
		tbl_grn_purchase_order gpo
		WHERE
		i.Item_ID = poi.Item_ID
		AND po.Purchase_Order_ID = poi.Purchase_Order_ID
		AND po.Sub_Department_ID = $Sub_Department_ID
		AND gpo.Purchase_Order_ID = po.Purchase_Order_ID
		AND po.Order_Status = 'Served'
		AND i.Item_ID = $Item_ID
		AND gpo.Created_Date < $from_date
		";
		
	    $opening_qr3 = "SELECT SUM(goi.quantity_received) as opening FROM
		tbl_grnopenbalance_items goi,
		tbl_grnopenbalance gob
		
		WHERE
		gob.grn_openbalance_id = goi.grn_openbalance_id
		AND gob.receiver = $Sub_Department_ID
		AND goi.Item_ID = $Item_ID
		AND gob.create_date < $from_date ";
	    
	    $opening_result1 = mysqli_query($conn,$opening_qr1);
	    $opening_result2 = mysqli_query($conn,$opening_qr2);
	    $opening_result3 = mysqli_query($conn,$opening_qr3);
	    
	    $opening = mysqli_fetch_assoc($opening_result1)['opening'];
	    $opening += mysqli_fetch_assoc($opening_result2)['opening'];
	    $opening += mysqli_fetch_assoc($opening_result3)['opening'];
	    
	    
	    //get Inward Balance
	    $inward_qr1 = "SELECT SUM(ri.Quantity_Received) as inward FROM 
		tbl_requisition_items as ri
		join tbl_items  as i on ri.Item_ID = i.Item_ID 
		join tbl_requisition as r on r.Requisition_ID = ri.Requisition_ID
		join tbl_issued as iss on iss.req_id = r.Requisition_ID
		join tbl_grnissue as gi on  gi.issue_id = iss.issue_id
		WHERE r.Store_Need = $Sub_Department_ID
		AND iss.isReceived=1
		AND i.Item_ID = $Item_ID
		AND iss.date_issued BETWEEN $from_date AND $to_date ";
		
	    $inward_qr2 = "SELECT SUM(poi.Quantity_Received) as inward FROM 
		tbl_purchase_order_items poi,
		tbl_Items i,
		tbl_purchase_order po,
		tbl_grn_purchase_order gpo
		WHERE
		i.Item_ID = poi.Item_ID
		AND po.Purchase_Order_ID = poi.Purchase_Order_ID
		AND po.Sub_Department_ID = $Sub_Department_ID
		AND gpo.Purchase_Order_ID = po.Purchase_Order_ID
		AND po.Order_Status = 'Served'
		AND i.Item_ID = $Item_ID
		AND gpo.Created_Date BETWEEN $from_date AND $to_date";
		
	    $inward_qr3 = "SELECT SUM(goi.quantity_received) as inward FROM
		tbl_grnopenbalance_items goi,
		tbl_grnopenbalance gob
		
		WHERE
		gob.grn_openbalance_id = goi.grn_openbalance_id
		AND gob.receiver = $Sub_Department_ID
		AND goi.Item_ID = $Item_ID
		AND gob.create_date BETWEEN $from_date AND $to_date ";
	    
	    $inward_result1 = mysqli_query($conn,$inward_qr1);
	    $inward_result2 = mysqli_query($conn,$inward_qr2);
	    $inward_result3 = mysqli_query($conn,$inward_qr3);
	    
	    $inward = mysqli_fetch_assoc($inward_result1)['inward'];
	    $inward += mysqli_fetch_assoc($inward_result2)['inward'];
	    $inward += mysqli_fetch_assoc($inward_result3)['inward'];
	    
	    if($inward==''){
		$inward = 0;
	    }
	    
	    //get Outward Balance
	    $outward_qr1 = "SELECT SUM(ri.Quantity_Issued) as outward FROM
		tbl_requisition_items as ri
		join tbl_items  as i on ri.Item_ID = i.Item_ID
		join tbl_requisition as r on r.Requisition_ID = ri.Requisition_ID
		join tbl_issued as iss on iss.req_id = r.Requisition_ID
		join tbl_grnissue as gi on  gi.issue_id = iss.issue_id
		WHERE r.Store_Issue = $Sub_Department_ID
		AND iss.isReceived=1
		AND i.Item_ID = $Item_ID
		AND iss.date_issued BETWEEN $from_date AND $to_date ";
		
	    $outward_qr2 = "SELECT SUM(ppi.Quantity) as outward FROM 
			    tbl_patient_payment_item_list ppi
			    
			    WHERE
			    ppi.Item_ID =$Item_ID
		";
		
	    $outward_result1 = mysqli_query($conn,$outward_qr1);
	    $outward = mysqli_fetch_assoc($outward_result1)['outward'];
	    
	    $outward_result2 = mysqli_query($conn,$outward_qr2);
	    $outward += mysqli_fetch_assoc($outward_result2)['outward'];
	    
	    if($outward==''){
		$outward = 0;
	    }
	    ?>
	    <tr>
		<td align='center' style='background-color: #1e90ff;'><?php echo $i; ?></td>
		<td><a href='stockmovementItemdetails.php?Item_ID=<?php echo $row['Item_ID']; ?>' target='_parent'><?php echo $row['Product_Name']; ?></a></td>
		<td><input style='width: 100%' type='text' value='<?php echo $opening; ?>' readonly></td>
		<td><input style='width: 100%' type='text' value='<?php echo $inward; ?>' readonly></td>
		<td><input style='width: 100%' type='text' value='<?php echo $outward; ?>' readonly></td>
		<td><input style='width: 100%' type='text' value='<?php echo (($opening+$inward)-$outward); ?>' readonly></td>
		<td><input style='width: 100%' type='text' value='0' readonly></td>
		<td><input style='width: 100%' type='text' value='0' readonly></td>
	    </tr>
	    <?php
	    $i++;
	}
    ?>
</table>