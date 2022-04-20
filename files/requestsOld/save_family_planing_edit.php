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
        $insert=mysql_query("UPDATE tbl_family_planing SET para='$para',Abortions='$abortions',Watoto_hai='$watoto_hai',Aina_vidonge='$vidonge_saiko',Kiasi_vidonge='$idadi_vidonge',Uzazi_njia='$njia_ya_uzazi',Idadi_Kondom_Ke='$idadi_Condom',Idadi_Kondom_Me='$idadi_Condom',Uchunguzi_matiti='$uchunguzi_titi',Buje='$Buje',Kidonda='$kidonda',Kutoka_damu='$chuchu_damu',Jipu='$jipu_chuchu',Mengineyo_matiti='$titi_mengine',Uchunguzi_saratani='$uchunguzi_saratani',Uchafu_ukeni='$uchafu_ukeni',Uvimbe_kizazi='$kizazi_uvimbe',Mchubuko_kizazi='$kizazi_mchubuko',Damu_ukeni='$damu_ukeni',VIA='$via',Mengineyo_Saratani='$saratani_mengineyo',Cryotherapy='$Cryotherapy',Baada_Kujifungua='$baada_ya_mimba',FP_after_matibabu='$matibabu_aliyochagua',Kuondoa='$Kuondoa',Marudio_Jan='$marudio_Jan',Marudio_Feb='$marudio_Feb',Marudio_March='$marudio_Machi',Marudio_April='$marudio_April',Marudio_May='$marudio_Mei',Marudio_Jun='$marudio_Jun',Marudio_July='$marudio_Jul',Marudio_Aug='$marudio_Ago',Marudio_Sept='$marudio_Sept',Marudio_Oct='$marudio_Oct',Marudio_Nov='$marudio_Nov',Marudio_Des='$marudio_Dec',Ameambukizwa='$VVU_maambukizi',Mama_matokeo='$matokeo_mama',Mwenza_matokeo='$matokeo_mwenza',Maoni='$maoni',Rufaa='$rufaa' WHERE Patient_ID='$patient_ID'");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }

    } 
   
}