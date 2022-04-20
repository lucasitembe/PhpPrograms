<?php
include("./includes/connection.php");

if (isset($_POST['save_to_labour'])) {
$patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
$admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
$Employee_Name = mysqli_real_escape_string($conn,trim($_POST['Employee_Name']));
$year_of_birth = mysqli_real_escape_string($conn,trim($_POST['year_of_birth']));
$date_and_time = mysqli_real_escape_string($conn,trim($_POST['date_and_time']));
$matunity = mysqli_real_escape_string($conn,trim($_POST['matunity']));
$gender = mysqli_real_escape_string($conn,trim($_POST['gender']));
$history_of_pregnancy = mysqli_real_escape_string($conn,trim($_POST['history_of_pregnancy']));
$mode_of_delivery = mysqli_real_escape_string($conn,trim($_POST['mode_of_delivery']));
$birth_weight = mysqli_real_escape_string($conn,trim($_POST['birth_weight']));
$place_of_birth = mysqli_real_escape_string($conn,trim($_POST['place_of_birth']));
$breast_fed_duration = mysqli_real_escape_string($conn,trim($_POST['breast_fed_duration']));
$peuperium = mysqli_real_escape_string($conn,trim($_POST['peuperium']));
$present_child_condition = mysqli_real_escape_string($conn,trim($_POST['present_child_condition']));

$sql_history_data = "INSERT INTO tbl_child_labour_history(
                      Registration_ID,admission_id,year_of_birth,Employee_Name,date_and_time,matunity,
                      gender,history_of_pregnancy,mode_of_delivery,birth_weight,place_of_birth,breast_fed_duration,peuperium,
                      present_child_condition
                    )VALUES('$patient_id','$admission_id','$year_of_birth','$Employee_Name','$date_and_time',
                    '$matunity','$gender','$history_of_pregnancy','$mode_of_delivery','$birth_weight','$place_of_birth','$breast_fed_duration',
                    '$peuperium','$present_child_condition')";

$save_history = mysqli_query($conn,$sql_history_data);

if ($save_history) {
  echo "Labour history Added successfully!";
}
else {
  echo "Failed to save data".mysqli_error($conn);

  mysqli_close($conn);
}

}


 ?>
