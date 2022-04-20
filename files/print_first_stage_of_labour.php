<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");

if (isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];
}
$htm = "<center><img src='./branchBanner/branchBanner.png'>";
$htm .= "<p style='font-weight:bold; text-align:center;'>FIRST STAGE OF LABOUR</p>";

$patientId = 537;
$today = Date("Y-m-d");
$intact = "";
$rapture = "";
$done = "";
$notDone = "";
$inductionYes = "";
$inductionYes = "";
$select_first_stage = "SELECT * FROM tbl_first_stage_of_labour 
        WHERE patient_id='$patientId'  
        AND date_time='$today'";

if ($result_first_stage = mysqli_query($conn,$select_first_stage)) {
    while ($row_first_stage = mysqli_fetch_assoc($result_first_stage)) {

        $set_labour_time_and_date = $row_first_stage['set_of_labour_time_and_date'];
        $admitted_at = $row_first_stage['admitted_at'];
        $if_ruptured_date_and_time = $row_first_stage['time_and_date_of_rupture'];
        $total_time_elapsed_since_rupture = $row_first_stage['time_elapsed_since_rupture'];
        $yes_reasons = $row_first_stage['induction_reason'];
        $state_of_membrane = $row_first_stage['state_of_membrane'];
        $duration_of_first_labour = $row_first_stage['total_duration_of_first_stage_labour'];
        $abdomalities = $row_first_stage['abdomalities_of_first_stage'];
        $drug_given = $row_first_stage['drugs_given'];
        $remark = $row_first_stage['remarks'];
        $arm = $row_first_stage['arm'];
        $no_of_vaginal_examination = $row_first_stage['no_of_vaginal_examination'];
        $induction_of_labour = $row_first_stage['induction_of_labour'];
    }

    if ($state_of_membrane == "intact") {
        $intact = "checked";
    } else if ($state_of_membrane == "Rapture") {
        $rapture = "checked";
    }

    if ($arm == "Done") {
        $done = "checked";
    } else if ($arm == "Not Done") {
        $notDone = "checked";
    }

    if ($induction_of_labour == "yes") {
        $inductionYes = "checked";
    } else if ($induction_of_labour == "no") {
        $iductionNo = "checked";
    }
}

?>
<?php 
$htm .= '<center>
    <table width="100%">
    <tr>
    <td><b>On Set Labour Time And Date:</b></td>
    <td>' . $set_labour_time_and_date . '</td>
    <td><b>admitted at:</b></td>
    <td>' . $admitted_at . '</td>
    </tr>
    <tr>
        <td><b>State of membrane:</b></td>
        <td>
        <span>
        <input type="radio" name="membrane_state" value="" ' . $intact . '>Intact
        <input type="radio" name="membrane_state" value="" ' . $rapture . '>Rapture
        </span>
        </td>
        <td><b>If Rupture: Date And Time:</b></td>
        <td>' . $if_ruptured_date_and_time . '</td>
    </tr>
    <tr>
    <td><b>Total Time elapsed since Rupture :</b></td>
    <td style="text-align:left">' . $total_time_elapsed_since_rupture . '</td>
    <td>
    <b>ARM:</b>
    <span>
        <input type="radio" name="arm" ' . $done . '>Done
    </span>
    <span>
    <input type="radio" name="arm" ' . $notDone . '>Not Done
    </span>
    </td>
    </tr>
    <tr>
        <td><b>Number Of varginal Examination:</b></td>
        <td>' . $no_of_vaginal_examination . '</td>
        <td><b>Induction Of Labour:</b></td>
        <td>
        <span>
            <input type="radio" name="induction" value="" ' . $inductionYes . '>YES
        </span>
        <span>
        <input type="radio" name="induction" value="" ' . $inductionNo . '>NO
        </span>
        </td>
    </tr>
    <tr>
        <td><b>If Yes Reasons:</b></td>
        <td>' . $yes_reasons . '</td>
    <td><b>Total Duration of First Stage Labour:</b></td>
    <td>' . $total_time_elapsed_since_rupture . '</td>
    </tr>
    <tr>
    <td><b>Any Abdomalities of First Stage:</b></td>
    <td>' . $abdomalities . '</td>
    <td><b>Drug Given:</b></td>
        <td>' . $drug_given . '</td>
    </tr>
    <tr>
     <td><b>Remarks:</b></td>
     <td colspan="2">' . $remark . '</td>   
    </tr>
    </table>
    </center>
    </center>';
?>
<?php
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;


?>
