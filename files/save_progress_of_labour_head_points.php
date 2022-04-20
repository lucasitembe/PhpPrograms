<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$time=date("H:i");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  // echo $patient_id;
 
  @$sx = $_POST['sx'];  
  @$sy = $_POST['sy'];

$datay = array();

if (!empty($sx) && !empty($sy)) {
  $insert_progress_of_labour = "INSERT INTO tbl_head_circumference(patient_id,admission_id,sx,sy,date_time,time_hours,Employee_ID)

  VALUES('$patient_id','$admission_id','$sx','$sy',NOW(),'$time','$Employee_ID')";

  if ($result = mysqli_query($conn,$insert_progress_of_labour)) {
    array_push($datay,$sx,$sy);

    echo json_encode($datay);

}else {
  echo mysqli_error($conn);
}


}
}

 ?>
