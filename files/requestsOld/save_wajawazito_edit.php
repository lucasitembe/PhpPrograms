<?php
session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $patient_ID = $_POST['patient_ID'];
        $leo_Date = mysql_real_escape_string($_POST['leo_Date']);
        $kijiji_jina = mysql_real_escape_string($_POST['kijiji_jina']);
        $mwenza = mysql_real_escape_string($_POST['mwenza']);
        $mwenyekitijina = mysql_real_escape_string($_POST['mwenyekitijina']);
        $anakadi = mysql_real_escape_string($_POST['anakadi']);
        $TT1date = mysql_real_escape_string($_POST['TT1date']);
        $TT2date = mysql_real_escape_string($_POST['TT2date']);
        $mimbaumri = mysql_real_escape_string($_POST['mimbaumri']);
        $mimbaNo = mysql_real_escape_string($_POST['mimbaNo']);
        $umezaaNo = mysql_real_escape_string($_POST['umezaaNo']);
        $watotohai = mysql_real_escape_string($_POST['watotohai']);
        $abortions = mysql_real_escape_string($_POST['abortions']);
        $FSB = mysql_real_escape_string($_POST['FSB']);
        $mtotowamwishoAge = mysql_real_escape_string($_POST['mtotowamwishoAge']);
        $damuKiwango = mysql_real_escape_string($_POST['damuKiwango']);
        $BP = mysql_real_escape_string($_POST['BP']);
        $urefu = mysql_real_escape_string($_POST['urefu']);
        $mkojosukari = mysql_real_escape_string($_POST['mkojosukari']);
        $kufungaCS = mysql_real_escape_string($_POST['kufungaCS']);
        $under_20 = mysql_real_escape_string($_POST['under_20']);
        $under_35 = mysql_real_escape_string($_POST['under_35']);
        $ksmatokeoke = mysql_real_escape_string($_POST['ksmatokeoke']);
        $ksmatokeome = mysql_real_escape_string($_POST['ksmatokeome']);
        $kstibake = mysql_real_escape_string($_POST['kstibake']);
        $kstibame = mysql_real_escape_string($_POST['kstibame']);
        $ngmatokeoke = mysql_real_escape_string($_POST['ngmatokeoke']);
        $ngmatokeome = mysql_real_escape_string($_POST['ngmatokeome']);
        $ngtibake = mysql_real_escape_string($_POST['ngtibake']);
        $ngtibame = mysql_real_escape_string($_POST['ngtibame']);
        $marudio_2 = mysql_real_escape_string($_POST['marudio_2']);
        $marudio_3 = mysql_real_escape_string($_POST['marudio_3']);
        $marudio_4 = mysql_real_escape_string($_POST['marudio_4']);
        $marudio_5 = mysql_real_escape_string($_POST['marudio_5']);
        $marudio_6 = mysql_real_escape_string($_POST['marudio_6']);
        $marudio_7 = mysql_real_escape_string($_POST['marudio_7']);
        $marudio_8 = mysql_real_escape_string($_POST['marudio_8']);
        $marudio_9 = mysql_real_escape_string($_POST['marudio_9']);
        $tayariVVUke = mysql_real_escape_string($_POST['tayariVVUke']);
        $tayariVVUme = mysql_real_escape_string($_POST['tayariVVUme']);
        $unasihike = mysql_real_escape_string($_POST['unasihike']);
        $unasihime = mysql_real_escape_string($_POST['unasihime']);
        $amepimaVVUke = mysql_real_escape_string($_POST['amepimaVVUke']);
        $amepimaVVUme = mysql_real_escape_string($_POST['amepimaVVUme']);
        $kimpimotareheke = mysql_real_escape_string($_POST['kimpimotareheke']);
        $kimpimotareheme = mysql_real_escape_string($_POST['kimpimotareheme']);
        $matokeoVVU1ke = mysql_real_escape_string($_POST['matokeoVVU1ke']);
        $matokeoVVU1me = mysql_real_escape_string($_POST['matokeoVVU1me']);
        $unasihibaadayakupmake = mysql_real_escape_string($_POST['unasihibaadayakupmake']);
        $unasihibaadayakupmame = mysql_real_escape_string($_POST['unasihibaadayakupmame']);
        $matokeoVVU2 = mysql_real_escape_string($_POST['matokeoVVU2']);
        $ushauriulishaji = mysql_real_escape_string($_POST['ushauriulishaji']);
        $mrdt = mysql_real_escape_string($_POST['mrdt']);
        $hatipunguzo = mysql_real_escape_string($_POST['hatipunguzo']);
        $ipt1 = mysql_real_escape_string($_POST['ipt1']);
        $ipt2 = mysql_real_escape_string($_POST['ipt2']);
        $ipt3 = mysql_real_escape_string($_POST['ipt3']);
        $ipt4 = mysql_real_escape_string($_POST['ipt4']);
        $aina_1 = mysql_real_escape_string($_POST['aina_1']);
        $aina_2 = mysql_real_escape_string($_POST['aina_2']);
        $aina_3 = mysql_real_escape_string($_POST['aina_3']);
        $aina_4 = mysql_real_escape_string($_POST['aina_4']);
        $idadi_1 = mysql_real_escape_string($_POST['idadi_1']);
        $idadi_2 = mysql_real_escape_string($_POST['idadi_2']);
        $idadi_3 = mysql_real_escape_string($_POST['idadi_3']);
        $idadi_4 = mysql_real_escape_string($_POST['idadi_4']);
        $idadi_4 = mysql_real_escape_string($_POST['idadi_4']);
        $amebendazole = mysql_real_escape_string($_POST['amebendazole']);
        $rufaatarehe = mysql_real_escape_string($_POST['rufaatarehe']);
        $alikopelekwa = mysql_real_escape_string($_POST['alikopelekwa']);
        $rufaasababu = mysql_real_escape_string($_POST['rufaasababu']);
        $kituoalikotoka = mysql_real_escape_string($_POST['kituoalikotoka']);
        $maoni = mysql_real_escape_string($_POST['maoni']);
        $insert = mysql_query("UPDATE tbl_wajawazito SET mtaa_jina='$kijiji_jina',mwenza_jina='$mwenza',mwenyekiti_jina='$mwenyekitijina',anakadi='$anakadi',tt1tarehe='$TT1date',tt2tarehe='$TT2date',mimba_umri='$mimbaumri',mimba_no='$mimbaNo',amezaa_mara='$umezaaNo',watoto_hai='$watotohai',abortions='$abortions',fsb='$FSB',mwisho_age='$mtotowamwishoAge',damu_kiwango='$damuKiwango',Bp='$BP',urefu='$urefu',mkojo_sukari='$mkojosukari',kufunga_CS='$kufungaCS',under_20='$under_20',under_35='$under_35',kaswende_matokeo_ke='$ksmatokeoke',kaswende_matokeo_me='$ksmatokeome',kaswende_ametibiwa_ke='$kstibake',kaswende_ametibiwa_me='$kstibame',ng_matokeo_ke='$ngmatokeoke',ng_matokeo_me='$ngmatokeome',ng_ametibiwa_ke='$ngtibake',ng_ametibiwa_me='$ngtibame',marudio_2='$marudio_2',marudio_3='$marudio_3',marudio_4='$marudio_4',marudio_5='$marudio_5',marudio_6='$marudio_6',marudio_7='$marudio_7',marudio_8='$marudio_8',marudio_9='$marudio_9',ana_VVU_ke='$tayariVVUke',ana_VVU_me='$tayariVVUme',unasihi_ke='$unasihike',unasihi_me='$unasihime',amepima_VVU_ke='$amepimaVVUke',amepima_VVU_me='$amepimaVVUme'
            ,kipimo_tarehe_ke='$kimpimotareheke',kipimo_tarehe_me='$kimpimotareheme',kipimo_1_VVU_matokeo_ke='$matokeoVVU1ke',kipimo_1_VVU_matokeo_me='$matokeoVVU1me',unasihi_kupima_ke='$unasihibaadayakupmake',unasihi_kupima_me='$unasihibaadayakupmame',matokeo_VVU_2='$matokeoVVU2',amepata_ushauri='$ushauriulishaji',mrdt='$mrdt',hatipunguzo='$hatipunguzo',IPT1='$ipt1',IPT2='$ipt2',IPT3='$ipt3',IPT4='$ipt4',vidonge_aina_1='$aina_1',vidonge_aina_2='$aina_2',vidonge_aina_3='$aina_3',vidonge_aina_4='$aina_4',vidonge_idadi_1='$idadi_1',vidonge_idadi_2='$idadi_2',vidonge_idadi_3='$idadi_3',vidonge_idadi_4='$idadi_4',mabendazol='$amebendazole',rufaa_tarehe='$rufaatarehe',alikopelekwa='$alikopelekwa',rufaa_sababu='$rufaasababu',alikotokea='$kituoalikotoka',maoni='$maoni' WHERE Patient_ID='$patient_ID'");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}