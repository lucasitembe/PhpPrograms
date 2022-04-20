$(document).ready(function () {

    $("#paeditrician").select2();

    $("#anaesthetist").select2();

    $("#surgeon").select2();

    $("#physician").select2();

    $("#date_of_admission").datetimepicker({
        maxDate: 0
    });

    $("#date_of_anc").datetimepicker({
        maxDate: 0
    });

    $("#date_of_discharge").datetimepicker({
        maxDate: 0
    });

    $('#lmp_date').datetimepicker({
        maxDate: 0
    });

    $('#edd_date').datetimepicker({ });

    $('#ga_date').datetimepicker({
        maxDate: 0
    });

    $("#save_obstetric_history").click(function (e) {

        e.preventDefault();

        addObstetricHistory();

    });

    $("#save_obstretic_record").click(function (e) {

        e.preventDefault();

        addObstetricRecord();

    });

});

/********** FUNCTION TO CALCULATE DIFFERENCE IN WEEKS **************/
function diff_weeks_lmp(date) {

    dt1 = new Date(date);

    dt2 = new Date();

    var diff = (dt2.getTime() - dt1.getTime()) / 1000;

    diff /= (60 * 60 * 24 * 7);

    week = Math.abs(Math.round(diff));

    document.getElementById("lmp_duration").value = week;

}

function diff_weeks_edd(date) {

    dt1 = new Date(date);

    dt2 = new Date();

    var diff = (dt1.getTime() - dt2.getTime()) / 1000;

    diff /= (60 * 60 * 24 * 7);

    week = Math.abs(Math.round(diff));

    document.getElementById("edd_duration").value = week;

}

function diff_weeks_ga(date) {

    dt1 = new Date(date);

    dt2 = new Date();

    var diff = (dt2.getTime() - dt1.getTime()) / 1000;

    diff /= (60 * 60 * 24 * 7);

    week = Math.abs(Math.round(diff));

    document.getElementById("ga_duration").value = week;

}
/********** FUNCTION TO CALCULATE DIFFERENCE IN WEEKS **************/

/********** FUNCTION TO ADD OBSTETRIC HISTORY **************/
function addObstetricHistory() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var year_of_birth = $("#year_of_birth").val();

    var matunity = $("#matunity").val();

    var sex = $("#sex").val();

    var mode_of_delivery = $("#mode_of_delivery").val();

    var birth_weight = $("#birth_weight").val();

    var place_of_birth = $("#place_of_birth").val();

    var breastfed_duration = $("#breastfed_duration").val();

    var puerperium = $("#puerperium").val();

    var present_child_condition = $("#present_child_condition").val();

    $.ajax({
        type: "POST",
        url: "repo/add_obstretic_history.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            year_of_birth: year_of_birth,
            matunity: matunity,
            sex: sex,
            mode_of_delivery: mode_of_delivery,
            birth_weight: birth_weight,
            place_of_birth: place_of_birth,
            breastfed_duration: breastfed_duration,
            puerperium: puerperium,
            present_child_condition: present_child_condition
        },
        success: function (data) {

            alert(data);

            window.location.reload();

            $("#year_of_birth").val("");

            $("#matunity").val("");

            $("#sex").val("");

            $("#mode_of_delivery").val("");

            $("#birth_weight").val("");

            $("#place_of_birth").val("");

            $("#breastfed_duration").val("");

            $("#puerperium").val("");

            $("#present_child_condition").val("");

        }
    });
}
/********** END FUNCTION TO ADD OBSTETRIC HISTORY **************/

/********** FUNCTION TO ADD OBSTETRIC RECORD **************/
function addObstetricRecord() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var paeditrician =$("#paeditrician").val();

    var anaesthetist = $("#anaesthetist").val();

    var surgeon = $("#surgeon").val();

    var physician = $("#physician").val();

    var date_of_admission = $("#date_of_admission").val();

    var date_of_anc = $("#date_of_anc").val();

    var drug_allergies = $("#drug_allergies").val();

    var date_of_discharge = $("#date_of_discharge").val();

    var lmp_duration = $("#lmp_date").val();

    var edd_duration = $("#edd_date").val();

    var ga_duration = $("#ga_duration").val();

    var para = $("#para").val();

    var gravida = $("#gravida").val();

    var blood_group = $("#blood_group").val();

    var weight = $("#weight").val();

    var height = $("#height").val();

    var medical_surgical_history = $("#medical_surgical_history").val();

    var family_history = $("#family_history").val();

    var reason_for_admission = $("#reason_for_admission").val();

    $.ajax({
        type: "POST",
        url: "repo/add_obstretic_record.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            paeditrician: paeditrician,
            anaesthetist: anaesthetist,
            surgeon: surgeon,
            physician: physician,
            date_of_admission: date_of_admission,
            date_of_anc: date_of_anc,
            drug_allergies: drug_allergies,
            date_of_discharge: date_of_discharge,
            lmp_duration: lmp_duration,
            edd_duration: edd_duration,
            ga_duration: ga_duration,
            para: para,
            gravida: gravida,
            blood_group: blood_group,
            weight: weight,
            height: height,
            medical_surgical_history: medical_surgical_history,
            family_history: family_history,
            reason_for_admission: reason_for_admission
        },
        success: function (data) {

            alert(data);

            $("#paeditrician").val("");

            $("#anaesthetist").val("");

            $("#surgeon").val("");

            $("#physician").val("");

            $("#date_of_admission").val("");

            $("#date_of_anc").val("");

            $("#drug_allergies").val("");

            $("#date_of_discharge").val("");

            $("#lmp_duration").val("");

            $("#edd_duration").val("");

            $("#ga_duration").val("");

            $("#para").val("");

            $("#gravida").val("");

            $("#blood_group").val("");

            $("#weight").val("");

            $("#height").val("");

            $("#medical_surgical_history").val("");

            $("#family_history").val("");

            $("#reason_for_admission").val("");

        }

    });

}
/********** END FUNCTION TO ADD OBSTETRIC RECORD **************/
