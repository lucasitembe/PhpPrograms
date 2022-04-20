var points = [[]];

var points1 = [[]];

var d = [];

var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function plotFetalGraph() {

    $.jqplot.config.enablePlugins = true;

    var fetal_heart_rate_plot = $.jqplot('fetal_heart_rate_chart', [points, points1], {
        axes: {
            xaxis: {
                max: 24,
                min: 0,
                tickInterval: 0.5,
                label: "Hour"
            },
            yaxis: {
                max: 180,
                min: 80,
                tickInterval: 20,
                label: " Fetal Heart Rate ",
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                tickOptions: {
                    angle: 180
                }
            },
        },
        grid: {
            drawBorder: false,
            shadow: false,
            background: 'white'
        },
        highlighter: {
            show: true,
            formatString: '%s',
            tooltipLocation: 'ne',
            useAxesFormatters: false,
            tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                var dt = plot.data[seriesIndex][pointIndex][0];
                var fhr = plot.data[seriesIndex][pointIndex][1];
                var html = "<div style='color:blue; font-size: 20px;'>Hour :- ";
                html += dt;
                html += "  <br>FHR :- ";
                html += fhr;
                html += "  </div>";
                return html;
            }
        },
        cursor: {
            show: true
        },
        series: [{
            lineWidth: 5,
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#ccc',
            markerOptions: {
                size: 10,
                style: 'filledCircle'
            }
        },
        {
            showLine: true,
            markerOptions: {
                size: 10,
                style: "filledCircle"
            }
        },
        ]
    });
}

function addFetalHeartRate() {

    $("#add_fetal_heart_rate").click(function (e) {

        e.preventDefault();

        var x = $("#pointx").val();

        var y = $("#pointy").val();

        var baby_no = $("#baby_no").val();

        var time = $("#time_remark").val();

        if (x != "" && y != "") {

            if (y >= 0 && y <= 24) {

                if (x >= 80 && x <= 180) {

                    $.ajax({
                        type: "GET",
                        url: "repo/add_fetal_heart_rate.php",
                        data: {
                            x: x,
                            y: y,
                            baby_no: baby_no,
                            time: time,
                            patient_id: patient_id,
                            admission_id: admission_id
                        },
                        success: function (data) {

                            if (data != "") {

                                jsonData = JSON.parse(data);

                                if (jsonData[3] == "1") {
                                    points.push(jsonData);
                                }

                                if (jsonData[3] == "2") {
                                    points1.push(jsonData);
                                }

                                $("#pointx").css("border", "1px  black");

                                $("#pointy").css("border", "1px  black");

                                $("#pointx").val("");

                                $("#pointy").val("");

                                $("#time_remark").val("");

                                plotFetalGraph();

                            }

                        }

                    });

                } else {

                    alert("You exceed fetal heart rate");

                    $("#pointx").css("border", "2px solid red");

                    return false;

                }
            } else {

                alert("Your exceed normal hours");

                $("#pointy").css("border", "2px solid red");

                return false;

            }
        } else {

            alert("Please Fill in both Fetal Heart Rate and Time value");

            $("#pointy").css("border", "2px solid red");

            $("#pointx").css("border", "2px solid red");

        }

    });

}

function getFetalHeartRate() {

    $.ajax({
        type: "GET",
        url: "repo/fetch_fetal_heart_rate.php",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            if (data != "") {
                
                var jsonData = JSON.parse(data);

                for (var i = 0; i < jsonData.length; i++) {

                    var counter = jsonData[i];

                    if (counter.baby_no == "1") {
                        console.log(counter);
                        d = [parseFloat(counter.y), counter.x, counter.time_hours]
                        points.push(d);
                    }

                    if (counter.baby_no == "2") {
                        console.log(counter);
                        d = [parseFloat(counter.y), counter.x, counter.time_hours]
                        points1.push(d);

                    }

                }

            }

            plotFetalGraph();

        }

    });

}