<?php
include("includes/connection.php");
$response = array();
$key_search = "";
if (isset($_GET['key_search'])) {
    $key_search = trim($_GET['key_search']);
    $sql = "SELECT * FROM `tbl_items` WHERE `Consultation_Type`='Laboratory' AND Product_Name LIKE '%$key_search%' LIMIT 15";
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        array_push($response, array("product_name" => $Product_Name, "Item_ID" => $Item_ID));
    }
    echo json_encode($response);
} else if ($key_search == "") {
    $sql = "SELECT * FROM `tbl_items` WHERE `Consultation_Type`='Laboratory' LIMIT 50";
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        array_push($response, array("product_name" => $Product_Name, "Item_ID" => $Item_ID));
    }
    echo json_encode($response);
}

?>