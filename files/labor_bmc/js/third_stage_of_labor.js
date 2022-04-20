$(document).ready(function () {

    $("#date_time").datetimepicker({
        maxDate: 0
    });

    $("#save_third_stage_of_labour").click(function (e) {

        e.preventDefault();

        addThirdStageOflabor();

    });

});

/********** FUNCTION TO ADD THIRD STAGE OF LABOUR **************/
function addThirdStageOflabor() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var placenta_method_of_delivery = $("#placenta_method_of_delivery").val();

    var date_time = $("#date_time").val();

    var duration = $("#duration").val();

    var placenta_weight = $("#placenta_weight").val();

    var stage_of_placenta = $("#stage_of_placenta").val();

    var colour = $("#colour").val();

    var cord = $("#cord").val();

    var membranes = $("#membranes").val();

    var disposal = $("#disposal").val();

    var state_of_cervix = $("#state_of_cervix").val();

    var tear = $("#tear").val();

    var repaired_with_sutures = $("#repaired_with_sutures").val();

    var total_blood_loss = $("#total_blood_loss").val();

    var temperature = $("#temperature").val();

    var pulse = $("#pulse").val();

    var resp = $("#resp").val();

    var bp = $("#bp").val();

    var lochia = $("#lochia").val();

    var state_of_uterus = $("#state_of_uterus").val();

    var remarks = $("#remarks").val();

    $.ajax({
        type: "POST",
        url: "repo/add_third_stage.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            placenta_method_of_delivery: placenta_method_of_delivery,
            date_time: date_time,
            duration: duration,
            placenta_weight: placenta_weight,
            stage_of_placenta: stage_of_placenta,
            colour: colour,
            cord: cord,
            membranes: membranes,
            disposal: disposal,
            state_of_cervix: state_of_cervix,
            tear: tear,
            repaired_with_sutures: repaired_with_sutures,
            total_blood_loss: total_blood_loss,
            temperature: temperature,
            pulse: pulse,
            resp: resp,
            bp: bp,
            lochia: lochia,
            state_of_uterus: state_of_uterus,
            remarks: remarks
        },
        success: function (data) {

            alert(data);

            $("#placenta_method_of_delivery").val("");
        
            $("#date_time").val("");
        
            $("#duration").val("");
        
            $("#placenta_weight").val("");
        
            $("#stage_of_placenta").val("");
        
            $("#colour").val("");
        
            $("#cord").val("");
        
            $("#membranes").val("");
        
            $("#disposal").val("");
        
            $("#state_of_cervix").val("");
        
            $("#tear").val("");
        
            $("#repaired_with_sutures").val("");
        
            $("#total_blood_loss").val("");
        
            $("#temperature").val("");
        
            $("#pulse").val("");
        
            $("#resp").val("");
        
            $("#bp").val("");
        
            $("#lochia").val("");
        
            $("#state_of_uterus").val("");
        
            $("#remarks").val("");
        }
    });

}
/********** END FUNCTION TO ADD THIRD STAGE OF LABOUR **************/