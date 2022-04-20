$(document).ready(function () {

    $("#hospital_duration").datetimepicker({
        maxDate: 0
    });

    $("#next_appointment").datetimepicker({
        maxDate: 0
    });

    $("#midwife_name").select2();

    $("#midwife_name2").select2();

    $("#save_post_natal_record").click(function (e) {

        e.preventDefault();

        addPostNatalRecord();

    });

});

/********** FUNCTION TO ADD BABY RECORD  **************/
function addPostNatalRecord() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var hospital_duration = $("#hospital_duration").val();

    var next_appointment = $("#next_appointment").val();

    var peurperium = $("#peurperium").val();

    var uterus = $("#uterus").val();

    var lochia = $("#lochia").val();

    var midwife_name = $("#midwife_name").val();

    var episiotomy = $("#episiotomy").val();

    var breasts = $("#breasts").val();

    var abdominal_scars = $("#abdominal_scars").val();

    var general_condition = $("#general_condition").val();

    var anaemia = $("#anaemia").val();

    var breasts2 = $("#breasts2").val();

    var cervix = $("#cervix").val();

    var vagina = $("#vagina").val();

    var episiotomy2 = $("#episiotomy2").val();

    var stress_incontinence = $("#stress_incontinence").val();

    var anus = $("#anus").val();

    var tenderness = $("#tenderness").val();

    var remarks = $("#remarks").val();

    var temperature = $("#temperature").val();

    var pulse = $("#pulse").val();

    var bp = $("#bp").val();

    var baby_condition = $("#baby_condition").val();

    var mother_remarks = $("#mother_remarks").val();

    var midwife_name2 = $("#midwife_name2").val();

    $.ajax({
        type: "POST",
        url: "repo/add_post_natal_record.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            hospital_duration: hospital_duration,
            next_appointment: next_appointment,
            peurperium: peurperium,
            uterus: uterus,
            lochia: lochia,
            midwife_name: midwife_name,
            episiotomy: episiotomy,
            breasts: breasts,
            abdominal_scars: abdominal_scars,
            general_condition: general_condition,
            anaemia: anaemia,
            breasts2: breasts2,
            cervix: cervix,
            vagina: vagina,
            episiotomy2: episiotomy2,
            stress_incontinence: stress_incontinence,
            anus: anus,
            tenderness: tenderness,
            remarks: remarks,
            temperature: temperature,
            pulse: pulse,
            bp: bp,
            baby_condition: baby_condition,
            mother_remarks: mother_remarks,
            midwife_name2: midwife_name2
        },
        success: function (data) {

            alert(data);

            $("#hospital_duration").val("");
        
            $("#next_appointment").val("");
        
            $("#peurperium").val("");
        
            $("#uterus").val("");
        
            $("#lochia").val("");
        
            $("#midwife_name").val("");
        
            $("#episiotomy").val("");
        
            $("#breasts").val("");
        
            $("#abdominal_scars").val("");
        
            $("#general_condition").val("");
        
            $("#anaemia").val("");
        
            $("#breasts2").val("");
        
            $("#cervix").val("");
        
            $("#vagina").val("");
        
            $("#episiotomy2").val("");
        
            $("#stress_incontinence").val("");
        
            $("#anus").val("");
        
            $("#tenderness").val("");
        
            $("#remarks").val("");
        
            $("#temperature").val("");
        
            $("#pulse").val("");
        
            $("#bp").val("");
        
            $("#baby_condition").val("");
        
            $("#mother_remarks").val("");
        
            $("#midwife_name2").val("");

        }

    });

}
/********** END FUNCTION TO ADD BABY RECORD **************/