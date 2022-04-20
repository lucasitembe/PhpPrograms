<?php
include('../includes/connection.php');


if (isset($_POST['patient_no']) ){
    $ward = mysqli_real_escape_string($conn,trim($_POST['ward']));
    $name = mysqli_real_escape_string($conn,trim($_POST['name']));
    $address = mysqli_real_escape_string($conn,trim($_POST['address']));
    $age= mysqli_real_escape_string($conn,trim($_POST['age']));
    $sex = mysqli_real_escape_string($conn,trim($_POST['sex']));
    $doctor = mysqli_real_escape_string($conn,trim($_POST['doctor']));
    $pedistrician = mysqli_real_escape_string($conn,trim($_POST['pedistrician']));
    $surgeon = mysqli_real_escape_string($conn,trim($_POST['surgeon']));
    $anaethesiologist =
    mysqli_real_escape_string($conn,trim($_POST['anaethesiologist']));
    $date_and_time_of_birth = mysqli_real_escape_string($conn,trim($_POST['date_and_time_of_birth']));
    $date_of_discharge = mysqli_real_escape_string($conn,trim($_POST['date_of_discharge']));
    $patient_number = mysqli_real_escape_string($conn,trim($_POST['patient_no']));

    // echo $sex;

    $insert_nenonata = "INSERT INTO tbl_neonatal_record(ward,name,address,age,sex,doctor,
      pedistrician,surgeon,anaethesiologist,date_and_time_of_birth,
      date_of_discharge,patient_number)  VALUES('$ward','$name','$address','$age','$sex',
        '$doctor','$pedistrician','$surgeon','$anaethesiologist',
        '$date_and_time_of_birth','$date_of_discharge','$patient_number')";

      if ($result = mysqli_query($conn,$insert_nenonata)) {
        echo "data saved";
      }else {
        echo mysqli_error($conn);
      }
}else {
  echo "Data do nto reach";
}
 ?>
