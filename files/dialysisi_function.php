<?php

require "middleware.php";
function fetch_Patient_Data($receive_data){
    return query_select($receive_data);
}

function Save_Machine_Data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_save_machine_access",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Vital_Data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_vitals",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Heparain_Data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_heparain_save",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Access_Data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_access_orders",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Data_Collection($receive_data){
    $json_data=json_encode(array("table"=>"tbl_data_collection",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Observation_Chart($receive_data){
    $json_data=json_encode(array("table"=>"tbl_observation_chart",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Medication_Chart($receive_data){
    $json_data=json_encode(array("table"=>"tbl_medication_chart",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Dialysis_oder($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_oder",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_dialysis_Doctor_Notes($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_doctor_notes",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_incident_data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_incident_records",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}


function Save_temporary_neck_line_data($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_temporary_neck_line",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_patient_result_summary($receive_data){
    $json_data=json_encode(array("table"=>"tbl_patient_result_summary",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_monthly_unity_meeting($receive_data){
    $json_data=json_encode(array("table"=>"tbl_monthly_unity_meeting",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}

function Save_Doctors_Oder($receive_data){
    $json_data=json_encode(array("table"=>"tbl_dialysis_inpatient_prescriptions",
                "data"=>json_decode($receive_data,true)
        ));
    return query_insert($json_data);
}
?>