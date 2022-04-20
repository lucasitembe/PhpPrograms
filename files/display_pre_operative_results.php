<?php
  include("./includes/connection.php");
  $Registration_ID = $_POST['Registration_ID'];
  $Consultation_ID = $_POST['Consultation_ID'];
  $select_pre_operative_details = mysqli_query($conn,"SELECT * FROM tbl_pre_operative_checklist poc, tbl_pre_operative_remarks por WHERE Registration_ID = $Registration_ID  AND poc.Pre_Operative_ID = por.Pre_Operative_ID AND poc.consultation_ID = $Consultation_ID") or die(mysqli_error($conn));
   //$pre_operative_results = mysqli_fetch_assoc($select_pre_operative_details);
  $pre_operative_details_checklist =array();
  $pre_operative_details_remarks =array();
  $pre_operative_details_items =array();
  $Pre_Operative_ID =0;
  while ($row = mysqli_fetch_assoc($select_pre_operative_details)) {
    $Pre_Operative_ID = $row['Pre_Operative_ID'];
    array_push($pre_operative_details_checklist, array(
      'Pre_Operative_ID' => $row['Pre_Operative_ID'],
      'Employee_ID' => $row['Employee_ID'],
      'Registration_ID' => $row['Registration_ID'],
      'Consultation_ID' => $row['consultation_ID'],
      'Admision_ID' => $row['Admision_ID'],
      'Theatre_Time' =>$row['Theatre_Time'] ,
      'Ward_Signature' =>$row['Ward_Signature'] ,
      'Theatre_Signature' =>$row['Theatre_Signature'] ,
      'Special_Information' =>$row['Special_Information'] ,
      'Operative_Date_Time' =>$row['Operative_Date_Time']
    ));
    // array_push($pre_operative_details_remarks, array(
    //   'Patient_Idenified_Remark' =>$row['Patient_Idenified_Remark'],
    //   'Urine_passed_Remark' =>$row['Urine_passed_Remark'],
    //   'Dentures_removed_Remark' =>$row['Dentures_removed_Remark'],
    //   'Contact_lenses_Remark' =>$row['Contact_lenses_Remark'],
    //   'Jowerly_removed_Remark' =>$row['Jowerly_removed_Remark'],
    //   'Cosmetic_and_Clothing_Remark' =>$row['Cosmetic_and_Clothing_Remark'],
    //   'Consent_form_signed_Remark' =>$row['Consent_form_signed_Remark'],
    //   'Enema_or_laxative_Remark' =>$row['Enema_or_laxative_Remark'],
    //   'Other_prosthesis_Remark' =>$row['Other_prosthesis_Remark'],
    //   'Special_order_Remark' =>$row['Special_order_Remark'],
    //   'Operative_site_Remark' =>$row['Operative_site_Remark'],
    //   'Radiographs_accompanying_Remark' =>$row['Radiographs_accompanying_Remark'],
    //   'Test_for_HIV_Remark' =>$row['Test_for_HIV_Remark'],
    //   'Identification_bands_Remark' =>$row['Identification_bands_Remark'],
    //   'Loose_teeth_Remark' =>$row['Loose_teeth_Remark'],
    //   'Hearing_adis_Remark' =>$row['Hearing_adis_Remark'],
    //   'Pre_operative_skin_Remark' =>$row['Pre_operative_skin_Remark'],
    //   'Valuable_securely_Remark' =>$row['Valuable_securely_Remark'],
    //   'Theatre_gowns_Remark' =>$row['Theatre_gowns_Remark'],
    //   'Care_patient_case_Remark' =>$row['Care_patient_case_Remark'],
    //   'Oral_hygiene_Remark' =>$row['Oral_hygiene_Remark'],
    //   'Catheter_Remark' =>$row['Catheter_Remark'],
    //   'Patient_having_Remark' =>$row['Patient_having_Remark'],
    //   'Check_list_Remark' =>$row['Check_list_Remark'],
    //   'Test_for_VDRL_Remark' =>$row['Test_for_VDRL_Remark'],
    //   'Test_for_Hopatitis_Remark' =>$row['Test_for_Hopatitis_Remark']
    // ));

    //echo $row['Pre_Operative_ID'].' , ';
  }
  // $select_pre_operative_items = mysqli_query($conn,"SELECT * FROM tbl_pre_operative_checklist_items WHERE Pre_Operative_ID = $Pre_Operative_ID");
  //
  // while ($row = mysqli_fetch_assoc($select_pre_operative_items)) {
  //   array_push($pre_operative_details_items, array(
  //     'Patient_Identified_Name' =>$row['Patient_Identified_Name'],
  //     'Urine_passed' =>$row['Urine_passed'],
  //     'Dentures_removed' =>$row['Dentures_removed'],
  //     'Contact_lenses' =>$row['Contact_lenses'],
  //     'Jowerly_removed' =>$row['Jowerly_removed'],
  //     'Cosmetic_and_Clothing' =>$row['Cosmetic_and_Clothing'],
  //     'Consent_form_signed' =>$row['Consent_form_signed'],
  //     'Enema_or_laxative' =>$row['Enema_or_laxative'],
  //     'Other_prosthesis' =>$row['Other_prosthesis'],
  //     'Special_order' =>$row['Special_order'],
  //     'Operative_site' =>$row['Operative_site'],
  //     'Radiographs_accompanying' =>$row['Radiographs_accompanying'],
  //     'Test_for_HIV' =>$row['Test_for_HIV'],
  //     'Identification_bands' =>$row['Identification_bands'],
  //     'Loose_teeth' =>$row['Loose_teeth'],
  //     'Hearing_adis' =>$row['Hearing_adis'],
  //     'Pre_operative_skin' =>$row['Pre_operative_skin'],
  //     'Valuable_securely' =>$row['Valuable_securely'],
  //     'Theatre_gowns' =>$row['Theatre_gowns'],
  //     'Care_patient_case' =>$row['Care_patient_case'],
  //     'Oral_hygiene' =>$row['Oral_hygiene'],
  //     'Catheter' =>$row['Catheter'],
  //     'Patient_having' =>$row['Patient_having'],
  //     'Check_list' =>$row['Check_list'],
  //     'Test_for_VDRL' =>$row['Test_for_VDRL'],
  //     'Test_for_Hopatitis' =>$row['Test_for_Hopatitis'],
  //     'Task_Name' =>$row['Task_Name'],
  //     'Task_Value' =>$row['Task_Value'],
  //     'Remark' =>$row['Remark']
  //   ));
  // }
  // $all_details =array(
  //   'checklist_details' => $pre_operative_details_checklist,
  //   'remarks_details'   => $pre_operative_details_remarks,
  //   'item_details'   => $pre_operative_details_items,
  // );
  /*
  display patient pre operative info
  */
  // echo "<table style='width:100%;'>";
  // echo "<tr>";
  // echo "<td>".$pre_operative_details_checklist[0]['Pre_Operative_ID']."</td>";
  // echo "</tr>";
  // echo "</table>";
echo ($pre_operative_details_checklist[0]['Pre_Operative_ID']);
 ?>
