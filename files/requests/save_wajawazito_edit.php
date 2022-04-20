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
        $insert = mysqli_query($conn,"UPDATE tbl_wajawazito SET mtaa_jina='$kijiji_jina',mwenza_jina='$mwenza',mwenyekiti_jina='$mwenyekitijina',anakadi='$anakadi',tt1tarehe='$TT1date',tt2tarehe='$TT2date',mimba_umri='$mimbaumri',mimba_no='$mimbaNo',amezaa_mara='$umezaaNo',watoto_hai='$watotohai',abortions='$abortions',fsb='$FSB',mwisho_age='$mtotowamwishoAge',damu_kiwango='$damuKiwango',Bp='$BP',urefu='$urefu',mkojo_sukari='$mkojosukari',kufunga_CS='$kufungaCS',under_20='$under_20',under_35='$under_35',kaswende_matokeo_ke='$ksmatokeoke',kaswende_matokeo_me='$ksmatokeome',kaswende_ametibiwa_ke='$kstibake',kaswende_ametibiwa_me='$kstibame',ng_matokeo_ke='$ngmatokeoke',ng_matokeo_me='$ngmatokeome',ng_ametibiwa_ke='$ngtibake',ng_ametibiwa_me='$ngtibame',marudio_2='$marudio_2',marudio_3='$marudio_3',marudio_4='$marudio_4',marudio_5='$marudio_5',marudio_6='$marudio_6',marudio_7='$marudio_7',marudio_8='$marudio_8',marudio_9='$marudio_9',ana_VVU_ke='$tayariVVUke',ana_VVU_me='$tayariVVUme',unasihi_ke='$unasihike',unasihi_me='$unasihime',amepima_VVU_ke='$amepimaVVUke',amepima_VVU_me='$amepimaVVUme'
            ,kipimo_tarehe_ke='$kimpimotareheke',kipimo_tarehe_me='$kimpimotareheme',kipimo_1_VVU_matokeo_ke='$matokeoVVU1ke',kipimo_1_VVU_matokeo_me='$matokeoVVU1me',unasihi_kupima_ke='$unasihibaadayakupmake',unasihi_kupima_me='$unasihibaadayakupmame',matokeo_VVU_2='$matokeoVVU2',amepata_ushauri='$ushauriulishaji',mrdt='$mrdt',hatipunguzo='$hatipunguzo',IPT1='$ipt1',IPT2='$ipt2',IPT3='$ipt3',IPT4='$ipt4',vidonge_aina_1='$aina_1',vidonge_aina_2='$aina_2',vidonge_aina_3='$aina_3',vidonge_aina_4='$aina_4',vidonge_idadi_1='$idadi_1',vidonge_idadi_2='$idadi_2',vidonge_idadi_3='$idadi_3',vidonge_idadi_4='$idadi_4',mabendazol='$amebendazole',rufaa_tarehe='$rufaatarehe',alikopelekwa='$alikopelekwa',rufaa_sababu='$rufaasababu',alikotokea='$kituoalikotoka',maoni='$maoni' WHERE Patient_ID='$patient_ID'");
        if ($insert) {
            echo 'Data saved successfully';
        } else {
            echo 'Data saving error';
        }
    }
}