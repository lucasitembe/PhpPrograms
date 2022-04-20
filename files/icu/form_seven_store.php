<?php
session_start();
include 'repository.php';
date_default_timezone_set("Africa/Nairobi");

if (isset($_POST['store']) && $_POST['store'] === 'form-seven') {
    $name = clean($_POST['name']);
    $shift = clean($_POST['shift']);
    $value = clean($_POST['value']);
    $form_id = clean($_POST['form_id']);
    $employee_id = $_SESSION['userinfo']['Employee_ID'];

    $date = date('Y-m-d H:i:s');

    // check if records exists
    $query = "SELECT id FROM tbl_icu_form_seven_records 
                WHERE record_id = '$form_id' AND name = '$name' AND shift = '$shift' 
                ORDER BY id DESC";

    $result = querySelectOne($query);

    if ($result){
        // update
        echo 'updating';
        $id = $result['id'];
        $query = "UPDATE `tbl_icu_form_seven_records` SET `value` = '$value' 
                    WHERE `id` = '$id';";

        $id = queryInsertOne($query);
    } else {
        // insert
        echo 'inserting';
        $query = "INSERT INTO tbl_icu_form_seven_records (record_id, employee_id, name, shift, value, created_at) 
                    VALUES ('$form_id', '$employee_id', '$name', '$shift', '$value', '$date')";

        $id = queryInsertOne($query);
    }

    return $id;
}

// Retrieve form data
if (isset($_GET['retrieve']) && $_GET['retrieve'] === 'form-seven') {
    $shift = clean($_GET['shift']);
    $form_id = clean($_GET['form_id']);

    $date = date('Y-m-d H:i:s');

    $query = "SELECT * FROM tbl_icu_form_seven_records WHERE record_id = '$form_id' AND shift = '$shift'";
    $results = querySelect($query);

    $data = [];
    if (count($results)){
        foreach ($results as $datum){
            $data[$datum['name']] = ['name' => $datum['name'], 'value' => $datum['value']];
        }
    }

    header('Content-Type: application/json');
    print json_encode(compact('data'), JSON_PRETTY_PRINT);

}