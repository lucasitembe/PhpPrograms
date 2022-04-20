<?php
session_start();
include 'repository.php';
date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['store'])){
  $date = date('Y-m-d H:i:s');
  $data = array(
      array(
          'registration_id' => $_POST['Registration_ID'],
          'consultation_id' => $_POST['consultation_ID'],
          'employee_id' => $_POST['employee_id'],
          'loc_mood' => $_POST['loc_mood'],
          'sensation' => $_POST['sensation'],
          'ecg' => $_POST['ecg'],
          'bp' => $_POST['bp'],
          'urine_output' => $_POST['urine_output'],
          'temperature' => $_POST['temperature'],
          'breathing' => $_POST['breathing'],
          'activity' => $_POST['activity'],
          'diet_elimination' => $_POST['diet_elimination'],
          'skin' => $_POST['skin'],
          'infection' => $_POST['infection'],
          'comfort' => $_POST['comfort'],
          'bleeding' => $_POST['bleeding'],
          'patient_complaint' => $_POST['patient_complaint'],
          'family_concern' => $_POST['family_concern'],
          'socio_culture_issues' => $_POST['socio_culture_issues'],
          'fluid_electrolyte' => $_POST['fluid_electrolyte'],
          'labs_investigation' => $_POST['labs_investigation'],
          'leading_needs' => $_POST['leading_needs'],
          'time' => $_POST['time'],
          'summary' => $_POST['summary'],
          'comments' => $_POST['comments'],
          'created_at' => $date
      )
  );
  $receive_data = json_encode($data);
  function icu_form_five($receive_data){
    $json_data=json_encode(array('table'=>'tbl_icu_form_five',
        'data'=>json_decode($receive_data,true)
    ));
    return $json_data;
  }
  echo"Done!";
  query_insert(icu_form_five($receive_data));
}

?>
