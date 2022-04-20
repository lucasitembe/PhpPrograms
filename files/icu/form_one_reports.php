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

