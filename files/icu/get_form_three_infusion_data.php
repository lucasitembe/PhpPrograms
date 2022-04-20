<?php
include('../includes/connection.php');
if (isset($_POST['registration_id'])) {
  $registration_id = $_POST['registration_id'];
  $select_medication = "SELECT * FROM tbl_icu_formthree_infusion WHERE patient_id='$registration_id' AND DATE(date_time) = CURDATE()";

  $data = array();
  $response = array();
  if($result = mysqli_query($conn,$select_medication)) {
    while($row=mysqli_fetch_assoc($result)) {
        $data['medic_id'] = $row['medic_id'];
        $data['medication'] = $row['medication'];
        array_push($response,$data);
    }


    echo  json_encode($response);
  }else {
    echo  mysqli_error($conn);
  }

}
// function getMedication($registration_id)

// }
?>
