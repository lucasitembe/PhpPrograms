$(document).ready(function () {

    $("#dob").datetimepicker({
        maxDate: 0
    });

    $("#delivered_by").select2();

    $("#prem_unit_by").select2();

    $("#recieved_by").select2();

    $("#time").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $("#save_neonatal_record").click(function (e) {

        e.preventDefault();

        addNeonatalRecord();

    });
});

/********** FUNCTION TO ADD LABOR RECORD **************/
function addNeonatalRecord() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var dob = $("#dob").val();

    var sex = $("#sex").val();

    var wt = $("#wt").val();

    var apgar = $("#apgar").val();

    var maturity = $("#maturity").val();

    var membranes_ruptured = $("#membranes_ruptured").val();

    var amniotic_fluids = $("#amniotic_fluids").val();

    var antenatal_care = $("#antenatal_care").val();

    var diseases_complications = $("#diseases_complications").val();

    var delivery_type = $("#delivery_type").val();

    var indication = $("#indication").val();

    var fhr = $("#fhr").val();

    var placenta = $("#placenta").val();

    var placenta_weight = $("#placenta_weight").val();

    var abnormalities = $("#abnormalities").val();

    var resucitation = $("#resucitation").val();

    var drugs_given = $("#drugs_given").val();

    var eye_drops = $("#eye_drops").val();

    var sent_to = $("#sent_to").val();

    var delivered_by = $("#delivered_by").val();

    var prem_unit_by = $("#prem_unit_by").val();

    var recieved_by = $("#recieved_by").val();

    var condition_on_arrival = $("#condition_on_arrival").val();

    var time = $("#time").val();

    if (dob != '' || sex != '') {

        $.ajax({
            url: "repo/add_neonatal_record.php",
            type: "POST",
            data: {
                registration_id: registration_id,
                admission_id: admission_id,
                dob: dob,
                sex: sex,
                wt: wt,
                apgar: apgar,
                maturity: maturity,
                membranes_ruptured: membranes_ruptured,
                amniotic_fluids: amniotic_fluids,
                antenatal_care: antenatal_care,
                diseases_complications: diseases_complications,
                delivery_type: delivery_type,
                indication: indication,
                fhr: fhr,
                placenta: placenta,
                placenta_weight: placenta_weight,
                abnormalities: abnormalities,
                resucitation: resucitation,
                drugs_given: drugs_given,
                eye_drops: eye_drops,
                sent_to: sent_to,
                delivered_by: delivered_by,
                prem_unit_by: prem_unit_by,
                recieved_by: recieved_by,
                condition_on_arrival: condition_on_arrival,
                time: time
            },
            success: function (data) {

                alert(data);

                $("#dob").val("");

                $("#sex").val("");

                $("#wt").val("");

                $("#apgar").val("");

                $("#maturity").val("");

                $("#membranes_ruptured").val("");

                $("#amniotic_fluids").val("");

                $("#antenatal_care").val("");

                $("#diseases_complications").val("");

                $("#delivery_type").val("");

                $("#indication").val("");

                $("#fhr").val("");

                $("#placenta").val("");

                $("#placenta_weight").val("");

                $("#abnormalities").val("");

                $("#resucitation").val("");

                $("#drugs_given").val("");

                $("#eye_drops").val("");

                $("#sent_to").val("");

                $("#delivered_by").val("");

                $("#prem_unit_by").val("");

                $("#recieved_by").val("");

                $("#condition_on_arrival").val("");

                $("#time").val("");

            }

        });

    } else {
        alert("Please fill the required data");
    }
}
/********** FUNCTION TO ADD LABOR RECORD **************/