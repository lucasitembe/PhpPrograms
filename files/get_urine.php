<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT DISTINCT * FROM tbl_urine ORDER By urine_time ";


  $data = array();

  echo "<center><table width='85%;'>";
  echo "<tr>
  <th style='text-align:center; height:35px; font-weight:bold;'>Protein</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Acetone </th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Volume</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Time</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Actual Time</th>
  </tr>";
  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr style='height:40px;'><td style='text-align:center'>".$row['protein']."</td>
      <td style='text-align:center'>".$row['acetone']."</td>
      <td style='text-align:center'>".$row['volume']."</td>
      <td style='text-align:center'>".$row['urine_time']."</td>
      <td style='text-align:center'>".$row['actual_urine_time']."</td>
      </tr>";
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
