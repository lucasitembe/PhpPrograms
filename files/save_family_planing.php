<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $patient_ID=$_POST['patient_ID'];
        $leo_Date= mysqli_real_escape_string($conn,$_POST['leo_Date']);
        $para=mysqli_real_escape_string($conn,$_POST['para']);
        $abortions= mysqli_real_escape_string($conn,$_POST['abortions']);
        $watoto_hai=mysqli_real_escape_string($conn,$_POST['watoto_hai']);
        $vidonge_saiko= mysqli_real_escape_string($conn,$_POST['vidonge_saiko']);
        $idadi_vidonge=  mysqli_real_escape_string($conn,$_POST['idadi_vidonge']);
        $njia_ya_uzazi= mysqli_real_escape_string($conn,$_POST['njia_ya_uzazi']);
//        $select_Condom=mysqli_real_escape_string($conn,$_POST['select_Condom']);
        $idadi_Condom=mysqli_real_escape_string($conn,$_POST['idadi_Condom']);
        $uchunguzi_titi=mysqli_real_escape_string($conn,$_POST['uchunguzi_titi']);
        $Buje=mysqli_real_escape_string($conn,$_POST['Buje']);
        $kidonda=mysqli_real_escape_string($conn,$_POST['kidonda']);
        $chuchu_damu=mysqli_real_escape_string($conn,$_POST['chuchu_damu']);
        $jipu_chuchu=mysqli_real_escape_string($conn,$_POST['jipu_chuchu']);
        $titi_mengine=mysqli_real_escape_string($conn,$_POST['titi_mengine']);
        $uchunguzi_saratani=mysqli_real_escape_string($conn,$_POST['uchunguzi_saratani']);
        $uchafu_ukeni=mysqli_real_escape_string($conn,$_POST['uchafu_ukeni']);
        $kizazi_uvimbe=mysqli_real_escape_string($conn,$_POST['kizazi_uvimbe']);
        $kizazi_mchubuko=mysqli_real_escape_string($conn,$_POST['kizazi_mchubuko']);
        $damu_ukeni=mysqli_real_escape_string($conn,$_POST['damu_ukeni']);
        $via=mysqli_real_escape_string($conn,$_POST['via']);
        $saratani_mengineyo=mysqli_real_escape_string($conn,$_POST['saratani_mengineyo']);
        $Cryotherapy=mysqli_real_escape_string($conn,$_POST['Cryotherapy']);
        $baada_ya_mimba=mysqli_real_escape_string($conn,$_POST['baada_ya_mimba']);
        $matibabu_aliyochagua=mysqli_real_escape_string($conn,$_POST['matibabu_aliyochagua']);
        $Kuondoa=mysqli_real_escape_string($conn,$_POST['Kuondoa']);
        $marudio_Jan=mysqli_real_escape_string($conn,$_POST['marudio_Jan']);
        $marudio_Feb=mysqli_real_escape_string($conn,$_POST['marudio_Feb']);
        $marudio_Machi=mysqli_real_escape_string($conn,$_POST['marudio_Machi']);
        $marudio_April=mysqli_real_escape_string($conn,$_POST['marudio_April']);
        $marudio_Mei=mysqli_real_escape_string($conn,$_POST['marudio_Mei']);
        $marudio_Jun=mysqli_real_escape_string($conn,$_POST['marudio_Jun']);
        $marudio_Jul=mysqli_real_escape_string($conn,$_POST['marudio_Jul']);
        $marudio_Ago=mysqli_real_escape_string($conn,$_POST['marudio_Ago']);
        $marudio_Sept=mysqli_real_escape_string($conn,$_POST['marudio_Sept']);
        $marudio_Oct=mysqli_real_escape_string($conn,$_POST['marudio_Oct']);
        $marudio_Nov=mysqli_real_escape_string($conn,$_POST['marudio_Nov']);
        $marudio_Dec=mysqli_real_escape_string($conn,$_POST['marudio_Des']);
        $VVU_maambukizi=mysqli_real_escape_string($conn,$_POST['VVU_maambukizi']);
        $matokeo_mama=mysqli_real_escape_string($conn,$_POST['matokeo_mama']);
        $matokeo_mwenza=mysqli_real_escape_string($conn,$_POST['matokeo_mwenza']);
        $maoni=mysqli_real_escape_string($conn,$_POST['maoni']);
        $rufaa=mysqli_real_escape_string($conn,$_POST['rufaa']);
        $mteja_aina=  mysqli_real_escape_string($conn,$_POST['mteja_aina']);
        $insert=mysqli_query($conn,"INSERT INTO tbl_family_planing (Patient_ID,Visiting_Date,Patient_type,Utambulisho_No,para,Abortions,Watoto_hai,Aina_vidonge,Kiasi_vidonge,Uzazi_njia,Idadi_Kondom_Ke,Idadi_Kondom_Me,Uchunguzi_matiti,Buje,Kidonda,Kutoka_damu,Jipu,Mengineyo_matiti,Uchunguzi_saratani,Uchafu_ukeni,Uvimbe_kizazi,Mchubuko_kizazi,Damu_ukeni,VIA,Mengineyo_Saratani,Cryotherapy,Baada_Kujifungua,FP_after_matibabu,Kuondoa,Marudio_Jan,Marudio_Feb,Marudio_March,Marudio_April,Marudio_May,Marudio_Jun,Marudio_July,Marudio_Aug,Marudio_Sept,Marudio_Oct,Marudio_Nov,Marudio_Des,Ameambukizwa,Mama_matokeo,Mwenza_matokeo,Maoni,Rufaa)
        VALUES('$patient_ID',NOW(),'$mteja_aina','','$para','$abortions','$watoto_hai','$vidonge_saiko','$idadi_vidonge','$njia_ya_uzazi','$idadi_Condom','$idadi_Condom','$uchunguzi_titi','$Buje','$kidonda','$chuchu_damu','$jipu_chuchu','$titi_mengine','$uchunguzi_saratani','$uchafu_ukeni','$kizazi_uvimbe','$kizazi_mchubuko','$damu_ukeni','$via','$saratani_mengineyo','$Cryotherapy','$baada_ya_mimba','$matibabu_aliyochagua','$Kuondoa','$marudio_Jan','$marudio_Feb','$marudio_Machi','$marudio_April','$marudio_Mei','$marudio_Jun','$marudio_Jul','$marudio_Ago','$marudio_Sept','$marudio_Oct','$marudio_Nov','$marudio_Dec','$VVU_maambukizi','$matokeo_mama','$matokeo_mwenza','$maoni','$rufaa')");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error';
        }
    }

}
