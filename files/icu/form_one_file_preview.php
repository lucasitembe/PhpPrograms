<?php
ini_set('display_errors', true);
include('new_header.php');
include 'form_one_functions.php';
$form_name = "VITAL SIGNS REPORT PREVIEW";

include 'partials/get_patient_details.php';

if (isset($_GET['record_id'])) {
    $recordId = mysqli_real_escape_string($conn, $_GET['record_id']);
} else {
    $recordId = '';
}

$record = querySelectOne("SELECT * FROM tbl_icu_form_one WHERE id = $recordId");

$data = getFormData($recordId);
$formattedPMH = getPMH($record['consultation_id']);
$remarks = getRemarks($record['consultation_id']);
$diagnosis = getDiagnosis($record['consultation_id']);
$transferReasons = getTransferReasons($record['registration_id'], $record['admission_id']);
$date = date_format(date_create($record['record_date']), 'd-m-Y H:i');
$boundingForms = getBoundingForms($record['registration_id'], $record['consultation_id'], $record['record_date']);

$users = getFormUsers($recordId);

$previousId = $boundingForms['previousId'];
$nextId = $boundingForms['nextId'];

$boundingBtns = "";

// if ($previousId){
//     $boundingBtns .= "<a href='form_one_preview.php?record_id=$previousId&Registration_ID={$record['registration_id']}' class='btn btn-warning fw-bold'>PREVIOUS RECORD</a>";
// }

// if ($nextId){
//     $boundingBtns .= "<a href='form_one_preview.php?record_id=$nextId&Registration_ID={$record['registration_id']}' class='btn btn-success ms-1 fw-bold'>NEXT RECORD</a>";
// } else {
//     $boundingBtns .= "<a href='form_one.php?Registration_ID={$record['registration_id']}&Admision_ID={$record['admission_id']}&consultation_ID={$record['consultation_id']}' class='btn btn-success ms-1 fw-bold'>CURRENT FORM</a>";
// }

$var_counts = 7;

print $boundingBtns;

include 'partials/new_patient_info.php';

?>

    <div class="pt-5 p-1 bg-white" id="print">
        <table class="table table-bordered align-middle my-3 ">

            <thead class="table-light">
                <tr class="text-center">
                    <th colspan="30">
                        <span><center>Done By : <b>---</b> Done On : <b>---</b></center></span>
                    </th>
                </tr>
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

        <br>

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
                <?php loopData('sas') ?>
            </tr>

            <tr>
                <td style="width: 10%;">Pain score (0 - 10)</td>
                <?php loopData('pain_score') ?>
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
                <?php loopData('ibp-systolic') ?>
            </tr>


            <tr>
                <td></td>
                <td style="width: 4%;">NIBP</td>
                <?php loopData('nibp-systolic') ?>
            </tr>

            <tr>
                <td style="width: 8%;" class="border-bottom-0">B.P Diastolic</td>
                <td style="width: 4%;">IBP</td>
                <?php loopData('ibp-diastolic') ?>
            </tr>

            <tr>
                <td></td>
                <td style="width: 4%;">NIBP</td>
                <?php loopData('nibp-diastolic') ?>
            </tr>

            <tr>
                <td style="width: 12%;" class="border-end-0">Pulse Rate</td>
                <td class="border-0"></td>
                <?php loopData('pulse-rate') ?>
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
                <?php loopData('temperature-peripheral') ?>
            </tr>

            <tr>
                <td style="width: 12%;">Temperature (Core)</td>
                <?php loopData('temperature-core') ?>
            </tr>

            <tr>
                <td style="width: 12%;"><span>CVP</span></td>
                <?php loopData('cvp') ?>
            </tr>

            <tr>
                <td style="width: 12%;"><span>Heart Rate</span></td>
                <?php loopData('heart-rate') ?>
            </tr>

            <tr>
                <td style="width: 12%;"><span>Rhythm</span></td>
                <?php loopData('rhythm') ?>
            </tr>

            <tr>
                <td style="width: 12%;">MAP</td>
                <?php loopData('map') ?>
            </tr>

            <tr>
                <td style="width: 12%;"><span>SPO2</span></td>
                <?php loopData('sp02') ?>
            </tr>


            </tbody>
        </table>

        <h6 class="text-center mt-5">Form Data By:</h6>
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

    <script>
    $(document).ready(function (){
        const labels = [
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
            '00:00',
            '01:00',
            '02:00',
            '03:00',
            '04:00',
            '05:00',
            '06:00',
        ];

        const data = {
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

        const temperatureData = {
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

        const config = {
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

        const temperatureConfig = {
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

        const bloodPressureChart = new Chart(
            document.getElementById('blood-pressure-chart'),
            config
        );

        const temperatureChart = new Chart(
            document.getElementById('temperature-chart'),
            temperatureConfig
        );

        window.jsPDF = window.jspdf.jsPDF;
        window.html2canvas = html2canvas;

    })

    function getData(selector){
        let values = [];
        $(selector).map(function (){
            let data = $(this).html();
            values.push(parseFloat(data));
        });

        return values;
    }

    function printReport(){
        let doc = new jsPDF();

        doc.text(20, 20, 'Hello world!')
        doc.text(20, 30, 'This is client-side Javascript, pumping out a PDF.')
        doc.addPage()
        doc.text(20, 20, 'Do you like that?')
        doc.output('pdfobjectnewwindow');

    }
</script>
    <script src="../js/chartjs/chart.min.js"></script>
    <script src="../js/sample/jspdf.umd.min.js"></script>
    <script src="../js/sample/html2canvas.min.js"></script>
    <script src="../js/sample/purify.min.js"></script>
