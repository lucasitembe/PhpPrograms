<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Emp_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Emp_Name = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
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
    }

	//Get Patient Details
	$select = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, ci.Check_In_Date_And_Time from
							tbl_patient_registration pr, tbl_check_in ci where
							pr.Registration_ID = ci.Registration_ID and
							pr.Registration_ID = '$Registration_ID' and
							ci.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Gender = $data['Gender'];
			$Phone_Number = $data['Phone_Number'];
			$Check_In_Date_And_Time = $data['Check_In_Date_And_Time'];
		}
	}else{
		$Patient_Name = '';
		$Date_Of_Birth = '';
		$Gender = '';
		$Phone_Number = '';
		$Check_In_Date_And_Time = '';
	}

	$date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";

	//get sponsor
	$select = mysqli_query($conn,"SELECT Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Guarantor_Name = $data['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

    $htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td>&nbsp;</td></tr></table>";

	$htm .=	'<table width="100%">
				<tr>
					<td width="13%" style="text-align: right;"><span style="font-size: x-small;">Patient Name : </span></td>
					<td width="20%"><span style="font-size: x-small;">'.ucwords(strtolower($Patient_Name)).'</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">Patient # : </span></td>
					<td width="9%"><span style="font-size: x-small;">'.$Registration_ID.'</span></td>
					<td width="10%" style="text-align: right;"><span style="font-size: x-small;">Sponsor Name : </span></td>
					<td width="15%"><span style="font-size: x-small;">'.ucwords(strtolower($Guarantor_Name)).'</span></td>
				</tr>
				<tr>
					<td style="text-align: right;"><span style="font-size: x-small;">Gender : </span></td>
					<td><span style="font-size: x-small;">'.strtoupper($Gender).'</span></td>
					<td style="text-align: right;"><span style="font-size: x-small;">Visit Date : </span></td>
					<td><span style="font-size: x-small;">'.@date("d F Y H:i:s",strtotime($Check_In_Date_And_Time)).'</span></td>
					<td style="text-align: right;"><span style="font-size: x-small;">Patient Age : </span></td>
					<td><span style="font-size: x-small;">'.$age.'</span></td>
				</tr>
			</table><br/><br/><b><span style="font-size: x-small;">PAYMENT DETAILS</span></b>';

	$Grand_Total = 0;
        $Grand_Total_outpatient=0;
        $Grand_Total_inpatient=0;
	//Get Receipts
	$select_res = mysqli_query($conn,"SELECT Patient_Payment_ID, Payment_Date_And_Time, Billing_Type from
	tbl_patient_payments where
	Registration_ID = '$Registration_ID' and
	Check_In_ID = '$Check_In_ID' and
	Sponsor_ID = '$Sponsor_ID' and
	Transaction_status <> 'cancelled' and auth_code <> '' AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list) and 
	(((Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Patient From Outside') and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and payment_type = 'pre'))") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select_res);
	if($nm > 0){
		while ($row = mysqli_fetch_array($select_res)) {
			$count = 0;
			$Total = 0;
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
			$Billing_Type = $row['Billing_Type'];

			$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
						<tr>
							<td style="text-align: left;" width="33%"><span style="font-size: x-small;">Receipt Number : '.$Patient_Payment_ID.'</span></td>
							<td style="text-align: left;" width="33%"><span style="font-size: x-small;">Receipt Date : '.@date("d F Y H:i:s",strtotime($Payment_Date_And_Time)).'</span></td>
							<td style="text-align: left;" width="33%"><span style="font-size: x-small;">Billing Type : '.$Billing_Type.'</span></td>
						</tr>
					</table>
					<table width="100%" border=1 style="border-collapse: collapse;">
						<thead><tr>
							<td width="5%"><span style="font-size: x-small;">SN</td>
							<td width="35%"><span style="font-size: x-small;">ITEM NAME</span></td>
							<td style="text-align: right;" width="15%"><span style="font-size: x-small;">PRICE</span></td>
							<td style="text-align: right;" width="15%"><span style="font-size: x-small;">DISCOUNT</span></td>
							<td style="text-align: right;" width="15%"><span style="font-size: x-small;">QUANTITY</span></td>
							<td style="text-align: right;" width="15%"><span style="font-size: x-small;">SUB TOTAL</span></td>
						</tr></thead>';

						$slct = mysqli_query($conn,"SELECT i.Product_Name, ppl.Price, ppl.Discount, ppl.Quantity from tbl_patient_payment_item_list ppl, tbl_items i where
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
											ppl.Item_ID = i.Item_ID") or die(mysqli_error($conn));
						$nmz = mysqli_num_rows($slct);
						if($nmz > 0){
							while ($dt = mysqli_fetch_array($slct)) {
								$htm .= '<tr>
											<td><span style="font-size: x-small;">'.(++$count).'</span></td>
											<td><span style="font-size: x-small;">'.$dt['Product_Name'].'</span></td>
											<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($dt['Price']).'</span></td>
											<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($dt['Discount']).'</span></td>
											<td style="text-align: right;"><span style="font-size: x-small;">'.$dt['Quantity'].'</span></td>
											<td style="text-align: right;"><span style="font-size: x-small;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</span></td>
										</tr>';
								$Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                 if($Billing_Type=="Outpatient Cash"){
                                                                    $Grand_Total_outpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }else{
                                                                    $Grand_Total_inpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }
								$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
						}
							$htm .= '<tr>
										<td colspan="5"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
										<td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total).'</span></td>
										</tr>';
						$htm .= '</table><br/>';
		}
	}else{
		$htm .= "<center><br/><br/><br/><br/><br/>NO TRANSACTIONS FOUND</center>";
	}
	$htm .= '<table width="100%"><tr>'
                . '<td style="text-align: right;"><b><span style="font-size: x-small;">OUTPATIENT TOTAL : '.number_format($Grand_Total_outpatient).'</span></td>'
                . '<td style="text-align: right;"><b><span style="font-size: x-small;">INPATIENT TOTAL : '.number_format($Grand_Total_inpatient).'</span></td>'
                . '<td style="text-align: right;"><b><span style="font-size: x-small;">GRAND TOTAL : '.number_format($Grand_Total).'</span></td>'
                . '</tr></table>';
	$htm .= "<br/><table width=100%>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:left;width:18%'><b><span style='font-size: small;'>Employee Sign : </span></b></td>";
    $htm .= "<td style='text-align:left;width:25%'>________________________________</td>";
    $htm .= "<td style='text-align:right;width:25%'><b><span style='font-size: small;'>Patient Sign : </span></b></td>";
    $htm .= "<td style='text-align:left;width:25%'>________________________________</td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:left;'></td>";
    $htm .= "<td style='text-align:left;'><b> <span style='font-size: small;'>{$Emp_Name} </span></b></td>";
    $htm .= "<td style='text-align:left;'></td>";
    $htm .= "<td style='text-align:left;'><b> <span style='font-size: small;'>{$Patient_Name} </span></b></td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>