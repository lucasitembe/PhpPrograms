<?php

function returnMedicationHours($registration_id,$item_id){

if (!empty($registration_id)){

  $select_eve_opening_data = "SELECT * FROM tbl_icu_form_three_hours WHERE patient_id = '$registration_id' AND DATE(date_time)=CURDATE() AND medication_id='$item_id'";
  $response = array();
  if ($result = mysqli_query($conn,$select_eve_opening_data)) {
   $data = array();
    while ($eve_rows = mysqli_fetch_assoc($result)) {
      // $data['item_id'] = $eve_rows['item_id'];
      $data['hr_one'] = $eve_rows['hr_one'];
      $data['hr_two'] = $eve_rows['hr_two'];
      $data['hr_three'] = $eve_rows['hr_three'];
      $data['hr_four'] = $eve_rows['hr_four'];
      $data['hr_five'] = $eve_rows['hr_five'];
      $data['hr_six'] = $eve_rows['hr_six'];
      $data['hr_seven'] = $eve_rows['hr_seven'];
      $data['hr_eight'] = $eve_rows['hr_eight'];
      $data['hr_nine'] = $eve_rows['hr_nine'];
      $data['hr_ten'] = $eve_rows['hr_ten'];
      $data['hr_eleven'] = $eve_rows['hr_eleven'];
      $data['hr_twelve'] = $eve_rows['hr_twelve'];
      $data['hr_thirteen'] = $eve_rows['hr_thirteen'];
      $data['hr_fourteen'] = $eve_rows['hr_fourteen'];
      $data['hr_fifteen'] = $eve_rows['hr_fifteen'];
      $data['hr_sixteen'] = $eve_rows['hr_sixteen'];
      $data['hr_seventeen'] = $eve_rows['hr_seventeen'];
      $data['hr_eighteen'] = $eve_rows['hr_eighteen'];
      $data['hr_nineteen'] = $eve_rows['hr_nineteen'];
      $data['hr_twenty'] = $eve_rows['hr_twenty'];
      $data['hr_twenty_one'] = $eve_rows['hr_twenty_one'];
      $data['hr_twenty_two'] = $eve_rows['hr_twenty_two'];
      $data['hr_twenty_three'] = $eve_rows['hr_twenty_three'];
      $data['hr_twenty_four'] = $eve_rows['hr_twenty_four'];
      array_push($response,$data);
    }

    return $data;
  }else {
    echo mysqli_error($conn);
  }

}
}


function returnIvFusionHours($registration_id,$item_id){

if (!empty($registration_id)){

  $select_eve_opening_data = "SELECT * FROM tbl_icu_form_three_infusion_hours WHERE patient_id = '$registration_id' AND DATE(date_time)=CURDATE() AND medication_id='$item_id'";
  $response = array();
  if ($result = mysqli_query($conn,$select_eve_opening_data)) {
   $data = array();
    while ($eve_rows = mysqli_fetch_assoc($result)) {
      // $data['item_id'] = $eve_rows['item_id'];
      $data['hr_one'] = $eve_rows['hr_one'];
      $data['hr_two'] = $eve_rows['hr_two'];
      $data['hr_three'] = $eve_rows['hr_three'];
      $data['hr_four'] = $eve_rows['hr_four'];
      $data['hr_five'] = $eve_rows['hr_five'];
      $data['hr_six'] = $eve_rows['hr_six'];
      $data['hr_seven'] = $eve_rows['hr_seven'];
      $data['hr_eight'] = $eve_rows['hr_eight'];
      $data['hr_nine'] = $eve_rows['hr_nine'];
      $data['hr_ten'] = $eve_rows['hr_ten'];
      $data['hr_eleven'] = $eve_rows['hr_eleven'];
      $data['hr_twelve'] = $eve_rows['hr_twelve'];
      $data['hr_thirteen'] = $eve_rows['hr_thirteen'];
      $data['hr_fourteen'] = $eve_rows['hr_fourteen'];
      $data['hr_fifteen'] = $eve_rows['hr_fifteen'];
      $data['hr_sixteen'] = $eve_rows['hr_sixteen'];
      $data['hr_seventeen'] = $eve_rows['hr_seventeen'];
      $data['hr_eighteen'] = $eve_rows['hr_eighteen'];
      $data['hr_nineteen'] = $eve_rows['hr_nineteen'];
      $data['hr_twenty'] = $eve_rows['hr_twenty'];
      $data['hr_twenty_one'] = $eve_rows['hr_twenty_one'];
      $data['hr_twenty_two'] = $eve_rows['hr_twenty_two'];
      $data['hr_twenty_three'] = $eve_rows['hr_twenty_three'];
      $data['hr_twenty_four'] = $eve_rows['hr_twenty_four'];
      array_push($response,$data);
    }

    return $data;
  }else {
    echo mysqli_error($conn);
  }

}
}


function returnBloodProductHours($registration_id,$item_id){

if (!empty($registration_id)){

  $select_eve_opening_data = "SELECT * FROM tbl_icu_form_three_blood_product_hours WHERE patient_id = '$registration_id' AND DATE(date_time)=CURDATE() AND item_id='$item_id'";
  $response = array();
  if ($result = mysqli_query($conn,$select_eve_opening_data)) {
   $data = array();
    while ($eve_rows = mysqli_fetch_assoc($result)) {
      // $data['item_id'] = $eve_rows['item_id'];
      $data['hr_one'] = $eve_rows['hr_one'];
      $data['hr_two'] = $eve_rows['hr_two'];
      $data['hr_three'] = $eve_rows['hr_three'];
      $data['hr_four'] = $eve_rows['hr_four'];
      $data['hr_five'] = $eve_rows['hr_five'];
      $data['hr_six'] = $eve_rows['hr_six'];
      $data['hr_seven'] = $eve_rows['hr_seven'];
      $data['hr_eight'] = $eve_rows['hr_eight'];
      $data['hr_nine'] = $eve_rows['hr_nine'];
      $data['hr_ten'] = $eve_rows['hr_ten'];
      $data['hr_eleven'] = $eve_rows['hr_eleven'];
      $data['hr_twelve'] = $eve_rows['hr_twelve'];
      $data['hr_thirteen'] = $eve_rows['hr_thirteen'];
      $data['hr_fourteen'] = $eve_rows['hr_fourteen'];
      $data['hr_fifteen'] = $eve_rows['hr_fifteen'];
      $data['hr_sixteen'] = $eve_rows['hr_sixteen'];
      $data['hr_seventeen'] = $eve_rows['hr_seventeen'];
      $data['hr_eighteen'] = $eve_rows['hr_eighteen'];
      $data['hr_nineteen'] = $eve_rows['hr_nineteen'];
      $data['hr_twenty'] = $eve_rows['hr_twenty'];
      $data['hr_twenty_one'] = $eve_rows['hr_twenty_one'];
      $data['hr_twenty_two'] = $eve_rows['hr_twenty_two'];
      $data['hr_twenty_three'] = $eve_rows['hr_twenty_three'];
      $data['hr_twenty_four'] = $eve_rows['hr_twenty_four'];
      array_push($response,$data);
    }

    return $data;
  }else {
    echo mysqli_error($conn);
  }

}
}
 ?>
