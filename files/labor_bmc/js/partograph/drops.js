var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addDrops() {

    $("#saved").click(function (e) {

        e.preventDefault();

        var time = $("#show-timed").val();

        var drop_remark = $("#drop_remark").val();

        $.ajax({
            url: "repo/add_drops.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                drops: drop_remark,
                time: time
            },
            success: function (data) {

                $("#drop_fill #" + time).html(drop_remark);

                $("#show_dialogd").dialog("close");

            }

        });

    });

}

function getDrops() {

    $.ajax({
        url: "repo/fetch_drops.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#drop_fill #" + counter.drops_time).html(counter.drops);
            }
        }
    })
}