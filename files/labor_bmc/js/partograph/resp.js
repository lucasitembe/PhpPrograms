var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addResp() {

    $("#saver").click(function (e) {

        e.preventDefault();

        var time = $("#show-timer").val();

        var res_remark = $("#res_remark").val();

        $.ajax({
            url: "repo/add_resp.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                resp: res_remark,
                time: time
            },
            success: function (data) {

                $("#res_fill #" + time).html(res_remark);

                $("#show_dialogr").dialog("close");

            }

        });

    });

}

function getResp() {

    $.ajax({
        url: "repo/fetch_resp.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#res_fill #" + counter.resp_time).html(counter.resp);

            }

        }

    });
    
}