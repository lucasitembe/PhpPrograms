<?php
ini_set('display_errors', true);

include('new_header.php');
include('form_two_functions.php');
include 'partials/get_patient_details.php';

$form_name = 'GLASGOW COMA SCALE';

$details = getFormDetails($consultation_ID, $Registration_ID, $Admission_ID);
$formId = $details['id'];
$data = $details['data'];
$previousFormId = $details['previousFormId'];
$infusionNames = getInfusionNames($formId);

?>

<a href="#" id="preview_form_two_records" class="btn btn-danger font-weight-bold">PREVIEW RECORD</a>
<!--<a href="form_two_reports.php?consultation_ID=--><?//= $consultation_ID; ?><!--&Registration_ID=--><?//= $_GET['Registration_ID']; ?><!--&Admision_ID=--><?//= $Admision_ID ?><!--"-->
<!--   class="btn btn-success">REPORTS</a>-->
<a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admision_ID ?>"
   class="btn btn-primary">BACK</a>

<?php include 'partials/new_patient_info.php' ?>

<div class="bg-white pt-5 pb-3">

    <table width='100%' class="table table-striped table-hover table-bordered table-sm align-middle">
        <thead class="table-secondary sticky-top">
        <tr>
            <th width="6%"></th>
            <th width='18%' colspan="3"> Glasgow Coma Scale (GCS)</th>
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
            <td rowspan="20" class="table-light align-top">
                <span class="d-block font-weight-bold my-3">Pupil Size (mm)</span>
                <span class="d-block my-3 mx-auto" style="width: 44px; height: 44px; background: black; border-radius: 22px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 40px; height: 40px; background: black; border-radius: 20px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 36px; height: 36px; background: black; border-radius: 18px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 32px; height: 32px; background: black; border-radius: 16px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 28px; height: 28px; background: black; border-radius: 14px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 24px; height: 24px; background: black; border-radius: 12px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 20px; height: 20px; background: black; border-radius: 10px;"></span>
                <span class="d-block my-3 mx-auto" style="width: 16px; height: 16px; background: black; border-radius: 8px;"></span>
            </td>

            <td rowspan="20" class="upright text-center font-weight-bold">CENTRAL NERVOUS SYSTEM</td>
            <td rowspan="4">Eye Opening</td>
            <td>4. Spontaneous</td>
            <?php generateFields('spontaneous', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>3. To Speech</td>
            <?php generateFields('to-speech', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>2. To Pain</td>
            <?php generateFields('to-pain', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>1. Nil</td>
            <?php generateFields('eo-nil', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td rowspan="5">Best Verbal Response</td>
            <td>5. Oriented</td>
            <?php generateFields('oriented', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>4. Confused</td>
            <?php generateFields('confused', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>3. Inappropriate Words</td>
            <?php generateFields('inappropriate-words', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>2. Incomprehensible Sounds</td>
            <?php generateFields('incomprehensible-sounds', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>1. Nil/Tube</td>
            <?php generateFields('nil-tube', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td rowspan="6">Best Motor Response</td>
            <td>6. Obeys</td>
            <?php generateFields('obeys', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>5. Localizes</td>
            <?php generateFields('localizes', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>4. Withdraw</td>
            <?php generateFields('withdraw', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>3. Abnormal Flexion</td>
            <?php generateFields('abnormal-flexion', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>2. Extension(Abnormal)</td>
            <?php generateFields('extension', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td>1. Nil</td>
            <?php generateFields('bmr-nil', 'gcs', 'gcs') ?>
        </tr>

        <tr>
            <td colspan="2">Glasgow Coma Scale Total</td>
            <?php generateFields('gcs-total') ?>
        </tr>

        <tr>
            <td rowspan="4">
                Ability To Move
                <br>
                <b> S - Strong</b>
                <br><br>
                <b> M - Moderate</b>
                <br><br>
                <b> W - Weak</b>
                <br><br>
                <b> A - Absent</b>
            </td>
            <td>R. Arm</td>
            <?php generateFields("r-arm") ?>
        </tr>

        <tr>
            <td>L. Arm</td>
            <?php generateFields("l-arm") ?>
        </tr>

        <tr>
            <td>R. Leg</td>
            <?php generateFields("r-leg") ?>
        </tr>

        <tr>
            <td>L. Leg</td>
            <?php generateFields("l-leg") ?>
        </tr>

        <tr>
            <td rowspan="4" class="table-light"></td>
            <td rowspan="4" class="upright text-center font-weight-bold">PUPIL</td>
            <td rowspan="2">Pupil Size</td>
            <td>Right</td>
            <?php generateFields("right-pupil") ?>
        </tr>

        <tr>
            <td>Left</td>
            <?php generateFields("left-pupil") ?>
        </tr>

        <tr>
            <td rowspan="2">
                Reaction
                <br>
                <b>&#xb7; Brisk</b>
                <br>
                <b>&#xb7; Sluggisk</b>
                <br>
                <b>&#xb7; Fixed</b>
            </td>
            <td>Right</td>
            <?php generateFields("right-reaction") ?>
        </tr>

        <tr>
            <td>Left</td>
            <?php generateFields("left-reaction") ?>
        </tr>

        <tr>
            <td rowspan="14" class="table-light">
                <span class="my-3 d-block font-weight-bold">SPUTUM</span>

                <span class="my-3 d-block"><b>1 - Small</b></span>

                <span class="my-3 d-block"><b>2 - Moderate</b></span>

                <span class="my-3 d-block"><b>3 - Copious</b></span>

                <span class="my-3 d-block"><b>F - Flothy</b></span>

                <span class="my-3 d-block"><b>M - Mucoid</b></span>

                <span class="my-3 d-block"><b>P - Purulent</b></span>

                <span class="my-3 d-block"><b>B - Blood</b></span>

            </td>
            <td rowspan="14" class="upright text-center font-weight-bold">LUNGS MECHANISMS</td>
            <td colspan="2">Air Entry</td>
            <?php generateFields("air-entry") ?>
        </tr>

        <tr>
            <td colspan="2">02 Therapy /FIO2</td>
            <?php generateFields("therapy") ?>
        </tr>

        <tr>
            <td colspan="2">Ventilator</td>
            <?php generateFields("ventilator") ?>
        </tr>

        <tr>
            <td colspan="2"> Mode</td>
            <?php generateFields("mode") ?>
        </tr>

        <tr>
            <td colspan="2">Pressure Support</td>
            <?php generateFields("pressure-support") ?>
        </tr>

        <tr>
            <td colspan="2">RR SET</td>
            <?php generateFields("rr-set") ?>
        </tr>

        <tr>
            <td colspan="2">RR Pt</td>
            <?php generateFields("rr-pt") ?>
        </tr>

        <tr>
            <td colspan="2">Peak Inspiratory Pressure(PIP)</td>
            <?php generateFields("peak-inspiratory-pressure") ?>
        </tr>

        <tr>
            <td colspan="2">Minute Volume</td>
            <?php generateFields("minute-volume") ?>
        </tr>

        <tr>
            <td colspan="2">Tidal Volume Set</td>
            <?php generateFields("tidal-volume-set") ?>
        </tr>

        <tr>
            <td colspan="2">Tidal Volume Pt</td>
            <?php generateFields("tidal-volume-pt") ?>
        </tr>

        <tr>
            <td colspan="2">PEEP/CPAP</td>
            <?php generateFields("peep-cpap") ?>
        </tr>

        <tr>
            <td colspan="2">I.E RATIO</td>
            <?php generateFields("ie-ratio") ?>
        </tr>

        <tr>
            <td colspan="2">ETT Mark</td>
            <?php generateFields("ett-mark") ?>
        </tr>

        <tr>
            <td rowspan="18" class="align-top">

                <span class="mb-3 d-block">
                    <b>POSITION KEY</b>
                </span>

                <span class="my-3 d-block"><b>SP : </b> Supine</span>

                <span class="my-3 d-block"><b>Sf : </b> Semi-Fowler</span>

                <span class="my-3 d-block"><b>St : </b> Right</span>

                <span class="my-3 d-block"><b>Lt : </b> Left</span>

                <span class="my-3 d-block"><b>St : </b> Sitting</span>

                <span class="my-3 d-block"><b>Mo : </b> Moblie</span>
            </td>
            <td rowspan="2"></td>
            <td colspan="2">POSITION</td>
            <?php generateFields("position") ?>
        </tr>

        <tr>
            <td colspan="2">Spasms</td>
            <?php generateFields("spasm") ?>
        </tr>

        <!-- additional  -->
        <tr>
            <td rowspan="16" class="upright text-center font-weight-bold">ARTERIAL GASES</td>
            <td colspan="2">PH</td>
            <?php generateFields("ph") ?>
        </tr>

        <tr>
            <td colspan="2">PCO2</td>
            <?php generateFields("pco2") ?>
        </tr>

        <tr>
            <td colspan="2">PO2</td>
            <?php generateFields("po2") ?>
        </tr>

        <tr>
            <td colspan="2">HCO3</td>
            <?php generateFields("hco3") ?>
        </tr>

        <tr>
            <td colspan="2">PK+</td>
            <?php generateFields("pk") ?>
        </tr>

        <tr>
            <td colspan="2">Na++</td>
            <?php generateFields("na") ?>
        </tr>

        <tr>
            <td colspan="2">Mg2+</td>
            <?php generateFields("mg2") ?>
        </tr>

        <tr>
            <td colspan="2">CI-1</td>
            <?php generateFields("ci") ?>
        </tr>

        <tr>
            <td colspan="2">HCO-3</td>
            <?php generateFields("hco-3") ?>
        </tr>

        <tr>
            <td colspan="2">PO++4</td>
            <?php generateFields("po-4") ?>
        </tr>

        <tr>
            <td colspan="2">BASE</td>
            <?php generateFields("base") ?>
        </tr>

        <tr>
            <td colspan="2">O2 SAT</td>
            <?php generateFields("sat") ?>
        </tr>

        <tr>
            <td colspan="2">Blood Sugar</td>
            <?php generateFields("blood-sugar") ?>
        </tr>

        <tr>
            <td colspan="2">Suction/Secretion</td>
            <?php generateFields("suction-secretion") ?>
        </tr>

        <tr>
            <td colspan="2">Comments</td>
            <?php generateFields("comments") ?>
        </tr>

        <tr>
            <td colspan="2">Chest Physiotherapy</td>
            <?php generateFields("chest-physiotherapy") ?>
        </tr>
    </table>


    <div class="table-responsive">
        <table width='100%' class="table table-sm table-striped table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th style="width: 15%"><b>INFUSION</b></th>
                <th style="width: 3.54%">0700</th>
                <th style="width: 3.54%">0800</th>
                <th style="width: 3.54%">0900</th>
                <th style="width: 3.54%">1000</th>
                <th style="width: 3.54%">1100</th>
                <th style="width: 3.54%">1200</th>
                <th style="width: 3.54%">1300</th>
                <th style="width: 3.54%">1400</th>
                <th style="width: 3.54%">1500</th>
                <th style="width: 3.54%">1600</th>
                <th style="width: 3.54%">1700</th>
                <th style="width: 3.54%">1800</th>
                <th style="width: 3.54%">1900</th>
                <th style="width: 3.54%">2000</th>
                <th style="width: 3.54%">2100</th>
                <th style="width: 3.54%">2200</th>
                <th style="width: 3.54%">2300</th>
                <th style="width: 3.54%">0000</th>
                <th style="width: 3.54%">0100</th>
                <th style="width: 3.54%">0200</th>
                <th style="width: 3.54%">0300</th>
                <th style="width: 3.54%">0400</th>
                <th style="width: 3.54%">0500</th>
                <th style="width: 3.54%">0600</th>
            </tr>
            </thead>

            <tbody id="infusion_rows">
            <?php
            foreach ($infusionNames as $name) {
                ?>
                <tr>
                    <td><input type="text" value="<?= $name['name'] ?>" class="form-control infusion-name tbl-input"
                               id="infusion-name-<?= $name['index'] ?>"></td>
                    <?php $id = 'infusion-input-' . $name['index'];
                    generateFields($id, 'infusion-input'); ?>
                </tr>
                <?php
            }
            ?>

            </tbody>

            <tr>
                <td colspan="25"><a href="#" class="btn btn-primary" id="add_row">ADD ROW</a></td>
            </tr>
        </table>
    </div>

</div>

<div id="form_two_records"></div>

<div id="element-to-print"></div>

<script>
    $(document).ready(function () {
        $(document.body).on('keyup', '.tbl-input', function () {
            var details = getInputDetails(this);

            var Registration_ID = '<?= $Registration_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            var form_id = '<?= $formId ?>';

            $.post('form_two_store.php', {
                form_id: form_id,
                consultation_ID: consultation_ID,
                Registration_ID: Registration_ID,
                name: details.name,
                hour: details.hour,
                value: details.value,
                store: 'form-two'
            }, function (response){
                console.log(response)
            })
        });
    })
</script>

<!-- Infusion Rows-->
<script type="text/javascript">
    var infusionRows = '<?= count($infusionNames) ?>';

    $('#add_row').click(function (e){
        e.preventDefault();
        // check if there are empty fusion rows
        var canAdd = true;

        $('.infusion-name').map(function () {
            if (!$(this).val()) {
                canAdd = false;
                alert('Please fill the available infusion inputs first');
                return;
            }
        })

        if (!canAdd) return;

        infusionRows++;

        $('#infusion_rows').append(getInfusionsFields(infusionRows));
    });

    function getInfusionsFields(index) {
        var hour = 7;
        var className = 'infusion-input form-control px-1 text-center tbl-input';

        var htm = "<tr>";
        htm += "<td><input type='text' class='form-control infusion-name tbl-input' id='infusion-name-" + index + "></td>";

        for (var i = 0; i < 24; i++) {
            htm += "<td><input class='" + className + " infusion-input-" + hour + "' type='text' id='infusion-input-" + index + "-" + hour + "'/></td>";
            hour === 23 ? hour = -1 : '';
            hour++;
        }

        return htm + "</tr>";
    }

</script>

<!-- Helper Functions -->
<script>
    $(document).ready(function (){
        $('.gcs').on('keyup', function () {
            var details = getInputDetails($(this));
            var total = getTotal('.gcs-' + details.hour);
            $('#gcs-total-' + details.hour).val(total).trigger('keyup');
        });
    });

    function getData(selector) {
        var values = [];
        $(selector).map(function () {
            var data = $(this).val();
            values.push(parseFloat(data));
        });

        return values;
    }

    function getTotal(selector) {
        var total = 0;
        $(selector).map(function () {
            var val = parseFloat($(this).val());
            if (isNaN(val)) {
                val = 0;
            }
            total += val;
        })
        return total;
    }


    function getInputDetails(input) {
        var id = $(input).prop('id');
        var value = $(input).val();
        var splitted = id.split('-');
        var hour = splitted[splitted.length - 1];
        var name = '';
        for (var i = 0; i < splitted.length - 1; i++) {
            name += splitted[i];
            name += '-'
        }
        name = name.substring(0, name.length - 1)

        return {id: id, value: value, hour: hour, name: name};
    }
</script>

<!-- Preview and Print -->
<script>
    $(document).ready(function (){
        var Registration_ID = '<?= $Registration_ID ?>';
        var consultation_ID = '<?= $consultation_ID ?>';

        $('#preview_form_two_records').click(function (e) {

            $.get(
                'form_two_preview_list.php', {
                    Registration_ID: Registration_ID,
                    consultation_ID: consultation_ID,
                    records_list: 'form_two'
                }, function (data){
                    $("#form_two_records").dialog({
                        title: "FORM TWO RECORDS LIST",
                        width: "90%",
                        height: 800,
                        modal: true
                    });
                    $("#form_two_records").html(data);
                    $("#form_two_records").dialog("open");
                });
        });


    });
</script>

<?php include "footer.php"; ?>
