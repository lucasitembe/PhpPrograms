<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $jalada_no = mysqli_real_escape_string($conn,$_POST['jalada_no']);
        $rch_no = mysqli_real_escape_string($conn,$_POST['rch_no']);
        $gravida = mysqli_real_escape_string($conn,$_POST['gravida']);
        $para = mysqli_real_escape_string($conn,$_POST['para']);
        $watoto_hai = mysqli_real_escape_string($conn,$_POST['watoto_hai']);
        $admission_date = mysqli_real_escape_string($conn,$_POST['admission_date']);
        $kujifungua_trh = mysqli_real_escape_string($conn,$_POST['kujifungua_trh']);
        $mtotoUzito = mysqli_real_escape_string($conn,$_POST['mtotoUzito']);
        $uchungu = mysqli_real_escape_string($conn,$_POST['uchungu']);
        $jifungulia = mysqli_real_escape_string($conn,$_POST['jifungulia']);
        $kujifungua_njia = mysqli_real_escape_string($conn,$_POST['kujifungua_njia']);
        $mtoto_jinsi = mysqli_real_escape_string($conn,$_POST['mtoto_jinsi']);
        $kupumua = mysqli_real_escape_string($conn,$_POST['kupumua']);
        $apgar = mysqli_real_escape_string($conn,$_POST['apgar']);
        $nyonyeshwa = mysqli_real_escape_string($conn,$_POST['nyonyeshwa']);
        $tathmin = mysqli_real_escape_string($conn,$_POST['tathmin']);
        $MSB = mysqli_real_escape_string($conn,$_POST['MSB']);
        $AP = mysqli_real_escape_string($conn,$_POST['AP']);
        $PPH = mysqli_real_escape_string($conn,$_POST['PPH']);
        $antibiotic = mysqli_real_escape_string($conn,$_POST['antibiotic']);
        $miso = mysqli_real_escape_string($conn,$_POST['miso']);
        $sulfate= mysqli_real_escape_string($conn,$_POST['sulfate']);
        $MVA = mysqli_real_escape_string($conn,$_POST['MVA']);
        $ongeza_damu = mysqli_real_escape_string($conn,$_POST['ongeza_damu']);
        $FGM = mysqli_real_escape_string($conn,$_POST['FGM']);
        $VVU_Kipimo = mysqli_real_escape_string($conn,$_POST['VVU_Kipimo']);
        $VVU_uchungu = mysqli_real_escape_string($conn,$_POST['VVU_uchungu']);
        $ARV_mtoto = mysqli_real_escape_string($conn,$_POST['ARV_mtoto']);
        $mtoto_ulishaji = mysqli_real_escape_string($conn,$_POST['mtoto_ulishaji']);
        $mtoto_hali = mysqli_real_escape_string($conn,$_POST['mtoto_hali']);
        $mama_hali_details = mysqli_real_escape_string($conn,$_POST['mama_hali_details']);
        $mama_discharge = mysqli_real_escape_string($conn,$_POST['mama_discharge']);
        $kifo_mama_sababu = mysqli_real_escape_string($conn,$_POST['kifo_mama_sababu']);
        $mtoto_hali_details = mysqli_real_escape_string($conn,$_POST['mtoto_hali_details']);
        $mtoto_discharge = mysqli_real_escape_string($conn,$_POST['mtoto_discharge']);
        $kifo_mtoto_sababu = mysqli_real_escape_string($conn,$_POST['kifo_mtoto_sababu']);
        $alikopelekwa = mysqli_real_escape_string($conn,$_POST['alikopelekwa']);
        $sababu_rufaa=mysqli_real_escape_string($conn,$_POST['sababu_rufaa']);
        $mzalishaji = mysqli_real_escape_string($conn,$_POST['mzalishaji']);
        $mama_hali = mysqli_real_escape_string($conn,$_POST['mama_hali']);
        $Patient_No=  mysqli_real_escape_string($conn,$_POST['patient_ID']);

        $insert = mysqli_query($conn,"INSERT INTO tbl_wazazi (Patient_No,jalada_no,rch_no,gravida,para,watoto_hai,admission_date,kujifungua_trh,mtotoUzito,uchungu,jifungulia,kujifungua_njia,mtoto_jinsi,kupumua,apgar,nyonyeshwa,tathmin,MSB,AP,PPH,antibiotic,miso,sulfate,MVA,ongeza_damu,FGM,VVU_Kipimo,VVU_uchungu,ARV_mtoto,mtoto_ulishaji,mama_hali,mtoto_hali,mama_hali_details,mama_discharge,kifo_mama_sababu,mtoto_hali_details,mtoto_discharge,kifo_mtoto_sababu,alikopelekwa,sababu_rufaa,mzalishaji)
        VALUES ('$Patient_No','$jalada_no','$rch_no','$gravida','$para','$watoto_hai','$admission_date','$kujifungua_trh','$mtotoUzito','$uchungu','$jifungulia','$kujifungua_njia','$mtoto_jinsi','$kupumua','$apgar','$nyonyeshwa','$tathmin','$MSB','$AP','$PPH','$antibiotic','$miso','$sulfate','$MVA','$ongeza_damu','$FGM','$VVU_Kipimo','$VVU_uchungu','$ARV_mtoto','$mtoto_ulishaji','$mama_hali','$mtoto_hali','$mama_hali_details','$mama_discharge','$kifo_mama_sababu','$mtoto_hali_details','$mtoto_discharge','$kifo_mtoto_sababu','$alikopelekwa','$sababu_rufaa','$mzalishaji')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}