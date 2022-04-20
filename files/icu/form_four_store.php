<?php
session_start();
include 'repository.php';
date_default_timezone_set("Africa/Nairobi");
ini_set('display_errors', true);

if (isset($_POST['form_type']) && $_POST['form_type'] === 'form_four') {
    $date = date('Y-m-d h:i:s');

    $data_uri = $_POST['anatomical_position_drawing'];

    $encoded_image = explode(",", $data_uri)[1];
    $decoded_image = base64_decode($encoded_image);

    $filename = $date . $_POST['Registration_ID'] . '.png';

    file_put_contents("./anatomical-positions-drawings/" . $filename, $decoded_image);

    $data = array(
        array(
            'registration_id' => $_POST['Registration_ID'],
            'consultation_id' => $_POST['consultation_ID'],
            'employee_id' => $_POST['employee_id'],
            'labels' => $_POST['labels'],
            'form_inputs' => $_POST['form_inputs'],
            'anatomical_position_drawing' => $filename,
            'comments' => $_POST['comments'],
            'created_at' => $date
        )
    );

    $receive_data = json_encode($data);
    function save_assesment_record($receive_data)
    {
        $json_data = json_encode(array("table" => "tbl_icu_form_four", "data" => json_decode($receive_data, true)
        ));
        return $json_data;
    }

    query_insert(save_assesment_record($receive_data));
    echo 'Done';
}

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, 'wb');
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    return $output_file;
};