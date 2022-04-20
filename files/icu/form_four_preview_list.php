<?php
session_start();
include '../includes/connection.php';
include 'repository.php';

if (isset($_GET['records_list'])) {

    $registrationId = $_GET['Registration_ID'];
    $consultationId = $_GET['consultation_ID'];

    $filter = "";
    if (isset($_GET['from']) && isset($_GET['to'])){
        $from = $_GET['from'];
        $to = $_GET['to'];

        $filter .= "AND created_at BETWEEN '$from' AND '$to'";
    }

    $query = "SELECT id, created_at FROM tbl_icu_form_four WHERE registration_id = $registrationId AND consultation_id = $consultationId $filter ORDER BY id DESC";

    $result = querySelect($query, $conn);
}

$count = 1;
?>



<table class="table table-bordered data-table table-sm align-middle">
    <thead class="table-secondary">
        <tr>
            <th style="width: 2%;">SN</th>
            <th scope="col" class="text-center">Record Time</th>
            <th scope="col" width="20%" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody id="records_preview_table">
    <?php foreach ($result as $record) { ?>
        <tr>
            <th scope="row" class="text-center"><?= $count ?></th>
            <td class="text-center"><?= $record['created_at'] ?></td>
            <td class="text-center">
                <a target="_blank" class="btn btn-primary btn-sm text-white px-3"
                   href="form_four_preview.php?record_id=<?= $record['id'] ?>&Registration_ID=<?= $registrationId ?>">
                    VIEW
                </a>
<!--                <a target="_blank" class="btn btn-primary btn-sm text-white"-->
<!--                   href="form_four_print_preview.php?record_id=--><?//= $record['id'] ?><!--&Registration_ID=--><?//= $registrationId ?><!--">-->
<!--                    PRINT PREVIEW-->
<!--                </a>-->
            </td>
        </tr>
        <?php
        $count++;
    } ?>
    </tbody>
</table>
