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
$payment_details=mysqli_query($conn,"SELECT Customer_ID,Supervisor_ID,Employee_ID,Payment_Date_And_Time,branch_id,customer_type FROM tbl_other_sources_payments WHERE Payment_ID=$Payment_ID");
$customer_type=mysqli_fetch_assoc($payment_details)['customer_type'];
if($customer_type=='CUSTOMER'){
	$select_customer = mysqli_query($conn,"SELECT osp.Employee_ID,osp.Payment_Date_And_Time,osp.Billing_Type,pr.Patient_Name as customer_name FROM tbl_patient_registration pr,tbl_other_sources_payments osp WHERE pr.Registration_ID=osp.Customer_ID AND osp.Payment_ID=$Payment_ID");
	$customer_label="Customer Name";
	$receipt_type="CUSTOMER RECEIPT";
	$customer_id_label="Customer ID";
}
if($customer_type=='SPONSOR'){
	$select_customer = mysqli_query($conn,"SELECT osp.Employee_ID,osp.Payment_Date_And_Time,osp.Billing_Type,sp.Guarantor_Name as customer_name FROM tbl_sponsor sp,tbl_other_sources_payments osp WHERE sp.Sponsor_ID=osp.Customer_ID AND osp.Payment_ID=$Payment_ID")or die(mysqli_error($conn));
	$customer_label="Sponsor Name";
	$receipt_type="SPONSOR RECEIPT";
	$customer_id_label="Sponsor ID";
}

$no = mysqli_num_rows($select_customer);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_customer)) {
        $customer_name = ucwords(strtolower($row['customer_name']));
    }
}
//get invoice details
$invoice_details = mysqli_query($conn,"select osp.cheque_number,osp.terminal_id,osp.narration,osp.auth_code,osp.Payment_Code,osp.Payment_Date_And_Time,osp.Receipt_Date, emp.Employee_Name,osp.Billing_Type,osp.payment_type from tbl_other_sources_payments osp, tbl_employee emp where osp.Payment_ID = '$Payment_ID' and osp.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
$num_invouce = mysqli_num_rows($invoice_details);

//Get sub _departments
$getDepartment = mysqli_query($conn,"SELECT tsb.Sub_Department_Name FROM tbl_item_list_cache ilc,tbl_sub_department tsb WHERE ilc.Payment_ID='$Payment_ID' and tsb.Sub_Department_ID=ilc.Sub_Department_ID");
$department = mysqli_fetch_assoc($getDepartment);
$Sub_Department = $department['Sub_Department_Name'];

if ($Sub_Department != '') {
    $Sub_Department_Name = $department['Sub_Department_Name'];
} else {
    $Sub_Department_Name = 'Unknown';
}


$offline_terminal_id="empty";
if ($num_invouce > 0){
    while ($data = mysqli_fetch_array($invoice_details)) {
        $offline_terminal_id= $data['terminal_id'];
        $offline_auth_code=$data['auth_code'];
        $manual_offline=$data['manual_offline'];
        $narration = $data['narration'];
        $Receipt_Date = $data['Receipt_Date'];
        $cheque_no = $data['cheque_number'];
        $Prepared_By = $data['Employee_Name'];
        $Billing_Type = $data['Billing_Type'];
        $payment_type = $data['payment_type'];
        $Payment_Date_And_Time = $data['Payment_Date_And_Time'];
        $Payment_Code=$data['Payment_Code'];
    }
} else {
    $Folio_Number = '';
    $Receipt_Date = '0000-00-00';
    $cheque_no ='unknown';
    $Prepared_By = '';
    $Payment_Date_And_Time = '0000/00/00 00:00:00';
    $Payment_Code='';
}

$recieptType = $receipt_type; //Out-Patient Credit Bill 
$recieptTypeNum = "";
$Date_Title = "";

//echo "SELECT Auth_No FROM tbl_bank_api_payments_details WHERE Payment_Code = '" .$Payment_Code. "' ORDER BY Payment_ID DESC LIMIT 1";
$gt = mysqli_query($conn,"SELECT Auth_No,trans_type,Terminal_ID FROM tbl_bank_api_payments_details WHERE Payment_Code = '" .$Payment_Code. "' ORDER BY Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));

$rs2 = mysqli_fetch_array($gt);
$auth_no = $rs2['Auth_No'];
if($rs2['Terminal_ID']!=null||$rs2['Terminal_ID']!=""){
  $offline_terminal_id = $rs2['Terminal_ID']; 
}
//$offline_terminal_id = "...$offline_terminal_id..."; 
if($offline_auth_code!=0){
 $auth_no=$offline_auth_code;
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


if ($Billing_Type == 'Outpatient Cash' || ($Billing_Type == 'Inpatient Cash' && $payment_type == 'pre')) {
    if ($Billing_Type == 'Outpatient Cash') {
        $recieptType = "OUT-PATIENT CASH RECEIPT";
    } else if ($Billing_Type == 'Inpatient Cash') {
        $recieptType = "IN-PATIENT CASH RECEIPT";
    } else {
        $recieptType = "CASH RECEIPT";
    }
    $recieptTypeNum = "Receipt  Number";
    $Date_Title = "Receipt Date";
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
					<td width="15%"><span style="font-size: small;"><span style="font-size: small;">Cheque No</span></td>
					<td width="1%">:</td><td width="30%"><span style="font-size: small;">' . $cheque_no . '</span></td>
					
					
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Receipt Number</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Payment_ID . '</span></td>
				</tr>
				
				<tr>
                    ';
                  
				
            
               $htm .= '</tr>
				
				<tr>
					<td width="15%"><span style="font-size: small;">'.$customer_label.'</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . strtoupper($customer_name) . '</span></td>
					</td>
					<td width="20%" style="text-align: right;"><span style="font-size: small;">Receipt Date</span></td>
					<td width="1%">:</td><td width="20%"><span style="font-size: small;">' . @date("d F Y H:i:s", strtotime($Payment_Date_And_Time)) . '</span></td>
				</tr>           
                <tr>
					<td width="15%"><span style="font-size: small;">'.$customer_id_label.'</span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">' . $Registration_ID . '</span></td>
					
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Location</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">Revenue Center</span></td>
					
				</tr>
                            <tr>
					<td width="15%"><span style="font-size: small;">Transaction Mode </span></td><td width="1%">:</td>
					<td width="30%"><span style="font-size: small;">Off-line</span></td>
					<td width="15%" style="text-align: right;"><span style="font-size: small;">Sub Department</span></td>
					<td width="1%">:</td><td width="10%"><span style="font-size: small;">' . $Sub_Department_Name . '</span></td>
				</tr>
                                <tr>
                                    <td colspan="1">
                                       <!--Authorization#' . $auth_no . ' -->
                                    </td>
									<td><!--:--></td>
                                    <td colspan="2">
										<!--' . $auth_no . ' -->
                                    </td>
                                  </tr>
                                  <tr>
					<td width="15%"><span style="font-size: small;"><!--Terminal Id--></span></td><td width="1%"><!--:--></td>
					<td width="30%"><span style="font-size: small;"><!--'.$offline_terminal_id.'--></span></td>
					</td>
					<td width="20%" style="text-align: right;"><span style="font-size: small;">Session Supervisor</span></td>
					<td width="1%">:</td><td width="20%"><span style="font-size: small;">' . strtoupper($Supervisor_Name) . '</span></td>
				</tr>
                                
                               
			</table><br/>';

//get transactions
$get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from tbl_item_category ic, tbl_item_subcategory isc, tbl_other_sources_payment_item_list ppl, tbl_items i where ic.Item_Category_ID = isc.Item_Category_ID and isc.Item_Subcategory_ID = i.Item_Subcategory_ID and ppl.Item_ID = i.Item_ID and ppl.Payment_ID = $Payment_ID group by ic.Item_Category_ID ") or die(mysqli_error($conn));
$num = mysqli_num_rows($get_categories);
if ($num > 0) {
    $Grand_Total = 0;
    while ($row = mysqli_fetch_array($get_categories)) {
        $Item_Category_ID = $row['Item_Category_ID'];
        $htm .= '<table width="100%" border=1 style="border-collapse: collapse;">';
        $htm .= '<tr><td colspan="4"><b><span style="font-size: small;">' . strtoupper($row['Item_Category_Name']) . '</span></b></td></tr>';
        //get transactions based on Item_Category_ID
        $get_details = mysqli_query($conn,"select Product_Name, Price, Quantity, Discount,ppl.Item_Description from tbl_item_subcategory isc, tbl_other_sources_payment_item_list ppl, tbl_items i where isc.Item_Subcategory_ID = i.Item_Subcategory_ID and ppl.Item_ID = i.Item_ID and isc.Item_Category_ID = $Item_Category_ID and ppl.Payment_ID = $Payment_ID ") or die(mysqli_error($conn));
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
										<td><span style="font-size: small;">' . $dtz['Product_Name'] . ', '.$dtz['Item_Description'].'</span></td>
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
							<tr><td>Narration: '.$narration.'</td></tr>
							<tr>
							<td><b>GRAND TOTAL : ' . number_format($Grand_Total) . '</b></td>
							</tr>
						</table>';

$htm .= '<br/><table width="100%" border="0"> <tr class="enddesc">'
        . '<td colspan="" style="text-align:left;">'
        . '<b>Employee Signature </span></b>________________'
        . '<br/><b><span style="font-size: small;">Prepared By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . strtoupper($Prepared_By) . '</span>	</b>'
        . '<td  style="text-align:right"><b><span style=";">'.ucfirst(strtolower($customer_type)).' Signature</span></b>________________</b></td>'
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
