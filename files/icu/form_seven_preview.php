<script>
        function close_window(){
                close()
        }
</script>
<?php
include('new_header.php');
echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-success' onclick='close_window()' value='BACK'>";
include 'form_seven_functions.php';

$form_name = "ROUTINE CARE REPORT PREVIEW";

include 'partials/get_patient_details.php';

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$data = getFormData($recordId);
$users = getFormUsers($recordId);

include 'partials/new_patient_info.php';
?>

    <div class="bg-white pt-5">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:25% !important;">Assessment</th>
                    <th class="text-center" style="width:25% !important;">Am</th>
                    <th class="text-center" style="width:25% !important;">PM</th>
                    <th class="text-center" style="width:25% !important;">Night</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td style="text-align: center;" class="label">Bath</td>
                    <?php loopData('bath'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Back Care</td>
                    <?php loopData('back-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Mouth Care</td>
                    <?php loopData('mouth-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Eye Care</td>
                    <?php loopData('eye-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Catheter Care</td>
                    <?php loopData('catheter-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Perinial Care</td>
                    <?php loopData('perinial-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">N/G Care</td>
                    <?php loopData('ng-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Nose /Ear Care</td>
                    <?php loopData('nose-ear-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Physio</td>
                    <?php loopData('physio'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Deep Breath Cough</td>
                    <?php loopData('deep-breath-cough'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">OETT/TT Care</td>
                    <?php loopData('oett-tt-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Line Care</td>
                    <?php loopData('line-care'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">1. Location</td>
                    <?php loopData('location-1'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Insertion Date</td>
                    <?php loopData('insertion-date-1'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Status of Site</td>
                    <?php loopData('status-of-site-1'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Redressed</td>
                    <?php loopData('redressed-1'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">2. Location</td>
                    <?php loopData('location-2'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Insertion Date</td>
                    <?php loopData('insertion-date-2'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Status of Site</td>
                    <?php loopData('status-of-site-2'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Redressed</td>
                    <?php loopData('redressed-2'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">3. Location</td>
                    <?php loopData('location-3'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Insertion Date</td>
                    <?php loopData('insertion-date-3'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Status of Site</td>
                    <?php loopData('status-of-site-3'); ?>
                </tr>
                <tr>
                    <td style="text-align: center;" class="label">Redressed</td>
                    <?php loopData('redressed-3'); ?>
                </tr>

                </tbody>
            </table>

            <h6 class="text-center mt-5 fw-bold">Form Data By:</h6>
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th class="text-center">SN</th>
                    <th class="text-center">Employee Name</th>
                    <th class="text-center">Last Updated At</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach($users as $user) { ?>
                    <tr>
                        <td style="width: 3.75%;" class="text-center"><?= $sn; ?></td>
                        <td class="text-center"><?= $user['name']; ?></td>
                        <td class="text-center"><?= $user['date']; ?></td>
                    </tr>
                    <?php $sn++;
                } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include 'footer.php'; ?>
