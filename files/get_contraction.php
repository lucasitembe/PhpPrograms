<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_contraction = "SELECT * FROM tbl_contraction WHERE patient_id = '$patient_id'";

  $data = array();

  echo "<center><table width='85%;'>";
  echo "<tr><th style='text-align:center; height:35px; font-weight:bold;'>Contraction</th><th style='text-align:center; height:35px; font-weight:bold;'>Time</th><th style='text-align:center; height:35px; font-weight:bold;'>Actual Time</th></tr>";
  if ($result = mysqli_query($conn,$select_contraction)) {
    while ($row = mysqli_fetch_assoc($result)) {
      if ($row['contraction'] > 2 && $row['contraction'] <=4) {
        $contraction_level = "40-60";
      }elseif ($row['contraction'] >=1 && $row['contraction'] <=2) {
        $contraction_level = "20-40";
      }
      elseif ($row['contraction'] >4 && $row['contraction'] <=6) {
        $contraction_level = "40-60";
      }
      echo "<tr style='height:40px;'><td style='text-align:center'>".$contraction_level."</td>
      <td style='text-align:center'>".$row['c_time']."</td><td style='text-align:center'>".$row['actual_time']."</td></tr>";
      // $data['contraction'] = $row['contraction'];
      // $data['time'] = $row['c_time'];
      // $data['actual_time'] = $row['actual_time'];

      // echo json_encode($data);
    }
    echo "</table></center>";
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
