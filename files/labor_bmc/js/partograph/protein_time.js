var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function updateProteinTime() {

    $("#save_protein_time").click(function (e) {

        e.preventDefault();

        var time = $("#protein_time_hours").val();

        var time_remark = $("#protein_time_remark").val();

        $.ajax({
            url: "repo/update_protein_time.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                time_remark: time_remark,
                time: time
            },
            success: function (data) {

                $("#protein_time_hour #" + time).html(time_remark);

                $("#show_dialog_protein_time").dialog("close");

            }

        });

        $("#show_dialog_protein_time").dialog("close");

    });

}

function getProteinTime() {

    $.ajax({
        url: "repo/fetch_protein_time.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#protein_time_hour #" + current.urine_time).html(current.time_hour);

            }

        }

    });

}