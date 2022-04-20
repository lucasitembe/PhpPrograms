<?php
include("./includes/connection.php");

	if(isset($_GET['Supplier_ID']) && $_GET['Supplier_ID'] != ""){
		$Supplier_ID = $_GET['Supplier_ID'];
	}else{
		$Supplier_ID = 0;
	}

// $startDate = 0;
	if(isset($_GET['start_date']) && $_GET['start_date'] != ""){
		$startDate = $_GET['start_date'];
	}else{
		$startDate = 0;
	}

	if(isset($_GET['end_date']) && $_GET['end_date'] != ""){
		$endDate = $_GET['end_date'];
		// echo Date($endDate);
	}else{
		$endDate = 0;
	}

	if(isset($_GET['Sub_Department_ID']) && $_GET['Sub_Department_ID'] != "") {
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}


	//Get SUpplier Name
	$slct = mysqli_query($conn,"SELECT Supplier_Name from tbl_supplier where Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while ($dt = mysqli_fetch_array($slct)) {
			$Supplier_Name = $dt['Supplier_Name'];
		}
	}else{
		$Supplier_Name = '';
	}
?>
<legend align="left"><b>VENDER LEDGER STATEMENT ~ <?php echo strtoupper($Supplier_Name); ?></b></legend>
<table width="100%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td><b>GRN DATE</b></td>
		<td width="13%"><b>STORE RECEIVED</b></td>
		<td width="16%"><b>GRN TYPE</b></td>
		<td width="8%"><b>GRN NUMBER</b></td>
		<td width="8%"><b>INVOICE NUMBER</b></td>
		<td width="8%"><b>DELIVERY NOTE NO</b></td>
		<td width="8%"><b>DELIVERY DATE</b></td>
		<td width="12%" style="text-align: right;"><b>AMOUNT</b></td>
		<td  width="8%"><b></b></td>
	</tr>
<?php
	$temp = 0;
	//get start date AND Created_Date BETWEEN ('$startDate' AND '$endDate')
	$select = mysqli_query($conn,"SELECT (select Created_Date_Time from tbl_grn_purchase_order where supplier_id = '$Supplier_ID' order by Grn_Purchase_Order_ID limit 1) as Grn_Date,
							Grn_Date_And_Time as Grn_Without_Date from tbl_grn_without_purchase_order order by Grn_ID limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select);
	if($nm > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Grn_Date'] < $data['Grn_Without_Date']){
				$Start_Date = strtotime(substr($startDate, 0, 10));
			}else{
				$Start_Date = strtotime(substr($startDate, 0, 10));
			}
		}
	}

	$select = mysqli_query($conn,"select (select Created_Date_Time from tbl_grn_purchase_order where supplier_id = '$Supplier_ID' order by Grn_Purchase_Order_ID desc limit 1) as Grn_Date,
							Grn_Date_And_Time as Grn_Without_Date from tbl_grn_without_purchase_order order by Grn_ID desc limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select);
	if($nm > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Grn_Date'] > $data['Grn_Without_Date']){
				$End_Date = strtotime(substr($endDate, 0, 10));
			}else{
				$End_Date = strtotime(substr($endDate, 0, 10));
			}
		}
	}
	$Grand_Total = 0;
	for ($i=$Start_Date; $i<=$End_Date; $i+=86400) {
		$Current_Date = date("Y-m-d", $i);
		$S_Date = $Current_Date.' 00:00';
		$E_Date = $Current_Date.' 23:59';

		//Get Grn Against Purchase Order
		$select1 = mysqli_query($conn,"SELECT gpo.Grn_Purchase_Order_ID, gpo.Created_Date_Time, Invoice_Number, Debit_Note_Number, Delivery_Date, sd.Sub_Department_Name FROM
								tbl_grn_purchase_order gpo, tbl_sub_department sd, tbl_purchase_order po where
								gpo.supplier_id = '$Supplier_ID' and
								po.Purchase_Order_ID = gpo.Purchase_Order_ID and
								po.Sub_Department_ID = sd.Sub_Department_ID and 
								sd.Sub_Department_ID = '$Sub_Department_ID' and 
                            	gpo.Created_Date_Time between '$S_Date' and '$E_Date'") or die(mysqli_error($conn));
		$nmz = mysqli_num_rows($select1);
		if($nmz > 0){
			while ($dtz = mysqli_fetch_array($select1)) {
				$Grn_Purchase_Order_ID = $dtz['Grn_Purchase_Order_ID'];
				$slct = mysqli_query($conn,"SELECT (Quantity_Received * Buying_Price) as Amount_Needed from
									tbl_purchase_order_items poi where
	                            	poi.Item_Status = 'active' and
	                            	poi.Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID' and
	                                Grn_Status IN ('RECEIVED','OUTSTANDING')") or die(mysqli_error($conn));
				$numb = mysqli_num_rows($slct);
				if($numb > 0){
					$Total = 0;
					while ($td = mysqli_fetch_array($slct)) {
						$Total += $td['Amount_Needed'];
					}
					echo '<tr>
								<td>'.++$temp.'</td>
								<td>'.$dtz['Created_Date_Time'].'</td>
								<td>'.$dtz['Sub_Department_Name'].'</td>
								<td>GRN AGAINST PURCHASE ORDER</td>
								<td>'.$dtz['Grn_Purchase_Order_ID'].'</td>
								<td>'.$dtz['Invoice_Number'].'</td>
								<td>'.$dtz['Debit_Note_Number'].'</td>
								<td>'.$dtz['Delivery_Date'].'</td>
								<td style="text-align: right;">'.number_format($Total,2).'</td>
								<td style="text-align: center;"><a href="grnpurchaseorderreport.php?Grn_Purchase_Order_ID='.$dtz['Grn_Purchase_Order_ID'].'&GrnPurchaseOrder=GrnPurchaseOrderThisPage" target="_blank" class="art-button-green">PREVIEW</a></td>
							</tr>';
					$Grand_Total += $Total;
				}
			}
		}

		//Get Grn Without Purchase Order
		$select2 = mysqli_query($conn,"select Grn_ID, Grn_Date_And_Time, Debit_Note_Number, Invoice_Number, Delivery_Date, sd.Sub_Department_Name from
								tbl_grn_without_purchase_order gp, tbl_sub_department sd where
								gp.Sub_Department_ID = sd.Sub_Department_ID and
								sd.Sub_Department_ID = '$Sub_Department_ID' and
								gp.Grn_Date_And_Time between '$S_Date' and '$E_Date' and
								gp.Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
		$numb = mysqli_num_rows($select2);
		if($numb > 0){
			while ($dtz = mysqli_fetch_array($select2)) {
				$Grn_ID = $dtz['Grn_ID'];
				$slct = mysqli_query($conn,"select (Quantity_Required * Price) as Amount_Needed from tbl_grn_without_purchase_order_items where Grn_ID = '$Grn_ID'") or die(mysqli_error($conn));
				$nmz = mysqli_num_rows($slct);
				if($nmz > 0){
					$Total = 0;
					while ($dt = mysqli_fetch_array($slct)) {
						$Total += $dt['Amount_Needed'];
					}
					echo '<tr>
								<td>'.++$temp.'</td>
								<td>'.$dtz['Grn_Date_And_Time'].'</td>
								<td>'.$dtz['Sub_Department_Name'].'</td>
								<td>GRN WITHOUT PURCHASE ORDER</td>
								<td>'.$Grn_ID.'</td>
								<td>'.$dtz['Invoice_Number'].'</td>
								<td>'.$dtz['Debit_Note_Number'].'</td>
								<td>'.$dtz['Delivery_Date'].'</td>
								<td style="text-align: right;">'.number_format($Total,2).'</td>
								<td style="text-align: center;"><a href="previewgrnwithoutpurchaseorderreport.php?Grn_ID='.$Grn_ID.'&GrnWithoutPurchaseOrder=GrnWithoutPurchaseOrderThisPage" target="_blank" class="art-button-green">PREVIEW</a></td>
							</tr>';
					$Grand_Total += $Total;
				}
			}
		}
	}
?>
<tr>
	<td colspan="8"><b>GRAND TOTAL</b>
	<td style="text-align: right;"><b><?php echo number_format($Grand_Total, 2); ?></b></td>
</tr>
