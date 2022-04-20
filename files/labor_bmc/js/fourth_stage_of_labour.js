$(document).ready(function () {

    $("#save_fourth_stage_of_labour").click(function (e) {

        e.preventDefault();

        addFourthStageOflabor();

    });

});

/********** FUNCTION TO ADD FOURTH STAGE OF LABOUR **************/
function addFourthStageOflabor() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var temperature = $("#temperature").val();

    var pr = $("#pr").val();

    var bp = $("#bp").val();

    var fundal_height = $("#fundal_height").val();

    var state_of_cervix = $("#state_of_cervix").val();

    var state_of_perinium = $("#state_of_perinium").val();

    var blood_loss = $("#blood_loss").val();

    var recommendations = $("#recommendations").val();

    $.ajax({
        type: "POST",
        url: "repo/add_fourth_stage.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            temperature: temperature,
            pr: pr,
            bp: bp,
            fundal_height: fundal_height,
            state_of_cervix: state_of_cervix,
            state_of_perinium: state_of_perinium,
            blood_loss: blood_loss,
            recommendations: recommendations
        },
        success: function (data) {

            alert(data);

            $("#temperature").val("");

            $("#pr").val("");

            $("#bp").val("");

            $("#fundal_height").val("");

            $("#state_of_cervix").val("");

            $("#state_of_perinium").val("");

            $("#blood_loss").val("");

            $("#recommendations").val("");
        }
    });

}
/********** END FUNCTION TO ADD FOURTH STAGE OF LABOUR **************/