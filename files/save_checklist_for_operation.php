<?php

include("../includes/connection.php");
session_start();

if($_SESSION['userinfo'])
{
  $employee_Name3 = $_SESSION['userinfo']['Employee_Name'];
}


$allergies = mysqli_real_escape_string($conn,trim($_POST['allergies']));
$Created_date = mysqli_real_escape_string($conn,trim($_POST['Created_date']));
$hb = mysqli_real_escape_string($conn,trim($_POST['hb']));
$hb2 = mysqli_real_escape_string($conn,trim($_POST['hb2']));
$blood_transfusion = mysqli_real_escape_string($conn,trim($_POST['blood_transfusion']));
$estimated_blood_loss = mysqli_real_escape_string($conn,trim($_POST['estimated_blood_loss']));
$estimated_blood_loss2 = mysqli_real_escape_string($conn,trim($_POST['estimated_blood_loss2']));
$team_brief = mysqli_real_escape_string($conn,trim($_POST['team_brief']));
$patient_consent = mysqli_real_escape_string($conn,trim($_POST['patient_consent']));
$patient_discussed = mysqli_real_escape_string($conn,trim($_POST['patient_discussed']));
$operation_confirmed = mysqli_real_escape_string($conn,trim($_POST['operation_confirmed']));
$equipments_available = mysqli_real_escape_string($conn,trim($_POST['equipments_available']));
$antibiotics_needed = mysqli_real_escape_string($conn,trim($_POST['antibiotics_needed']));
$available = mysqli_real_escape_string($conn,trim($_POST['available']));
$imaging_displayed = mysqli_real_escape_string($conn,trim($_POST['imaging_displayed']));
$pulse_oximeter = mysqli_real_escape_string($conn,trim($_POST['pulse_oximeter']));
$aspiration = mysqli_real_escape_string($conn,trim($_POST['aspiration']));
$analgesia_morphine = mysqli_real_escape_string($conn,trim($_POST['analgesia_morphine']));
$swabs_counted = mysqli_real_escape_string($conn,trim($_POST['swabs_counted']));
$equipment_problems = mysqli_real_escape_string($conn,trim($_POST['equipment_problems']));
$operation_documented = mysqli_real_escape_string($conn,trim($_POST['operation_documented']));
$any_patient_concerns = mysqli_real_escape_string($conn,trim($_POST['any_patient_concerns']));
$handover_to_ward = mysqli_real_escape_string($conn,trim($_POST['handover_to_ward']));
$consultation_ID = mysqli_real_escape_string($conn,trim($_POST['consultation_ID']));
$Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
$Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
$Employee_ID = mysqli_real_escape_string($conn,trim($_POST['Employee_ID']));
$Patient_Payment_Item_List_ID = mysqli_real_escape_string($conn,trim($_POST['Patient_Payment_Item_List_ID']));
$Patient_Payment_Item_List_ID2 = mysqli_real_escape_string($conn,trim($_POST['Patient_Payment_Item_List_ID2']));
$consultant_approved = mysqli_real_escape_string($conn,trim($_POST['consultant_approved']));
$essential_imaging = mysqli_real_escape_string($conn,trim($_POST['essential_imaging']));

$swabs_counted2 = mysqli_real_escape_string($conn,trim($_POST['swabs_counted2']));
$equipment_problems2 = mysqli_real_escape_string($conn,trim($_POST['equipment_problems2']));
$operation_documented2 = mysqli_real_escape_string($conn,trim($_POST['operation_documented2']));
$any_patient_concerns2 = mysqli_real_escape_string($conn,trim($_POST['any_patient_concerns2']));
$handover_to_ward2 = mysqli_real_escape_string($conn,trim($_POST['handover_to_ward2']));
$weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
//$Created_date = date_create($Created_date);



$date = date("Y-m-d h:i");

// SAVE
if($_POST['action'] == 'Save')
{
  $select_operation = mysqli_query($conn,"SELECT * FROM tbl_checklist_for_operation WHERE Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID' AND  consultation_id = '$consultation_ID' AND Admision_ID = '$Admision_ID' AND Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Created_date = '$date'") or die(mysqli_error($conn));


    if(mysqli_num_rows($select_operation) > 0)
    {
      echo "These Information are Already Saved.";

    }
    else{

      $insert = mysqli_query($conn,"INSERT INTO  tbl_checklist_for_operation(Registration_ID,Employee_ID,consultation_id,Admision_ID,Patient_Payment_Item_List_ID,
      Confirm_Patient_Consent,Patient_Discussed_At_Team_Brief,Operation_Confirmed,All_Equipment_Available,Antibiotics_Needed,Estimated_Blood_Loss,Blood_Transfusion_Predicted,
      Available,Essential_Imaging_Displayed,Pulse_Oximeter,Aspiration,Analgesia,Swabs_Counted,Equipment_Problems_Addressed,Operation_Documented,
      Any_Patient_Concerns,Hand_Over_To_Ward_Staff,Hb,weight,Consultant_Approved,Any_Essential_Imaging,allergies)
      VALUES('$Registration_ID','$Employee_ID','$consultation_ID','$Admision_ID','$Patient_Payment_Item_List_ID','$patient_consent','$patient_discussed','$operation_confirmed',
      '$equipments_available','$antibiotics_needed','$estimated_blood_loss','$blood_transfusion','$available','$imaging_displayed','$pulse_oximeter',
      '$aspiration','$analgesia_morphine','$swabs_counted','$equipment_problems','$operation_documented','$any_patient_concerns','$handover_to_ward','$hb','$weight','$consultant_approved','$essential_imaging','$allergies')") or die(mysqli_error($conn));

       if($insert)
       {
         echo "Saved Successfully!";
         mysqli_close($conn);

       }
    }

}





 ?>
