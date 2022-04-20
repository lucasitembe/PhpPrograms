<?php
include("./includes/connection.php");
$village = "";
if (isset($_GET['village'])) {
    $village = $_GET['village'];
} else {
    $village = 0;
}

$data = "";

$checkvillage = "SELECT village_id FROM tbl_village WHERE village_name='$village'";
$resultCheckvilage = mysqli_query($conn, $checkvillage) or die(mysqli_error($conn));

$village_id = mysqli_fetch_assoc($resultCheckvilage)['village_id'];
// die($village_id);

$Select_village = "select * from tbl_leaders where date_deactivated is null and village_id='$village_id'";
$result = mysqli_query($conn, $Select_village) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    $data .= "<option selected value=''>Select Leader</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $leader_id = $row['leader_ID'];
        $data .= "<option value='$leader_id'>" . $row['leader_name'] . "</option>";
    }
} else {
    $data .= "<option selected value=''>Select Leader</option>";
}

echo  $data;
