<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Sponsor_ID=$_POST['Sponsor_ID'];
$narration=$_POST['narration'];
$Start_Date=$_POST['Start_Date'];
$End_Date=$_POST['End_Date'];
// create an invoice
$invoice_date=date('Y-m-d');
$invoice_year=date('Y');
$invoice_month=date('m');
$rows_updated=0;
$sql_select = mysqli_query($conn,"SELECT bl.Bill_ID FROM tbl_bills bl, tbl_employee emp, tbl_sponsor sp 
							WHERE emp.Employee_ID = bl.Employee_ID AND bl.Sponsor_ID = sp.Sponsor_ID AND
                               DATE(bl.Bill_Date_And_Time) between '$Start_Date' AND '$End_Date' AND
                               bl.Sponsor_ID = '$Sponsor_ID' AND bl.invoice_created= 'no' ") or die(mysqli_error($conn));
$Amount=0;
$bill_list=[];
$count=mysqli_num_rows($sql_select);
if($count > 0){
	while ($row= mysqli_fetch_assoc($sql_select)) {
		$Bill_ID=$row['Bill_ID'];
	    $get_Total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) AS Bill_Amount,pp.Folio_Number,pp.Patient_Bill_ID,pp.Registration_ID,pr.Patient_Name from
	    tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_patient_registration pr WHERE
	    pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
	    pp.Registration_ID=pr.Registration_ID AND
	    pp.Bill_ID = '$Bill_ID'") or die(mysqli_error($conn));   
	    $Amount+=mysqli_fetch_assoc($get_Total)['Bill_Amount'];
	    $bill_list[] = $Bill_ID;
	}
	if($Amount > 0){
		mysqli_query($conn,"INSERT INTO tbl_invoice(invoice_date,sponsor_id,employee_id,amount,invoice_month,invoice_year,narration) VALUES('$invoice_date',$Sponsor_ID,$Employee_ID,'$Amount','$invoice_month','$invoice_year','$narration')");
		// update bill table for created invoice
		$query=mysqli_query($conn,"SELECT invoice_id FROM tbl_invoice WHERE employee_id=$Employee_ID AND amount='$Amount' AND sponsor_id=$Sponsor_ID AND invoice_date='$invoice_date' ORDER BY Invoice_ID DESC LIMIT 1");
		$invoice_id=mysqli_fetch_assoc($query)['invoice_id'];
			foreach ($bill_list as $Bill_ID) {
				$results=mysqli_query($conn,"UPDATE tbl_bills SET invoice_id=$invoice_id, invoice_created='yes' WHERE Bill_ID=$Bill_ID AND Sponsor_ID=$Sponsor_ID");
			}
		echo 'An Invoice Created With '.sizeof($bill_list).' Bills and Amount='.number_format($Amount);
	}
}else{
	echo "fail";
}
?>