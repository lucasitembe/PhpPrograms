var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

var heart_rate;

var heart_rate_data;

var respiration;

var respiration_data;

var muscle_tone;

var muscle_tone_data;

var reflexe;

var reflexe_data;

var color;

var color_data;

var five_heart_rate;

var five_heart_rate_data;

var five_respiration;

var five_respiration_data;

var five_muscle_tone;

var five_muscle_tone_data;

var five_reflexe;

var five_reflexe_data;

var five_color;

var five_color_data;

function addBabyStatusAfterOneMinute(){

    if ($("#heart_rate_less_60").is(":checked")) {

        heart_rate_data = 0;
        heart_rate = "none > 60";

    }

    if ($("#heart_rate_60").is(":checked")) {

        heart_rate_data = 1;
        heart_rate = "Below 60 - 100";

    }

    if ($("#heart_rate_greater_60").is(":checked")) {

        heart_rate_data = 2;
        heart_rate = "Over 100";

    }

    if ($("#respiration_none").is(":checked")) {

        respiration_data = 0;
        respiration = "None";

    }

    if ($("#respiration_gasp").is(":checked")) {

        respiration_data = 1;
        respiration = "Gasp";

    }

    if ($("#respiration_cry").is(":checked")) {

        respiration_data = 2;
        respiration = "Crying";

    }

    if ($("#muscle_tone_none").is(":checked")) {

        muscle_tone_data = 0;
        muscle_tone = "None";

    }

    if ($("#muscle_tone_bil").is(":checked")) {

        muscle_tone_data = 1;
        muscle_tone = "A bil";

    }

    if ($("#muscle_tone_mvt").is(":checked")) {

        muscle_tone_data = 2;
        muscle_tone = "Active movement";

    }

    if ($("#reflexe_none").is(":checked")) {

        reflexe_data = 0;
        reflexe = "None";

    }

    if ($("#reflexe_grimance").is(":checked")) {

        reflexe_data = 1;
        reflexe = "Grimance";

    }

    if ($("#reflexe_cough").is(":checked")) {

        reflexe_data = 2;
        reflexe = "Cough";

    }

    if ($("#color_none").is(":checked")) {

        color_data = 0;
        color = "None";

    }

    if ($("#color_blue").is(":checked")) {

        color_data = 1;
        color = "Blue";

    }

    if ($("#color_pink").is(":checked")) {

        color_data = 2;
        color = "Pink";

    }

    var sum = heart_rate_data + respiration_data + muscle_tone_data + reflexe_data + color_data;

    if(confirm("Are you sure you want to save ?")){
        $.ajax({
            type: "POST",
            url: "repo/add_baby_status_one.php",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                heart_rate: heart_rate,
                respiration: respiration,
                muscle_tone: muscle_tone,
                reflexe: reflexe,
                color: color,
                sum: sum
            },
            success: function (data) {

                jsonData = JSON.parse(data);

                alert(jsonData.display);

                $("#total_one_min").val(jsonData.sum);
                
            }
        });
    }

}

function addBabyStatusAfterFiveMinute(){

    if ($("#five_heart_rate_less_60").is(":checked")) {

        five_heart_rate_data = 0;
        five_heart_rate = "none > 60";

    }

    if ($("#five_heart_rate_60").is(":checked")) {

        five_heart_rate_data = 1;
        five_heart_rate = "Below 60 - 100";

    }

    if ($("#five_heart_rate_greater_60").is(":checked")) {

        five_heart_rate_data = 2;
        five_heart_rate = "Over 100";

    }

    if ($("#five_respiration_none").is(":checked")) {

        five_respiration_data = 0;
        five_respiration = "None";

    }

    if ($("#five_respiration_gasp").is(":checked")) {

        five_respiration_data = 1;
        five_respiration = "Gasp";

    }

    if ($("#five_respiration_cry").is(":checked")) {

        five_respiration_data = 2;
        five_respiration = "Crying";

    }

    if ($("#five_muscle_tone_none").is(":checked")) {

        five_muscle_tone_data = 0;
        five_muscle_tone = "None";

    }

    if ($("#five_muscle_tone_bil").is(":checked")) {

        five_muscle_tone_data = 1;
        five_muscle_tone = "A bil";

    }

    if ($("#five_muscle_tone_mvt").is(":checked")) {

        five_muscle_tone_data = 2;
        five_muscle_tone = "Active movement";

    }

    if ($("#five_reflexe_none").is(":checked")) {

        five_reflexe_data = 0;
        five_reflexe = "None";

    }

    if ($("#five_reflexe_grimance").is(":checked")) {

        five_reflexe_data = 1;
        five_reflexe = "Grimance";

    }

    if ($("#five_reflexe_cough").is(":checked")) {

        five_reflexe_data = 2;
        five_reflexe = "Cough";

    }

    if ($("#five_color_none").is(":checked")) {

        five_color_data = 0;
        five_color = "None";

    }

    if ($("#five_color_blue").is(":checked")) {

        five_color_data = 1;
        five_color = "Blue";

    }

    if ($("#five_color_pink").is(":checked")) {

        five_color_data = 2;
        five_color = "Pink";

    }

    var sum = five_heart_rate_data + five_respiration_data + five_muscle_tone_data + five_reflexe_data + five_color_data;

    if(confirm("Are you sure you want to save ?")){
        $.ajax({
            type: "POST",
            url: "repo/add_baby_status_five.php",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                five_heart_rate: five_heart_rate,
                five_respiration: five_respiration,
                five_muscle_tone: five_muscle_tone,
                five_reflexe: five_reflexe,
                five_color: five_color,
                sum: sum
            },
            success: function (data) {

                jsonData = JSON.parse(data);

                alert(jsonData.display);

                $("#total_five_min").val(jsonData.sum);
                
            }
        });
    }
}

function getBabyStatusAfterOneMinute(){

    $.ajax({
        type: "POST",
        url: "repo/fetch_baby_status_one.php",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                if(counter.heart_rate == "none > 60"){
                    $('#heart_rate_less_60').prop("checked", true);
                    $('#heart_rate_60').prop("disabled", true);
                    $('#heart_rate_greater_60').prop("disabled", true);
                }
                if(counter.heart_rate == "Below 60 - 100"){
                    $('#heart_rate_less_60').prop("disabled", true);
                    $('#heart_rate_60').prop("checked", true);
                    $('#heart_rate_greater_60').prop("disabled", true);
                }
                if(counter.heart_rate == "Over 100"){
                    $('#heart_rate_less_60').prop("disabled", true);
                    $('#heart_rate_60').prop("disabled", true);
                    $('#heart_rate_greater_60').prop("checked", true);
                }

                if(counter.respiration == "None"){
                    $('#respiration_none').prop("checked", true);
                    $('#respiration_gasp').prop("disabled", true);
                    $('#respiration_cry').prop("disabled", true);
                }
                if(counter.respiration == "Gasp"){
                    $('#respiration_none').prop("disabled", true);
                    $('#respiration_gasp').prop("checked", true);
                    $('#respiration_cry').prop("disabled", true);
                }
                if(counter.respiration == "Crying"){
                    $('#respiration_none').prop("disabled", true);
                    $('#respiration_gasp').prop("disabled", true);
                    $('#respiration_cry').prop("checked", true);
                }

                if(counter.muscle_tone == "None"){
                    $('#muscle_tone_none').prop("checked", true);
                    $('#muscle_tone_bil').prop("disabled", true);
                    $('#muscle_tone_mvt').prop("disabled", true);
                }
                if(counter.muscle_tone == "A bil"){
                    $('#muscle_tone_none').prop("disabled", true);
                    $('#muscle_tone_bil').prop("checked", true);
                    $('#muscle_tone_mvt').prop("disabled", true);
                }
                if(counter.muscle_tone == "Active movement"){
                    $('#muscle_tone_none').prop("disabled", true);
                    $('#muscle_tone_bil').prop("disabled", true);
                    $('#muscle_tone_mvt').prop("checked", true);
                }

                if(counter.reflexe == "None"){
                    $('#reflexe_none').prop("checked", true);
                    $('#reflexe_grimance').prop("disabled", true);
                    $('#reflexe_cough').prop("disabled", true);
                }
                if(counter.reflexe == "Grimance"){
                    $('#reflexe_none').prop("disabled", true);
                    $('#reflexe_grimance').prop("checked", true);
                    $('#reflexe_cough').prop("disabled", true);
                }
                if(counter.reflexe == "Cough"){
                    $('#reflexe_none').prop("disabled", true);
                    $('#reflexe_grimance').prop("disabled", true);
                    $('#reflexe_cough').prop("checked", true);
                }

                if(counter.color == "None"){
                    $('#color_none').prop("checked", true);
                    $('#color_blue').prop("disabled", true);
                    $('#color_pink').prop("disabled", true);
                }
                if(counter.color == "Blue"){
                    $('#color_none').prop("disabled", true);
                    $('#color_blue').prop("checked", true);
                    $('#color_pink').prop("disabled", true);
                }
                if(counter.color == "Pink"){
                    $('#color_none').prop("disabled", true);
                    $('#color_blue').prop("disabled", true);
                    $('#color_pink').prop("checked", true);
                }

                $("#total_one_min").val(counter.sum);
            }
        }
    });
}

function getBabyStatusAfterFiveMinute(){

    $.ajax({
        type: "POST",
        url: "repo/fetch_baby_status_five.php",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                if(counter.heart_rate == "none > 60"){
                    $('#five_heart_rate_less_60').prop("checked", true);
                    $('#five_heart_rate_60').prop("disabled", true);
                    $('#five_heart_rate_greater_60').prop("disabled", true);
                }
                if(counter.heart_rate == "Below 60 - 100"){
                    $('#five_heart_rate_less_60').prop("disabled", true);
                    $('#five_heart_rate_60').prop("checked", true);
                    $('#five_heart_rate_greater_60').prop("disabled", true);
                }
                if(counter.heart_rate == "Over 100"){
                    $('#five_heart_rate_less_60').prop("disabled", true);
                    $('#five_heart_rate_60').prop("disabled", true);
                    $('#five_heart_rate_greater_60').prop("checked", true);
                }

                if(counter.respiration == "None"){
                    $('#five_respiration_none').prop("checked", true);
                    $('#five_respiration_gasp').prop("disabled", true);
                    $('#five_respiration_cry').prop("disabled", true);
                }
                if(counter.respiration == "Gasp"){
                    $('#five_respiration_none').prop("disabled", true);
                    $('#five_respiration_gasp').prop("checked", true);
                    $('#five_respiration_cry').prop("disabled", true);
                }
                if(counter.respiration == "Crying"){
                    $('#five_respiration_none').prop("disabled", true);
                    $('#five_respiration_gasp').prop("disabled", true);
                    $('#five_respiration_cry').prop("checked", true);
                }

                if(counter.muscle_tone == "None"){
                    $('#five_muscle_tone_none').prop("checked", true);
                    $('#five_muscle_tone_bil').prop("disabled", true);
                    $('#five_muscle_tone_mvt').prop("disabled", true);
                }
                if(counter.muscle_tone == "A bil"){
                    $('#five_muscle_tone_none').prop("disabled", true);
                    $('#five_muscle_tone_bil').prop("checked", true);
                    $('#five_muscle_tone_mvt').prop("disabled", true);
                }
                if(counter.muscle_tone == "Active movement"){
                    $('#five_muscle_tone_none').prop("disabled", true);
                    $('#five_muscle_tone_bil').prop("disabled", true);
                    $('#five_muscle_tone_mvt').prop("checked", true);
                }

                if(counter.reflexe == "None"){
                    $('#five_reflexe_none').prop("checked", true);
                    $('#five_reflexe_grimance').prop("disabled", true);
                    $('#five_reflexe_cough').prop("disabled", true);
                }
                if(counter.reflexe == "Grimance"){
                    $('#five_reflexe_none').prop("disabled", true);
                    $('#five_reflexe_grimance').prop("checked", true);
                    $('#five_reflexe_cough').prop("disabled", true);
                }
                if(counter.reflexe == "Cough"){
                    $('#five_reflexe_none').prop("disabled", true);
                    $('#five_reflexe_grimance').prop("disabled", true);
                    $('#five_reflexe_cough').prop("checked", true);
                }

                if(counter.color == "None"){
                    $('#five_color_none').prop("checked", true);
                    $('#five_color_blue').prop("disabled", true);
                    $('#five_color_pink').prop("disabled", true);
                }
                if(counter.color == "Blue"){
                    $('#five_color_none').prop("disabled", true);
                    $('#five_color_blue').prop("checked", true);
                    $('#five_color_pink').prop("disabled", true);
                }
                if(counter.color == "Pink"){
                    $('#five_color_none').prop("disabled", true);
                    $('#five_color_blue').prop("disabled", true);
                    $('#five_color_pink').prop("checked", true);
                }

                $("#total_five_min").val(counter.sum);
            }
        }
    });
}