$(document).ready(function () {

    $("#date_time").datetimepicker({
        maxDate: 0
    });

    $("#date_time2").datetimepicker({
        maxDate: 0
    });

    $("#examinaer").select2();

    $("#admitted_by").select2();

    $("#informed_by").select2();

    $("#rapture").hide();

    $("#rapture_date").hide();

    $("#membrane_liquor").click(function () {

        if ($("#membrane_liquor :selected").text() == "Rapture") {
            $("#rapture").show();
            $("#rapture_date").show();
        } else {
            $("#rapture").hide();
            $("#rapture_date").hide();
        }

    });

    $("#save_labor_record").click(function (e) {

        e.preventDefault();

        addLaborRecord();

    });

});

/********** FUNCTION TO ADD LABOR RECORD **************/
function addLaborRecord() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var date_time = $("#date_time").val();

    var temperature = $("#temperature").val();

    var colour = $("#colour").val();

    var specific_gravity = $("#specific_gravity").val();

    var pulse = $("#pulse").val();

    var ph = $("#ph").val();

    var blood = $("#blood").val();

    var resp = $("#resp").val();

    var albumin = $("#albumin").val();

    var sugar = $("#sugar").val();

    var state_of_admission = $("#state_of_admission").val();

    var bp = $("#bp").val();

    var leucocytes = $("#leucocytes").val();

    var ketones = $("#ketones").val();

    var clinical_appearance = $("#clinical_appearance").val();

    var hb = $("#hb").val();

    var vdrl = $("#vdrl").val();

    var elisa = $("#elisa").val();

    var varicose_veins = $("#varicose_veins").val();

    var blood2 = $("#blood2").val();

    var oedema = $("#oedema").val();

    var mental_status = $("#mental_status").val();

    var shape = $("#shape").val();

    var scars = $("#scars").val();

    var inspection = $("#inspection").val();

    var fundal_height = $("#fundal_height").val();

    var lie = $("#lie").val();

    var presentation = $("#presentation").val();

    var position = $("#position").val();

    var brim = $("#brim").val();

    var contraction = $("#contraction").val();

    var fhr = $("#fhr").val();

    var date_time2 = $("#date_time2").val();

    var examinaer = $("#examinaer").val();

    var cervic_state = $("#cervic_state").val();

    var dilation = $("#dilation").val();

    var presenting_part = $("#presenting_part").val();

    var station = $("#station").val();

    var position2 = $("#position2").val();

    var moulding = $("#moulding").val();

    var caput = $("#caput").val();

    var membrane_liquor = $("#membrane_liquor").val();

    var rapture = $("#rapture").val();

    var rapture_date = $("#rapture_date").val();

    var sacral_promontory = $("#sacral_promontory").val();

    var sacral_curve = $("#sacral_curve").val();

    var ischial_spine = $("#ischial_spine").val();

    var subpubic_angle = $("#subpubic_angle").val();

    var sacral_tuberosites = $("#sacral_tuberosites").val();

    var expected_mode_of_delivery = $("#expected_mode_of_delivery").val();

    var remarks = $("#remarks").val();

    var admitted_by = $("#admitted_by").val();

    var informed_by = $("#informed_by").val();

    if (date_time != '' || temperature != '') {

        $.ajax({
            url: "repo/add_labor_record.php",
            type: "POST",
            data: {
                registration_id: registration_id,
                admission_id: admission_id,
                date_time: date_time,
                temperature: temperature,
                colour: colour,
                specific_gravity: specific_gravity,
                pulse: pulse,
                ph: ph,
                blood: blood,
                resp: resp,
                albumin: albumin,
                sugar: sugar,
                state_of_admission: state_of_admission,
                bp: bp,
                leucocytes: leucocytes,
                ketones: ketones,
                clinical_appearance: clinical_appearance,
                hb: hb,
                vdrl: vdrl,
                elisa: elisa,
                varicose_veins: varicose_veins,
                blood2: blood2,
                oedema: oedema,
                mental_status: mental_status,
                shape: shape,
                scars: scars,
                inspection: inspection,
                fundal_height: fundal_height,
                lie: lie,
                presentation: presentation,
                position: position,
                brim: brim,
                contraction: contraction,
                fhr: fhr,
                date_time2: date_time2,
                examinaer: examinaer,
                cervic_state: cervic_state,
                dilation: dilation,
                presenting_part: presenting_part,
                station: station,
                position2: position2,
                moulding: moulding,
                caput: caput,
                membrane_liquor: membrane_liquor,
                rapture: rapture,
                rapture_date: rapture_date,
                sacral_promontory: sacral_promontory,
                sacral_curve: sacral_curve,
                ischial_spine: ischial_spine,
                subpubic_angle: subpubic_angle,
                sacral_tuberosites: sacral_tuberosites,
                expected_mode_of_delivery: expected_mode_of_delivery,
                remarks: remarks,
                admitted_by: admitted_by,
                informed_by: informed_by
            },
            success: function (data) {

                alert(data);

                $("#patient_id").val("");

                $("#admission_id").val("");

                $("#date_time").val("");

                $("#temperature").val("");

                $("#colour").val("");

                $("#specific_gravity").val("");

                $("#pulse").val("");

                $("#ph").val("");

                $("#blood").val("");

                $("#resp").val("");

                $("#albumin").val("");

                $("#sugar").val("");

                $("#state_of_admission").val("");

                $("#bp").val("");

                $("#leucocytes").val("");

                $("#ketones").val("");

                $("#clinical_appearance").val("");

                $("#hb").val("");

                $("#vdrl").val("");

                $("#elisa").val("");

                $("#varicose_veins").val("");

                $("#blood2").val("");

                $("#oedema").val("");

                $("#mental_status").val("");

                $("#shape").val("");

                $("#scars").val("");

                $("#inspection").val("");

                $("#fundal_height").val("");

                $("#lie").val("");

                $("#presentation").val("");

                $("#position").val("");

                $("#brim").val("");

                $("#contraction").val("");

                $("#fhr").val("");

                $("#date_time2").val("");

                $("#examinaer").val("");

                $("#cervic_state").val("");

                $("#dilation").val("");

                $("#presenting_part").val("");

                $("#station").val("");

                $("#position2").val("");

                $("#moulding").val("");

                $("#caput").val("");

                $("#membrane_liquor").val("");

                $("#rapture").val("");

                $("#rapture_date").val("");

                $("#sacral_promontory").val("");

                $("#sacral_curve").val("");

                $("#ischial_spine").val("");

                $("#subpubic_angle").val("");

                $("#sacral_tuberosites").val("");

                $("#expected_mode_of_delivery").val("");

                $("#remarks").val("");

                $("#admitted_by").val("");

                $("#informed_by").val("");

            }

        });

    } else {
        alert("Please fill the required data");
    }
}
/********** FUNCTION TO ADD LABOR RECORD **************/
