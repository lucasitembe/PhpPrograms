<?php
session_start();
include '../includes/connection.php';
include 'repository.php';

if (isset($_GET['records_list'])) {

    $registrationId = $_GET['Registration_ID'];
    $consultationId = $_GET['consultation_ID'];

    $query = "SELECT id, created_at FROM tbl_icu_form_five WHERE registration_id = $registrationId AND consultation_id = $consultationId ORDER BY id DESC";

    $result = querySelect($query, $conn);
}

$count = 1;
?>

<table class="table table-bordered">
    <thead class="table-secondary">
    <tr>
        <th style="width: 2%;">SN</th>
        <th scope="col" class="text-center">Record Time</th>
        <th scope="col" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody id="records_preview_table">
    <?php foreach ($result as $record) { ?>
        <tr>
            <th scope="row"><?= $count ?></th>
            <td class="text-center"><?= $record['created_at'] ?></td>
            <td class="text-center">
                <a target="_blank" class="btn btn-primary btn-sm text-white"
                   href="form_five_preview.php?record_id=<?= $record['id'] ?>&Registration_ID=<?= $registrationId ?>">
                    VIEW
                </a>
<!--                <a target="_blank" class="btn btn-primary btn-sm text-white"-->
<!--                   href="form_five_print_preview.php?record_id=--><?//= $record['id'] ?><!--&Registration_ID=--><?//= $registrationId ?><!--">-->
<!--                    PRINT PREVIEW-->
<!--                </a>-->
            </td>
        </tr>
        <?php
        $count++;
    } ?>
    </tbody>
</table>