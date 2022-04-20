var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addMoulding(moulding) {

    $("#savem").click(function (e) {

        e.preventDefault();

        var time = $(".show-timem").val();

        $("#moulding #" + time).html(moulding);

        $("#show_dialogm").dialog("close");

        $.ajax({
            type: "POST",
            url: "repo/add_moulding.php",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                moulding: moulding,
                moulding_time: time
            },
            success: function (data) {
                console.log(data);
            }

        });

    });

}

function getMoulding() {

    $.ajax({
        url: "repo/fetch_moulding.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var json_data = JSON.parse(data);

            for (var i = 0; i < json_data.length; i++) {

                var current = json_data[i];

                $("#moulding #" + current.moulding_time).html(current.moulding);

            }
        }

    });
    
}