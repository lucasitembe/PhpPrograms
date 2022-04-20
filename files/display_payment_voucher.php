<?php
include("./includes/connection.php");
$Search=mysqli_real_escape_string($conn,$_POST['Search']);
$voucher_ID=mysqli_real_escape_string($conn,$_POST['voucher_ID']);
if(isset($voucher_ID) && $Search =='no'){
	$select_voucher=mysqli_query($conn,"SELECT * from tbl_voucher WHERE voucher_ID = $voucher_ID AND payee_type='employee'");
	$data=mysqli_fetch_assoc($select_voucher);
	//get payee information
	$select_payee=mysqli_query($conn,"SELECT e.Employee_Name FROM tbl_employee e WHERE Employee_ID={$data['Supplier_ID']}");
	$payee_name=mysqli_fetch_assoc($select_payee)['Employee_Name'];
	//get payer information
	$select_payer=mysqli_query($conn,"SELECT e.Employee_Name FROM tbl_employee e WHERE Employee_ID={$data['Employee_ID']}");
	$payer=mysqli_fetch_assoc($select_payer)['Employee_Name'];

	$select_hospital_info=mysqli_query($conn,"SELECT Hospital_Name, Box_Address FROM tbl_system_configuration");
	$info=mysqli_fetch_assoc($select_hospital_info);
	echo "<p style='text-align:center'><u> &emsp; CASH PAYMENT VOUCHER &emsp;</u></p>";
	echo "<p style='text-align:center'> &emsp;P.O Box {$info['Box_Address']} &emsp;</p>";
	echo "<p style='text-align:right;'>Date:<u>&emsp;&emsp;".date_format(date_create($data['voucher_date']),'d-m-Y')."&emsp;&emsp;</u></p>";
	echo "<p > Paid to<br>Nimelipa kwa<u> &emsp;{$payee_name} &emsp;</u></p>";
	echo "<p > Sum of T/Shs.<u> &emsp;{$data['word_amount']} &emsp;</u><br>Kiasi cha</p>";
	echo "<p > For the purpose of . <u> &emsp; {$data['narration']} &emsp;</u><br>Kwa ajila ya</p>";
	echo "<p > Cash / Cheque <u> &emsp;{$data['cheque_number']} &emsp;</u><span style='float:right;'>Authorized by _____________________________</span></p>";
	echo "<p > T.Shs <u> &emsp;{$data['amount']} &emsp;</u> <span style='float:right;'>Paid by <u>&emsp;&emsp;&emsp;{$payer}&emsp;&emsp;</u></span></p>";
	echo "<p><br></p>";
	echo "<p >Allocation Code:</p>";
	echo "<p>No<u>______________________________________________</u></p>";
	echo "<p>No<u>______________________________________________</u></p>";
	echo "<p>No<u>______________________________________________</u></p>";
	echo"<table>";
		echo"<tr><td></td></tr>";
	echo"</table>";
	echo "<p><br></p>";
	echo "<p><input type='submit' name='submit' value='PREVIEW' class='art-button-green' style='float:right;' onclick='Preview_Voucher(\"{$voucher_ID}\")'></p>";
}

?>