var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addTemperature() {

    $("#savet").click(function (e) {

        e.preventDefault();

        var time = $("#show-timet").val();

        var temp_remark = $("#temp_remark").val();

        $.ajax({
            url: "repo/add_temperature.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                temp: temp_remark,
                time: time
            },
            success: function (data) {

                $("#temp_fill #" + time).html(temp_remark);

                $("#show_dialogt").dialog("close");

            }

        });

        $("#show_dialogt").dialog("close");

    });

}

function getTemperature() {

    $.ajax({
        url: "repo/fetch_temperature.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#temp_fill #" + counter.tr_time).html(counter.temp + " C");

            }
        }
    })
}