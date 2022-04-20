<?php

require "middleware.php";

function fetch_Patient_Data($receive_data){
    return query_select($receive_data);
}

function save_type_of_burn($receive_data){
    $json_data=json_encode(array("table"=>"tbl_burn_type",
                "data"=>json_decode($receive_data,true) 
        ));
    return query_insert($json_data);
}

function save_classfication_of_burn($receive_data){
    $json_data=json_decode(array("table"=>"tbl_burn_classfication",
    "data"=>json_decode($receive_data,true)
    ));

    //return query_insert($json_data);
    return print_r($json_data."===");
}

function save_patient_nurse_assessment($receive_data){
    $json_data=json_encode(array("table"=>"tbl_nurse_assessment",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

 function save_burn_assessment_info($receive_data){
        $json_data=json_encode(array("table"=>"tbl_assessment_information",
        "data"=>json_decode($receive_data,true)
    ));
    return query_insert($json_data);
 }

 function save_tumorboard_registration($receive_data){
    $json_data=json_encode(array("table"=>"tbl_tumorbord_registration",
        "data"=>json_decode($receive_data,true)
    ));
    return query_insert($json_data);
 }