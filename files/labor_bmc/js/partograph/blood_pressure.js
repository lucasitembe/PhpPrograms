var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addBloodPressure() {

    $("#savebp").click(function (e) {

        e.preventDefault();

        var time = $("#show-timebp").val();

        var bp_remark = $("#bp_remark").val();

        $.ajax({
            url: "repo/add_blood_pressure.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                bp: bp_remark,
                time: time
            },
            success: function (data) {

                $("#bpressure #" + time).html(bp_remark);

                $("#show_dialogbp").dialog("close");

            }

        });

    });

}

function getBloodPressure() {

    $.ajax({
        url: "repo/fetch_blood_pressure.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#bpressure #" + counter.pressure_time).html(counter.pressure);

            }

        }

    });

}