
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

    $pn = $_GET['pn'];

    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender,pr.registration_id from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));
    //Find the current date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    //display all items
    while ($row2 = mysqli_fetch_array($select_Patient_Details)) {
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
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

    $checkExistency = mysqli_query($conn,"SELECT * FROM tbl_wajawazito WHERE Patient_ID='$pn'");
    while ($result = mysqli_fetch_assoc($checkExistency)) {
        $hudhurio_tarehe = $result['hudhurio_tarehe'];
        $mtaa_jina = $result['mtaa_jina'];
        $mwenza_jina = $result['mwenza_jina'];
        $mwenyekiti_jina = $result['mwenyekiti_jina'];
        $anakadi = $result['anakadi'];
        $tt1tarehe = $result['tt1tarehe'];
        $tt2tarehe = $result['tt2tarehe'];
        $mimba_umri = $result['mimba_umri'];
        $mimba_no = $result['mimba_no'];
        $amezaa_mara = $result['amezaa_mara'];
        $watoto_hai = $result['watoto_hai'];
        $amezaa_mara = $result['amezaa_mara'];
        $watoto_hai = $result['watoto_hai'];
        $abortions = $result['abortions'];
        $fsb = $result['fsb'];
        $mwisho_age=$result['mwisho_age'];
        $damu_kiwango=$result['damu_kiwango'];
        $Bp=$result['Bp'];
        $urefu=$result['urefu'];
        $mkojo_sukari=$result['mkojo_sukari'];
        $kufunga_CS=$result['kufunga_CS'];
        $under_20=$result['under_20'];
        $under_35=$result['under_35'];
        $kaswende_matokeo_ke=$result['kaswende_matokeo_ke'];
        $kaswende_matokeo_me=$result['kaswende_matokeo_me'];
        $kaswende_ametibiwa_ke=$result['kaswende_ametibiwa_ke'];
        $kaswende_ametibiwa_me=$result['kaswende_ametibiwa_me'];
        $ng_matokeo_ke=$result['ng_matokeo_ke'];
        $ng_matokeo_me=$result['ng_matokeo_me'];
        $ng_ametibiwa_ke=$result['ng_ametibiwa_ke'];
        $ng_ametibiwa_me=$result['ng_ametibiwa_me'];
        $marudio_2=$result['marudio_2'];
        $marudio_3=$result['marudio_3'];
        $marudio_4=$result['marudio_4'];
        $marudio_5=$result['marudio_5'];
        $marudio_6=$result['marudio_6'];
        $marudio_7=$result['marudio_7'];
        $marudio_8=$result['marudio_8'];
        $marudio_9=$result['marudio_9'];
        $ana_VVU_ke=$result['ana_VVU_ke'];
        $ana_VVU_me=$result['ana_VVU_me'];
        $unasihi_ke=$result['unasihi_ke'];
        $unasihi_me=$result['unasihi_me'];
        $amepima_VVU_ke=$result['amepima_VVU_ke'];
        $amepima_VVU_me=$result['amepima_VVU_me'];
        $kipimo_tarehe_ke=$result['kipimo_tarehe_ke'];
        $kipimo_tarehe_me=$result['kipimo_tarehe_me'];
        $kipimo_1_VVU_matokeo_ke=$result['kipimo_1_VVU_matokeo_ke'];
        $kipimo_1_VVU_matokeo_me=$result['kipimo_1_VVU_matokeo_me'];
        $unasihi_kupima_ke=$result['unasihi_kupima_ke'];
        $unasihi_kupima_me=$result['unasihi_kupima_me'];
        $matokeo_VVU_2=$result['matokeo_VVU_2'];
        $amepata_ushauri=$result['amepata_ushauri'];
        $mrdt=$result['mrdt'];
        $hatipunguzo=$result['hatipunguzo'];
        $IPT1=$result['IPT1'];
        $IPT2=$result['IPT2'];
        $IPT3=$result['IPT3'];
        $IPT4=$result['IPT4'];
        $vidonge_aina_1=$result['vidonge_aina_1'];
        $vidonge_aina_2=$result['vidonge_aina_2'];
        $vidonge_aina_3=$result['vidonge_aina_3'];
        $vidonge_aina_4=$result['vidonge_aina_4'];
        $vidonge_idadi_1=$result['vidonge_idadi_1'];
        $vidonge_idadi_2=$result['vidonge_idadi_2'];
        $vidonge_idadi_3=$result['vidonge_idadi_3'];
        $vidonge_idadi_4=$result['vidonge_idadi_4'];
        $mabendazol=$result['mabendazol'];
        $rufaa_tarehe=$result['rufaa_tarehe'];
        $alikopelekwa=$result['alikopelekwa'];
        $rufaa_sababu=$result['rufaa_sababu'];
        $alikotokea=$result['alikotokea'];
        $maoni=$result['maoni'];

    }
}

//Patient_ID
$checkExistency = mysqli_query($conn,"SELECT * FROM tbl_family_planing WHERE Patient_ID='$pn'");
$num_rows = mysqli_num_rows($checkExistency);
if ($num_rows > 1) {
    $mteja = 'Marudio';
} else {
    $mteja = 'Mpya';
}
?>
<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA WAJAWAZITO</b></legend>
    <div class="tabcontents" style="height:550px;overflow:auto" >
        <!--first step-->
        <div id="tabs-1">
            <center>
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Jina kamili ya mteja
                        </td>
                        <td width="40%">
                            <input  type="text" readonly="true" id="rchNo" name="tarehe1" value="<?php echo $name; ?>">
                        </td>
                        <td  style="text-align:right;" width="20%">Tarehe</td>
                        <td  width="40%" colspan="2">
                            <input style="width:240px;" readonly="true" id="leo_Date" name="" type="text" value="<?php echo $hudhurio_tarehe; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Namba ya Usajili</td><td ><input name="jinakamili" id="patientNo" value="<?php echo $regNo; ?>" type="text" readonly="true"></td>
                        <td  colspan="" align="right" style="text-align:right;">Umri</td><td>
                            <input name="" id="age" readonly="true" type="text" value="<?php echo $age; ?>" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jina la Kijiji/Kitongoji/Balozi/mtaa/barabara/Na. ya nyumba</td><td><input name="name" id="kijiji_jina" type="text" value="<?= $mtaa_jina;?>"> </td>
                        <td  colspan="" align="right" style="text-align:right;">Mume/Mwenza(Jina)</td><td>
                            <input name="baloz" id="mwenza" type="text" style="width:240px;" value="<?php echo $mwenza_jina?>"></td> ;
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jina la M/kiti serikali ya mtaa/kitongoji</td>
                        <td>
                            <input type="text" id="mwenyekitijina" value="<?php echo $mwenyekiti_jina; ?>">
                        </td>

                    </tr>


                </table>


                <table align="left" style="width:100%">
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="38%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo ya TT </td> <td width="29%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Taarifa ya Mimba Zilizopita</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Vipimo/Taarifa Muhimu</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Ana kadi
                                            </td>
                                            <td>
                                                <?php
                                                    if($anakadi=='N'){
                                                        echo '
                                                            <span class="pointer" id="hapanabtn"><input type="radio" name="kadi" value="H" id="hapanakadi">Hapana</span>
                                                            <span class="pointer" id="ndiyobtn"><input type="radio" checked="true" name="kadi" value="N" id="Ndiyokadi">Ndiyo</span>
                                                            ';
                                                    }elseif($anakadi=='H'){
                                                        echo '
                                                            <span class="pointer" id="hapanabtn"><input type="radio" checked="true" name="kadi" value="H" id="hapanakadi">Hapana</span>
                                                            <span class="pointer" id="ndiyobtn"><input type="radio" name="kadi" value="N" id="Ndiyokadi">Ndiyo</span>
                                                             ';
                                                    } else {
                                                        echo '
                                                            <span class="pointer" id="hapanabtn"><input type="radio" name="kadi" value="H" id="hapanakadi">Hapana</span>
                                                            <span class="pointer" id="ndiyobtn"><input type="radio" name="kadi" value="N" id="Ndiyokadi">Ndiyo</span>
                                                             ';
                                                    }

                                                ?>
                                               </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Andika tarehe ya TT1
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="TT1date" value="<?php echo $tt1tarehe;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Andika tarehe ya TT2+
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="TT2date" value="<?php echo $tt2tarehe;?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td >
                                                Umri wa Mimba Kwa Wiki
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="mimbaumri" value="<?php echo $mimba_umri;?>">

                                            </td>

                                        </tr>

                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:130px;">Mimba ya ngapi</td>
                                            <td width="25%">
                                                <input type="text" id="mimbaNo" style="width:150px" value="<?php echo $mimba_no;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:130px;">
                                                Umezaa mara ngapi
                                            </td>
                                            <td>
                                                <input type="text" id="umezaaNo" style="width:150px" value="<?php echo $amezaa_mara;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Watoto hai
                                            </td>
                                            <td>
                                                <input type="text" id="watotohai" style="width:150px" value="<?php echo $watoto_hai;?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mimba zilizoharibika
                                            </td>
                                            <td>
                                                <input type="text" id="abortions" style="width:150px" value="<?php echo $abortions;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                FSB/MSB/kifo cha mtoto katika wiki moja
                                            </td>
                                            <td>
                                                <?php
                                                if($fsb=='N'){
                                                     echo '
                                                    <span class="pointer" id="spanhapanaFSB"><input type="radio" id="hapanaFSB" name="mimba" >Hapana</span>
                                                    <span class="pointer" id="spanndiyoFSB"><input type="radio" checked="true" id="ndiyoFSB" name="mimba">Ndiyo</span>
                                                    ';
                                                }else if($fsb=='H'){
                                                     echo '
                                                    <span class="pointer" id="spanhapanaFSB"><input type="radio" checked="true" id="hapanaFSB" name="mimba" >Hapana</span>
                                                    <span class="pointer" id="spanndiyoFSB"><input type="radio" id="ndiyoFSB" name="mimba">Ndiyo</span>
                                                    ';
                                                }  else {
                                                   echo '
                                                    <span class="pointer" id="spanhapanaFSB"><input type="radio" id="hapanaFSB" name="mimba" >Hapana</span>
                                                    <span class="pointer" id="spanndiyoFSB"><input type="radio" id="ndiyoFSB" name="mimba">Ndiyo</span>
                                                    ';
                                                }

                                                ?>
                                               </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri wa mtoto wa mwisho
                                            </td>
                                            <td>
                                                <input type="text" id="mtotowamwishoAge" style="width:150px" value="<?php echo $mwisho_age;?>">
                                            </td>
                                        </tr>


                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Kiwango cha damu (HB) mfano 11.0</td>
                                            <td width="25%">
                                                <input type="text" style="width:120px;" id="damuKiwango" value="<?php echo $damu_kiwango;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Shinikizo la damu (BP)
                                            </td>
                                            <td>
                                                <?php
                                                if($Bp=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanBPH"><input type="radio" id="hapanaBP" name="BP">Hapana</span>
                                                        <span class="pointer" id="spanBPN"><input type="radio" checked="true" id="ndiyoBP" name="BP">Ndiyo</span>
                                                        ';
                                                }elseif($Bp=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanBPH"><input type="radio" checked="true" id="hapanaBP" name="BP">Hapana</span>
                                                        <span class="pointer" id="spanBPN"><input type="radio" id="ndiyoBP" name="BP">Ndiyo</span>
                                                         ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanBPH"><input type="radio" id="hapanaBP" name="BP">Hapana</span>
                                                        <span class="pointer" id="spanBPN"><input type="radio" id="ndiyoBP" name="BP">Ndiyo</span>
                                                          ';
                                                }

                                                ?>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Urefu(cm)
                                            </td>
                                            <td>
                                                <input type="text" style="width:120px;" name="mimbanamba" id="urefu" value="<?php echo $urefu;?>">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Sukari kwenye mkojo
                                            </td>
                                            <td>
                                                <?php
                                                if($mkojo_sukari=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanhapanasukari"><input  type="radio" name="mkojosukari" id="hapanasukari">Hapana</span>
                                                        <span class="pointer" id="spanndiyosukari"><input type="radio" checked="true" name="mkojosukari" id="ndiyosukari">Ndiyo</span>
                                                        ';
                                                }else if($mkojo_sukari=='H'){
                                                   echo '
                                                        <span class="pointer" id="spanhapanasukari"><input  type="radio" checked="true" name="mkojosukari" id="hapanasukari">Hapana</span>
                                                        <span class="pointer" id="spanndiyosukari"><input type="radio" name="mkojosukari" id="ndiyosukari">Ndiyo</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanhapanasukari"><input  type="radio" name="mkojosukari" id="hapanasukari">Hapana</span>
                                                        <span class="pointer" id="spanndiyosukari"><input type="radio" name="mkojosukari" id="ndiyosukari">Ndiyo</span>
                                                        ';
                                                }

                                                ?>
                                                </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kufunga kwa CS
                                            </td>
                                            <td>
                                                <?php
                                                if($kufunga_CS=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanCSH"><input  type="radio" name="CS" id="CSH">Hapana</span>
                                                        <span class="pointer" id="spanCSN"><input type="radio" checked="true" name="CS" id="CSN">Ndiyo</span>
                                                        ';

                                                }elseif ($kufunga_CS=='H') {
                                                    echo '
                                                        <span class="pointer" id="spanCSH"><input checked="true"  type="radio" name="CS" id="CSH">Hapana</span>
                                                        <span class="pointer" id="spanCSN"><input type="radio" name="CS" id="CSN">Ndiyo</span>
                                                          ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanCSH"><input  type="radio" name="CS" id="CSH">Hapana</span>
                                                        <span class="pointer" id="spanCSN"><input type="radio" name="CS" id="CSN">Ndiyo</span>
                                                        ';
                                                }

                                                ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri chini ya Miaka 20
                                            </td>
                                            <td>
                                                <?php
                                                if($under_20=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanUnder20H"><input  type="radio" name="Under20" id="Under20H">Hapana</span>
                                                        <span class="pointer" id="spanUnder20N"><input type="radio" checked="true" name="Under20" id="Under20N">Ndiyo</span>
                                                        ';
                                                }elseif($under_20=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanUnder20H"><input  type="radio" checked="true" name="Under20" id="Under20H">Hapana</span>
                                                        <span class="pointer" id="spanUnder20N"><input type="radio" name="Under20" id="Under20N">Ndiyo</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanUnder20H"><input  type="radio" name="Under20" id="Under20H">Hapana</span>
                                                        <span class="pointer" id="spanUnder20N"><input type="radio" name="Under20" id="Under20N">Ndiyo</span>
                                                         ';
                                                }


                                                ?>
                                          </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri zaidi ya miaka 35
                                            </td>
                                            <td>
                                                <?php
                                                if($under_35=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanUnder35H"><input  type="radio" name="Under35" id="Under35H">Hapana</span>
                                                        <span class="pointer" id="spanUnder35N"><input type="radio" checked="true" name="Under35" id="Under35N">Ndiyo</span>
                                                        ';

                                                }elseif ($under_35=='H') {
                                                    echo '
                                                        <span class="pointer" id="spanUnder35H"><input  type="radio" checked="true" name="Under35" id="Under35H">Hapana</span>
                                                        <span class="pointer" id="spanUnder35N"><input type="radio" name="Under35" id="Under35N">Ndiyo</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanUnder35H"><input  type="radio" name="Under35" id="Under35H">Hapana</span>
                                                        <span class="pointer" id="spanUnder35N"><input type="radio" name="Under35" id="Under35N">Ndiyo</span>
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
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Kipimo cha Kaswendwe</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Vipimo vya Magonjwa ya ngono</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Maudhurio ya Marudio</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($kaswende_matokeo_ke=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanksmatokeokeN"><input  type="radio" name="ksmatokeoke" id="ksmatokeokeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeokeP"><input type="radio" checked="true" name="ksmatokeoke" id="ksmatokeokeP">Positive</span>
                                                            ';
                                                }elseif($kaswende_matokeo_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanksmatokeokeN"><input  type="radio" checked="true" name="ksmatokeoke" id="ksmatokeokeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeokeP"><input type="radio" name="ksmatokeoke" id="ksmatokeokeP">Positive</span>
                                                         ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanksmatokeokeN"><input  type="radio" name="ksmatokeoke" id="ksmatokeokeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeokeP"><input type="radio" name="ksmatokeoke" id="ksmatokeokeP">Positive</span>
                                                         ';
                                                }

                                                ?>


                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Me
                                            </td>
                                            <td>
                                                <?php
                                                if($kaswende_matokeo_me=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanksmatokeomeN"><input  type="radio" name="ksmatokeome" id="ksmatokeomeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeomeP"><input type="radio" checked="true" name="ksmatokeome" id="ksmatokeomeP">Positive</span>
                                                        ';

                                                }elseif($kaswende_matokeo_me=='N'){
                                                     echo '
                                                        <span class="pointer" id="spanksmatokeomeN"><input  type="radio" checked="true" name="ksmatokeome" id="ksmatokeomeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeomeP"><input type="radio" name="ksmatokeome" id="ksmatokeomeP">Positive</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanksmatokeomeN"><input  type="radio" name="ksmatokeome" id="ksmatokeomeN">Negative</span>
                                                        <span class="pointer" id="spanksmatokeomeP"><input type="radio" name="ksmatokeome" id="ksmatokeomeP">Positive</span>
                                                        ';
                                                }

                                                ?>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($kaswende_ametibiwa_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spankstibakeH"><input  type="radio" name="kstibake" id="kstibakeH">Hapana</span>
                                                       <span class="pointer" id="spankstibakeN"><input type="radio" checked="true" name="kstibake" id="kstibakeN">Ndiyo</span>
                                                            ';
                                                }elseif($kaswende_ametibiwa_ke=='H'){
                                                    echo '
                                                        <span class="pointer" id="spankstibakeH"><input  type="radio" checked="true" name="kstibake" id="kstibakeH">Hapana</span>
                                                         <span class="pointer" id="spankstibakeN"><input type="radio" name="kstibake" id="kstibakeN">Ndiyo</span>
                                                           ';

                                                } else {
                                                    echo '
                                                        <span class="pointer" id="spankstibakeH"><input  type="radio" name="kstibake" id="kstibakeH">Hapana</span>
                                                        <span class="pointer" id="spankstibakeN"><input type="radio" name="kstibake" id="kstibakeN">Ndiyo</span>
                                                        ';
                                                }


                                                ?>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Me
                                            </td>
                                            <td>
                                                <?php
                                                if($kaswende_ametibiwa_me=='H'){
                                                    echo '
                                                        <span class="pointer" id="spankstibameH"><input  type="radio" checked="true" name="kstibame" id="kstibameH">Hapana</span>
                                                        <span class="pointer" id="spankstibameN"><input type="radio" name="kstibame" id="kstibameN">Ndiyo</span>
                                                            ';

                                                }elseif($kaswende_ametibiwa_me=='N'){
                                                    echo '
                                                        <span class="pointer" id="spankstibameH"><input  type="radio" name="kstibame" id="kstibameH">Hapana</span>
                                                        <span class="pointer" id="spankstibameN"><input type="radio" checked="true" name="kstibame" id="kstibameN">Ndiyo</span>
                                                        ';

                                                }  else {
                                                    echo '
                                                        <span class="pointer" id="spankstibameH"><input  type="radio" name="kstibame" id="kstibameH">Hapana</span>
                                                        <span class="pointer" id="spankstibameN"><input type="radio" name="kstibame" id="kstibameN">Ndiyo</span>
                                                        ';

                                                }

                                                ?>
                                                </td>

                                        </tr>
                                    </table>
                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($ng_matokeo_ke=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanngmatokeokeN"><input  type="radio" name="ngmatokeoke" id="ngmatokeokeN">Negative</span>
                                                    <span class="pointer" id="spanngmatokeokeP"><input type="radio" checked="true" name="ngmatokeoke" id="ngmatokeokeP">Positive</span>

                                                      ';
                                                }elseif($ng_matokeo_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanngmatokeokeN"><input  type="radio" checked="true" name="ngmatokeoke" id="ngmatokeokeN">Negative</span>
                                                         <span class="pointer" id="spanngmatokeokeP"><input type="radio" name="ngmatokeoke" id="ngmatokeokeP">Positive</span>

                                                        ';

                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanngmatokeokeN"><input  type="radio" name="ngmatokeoke" id="ngmatokeokeN">Negative</span>
                                                        <span class="pointer" id="spanngmatokeokeP"><input type="radio" name="ngmatokeoke" id="ngmatokeokeP">Positive</span>

                                                    ';

                                                }

                                                ?>
                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Me
                                            </td>
                                            <td>

                                                <?php
                                                if($ng_matokeo_me=='N'){
                                                  echo '
                                                      <span class="pointer" id="spanngmatokeomeN"><input  type="radio" checked="true" name="ngmatokeome" id="ngmatokeomeN">Negative</span>
                                                      <span class="pointer" id="spanngmatokeomeP"><input type="radio" name="ngmatokeome" id="ngmatokeomeP">Positive</span>
                                                        ';

                                                }elseif($ng_matokeo_me=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanngmatokeomeN"><input  type="radio" name="ngmatokeome" id="ngmatokeomeN">Negative</span>
                                                        <span class="pointer" id="spanngmatokeomeP"><input type="radio" checked="true" name="ngmatokeome" id="ngmatokeomeP">Positive</span>
                                                          ';

                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanngmatokeomeN"><input  type="radio" name="ngmatokeome" id="ngmatokeomeN">Negative</span>
                                                         <span class="pointer" id="spanngmatokeomeP"><input type="radio" name="ngmatokeome" id="ngmatokeomeP">Positive</span>
                                                        ';

                                                }
                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($ng_ametibiwa_ke=='N'){
                                                  echo '
                                                      <span class="pointer" id="spanngtibakeH"><input  type="radio" name="ngtibake" id="ngtibakeH">Hapana</span>
                                                       <span class="pointer" id="spanngtibakeN"><input type="radio" checked="true" name="ngtibake" id="ngtibakeN">Ndiyo</span>
                                                        ';


                                                }elseif($ng_ametibiwa_ke=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanngtibakeH"><input  type="radio" checked="true" name="ngtibake" id="ngtibakeH">Hapana</span>
                                                        <span class="pointer" id="spanngtibakeN"><input type="radio" name="ngtibake" id="ngtibakeN">Ndiyo</span>
                                                            ';

                                                }else{

                                                  echo '
                                                      <span class="pointer" id="spanngtibakeH"><input  type="radio" name="ngtibake" id="ngtibakeH">Hapana</span>
                                                      <span class="pointer" id="spanngtibakeN"><input type="radio" name="ngtibake" id="ngtibakeN">Ndiyo</span>
                                                    ';
                                                }


                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Me
                                            </td>
                                            <td>
                                                <?php
                                                if($ng_ametibiwa_me=='H'){
                                                    echo '

                                                    <span class="pointer" id="spanngtibameH"><input  type="radio" checked="true" name="ngtibame" id="ngtibameH">Hapana</span>
                                                    <span class="pointer" id="spanngtibameN"><input type="radio" name="ngtibame" id="ngtibameN">Ndiyo</span>
                                                         ';



                                                }elseif($ng_ametibiwa_me=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanngtibameH"><input  type="radio" name="ngtibame" id="ngtibameH">Hapana</span>
                                                         <span class="pointer" id="spanngtibameN"><input type="radio" checked="true" name="ngtibame" id="ngtibameN">Ndiyo</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanngtibameH"><input  type="radio" name="ngtibame" id="ngtibameH">Hapana</span>
                                                        <span class="pointer" id="spanngtibameN"><input type="radio" name="ngtibame" id="ngtibameN">Ndiyo</span>
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
                                            <td style="text-align:right;width:100px;">2</td>
                                            <td width="25%">
                                                <select style="width:200px;" id="marudio_2">
                                                    <option value="<?php echo $marudio_2;?>"><?php echo $marudio_2;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                3
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_3" >
                                                    <option value="<?php echo $marudio_3;?>"><?php echo $marudio_3;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                4
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_4" >
                                                    <option value="<?php echo $marudio_4;?>"><?php echo $marudio_4;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                5
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_5" >
                                                    <option value="<?php echo $marudio_5;?>"><?php echo $marudio_5;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                6
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_6" >
                                                    <option value="<?php echo $marudio_6;?>"><?php echo $marudio_6;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                7
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_7" >
                                                    <option value="<?php echo $marudio_7;?>"><?php echo $marudio_7;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                8
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_8" >
                                                    <option value="<?php echo $marudio_8;?>"><?php echo $marudio_8;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                9
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_9" >
                                                    <option value="<?php echo $marudio_9;?>"><?php echo $marudio_9;?></option>
                                                    <option value="V">Hana matatizo</option>
                                                    <option value="KM">Kuharibika mimba mfululizo x2</option>
                                                    <option value="A">Anaemia</option>
                                                    <option value="P">Protenuria</option>
                                                    <option value="H">High BP</option>
                                                    <option value="U">Kutoongezeka uzito</option>
                                                    <option value="D">Damu ukeni</option>
                                                    <option value="M">Mlalo mbaya wa mtoto</option>
                                                    <option value="M4">Mimba 4+</option>
                                                    <option value="C/S">Kuzaa kwa operesheni</option>
                                                    <option value="VE">Vacuum extraction</option>
                                                    <option value="TB">TB</option>
                                                </select>
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
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Huduma ya PMTCT</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Malaria</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Idadi ya vidonge vya "I" Iron/"FA" Folic Acid</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                Tayari ana maambukizi ya VVU:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($ana_VVU_ke=='H'){
                                                   echo '
                                                      <span class="pointer" id="spantayariVVUkeH"><input  type="radio" checked="true" name="tayariVVUke" id="tayariVVUkeH">Hapana</span>
                                                      <span class="pointer" id="spantayariVVUkeN"><input type="radio" name="tayariVVUke" id="tayariVVUkeN">Ndiyo</span>
                                                            ';

                                                }elseif($ana_VVU_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spantayariVVUkeH"><input  type="radio" name="tayariVVUke" id="tayariVVUkeH">Hapana</span>
                                                        <span class="pointer" id="spantayariVVUkeN"><input type="radio" checked="true" name="tayariVVUke" id="tayariVVUkeN">Ndiyo</span>
                                                        ';

                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spantayariVVUkeH"><input  type="radio" name="tayariVVUke" id="tayariVVUkeH">Hapana</span>
                                                        <span class="pointer" id="spantayariVVUkeN"><input type="radio" name="tayariVVUke" id="tayariVVUkeN">Ndiyo</span>
                                                    ';

                                                }
                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Tayari ana maambukizi ya VVU:Me
                                            </td>
                                            <td>
                                                    <?php
                                                    if($ana_VVU_me=='N'){
                                                        echo '
                                                            <span class="pointer" id="spantayariVVUmeH"><input  type="radio" name="tayariVVUme" id="tayariVVUmeH">Hapana</span>
                                                            <span class="pointer" id="spantayariVVUmeN"><input type="radio" checked="true" name="tayariVVUme" id="tayariVVUmeN">Ndiyo</span>
                                                                 ';

                                                    }elseif ($ana_VVU_me=='H') {
                                                        echo '
                                                            <span class="pointer" id="spantayariVVUmeH"><input  type="radio" checked="true" name="tayariVVUme" id="tayariVVUmeH">Hapana</span>
                                                             <span class="pointer" id="spantayariVVUmeN"><input type="radio" name="tayariVVUme" id="tayariVVUmeN">Ndiyo</span>
                                                            ';

                                                    }else{
                                                       echo'
                                                           <span class="pointer" id="spantayariVVUmeH"><input  type="radio" name="tayariVVUme" id="tayariVVUmeH">Hapana</span>
                                                           <span class="pointer" id="spantayariVVUmeN"><input type="radio" name="tayariVVUme" id="tayariVVUmeN">Ndiyo</span>
                                                            ';

                                                    }

                                                    ?>


                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi:Ke
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="unasihike" value="<?php echo $unasihi_ke;?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi:Me
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="unasihime" value="<?php echo $unasihi_me;?>">
                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepima VVU:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($amepima_VVU_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanamepimaVVUkeH"><input  type="radio" name="amepimaVVUke" id="amepimaVVUkeH">Hapana</span>
                                                  <span class="pointer" id="spanamepimaVVUkeN"><input type="radio" checked="true" name="amepimaVVUke" id="amepimaVVUkeN">Ndiyo</span>
                                                  ';


                                                }elseif($amepima_VVU_ke=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanamepimaVVUkeH"><input  type="radio" checked="true" name="amepimaVVUke" id="amepimaVVUkeH">Hapana</span>
                                                          <span class="pointer" id="spanamepimaVVUkeN"><input type="radio" name="amepimaVVUke" id="amepimaVVUkeN">Ndiyo</span>
                                                      ';

                                                } else {
                                                  echo '
                                                      <span class="pointer" id="spanamepimaVVUkeH"><input  type="radio" name="amepimaVVUke" id="amepimaVVUkeH">Hapana</span>
                                                     <span class="pointer" id="spanamepimaVVUkeN"><input type="radio" name="amepimaVVUke" id="amepimaVVUkeN">Ndiyo</span>
                                                       ';

                                                }
                                                ?>
                                               </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Amepima VVU:Me
                                            </td>
                                            <td>
                                                <?php
                                                if($amepima_VVU_me=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanamepimaVVUmeH"><input  type="radio" name="amepimaVVUme" id="amepimaVVUmeH">Hapana</span>
                                                        <span class="pointer" id="spanamepimaVVUmeN"><input type="radio" checked="true" name="amepimaVVUme" id="amepimaVVUmeN">Ndiyo</span>
                                                        ';


                                                } elseif($amepima_VVU_me=='H'){
                                                   echo '
                                                       <span class="pointer" id="spanamepimaVVUmeH"><input  type="radio" checked="true" name="amepimaVVUme" id="amepimaVVUmeH">Hapana</span>
                                                       <span class="pointer" id="spanamepimaVVUmeN"><input type="radio" name="amepimaVVUme" id="amepimaVVUmeN">Ndiyo</span>
                                                    ';

                                                } else {
                                                  echo
                                                    ' <span class="pointer" id="spanamepimaVVUmeH"><input  type="radio" name="amepimaVVUme" id="amepimaVVUmeH">Hapana</span>
                                                      <span class="pointer" id="spanamepimaVVUmeN"><input type="radio" name="amepimaVVUme" id="amepimaVVUmeN">Ndiyo</span>
                                                    ';

                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya kipimo:Ke
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="kimpimotareheke" value="<?php echo $kipimo_tarehe_ke;?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya kipimo:Me
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="kimpimotareheme" value="<?php echo $kipimo_tarehe_me;?>">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya kipimo cha 1 cha VVU:Ke
                                            </td>
                                            <td>

                                                <?php
                                                   if($kipimo_1_VVU_matokeo_ke=='P'){
                                                       echo '
                                                           <span class="pointer" id="spanmatokeoVVU1keN"><input  type="radio" name="matokeoVVU1ke" id="matokeoVVU1keN">Negative</span>
                                                           <span class="pointer" id="spanmatokeoVVU1keP"><input type="radio" checked="true" name="matokeoVVU1ke" id="matokeoVVU1keP">Positive</span>
                                                    ';

                                                   }elseif($kipimo_1_VVU_matokeo_ke=='N'){
                                                       echo '
                                                           <span class="pointer" id="spanmatokeoVVU1keN"><input  type="radio" checked="true" name="matokeoVVU1ke" id="matokeoVVU1keN">Negative</span>
                                                           <span class="pointer" id="spanmatokeoVVU1keP"><input type="radio" name="matokeoVVU1ke" id="matokeoVVU1keP">Positive</span>
                                                            ';

                                                   }  else {
                                                       echo '
                                                           <span class="pointer" id="spanmatokeoVVU1keN"><input  type="radio" name="matokeoVVU1ke" id="matokeoVVU1keN">Negative</span>
                                                           <span class="pointer" id="spanmatokeoVVU1keP"><input type="radio" name="matokeoVVU1ke" id="matokeoVVU1keP">Positive</span>
                                                           ';

                                                    }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya kipimo cha 1 cha VVU:Me
                                            </td>
                                            <td>
                                                <?php
                                                if($kipimo_1_VVU_matokeo_me=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanmatokeoVVU1meN"><input  type="radio" name="matokeoVVU1me" id="matokeoVVU1meN">Negative</span>
                                                         <span class="pointer" id="spanmatokeoVVU1meP"><input type="radio" checked="true" name="matokeoVVU1me" id="matokeoVVU1meP">Positive</span>
                                                        ';

                                                } elseif ($kipimo_1_VVU_matokeo_me=='N') {
                                                    echo '
                                                        <span class="pointer" id="spanmatokeoVVU1meN"><input  type="radio" checked="true" name="matokeoVVU1me" id="matokeoVVU1meN">Negative</span>
                                                      <span class="pointer" id="spanmatokeoVVU1meP"><input type="radio" name="matokeoVVU1me" id="matokeoVVU1meP">Positive</span>
                                                        ';
                                                }  else {
                                                    echo '
                                                        <span class="pointer" id="spanmatokeoVVU1meN"><input  type="radio" name="matokeoVVU1me" id="matokeoVVU1meN">Negative</span>
                                                        <span class="pointer" id="spanmatokeoVVU1meP"><input type="radio" name="matokeoVVU1me" id="matokeoVVU1meP">Positive</span>
                                                        ';
                                                  }
                                                ?>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi baada ya kupima:Ke
                                            </td>
                                            <td>
                                                <?php
                                                if($unasihi_kupima_ke=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanunasihibaadayakupmakeH"><input  type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeH">Hapana</span>
                                                        <span class="pointer" id="spanunasihibaadayakupmakeN"><input type="radio" checked="true" name="unasihibaadayakupmake" id="unasihibaadayakupmakeN">Ndiyo</span>
                                                         ';


                                                }elseif($unasihi_kupima_ke=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanunasihibaadayakupmakeH"><input  type="radio" checked="true" name="unasihibaadayakupmake" id="unasihibaadayakupmakeH">Hapana</span>
                                                         <span class="pointer" id="spanunasihibaadayakupmakeN"><input type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeN">Ndiyo</span>
                                                        ';


                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanunasihibaadayakupmakeH"><input  type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeH">Hapana</span>
                                                        <span class="pointer" id="spanunasihibaadayakupmakeN"><input type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeN">Ndiyo</span>
                                                        ';

                                                }

                                                ?>
                                                </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi baada ya kupima:Me
                                            </td>
                                            <td>

                                                <?php
                                                if($unasihi_kupima_me=='N'){
                                                    echo ' <span class="pointer" id="spanunasihibaadayakupmameH"><input  type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameH">Hapana</span>
                                                <span class="pointer" id="spanunasihibaadayakupmameN"><input type="radio" checked="true" name="unasihibaadayakupmame" id="unasihibaadayakupmameN">Ndiyo</span>
                                                                ';

                                                }elseif ($unasihi_kupima_me=='H') {
                                                    echo ' <span class="pointer" id="spanunasihibaadayakupmameH"><input  type="radio" checked="true" name="unasihibaadayakupmame" id="unasihibaadayakupmameH">Hapana</span>
                                                        <span class="pointer" id="spanunasihibaadayakupmameN"><input type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameN">Ndiyo</span>
                                                        ';

                                                }  else {

                                                    echo ' <span class="pointer" id="spanunasihibaadayakupmameH"><input  type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameH">Hapana</span>
                                                            <span class="pointer" id="spanunasihibaadayakupmameN"><input type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameN">Ndiyo</span>
                                                        ';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya kipimo cha pili cha VVU
                                            </td>
                                            <td>
                                                <?php
                                                if($matokeo_VVU_2=='P'){
                                                    echo '
                                                        <span class="pointer" id="spanmatokeoVVU2N"><input  type="radio" name="matokeoVVU2" id="matokeoVVU2N">Negative</span>
                                                        <span class="pointer" id="spanmatokeoVVU2P"><input type="radio" checked="true" name="matokeoVVU2" id="matokeoVVU2P">Positive</span>
                                                        ';

                                                }elseif ($matokeo_VVU_2=='N') {
                                                    echo '<span class="pointer" id="spanmatokeoVVU2N"><input  type="radio" checked="true" name="matokeoVVU2" id="matokeoVVU2N">Negative</span>
                                                           <span class="pointer" id="spanmatokeoVVU2P"><input type="radio" name="matokeoVVU2" id="matokeoVVU2P">Positive</span>
                                                        ';
                                                }  else {
                                                    echo '<span class="pointer" id="spanmatokeoVVU2N"><input  type="radio" name="matokeoVVU2" id="matokeoVVU2N">Negative</span>
                                                        <span class="pointer" id="spanmatokeoVVU2P"><input type="radio" name="matokeoVVU2" id="matokeoVVU2P">Positive</span>
                                                        ';
                                                }
                                                ?>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata ushauri juu ya ulishaji wa mtoto
                                            </td>
                                            <td>
                                                <?php
                                                if($amepata_ushauri=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanushauriulishajiH"><input  type="radio" name="ushauriulishaji" id="ushauriulishajiH">Hapana</span>
                                                        <span class="pointer" id="spanushauriulishajiN"><input type="radio" checked="true" name="ushauriulishaji" id="ushauriulishajiN">Ndiyo</span>
                                                         ';

                                                }elseif ($amepata_ushauri=='H') {
                                                    echo '
                                                        <span class="pointer" id="spanushauriulishajiH"><input  type="radio" checked="true" name="ushauriulishaji" id="ushauriulishajiH">Hapana</span>
                                                         <span class="pointer" id="spanushauriulishajiN"><input type="radio" name="ushauriulishaji" id="ushauriulishajiN">Ndiyo</span>
                                                         ';
                                                }  else {
                                                    echo '
                                                        <span class="pointer" id="spanushauriulishajiH"><input  type="radio" name="ushauriulishaji" id="ushauriulishajiH">Hapana</span>
                                                         <span class="pointer" id="spanushauriulishajiN"><input type="radio" name="ushauriulishaji" id="ushauriulishajiN">Ndiyo</span>
                                                        ';
                                                }


                                                ?>


                                            </td>

                                        </tr>


                                    </table>
                                </td><td >
                                    <table width="100%">

                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya MRDT au BS
                                            </td>
                                            <td>
                                                <?php
                                                if($mrdt=='N'){
                                                    echo ' <span class="pointer" id="spanmrdtH"><input  type="radio" name="mrdt" id="mrdtH">Hapana</span>
                                                           <span class="pointer" id="spanmrdtN"><input type="radio" checked="true" name="mrdt" id="mrdtN">Ndiyo</span>
                                                         ';
                                                }elseif ($mrdt=='H') {
                                                    echo '
                                                        <span class="pointer" id="spanmrdtH"><input  type="radio" checked="true" name="mrdt" id="mrdtH">Hapana</span>
                                                        <span class="pointer" id="spanmrdtN"><input type="radio" name="mrdt" id="mrdtN">Ndiyo</span>
                                                        ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanmrdtH"><input  type="radio" name="mrdt" id="mrdtH">Hapana</span>
                                                        <span class="pointer" id="spanmrdtN"><input type="radio" name="mrdt" id="mrdtN">Ndiyo</span>
                                                         ';

                                                }

                                                ?>
                                                </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata hati Punguzo
                                            </td>
                                            <td>
                                                <?php
                                                if($hatipunguzo=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanhatipunguzoH"><input  type="radio" name="hatipunguzo" id="hatipunguzoH">Hapana</span>
                                                      <span class="pointer" id="spanhatipunguzoN"><input type="radio" checked="true" name="hatipunguzo" id="hatipunguzoN">Ndiyo</span>
                                                    ';

                                                }elseif($hatipunguzo=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanhatipunguzoH"><input  type="radio" checked="true" name="hatipunguzo" id="hatipunguzoH">Hapana</span>
                                                       <span class="pointer" id="spanhatipunguzoN"><input type="radio" name="hatipunguzo" id="hatipunguzoN">Ndiyo</span>
                                                        ';

                                                }  else {

                                                    echo '
                                                        <span class="pointer" id="spanhatipunguzoH"><input  type="radio" name="hatipunguzo" id="hatipunguzoH">Hapana</span>
                                                        <span class="pointer" id="spanhatipunguzoN"><input type="radio" name="hatipunguzo" id="hatipunguzoN">Ndiyo</span>
                                                        ';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-1
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt1" value="<?php echo $IPT1;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-2
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt2" value="<?php echo $IPT2;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-3
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt3" value="<?php echo $IPT3;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-4
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt4" value="<?php echo $IPT4;?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                1:
                                                <?php
                                                if($vidonge_aina_1=='I'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_1I"><input  type="radio" checked="true" name="aina_1" id="aina_1I">I</span>
                                                         <span class="pointer" id="spanaina_1FA"><input type="radio" name="aina_1" id="aina_1FA">FA</span>
                                                         <span class="pointer" id="spanaina_1IFA"><input type="radio" name="aina_1" id="aina_1IFA">IFA</span>
                                                        ';
                                                }elseif($vidonge_aina_1=='FA'){

                                                    echo '
                                                        <span class="pointer" id="spanaina_1I"><input  type="radio" name="aina_1" id="aina_1I">I</span>
                                                         <span class="pointer" id="spanaina_1FA"><input type="radio" checked="true" name="aina_1" id="aina_1FA">FA</span>
                                                         <span class="pointer" id="spanaina_1IFA"><input type="radio" name="aina_1" id="aina_1IFA">IFA</span>
                                                        ';
                                                }elseif ($vidonge_aina_1=='IFA') {
                                                    echo '
                                                        <span class="pointer" id="spanaina_1I"><input  type="radio" name="aina_1" id="aina_1I">I</span>
                                                <span class="pointer" id="spanaina_1FA"><input type="radio" name="aina_1" id="aina_1FA">FA</span>
                                                <span class="pointer" id="spanaina_1IFA"><input type="radio" checked="true" name="aina_1" id="aina_1IFA">IFA</span>
                                                            ';
                                                }  else {
                                                    echo '
                                                        <span class="pointer" id="spanaina_1I"><input  type="radio" name="aina_1" id="aina_1I">I</span>
                                                        <span class="pointer" id="spanaina_1FA"><input type="radio" name="aina_1" id="aina_1FA">FA</span>
                                                        <span class="pointer" id="spanaina_1IFA"><input type="radio" name="aina_1" id="aina_1IFA">IFA</span>
                                                        ';

                                                }


                                                ?>

                                            </td>
                                            <td width="25%">
                                                <input type="text" style="width:200px;" id="idadi_1" value="<?php echo $vidonge_idadi_1;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                2:

                                                <?php
                                                if($vidonge_aina_2=='I'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_2I"><input  type="radio" checked="true" name="aina_2" id="aina_2I">I</span>
                                                     <span class="pointer" id="spanaina_2FA"><input type="radio" name="aina_2" id="aina_2FA">FA</span>
                                                     <span class="pointer" id="spanaina_2IFA"><input type="radio" name="aina_2" id="aina_2IFA">IFA</span>
                                                        ';

                                                }elseif($vidonge_aina_2=='FA'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_2I"><input  type="radio" name="aina_2" id="aina_2I">I</span>
                                                    <span class="pointer" id="spanaina_2FA"><input type="radio" checked="true" name="aina_2" id="aina_2FA">FA</span>
                                                   <span class="pointer" id="spanaina_2IFA"><input type="radio" name="aina_2" id="aina_2IFA">IFA</span>
                                                        ';

                                                }elseif($vidonge_aina_2=='IFA'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_2I"><input  type="radio" name="aina_2" id="aina_2I">I</span>
                                                         <span class="pointer" id="spanaina_2FA"><input type="radio" name="aina_2" id="aina_2FA">FA</span>
                                                        <span class="pointer" id="spanaina_2IFA"><input type="radio" checked="true" name="aina_2" id="aina_2IFA">IFA</span>
                                                         ';

                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanaina_2I"><input  type="radio" name="aina_2" id="aina_2I">I</span>
                                                        <span class="pointer" id="spanaina_2FA"><input type="radio" name="aina_2" id="aina_2FA">FA</span>
                                                        <span class="pointer" id="spanaina_2IFA"><input type="radio" name="aina_2" id="aina_2IFA">IFA</span>
                                                    ';

                                                }

                                                ?>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_2" value="<?php echo $vidonge_idadi_2;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                3:

                                                <?php
                                                if($vidonge_aina_3=='I'){
                                                    echo
                                                    ' <span class="pointer" id="spanaina_3I"><input  type="radio" checked="true" name="aina_3" id="aina_3I">I</span>
                                                      <span class="pointer" id="spanaina_3FA"><input type="radio" name="aina_3" id="aina_3FA">FA</span>
                                                     <span class="pointer" id="spanaina_3IFA"><input type="radio" name="aina_3" id="aina_3IFA">IFA</span>
                                                        ';

                                                }elseif($vidonge_aina_3=='FA'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_3I"><input  type="radio" name="aina_3" id="aina_3I">I</span>
                                                        <span class="pointer" id="spanaina_3FA"><input type="radio" checked="true" name="aina_3" id="aina_3FA">FA</span>
                                                        <span class="pointer" id="spanaina_3IFA"><input type="radio" name="aina_3" id="aina_3IFA">IFA</span>
                                                            ';

                                                }elseif ($vidonge_aina_3=='IFA') {
                                                    echo '
                                                        <span class="pointer" id="spanaina_3I"><input  type="radio" name="aina_3" id="aina_3I">I</span>
                                                         <span class="pointer" id="spanaina_3FA"><input type="radio" name="aina_3" id="aina_3FA">FA</span>
                                                         <span class="pointer" id="spanaina_3IFA"><input type="radio" checked="true" name="aina_3" id="aina_3IFA">IFA</span>
                                                        ';


                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanaina_3I"><input  type="radio" name="aina_3" id="aina_3I">I</span>
                                                       <span class="pointer" id="spanaina_3FA"><input type="radio" name="aina_3" id="aina_3FA">FA</span>
                                                         <span class="pointer" id="spanaina_3IFA"><input type="radio" name="aina_3" id="aina_3IFA">IFA</span>
                                                        ';

                                                }

                                                ?>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_3" value="<?php echo $vidonge_idadi_3;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                4:
                                                <?php
                                                if($vidonge_aina_4=='I'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_4I"><input  type="radio" checked="true" name="aina_4" id="aina_4I">I</span>
                                                         <span class="pointer" id="spanaina_4FA"><input type="radio" name="aina_4" id="aina_4FA">FA</span>
                                                         <span class="pointer" id="spanaina_4IFA"><input type="radio" name="aina_4" id="aina_4IFA">IFA</span>
                                                        ';



                                                }elseif($vidonge_aina_4=='FA'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_4I"><input  type="radio" name="aina_4" id="aina_4I">I</span>
                                                         <span class="pointer" id="spanaina_4FA"><input type="radio" checked="true" name="aina_4" id="aina_4FA">FA</span>
                                                         <span class="pointer" id="spanaina_4IFA"><input type="radio" name="aina_4" id="aina_4IFA">IFA</span>
                                                            ';


                                                }elseif($vidonge_aina_4=='IFA'){
                                                    echo '
                                                        <span class="pointer" id="spanaina_4I"><input  type="radio" name="aina_4" id="aina_4I">I</span>
                                                         <span class="pointer" id="spanaina_4FA"><input type="radio" name="aina_4" id="aina_4FA">FA</span>
                                                         <span class="pointer" id="spanaina_4IFA"><input type="radio" checked="true" name="aina_4" id="aina_4IFA">IFA</span>
                                                        ';


                                                }  else {

                                                    echo '
                                                        <span class="pointer" id="spanaina_4I"><input  type="radio" name="aina_4" id="aina_4I">I</span>
                                                       <span class="pointer" id="spanaina_4FA"><input type="radio" name="aina_4" id="aina_4FA">FA</span>
                                                         <span class="pointer" id="spanaina_4IFA"><input type="radio" name="aina_4" id="aina_4IFA">IFA</span>
                                                        ';

                                                }


                                                ?>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_4" value="<?php echo $vidonge_idadi_4;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Amepata Albendazole/Mebendazole
                                            </td>
                                            <td>

                                                <?php
                                                if($mabendazol=='N'){
                                                    echo '
                                                        <span class="pointer" id="spanamebendazoleH"><input  type="radio" checked="true" name="amebendazole" id="amebendazoleH">Hapana</span>
                                                           <span class="pointer" id="spanamebendazoleN"><input type="radio" name="amebendazole" id="amebendazoleN">Ndiyo</span>
                                                         ';

                                                }elseif($mabendazol=='H'){
                                                    echo '
                                                        <span class="pointer" id="spanamebendazoleH"><input  type="radio" checked="true" name="amebendazole" id="amebendazoleH">Hapana</span>
                                                        <span class="pointer" id="spanamebendazoleN"><input type="radio" name="amebendazole" id="amebendazoleN">Ndiyo</span>
                                                            ';


                                                }  else {
                                                    echo '
                                                        <span class="pointer" id="spanamebendazoleH"><input  type="radio" name="amebendazole" id="amebendazoleH">Hapana</span>
                                                         <span class="pointer" id="spanamebendazoleN"><input type="radio" name="amebendazole" id="amebendazoleN">Ndiyo</span>
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


                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~final table starts here plzzzzzzzzzzzzzzzzzzzzz~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <table align="left" style="width:100%">
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Rufaa</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Maoni</td></tr>

                                <td >
                                    <table width="100%">
                                        <tr>
                                            <td>Tarehe</td>
                                            <td width="70%">
                                                <input type="text" id="rufaatarehe" style="width:100%" value="<?php echo $rufaa_tarehe;?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kituo alikopelekwa</td>
                                            <td width="70%">
                                                <input type="text" id="alikopelekwa" style="width:100%" value="<?php echo $alikopelekwa;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Sababu ya rufaa</td>
                                            <td width="70%">
                                                <input type="text" id="rufaasababu" style="width:100%" value="<?php echo $rufaa_sababu;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Kituo alikotokea</td>
                                            <td width="70%">
                                                <input type="text" id="kituoalikotoka" style="width:100%" value="<?php echo $alikotokea;?>">
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>

                                            <td width="100%">
                                                <textarea style="width:100%;height:130px;text-align:left" id="maoni">
                                                    <?php echo $maoni;?>
                                                </textarea>

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
                    <center> <input type="button" value="Save" id="save_data" class="art-button-green" style="width:200px"> </center>
                    <input type="hidden" id="patient_ID" value="<?php echo $_GET['pn']; ?>">
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
    .pointer:hover{
    cursor:pointer;
    }
</style>
<script>
    $(".tabcontents").tabs();
    $('#TT1date,#TT2date,#rufaatarehe,#kimpimotareheke,#kimpimotareheme,#ipt1,#ipt2,#ipt3,#ipt4').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    $('#hapanabtn').on('click', function () {
        $('#hapanakadi').prop('checked', true);
    });

    $('#ndiyobtn').on('click', function () {
        $('#Ndiyokadi').prop('checked', true);
    });

    $('#spanhapanaFSB').on('click', function () {
        $('#hapanaFSB').prop('checked', true);
    });

    $('#spanndiyoFSB').on('click', function () {
        $('#ndiyoFSB').prop('checked', true);
    });

    $('#spanhapanasukari').on('click', function () {
        $('#hapanasukari').prop('checked', true);
    });

    $('#spanndiyosukari').on('click', function () {
        $('#ndiyosukari').prop('checked', true);
    });

    $('#spanBPH').on('click', function () {
        $('#hapanaBP').prop('checked', true);
    });

    $('#spanBPN').on('click', function () {
        $('#ndiyoBP').prop('checked', true);
    });

    $('#spanCSH').on('click', function () {
        $('#CSH').prop('checked', true);
    });

    $('#spanCSN').on('click', function () {
        $('#CSN').prop('checked', true);
    });

    $('#spanUnder20H').on('click', function () {
        $('#Under20H').prop('checked', true);
    });

    $('#spanUnder20N').on('click', function () {
        $('#Under20N').prop('checked', true);
    });



    $('#spanUnder35H').on('click', function () {
        $('#Under35H').prop('checked', true);
    });

    $('#spanUnder35N').on('click', function () {
        $('#Under35N').prop('checked', true);
    });

    $('#spanksmatokeokeN').on('click', function () {
        $('#ksmatokeokeN').prop('checked', true);
    });

    $('#spanksmatokeokeP').on('click', function () {
        $('#ksmatokeokeP').prop('checked', true);
    });

    $('#spanksmatokeomeN').on('click', function () {
        $('#ksmatokeomeN').prop('checked', true);
    });

    $('#spanksmatokeomeP').on('click', function () {
        $('#ksmatokeomeP').prop('checked', true);
    });

//
    $('#spankstibakeH').on('click', function () {
        $('#kstibakeH').prop('checked', true);
    });

    $('#spankstibakeN').on('click', function () {
        $('#kstibakeN').prop('checked', true);
    });

    $('#spankstibameH').on('click', function () {
        $('#kstibameH').prop('checked', true);
    });

    $('#spankstibameN').on('click', function () {
        $('#kstibameN').prop('checked', true);
    });

    $('#spanngmatokeokeN').on('click', function () {
        $('#ngmatokeokeN').prop('checked', true);
    });

    $('#spanngmatokeokeP').on('click', function () {
        $('#ngmatokeokeP').prop('checked', true);
    });

    $('#spanngmatokeomeN').on('click', function () {
        $('#ngmatokeomeN').prop('checked', true);
    });

    $('#spanngmatokeomeP').on('click', function () {
        $('#ngmatokeomeP').prop('checked', true);
    });

    $('#spanngtibakeH').on('click', function () {
        $('#ngtibakeH').prop('checked', true);
    });

    $('#spanngtibakeN').on('click', function () {
        $('#ngtibakeN').prop('checked', true);
    });

    $('#spanngtibameH').on('click', function () {
        $('#ngtibameH').prop('checked', true);
    });

    $('#spanngtibameN').on('click', function () {
        $('#ngtibameN').prop('checked', true);
    });

    $('#spantayariVVUkeH').on('click', function () {
        $('#tayariVVUkeH').prop('checked', true);
    });

    $('#spantayariVVUkeN').on('click', function () {
        $('#tayariVVUkeN').prop('checked', true);
    });

    $('#spantayariVVUmeH').on('click', function () {
        $('#tayariVVUmeH').prop('checked', true);
    });

    $('#spantayariVVUmeN').on('click', function () {
        $('#tayariVVUmeN').prop('checked', true);
    });


    $('#spanamepimaVVUkeH').on('click', function () {
        $('#amepimaVVUkeH').prop('checked', true);
    });

    $('#spanamepimaVVUkeN').on('click', function () {
        $('#amepimaVVUkeN').prop('checked', true);
    });

    $('#spanamepimaVVUmeH').on('click', function () {
        $('#amepimaVVUmeH').prop('checked', true);
    });

    $('#spanamepimaVVUmeN').on('click', function () {
        $('#amepimaVVUmeN').prop('checked', true);
    });


    $('#spanmatokeoVVU1keN').on('click', function () {
        $('#matokeoVVU1keN').prop('checked', true);
    });

    $('#spanmatokeoVVU1keP').on('click', function () {
        $('#matokeoVVU1keP').prop('checked', true);
    });

    $('#spanmatokeoVVU1meN').on('click', function () {
        $('#matokeoVVU1meN').prop('checked', true);
    });

    $('#spanmatokeoVVU1meP').on('click', function () {
        $('#matokeoVVU1meP').prop('checked', true);
    });


    $('#spanunasihibaadayakupmakeH').on('click', function () {
        $('#unasihibaadayakupmakeH').prop('checked', true);
    });

    $('#spanunasihibaadayakupmakeN').on('click', function () {
        $('#unasihibaadayakupmakeN').prop('checked', true);
    });

    $('#spanunasihibaadayakupmameH').on('click', function () {
        $('#unasihibaadayakupmameH').prop('checked', true);
    });

    $('#spanunasihibaadayakupmameN').on('click', function () {
        $('#unasihibaadayakupmameN').prop('checked', true);
    });


    $('#spanmatokeoVVU2N').on('click', function () {
        $('#matokeoVVU2N').prop('checked', true);
    });

    $('#spanmatokeoVVU2P').on('click', function () {
        $('#matokeoVVU2P').prop('checked', true);
    });

    $('#spanushauriulishajiH').on('click', function () {
        $('#ushauriulishajiH').prop('checked', true);
    });

    $('#spanushauriulishajiN').on('click', function () {
        $('#ushauriulishajiN').prop('checked', true);
    });


    $('#spanmrdtH').on('click', function () {
        $('#mrdtH').prop('checked', true);
    });

    $('#spanmrdtN').on('click', function () {
        $('#mrdtN').prop('checked', true);
    });

    $('#spanhatipunguzoH').on('click', function () {
        $('#hatipunguzoH').prop('checked', true);
    });

    $('#spanhatipunguzoN').on('click', function () {
        $('#hatipunguzoN').prop('checked', true);
    });


    $('#spanaina_1I').on('click', function () {
        $('#aina_1I').prop('checked', true);
    });

    $('#spanaina_1FA').on('click', function () {
        $('#aina_1FA').prop('checked', true);
    });

    $('#spanaina_1IFA').on('click', function () {
        $('#aina_1IFA').prop('checked', true);
    });

    $('#spanaina_2I').on('click', function () {
        $('#aina_2I').prop('checked', true);
    });

    $('#spanaina_2FA').on('click', function () {
        $('#aina_2FA').prop('checked', true);
    });

    $('#spanaina_2IFA').on('click', function () {
        $('#aina_2IFA').prop('checked', true);
    });

    $('#spanaina_3I').on('click', function () {
        $('#aina_3I').prop('checked', true);
    });

    $('#spanaina_3FA').on('click', function () {
        $('#aina_3FA').prop('checked', true);
    });

    $('#spanaina_3IFA').on('click', function () {
        $('#aina_3IFA').prop('checked', true);
    });

    $('#spanaina_4I').on('click', function () {
        $('#aina_4I').prop('checked', true);
    });

    $('#spanaina_4FA').on('click', function () {
        $('#aina_4FA').prop('checked', true);
    });

    $('#spanaina_4IFA').on('click', function () {
        $('#aina_4IFA').prop('checked', true);
    });

//

    $('#spanamebendazoleH').on('click', function () {
        $('#amebendazoleH').prop('checked', true);
    });

    $('#spanamebendazoleN').on('click', function () {
        $('#amebendazoleN').prop('checked', true);
    });

    $('#save_data').click(function () {
        var patient_ID = $('#patientNo').val();
        var leo_Date = $('#leo_Date').val();
        var kijiji_jina = $('#kijiji_jina').val();
        var mwenza = $('#mwenza').val();
        var mwenyekitijina = $('#mwenyekitijina').val();
        var urefu = $('#urefu').val();
        var TT1date = $('#TT1date').val();
        var TT2date = $('#TT2date').val();
        var mimbaNo = $('#mimbaNo').val();
        var mimbaumri = $('#mimbaumri').val();
        var umezaaNo = $('#umezaaNo').val();
        var watotohai = $('#watotohai').val();
        var abortions = $('#abortions').val();
        var mtotowamwishoAge = $('#mtotowamwishoAge').val();
        var damuKiwango = $('#damuKiwango').val();
        var anakadi;
        var kufungaCS;
        var under_20;
        var under_35;
        var ksmatokeoke;
        var ksmatokeome;
        var kstibake;
        var kstibame;
        var ngmatokeoke;
        var ngmatokeome;
        var ngtibake;
        var ngtibame;
        var tayariVVUke;
        var tayariVVUme;
        var amepimaVVUke;
        var amepimaVVUme;
        var matokeoVVU1ke;
        var matokeoVVU1me;
        var unasihibaadayakupmake;
        var unasihibaadayakupmame;
        var matokeoVVU2;
        var ushauriulishaji;
        var mrdt;
        var hatipunguzo;
        var aina_1;
        var aina_2;
        var aina_3;
        var aina_4;
        var amebendazole;

        if ($('#amebendazoleH').is(':checked')) {
            amebendazole = 'H';
        } else if ($('#amebendazoleN').is(':checked')) {
            amebendazole = 'N';
        }

        if ($('#aina_4I').is(':checked')) {
            aina_4 = 'I';
        } else if ($('#aina_4FA').is(':checked')) {
            aina_4 = 'FA';
        } else if ($('#aina_4IFA')) {
            aina_4 = 'IFA';
        }

        if ($('#aina_3I').is(':checked')) {
            aina_3 = 'I';
        } else if ($('#aina_3FA').is(':checked')) {
            aina_3 = 'FA';
        } else if ($('#aina_3IFA')) {
            aina_3 = 'IFA';
        }
        if ($('#aina_2I').is(':checked')) {
            aina_2 = 'I';
        } else if ($('#aina_2FA').is(':checked')) {
            aina_2 = 'FA';
        } else if ($('#aina_2IFA')) {
            aina_2 = 'IFA';
        }


        if ($('#aina_1I').is(':checked')) {
            aina_1 = 'I';
        } else if ($('#aina_1FA').is(':checked')) {
            aina_1 = 'FA';
        } else if ($('#aina_1IFA')) {
            aina_1 = 'IFA';
        }

        if ($('#hatipunguzoH').is(':checked')) {
            hatipunguzo = 'H';
        } else if ($('#hatipunguzoN').is(':checked')) {
            hatipunguzo = 'N';
        }


        if ($('#mrdtH').is(':checked')) {
            mrdt = 'H';
        } else if ($('#mrdtN').is(':checked')) {
            mrdt = 'N';
        }

        if ($('#ushauriulishajiH').is(':checked')) {
            ushauriulishaji = 'H';
        } else if ($('#ushauriulishajiN').is(':checked')) {
            matokeoVVU2 = 'N';
        }

        if ($('#matokeoVVU2N').is(':checked')) {
            matokeoVVU2 = 'N';
        } else if ($('#matokeoVVU2P').is(':checked')) {
            matokeoVVU2 = 'P';
        }

        if ($('#unasihibaadayakupmameH').is(':checked')) {
            unasihibaadayakupmame = 'N';
        } else if ($('#unasihibaadayakupmameN').is(':checked')) {
            unasihibaadayakupmame = 'H';
        }

        if ($('#unasihibaadayakupmakeH').is(':checked')) {
            unasihibaadayakupmake = 'N';
        } else if ($('#unasihibaadayakupmakeN').is(':checked')) {
            unasihibaadayakupmake = 'H';
        }

        if ($('#matokeoVVU1meN').is(':checked')) {
            matokeoVVU1me = 'N';
        } else if ($('#matokeoVVU1meP').is(':checked')) {
            matokeoVVU1me = 'P';
        }

        if ($('#matokeoVVU1keN').is(':checked')) {
            matokeoVVU1ke = 'N';
        } else if ($('#matokeoVVU1keP').is(':checked')) {
            matokeoVVU1ke = 'P';
        }

        if ($('#amepimaVVUmeH').is(':checked')) {
            amepimaVVUme = 'H';
        } else if ($('#amepimaVVUmeN').is(':checked')) {
            amepimaVVUme = 'N';
        }

        if ($('#amepimaVVUkeH').is(':checked')) {
            amepimaVVUke = 'H';
        } else if ($('#amepimaVVUkeN').is(':checked')) {
            amepimaVVUke = 'N';
        }

        if ($('#tayariVVUmeH').is(':checked')) {
            tayariVVUme = 'H';
        } else if ($('#tayariVVUmeN').is(':checked')) {
            tayariVVUme = 'N';
        }


        if ($('#tayariVVUkeH').is(':checked')) {
            tayariVVUke = 'H';
        } else if ($('#tayariVVUkeN').is(':checked')) {
            tayariVVUke = 'N';
        }


        if ($('#ngtibameH').is(':checked')) {
            ngtibame = 'H';
        } else if ($('#ngtibameN').is(':checked')) {
            ngtibame = 'N';
        }

        if ($('#ngtibakeH').is(':checked')) {
            ngtibake = 'H';
        } else if ($('#ngtibakeN').is(':checked')) {
            ngtibake = 'N';
        }

        if ($('#ngmatokeomeN').is(':checked')) {
            ngmatokeome = 'N';
        } else if ($('#ngmatokeomeP').is(':checked')) {
            ngmatokeome = 'P';
        }

        if ($('#ngmatokeokeN').is(':checked')) {
            ngmatokeoke = 'N';
        } else if ($('#ngmatokeokeP').is(':checked')) {
            ngmatokeoke = 'P';
        }

        if ($('#kstibameN').is(':checked')) {
            kstibame = 'N';
        } else if ($('#kstibameH').is(':checked')) {
            kstibame = 'H';
        }

        if ($('#kstibakeN').is(':checked')) {
            kstibake = 'N';
        } else if ($('#kstibakeH').is(':checked')) {
            kstibake = 'H';
        }

        if ($('#ksmatokeomeN').is(':checked')) {
            ksmatokeoke = 'N';
        } else if ($('#ksmatokeomeP').is(':checked')) {
            ksmatokeoke = 'P';
        }

        if ($('#spanksmatokeokeN').is(':checked')) {
            ksmatokeoke = 'N';
        } else if ($('#spanksmatokeokeP').is(':checked')) {
            ksmatokeoke = 'P';
        }


        if ($('#Under35N').is(':checked')) {
            under_35 = 'N';
        } else if ($('#Under35H').is(':checked')) {
            under_35 = 'H';
        }


        if ($('#Under20N').is(':checked')) {
            under_20 = 'N';
        } else if ($('#Under20H').is(':checked')) {
            under_20 = 'H';
        }

        if ($('#CSN').is(':checked')) {
            kufungaCS = 'N';
        } else if ($('#CSH').is(':checked')) {
            kufungaCS = 'H';
        }

        if ($('#hapanakadi').is(':checked')) {
            anakadi = 'H';
        } else if ($('#Ndiyokadi').is(':checked')) {
            anakadi = 'N';
        }

        var FSB;
        if ($('#hapanaFSB').is(':checked')) {
            FSB = 'H';
        } else if ($('#ndiyoFSB').is(':checked')) {
            FSB = 'N';
        }

        var BP;
        if ($('#hapanaBP').is(':checked')) {
            BP = 'H';
        } else if ($('#ndiyoBP').is(':checked')) {
            BP = 'N';
        }

        var mkojosukari;
        if ($('#hapanasukari').is(':checked')) {
            mkojosukari = 'H';
        } else if ($('#ndiyosukari').is(':checked')) {
            mkojosukari = 'N';
        }

        var marudio_2 = $('#marudio_2').val();
        var marudio_3 = $('#marudio_3').val();
        var marudio_4 = $('#marudio_4').val();
        var marudio_5 = $('#marudio_5').val();
        var marudio_6 = $('#marudio_6').val();
        var marudio_7 = $('#marudio_7').val();
        var marudio_8 = $('#marudio_8').val();
        var marudio_9 = $('#marudio_9').val();
        var unasihike = $('#unasihike').val();
        var unasihime = $('#unasihime').val();
        var kimpimotareheke = $('#kimpimotareheke').val();
        var kimpimotareheme = $('#kimpimotareheme').val();
        var ipt1 = $('#ipt1').val();
        var ipt2 = $('#ipt2').val();
        var ipt3 = $('#ipt3').val();
        var ipt4 = $('#ipt4').val();
        var idadi_1 = $('#idadi_1').val();
        var idadi_2 = $('#idadi_2').val();
        var idadi_3 = $('#idadi_3').val();
        var idadi_4 = $('#idadi_4').val();
        var rufaatarehe = $('#rufaatarehe').val();
        var alikopelekwa = $('#alikopelekwa').val();
        var rufaasababu = $('#rufaasababu').val();
        var kituoalikotoka = $('#kituoalikotoka').val();
        var maoni = $('#maoni').val();
        $.ajax({
            type: 'POST',
            url: 'requests/save_wajawazito_edit.php',
            data: 'action=save&patient_ID=' + patient_ID + '&leo_Date=' + leo_Date + '&kijiji_jina=' + kijiji_jina + '&mwenza=' + mwenza + '&mwenyekitijina=' + mwenyekitijina + '&anakadi=' + anakadi + '&TT1date=' + TT1date + '&TT2date=' + TT2date + '&mimbaNo=' + mimbaNo + '&mimbaumri=' + mimbaumri + '&umezaaNo=' + umezaaNo + '&watotohai=' + watotohai + '&abortions=' + abortions + '&FSB=' + FSB +
                    '&mtotowamwishoAge=' + mtotowamwishoAge + '&damuKiwango=' + damuKiwango + '&BP=' + BP + '&urefu=' + urefu + '&mkojosukari=' + mkojosukari + '&kufungaCS=' + kufungaCS + '&under_20=' + under_20 + '&under_35=' + under_35 + '&ksmatokeoke=' + ksmatokeoke + '&ksmatokeome=' + ksmatokeome + '&kstibake=' + kstibake + '&kstibame=' + kstibame + '&ngmatokeoke=' + ngmatokeoke + '&ngmatokeome=' + ngmatokeome +
                    '&ngtibake=' + ngtibake + '&ngtibame=' + ngtibame + '&marudio_2=' + marudio_2 + '&marudio_3=' + marudio_3 + '&marudio_4=' + marudio_4 + '&marudio_5=' + marudio_5 + '&marudio_6=' + marudio_6 + '&marudio_7=' + marudio_7 + '&marudio_8=' + marudio_8 + '&marudio_9=' + marudio_9 + '&tayariVVUke=' + tayariVVUke + '&tayariVVUme=' + tayariVVUme + '&unasihike=' + unasihike + '&unasihime=' + unasihime +
                    '&amepimaVVUke=' + amepimaVVUke + '&amepimaVVUme=' + amepimaVVUme + '&kimpimotareheke=' + kimpimotareheke + '&kimpimotareheme=' + kimpimotareheme + '&matokeoVVU1ke=' + matokeoVVU1ke + '&matokeoVVU1me=' + matokeoVVU1me + '&unasihibaadayakupmake=' + unasihibaadayakupmake + '&unasihibaadayakupmame=' + unasihibaadayakupmame + '&matokeoVVU2=' + matokeoVVU2 + '&ushauriulishaji=' + ushauriulishaji +
                    '&mrdt=' + mrdt + '&hatipunguzo=' + hatipunguzo + '&ipt1=' + ipt1 + '&ipt2=' + ipt2 + '&ipt3=' + ipt3 + '&ipt4=' + ipt4 + '&aina_1=' + aina_1 + '&aina_2=' + aina_2 + '&aina_3=' + aina_3 + '&aina_4=' + aina_4 + '&idadi_1=' + idadi_1 + '&idadi_2=' + idadi_2 + '&idadi_3=' + idadi_3 + '&idadi_4=' + idadi_4 + '&amebendazole=' + amebendazole + '&rufaatarehe=' + rufaatarehe + '&alikopelekwa=' + alikopelekwa + '&rufaasababu=' + rufaasababu + '&kituoalikotoka=' + kituoalikotoka + '&maoni=' + maoni,
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
    });
</script>
