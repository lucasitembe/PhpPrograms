<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id']) ) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];

  $select_cervical_points  = "SELECT fx,fy FROM tbl_progress_of_labour WHERE
                               patient_id='$patient_id' AND admission_id='$admission_id'";
                              $data  = array();

  if ($result=mysqli_query($conn,$select_cervical_points)) {

    if (($num = mysqli_num_rows($result)) > 0) {
      $d = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $d['fx'] = $row['fx'];
        $d['fy'] = $row['fy'];
        array_push($data,$d);
      }

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}

 ?>
