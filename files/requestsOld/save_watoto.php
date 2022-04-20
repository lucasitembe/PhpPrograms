<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $utambulisho_No = mysql_real_escape_string($_POST['utambulisho_No']);
        $birth_reg_No = mysql_real_escape_string($_POST['birth_reg_No']);
        $mtoto_Jina = mysql_real_escape_string($_POST['mtoto_Jina']);
        $birth_date = mysql_real_escape_string($_POST['birth_date']);
        $kijiji_jina = mysql_real_escape_string($_POST['kijiji_jina']);
        $jinsi = mysql_real_escape_string($_POST['jinsi']);
        $mother_name = mysql_real_escape_string($_POST['mother_name']);
        $TT2_kinga = mysql_real_escape_string($_POST['TT2_kinga']);
        $VVU_hali = mysql_real_escape_string($_POST['VVU_hali']);
        $heid_No = mysql_real_escape_string($_POST['heid_No']);
        $BCG = mysql_real_escape_string($_POST['BCG']);
        $OPVO = mysql_real_escape_string($_POST['OPVO']);
        $penta_1 = mysql_real_escape_string($_POST['penta_1']);
        $penta_2 = mysql_real_escape_string($_POST['penta_2']);
        $penta_3 = mysql_real_escape_string($_POST['penta_3']);
        $polio_1 = mysql_real_escape_string($_POST['polio_1']);
        $polio_2 = mysql_real_escape_string($_POST['polio_2']);
        $polio_3 = mysql_real_escape_string($_POST['polio_3']);
        $PCV_1 = mysql_real_escape_string($_POST['PCV_1']);
        $PCV_2 = mysql_real_escape_string($_POST['PCV_2']);
        $PCV_3 = mysql_real_escape_string($_POST['PCV_3']);
        $Rota_1= mysql_real_escape_string($_POST['Rota_1']);
        $Rota_2 = mysql_real_escape_string($_POST['Rota_2']);
        $surua_1 = mysql_real_escape_string($_POST['surua_1']);
        $surua_2 = mysql_real_escape_string($_POST['surua_2']);
        $VM_6 = mysql_real_escape_string($_POST['VM_6']);
        $VM_U_mwaka = mysql_real_escape_string($_POST['VM_U_mwaka']);
        $VM_1_5 = mysql_real_escape_string($_POST['VM_1_5']);
        $uz_um_9 = mysql_real_escape_string($_POST['uz_um_9']);
        $uz_ur_9 = mysql_real_escape_string($_POST['uz_ur_9']);
        $ur_um_9 = mysql_real_escape_string($_POST['ur_um_9']);
        $uz_um_18 = mysql_real_escape_string($_POST['uz_um_18']);
        $uz_ur_18 = mysql_real_escape_string($_POST['uz_ur_18']);
        $ur_um_18 = mysql_real_escape_string($_POST['ur_um_18']);
        $uz_um_36 = mysql_real_escape_string($_POST['uz_um_36']);
        $uz_ur_36 = mysql_real_escape_string($_POST['uz_ur_36']);
        $ur_um_36 = mysql_real_escape_string($_POST['ur_um_36']);
        $uz_um_48 = mysql_real_escape_string($_POST['uz_um_48']);
        $uz_ur_48 = mysql_real_escape_string($_POST['uz_ur_48']);
        $ur_um_48 = mysql_real_escape_string($_POST['ur_um_48']);
        $AM_12 = mysql_real_escape_string($_POST['AM_12']);
        $AM_18 = mysql_real_escape_string($_POST['AM_18']);
        $AM_24 = mysql_real_escape_string($_POST['AM_24']);
        $AM_30 = mysql_real_escape_string($_POST['AM_30']);
        $hatipunguzo = mysql_real_escape_string($_POST['hatipunguzo']);
        $maziwa_pekee = mysql_real_escape_string($_POST['maziwa_pekee']);
        $maziwa_mbadala = mysql_real_escape_string($_POST['maziwa_mbadala']);
        $alikotoka = mysql_real_escape_string($_POST['alikotoka']);
        $alikopelekwa = mysql_real_escape_string($_POST['alikopelekwa']);
        $rufaasababu = mysql_real_escape_string($_POST['rufaasababu']);
        $maoni = mysql_real_escape_string($_POST['maoni']);

        $insert = mysql_query("INSERT INTO tbl_watoto (Tarehe,Identity_No,Birth_reg_No,Mtoto_Jina,Birth_date,Address,Jinsi,Mama_Jina,Ana_TT2,VVU_Hali,HEID_No,BCG,OPVO,PENTA_1,PENTA_2,PENTA_3,Polio_1,Polio_2,Polio_3,PCV_1,PCV_2,PCV_3,Rota_1,Rota_2,Surua_1,Surua_2,VM_6,V_U_mwaka,V_mwaka_1_5,Uz_um_9,Uz_ur_9,Ur_um_9,Uz_um_18,Uz_ur_18,Ur_um_18,Uz_um_36,Uz_ur_36,Ur_um_36,Uz_um_48,Uz_ur_48,Ur_um_48,AM_12,AM_18,AM_24,AM_30,Hati_punguzo,Mama_maziwa,maziwa_mbadala,kituo_alikotoka,alikopelekwa,Rufaa_sababu,maoni)
        VALUES (NOW(),'$utambulisho_No','$birth_reg_No','$mtoto_Jina','$birth_date','$kijiji_jina','$jinsi','$mother_name','$TT2_kinga','$VVU_hali','$heid_No','$BCG','$OPVO','$penta_1','$penta_2','$penta_3','$polio_1','$polio_2','$polio_3','$PCV_1','$PCV_2','$PCV_3','$Rota_1','$Rota_2','$surua_1','$surua_2','$VM_6','$VM_U_mwaka','$VM_1_5','$uz_um_9','$uz_ur_9','$ur_um_9','$uz_um_18','$uz_ur_18','$ur_um_18','$uz_um_36','$uz_ur_36','$ur_um_36','$uz_um_48','$uz_ur_48','$ur_um_48','$AM_12','$AM_18','$AM_24','$AM_30','$hatipunguzo','$maziwa_pekee','$maziwa_mbadala','$alikotoka','$alikopelekwa','$rufaasababu','$maoni')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}