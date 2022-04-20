var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addCaput() {

    $("#save_caput").click(function (e) {

        e.preventDefault();

        var time = $("#time_caput").val();

        var caput_remark = $("#caput_remark").val();

        $.ajax({
            url: "repo/add_caput.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                caput_remark: caput_remark,
                time: time
            },
            success: function (data) {

                console.log(data);

                $("#caput #" + time).html(caput_remark);

                $("#show_dialog_caput").dialog("close");

            }

        });

        $("#show_dialog_caput").dialog("close");

    });

}

function getCaput() {

    $.ajax({
        url: "repo/fetch_caput.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#caput #" + counter.caput_remark_time).html(counter.caput_remark);

            }

        }

    });
    
}

