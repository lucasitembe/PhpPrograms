<?php
session_start();
include 'form_four_functions.php';

$registrationId = $_GET['Registration_ID'];
$consultationId = $_GET['consultation_ID'];

$from = "";
$to = "";

if (isset($_GET['from']) && isset($_GET['to'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
}

$results = getData($registrationId, $consultationId, $from, $to);

$count = 1;

//print_r($results);

?>



<table class="table table-bordered records-table table-sm align-middle">
    <thead class="table-secondary">
    <tr>
        <th style="width: 2%;">SN</th>
        <th scope="col" class="text-center">Record Time</th>
        <th scope="col" class="text-center">Calcium</th>
        <th scope="col" class="text-center">Magnesium</th>
        <th scope="col" class="text-center">Chloride</th>
        <th scope="col" class="text-center">Potassium</th>
        <th scope="col" class="text-center">Sodium</th>
        <th scope="col" class="text-center">Bicarbonate>
        <th scope="col" class="text-center">Blood Glucose</th>
        <th scope="col" class="text-center">HB</th>
        <th scope="col" class="text-center">ABG</th>
    </tr>
    </thead>
    <tbody id="records_preview_table">
    <?php foreach ($results as $record) { ?>
        <tr>
            <th scope="row" class="text-center"><?= $count ?></th>
            <td class="text-center"><?= $record['created_at'] ?></td>
            <td class="text-center"><?= isset($record['Calcium']) ? $record['Calcium'] : '' ?></td>
            <td class="text-center"><?= isset($record['Magnesium']) ? $record['Magnesium'] : '' ?></td>
            <td class="text-center"><?= isset($record['Chloride']) ? $record['Chloride'] : '' ?></td>
            <td class="text-center"><?= isset($record['Potassium']) ? $record['Potassium'] : '' ?></td>
            <td class="text-center"><?= isset($record['Sodium']) ? $record['Sodium'] : '' ?></td>
            <td class="text-center"><?= isset($record['Bicarbonate']) ? $record['Bicarbonate'] : '' ?></td>
            <td class="text-center"><?= isset($record['Blood Glucose']) ? $record['Blood Glucose'] : '' ?></td>
            <td class="text-center"><?= isset($record['HB']) ? $record['HB'] : '' ?></td>
            <td class="text-center"><?= isset($record['ABG']) ? $record['ABG'] : '' ?></td>

        </tr>
        <?php
        $count++;
    } ?>
    </tbody>
</table>