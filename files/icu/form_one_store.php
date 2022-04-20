<?php
ini_set('display_errors', true);
session_start();
include 'repository.php';
date_default_timezone_set("Africa/Nairobi");

if (isset($_POST['store']) && $_POST['store'] === 'form-one') {
    $name = $_POST['name'];
    $hour = $_POST['hour'];
    $value = $_POST['value'];
    $form_id = $_POST['form_id'];
    $employee_id = $_SESSION['userinfo']['Employee_ID'];

    $date = date('Y-m-d H:i:s');

    // check if records exists
    $query = "SELECT id FROM tbl_icu_form_one_records 
                WHERE record_id = '$form_id' AND name = '$name' AND hour = '$hour' 
                ORDER BY id DESC";

    $result = querySelectOne($query);

    if ($result){
        // update
        echo 'updating';
        $id = $result['id'];
        $query = "UPDATE `tbl_icu_form_one_records` SET `value` = '$value' 
                    WHERE `id` = '$id';";

        $id = queryInsertOne($query);
    } else {
        // insert
        echo 'inserting';
        $query = "INSERT INTO tbl_icu_form_one_records (record_id, employee_id, name, hour, value, created_at) 
                    VALUES ('$form_id', '$employee_id', '$name', '$hour', '$value', '$date')";

        $id = queryInsertOne($query);
    }

    return $id;
}

?>