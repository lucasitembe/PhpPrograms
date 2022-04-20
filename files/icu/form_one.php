<?php
//ini_set('display_errors', true);
include('new_header.php');
include('form_one_functions.php');
include 'partials/get_patient_details.php';
$form_name = "VITAL SIGNS";
# counts
$var_counts = 0;

// Get diagnosis
$diagnosis = getDiagnosis($consultation_ID);
$date = date('Y-m-d');
$details = getFormDetails($consultation_ID, $Registration_ID, $Admission_ID);
$formId = $details['id'];
$data = $details['data'];
$remarks = $details['remarks'];
$transferReasons = $details['transferReasons'];
$formattedPMH = $details['formattedPMH'];
$previousFormId = $details['previousFormId'];

?>

    <a href="#" class="btn btn-danger font-weight-bold" id='form_one_records'>PREVIEW RECORDS</a>

    <a href="form_one_preview.php?record_id=<?= $previousFormId ?>&Registration_ID=<?= $Registration_ID ?>" class="btn btn-warning font-weight-bold">PREVIOUS RECORD</a>

    <a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admision_ID ?>"
    class="btn btn-primary">BACK</a>

    <div id="demo"></div>

<?php include "partials/new_patient_info.php"; ?>

    <div class="bg-white pt-5 px-2">
        <table class="table table-bordered align-middle my-3 ">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="width: 20%" >Date</th>
                    <td style="width: 80%"><?= $date ?></td>
                </tr>
                <tr>
                    <th style="width: 20%" >Previous Medical History (PMH)</th>
                    <td style="width: 80%"><?= $formattedPMH ?></td>
                </tr>
                <tr>
                    <th style="width: 20%" >Diagnosis (Provisional Diagnosis)</th>
                    <td style="width: 80%"><?= $diagnosis['provisional_diagnosis'] ?></td>
                </tr>
                <tr>
                    <th style="width: 20%" >Differential Diagnosis</th>
                    <td style="width: 80%"><?= $diagnosis['differential_diagnosis'] ?></td>
                </tr>
                <tr>
                    <th style="width: 20%" >Working (Final Diagnosis)</th>
                    <td style="width: 80%"><?= $diagnosis['final_diagnosis'] ?></td>
                </tr>
                <tr>
                    <th style="width: 20%" >Reasons for ICU Admission</th>
                    <td style="width: 80%">
                        <?= $remarks ?>
                        <br>
                        <?= $transferReasons ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row px-4 py-3">
            <div class="col-md-6">
                <h6 class="mt-3 mb-2 text-uppercase">Sedation - Agitation Scale</h6>
                <div class="row">
                    <div class="col-md-6">
                        <span class="d-block my-1">0 - <b>Unresponse</b></span>
                        <span class="d-block my-1">1 - <b>Response only to deep painful stimuli</b></span>
                        <span class="d-block my-1">2 - <b>Response to touch or name</b></span>
                        <span class="d-block my-1">3 - <b>Calm and cooperative</b></span>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block my-1">4 - <b>Restless but cooperative</b></span>
                        <span class="d-block my-1">5 - <b>Agitated</b></span>
                        <span class="d-block my-1">6 - <b>Dangerously agitated and uncooperative</b></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mt-3 mb-2">RHYTHM KEY</h6>
                <div class="row">
                    <div class="col-md-6">
                        <span class="d-block my-1">SR - <b>Sinus rhythm</b></span>
                        <span class="d-block my-1">SB - <b>Sinus Bradycardia</b></span>
                        <span class="d-block my-1">ST - <b>Sinus Tachycardia</b></span>
                        <span class="d-block my-1">SVT - <b>Supra Ventricular tacycardia</b></span>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block my-1">VF - <b>Ventricular Fibrillation</b></span>
                        <span class="d-block my-1">VT - <b>Ventricular Fibrillation</b></span>
                        <span class="d-block my-1">A - <b>Asystole</b></span>
                        <span class="d-block my-1">AF - <b>Atrial Fibrillation / flutter</b></span>
                    </div>
                </div>
            </div>
        </div>

        <table id="tables" width='100%' class="table table-striped table-hover table-bordered table-sm">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="width: 10%;" class="table-secondary text-start sticky-top index-1">Names</th>
                    <?php for ($i = 1; $i < 25; $i++) { ?>
                        <th style="width: 3.75%;" class="table-secondary text-center sticky-top index-1"><?= $var_counts; ?>:00
                        </th>
                        <?php if ($var_counts == 23) {
                            $var_counts = -1;
                        } ?>
                        <?php $var_counts++;
                    } ?>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width: 10%;">SAS</td>
                    <?php perform_counts('sas') ?>
                </tr>

                <tr>
                    <td style="width: 10%;">Pain score (0 - 10)</td>
                    <?php perform_counts('pain_score') ?>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-12 p-4" style="height: 440px; width: 100%;">
                <canvas id="blood-pressure-chart" style="width: 100%;"></canvas>
            </div>
        </div>

        <table id="tables" width='100%' class="table table-striped table-hover table-bordered table-sm align-middle">
            <colgroup></colgroup>
            <colgroup></colgroup>
            <?php for ($i = 1; $i < 25; $i++) { ?>
                <colgroup></colgroup>
            <?php } ?>
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="width: 12%;" colspan="2" class="table-secondary text-start sticky-top index-1">Names</th>
                    <?php for ($i = 1; $i < 25; $i++) { ?>
                        <th style="width: 3.5%;" class="table-secondary text-center sticky-top index-1"><?= $var_counts; ?>:00
                        </th>
                        <?php if ($var_counts == 23) {
                            $var_counts = -1;
                        } ?>
                        <?php $var_counts++;
                    } ?>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td style="width: 8%;">B.P Systolic</td>
                    <td style="width: 4%;">IBP</td>
                    <?php perform_counts('ibp-systolic') ?>
                </tr>


                <tr>
                    <td></td>
                    <td style="width: 4%;">NIBP</td>
                    <?php perform_counts('nibp-systolic') ?>
                </tr>

                <tr>
                    <td style="width: 8%;" class="border-bottom-0">B.P Diastolic</td>
                    <td style="width: 4%;">IBP</td>
                    <?php perform_counts('ibp-diastolic') ?>
                </tr>

                <tr>
                    <td></td>
                    <td style="width: 4%;">NIBP</td>
                    <?php perform_counts('nibp-diastolic') ?>
                </tr>

                <tr>
                    <td style="width: 12%;" class="border-right-0">Pulse Rate</td>
                    <td class="border-0"></td>
                    <?php perform_counts('pulse-rate') ?>
                </tr>



            </tbody>
        </table>

        <div class="row">
            <div class="col-md-12 p-4" style="height: 440px; width: 100%;">
                <canvas id="temperature-chart" style="width: 100%;"></canvas>
            </div>
        </div>

        <table id="tables" width='100%' class="table table-striped table-hover table-bordered table-sm align-middle">
            <colgroup></colgroup>
            <?php for ($i = 1; $i < 25; $i++) { ?>
                <colgroup></colgroup>
            <?php } ?>
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="width: 12%;" class="table-secondary text-start sticky-top index-1">Names</th>
                    <?php for ($i = 1; $i < 25; $i++) { ?>
                        <th style="width: 3.66%;" class="table-secondary text-center sticky-top index-1"><?= $var_counts; ?>:00
                        </th>
                        <?php if ($var_counts == 23) {
                            $var_counts = -1;
                        } ?>
                        <?php $var_counts++;
                    } ?>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width: 12%;">Temperature (Peripheral)</td>
                    <?php perform_counts('temperature-peripheral') ?>
                </tr>

                <tr>
                    <td style="width: 12%;">Temperature (Core)</td>
                    <?php perform_counts('temperature-core') ?>
                </tr>

                <tr>
                    <td style="width: 12%;"><span>CVP</span></td>
                    <?php perform_counts('cvp') ?>
                </tr>

                <tr>
                    <td style="width: 12%;"><span>Heart Rate</span></td>
                    <?php perform_counts('heart-rate') ?>
                </tr>

                <tr>
                    <td style="width: 12%;"><span>Rhythm</span></td>
                    <?php perform_counts('rhythm') ?>
                </tr>

                <tr>
                    <td style="width: 12%;">MAP</td>
                    <?php perform_counts('map') ?>
                </tr>

                <tr>
                    <td style="width: 12%;"><span>SPO2</span></td>
                    <?php perform_counts('sp02') ?>
                </tr>


            </tbody>
        </table>

    </div>

    <div id="form_one_records_preview"></div>

    <div id="records-preview-list-dialog"></div>

    <script>
        var selectedDiagnosis = [];

        $(document).ready(function () {

            // Vertical columns hovering
            $(".table").delegate('td', 'mouseover mouseleave', function (e) {
                if (e.type == 'mouseover') {
                    $(this).parent().addClass("hover");
                    $("colgroup").eq($(this).index()).addClass("hover");
                } else {
                    $(this).parent().removeClass("hover");
                    $("colgroup").eq($(this).index()).removeClass("hover");
                }
            });

            $("#records-preview-list-dialog").dialog({
                autoOpen: false,
                minWidth: 600,
                modal: true,
                title: 'ICU FORM ONE RECORDS PREVIEW',
                width: "75%",
                height: 600,
            });

            var dialog = $('#records-preview-list-dialog');

            $('#form_one_records').click(function () {
                var Registration_ID = '<?= $Registration_ID ?>';
                var consultation_ID = '<?= $consultation_ID ?>';

                $.get(
                    'form_one_preview_list.php', {
                        records_list: 'form_one',
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    }, function (response){
                        dialog.html(response)
                        dialog.dialog('open');
                    }
                );
            });

            var labels = [
                '00:00',
                '01:00',
                '02:00',
                '03:00',
                '04:00',
                '05:00',
                '06:00',
                '07:00',
                '08:00',
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00',
                '16:00',
                '17:00',
                '18:00',
                '19:00',
                '20:00',
                '21:00',
                '22:00',
                '23:00',
            ];

            var data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Systolic IBP',
                        data: getData('.ibp-systolic'),
                        borderColor: '#b92323',
                        backgroundColor: '#b92323',
                        pointStyle: 'triangle',
                        spanGaps: true
                    },
                    {
                        label: 'Systolic NIBP',
                        data: getData('.nibp-systolic'),
                        borderColor: '#4b4743',
                        backgroundColor: "#4b4743",
                        pointStyle: 'star',
                        spanGaps: true
                    },
                    {
                        label: 'Diastolic IBP',
                        data: getData('.ibp-diastolic'),
                        borderColor: '#b92323',
                        backgroundColor: '#b92323',
                        pointStyle: 'rect',
                        spanGaps: true,
                    },
                    {
                        label: 'Diastolic NIBP',
                        data: getData('.nibp-diastolic'),
                        borderColor: '#ff8934',
                        backgroundColor: "#ff8934",
                        pointStyle: 'rectRot',
                        spanGaps: true
                    },
                    {
                        label: 'Pulse Rate',
                        data: getData('.pulse-rate'),
                        borderColor: '#58a846',
                        backgroundColor: "#58a846",
                        pointStyle: 'circle',
                        spanGaps: true
                    },
                ]
            };

            var temperatureData = {
                labels: labels,
                datasets: [
                    {
                        label: 'Peripheral',
                        data: getData('.temperature-peripheral'),
                        borderColor: '#b92323',
                        backgroundColor: '#b92323',
                        pointStyle: 'triangle',
                        spanGaps: true
                    },
                    {
                        label: 'Core',
                        data: getData('.temperature-core'),
                        borderColor: '#1e7594',
                        backgroundColor: '#1e7594',
                        pointStyle: 'star',
                        spanGaps: true
                    },
                ],
            };

            var config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        point: {
                            radius: 5,
                            hoverRadius: 6,
                            hitRadius: 5
                        }
                    },
                    scales: {
                        y: {
                            suggestedMax: 200,
                            suggestedMin: 40,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Blood Pressure & Pulse Rate'
                        }
                    }
                },
            };

            var temperatureConfig = {
                type: 'line',
                data: temperatureData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        point: {
                            radius: 5,
                            hoverRadius: 6,
                            hitRadius: 5
                        }
                    },
                    scales: {
                        y: {
                            suggestedMax: 42,
                            suggestedMin: 35,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Temperature °C'
                        }
                    }
                },
            };

            var bloodPressureChart = new Chart(
                document.getElementById('blood-pressure-chart'),
                config
            );

            var temperatureChart = new Chart(
                document.getElementById('temperature-chart'),
                temperatureConfig
            );

            $('.ibp-systolic').on('change keyup', function (){
                bloodPressureChart.data.datasets[0].data = getData('.ibp-systolic');
                bloodPressureChart.update();
            });

            $('.nibp-systolic').on('change keyup', function (){
                bloodPressureChart.data.datasets[1].data = getData('.nibp-systolic');;
                bloodPressureChart.update();
            });

            $('.ibp-diastolic').on('change keyup', function (){
                bloodPressureChart.data.datasets[2].data = getData('.ibp-diastolic');
                bloodPressureChart.update();
            });

            $('.nibp-diastolic').on('change keyup', function (){
                bloodPressureChart.data.datasets[3].data = getData('.nibp-diastolic');;
                bloodPressureChart.update();
            });

            $('.pulse-rate').on('change keyup', function (){
                bloodPressureChart.data.datasets[4].data = getData('.pulse-rate');
                bloodPressureChart.update();
            });


            $('.temperature-peripheral').on('change keyup', function (){
                temperatureChart.data.datasets[0].data = getData('.temperature-peripheral');
                temperatureChart.update();
            });

            $('.temperature-core').on('change keyup', function (){
                temperatureChart.data.datasets[1].data = getData('.temperature-core');
                temperatureChart.update();
            });

            $('.tbl-input').on('keyup', function(){
                var details = getInputDetails(this);

                var Registration_ID = '<?= $Registration_ID ?>';
                var consultation_ID = '<?= $consultation_ID ?>';
                var form_id = '<?= $formId ?>';

                $.post('form_one_store.php', {
                    form_id: form_id,
                    consultation_ID: consultation_ID,
                    Registration_ID: Registration_ID,
                    name: details.name,
                    hour: details.hour,
                    value: details.value,
                    store: 'form-one'
                }, function (response){
                    console.log(response)
                })
            });

        });

        function getData(selector){
            var values = [];
            $(selector).map(function (){
                var data = $(this).val();
                values.push(parseFloat(data));
            });

            return values;
        }

        function getInputDetails(input){
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
    <script src="../js/chartjs/chart.min.js"></script>

<?php
include("footer.php");
?>