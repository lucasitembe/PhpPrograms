<?php
$membranes_rapture = "";
$fetal_mvt = "";
$addmittedward = "";
$addmittedhome = "";
$admitted_from = "";
$sql = "SELECT * FROM tbl_labour WHERE patient_ID = '$pn'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
}


if ($membranes_rapture == "Yes") {
    $membrane_rapture_yes = "checked";
} else if ($membranes_rapture == "No") {
    $membrane_rapture_no = "checked";
}

if ($admitted_from == "ward") {
    $addmittedward = "checked";
} else if ($admitted_from == "Home") {
    $addmittedhome = "checked";
}


if ($fetal_mvt == "Present") {
    $fetal_mvt_present = "checked";
} else if ($fetal_mvt == "Absent") {
    $fetal_mvt_absent = "checked";
}
?>