<?php
include("./includes/connection.php");
$temp = 1;
$total = 0;
$Title = '';

if (isset($_GET['Date_From'])) {
    $Start_Date = $_GET['Date_From'];
} else {
    $Start_Date = '';
}

if (isset($_GET['Date_To'])) {
    $End_Date = $_GET['Date_To'];
} else {
    $End_Date = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

if (isset($_GET['Patient_Name'])) {
    $Patient_Name = $_GET['Patient_Name'];
} else {
    $Patient_Name = '';
}

$invoice_id = '';
$invoice_status = '';
$Invoice_Number = '';
$invoice_date ='';
$amount='';
$invoice_month = '';
$invoice_year = '';
if (isset($_GET['ref'])) {
    $invoice_id = trim($_GET['ref']);
    $invoice_status = '1';
    $query = mysqli_query($conn,"SELECT Invoice_Number,invoice_date,invoice_month,invoice_year,sponsor_id,amount FROM tbl_invoice WHERE Invoice_ID='$invoice_id'") or die(mysqli_error($conn));

    $res = mysqli_fetch_assoc($query);
    $Sponsor_ID = $res['sponsor_id'];
    $Invoice_Number = $res['Invoice_Number'];
    $invoice_date = $res['invoice_date'];
    $amount = $res['amount'];
    $invoice_month = $res['invoice_month'];
    $invoice_year = $res['invoice_year'];

    $timestamp = strtotime($res['invoice_month'] . " " . $res['invoice_year']);
    $Start_Date = date('Y-m-01', $timestamp);
    $End_Date = date('Y-m-t', $timestamp);
}

;

$sponsor_query = mysqli_query($conn,"SELECT Guarantor_Name FROM `tbl_sponsor` WHERE Sponsor_ID=$Sponsor_ID");
$sponsor_result = mysqli_fetch_array($sponsor_query);
$sponsor = $sponsor_result['Guarantor_Name'];

$htm = "<table style='font-size:12px' width ='100%' height = '20px'>
                <tr><td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>
                <tr><td style='text-align: center;'><h2>CUSTOMER INVOICE</h2></td></tr>
            </table>";
$htm .= "<br/>";

$htm .= "<table width ='100%' border=0 style='width:100%;'>
             <tr>
               <td width='15%'><b>Invoice No</b></td>
               <td >$Invoice_Number</td>
               <td  style='text-align: right;'><b>Invoice Date</b></td>
               <td width='15%' style='text-align: right;'>$invoice_date</td>    
            </tr>
             <tr>
               <td width='15%'><b>Customer</b></td>
               <td >$sponsor</td>
               <td  style='text-align: right;'><b>Amount</b></td>
               <td width='15%' style='text-align: right;'>". number_format($amount,2)."</td>    
            </tr>
            <tr>
               <td width='15%'><b>Invoice Month</b></td>
               <td >$invoice_month</td>
               <td  style='text-align: right;'><b>Invoice Year</b></td>
               <td width='15%' style='text-align: right;'>$invoice_year</td>    
            </tr>
             
        </table>";

$htm .= '<center><table border="0" style="width:100%">';

$htm .= '<tr><td colspan="8"><hr></td></tr>
    			<tr>
                            <td width=5%><b>SN</b></td>
                            <td ><b>Item Category</b></td>
	            	    <td width="70%"  style="text-align: right;"><b>AMOUNT</b></td>
		        </tr>
			<tr><td colspan="8"><hr></td></tr>';


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}



$getCategory = mysqli_query($conn,"SELECT Item_Category_Name,item_category_id FROM tbl_item_category") or die(mysqli_error($conn));
//$rowCat = mysqli_fetch_array($getCategory);
// $rowCat = mysqli_fetch_array($getCategory);
// print_r($rowCat);
// exit();




while ($rowCat = mysqli_fetch_array($getCategory)) {
    $results = mysqli_query($conn,"
                    SELECT SUM((Price-Discount)*Quantity) AS CATSUM
                    from tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_bills bl,
                    tbl_items t, tbl_item_subcategory ts,tbl_sponsor sp WHERE 
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID  and
                                pp.Bill_ID = bl.Bill_ID  and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id and
                                bl.Bill_Date between '$Start_Date' and '$End_Date' and 
                                sp.Sponsor_ID = pp.Sponsor_ID and
                                pp.Sponsor_ID = '$Sponsor_ID' and
			        ts.item_category_id='" . $rowCat['item_category_id'] . "' AND
                                invoice_status='$invoice_status'") or die(mysqli_error($conn));

    $cat = mysqli_fetch_assoc($results);


    $htm .= "
            <tr><td> $temp </td>
            <td>" . ucwords(strtolower($rowCat['Item_Category_Name'])) . "</td>

           <td style='text-align: right;'>" . number_format($cat['CATSUM'], 2) . "</td>
            </tr>";


    $total += $cat['CATSUM'];

    $temp++;
}

$htm .= "<tr><td colspan='8'><hr></td></tr>";
$htm .= "<tr><td colspan='6' style='text-align: left;'><b>GRAND TOTAL : </td><td style='text-align: right;'><b>" . number_format($total, 2) . "</b></td></tr>";

// $htm .= "<tr><td colspan='3' style='text-align: right;'><b> GRAND TOTAL : " . number_format($total) . "</td></tr>";

$htm .= '</table></center>';

include("./MPDF/mpdf.php");

$mpdf = new mPDF('', 'A4-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
?>
<div id="invoice_success"></div>
</table></center>


