$(document).ready(function () {

    $("#time_began").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $("#date_time").datetimepicker({
        maxDate: 0
    });

    $("#save_second_stage_of_labour").click(function (e) {

        e.preventDefault();

        addSecondStageOflabor();

    });

});

/********** FUNCTION TO ADD SECOND STAGE OF LABOUR **************/
function addSecondStageOflabor() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var time_began = $("#time_began").val();

    var date_time = $("#date_time").val();

    var duration = $("#duration").val();

    var mode_of_delivery = $("#mode_of_delivery").val();

    var drugs = $("#drugs").val();

    var remarks = $("#remarks").val();

    $.ajax({
        type: "POST",
        url: "repo/add_second_stage.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            time_began: time_began,
            date_time: date_time,
            duration: duration,
            mode_of_delivery: mode_of_delivery,
            drugs: drugs,
            remarks: remarks
        },
        success: function (data) {

            alert(data);

            $("#time_began").val("");

            $("#date_time").val("");

            $("#duration").val("");

            $("#mode_of_delivery").val("");

            $("#drugs").val("");

            $("#remarks").val("");
        }
    });

}
/********** END FUNCTION TO ADD SECOND STAGE OF LABOUR **************/