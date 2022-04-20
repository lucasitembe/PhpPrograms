<?php
require "middleware.php";

function fetch_Patient_Data($receive_data){
    return query_select($receive_data);
}

function Save_Patient_Data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_patient_registration",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_finger_print($receive_data){
    $json_data=json_encode(array("table"=>"tbl_finger_print_details",
        "data"=>json_decode($receive_data,true)
    ));
    return query_insert($json_data);
}
?>