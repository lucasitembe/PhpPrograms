<?php
ini_set('display_errors', true);
include 'form_two_functions.php';

$registrationId = post('Registration_ID');
$consultationId = post('consultation_ID');

$query = "SELECT name, hour, value FROM tbl_icu_form_two two, tbl_icu_form_two_records twor 
                        WHERE two.id = twor.record_id 
                        AND two.registration_id = '$registrationId' 
                        AND two.consultation_id = '$consultationId'
                        AND two.created_at = CURDATE()";

$results = querySelect($query);

$formatted = [];
foreach ($results as $result){
    $formatted[$result['name']] = array_replace(
        isset($formatted[$result['name']]) ? $formatted[$result['name']] : [],
        [$result['hour'] => $result['value']]
    );
}

?>

<div class="table-responsive">
    <table width='100%' class="table table-striped table-hover table-bordered table-sm align-middle">
        <thead class="table-secondary sticky-top">
            <tr>
                <th width="6%"></th>
                <th width='18%' colspan="3"> Glasgow Coma Scale (GCS)</th>
                <th style="width: 3%;">07:00</th>
                <th style="width: 3%;">08:00</th>
                <th style="width: 3%;">09:00</th>
                <th style="width: 3%;">10:00</th>
                <th style="width: 3%;">11:00</th>
                <th style="width: 3%;">12:00</th>
                <th style="width: 3%;">13:00</th>
                <th style="width: 3%;">14:00</th>
                <th style="width: 3%;">15:00</th>
                <th style="width: 3%;">16:00</th>
                <th style="width: 3%;">17:00</th>
                <th style="width: 3%;">18:00</th>
                <th style="width: 3%;">19:00</th>
                <th style="width: 3%;">20:00</th>
                <th style="width: 3%;">21:00</th>
                <th style="width: 3%;">22:00</th>
                <th style="width: 3%;">23:00</th>
                <th style="width: 3%;">00:00</th>
                <th style="width: 3%;">01:00</th>
                <th style="width: 3%;">02:00</th>
                <th style="width: 3%;">03:00</th>
                <th style="width: 3%;">04:00</th>
                <th style="width: 3%;">05:00</th>
                <th style="width: 3%;">06:00</th>
            </tr>
        </thead>
    </table>
</div>