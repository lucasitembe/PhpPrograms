<?php
include("./includes/connection.php");


//   "SELECT item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='Pharmacy'"
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
    $patient_id = $_POST['patient_id'];
    $admision_id = $_POST['admission_id'];
  


    $select_mould_liqour = "SELECT med.medicine_id, med.patient_id, med.admission_id,med.medicine_time, med.med_short_name, med.actual_time, med.med_id,it.Product_Name FROM tbl_medicine as med,tbl_items as it WHERE patient_id = '$patient_id' AND admission_id='$admision_id' AND med.med_id=it.item_ID ORDER BY med.medicine_time";
    $response = array();
    $data = array();
  
  
    if ($result = mysqli_query($conn,$select_mould_liqour)) {
      while ($row = mysqli_fetch_assoc($result)) {
      $data['Product_Name'] =$row['Product_Name'];
      $data['medicine_time'] = $row['medicine_time'];
      $data['med_short_name'] = $row['Product_Name'];
      array_push($response,$data);
      }
      echo json_encode($response);
    }else {
      echo mysqli_error($conn);
    }
  
  }
 ?>
