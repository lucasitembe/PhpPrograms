<?php
include('../includes/connection.php');
if (isset($_POST['baby_of'])) {
  $baby_of = mysqli_real_escape_string($conn,trim($_POST['baby_of']));
  $address = mysqli_real_escape_string($conn,trim($_POST['address']));
  $foetal_heart_rate_at_delivery = mysqli_real_escape_string($conn,trim($_POST['foetal_heart_rate_at_delivery']));
  $religion = mysqli_real_escape_string($conn,trim($_POST['religion']));
  $tribe = mysqli_real_escape_string($conn,trim($_POST['tribe']));
  $indication = mysqli_real_escape_string($conn,trim($_POST['indication']));
  $ten_cell_leader = mysqli_real_escape_string($conn,trim($_POST['ten_cell_leader']));
  $case_file_no = mysqli_real_escape_string($conn,trim($_POST['case_file_no']));
  $type_of_delivery =mysqli_real_escape_string($conn,trim($_POST['type_of_delivery']));
  $date_and_time_of_birth = mysqli_real_escape_string($conn,trim($_POST['date_and_time_of_birth']));
  $sex = mysqli_real_escape_string($conn,trim($_POST['sex']));
  $matenal_disease_and_contraction = mysqli_real_escape_string($conn,trim($_POST['matenal_disease_and_contraction']));
  $weight_at_birth = mysqli_real_escape_string($conn,trim($_POST['weight_at_birth']));
  $apga_score = mysqli_real_escape_string($conn,trim($_POST['apga_score']));
  $maturity_by_dates = mysqli_real_escape_string($conn,trim($_POST['maturity_by_dates']));
  $membrane_rupture_for = mysqli_real_escape_string($conn,trim($_POST['membrane_rupture_for']));
  $type_of_amnionic_fluid = mysqli_real_escape_string($conn,trim($_POST['type_of_amnionic_fluid']));
  $weight_of_placenta = mysqli_real_escape_string($conn,trim($_POST['weight_of_placenta']));
  $resucitation_method = mysqli_real_escape_string($conn,trim($_POST['resucitation_method']));
  $delivery_by = mysqli_real_escape_string($conn,trim($_POST['delivery_by']));
  $sent_to_premium = mysqli_real_escape_string($conn,trim($_POST['sent_to_premium']));
  $received_by = mysqli_real_escape_string($conn,trim($_POST['received_by']));
  $condition_on_arrival=mysqli_real_escape_string($conn,trim(
    $_POST['condition_on_arrival']));
  $time = mysqli_real_escape_string($conn,trim($_POST['time']));
  $sent_to_neonatal_ward_ward_becauseo_of = mysqli_real_escape_string($conn,trim($_POST['sent_to_neonatal_ward_ward_becauseo_of']));


echo $baby_of;

$insert_infant_labour = "INSERT INTO tbl_infant_labour(baby_of,address,foetal_heart_rate_at_delivery,religion,tribe,
indication,ten_cell_leader,case_file_no,type_of_delivery,date_and_time_of_birth,sex,matenal_disease_and_contraction,weight_at_birth,apga_score,
maturity_by_dates,membrane_rupture_for,type_of_amnionic_fluid,weight_of_placenta,resucitation_method,delivery_by,sent_to_premium,received_by,
condition_on_arrival,time,sent_to_neonatal_ward_ward_becauseo_of)  VALUES('$baby_of','$address','$foetal_heart_rate_at_delivery','$religion',
  '$tribe','$indication','$ten_cell_leader','$case_file_no','$type_of_delivery','$date_and_time_of_birth','$sex','$matenal_disease_and_contraction',
  '$weight_of_placenta','$apga_score','$maturity_by_dates',
  '$membrane_rupture_for','$type_of_amnionic_fluid','$weight_of_placenta',
  '$resucitation_method','$delivery_by','$sent_to_premium','$received_by',
  '$condition_on_arrival','$time','$sent_to_neonatal_ward_ward_becauseo_of')";


if ($result  = mysqli_query($conn,$insert_infant_labour)) {
  echo "data saved";
}else {
  echo mysqli_error($conn);
}

}
 ?>
