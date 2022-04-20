<?php

include 'repository.php';
include 'partials/get_patient_details.php';

if (isset($_GET['record_id'])) {
    $recordId = mysqli_real_escape_string($conn, $_GET['record_id']);
} else {
    $recordId = '';
}

$htm = "<img src='../branchBanner/branchBanner.png' width='100%' >";
$htm .= "<h3 style='text-align: center'><b>ICU - FORM FIVE RECORD</b></h3>";
$htm .= "<p style='text-align: center'><b>" . $Patient_Name . " | " . $Gender . " | " . $age . " years | " . $Guarantor_Name . "</b></p>";


include("../MPDF/mpdf.php");

$mpdf = new mPDF('utf-8', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;    // 1 or 0 - whether to indent the first level of a list
$mpdf->SetFooter('Printed By ' . strtoupper($get_employee_name) . '|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By eHMS');
$stylesheet = file_get_contents('../patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML(utf8_encode($htm));
$mpdf->Output();
exit;

?>

?>