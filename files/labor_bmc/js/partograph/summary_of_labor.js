function addSummaryOfLabor() {

    $("#save_labour").click(function (e) {

        e.preventDefault();

        var Registration_ID = $("#patient_id").val();

        var admission_id = $("#admission_id").val();

        var consultation_id = $("#consultation_id").val();

        var sex = $("#sex").val();

        var weight = $("#weight").val();

        var abnormalities = $("#abnormalities").val();

        var resuscitation = $("#resuscitation").val();

        var drugs = $("#drugs").val();

        var eye_drop = $("#eye_drop").val();

        if (confirm("Are You Sure You want To Save ")) {
            $.ajax({
                url: 'repo/add_summary_of_labor.php',
                type: 'POST',
                data: {
                    Registration_ID: Registration_ID,
                    admission_id: admission_id,
                    consultation_id: consultation_id,
                    sex: sex,
                    weight: weight,
                    abnormalities: abnormalities,
                    resuscitation: resuscitation,
                    drugs: drugs,
                    eye_drop: eye_drop
                },
                success: function (data) {

                    jsonData = JSON.parse(data);

                    alert(jsonData.alert);

                    $("#sex").val(jsonData.sex);

                    $("#weight").val(jsonData.weight);

                    $("#abnormalities").val(jsonData.abnormalities);

                    $("#resuscitation").val(jsonData.resuscitation);

                    $("#drugs").val(jsonData.drugs);

                    $("#eye_drop").val(jsonData.eye_drop);

                }

            });

        }

    });

}