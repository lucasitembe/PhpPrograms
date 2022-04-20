<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}
	
	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}
	
	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = 'All';
	}
	
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = '0';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor = $_GET['Sponsor_ID'];
	}else{
		$Sponsor = 0;
	}

	$filter = " pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and pp.Transaction_status <> 'cancelled' and ";

	if($Employee_ID != null && $Employee_ID != '' && $Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and ";
	}

	if($Sponsor != 0 && $Sponsor != null && $Sponsor != ''){
		$filter .= " pp.Sponsor_ID = '$Sponsor' and ";
	}
?>

<legend><b>REVENUE SUMMARY PATIENT CONTRIBUTIONS</b></legend>
<table width="100%">
<?php
	$Title = "<tr><td colspan='5'><hr></td></tr>
				<tr>
				    <td width=5%><b>SN</b></td>
				    <td><b>PARTICULAR NAME</b></td>
				    <td style='text-align: right;' width='15%'><b>CASH</b></td>
				    <td style='text-align: right;' width='15%'><b>CREDIT</b></td>
				    <td style='text-align: right;' width='15%'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr><td colspan='5'><hr></td></tr>";
	echo $Title;
	$temp = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;

	//Tunatumia hii kwa muda mpaka fast track itakapokamilika maeneo yote
	$Total_Cash_Fast_Track = 0;
	$Total_Credit_Fast_Track = 0;

	//get Particular type
	$get_particulars = mysqli_query($conn,"select Particular_Type from tbl_items group by Particular_Type order by Particular_Type asc") or die(mysqli_error($conn));
	$num_particulars = mysqli_num_rows($get_particulars);
	if($num_particulars > 0){
		while ($data = mysqli_fetch_array($get_particulars)) {
			$Particular_Type = $data['Particular_Type'];
			$Total_Cash = 0;
			$Total_Credit = 0;

			if($Billing_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										(pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}else if($Billing_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										(pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}else{
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}

			while ($row = mysqli_fetch_array($select)) {
				$Amount = (($row['Price'] - $row['Discount']) * $row['Quantity']);
				if($row['Fast_Track'] == '1' || strtolower($Particular_Type) == 'fast track'){
					if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
				        $Total_Cash_Fast_Track += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }else if((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
				        $Total_Credit_Fast_Track += $Amount;
				        $Grand_Total_Credit += $Amount;
				    }
				}else{
					if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
				        $Total_Cash += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }else if((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
				        $Total_Credit += $Amount;
				        $Grand_Total_Credit += $Amount;
				    }
				}
			}
			if(strtolower($Particular_Type) != 'fast track'){
?>
			<tr>
			    <td><?php echo ++$temp; ?></td>
			    <td><?php echo strtoupper($data['Particular_Type']); ?></td>

			    <td style='text-align: right;'><?php echo number_format($Total_Cash); ?></td>
			    <td style='text-align: right;'><?php echo number_format($Total_Credit); ?></td>
			    <td style='text-align: right;'><?php echo number_format($Total_Cash + $Total_Credit); ?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
<?php
			}
		}
	}
?>
<tr>
    <td><?php echo ++$temp; ?></td>
    <td>FAST TRACK</td>
    <td style='text-align: right;'><?php echo number_format($Total_Cash_Fast_Track); ?></td>
    <td style='text-align: right;'><?php echo number_format($Total_Credit_Fast_Track); ?></td>
    <td style='text-align: right;'><?php echo number_format($Total_Cash_Fast_Track + $Total_Credit_Fast_Track); ?>&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr><td colspan="5"><hr></td></tr>
<tr>
    <td colspan="2"><b>GRAND TOTAL</b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Cash); ?></b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Credit); ?></b></td>
    <td style='text-align: right;'><b><?php echo number_format($Grand_Total_Cash + $Grand_Total_Credit); ?>&nbsp;&nbsp;&nbsp;</b></td>
</tr>
<tr><td colspan="5"><hr></td></tr>
</table>