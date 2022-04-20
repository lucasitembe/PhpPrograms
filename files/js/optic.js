$(document).ready(function(e) {

    var patientID = $("#patient_id").val();
    $.ajax({
        url: 'patient_previous_file.php',
        method: 'POST',
        data: {
            patient_id: patientID
        },
        success: function(data) {
            console.log(data)
            var d = JSON.parse(data)
            console.log(d);
            d.forEach(element => {
                $("#files").append("<button  class='history art-button' id='" + element['patientId'] + "'>" + element['date'] + "</button>")
            });


            $(".history").click(function() {
                // alert("history")
                var patientId = $(this).attr('id');
                var date = $(this).text();
                // alert(date)
                // window.location = 'optic.php?patientId=' +
                // patientId + '&date=' + date + '&record=record';
                window.open('optic_previous.php?patientId=' +
                    patientId + '&date=' + date + '&record=record', '_blank');
            })
        }

    })



    // save right eye data
    $(".distance-reading-right").keyup(function(e) {
        e.preventDefault()
        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();

        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_optical.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })

    // save left eye data
    $(".distance-reading-left").keyup(function(e) {
        e.preventDefault()
        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();

        var patientId = $("#patient_id").val();
        $.ajax({
            url: "save_optical_distance_left.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })
    })


    // save spectacle right eye data
    $(".spectacle-right").keyup(function(e) {
        e.preventDefault()
        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();

        patientId = $("#patient_id").val();
        $.ajax({
            url: "save_spectacle_right_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })
    })

    // save spectacle left data
    $(".spectacle-left").keyup(function(e) {
        e.preventDefault()

        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();

        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_spectacle_left_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })
    })

    // save i.p distance right data
    $(".ip-right").keyup(function(e) {
        e.preventDefault()

        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_ip_distabce_right_data.php",
            type: "POST",
            data: ({
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            }),
            success: function(data) {
                console.log(data)
            }
        })

    })

    // save ip distance left data
    $(".ip-left").keyup(function(e) {
        e.preventDefault()
        var name = "";
        name = $(this).attr("name");
        nameValue = $("input[name*='" +
            name + "']").val();

        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_ip_distance_left_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })


    // save va right eye
    $("#vau_right").change(function(e) {
        e.preventDefault()
        console.log($(this).val());
        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_va_right_eye.php",
            type: "POST",
            data: ({
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            }),
            success: function(data) {
                console.log(data)
            }
        })

    })

    // save va left eye data
    $("#vau_left").change(function(e) {
        e.preventDefault()

        console.log($(this).val());
        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_va_left_eye_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })


    // save ph right eye data
    $("#ph_right").change(function(e) {
        e.preventDefault()
        console.log($(this).val());

        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_va_right_eye.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })

    // save ph left eye data
    $("#ph_left").change(function(e) {
        e.preventDefault()
        console.log($(this).val());

        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val()

        $.ajax({
            url: "save_va_left_eye_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
            ``
        })

    })


    // save glasses left eye data
    $("#glasses_left").change(function(e) {
        e.preventDefault()
        console.log($(this).val());

        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_va_left_eye_data.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })

    // save glasses right eye data
    $("#glasses_right").change(function(e) {
        e.preventDefault()
        console.log($(this).val());

        var name = "";
        name = $(this).attr("name");
        nameValue = $(this).val();
        var patientId = $("#patient_id").val();

        $.ajax({
            url: "save_va_right_eye.php",
            type: "POST",
            data: {
                patient_id: patientId,
                name: name,
                nameValue: nameValue
            },
            success: function(data) {
                console.log(data)
            }
        })

    })



    var spectacleStatusValue = $("#spectaclestatus").val();

    if (spectacleStatusValue === "no") {
        $("#reason").show();
        $(".add-items").hide();
    } else {
        $("#reason").hide();
    }

    // check if spectacle offered
    $(".offer-spectacle").click(function(e) {
        // e.preventDefault();

        var spectacle = $(this).val();

        var patientId = $("#patient_id").val();
        toggleResonInput(spectacle);
        $.ajax({
            type: "POST",
            url: "spectacle_offered.php",
            data: {
                spectacle: spectacle,
                patient_id: patientId
            },
            success: function(result) {
                console.log(result);
            }
        })

    })

    // save all data
    $("#save").click(function(e) {
        e.preventDefault();
        var patientId = $("#patient_id").val();
        alert(patientId)
        $.ajax({
            type: "POST",
            url: "save_all_optical.php",
            data: {
                patient_id: patientId
            },
            success: function(data) {
                alert(data + patientId);
            }
        })
    })


    // save no spectacle reasons
    $("#reason").keyup(function() {

        var reason = $(this).val();

        var patientId = $("#patient_id").val();

        $.ajax({
            type: "POST",
            url: "no_spectacle_reasons.php",
            data: {
                reason: reason,
                patient_id: patientId
            },
            success: function(result) {
                console.log(result);
            }
        })

    })



    function toggleResonInput(spectacle) {
        if (spectacle === "") {
            $("#reason").hide();
            $(".add-items").hide();
        } else if (spectacle === "no") {
            $("#reason").show();
            $(".add-items").hide();
        } else if (spectacle === "yes") {
            $("#reason").hide();
            $(".add-items").show();
        }
    }








})