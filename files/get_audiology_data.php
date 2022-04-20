<?php

function get_tympanomerty($conn, $Registration_ID, $Payment_Item_Cache_List_ID)
{
    $select_tympanometry = "SELECT jerger_type_right, admittance_right, pressure_right, width_right, volume_right, jerger_type_left, admittance_left, pressure_left, width_left, volume_left
                            FROM tbl_tympanometry 
                            WHERE registration_id=? AND payment_item_cache_list_id=?";

    $stmt = mysqli_prepare($conn, $select_tympanometry);

    mysqli_stmt_bind_param($stmt, "ii", $Registration_ID, $Payment_Item_Cache_List_ID);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $jerger_type_right,
        $admittance_right,
        $pressure_right,
        $width_right,
        $volume_right,
        $jerger_type_left,
        $admittance_left,
        $pressure_left,
        $width_left,
        $volume_left
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $jerger_type_right, $admittance_right, $pressure_right, $width_right, $volume_right, $jerger_type_left, $admittance_left, $pressure_left, $width_left, $volume_left
    );
}

function get_audiology($conn, $Registration_ID, $Payment_Item_Cache_List_ID)
{
    $select_audiology = "SELECT date, equipment, right_otoscopy, left_otoscopy, recommendation
                            FROM tbl_audiogram 
                            WHERE registration_id=? AND payment_item_cache_list_id=?";

    $stmt = mysqli_prepare($conn, $select_audiology);

    mysqli_stmt_bind_param($stmt, "ii", $Registration_ID, $Payment_Item_Cache_List_ID);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $date,
        $equipment,
        $right_otoscopy,
        $left_otoscopy,
        $recommendation
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $date, $equipment, $right_otoscopy, $left_otoscopy, $recommendation
    );
}

function get_audiogram_data($conn, $registration_id, $paymant_item_cache_list_id)
{
    $data = array();

    $d = array();

    $select_audiogram = "SELECT ta.date, ta.equipment, ta.right_otoscopy, ta.left_otoscopy, 
                                        ta.recommendation, tt.jerger_type_right, tt.admittance_right, 
                                        tt.pressure_right, tt.width_right, tt.volume_right, 
                                        tt.jerger_type_left, tt.admittance_left, tt.pressure_left, 
                                        tt.width_left, tt.volume_left, te.Employee_Name
                                FROM tbl_audiogram ta, tbl_tympanometry tt, tbl_employee te
                                WHERE ta.registration_id = ? AND ta.payment_item_cache_list_id = ? AND tt.registration_id = ? AND tt.payment_item_cache_list_id = ? AND te.Employee_ID = ta.employee_id";

    $stmt = mysqli_prepare($conn, $select_audiogram);

    mysqli_stmt_bind_param($stmt, "iiii", $registration_id, $paymant_item_cache_list_id, $registration_id, $paymant_item_cache_list_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $date,
        $equipment,
        $right_otoscopy,
        $left_otoscopy,
        $recommendation,
        $jerger_type_right,
        $admittance_right,
        $pressure_right,
        $width_right,
        $volume_right,
        $jerger_type_left,
        $admittance_left,
        $pressure_left,
        $width_left,
        $volume_left,
        $employee_name
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['date'] = $date;

        $d['equipment'] = $equipment;

        $d['right_otoscopy'] = $right_otoscopy;

        $d['left_otoscopy'] = $left_otoscopy;

        $d['recommendation'] = $recommendation;

        $d['jerger_type_right'] = $jerger_type_right;

        $d['admittance_right'] = $admittance_right;

        $d['pressure_right'] = $pressure_right;

        $d['width_right'] = $width_right;

        $d['volume_right'] = $volume_right;

        $d['jerger_type_left'] = $jerger_type_left;

        $d['admittance_left'] = $admittance_left;

        $d['pressure_left'] = $pressure_left;

        $d['width_left'] = $width_left;

        $d['volume_left'] = $volume_left;

        $d['employee_name'] = $employee_name;

        array_push($data, $d);
    }

    return $data;
}
