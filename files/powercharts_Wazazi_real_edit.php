<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Rch_Works'])) {
        if ($_SESSION['userinfo']['Rch_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='searchvisitorsoutpatientlistrchmahudhurio.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<script>
    $(function () {
        $("#datepickery").datepicker();
    });
</script>

<?php
if (isset($_GET['pn'])) {

    $pn = mysqli_real_escape_string($conn,$_GET['pn']);
    $sn=  mysqli_real_escape_string($conn,$_GET['sn']);

    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender,pr.registration_id from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    //display all items
    while ($row2 = mysqli_fetch_array($select_Patient_Details)) {
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row2['Date_Of_Birth']);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";

        $name = $row2['Patient_Name'];
        $gende = $row2['Gender'];
        $regNo = $row2['registration_id'];
    }

    $query=  mysqli_query($conn,"SELECT * FROM tbl_wazazi WHERE wazazi_id='$sn'");
    while ($result=  mysqli_fetch_assoc($query)){
      $jalada_no=$result['jalada_no'];
      $rch_no=$result['rch_no'];
      $gravida=$result['gravida'];
      $para=$result['para'];
      $watoto_hai=$result['watoto_hai'];
      $admission_date=$result['admission_date'];
      $kujifungua_trh=$result['kujifungua_trh'];
      $uchungu=$result['uchungu'];
      $jifungulia=$result['jifungulia'];
      $kujifungua_njia=$result['kujifungua_njia'];
      $mtoto_jinsi=$result['mtoto_jinsi'];
      $kupumua=$result['kupumua'];
      $apgar=$result['apgar'];
      $nyonyeshwa=$result['nyonyeshwa'];
      $mtotoUzito=$result['mtotoUzito'];
      $tathmin=$result['tathmin'];
      $MSB=$result['MSB'];
      $AP=$result['AP'];
      $PPH=$result['PPH'];
      $antibiotic=$result['antibiotic'];
      $miso=$result['miso'];
      $sulfate=$result['sulfate'];
      $MVA=$result['MVA'];
      $ongeza_damu=$result['ongeza_damu'];
      $FGM=$result['FGM'];
      $VVU_Kipimo=$result['VVU_Kipimo'];
      $VVU_uchungu=$result['VVU_uchungu'];
      $ARV_mtoto=$result['ARV_mtoto'];
      $mtoto_ulishaji=$result['mtoto_ulishaji'];
      $mama_hali=$result['mama_hali'];
      $mama_hali_details=$result['mama_hali_details'];
      $mama_discharge=$result['mama_discharge'];
      $kifo_mama_sababu=$result['kifo_mama_sababu'];
      $mtoto_hali=$result['mtoto_hali'];
      $mtoto_hali_details=$result['mtoto_hali_details'];
      $mtoto_discharge=$result['mtoto_discharge'];
      $kifo_mtoto_sababu=$result['kifo_mtoto_sababu'];
      $alikopelekwa=$result['alikopelekwa'];
      $sababu_rufaa=$result['sababu_rufaa'];
      $mzalishaji=$result['mzalishaji'];
      $kada=$result['kada'];
      $kijiji_kitongoji=$result['kijiji_kitongoji'];
    }

}
?>
<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA WAZAZI</b></legend>
    <div class="tabcontents" style="height:550px;overflow:auto" >


        <!--first step-->

        <div id="tabs-1">
            <center>
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Namba ya Jalada
                        </td>
                        <td width="40%">
                            <input  type="text" id="jalada_no" value="<?php echo $jalada_no;?>">
                        </td>
                        <td  style="text-align:right;" width="20%">Namba ya Kadi ya Uzazi(RCH-4)</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;"  id="rch_no" name="" type="text" value="<?php echo $rch_no;?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Jina la mama
                        </td>
                        <td >
                            <input name="jinakamili" id="mtoto_Jina"  type="text" value="<?= $name;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Jina la Kijiji/Kitongoji/Balozi/mtaa
                        </td>
                        <td>
                            <input name="kijiji_kitongoji" id="kijiji_kitongoji"  type="text" value="<?=$kijiji_kitongoji; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Umri
                        </td>
                        <td>
                            <input name="name" id="kijiji_jina" type="text" value="<?php echo $age;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Mimba ya ngapi(Gravida)
                        </td>
                        <td>
                            <input type="text" id="gravida" value="<?php echo $gravida;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Amezaa mara ngapi(Para)
                        </td>
                        <td>
                            <input name="name" id="para" type="text" value="<?php echo $para;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Watoto Hai(Alive)
                        </td>
                        <td>
                            <input type="text" id="watoto_hai" value="<?php echo $watoto_hai;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Tarehe na muda wa kulazwa
                        </td>
                        <td>
                            <input name="name" id="admission_date" type="text" value="<?php echo $admission_date;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Tarehe na muda wa kujifungua
                        </td>
                        <td>
                            <input type="text" id="kujifungua_trh" value="<?php echo $kujifungua_trh;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Taarifa kuhusu uchungu
                        </td>
                        <td>
                            <?php
                            if($uchungu=='baada12'){
                               echo '
                                    <span id="uchungundani12"> <input type="radio" name="uchungu" id="ndani12">Ndani ya saa 12</span>
                                     <span id="uchungubaada12"> <input type="radio" checked="true" name="uchungu" id="baada12">Baada ya saa 12</span>
                                    ';
                            }elseif($uchungu=='ndani12'){
                                 echo '
                                    <span id="uchungundani12"> <input type="radio" checked="true" name="uchungu" id="ndani12">Ndani ya saa 12</span>
                                     <span id="uchungubaada12"> <input type="radio" checked="true" name="uchungu" id="baada12">Baada ya saa 12</span>
                                    ';

                            }else{
                                echo '
                                    <span id="uchungundani12"> <input type="radio" name="uchungu" id="ndani12">Ndani ya saa 12</span>
                                     <span id="uchungubaada12"> <input type="radio" name="uchungu" id="baada12">Baada ya saa 12</span>
                                    ';

                            }

                            ?>
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Alipojifungulia
                        </td>
                        <td>
                            <?php
                            if($jifungulia=='HF'){
                                echo '
                                    <span id="funguliaHF"> <input type="radio" checked="true" name="alipojifungulia" id="HF">HF</span>
                                    <span id="funguliaBBA"> <input type="radio" name="alipojifungulia" id="BBA">BBA</span>
                                    <span id="funguliaTBA"> <input type="radio" name="alipojifungulia" id="TBA">TBA</span>
                                    <span id="funguliaH"> <input type="radio" name="alipojifungulia" id="H">H</span>
                                    ';

                            }elseif($jifungulia=='BBA'){
                                echo '
                                    <span id="funguliaHF"> <input type="radio" name="alipojifungulia" id="HF">HF</span>
                                     <span id="funguliaBBA"> <input type="radio" checked="true" name="alipojifungulia" id="BBA">BBA</span>
                                     <span id="funguliaTBA"> <input type="radio" name="alipojifungulia" id="TBA">TBA</span>
                                     <span id="funguliaH"> <input type="radio" name="alipojifungulia" id="H">H</span>
                                ';

                            }elseif($jifungulia=='TBA'){
                                echo '
                                    <span id="funguliaHF"> <input type="radio" name="alipojifungulia" id="HF">HF</span>
                                     <span id="funguliaBBA"> <input type="radio" name="alipojifungulia" id="BBA">BBA</span>
                                     <span id="funguliaTBA"> <input type="radio" checked="true" name="alipojifungulia" id="TBA">TBA</span>
                                     <span id="funguliaH"> <input type="radio" name="alipojifungulia" id="H">H</span>
                                    ';

                            }elseif($jifungulia=='H'){
                                echo '
                                    <span id="funguliaHF"> <input type="radio" name="alipojifungulia" id="HF">HF</span>
                                     <span id="funguliaBBA"> <input type="radio" name="alipojifungulia" id="BBA">BBA</span>
                                     <span id="funguliaTBA"> <input type="radio" name="alipojifungulia" id="TBA">TBA</span>
                                     <span id="funguliaH"> <input type="radio" checked="true" name="alipojifungulia" id="H">H</span>
                                    ';

                            } else {
                                echo '
                                    <span id="funguliaHF"> <input type="radio" name="alipojifungulia" id="HF">HF</span>
                                    <span id="funguliaBBA"> <input type="radio" name="alipojifungulia" id="BBA">BBA</span>
                                    <span id="funguliaTBA"> <input type="radio" name="alipojifungulia" id="TBA">TBA</span>
                                    <span id="funguliaH"> <input type="radio" name="alipojifungulia" id="H">H</span>
                                    ';


                            }

                            ?>

                        </td>
                    </tr>

                </table>
                <table align="left" style="width:100%">
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="38%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Matatizo ya mimba,Matokeo ya Uzazi na Hali ya Mama na Mtoto </td> <td width="29%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Huduma za EmONC</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">FGM</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Njia ya kujifungua
                                            </td>
                                            <td>
                                                <?php
                                                if($kujifungua_njia=='KW'){
                                                    echo '<span id="funguaKW"><input type="radio" checked="true" name="kujifunguanjia" id="KW">KW</span>
                                                         <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                         <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                         <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                         <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                                        ';


                                                }elseif($kujifungua_njia=='VM'){
                                                    echo '
                                                        <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                       <span id="funguaVM"><input type="radio" checked="true" name="kujifunguanjia" id="VM">VM</span>
                                                       <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                       <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                      <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                                       ';


                                                }elseif($kujifungua_njia=='CS'){
                                                    echo '
                                                        <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                <span id="funguaCS"><input type="radio" checked="true" name="kujifunguanjia" id="CS">CS</span>
                                                <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                                  ';

                                                }elseif($kujifungua_njia=='BR'){
                                                    echo '
                                                        <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                          <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                          <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                          <span id="funguaBR"><input type="radio" checked="true" name="kujifunguanjia" id="BR">BR</span>
                                                          <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                                         ';

                                                }elseif ($kujifungua_njia=='NY') {
                                                    echo '
                                                <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                <span id="funguaNY"><input type="radio" checked="true" name="kujifunguanjia" id="NY">NY</span>
                                                  ';

                                                }else{
                                                    echo '
                                                        <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                        <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                        <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                        <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                        <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                                        ';


                                                }


                                                ?>
                                             </td>


                                        </tr>

                                        <tr>
                                            <td >
                                                Jinsi ya Mtoto(KE/ME)
                                            </td>
                                            <td>
                                                <?php
                                                if($mtoto_jinsi=='me'){
                                                    echo '
                                                        <span id="jinsime"><input type="radio" checked="true" name="jinsi" id="me">ME</span>
                                                        <span id="jinsike"><input type="radio" name="jinsi" id="ke">KE</span>
                                                        ';
                                                }elseif ($mtoto_jinsi=='ke') {
                                                    echo '
                                                        <span id="jinsime"><input type="radio" name="jinsi" id="me">ME</span>
                                                        <span id="jinsike"><input type="radio" checked="true" name="jinsi" id="ke">KE</span>
                                                        ';

                                                }  else {

                                                    echo '
                                                        <span id="jinsime"><input type="radio" name="jinsi" id="me">ME</span>
                                                        <span id="jinsike"><input type="radio" name="jinsi" id="ke">KE</span>
                                                       ';

                                                }


                                                ?>
                                             </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Mtoto amesaidiwa kupumua
                                            </td>
                                            <td>

                                                <?php
                                                if($kupumua=='suction'){
                                                    echo '
                                                        <span id="spansuction"><input type="radio" checked="true" name="kupumua" id="suction">1:suction</span>
                                                        <span id="spanstimulation"><input type="radio" name="kupumua" id="stimulation">2:stimulation</span>
                                                        <span id="spanmask"><input type="radio" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                        <span id="spanhapana"><input type="radio" name="kupumua" id="hapana">Hapana</span>
                                                            ';

                                                }elseif($kupumua=='stimulation'){
                                                    echo '
                                                       <span id="spansuction"><input type="radio" name="kupumua" id="suction">1:suction</span>
                                                       <span id="spanstimulation"><input type="radio" checked="true" name="kupumua" id="stimulation">2:stimulation</span>
                                                       <span id="spanmask"><input type="radio" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                       <span id="spanhapana"><input type="radio" name="kupumua" id="hapana">Hapana</span>
                                                        ';


                                                }elseif ($kupumua=='Mask') {
                                                    echo '
                                                        <span id="spansuction"><input type="radio" name="kupumua" id="suction">1:suction</span>
                                                         <span id="spanstimulation"><input type="radio" name="kupumua" id="stimulation">2:stimulation</span>
                                                        <span id="spanmask"><input type="radio" checked="true" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                         <span id="spanhapana"><input type="radio" name="kupumua" id="hapana">Hapana</span>
                                                        ';


                                                }elseif($kupumua=='hapana'){
                                                    echo '
                                                        <span id="spansuction"><input type="radio" name="kupumua" id="suction">1:suction</span>
                                                         <span id="spanstimulation"><input type="radio" name="kupumua" id="stimulation">2:stimulation</span>
                                                         <span id="spanmask"><input type="radio" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                          <span id="spanhapana"><input type="radio" checked="true" name="kupumua" id="hapana">Hapana</span>
                                                        ';

                                                }else{

                                                    echo '
                                                        <span id="spansuction"><input type="radio" name="kupumua" id="suction">1:suction</span>
                                                        <span id="spanstimulation"><input type="radio" name="kupumua" id="stimulation">2:stimulation</span>
                                                        <span id="spanmask"><input type="radio" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                         <span id="spanhapana"><input type="radio" name="kupumua" id="hapana">Hapana</span>
                                                       ';

                                                }


                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                APGAR
                                            </td>
                                            <td>
                                                <?php
                                                if($apgar=='1'){
                                                    echo '
                                                        <span id="spandk1"><input type="radio" checked="true" name="APGAR" id="dk1">Dakika-1</span>
                                                         <span id="spandk5"><input type="radio" name="APGAR" id="dk5">Dakika-5</span>
                                                            ';

                                                }elseif($apgar=='5'){
                                                    echo '
                                                        <span id="spandk1"><input type="radio" name="APGAR" id="dk1">Dakika-1</span>
                                                        <span id="spandk5"><input type="radio" checked="true" name="APGAR" id="dk5">Dakika-5</span>
                                                         ';

                                                }else{
                                                    echo '
                                                        <span id="spandk1"><input type="radio" name="APGAR" id="dk1">Dakika-1</span>
                                                        <span id="spandk5"><input type="radio" name="APGAR" id="dk5">Dakika-5</span>
                                                         ';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Amenyonyeshwa ndani ya saa moja ya kuzaliwa
                                            </td>
                                            <td>
                                                <?php
                                                 if($nyonyeshwa=='Hapana'){
                                                     echo '
                                                         <span id="spannyonyandiyo"><input type="radio" name="nyonya" id="nyonyandiyo">Ndiyo</span>
                                                         <span id="spannyonyahapana"><input type="radio" checked="true" name="nyonya" id="nyonyahapana">Hapana</span>
                                                        ';

                                                 }elseif ($nyonyeshwa=='Ndiyo') {
                                                     echo '
                                                         <span id="spannyonyandiyo"><input type="radio" checked="true" name="nyonya" id="nyonyandiyo">Ndiyo</span>
                                                         <span id="spannyonyahapana"><input type="radio" name="nyonya" id="nyonyahapana">Hapana</span>
                                                          ';
                                                }  else {
                                                    echo '

                                                <span id="spannyonyandiyo"><input type="radio" name="nyonya" id="nyonyandiyo">Ndiyo</span>
                                                <span id="spannyonyahapana"><input type="radio" name="nyonya" id="nyonyahapana">Hapana</span>
                                                      ';

                                                }

                                                ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Uzito wa mtoto kwa gm/Kg
                                            </td>
                                            <td>
                                                <input type="text" id="mtotoUzito" value="<?php echo $mtotoUzito;?>">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Tathmini ya mtoto baada ya masaa 24
                                            </td>
                                            <td>
                                                <?php
                                                if($tathmin=='Ndiyo'){
                                                    echo '
                                                        <span id="spantathminndiyo"><input type="radio" checked="true" name="tathmin" id="tahminndiyo">Ndiyo</span>
                                                        <span id="spantathminhapana"><input type="radio" name="tathmin" id="tathminhapana">Hapana</span>
                                                          ';


                                                }elseif ($tathmin=='Hapana') {
                                                    echo '
                                                        <span id="spantathminndiyo"><input type="radio" name="tathmin" id="tahminndiyo">Ndiyo</span>
                                                        <span id="spantathminhapana"><input type="radio" checked="true" name="tathmin" id="tathminhapana">Hapana</span>
                                                        ';

                                                }  else {
                                                    echo '
                                                        <span id="spantathminndiyo"><input type="radio" name="tathmin" id="tahminndiyo">Ndiyo</span>
                                                        <span id="spantathminhapana"><input type="radio" name="tathmin" id="tathminhapana">Hapana</span>
                                                         ';

                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                FSB/MSB
                                            </td>
                                            <td>
                                                <?php
                                                if($MSB=='FSB'){
                                                    echo '
                                                        <span id="spanFSB"><input type="radio" checked="true" name="FSB" id="FSB">FSB</span>
                                                        <span id="spanMSB"><input type="radio" name="FSB" id="MSB">MSB</span>
                                                          ';

                                                }elseif ($MSB=='MSB') {
                                                    echo '
                                                        <span id="spanFSB"><input type="radio" name="FSB" id="FSB">FSB</span>
                                                       <span id="spanMSB"><input type="radio" checked="true" name="FSB" id="MSB">MSB</span>
                                                         ';
                                                }  else {
                                                    echo '
                                                        <span id="spanFSB"><input type="radio" name="FSB" id="FSB">FSB</span>
                                                        <span id="spanMSB"><input type="radio" name="FSB" id="MSB">MSB</span>
                                                        ';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >

                                            </td>
                                            <td>
                                                <?php
                                                if($AP=='AP'){
                                                    echo '
                                                <span id="spanAP"><input type="radio" checked="true" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';

                                                }elseif ($AP=='PROM') {
                                                    echo '
                                                <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" checked="true" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';
                                                }elseif($AP=='A'){
                                                    echo '
                                                <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" checked="true" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';

                                                }elseif($AP=='E'){
                                                    echo '
                                                <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" checked="true" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';


                                                }elseif($AP=='sepsis'){
                                                    echo '
                                                <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">\'E\' eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" checked="true" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';

                                                }elseif ($AP=='Malaria') {
                                                    echo '  <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" checked="true" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';


                                                }elseif($AP=='HIV'){
                                                    echo '
                                                        <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" checked="true" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';
                                                }elseif ($AP=='FGM') {

                                                    echo '  <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" checked="true" name="AP" id="FGM">FGM</span>
                                                        ';

                                                }  else {
                                                    echo '  <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">"E" eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>
                                                    ';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >

                                            </td>
                                            <td>
                                                <?php
                                                if($PPH=='PPH'){
                                                echo ' <span id="spanPPH"><input type="radio" checked="true" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';

                                                }elseif ($PPH=='pree') {
                                                echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" checked="true" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';

                                                }elseif ($PPH=='ecla') {
                                                 echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" checked="true" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';


                                                }elseif ($PPH=='OL') {
                                                 echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" checked="true" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';

                                                }elseif($PPH=='tear'){
                                                  echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" checked="true" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';


                                                }elseif ($PPH=='RP') {
                                                     echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" checked="true" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';
                                                }elseif ($PPH=='MK') {
                                                 echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" checked="true" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';
                                                }elseif($PPH=='KN'){
                                                    echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" checked="true" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';

                                                }  else {
                                                     echo ' <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">\'PE\' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">\'RP\' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">\'MK\' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">\'KN\' Kuishiwa nguvu</span>
                                                ';
                                                }

                                                ?>

                                            </td>

                                        </tr>


                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:60px;">Mama amepewa sindano ya antibiotic</td>
                                            <td width="20%">
                                                <?php
                                                 if($antibiotic=='Ndiyo'){
                                                     echo '
                                                         <span id="spanantindiyo"><input type="radio" checked="true" id="antindiyo" name="antibiotic">Ndiyo</span>
                                                         <span id="spanantihapana"><input type="radio" id="antihapana" name="antibiotic">Hapana</span>
                                                          ';

                                                 }elseif($antibiotic=='Hapana'){
                                                     echo '
                                                         <span id="spanantindiyo"><input type="radio" id="antindiyo" name="antibiotic">Ndiyo</span>
                                                         <span id="spanantihapana"><input type="radio" checked="true" id="antihapana" name="antibiotic">Hapana</span>
                                                        ';

                                                 }  else {
                                                     echo '
                                                         <span id="spanantindiyo"><input type="radio" id="antindiyo" name="antibiotic">Ndiyo</span>
                                                         <span id="spanantihapana"><input type="radio" id="antihapana" name="antibiotic">Hapana</span>
                                                           ';
                                                 }


                                                ?>
                                             </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Oxytocin/ergometrin/misoprostol</td>
                                            <td width="20%">
                                                <?php
                                                if($miso=='Ndiyo'){
                                                    echo '
                                                        <span id="spanmisondiyo"><input type="radio" checked="true" id="misondiyo" name="miso">Ndiyo</span>
                                                        <span id="spanmisohapana"><input type="radio" id="misohapana" name="miso">Hapana</span>
                                                        ';

                                                }elseif($miso=='Hapana'){
                                                    echo '
                                                        <span id="spanmisondiyo"><input type="radio" id="misondiyo" name="miso">Ndiyo</span>
                                                        <span id="spanmisohapana"><input type="radio" checked="true" id="misohapana" name="miso">Hapana</span>
                                                        ';

                                                }else{
                                                    echo '
                                                        <span id="spanmisondiyo"><input type="radio" id="misondiyo" name="miso">Ndiyo</span>
                                                        <span id="spanmisohapana"><input type="radio" id="misohapana" name="miso">Hapana</span>
                                                        ';
                                                }

                                                ?>
                                             </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">inj.Magnesium sulfate</td>
                                            <td width="20%">
                                                <?php
                                                if($sulfate=='Ndiyo'){
                                                    echo '
                                                        <span id="spansulndiyo"><input type="radio" checked="true" id="sulndiyo" name="sul">Ndiyo</span>
                                                         <span id="spansulhapana"><input type="radio" id="sulhapana" name="sul">Hapana</span>
                                                        ';

                                                }elseif($sulfate=='Hapana'){
                                                    echo '
                                                        <span id="spansulndiyo"><input type="radio" id="sulndiyo" name="sul">Ndiyo</span>
                                                        <span id="spansulhapana"><input type="radio" checked="true" id="sulhapana" name="sul">Hapana</span>
                                                         ';
                                                }else{

                                                    echo '
                                                        <span id="spansulndiyo"><input type="radio" id="sulndiyo" name="sul">Ndiyo</span>
                                                        <span id="spansulhapana"><input type="radio" id="sulhapana" name="sul">Hapana</span>
                                                        ';
                                                }

                                                ?>
                                             </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Amefanyiwa MVA/D&C</td>
                                            <td width="20%">
                                                <?php
                                                if($MVA=='Ndiyo'){
                                                    echo '
                                                        <span id="spanMVAndiyo"><input type="radio" checked="true" id="MVAndiyo" name="MVA">Ndiyo</span>
                                                        <span id="spanMVAhapana"><input type="radio" id="MVAhapana" name="MVA">Hapana</span>
                                                         ';
                                                }elseif($MVA=='Hapana'){
                                                    echo '
                                                        <span id="spanMVAndiyo"><input type="radio" id="MVAndiyo" name="MVA">Ndiyo</span>
                                                        <span id="spanMVAhapana"><input type="radio" checked="true" id="MVAhapana" name="MVA">Hapana</span>
                                                        ';

                                                }else{

                                                    echo '
                                                        <span id="spanMVAndiyo"><input type="radio" id="MVAndiyo" name="MVA">Ndiyo</span>
                                                        <span id="spanMVAhapana"><input type="radio" id="MVAhapana" name="MVA">Hapana</span>
                                                        ';
                                                }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Kuongezewa damu</td>
                                            <td width="20%">
                                                <?php
                                                if($ongeza_damu=='Ndiyo'){
                                                    echo '
                                                      <span id="spandamundiyo"><input type="radio" checked="true" id="damundiyo" name="damu">Ndiyo</span>
                                                      <span id="spandamuhapana"><input type="radio" id="damuhapana" name="damu">Hapana</span>
                                                        ';

                                                }elseif($ongeza_damu=='Hapana'){
                                                    echo '
                                                        <span id="spandamundiyo"><input type="radio" id="damundiyo" name="damu">Ndiyo</span>
                                                        <span id="spandamuhapana"><input type="radio" checked="true" id="damuhapana" name="damu">Hapana</span>
                                                            ';

                                                }  else {
                                                    echo '
                                                        <span id="spandamundiyo"><input type="radio" id="damundiyo" name="damu">Ndiyo</span>
                                                        <span id="spandamuhapana"><input type="radio" id="damuhapana" name="damu">Hapana</span>
                                                         ';
                                                }

                                                ?>
                                             </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">Mama amekeketwa</td>
                                            <td width="25%">
                                                <?php
                                                if($FGM=='Ndiyo'){
                                                    echo '
                                                        <span id="spanFGMndiyo"><input type="radio" checked="true" id="FGMndiyo" name="keketwa">Ndiyo</span><br />
                                                         <span id="spanFGMhapana"><input type="radio" id="FGMhapana" name="keketwa">Hapana</span>
                                                        ';

                                                }elseif($FGM=='Hapana'){
                                                    echo '
                                                        <span id="spanFGMndiyo"><input type="radio" id="FGMndiyo" name="keketwa">Ndiyo</span><br />
                                                        <span id="spanFGMhapana"><input type="radio" checked="true" id="FGMhapana" name="keketwa">Hapana</span>
                                                        ';

                                                }else{
                                                    echo '
                                                        <span id="spanFGMndiyo"><input type="radio" id="FGMndiyo" name="keketwa">Ndiyo</span><br />
                                                        <span id="spanFGMhapana"><input type="radio" id="FGMhapana" name="keketwa">Hapana</span>
                                                        ';

                                                }

                                                ?>
                                             </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Kipimo cha VVU
                                            </td>
                                            <td>
                                                <?php
                                                if($VVU_Kipimo=='Positive'){
                                                   echo '
                                                     <span id="spanVVUpos"><input type="radio" checked="true" id="VVUpos" name="vvukipimo">Positive</span><br />
                                                     <span id="spanVVUnegat"><input type="radio" id="VVUnegat" name="vvukipimo">Negative</span><br />
                                                     <span id="spanVVUjuli"><input type="radio" id="VVUjuli" name="vvukipimo">Haijulikani</span>
                                                    ';

                                                }elseif($VVU_Kipimo=='Negative'){
                                                    echo '
                                                     <span id="spanVVUpos"><input type="radio" id="VVUpos" name="vvukipimo">Positive</span><br />
                                                     <span id="spanVVUnegat"><input type="radio" checked="true" id="VVUnegat" name="vvukipimo">Negative</span><br />
                                                     <span id="spanVVUjuli"><input type="radio" id="VVUjuli" name="vvukipimo">Haijulikani</span>
                                                    ';

                                                }elseif ($VVU_Kipimo=='Haijulikani') {
                                                     echo '
                                                     <span id="spanVVUpos"><input type="radio" id="VVUpos" name="vvukipimo">Positive</span><br />
                                                     <span id="spanVVUnegat"><input type="radio" id="VVUnegat" name="vvukipimo">Negative</span><br />
                                                     <span id="spanVVUjuli"><input type="radio" checked="true" id="VVUjuli" name="vvukipimo">Haijulikani</span>
                                                    ';

                                                }else{
                                                    echo '
                                                     <span id="spanVVUpos"><input type="radio" id="VVUpos" name="vvukipimo">Positive</span><br />
                                                     <span id="spanVVUnegat"><input type="radio" id="VVUnegat" name="vvukipimo">Negative</span><br />
                                                     <span id="spanVVUjuli"><input type="radio" id="VVUjuli" name="vvukipimo">Haijulikani</span>
                                                    ';

                                                }

                                                ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Kipimo cha VVU wakati wa uchungu na baada ya kujifungua
                                            </td>
                                            <td>
                                                <?php
                                                if($VVU_uchungu=='Positive'){
                                                     echo '
                                                        <span id="spankipimoVVUpos"><input type="radio" checked="true" id="kipimoVVUpos" name="kujifunguaVVU">Positive</span><br />
                                                        <span id="spankipimoVVUnegat"><input type="radio" id="kipimoVVUnegat" name="kujifunguaVVU">Negative</span><br />
                                                        <span id="spankipimoVVUjuli"><input type="radio" id="kipimoVVUjuli" name="kujifunguaVVU">Haijulikani</span>
                                                    ';

                                                }elseif($VVU_uchungu=='Negative'){
                                                     echo '
                                                        <span id="spankipimoVVUpos"><input type="radio" id="kipimoVVUpos" name="kujifunguaVVU">Positive</span><br />
                                                        <span id="spankipimoVVUnegat"><input type="radio" checked="true" id="kipimoVVUnegat" name="kujifunguaVVU">Negative</span><br />
                                                        <span id="spankipimoVVUjuli"><input type="radio" id="kipimoVVUjuli" name="kujifunguaVVU">Haijulikani</span>
                                                    ';

                                                }elseif ($VVU_uchungu=='Haijulikani') {
                                                     echo '
                                                        <span id="spankipimoVVUpos"><input type="radio" id="kipimoVVUpos" name="kujifunguaVVU">Positive</span><br />
                                                        <span id="spankipimoVVUnegat"><input type="radio" id="kipimoVVUnegat" name="kujifunguaVVU">Negative</span><br />
                                                        <span id="spankipimoVVUjuli"><input type="radio" checked="true" id="kipimoVVUjuli" name="kujifunguaVVU">Haijulikani</span>
                                                    ';
                                                }else{
                                                    echo '
                                                        <span id="spankipimoVVUpos"><input type="radio" id="kipimoVVUpos" name="kujifunguaVVU">Positive</span><br />
                                                        <span id="spankipimoVVUnegat"><input type="radio" id="kipimoVVUnegat" name="kujifunguaVVU">Negative</span><br />
                                                        <span id="spankipimoVVUjuli"><input type="radio" id="kipimoVVUjuli" name="kujifunguaVVU">Haijulikani</span>
                                                    ';

                                                }

                                                ?>
                                             </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Dawa za ARV kwa Mtoto
                                            </td>
                                            <td>
                                                <?php
                                                if($ARV_mtoto=='Amepewa'){
                                                  echo '
                                                        <span id="spanmtotoARVpewa"><input type="radio" checked="true" id="mtotoARVpewa" name="mtotoARV">Amepewa</span><br />
                                                       <span id="spanmtotoARVhapana"><input type="radio" id="mtotoARVhapana" name="mtotoARV">Hajapewa</span>
                                                        ';
                                                }elseif($ARV_mtoto=='Hapana'){
                                                    echo '
                                                        <span id="spanmtotoARVpewa"><input type="radio" id="mtotoARVpewa" name="mtotoARV">Amepewa</span><br />
                                                         <span id="spanmtotoARVhapana"><input type="radio" checked="true" id="mtotoARVhapana" name="mtotoARV">Hajapewa</span>
                                                        ';

                                                }  else {
                                                    echo '
                                                        <span id="spanmtotoARVpewa"><input type="radio" id="mtotoARVpewa" name="mtotoARV">Amepewa</span><br />
                                                       <span id="spanmtotoARVhapana"><input type="radio" id="mtotoARVhapana" name="mtotoARV">Hajapewa</span>
                                                        ';

                                                }

                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ulishaji wa mtoto
                                            </td>
                                            <td>
                                                <?php
                                                if($mtoto_ulishaji=='EBF'){
                                                    echo '
                                                        <span id="spanEBF"><input type="radio" checked="true" id="EBF" name="mtotoulishaji">Maziwa ya mama pekee</span><br />
                                                        <span id="spanRF"><input type="radio" id="RF" name="mtotoulishaji">Maziwa mbadala</span>
                                                        ';

                                                }elseif ($mtoto_ulishaji=='RF') {
                                                    echo '
                                                        <span id="spanEBF"><input type="radio" id="EBF" name="mtotoulishaji">Maziwa ya mama pekee</span><br />
                                                        <span id="spanRF"><input type="radio" id="RF" checked="true" name="mtotoulishaji">Maziwa mbadala</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span id="spanEBF"><input type="radio" id="EBF" name="mtotoulishaji">Maziwa ya mama pekee</span><br />
                                                        <span id="spanRF"><input type="radio" id="RF" name="mtotoulishaji">Maziwa mbadala</span>
                                                        ';
                                                }

                                                ?>
                                             </td>
                                        </tr>


                                    </table>

                                </td>
                    </tr>
                </table>
                </td>
                </tr>
                </table>

                <table align="left" style="width:100%">
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Hali ya Mama na Mtoto wakati wa kuruhusiwa kutoka kituo cha huduma za afya</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Rufaa</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Mzalishaji</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                Hali ya mama
                                            </td>
                                            <td>
                                                <?php
                                                if($mama_hali=='hai'){
                                                 echo ' <span id="spanmamahai"><input type="radio" checked="true" name=\'mamahali\' id=\'mamahai\'>Hai</span>
                                                           <span id="spanmamakufa"><input type="radio" name=\'mamahali\' id=\'mamakufa\'>Amefariki</span>
                                                           ';

                                                }elseif($mama_hali=='kufa'){
                                                    echo ' <span id="spanmamahai"><input type="radio" name=\'mamahali\' id=\'mamahai\'>Hai</span>
                                                           <span id="spanmamakufa"><input type="radio" checked="true" name=\'mamahali\' id=\'mamakufa\'>Amefariki</span>
                                                           ';

                                                }else{
                                                    echo ' <span id="spanmamahai"><input type="radio" name=\'mamahali\' id=\'mamahai\'>Hai</span>
                                                           <span id="spanmamakufa"><input type="radio" name=\'mamahali\' id=\'mamakufa\'>Amefariki</span>
                                                           ';

                                                }

                                                ?>
                                                </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali(Mama)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="mama_hali_details" value="<?php echo $mama_hali_details;?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya Kuruhusiwa/Kufariki(Mama)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="discharge_date_mama" value="<?php echo $mama_discharge;?>">

                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu za kifo
                                            </td>
                                            <td>
                                                <input type="text" style="" id="kifo_sababu_mama" value="<?php echo $kifo_mama_sababu;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali ya mtoto
                                            </td>
                                            <td>
                                                <?php
                                                if($mtoto_hali=='hai'){
                                                    echo '
                                                        <span id="spanmtotohai"><input type="radio" checked="true" name=\'mtotohali\' id=\'mtotohai\'>Hai</span>
                                                        <span id="spanmtotokufa"><input type="radio" name=\'mtotohali\' id=\'mtotokufa\'>Amefariki</span>
                                                        ';

                                                }elseif($mtoto_hali=='kufa'){
                                                   echo '
                                                        <span id="spanmtotohai"><input type="radio" name=\'mtotohali\' id=\'mtotohai\'>Hai</span>
                                                        <span id="spanmtotokufa"><input type="radio" checked="true" name=\'mtotohali\' id=\'mtotokufa\'>Amefariki</span>
                                                        ';

                                                }else{
                                                    echo '
                                                        <span id="spanmtotohai"><input type="radio" name=\'mtotohali\' id=\'mtotohai\'>Hai</span>
                                                        <span id="spanmtotokufa"><input type="radio" name=\'mtotohali\' id=\'mtotokufa\'>Amefariki</span>
                                                        ';
                                                }


                                                ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="mtoto_hali_details" value="<?php echo $mtoto_hali_details;?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya Kuruhusiwa/Kufariki(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="discharge_date_mtoto" value="<?php echo $mtoto_discharge;?>">

                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu za kifo(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="kifo_sababu_mtoto" value="<?php echo $kifo_mtoto_sababu;?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                Alikopokelewa
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="alikopelekwa" value="<?php echo $alikopelekwa;?>">

                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu
                                            </td>
                                            <td>
                                                <textarea id="rufaa_sababu">
                                                    <?php echo $sababu_rufaa;?>
                                                </textarea>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Jina</td>
                                            <td width="25%">
                                                <input type="text" style="width:370px;" id="mzalishaji" value="<?php echo $mzalishaji;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kada
                                            </td>
                                            <td>
                                                <input type="text" style="width:370px;" name="kada" id="kada" value="<?= $kada;?>">

                                            </td>
                                        </tr>


                                    </table>

                                </td>
                    </tr>
                </table>
                </td>
                </tr>
                </table>


                <table align="left" style="width:100%">
                    <tr>
                        <td>
                    <center>
                        <input type="button" value="Save" id="save_data" class="art-button-green" style="width:200px">
                    </center>
                    <input type="hidden" id="patient_ID" value="<?php echo $_GET['pn']; ?>">
                    <input type="hidden" id="wazazi_ID" value="<?php echo $_GET['sn']; ?>">
                    </td>
                    </tr>
                </table>
        </div>

    </div>
</fieldset>

<?php
include("./includes/footer.php");
?>

<link href="css/jquery-ui.css" rel="stylesheet">
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<style>
    #HF:hover,#BBA:hover,#TBA:hover,#H:hover,#funguliaHF:hover,#funguliaBBA:hover,#funguliaTBA:hover,#funguliaH:hover,#uchungubaada12:hover,#uchungundani12:hover,#spandk1:hover,#spandk5:hover{
    cursor:pointer;
    }

    #funguaKW:hover,#funguaVM:hover,#funguaCS:hover,#funguaBR:hover,#funguaNY:hover,#jinsime:hover,#jinsike:hover,#spansuction:hover,#spanstimulation:hover,#spanmask:hover,#spanhapana:hover{
    cursor:pointer;
    }

    #spantathminndiyo:hover,#spantathminhapana:hover,#spanFSB:hover,#spanMSB:hover,#spanAP:hover,#spanPROM:hover,#spanA:hover,#spanPE:hover,#spanE:hover,#spanSepsis:hover,#spanMalaria:hover,#spanHIV:hover,#spanFGM:hover{
    cursor:pointer;
    }

   #spanmtotokufa,#spanmtotohai:hover,#spanmamakufa:hover,#spanmamahai:hover,#spanRF:hover,#spanEBF:hover,#spanmtotoARVhapana:hover,#spanmtotoARVpewa:hover,#spankipimoVVUjuli:hover,#spankipimoVVUnegat:hover,
   #spankipimoVVUpos:hover,#spanVVUjuli:hover,#spanVVUnegat:hover,#spanVVUpos:hover,#spanMVAhapana:hover,#spanMVAndiyo:hover,#spanFGMhapana:hover,#spanFGMndiyo:hover,#spandamuhapana:hover,#spandamundiyo:hover,
   #spansulhapana:hover,#spansulndiyo:hover,#spanmisohapana:hover,#spanmisondiyo:hover,#spanPPH:hover,#spanpree:hover,#spanecla:hover,#spanOL:hover,#spantear:hover,#spanRP:hover,#spanMK:hover,#spanKN:hover,
   #spannyonyandiyo:hover,#spannyonyahapana:hover,#spanantindiyo:hover,#spanantihapana:hover{
    cursor:pointer;
    }
</style>

 <script>
    $(".tabcontents").tabs();
    $('#birth_date,#discharge_date_mama,#discharge_date_mtoto,#admission_date,#kujifungua_trh').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('#spanmtotohai').on('click',function(){
        $('#mtotohai').prop('checked',true);
    });

    $('#spanmtotokufa').on('click',function(){
        $('#mtotokufa').prop('checked',true);
    });


    $('#spanmamahai').on('click',function(){
       $('#mamahai').prop('checked',true);
    });
    $('#spanmamakufa').on('click',function(){
       $('#mamakufa').prop('checked',true);
    });


    $('#spanRF').on('click',function(){
      $('#RF').prop('checked',true);
    });
    $('#spanEBF').on('click',function(){
        $('#EBF').prop('checked',true);
    });
    $('#spanmtotoARVhapana').on('click',function(){
      $('#mtotoARVhapana').prop('checked',true);
    });
    $('#spanmtotoARVpewa').on('click',function(){
        $('#mtotoARVpewa').prop('checked',true);
    });

    $('#spankipimoVVUjuli').on('click',function(){
        $('#kipimoVVUjuli').prop('checked',true);
    });
    $('#spankipimoVVUnegat').on('click',function(){
       $('#kipimoVVUnegat').prop('checked',true);
    });

    $('#spankipimoVVUpos').on('click',function(){
      $('#kipimoVVUpos').prop('checked',true);
    });
    $('#spanVVUjuli').on('click',function(){
      $('#VVUjuli').prop('checked',true);
    });
    $('#spanVVUnegat').on('click',function(){
       $('#VVUnegat').prop('checked',true);
    });
    $('#spanVVUpos').on('click',function(){
        $('#VVUpos').prop('checked',true);
    });
    $('#spanMVAhapana').on('click',function(){
       $('#MVAhapana').prop('checked',true);
    });
    $('#spanMVAndiyo').on('click',function(){
      $('#MVAndiyo').prop('checked',true);
    });

    $('#spanFGMhapana').on('click',function(){
       $('#FGMhapana').prop('checked',true);
    });
    $('#spanFGMndiyo').on('click',function(){
      $('#FGMndiyo').prop('checked',true);
    });

     $('#spandamuhapana').on('click',function(){
       $('#damuhapana').prop('checked',true);
    });
    $('#spandamundiyo').on('click',function(){
       $('#damundiyo').prop('checked',true);
    });

    $('#spansulhapana').on('click',function(){
      $('#sulhapana').prop('checked',true);
    });
    $('#spansulndiyo').on('click',function(){
      $('#sulndiyo').prop('checked',true);
    });
    $('#spanmisohapana').on('click',function(){
        $('#misohapana').prop('checked',true)
    });

    $('#spanmisondiyo').on('click',function(){
      $('#misondiyo').prop('checked',true);
    });

    $('#spanantihapana').on('click',function(){
       $('#antihapana').prop('checked',true);
    });

    $('#spanantindiyo').on('click',function(){
      $('#antindiyo').prop('checked',true);
    });

    $('#spannyonyahapana').on('click',function(){
      $('#nyonyahapana').prop('checked',true);
    });
    $('#spannyonyandiyo').on('click',function(){
       $('#nyonyandiyo').prop('checked',true);
    });

    $('#spanKN').on('click',function(){
       $('#KN').prop('checked',true);
    });
    $('#spanMK').on('click',function(){
       $('#MK').prop('checked',true);
    });
    $('#spanRP').on('click',function(){
       $('#RP').prop('checked',true);
    });
    $('#spantear').on('click',function(){
      $('#tear').prop('checked',true);
    });
    $('#spanOL').on('click',function(){
       $('#OL').prop('checked',true);
    });

    $('#spanecla').on('click',function(){
       $('#ecla').prop('checked',true);
    });
    $('#spanpree').on('click',function(){
       $('#pree').prop('checked',true);
    });
    $('#spanPPH').on('click',function(){
       $('#PPH').prop('checked',true);
    });


    $('#spanAP').on('click',function(){
        $('#AP').prop('checked',true);
    });
    $('#spanPROM').on('click',function(){
       $('#PROM').prop('checked',true);
    });
    $('#spanA').on('click',function(){
       $('#A').prop('checked',true);
    });
    $('#spanPE').on('click',function(){
      $('#PE').prop('checked',true);
    });

    $('#spanE').on('click',function(){
        $('#E').prop('checked',true);
    });

    $('#spanSepsis').on('click',function(){
        $('#sepsis').prop('checked',true);
    });

    $('#spanMalaria').on('click',function(){
      $('#Malaria').prop('checked',true);
    });

    $('#spanHIV').on('click',function(){
       $('#HIV').prop('checked',true);
    });

    $('#spanFGM').on('click',function(){
      $('#FGM').prop('checked',true);
    });


    $('#spantathminndiyo').on('click',function(){
        $('#tahminndiyo').prop('checked',true);
    });

    $('#spantathminhapana').on('click',function(){
        $('#tathminhapana').prop('checked',true);
    });

    $('#spanFSB').on('click',function(){
       $('#FSB').prop('checked',true);
    });

    $('#spanMSB').on('click',function(){
        $('#MSB').prop('checked',true);
    })

    $('#spandk1').on('click',function(){
        $('#dk1').prop('checked',true);
    });

     $('#spandk5').on('click',function(){
        $('#dk5').prop('checked',true);
    });

    $('#spanhapana').on('click', function () {
        $('#hapana').prop('checked', true);
    });

    $('#spanmask').on('click', function () {
        $('#Mask').prop('checked', true);
    });

    $('#spanstimulation').on('click', function () {
        $('#stimulation').prop('checked', true);
    });

    $('#spansuction').on('click', function () {
        $('#suction').prop('checked', true);
    });
    $('#jinsime').on('click', function () {
        $('#me').prop('checked', true);
    });

    $('#jinsike').on('click', function () {
        $('#ke').prop('checked', true);
    });

    $('#funguaKW').on('click', function () {
        $('#KW').prop('checked', true);
    });

    $('#funguaVM').on('click', function () {
        $('#VM').prop('checked', true);
    });
    $('#funguaCS').on('click', function () {
        $('#CS').prop('checked', true);
    });

    $('#funguaBR').on('click', function () {
        $('#BR').prop('checked', true);
    });
    $('#funguaNY').on('click', function () {
        $('#NY').prop('checked', true);
    });


    $('#funguliaHF').on('click', function () {
        $('#HF').prop('checked', true);
    });

    $('#funguliaBBA').on('click', function () {
        $('#BBA').prop('checked', true);
    });

    $('#funguliaTBA').on('click', function () {
        $('#TBA').prop('checked', true);
    });

    $('#funguliaH').on('click', function () {
        $('#H').prop('checked', true);
    });

    $('#uchungundani12').on('click', function () {
        $('#ndani12').prop('checked', true);
    });
    $('#uchungubaada12').on('click', function () {
        $('#baada12').prop('checked', true);
    });


    $('#save_data').click(function () {
        var jalada_no = $('#jalada_no').val();
        var rch_no = $('#rch_no').val();
        var kijiji_kitongoji = $('#kijiji_kitongoji').val();
        var gravida = $('#gravida').val();
        var para = $('#para').val();
        var watoto_hai = $('#watoto_hai').val();
        var admission_date = $('#admission_date').val();
        var kujifungua_trh = $('#kujifungua_trh').val();
        var mtotoUzito=$('#mtotoUzito').val();
        var mama_hali_details=$('#mama_hali_details').val();
        var mama_discharge=$('#discharge_date_mama').val();
        var kifo_mama_sababu=$('#kifo_sababu_mama').val();
        var mtoto_hali_details=$('#mtoto_hali_details').val();
        var mtoto_discharge=$('#discharge_date_mtoto').val();
        var kifo_mtoto_sababu=$('#kifo_sababu_mtoto').val();
        var alikopelekwa=$('#alikopelekwa').val();
        var sababu_rufaa=$('#rufaa_sababu').val();
        var mzalishaji=$('#mzalishaji').val();
        var kada=$('#kada').val();
        var patient_ID=$('#patient_ID').val();
        var wazazi_ID=$('#wazazi_ID').val();
        var uchungu;
        var jifungulia;
        var kujifungua_njia;
        var mtoto_jinsi;
        var kupumua;
        var apgar;
        var nyonyeshwa;
        var tathmin;
        var MSB;
        var AP;
        var PPH;
        var antibiotic;
        var miso;
        var sulfate;
        var MVA;
        var ongeza_damu;
        var FGM;
        var VVU_Kipimo;
        var VVU_uchungu;
        var ARV_mtoto;
        var mtoto_ulishaji;
        var mama_hali;
        var mtoto_hali;
        if($('#ndani12').is(':checked')){
            uchungu='ndani12';
        }else if($('#baada12').is(':checked')){
            uchungu='baada12';
        }

        if($('#H').is(':checked')){
            jifungulia='H';
        }else if($('#TBA').is(':checked')){
            jifungulia='TBA';
        }else if($('#BBA').is(':checked')){
            jifungulia='BBA';
        }else if($('#HF').is(':checked')){
            jifungulia='HF';
        }

        if($('#NY').is(':checked')){
            kujifungua_njia='NY';
        }else if($('#BR').is(':checked')){
            kujifungua_njia='BR';
        }else if($('#CS').is(':checked')){
            kujifungua_njia='CS';
        }else if($('#VM').is(':checked')){
            kujifungua_njia='VM';
        }else if($('#KW').is(':checked')){
            kujifungua_njia='KW';
        }

        if($('#me').is(':checked')){
            mtoto_jinsi='me';
        }else if($('#ke').is(':checked')){
            mtoto_jinsi='ke';
        }

        if($('#suction').is(':checked')){
            kupumua='suction';
        }else if($('#stimulation').is(':checked')){
            kupumua='stimulation';
        }else if($('#Mask').is(':checked')){
            kupumua='Mask';
        }else if($('#hapana').is(':checked')){
            kupumua='hapana';
        }

        if($('#dk1').is(':checked')){
         apgar='1';
        }else if($('#dk5').is(':checked')){
            apgar='5';
        }

        if($('#nyonyandiyo').is(':checked')){
            nyonyeshwa='Ndiyo';
        }else if($('#nyonyahapana').is(':checked')){
            nyonyeshwa='Hapana';
        }

        if($('#tahminndiyo').is(':checked')){
            tathmin='Ndiyo';
        }else if($('#tathminhapana').is(':checked')){
            tathmin='Hapana';
        }

        if($('#MSB').is(':checked')){
            MSB='MSB';
        }else if($('#FSB').is(':checked')){
            MSB='FSB';
        }

        if($('#AP').is(':checked')){
            AP='AP';
        }else if($('#PROM').is(':checked')){
            AP='PROM';
        }else if($('#A').is(':checked')){
            AP='A';
        }else if($('#PE').is(':checked')){
            AP='PE';
        }else if($('#sepsis').is(':checked')){
            AP='sepsis';
        }else if($('#E').is(':checked')){
            AP='E';
        }else if($('#Malaria').is(':checked')){
            AP='Malaria';
        }else if($('#HIV').is(':checked')){
            AP='HIV';
        }else if($('#FGM').is(':checked')){
            AP='FGM';
        }

        if($('#KN').is(':checked')){
            PPH='KN';
        }else if($('#MK').is(':checked')){
            PPH='MK';
        }else if($('#RP').is(':checked')){
            PPH='RP';
        }else if($('#tear').is(':checked')){
            PPH='tear';
        }else if($('#OL').is(':checked')){
            PPH='OL';
        }else if($('#ecla').is(':checked')){
            PPH='ecla';
        }else if($('#pree').is(':checked')){
            PPH='pree';
        }else if($('#PPH').is(':checked')){
            PPH='PPH';
        }

        if($('#antindiyo').is(':checked')){
           antibiotic='Ndiyo';
        }else if($('#antihapana').is(':checked')){
           antibiotic='Hapana';
        }

        if($('#misohapana').is(':checked')){
            miso='Hapana';
        }else if($('#misondiyo').is(':checked')){
            miso='Ndiyo';
        }

        if($('#sulndiyo').is(':checked')){
            sulfate='Ndiyo';
        }else if($('#sulhapana').is(':checked')){
            sulfate='Hapana';
        }

        if($('#MVAhapana').is(':checked')){
            MVA='Hapana';
        }else if($('#MVAndiyo').is(':checked')){
            MVA='Ndiyo';
        }

        if($('#damuhapana').is(':checked')){
           ongeza_damu='Hapana';
        }else if($('#damundiyo').is(':checked')){
            ongeza_damu='Ndiyo';
        }

        if($('#FGMhapana').is(':checked')){
            FGM='Hapana';
        }else if($('#FGMndiyo').is(':checked')){
            FGM='Hapana';
        }else{
            alert('Jaza kama mama amekeketwa');
            return false;
        }

        if($('#VVUpos').is(':checked')){
           VVU_Kipimo='Positive';
        }else if($('#VVUnegat').is(':checked')){
          VVU_Kipimo='Negative';
        }else if($('#VVUjuli').is(':checked')){
            VVU_Kipimo='Haijulikani';
        }else{
            alert('Jaza kama amefanya kipimo cha VVU');
            return false;
        }

        if($('#kipimoVVUpos').is(':checked')){
            VVU_uchungu='Positive';
        }else if($('#kipimoVVUnegat').is(':checked')){
           VVU_uchungu='Negative';
        }else if($('#kipimoVVUjuli').is(':checked')){
            VVU_uchungu='Haijulikani';
        }else{
            alert('Jaza kama amepima kipimo cha VVU wakati wa uchungu na baada ya kujifungua');
            return false;
        }

        if($('#mtotoARVpewa').is(':checked')){
            ARV_mtoto='Amepewa';
        }else if($('#mtotoARVhapana').is(':checked')){
            ARV_mtoto='Hapana';
        }

        if($('#EBF').is(':checked')){
            mtoto_ulishaji='EBF';
        }else if($('#RF').is(':checked')){
            mtoto_ulishaji='RF';
        }

        if($('#mamahai').is(':checked')){
            mama_hali='hai';
        }else if($('#mamakufa').is(':checked')){
            mama_hali='kufa';
        }

        if($('#mtotohai').is(':checked')){
          mtoto_hali='hai';
        }else if($('#mtotokufa').is(':checked')){
          mtoto_hali='kufa';
        }

        $.ajax({
            type: 'POST',
            url: 'requests/save_wazazi_edit.php',
            data: 'action=update&patient_ID= '+patient_ID+'&jalada_no=' + jalada_no+'&rch_no='+rch_no+'&kijiji_kitongoji=' + kijiji_kitongoji +'&gravida='+gravida+'&para='+para+'&watoto_hai='+watoto_hai+'&admission_date='+admission_date+'&kujifungua_trh='+kujifungua_trh+'&mtotoUzito='+mtotoUzito+'&uchungu='+uchungu
                    +'&jifungulia='+jifungulia+'&kujifungua_njia='+kujifungua_njia+'&mtoto_jinsi='+mtoto_jinsi+'&kupumua='+kupumua+'&apgar='+apgar+'&nyonyeshwa='+nyonyeshwa+'&tathmin='+tathmin+'&MSB='+MSB+'&AP='+AP+'&PPH='+PPH+'&antibiotic='+antibiotic
                    +'&miso='+miso+'&sulfate='+sulfate+'&MVA='+MVA+'&ongeza_damu='+ongeza_damu+'&FGM='+FGM+'&VVU_Kipimo='+VVU_Kipimo+'&VVU_uchungu='+VVU_uchungu+'&ARV_mtoto='+ARV_mtoto+'&mtoto_ulishaji='+mtoto_ulishaji+'&mama_hali='+mama_hali+'&mtoto_hali='+mtoto_hali+'&mama_hali_details='+mama_hali_details
                    +'&mama_discharge='+mama_discharge+'&kifo_mama_sababu='+kifo_mama_sababu+'&mtoto_hali_details='+mtoto_hali_details+'&mtoto_discharge='+mtoto_discharge+'&kifo_mtoto_sababu='+kifo_mtoto_sababu+'&alikopelekwa='+alikopelekwa+'&sababu_rufaa='+sababu_rufaa+'&mzalishaji='+mzalishaji++'&kada=' + kada+'&wazazi_ID='+wazazi_ID,
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
    });
</script>
