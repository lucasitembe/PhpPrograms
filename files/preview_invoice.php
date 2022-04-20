<?php
include("./includes/connection.php");
//$report_type=$_POST['report_type'];
$invoice_id=mysqli_real_escape_string($conn,$_GET['Invoice_ID']);
if($invoice_id == 'from_bill_list'){
    $today=date('Y-m-d');
    $select_invoice_id=mysqli_query($conn,"SELECT invoice_id FROM tbl_invoice WHERE invoice_date = '$today' ORDER BY invoice_id DESC LIMIT 1");
    $invoice_id=mysqli_fetch_assoc($select_invoice_id)['invoice_id'];
}
	//if($report_type==='invoice'){
$select_invoice=mysqli_query($conn,"SELECT invoice_date,sponsor_id FROM tbl_invoice WHERE invoice_id = $invoice_id");
$invoice_data=mysqli_fetch_assoc($select_invoice);
$trans_date=$invoice_data['invoice_date'];
$sponsor_id=$invoice_data['sponsor_id'];
$sponsor_data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name, Postal_Address, Region,District FROM tbl_sponsor WHERE Sponsor_ID=$sponsor_id
"));
$html="<table width ='100%' height = '30px'>
<tr> <td> <img src='./branchBanner/branchBanner.png' width=100% height='100px'> </td></tr>
</table>";
$html .="<p style='text-align:center; font-size:18px;'>INVOICE</p>
    <p style='text-align:right; font-size:15px;'>P.O BOX 1370</p>
    <p style='text-align:right; font-size:15px;'>PHONE: 40610/5</p>
    <p style='text-align:right; font-size:15px;'>MWANZA, Tanzania</p>
    <p style='text-align:left; font-size:15px;'>M/S <u>&emsp;&emsp;&emsp;".$sponsor_data['Guarantor_Name']."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</p>
    <p style='text-align:left; font-size:15px;'><u>&emsp;&emsp;&emsp;".$sponsor_data['Postal_Address']."&emsp;&emsp;&emsp;</p>
    <p style='text-align:left; font-size:15px;'><u>&emsp;&emsp;&emsp;".$sponsor_data['Region']."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</p>
    <p style='text-align:right; font-size:15px;'>Date:<u>&emsp;&emsp;&emsp;".date_format(date_create($trans_date),'d-m-Y')."&emsp;&emsp;&emsp;</u></p>
    <p style='text-align:right; font-size:15px;padding-right:165px;'>No:<label id='invoice_no' style='font-size:18px;'>".$invoice_id."</label></p>";
 

$html .="<table width='100%' style='background-color:#fff; border-collapse:collapse;' border='1' >";
$html .=" <tr><th style='text-align:center'>Qty</th><th style='text-align:center'>PARTICULARS</th><th style='text-align:center'>@</th><th style='text-align:center'>SHS.</th><th style='text-align:center'>CTS.</th></tr>";
$select_invoice=mysqli_query($conn,"SELECT * FROM tbl_invoice WHERE invoice_id = $invoice_id");
$total_amount=0;
$count=1;
while($row=mysqli_fetch_assoc($select_invoice)){
	$amount=$row['amount'];
    $html .="<tr><td>{$count}</td><td>{$row['narration']}</td><td></td><td style='text-align:right;'>".number_format($amount)."</td><td style='text-align:right;'>00</td></tr>";
	$count++;
        
    }
    $html .="</table>
    <br>
    <center>Payment of this Invoice must be made before the end of the month following the date of the Invoice</center>
    <br>
    <br>";

    //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($html);
    $mpdf->Output();


    
 ?>
