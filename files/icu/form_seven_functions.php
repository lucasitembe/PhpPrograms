<?php
//ini_set('display_errors', true);
include 'repository.php';

function getFormDetails($consultation_ID, $Registration_ID, $Admission_ID){
    $consultation_ID = clean($consultation_ID);
    $Registration_ID = clean($Registration_ID);
    $Admission_ID = clean($Admission_ID);

    // Form ID and previous data
    $query = "SELECT id FROM tbl_icu_form_seven WHERE registration_id = '$Registration_ID' 
                                    AND consultation_id = $consultation_ID 
                                    AND date(record_date) = CURDATE()";

    $result = querySelectOne($query);

    if ($result){
        $id = $result['id'];
        $data = getFormData($id);
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT into tbl_icu_form_seven (registration_id, consultation_id, admission_id, record_date, created_at)
                        VALUES ('$Registration_ID', '$consultation_ID', '$Admission_ID', '$date', '$date')";

        $id = queryInsertOne($query);
        $data = [];
    }

    return compact('id', 'data');
}

function getFormData($formId){
    $formId = clean($formId);

    $query = "SELECT name, shift, value from tbl_icu_form_seven_records WHERE record_id = $formId";

    $results = querySelect($query);

    $formatted = [];
    foreach ($results as $result){
        $formatted[$result['name']] = array_replace(
            isset($formatted[$result['name']]) ? $formatted[$result['name']] : [],
            [$result['shift'] => $result['value']]
        );
    }

    return $formatted;
}

function getFormUsers($recordId){
    $query = "SELECT employee_id, MAX(created_at) as created_at
                FROM tbl_icu_form_seven_records 
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

// Additional helper functions
function generateFields($id)
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $hours = ['am', 'pm', 'night'];

    foreach ($hours as $hour){
        $value = isset($row[$hour]) ? $row[$hour] : '';
        echo "<td><input value='$value' id='{$id}-{$hour}' class='form-control form-input text-center' type='text'/></td>";
    }
}

function loopData($id)
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $hours = ['AM', 'PM', 'Night'];

    foreach ($hours as $hour){
        $value = isset($row[$hour]) ? $row[$hour] : '';
        echo "<td class='text-center'>$value</td>";
    }
}