<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$E_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$E_Name = '';
	}
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	//get Guarantor_Name
	if($Sponsor_ID == 0){
		$Guarantor_Name = 'All';
	}else{
		$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Guarantor_Name = $data['Guarantor_Name'];
			}
		}else{
			$Guarantor_Name = 'All';
		}
	}
	
	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}

	
	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}

    $htm = "<table width ='100%' height = '30px'>
			<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td></td></tr></table>";
		
	$htm .= '<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">SPECTACLES SALES REPORT</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($Start_Date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($End_Date)).'</b></span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">SPONSOR : '.strtoupper($Guarantor_Name).'</span></td></tr>
	        </table></center>';


	$htm .=	'<table width="100%" border=1 style="border-collapse: collapse;">
			    <thead><tr>
			        <td width="5%"><b><span style="font-size: x-small;">SN</span></b></td>
			        <td><b><span style="font-size: x-small;">PARTICULAR NAME</span></b></td>
			        <td width="7%" style="text-align: center;"><b><span style="font-size: x-small;">CASH QTY</span></b></td>
			        <td width="13%" style="text-align: right;"><b><span style="font-size: x-small;">CASH VALUE</span></b></td>
			        <td width="7%" style="text-align: center;"><b><span style="font-size: x-small;">CREDIT QTY</span></b></td>
			        <td width="13%" style="text-align: right;"><b><span style="font-size: x-small;">CREDIT VALUE</span></b></td>
			        <td width="7%" style="text-align: center;"><b><span style="font-size: x-small;">TOTAL QTY</span></b></td>
			        <td width="13%" style="text-align: right;"><b><span style="font-size: x-small;">TOTAL</span></b></td>
			    </tr></thead>';

	if($Sponsor_ID == 0){
		$select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
							from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
							i.Item_ID = ppl.Item_ID and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							i.Consultation_Type = 'Optical' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and
							pp.Transaction_status <> 'cancelled' and
							pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
							
	}else{
		$select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name 
							from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_item_list_cache ilc where
							i.Item_ID = ppl.Item_ID and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							i.Consultation_Type = 'Optical' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and
							pp.Transaction_status <> 'cancelled' and
							pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by i.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
	}
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		$Grand_Total_Quantity_Cash = 0;
		$Grand_Total_Quantity_Credit = 0;
		$Grand_Total_Value_Cash = 0;
		$Grand_Total_Value_Credit = 0;
		while ($data = mysqli_fetch_array($select)) {
			$Total_Quantity_Cash = 0;
			$Total_Quantity_Credit = 0;
			$Total_Value_Cash = 0;
			$Total_Value_Credit = 0;
			$Item_ID = $data['Item_ID'];

			//generate quantity & Total
			if($Sponsor_ID == 0){
				$select_details = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
									from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									i.Item_ID = ppl.Item_ID and
									i.Item_ID = '$Item_ID' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									i.Consultation_Type = 'Optical' and
									pp.Transaction_status <> 'cancelled' and
									pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
			}else{
				$select_details = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount, pp.Billing_Type, pp.payment_type
									from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
									i.Item_ID = ppl.Item_ID and
									i.Item_ID = '$Item_ID' and
									pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
									pp.Sponsor_ID = '$Sponsor_ID' and
									i.Consultation_Type = 'Optical' and
									pp.Transaction_status <> 'cancelled' and
									pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
			}
			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					if(strtolower($row['Billing_Type']) == 'outpatient cash' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
						$Total_Quantity_Cash += $row['Quantity'];
						$Total_Value_Cash += (($row['Price'] - $row['Discount']) * $row['Quantity']);
						$Grand_Total_Quantity_Cash += $row['Quantity'];
						$Grand_Total_Value_Cash += (($row['Price'] - $row['Discount']) * $row['Quantity']);
					}else if(strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
						$Total_Quantity_Credit += $row['Quantity'];
						$Total_Value_Credit += (($row['Price'] - $row['Discount']) * $row['Quantity']);
						$Grand_Total_Quantity_Credit += $row['Quantity'];
						$Grand_Total_Value_Credit += (($row['Price'] - $row['Discount']) * $row['Quantity']);
					}
				}
			}

			$htm .=	'<tr>
				        <td><span style="font-size: x-small;">'.++$temp.'</span></td>
				        <td><span style="font-size: x-small;">'.$data['Product_Name'].'</span></td>
				        <td style="text-align: center;"><span style="font-size: x-small;">'.number_format($Total_Quantity_Cash).'</span></td>
				        <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Value_Cash).'</span></td>
				        <td style="text-align: center;"><span style="font-size: x-small;">'.number_format($Total_Quantity_Credit).'</span></td>
				        <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Value_Credit).'</span></td>
				        <td style="text-align: center;"><span style="font-size: x-small;">'.number_format($Total_Quantity_Credit + $Total_Quantity_Cash).'</span></td>
				        <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Value_Credit + $Total_Value_Cash).'</span></td>
				    </tr>';

		}

		$htm .=	'<tr>
					<td colspan="2"><span style="font-size: x-small;"><b>GRAND TOTAL</b></span></td>
					<td style="text-align: center;"><span style="font-size: x-small;"><b>'.$Grand_Total_Quantity_Cash.'</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>'.number_format($Grand_Total_Value_Cash).'</b></span></td>
					<td style="text-align: center;"><span style="font-size: x-small;"><b>'.$Grand_Total_Quantity_Credit.'</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>'.number_format($Grand_Total_Value_Credit).'</b></span></td>
					<td style="text-align: center;"><span style="font-size: x-small;"><b>'.($Grand_Total_Quantity_Credit + $Grand_Total_Quantity_Cash).'</b></span></td>
					<td style="text-align: right;"><span style="font-size: x-small;"><b>'.number_format($Grand_Total_Value_Credit + $Grand_Total_Value_Cash).'</b></span></td>
				</tr>';
	}
	$htm .= '</table>';

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>