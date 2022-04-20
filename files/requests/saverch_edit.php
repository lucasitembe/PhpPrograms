<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $rchNo= mysqli_real_escape_string($conn,$_POST['rchNo']);
        $postnatal_Date=  mysqli_real_escape_string($conn,$_POST['postnatal_Date']);
        $para=  mysqli_real_escape_string($conn,$_POST['para']);
        $birth_date= mysqli_real_escape_string($conn,$_POST['birth_date']);
        $hali_mama=mysqli_real_escape_string($conn,$_POST['hali_mama']);
        $hali_mtoto=mysqli_real_escape_string($conn,$_POST['hali_mtoto']);
        $saa_1_nyonyesha=mysqli_real_escape_string($conn,$_POST['saa_1_nyonyesha']);
        $vvu_hali=mysqli_real_escape_string($conn,$_POST['vvu_hali']);
        $vvuPostnatal=mysqli_real_escape_string($conn,$_POST['vvuPostnatal']);
        $alipojifungulia=mysqli_real_escape_string($conn,$_POST['alipojifungulia']);
        $mzalishaji_kada=mysqli_real_escape_string($conn,$_POST['mzalishaji_kada']);
        $BP=mysqli_real_escape_string($conn,$_POST['BP']);
        $HB=mysqli_real_escape_string($conn,$_POST['HB']);
        $Matiti=mysqli_real_escape_string($conn,$_POST['Matiti']);
        $uzazi_tumbo=mysqli_real_escape_string($conn,$_POST['uzazi_tumbo']);
        $Lochia=mysqli_real_escape_string($conn,$_POST['Lochia']);
        $Hudhurio=mysqli_real_escape_string($conn,$_POST['Hudhurio']);
        $tarehe_hudhurio=mysqli_real_escape_string($conn,$_POST['tarehe_hudhurio']);
        $msamba=mysqli_real_escape_string($conn,$_POST['msamba']);
        $fistula=mysqli_real_escape_string($conn,$_POST['fistula']);
        $Akili=mysqli_real_escape_string($conn,$_POST['Akili']);
        $dawa_aina=mysqli_real_escape_string($conn,$_POST['dawa_aina']);
        $uzazi_mpango=mysqli_real_escape_string($conn,$_POST['uzazi_mpango']);
        $mtoto_hudhurio=mysqli_real_escape_string($conn,$_POST['mtoto_hudhurio']);
        $hudhurio_date=mysqli_real_escape_string($conn,$_POST['hudhurio_date']);
        $jinsi_mtoto=mysqli_real_escape_string($conn,$_POST['jinsi_mtoto']);
        $vitamin_A=mysqli_real_escape_string($conn,$_POST['vitamin_A']);
        $idadi_dawa=mysqli_real_escape_string($conn,$_POST['idadi_dawa']);
        $ttchanjo=mysqli_real_escape_string($conn,$_POST['ttchanjo']);
        $mtoto_jina=mysqli_real_escape_string($conn,$_POST['mtoto_jina']);
        $kitovu=mysqli_real_escape_string($conn,$_POST['kitovu']);
        $chanjo=mysqli_real_escape_string($conn,$_POST['chanjo']);
        $Ngozi=mysqli_real_escape_string($conn,$_POST['Ngozi']);
        $uzito_mtoto=mysqli_real_escape_string($conn,$_POST['uzito_mtoto']);
        $mdomo=mysqli_real_escape_string($conn,$_POST['mdomo']);
        $HB_mtoto=mysqli_real_escape_string($conn,$_POST['HB_mtoto']);
        $macho=mysqli_real_escape_string($conn,$_POST['macho']);
        $kmc=mysqli_real_escape_string($conn,$_POST['kmc']);
        $Jaundice=mysqli_real_escape_string($conn,$_POST['Jaundice']);
        $uambukizo_mkali=mysqli_real_escape_string($conn,$_POST['uambukizo_mkali']);
        $ARV=mysqli_real_escape_string($conn,$_POST['ARV']);
        $muda=mysqli_real_escape_string($conn,$_POST['muda']);
        $ulishaje_mtoto=mysqli_real_escape_string($conn,$_POST['ulishaje_mtoto']);
        $alikopelekwa=mysqli_real_escape_string($conn,$_POST['alikopelekwa']);
        $alikotokea=mysqli_real_escape_string($conn,$_POST['alikotokea']);
        $rufaa_sababu=mysqli_real_escape_string($conn,$_POST['rufaa_sababu']);
        $motherID=mysqli_real_escape_string($conn,$_POST['motherID']);

        $insert=mysqli_query($conn,"UPDATE tbl_postnal SET RCH_4_Card_No='$rchNo',Reg_Date='$postnatal_Date',Para='$para',Tarehe_ya_kujifungua='$birth_date',Mahali_Alipojifungulia='$alipojifungulia',Kada_ya_mzalishaji='$mzalishaji_kada',Hali_ya_mama='$hali_mama',Hali_ya_mtoto='$hali_mtoto',Unyonyeshaji_withn_1_hr='$saa_1_nyonyesha',Hali_ya_VVU='$vvu_hali',Kipimo_VVU='$vvuPostnatal',Hudhurio='$Hudhurio',Hudhurio_Date='$tarehe_hudhurio',BP='$BP',HB='$HB',Matiti_hali='$Matiti',Uzazi_tumbo='$uzazi_tumbo',Rangi='$Lochia',Msamba_hali='$msamba',Fistula='$fistula',Akili_Timamu='$Akili',Aina_Dawa='$dawa_aina',Idadi_Dawa='$idadi_dawa',Idadi_Vitamin='$vitamin_A',Chanjo_TT='$ttchanjo',Family_planing='$uzazi_mpango',Hudhurio_la_mtoto='$mtoto_hudhurio',tareh_ya_hururio_la_mtoto='$hudhurio_date',Mtoto_gender='$jinsi_mtoto',Joto='$mtoto_jina',Chanjo='$chanjo',Uzito_wa_mtoto='$uzito_mtoto',mtoto_HB='$HB_mtoto',KMC='$kmc',Jaundice='$Jaundice',Uambukizo_Mkali='$uambukizo_mkali',Kitovu='$kitovu',Ngozi='$Ngozi',Mdomo='$mdomo',Macho='$macho',ARV='$ARV',ARV_Muda='$muda',Ulishaji_wa_mtoto='$ulishaje_mtoto',Rufaa_To='$alikopelekwa',Rufaa_from='$alikotokea',Rufaa_reason='$rufaa_sababu' WHERE Mother_ID=''$motherID''");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }
    } 
   
}