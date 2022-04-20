$(document).ready(function () {

    $("#onset_labor").datetimepicker({
        maxDate: 0
    });

    $("#admitted_at").datetimepicker({
        maxDate: 0
    });

    $("#rapture_date").datetimepicker({
        maxDate: 0
    });

    $("#arm_date").datetimepicker({
        maxDate: 0
    });

    $("#rapture_date").hide();

    $("#rapture_duration").hide();

    $("#arm_date").hide();

    $("#arm").click(function () {

        if ($("#arm :selected").text() == "Done") {
            $("#arm_date").show();
        } else {
            $("#arm_date").hide();
        }

    });

    $("#membrane_liquor").click(function () {

        if ($("#membrane_liquor :selected").text() == "Rapture") {
            $("#rapture_duration").show();
            $("#rapture_date").show();
        } else {
            $("#rapture_duration").hide();
            $("#rapture_date").hide();
        }

    });

    $("#induction_of_labor_reason").hide();

    $("#induction_of_labor").click(function () {

        if ($("#induction_of_labor :selected").text() == "Yes") {
            $("#induction_of_labor_reason").show();
        } else {
            $("#induction_of_labor_reason").hide();
        }

    });

    $("#save_first_stage_of_labour").click(function (e) {

        e.preventDefault();

        addFirstStageOflabor();

    });

});

/********** FUNCTION TO ADD FIRST STAGE OF LABOUR **************/
function addFirstStageOflabor() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var onset_labor = $("#onset_labor").val();

    var admitted_at = $("#admitted_at").val();

    var cervix_dilation = $("#cervix_dilation").val();

    var membrane_liquor = $("#membrane_liquor").val();

    var rapture_date = $("#rapture_date").val();

    var rapture_duration = $("#rapture_duration").val();

    var arm = $("#arm").val();

    var no_of_examinations = $("#no_of_examinations").val();

    var abnormalities = $("#abnormalities").val();

    var induction_of_labor = $("#induction_of_labor").val();

    var induction_of_labor_reason = $("#induction_of_labor_reason").val();

    var first_stage_duration = $("#first_stage_duration").val();

    var drugs_given = $("#drugs_given").val();

    var remarks = $("#remarks").val();

    $.ajax({
        type: "POST",
        url: "repo/add_first_stage.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            onset_labor: onset_labor,
            admitted_at: admitted_at,
            cervix_dilation: cervix_dilation,
            membrane_liquor: membrane_liquor,
            rapture_date: rapture_date,
            rapture_duration: rapture_duration,
            arm: arm,
            no_of_examinations: no_of_examinations,
            abnormalities: abnormalities,
            induction_of_labor: induction_of_labor,
            induction_of_labor_reason: induction_of_labor_reason,
            first_stage_duration: first_stage_duration,
            drugs_given: drugs_given,
            remarks: remarks
        },
        success: function (data) {

            alert(data);

            $("#onset_labor").val("");

            $("#admitted_at").val("");

            $("#cervix_dilation").val("");

            $("#membrane_liquor").val("");

            $("#rapture_date").val("");

            $("#rapture_duration").val("");

            $("#arm").val("");

            $("#no_of_examinations").val("");

            $("#abnormalities").val("");

            $("#induction_of_labor").val("");

            $("#induction_of_labor_reason").val("");

            $("#first_stage_duration").val("");

            $("#drugs_given").val("");

            $("#remarks").val("");
        }
    });

}
/********** END FUNCTION TO ADD FIRST STAGE OF LABOUR **************/