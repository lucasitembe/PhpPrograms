<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Emp_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Emp_Name = '';
	}

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

    $htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td>&nbsp;</td></tr></table>";
	
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Sponsor_ID, pd.Status, pd.Cleared_By, pd.Cleared_Date_Time
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_prepaid_details pd where
							pr.Registration_ID = pd.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							pd.Patient_Bill_ID = '$Patient_Bill_ID' and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Registration_ID = $data['Registration_ID'];
			$Gender = $data['Gender'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Member_Number = $data['Member_Number'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Sponsor_ID = $data['Sponsor_ID'];
			$Status = $data['Status'];
			$Cleared_By = $data['Cleared_By'];
			$Cleared_Date_Time = $data['Cleared_Date_Time'];

			//calculate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
		}
	}else{
		$Patient_Name = '';
		$Registration_ID = '';
		$Gender = '';
		$Date_Of_Birth = '';
		$Member_Number = '';
		$Guarantor_Name = '';
		$Sponsor_ID = '';
		$Status = 'Pending';
		$Cleared_By  = 0;
		$Cleared_Date_Time = '';
	}

	$htm .=	'<table width="100%">
			<tr>
				<td colspan="2"><span style="font-size: x-small;"><b>Patient Name &nbsp;&nbsp;&nbsp;</b>'.ucwords(strtolower($Patient_Name)).'</span></td>
				<td width="33%"><span style="font-size: x-small;"><b>Patient Number &nbsp;&nbsp;&nbsp;</b>'.$Registration_ID.'</span></td>
			</tr>
			<tr>
				<td width="33%"><span style="font-size: x-small;"><b>Member Number &nbsp;&nbsp;&nbsp;</b>'.$Member_Number.'</span></td>
				<td><span style="font-size: x-small;"><b>Gender &nbsp;&nbsp;&nbsp;</b>'.$Gender.'</span></td>
				<td><span style="font-size: x-small;"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b>'.strtoupper($Guarantor_Name).'</span></td>
			</tr>
		</table><br/>';

	//get categories
	$Grand_Total = 0;
	$get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.Billing_Type = 'Outpatient Cash' and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								pp.Transaction_status <> 'cancelled' and
								pp.Registration_ID = '$Registration_ID' and
								pp.Pre_Paid = 1 group by ic.Item_Category_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($get_cat);
	$htm .= "<span style='font-size: x-small;'><b>PRE/ POST PAID INVOICE</b></span><br/><br/>";

	if($num > 0){
		$temp_cat = 0;
		$htm .= "<span style='font-size: x-small;'><b>BILL DETAILS</b></span>";
		while ($row = mysqli_fetch_array($get_cat)) {
			$Item_category_ID = $row['Item_category_ID'];
			$htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
			$htm .= "<thead><tr><td colspan='7'><span style='font-size: x-small;'>".++$temp_cat.'. '.strtoupper($row['Item_Category_Name'])."</span></td></tr>";

			$htm .=	'<tr>
					<td width="4%"><span style="font-size: x-small;">SN</span></td>
					<td><span style="font-size: x-small;">ITEM NAME</span></td>
					<td width="10%" style="text-align: center;"><span style="font-size: x-small;">TRANSACTION#</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">PRICE</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">DISCOUNT</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">QUANTITY</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">SUB TOTAL</span></td>
				</tr></thead>';
			
				$items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
									tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									ic.Item_Category_ID = isc.Item_Category_ID and
									isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
									i.Item_ID = ppl.Item_ID and
									pp.Transaction_type = 'indirect cash' and
									pp.Billing_Type = 'Outpatient Cash' and
									pp.Transaction_status <> 'cancelled' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									pp.Patient_Bill_ID = '$Patient_Bill_ID' and
									ic.Item_category_ID = '$Item_category_ID' and
									pp.Registration_ID = '$Registration_ID' and
									pp.Pre_Paid = 1") or die(mysqli_error($conn));

			$nm = mysqli_num_rows($items);
			if($nm > 0){
				$temp = 0;
				$Sub_Total = 0;
				while ($dt = mysqli_fetch_array($items)) {
					$htm .= '<tr>
							<td width="4%"><span style="font-size: x-small;">'.++$temp.'<b>.</b></span></td>
							<td><span style="font-size: x-small;">'.ucwords(strtolower($dt['Product_Name'])).'</span></td>
							<td width="10%" style="text-align: center"><span style="font-size: x-small;">'.$dt['Patient_Payment_ID'].'</span></td>
							<td width="10%" style="text-align: right"><span style="font-size: x-small;">'.number_format($dt['Price']).'</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">'.number_format($dt['Discount']).'</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">'.$dt['Quantity'].'</span></td>
							<td width="10%" style="text-align: right;"><span style="font-size: x-small;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</span></td>
						</tr>';
					$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
					$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
				}
				$htm .= "<tr>
							<td colspan='6' style='text-align: right;'>
								<span style='font-size: x-small;'><b>SUB TOTAL</b></span></td><td style='text-align: right;'>
								<span style='font-size: x-small;'><b>".number_format($Sub_Total)."</b></span>
							</td>
						</tr>";
			}
			$htm .= "</table><br/>";
		}

		$htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
		$htm .= '<tr>
					<td width="90%" style="text-align: right;">
						<span style="font-size: x-small;"><b>GRAND TOTAL</b></span>
					</td>
					<td style="text-align: right;">
						<span style="font-size: x-small;"><b>'.number_format($Grand_Total).'</b></span></td></tr>';
		$htm .= '</table>';
	}



	$htm .=	'<br/><span style="font-size: x-small;"><b>BILL SUMMARY</b></span>
		<table width=100% border=1 style="border-collapse: collapse;">
			<thead><tr>
				<td width="5%"><span style="font-size: x-small;">SN</span></td>
				<td><span style="font-size: x-small;">CATEGORY NAME</span></td>
				<td width="20%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
			</tr></thead>';

	$get_cat = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
							tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
							ic.Item_Category_ID = isc.Item_Category_ID and
							isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
							i.Item_ID = ppl.Item_ID and
							pp.Transaction_type = 'indirect cash' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Billing_Type = 'Outpatient Cash' and
							pp.Transaction_status <> 'cancelled' and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							pp.Registration_ID = '$Registration_ID' and
							pp.Pre_Paid = 1 group by ic.Item_Category_ID") or die(mysqli_error($conn));

	$nms_slct = mysqli_num_rows($get_cat);
	$tmp = 0;
	$cont = 0;
	$Cat_Grand_Total = 0;
	if($nms_slct > 0){
		while ($dts = mysqli_fetch_array($get_cat)) {
			$Item_Category_Name = $dts['Item_Category_Name'];
			$Item_Category_ID = $dts['Item_Category_ID'];
			$Category_Grand_Total = 0;

			//calculate total
			$items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
								tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								ic.Item_Category_ID = isc.Item_Category_ID and
								isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
								i.Item_ID = ppl.Item_ID and
								pp.Transaction_type = 'indirect cash' and
								pp.Billing_Type = 'Outpatient Cash' and
								pp.Transaction_status <> 'cancelled' and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.Patient_Bill_ID = '$Patient_Bill_ID' and
								ic.Item_Category_ID = '$Item_Category_ID' and
								pp.Registration_ID = '$Registration_ID' and
								pp.Pre_Paid = 1") or die(mysqli_error($conn));
			$nums = mysqli_num_rows($items);
			if($nums > 0){
				while ($t_item = mysqli_fetch_array($items)) {
					$Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
					$Cat_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
				}
			}
			$htm .= "<tr><td><span style='font-size: x-small;'>".++$cont.'<b>.</b></span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($Item_Category_Name))."</span></td>
						<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($Category_Grand_Total)."</span></td></tr>";
		}
	}
	

	$htm .=	'<tr><td colspan="2" style="text-align: right;"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
				<td style="text-align: right;"><span style="font-size: x-small;"><b>'.number_format($Cat_Grand_Total).'</b></span></td></tr>
				</table><br/><br/>';
	
	$htm .=	'<span style="font-size: x-small;"><b>ADVANCE PAYMENTS</b></span>
			<table width=100% border=1 style="border-collapse: collapse;">
				<tr>
					<td width="5%"><span style="font-size: x-small;">SN</span></td>
					<td><span style="font-size: x-small;">ITEM NAME</span></td>
					<td width="12%"><span style="font-size: x-small;">RECEIPT#</span></td>
					<td width="20%"><span style="font-size: x-small;">RECEIPT DATE</span></td>
					<td width="12%" style="text-align: right;"><span style="font-size: x-small;">AMOUNT</span></td>
				</tr>';

	$Direct_Cash_Grand_Total = 0;
	$sn = 0;
	$select = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Patient_Payment_ID, PP.Payment_Date_And_Time, i.Product_Name, ppl.Item_Name from 
							tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
							pp.Transaction_type = 'direct cash' and
							ppl.Item_ID = i.Item_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {

			$htm .= '<tr>
						<td><span style="font-size: x-small;">'.++$sn.'<b>.</b></span></td>
						<td><span style="font-size: x-small;">'.ucwords(strtolower($data['Product_Name'].' ~ '.$data['Item_Name'])).'</span></td>
						<td><span style="font-size: x-small;">'.$data['Patient_Payment_ID'].'</span></td>
						<td><span style="font-size: x-small;">'.@date("d F Y H:i:s",strtotime($data['Payment_Date_And_Time'])).'</span></td>
						<td style="text-align: right;"><span style="font-size: x-small;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</span></td>
					</tr>';
			$Direct_Cash_Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
		}
	}

		$htm .= '<tr><td colspan="4" style="text-align: left;"><span style="font-size: x-small;"><b>TOTAL AMOUNT PAID</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>'.number_format($Direct_Cash_Grand_Total).'</b></span></td></tr>
				</table><br/>';
	
 
		$htm .= '
				<span style="font-size: x-small;"><b>OVERALL SUMMARY</b></span>
					<table width="55%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="50%"><span style="font-size: x-small;">Total Amount Required</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Cat_Grand_Total).'</span></td>
						</tr>
						<tr>
							<td width="50%"><span style="font-size: x-small;">Total Amount Paid</span></td>
							<td style="text-align: right;">';
		$htm .= '<span style="font-size: x-small;">'.number_format($Direct_Cash_Grand_Total).'</span>';				
		$htm .=	'</td>
					</tr>
					<tr>
						<td><span style="font-size: x-small;">Balance</span></td>';

		if($Cat_Grand_Total > $Direct_Cash_Grand_Total){
			$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Cat_Grand_Total - $Direct_Cash_Grand_Total).'</span></td>';
		}else{
			$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">0</span></td>';
		}
		$htm .=	'</tr>';
		
			if($Cat_Grand_Total < $Direct_Cash_Grand_Total){
				$htm .= "<tr>
							<td><span style='font-size: x-small;'>Refund Amount</span></td>
							<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($Direct_Cash_Grand_Total - $Cat_Grand_Total)."</span></td>
						</tr>";
			}

		$htm .=	'<tr><td width="50%"><span style="font-size: x-small;">Bill Status</span></td>';
		$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.$Status.'</span></td>';
		$htm .=	'</tr>';

		if(strtolower($Status) == 'cleared'){
			//get employee clearded the bill(Cash)
			$htm .=	'<tr><td width="50%"><span style="font-size: x-small;">Cleared By</span></td>';
			$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Cleared_By'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select);
			if($no > 0){
				while ($dtz = mysqli_fetch_array($select)) {
					$Cash_Clearer = $dtz['Employee_Name'];
				}
			}else{
				$Cash_Clearer = '';
			}
			$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.ucwords(strtolower($Cash_Clearer)).'</span></td></tr>';
			$htm .=	'<tr><td><span style="font-size: x-small;">Cleared Date</span></td>';
			$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.@date("d F Y H:i:s",strtotime($Cleared_Date_Time)).'</span></td></tr>';
		}
		$htm .= '</table></td></tr></table>';
	?>

<?php
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>