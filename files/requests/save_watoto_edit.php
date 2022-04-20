<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $patient_ID=  mysqli_real_escape_string($conn,$_POST['patient_ID']);
        $utambulisho_No = mysqli_real_escape_string($conn,$_POST['utambulisho_No']);
        $birth_reg_No = mysqli_real_escape_string($conn,$_POST['birth_reg_No']);
        $mtoto_Jina = mysqli_real_escape_string($conn,$_POST['mtoto_Jina']);
        $birth_date = mysqli_real_escape_string($conn,$_POST['birth_date']);
        $kijiji_jina = mysqli_real_escape_string($conn,$_POST['kijiji_jina']);
        $jinsi = mysqli_real_escape_string($conn,$_POST['jinsi']);
        $mother_name = mysqli_real_escape_string($conn,$_POST['mother_name']);
        $TT2_kinga = mysqli_real_escape_string($conn,$_POST['TT2_kinga']);
        $VVU_hali = mysqli_real_escape_string($conn,$_POST['VVU_hali']);
        $heid_No = mysqli_real_escape_string($conn,$_POST['heid_No']);
        $BCG = mysqli_real_escape_string($conn,$_POST['BCG']);
        $OPVO = mysqli_real_escape_string($conn,$_POST['OPVO']);
        $penta_1 = mysqli_real_escape_string($conn,$_POST['penta_1']);
        $penta_2 = mysqli_real_escape_string($conn,$_POST['penta_2']);
        $penta_3 = mysqli_real_escape_string($conn,$_POST['penta_3']);
        $polio_1 = mysqli_real_escape_string($conn,$_POST['polio_1']);
        $polio_2 = mysqli_real_escape_string($conn,$_POST['polio_2']);
        $polio_3 = mysqli_real_escape_string($conn,$_POST['polio_3']);
        $PCV_1 = mysqli_real_escape_string($conn,$_POST['PCV_1']);
        $PCV_2 = mysqli_real_escape_string($conn,$_POST['PCV_2']);
        $PCV_3 = mysqli_real_escape_string($conn,$_POST['PCV_3']);
        $Rota_1= mysqli_real_escape_string($conn,$_POST['Rota_1']);
        $Rota_2 = mysqli_real_escape_string($conn,$_POST['Rota_2']);
        $surua_1 = mysqli_real_escape_string($conn,$_POST['surua_1']);
        $surua_2 = mysqli_real_escape_string($conn,$_POST['surua_2']);
        $VM_6 = mysqli_real_escape_string($conn,$_POST['VM_6']);
        $VM_U_mwaka = mysqli_real_escape_string($conn,$_POST['VM_U_mwaka']);
        $VM_1_5 = mysqli_real_escape_string($conn,$_POST['VM_1_5']);
        $uz_um_9 = mysqli_real_escape_string($conn,$_POST['uz_um_9']);
        $uz_ur_9 = mysqli_real_escape_string($conn,$_POST['uz_ur_9']);
        $ur_um_9 = mysqli_real_escape_string($conn,$_POST['ur_um_9']);
        $uz_um_18 = mysqli_real_escape_string($conn,$_POST['uz_um_18']);
        $uz_ur_18 = mysqli_real_escape_string($conn,$_POST['uz_ur_18']);
        $ur_um_18 = mysqli_real_escape_string($conn,$_POST['ur_um_18']);
        $uz_um_36 = mysqli_real_escape_string($conn,$_POST['uz_um_36']);
        $uz_ur_36 = mysqli_real_escape_string($conn,$_POST['uz_ur_36']);
        $ur_um_36 = mysqli_real_escape_string($conn,$_POST['ur_um_36']);
        $uz_um_48 = mysqli_real_escape_string($conn,$_POST['uz_um_48']);
        $uz_ur_48 = mysqli_real_escape_string($conn,$_POST['uz_ur_48']);
        $ur_um_48 = mysqli_real_escape_string($conn,$_POST['ur_um_48']);
        $AM_12 = mysqli_real_escape_string($conn,$_POST['AM_12']);
        $AM_18 = mysqli_real_escape_string($conn,$_POST['AM_18']);
        $AM_24 = mysqli_real_escape_string($conn,$_POST['AM_24']);
        $AM_30 = mysqli_real_escape_string($conn,$_POST['AM_30']);
        $hatipunguzo = mysqli_real_escape_string($conn,$_POST['hatipunguzo']);
        $maziwa_pekee = mysqli_real_escape_string($conn,$_POST['maziwa_pekee']);
        $maziwa_mbadala = mysqli_real_escape_string($conn,$_POST['maziwa_mbadala']);
        $alikotoka = mysqli_real_escape_string($conn,$_POST['alikotoka']);
        $alikopelekwa = mysqli_real_escape_string($conn,$_POST['alikopelekwa']);
        $rufaasababu = mysqli_real_escape_string($conn,$_POST['rufaasababu']);
        $maoni = mysqli_real_escape_string($conn,$_POST['maoni']);

        $insert = mysqli_query($conn,"UPDATE tbl_watoto SET Identity_No='$utambulisho_No',Birth_reg_No='$birth_reg_No',Birth_date='$birth_date',Mtoto_Jina='$mtoto_Jina',Address='$kijiji_jina',Jinsi='$jinsi',Mama_Jina='$mother_name',Ana_TT2='$TT2_kinga',VVU_Hali='$VVU_hali',HEID_No='$heid_No',BCG='$BCG',OPVO='$OPVO',PENTA_1='$penta_1',PENTA_2='$penta_2',PENTA_3='$penta_3',Polio_1='$polio_1',Polio_2='$polio_2',Polio_3='$polio_3',PCV_1='$PCV_1',PCV_2='$PCV_2',PCV_3='$PCV_3',Rota_1='$Rota_1',Rota_2='$Rota_2',Surua_1='$surua_1',Surua_2='$surua_2',VM_6='$VM_6',V_U_mwaka='$VM_U_mwaka',V_mwaka_1_5='$VM_1_5',Uz_um_9='$uz_um_9',Uz_ur_9='$uz_ur_9',Ur_um_9='$ur_um_9',Uz_um_18='$uz_um_18',Uz_ur_18='$uz_ur_18',Ur_um_18='$ur_um_18',Uz_um_36='$uz_um_36',Uz_ur_36='$uz_ur_36',Ur_um_36='$ur_um_36',Uz_um_48='$uz_um_48',Uz_ur_48='$uz_ur_48',Ur_um_48='$ur_um_48',AM_12='$AM_12',AM_18='$AM_18',AM_24='$AM_24',AM_30='$AM_30',Hati_punguzo='$hatipunguzo',Mama_maziwa='$maziwa_pekee',maziwa_mbadala='$maziwa_mbadala',kituo_alikotoka='$alikotoka',alikopelekwa='$alikopelekwa',Rufaa_sababu='$rufaasababu',maoni='$maoni' WHERE watoto_ID='$patient_ID'");
        if ($insert) {
          
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}