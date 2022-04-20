$(document).ready(function () {

    /**********  PARTOGRAPH REPORT JS **********/
    $('#show_dialog_partograph_report').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        position: ['center',50],
        title: 'PARTOGRAPH REPORT'
    });

    $("#partograph_report_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_partograph_report').dialog("open");
    });

    /**********  END PARTOGRAPH REPORT  JS **********/

});