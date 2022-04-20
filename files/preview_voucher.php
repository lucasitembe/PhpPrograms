<?php
include("./includes/connection.php");
$voucher_id=mysqli_real_escape_string($conn,$_GET['Voucher_ID']);
$From=mysqli_real_escape_string($conn,$_GET['From']);

$htm="<table width ='100%' height = '40px'>
<tr> <td> <img src='./branchBanner/branchBanner.png' width=100% height='100px'> </td></tr>
</table>";
if(isset($voucher_id) && isset($From) && $From =='supplier_ledger'){
$select_voucher=mysqli_query($conn,"SELECT voucher_date FROM tbl_voucher WHERE voucher_id = $voucher_id");
$trans_date=mysqli_fetch_assoc($select_voucher)['voucher_date'];
$select_box=mysqli_query($conn,"SELECT Hospital_Name, Box_Address FROM tbl_system_configuration");
$box_number=mysqli_fetch_assoc($select_box)['Box_Address'];


$htm.="<p style='text-align:center; font-size:13px;'>PAYMENT VOUCHER</p>
    <p style='text-align:center; font-size:13px;'>P.O Box ".$box_number."</p>";


$htm .= "<table width='100%' style='background-color:#fff; border-collapse:collapse;' border='1' >";
$htm .= " <tr><th style='text-align:center'>Qty</th><th style='text-align:center'>PAYMENT DETAILS</th><th style='text-align:center'>@</th><th style='text-align:center'>SHS.</th></tr>";
$select_voucher=mysqli_query($conn,"SELECT * FROM tbl_voucher WHERE voucher_id = $voucher_id  AND payee_type='supplier'");
$total_amount=0;
$cheque_no="";
$prepared_date="";
$word_amount="";
$account_title="";
$account_code="";
$emp_id="";
$employee="";
$count=1;
while($row=mysqli_fetch_assoc($select_voucher)){
    $amount=$row['amount'];
    $cheque_no=$row['cheque_number'];
    $prepared_date=$row['voucher_date'];
    $word_amount=$row['word_amount'];
    $account_title=$row['account_title'];
    $account_code=$row['account_code'];
    $emp_id=$row['Employee_ID'];
    $employee=mysqli_fetch_assoc(mysqli_query($conn,"SELECT  Employee_Name FROM tbl_employee WHERE Employee_ID=$emp_id"))['Employee_Name'];
        //while ($row=mysqli_fetch_assoc($select_patients)) {
            $htm .= "<tr><td>{$count}</td><td>{$row['narration']}</td><td></td><td style='text-align:right;'>".number_format($amount)."</td></tr>";
            $count++;
            $total_amount+=$amount;
            //$total_amount += $row['Bill_Amount'];
        //}
    }
            $htm .= "<tr><td colspan='3' style='text-align:center;'><b>Total Amount</b></td><td style='text-align:right;'><b>".number_format($total_amount)."</b></td></tr></table>";

 $htm .= "
    <br>
    <fieldset style='border:1px solid #000; width:350px; height:70px; background-color:#fff;'>
    <label>Cheque No: <u>&emsp;&emsp;".$cheque_no."&emsp;&emsp;</u></label>    <br>
    <label>Date: <u>&emsp;&emsp;".date_format(date_create($prepared_date),'d-m-Y')."&emsp;&emsp;</u></label>
</fieldset> <br>
<label>Amount in words: <u>&emsp;&emsp;".ucfirst($word_amount)."&emsp;&emsp;</u></label> <br><br>
<label>Title of Account:<u>&emsp;&emsp;".$account_title."&emsp;&emsp;</u></label> <label>Account Code: <u>&emsp;&emsp;".$account_code."&emsp;&emsp;</u></label><br><br>
<label>Prepared by: <u>&emsp;&emsp;".$employee."&emsp;&emsp;</u>&emsp;&emsp;Sign<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u>Date: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u></label><br>
<!--label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; Accountant</label><br--><br>
<label style='margin-left:10%'>Examined by: <u>&emsp;&emsp; &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp; &emsp;&emsp;</u>&emsp;&emsp;Sign<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u>Date: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u></label><br>
<!--label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; CTC I/C</label><br-->
<table>
    <tr><td style='text-align:center;'>CERTIFICATION OF PAYMENT</td></tr>
    <tr><td style='text-align:center;'>I Certify that payment is in order and that there are sufficient funds to meet this expenditure</td></tr>
    <tr><td style='text-align:center;'><u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> <td></tr>
    <tr><td  style='text-align:center;'>Authorizing Officer<td><tr>
    <!--tr><td  style='text-align:center;'>MOI<td><tr-->
</table>
    <br>
    <br>";
}
if(isset($voucher_id) && isset($From) && $From =='employee_payments'){
    $select_voucher=mysqli_query($conn,"SELECT * from tbl_voucher WHERE voucher_ID = $voucher_id AND payee_type='employee'");
    $data=mysqli_fetch_assoc($select_voucher);
    //get payee information
    $select_payee=mysqli_query($conn,"SELECT e.Employee_Name FROM tbl_employee e WHERE Employee_ID={$data['Supplier_ID']}");
    $payee_name=mysqli_fetch_assoc($select_payee)['Employee_Name'];
    //get payer information
    $select_payer=mysqli_query($conn,"SELECT e.Employee_Name FROM tbl_employee e WHERE Employee_ID={$data['Employee_ID']}");
    $payer=mysqli_fetch_assoc($select_payer)['Employee_Name'];

    $select_hospital_info=mysqli_query($conn,"SELECT Hospital_Name, Box_Address FROM tbl_system_configuration");
    $info=mysqli_fetch_assoc($select_hospital_info);
    $htm .="<p style='text-align:center'><u> &emsp; CASH PAYMENT VOUCHER &emsp;</u></p>";
    $htm .="<p style='text-align:center'> &emsp;P.O Box {$info['Box_Address']} &emsp;</p>";
    $htm .="<p style='text-align:right;'>Date:<u>&emsp;&emsp;".date_format(date_create($data['voucher_date']),'d-m-Y')."&emsp;&emsp;</u></p>";
    $htm .="<p > Paid to<br>Nimelipa kwa<u> &emsp;{$payee_name} &emsp;</u></p>";
    $htm .="<p > Sum of T/Shs.<u> &emsp;{$data['word_amount']} &emsp;</u><br>Kiasi cha</p>";
    $htm .="<p > For the purpose of . <u> &emsp; {$data['narration']} &emsp;</u><br>Kwa ajila ya</p>";
    $htm .="<table width='100%'>";
    $htm .="<tr > <td>Cash / Cheque <u> &emsp;{$data['cheque_number']} &emsp;</u><td style='text-align:right;'>Authorized by _____________________________</td></tr>";
    $htm .="<tr > <td>T.Shs <u> &emsp;{$data['amount']} &emsp;</u> </td><td style='text-align:right;'>Paid by <u>&emsp;&emsp;&emsp;{$payer}&emsp;&emsp;</u></td></tr>";
    $htm .="</table>";
    $htm .="<p><br></p>";
    $htm .="<p >Allocation Code:</p>";
    $htm .="<p>No______________________________________________</p>";
    $htm .="<p>No______________________________________________</p>";
    $htm .="<p>No______________________________________________</p>";
    $htm .="<table>";
    $htm .="<tr><td></td></tr>";
    $htm .="</table>";
    $htm .="<p><br></p>";
}

       //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();


  ?>
