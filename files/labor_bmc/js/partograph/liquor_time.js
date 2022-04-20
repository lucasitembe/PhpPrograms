var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function updateLiquorTime() {

    $("#save_liquor_time").click(function (e) {

        e.preventDefault();

        var time = $("#liquor_time_hours").val();

        var time_remark = $("#liquor_time_remark").val();

        $.ajax({
            url: "repo/update_liquor_time.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                time_remark: time_remark,
                time: time
            },
            success: function (data) {

                console.log(data);

                $("#liquor_time_hour #" + time).html(time_remark);

                $("#show_dialog_liqour_time").dialog("close");

            }

        });

        $("#show_dialog_liqour_time").dialog("close");

    });

}

function getLiquorTime() {

    $.ajax({
        url: "repo/fetch_liquor_time.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#liquor_time_hour #" + current.moulding_time).html(current.time_hour);
            }
        }

    })
}