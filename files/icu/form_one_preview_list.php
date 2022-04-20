<?php
session_start();
include '../includes/connection.php';
include 'repository.php';

if (isset($_GET['records_list']) && $_GET['records_list'] === 'all_records') {

    $registrationId = mysqli_real_escape_string($conn, $_GET['Registration_ID']);

    $query = "SELECT * FROM tbl_icu_form_one WHERE registration_id = $registrationId ORDER BY id DESC";

    $result = querySelect($query, $conn);

} else if (isset($_GET['records_list'])) {

    $registrationId = mysqli_real_escape_string($conn, $_GET['Registration_ID']);
    $consultationId = mysqli_real_escape_string($conn, $_GET['consultation_ID']);

    $query = "SELECT * FROM tbl_icu_form_one WHERE registration_id = $registrationId AND consultation_id = $consultationId ORDER BY id DESC";

    $result = querySelect($query, $conn);
}

$count = 1;
?>



<table class="table table-bordered">
    <thead class="table-secondary">
        <tr>
            <th style="width: 2%;">SN</th>
            <th scope="col" class="text-center">Recorded Time</th>
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
                   href="form_one_preview.php?record_id=<?= $record['id'] ?>&Registration_ID=<?= $registrationId ?>">
                    VIEW RECORD
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
