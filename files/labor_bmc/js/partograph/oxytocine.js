var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addOxytocin() {

    $("#saveo").click(function (e) {

        e.preventDefault();

        var time = $("#show-timeo").val();

        var oxytocine_remark = $("#oxytocine_remark").val();

        $.ajax({
            url: "repo/add_oxytocine.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                oxytocine: oxytocine_remark,
                time: time
            },
            success: function (data) {

                $("#oxytocine_fill #" + time).html(oxytocine_remark);

                $("#show_dialogo").dialog("close");

            }

        });

    });

}

function getOxytocine() {

    $.ajax({
        url: "repo/fetch_oxytocine.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#oxytocine_fill #" + counter.oxytocine_time).html(counter.oxytocine);

            }
        }
    })
}