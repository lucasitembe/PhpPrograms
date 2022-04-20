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
        echo "<a href='' id='history' class='art-button-green'>HISTORY</a>";
    }
}



if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='' id='deliverChart' class='art-button-green'>CHART</a>";
    }
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='' id='delivery' class='art-button-green'>DERIVERY INFORMATION</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='searchvisitorsWazazipatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
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
}
?>

<style>
  #spanARVyes:hover,#spanARVno:hover,#spannotremoved:hover,#spanyesremoved:hover,#spanrapturedpoint:hover,#HF:hover,#BBA:hover,#TBA:hover,#H:hover,#funguliaHF:hover,#funguliaBBA:hover,#funguliaTBA:hover,#funguliaH:hover,#uchungubaada12:hover,#uchungundani12:hover,#spandk1:hover,#spandk5:hover{
    cursor:pointer;
    }

   #spantt1:hover,#spantt2:hover,#spantt3:hover,#spantt4:hover,#spantt5:hover,#spanartificialrapture:hover,#funguaKW:hover,#funguaVM:hover,#funguaCS:hover,#funguaBR:hover,#funguaNY:hover,#jinsime:hover,#jinsike:hover,#spansuction:hover,#spanstimulation:hover,#spanmask:hover,#spanhapana:hover{
    cursor:pointer;
    }

    #spanMale:hover,#spanFemale:hover,#spantathminndiyo:hover,#spantathminhapana:hover,#spanFSB:hover,#spanMSB:hover,#spanAP:hover,#spanPROM:hover,#spanA:hover,#spanPE:hover,#spanE:hover,#spanSepsis:hover,#spanMalaria:hover,#spanHIV:hover,#spanFGM:hover{
    cursor:pointer;
    }

  #spanpolioyes:hover,#spanpoliono:hover,#spanbcgyes:hover,#spanbcgno:hover,#spanbelow:hover,#spantemp:hover,#spansuck:hover,#spanapgar:hover,#spanIntact:hover,#spanTear:hover,#spanEpisiotomy:hover,#spanmtotokufa,#spanmtotohai:hover,#spanmamakufa:hover,#spanmamahai:hover,#spanRF:hover,#spanEBF:hover,#spanmtotoARVhapana:hover,#spanmtotoARVpewa:hover,#spankipimoVVUjuli:hover,
  #spankipimoVVUnegat:hover,#spankipimoVVUpos:hover,#spanVVUjuli:hover,#spanVVUnegat:hover,#spanVVUpos:hover,#spanMVAhapana:hover,#spanMVAndiyo:hover,#spanFGMhapana:hover,#spanFGMndiyo:hover,#spandamuhapana:hover,#spandamundiyo:hover,#spansulhapana:hover,#spansulndiyo:hover,#spanmisohapana:hover,#spanmisondiyo:hover,#spanPPH:hover,#spanpree:hover,#spanecla:hover,#spanOL:hover,#spantear:hover,
  #spanRP:hover,#spanMK:hover,#spanKN:hover,#spannyonyandiyo:hover,#spannyonyahapana:hover,#spanantindiyo:hover,#spanantihapana:hover{
    cursor:pointer;
    }
</style>

<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA WAZAZI</b></legend>
    <div class="tabcontents" style="height:550px;overflow:auto" >
        <ul style="display:none">
            <li style=""><a href="#tabs-1">Hatua ya 1</a></li>
            <li style=""><a href="#tabs-2">Hatua ya 2</a></li>
        </ul>

        <!--first step-->

        <div id="tabs-1">
            <center>
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Namba ya Jalada
                        </td>
                        <td width="40%">
                            <input  type="text" id="jalada_no">
                        </td>
                        <td  style="text-align:right;" width="20%">Namba ya Kadi ya Uzazi(RCH-4)</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;"  id="rch_no" name="" type="text">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Jina la mama
                        </td>
                        <td >
                            <input name="jinakamili" id="mtoto_Jina"  type="text" value="<?php echo $name;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Jina la Kijiji/Kitongoji/Balozi/mtaa
                        </td>
                        <td>
                            <input name="kijiji_kitongoji" id="kijiji_kitongoji"  type="text">
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
                            <input type="text" id="gravida">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Amezaa mara ngapi(Para)
                        </td>
                        <td>
                            <input name="name" id="para" type="text">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Watoto Hai(Alive)
                        </td>
                        <td>
                            <input type="text" id="watoto_hai">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Tarehe na muda wa kulazwa
                        </td>
                        <td>
                            <input name="admission_date" id="admission_date" class="date" type="text">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Tarehe na muda wa kujifungua
                        </td>
                        <td>
                            <input type="text" id="kujifungua_trh" class="date">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Taarifa kuhusu uchungu
                        </td>
                        <td>
                            <span id="uchungundani12"> <input type="radio" name="uchungu" id="ndani12">Ndani ya saa 12</span>
                            <span id="uchungubaada12"> <input type="radio" name="uchungu" id="baada12">Baada ya saa 12</span>
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Alipojifungulia
                        </td>
                        <td>
                            <span id="funguliaHF"> <input type="radio" name="alipojifungulia" id="HF">HF</span>
                            <span id="funguliaBBA"> <input type="radio" name="alipojifungulia" id="BBA">BBA</span>
                            <span id="funguliaTBA"> <input type="radio" name="alipojifungulia" id="TBA">TBA</span>
                            <span id="funguliaH"> <input type="radio" name="alipojifungulia" id="H">H</span>
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
                                                <span id="funguaKW"><input type="radio" name="kujifunguanjia" id="KW">KW</span>
                                                <span id="funguaVM"><input type="radio" name="kujifunguanjia" id="VM">VM</span>
                                                <span id="funguaCS"><input type="radio" name="kujifunguanjia" id="CS">CS</span>
                                                <span id="funguaBR"><input type="radio" name="kujifunguanjia" id="BR">BR</span>
                                                <span id="funguaNY"><input type="radio" name="kujifunguanjia" id="NY">NY</span>
                                            </td>


                                        </tr>

                                        <tr>
                                            <td >
                                                Jinsi ya Mtoto(KE/ME)
                                            </td>
                                            <td>
                                                <span id="jinsime"><input type="radio" name="jinsi" id="me">ME</span>
                                                <span id="jinsike"><input type="radio" name="jinsi" id="ke">KE</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Mtoto amesaidiwa kupumua
                                            </td>
                                            <td>
                                                <span id="spansuction"><input type="radio" name="kupumua" id="suction">1:suction</span>
                                                <span id="spanstimulation"><input type="radio" name="kupumua" id="stimulation">2:stimulation</span>
                                                <span id="spanmask"><input type="radio" name="kupumua" id="Mask">3:bag &amp; Mask</span><br />
                                                <span id="spanhapana"><input type="radio" name="kupumua" id="hapana">Hapana</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                APGAR
                                            </td>
                                            <td>
                                                <span id="spandk1"><input type="radio" name="APGAR" id="dk1">Dakika-1</span>
                                                <span id="spandk5"><input type="radio" name="APGAR" id="dk5">Dakika-5</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Amenyonyeshwa ndani ya saa moja ya kuzaliwa
                                            </td>
                                            <td>
                                                <span id="spannyonyandiyo"><input type="radio" name="nyonya" id="nyonyandiyo">Ndiyo</span>
                                                <span id="spannyonyahapana"><input type="radio" name="nyonya" id="nyonyahapana">Hapana</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Uzito wa mtoto kwa gm/Kg
                                            </td>
                                            <td>
                                                <input type="text" id="mtotoUzito">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Tathmini ya mtoto baada ya masaa 24
                                            </td>
                                            <td>
                                                <span id="spantathminndiyo"><input type="radio" name="tathmin" id="tahminndiyo">Ndiyo</span>
                                                <span id="spantathminhapana"><input type="radio" name="tathmin" id="tathminhapana">Hapana</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                FSB/MSB
                                            </td>
                                            <td>
                                                <span id="spanFSB"><input type="radio" name="FSB" id="FSB">FSB</span>
                                                <span id="spanMSB"><input type="radio" name="FSB" id="MSB">MSB</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >

                                            </td>
                                            <td>
                                                <span id="spanAP"><input type="radio" name="AP" id="AP">AP</span>
                                                <span id="spanPROM"><input type="radio" name="AP" id="PROM">PROM</span>
                                                <span id="spanA"><input type="radio" name="AP" id="A">"A" Anaemia</span><br />
                                                <span id="spanPE"><input type="radio" name="AP" id="PE">"PE" preeclampsia</span>
                                                <span id="spanE"><input type="radio" name="AP" id="E">'E' eclampsia</span><br />
                                                <span id="spanSepsis"><input type="radio" name="AP" id="sepsis">Sepsis</span>
                                                <span id="spanMalaria"><input type="radio" name="AP" id="Malaria">Malaria</span>
                                                <span id="spanHIV"><input type="radio" name="AP" id="HIV">HIV+</span>
                                                <span id="spanFGM"><input type="radio" name="AP" id="FGM">FGM</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >

                                            </td>
                                            <td>
                                                <span id="spanPPH"><input type="radio" name="PPH" id="PPH">PPH</span>
                                                <span id="spanpree"><input type="radio" name="PPH" id="pree">'PE' preeclampsia</span><br />
                                                <span id="spanecla"><input type="radio" name="PPH" id="ecla">"E" eclampsia</span>
                                                <span id="spanOL"><input type="radio" name="PPH" id="OL">"OL" Obstructed labour</span>
                                                <span id="spantear"><input type="radio" name="PPH" id="tear">3 tear</span>
                                                <span id="spanRP"><input type="radio" name="PPH" id="RP">'RP' Retained placenta</span>
                                                <span id="spanMK"><input type="radio" name="PPH" id="MK">'MK' maumivu ya kifua</span>
                                                <span id="spanKN"><input type="radio" name="PPH" id="KN">'KN' Kuishiwa nguvu</span>

                                            </td>

                                        </tr>


                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:60px;">Mama amepewa sindano ya antibiotic</td>
                                            <td width="20%">
                                                <span id="spanantindiyo"><input type="radio" id="antindiyo" name="antibiotic">Ndiyo</span>
                                                <span id="spanantihapana"><input type="radio" id="antihapana" name="antibiotic">Hapana</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Oxytocin/ergometrin/misoprostol</td>
                                            <td width="20%">
                                                <span id="spanmisondiyo"><input type="radio" id="misondiyo" name="miso">Ndiyo</span>
                                                <span id="spanmisohapana"><input type="radio" id="misohapana" name="miso">Hapana</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">inj.Magnesium sulfate</td>
                                            <td width="20%">
                                                <span id="spansulndiyo"><input type="radio" id="sulndiyo" name="sul">Ndiyo</span>
                                                <span id="spansulhapana"><input type="radio" id="sulhapana" name="sul">Hapana</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Amefanyiwa MVA/D&C</td>
                                            <td width="20%">
                                                <span id="spanMVAndiyo"><input type="radio" id="MVAndiyo" name="MVA">Ndiyo</span>
                                                <span id="spanMVAhapana"><input type="radio" id="MVAhapana" name="MVA">Hapana</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">Kuongezewa damu</td>
                                            <td width="20%">
                                                <span id="spandamundiyo"><input type="radio" id="damundiyo" name="damu">Ndiyo</span>
                                                <span id="spandamuhapana"><input type="radio" id="damuhapana" name="damu">Hapana</span>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">Mama amekeketwa</td>
                                            <td width="25%">
                                                <span id="spanFGMndiyo"><input type="radio" id="FGMndiyo" name="keketwa">Ndiyo</span><br />
                                                <span id="spanFGMhapana"><input type="radio" id="FGMhapana" name="keketwa">Hapana</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Kipimo cha VVU
                                            </td>
                                            <td>
                                                <span id="spanVVUpos"><input type="radio" id="VVUpos" name="vvukipimo">Positive</span><br />
                                                <span id="spanVVUnegat"><input type="radio" id="VVUnegat" name="vvukipimo">Negative</span><br />
                                                <span id="spanVVUjuli"><input type="radio" id="VVUjuli" name="vvukipimo">Haijulikani</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Kipimo cha VVU wakati wa uchungu na baada ya kujifungua
                                            </td>
                                            <td>
                                                <span id="spankipimoVVUpos"><input type="radio" id="kipimoVVUpos" name="kujifunguaVVU">Positive</span><br />
                                                <span id="spankipimoVVUnegat"><input type="radio" id="kipimoVVUnegat" name="kujifunguaVVU">Negative</span><br />
                                                <span id="spankipimoVVUjuli"><input type="radio" id="kipimoVVUjuli" name="kujifunguaVVU">Haijulikani</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Dawa za ARV kwa Mtoto
                                            </td>
                                            <td>
                                                <span id="spanmtotoARVpewa"><input type="radio" id="mtotoARVpewa" name="mtotoARV">Amepewa</span><br />
                                                <span id="spanmtotoARVhapana"><input type="radio" id="mtotoARVhapana" name="mtotoARV">Hajapewa</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ulishaji wa mtoto
                                            </td>
                                            <td>
                                                <span id="spanEBF"><input type="radio" id="EBF" name="mtotoulishaji">Maziwa ya mama pekee</span><br />
                                                <span id="spanRF"><input type="radio" id="RF" name="mtotoulishaji">Maziwa mbadala</span>
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
                                                <span id="spanmamahai"><input type="radio" name='mamahali' id='mamahai'>Hai</span>
                                                <span id="spanmamakufa"><input type="radio" name='mamahali' id='mamakufa'>Amefariki</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali(Mama)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="mama_hali_details">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya Kuruhusiwa/Kufariki(Mama)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="discharge_date_mama" class="date">

                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu za kifo
                                            </td>
                                            <td>
                                                <input type="text" style="" id="kifo_sababu_mama">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali ya mtoto
                                            </td>
                                            <td>
                                                <span id="spanmtotohai"><input type="radio" name='mtotohali' id='mtotohai'>Hai</span>
                                                <span id="spanmtotokufa"><input type="radio" name='mtotohali' id='mtotokufa'>Amefariki</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Hali(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="mtoto_hali_details">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya Kuruhusiwa/Kufariki(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="discharge_date_mtoto" class="date">

                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu za kifo(Mtoto)
                                            </td>
                                            <td>
                                                <input type="text" style="" id="kifo_sababu_mtoto">

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
                                                <input type="text" style="width:340px" id="alikopelekwa">

                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                Sababu
                                            </td>
                                            <td>
                                                <textarea id="rufaa_sababu"></textarea>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Jina</td>
                                            <td width="25%">
                                                <input type="text" style="width:370px;" id="mzalishaji">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kada
                                            </td>
                                            <td>
                                                <input type="text" style="width:370px;" name="kada" id="kada" >

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
                    </td>
                    </tr>
                </table>
        </div>

    </div>
</fieldset>


<!--Dialog starts here-->
<div id="wazazidialog" style="display:none">
    <?php
    $selectQ= mysqli_query($conn,"SELECT * FROM tbl_antenatal_records WHERE Patient_ID='$pn'");
    $result=  mysqli_fetch_assoc($selectQ);
     $lmp=$result['LMP'];
     $edd=$result['EDD'];
     $fdate=$result['EXAM_DATE'];
     $bgroup=$result['BLOOD_GROUP'];
     $bp=$result['BP'];
     $oedema=$result['OEDEMA'];
     $breast=$result['BREASTS'];
     $hb=$result['HB'];
     $lungs=$result['LUNGS'];
     $rh=$result['RH'];
     $abdomen=$result['ABDOMEN'];
     $pmtct0=$result['PMTCT_1'];
     $pmtct1=$result['PMTCT_2'];
     $pmtct2=$result['PMTCT_3'];
     $urine=$result['URINE'];
     $ppr=$result['PPR'];
     $remarks=$result['REMARKS'];
     $tt=$result['TT'];
     $today=$result['DATE_VAL'];
     $examine=$result['EXAM_BY'];

    $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='$examine'");
    $row= mysqli_fetch_assoc($employee);
    $name=$row['Employee_Name'];

    ?>
    <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;">
        <tr>
            <td class="powercharts_td_left" style="text-align:right">
                LMP
            </td>
            <td width="40%">
                <input  type="text" id="lmp" value="<?php echo $lmp;?>">
            </td>


            <td  style="text-align:right;">DATE OF FIRST EXAM</td>
            <td  width="40%" colspan="2">

                <input id="first_Date" name="" type="text" value="<?php echo $fdate;?>">
            </td>
        </tr>

        <tr>
            <td class="powercharts_td_left" style="text-align:right">
                E.D.D
            </td>
            <td width="40%">
                <input  type="text" id="edd" value="<?php echo $edd;?>">
            </td>


            <td  style="text-align:right;">BLOOD GR</td>
            <td  width="40%" colspan="2">

                <input id="blood_group" name="" type="text" value="<?php echo $bgroup;?>">
            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                BP
            </td>
            <td width="40%">
                <input  type="text" id="bp2" value="<?php echo $bp;?>">
            </td>


            <td  style="text-align:right;">OEDEMA</td>
            <td  width="40%" colspan="2">

                <input id="oedema2" name="" type="text" value="<?php echo $oedema;?>">
            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                BREASTS
            </td>
            <td >
                <input name="jinakamili" id="breast"  type="text" value="<?php echo $breast;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">
                HB
            </td>
            <td>
                <input name="" id="hb2"  type="text" value="<?php echo $hb;?>">
            </td>

        </tr>
        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                LUNGS
            </td>
            <td>
                <input name="name" id="lungs" type="text" value="<?php echo $lungs;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">
                Rh
            </td>
            <td>
                <input type="text" id="rh" value="<?php echo $rh;?>">
            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                ABDOMEN
            </td>
            <td>
                <input name="name" id="abdomen" type="text" value="<?php echo $abdomen;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">
              PMTCT  0<br /><br />
                1<br /><br />
                2<br /><br />
            </td>
            <td>
                <input type="text" id="pmtct0" value="<?php echo $pmtct0;?>">
                <input type="text" id="pmtct1" value="<?php echo $pmtct1;?>">
                <input type="text" id="pmtct2" value="<?php echo $pmtct2;?>">
            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                URINE
            </td>
            <td>
                <input name="name" id="urine" type="text" value="<?php echo $urine;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">
               PPR
            </td>
            <td>
                <input type="text" id="ppr" value="<?php echo $ppr;?>">
            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                REMARKS
            </td>
            <td>
                <input type="text" id="remarks_data" value="<?php echo $remarks;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">

            </td>
            <td>
                <?php
                   if($tt=='TT1'){
                    echo '
                        <span id="spantt1"> <input type="radio" checked="true" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" name="tt" id="TT5">TT5</span';
                   }elseif ($tt=='TT2') {
                       echo '
                           <span id="spantt1"> <input type="radio" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" checked="true" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" name="tt" id="TT5">TT5</span';
                }else if($tt=='TT3'){
                    echo '<span id="spantt1"> <input type="radio" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" checked="true" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" name="tt" id="TT5">TT5</span';

                }else if($tt=='TT4'){
                    echo '<span id="spantt1"> <input type="radio" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" checked="true" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" name="tt" id="TT5">TT5</span';
                }else if($tt=='TT5'){
                    echo '<span id="spantt1"> <input type="radio" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" checked="true" name="tt" id="TT5">TT5</span';
                }else{
                    echo '<span id="spantt1"> <input type="radio" name="tt" id="TT1">TT1</span>
                        <span id="spantt2"> <input type="radio" name="tt" id="TT2">TT2</span>
                        <span id="spantt3"> <input type="radio" name="tt" id="TT3">TT3</span>
                        <span id="spantt4"> <input type="radio" name="tt" id="TT4">TT4</span>
                        <span id="spantt5"> <input type="radio" name="tt" id="TT5">TT5</span';
                }

                ?>

            </td>
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                DATE
            </td>
            <td>
                <input name="name" id="urine" type="text" value="<?php echo $today;?>">
            </td>
            <td  colspan="" align="right" style="text-align:right;">
               EXAM BY
            </td>
            <td>
                <input type="text" id="ppr" value="<?php echo $name;?>">
            </td>
        </tr>

        <tr>
            <td>
                <input type="button" id="savehistorybtn" class="art-button-green" value="Save">
                <input type="hidden" id="patient_ID_here" value="<?php echo $_GET['pn']; ?>">
            </td>

            <td>
                <input type="button" id="print_historybtn" class="art-button-green" value="Print">
            </td>
        </tr>
    </table>
</div>

<!--Deliver notes dialog-->
<div id="deliveryDialog" style="display:none">
    <?php

    $selectQ = mysqli_query($conn,"SELECT * FROM tbl_delivery_information WHERE Patient_ID='$pn'");
    $result=  mysqli_fetch_assoc($selectQ);
    $delivery_Date=$result['delivery_Date'];
    $Stage_1=$result['Stage_1'];
    $delivery_methode=$result['delivery_methode'];
    $artificial_reason=$result['artificial_reason'];
    $Stage_2=$result['Stage_2'];
    $placenta_removed=$result['placenta_removed'];
    $stage3=$result['Stage_3'];
    $completely_removed=$result['completely_removed'];
    $Remarks=$result['Remarks'];
    $Blood_lost=$result['Blood_lost'];
    $Ergometrine=$result['Ergometrine'];
    $Perineum=$result['Perineum'];
    $Baby_sex=$result['Baby_sex'];
    $Bp_after_delivery=$result['Bp_after_delivery'];
    $Baby_weight=$result['Baby_weight'];
    $Apgar_1=$result['Apgar_1'];
    $Apgar_5=$result['Apgar_5'];
    $ARV=$result['ARV'];
    $Risk=$result['Risk'];
    $BCG=$result['BCG'];
    $Polio=$result['Polio'];
    $Delivered_By=$result['Delivered_By'];
    $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='$Delivered_By'");
    $row= mysqli_fetch_assoc($employee);
    $name=$row['Employee_Name'];
    $title=$row['Employee_Title'];
    ?>
    <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Delivery Date &amp; Time:
                        </td>
                        <td width="40%">
                            <input  type="text" id="delivery_date" value="<?php echo $delivery_Date;?>">
                        </td>
                        <td  style="text-align:right;" width="20%">Summary:First Stage 1 Time:</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;" id="stage1" type="text" value="<?php echo $Stage_1;?>">

                        </td>
                    </tr>


                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Method of delivery:
                        </td>
                        <td >
                            <?php
                             if($delivery_methode=='Ruptured Point'){
                             echo '<span id="spanrapturedpoint">  <input type="radio" checked="true" id="rapturedpoint" name="membrane">Membrane ruptured point</span>
                             <span id="spanartificialrapture"><input type="radio" id="artificialrapture" name="membrane">Membrane artificial rupture</span><br /><br />
                             <input type="text" id="artreason" placeholder="reason of artificial rupture" style="display:none">
                             ';
                             } elseif ($delivery_methode=='Articial rupture') {
                              echo '<span id="spanrapturedpoint">  <input type="radio" id="rapturedpoint" name="membrane">Membrane ruptured point</span>
                              <span id="spanartificialrapture"><input type="radio" id="artificialrapture" checked="true" name="membrane">Membrane artificial rupture</span><br /><br />
                              <input type="text" id="artreason" placeholder="reason of artificial rupture" value="'.$artificial_reason.'">
                              ';

                            }else{
                            echo '<span id="spanrapturedpoint">  <input type="radio" id="rapturedpoint" name="membrane">Membrane ruptured point</span>
                            <span id="spanartificialrapture"><input type="radio" id="artificialrapture" name="membrane">Membrane artificial rupture</span><br /><br />
                            <input type="text" id="artreason" placeholder="reason of artificial rupture" style="display:none" value="'.$artificial_reason.'">
                            ';
                            }
                            ?>
                       </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Second stage 2 Time&amp;Min
                        </td>
                        <td>
                            <input name="" id="stage2"  type="text" value="<?php echo $Stage_2;?>">
                        </td>
                    </tr>


                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Placenta removed:Date and Time
                        </td>
                        <td>
                            <input name="name" id="placenta_removed" type="text" value="<?php echo $placenta_removed;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Third stage 3 Time&amp;Min
                        </td>
                        <td>
                            <input type="text" id="stage3" value="<?php echo $stage3;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Placenta and membrane completely removed
                        </td>
                        <td>
                            <?php

                            if($completely_removed=='Yes'){
                               echo '<span id="spanyesremoved"> <input type="radio" checked="true" name="removed" id="yesremoved">YES </span>
                                     <span id="spannotremoved"> <input type="radio" name="removed" id="notremoved">NO </span>
                                ';

                            }else if($completely_removed=='No'){
                                echo '<span id="spanyesremoved"> <input type="radio" name="removed" id="yesremoved">YES </span>
                                     <span id="spannotremoved"> <input type="radio" checked="true" name="removed" id="notremoved">NO </span>
                                ';

                            }  else {
                                echo '<span id="spanyesremoved"> <input type="radio" name="removed" id="yesremoved">YES </span>
                                     <span id="spannotremoved"> <input type="radio" name="removed" id="notremoved">NO </span>
                                ';
                            }
                            ?>
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Remarks
                        </td>
                        <td>
                            <input type="text" id="remarks_info" value="<?php echo $Remarks;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Blood lost
                        </td>
                        <td>
                            <input name="name" id="blood_loss" type="text" value="<?php echo $Blood_lost;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Ergometrine/Syntometrine/Oxtocin
                        </td>
                        <td>
                            <input type="text" id="oxctocin" value="<?php echo $Ergometrine;?>">
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Perineum
                        </td>
                        <td>
                            <?php
                            if($Perineum=='Intact'){
                              echo '
                                    <span id="spanIntact"> <input type="radio" checked="true" name="perineum" id="Intact">Intact</span>
                                     <span id="spanTear"> <input type="radio" name="perineum" id="Tear">Tear</span>
                                     <span id="spanEpisiotomy"> <input type="radio" name="perineum" id="Episiotomy">Episiotomy</span>
                                 ';
                            }elseif ($Perineum=='Tear') {
                                echo '
                                    <span id="spanIntact"> <input type="radio" name="perineum" id="Intact">Intact</span>
                                     <span id="spanTear"> <input type="radio" checked="true" name="perineum" id="Tear">Tear</span>
                                     <span id="spanEpisiotomy"> <input type="radio" name="perineum" id="Episiotomy">Episiotomy</span>
                                 ';
                            }elseif ($Perineum=='Episiotomy') {
                                echo '
                                    <span id="spanIntact"> <input type="radio" name="perineum" id="Intact">Intact</span>
                                     <span id="spanTear"> <input type="radio" name="perineum" id="Tear">Tear</span>
                                     <span id="spanEpisiotomy"> <input type="radio" checked="true" name="perineum" id="Episiotomy">Episiotomy</span>
                                 ';
                            }else{
                                echo '
                                    <span id="spanIntact"> <input type="radio" name="perineum" id="Intact">Intact</span>
                                     <span id="spanTear"> <input type="radio" name="perineum" id="Tear">Tear</span>
                                     <span id="spanEpisiotomy"> <input type="radio" name="perineum" id="Episiotomy">Episiotomy</span>
                                 ';
                            }

                            ?>
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Baby Sex
                        </td>
                        <td>
                            <?php
                             if($Baby_sex=='Male'){
                                 echo '
                                     <span id="spanMale"> <input type="radio" checked="true" name="alipojifungulia" id="babyMale">Male</span>
                                     <span id="spanFemale"> <input type="radio" name="alipojifungulia" id="babyFemale">Female</span>
                                    ';


                             }elseif($Baby_sex=='Female'){

                                 echo '
                                     <span id="spanMale"> <input type="radio" name="alipojifungulia" id="babyMale">Male</span>
                                     <span id="spanFemale"> <input type="radio" checked="true" name="alipojifungulia" id="babyFemale">Female</span>
                             ';
                             }  else {
                            echo '
                                <span id="spanMale"> <input type="radio" name="alipojifungulia" id="babyMale">Male</span>
                                <span id="spanFemale"> <input type="radio" name="alipojifungulia" id="babyFemale">Female</span>
                             ';
                             }

                            ?>
                            </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            BP after delivery(MmHg)
                        </td>
                        <td>
                            <input type="text" id="bp_info" value="<?php echo $Bp_after_delivery;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Weight(Kgs)
                        </td>
                        <td>
                            <input type="text" id="weight" value="<?php echo $Baby_weight;?>">
                         </td>
                    </tr>


                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Apgarscore after 1 minute:
                        </td>
                        <td>
                            <input type="text" id="apgar1" value="<?php echo $Apgar_1;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Apgarscore after 5 minutes
                        </td>
                        <td>
                            <input type="text" id="apgar5" value="<?php echo $Apgar_5;?>">
                         </td>
                    </tr>

                     <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Given ARV if mother is PMTCTI
                        </td>
                        <td>
                            <?php
                            if($ARV=='Yes'){
                                echo '
                                    <span id="spanARVyes"> <input type="radio" checked="true" name="pmtct" id="pmtctyes">Yes</span>
                                     <span id="spanARVno"> <input type="radio" name="pmtct" id="pmtctno">No </span>
                                    ';
                            }elseif($ARV=='No'){
                                echo '
                                    <span id="spanARVyes"> <input type="radio" name="pmtct" id="pmtctyes">Yes</span>
                                    <span id="spanARVno"> <input type="radio" checked="true" name="pmtct" id="pmtctno">No </span>
                                    ';
                            }else{
                                echo '
                                    <span id="spanARVyes"> <input type="radio" name="pmtct" id="pmtctyes">Yes</span>
                                    <span id="spanARVno"> <input type="radio" name="pmtct" id="pmtctno">No </span>
                                    ';
                            }
                            ?>
                         </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Put tick where appropriate
                        </td>
                        <td>
                            <?php
                            if($Risk=='belowWeight'){
                                echo '<span id="spanbelow"> <input type="radio" name="assessment" checked="true" id="below">Bring to hospital weight below 25kg </span> <br />
                            <span id="spantemp"> <input type="radio" name="assessment" id="temp">Temperature more than 38c</span> <br />
                            <span id="spansuck"> <input type="radio" name="assessment" id="suck">Baby unable to suck</span><br />
                            <span id="spanapgar"> <input type="radio" name="assessment" id="apgar">Apgarscore is below 5 after 5min</span> <br />
                                ';
                            }elseif($Risk=='higherTemperature'){
                                echo ' <span id="spanbelow"> <input type="radio" name="assessment" id="below">Bring to hospital weight below 25kg </span> <br />
                            <span id="spantemp"> <input type="radio" name="assessment" checked="true" id="temp">Temperature more than 38c</span> <br />
                            <span id="spansuck"> <input type="radio" name="assessment" id="suck">Baby unable to suck</span><br />
                            <span id="spanapgar"> <input type="radio" name="assessment" id="apgar">Apgarscore is below 5 after 5min</span> <br />
                                    ';

                            }elseif ($Risk=='unableSuck') {
                                echo '<span id="spanbelow"> <input type="radio" name="assessment" id="below">Bring to hospital weight below 25kg </span> <br />
                            <span id="spantemp"> <input type="radio" name="assessment" id="temp">Temperature more than 38c</span> <br />
                            <span id="spansuck"> <input type="radio" name="assessment" checked="true" id="suck">Baby unable to suck</span><br />
                            <span id="spanapgar"> <input type="radio" name="assessment" id="apgar">Apgarscore is below 5 after 5min</span> <br />
                                  ';
                            }elseif ($Risk=='belowApgar') {
                                echo '
                                    <span id="spanbelow"> <input type="radio" name="assessment" id="below">Bring to hospital weight below 25kg </span> <br />
                            <span id="spantemp"> <input type="radio" name="assessment" id="temp">Temperature more than 38c</span> <br />
                            <span id="spansuck"> <input type="radio" name="assessment" id="suck">Baby unable to suck</span><br />
                            <span id="spanapgar"> <input type="radio" name="assessment" checked="true" id="apgar">Apgarscore is below 5 after 5min</span> <br />
                                ';



                            }  else {
                                echo '
                                  <span id="spanbelow"> <input type="radio" name="assessment" id="below">Bring to hospital weight below 25kg </span> <br />
                                 <span id="spantemp"> <input type="radio" name="assessment" id="temp">Temperature more than 38c</span> <br />
                                 <span id="spansuck"> <input type="radio" name="assessment" id="suck">Baby unable to suck</span><br />
                                 <span id="spanapgar"> <input type="radio" name="assessment" id="apgar">Apgarscore is below 5 after 5min</span> <br />
                                ';
                            }

                            ?>
                         </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            BCG Vaccine
                        </td>
                        <td>

                            <?php
                            if($BCG=='Yes'){
                                echo '
                                    <span id="spanbcgyes">  <input type="radio" checked="true" name="bcg" id="bcgyes">Yes</span>
                                   <span id="spanbcgno"> <input type="radio" name="bcg" id="bcgno">No</span>
                                    ';

                            }elseif($BCG=='No'){
                                echo ' <span id="spanbcgyes">  <input type="radio" name="bcg" id="bcgyes">Yes</span>
                                     <span id="spanbcgno"> <input type="radio" checked="true" name="bcg" id="bcgno">No</span>
                                    ';
                            }else{
                                echo '
                                    <span id="spanbcgyes">  <input type="radio" name="bcg" id="bcgyes">Yes</span>
                                    <span id="spanbcgno"> <input type="radio" name="bcg" id="bcgno">No</span>
                                   ';
                            }
                            ?>
                         </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Polio "O"
                        </td>
                        <td>
                            <?php
                            if($Polio=='Yes'){
                                echo '<span id="spanpolioyes">  <input type="radio" checked="true" name="polio" id="polioyes">Yes</span>
                                     <span id="spanpoliono"> <input type="radio" name="polio" id="poliono">No</span>
                                    ';

                            }elseif($Polio=='No'){
                                echo ' <span id="spanpolioyes">  <input type="radio" name="polio" id="polioyes">Yes</span>
                                       <span id="spanpoliono"> <input type="radio" checked="true" name="polio" id="poliono">No</span>
                                    ';
                            }else{
                                echo ' <span id="spanpolioyes">  <input type="radio" name="polio" id="polioyes">Yes</span>
                                      <span id="spanpoliono"> <input type="radio" name="polio" id="poliono">No</span>
                                ';
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Prepared By
                        </td>
                        <td>
                            <input type="text" readonly="true" id="prepared" value="<?php echo $name;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                           Designation
                        </td>
                        <td>
                            <input type="text" readonly="true" id="designtion" value="<?php echo $title;?>">
                         </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" id="savebtn" class="art-button-green" value="Save">
                            <input type="hidden" id="patient_ID_info" value="<?php echo $_GET['pn'];?>">
                        </td>

                        <td>
                            <input type="button" id="print_btn" class="art-button-green" value="Print">
                        </td>
                    </tr>

 </table>



</div>


<!--Delivery Chart dialog-->
<div id="chartDialog" style="display:none;">

    <table style="width:100%;border:0" id="displayhere">


        <tr style="text-weight:bold">
            <td>
                <b> DATE </b>
            </td>

            <td>
               <b> WT </b>
            </td>

            <td>
               <b> B.P </b>
            </td>

            <td>
               <b> URINE ALB </b>
            </td>
            <td>
                <b>URINE SUG</b>
            </td>

            <td>
               <b> UT FH </b>
            </td>

            <td>
              <b>  POS </b>
            </td>

            <td>
              <b>  FH </b>
            </td>

            <td>
               <b> OEDEMA </b>
            </td>
            <td>
              <b>  HB </b>
            </td>

            <td>
               <b> GA </b>
            </td>

            <td>
               <b> REMARKS </b>
            </td>
            <td>
               <b> SIGN </b>
            </td>
        </tr>
         <?php
                $select=mysqli_query($conn,"SELECT * FROM tbl_deliverychart WHERE Patient_ID='$pn'");
                while ($row=mysqli_fetch_assoc($select)){
                echo '<tr><td>'.$row['Attend_Date'].'</td>';
                echo '<td>'.$row['wt'].'</td>';
                echo '<td>'.$row['bp'].'</td>';
                echo '<td>'.$row['urine_alb'].'</td>';
                echo '<td>'.$row['urine_sug'].'</td>';
                echo '<td>'.$row['ut_fh'].'</td>';
                echo '<td>'.$row['pos'].'</td>';
                echo '<td>'.$row['fh'].'</td>';
                echo '<td>'.$row['oedema'].'</td>';
                echo '<td>'.$row['hb'].'</td>';
                echo '<td>'.$row['ga'].'</td>';
                echo '<td>'.$row['remarks'].'</td>';
                $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='".$row['Employee']."'");
                $row2= mysqli_fetch_assoc($employee);
                $name=$row2['Employee_Name'];
                echo '<td>'.$name.'</td></tr>';
            }

        ?>


        <tr>
            <td>
                <input type="text" value="<?php echo Date('Y-m-d');?>" readonly="true" id="todaydate">
            </td>
            <td>
             <input type="text" id="wt">
            </td>
            <td>
                <input type="text" id="bp">
            </td>

            <td>
                <input type="text" id="alb">
            </td>

            <td>
                <input type="text" id="sug">
            </td>

            <td>
                <input type="text" id="ut">
            </td>

            <td>
                <input type="text" id="pos">
            </td>

            <td>
                <input type="text" id="fh">
            </td>

            <td>
                <input type="text" id="oedema">
            </td>

            <td>
                <input type="text" id="hb">
            </td>

            <td>
                <input type="text" id="ga">
            </td>

            <td>
                <textarea style="height:15px;width:100px" id="remarks">

                </textarea>
            </td>

            <td>
                <input type="text" id="name">
            </td>
        </tr>

        <tr>
            <center>
                <td>
                    <input type="button" value="Save" id="savedata" class="art-button-green">
                </td>
                <td>
                    <input type="button" value="Print" id="print_data" class="art-button-green">
                </td>
             </center>
        </tr>


    </table>
</div>

<?php
include("./includes/footer.php");
?>

<link href="css/jquery-ui.css" rel="stylesheet"></link>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>


<script>
   $(".tabcontents").tabs();
   $('#birth_date,#first_Date').datepicker({
       dateFormat: 'yy-mm-dd',
       changeMonth: true,
       changeYear: true
   });


    $('#placenta_removed,#delivery_date').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now',
//        dateFormat: 'yy-mm-dd',
        });

   $('#history').click(function (e) {
       e.preventDefault();
       $('#wazazidialog').dialog({
           modal: true,
           width: '90%',
           minHeight: 450,
           resizable: true,
           draggable: true,
       });

   });

    $('#delivery').click(function(e){
          e.preventDefault();
          $('#deliveryDialog').dialog({
           modal: true,
           width: '90%',
           minHeight: 450,
           resizable: true,
           draggable: true,

          });
       });


       $('#deliverChart').click(function(e){
          e.preventDefault();
          $('#chartDialog').dialog({
           modal: true,
           width: '90%',
           minHeight: 450,
           resizable: true,
           draggable: true,

          });
       });



   $('#spanmtotohai').on('click', function () {
       $('#mtotohai').prop('checked', true);
   });

   $('#spanmtotokufa').on('click', function () {
       $('#mtotokufa').prop('checked', true);
   });


   $('#spanmamahai').on('click', function () {
       $('#mamahai').prop('checked', true);
   });
   $('#spanmamakufa').on('click', function () {
       $('#mamakufa').prop('checked', true);
   });


   $('#spanRF').on('click', function () {
       $('#RF').prop('checked', true);
   });
   $('#spanEBF').on('click', function () {
       $('#EBF').prop('checked', true);
   });
   $('#spanmtotoARVhapana').on('click', function () {
       $('#mtotoARVhapana').prop('checked', true);
   });
   $('#spanmtotoARVpewa').on('click', function () {
       $('#mtotoARVpewa').prop('checked', true);
   });

   $('#spankipimoVVUjuli').on('click', function () {
       $('#kipimoVVUjuli').prop('checked', true);
   });
   $('#spankipimoVVUnegat').on('click', function () {
       $('#kipimoVVUnegat').prop('checked', true);
   });

   $('#spankipimoVVUpos').on('click', function () {
       $('#kipimoVVUpos').prop('checked', true);
   });
   $('#spanVVUjuli').on('click', function () {
       $('#VVUjuli').prop('checked', true);
   });
   $('#spanVVUnegat').on('click', function () {
       $('#VVUnegat').prop('checked', true);
   });
   $('#spanVVUpos').on('click', function () {
       $('#VVUpos').prop('checked', true);
   });
   $('#spanMVAhapana').on('click', function () {
       $('#MVAhapana').prop('checked', true);
   });
   $('#spanMVAndiyo').on('click', function () {
       $('#MVAndiyo').prop('checked', true);
   });

   $('#spanFGMhapana').on('click', function () {
       $('#FGMhapana').prop('checked', true);
   });
   $('#spanFGMndiyo').on('click', function () {
       $('#FGMndiyo').prop('checked', true);
   });

   $('#spandamuhapana').on('click', function () {
       $('#damuhapana').prop('checked', true);
   });
   $('#spandamundiyo').on('click', function () {
       $('#damundiyo').prop('checked', true);
   });

   $('#spansulhapana').on('click', function () {
       $('#sulhapana').prop('checked', true);
   });
   $('#spansulndiyo').on('click', function () {
       $('#sulndiyo').prop('checked', true);
   });
   $('#spanmisohapana').on('click', function () {
       $('#misohapana').prop('checked', true)
   });

   $('#spanmisondiyo').on('click', function () {
       $('#misondiyo').prop('checked', true);
   });

   $('#spanantihapana').on('click', function () {
       $('#antihapana').prop('checked', true);
   });

   $('#spanantindiyo').on('click', function () {
       $('#antindiyo').prop('checked', true);
   });

   $('#spannyonyahapana').on('click', function () {
       $('#nyonyahapana').prop('checked', true);
   });
   $('#spannyonyandiyo').on('click', function () {
       $('#nyonyandiyo').prop('checked', true);
   });

   $('#spanKN').on('click', function () {
       $('#KN').prop('checked', true);
   });
   $('#spanMK').on('click', function () {
       $('#MK').prop('checked', true);
   });
   $('#spanRP').on('click', function () {
       $('#RP').prop('checked', true);
   });
   $('#spantear').on('click', function () {
       $('#tear').prop('checked', true);
   });
   $('#spanOL').on('click', function () {
       $('#OL').prop('checked', true);
   });

   $('#spanecla').on('click', function () {
       $('#ecla').prop('checked', true);
   });
   $('#spanpree').on('click', function () {
       $('#pree').prop('checked', true);
   });
   $('#spanPPH').on('click', function () {
       $('#PPH').prop('checked', true);
   });


   $('#spanAP').on('click', function () {
       $('#AP').prop('checked', true);
   });
   $('#spanPROM').on('click', function () {
       $('#PROM').prop('checked', true);
   });
   $('#spanA').on('click', function () {
       $('#A').prop('checked', true);
   });
   $('#spanPE').on('click', function () {
       $('#PE').prop('checked', true);
   });

   $('#spanE').on('click', function () {
       $('#E').prop('checked', true);
   });

   $('#spanSepsis').on('click', function () {
       $('#sepsis').prop('checked', true);
   });

   $('#spanMalaria').on('click', function () {
       $('#Malaria').prop('checked', true);
   });

   $('#spanHIV').on('click', function () {
       $('#HIV').prop('checked', true);
   });

   $('#spanFGM').on('click', function () {
       $('#FGM').prop('checked', true);
   });


   $('#spantathminndiyo').on('click', function () {
       $('#tahminndiyo').prop('checked', true);
   });

   $('#spantathminhapana').on('click', function () {
       $('#tathminhapana').prop('checked', true);
   });

   $('#spanFSB').on('click', function () {
       $('#FSB').prop('checked', true);
   });

   $('#spanMSB').on('click', function () {
       $('#MSB').prop('checked', true);
   })

   $('#spandk1').on('click', function () {
       $('#dk1').prop('checked', true);
   });

   $('#spandk5').on('click', function () {
       $('#dk5').prop('checked', true);
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

   $('#spanrapturedpoint').on('click',function(){
       $('#rapturedpoint').prop('checked',true);
       $('#artreason').hide();
   });

    $('#spanartificialrapture').on('click',function(){
        $('#artificialrapture').prop('checked',true);
        $('#artreason').show();
    });

    $('#spannotremoved').on('click',function(){
        $('#notremoved').prop('checked',true);
    });

    $('#spanyesremoved').on('click',function(){
        $('#yesremoved').prop('checked',true);
    });

    $('#spanIntact').on('click',function(){
        $('#Intact').prop('checked',true);
    });

    $('#spanTear').on('click',function(){
       $('#Tear').prop('checked',true);
    });

     $('#spanEpisiotomy').on('click',function(){
         $('#Episiotomy').prop('checked',true);
     });

     $('#spanMale').on('click',function(){
         $('#babyMale').prop('checked',true);
     });

     $('#spanFemale').on('click',function(){
         $('#babyFemale').prop('checked',true);
     });

     $('#spanARVyes').on('click',function(){
       $('#pmtctyes').prop('checked',true);
     });

     $('#spanARVno').on('click',function(){
       $('#pmtctno').prop('checked',true);
     });

     $('#spanbelow').on('click',function(){
        $('#below').prop('checked',true);
     });

     $('#spantemp').on('click',function(){
         $('#temp').prop('checked',true);
     });

     $('#spansuck').on('click',function(){
        $('#suck').prop('checked',true);

     });

     $('#spanapgar').on('click',function(){
         $('#apgar').prop('checked',true);
     });

     $('#spanbcgyes').on('click',function(){
        $('#bcgyes').prop('checked',true);
     });

     $('#spanbcgno').on('click',function(){
        $('#bcgno').prop('checked',true);
     });

     $('#spanpolioyes').on('click',function(){
         $('#polioyes').prop('checked',true);
     });

      $('#spanpoliono').on('click',function(){
         $('#poliono').prop('checked',true);
     });

     $('#spantt1').on('click',function(){
        $('#TT1').prop('checked',true);
     });

     $('#spantt2').on('click',function(){
        $('#TT2').prop('checked',true);
     });

     $('#spantt3').on('click',function(){
         $('#TT3').prop('checked',true);

     });

     $('#spantt4').on('click',function(){
         $('#TT4').prop('checked',true);
     });

     $('#spantt5').on('click',function(){
        $('#TT5').prop('checked',true);
     });

     $('#print_btn').click(function(){
         var patient_ID=$('#patient_ID').val();
         window.open('PrintDeliveryInfo.php?patient_ID=' + patient_ID + '');

     });


     $('#print_data').click(function(){
         var patient_ID=$('#patient_ID').val();
         window.open('PrintDeliveryChart.php?patient_ID=' + patient_ID + '');

     });

     $('#print_historybtn').click(function(){
       var patient_ID=$('#patient_ID').val();
       window.open('PrintDeliveryhistory.php?patient_ID=' + patient_ID + '');

     });

     $('#savehistorybtn').click(function(){
        var patient_ID=$('#patient_ID_here').val();
        var tt;
        var lmp=$('#lmp').val();
        var first_Date=$('#first_Date').val();
        var bp=$('#bp2').val();
        var oedema=$('#oedema2').val();
        var breast=$('#breast').val();
        var hb=$('#hb2').val();
        var blood_group=$('#blood_group').val();
        var lungs=$('#lungs').val();
        var rh=$('#rh').val();
        var abdomen=$('#abdomen').val();
        var pmtct0=$('#pmtct0').val();
        var pmtct1=$('#pmtct1').val();
        var pmtct2=$('#pmtct2').val();
        var urine=$('#urine').val();
        var ppr=$('#ppr').val();
        var remarks=$('#remarks_data').val();
        var edd=$('#edd').val();
        if($('#TT1').is(':checked')){
            tt='TT1';
        }else if($('#TT2').is(':checked')){
            tt='TT2';
        }else if($('#TT3').is(':checked')){

            tt='TT3';
        }else if($('#TT4').is(':checked')){
            tt='TT4';
        }else if($('#TT5').is(':checked')){
            tt='TT5';
        }

         $.ajax({
           type: 'POST',
           url: 'requests/save_antenatal_record.php',
           data: 'action=save&lmp='+lmp+'&edd='+edd+'&first_Date='+first_Date+'&bp='+bp+'&oedema='+oedema+'&breast='+breast+'&hb='+hb+'&blood_group='+blood_group+'&lungs='+lungs+'&rh='+rh+'&abdomen='+abdomen+'&pmtct0='+pmtct0+'&pmtct1='+pmtct1+'&pmtct2='+pmtct2+'&urine='+urine+'&ppr='+ppr+'&bp='+bp+'&remarks='+remarks+'&tt='+tt+'&patient_ID='+patient_ID,
           cache: false,
           success: function (html) {

               alert(html);
           }
       });



     });


    $('#savebtn').click(function(){
       var rapture;
       var membraneremoved;
       var perineum;
       var babySex;
       var risk;
       var BCG;
       var polio;
       var ARV;
       var patient_ID=$('#patient_ID_info').val();
       var delivery_date=$('#delivery_date').val();
       var artreason=$('#artreason').val();
       var stage1=$('#stage1').val();
       var stage2=$('#stage2').val();
       var placenta_removed=$('#placenta_removed').val();
       var stage3=$('#stage3').val();
       var remarks=$('#remarks_info').val();
       var blood_loss=$('#blood_loss').val();
       var oxctocin=$('#oxctocin').val();
       var bp=$('#bp_info').val();
       var weight=$('#weight').val();
       var apgar1=$('#apgar1').val();
       var apgar5=$('#apgar5').val();

       if($('#pmtctyes').is(':checked')){

           ARV='Yes'
       }else if($('#pmtctno').is(':checked')){
           ARV='No';
       }

       if($('#below').is(':checked')){
           risk='belowWeight';

       }else if($('#temp').is(':checked')){
           risk='higherTemperature';
       }else if($('#suck').is(':checked')){
           risk='unableSuck';
       }else if($('#apgar').is(':checked')){
           risk='belowApgar';
       }


       if($('#rapturedpoint').is(':checked')){
           rapture="Ruptured Point";

       }else if($('#artificialrapture').is(':checked')){
           rapture="Articial rupture";
       }

       if($('#notremoved').is(':checked')){
           membraneremoved="No";

       } else if($('#yesremoved').is(':checked')){
          membraneremoved="Yes";
       }

       if($('#Intact').is(':checked')){
           perineum="Intact";
       } else if($('#Tear').is(':checked')){
           perineum="Tear";

       }else if($('#Episiotomy').is(':checked')){
           perineum="Episiotomy";
       }

       if($('#babyMale').is(':checked')){
           babySex="Male";
       } else if($('#babyFemale').is(':checked')){
           babySex="Female";
       }

       if($('#bcgyes').is(':checked')){
           BCG='Yes';
       }else if($('#bcgno').is(':checked')){
          BCG='No';
       }

       if($('#polioyes').is(':checked')){
         polio='Yes';
       }else if($('#poliono').is(':checked')){
           polio='No';
       }

        $.ajax({
           type: 'POST',
           url: 'requests/save_delivery_info.php',
           data: 'action=save&rapture='+rapture+'&membraneremoved='+membraneremoved+'&perineum='+perineum+'&babySex='+babySex+'&risk='+risk+'&BCG='+BCG+'&polio='+polio+'&delivery_date='+delivery_date+'&stage1='+stage1+'&stage2='+stage2+'&placenta_removed='+placenta_removed+'&stage3='+stage3+'&remarks='+remarks+'&blood_loss='+blood_loss+'&oxctocin='+oxctocin+'&bp='+bp+'&weight='+weight+'&apgar1='+apgar1+'&apgar5='+apgar5+'&patient_ID='+patient_ID+'&artreason='+artreason+'&ARV='+ARV,
           cache: false,
           success: function (html) {

               alert(html);
//              $('#displayhere').html(html);
           }
       });

    });


    $('#savedata').click(function(){
      var todaydate=$('#todaydate').val();
      var wt=$('#wt').val();
      var bp=$('#bp').val();
      var alb=$('#alb').val();
      var sug=$('#sug').val();
      var ut=$('#ut').val();
      var pos=$('#pos').val();
      var fh=$('#fh').val();
      var oedema=$('#oedema').val();
      var hb=$('#hb').val();
      var ga=$('#ga').val();
      var remarks=$('#remarks').val();
      var sign=$('#sign').val();
      var patient_ID=$('#patient_ID_info').val();
       $.ajax({
           type: 'POST',
           url: 'requests/save_delivery_chart.php',
           data: 'action=save&todaydate='+todaydate+'&wt='+wt+'&bp='+bp+'&alb='+alb+'&sug='+sug+'&ut='+ut+'&pos='+pos+'&fh='+fh+'&oedema='+oedema+'&hb='+hb+'&ga='+ga+'&remarks='+remarks+'&sign='+sign+'&patient_ID='+patient_ID,
           cache: false,
           success: function (html) {
              $('#displayhere').html(html);
           }
       });

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
       var mtotoUzito = $('#mtotoUzito').val();
       var mama_hali_details = $('#mama_hali_details').val();
       var mama_discharge = $('#discharge_date_mama').val();
       var kifo_mama_sababu = $('#kifo_sababu_mama').val();
       var mtoto_hali_details = $('#mtoto_hali_details').val();
       var mtoto_discharge = $('#discharge_date_mtoto').val();
       var kifo_mtoto_sababu = $('#kifo_sababu_mtoto').val();
       var alikopelekwa = $('#alikopelekwa').val();
       var sababu_rufaa = $('#rufaa_sababu').val();
       var mzalishaji = $('#mzalishaji').val();
       var kada = $('#kada').val();
       var patient_ID = $('#patient_ID').val();
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
       if ($('#ndani12').is(':checked')) {
           uchungu = 'ndani12';
       } else if ($('#baada12').is(':checked')) {
           uchungu = 'baada12';
       }

       if ($('#H').is(':checked')) {
           jifungulia = 'H';
       } else if ($('#TBA').is(':checked')) {
           jifungulia = 'TBA';
       } else if ($('#BBA').is(':checked')) {
           jifungulia = 'BBA';
       } else if ($('#HF').is(':checked')) {
           jifungulia = 'HF';
       }

       if ($('#NY').is(':checked')) {
           kujifungua_njia = 'NY';
       } else if ($('#BR').is(':checked')) {
           kujifungua_njia = 'BR';
       } else if ($('#CS').is(':checked')) {
           kujifungua_njia = 'CS';
       } else if ($('#VM').is(':checked')) {
           kujifungua_njia = 'VM';
       } else if ($('#KW').is(':checked')) {
           kujifungua_njia = 'KW';
       }

       if ($('#me').is(':checked')) {
           mtoto_jinsi = 'me';
       } else if ($('#ke').is(':checked')) {
           mtoto_jinsi = 'ke';
       }

       if ($('#suction').is(':checked')) {
           kupumua = 'suction';
       } else if ($('#stimulation').is(':checked')) {
           kupumua = 'stimulation';
       } else if ($('#Mask').is(':checked')) {
           kupumua = 'Mask';
       } else if ($('#hapana').is(':checked')) {
           kupumua = 'hapana';
       }

       if ($('#dk1').is(':checked')) {
           apgar = '1';
       } else if ($('#dk5').is(':checked')) {
           apgar = '5';
       }

       if ($('#nyonyandiyo').is(':checked')) {
           nyonyeshwa = 'Ndiyo';
       } else if ($('#nyonyahapana').is(':checked')) {
           nyonyeshwa = 'Hapana';
       }

       if ($('#tahminndiyo').is(':checked')) {
           tathmin = 'Ndiyo';
       } else if ($('#tathminhapana').is(':checked')) {
           tathmin = 'Hapana';
       }
       if ($('#MSB').is(':checked')) {
           MSB = 'MSB';
       } else if ($('#FSB').is(':checked')) {
           MSB = 'FSB';
       }

       if ($('#AP').is(':checked')) {
           AP = 'AP';
       } else if ($('#PROM').is(':checked')) {
           AP = 'PROM';
       } else if ($('#A').is(':checked')) {
           AP = 'A';
       } else if ($('#PE').is(':checked')) {
           AP = 'PE';
       } else if ($('#sepsis').is(':checked')) {
           AP = 'sepsis';
       } else if ($('#E').is(':checked')) {
           AP = 'E';
       } else if ($('#Malaria').is(':checked')) {
           AP = 'Malaria';
       } else if ($('#HIV').is(':checked')) {
           AP = 'HIV';
       } else if ($('#FGM').is(':checked')) {
           AP = 'FGM';
       }

       if ($('#KN').is(':checked')) {
           PPH = 'KN';
       } else if ($('#MK').is(':checked')) {
           PPH = 'MK';
       } else if ($('#RP').is(':checked')) {
           PPH = 'RP';
       } else if ($('#tear').is(':checked')) {
           PPH = 'tear';
       } else if ($('#OL').is(':checked')) {
           PPH = 'OL';
       } else if ($('#ecla').is(':checked')) {
           PPH = 'ecla';
       } else if ($('#pree').is(':checked')) {
           PPH = 'pree';
       } else if ($('#PPH').is(':checked')) {
           PPH = 'PPH';
       }

       if ($('#antindiyo').is(':checked')) {
           antibiotic = 'Ndiyo';
       } else if ($('#antihapana').is(':checked')) {
           antibiotic = 'Hapana';
       }

       if ($('#misohapana').is(':checked')) {
           miso = 'Hapana';
       } else if ($('#misondiyo').is(':checked')) {
           miso = 'Ndiyo';
       }

       if ($('#sulndiyo').is(':checked')) {
           sulfate = 'Ndiyo';
       } else if ($('#sulhapana').is(':checked')) {
           sulfate = 'Hapana';
       }

       if ($('#MVAhapana').is(':checked')) {
           MVA = 'Hapana';
       } else if ($('#MVAndiyo').is(':checked')) {
           MVA = 'Ndiyo';
       }

       if ($('#damuhapana').is(':checked')) {
           ongeza_damu = 'Hapana';
       } else if ($('#damundiyo').is(':checked')) {
           ongeza_damu = 'Ndiyo';
       }

       if ($('#FGMhapana').is(':checked')) {
           FGM = 'Hapana';
       } else if ($('#FGMndiyo').is(':checked')) {
           FGM = 'Hapana';
       }

       if ($('#VVUpos').is(':checked')) {
           VVU_Kipimo = 'Positive';
       } else if ($('#VVUnegat').is(':checked')) {
           VVU_Kipimo = 'Negative';
       } else if ($('#VVUjuli').is(':checked')) {
           VVU_Kipimo = 'Haijulikani';
       }

       if ($('#kipimoVVUpos').is(':checked')) {
           VVU_uchungu = 'Positive';
       } else if ($('#kipimoVVUnegat').is(':checked')) {
           VVU_uchungu = 'Negative';
       } else if ($('#kipimoVVUjuli').is(':checked')) {
           VVU_uchungu = 'Haijulikani';
       }

       if ($('#mtotoARVpewa').is(':checked')) {
           ARV_mtoto = 'Amepewa';
       } else if ($('#mtotoARVhapana').is(':checked')) {
           ARV_mtoto = 'Hapana';
       }

       if ($('#EBF').is(':checked')) {
           mtoto_ulishaji = 'EBF';
       } else if ($('#RF').is(':checked')) {
           mtoto_ulishaji = 'RF';
       }

       if ($('#mamahai').is(':checked')) {
           mama_hali = 'hai';
       } else if ($('#mamakufa').is(':checked')) {
           mama_hali = 'kufa';
       }

       if ($('#mtotohai').is(':checked')) {
           mtoto_hali = 'hai';
       } else if ($('#mtotokufa').is(':checked')) {
           mtoto_hali = 'kufa';
       }

       $.ajax({
           type: 'POST',
           url: 'requests/save_wazazi.php',
           data: 'action=save&patient_ID= ' + patient_ID + '&jalada_no=' + jalada_no + '&rch_no=' + rch_no + '&kijiji_kitongoji=' + kijiji_kitongoji +'&gravida=' + gravida + '&para=' + para + '&watoto_hai=' + watoto_hai + '&admission_date=' + admission_date + '&kujifungua_trh=' + kujifungua_trh + '&mtotoUzito=' + mtotoUzito + '&uchungu=' + uchungu
                   + '&jifungulia=' + jifungulia + '&kujifungua_njia=' + kujifungua_njia + '&mtoto_jinsi=' + mtoto_jinsi + '&kupumua=' + kupumua + '&apgar=' + apgar + '&nyonyeshwa=' + nyonyeshwa + '&tathmin=' + tathmin + '&MSB=' + MSB + '&AP=' + AP + '&PPH=' + PPH + '&antibiotic=' + antibiotic
                   + '&miso=' + miso + '&sulfate=' + sulfate + '&MVA=' + MVA + '&ongeza_damu=' + ongeza_damu + '&FGM=' + FGM + '&VVU_Kipimo=' + VVU_Kipimo + '&VVU_uchungu=' + VVU_uchungu + '&ARV_mtoto=' + ARV_mtoto + '&mtoto_ulishaji=' + mtoto_ulishaji + '&mama_hali=' + mama_hali + '&mtoto_hali=' + mtoto_hali + '&mama_hali_details=' + mama_hali_details
                   + '&mama_discharge=' + mama_discharge + '&kifo_mama_sababu=' + kifo_mama_sababu + '&mtoto_hali_details=' + mtoto_hali_details + '&mtoto_discharge=' + mtoto_discharge + '&kifo_mtoto_sababu=' + kifo_mtoto_sababu + '&alikopelekwa=' + alikopelekwa + '&sababu_rufaa=' + sababu_rufaa + '&mzalishaji=' + mzalishaji+'&kada=' + kada,
           cache: false,
           success: function (html) {
               alert(html);
           }
       });
   });
</script>
