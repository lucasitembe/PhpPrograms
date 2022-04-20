<?php
ini_set('display_errors', true);
include('new_header.php');
include 'form_two_functions.php';
$form_name = "VITAL SIGNS REPORT PREVIEW";

include 'partials/get_patient_details.php';

if (isset($_GET['record_id'])) {
    $recordId = mysqli_real_escape_string($conn, $_GET['record_id']);
} else {
    $recordId = '';
}

$data = getFormData($recordId);
$infusionData = getInfusionData($recordId);
$users = getFormUsers($recordId);

?>

<button class="btn btn-primary" id="print">Print</button>


<?php include 'partials/new_patient_info.php'; ?>

    <div id="element-to-print" class="bg-white pt-5">
        <img src="../branchBanner/branchBanner.png" class="mx-auto d-block" />
        <div class="table-responsive">
            <table id="table" class="table table-sm table-striped table-bordered">

                <thead class="table-light">
                <tr>
                    <th colspan="2" width="10%">Glasgow Coma Scale (GCS)</th>
                    <th style="width: 3%;">07:00</th>
                    <th style="width: 3%;">08:00</th>
                    <th style="width: 3%;">09:00</th>
                    <th style="width: 3%;">10:00</th>
                    <th style="width: 3%;">11:00</th>
                    <th style="width: 3%;">12:00</th>
                    <th style="width: 3%;">13:00</th>
                    <th style="width: 3%;">14:00</th>
                    <th style="width: 3%;">15:00</th>
                    <th style="width: 3%;">16:00</th>
                    <th style="width: 3%;">17:00</th>
                    <th style="width: 3%;">18:00</th>
                    <th style="width: 3%;">19:00</th>
                    <th style="width: 3%;">20:00</th>
                    <th style="width: 3%;">21:00</th>
                    <th style="width: 3%;">22:00</th>
                    <th style="width: 3%;">23:00</th>
                    <th style="width: 3%;">00:00</th>
                    <th style="width: 3%;">01:00</th>
                    <th style="width: 3%;">02:00</th>
                    <th style="width: 3%;">03:00</th>
                    <th style="width: 3%;">04:00</th>
                    <th style="width: 3%;">05:00</th>
                    <th style="width: 3%;">06:00</th>
                </tr>
                </thead>


                <tr>
                    <td rowspan="4">Eye Opening</td>
                    <td width="7%">4.Spontaneous</td>
                    <?php loopData('spontaneous') ?>

                </tr>

                <tr>
                    <td>3.To Speech</td>
                    <?php loopData('to-speech') ?>
                </tr>

                <tr>
                    <td>2.To Pain</td>
                    <?php loopData('to-pain') ?>
                </tr>

                <tr>
                    <td>1.Nil</td>
                    <?php loopData('eo-nil') ?>
                </tr>

                <tr>
                    <td rowspan="5">Best Verbal Response</td>
                    <td>5. Oriented</td>
                    <?php loopData('oriented') ?>
                </tr>

                <tr>
                    <td>4. Confused</td>
                    <?php loopData('confused') ?>
                </tr>

                <tr>
                    <td>3. Inappropriate Words</td>
                    <?php loopData('inappropriate-words') ?>
                </tr>

                <tr>
                    <td>2. Incomprehensible Sounds</td>
                    <?php loopData('incomprehensible-sounds') ?>
                </tr>

                <tr>
                    <td>1. Nil/Tube</td>
                    <?php loopData('nil-tube') ?>
                </tr>


                <!-- not yet -->
                <tr>
                    <td rowspan="6">Best Motor Response</td>
                    <td>6. Obeys</td>
                    <?php loopData('obeys') ?>
                </tr>
                <!-- not yet -->

                <tr>
                    <td>5. Localizes</td>
                    <?php loopData('localizes') ?>
                </tr>

                <!-- not yet -->
                <tr>
                    <td>4. Withdraw</td>
                    <?php loopData('withdraw') ?>
                </tr>
                <!-- not yet -->

                <tr>
                    <td>3. Abnormal Flexion</td>
                    <?php loopData('abnormal-flexion') ?>
                </tr>

                <tr>
                    <td>2. Extension(Abnormal)</td>
                    <?php loopData('extension') ?>
                </tr>

                <tr>
                    <td>1. Nil</td>
                    <?php loopData('bmr-nil') ?>
                </tr>

                <tr>
                    <td colspan="2">Glasgow Coma Scale Total</td>
                    <?php loopData('gcs-total') ?>
                </tr>

                <tr>
                    <td rowspan="4">
                        Ability To Move
                        <br>
                        S - Strong
                        <br>
                        M - Moderate
                        <br>
                        W - Weak
                        <br>
                        A - Absent
                    </td>
                    <td>R. Arm</td>
                    <?php loopData('r-arm') ?>
                </tr>

                <tr>
                    <td>L. Arm</td>
                    <?php loopData('l-arm') ?>
                </tr>

                <tr>
                    <td>R. Leg</td>
                    <?php loopData('r-leg') ?>
                </tr>

                <tr>
                    <td>L. Leg</td>
                    <?php loopData('l-leg') ?>
                </tr>

                <tr>
                    <td rowspan="2">Pupil Size</td>
                    <td>Right</td>
                    <?php loopData('right-pupil') ?>
                </tr>

                <tr>
                    <td>Left</td>
                    <?php loopData('left-pupil') ?>
                </tr>

                <tr>
                    <td rowspan="2">
                        Reaction
                        <br>
                        · Brisk
                        <br>
                        · Sluggisk
                        <br>
                        · Fixed
                    </td>
                    <!-- not yet -->
                    <td>Right</td>
                    <?php loopData('right-reaction') ?>
                    <!-- not yet -->
                </tr>

                <tr>
                    <td>Left</td>
                    <?php loopData('left-reaction') ?>
                </tr>

                <tr>
                    <td colspan="2">Air Entry</td>
                    <?php loopData('air-entry') ?>
                </tr>

                <tr>
                    <td colspan="2">02 Therapy / FIO2</td>
                    <?php loopData('therapy') ?>
                </tr>

                <tr>
                    <td colspan="2">Ventilator</td>
                    <?php loopData('ventilator') ?>
                </tr>

                <tr>
                    <td colspan="2"> Mode</td>
                    <?php loopData("mode") ?>
                </tr>

                <tr>
                    <td colspan="2">Pressure Support</td>
                    <?php loopData("pressure-support") ?>
                </tr>

                <tr>
                    <td colspan="2">RR SET</td>
                    <?php loopData("rr-set") ?>
                </tr>

                <tr>
                    <td colspan="2">RR Pt</td>
                    <?php loopData("rr-pt") ?>
                </tr>

                <tr>
                    <td colspan="2">Peak Inspiratory Pressure(PIP)</td>
                    <?php loopData('peak-inspiratory-pressure') ?>
                </tr>

                <tr>
                    <td colspan="2">Minute Volume</td>
                    <?php loopData('minute-volume') ?>
                </tr>

                <tr>
                    <td colspan="2">Tidal Volume Set </td>
                    <?php loopData('tidal-volume-set') ?>
                </tr>


                <tr>
                    <td colspan="2">Tidal Volume Pt </td>
                    <?php loopData('tidal-volume-pt') ?>
                </tr>

                <tr>
                    <td colspan="2">PEEP/CPAP</td>
                    <?php loopData('peep-cpap') ?>
                </tr>

                <tr>
                    <td colspan="2">I.E RATIO</td>
                    <?php loopData('ie-ratio') ?>
                </tr>

                <tr>
                    <td colspan="2">ETT Mark</td>
                    <?php loopData('ett-mark') ?>
                </tr>

                <tr>
                    <td colspan="2">POSITION</td>
                    <?php loopData('position') ?>
                </tr>

                <tr>
                    <td colspan="2">Spasm</td>
                    <?php loopData('spasm') ?>
                </tr>

                <tr>
                    <td colspan="2">PH</td>
                    <?php loopData('ph') ?>
                </tr>

                <tr>
                    <td colspan="2">PCO2</td>
                    <?php loopData('pco2') ?>
                </tr>

                <tr>
                    <td colspan="2">PK+</td>
                    <?php loopData('pk') ?>
                </tr>

                <tr>
                    <td colspan="2">Na++</td>
                    <?php loopData('na') ?>
                </tr>

                <tr>
                    <td colspan="2">Mg2+</td>
                    <?php loopData('mg2') ?>
                </tr>

                <tr>
                    <td colspan="2">CI-1</td>
                    <?php loopData('ci') ?>
                </tr>

                <tr>
                    <td colspan="2">HCO-3</td>
                    <?php loopData('hco3') ?>
                </tr>

                <tr>
                    <td colspan="2">PO++4</td>
                    <?php loopData('po-4') ?>
                </tr>

                <tr>
                    <td colspan="2">O2 SAT</td>
                    <?php loopData('sat') ?>
                </tr>

                <tr>
                    <td colspan="2">Blood Sugar</td>
                    <?php loopData('blood-sugar') ?>
                </tr>

                <tr>
                    <td colspan="2">Suction/Secretion</td>
                    <?php loopData('suction-secretion') ?>
                </tr>

                <tr>
                    <td colspan="2">Comments</td>
                    <?php loopData('comments') ?>
                </tr>

                <tr>
                    <td colspan="2">Chest Physiotherapy</td>
                    <?php loopData('chest-physiotherapy') ?>
                </tr>
            </table>
        </div>

        <div class="table-responsive">
            <table id="table-form" width=100% class="table table-sm table-striped table-bordered">
                <thead>
                <tr style="background-color:#eee">
                    <th width="20%;">IV infusion</th>
                    <th>0700</th>
                    <th>0800</th>
                    <th>0900</th>
                    <th>1000</th>
                    <th>1100</th>
                    <th>1200</th>
                    <th>1300</th>
                    <th>1400</th>
                    <th>1500</th>
                    <th>1600</th>
                    <th>1700</th>
                    <th>1800</th>
                    <th>1900</th>
                    <th>2000</th>
                    <th>2100</th>
                    <th>2200</th>
                    <th>2300</th>
                    <th>0000</th>
                    <th>0100</th>
                    <th>0200</th>
                    <th>0300</th>
                    <th>0400</th>
                    <th>0500</th>
                    <th>0600</th>
                </tr>
                </thead>
                <tbody id="infusion_section">
                <?php
                foreach ($infusionData as $infusion) {
                    ?>
                    <tr>
                        <td><?= $infusion['name'] ?></td>
                        <?php

                        $row = $infusion['data'];

                        $index = 7;

                        for ($i = 0; $i < 24; $i++) {
                            $value = isset($row[$index]) ? $row[$index] : '';

                            echo "<td class='text-center'>$value</td>";

                            $index === 23 ? $index = -1 : '';
                            $index++;
                        }

                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <h6 class="text-center mt-3 fw-bold">Form Data By:</h6>
        <div class="table-responsive">
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

        <script language="javascript" type="text/javascript">
            /* <![CDATA[ */
            document.write('<a href="makepdf.php?url=' + encodeURIComponent(location.href) +'">');
            document.write('Create PDF file of this page');
            document.write('</a>');
            /* ]]> */
        </script>
    </div>


<?php include 'footer.php'; ?>
