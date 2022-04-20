<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];


  $select_medicine = "SELECT tm.medicine_time,tm.med_short_name,tm.actual_time,ti.Product_Name FROM tbl_medicine tm JOIN tbl_items ti ON tm.med_id=ti.item_ID WHERE patient_id='$patient_id' ";


  $data = array();
  $response = array();

  echo "<table width='98%'>
        <tr><th style='height:30px;''>Time</th>
        <th style='height:25px;'>Medicine Short Name</th>
        <th>Medicine Name</th><th style='height:25px;'>Actual Time</th>
        <th style='height:25px;'>Prepared By</th></tr><tr>";

  if ($result = mysqli_query($conn,$select_medicine)) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr style='height:30px;'>
      <td style='padding-left:5px'>". $row['medicine_time']."</td>
      <td style='padding-left:5px'>".$row['med_short_name']."</td>
      <td style='padding-left:5px'>".$row['actual_time']."</td>
      <td style='padding-left:5px'>".$row['Product_Name']."</td></tr>";

    }
    echo "</table>";
  }else {
    echo mysqli_error($conn);
  }
}else {
  echo "data are not set";
}

 ?>
