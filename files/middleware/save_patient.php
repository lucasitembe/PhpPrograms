<?php 
    require "middleware.php";
    $receive_data=file_get_contents('php://input');
    $json_data=json_encode(array("table"=>"tbl_patient_registration",
                "data"=>json_decode($receive_data,true)
        ));
    print_r(query_insert($json_data));
?>