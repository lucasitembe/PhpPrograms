var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function updateOxytocineTime() {

    $("#save_oxytocin_time").click(function (e) {

        e.preventDefault();

        var time = $("#oxytocin_time_hours").val();

        var time_remark = $("#oxytocin_time_remark").val();

        $.ajax({
            url: "repo/update_oxytocine_time.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                time_remark: time_remark,
                time: time
            },
            success: function (data) {

                $("#oxytocin_time_hour #" + time).html(time_remark);

                $("#show_dialog_oxytocin_time").dialog("close");

            }

        });

        $("#show_dialog_oxytocin_time").dialog("close");

    });

}

function getOxytocineTime() {

    $.ajax({
        url: "repo/fetch_oxytocine_time.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#oxytocin_time_hour #" + current.oxytocine_time).html(current.time_hour);

            }

        }

    });

}