<?php
  include("./includes/connection.php");
  $month = $_POST['month'];
  $year = $_POST['year'];
  $sponsor_id = $_POST['sponsor_id'];
 ?>
 <table style="width: 98%;font-size: 18px;">
     <tr>
         <th>SN</th>
         <th>Folio Number</th>
         <th>Patient Name</th>
         <th>Phone Number</th>
         <th>Member Number</th>
         <th>Sent Date</th>
         <th  style='text-align: right;'>Amount</th>
	 <th>Form 2A&B</th>
	 <th>Case Notes</th>

     </tr>
 <?php

$month = intVal(date("m", strtotime($month)));
//get all monthly bills
// $select_monthly_bills = mysqli_query($conn,"SELECT pp.Folio_Number, pp.Sponsor_ID, pp.Registration_ID, pp.Patient_Bill_ID, pp.Check_In_ID, pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date , SUM(ppl.Price * ppl.Quantity) as BillAmount FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pr.Registration_ID = pp.Registration_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND cf.Bill_ID = pp.Bill_ID AND pp.Billing_Process_Status = 'billed' AND cf. claim_month = '$month' AND claim_year = '$year' AND pp.Sponsor_ID = '$sponsor_id' GROUP BY cf.Bill_ID ORDER BY cf.Folio_No ASC ");
//get the folios for the specified dates
$select_monthly_bills = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID = b.Bill_ID AND pr.Registration_ID = cf.Registration_ID AND cf.claim_month = '$month' AND cf.claim_year = '$year'  AND b.Sponsor_ID = '$sponsor_id' AND b.e_bill_delivery_status = 1  ORDER BY cf.Folio_No ASC ");

// print_r("SELECT pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID = b.Bill_ID AND pr.Registration_ID = cf.Registration_ID AND cf.claim_month = '$month' AND cf.claim_year = '$year'  AND b.Sponsor_ID = '$sponsor_id'  ORDER BY cf.Folio_No ASC ");

//pp.Folio_Number,pp.Sponsor_ID,pp.Registration_ID,pp.Patient_Bill_ID 
$select_monthly_bills_list=[];
$bills_list = '';
while ($row = mysqli_fetch_assoc($select_monthly_bills)) {
  $bills_list .= $row['Bill_ID'].",";
  array_push($select_monthly_bills_list, array('Patient_Name'=>$row['Patient_Name'], 'Phone_Number'=>$row['Phone_Number'], 'Member_Number'=>$row['Member_Number'], 'Bill_ID'=>$row['Bill_ID'], 'Folio_No'=>$row['Folio_No'], 'sent_date'=>$row['sent_date']));
}
$select_monthly_bills_list = ((object)$select_monthly_bills_list);
// foreach ($select_monthly_bills_list as $monthly_bills_row) {
//   print_r($obj);
// }

$bills_list = chop($bills_list, ',');
//get bills amounts
$bills_amount = [];
$select_monthly_bills_amount = mysqli_query($conn,"SELECT pp.Folio_Number,pp.Sponsor_ID,pp.Check_In_ID, pp.Registration_ID,pp.Patient_Bill_ID , pp.Bill_ID, SUM(ppl.Price * ppl.Quantity) as BillAmount FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Bill_ID IN($bills_list) GROUP BY pp.Bill_ID");

while ($row = mysqli_fetch_assoc($select_monthly_bills_amount)) {
  $bills_amount[$row['Bill_ID']] = $row['BillAmount'];

    $Folio_Number = $row['Folio_Number'];
    $Sponsor_ID = $row['Sponsor_ID'];
    $Registration_ID = $row['Registration_ID'];
    $Patient_Bill_ID = $row['Patient_Bill_ID'];
    $Check_In_ID = $row['Check_In_ID'];
}

$count = 1;
$Total_Amount = 0;
// while( $monthly_bills_row = mysqli_fetch_assoc($select_monthly_bills_list)){
foreach ($select_monthly_bills_list as $monthly_bills_row) {
  $Bill_ID = $monthly_bills_row['Bill_ID'];
  $Folio_No = $monthly_bills_row['Folio_No'];
  $Patient_Name= $monthly_bills_row['Patient_Name'];
  $Phone_Number = $monthly_bills_row['Phone_Number'];
  $Member_Number = $monthly_bills_row['Member_Number'];
  $Sent_Date = $monthly_bills_row['sent_date'];
  $BillAmount = $bills_amount[$Bill_ID];


// $Folio_Number = $monthly_bills_row['Folio_Number'];
// $Sponsor_ID = $monthly_bills_row['Sponsor_ID'];
// $Registration_ID = $monthly_bills_row['Registration_ID'];
// $Patient_Bill_ID = $monthly_bills_row['Patient_Bill_ID'];
// $Check_In_ID = $monthly_bills_row['Check_In_ID'];


$Bill_ID = $monthly_bills_row['Bill_ID'];

$typecode = "";
  $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
      if(mysqli_num_rows($select111) > 0){
          $Billing_Type ='Inpatient Credit';
          $typecode ="IN";
      }else{
          $Billing_Type ='Outpatient Credit';
          $typecode = "OUT";

      }

echo "<tr><td>".($count++)."</td><td>".$Folio_No."</td><td>".$Patient_Name."</td><td>".$Phone_Number."</td><td>".$Member_Number."</td><td style='text-align: center;'>".$Sent_Date."</td><td  style='text-align: right;'>".number_format($BillAmount)."</td><td style='text-align: center;'>
<input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(\"$Folio_Number\",\"$Sponsor_ID\",\"$Registration_ID\",\"$Patient_Bill_ID \",\"$Check_In_ID\",\"$Bill_ID\")' value='PREVIEW'></td><td style='text-align: center;'>
<input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(\"$Registration_ID\",\"$Check_In_ID\",\"$Bill_ID\",\"$typecode\")' value='PREVIEW'></td></tr>";



$Total_Amount +=$BillAmount;
}

?>
<tr>
<td style='text-align: center;' colspan="6"><b>Total Amount</b></td>
<td style='text-align: right;'><?=number_format($Total_Amount);?></td><td></td><td></td>
</tr>
</table>

