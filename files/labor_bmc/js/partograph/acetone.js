var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addAcetone() {

    $("#savea").click(function (e) {

        e.preventDefault();

        var time = $("#show-timea").val();

        var acetone_remark = $("#acetone_remark").val();

        $.ajax({
            url: "repo/add_acetone.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                acetone: acetone_remark,
                time: time
            },
            success: function (data) {

                $("#acetone_fill #" + time).html(acetone_remark);

                $("#show_dialoga").dialog("close");

            }

        });

    });

}

function getAcetone() {

    $.ajax({
        url: "repo/fetch_acetone.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);
            
            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#acetone_fill #" + counter.acetone_time).html(counter.acetone);

            }

        }
    })
}