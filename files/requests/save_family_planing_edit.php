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
        $insert=mysqli_query($conn,"UPDATE tbl_family_planing SET para='$para',Abortions='$abortions',Watoto_hai='$watoto_hai',Aina_vidonge='$vidonge_saiko',Kiasi_vidonge='$idadi_vidonge',Uzazi_njia='$njia_ya_uzazi',Idadi_Kondom_Ke='$idadi_Condom',Idadi_Kondom_Me='$idadi_Condom',Uchunguzi_matiti='$uchunguzi_titi',Buje='$Buje',Kidonda='$kidonda',Kutoka_damu='$chuchu_damu',Jipu='$jipu_chuchu',Mengineyo_matiti='$titi_mengine',Uchunguzi_saratani='$uchunguzi_saratani',Uchafu_ukeni='$uchafu_ukeni',Uvimbe_kizazi='$kizazi_uvimbe',Mchubuko_kizazi='$kizazi_mchubuko',Damu_ukeni='$damu_ukeni',VIA='$via',Mengineyo_Saratani='$saratani_mengineyo',Cryotherapy='$Cryotherapy',Baada_Kujifungua='$baada_ya_mimba',FP_after_matibabu='$matibabu_aliyochagua',Kuondoa='$Kuondoa',Marudio_Jan='$marudio_Jan',Marudio_Feb='$marudio_Feb',Marudio_March='$marudio_Machi',Marudio_April='$marudio_April',Marudio_May='$marudio_Mei',Marudio_Jun='$marudio_Jun',Marudio_July='$marudio_Jul',Marudio_Aug='$marudio_Ago',Marudio_Sept='$marudio_Sept',Marudio_Oct='$marudio_Oct',Marudio_Nov='$marudio_Nov',Marudio_Des='$marudio_Dec',Ameambukizwa='$VVU_maambukizi',Mama_matokeo='$matokeo_mama',Mwenza_matokeo='$matokeo_mwenza',Maoni='$maoni',Rufaa='$rufaa' WHERE Patient_ID='$patient_ID'");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }

    } 
   
}