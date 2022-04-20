<?php
ini_set('display_errors', true);
include 'repository.php';

function getPreviousData($Registration_ID, $consultation_ID){
    $query = "SELECT labels, form_inputs FROM tbl_icu_form_four 
                WHERE consultation_id = '$consultation_ID' 
                AND registration_id = '$Registration_ID' ORDER BY created_at DESC, id DESC LIMIT 1;";

    $results = querySelectOne($query);

    return getCombined($results);
}

function getData($Registration_ID, $consultation_ID, $from, $to){
    $filter = "";
    if ($from && $to){
        $filter .= "AND created_at BETWEEN '$from' AND '$to'";
    }

    $query = "SELECT labels, form_inputs, created_at FROM tbl_icu_form_four 
                WHERE consultation_id = '$consultation_ID' 
                AND registration_id = '$Registration_ID' $filter ORDER BY created_at DESC, id DESC;";

    $results = querySelect($query);

    $decoded = [];

    foreach ($results as $result) {

        $combined = getCombined($result);

        $combined['created_at'] = $result['created_at'];

        array_push($decoded, $combined);
    }

    return $decoded;
}

/**
 * @param $result
 * @return array
 */
function getCombined($result)
{
    $labels = isset($result['labels']) ? json_decode($result['labels']) : [];
    $values = isset($result['form_inputs']) ? json_decode($result['form_inputs']) : [];

    $combined = [];
    for ($i = 0; $i < count($labels); $i++) {
        // dont set if empty
        if ($values[$i]) {
            $combined[$labels[$i]] = $values[$i];
        }
    }
    return $combined;
}
