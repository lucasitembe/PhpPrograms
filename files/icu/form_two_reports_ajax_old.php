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
    <table class="table table-bordered table-sm">
        <thead class="table-light">

        <tr class="text-center">
            <!-- CNS -->
            <th colspan="4">Ability to Move</th>

            <!-- Pupil, Size -->
            <th colspan="2">Pupil Size</th>

            <!-- Pupil, Reaction -->
            <th colspan="2">Pupil Reaction</th>

            <!-- Lungs Mechanisms -->
            <th colspan="12">Lungs Mechanism</th>

            <!-- Lungs Mechanisms -->
            <th colspan="2">Position</th>

            <!-- Lungs Mechanisms -->
            <th colspan="9">Arterial Gases</th>

        </tr>
        <tr class="text-center">
            <!-- CNS, Ability to move -->
            <th>R. Arm</th>
            <th>L. Arm</th>
            <th>R. Leg</th>
            <th>L. Leg</th>

            <!-- Pupil, Size -->
            <th>Right</th>
            <th>Left</th>

            <!-- Pupil, Reaction -->
            <th>Right</th>
            <th>Left</th>

            <!-- Lungs Mechanism -->
            <th>Air Entry</th>
            <th>O2 Therapy / FIO2</th>
            <th>Ventilator</th>
            <th>Mode</th>
            <th>Pressure Support</th>
            <th>RR SET</th>
            <th>RR Pt</th>
            <th>Peak Inspiratory Pressure</th>
            <th>Minute Volume</th>
            <th>PEEP / CPAP</th>
            <th>IE Ratio</th>
            <th>ETT Mark</th>

            <th>Position</th>
            <th>Spasms</th>

            <th>PH</th>
            <th>PCO2</th>
            <th>PO2</th>
            <th>HC03</th>
            <th>PK+</th>
            <th>Na++</th>
            <th>Mg2+</th>
            <th>CI=1</th>
            <th>HCO3</th>
        </tr>
        </thead>
    </table>
</div>