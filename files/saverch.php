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
        $umri=mysqli_real_escape_string($conn,$_POST['umri']);

        $birth_dates = date_format(date_create($birth_date),'Y-m-d');
        $hudhurio_dates = date_format(date_create($hudhurio_date),'Y-m-d');
        $insert=mysqli_query($conn,"INSERT INTO tbl_postnal (
          Mother_ID,RCH_4_Card_No,Reg_Date,Para,Tarehe_ya_kujifungua,Mahali_Alipojifungulia,Kada_ya_mzalishaji,Hali_ya_mama,Hali_ya_mtoto,
          Unyonyeshaji_withn_1_hr,Hali_ya_VVU,Kipimo_VVU,Hudhurio,Hudhurio_Date,BP,HB,Matiti_hali,Uzazi_tumbo,Rangi,Msamba_hali,
          Fistula,Akili_Timamu,Aina_Dawa,Idadi_Dawa,Idadi_Vitamin,Chanjo_TT,Family_planing,Hudhurio_la_mtoto,tareh_ya_hururio_la_mtoto,
          Mtoto_gender,Joto,Chanjo,Uzito_wa_mtoto,mtoto_HB,KMC,Jaundice,Uambukizo_Mkali,Kitovu,Ngozi,Mdomo,Macho,ARV,ARV_Muda,
          Ulishaji_wa_mtoto,Rufaa_To,Rufaa_from,Rufaa_reason,umri)
          VALUES('$motherID','$rchNo','$postnatal_Date','$para','$birth_dates','$alipojifungulia','$mzalishaji_kada','$hali_mama','$hali_mtoto',
            '$saa_1_nyonyesha','$vvu_hali','$vvuPostnatal','$Hudhurio','$tarehe_hudhurio','$BP','$HB','$Matiti','$uzazi_tumbo','$Lochia','$msamba',
            '$fistula','$Akili','$dawa_aina','$idadi_dawa','$vitamin_A','$ttchanjo','$uzazi_mpango','$mtoto_hudhurio','$hudhurio_dates','$jinsi_mtoto'
            ,'$mtoto_jina','$chanjo','$uzito_mtoto','$HB_mtoto','$kmc','$Jaundice','$uambukizo_mkali','$kitovu','$Ngozi','$mdomo','$macho','$ARV',
            '$muda','$ulishaje_mtoto','$alikopelekwa','$alikotokea','$rufaa_sababu','$umri')");
        if($insert){
            echo 'Data saved successfully';
        }  else {
            echo 'Data saving error'.mysqli_error($conn);
        }
    }

}
