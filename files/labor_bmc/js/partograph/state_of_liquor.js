var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addStateOfLiquor(liqour_remark) {

    $("#savel").click(function (e) {

        e.preventDefault();

        var time = $(".show-time").val();

        $("#liqour_remark #" + time).html(liqour_remark);

        $("#show_dialogl").dialog("close");

        $.ajax({
            type: "POST",
            url: "repo/add_state_of_liquor.php",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                liqour_remark: liqour_remark,
                liqour_remark_time: time
            },
            success: function (data) {

                console.log(data);

            }

        });

    });
}

function getStateOfLiquor() {

    $.ajax({
        url: "repo/fetch_state_of_liquor.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            console.log(json_data)

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#liqour_remark #" + current.liqour_remark_time).html(current.liqour_remark);
            }
        }

    })
}