<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_contraction = "SELECT * FROM tbl_contraction WHERE patient_id = '$patient_id'  and admission_id='$admision_id'";

  $response = array();
  $data = array();
  if ($result = mysqli_query($conn,$select_contraction)) {
    while ($row = mysqli_fetch_assoc($result)) {


      $data['contraction'] = $row['contraction'];
      $data['time'] = $row['c_time'];
      $data['actual_time'] = $row['actual_time'];
    //   array_push($data,array('contraction' => $row['contraction'],
    //   'time' => $row['c_time'],'actual_time' => $row['actual_time']
    // ));
    array_push($response,$data);
    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
