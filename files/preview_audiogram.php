<?php
include("./includes/header.php");
include("./button_configuration.php");
include("./get_audiology_data.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = '';
}


if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}


if (isset($_GET['Sponsor'])) {
    $Sponsor = $_GET['Sponsor'];
} else {
    $Sponsor = '';
}
$Registration_ID = $_GET['Registration_id'];
$Payment_Item_Cache_List_ID = 0;
if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
}

if (isset($_GET['Registration_id']) && $_GET['Registration_id'] != 0) {
    $select_patien_details = mysqli_query($conn, "
          SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
              FROM
                  tbl_patient_registration pr,
                  tbl_sponsor sp
              WHERE
                  pr.Registration_ID = '" . $Registration_ID . "' AND
                  sp.Sponsor_ID = pr.Sponsor_ID
                  ") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;

list($jerger_type_right, $admittance_right, $pressure_right, $width_right, $volume_right, $jerger_type_left, $admittance_left, $pressure_left, $width_left, $volume_left) = get_tympanomerty($conn, $Registration_ID, $Payment_Item_Cache_List_ID);

list($date, $equipment, $right_otoscopy, $left_otoscopy, $recommendation) = get_audiology($conn, $Registration_ID, $Payment_Item_Cache_List_ID);

?>

<fieldset>
    <legend align='center'><b>AUDIOLOGY /HEARING EVALUATION PREVIEW</b></legend>

    <table class="table table-striped table-hover" style="margin-bottom: 10px;">
        <tr>
            <td style="font-size: 14px;"><b>Name</b></td>
            <td style="font-size: 14px;"><?= $Patient_Name ?></td>
            <td style="font-size: 14px;"><b>Date of Birth</b></td>
            <td style="font-size: 14px;"><?= $DOB ?></td>
            <td style="font-size: 14px;"><b>Date</b></td>
            <td style="font-size: 14px;"><?= $date ?></td>
        </tr>

        <tr>
            <td style="font-size: 14px;"><b>Audiometrist</b></td>
            <td style="font-size: 14px;"><?= $Employee_Name ?></td>
            <td colspan="2" style="font-size: 14px;"><b>Equipment</b></td>
            <td colspan="2" style="font-size: 14px;"><?= $equipment ?></td>
        </tr>
    </table>

    <center>
        <div class="row">
            <div id="audiogram_chart" style="width: 98%; height: 500px;"></div>
            <div class="col-md-12">
                <table class="table table-striped table-hover" style="margin-top: 10px;">
                    <tr>
                        <td style="background-color: rgb(100, 55, 124);"></td>
                        <td><b>Normal Hearing</b></td>
                        <td style="background-color: rgb(50, 55, 30);"></td>
                        <td><b>Mild Hearing Loss</b></td>
                        <td style="background-color: rgb(133, 120, 24);"></td>
                        <td><b>Moderate Hearing Loss</b></td>
                        <td style="background-color: rgb(89, 198, 154);"></td>
                        <td><b>Moderately Severe Hearing Loss</b></td>
                        <td style="background-color: rgb(66, 98, 144);"></td>
                        <td><b>Severe Hearing Loss</b></td>
                        <td style="background-color: rgb(170, 98, 150);"></td>
                        <td><b>Profound Hearing Loss</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-hover" style="margin-top: 10px;">
                    <tr>
                        <td colspan="2" style="text-align: center;background-color:#bdb5ac;"><b>OTOSCOPY</b></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;">RIGHT</td>
                        <td style="font-size: 14px;"><?= $right_otoscopy ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;">LEFT</td>
                        <td style="font-size: 14px;"><?= $left_otoscopy ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 14px;"><b>RESULT/RECOMENDATIONS</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 14px;"><?= $recommendation ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-striped table-hover" style="margin-top: 10px;">
                    <tr>
                        <td colspan="6" style="text-align: center;background-color:#bdb5ac;"><b>TYMPANOMETRY</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>JERGER TYPE</td>
                        <td>ADMITTANCE</td>
                        <td>PRESSURE</td>
                        <td>WIDTH</td>
                        <td>Volume</td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;"><b>R</b></td>
                        <td style="font-size: 14px;"><?= $jerger_type_right ?></td>
                        <td style="font-size: 14px;"><?= $admittance_right ?><span>  ml</span></td>
                        <td style="font-size: 14px;"><?= $pressure_right ?><span>  daPa</span></td>
                        <td style="font-size: 14px;"><?= $width_right ?><span>  daPa</span></td>
                        <td style="font-size: 14px;"><?= $volume_right ?><span>  cm<sup>3</sup></span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;"><b>L</b></td>
                        <td style="font-size: 14px;"><?= $jerger_type_left ?></td>
                        <td style="font-size: 14px;"><?= $admittance_left ?><span>  ml</span></td>
                        <td style="font-size: 14px;"><?= $pressure_left ?><span>  daPa</span></td>
                        <td style="font-size: 14px;"><?= $width_left ?><span>  daPa</span></td>
                        <td style="font-size: 14px;"><?= $volume_left ?><span>  cm<sup>3</sup></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
</fieldset>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="jqplot/jquery.min.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.logAxisRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.css" />
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/smoothness/jquery-ui.css" />

<script type="text/javascript">
    $(document).ready(function() {

        plotAudiogram();

        $("#save_right").click(function(e) {

            e.preventDefault();

            addAudiogramRight();
        });

        $("#save_left").click(function(e) {

            e.preventDefault();

            addAudiogramLeft();
        });

        getAudiogramRight();

        getAudiogramLeft();

        $("#save_tympanometry").click(function(e) {

            e.preventDefault();

            addTympanometry();
        });

        $("#save_audiology").click(function(e) {

            e.preventDefault();

            addAudiology();
        });

    });

    var normal_hearing = [
        [0, 10],
        [8000, 10]
    ];

    var mild_hearing = [
        [0, 30],
        [8000, 30]
    ];

    var moderate_hearing = [
        [0, 50],
        [8000, 50]
    ];

    var moderately_hearing = [
        [0, 60],
        [8000, 60]
    ];

    var severe_hearing = [
        [0, 80],
        [8000, 80]
    ];

    var profound_hearing = [
        [0, 100],
        [8000, 100]
    ];

    var unmasked_ac = [
        [],
    ];

    var masked_ac = [
        []
    ];

    var unmasked_bc = [
        []
    ];

    var masked_bc = [
        []
    ];

    var no_response = [
        []
    ];

    var left_unmasked_ac = [
        [],
    ];

    var left_masked_ac = [
        []
    ];

    var left_unmasked_bc = [
        []
    ];

    var left_masked_bc = [
        []
    ];

    var left_no_response = [
        []
    ];

    function plotAudiogram() {

        var air_unmasked_img = new Image();

        var air_masked_img = new Image();

        var bone_unmasked_img = new Image();

        var bone_masked_img = new Image();

        var no_response_img = new Image();

        var left_air_unmasked_img = new Image();

        var left_air_masked_img = new Image();

        var left_bone_unmasked_img = new Image();

        var left_bone_masked_img = new Image();

        var left_no_response_img = new Image();

        air_unmasked_img.src = 'audiology_image/attachement.png';

        air_masked_img.src = 'audiology_image/right_air_masked_red.png';

        bone_unmasked_img.src = 'audiology_image/right_bone_unmasked_red.png';

        bone_masked_img.src = 'audiology_image/right_bone_masked_red.png';

        no_response_img.src = 'audiology_image/aide_red.png';

        left_air_unmasked_img.src = 'audiology_image/left_air_unmasked_blue.png';

        left_air_masked_img.src = 'audiology_image/left_air_mask_blue.png';

        left_bone_unmasked_img.src = 'audiology_image/left_bone_unmasked_blue.png';

        left_bone_masked_img.src = 'audiology_image/left_bone_masked_blue.png';

        left_no_response_img.src = 'audiology_image/aide_blue.png';

        $.jqplot.config.enablePlugins = true;

        var audiogram_plot = $.jqplot('audiogram_chart', [normal_hearing, mild_hearing, moderate_hearing, moderately_hearing, severe_hearing, profound_hearing, unmasked_ac, masked_ac, unmasked_bc, masked_bc, no_response, left_unmasked_ac, left_masked_ac, left_unmasked_bc, left_masked_bc, left_no_response], {
            title: 'Pure-Tone Audiogram',
            axes: {
                xaxis: {
                    label: "Frequency in Hertz (Hz)",
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    ticks: [125, 250, 500, 1000, 1500, 2000, 4000, 6000, 8000],
                    tickOptions: {
                        angle: -90
                    }
                },
                yaxis: {
                    label: "Hearing Level in Decibels (dB)",
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    ticks: [110, 100, 90, 80, 70, 60, 50, 40, 30, 20, 10, 0, -10],
                    tickOptions: {
                        fontSize: '12pt'
                    }
                },
            },
            grid: {
                drawBorder: true,
                shadow: false,
                background: 'white'
            },
            highlighter: {
                show: true,
                formatString: '%s',
                tooltipLocation: 'ne',
                useAxesFormatters: false,
                tooltipContentEditor: function(str, seriesIndex, pointIndex, plot) {
                    var frequency = plot.data[seriesIndex][pointIndex][0];
                    var hearing = plot.data[seriesIndex][pointIndex][1];
                    var display = plot.data[seriesIndex][pointIndex][2];
                    var html = "<div style='color:blue; font-size: 20px;'>";
                    html += display;
                    html += "  <br>Frequency :";
                    html += frequency;
                    html += " Hz";
                    html += "  <br>Hearing :";
                    html += hearing;
                    html += " dB  </div>";
                    return html;
                }
            },
            series: [{
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(100, 55, 124)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(50, 55, 30)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(133, 120, 24)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(89, 198, 154)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(66, 98, 144)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    fill: false,
                    fillAndStroke: true,
                    color: 'rgb(170, 98, 150)',
                    fillColor: '#000',
                    showLine: true,
                    markerOptions: {
                        size: 5,
                        style: "x"
                    }
                },
                {
                    color: 'red',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        size: 5,
                        imageElement: air_unmasked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'red',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        size: 7,
                        imageElement: air_masked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'red',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        size: 9,
                        imageElement: bone_unmasked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'red',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        size: 11,
                        imageElement: bone_masked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'red',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        size: 13,
                        imageElement: no_response_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'blue',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        imageElement: left_air_unmasked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'blue',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        imageElement: left_air_masked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'blue',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        imageElement: left_bone_unmasked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'blue',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        imageElement: left_bone_masked_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                },
                {
                    color: 'blue',
                    fillColor: '#000',
                    markerRenderer: $.jqplot.ImageMarkerRenderer,
                    markerOptions: {
                        show: true,
                        imageElement: left_no_response_img,
                        xOffset: -7,
                        yOffset: -7,
                    }
                }
            ]
        });
    }

    function addAudiology() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        var date = $("#date").val();

        var equipment = $("#equipment").val();

        var right_otoscopy = $("#right_otoscopy").val();

        var left_otoscopy = $("#left_otoscopy").val();

        var recommendation = $("#recommendation").val();

        $.ajax({
            type: "POST",
            url: "add_audiology.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id,
                date: date,
                equipment: equipment,
                right_otoscopy: right_otoscopy,
                left_otoscopy: left_otoscopy,
                recommendation: recommendation
            },
            success: function(data) {

                audiology_data = JSON.parse(data);

                alert(audiology_data[5]);

                $("#date").val(audiology_data[0]);

                $("#equipment").val(audiology_data[1]);

                $("#right_otoscopy").val(audiology_data[2]);

                $("#left_otoscopy").val(audiology_data[3]);

                $("#recommendation").val(audiology_data[4]);

            }
        });

    }


    function addTympanometry() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        var jerger_type_right = $("#jerger_type_right").val();

        var admittance_right = $("#admittance_right").val();

        var pressure_right = $("#pressure_right").val();

        var width_right = $("#width_right").val();

        var volume_right = $("#volume_right").val();

        var jerger_type_left = $("#jerger_type_left").val();

        var admittance_left = $("#admittance_left").val();

        var pressure_left = $("#pressure_left").val();

        var width_left = $("#width_left").val();

        var volume_left = $("#volume_left").val();

        $.ajax({
            type: "POST",
            url: "add_tympanometry.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id,
                jerger_type_right: jerger_type_right,
                admittance_right: admittance_right,
                pressure_right: pressure_right,
                width_right: width_right,
                volume_right: volume_right,
                jerger_type_left: jerger_type_left,
                admittance_left: admittance_left,
                pressure_left: pressure_left,
                width_left: width_left,
                volume_left: volume_left
            },
            success: function(data) {

                tympanometry_data = JSON.parse(data);

                $("#jerger_type_right").val(tympanometry_data[0]);

                $("#admittance_right").val(tympanometry_data[1]);

                $("#pressure_right").val(tympanometry_data[2]);

                $("#width_right").val(tympanometry_data[3]);

                $("#volume_right").val(tympanometry_data[4]);

                $("#jerger_type_left").val(tympanometry_data[5]);

                $("#admittance_left").val(tympanometry_data[6]);

                $("#width_left").val(tympanometry_data[8]);

                $("#volume_left").val(tympanometry_data[9]);

                $("#pressure_left").val(tympanometry_data[7]);
            }
        });

    }

    function addAudiogramRight() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        var frequency_unmasked_ac = $("#frequency_unmasked_ac").val();

        var hearing_unmasked_ac = $("#hearing_unmasked_ac").val();

        var frequency_masked_ac = $("#frequency_masked_ac").val();

        var hearing_masked_ac = $("#hearing_masked_ac").val();

        var frequency_unmasked_bc = $("#frequency_unmasked_bc").val();

        var hearing_unmasked_bc = $("#hearing_unmasked_bc").val();

        var frequency_masked_bc = $("#frequency_masked_bc").val();

        var hearing_masked_bc = $("#hearing_masked_bc").val();

        var frequency_no_reponse = $("#frequency_no_reponse").val();

        var hearing_no_response = $("#hearing_no_response").val();

        $.ajax({
            type: "POST",
            url: "add_audiogram_right.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id,
                frequency_unmasked_ac: frequency_unmasked_ac,
                hearing_unmasked_ac: hearing_unmasked_ac,
                frequency_masked_ac: frequency_masked_ac,
                hearing_masked_ac: hearing_masked_ac,
                frequency_unmasked_bc: frequency_unmasked_bc,
                hearing_unmasked_bc: hearing_unmasked_bc,
                frequency_masked_bc: frequency_masked_bc,
                hearing_masked_bc: hearing_masked_bc,
                frequency_no_reponse: frequency_no_reponse,
                hearing_no_response: hearing_no_response
            },
            success: function(data) {

                var audiogram_right = JSON.parse(data);

                var point1 = [];

                var point2 = [];

                var point3 = [];

                var point4 = [];

                var point5 = [];

                if(audiogram_right[0] == 0){
                    audiogram_right[0] = "NULL";
                }

                if(audiogram_right[1] == 0){
                    audiogram_right[1] = "NULL";
                }

                if(audiogram_right[2] == 0){
                    audiogram_right[2] = "NULL";
                }

                if(audiogram_right[3] == 0){
                    audiogram_right[3] = "NULL";
                }

                if(audiogram_right[4] == 0){
                    audiogram_right[4] = "NULL";
                }

                if(audiogram_right[5] == 0){
                    audiogram_right[5] = "NULL";
                }

                if(audiogram_right[6] == 0){
                    audiogram_right[6] = "NULL";
                }

                if(audiogram_right[7] == 0){
                    audiogram_right[7] = "NULL";
                }

                if(audiogram_right[8] == 0){
                    audiogram_right[8] = "NULL";
                }

                if(audiogram_right[9] == 0){
                    audiogram_right[9] = "NULL";
                }

                point1 = [audiogram_right[0], audiogram_right[1], audiogram_right[10]];

                point2 = [audiogram_right[2], audiogram_right[3], audiogram_right[11]];

                point3 = [audiogram_right[4], audiogram_right[5], audiogram_right[12]];

                point4 = [audiogram_right[6], audiogram_right[7], audiogram_right[13]];

                point5 = [audiogram_right[8], audiogram_right[9], audiogram_right[14]];

                unmasked_ac.push(point1);

                masked_ac.push(point2);

                unmasked_bc.push(point3);

                masked_bc.push(point4);

                no_response.push(point5);

                plotAudiogram();

                $("#frequency_unmasked_ac").val("");

                $("#hearing_unmasked_ac").val("");

                $("#frequency_masked_ac").val("");

                $("#hearing_masked_ac").val("");

                $("#frequency_unmasked_bc").val("");

                $("#hearing_unmasked_bc").val("");

                $("#frequency_masked_bc").val("");

                $("#hearing_masked_bc").val("");

                $("#frequency_no_reponse").val("");

                $("#hearing_no_response").val("");
            }
        });
    }

    function addAudiogramLeft() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        var left_frequency_unmasked_ac = $("#left_frequency_unmasked_ac").val();

        var left_hearing_unmasked_ac = $("#left_hearing_unmasked_ac").val();

        var left_frequency_masked_ac = $("#left_frequency_masked_ac").val();

        var left_hearing_masked_ac = $("#left_hearing_masked_ac").val();

        var left_frequency_unmasked_bc = $("#left_frequency_unmasked_bc").val();

        var left_hearing_unmasked_bc = $("#left_hearing_unmasked_bc").val();

        var left_frequency_masked_bc = $("#left_frequency_masked_bc").val();

        var left_hearing_masked_bc = $("#left_hearing_masked_bc").val();

        var left_frequency_no_reponse = $("#left_frequency_no_reponse").val();

        var left_hearing_no_response = $("#left_hearing_no_response").val();

        $.ajax({
            type: "POST",
            url: "add_audiogram_left.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id,
                left_frequency_unmasked_ac: left_frequency_unmasked_ac,
                left_hearing_unmasked_ac: left_hearing_unmasked_ac,
                left_frequency_masked_ac: left_frequency_masked_ac,
                left_hearing_masked_ac: left_hearing_masked_ac,
                left_frequency_unmasked_bc: left_frequency_unmasked_bc,
                left_hearing_unmasked_bc: left_hearing_unmasked_bc,
                left_frequency_masked_bc: left_frequency_masked_bc,
                left_hearing_masked_bc: left_hearing_masked_bc,
                left_frequency_no_reponse: left_frequency_no_reponse,
                left_hearing_no_response: left_hearing_no_response
            },
            success: function(data) {

                var audiogram_left = JSON.parse(data);

                var point1 = [];

                var point2 = [];

                var point3 = [];

                var point4 = [];

                var point5 = [];

                if(audiogram_left[0] == 0){
                    audiogram_left[0] = "NULL";
                }

                if(audiogram_left[1] == 0){
                    audiogram_left[1] = "NULL";
                }

                if(audiogram_left[2] == 0){
                    audiogram_left[2] = "NULL";
                }

                if(audiogram_left[3] == 0){
                    audiogram_left[3] = "NULL";
                }

                if(audiogram_left[4] == 0){
                    audiogram_left[4] = "NULL";
                }

                if(audiogram_left[5] == 0){
                    audiogram_left[5] = "NULL";
                }

                if(audiogram_left[6] == 0){
                    audiogram_left[6] = "NULL";
                }

                if(audiogram_left[7] == 0){
                    audiogram_left[7] = "NULL";
                }

                if(audiogram_left[8] == 0){
                    audiogram_left[8] = "NULL";
                }

                if(audiogram_left[9] == 0){
                    audiogram_left[9] = "NULL";
                }

                point1 = [audiogram_left[0], audiogram_left[1], audiogram_left[10]];

                point2 = [audiogram_left[2], audiogram_left[3], audiogram_left[11]];

                point3 = [audiogram_left[4], audiogram_left[5], audiogram_left[12]];

                point4 = [audiogram_left[6], audiogram_left[7], audiogram_left[13]];

                point5 = [audiogram_left[8], audiogram_left[9], audiogram_left[14]];

                left_unmasked_ac.push(point1);

                left_masked_ac.push(point2);

                left_unmasked_bc.push(point3);

                left_masked_bc.push(point4);

                left_no_response.push(point5);

                plotAudiogram();

                $("#left_frequency_unmasked_ac").val("");

                $("#left_hearing_unmasked_ac").val("");

                $("#left_frequency_masked_ac").val("");

                $("#left_hearing_masked_ac").val("");

                $("#left_frequency_unmasked_bc").val("");

                $("#left_hearing_unmasked_bc").val("");

                $("#left_frequency_masked_bc").val("");

                $("#left_hearing_masked_bc").val("");

                $("#left_frequency_no_reponse").val("");

                $("#left_hearing_no_response").val("");
            }
        });
    }

    function getAudiogramRight() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        $.ajax({
            type: "POST",
            url: "get_audiogram_right.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id
            },
            success: function(data) {

                var audiogram_right = JSON.parse(data);

                var point1 = [];

                var point2 = [];

                var point3 = [];

                var point4 = [];

                var point5 = [];

                for (var i = 0; i < audiogram_right.length; i++) {

                    var counter = audiogram_right[i];

                    if(counter.frequency_unmasked_ac == 0){
                        counter.frequency_unmasked_ac = "NULL"
                    }

                    if(counter.hearing_unmasked_ac == 0){
                        counter.hearing_unmasked_ac = "NULL"
                    }

                    if(counter.frequency_masked_ac == 0){
                        counter.frequency_masked_ac = "NULL"
                    }

                    if(counter.hearing_masked_ac == 0){
                        counter.hearing_masked_ac = "NULL"
                    }

                    if(counter.frequency_unmasked_bc == 0){
                        counter.frequency_unmasked_bc = "NULL"
                    }

                    if(counter.hearing_unmasked_bc == 0){
                        counter.hearing_unmasked_bc = "NULL"
                    }

                    if(counter.frequency_masked_bc == 0){
                        counter.frequency_masked_bc = "NULL"
                    }

                    if(counter.hearing_masked_bc == 0){
                        counter.hearing_masked_bc = "NULL"
                    }

                    if(counter.frequency_no_reponse == 0){
                        counter.frequency_no_reponse = "NULL"
                    }

                    if(counter.hearing_no_response == 0){
                        counter.hearing_no_response = "NULL"
                    }

                    point1 = [counter.frequency_unmasked_ac, counter.hearing_unmasked_ac, counter.display];

                    point2 = [counter.frequency_masked_ac, counter.hearing_masked_ac, counter.display1];

                    point3 = [counter.frequency_unmasked_bc, counter.hearing_unmasked_bc, counter.display2];

                    point4 = [counter.frequency_masked_bc, counter.hearing_masked_bc, counter.display3];

                    point5 = [counter.frequency_no_reponse, counter.hearing_no_response, counter.display4];

                    unmasked_ac.push(point1);

                    masked_ac.push(point2);

                    unmasked_bc.push(point3);

                    masked_bc.push(point4);

                    no_response.push(point5);

                    plotAudiogram();

                }
            }
        });
    }

    function getAudiogramLeft() {

        var registration_id = <?= $Registration_ID; ?>

        var payment_item_cache_list_id = <?= $Payment_Item_Cache_List_ID; ?>

        $.ajax({
            type: "POST",
            url: "get_audiogram_left.php",
            data: {
                registration_id: registration_id,
                payment_item_cache_list_id: payment_item_cache_list_id
            },
            success: function(data) {

                var audiogram_left = JSON.parse(data);

                var point1 = [];

                var point2 = [];

                var point3 = [];

                var point4 = [];

                var point5 = [];

                for (var i = 0; i < audiogram_left.length; i++) {

                    var counter = audiogram_left[i];

                    if(counter.left_frequency_unmasked_ac == 0){
                        counter.left_frequency_unmasked_ac = "NULL"
                    }

                    if(counter.left_hearing_unmasked_ac == 0){
                        counter.left_hearing_unmasked_ac = "NULL"
                    }

                    if(counter.left_frequency_masked_ac == 0){
                        counter.left_frequency_masked_ac = "NULL"
                    }

                    if(counter.left_hearing_masked_ac == 0){
                        counter.left_hearing_masked_ac = "NULL"
                    }

                    if(counter.left_frequency_unmasked_bc == 0){
                        counter.left_frequency_unmasked_bc = "NULL"
                    }

                    if(counter.left_hearing_unmasked_bc == 0){
                        counter.left_hearing_unmasked_bc = "NULL"
                    }

                    if(counter.left_frequency_masked_bc == 0){
                        counter.left_frequency_masked_bc = "NULL"
                    }

                    if(counter.left_hearing_masked_bc == 0){
                        counter.left_hearing_masked_bc = "NULL"
                    }

                    if(counter.left_frequency_no_reponse == 0){
                        counter.left_frequency_no_reponse = "NULL"
                    }

                    if(counter.left_hearing_no_response == 0){
                        counter.left_hearing_no_response = "NULL"
                    }

                    point1 = [counter.left_frequency_unmasked_ac, counter.left_hearing_unmasked_ac, counter.display];

                    point2 = [counter.left_frequency_masked_ac, counter.left_hearing_masked_ac, counter.display1];

                    point3 = [counter.left_frequency_unmasked_bc, counter.left_hearing_unmasked_bc, counter.display2];

                    point4 = [counter.left_frequency_masked_bc, counter.left_hearing_masked_bc, counter.display3];

                    point5 = [counter.left_frequency_no_reponse, counter.left_hearing_no_response, counter.display4];

                    left_unmasked_ac.push(point1);

                    left_masked_ac.push(point2);

                    left_unmasked_bc.push(point3);

                    left_masked_bc.push(point4);

                    left_no_response.push(point5);

                    plotAudiogram();

                }
            }
        });
    }
</script>
<script>
    //A jqplot plugin to render image as a data point.
    (function($) {
        $.jqplot.ImageMarkerRenderer = function() {
            $.jqplot.MarkerRenderer.call(this);
            //image element which should have src attribute populated with the image source path
            this.imageElement = null;
            //the offset to be added to the x position of the point to align the image correctly in the center of the point.
            this.xOffset = null;
            //the offset to be added to the y position of the point to align the image correctly in the center of the point.
            this.yOffset = null;
        };
        $.jqplot.ImageMarkerRenderer.prototype = new $.jqplot.MarkerRenderer();
        $.jqplot.ImageMarkerRenderer.constructor = $.jqplot.ImageMarkerRenderer;

        $.jqplot.ImageMarkerRenderer.prototype.init = function(options) {
            options = options || {};
            this.imageElement = options.imageElement;
            this.xOffset = options.xOffset || 0;
            this.yOffset = options.yOffset || 0;
            $.jqplot.MarkerRenderer.prototype.init.call(this, options);
        };

        $.jqplot.ImageMarkerRenderer.prototype.draw = function(x, y, ctx, options) {
            //draw the image onto the canvas
            ctx.drawImage(this.imageElement, x + this.xOffset, y + this.yOffset);
            ctx.restore();
            return;
        };
    })(jQuery);
</script>