<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $patient_ID=$_POST['patient_ID'];
        $leo_Date= mysql_real_escape_string($_POST['leo_Date']);
        $para=mysql_real_escape_string($_POST['para']);
        $abortions= mysql_real_escape_string($_POST['abortions']);
        $watoto_hai=mysql_real_escape_string($_POST['watoto_hai']);
        $vidonge_saiko= mysql_real_escape_string($_POST['vidonge_saiko']);
        $idadi_vidonge=  mysql_real_escape_string($_POST['idadi_vidonge']);
        $njia_ya_uzazi= mysql_real_escape_string($_POST['njia_ya_uzazi']);
//        $select_Condom=mysql_real_escape_string($_POST['select_Condom']);
        $idadi_Condom=mysql_real_escape_string($_POST['idadi_Condom']);
        $uchunguzi_titi=mysql_real_escape_string($_POST['uchunguzi_titi']);
        $Buje=mysql_real_escape_string($_POST['Buje']);
        $kidonda=mysql_real_escape_string($_POST['kidonda']);
        $chuchu_damu=mysql_real_escape_string($_POST['chuchu_damu']);
        $jipu_chuchu=mysql_real_escape_string($_POST['jipu_chuchu']);
        $titi_mengine=mysql_real_escape_string($_POST['titi_mengine']);
        $uchunguzi_saratani=mysql_real_escape_string($_POST['uchunguzi_saratani']);
        $uchafu_ukeni=mysql_real_escape_string($_POST['uchafu_ukeni']);
        $kizazi_uvimbe=mysql_real_escape_string($_POST['kizazi_uvimbe']);
        $kizazi_mchubuko=mysql_real_escape_string($_POST['kizazi_mchubuko']);
        $damu_ukeni=mysql_real_escape_string($_POST['damu_ukeni']);
        $via=mysql_real_escape_string($_POST['via']);
        $saratani_mengineyo=mysql_real_escape_string($_POST['saratani_mengineyo']);
        $Cryotherapy=mysql_real_escape_string($_POST['Cryotherapy']);
        $baada_ya_mimba=mysql_real_escape_string($_POST['baada_ya_mimba']);
        $matibabu_aliyochagua=mysql_real_escape_string($_POST['matibabu_aliyochagua']);
        $Kuondoa=mysql_real_escape_string($_POST['Kuondoa']);
        $marudio_Jan=mysql_real_escape_string($_POST['marudio_Jan']);
        $marudio_Feb=mysql_real_escape_string($_POST['marudio_Feb']);
        $marudio_Machi=mysql_real_escape_string($_POST['marudio_Machi']);
        $marudio_April=mysql_real_escape_string($_POST['marudio_April']);
        $marudio_Mei=mysql_real_escape_string($_POST['marudio_Mei']);
        $marudio_Jun=mysql_real_escape_string($_POST['marudio_Jun']);
        $marudio_Jul=mysql_real_escape_string($_POST['marudio_Jul']);
        $marudio_Ago=mysql_real_escape_string($_POST['marudio_Ago']);
        $marudio_Sept=mysql_real_escape_string($_POST['marudio_Sept']);
        $marudio_Oct=mysql_real_escape_string($_POST['marudio_Oct']);
        $marudio_Nov=mysql_real_escape_string($_POST['marudio_Nov']);
        $marudio_Dec=mysql_real_escape_string($_POST['marudio_Des']);
        $VVU_maambukizi=mysql_real_escape_string($_POST['VVU_maambukizi']);
        $matokeo_mama=mysql_real_escape_string($_POST['matokeo_mama']);
        $matokeo_mwenza=mysql_real_escape_string($_POST['matokeo_mwenza']);
        $maoni=mysql_real_escape_string($_POST['maoni']);
        $rufaa=mysql_real_escape_string($_POST['rufaa']);
        $mteja_aina=  mysql_real_escape_string($_POST['mteja_aina']);
        $insert=mysql_query("INSERT INTO tbl_family_planing (Patient_ID,Visiting_Date,Patient_type,Utambulisho_No,para,Abortions,Watoto_hai,Aina_vidonge,Kiasi_vidonge,Uzazi_njia,Idadi_Kondom_Ke,Idadi_Kondom_Me,Uchunguzi_matiti,Buje,Kidonda,Kutoka_damu,Jipu,Mengineyo_matiti,Uchunguzi_saratani,Uchafu_ukeni,Uvimbe_kizazi,Mchubuko_kizazi,Damu_ukeni,VIA,Mengineyo_Saratani,Cryotherapy,Baada_Kujifungua,FP_after_matibabu,Kuondoa,Marudio_Jan,Marudio_Feb,Marudio_March,Marudio_April,Marudio_May,Marudio_Jun,Marudio_July,Marudio_Aug,Marudio_Sept,Marudio_Oct,Marudio_Nov,Marudio_Des,Ameambukizwa,Mama_matokeo,Mwenza_matokeo,Maoni,Rufaa)
        VALUES ('$patient_ID',NOW(),'$mteja_aina','','$para','$abortions','$watoto_hai','$vidonge_saiko','$idadi_vidonge','$njia_ya_uzazi','$idadi_Condom','$idadi_Condom','$uchunguzi_titi','$Buje','$kidonda','$chuchu_damu','$jipu_chuchu','$titi_mengine','$uchunguzi_saratani','$uchafu_ukeni','$kizazi_uvimbe','$kizazi_mchubuko','$damu_ukeni','$via','$saratani_mengineyo','$Cryotherapy','$baada_ya_mimba','$matibabu_aliyochagua','$Kuondoa','$marudio_Jan','$marudio_Feb','$marudio_Machi','$marudio_April','$marudio_Mei','$marudio_Jun','$marudio_Jul','$marudio_Ago','$marudio_Sept','$marudio_Oct','$marudio_Nov','$marudio_Dec','$VVU_maambukizi','$matokeo_mama','$matokeo_mwenza','$maoni','$rufaa')");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }
    } 
   
}