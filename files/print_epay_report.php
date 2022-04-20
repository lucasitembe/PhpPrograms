<?php
session_start();
include("./includes/connection.php");
$temp = 0;
$temp2 = 0;
$Grand_Total = 0;
$Date_From = '';
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
}

$Date_To = '';
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
}

$Report_Type = '';
if (isset($_GET['Report_Type'])) {
    $Report_Type = $_GET['Report_Type'];
}

$Payment_Mode = '';
if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = $_GET['Payment_Mode'];
}

/*
* transacion mode (normal=online,offline, both)
*/
$trans_mode = '';
if (isset($_GET['trans_mode'])) {
    $trans_mode = $_GET['trans_mode'];
}

$src = '';
if (isset($_GET['src'])) {
    $src = $_GET['src'];
}

$limit = "LIMIT 500";
if (isset($_GET['number_recordes'])) {
    $number_recordes = $_GET['number_recordes'];
}

if ($number_recordes == '500_Rec')
    $limit = "LIMIT 500";
else if ($number_recordes == '300_Rec')
    $limit = "LIMIT 300";
else if ($number_recordes == '100_Rec')
    $limit = "LIMIT 100";
else if ($number_recordes == '50_Rec')
    $limit = "LIMIT 50";
else if ($number_recordes == 'All')
    $limit = "";

 $cashier_id = '';
 if(isset($_GET['cashier_id'])){
    $cashier_id = $_GET['cashier_id'];
 }

if ($src == 'complete') {
    $Terminal_ID = '';
    if (isset($_GET['Terminal_ID'])) {
        $Terminal_ID = $_GET['Terminal_ID'];
    }

    $filterterminal = '';
    if (!empty($Terminal_ID) && $Terminal_ID != 'all') {
        $filterterminal = " AND ba.Terminal_ID='$Terminal_ID'";
    }

    if(!empty($trans_mode) && $trans_mode !='All'){
        $trans_mode = strtolower($trans_mode);
        if($trans_mode=='normal'){
            $filterterminal .= " AND (lower(ba.trans_type)='$trans_mode' OR ba.trans_type='')";
        } else {
            $filterterminal .= " AND lower(ba.trans_type)='$trans_mode'";
        } 
    }
    if (!empty($cashier_id) && $cashier_id != 'All') {
        $filterterminal .= " AND emp.Employee_ID='$cashier_id'";
    }
}//end of checking transaction status

//$myTransIDs = array();
$htm = "<img src='./branchBanner/branchBanner.png'>
          <table width ='100%' class='nobordertable'>
		    <tr>
               <td style='text-align: center;'>
			       <span><b>Epayment " . ucfirst(strtolower($src)) . " Transaction Report</b></span>
				</td>
			</tr>
            <tr>
              <td  style='text-align: center;'>
			     <b>From</b> ".$Date_From."&nbsp;&nbsp;<b>To</b> ".$Date_To."
		      </td>
            </tr>
			<tr>
			  <td  style='text-align: center;'>
					 ".(!empty($Terminal_ID)?"<b>Terminal</b>&nbsp;&nbsp;".ucfirst($Terminal_ID):"")."
		      </td>
            </tr>
            <tr>
              <td  style='text-align: center;'>
                     ".(!empty($trans_mode)?"<b>Transaction Mode</b>&nbsp;&nbsp;".ucfirst($trans_mode):"")."
              </td>
            </tr>
		  </table>	
		    ";


if ($src == 'pending') {
    $select = mysqli_query($conn,"select tc.Source, tc.Amount_Required, pr.Patient_Name, tc.Transaction_ID, tc.Payment_Code, sp.Guarantor_Name, emp.Employee_Name, 					tc.Transaction_Date_Time, pr.Registration_ID,Transaction_Status,Payment_Code
							from tbl_bank_transaction_cache tc, tbl_patient_registration pr, 
							tbl_sponsor sp, tbl_employee emp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID = tc.Employee_ID and
							tc.Registration_ID = pr.Registration_ID and
							tc.Transaction_Date_Time between '$Date_From' and '$Date_To' and
                            (tc.Transaction_Status = 'pending' or tc.Transaction_Status = 'uploaded')
							order by tc.Transaction_ID desc $limit") or die(mysqli_error($conn));

    $htm .= '<table width = "100%">
        <tr>
           <td width="5%"><b>SN</b></td>
            <td><b>PAT NAME</b></td>
            <td ><b>PAT. #</b></td>
            <td ><b>SPONSOR</b></td>
            <td><b>TRANS MODE</b></td>
            <td  style="text-align: right;"><b>PREP DATE</b></td>
            <td  style="text-align: right;"><b>EMP PREPARED</b></td>
            <td ><b>BILL #</b></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td  style="text-align: right;" colspan="6"><b>AMOUNT REQUIRED</b></td>
            
        </tr>';
} else if ($src == 'complete') {

    $htm .= '<table width = 100%>
        
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PAT NAME</b></td>
            <td width="10%"><b>PAT. #</b></td>
            <td width="12%"><b>SPONSOR</b></td>
            <td><b>TRANS MODE</b></td>
            <td width="10%" style="text-align: right;"><b>PREP DATE</b></td>
            <td width="10%" style="text-align: right;"><b>EMP PREPARED</b></td>
            <td width="10%">&nbsp;&nbsp;&nbsp;<b>BILL #</b></td>
            <td width="10%"><b>TRANS REF</b></td>
            <td width="10%"><b>TRANS DATE</b></td>
            <td width="10%"><b>TERM ID</b></td>
            <td width="10%"><b>MERCH ID</b></td>
            <td width="10%"><b>BATCH #</b></td>
            <td width="10%"><b>AUTH #</b></td>
            <td width="10%" style="text-align: right;"><b>AMOUNT</b></td>
        </tr>';

    $select = mysqli_query($conn,"select DISTINCT(Auth_No), tc.Source,trans_type, tc.Amount_Required,ba.Amount_Paid, pr.Patient_Name, tc.Transaction_ID, 
                            tc.Payment_Code, sp.Guarantor_Name, emp.Employee_Name, tc.Transaction_Date_Time, pr.Registration_ID,
                            ba.Transaction_Ref, ba.Transaction_Date,Terminal_ID,Merchant_ID,Batch_No,ba.P_ID
                            from tbl_bank_transaction_cache tc
                            JOIN tbl_patient_registration pr ON tc.Registration_ID = pr.Registration_ID 
                            JOIN tbl_sponsor sp ON pr.Sponsor_ID = sp.Sponsor_ID
                            JOIN tbl_employee emp ON emp.Employee_ID = tc.Employee_ID
                            JOIN tbl_bank_api_payments_details ba ON ba.Payment_Code=tc.Payment_Code
                            WHERE
                            ba.Transaction_Date between '$Date_From' and '$Date_To' AND
                            tc.Transaction_Status = 'Completed'
                            $filterterminal
                            order by ba.Transaction_Date desc $limit") or die(mysqli_error($conn));
}

$num = mysqli_num_rows($select);
if ($num > 0) {
    $transaction_mode = '';
    while ($data = mysqli_fetch_array($select)) {


        if($data['trans_type']!='' && strtolower($data['trans_type'])!='normal'){
            $transaction_mode =  strtoupper($data['trans_type']);
        } else {
            $transaction_mode = 'ONLINE';
        }

        $Grand_Total += $data['Amount_Required'];
        $Transaction_ID = $data['Transaction_ID'];
        $Payment_Code = $data['Payment_Code'];
        //$myTransIDs[] = $Transaction_ID;

//        $qr = mysqli_query($conn,"select Price,Discount,Quantity
//                                from tbl_bank_items_detail_cache cc, tbl_items i where
//                                i.Item_ID = cc.Item_ID and
//                                Transaction_ID = '$Transaction_ID' ") or die(mysqli_error($conn));
//        $suCatTotal = 0;
//        while ($data2 = mysqli_fetch_array($qr)) {
//            $suCatTotal += (($data2['Price'] - $data2['Discount']) * $data2['Quantity']);
//        }
        if ($src == 'complete') {
            //get Transaction_Ref & Transaction_Date

            $Transaction_Ref = $data['Transaction_Ref'];
            $Transaction_Date = $data['Transaction_Date'];
            $Terminal_ID = $data['Terminal_ID'];
            $Merchant_ID = $data['Merchant_ID'];
            $Batch_No = $data['Batch_No'];
            $Auth_No = $data['Auth_No'];
        }

        $htm .= '<tr>';
        $htm .= '<td>' . ++$temp . '</td>';
        $htm .= '<td>' . ucwords(strtolower($data['Patient_Name'])) . '</td>';
        $htm .= '<td>' . $data['Registration_ID'] . '</td>';
        $htm .= '<td>' . $data['Guarantor_Name'] . '</td>';
       

        $htm .= '<td>' . $transaction_mode . '</td>';

        $htm .= '<td>' . $data['Transaction_Date_Time'] . '</td>';
        $htm .= '<td>' . $data['Employee_Name'] . '</td>';
        $htm .= '<td>' . $data['Payment_Code'] . '</td>';

        if ($src == 'complete') {
            $htm .= '<td>' . $Transaction_Ref . '</td>';
            $htm .= '<td>' . $Transaction_Date . '</td>';
            $htm .= '<td>' . $Terminal_ID . '</td>';
            $htm .= '<td>' . $Merchant_ID . '</td>';
            $htm .= '<td>' . $Batch_No . '</td>';
            $htm .= '<td>' . $Auth_No . '</td>';
        }

        $htm .= '<td style="text-align: right;" colspan="7">' . number_format($data['Amount_Required'], 2) . '</td>';
        $htm .= '</tr>';
    }

    //$htm .= '<tr><td colspan="17"><hr></td></tr>';
    $htm .= '<tr><td colspan="14" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b>' . number_format($Grand_Total, 2) . '</b></td></tr>';
  //  $htm .= '<tr><td colspan="17"><hr></td></tr></table></center>';
        $htm .='</table></center>';
}

//$htm .= '<br/><br/><br/><table width="100%" border="0">';
//$htm .= '<tr><td width="5%"><b>SN</b></td>';
//$htm .= '<td width="55%"><b>SUB CATEGORY NAME</b></td>';
//if ($src == 'complete') {
//    // $htm .= '<td>&nbsp;</td>';
//    // $htm .= '<td>&nbsp;</td>';
//    // $htm .= '<td>&nbsp;</td>';
//    // $htm .= '<td>&nbsp;</td>';
//    // $htm .= '<td>&nbsp;</td>';
//    // $htm .= '<td>&nbsp;</td>';
//}
//$htm .= '<td width="15%" style="text-align: right;" colspan="7"><b>AMOUNT</b></td></tr>';
//$getSubCategories = "SELECT Item_Subcategory_ID, Item_Subcategory_Name FROM tbl_item_subcategory";
//$getSub = mysqli_query($conn,$getSubCategories) or die(mysqli_error($conn));
//
//$grandTotoalSub = 0;
//while ($rowSub = mysqli_fetch_array($getSub)) {
//    $Item_Subcategory_ID = $rowSub['Item_Subcategory_ID'];
//    $Item_Subcategory_Name = $rowSub['Item_Subcategory_Name'];
//
//    $suCatTotal = 0;
//
//    foreach ($myTransIDs as $key => $TransactionID) {
////                $query = "
////                   SELECT SUM((Price-Discount)*Quantity) AS AMOUNT FROM tbl_patient_payment_item_list pl 
////                   JOIN tbl_patient_payments pp ON pl.Patient_Payment_ID=pp.Patient_Payment_ID
////                   JOIN  tbl_items i ON pl.Item_ID=i.Item_ID
////                   WHERE pp.Payment_Code='$Payment_Code' AND i.Item_Subcategory_ID='$Item_Subcategory_ID'
////                  ";
//
//        $qr = mysqli_query($conn,"select Price,Discount,Quantity
//                                from tbl_bank_items_detail_cache cc, tbl_items i where
//                                i.Item_ID = cc.Item_ID and
//                                Transaction_ID = '$TransactionID'  AND i.Item_Subcategory_ID='$Item_Subcategory_ID'") or die(mysqli_error($conn));
//
//        while ($data = mysqli_fetch_array($qr)) {
//            $suCatTotal += (($data['Price'] - $data['Discount']) * $data['Quantity']);
//        }
//
//        //$qr = mysqli_query($conn,$query) or die(mysqli_error($conn));
////                $fetchAm = mysqli_fetch_assoc($qr);
////                $suCatTotal += $fetchAm['AMOUNT'];
//    }
//
//    $grandTotoalSub += $suCatTotal;
//
//    if ($suCatTotal > 0) {
//        $htm .= '<tr><td>' . ++$temp2 . '</td>';
//        $htm .= '<td>' . ucwords(strtolower($Item_Subcategory_Name)) . '</td>';
//        $htm .= '<td style="text-align: right;"  colspan="7">' . number_format($suCatTotal, 2) . '</td></tr>';
//    }
//}
//  $adjTotal=0;
//if ($src == 'complete') {
//	$qr2 = mysqli_query($conn,"select Amount_Required,tc.Transaction_ID
//                                from tbl_bank_transaction_cache tc
//								where tc.Transaction_ID IN  (".implode(',',$myTransIDs).") and
//								tc.Transaction_ID NOT IN  (select distinct(Transaction_ID) from tbl_bank_items_detail_cache)"
//								) or die(mysqli_error($conn));
//		
//            while ($data3 = mysqli_fetch_assoc($qr2)) {
//                $adjTotal += $data3['Amount_Required'];
//                $Transaction_ID = $data3['Transaction_ID'];
//				
//	      /*   $htm .= '<tr><td>' . ++$temp2 . '</td>';
//            $htm .= '<td>' . ucwords(strtolower($Transaction_ID)) . '</td>';
//            $htm .= '<td style="text-align: right;"  colspan="2">' . number_format($data3['Amount_Required'],2) . '</td></tr>'; */
//			}
//            $htm .= '<tr><td>' . ++$temp2 . '</td>';
//            $htm .= '<td>' . ucwords(strtolower('Adjustiment')) . '</td>';
//            $htm .= '<td style="text-align: right;"  colspan="7">' . number_format($adjTotal,2) . '</td></tr>';
//}
//
////$htm .= '<tr><td colspan="11"><hr></td></tr>';
//$htm .= '<tr><td colspan="8" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b>' . number_format($grandTotoalSub+$adjTotal, 2) . '<b/></td></tr>';
////$htm .= '<tr><td colspan="11"><hr></td></tr>';
//$htm .= '</table>';
//
////echo $htm;
//$htm .= "</table>";

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();
?>

</html>