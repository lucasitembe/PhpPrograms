var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addVolume() {

    $("#savev").click(function (e) {

        e.preventDefault();

        var time = $("#show-timev").val();

        var volume_remark = $("#volume_remark").val();

        $.ajax({
            url: "repo/add_volume.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                volume: volume_remark,
                time: time
            },
            success: function (data) {

                $("#volume_fill #" + time).html(volume_remark);

                $("#show_dialogv").dialog("close");
            }
        })
    })
}

function getVolume() {
    $.ajax({
        url: "repo/fetch_volume.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data)
            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#volume_fill #" + counter.volume_time).html(counter.volume);
            }

        }
    })
}