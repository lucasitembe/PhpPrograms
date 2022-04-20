<?php
ini_set('display_errors', true);
include 'repository.php';

function getDiagnosis($consultation_ID){
    $query = "SELECT * FROM tbl_ward_round_disease wd, tbl_disease d, tbl_ward_round wr 
                WHERE wd.Round_ID=wr.Round_ID
                  AND wr.consultation_ID = $consultation_ID 
                  AND wd.disease_ID = d.disease_ID 
                GROUP BY disease_name";

    $results = querySelect($query);

    $provisional_diagnosis = '';
    $final_diagnosis = '';
    $differential_diagnosis = '';

    if (count($results) > 0) {
        foreach ($results as $result){
            if ($result['diagnosis_type'] == 'provisional_diagnosis') {
                $provisional_diagnosis .= ' ' . $result['disease_name'] . ';';
            }
            if ($result['diagnosis_type'] == 'diferential_diagnosis') {
                $differential_diagnosis .= ' ' . $result['disease_name'] . ';';
            }
            if ($result['diagnosis_type'] == 'diagnosis') {
                $final_diagnosis .= ' ' . $result['disease_name'] . ';';
            }
        }
    }

    return compact('differential_diagnosis', 'provisional_diagnosis', 'final_diagnosis');
}

function getFormDetails($consultation_ID, $Registration_ID, $Admission_ID){
    // Form ID and previous data
    $query = "SELECT id FROM tbl_icu_form_one WHERE consultation_id = $consultation_ID AND date(record_date) = CURDATE()";

    $result = querySelectOne($query);

    if ($result){
        $id = $result['id'];
        $data = getFormData($id);
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT into tbl_icu_form_one (registration_id, consultation_id, admission_id, record_date, created_at)
                        VALUES ('$Registration_ID', '$consultation_ID', '$Admission_ID', '$date', '$date')";

        $id = queryInsertOne($query);
        $data = [];
    }

    $previousFormId = getBoundingForms($Registration_ID, $consultation_ID)['previousId'];
    $transferReasons = getTransferReasons($Registration_ID, $Admission_ID);
    $formattedPMH = getPMH($consultation_ID);
    $remarks = getRemarks($consultation_ID);

    return compact('id', 'data', 'remarks', 'transferReasons', 'formattedPMH', 'previousFormId');
}

function getFormData($formId){
    $query = "SELECT name, hour, value from tbl_icu_form_one_records WHERE record_id = $formId";

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

function getPMH($consultation_ID){
    // Past Medical History (Outpatient)
    $PMHQuery = "SELECT past_medical_history, employee_ID FROM tbl_consultation_history  c WHERE c.consultation_ID = $consultation_ID";

    $PMHResults = querySelect($PMHQuery);

    $formattedPMH = '';
    if (count($PMHResults)){
        foreach ($PMHResults as $result){
            // Skip for empty PMH's
            if (!$result['past_medical_history']){
                continue;
            }

            // Query employee name
            $name = querySelectOne("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = {$result['employee_ID']}")['Employee_Name'];

            $formattedPMH .= "{$result['past_medical_history']}; ";
//            array_push($formattedPMH, ['name' => $name, 'pmh' => $result['past_medical_history']]);
        }
    }

    return $formattedPMH;
}

function getRemarks($consultation_ID){
    // Remarks
    $remarksQuery = "SELECT remarks FROM tbl_ward_round WHERE consultation_ID = $consultation_ID
                                     AND Process_Status='served' ORDER BY Round_ID DESC";
    $remarks = querySelectOne($remarksQuery);
    return isset($remarks['remarks']) ? $remarks['remarks'] : '';
}

function getTransferReasons($Registration_ID, $Admission_ID){
    $icuWardId = getICUWardId();

    $wardTransferQuery = "SELECT reasson_for_tranfer from tbl_patient_transfer_details where Registration_ID = $Registration_ID
                            AND Admision_ID = $Admission_ID AND Hospital_Ward_ID = $icuWardId ORDER BY date(Received_Date) DESC";
    $transferReasons = querySelectOne($wardTransferQuery);
    return isset($transferReasons['reasson_for_tranfer']) ? $transferReasons['reasson_for_tranfer'] : '';
}

function getBoundingForms($Registration_ID, $consultation_ID, $curdate = ''){
    if (!$curdate){
        $curdate = date("Y/m/d H:i:s");
    }

    $previousFormQuery = "SELECT id FROM tbl_icu_form_one 
                WHERE registration_id = '$Registration_ID' 
                AND consultation_id = $consultation_ID 
                AND record_date < '$curdate'
                ORDER BY record_date DESC, id DESC LIMIT 1";

    $nextFormQuery = "SELECT id FROM tbl_icu_form_one 
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

function getFormUsers($recordId){
    $query = "SELECT employee_id, MAX(created_at) as created_at
                FROM tbl_icu_form_one_records 
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

function getICUWardId(){
    $icuWardId = querySelectOne("SELECT Hospital_Ward_ID FROM tbl_hospital_ward WHERE ward_type = 'ordinary_ward'");

    if (isset($icuWardId['Hospital_Ward_ID'])){
        return $icuWardId['Hospital_Ward_ID'];
    } else {
        print "<b class='text-danger py-5 text-center bg-white my-2 d-block'>Please Set ICU Ward First.</b>";
        exit();
    }
}

//    Additional helper functions
function perform_counts($id, $class = '')
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $index = 7;
    $class .= ' form-control px-1 text-center tbl-input';

    for ($i = 0; $i < 24; $i++) {
        $value = isset($row[$index]) ? $row[$index] : '';

        echo "<td><input value='$value' class='$id $class' type='text' id='{$id}-{$index}' /></td>";

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

