<?php
session_start();

include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $hospital_no = mysql_real_escape_string($_POST['hospital_no']);
        $admissionDate = mysql_real_escape_string($_POST['admissionDate']);
        $admitting_doctor = mysql_real_escape_string($_POST['admitting_doctor']);
        $admitted_from = mysql_real_escape_string($_POST['admitted_from']);
        $reffered_from = mysql_real_escape_string($_POST['reffered_from']);
        $danger_signs = mysql_real_escape_string($_POST['danger_signs']);
        $patient_ID = mysql_real_escape_string($_POST['patient_ID']);
        $partner_name = mysql_real_escape_string($_POST['partner_name']);
        $gravida = mysql_real_escape_string($_POST['gravida']);
        $parity = mysql_real_escape_string($_POST['parity']);
        $current_children = mysql_real_escape_string($_POST['current_children']);
        $abortion = mysql_real_escape_string($_POST['abortion']);
        $lnmp = mysql_real_escape_string($_POST['lnmp']);
        $edd = mysql_real_escape_string($_POST['edd']);
        $ga = mysql_real_escape_string($_POST['ga']);
        $obste_history_1 = mysql_real_escape_string($_POST['obste_history_1']);
        $obste_history_2 = mysql_real_escape_string($_POST['obste_history_2']);
        $obste_history_3 = mysql_real_escape_string($_POST['obste_history_3']);
        $visits = mysql_real_escape_string($_POST['visits']);
        $IPT_doses = mysql_real_escape_string($_POST['IPT_doses']);
        $TT_doses = mysql_real_escape_string($_POST['TT_doses']);
        $ITN_doses= mysql_real_escape_string($_POST['ITN_doses']);
        $bloodgroup = mysql_real_escape_string($_POST['bloodgroup']);
        $last_Hb = mysql_real_escape_string($_POST['last_Hb']);
        $pmtct = mysql_real_escape_string($_POST['pmtct']);
        $art = mysql_real_escape_string($_POST['art']);
        $vdrl = mysql_real_escape_string($_POST['vdrl']);
        $labour_onset = mysql_real_escape_string($_POST['labour_onset']);
        $membranes_rapture = mysql_real_escape_string($_POST['membranes_rapture']);
        $fetal_mvt = mysql_real_escape_string($_POST['fetal_mvt']);
        $general = mysql_real_escape_string($_POST['general']);
        $pulse_rate = mysql_real_escape_string($_POST['pulse_rate']);
        $rufaasababu = mysql_real_escape_string($_POST['rufaasababu']);
        $maoni = mysql_real_escape_string($_POST['maoni']);
        $insert = mysql_query("INSERT INTO tbl_labour (admitted_from,reffered_from,danger_signs,patient_ID,partner_name,gravida,parity,current_children,abortion,lnmp,edd,ga,obste_history_1,obste_history_2,obste_history_3,visits,IPT_doses,TT_doses,ITN_doses,bloodgroup,last_Hb,pmtct,art,vdrl,labour_onset,membranes_rapture,fetal_mvt,general,pulse_rate,rufaasababu,maoni)
        VALUES ('$admitted_from','$reffered_from','$danger_signs','$patient_ID','$partner_name','$gravida','$parity','$current_children','$abortion','$lnmp','$edd','$ga','$obste_history_1','$obste_history_2','$obste_history_3','$visits','$IPT_doses','$TT_doses','$ITN_doses','$bloodgroup','$last_Hb','$pmtct','$art','$vdrl','$labour_onset','$membranes_rapture','$fetal_mvt','$general','$pulse_rate','$rufaasababu','$maoni')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}