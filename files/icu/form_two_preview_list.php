<?php
session_start();
include '../includes/connection.php';
include 'repository.php';
ini_set('display_errors', true);

if (isset($_GET['records_list'])) {

    $registrationId = clean($_GET['Registration_ID']);
    $consultationId = clean($_GET['consultation_ID']);

    $query = "SELECT * FROM tbl_icu_form_two WHERE registration_id = $registrationId AND consultation_id = $consultationId ORDER BY id DESC";

    $result = querySelect($query, $conn);
}

$count = 1;
?>

<table class="table table-bordered">
    <thead class="table-secondary">
    <tr>
        <th style="width: 2%;">SN</th>
        <th scope="col" class="text-center">Record Time</th>
        <th scope="col" width="30%" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody id="records_preview_table">
    <?php foreach ($result as $record) { ?>
        <tr>
            <th scope="row"><?= $count ?></th>
            <td class="text-center"><?= $record['created_at'] ?></td>
            <td class="text-center">
                <a target="_blank" class="btn btn-primary btn-sm text-white"
                   href="form_two_preview.php?record_id=<?= $record['id'] ?>&Registration_ID=<?= $registrationId ?>">
                    VIEW
                </a>
            </td>
        </tr>
        <?php
        $count++;
    } ?>
    </tbody>
</table>
