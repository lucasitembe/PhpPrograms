<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $jalada_no = mysql_real_escape_string($_POST['jalada_no']);
        $rch_no = mysql_real_escape_string($_POST['rch_no']);
        $gravida = mysql_real_escape_string($_POST['gravida']);
        $para = mysql_real_escape_string($_POST['para']);
        $watoto_hai = mysql_real_escape_string($_POST['watoto_hai']);
        $admission_date = mysql_real_escape_string($_POST['admission_date']);
        $kujifungua_trh = mysql_real_escape_string($_POST['kujifungua_trh']);
        $mtotoUzito = mysql_real_escape_string($_POST['mtotoUzito']);
        $uchungu = mysql_real_escape_string($_POST['uchungu']);
        $jifungulia = mysql_real_escape_string($_POST['jifungulia']);
        $kujifungua_njia = mysql_real_escape_string($_POST['kujifungua_njia']);
        $mtoto_jinsi = mysql_real_escape_string($_POST['mtoto_jinsi']);
        $kupumua = mysql_real_escape_string($_POST['kupumua']);
        $apgar = mysql_real_escape_string($_POST['apgar']);
        $nyonyeshwa = mysql_real_escape_string($_POST['nyonyeshwa']);
        $tathmin = mysql_real_escape_string($_POST['tathmin']);
        $MSB = mysql_real_escape_string($_POST['MSB']);
        $AP = mysql_real_escape_string($_POST['AP']);
        $PPH = mysql_real_escape_string($_POST['PPH']);
        $antibiotic = mysql_real_escape_string($_POST['antibiotic']);
        $miso = mysql_real_escape_string($_POST['miso']);
        $sulfate= mysql_real_escape_string($_POST['sulfate']);
        $MVA = mysql_real_escape_string($_POST['MVA']);
        $ongeza_damu = mysql_real_escape_string($_POST['ongeza_damu']);
        $FGM = mysql_real_escape_string($_POST['FGM']);
        $VVU_Kipimo = mysql_real_escape_string($_POST['VVU_Kipimo']);
        $VVU_uchungu = mysql_real_escape_string($_POST['VVU_uchungu']);
        $ARV_mtoto = mysql_real_escape_string($_POST['ARV_mtoto']);
        $mtoto_ulishaji = mysql_real_escape_string($_POST['mtoto_ulishaji']);
        $mtoto_hali = mysql_real_escape_string($_POST['mtoto_hali']);
        $mama_hali_details = mysql_real_escape_string($_POST['mama_hali_details']);
        $mama_discharge = mysql_real_escape_string($_POST['mama_discharge']);
        $kifo_mama_sababu = mysql_real_escape_string($_POST['kifo_mama_sababu']);
        $mtoto_hali_details = mysql_real_escape_string($_POST['mtoto_hali_details']);
        $mtoto_discharge = mysql_real_escape_string($_POST['mtoto_discharge']);
        $kifo_mtoto_sababu = mysql_real_escape_string($_POST['kifo_mtoto_sababu']);
        $alikopelekwa = mysql_real_escape_string($_POST['alikopelekwa']);
        $sababu_rufaa=mysql_real_escape_string($_POST['sababu_rufaa']);
        $mzalishaji = mysql_real_escape_string($_POST['mzalishaji']);
        $mama_hali = mysql_real_escape_string($_POST['mama_hali']);
        $Patient_No=  mysql_real_escape_string($_POST['patient_ID']);

        $insert = mysql_query("INSERT INTO tbl_wazazi (Patient_No,jalada_no,rch_no,gravida,para,watoto_hai,admission_date,kujifungua_trh,mtotoUzito,uchungu,jifungulia,kujifungua_njia,mtoto_jinsi,kupumua,apgar,nyonyeshwa,tathmin,MSB,AP,PPH,antibiotic,miso,sulfate,MVA,ongeza_damu,FGM,VVU_Kipimo,VVU_uchungu,ARV_mtoto,mtoto_ulishaji,mama_hali,mtoto_hali,mama_hali_details,mama_discharge,kifo_mama_sababu,mtoto_hali_details,mtoto_discharge,kifo_mtoto_sababu,alikopelekwa,sababu_rufaa,mzalishaji)
        VALUES ('$Patient_No','$jalada_no','$rch_no','$gravida','$para','$watoto_hai','$admission_date','$kujifungua_trh','$mtotoUzito','$uchungu','$jifungulia','$kujifungua_njia','$mtoto_jinsi','$kupumua','$apgar','$nyonyeshwa','$tathmin','$MSB','$AP','$PPH','$antibiotic','$miso','$sulfate','$MVA','$ongeza_damu','$FGM','$VVU_Kipimo','$VVU_uchungu','$ARV_mtoto','$mtoto_ulishaji','$mama_hali','$mtoto_hali','$mama_hali_details','$mama_discharge','$kifo_mama_sababu','$mtoto_hali_details','$mtoto_discharge','$kifo_mtoto_sababu','$alikopelekwa','$sababu_rufaa','$mzalishaji')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}