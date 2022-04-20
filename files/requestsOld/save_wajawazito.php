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
        $insert = mysql_query("INSERT INTO tbl_wajawazito (Patient_ID,hudhurio_tarehe,mtaa_jina,mwenza_jina,mwenyekiti_jina,anakadi,tt1tarehe,tt2tarehe,mimba_umri,mimba_no,amezaa_mara,watoto_hai,abortions,fsb,mwisho_age,damu_kiwango,Bp,urefu,mkojo_sukari,kufunga_CS,under_20,under_35,kaswende_matokeo_ke,kaswende_matokeo_me,kaswende_ametibiwa_ke,kaswende_ametibiwa_me,ng_matokeo_ke,ng_matokeo_me,ng_ametibiwa_ke,ng_ametibiwa_me,marudio_2,marudio_3,marudio_4,marudio_5,marudio_6,marudio_7,marudio_8,marudio_9,ana_VVU_ke,ana_VVU_me,unasihi_ke,unasihi_me,amepima_VVU_ke,amepima_VVU_me,kipimo_tarehe_ke,kipimo_tarehe_me,kipimo_1_VVU_matokeo_ke,kipimo_1_VVU_matokeo_me,unasihi_kupima_ke,unasihi_kupima_me,matokeo_VVU_2,amepata_ushauri,mrdt,hatipunguzo,IPT1,IPT2,IPT3,IPT4,vidonge_aina_1,vidonge_aina_2,vidonge_aina_3,vidonge_aina_4,vidonge_idadi_1,vidonge_idadi_2,vidonge_idadi_3,vidonge_idadi_4,mabendazol,rufaa_tarehe,alikopelekwa,rufaa_sababu,alikotokea,maoni)
        VALUES ('$patient_ID','$leo_Date','$kijiji_jina','$mwenza','$mwenyekitijina','$anakadi','$TT1date','$TT2date','$mimbaumri','$mimbaNo','$umezaaNo','$watotohai','$abortions','$FSB','$mtotowamwishoAge','$damuKiwango','$BP','$urefu','$mkojosukari','$kufungaCS','$under_20','$under_35','$ksmatokeoke','$ksmatokeome','$kstibake','$kstibame','$ngmatokeoke','$ngmatokeome','$ngtibake','$ngtibame','$marudio_2','$marudio_3','$marudio_4','$marudio_5','$marudio_6','$marudio_7','$marudio_8','$marudio_9','$tayariVVUke','$tayariVVUme','$unasihike','$unasihime','$amepimaVVUke','$amepimaVVUme','$kimpimotareheke','$kimpimotareheme','$matokeoVVU1ke','$matokeoVVU1me','$unasihibaadayakupmake','$unasihibaadayakupmame','$matokeoVVU2','$ushauriulishaji','$mrdt','$hatipunguzo','$ipt1','$ipt2','$ipt3','$ipt4','$aina_1','$aina_2','$aina_3','$aina_4','$idadi_1','$idadi_2','$idadi_3','$idadi_4','$amebendazole','$rufaatarehe','$alikopelekwa','$rufaasababu','$kituoalikotoka','$maoni')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}