<script>
        function close_window(){
                close()
        }
</script>
<?php
include('new_header.php');
include 'form_six_functions.php';
include 'partials/get_patient_details.php';
$form_name = "PATIENT ASSESSMENT ISSUE";

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$data = getFormData($recordId);
$users = getFormUsers($recordId);
echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-success' onclick='close_window()' value='BACK'>";
//print_r($data);

include 'partials/new_patient_info.php';
?>

    <div class="pt-5 bg-white">

        <table width="100%" class="table table-bordered table-striped">
            <thead class="table-light">
                <tr class="text-center">
                    <th class="text-center" width="30%">Assessment</th>
                    <th class="text-center" width="23.3%">AM</th>
                    <th class="text-center" width="23.3%">PM</th>
                    <th class="text-center" width="23.3%">Night</th>
                </tr>
            </thead>

            <tbody>
            <tr>
                <td class="text-center fw-bold" colspan="4">Respiratory</td>
            </tr>
            <tr>
                <td class="text-center">Air entry</td>
                <?php loopData('air-entry'); ?>
            </tr>
            <tr>
                <td class="text-center">Breath Sound</td>
                <?php loopData('breath-sound'); ?>
            </tr>
            <tr>
                <td class="text-center">Chest Expansion</td>
                <?php loopData('chest-expansion'); ?>
            </tr>
            <tr>
                <td class="text-center">Use of Accessory Muscle</td>
                <?php loopData('use-of-accessory-muscle'); ?>
            </tr>
            <tr>
                <td class="text-center">Ability To Cough</td>
                <?php loopData('ability-to-cough'); ?>
            </tr>
            <tr>
                <td class="text-center fw-bold" colspan="4">CV</td>
            </tr>
            <tr>
                <td class="text-center">Rhythm</td>
                <?php loopData('rhythm'); ?>
            </tr>
            <tr>
                <td class="text-center">Daily Weight</td>
                <?php loopData('daily-weight'); ?>
            </tr>
            <tr>
                <td class="text-center">Capillary Refill</td>
                <?php loopData('capillary-refill'); ?>
            </tr>
            <tr>
                <td class="text-center">Skin Condition</td>
                <?php loopData('skin-condition'); ?>
            </tr>
            <tr>
                <td class="text-center">Color:Pink/Pale/Cynotic/Juandice</td>
                <?php loopData('skin-color'); ?>
            </tr>
            <tr>
                <td class="text-center">Turgor:Normal/Loose/Tight/Shiny</td>
                <?php loopData('skin-turgor'); ?>
            </tr>
            <tr>
                <td class="text-center">Texture Dry/Moist</td>
                <?php loopData('skin-texture'); ?>
            </tr>
            <tr>
                <td class="text-center">Odema[Sites]</td>
                <?php loopData('odema'); ?>
            </tr>
            <tr>
                <td class="text-center fw-bold" colspan="4">GI</td>
            </tr>
            <tr>
                <td class="text-center">Abdomen:Soft/Hard/Distended/Tender</td>
                <?php loopData('abdomen'); ?>
            </tr>
            <tr>
                <td class="text-center">Bowel Sound:Normal hyperactive</td>
                <?php loopData('bowel-sound'); ?>

            </tr>
            <tr>
                <td class="text-center">Hypoactive/Absent</td>
                <?php loopData('hypoactive'); ?>

            </tr>
            <tr>
                <td class="text-center">*NG Tube Insertion Date NA/Clamped/Cont Suction/INT.Suction Gravity</td>
                <?php loopData('ngtube-insertion-date'); ?>

            </tr>
            <tr>
                <td class="text-center">Diet(Restricted/Regular)</td>
                <?php loopData('diet'); ?>

            </tr>
            <tr>
                <td class="text-center">Activity</td>
                <?php loopData('activity'); ?>

            </tr>
            <tr>
                <td class="text-center">Level Of Mobility</td>
                <?php loopData('level-of-mobility'); ?>

            </tr>
            <tr>
                <td class="text-center">(**CBR/up To Washroom)</td>
                <?php loopData('cbr'); ?>

            </tr>
            <tr>
                <td class="text-center">Activity(Assisted/Self)</td>
                <?php loopData('activity-2'); ?>

            </tr>
            <tr>
                <td class="text-center">Drains NA/Type/Location</td>
                <?php loopData('drains'); ?>

            </tr>
            <tr>
                <td class="text-center">Character</td>
                <?php loopData('character'); ?>

            </tr>
            <tr>
                <td class="text-center">Vomitus: Amount/Colour</td>
                <?php loopData('vomitus'); ?>

            </tr>
            <tr>
                <td class="text-center">Stool: Consistency/Clour/Pattern</td>
                <?php loopData('stool'); ?>

            </tr>
            <tr>
                <td class="text-center">Amount: Small,Medium,Large,Nil</td>
                <?php loopData('stool-amount'); ?>

            </tr>
            <tr>
                <td class="text-center fw-bold" colspan="4">GU</td>

            </tr>
            <tr>
                <td class="text-center">Urine Colour /Sediments /Haematite</td>
                <?php loopData('urine'); ?>

            </tr>
            <tr>
                <td class="text-center">Foleys Insertion Date</td>
                <?php loopData('foleys-insertion-date'); ?>
            </tr>
            <tr>
                <td class="text-center">Dialysis</td>
                <?php loopData('dialysis'); ?>

            </tr>
            <tr>
                <td class="text-center">Pulse Code</td>
                <?php loopData('pulse-code'); ?>

            </tr>
            <tr>
                <td>
                    <div class="d-flex justify-content-around">
                        <span>0 = Absent</span>
                        <span>Radial:R/L</span>
                    </div>
                </td>
                <?php loopData('pc-radial'); ?>

            </tr>
            <tr>
                <td>
                    <div class="d-flex justify-content-around">
                        <span>+1 = Weak</span>
                        <span>Femoral: R/L</span>
                    </div>
                </td>
                <?php loopData('pc-femoral'); ?>

            </tr>
            <tr>
                <td>
                    <div class="d-flex justify-content-around">
                        <span>+2 = Normal</span>
                        <span>Dor Pedis: R/L</span>
                    </div>
                </td>
                <?php loopData('pc-dor-pedis'); ?>

            </tr>
            <tr>
                <td>
                    <div class="d-flex justify-content-around">
                        <span>+3 = Strong</span>
                        <span>Post Tib R/L</span>
                    </div>
                </td>
                <?php loopData('pc-post-tib'); ?>

            </tr>
            <tr>
                <td class="text-center">+4 = Bounding</td>
                <?php loopData('pc-bounding'); ?>

            </tr>
            <tr>
                <td class="text-center">Nurse - Family Interaction</td>
                <?php loopData('nurse-family-interaction'); ?>

            </tr>
            <tr>
                <td class="text-center">Psychological Family Support</td>
                <?php loopData('pyschological-family-support'); ?>

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

    <?php include 'footer.php'; ?>
