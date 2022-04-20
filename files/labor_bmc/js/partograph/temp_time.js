var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function updateTempTime() {

    $("#save_temp_time").click(function (e) {

        e.preventDefault();

        var time = $("#temp_time_hours").val();

        var time_remark = $("#temp_time_remark").val();

        $.ajax({
            url: "repo/update_temp_time.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                time_remark: time_remark,
                time: time
            },
            success: function (data) {

                $("#temp_time_hour #" + time).html(time_remark);

                $("#show_dialog_temp_time").dialog("close");

            }

        });

        $("#show_dialog_temp_time").dialog("close");

    });

}

function getTempTime() {

    $.ajax({
        url: "repo/fetch_temp_time.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#temp_time_hour #" + current.tr_time).html(current.time_hour);

            }

        }

    });

}