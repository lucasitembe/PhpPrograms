<?php
  include("./includes/connection.php");
  @session_start();
  
  $Registration_ID = '';$folio='';$Admision_ID='';
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['folio'])){
        $folio = $_GET['folio'];
    } if(isset($_GET['Admision_ID'])){
        $Admision_ID = $_GET['Admision_ID'];
    }
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    
    $bl=  mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = '$folio' ORDER BY Patient_Payment_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    $Billing_Type=  mysqli_fetch_assoc($bl)['Billing_Type'];
    
    if($Billing_Type == 'Outpatient Cash'){
        $Billing_Type='Inpatient Cash';
    }elseif ($Billing_Type == 'Outpatient Credit') {
        $Billing_Type='Inpatient Credit';
    }
    
    $result=  mysqli_query($conn,"UPDATE tbl_admission SET Discharge_Clearance_Status='cleared',Clearer_ID='$Employee_ID' WHERE Admision_ID='$Admision_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

  if($result){
      
     
    $selec_receipt_number="
               SELECT distinct(pp.Patient_Payment_ID) FROM `tbl_patient_payment_item_list` ppil LEFT JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID WHERE pp.Billing_Type = '$Billing_Type' AND pp.Registration_ID=$Registration_ID AND Folio_Number = '$folio' AND ppil.Check_In_Type !='Direct Cash'
            ";
    $total_results = mysqli_query($conn,$selec_receipt_number) or die(mysqli_error($conn));
     
    while ($row = mysqli_fetch_array($total_results)) {
         mysqli_query($conn," UPDATE `tbl_patient_payment_item_list` ppil SET Status='Served' WHERE Patient_Payment_ID='".$row['Patient_Payment_ID']."' ") or die(mysqli_error($conn));
    }
     
      echo 'Allowed';
  }