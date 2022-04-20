var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addProtein() {

    $("#savep").click(function (e) {

        e.preventDefault();

        var patient_id = $("#patient_id").val();

        var admission_id = $("#admission_id").val();

        var time = $("#show-timepro").val();

        var protein_remark = $("#protein_remark").val();

        $.ajax({
            url: "repo/add_protein.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                protein: protein_remark,
                time: time
            },
            success: function (data) {

                $("#protein_fill #" + time).html(protein_remark);

                $("#show_dialogp").dialog("close");

            }

        });

    });

}

function getProtein() {

    $.ajax({
        url: "repo/fetch_protein.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                $("#protein_fill #" + counter.urine_time).html(counter.protein);

            }

        }

    });

}