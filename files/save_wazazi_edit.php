<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
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
        $kijiji_kitongoji=  mysqli_real_escape_string($conn,$_POST['kijiji_kitongoji']);
        $kada=  mysqli_real_escape_string($conn,$_POST['kada']);

        $update = mysqli_query($conn,"UPDATE  tbl_wazazi
        SET  Patient_No = '$Patient_No',jalada_no = '$jalada_no',rch_no=$rch_no,kijiji_kitongoji = '$kijiji_kitongoji',gravida = '$gravida',
        para = '$para',watoto_hai = '$watoto_hai',admission_date = '$admission_date',kujifungua_trh = '$kujifungua_trh',mtotoUzito = '$mtotoUzito',
        uchungu = '$uchungu',jifungulia = '$jifungulia',  kujifungua_njia = '$kujifungua_njia',mtoto_jinsi = '$mtoto_jinsi',kupumua = '$kupumua',
        apgar = '$apgar',nyonyeshwa = '$nyonyeshwa',tathmin = '$tathmin',MSB = '$MSB',AP = '$AP',PPH = '$PPH',antibiotic = '$antibiotic',
        miso = '$miso',sulfate = '$sulfate',MVA = '$MVA',ongeza_damu = '$ongeza_damu',FGM = '$FGM',VVU_Kipimo = '$VVU_Kipimo',VVU_uchungu = '$VVU_uchungu',
        ARV_mtoto = '$ARV_mtoto',mtoto_ulishaji = '$mtoto_ulishaji',mama_hali = '$mama_hali',mtoto_hali = '$mtoto_hali',mama_hali_details = '$mama_hali_details',
        mama_discharge = '$mama_discharge',kifo_mama_sababu = '$kifo_mama_sababu',mtoto_hali_details = '$mtoto_hali_details',mtoto_discharge = '$mtoto_discharge',
        kifo_mtoto_sababu = '$kifo_mtoto_sababu',alikopelekwa = '$alikopelekwa',sababu_rufaa = '$sababu_rufaa',mzalishaji = '$mzalishaji',kada = '$kada';


        if ($update) {
            echo 'Data updated successfully';
        } else {
            echo 'Data updated error';
        }
    }
}
