var cervical_pointsx = [[]];

var cervical_pointsy = [[]];

var powPoints1 = [
    [0, 0],
    [0, 0]
];

var powPoints3 = [[]];

var powPoints4 = [[]];

var powPoints5 = [[]];

var powPoints6 = [
    [0, 3],
    [8, 3]
];

var powPoints7 = [
    [8, 0],
    [8, 10]
];

var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function plotLaborGraph() {
    var customImg = new Image();

    customImg.src = 'img/buttock.png';

    $.jqplot.config.enablePlugins = true;

    var progress_of_labor_plot = $.jqplot('progress_of_labor_chart', [powPoints1, powPoints3, powPoints4, powPoints5, cervical_pointsx, cervical_pointsy, powPoints6, powPoints7], {
        axes: {
            xaxis: {
                max: 24,
                min: 0,
                tickInterval: 1,
                label: "Duration Of Labour(Hour)"
            },
            yaxis: {
                max: 10,
                min: 0,
                tickInterval: 1,
                label: " Plot X  CERVICAL DILATION ",
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                tickOptions: {
                    angle: 180
                }
            },
        },
        title:{
            text : "<div style='top:20px; left:180px; position:absolute; z-index:55;'>Latent Phase</div> <div style='top:20px; left:600px; position:absolute; z-index:55;'>Active Phase</div>",
            show : true,
            fontSize : '12pt',
            textAlign : 'left',
            textColor : '#333',
            escapeHtml : false,
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
                var dilation = plot.data[seriesIndex][pointIndex][1];
                var dt = plot.data[seriesIndex][pointIndex][2];
                var html = "<div style='color:blue; font-size: 20px;'>Dilation/Descent :";
                html += dilation + "cm";
                html += "  <br>Duration :";
                html += dt;
                html += "  </div>";
                return html;
            }
        },
        cursor: {
            show: false
        },
        seriesDefaults: {
            rendererOptions: {
                smooth: true
            }
        },
        series: [{
            fill: false,
            fillAndStroke: true,
            color: '#000',
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
            color: '#000',
            fillColor: '#000',
            showLine: true,
            label: 'Alert',
            markerOptions: {
                size: 5,
                style: "x"
            }
        },
        {
            showLine: true,
            lineWidth: 3,
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#000',
            label: 'Action',
            markerOptions: {
                sizi: 5,
                style: "x"
            }
        },
        {
            color: '#000',
            fillColor: '#000',
            label: 'Descent',
            markerRenderer: $.jqplot.ImageMarkerRenderer,
            markerOptions: {
                show: true,
                imageElement: customImg,
                xOffset: -10,
                yOffset: -7,
            }
        },
        {
            showLine: true,
            lineWidth: 3,
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#000',
            label: 'Cervix',
            markerOptions: {
                size: 5,
                style: "x"
            }
        },
        {
            showLine: true,
            lineWidth: 3,
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#000',
            label: 'Descent',
            markerOptions: {
                size: 10,
                style: "circle"
            }
        },
        {
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#000',
            showLine: true,
            label: 'Alert',
            markerOptions: {
                size: 5,
                style: "x"
            }
        },
        {
            fill: false,
            fillAndStroke: true,
            color: '#000',
            fillColor: '#000',
            showLine: true,
            label: 'Alert',
            markerOptions: {
                size: 5,
                style: "x"
            }
        },
        ]
    });
}

/****** A jqplot plugin to render image as a data point ****/
(function ($) {
    $.jqplot.ImageMarkerRenderer = function () {
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

    $.jqplot.ImageMarkerRenderer.prototype.init = function (options) {
        options = options || {};
        this.imageElement = options.imageElement;
        this.xOffset = options.xOffset || 0;
        this.yOffset = options.yOffset || 0;
        $.jqplot.MarkerRenderer.prototype.init.call(this, options);
    };

    $.jqplot.ImageMarkerRenderer.prototype.draw = function (x, y, ctx, options) {
        //draw the image onto the canvas
        ctx.drawImage(this.imageElement, x + this.xOffset, y + this.yOffset);
        ctx.restore();
        return;
    };
})(jQuery);


function addProgressOfLabor() {

    $("#add_progress_of_labor").click(function (e) {

        e.preventDefault();

        var fx = $(".input-fx").val();

        var fy = $(".input-fy").val();

        var sy = $(".input-sy").val();

        var time_hour = $("#labour_time_remark").val();

        var fetal_position = $("#fetal_position").val();

        if (fx != "" && fy != "" && sy != "" && time_hour != "" && fetal_position != "") {

            if (fx > 24) {
                alert("You Can't Enter maximum Of 24Hours");
                $(".input-fx").css("border", "2px solid red");
                return false;
            }

            if (fy > 10) {
                alert("Cervical dilation cannot be greater than 10");
                $(".input-fy").css("border", "2px solid red");
                return false;
            }

            if (fy < 3) {
                alert("Cervical dilation cannot be less than 3");
                $(".input-fy").css("border", "2px solid red");
                return false;
            }

            if (sy > 3) {
                alert("Descent value cannot be greater than 3");
                $(".input-sy").css("border", "2px solid red");
                return false;
            }

            $.ajax({
                url: "repo/add_progress_of_labor.php",
                type: "POST",
                data: {
                    patient_id: patient_id,
                    admission_id: admission_id,
                    fx: fx,
                    fy: fy,
                    sy: sy,
                    time_hour: time_hour,
                    fetal_position: fetal_position
                },
                success: function (data) {

                    var cervical_data = JSON.parse(data);

                    var point1 = [];

                    var point2 = [];

                    point1 = [cervical_data[2], cervical_data[0], cervical_data[3]];

                    point2 = [cervical_data[2], cervical_data[1], cervical_data[3]];

                    if (cervical_data[4] == "head") {
                        cervical_pointsy.push(point2);
                    }

                    if (cervical_data[4] == "buttock") {
                        powPoints5.push(point2);
                    }

                    cervical_pointsx.push(point1);

                    plotLaborGraph();

                    $(".input-fx").css("border", "1px black");

                    $(".input-fy").css("border", "1px black");

                    $("#labour_time_remark").css("border", "1px black");

                    $(".input-sy").css("border", "1px black");

                    $(".input-fy").val("");

                    $(".input-fx").val("");

                    $(".input-sy").val("");

                    $("#labour_time_remark").val("");

                }

            });
        } else {

            alert("Please Fill in required values");

            $(".input-fx").css("border", "2px solid red");

            $(".input-fy").css("border", "2px solid red");

            $(".input-sy").css("border", "2px solid red");

        }

    });

}


function getProgressOfLabor() {

    $.ajax({
        url: "repo/fetch_progress_of_labor.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var cervical_data = JSON.parse(data);

            var point1 = [];

            var point2 = [];

            for (var i = 0; i < cervical_data.length; i++) {

                var counter = cervical_data[i];

                point1 = [parseInt(counter.fx), parseFloat(counter.fy), counter.time_hours];

                point2 = [parseInt(counter.fx), parseFloat(counter.sy), counter.time_hours];

                cervical_pointsx.push(point1);

                if (counter.fetal_position == "head") {
                    cervical_pointsy.push(point2);
                }

                if (counter.fetal_position == "buttock") {
                    powPoints5.push(point2);
                }

                plotLaborGraph();

            }

        }

    });

}