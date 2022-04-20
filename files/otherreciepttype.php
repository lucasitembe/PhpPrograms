<?php

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Emp_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Emp_Name = '';
}
$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
$Supervisor_Name = $_SESSION['supervisor']['Employee_Name'];

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}


$isReceipt = false;
                $q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($q);
                $exist = mysqli_num_rows($q);
                if ($exist > 0) {
                    $Paper_Type = $row['Paper_Type'];
                    $Include_Sponsor_Name_On_Printed_Receipts = $row['Include_Sponsor_Name_On_Printed_Receipts'];
                    if ($Paper_Type == 'Receipt') {
                        $isReceipt = true;
                        echo "<script>".$Include_Sponsor_Name_On_Printed_Receipts."</script>";
                    }
                }else{
                    $Include_Sponsor_Name_On_Printed_Receipts = 'no';
                }

?>

<input type="hidden" name="sponsor" id="sponsor" value="<?=$Include_Sponsor_Name_On_Printed_Receipts?>">

<script>
var doc = document.getElementById("sponsor").value;
alert(" Tgis is exactly page: "doc)
</script>
<?php 
$select_Patient = mysqli_query($conn,
        "SELECT pp.Employee_ID,pp.Billing_Type,pp.Payment_Date_And_Time,preg.Patient_Name,preg.Registration_ID,preg.Old_Registration_Number,Guarantor_Name,preg.Gender,preg.Member_Number,Date_Of_Birth,pp.Transaction_status from  tbl_patient_payments pp 
                JOIN tbl_patient_registration preg ON preg.Registration_ID = pp.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pp.Sponsor_ID
                where
		    pp.Patient_Payment_ID = '$Patient_Payment_ID' limit 1") or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Patient);

if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Patient)) {
        $Old_Registration_Number = $row['Old_Registration_Number'];
        $Title = $row['Title'];
        $Patient_Name = ucwords(strtolower($row['Patient_Name']));
        $Sponsor_ID = $row['Sponsor_ID'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Region = $row['Region'];
        $District = $row['District'];
        $Ward = $row['Ward'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Member_Number = $row['Member_Number'];
        $Member_Card_Expire_Date = $row['Member_Card_Expire_Date']; //
        $Phone_Number = $row['Phone_Number'];
        $Registration_ID = $row['Registration_ID'];
        $Transaction_status = $row['Transaction_status'];
    }
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
}


//get consu
$slct = mysqli_query($conn,"SELECT Employee_Name from tbl_employee emp, tbl_patient_payment_item_list pl where
                            emp.Employee_ID = pl.Consultant_ID and
                            pl.Patient_Payment_ID = '$Patient_Payment_ID' AND pl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station') ") or die(mysqli_error($conn));
$slct_no = mysqli_num_rows($slct);
if ($slct > 0) {
    while ($dt = mysqli_fetch_array($slct)) {
        $Consulting_Doctor = $dt['Employee_Name'];
    }
} else {
    $Consulting_Doctor = '';
}

//get invoice details
$invoice_details = mysqli_query($conn,"SELECT pp.manual_offline,pp.terminal_id,pp.auth_code,Payment_Code,Payment_Date_And_Time, Folio_Number, Receipt_Date, Claim_Form_Number, Employee_Name,Billing_Type,pp.payment_type from tbl_patient_payments pp, tbl_employee emp where 
    								pp.Patient_Payment_ID = '$Patient_Payment_ID' and
    								pp.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
$num_invouce = mysqli_num_rows($invoice_details);

//Get sub _departments
$getDepartment = mysqli_query($conn,"SELECT tsb.Sub_Department_Name FROM tbl_item_list_cache ilc,tbl_sub_department tsb WHERE ilc.Patient_Payment_ID='$Patient_Payment_ID' and tsb.Sub_Department_ID=ilc.Sub_Department_ID");
$department = mysqli_fetch_assoc($getDepartment);
$Sub_Department = $department['Sub_Department_Name'];

if ($Sub_Department != '') {
    $Sub_Department_Name = $department['Sub_Department_Name'];
} else {
    $Sub_Department_Name = 'Unknown';
}


$offline_terminal_id="empty";
if ($num_invouce > 0) {
    while ($data = mysqli_fetch_array($invoice_details)) {
        $offline_terminal_id= $data['terminal_id'];
        $Sangira_Code=$data['auth_code'];
        $manual_offline=$data['manual_offline'];
        $Folio_Number = $data['Folio_Number'];
        $Receipt_Date = $data['Receipt_Date'];
        $Claim_Form_Number = $data['Claim_Form_Number'];
        $Prepared_By = $data['Employee_Name'];
        $Billing_Type = $data['Billing_Type'];
        $payment_type = $data['payment_type'];
        $Payment_Date_And_Time = $data['Payment_Date_And_Time'];
        $Payment_Code=$data['Payment_Code'];
    }
} else {
    $Folio_Number = '';
    $Receipt_Date = '0000-00-00';
    $Claim_Form_Number = '';
    $Prepared_By = '';
    $Payment_Date_And_Time = '0000/00/00 00:00:00';
    $Payment_Code='';
}

// $recieptType = "RECEIPT"; //Out-Patient Credit Bill 
$recieptTypeNum = "";
$Date_Title = "";

//echo "SELECT Auth_No FROM tbl_bank_api_payments_details WHERE Payment_Code = '" .$Payment_Code. "' ORDER BY Payment_ID DESC LIMIT 1";
$gt = mysqli_query($conn,"SELECT Auth_No,trans_type,Terminal_ID FROM tbl_bank_api_payments_details WHERE Payment_Code = '" .$Payment_Code. "' ORDER BY Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
$rs2 = mysqli_fetch_array($gt);
if (empty($Sangira_Code)) {
	$auth_no = $rs2['Auth_No'];
}else{
//die("SELECT receiptNumber FROM tbl_mobile_payemts WHERE Payment_Number='$offline_auth_code'");
$getPayment_Numbert = mysqli_query($conn,"SELECT Payment_Number FROM tbl_mobile_payemts WHERE receiptNumber='$Sangira_Code'");
    $Payment_Number = mysqli_fetch_assoc($getPayment_Numbert)['Payment_Number'];
	$auth_no = $Payment_Number;
}
if($rs2['Terminal_ID']!=null||$rs2['Terminal_ID']!=""){
  $offline_terminal_id = $rs2['Terminal_ID']; 
}
//$offline_terminal_id = "...$offline_terminal_id..."; 
if($Sangira_Code!=0){
 $auth_no=$Sangira_Code;
}
$transaction_mode = '';
if($rs2['trans_type']=='' || strtolower($rs2['trans_type'])=='normal'){
        
	$transaction_mode = $manual_offline;
        if($manual_offline==""){
            $transaction_mode = "O n l i n e";
        }
} else {
	$transaction_mode = $rs2['trans_type'];
	
}

$getPayment_Numbert = mysqli_query($conn,"SELECT receiptNumber FROM tbl_mobile_payemts WHERE  Payment_Number='$Sangira_Code'");
    $Payment_Number = mysqli_fetch_assoc($getPayment_Numbert)['receiptNumber'];
	// $auth_no = $Payment_Number;

if ((($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') && $Sangira_Code != '') || ($Billing_Type == 'Inpatient cash' && $Sangira_Code !='')) {
    if ($Billing_Type == 'Outpatient Cash') {
        $recieptType = "OUT-PATIENT CASH RECEIPT";
    } else if ($Billing_Type == 'Inpatient Cash' || $Billing_Type == 'Inpatient cash') {
        $recieptType = "IN-PATIENT CASH RECEIPT";
    } else {
        $recieptType = "CASH RECEIPT";
    }
    $recieptTypeNum = "Receipt  Number";
    $Date_Title = "Receipt Date";
} elseif ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit' || (($Billing_Type == 'Inpatient Cash' ||  $Billing_Type == 'Outpatient Cash') && $payment_type == 'post')) {

    if ($Billing_Type == 'Outpatient Credit') {
        $recieptType = "OUT-PATIENT CREDIT RECEIPT";
    } else if ($Billing_Type == 'Inpatient Credit') {
        $recieptType = "IN-PATIENT CREDIT RECEIPT";
    } else if ($Billing_Type == 'Inpatient Cash' && $payment_type == 'post') {
        $recieptType = "IN-PATIENT INVOICE/FOMU YA MADAI";
    } else if ($Billing_Type == 'Outpatient Cash' && $payment_type == 'post'){
        $recieptType = "OUT-PATIENT INVOICE/FOMU YA MADAI";
    }else {
        $recieptType = "CREDIT RECEIPT";
    }

    $recieptTypeNum = "Invoice  Number";
    $Date_Title = "Invoice Date";
}

$htm = "<table width ='100%' height = '30px' border='0'>
			    <tr><td>
				<img src='./branchBanner/branchBanner.png' width='100%'>
			    </td></tr>
			    <tr><td><hr></td></tr>
			    <tr><td style='text-align: center;'><span style='font-size: small;'><b>" . $recieptType . "</b></span></td></tr>
			</table>";
if (strtolower($Transaction_status) == 'cancelled') {
    $htm .= "<center><h3><b>CANCELLED TRANSACTION</b></h3></center>";
}
$htm .= '<table width="100%">
				<tr>
					<td width="15%"><span style="font-size: small;"><span style="font-size: small;">' . $recieptTypeNum . '</span></td>
					<td width="1%">:</td><td width="30%"><span style="font-size: small;">' . $Patient_Payment_ID . '</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Location</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">Revenue Center</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">' . $Date_Title . '</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . @date("d F Y H:i:s", strtotime($Payment_Date_And_Time)) . '</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Patient Age</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $age . '</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Claim Form Number</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . $Claim_Form_Number . '</span></td>
                    ';
                    if ($Include_Sponsor_Name_On_Printed_Receipts == 'no') {

                $htm .= '<td width="15%" style="text-align: right;"><span style="font-size: small;">&nbsp</span></td>
                    <td width="1%">:</td><td width="10%"><span style="font-size: small;"></span></td>
                ';

                    }else{
                        $htm .= '<td width="15%" style="text-align: right;"><span style="font-size: small;">Sponsor Name </span></td>
                    <td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Guarantor_Name . '</span></td>
                ';
                    }
				
            
               $htm .= '</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Membership  No</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . $Member_Number . '</span></td>
					<td width="20%" style="text-align: right;"><span style="font-size: small;">Session Supervisor</span></td>
					<td width="1%">:</td><td width="20%"><span style="font-size: small;">' . strtoupper($Supervisor_Name) . '</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Patient Name</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . strtoupper($Patient_Name) . '</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Gender</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Gender . '</span></td>
				</tr>
				<tr>
					<td width="15%"><span style="font-size: small;">Consulting Doctor</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . strtoupper($Consulting_Doctor) . '</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Folio Number</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Folio_Number . '</span></td>
				</tr>
                                
                <tr>
					<td width="15%"><span style="font-size: small;">Patient Number</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . $Registration_ID . '</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Sub Department</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Sub_Department_Name . '</span></td>
				</tr>
                <tr>
                                    <td colspan="4">
                                       Transaction Mode:  ' . $transaction_mode . ' 
                                    </td>
                                  </tr>
                                <tr>
                                    <td colspan="4">
                                       Authorization #:' . $auth_no . ' 
                                    </td>
                                    
                                  </tr>
                                  <tr>
                                  <td colspan="4">
                                      Bank Auth. #: '.$Payment_Number.'
                                  </td>
                               </tr>
                                  <tr>
                                    <td colspan="4">
                                        Terminal Id: '.$offline_terminal_id.'
                                    </td>
                                 </tr>
                                
                               
			</table><br/>';

//get transactions
$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
											tbl_item_category ic, tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_items i where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											ppl.Item_ID = i.Item_ID and
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
$num = mysqli_num_rows($get_categories);
if ($num > 0) {
    $Grand_Total = 0;
    while ($row = mysqli_fetch_array($get_categories)) {
        $Item_Category_ID = $row['Item_Category_ID'];
        $htm .= '<table width="100%" border=1 style="border-collapse: collapse;">';
        $htm .= '<tr><td colspan="4"><b><span style="font-size: small;">' . strtoupper($row['Item_Category_Name']) . '</span></b></td></tr>';
        //get transactions based on Item_Category_ID
        $get_details = mysqli_query($conn,"select Product_Name, Price, Quantity, Discount from
												tbl_item_subcategory isc, tbl_patient_payment_item_list ppl, tbl_items i where
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												ppl.Item_ID = i.Item_ID and
                                                isc.Item_Category_ID = '$Item_Category_ID' and
												ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
        $num_get_details = mysqli_num_rows($get_details);
        if ($num > 0) {
            $temp = 0;
            $Sub_Total = 0;
            $htm .= '<tr>
									<td width="4%"><b><span style="font-size: small;">No</span></b></td>
									<td><b><span style="font-size: small;">Particular</span></b></td>
									<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Quantity</span></b></td>
									<td width="14%" style="text-align: right;"><b><span style="font-size: small;">Amount</span></b></td>
								</tr>';
            while ($dtz = mysqli_fetch_array($get_details)) {
                $htm .= '<tr>
										<td><span style="font-size: small;">' . ++$temp . '</span></td>
										<td><span style="font-size: small;">' . $dtz['Product_Name'] . '</span></td>
										<td style="text-align: right;"><span style="font-size: small;">' . $dtz['Quantity'] . '</span></td>
										<td style="text-align: right;"><span style="font-size: small;">' . number_format(($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']) . '</span></td>
									</tr>';
                $Sub_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
                $Grand_Total += (($dtz['Price'] - $dtz['Discount']) * $dtz['Quantity']);
            }
        }
        $htm .= '<tr>
									<td colspan="3"><b><span style="font-size: small;">Sub Total</span></b></td>
									<td style="text-align: right;"><b><span style="font-size: small;">' . number_format($Sub_Total) . '</span></b></td>
								</tr>';
        $htm .= '</table><br/>';
    }
}

$htm .= '<br/><table width="100%">
							<tr>
							<td><b>GRAND TOTAL : ' . number_format($Grand_Total) . '</b></td>
							</tr>
						</table>';

$htm .= '<br/><table width="100%" border="0"> <tr class="enddesc">'
        . '<td colspan="" style="text-align:left;">'
        . '<b>Employee Signature </span></b>________________'
        . '<br/><b><span style="font-size: small;">Prepared By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . strtoupper($Prepared_By) . '</span>	</b>'
        . '<td  style="text-align:right"><b><span style=";">Patient Signature</span></b>________________</b></td>'
        . '</tr></table>';

if (strtolower($Transaction_status) == 'cancelled') {
    $htm .= "<br/><br/><center><h3><b>CANCELLED TRANSACTION</b></h3></center>";
}

$htm .= '<script>
  window.print(false);
  CheckWindowState();

   function PrintWindow() {
       window.print();
       CheckWindowState();
   }

   function CheckWindowState() {
       if (document.readyState == "complete") {
           window.close();
       } else {
           setTimeout("CheckWindowState()", 2000);
       }
   }
</script>';

//$htm .= '<br/><b><span style="font-size: small;">Employee Signature </span></b>______________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//				<b><span style="font-size: small;">Patient Signature</span></b>______________________________<br/>';
//$htm .= '<b><span style="font-size: small;">Prepared By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . strtoupper($Prepared_By) . '</span>	</b>';

echo $htm;

// include("./MPDF/mpdf.php");
// $mpdf = new mPDF('', '', 0, '', 15, 15, 20, 40, 15, 35, 'P');
// $mpdf->SetFooter('Printed By ' . strtoupper($Emp_Name) . '|{PAGENO}/{nb}|{DATE d-m-Y}');
// $mpdf->WriteHTML($htm);
// $mpdf->Output();
