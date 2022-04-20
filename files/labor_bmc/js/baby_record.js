$(document).ready(function () {

    $("#transferred_by").select2();

    $("#save_baby_record").click(function (e) {

        e.preventDefault();

        addBabyRecord();

    });

});

/********** FUNCTION TO ADD BABY RECORD  **************/
function addBabyRecord() {

    var registration_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var sex = $("#sex").val();

    var state_of_birth = $("#state_of_birth").val();

    var apgar = $("#apgar").val();

    var birth_weight = $("#birth_weight").val();

    var length = $("#length").val();

    var head_circumference = $("#head_circumference").val();

    var abnormalities = $("#abnormalities").val();

    var drugs = $("#drugs").val();

    var paediatrician = $("#paediatrician").val();

    var transferred_to = $("#transferred_to").val();

    var reason = $("#reason").val();

    var transferred_by = $("#transferred_by").val();

    var name = $("#name").val();

    var temperature = $("#temperature").val();

    $.ajax({
        type: "POST",
        url: "repo/add_baby_record.php",
        data: {
            registration_id: registration_id,
            admission_id: admission_id,
            sex: sex,
            state_of_birth: state_of_birth,
            apgar: apgar,
            birth_weight: birth_weight,
            length: length,
            head_circumference: head_circumference,
            abnormalities: abnormalities,
            drugs: drugs,
            paediatrician: paediatrician,
            transferred_to: transferred_to,
            reason: reason,
            transferred_by: transferred_by,
            name: name,
            temperature: temperature
        },
        success: function (data) {

            alert(data);

            $("#sex").val("");
        
            $("#state_of_birth").val("");
        
            $("#apgar").val("");
        
            $("#birth_weight").val("");
        
            $("#length").val("");
        
            $("#head_circumference").val("");
        
            $("#abnormalities").val("");
        
            $("#drugs").val("");
        
            $("#paediatrician").val("");
        
            $("#transferred_to").val("");
        
            $("#reason").val("");
        
            $("#transferred_by").val("");
        
            $("#name").val("");
        
            $("#temperature").val("");
        }
    });

}
/********** END FUNCTION TO ADD BABY RECORD **************/