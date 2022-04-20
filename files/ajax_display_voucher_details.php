<?php
include("./includes/connection.php");
$report_type=$_POST['report_type'];
$voucher_id=$_POST['Invoice_ID'];
	if($report_type==='voucher'){
$select_invoice=mysqli_query($conn,"SELECT voucher_date FROM tbl_voucher WHERE voucher_id = $voucher_id");
$trans_date=mysqli_fetch_assoc($select_invoice)['voucher_date'];
$select_box=mysqli_query($conn,"SELECT Hospital_Name, Box_Address FROM tbl_system_configuration");
$box_number=mysqli_fetch_assoc($select_box)['Box_Address'];

?>
    <p style='text-align:center; font-size:18px;'>PAYMENT VOUCHER</p>
    <p style='text-align:center; font-size:18px;'>P.O Box <?=$box_number;?></p>

<?php
echo "<table width='100%' style='background-color:#fff;' border='1' >";
echo " <tr><th style='text-align:center'>Qty</th><th style='text-align:center'>PAYMENT DETAILS</th><th style='text-align:center'>@</th><th style='text-align:center'>SHS.</th></tr>";
$select_invoice=mysqli_query($conn,"SELECT * FROM tbl_voucher WHERE voucher_id = $voucher_id");
$total_amount=0;
$cheque_no="";
$prepared_date="";
$word_amount="";
$account_title="";
$account_code="";
$emp_id="";
$employee="";
$count=1;
while($row=mysqli_fetch_assoc($select_invoice)){
	$amount=$row['amount'];
    $cheque_no=$row['cheque_number'];
    $prepared_date=$row['voucher_date'];
    $word_amount=$row['word_amount'];
    $account_title=$row['account_title'];
    $account_code=$row['account_code'];
    $emp_id=$row['Employee_ID'];
    $employee=mysqli_fetch_assoc(mysqli_query($conn,"SELECT  Employee_Name FROM tbl_employee WHERE Employee_ID=$emp_id"))['Employee_Name'];
        //while ($row=mysqli_fetch_assoc($select_patients)) {
            echo "<tr><td>{$count}</td><td>{$row['narration']}</td><td></td><td style='text-align:right;'>".number_format($amount)."</td></tr>";
        	$count++;
            $total_amount+=$amount;
        	//$total_amount += $row['Bill_Amount'];
        //}
    }
            echo "<tr><td colspan='3' style='text-align:center;'><b>Total Amount</b></td><td style='text-align:right;'><b>".number_format($total_amount)."</b></td></tr>";
        ?>
 </table>
    <br>
    <fieldset style='border:1px solid #000; width:350px; height:70px; background-color:#fff;'>
    <label>Cheque No: <u>&emsp;&emsp;<?=$cheque_no;?>&emsp;&emsp;</u></label>    <br>
    <label>Date: <u>&emsp;&emsp;<?=date_format(date_create($prepared_date),'d-m-Y');?>&emsp;&emsp;</u></label>
</fieldset> <br>
<label>Amount in words:<u>&emsp;&emsp;<?=ucfirst($word_amount);?>&emsp;&emsp;</u></label> <br><br>
<label>Title of Account: <u>&emsp;&emsp;<?=$account_title;?>&emsp;&emsp;</u></label> <label>Account Code: <u>&emsp;&emsp;<?=$account_code;?>&emsp;&emsp;</u></label><br><br>
<label>Prepared by: <u>&emsp;&emsp;<?=$employee;?></u>&emsp;&emsp;Sign<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u></label> <label style='margin-left:10%'>Examined by: _____________Sign_________________</label><br>
<span  style='float:right;'>Date: <u>&emsp;&emsp;<?=date('d-m-Y');?>&emsp;&emsp;</u></span><br>
<center>
    <span>CERTIFICATION OF PAYMENT</span><br>
    I Certify that payment is in order and that there are sufficient funds to meet this expenditure
    <br><u>__________________________________</u> <br>
    Authorising Officer <br>
    <label style='margin-left:30%'>Date _________________________</label>
</center>
    <input type="submit" name="preview_invoice" value="Preview"  class="art-button-green" style="float:right;" onclick='Preview_Voucher(<?=$voucher_id;?>);'>
    <br>
    <br>
<?php } ?>
