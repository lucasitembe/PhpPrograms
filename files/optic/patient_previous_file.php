<?php
include("../includes/connection.php");


if (isset($_POST['patient_id'])) {
    $patientId = $_POST['patient_id'];
}


$response = array();
$sql = "SELECT * FROM  tbl_spectacle_status WHERE patient_id='$patientId'";
$result = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($result)) {
    extract($row);

    array_push($response, array('patientId' => $patient_id, 'date' => $created_at));
}


echo json_encode($response);

?>