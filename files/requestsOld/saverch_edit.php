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

        $insert=mysql_query("UPDATE tbl_postnal SET RCH_4_Card_No='$rchNo',Reg_Date='$postnatal_Date',Para='$para',Tarehe_ya_kujifungua='$birth_date',Mahali_Alipojifungulia='$alipojifungulia',Kada_ya_mzalishaji='$mzalishaji_kada',Hali_ya_mama='$hali_mama',Hali_ya_mtoto='$hali_mtoto',Unyonyeshaji_withn_1_hr='$saa_1_nyonyesha',Hali_ya_VVU='$vvu_hali',Kipimo_VVU='$vvuPostnatal',Hudhurio='$Hudhurio',Hudhurio_Date='$tarehe_hudhurio',BP='$BP',HB='$HB',Matiti_hali='$Matiti',Uzazi_tumbo='$uzazi_tumbo',Rangi='$Lochia',Msamba_hali='$msamba',Fistula='$fistula',Akili_Timamu='$Akili',Aina_Dawa='$dawa_aina',Idadi_Dawa='$idadi_dawa',Idadi_Vitamin='$vitamin_A',Chanjo_TT='$ttchanjo',Family_planing='$uzazi_mpango',Hudhurio_la_mtoto='$mtoto_hudhurio',tareh_ya_hururio_la_mtoto='$hudhurio_date',Mtoto_gender='$jinsi_mtoto',Joto='$mtoto_jina',Chanjo='$chanjo',Uzito_wa_mtoto='$uzito_mtoto',mtoto_HB='$HB_mtoto',KMC='$kmc',Jaundice='$Jaundice',Uambukizo_Mkali='$uambukizo_mkali',Kitovu='$kitovu',Ngozi='$Ngozi',Mdomo='$mdomo',Macho='$macho',ARV='$ARV',ARV_Muda='$muda',Ulishaji_wa_mtoto='$ulishaje_mtoto',Rufaa_To='$alikopelekwa',Rufaa_from='$alikotokea',Rufaa_reason='$rufaa_sababu' WHERE Mother_ID=''$motherID''");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'; 
        }
    } 
   
}