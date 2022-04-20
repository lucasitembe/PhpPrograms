var patient_id = $("#patient_id").val();

var admission_id = $("#admission_id").val();

function addContraction() {

    $("#savec").click(function (e) {

        e.preventDefault();

        var time = $(".show-timec").val();

        var contraction_per_min = $(".contraction_per_min:checked").val();

        var contraction_no = $(".contraction_no").val();

        var contraction_loop = $(".contraction_loop").val();

        var time_remark = $("#contraction_time_remark").val();

        $.ajax({
            url: "repo/add_contraction.php",
            type: "POST",
            data: {
                patient_id: patient_id,
                admission_id: admission_id,
                contraction_lasts: contraction_per_min,
                contraction: contraction_no,
                contraction_loop: contraction_loop,
                time: time,
                hour: time_remark
            },
            success: function (data) {

                $("#contraction_time_hour #" + time).html(time_remark);

                jsonData = JSON.parse(data);

                time_new = jsonData.time;

                contraction_new = jsonData.contraction;

                if (jsonData.contraction_lasts == "1") {

                    if (jsonData.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (jsonData.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five");
                        }
                    }

                    if (jsonData.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "4") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "5") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                }

                if (jsonData.contraction_lasts == "2") {

                    if (jsonData.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (jsonData.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "4") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "5") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                }

                if (jsonData.contraction_lasts == "3") {

                    if (jsonData.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (jsonData.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "4") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                    if (jsonData.contraction_loop == "5") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "three") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "four") {
                            alert("Can not exceed contraction of five")
                        }

                        if (contraction_new == "five") {
                            alert("Can not exceed contraction of five")
                        }

                    }

                }

                $("#show_dialogc").dialog("close");

            }

        });

    });

}


function getContraction() {

    $.ajax({
        url: "repo/fetch_contraction.php",
        type: "POST",
        data: {
            patient_id: patient_id,
            admission_id: admission_id
        },
        success: function (data) {

            var jsonData = JSON.parse(data);

            for (var i = 0; i < jsonData.length; i++) {

                var counter = jsonData[i];

                time_new = counter.time;

                contraction_new = counter.contraction;

                $("#contraction_time_hour #" + time_new).html(counter.hour);

                if (counter.contraction_lasts == "1") {

                    if (counter.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }
                        
                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "4") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "5") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                }

                if (counter.contraction_lasts == "2") {

                    if (counter.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "4") {
                        
                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "5") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                }

                if (counter.contraction_lasts == "3") {

                    if (counter.contraction_loop == "1") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "five") {
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "2") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "four") {
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "3") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }
                        
                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }
                        if (contraction_new == "three") {
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "4") {

                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                        if (contraction_new == "two") {
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                    if (counter.contraction_loop == "5") {
                        if (contraction_new == "one") {
                            $("#one #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#two #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#three #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#four #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                            $("#five #" + time_new).html(
                                "<div class='image'>" +
                                "<img src='img/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
                        }

                    }

                }

            }

        }

    });

}