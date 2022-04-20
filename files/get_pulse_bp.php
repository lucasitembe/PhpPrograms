<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT DISTINCT pulse,b.bp_start,p.pulse_time,p.actual_pulse_time,b.actual_bp_time,b.bp_end FROM
  tbl_pulse p JOIN tbl_bp b ON
  p.patient_id=b.patient_id GROUP BY p.pulse_time ORDER By p.pulse_time ";


  $data = array();

  echo "<table width='100%;'>";
  echo "<tr><th style='text-align:center; height:35px; font-weight:bold;'>Pulse</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Bp Start </th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Bp End </th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Actual Time</th>
  </tr>";
  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr style='height:40px;'><td style='text-align:center'>".$row['pulse']."</td>
      <td style='text-align:center'>".$row['bp_start']."</td>
      <td style='text-align:center'>".$row['bp_end']."</td>
      <td style='text-align:center'>".$row['pulse_time']."</td></tr>";
      // $data['contraction'] = $row['contraction'];
      // $data['time'] = $row['c_time'];
      // $data['actual_time'] = $row['actual_time'];

      // echo json_encode($data);
    }
    echo "</table>";
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
