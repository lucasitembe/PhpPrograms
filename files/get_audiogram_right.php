<?php

include("./includes/connection.php");

$data = array();

$d = array();

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$payment_item_cache_list_id = mysqli_real_escape_string($conn, trim($_POST['payment_item_cache_list_id']));

$select_audiogram_right = "SELECT frequency_unmasked_ac, hearing_unmasked_ac, frequency_masked_ac, hearing_masked_ac, frequency_unmasked_bc, hearing_unmasked_bc, frequency_masked_bc, hearing_masked_bc, frequency_no_reponse, hearing_no_response
                             FROM tbl_audiogram_right
                             WHERE registration_id=? AND payment_item_cache_list_id=?";

$stmt = mysqli_prepare($conn, $select_audiogram_right);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $payment_item_cache_list_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $frequency_unmasked_ac, $hearing_unmasked_ac, $frequency_masked_ac, $hearing_masked_ac, $frequency_unmasked_bc, $hearing_unmasked_bc, $frequency_masked_bc, $hearing_masked_bc, $frequency_no_reponse, $hearing_no_response);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['frequency_unmasked_ac'] = $frequency_unmasked_ac;

    $d['hearing_unmasked_ac'] = $hearing_unmasked_ac;

    $d['frequency_masked_ac'] = $frequency_masked_ac;

    $d['hearing_masked_ac'] = $hearing_masked_ac;

    $d['frequency_unmasked_bc'] = $frequency_unmasked_bc;

    $d['hearing_unmasked_bc'] = $hearing_unmasked_bc;

    $d['frequency_masked_bc'] = $frequency_masked_bc;

    $d['hearing_masked_bc'] = $hearing_masked_bc;

    $d['frequency_no_reponse'] = $frequency_no_reponse;

    $d['hearing_no_response'] = $hearing_no_response;

    $d['display'] = "UNMASKED AC";

    $d['display1'] = "MASKED AC";

    $d['display2'] = "UNMASKED BC";

    $d['display3'] = "MASKED BC";

    $d['display4'] = "NO RESPONSE";

    array_push($data, $d);
}

echo json_encode($data);
