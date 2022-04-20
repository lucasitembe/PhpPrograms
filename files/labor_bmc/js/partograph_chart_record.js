$(document).ready(function () {

    var patient_id = $("#patient_id").val();

    var admission_id = $("#admission_id").val();

    var moulding = "";
    let liqour_remark = "";

    plotFetalGraph();

    plotLaborGraph();

    /**********  ALERT ACTION JS **********/
    $('#show_dialog_alert_action').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        height: 300,
        title: 'Alert and Action'
    });

    $("#alert_action_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_alert_action').dialog("open");
    });

    addAlertAction();

    getAlertAction();
    /**********  END ALERT ACTION JS **********/


    /**********  FETAL HEART RATE JS **********/
    $("#time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    addFetalHeartRate();

    getFetalHeartRate();
    /**********  END FETAL HEART RATE JS **********/


    /**********  PROGRESS OF LABOR JS **********/
    $("#labour_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    addProgressOfLabor();

    getProgressOfLabor();
    /**********  END PROGRESS OF LABOR JS **********/


    /**********  LIQUOR STATE TIME JS **********/
    $("#liquor_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $('#show_dialog_liqour_time').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Time'
    });

    $("#liquor_time_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_liqour_time').dialog("open");
    });

    updateLiquorTime();

    getLiquorTime();
    /**********  END LIQUOR STATE TIME JS **********/


    /**********  LIQUOR STATE JS **********/
    $('#show_dialogl').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'State Of Liqour'
    });

    $(".btn-inputl").click(function (e) {
        e.preventDefault();
        liqour_remark = $(this).text();
        $("#selected_liqour").html(liqour_remark);
        $("#show_dialogl").dialog("open");

        addStateOfLiquor(liqour_remark);
    });

    getStateOfLiquor();
    /**********  END LIQUOR STATE JS **********/


    /**********  MOULDING JS **********/
    $('#show_dialogm').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Moulding'
    });

    $(".btn-inputm").click(function (e) {
        e.preventDefault();
        var val = $(this).text();
        moulding = val;
        $("#selected_moulding").html(val)
        $("#show_dialogm").dialog("open");

        addMoulding(moulding);
    })

    getMoulding();
    /**********  END MOULDING JS **********/


    /**********  CAPUT JS **********/
    $('#show_dialog_caput').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        height: 300,
        title: 'Caput'
    });

    $("#caput_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_caput').dialog("open");
    });

    addCaput();

    getCaput();
    /**********  END CAPUT JS **********/


    /**********  PROTEIN JS **********/
    $('#show_dialogp').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Protein'
    });

    $("#protein").click(function (e) {
        e.preventDefault();
        $("#show_dialogp").dialog("open");
    });

    addProtein();

    getProtein();
    /**********  END PROTEIN JS **********/


    /**********  ACETONE JS **********/
    $('#show_dialoga').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Acetone'
    });

    $("#acetone").click(function (e) {
        e.preventDefault();
        $('#show_dialoga').dialog("open")
    });

    addAcetone();

    getAcetone();
    /**********  END ACETONE JS **********/

    /**********  VOLUME JS **********/
    $('#show_dialogv').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Volume'
    });

    $("#volume").click(function (e) {
        e.preventDefault();
        $('#show_dialogv').dialog("open")
    });

    addVolume();

    getVolume();
    /**********  END VOLUME JS **********/


    /**********  BLOOD PRESSURE JS **********/
    $('#show_dialogbp').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Blood Pressure'
    });

    $("#bp").click(function (e) {
        e.preventDefault();
        $("#show_dialogbp").dialog("open");
    });

    addBloodPressure();

    getBloodPressure();
    /**********  END BLOOD PRESSURE JS **********/


    /**********  TEMPERATURE JS **********/
    $('#show_dialogt').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Temperature'
    });

    $("#temperature").click(function (e) {
        e.preventDefault();
        $('#show_dialogt').dialog("open")
    });

    addTemperature();

    getTemperature();
    /**********  END TEMPERATURE JS **********/


    /**********  RESP JS **********/
    $('#show_dialogr').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Resp'
    });

    $("#resp").click(function (e) {
        e.preventDefault();
        $('#show_dialogr').dialog("open")
    });

    addResp();

    getResp();
    /**********  END RESP JS **********/


    /**********  OXYTOCIN JS **********/
    $('#show_dialogo').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Oxytocine'
    });

    $("#oxyticin").click(function (e) {
        e.preventDefault();
        $('#show_dialogo').dialog("open");
    });

    addOxytocin();

    getOxytocine();
    /**********  END OXYTOCIN JS **********/


    /**********  DROPS JS **********/
    $('#show_dialogd').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Drops/Minute Pulse'
    });

    $("#drop").click(function (e) {
        e.preventDefault();
        $('#show_dialogd').dialog("open");
    });

    addDrops();

    getDrops();
    /**********  END DROPS JS **********/


    /**********  CONTRACTION JS **********/
    $("#contraction_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    })

    $('#show_dialogc').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Contraction in 10 min'
    });

    $("#contraction").click(function (e) {
        e.preventDefault();
        $('#show_dialogc').dialog("open");
    });

    addContraction();

    getContraction();
    /**********  END CONTRACTION JS **********/


    /**********  OXYTOCIN TIME JS **********/
    $("#oxytocin_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $('#show_dialog_oxytocin_time').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Time'
    });

    $("#oxytocin_time_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_oxytocin_time').dialog("open");
    });

    updateOxytocineTime();

    getOxytocineTime();
    /**********  END OXYTOCIN TIME JS **********/


    /**********  TEMPERATURE TIME JS **********/
    $("#temp_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $('#show_dialog_temp_time').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Time'
    });

    $("#temp_time_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_temp_time').dialog("open");
    });

    updateTempTime();

    getTempTime();
    /**********  END TEMPERATURE TIME JS **********/


    /**********  PROTEIN TIME JS **********/
    $("#protein_time_remark").datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 1
    });

    $('#show_dialog_protein_time').dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 300,
        title: 'Time'
    });

    $("#protein_time_open").click(function (e) {
        e.preventDefault();
        $('#show_dialog_protein_time').dialog("open");
    });

    updateProteinTime();

    getProteinTime();
    /**********  END PROTEIN TIME JS **********/

    /**********  BABY STATUS JS **********/
    $("#one_minute").click(function (e) {

        e.preventDefault();

        addBabyStatusAfterOneMinute();
    });

    $("#five_minutes").click(function (e) {

        e.preventDefault();

        addBabyStatusAfterFiveMinute();
    });

    $("#heart_rate_less_60").click(function () {
        $('#heart_rate_60').prop("disabled", true);
        $('#heart_rate_greater_60').prop("disabled", true);
    });
    $("#heart_rate_60").click(function () {
        $('#heart_rate_less_60').prop("disabled", true);
        $('#heart_rate_greater_60').prop("disabled", true);
    });
    $("#heart_rate_greater_60").click(function () {
        $('#heart_rate_60').prop("disabled", true);
        $('#heart_rate_less_60').prop("disabled", true);
    });

    $("#respiration_none").click(function () {
        $('#respiration_gasp').prop("disabled", true);
        $('#respiration_cry').prop("disabled", true);
    });
    $("#respiration_gasp").click(function () {
        $('#respiration_none').prop("disabled", true);
        $('#respiration_cry').prop("disabled", true);
    });
    $("#respiration_cry").click(function () {
        $('#respiration_gasp').prop("disabled", true);
        $('#respiration_none').prop("disabled", true);
    });

    $("#muscle_tone_none").click(function () {
        $('#muscle_tone_bil').prop("disabled", true);
        $('#muscle_tone_mvt').prop("disabled", true);
    });
    $("#muscle_tone_bil").click(function () {
        $('#muscle_tone_none').prop("disabled", true);
        $('#muscle_tone_mvt').prop("disabled", true);
    });
    $("#muscle_tone_mvt").click(function () {
        $('#muscle_tone_bil').prop("disabled", true);
        $('#muscle_tone_none').prop("disabled", true);
    });

    $("#reflexe_none").click(function () {
        $('#reflexe_grimance').prop("disabled", true);
        $('#reflexe_cough').prop("disabled", true);
    });
    $("#reflexe_grimance").click(function () {
        $('#reflexe_none').prop("disabled", true);
        $('#reflexe_cough').prop("disabled", true);
    });
    $("#reflexe_cough").click(function () {
        $('#reflexe_none').prop("disabled", true);
        $('#reflexe_grimance').prop("disabled", true);
    });

    $("#color_none").click(function () {
        $('#color_blue').prop("disabled", true);
        $('#color_pink').prop("disabled", true);
    });
    $("#color_blue").click(function () {
        $('#color_none').prop("disabled", true);
        $('#color_pink').prop("disabled", true);
    });
    $("#color_pink").click(function () {
        $('#color_blue').prop("disabled", true);
        $('#color_none').prop("disabled", true);
    });

    $("#five_heart_rate_less_60").click(function () {
        $('#five_heart_rate_60').prop("disabled", true);
        $('#five_heart_rate_greater_60').prop("disabled", true);
    });
    $("#five_heart_rate_60").click(function () {
        $('#five_heart_rate_less_60').prop("disabled", true);
        $('#five_heart_rate_greater_60').prop("disabled", true);
    });
    $("#five_heart_rate_greater_60").click(function () {
        $('#five_heart_rate_60').prop("disabled", true);
        $('#five_heart_rate_less_60').prop("disabled", true);
    });

    $("#five_respiration_none").click(function () {
        $('#five_respiration_gasp').prop("disabled", true);
        $('#five_respiration_cry').prop("disabled", true);
    });
    $("#five_respiration_gasp").click(function () {
        $('#five_respiration_none').prop("disabled", true);
        $('#five_respiration_cry').prop("disabled", true);
    });
    $("#five_respiration_cry").click(function () {
        $('#five_respiration_gasp').prop("disabled", true);
        $('#five_respiration_none').prop("disabled", true);
    });

    $("#five_muscle_tone_none").click(function () {
        $('#five_muscle_tone_bil').prop("disabled", true);
        $('#five_muscle_tone_mvt').prop("disabled", true);
    });
    $("#five_muscle_tone_bil").click(function () {
        $('#five_muscle_tone_none').prop("disabled", true);
        $('#five_muscle_tone_mvt').prop("disabled", true);
    });
    $("#five_muscle_tone_mvt").click(function () {
        $('#five_muscle_tone_bil').prop("disabled", true);
        $('#five_muscle_tone_none').prop("disabled", true);
    });

    $("#five_reflexe_none").click(function () {
        $('#five_reflexe_grimance').prop("disabled", true);
        $('#five_reflexe_cough').prop("disabled", true);
    });
    $("#five_reflexe_grimance").click(function () {
        $('#five_reflexe_none').prop("disabled", true);
        $('#five_reflexe_cough').prop("disabled", true);
    });
    $("#five_reflexe_cough").click(function () {
        $('#five_reflexe_grimance').prop("disabled", true);
        $('#five_reflexe_none').prop("disabled", true);
    });

    $("#five_color_none").click(function () {
        $('#five_color_blue').prop("disabled", true);
        $('#five_color_pink').prop("disabled", true);
    });
    $("#five_color_blue").click(function () {
        $('#five_color_none').prop("disabled", true);
        $('#five_color_pink').prop("disabled", true);
    });
    $("#five_color_pink").click(function () {
        $('#five_color_blue').prop("disabled", true);
        $('#five_color_none').prop("disabled", true);
    });

    getBabyStatusAfterOneMinute();

    getBabyStatusAfterFiveMinute();
    /**********  END BABY STATUS JS **********/


    /**********  SUMMARY OF LABOR JS **********/
    $(".tear_div").hide();

    $("#perineum").click(function () {

        if ($("#perineum :selected").text() == "Tear") {
            $(".tear_div").show();
        } else {
            $(".tear_div").hide();
        }

    });

    $('#date_birth').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        value: '',
        step: 01
    });

    addSummaryOfLabor();
    /**********  END SUMMARY OF LABOR JS **********/

});