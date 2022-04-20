<?php
ini_set('display_errors', true);
include 'repository.php';

function getFormDetails($consultation_ID, $Registration_ID, $Admission_ID){
    $consultation_ID = clean($consultation_ID);
    $Registration_ID = clean($Registration_ID);
    $Admission_ID = clean($Admission_ID);

    // Form ID and previous data
    $query = "SELECT id FROM tbl_icu_form_two WHERE consultation_id = $consultation_ID AND date(record_date) = CURDATE()";

    $result = querySelectOne($query);

    if ($result){
        $id = $result['id'];
        $data = getFormData($id);
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT into tbl_icu_form_two (registration_id, consultation_id, admission_id, record_date, created_at)
                        VALUES ('$Registration_ID', '$consultation_ID', '$Admission_ID', '$date', '$date')";

        $id = queryInsertOne($query);
        $data = [];
    }

    $previousFormId = getBoundingForms($Registration_ID, $consultation_ID)['previousId'];

    return compact('id', 'data', 'previousFormId');
}

function getFormData($formId){
    $formId = clean($formId);

    $query = "SELECT name, hour, value from tbl_icu_form_two_records WHERE record_id = $formId";

    $results = querySelect($query);

    $formatted = [];
    foreach ($results as $result){
        $formatted[$result['name']] = array_replace(
            isset($formatted[$result['name']]) ? $formatted[$result['name']] : [],
            [$result['hour'] => $result['value']]
        );
    }

    return $formatted;
}

function getInfusionNames($formId){
    $formId = clean($formId);
    // infusion names
    $query = "SELECT name, hour, value from tbl_icu_form_two_records WHERE record_id = $formId AND name = 'infusion-name'";

    $results = querySelect($query);

    $formatted = [];
    foreach ($results as $result){
        array_push($formatted, ['index' => $result['hour'], 'name' => $result['value']]);
    }

    return $formatted;
}

function getInfusionData($recordId){
    $recordId = clean($recordId);

    $infusionNames = getInfusionNames($recordId);

    $infusionData = [];

    foreach ($infusionNames as $infusion){
        $index = $infusion['index'];

        $query = "SELECT name, hour, value FROM tbl_icu_form_two_records WHERE record_id = '$recordId' AND name = 'infusion-input-{$index}'";

        $results = querySelect($query);

        $formatted = [];
        foreach ($results as $result) {
            $formatted[$result['hour']] = $result['value'];
        }

        $infusion['data'] = $formatted;
        array_push($infusionData, $infusion);
    }

    return $infusionData;
}

function getFormUsers($recordId){
    $query = "SELECT employee_id, MAX(created_at) as created_at
                FROM tbl_icu_form_two_records 
                WHERE record_id = '$recordId' 
                GROUP BY employee_id";

    $results = querySelect($query);

    $users = [];

    foreach ($results as $user) {
        $employee = querySelectOne("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '{$user['employee_id']}' LIMIT 1");
        array_push($users, ['name' => $employee['Employee_Name'], 'date' => $user['created_at']]);
    }

    return $users;
}

function getBoundingForms($Registration_ID, $consultation_ID, $curdate = ''){
    $Registration_ID = clean($Registration_ID);
    $consultation_ID = clean($consultation_ID);
    $curdate = clean($curdate);

    if (!$curdate){
        $curdate = date("Y/m/d H:i:s");
    }

    $previousFormQuery = "SELECT id FROM tbl_icu_form_two 
                WHERE registration_id = '$Registration_ID' 
                AND consultation_id = $consultation_ID 
                AND record_date < '$curdate'
                ORDER BY record_date DESC, id DESC LIMIT 1";

    $nextFormQuery = "SELECT id FROM tbl_icu_form_two 
                WHERE registration_id = '$Registration_ID' 
                AND consultation_id = $consultation_ID 
                AND record_date > '$curdate'
                ORDER BY record_date LIMIT 1";

    $previous = querySelectOne($previousFormQuery);
    $next = querySelectOne($nextFormQuery);

    $previousId = isset($previous['id']) ? $previous['id'] : '';
    $nextId = isset($next['id']) ? $next['id'] : '';

    return compact('previousId', 'nextId');
}

//    Additional helper functions
function generateFields($id, $class = '', $uniqueClass = '')
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $index = 7;
    $class .= ' form-control px-1 text-center tbl-input';

    for ($i = 0; $i < 24; $i++) {
        $value = isset($row[$index]) ? $row[$index] : '';

        // probably not needed
        $append = "";
        $uniqueClass ? $append = "$uniqueClass-$index" : '';

        echo "<td><input value='$value' class='$id $class $append' type='text' id='{$id}-{$index}' /></td>";

        $index === 23 ? $index = -1 : '';
        $index++;
    }
}

function loopData($id, $class = '')
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $index = 7;
    $class .= 'text-center tbl-input';

    for ($i = 0; $i < 24; $i++) {
        $value = isset($row[$index]) ? $row[$index] : '';

        echo "<td class='$id $class' id='{$id}-{$index}'>$value</td>";

        $index === 23 ? $index = -1 : '';
        $index++;
    }
}
