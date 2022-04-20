<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $patient_ID = $_POST['patient_ID'];
        $leo_Date = mysqli_real_escape_string($conn,$_POST['leo_Date']);
        $kijiji_jina = mysqli_real_escape_string($conn,$_POST['kijiji_jina']);
        $mwenza = mysqli_real_escape_string($conn,$_POST['mwenza']);
        $mwenyekitijina = mysqli_real_escape_string($conn,$_POST['mwenyekitijina']);
        $anakadi = mysqli_real_escape_string($conn,$_POST['anakadi']);
        $TT1date = mysqli_real_escape_string($conn,$_POST['TT1date']);
        $TT2date = mysqli_real_escape_string($conn,$_POST['TT2date']);
        $mimbaumri = mysqli_real_escape_string($conn,$_POST['mimbaumri']);
        $mimbaNo = mysqli_real_escape_string($conn,$_POST['mimbaNo']);
        $umezaaNo = mysqli_real_escape_string($conn,$_POST['umezaaNo']);
        $watotohai = mysqli_real_escape_string($conn,$_POST['watotohai']);
        $abortions = mysqli_real_escape_string($conn,$_POST['abortions']);
        $FSB = mysqli_real_escape_string($conn,$_POST['FSB']);
        $mtotowamwishoAge = mysqli_real_escape_string($conn,$_POST['mtotowamwishoAge']);
        $damuKiwango = mysqli_real_escape_string($conn,$_POST['damuKiwango']);
        $BP = mysqli_real_escape_string($conn,$_POST['BP']);
        $urefu = mysqli_real_escape_string($conn,$_POST['urefu']);
        $mkojosukari = mysqli_real_escape_string($conn,$_POST['mkojosukari']);
        $kufungaCS = mysqli_real_escape_string($conn,$_POST['kufungaCS']);
        $under_20 = mysqli_real_escape_string($conn,$_POST['under_20']);
        $under_35 = mysqli_real_escape_string($conn,$_POST['under_35']);
        $ksmatokeoke = mysqli_real_escape_string($conn,$_POST['ksmatokeoke']);
        $ksmatokeome = mysqli_real_escape_string($conn,$_POST['ksmatokeome']);
        $kstibake = mysqli_real_escape_string($conn,$_POST['kstibake']);
        $kstibame = mysqli_real_escape_string($conn,$_POST['kstibame']);
        $ngmatokeoke = mysqli_real_escape_string($conn,$_POST['ngmatokeoke']);
        $ngmatokeome = mysqli_real_escape_string($conn,$_POST['ngmatokeome']);
        $ngtibake = mysqli_real_escape_string($conn,$_POST['ngtibake']);
        $ngtibame = mysqli_real_escape_string($conn,$_POST['ngtibame']);
        $marudio_2 = mysqli_real_escape_string($conn,$_POST['marudio_2']);
        $marudio_3 = mysqli_real_escape_string($conn,$_POST['marudio_3']);
        $marudio_4 = mysqli_real_escape_string($conn,$_POST['marudio_4']);
        $marudio_5 = mysqli_real_escape_string($conn,$_POST['marudio_5']);
        $marudio_6 = mysqli_real_escape_string($conn,$_POST['marudio_6']);
        $marudio_7 = mysqli_real_escape_string($conn,$_POST['marudio_7']);
        $marudio_8 = mysqli_real_escape_string($conn,$_POST['marudio_8']);
        $marudio_9 = mysqli_real_escape_string($conn,$_POST['marudio_9']);
        $tayariVVUke = mysqli_real_escape_string($conn,$_POST['tayariVVUke']);
        $tayariVVUme = mysqli_real_escape_string($conn,$_POST['tayariVVUme']);
        $unasihike = mysqli_real_escape_string($conn,$_POST['unasihike']);
        $unasihime = mysqli_real_escape_string($conn,$_POST['unasihime']);
        $amepimaVVUke = mysqli_real_escape_string($conn,$_POST['amepimaVVUke']);
        $amepimaVVUme = mysqli_real_escape_string($conn,$_POST['amepimaVVUme']);
        $kimpimotareheke = mysqli_real_escape_string($conn,$_POST['kimpimotareheke']);
        $kimpimotareheme = mysqli_real_escape_string($conn,$_POST['kimpimotareheme']);
        $matokeoVVU1ke = mysqli_real_escape_string($conn,$_POST['matokeoVVU1ke']);
        $matokeoVVU1me = mysqli_real_escape_string($conn,$_POST['matokeoVVU1me']);
        $unasihibaadayakupmake = mysqli_real_escape_string($conn,$_POST['unasihibaadayakupmake']);
        $unasihibaadayakupmame = mysqli_real_escape_string($conn,$_POST['unasihibaadayakupmame']);
        $matokeoVVU2 = mysqli_real_escape_string($conn,$_POST['matokeoVVU2']);
        $ushauriulishaji = mysqli_real_escape_string($conn,$_POST['ushauriulishaji']);
        $mrdt = mysqli_real_escape_string($conn,$_POST['mrdt']);
        $hatipunguzo = mysqli_real_escape_string($conn,$_POST['hatipunguzo']);
        $ipt1 = mysqli_real_escape_string($conn,$_POST['ipt1']);
        $ipt2 = mysqli_real_escape_string($conn,$_POST['ipt2']);
        $ipt3 = mysqli_real_escape_string($conn,$_POST['ipt3']);
        $ipt4 = mysqli_real_escape_string($conn,$_POST['ipt4']);
        $aina_1 = mysqli_real_escape_string($conn,$_POST['aina_1']);
        $aina_2 = mysqli_real_escape_string($conn,$_POST['aina_2']);
        $aina_3 = mysqli_real_escape_string($conn,$_POST['aina_3']);
        $aina_4 = mysqli_real_escape_string($conn,$_POST['aina_4']);
        $idadi_1 = mysqli_real_escape_string($conn,$_POST['idadi_1']);
        $idadi_2 = mysqli_real_escape_string($conn,$_POST['idadi_2']);
        $idadi_3 = mysqli_real_escape_string($conn,$_POST['idadi_3']);
        $idadi_4 = mysqli_real_escape_string($conn,$_POST['idadi_4']);
        $idadi_4 = mysqli_real_escape_string($conn,$_POST['idadi_4']);
        $amebendazole = mysqli_real_escape_string($conn,$_POST['amebendazole']);
        $rufaatarehe = mysqli_real_escape_string($conn,$_POST['rufaatarehe']);
        $alikopelekwa = mysqli_real_escape_string($conn,$_POST['alikopelekwa']);
        $rufaasababu = mysqli_real_escape_string($conn,$_POST['rufaasababu']);
        $kituoalikotoka = mysqli_real_escape_string($conn,$_POST['kituoalikotoka']);
        $maoni = mysqli_real_escape_string($conn,$_POST['maoni']);
        $insert = mysqli_query($conn,"INSERT INTO tbl_wajawazito (Patient_ID,hudhurio_tarehe,mtaa_jina,mwenza_jina,mwenyekiti_jina,anakadi,tt1tarehe,tt2tarehe,mimba_umri,mimba_no,amezaa_mara,watoto_hai,abortions,fsb,mwisho_age,damu_kiwango,Bp,urefu,mkojo_sukari,kufunga_CS,under_20,under_35,kaswende_matokeo_ke,kaswende_matokeo_me,kaswende_ametibiwa_ke,kaswende_ametibiwa_me,ng_matokeo_ke,ng_matokeo_me,ng_ametibiwa_ke,ng_ametibiwa_me,marudio_2,marudio_3,marudio_4,marudio_5,marudio_6,marudio_7,marudio_8,marudio_9,ana_VVU_ke,ana_VVU_me,unasihi_ke,unasihi_me,amepima_VVU_ke,amepima_VVU_me,kipimo_tarehe_ke,kipimo_tarehe_me,kipimo_1_VVU_matokeo_ke,kipimo_1_VVU_matokeo_me,unasihi_kupima_ke,unasihi_kupima_me,matokeo_VVU_2,amepata_ushauri,mrdt,hatipunguzo,IPT1,IPT2,IPT3,IPT4,vidonge_aina_1,vidonge_aina_2,vidonge_aina_3,vidonge_aina_4,vidonge_idadi_1,vidonge_idadi_2,vidonge_idadi_3,vidonge_idadi_4,mabendazol,rufaa_tarehe,alikopelekwa,rufaa_sababu,alikotokea,maoni)
        VALUES ('$patient_ID','$leo_Date','$kijiji_jina','$mwenza','$mwenyekitijina','$anakadi','$TT1date','$TT2date','$mimbaumri','$mimbaNo','$umezaaNo','$watotohai','$abortions','$FSB','$mtotowamwishoAge','$damuKiwango','$BP','$urefu','$mkojosukari','$kufungaCS','$under_20','$under_35','$ksmatokeoke','$ksmatokeome','$kstibake','$kstibame','$ngmatokeoke','$ngmatokeome','$ngtibake','$ngtibame','$marudio_2','$marudio_3','$marudio_4','$marudio_5','$marudio_6','$marudio_7','$marudio_8','$marudio_9','$tayariVVUke','$tayariVVUme','$unasihike','$unasihime','$amepimaVVUke','$amepimaVVUme','$kimpimotareheke','$kimpimotareheme','$matokeoVVU1ke','$matokeoVVU1me','$unasihibaadayakupmake','$unasihibaadayakupmame','$matokeoVVU2','$ushauriulishaji','$mrdt','$hatipunguzo','$ipt1','$ipt2','$ipt3','$ipt4','$aina_1','$aina_2','$aina_3','$aina_4','$idadi_1','$idadi_2','$idadi_3','$idadi_4','$amebendazole','$rufaatarehe','$alikopelekwa','$rufaasababu','$kituoalikotoka','$maoni')");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}