<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
    }

	if(isset($_GET['Supplier_ID'])){
		$Supplier_ID = $_GET['Supplier_ID'];
	}else{
		$Supplier_ID = 0;
	}

	if(isset($_GET['Sub_Department_ID']) && $_GET['Sub_Department_ID'] != "") {
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}

	if(isset($_GET['Date_From']) && $_GET['Date_From'] != ""){
		$Date_From = $_GET['Date_From'];
	}

	if(isset($_GET['Date_To']) && $_GET['Date_To'] != ""){
		$Date_To = $_GET['Date_To'];
	}

	//Get SUpplier Name
	$slct = mysqli_query($conn,"select Supplier_Name from tbl_supplier where Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while ($dt = mysqli_fetch_array($slct)) {
			$Supplier_Name = $dt['Supplier_Name'];
		}
	}else{
		$Supplier_Name = '';
	}
	$htm = "<table width ='100%'  class='nobordertable'>
			    <tr><td width=100%><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	            <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>VENDER LEDGER STATEMENT</span></b></td></tr>
	            <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>SUPPLIER NAME : ".strtoupper($Supplier_Name)."</span></b></td></tr>
            </table>";

	$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
				<tr>
					<td width="5%"><b><span style="font-size: x-small;">SN</span></b></td>
					<td><b><span style="font-size: x-small;">GRN DATE</span></b></td>
					<td width="13%"><b><span style="font-size: x-small;">STORE RECEIVED</span></b></td>
					<td width="16%"><b><span style="font-size: x-small;">GRN TYPE</span></b></td>
					<td width="8%"><b><span style="font-size: x-small;">GRN NUMBER</span></b></td>
					<td width="8%"><b><span style="font-size: x-small;">INVOICE NUMBER</span></b></td>
					<td width="8%"><b><span style="font-size: x-small;">DELIVERY NOTE NO</span></b></td>
					<td width="8%"><b><span style="font-size: x-small;">DELIVERY DATE</span></b></td>
					<td width="12%" style="text-align: right;"><b><span style="font-size: x-small;">AMOUNT</span></b></td>
				</tr>';

	$temp = 0;
	//get start date
	$select = mysqli_query($conn,"select (select Created_Date_Time from tbl_grn_purchase_order where supplier_id = '$Supplier_ID' order by Grn_Purchase_Order_ID limit 1) as Grn_Date, 
							Grn_Date_And_Time as Grn_Without_Date from tbl_grn_without_purchase_order  order by Grn_ID limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select);
	if($nm > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Grn_Date'] < $data['Grn_Without_Date']){
				$Start_Date = strtotime(substr($data['Grn_Date'], 0, 10));
			}else{
				$Start_Date = strtotime(substr($data['Grn_Without_Date'], 0, 10));
			}
		}
	}

	$select = mysqli_query($conn,"select (select Created_Date_Time from tbl_grn_purchase_order where supplier_id = '$Supplier_ID' order by Grn_Purchase_Order_ID desc limit 1) as Grn_Date, 
							Grn_Date_And_Time as Grn_Without_Date from tbl_grn_without_purchase_order order by Grn_ID desc limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select);
	if($nm > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Grn_Date'] > $data['Grn_Without_Date']){
				$End_Date = strtotime(substr($data['Grn_Date'], 0, 10));
			}else{
				$End_Date = strtotime(substr($data['Grn_Without_Date'], 0, 10));
			}
		}
	}
	$Grand_Total = 0;
	// for ($i=$Start_Date; $i<=$End_Date; $i+=86400) {
	// 	$Current_Date = date("Y-m-d", $i);
	// 	$S_Date = $Current_Date.' 00:00';
	// 	$E_Date = $Current_Date.' 23:59';

		//Get Grn Against Purchase Order
		$select1 = mysqli_query($conn,"select gpo.Grn_Purchase_Order_ID, gpo.Created_Date_Time, Invoice_Number, Debit_Note_Number, Delivery_Date, sd.Sub_Department_Name from 
								tbl_grn_purchase_order gpo, tbl_sub_department sd, tbl_purchase_order po where
								gpo.supplier_id = '$Supplier_ID' and
								po.Purchase_Order_ID = gpo.Purchase_Order_ID and
								po.Sub_Department_ID = sd.Sub_Department_ID and 
								sd.Sub_Department_ID = '$Sub_Department_ID' and 
                            	gpo.Created_Date_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
		$nmz = mysqli_num_rows($select1);
		if($nmz > 0){
			while ($dtz = mysqli_fetch_array($select1)) {
				$Grn_Purchase_Order_ID = $dtz['Grn_Purchase_Order_ID'];
				$slct = mysqli_query($conn,"select (Quantity_Received * Buying_Price) as Amount_Needed from 
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
					$htm .= '<tr>
								<td><span style="font-size: x-small;">'.++$temp.'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Created_Date_Time'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Sub_Department_Name'].'</span></td>
								<td><span style="font-size: x-small;">GRN AGAINST PURCHASE ORDER</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Grn_Purchase_Order_ID'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Invoice_Number'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Debit_Note_Number'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Delivery_Date'].'</span></td>
								<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total,2).'</span></td>
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
								gp.Grn_Date_And_Time between '$Date_From' and '$Date_To' and
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
					$htm .= '<tr>
								<td><span style="font-size: x-small;">'.++$temp.'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Grn_Date_And_Time'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Sub_Department_Name'].'</span></td>
								<td><span style="font-size: x-small;">GRN WITHOUT PURCHASE ORDER</span></td>
								<td><span style="font-size: x-small;">'.$Grn_ID.'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Invoice_Number'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Debit_Note_Number'].'</span></td>
								<td><span style="font-size: x-small;">'.$dtz['Delivery_Date'].'</span></td>
								<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total,2).'</span></td>
							</tr>';
					$Grand_Total += $Total;
				}
			}
		}
	// }

	$htm .= '<tr>
				<td colspan="8"><b><span style="font-size: x-small;">GRAND TOTAL</span></b>
				<td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total, 2).'</span></b></td>
			</tr></table>';

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>