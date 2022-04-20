<?php

 include("./includes/connection.php");


//header("Content-Type:application/json");
require_once('nhif3/ServiceManager.php');

$ClaimYear = $_POST['year'];
$ClaimMonth = $_POST['month'];
$manager=new ServiceManager;

$FacilityCode = mysqli_fetch_assoc(mysqli_query($conn,"SELECT facility_code FROM tbl_system_configuration LIMIT 1"))['facility_code'];

$HospitalDetails = "FacilityCode=".$FacilityCode."&ClaimYear=".$ClaimYear."&ClaimMonth=".$ClaimMonth;

$system_results = mysqli_query($conn, "SELECT pr.Registration_ID, pr.Patient_Name, b.Sponsor_ID, b.Bill_ID, cf.Folio_No, pr.Phone_Number, b.`Bill_Date_And_Time` FROM tbl_bills b, tbl_claim_folio cf, tbl_patient_registration pr WHERE pr.Registration_ID = cf.Registration_ID AND b.Bill_ID = cf.Bill_ID AND cf.claim_year = '$ClaimYear' AND cf.claim_month = '$ClaimMonth'");
$folio_list = [];
while($row = mysqli_fetch_assoc($system_results)){
	$folio_list[$row['Bill_ID']] = $row;
}
//echo $HospitalDetails;
$claimsResults=$manager->GetSubmittedClaims($HospitalDetails);
//

//echo $claimsResults;

$claimsResults=json_decode($claimsResults);
//print_r($claimsResults);
//die();
echo "<center><table width = '100%' border=0 >
          <tr><td width=3%><b>SN</b></td><td width=5% style='text-align: left;'><b>Bill No</b></td>
              <td width=4%><b>Folio No.</b></td>
              <td width=8% style'text-align: left;'><b>Patient Number </b></td>
              <td width=15% style='text-align: left;'><b>Patient Name </b></td>
              <td width=7% style='text-align: left;'><b>Phone Number </b></td>
              <td width=6% style='text-align: left;'><b>Patient Type </b></td>
              <td width=7% style='text-align: left;'><b>Attendence Date</b></td>
              <td width=7% style='text-align: left;'><b>Created Date</b></td>
              <td width=7% style='text-align: right;'><b>Total Amount</b></td>

              <td width=9% style='text-align: center;'><b>Bill Status</b></td>
              <td width=9% style='text-align: center;'><b>Form 2A&B</b></td>

              <td width=6% style='text-align: center;'><b>Case Notes</b></td>
          </tr>";
//if(sizeof($claimsResults) > 2){
$count = 1;
foreach ($claimsResults as $key => $value) {
	if(!isset($value->BillNo)){continue;}
$details = mysqli_fetch_assoc(mysqli_query($conn, "SELECT pp.Patient_Bill_ID, ci.Check_In_ID, ci.Check_In_Date_And_Time FROM tbl_check_in ci, tbl_patient_payments pp WHERE ci.Check_In_ID = pp.Check_In_ID AND pp.Bill_ID = '$value->BillNo' LIMIT 1 "));

$Patient_Bill_ID = $details['Patient_Bill_ID'];
$Check_In_ID = $details['Check_In_ID'];;

$Registration_ID = $folio_list[$value->BillNo]['Registration_ID'];
$Sponsor_ID = $folio_list[$value->BillNo]['Sponsor_ID'];

$typecode = "";
    $Billing_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' AND Billing_Type ='Inpatient Credit' ORDER BY patient_payment_id DESC LIMIT 1"))['Billing_Type'];
                  $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = $Check_In_ID");
                  if(mysqli_num_rows($select111) > 0){
                      $Billing_Type ='Inpatient';
                      $typecode ="IN";
                  }else{
                      $Billing_Type ='Outpatient';
                      $typecode = "OUT";

                  }
  //mysqli_query($conn,"INSERT IGNORE INTO tbl_claims_form_nhif(BillNo, FolioNo, ClaimMonth, ClaimYear) VALUES('$value->BillNo','$value->FolioNo','$value->ClaimMonth','$value->ClaimYear')");

echo "<tr><td>".($count++)."</td><td>".$value->BillNo."</td><td>".$value->FolioNo."</td><td>".$folio_list[$value->BillNo]['Registration_ID']."</td><td>".$folio_list[$value->BillNo]['Patient_Name']."</td><td>".$folio_list[$value->BillNo]['Phone_Number']."</td><td>".$Billing_Type."</td><td>".$details['Check_In_Date_And_Time']."</td><td>".$folio_list[$value->BillNo]['Bill_Date_And_Time']."</td><td>".$value->AmountClaimed."</td><td style='text-align: center;background-color:white;color:green'><b>Sent</b></td><td><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$value->BillNo.")' value='PREVIEW'></td><td><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$value->BillNo.")' value='PREVIEW'></td></tr>";

}
/*}else{
	echo "<tr><td colspan='13'> Could Not Fetch Data From NHIF<br> Error Code:".$claimsResults->StatusCode." <br> Message:".$claimsResults->Message."<td></tr>";
}
*/
echo "</table>
          </center>";


 ?>
