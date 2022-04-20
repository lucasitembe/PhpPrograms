<?php
//ini_set('display_errors', true);
include('new_header.php');
include('form_one_functions.php');
include 'partials/get_patient_details.php';
$form_name = "INTENSIVE CARE UNIT REPORTS";

// Additional parameters
isset($_GET['this_page_from']) ? $pageFrom = $_GET['this_page_from'] : $pageFrom = "";
?>

    <a href="../all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID; ?>&this_page_from=<?= $pageFrom ?>&Admision_ID=<?= $Admission_ID ?>&consultation_ID=<?= $consultation_ID ?>"
   class="btn btn-primary">BACK</a>

<?php include "partials/new_patient_info.php"; ?>

<div class="bg-white pt-5 px-2" style="min-height: 70vh;">
    <div class="d-flex justify-content-center">
        <div style="width: 50vw;">
            <table class="w-100 table table-bordered">
                <tr>
                    <td><a href="form_one_reports.php?Registration_ID=<?= $Registration_ID; ?>&consultation_ID=<?= $consultation_ID; ?>">
                            <button class="menu-button">
                                VITAL SIGNS
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td><button class="menu-button">GLASGOW COMA SCALE (GCS)</button></td>
                </tr>
                <tr>
                    <td><button class="menu-button">MEDICATION ADMINISTRATION (eMAR)</button></td>
                </tr>
                <tr>
                    <td><button class="menu-button">LABORATORY REPORT</button></td>
                </tr>
                <tr>
                    <td><button class="menu-button">HANDOVER ISSUE</button></td>
                </tr>
                <tr>
                    <td><button class="menu-button">PATIENT ASSESSMENT</button></td>
                </tr>
                <tr>
                    <td><button class="menu-button">ROUTINE CARE</button></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div id="dialog"></div>

<script>
    $(document).ready(function (){
        $("#dialog").dialog({
            autoOpen: false,
            minWidth: 600,
            modal: true,
            width: "75%",
            height: 600,
        });

        let dialog = $('#dialog');
        let Registration_ID = '<?= $Registration_ID ?>';

        $('#vital-signs').click(function (){
            $.get(
                'form_one_preview_list.php', {
                    records_list: 'all_records',
                    Registration_ID: Registration_ID,
                }, (response) => {
                    dialog.dialog({title: 'VITAL SIGNS RECORDS'});
                    dialog.html(response)
                    dialog.dialog('open');
                }
            );
        })
    })
</script>

<?php include 'footer.php' ?>