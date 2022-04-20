<?php
//ini_set('display_errors', true);
include 'repository.php';

function getFormDetails($consultation_ID, $Registration_ID, $Admission_ID){
    $consultation_ID = clean($consultation_ID);
    $Registration_ID = clean($Registration_ID);
    $Admission_ID = clean($Admission_ID);

    // Form ID and previous data
    $query = "SELECT id FROM tbl_icu_form_three WHERE registration_id = '$Registration_ID' 
                                    AND consultation_id = $consultation_ID 
                                    AND date(record_date) = CURDATE()";

    $result = querySelectOne($query);

    if ($result){
        $id = $result['id'];
        $data = getFormData($id);
        $metadata = getFormMetadata($id);
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT into tbl_icu_form_three (registration_id, consultation_id, admission_id, record_date, created_at)
                        VALUES ('$Registration_ID', '$consultation_ID', '$Admission_ID', '$date', '$date')";

        $id = queryInsertOne($query);
        $data = [];
        $metadata = [];
    }

//    $previousFormId = getBoundingForms($Registration_ID, $consultation_ID)['previousId'];
    $preDetails = getPreDetails($Registration_ID, $Admission_ID);

    return compact('id', 'data', 'metadata', 'preDetails');
}


function getFormData($formId){
    $formId = clean($formId);

    $query = "SELECT name, hour, value from tbl_icu_form_three_records WHERE record_id = $formId";

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

function getFormMetadata($formId){
    $formId = clean($formId);

    $query = "SELECT name, value from tbl_icu_form_three_metadata WHERE record_id = $formId";

    $results = querySelect($query);

    $formatted = [];
    foreach ($results as $result){
        $formatted[$result['name']] = $result['value'];
    }

    return $formatted;
}

function getPreDetailsFromFormId($formId){
    $query = "SELECT registration_id, admission_id FROM tbl_icu_form_three where id = '$formId' LIMIT 1";

    $result = querySelectOne($query);

    return getPreDetails($result['registration_id'], $result['admission_id']);
}

function getPreDetails($Registration_ID, $Admission_ID){
    $icuWardId = getICUWardId();

    $wardTransferDetailsQuery = "SELECT Received_Date, Hospital_Ward_Name FROM tbl_patient_transfer_details ptd, tbl_hospital_ward hw WHERE Registration_ID = $Registration_ID
                            AND Admision_ID = $Admission_ID AND ptd.Hospital_Ward_ID = hw.Hospital_Ward_ID AND ptd.Hospital_Ward_ID = $icuWardId ORDER BY date(Received_Date) DESC LIMIT 1";

    $wardTransferDetails = querySelectOne($wardTransferDetailsQuery);
    $transferDate = isset($wardTransferDetails['Received_Date']) ? $wardTransferDetails['Received_Date'] : '';
    $transferWard = isset($wardTransferDetails['Hospital_Ward_Name']) ? $wardTransferDetails['Hospital_Ward_Name'] : '';

    $admissionDetailsQuery = "SELECT Kin_Name, Kin_Relationship, Kin_Phone, Admission_Date_Time, Hospital_Ward_Name FROM tbl_admission ad, tbl_hospital_ward hw WHERE ad.Hospital_Ward_ID = hw.Hospital_Ward_ID AND Admision_ID = '$Admission_ID LIMIT 1'";

    $admissionDetails = querySelectOne($admissionDetailsQuery);

    $kinName = isset($admissionDetails['Kin_Name']) ? $admissionDetails['Kin_Name'] : '';
    $kinRelation = isset($admissionDetails['Kin_Relationship']) ? $admissionDetails['Kin_Relationship'] : '';
    $kinPhone = isset($admissionDetails['Kin_Phone']) ? $admissionDetails['Kin_Phone'] : '';
    $admissionDate = isset($admissionDetails['Admission_Date_Time']) ? $admissionDetails['Admission_Date_Time'] : '';
    $wardName = isset($admissionDetails['Hospital_Ward_Name']) ? $admissionDetails['Hospital_Ward_Name'] : '';

    if ($transferDate){
        // Take data from ward transfer details
        $primaryAttending = $transferWard;
        $daysInUnits = getDifferenceInDays($transferDate);
        $doa = $transferDate;
    } else {
        // Take data from admission
        $primaryAttending = $wardName;
        $daysInUnits = getDifferenceInDays($admissionDate);
        $doa = $admissionDate;
    }

    return compact('kinName', 'kinRelation', 'kinPhone', 'primaryAttending', 'daysInUnits', 'doa');
}

function getFormUsers($recordId){
    $query = "SELECT employee_id, MAX(created_at) as created_at
                FROM tbl_icu_form_three_records 
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

function getDifferenceInDays($date1, $date2 = ''){
    if (!$date2){
        $date2 = time();
    }

    $dateDiff = strtotime($date1) - $date2;

    return abs(round($dateDiff / (60 * 60 * 24)));
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

// Additional helper functions
function generateFields($id, $class = '', $uniqueClass = '', $attributes = '')
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $index = 7;

    for ($i = 0; $i < 24; $i++) {
        $value = isset($row[$index]) ? $row[$index] : '';

        // probably not needed
        $append = "";
        $uniqueClass ? $append = "$uniqueClass-$index" : '';

        echo "<td><input value='$value' id='{$id}-{$index}' class='$class $class-$index $append form-control px-1 text-center tbl-input' $attributes type='text'/></td>";

        $index === 23 ? $index = -1 : '';
        $index++;
    }
}


function loopData($id, $class = '')
{
    global $data;
    $row = isset($data[$id]) ? $data[$id] : [];

    $index = 7;
    $class .= ' text-center tbl-input';

    for ($i = 0; $i < 24; $i++) {
        $value = isset($row[$index]) ? $row[$index] : '';

        echo "<td class='$id $class' id='{$id}-{$index}'>$value</td>";

        $index === 23 ? $index = -1 : '';
        $index++;
    }
}

// Functions for repeating rows, i.e infusion, blood inputs & ng-oral
function getRowsNames($formId, $name){
    $formId = clean($formId);
    // infusion names
    $query = "SELECT name, hour, value from tbl_icu_form_three_records WHERE record_id = $formId AND name = '$name'";

    $results = querySelect($query);

    $formatted = [];
    foreach ($results as $result){
        array_push($formatted, ['index' => $result['hour'], 'name' => $result['value']]);
    }

    return $formatted;
}

function getRows($recordId, $name){
    $recordId = clean($recordId);

    $infusionNames = getRowsNames($recordId, $name);

    $infusionData = [];

    foreach ($infusionNames as $infusion){
        $index = $infusion['index'];

        $query = "SELECT name, hour, value FROM tbl_icu_form_three_records WHERE record_id = '$recordId' AND name = '{$name}-{$index}'";

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