<?php
include 'repository.php';
include 'partials/get_patient_details.php';

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$query = "SELECT * FROM tbl_icu_form_five WHERE id = '$recordId'";
$result = querySelectOne($query, $conn);

$save_date = $result['created_at'];
$employee_id = $result['employee_id'];
$labels = json_decode($result['labels']);
$time_inputs = json_decode($result['time_inputs']);
$events_inputs = json_decode($result['events_inputs']);
$summary_inputs = json_decode($result['summary_inputs']);
$comments = $result['comments'];

$select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee_id' ";
$select_employee_name = mysqli_query($conn, $select_employee_name);
while ($employee_row = mysqli_fetch_array($select_employee_name)):
    $get_employee_name = $employee_row['Employee_Name'];
endwhile;

$htm = "<img src='../branchBanner/branchBanner.png' width='100%' >";
$htm .= "<h3 style='text-align: center'><b>ICU - FORM FIVE RECORD</b></h3>";
$htm .= "<p style='text-align: center'><b>" . $Patient_Name . " | " . $Gender . " | " . $age . " years | " . $Guarantor_Name . "</b></p>";

//    if (!empty($start) && !empty($end)){
//        $htm .= "<h4 style='text-align: center;'>BETWEEN $start AND '$end' </h4>";
//    }

$htm .= '<table width="100%" class="table table-bordered table-striped">';
$htm .= "
           <thead class='table-light'>
                <tr class='text-center'>
                    <th colspan='4'>
                        <span><center>Done By : <b> $get_employee_name </b> Done On : <b> $save_date </b></center></span>
                    </th>
                </tr>
                <tr class='text-center'>
                    <th style='width:30% !important; '>Handover Issues</th>
                    <th style='width:20% !important; '>Time</th>
                    <th style='width:25% !important; '>Events</th>
                    <th style='width:25% !important; '>Summary</th>
                </tr>
           </thead>
                ";

for ($i = 0; $i < count($labels); $i++) {
    $htm .= '<tr>';
    $htm .= "<td class='text-center'> $labels[$i] </td>";
    $htm .= '<td class="text-center">';
    $htm .= $time_inputs[$i];
    $htm .= '</td>';
    $htm .= '<td class="text-center">';
    $htm .= $events_inputs[$i];
    $htm .= '</td>';
    $htm .= '<td class="text-center">';
    $htm .= $summary_inputs[$i];
    $htm .= '</td>';
    $htm .= '</tr>';
}

$htm .= "</table>";

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