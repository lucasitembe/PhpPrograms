<?php
session_start();

include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $hospital_no = mysqli_real_escape_string($conn,$_POST['hospital_no']);
        $admissionDate = mysqli_real_escape_string($conn,$_POST['admissionDate']);
        $admitting_doctor = mysqli_real_escape_string($conn,$_POST['admitting_doctor']);
        $admitted_from = mysqli_real_escape_string($conn,$_POST['admitted_from']);
        $reffered_from = mysqli_real_escape_string($conn,$_POST['reffered_from']);
        $danger_signs = mysqli_real_escape_string($conn,$_POST['danger_signs']);
        $patient_ID = mysqli_real_escape_string($conn,$_POST['patient_ID']);
        $partner_name = mysqli_real_escape_string($conn,$_POST['partner_name']);
        $gravida = mysqli_real_escape_string($conn,$_POST['gravida']);
        $parity = mysqli_real_escape_string($conn,$_POST['parity']);
        $current_children = mysqli_real_escape_string($conn,$_POST['current_children']);
        $abortion = mysqli_real_escape_string($conn,$_POST['abortion']);
        $lnmp = mysqli_real_escape_string($conn,$_POST['lnmp']);
        $edd = mysqli_real_escape_string($conn,$_POST['edd']);
        $ga = mysqli_real_escape_string($conn,$_POST['ga']);
        $obste_history_1 = mysqli_real_escape_string($conn,$_POST['obste_history_1']);
        $obste_history_2 = mysqli_real_escape_string($conn,$_POST['obste_history_2']);
        $obste_history_3 = mysqli_real_escape_string($conn,$_POST['obste_history_3']);
        $visits = mysqli_real_escape_string($conn,$_POST['visits']);
        $IPT_doses = mysqli_real_escape_string($conn,$_POST['IPT_doses']);
        $TT_doses = mysqli_real_escape_string($conn,$_POST['TT_doses']);
        $ITN_doses= mysqli_real_escape_string($conn,$_POST['ITN_doses']);
        $bloodgroup = mysqli_real_escape_string($conn,$_POST['bloodgroup']);
        $last_Hb = mysqli_real_escape_string($conn,$_POST['last_Hb']);
        $pmtct = mysqli_real_escape_string($conn,$_POST['pmtct']);
        $art = mysqli_real_escape_string($conn,$_POST['art']);
        $vdrl = mysqli_real_escape_string($conn,$_POST['vdrl']);
        $labour_onset = mysqli_real_escape_string($conn,$_POST['labour_onset']);
        $membranes_rapture = mysqli_real_escape_string($conn,$_POST['membranes_rapture']);
        $fetal_mvt = mysqli_real_escape_string($conn,$_POST['fetal_mvt']);
        $general = mysqli_real_escape_string($conn,$_POST['general']);
        $pulse_rate = mysqli_real_escape_string($conn,$_POST['pulse_rate']);
        $rufaasababu = mysqli_real_escape_string($conn,$_POST['rufaasababu']);
        $maoni = mysqli_real_escape_string($conn,$_POST['maoni']);
        $insert = mysqli_query($conn,"INSERT INTO tbl_labour (admitted_from,reffered_from,danger_signs,patient_ID,partner_name,gravida,parity,current_children,abortion,lnmp,edd,ga,obste_history_1,obste_history_2,obste_history_3,visits,IPT_doses,TT_doses,ITN_doses,bloodgroup,last_Hb,pmtct,art,vdrl,labour_onset,membranes_rapture,fetal_mvt,general,pulse_rate,rufaasababu,maoni)
        VALUES ('$admitted_from','$reffered_from','$danger_signs','$patient_ID','$partner_name','$gravida','$parity','$current_children','$abortion','$lnmp','$edd','$ga','$obste_history_1','$obste_history_2','$obste_history_3','$visits','$IPT_doses','$TT_doses','$ITN_doses','$bloodgroup','$last_Hb','$pmtct','$art','$vdrl','$labour_onset','$membranes_rapture','$fetal_mvt','$general','$pulse_rate','$rufaasababu','$maoni')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}