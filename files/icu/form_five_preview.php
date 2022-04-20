<script>
        function close_window(){
                close()
        }
</script>
<?php
include('new_header.php');
echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-success' onclick='close_window()' value='BACK'>";
include 'repository.php';
include 'partials/get_patient_details.php';
$form_name = "HANDOVER ISSUE";
include 'partials/new_patient_info.php';

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$query = "SELECT * FROM tbl_icu_form_five WHERE id = '$recordId'";
$result = querySelectOne($query, $conn);

$employee_id = $result['employee_id'];
$loc_mood = $result['loc_mood'];
$sensation = $result['sensation'];
$ecg = $result['ecg'];
$bp = $result['bp'];
$urine_output = $result['urine_output'];
$temperature = $result['temperature'];
$breathing = $result['breathing'];
$activity = $result['activity'];
$diet_elimination = $result['diet_elimination'];
$skin = $result['skin'];
$infection = $result['infection'];
$comfort = $result['comfort'];
$bleeding = $result['bleeding'];
$patient_complaint = $result['patient_complaint'];
$family_concern = $result['family_concern'];
$socio_culture_issues = $result['socio_culture_issues'];
$fluid_electrolyte = $result['fluid_electrolyte'];
$labs_investigation = $result['labs_investigation'];
$leading_needs = $result['leading_needs'];
$time = $result['time'];
$summary = $result['summary'];
$comments = $result['comments'];
$created_at = $result['created_at'];

$select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee_id' ";
$select_employee_name = mysqli_query($conn, $select_employee_name);
while ($employee_row = mysqli_fetch_array($select_employee_name)):
    $get_employee_name = $employee_row['Employee_Name'];
endwhile;

?>

    <div class="pt-5 bg-white">
        <table width="100%" class="table table-bordered table-striped">
            <thead class="table-light">
            <tr class="text-center">
                <th colspan="4">
                    <span><center>Done By : <b><?= $get_employee_name ?></b> Done On : <b><?= $created_at ?></b></center></span>
                </th>
            </tr>
            <tr class="text-center">
                <th style="width:30% !important; ">Handover issues</th>
                <th style="width:50% !important; ">Events</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="text-center label">Time</td>
                <td class="text-center"><?= $time ?></td>
            </tr>
            <tr>
                <td class="text-center label">LOC / Mood</td>
                <td class="text-center"><?= $loc_mood ?></td>
            </tr>
            <tr>
                <td class="text-center label">Sensation</td>
                <td class="text-center"><?= $sensation ?></td>
            </tr>
            <tr>
                <td class="text-center label">ECG (Rate Rhythm)</td>
                <td class="text-center"><?= $ecg ?></td>
            </tr>
            <tr>
                <td class="text-center label">BP</td>
                <td class="text-center"><?= $bp ?></td>
            </tr>
            <tr>
                <td class="text-center label">Urine Output</td>
                <td class="text-center"><?= $urine_output ?></td>
            </tr>
            <tr>
                <td class="text-center label">Temperature</td>
                <td class="text-center"><?= $temperature ?></td>
            </tr>
            <tr>
                <td class="text-center label">Breathing</td>
                <td class="text-center"><?= $breathing ?></td>
            </tr>
            <tr>
                <td class="text-center label">Activity</td>
                <td class="text-center"><?= $activity ?></td>
            </tr>
            <tr>
                <td class="text-center label">Diet And Elimination</td>
                <td class="text-center"><?= $diet_elimination ?></td>
            </tr>
            <tr>
                <td class="text-center label">Skin</td>
                <td class="text-center"><?= $skin  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Infection</td>
                <td class="text-center"><?= $infection  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Comfort</td>
                <td class="text-center"><?= $comfort  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Bleeding</td>
                <td class="text-center"><?= $bleeding  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Patient Complaint</td>
                <td class="text-center"><?= $patient_complaint ?></td>
            </tr>
            <tr>
                <td class="text-center label">Family Concern</td>
                <td class="text-center"><?= $family_concern  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Socio culture issues</td>
                <td class="text-center"><?= $socio_culture_issues ?></td>
            </tr>
            <tr>
                <td class="text-center label">Fluid and Electrolyte</td>
                <td class="text-center"><?= $fluid_electrolyte  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Labs / Investigation</td>
                <td class="text-center"><?= $labs_investigation  ?></td>
            </tr>
            <tr>
                <td class="text-center label">Leading Needs</td>
                <td class="text-center"><?= $leading_needs  ?></td>
            </tr>
            <tr>
                <td class="text-center">Summary</td>
                <td colspan="3">
                    <?= $summary ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">Comment</td>
                <td colspan="3">
                    <?= $comments ?>
                </td>
            </tr>
            </tbody>

        </table>
    </div>


<?php include 'footer.php'; ?>
