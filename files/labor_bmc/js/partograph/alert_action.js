function addAlertAction() {

    $("#save_alert_action").click(function (e) {

        e.preventDefault();

        var alert_x1 = $("#alert_x1").val();

        var alert_y1 = $("#alert_y1").val();

        var alert_x2 = $("#alert_x2").val();

        var alert_y2 = $("#alert_y2").val();

        var action_x1 = $("#action_x1").val();

        var action_y1 = $("#action_y1").val();

        var action_x2 = $("#action_x2").val();

        var action_y2 = $("#action_y2").val();

        if (alert_x1 != "" && alert_y1 != "" && alert_x2 != "" && alert_y2 != "" && action_x1 != "" && action_y1 != "" && action_x2 != "" && action_y2 != "") {
            $.ajax({
                url: "repo/add_alert_action.php",
                type: "POST",
                data: {
                    alert_x1: alert_x1,
                    alert_y1: alert_y1,
                    alert_x2: alert_x2,
                    alert_y2: alert_y2,
                    action_x1: action_x1,
                    action_y1: action_y1,
                    action_x2: action_x2,
                    action_y2: action_y2,
                },
                success: function (data) {

                    var alert_action = JSON.parse(data);

                    alert(alert_action);

                    var point3 = [];

                    var point4 = [];

                    var point5 = [];

                    var point6 = [];

                    point3 = [alert_action[0], alert_action[1]];

                    point4 = [alert_action[2], alert_action[3]];

                    point5 = [alert_action[4], alert_action[5]];

                    point6 = [alert_action[6], alert_action[7]];

                    powPoints3.push(point3);

                    powPoints3.push(point4);

                    powPoints4.push(point5);

                    powPoints4.push(point6);

                    plotLaborGraph();

                    $("#show_dialog_alert_action").dialog("close");

                    $("#alert_x1").val(alert_x1);

                    $("#alert_y1").val(alert_y1);

                    $("#alert_x2").val(alert_x2);

                    $("#alert_y2").val(alert_y2);

                    $("#action_x1").val(action_x1);

                    $("#action_y1").val(action_y1);

                    $("#action_x2").val(action_x2);

                    $("#action_y2").val(action_y2);

                }

            });
        } else {

            alert("Please Fill in required values");

        }

    });

}

function getAlertAction() {

    $.ajax({
        url: "repo/fetch_alert_action.php",
        type: "GET",
        success: function (data) {

            var alert_action = JSON.parse(data);

            var point1 = [];

            var point2 = [];

            var point7 = [];

            var point8 = [];

            for (var i = 0; i < alert_action.length; i++) {

                var counter = alert_action[i];

                point1 = [counter.alert_x1, counter.alert_y1];

                point2 = [counter.alert_x2, counter.alert_y2];

                point7 = [counter.action_x1, counter.action_y1];

                point8 = [counter.action_x2, counter.action_y2];

                powPoints3.push(point1);

                powPoints3.push(point2);

                powPoints4.push(point7);

                powPoints4.push(point8);

                plotLaborGraph();

                $("#alert_x1").val(counter.alert_x1);

                $("#alert_y1").val(counter.alert_y1);

                $("#alert_x2").val(counter.alert_x2);

                $("#alert_y2").val(counter.alert_y2);

                $("#action_x1").val(counter.action_x1);

                $("#action_y1").val(counter.action_y1);

                $("#action_x2").val(counter.action_x2);

                $("#action_y2").val(counter.action_y2);

            }

        }

    });
}