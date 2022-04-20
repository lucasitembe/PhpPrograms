<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $rchNo= mysql_real_escape_string($_POST['rchNo']);
        $postnatal_Date=  mysql_real_escape_string($_POST['postnatal_Date']);
        $para=  mysql_real_escape_string($_POST['para']);
        $birth_date= mysql_real_escape_string($_POST['birth_date']);
        $hali_mama=mysql_real_escape_string($_POST['hali_mama']);
        $hali_mtoto=mysql_real_escape_string($_POST['hali_mtoto']);
        $saa_1_nyonyesha=mysql_real_escape_string($_POST['saa_1_nyonyesha']);
        $vvu_hali=mysql_real_escape_string($_POST['vvu_hali']);
        $vvuPostnatal=mysql_real_escape_string($_POST['vvuPostnatal']);
        $alipojifungulia=mysql_real_escape_string($_POST['alipojifungulia']);
        $mzalishaji_kada=mysql_real_escape_string($_POST['mzalishaji_kada']);
        $BP=mysql_real_escape_string($_POST['BP']);
        $HB=mysql_real_escape_string($_POST['HB']);
        $Matiti=mysql_real_escape_string($_POST['Matiti']);
        $uzazi_tumbo=mysql_real_escape_string($_POST['uzazi_tumbo']);
        $Lochia=mysql_real_escape_string($_POST['Lochia']);
        $Hudhurio=mysql_real_escape_string($_POST['Hudhurio']);
        $tarehe_hudhurio=mysql_real_escape_string($_POST['tarehe_hudhurio']);
        $msamba=mysql_real_escape_string($_POST['msamba']);
        $fistula=mysql_real_escape_string($_POST['fistula']);
        $Akili=mysql_real_escape_string($_POST['Akili']);
        $dawa_aina=mysql_real_escape_string($_POST['dawa_aina']);
        $uzazi_mpango=mysql_real_escape_string($_POST['uzazi_mpango']);
        $mtoto_hudhurio=mysql_real_escape_string($_POST['mtoto_hudhurio']);
        $hudhurio_date=mysql_real_escape_string($_POST['hudhurio_date']);
        $jinsi_mtoto=mysql_real_escape_string($_POST['jinsi_mtoto']);
        $vitamin_A=mysql_real_escape_string($_POST['vitamin_A']);
        $idadi_dawa=mysql_real_escape_string($_POST['idadi_dawa']);
        $ttchanjo=mysql_real_escape_string($_POST['ttchanjo']);
        $mtoto_jina=mysql_real_escape_string($_POST['mtoto_jina']);
        $kitovu=mysql_real_escape_string($_POST['kitovu']);
        $chanjo=mysql_real_escape_string($_POST['chanjo']);
        $Ngozi=mysql_real_escape_string($_POST['Ngozi']);
        $uzito_mtoto=mysql_real_escape_string($_POST['uzito_mtoto']);
        $mdomo=mysql_real_escape_string($_POST['mdomo']);
        $HB_mtoto=mysql_real_escape_string($_POST['HB_mtoto']);
        $macho=mysql_real_escape_string($_POST['macho']);
        $kmc=mysql_real_escape_string($_POST['kmc']);
        $Jaundice=mysql_real_escape_string($_POST['Jaundice']);
        $uambukizo_mkali=mysql_real_escape_string($_POST['uambukizo_mkali']);
        $ARV=mysql_real_escape_string($_POST['ARV']);
        $muda=mysql_real_escape_string($_POST['muda']);
        $ulishaje_mtoto=mysql_real_escape_string($_POST['ulishaje_mtoto']);
        $alikopelekwa=mysql_real_escape_string($_POST['alikopelekwa']);
        $alikotokea=mysql_real_escape_string($_POST['alikotokea']);
        $rufaa_sababu=mysql_real_escape_string($_POST['rufaa_sababu']);
        $motherID=mysql_real_escape_string($_POST['motherID']);

        $insert=mysql_query("INSERT INTO tbl_postnal (Mother_ID,RCH_4_Card_No,Reg_Date,Para,Tarehe_ya_kujifungua,Mahali_Alipojifungulia,Kada_ya_mzalishaji,Hali_ya_mama,Hali_ya_mtoto,Unyonyeshaji_withn_1_hr,Hali_ya_VVU,Kipimo_VVU,Hudhurio,Hudhurio_Date,BP,HB,Matiti_hali,Uzazi_tumbo,Rangi,Msamba_hali,Fistula,Akili_Timamu,Aina_Dawa,Idadi_Dawa,Idadi_Vitamin,Chanjo_TT,Family_planing,Hudhurio_la_mtoto,tareh_ya_hururio_la_mtoto,Mtoto_gender,Joto,Chanjo,Uzito_wa_mtoto,mtoto_HB,KMC,Jaundice,Uambukizo_Mkali,Kitovu,Ngozi,Mdomo,Macho,ARV,ARV_Muda,Ulishaji_wa_mtoto,Rufaa_To,Rufaa_from,Rufaa_reason) VALUES ('$motherID','$rchNo','$postnatal_Date','$para','$birth_date','$alipojifungulia','$mzalishaji_kada','$hali_mama','$hali_mtoto','$saa_1_nyonyesha','$vvu_hali','$vvuPostnatal','$Hudhurio','$tarehe_hudhurio','$BP','$HB','$Matiti','$uzazi_tumbo','$Lochia','$msamba','$fistula','$Akili','$dawa_aina','$idadi_dawa','$vitamin_A','$ttchanjo','$uzazi_mpango','$mtoto_hudhurio','$hudhurio_date','$jinsi_mtoto','$mtoto_jina','$chanjo','$uzito_mtoto','$HB_mtoto','$kmc','$Jaundice','$uambukizo_mkali','$kitovu','$Ngozi','$mdomo','$macho','$ARV','$muda','$ulishaje_mtoto','$alikopelekwa','$alikotokea','$rufaa_sababu')");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }
    } 
   
}