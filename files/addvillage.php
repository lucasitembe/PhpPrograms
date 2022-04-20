<?php

use function PHPSTORM_META\type;

include("./includes/connection.php");
$data = array();


if (isset($_POST['wardName'])) {
    $wardName = mysqli_real_escape_string($conn, trim($_POST['wardName']));
    $district_id = mysqli_real_escape_string($conn, trim($_POST['district']));

    $check_if_ward_already_added_result = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE Ward_Name='$wardName'") or die(mysqli_error($conn));
    if (mysqli_num_rows($check_if_ward_already_added_result) <= 0) {
        $sql = "INSERT INTO tbl_ward(Ward_Name,District_ID)
         VALUES('$wardName', '$district_id')";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $id = mysqli_insert_id($conn);


        if ($result) {
            $data['Ward_ID'] = $id;
            $data['Ward_Name'] = $wardName;
            $data['result'] = 'ok';
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $data['result'] = 'Duplicate';
    }
    echo json_encode($data);
}
// if (isset($_POST['wardName'])) {
//     $wardName = mysqli_real_escape_string($conn, trim($_POST['wardName']));
//     $district = mysqli_real_escape_string($conn, trim($_POST['district']));
//     $check_if_ward_already_added_result = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE Ward_Name='$wardName'") or die(mysqli_error($conn));
//     if (mysqli_num_rows($check_if_ward_already_added_result) <= 0) {
//         $sql = "INSERT INTO tbl_ward(Ward_Name,District_ID)
//          VALUES('$wardName',(SELECT District_ID FROM tbl_district WHERE District_Name = '$district'))";
//         $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
//         $id = mysqli_insert_id($conn);


//         if ($result) {
//             $data['Ward_ID'] = $id;
//             $data['Ward_Name'] = $wardName;
//             $data['result'] = 'ok';
//         } else {
//             echo mysqli_error($conn);
//         }
//     } else {
//         $data['result'] = 'Duplicate';
//     }
//     echo json_encode($data);
// }

if (isset($_GET['villageName'])) {
    $villageName = mysqli_real_escape_string($conn, trim($_GET['villageName']));
    $Ward_ID = mysqli_real_escape_string($conn, trim($_GET['Ward_ID']));
    // die( "INSERT INTO tbl_village(village_name,Ward_ID)
    // VALUES('$villageName','$Ward_ID')");
    $sql = "INSERT INTO tbl_village(village_name,Ward_ID)
         VALUES('$villageName', '$Ward_ID')";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $id = mysqli_insert_id($conn);
    // die($id);
    // echo $id;

    if ($result) {
        $data['village_id'] = $id;
        $data['village_name'] = $villageName;

        $data_results = json_encode($data);
        echo $data_results;
    } else {
        echo mysqli_error($conn);
    }
}

