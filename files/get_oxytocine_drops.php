<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_contraction = "SELECT * FROM tbl_oxytocine_drops WHERE patient_id = '$patient_id' ORDER BY oxytocine_time";

  $data = array();

  echo "<table width='100%;'>";
  echo "<tr>
  <th style='text-align:center; height:35px; font-weight:bold;'>Oxytocine</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Drops</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Time</th>
  <th style='text-align:center; height:35px; font-weight:bold;'>Actual Time</th>
  </tr>";
  if ($result = mysqli_query($conn,$select_contraction)) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr style='height:40px;'><td style='text-align:center'>".$row['oxytocine']."</td>
      <td style='text-align:center'>".$row['drops']."</td>
      <td style='text-align:center'>".$row['oxytocine_time']."</td>
      <td style='text-align:center'>".$row['actual_time']."</td>
      </tr>";

    }
    echo "</table>";
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
