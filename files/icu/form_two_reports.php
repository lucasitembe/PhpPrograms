<?php
ini_set('display_errors', true);

include 'new_header.php';
include 'form_two_functions.php';
include 'partials/get_patient_details.php';

$form_name = "GLASGOW COMA SCALE REPORTS (from date to date)";

include 'partials/new_patient_info.php' ?>

<div class="bg-white pt-5 pb-3 px-2">
    <div class="d-flex pt-2">
        <input class="form-control datetime mx-1" placeholder="Start Date">
        <input class="form-control datetime mx-1" placeholder="End Date">
        <input class="form-control mx-1" placeholder="Selected Fields">
        <input class="form-control mx-1" placeholder="Employee Name">
        <button class="btn btn-primary mx-1" id="filter">Filter</button>
        <button class="btn btn-primary mx-1">PDF</button>
        <button class="btn btn-primary mx-1">Excel</button>
    </div>
    <div id="reports"></div>
</div>

<script>
    $(document).ready(function (){
        $('.datetime').datetimepicker();

        let consultation_ID = '<?= $consultation_ID ?>';
        let Registration_ID = '<?= $Registration_ID ?>';

        $('#filter').on('click', function (){
            $.post('form_two_reports_ajax.php', {
                consultation_ID,
                Registration_ID,
                get: 'form-two-reports'
            }, (response) => {
                console.log(response)
                $('#reports').html(response)
            })
        });
    });
</script>

<?php include 'footer.php'; ?>