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
        echo "<a href='searchvisitorsfamilyplaningpatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<script>
    $(function () {
        $("#datepickery").datepicker();
    });
    $('#attend_btn').on('click',function(){
//       alert('view attendance');
    });
</script>


<?php
if (isset($_GET['pn'])) {

    $pn = $_GET['pn'];

    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
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
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA UZAZI WA MPANGO</b></legend>
    <div class="tabcontents" style="height:550px;overflow:auto" >


        <!--first step-->

        <div id="tabs-1">
            <center>
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Jina
                        </td>
                        <td width="40%">
                            <input  type="text" readonly="true" id="rchNo" name="tarehe1" value="<?php echo $name; ?>">

                        </td>
                        <td  style="text-align:right;" width="20%">Tarehe</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;" readonly="true" id="leo_Date" name="" type="text" value="<?php echo Date('Y-m-d'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jinsi</td><td ><input name="jinakamili" value="<?php echo $gende; ?>" type="text" readonly="true"></td>
                        <td  colspan="" align="right" style="text-align:right;">Umri</td><td>
                            <input name="" id="mtaa_jina" readonly="true" type="text" value="<?php echo $age; ?>" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Aina ya Mteja</td><td><input name="name" id="mteja_aina" type="text" value="<?php echo $mteja; ?>" readonly="true" </td>
                        <td  colspan="" align="right" style="text-align:right;">Amezaa mara ngapi</td><td>
                            <input name="baloz" id="para" type="text" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Mimba zilizoharibika</td>
                        <td>
                            <input type="text" id="abortions">
                        </td>

                        <td width="20%" class="powercharts_td_left" style="text-align:right">Watoto hai</td>
                        <td>
                            <input type="text" style="width:240px" id="watoto_hai">
                        </td>
                    </tr>

                </table>
<!------ hapa------->
                <table align="left" style="width:100%">
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Njia za uzazi wa mpango alizochagua katika hudhurio la kwanza</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Uchunguzi wa saratani(Matiti)</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Uchunguzi wa saratani(Shingo ya kizazi)</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Aina na saiko ya vidonge vya uzazi wa mpango
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanvidonge_saikoCOC"><input type="radio" id="vidonge_saikoCOC" name="vidonge_saiko">COC</span>
                                                <span class="pointer" id="spanvidonge_saikoPOP"><input type="radio" id="vidonge_saikoPOP" name="vidonge_saiko">POP</span>
                                            </td>
                                            <td>
                                                <input type="text" placeholder="kiasi" id="idadi_vidonge">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td >
                                                Njia ya uzazi wa mpango
                                            </td>
                                            <td>
                                                <select style="width:130px;" class="nn" id="njia_ya_uzazi">
                                                    <option value=""></option>
                                                    <option value="Sindano">
                                                        Sindano
                                                    </option>
                                                    <option value="Kuweka Vipandikizi">
                                                        Kuweka Vipandikizi
                                                    </option>

                                                    <option value="Kuweka Kitanzi">
                                                        Kuweka Kitanzi
                                                    </option>

                                                    <option value="Kondomu">
                                                        Kondomu (Pcs)
                                                    </option>

                                                    <option value="Kufunga Kizazi (Me)">
                                                        Kufunga Kizazi (Me)
                                                    </option>

                                                    <option value="Kufunga Kizazi (Ke)">
                                                        Kufunga Kizazi (Ke)
                                                    </option>

                                                    <option value="Njia nyingine">
                                                        Njia nyingine
                                                    </option>
                                                </select>
                                            </td>


                                        </tr>
                                        <tr id="condom" style="display:none">
                                            <td>
                                                Idadi ya kondom
                                            </td>
                                            <td>
                                                <select id="select_Condom" style="width:130px;">
                                                    <option value="">--select--</option>
                                                    <option value="Kondom(Ke)">Kondom(Ke)</option>
                                                    <option value="Kondom(Me)">Kondom(Me)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input id="idadi_condom" type="text" placeholder="Idadi ya kondom">
                                            </td>
                                        </tr>

                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Uchunguzi</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="radio" id="uchunguzi_titiN" name="uchunguzi_titi">Ndiyo</span>
                                                <span class="pointer" id="spanuchunguzi_titiH"><input type="radio" id="uchunguzi_titiH" name="uchunguzi_titi">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Buje
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanBujeN"><input type="radio" id="BujeN" name="Buje">Ndiyo</span>
                                                <span class="pointer" id="spanBujeH"><input type="radio" id="BujeH" name="Buje">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kidonda
                                            </td>
                                            <td>

                                                <span class="pointer" id="spankidondaN"><input type="radio" id="kidondaN" name="kidonda">Ndiyo</span>
                                                <span class="pointer" id="spankidondaH"><input type="radio" id="kidondaH" name="kidonda">Hapana</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Chuchu kutoka damu
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN"><input type="radio" id="chuchu_damuN" name="chuchu_damu">Ndiyo</span>
                                                <span class="pointer" id="spanchuchu_damuH"><input type="radio" id="chuchu_damuH" name="chuchu_damu">Hapana</span>


                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Jipu katika chuchu
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanjipu_chuchuN"><input type="radio" id="jipu_chuchuN" name="jipu_chuchu">Ndiyo</span>
                                                <span class="pointer" id="spanjipu_chuchuH"><input type="radio" id="jipu_chuchuH" name="jipu_chuchu">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mengineyo
                                            </td>
                                            <td>

                                                <span class="pointer" id="spantiti_mengineN"><input type="radio" id="titi_mengineN" name="titi_mengine">Ndiyo</span>
                                                <span class="pointer" id="spantiti_mengineH"><input type="radio" id="titi_mengineH" name="titi_mengine">Hapana</span>

                                            </td>
                                        </tr>


                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Uchunguzi</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_sarataniN"><input type="radio" id="uchunguzi_sarataniN" name="uchunguzi_saratani">Ndiyo</span>
                                                <span class="pointer" id="spanuchunguzi_sarataniH"><input type="radio" id="uchunguzi_sarataniH" name="uchunguzi_saratani">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kutoka uchafu ukeni
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanuchafu_ukeniN"><input type="radio" id="uchafu_ukeniN" name="uchafu_ukeni">Ndiyo</span>
                                                <span class="pointer" id="spanuchafu_ukeniH"><input type="radio" id="uchafu_ukeniH" name="uchafu_ukeni">Hapana</span>


                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Uvimbe kwenye shingo ya kizazi
                                            </td>
                                            <td>

                                                <span class="pointer" id="spankizazi_uvimbeN"><input type="radio" id="kizazi_uvimbeN" name="kizazi_uvimbe">Ndiyo</span>
                                                <span class="pointer" id="spankizazi_uvimbeH"><input type="radio" id="kizazi_uvimbeH" name="kizazi_uvimbe">Hapana</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mchubuko kwenye shingo ya kizazi
                                            </td>
                                            <td>

                                                <span class="pointer" id="spankizazi_mchubukoN"><input type="radio" id="kizazi_mchubukoN" name="kizazi_mchubuko">Ndiyo</span>
                                                <span class="pointer" id="spankizazi_mchubukoH"><input type="radio" id="kizazi_mchubukoH" name="kizazi_mchubuko">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kutokwa damu ukeni kiurahisi
                                            </td>
                                            <td>

                                                <span class="pointer" id="spandamu_ukeniN"><input type="radio" id="damu_ukeniN" name="damu_ukeni">Ndiyo</span>
                                                <span class="pointer" id="spandamu_ukeniH"><input type="radio" id="damu_ukeniH" name="damu_ukeni">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Uwezekano wa saratani(VIA)
                                            </td>
                                            <td>


                                                <span class="pointer" id="spanviaN"><input type="radio" id="viaN" name="via">Ndiyo</span>
                                                <span class="pointer" id="spanviaH"><input type="radio" id="viaH" name="via">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mengineyo
                                            </td>
                                            <td>
                                                <span class="pointer" id="spansaratani_mengineyoN"><input type="radio" id="saratani_mengineyoN" name="saratani_mengineyo">Ndiyo</span>
                                                <span class="pointer" id="spansaratani_mengineyoH"><input type="radio" id="saratani_mengineyoH" name="saratani_mengineyo">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Waliopewa huduma ya Cryotherapy
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanCryotherapyN"><input type="radio" id="CryotherapyN" name="Cryotherapy">Ndiyo</span>
                                                <span class="pointer" id="spanCryotherapyH"><input type="radio" id="CryotherapyH" name="Cryotherapy">Hapana</span>

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
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Huduma ya Uzazi wa Mpango baada ya mimba kuharibika(cPAC) au Baada ya kujifungua</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Njia za uzazi wa mpango katika hudhurio la marudio</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Taarifa kuhusu kipimo cha VVU</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Mimba Imeharibika/Baada ya kujifungua
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanbaada_ya_mimbaN"><input type="radio" id="baada_ya_mimbaN" name="baada_ya_mimba">Mimba Kuharibika</span><br />
                                                <span class="pointer" id="spanbaada_ya_mimbaH"><input type="radio" id="baada_ya_mimbaH" name="baada_ya_mimba">Kujifungua</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Njia ya uzazi baada ya Matibabu/aliyochagua ndani ya siku 42
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanVidonge"><input type="radio" id="Vidonge" name="matibabu_aliyochagua">Vidonge</span><br />
                                                <span class="pointer" id="spanSindano"><input type="radio" id="Sindano" name="matibabu_aliyochagua">Sindano</span><br />
                                                <span class="pointer" id="spanVipandikizi"><input type="radio" id="Vipandikizi" name="matibabu_aliyochagua">Vipandikizi</span><br />
                                                <span class="pointer" id="spanKitanzi"><input type="radio" id="Kitanzi" name="matibabu_aliyochagua">Kitanzi</span><br />
                                                <span class="pointer" id="spanKufunga"><input type="radio" id="Kufunga" name="matibabu_aliyochagua">Kufunga</span><br />
                                                <span class="pointer" id="spanKondom"><input type="radio" id="Kondom" name="matibabu_aliyochagua">Kondom</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Kuondoa
                                            </td>

                                            <td>

                                                <span class="pointer" id="spanKuondoaN"><input type="radio" id="KuondoaN" name="Kuondoa">Kitanzi</span><br />
                                                <span class="pointer" id="spanKuondoaH"><input type="radio" id="KuondoaH" name="Kuondoa">Kipandikizi</span>

                                            </td>
                                        </tr>
                                    </table>
                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Jan</td>
                                            <td width="25%">
                                                <select style="width:200px;" id="marudio_Jan">
                                                    <option value=""></option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Feb
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Feb" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Machi
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Machi" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                April
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_April" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mei
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Mei" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Jun
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Jun" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Jul
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Jul" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Ago
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Ago" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Sept
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Sept" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Oct
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Oct" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Nov
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Nov" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Des
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="marudio_Des" >
                                                    <option value="">

                                                    </option>
                                                    <option value="KO">KO</option>
                                                    <option value="VD">VD</option>
                                                    <option value="S">S</option>
                                                    <option value="VP">VP</option>
                                                    <option value="KT">KT</option>
                                                    <option value="BTL">BTL</option>
                                                    <option value="VAC">VAC</option>
                                                </select>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Tayari ana maambukizi ya VVU PNU</td>
                                            <td width="25%">
                                                <span class="pointer" id="spanVVU_maambukiziN"><input type="radio" id="VVU_maambukiziN" name="VVU_maambukizi">Ndiyo</span><br />
                                                <span class="pointer" id="spanVVU_maambukiziH"><input type="radio" id="VVU_maambukiziH" name="VVU_maambukizi">Hapana</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Matokeo ya kipimo cha VVU cha Mama
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanmatokeo_mamaN"><input type="radio" id="matokeo_mamaN" name="matokeo_mama">Positive</span><br />
                                                <span class="pointer" id="spanmatokeo_mamaH"><input type="radio" id="matokeo_mamaH" name="matokeo_mama">Negative</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Matokeo ya kipimo cha VVU cha Mwenza
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanmatokeo_mwenzaN"><input type="radio" id="matokeo_mwenzaN" name="matokeo_mwenza">Positive</span><br />
                                                <span class="pointer" id="spanmatokeo_mwenzaH"><input type="radio" id="matokeo_mwenzaH" name="matokeo_mwenza">Negative</span>
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
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Maoni / Maudhui Madogo Madogo</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Rufaa</td></tr>

                                <td >
                                    <table width="100%">
                                        <tr>
                                            <td width="100%">
                                                <textarea style="width:100%;text-align:left" id="maoni"></textarea>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>

                                            <td width="100%">
                                                <textarea style="width:100%;text-align:left" id="rufaa"></textarea>

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

<style>
    .pointer:hover{
    cursor:pointer;
    }
</style>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(".tabcontents").tabs();
    $('#birth_date,#hudhurio_date').datepicker({
        changeMonth: true,
        changeYear: true
    });

    $('#njia_ya_uzazi').on('change', function () {
        var value = $(this).val();
        if (value == 'Kondomu') {
            $('#condom').show();
        } else {
            $('#condom').hide();
        }
    });
    $('#spanvidonge_saikoCOC').on('click', function () {
        $('#vidonge_saikoCOC').prop('checked', true);
    });

    $('#spanvidonge_saikoPOP').on('click', function () {
        $('#vidonge_saikoPOP').prop('checked', true);
    });

    $('#spanuchunguzi_titiN').on('click', function () {
        $('#uchunguzi_titiN').prop('checked', true);
    });

    $('#spanuchunguzi_titiH').on('click', function () {
        $('#uchunguzi_titiH').prop('checked', true);
    });


    $('#spanBujeN').on('click', function () {
        $('#BujeN').prop('checked', true);
    });

    $('#spanBujeH').on('click', function () {
        $('#BujeH').prop('checked', true);
    });


    $('#spankidondaN').on('click', function () {
        $('#kidondaN').prop('checked', true);
    });

    $('#spankidondaH').on('click', function () {
        $('#kidondaH').prop('checked', true);
    });

    $('#spanchuchu_damuN').on('click', function () {
        $('#chuchu_damuN').prop('checked', true);
    });

    $('#spanchuchu_damuH').on('click', function () {
        $('#chuchu_damuH').prop('checked', true);
    });


//
    $('#spanjipu_chuchuN').on('click', function () {
        $('#jipu_chuchuN').prop('checked', true);
    });

    $('#spanjipu_chuchuH').on('click', function () {
        $('#jipu_chuchuH').prop('checked', true);
    });


    $('#spantiti_mengineN').on('click', function () {
        $('#titi_mengineN').prop('checked', true);
    });

    $('#spantiti_mengineH').on('click', function () {
        $('#titi_mengineH').prop('checked', true);
    });

    $('#spanuchunguzi_sarataniN').on('click', function () {
        $('#uchunguzi_sarataniN').prop('checked', true);
    });

    $('#spanuchunguzi_sarataniH').on('click', function () {
        $('#uchunguzi_sarataniH').prop('checked', true);
    });


    $('#spanuchafu_ukeniN').on('click', function () {
        $('#uchafu_ukeniN').prop('checked', true);
    });

    $('#spanuchafu_ukeniH').on('click', function () {
        $('#uchafu_ukeniH').prop('checked', true);
    });


    $('#spankizazi_uvimbeN').on('click', function () {
        $('#kizazi_uvimbeN').prop('checked', true);
    });

    $('#spankizazi_uvimbeH').on('click', function () {
        $('#kizazi_uvimbeH').prop('checked', true);
    });

     $('#spankizazi_mchubukoN').on('click', function () {
        $('#kizazi_mchubukoN').prop('checked', true);
    });

    $('#spankizazi_mchubukoH').on('click', function () {
        $('#kizazi_mchubukoH').prop('checked', true);
    });

    $('#spandamu_ukeniN').on('click', function () {
        $('#damu_ukeniN').prop('checked', true);
    });

    $('#spandamu_ukeniH').on('click', function () {
        $('#damu_ukeniH').prop('checked', true);
    });



    $('#spanviaN').on('click', function () {
        $('#viaN').prop('checked', true);
    });

    $('#spanviaH').on('click', function () {
        $('#viaH').prop('checked', true);
    });

    $('#spansaratani_mengineyoN').on('click', function () {
        $('#saratani_mengineyoN').prop('checked', true);
    });

    $('#spansaratani_mengineyoH').on('click', function () {
        $('#saratani_mengineyoH').prop('checked', true);
    });


    $('#spanCryotherapyN').on('click', function () {
        $('#CryotherapyN').prop('checked', true);
    });

    $('#spanCryotherapyH').on('click', function () {
        $('#CryotherapyH').prop('checked', true);
    });


    $('#spanbaada_ya_mimbaN').on('click', function () {
        $('#baada_ya_mimbaN').prop('checked', true);
    });

    $('#spanbaada_ya_mimbaH').on('click', function () {
        $('#baada_ya_mimbaH').prop('checked', true);
    });

//
     $('#spanKuondoaN').on('click', function () {
        $('#KuondoaN').prop('checked', true);
    });

    $('#spanKuondoaH').on('click', function () {
        $('#KuondoaH').prop('checked', true);
    });



     $('#spanVVU_maambukiziN').on('click', function () {
        $('#VVU_maambukiziN').prop('checked', true);
    });

    $('#spanVVU_maambukiziH').on('click', function () {
        $('#VVU_maambukiziH').prop('checked', true);
    });


    $('#spanmatokeo_mamaN').on('click', function () {
        $('#matokeo_mamaN').prop('checked', true);
    });

    $('#spanmatokeo_mamaH').on('click', function () {
        $('#matokeo_mamaH').prop('checked', true);
    });


    $('#spanmatokeo_mwenzaN').on('click', function () {
        $('#matokeo_mwenzaN').prop('checked', true);
    });

    $('#spanmatokeo_mwenzaH').on('click', function () {
        $('#matokeo_mwenzaH').prop('checked', true);
    });

        $('#spanVidonge').on('click', function () {
            $('#Vidonge').prop('checked', true);
        });

        $('#spanSindano').on('click', function () {
            $('#Sindano').prop('checked', true);
        });

         $('#spanVipandikizi').on('click', function () {
            $('#Vipandikizi').prop('checked', true);
        });

        $('#spanKitanzi').on('click', function () {
            $('#Kitanzi').prop('checked', true);
        });


         $('#spanKufunga').on('click', function () {
            $('#Kufunga').prop('checked', true);
        });

        $('#spanKondom').on('click', function () {
            $('#Kondom').prop('checked', true);
        });

    $('#save_data').click(function () {
        var patient_ID = $('#patient_ID').val();
        var leo_Date = $('#leo_Date').val();
        var para = $('#para').val();
        var abortions = $('#abortions').val();
        var watoto_hai = $('#watoto_hai').val();
        var vidonge_saiko;
        var uchunguzi_titi;
        var Buje;
        var kidonda;
        var chuchu_damu;
        var jipu_chuchu;
        var titi_mengine;
        var uchunguzi_saratani;
        var uchafu_ukeni;
        var kizazi_uvimbe;
        var kizazi_mchubuko;
        var damu_ukeni;
        var via;
        var saratani_mengineyo;
        var Cryotherapy;
        var baada_ya_mimba;
        var Kuondoa;
        var VVU_maambukizi;
        var matokeo_mama;
        var matokeo_mwenza;
        var matibabu_aliyochagua;
        if ($('#vidonge_saikoCOC').is(':checked')) {
            vidonge_saiko = 'COC';
        } else if ($('#vidonge_saikoPOP').is(':checked')) {
            vidonge_saiko = 'POP';
        }

        if ($('#uchunguzi_titiN').is(':checked')) {
            uchunguzi_titi = 'N';
        } else if ($('#uchunguzi_titiH').is(':checked')) {
            uchunguzi_titi = 'H';
        }

        if ($('#BujeN').is(':checked')) {
            Buje = 'N';
        } else if ($('#BujeH').is(':checked')) {
            Buje = 'H';
        }

        if ($('#kidondaN').is(':checked')) {
            kidonda = 'N';
        } else if ($('#kidondaH').is(':checked')) {
            kidonda = 'H';
        }

        if ($('#chuchu_damuN').is(':checked')) {
            chuchu_damu = 'N';
        } else if ($('#chuchu_damuH').is(':checked')) {
            chuchu_damu = 'H';
        }

        if ($('#jipu_chuchuN').is(':checked')) {
            jipu_chuchu = 'N';
        } else if ($('#jipu_chuchuH').is(':checked')) {
            jipu_chuchu = 'H';
        }

        if ($('#titi_mengineN').is(':checked')) {
            titi_mengine = 'N';
        } else if ($('#titi_mengineH').is(':checked')) {
            titi_mengine = 'H';
        }


        if ($('#uchunguzi_sarataniN').is(':checked')) {
            uchunguzi_saratani = 'N';
        } else if ($('#uchunguzi_sarataniH').is(':checked')) {
            uchunguzi_saratani = 'H';
        }

        if ($('#uchafu_ukeniN').is(':checked')) {
            uchafu_ukeni = 'N';
        } else if ($('#uchafu_ukeniH').is(':checked')) {
            uchafu_ukeni = 'H';
        }

         if ($('#kizazi_uvimbeN').is(':checked')) {
            kizazi_uvimbe = 'N';
        } else if ($('#kizazi_uvimbeH').is(':checked')) {
            kizazi_uvimbe = 'H';
        }

         if ($('#kizazi_mchubukoN').is(':checked')) {
            kizazi_mchubuko = 'N';
        } else if ($('#kizazi_mchubukoH').is(':checked')) {
            kizazi_mchubuko = 'H';
        }

        if ($('#damu_ukeniN').is(':checked')) {
            damu_ukeni = 'N';
        } else if ($('#damu_ukeniH').is(':checked')) {
            damu_ukeni = 'H';
        }


        if ($('#viaN').is(':checked')) {
            via = 'N';
        } else if ($('#viaH').is(':checked')) {
            via = 'H';
        }

        if ($('#saratani_mengineyoN').is(':checked')) {
            saratani_mengineyo = 'N';
        } else if ($('#saratani_mengineyoH').is(':checked')) {
            saratani_mengineyo = 'H';
        }

        if ($('#CryotherapyN').is(':checked')) {
            Cryotherapy = 'N';
        } else if ($('#CryotherapyH').is(':checked')) {
            Cryotherapy = 'H';
        }

        if ($('#baada_ya_mimbaN').is(':checked')) {
            baada_ya_mimba = 'Mimba Kuharibika';
        } else if ($('#baada_ya_mimbaH').is(':checked')) {
            baada_ya_mimba = 'Kujifungua';
        }



        if ($('#KuondoaN').is(':checked')) {
            Kuondoa = 'Kitanzi';
        } else if ($('#KuondoaH').is(':checked')) {
            Kuondoa = 'Kipandikizi';
        }


        if ($('#VVU_maambukiziN').is(':checked')) {
            VVU_maambukizi = 'N';
        } else if ($('#VVU_maambukiziH').is(':checked')) {
            VVU_maambukizi = 'H';
        }


        if ($('#matokeo_mamaN').is(':checked')) {
            matokeo_mama = 'P';
        } else if ($('#matokeo_mamaH').is(':checked')) {
            matokeo_mama = 'N';
        }

//        matokeo_mwenza

         if ($('#matokeo_mwenzaN').is(':checked')) {
            matokeo_mwenza = 'P';
        } else if ($('#matokeo_mwenzaH').is(':checked')) {
            matokeo_mwenza = 'N';
        }


         if ($('#Vidonge').is(':checked')) {
            matibabu_aliyochagua = 'Vidonge';
        } else if ($('#Sindano').is(':checked')) {
            matibabu_aliyochagua = 'Sindano';
        }else if($('#Vipandikizi')){
            matibabu_aliyochagua='Vipandikizi';
        }else if($('#Kitanzi')){
            matibabu_aliyochagua='Kitanzi';
        }else if($('#Kufunga')){
            matibabu_aliyochagua='Kufunga';
        }else if($('#Kondom')){
             matibabu_aliyochagua='Kondom';
        }

        var idadi_vidonge = $('#idadi_vidonge').val();
        var njia_ya_uzazi = $('#njia_ya_uzazi').val();
        var select_Condom = $('#select_Condom').val();
        var idadi_Condom = $('#idadi_Condom').val();
        var marudio_Jan = $('#marudio_Jan').val();
        var marudio_Feb = $('#marudio_Feb').val();
        var marudio_Machi = $('#marudio_Machi').val();
        var marudio_April = $('#marudio_April').val();
        var marudio_Mei = $('#marudio_Mei').val();
        var marudio_Jun = $('#marudio_Jun').val();
        var marudio_Jul = $('#marudio_Jul').val();
        var marudio_Ago = $('#marudio_Ago').val();
        var marudio_Sept = $('#marudio_Sept').val();
        var marudio_Oct = $('#marudio_Oct').val();
        var marudio_Nov = $('#marudio_Nov').val();
        var marudio_Des = $('#marudio_Des').val();



        var maoni = $('#maoni').val();
        var rufaa = $('#rufaa').val();
        var mteja_aina = $('#mteja_aina').val();
        $.ajax({
            type: 'POST',
            url: 'requests/save_family_planing.php',
            data: 'action=save&patient_ID=' + patient_ID + '&leo_Date=' + leo_Date + '&para=' + para + '&abortions=' + abortions + '&watoto_hai=' + watoto_hai + '&vidonge_saiko=' + vidonge_saiko + '&idadi_vidonge=' + idadi_vidonge + '&njia_ya_uzazi=' + njia_ya_uzazi + '&select_Condom=' + select_Condom + '&idadi_Condom=' + idadi_Condom + '&uchunguzi_titi=' + uchunguzi_titi + '&Buje=' + Buje + '&kidonda=' + kidonda + '&chuchu_damu=' + chuchu_damu + '&jipu_chuchu=' + jipu_chuchu + '&titi_mengine=' + titi_mengine + '&uchunguzi_saratani=' + uchunguzi_saratani + '&uchafu_ukeni=' + uchafu_ukeni + '&kizazi_uvimbe=' + kizazi_uvimbe + '&kizazi_mchubuko=' + kizazi_mchubuko + '&damu_ukeni=' + damu_ukeni + '&via='
                    + via + '&saratani_mengineyo=' + saratani_mengineyo + '&Cryotherapy='
                    + Cryotherapy + '&baada_ya_mimba=' + baada_ya_mimba + '&matibabu_aliyochagua=' + matibabu_aliyochagua + '&Kuondoa=' + Kuondoa + '&marudio_Jan='
                    + marudio_Jan + '&marudio_Feb=' + marudio_Feb + '&marudio_Machi=' + marudio_Machi
                    + '&marudio_April=' + marudio_April + '&marudio_Mei=' + marudio_Mei + '&marudio_Jun=' + marudio_Jun + '&marudio_Jul='
                    + marudio_Jul + '&marudio_Ago=' + marudio_Ago + '&marudio_Sept=' + marudio_Sept + '&marudio_Oct=' + marudio_Oct + '&marudio_Nov='
                    + marudio_Nov + '&marudio_Des=' + marudio_Des + '&VVU_maambukizi=' + VVU_maambukizi + '&matokeo_mama=' + matokeo_mama + '&matokeo_mwenza='
                    + matokeo_mwenza + '&maoni=' + maoni + '&rufaa=' + rufaa + '&mteja_aina=' + mteja_aina,
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
    });

</script>
