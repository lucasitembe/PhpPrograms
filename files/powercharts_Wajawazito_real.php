
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
        echo "<a href='searchvisitorsWajawazitopatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
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

                            <input style="width:240px;" readonly="true" id="leo_Date" name="" type="text" value="<?php echo Date('Y-m-d');?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Namba ya Usajili</td><td ><input name="jinakamili" id="patientNo" value="<?php echo $regNo; ?>" type="text" readonly="true"></td>
                        <td  colspan="" align="right" style="text-align:right;">Umri</td><td>
                            <input name="" id="age" readonly="true" type="text" value="<?php echo $age; ?>" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jina la Kijiji/Kitongoji/Balozi/mtaa/barabara/Na. ya nyumba</td><td><input name="name" id="kijiji_jina" type="text" </td>
                        <td  colspan="" align="right" style="text-align:right;">Mume/Mwenza(Jina)</td><td>
                        <input name="baloz" id="mwenza" type="text" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jina la M/kiti serikali ya mtaa/kitongoji</td>
                        <td>
                            <input type="text" id="mwenyekitijina">
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
                                                <span class="pointer" id="hapanabtn"><input type="radio" name="kadi" value="H" id="hapanakadi">Hapana</span>
                                                <span class="pointer" id="ndiyobtn"><input type="radio" name="kadi" value="N" id="Ndiyokadi">Ndiyo</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Andika tarehe ya TT1
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="TT1date">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Andika tarehe ya TT2+
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="TT2date">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td >
                                                Umri wa Mimba Kwa Wiki
                                            </td>
                                            <td>
                                                <input type="text" style="width:150px" id="mimbaumri">

                                            </td>

                                        </tr>

                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:130px;">Mimba ya ngapi</td>
                                            <td width="25%">
                                                <input type="text" id="mimbaNo" style="width:150px">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:130px;">
                                                Umezaa mara ngapi
                                            </td>
                                            <td>
                                                <input type="text" id="umezaaNo" style="width:150px">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Watoto hai
                                            </td>
                                            <td>
                                                <input type="text" id="watotohai" style="width:150px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Mimba zilizoharibika
                                            </td>
                                            <td>
                                                <input type="text" id="abortions" style="width:150px">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                FSB/MSB/kifo cha mtoto katika wiki moja
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanhapanaFSB"><input type="radio" id="hapanaFSB" name="mimba" >Hapana</span>
                                                <span class="pointer" id="spanndiyoFSB"><input type="radio" id="ndiyoFSB" name="mimba">Ndiyo</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri wa mtoto wa mwisho
                                            </td>
                                            <td>
                                                <input type="text" id="mtotowamwishoAge" style="width:150px">
                                            </td>
                                        </tr>


                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Kiwango cha damu (HB) mfano 11.0</td>
                                            <td width="25%">
                                                <input type="text" style="width:120px;" id="damuKiwango">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Shinikizo la damu (BP)
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanBPH"><input type="radio" id="hapanaBP" name="BP">Hapana</span>
                                                <span class="pointer" id="spanBPN"><input type="radio" id="ndiyoBP" name="BP">Ndiyo</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Urefu(cm)
                                            </td>
                                            <td>
                                                <input type="text" style="width:120px;" name="mimbanamba" id="urefu">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Sukari kwenye mkojo
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanhapanasukari"><input  type="radio" name="mkojosukari" id="hapanasukari">Hapana</span>
                                                <span class="pointer" id="spanndiyosukari"><input type="radio" name="mkojosukari" id="ndiyosukari">Ndiyo</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kufunga kwa CS
                                            </td>
                                            <td>

                                               <span class="pointer" id="spanCSH"><input  type="radio" name="CS" id="CSH">Hapana</span>
                                               <span class="pointer" id="spanCSN"><input type="radio" name="CS" id="CSN">Ndiyo</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri chini ya Miaka 20
                                            </td>
                                            <td>
                                               <span class="pointer" id="spanUnder20H"><input  type="radio" name="Under20" id="Under20H">Hapana</span>
                                               <span class="pointer" id="spanUnder20N"><input type="radio" name="Under20" id="Under20N">Ndiyo</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Umri zaidi ya miaka 35
                                            </td>
                                            <td>
                                               <span class="pointer" id="spanUnder35H"><input  type="radio" name="Under35" id="Under35H">Hapana</span>
                                               <span class="pointer" id="spanUnder35N"><input type="radio" name="Under35" id="Under35N">Ndiyo</span>
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
                                               <span class="pointer" id="spanksmatokeokeN"><input  type="radio" name="ksmatokeoke" id="ksmatokeokeN">Negative</span>
                                               <span class="pointer" id="spanksmatokeokeP"><input type="radio" name="ksmatokeoke" id="ksmatokeokeP">Positive</span>


                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Me
                                            </td>
                                            <td>
                                               <span class="pointer" id="spanksmatokeomeN"><input  type="radio" name="ksmatokeome" id="ksmatokeomeN">Negative</span>
                                               <span class="pointer" id="spanksmatokeomeP"><input type="radio" name="ksmatokeome" id="ksmatokeomeP">Positive</span>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Ke
                                            </td>
                                            <td>
                                              <span class="pointer" id="spankstibakeH"><input  type="radio" name="kstibake" id="kstibakeH">Hapana</span>
                                              <span class="pointer" id="spankstibakeN"><input type="radio" name="kstibake" id="kstibakeN">Ndiyo</span>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Me
                                            </td>
                                            <td>
                                              <span class="pointer" id="spankstibameH"><input  type="radio" name="kstibame" id="kstibameH">Hapana</span>
                                              <span class="pointer" id="spankstibameN"><input type="radio" name="kstibame" id="kstibameN">Ndiyo</span>
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
                                            <span class="pointer" id="spanngmatokeokeN"><input  type="radio" name="ngmatokeoke" id="ngmatokeokeN">Negative</span>
                                            <span class="pointer" id="spanngmatokeokeP"><input type="radio" name="ngmatokeoke" id="ngmatokeokeP">Positive</span>

                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                Matokeo:Me
                                            </td>
                                            <td>
                                            <span class="pointer" id="spanngmatokeomeN"><input  type="radio" name="ngmatokeome" id="ngmatokeomeN">Negative</span>
                                            <span class="pointer" id="spanngmatokeomeP"><input type="radio" name="ngmatokeome" id="ngmatokeomeP">Positive</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Ke
                                            </td>
                                            <td>
                                            <span class="pointer" id="spanngtibakeH"><input  type="radio" name="ngtibake" id="ngtibakeH">Hapana</span>
                                            <span class="pointer" id="spanngtibakeN"><input type="radio" name="ngtibake" id="ngtibakeN">Ndiyo</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Ametibiwa:Me
                                            </td>
                                            <td>

                                            <span class="pointer" id="spanngtibameH"><input  type="radio" name="ngtibame" id="ngtibameH">Hapana</span>
                                            <span class="pointer" id="spanngtibameN"><input type="radio" name="ngtibame" id="ngtibameN">Ndiyo</span>

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
                                                <span class="pointer" id="spantayariVVUkeH"><input  type="radio" name="tayariVVUke" id="tayariVVUkeH">Hapana</span>
                                                <span class="pointer" id="spantayariVVUkeN"><input type="radio" name="tayariVVUke" id="tayariVVUkeN">Ndiyo</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Tayari ana maambukizi ya VVU:Me
                                            </td>
                                            <td>

                                                <span class="pointer" id="spantayariVVUmeH"><input  type="radio" name="tayariVVUme" id="tayariVVUmeH">Hapana</span>
                                                <span class="pointer" id="spantayariVVUmeN"><input type="radio" name="tayariVVUme" id="tayariVVUmeN">Ndiyo</span>


                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi:Ke
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="unasihike">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi:Me
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="unasihime">
                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepima VVU:Ke
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanamepimaVVUkeH"><input  type="radio" name="amepimaVVUke" id="amepimaVVUkeH">Hapana</span>
                                                <span class="pointer" id="spanamepimaVVUkeN"><input type="radio" name="amepimaVVUke" id="amepimaVVUkeN">Ndiyo</span>
                                           </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Amepima VVU:Me
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanamepimaVVUmeH"><input  type="radio" name="amepimaVVUme" id="amepimaVVUmeH">Hapana</span>
                                                <span class="pointer" id="spanamepimaVVUmeN"><input type="radio" name="amepimaVVUme" id="amepimaVVUmeN">Ndiyo</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya kipimo:Ke
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="kimpimotareheke">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                Tarehe ya kipimo:Me
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="kimpimotareheme">

                                            </td>
                                        </tr>
                                         <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya kipimo cha 1 cha VVU:Ke
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanmatokeoVVU1keN"><input  type="radio" name="matokeoVVU1ke" id="matokeoVVU1keN">Negative</span>
                                                <span class="pointer" id="spanmatokeoVVU1keP"><input type="radio" name="matokeoVVU1ke" id="matokeoVVU1keP">Positive</span>

                                            </td>

                                        </tr>

                                         <tr>
                                            <td style="text-align:right;">
                                                Matokeo ya kipimo cha 1 cha VVU:Me
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanmatokeoVVU1meN"><input  type="radio" name="matokeoVVU1me" id="matokeoVVU1meN">Negative</span>
                                                <span class="pointer" id="spanmatokeoVVU1meP"><input type="radio" name="matokeoVVU1me" id="matokeoVVU1meP">Positive</span>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata unasihi baada ya kupima:Ke
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanunasihibaadayakupmakeH"><input  type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeH">Hapana</span>
                                                <span class="pointer" id="spanunasihibaadayakupmakeN"><input type="radio" name="unasihibaadayakupmake" id="unasihibaadayakupmakeN">Ndiyo</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                               Amepata unasihi baada ya kupima:Me
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanunasihibaadayakupmameH"><input  type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameH">Hapana</span>
                                                <span class="pointer" id="spanunasihibaadayakupmameN"><input type="radio" name="unasihibaadayakupmame" id="unasihibaadayakupmameN">Ndiyo</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                               Matokeo ya kipimo cha pili cha VVU
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanmatokeoVVU2N"><input  type="radio" name="matokeoVVU2" id="matokeoVVU2N">Negative</span>
                                                <span class="pointer" id="spanmatokeoVVU2P"><input type="radio" name="matokeoVVU2" id="matokeoVVU2P">Positive</span>

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                               Amepata ushauri juu ya ulishaji wa mtoto
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanushauriulishajiH"><input  type="radio" name="ushauriulishaji" id="ushauriulishajiH">Hapana</span>
                                                <span class="pointer" id="spanushauriulishajiN"><input type="radio" name="ushauriulishaji" id="ushauriulishajiN">Ndiyo</span>


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
                                                <span class="pointer" id="spanmrdtH"><input  type="radio" name="mrdt" id="mrdtH">Hapana</span>
                                                <span class="pointer" id="spanmrdtN"><input type="radio" name="mrdt" id="mrdtN">Ndiyo</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Amepata hati Punguzo
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanhatipunguzoH"><input  type="radio" name="hatipunguzo" id="hatipunguzoH">Hapana</span>
                                                <span class="pointer" id="spanhatipunguzoN"><input type="radio" name="hatipunguzo" id="hatipunguzoN">Ndiyo</span>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-1
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt1">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-2
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt2">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-3
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt3">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                Andika tarehe ya IPT-4
                                            </td>
                                            <td>
                                                <input type="text" style="width:130px" id="ipt4">

                                            </td>

                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                           <td style="text-align:right;width:100px;">
                                               1:

                                                <span class="pointer" id="spanaina_1I"><input  type="radio" name="aina_1" id="aina_1I">I</span>
                                                <span class="pointer" id="spanaina_1FA"><input type="radio" name="aina_1" id="aina_1FA">FA</span>
                                                <span class="pointer" id="spanaina_1IFA"><input type="radio" name="aina_1" id="aina_1IFA">IFA</span>

                                           </td>
                                            <td width="25%">
                                                <input type="text" style="width:200px;" id="idadi_1">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                2:
                                                 <span class="pointer" id="spanaina_2I"><input  type="radio" name="aina_2" id="aina_2I">I</span>
                                                <span class="pointer" id="spanaina_2FA"><input type="radio" name="aina_2" id="aina_2FA">FA</span>
                                                <span class="pointer" id="spanaina_2IFA"><input type="radio" name="aina_2" id="aina_2IFA">IFA</span>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_2" >

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                3:
                                                 <span class="pointer" id="spanaina_3I"><input  type="radio" name="aina_3" id="aina_3I">I</span>
                                                <span class="pointer" id="spanaina_3FA"><input type="radio" name="aina_3" id="aina_3FA">FA</span>
                                                <span class="pointer" id="spanaina_3IFA"><input type="radio" name="aina_3" id="aina_3IFA">IFA</span>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_3" >

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                4:
                                                <span class="pointer" id="spanaina_4I"><input  type="radio" name="aina_4" id="aina_4I">I</span>
                                                <span class="pointer" id="spanaina_4FA"><input type="radio" name="aina_4" id="aina_4FA">FA</span>
                                                <span class="pointer" id="spanaina_4IFA"><input type="radio" name="aina_4" id="aina_4IFA">IFA</span>

                                            </td>
                                            <td>
                                                <input type="text" style="width:200px;" name="mimbanamba" id="idadi_4" >

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Amepata Albendazole/Mebendazole
                                            </td>
                                            <td>
                                            <span class="pointer" id="spanamebendazoleH"><input  type="radio" name="amebendazole" id="amebendazoleH">Hapana</span>
                                            <span class="pointer" id="spanamebendazoleN"><input type="radio" name="amebendazole" id="amebendazoleN">Ndiyo</span>

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
                                                <input type="text" id="rufaatarehe" style="width:100%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kituo alikopelekwa</td>
                                            <td width="70%">
                                                <input type="text" id="alikopelekwa" style="width:100%">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Sababu ya rufaa</td>
                                            <td width="70%">
                                                <input type="text" id="rufaasababu" style="width:100%">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Kituo alikotokea</td>
                                            <td width="70%">
                                                <input type="text" id="kituoalikotoka" style="width:100%">
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>

                                            <td width="100%">
                                                <textarea style="width:100%;height:130px;text-align:left" id="maoni"></textarea>

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
    $('#TT1date,#TT2date,#rufaatarehe').datepicker({
        changeMonth: true,
        changeYear: true
    });

    $('#hapanabtn').on('click',function(){
        $('#hapanakadi').prop('checked',true);
    });

    $('#ndiyobtn').on('click',function(){
        $('#Ndiyokadi').prop('checked',true);
    });

    $('#spanhapanaFSB').on('click',function(){
        $('#hapanaFSB').prop('checked',true);
    });

    $('#spanndiyoFSB').on('click',function(){
        $('#ndiyoFSB').prop('checked',true);
    });

    $('#spanhapanasukari').on('click',function(){
       $('#hapanasukari').prop('checked',true);
    });

     $('#spanndiyosukari').on('click',function(){
       $('#ndiyosukari').prop('checked',true);
    });

    $('#spanBPH').on('click',function(){
       $('#hapanaBP').prop('checked',true);
    });

    $('#spanBPN').on('click',function(){
       $('#ndiyoBP').prop('checked',true);
    });

    $('#spanCSH').on('click',function(){
       $('#CSH').prop('checked',true);
    });

     $('#spanCSN').on('click',function(){
       $('#CSN').prop('checked',true);
    });

    $('#spanUnder20H').on('click',function(){
       $('#Under20H').prop('checked',true);
    });

    $('#spanUnder20N').on('click',function(){
       $('#Under20N').prop('checked',true);
    });



    $('#spanUnder35H').on('click',function(){
       $('#Under35H').prop('checked',true);
    });

    $('#spanUnder35N').on('click',function(){
       $('#Under35N').prop('checked',true);
    });

    $('#spanksmatokeokeN').on('click',function(){
       $('#ksmatokeokeN').prop('checked',true);
    });

     $('#spanksmatokeokeP').on('click',function(){
       $('#ksmatokeokeP').prop('checked',true);
    });

     $('#spanksmatokeomeN').on('click',function(){
       $('#ksmatokeomeN').prop('checked',true);
    });

     $('#spanksmatokeomeP').on('click',function(){
       $('#ksmatokeomeP').prop('checked',true);
    });

//
     $('#spankstibakeH').on('click',function(){
       $('#kstibakeH').prop('checked',true);
    });

     $('#spankstibakeN').on('click',function(){
       $('#kstibakeN').prop('checked',true);
    });

     $('#spankstibameH').on('click',function(){
       $('#kstibameH').prop('checked',true);
    });

     $('#spankstibameN').on('click',function(){
       $('#kstibameN').prop('checked',true);
    });

    $('#spanngmatokeokeN').on('click',function(){
       $('#ngmatokeokeN').prop('checked',true);
    });

     $('#spanngmatokeokeP').on('click',function(){
       $('#ngmatokeokeP').prop('checked',true);
    });

     $('#spanngmatokeomeN').on('click',function(){
       $('#ngmatokeomeN').prop('checked',true);
    });

     $('#spanngmatokeomeP').on('click',function(){
       $('#ngmatokeomeP').prop('checked',true);
    });

     $('#spanngtibakeH').on('click',function(){
       $('#ngtibakeH').prop('checked',true);
    });

     $('#spanngtibakeN').on('click',function(){
       $('#ngtibakeN').prop('checked',true);
    });

     $('#spanngtibameH').on('click',function(){
       $('#ngtibameH').prop('checked',true);
    });

     $('#spanngtibameN').on('click',function(){
       $('#ngtibameN').prop('checked',true);
    });

     $('#spantayariVVUkeH').on('click',function(){
       $('#tayariVVUkeH').prop('checked',true);
    });

     $('#spantayariVVUkeN').on('click',function(){
       $('#tayariVVUkeN').prop('checked',true);
    });

     $('#spantayariVVUmeH').on('click',function(){
       $('#tayariVVUmeH').prop('checked',true);
    });

     $('#spantayariVVUmeN').on('click',function(){
       $('#tayariVVUmeN').prop('checked',true);
    });


    $('#spanamepimaVVUkeH').on('click',function(){
       $('#amepimaVVUkeH').prop('checked',true);
    });

     $('#spanamepimaVVUkeN').on('click',function(){
       $('#amepimaVVUkeN').prop('checked',true);
    });

     $('#spanamepimaVVUmeH').on('click',function(){
       $('#amepimaVVUmeH').prop('checked',true);
    });

     $('#spanamepimaVVUmeN').on('click',function(){
       $('#amepimaVVUmeN').prop('checked',true);
    });



    $('#spanmatokeoVVU1keN').on('click',function(){
       $('#matokeoVVU1keN').prop('checked',true);
    });

     $('#spanmatokeoVVU1keP').on('click',function(){
       $('#matokeoVVU1keP').prop('checked',true);
    });

     $('#spanmatokeoVVU1meN').on('click',function(){
       $('#matokeoVVU1meN').prop('checked',true);
    });

     $('#spanmatokeoVVU1meP').on('click',function(){
       $('#matokeoVVU1meP').prop('checked',true);
    });


    $('#spanunasihibaadayakupmakeH').on('click',function(){
       $('#unasihibaadayakupmakeH').prop('checked',true);
    });

     $('#spanunasihibaadayakupmakeN').on('click',function(){
       $('#unasihibaadayakupmakeN').prop('checked',true);
    });

     $('#spanunasihibaadayakupmameH').on('click',function(){
       $('#unasihibaadayakupmameH').prop('checked',true);
    });

     $('#spanunasihibaadayakupmameN').on('click',function(){
       $('#unasihibaadayakupmameN').prop('checked',true);
    });


    $('#spanmatokeoVVU2N').on('click',function(){
       $('#matokeoVVU2N').prop('checked',true);
    });

     $('#spanmatokeoVVU2P').on('click',function(){
       $('#matokeoVVU2P').prop('checked',true);
    });

    $('#spanushauriulishajiH').on('click',function(){
       $('#ushauriulishajiH').prop('checked',true);
    });

     $('#spanushauriulishajiN').on('click',function(){
       $('#ushauriulishajiN').prop('checked',true);
    });


     $('#spanmrdtH').on('click',function(){
       $('#mrdtH').prop('checked',true);
    });

     $('#spanmrdtN').on('click',function(){
       $('#mrdtN').prop('checked',true);
    });

     $('#spanhatipunguzoH').on('click',function(){
       $('#hatipunguzoH').prop('checked',true);
    });

     $('#spanhatipunguzoN').on('click',function(){
       $('#hatipunguzoN').prop('checked',true);
    });


    $('#spanaina_1I').on('click',function(){
       $('#aina_1I').prop('checked',true);
    });

     $('#spanaina_1FA').on('click',function(){
       $('#aina_1FA').prop('checked',true);
    });

    $('#spanaina_1IFA').on('click',function(){
       $('#aina_1IFA').prop('checked',true);
    });

    $('#spanaina_2I').on('click',function(){
       $('#aina_2I').prop('checked',true);
    });

     $('#spanaina_2FA').on('click',function(){
       $('#aina_2FA').prop('checked',true);
    });

    $('#spanaina_2IFA').on('click',function(){
       $('#aina_2IFA').prop('checked',true);
    });

    $('#spanaina_3I').on('click',function(){
       $('#aina_3I').prop('checked',true);
    });

     $('#spanaina_3FA').on('click',function(){
       $('#aina_3FA').prop('checked',true);
    });

    $('#spanaina_3IFA').on('click',function(){
       $('#aina_3IFA').prop('checked',true);
    });

    $('#spanaina_4I').on('click',function(){
       $('#aina_4I').prop('checked',true);
    });

     $('#spanaina_4FA').on('click',function(){
       $('#aina_4FA').prop('checked',true);
    });

    $('#spanaina_4IFA').on('click',function(){
       $('#aina_4IFA').prop('checked',true);
    });

//

    $('#spanamebendazoleH').on('click',function(){
       $('#amebendazoleH').prop('checked',true);
    });

    $('#spanamebendazoleN').on('click',function(){
       $('#amebendazoleN').prop('checked',true);
    });

    $('#save_data').click(function (){
        var patient_ID = $('#patientNo').val();
        var leo_Date = $('#leo_Date').val();
        var kijiji_jina=$('#kijiji_jina').val();
        var mwenza=$('#mwenza').val();
        var mwenyekitijina=$('#mwenyekitijina').val();
        var urefu = $('#urefu').val();
        var TT1date=$('#TT1date').val();
        var TT2date=$('#TT2date').val();
        var mimbaNo=$('#mimbaNo').val();
        var mimbaumri=$('#mimbaumri').val();
        var umezaaNo=$('#umezaaNo').val();
        var watotohai=$('#watotohai').val();
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

        if($('#amebendazoleH').is(':checked')){
            amebendazole='H';
        }else if($('#amebendazoleN').is(':checked')){
            amebendazole='N';
        }else{
            alert('Jaza kama amepata Albendazole/Mebendazole')
        }


        if($('#aina_4I').is(':checked')){
            aina_4='I';
        }else if($('#aina_4FA').is(':checked')){
            aina_4='FA';
        }else if($('#aina_4IFA')){
            aina_4='IFA';
        }

        if($('#aina_3I').is(':checked')){
            aina_3='I';
        }else if($('#aina_3FA').is(':checked')){
            aina_3='FA';
        }else if($('#aina_3IFA')){
            aina_3='IFA';
        }
        if($('#aina_2I').is(':checked')){
            aina_2='I';
        }else if($('#aina_2FA').is(':checked')){
            aina_2='FA';
        }else if($('#aina_2IFA')){
            aina_2='IFA';
        }


        if($('#aina_1I').is(':checked')){
            aina_1='I';
        }else if($('#aina_1FA').is(':checked')){
            aina_1='FA';
        }else if($('#aina_1IFA')){
            aina_1='IFA';
        }

        if($('#hatipunguzoH').is(':checked')){
            hatipunguzo='H';
        }else if($('#hatipunguzoN').is(':checked')){
            hatipunguzo='N';
        }else{
            alert('Jaza kama amepata hati Punguzo');
            return false;
        }

        if($('#mrdtH').is(':checked')){
            mrdt='H';
        }else if($('#mrdtN').is(':checked')){
            mrdt='N';
        }
//        else{
//            alert('Jaza matokeo ya MRDT au BS');
//            return false;
//        }

        if($('#ushauriulishajiH').is(':checked')){
            ushauriulishaji='H';
        }else if($('#ushauriulishajiN').is(':checked')){
            matokeoVVU2='N';
        }else{
            alert('Jaza kama amepata ushauri juu ya ulishaji wa mtoto');
            return false;
        }

         if($('#matokeoVVU2N').is(':checked')){
            matokeoVVU2='N';
        }else if($('#matokeoVVU2P').is(':checked')){
            matokeoVVU2='P';
        }else{
            alert('Jaza matokeo ya kipimo cha pili cha VVU');
            return false;
        }

         if($('#unasihibaadayakupmameH').is(':checked')){
            unasihibaadayakupmame='N';
        }else if($('#unasihibaadayakupmameN').is(':checked')){
            unasihibaadayakupmame='H';
        }else{
            alert('Jaza kama amepata unasihi baada ya kupima:Me');
            return false;
        }
        if($('#unasihibaadayakupmakeH').is(':checked')){
            unasihibaadayakupmake='N';
        }else if($('#unasihibaadayakupmakeN').is(':checked')){
            unasihibaadayakupmake='H';
        }else{
            alert('Jaza kama amepata unasihi baada ya kupima:Ke');
            return false;
        }

        if($('#matokeoVVU1meN').is(':checked')){
            matokeoVVU1me='N';
        }else if($('#matokeoVVU1meP').is(':checked')){
            matokeoVVU1me='P';
        }else{
            alert('Jaza matokeo ya kipimo cha 1 cha VVU:Me');
            return false;
        }

        if($('#matokeoVVU1keN').is(':checked')){
            matokeoVVU1ke='N';
        }else if($('#matokeoVVU1keP').is(':checked')){
            matokeoVVU1ke='P';
        }else{
            alert('Jaza matokeo ya kipimo cha 1 cha VVU:Ke');
            return false;
        }

        if($('#amepimaVVUmeH').is(':checked')){
            amepimaVVUme='H';
        }else if($('#amepimaVVUmeN').is(':checked')){
            amepimaVVUme='N';
        }else{
            alert('Jaza kama amepima VVU:Me');
            return false;
        }
         if($('#amepimaVVUkeH').is(':checked')){
            amepimaVVUke='H';
        }else if($('#amepimaVVUkeN').is(':checked')){
            amepimaVVUke='N';
        }else{
            alert('Jaza kama amepima VVU:Ke');
            return false;
        }

        if($('#tayariVVUmeH').is(':checked')){
            tayariVVUme='H';
        }else if($('#tayariVVUmeN').is(':checked')){
            tayariVVUme='N';
        }else{
            alert('Jaza kama tayari ana maambukizi ya VVU:Me');
            return false;
        }

        if($('#tayariVVUkeH').is(':checked')){
            tayariVVUke='H';
        }else if($('#tayariVVUkeN').is(':checked')){
            tayariVVUke='N';
        }else{
            alert('Jaza kama tayari ana maambukizi ya VVU:Ke');
            return false;
        }

        if($('#ngtibameH').is(':checked')){
            ngtibame='H';
        }else if($('#ngtibameN').is(':checked')){
            ngtibame='N';
        }else{
            alert('Jaza kama ametibiwa magonjwa ya ngono me');
            return false;
        }

        if($('#ngtibakeH').is(':checked')){
            ngtibake='H';
        }else if($('#ngtibakeN').is(':checked')){
            ngtibake='N';
        }else{
            alert('Jaza kama ametibiwa magonjwa ya ngono ke');
            return false;
        }

        if($('#ngmatokeomeN').is(':checked')){
            ngmatokeome='N';
        }else if($('#ngmatokeomeP').is(':checked')){
            ngmatokeome='P';
        }else{
            alert('Jaza matokeo ya magonjwa ya ngono me');
            return false;
        }

        if($('#ngmatokeokeN').is(':checked')){
            ngmatokeoke='N';
        }else if($('#ngmatokeokeP').is(':checked')){
            ngmatokeoke='P';
        }else{
            alert('Jaza matokeo ya magonjwa ya ngono ke');
            return false;
        }

        if($('#kstibameN').is(':checked')){
            kstibame='N';
        }else if($('#kstibameH').is(':checked')){
            kstibame='H';
        }else{
            alert('Jaza ametibiwa kaswende me');
            return false;
        }

        if($('#kstibakeN').is(':checked')){
            kstibake='N';
        }else if($('#kstibakeH').is(':checked')){
            kstibake='H';
        }else{
            alert('Jaza ametibiwa kaswende ke');
            return false;
        }

         if($('#ksmatokeomeN').is(':checked')){
            ksmatokeome='N';
        }else if($('#ksmatokeomeP').is(':checked')){
            ksmatokeome='P';
        }
        else{
            alert('Jaza matokeo ya kaswende me');
            return false;
        }

        if($('#ksmatokeokeN').is(':checked')){
            ksmatokeoke='N';
        }else if($('#ksmatokeokeP').is(':checked')){
            ksmatokeoke='P';
        }else{
            alert('Jaza matokeo ya kaswende ke');
            return false;
        }

        if($('#Under35N').is(':checked')){
            under_35='N';
        }else if($('#Under35H').is(':checked')){
            under_35='H';
        }else{
            alert('Jaza umri chini ya miaka 35');
            return false;
        }


        if($('#Under20N').is(':checked')){
            under_20='N';
        }else if($('#Under20H').is(':checked')){
            under_20='H';
        }else{
            alert('Jaza umri chini ya miaka 20');
            return false;
        }
        if($('#CSN').is(':checked')){
            kufungaCS='N';
        }else if($('#CSH').is(':checked')){
            kufungaCS='H';
        }else{
            alert('Jaza kufunga kwa CS');
            return false;
        }

        if($('#hapanakadi').is(':checked')){
         anakadi='H';
        }else if($('#Ndiyokadi').is(':checked')){
            anakadi='N';
        }else{
            alert('Jaza kama ana kadi');
            return false;
        }

        var FSB;
        if($('#hapanaFSB').is(':checked')){
            FSB='H';
        }else if($('#ndiyoFSB').is(':checked')){
            FSB='N';
        }else{
            alert('Jaza kifo cha mtoto katika wiki');
            return false;
        }

        var BP;
        if($('#hapanaBP').is(':checked')){
           BP='H';
        }else if($('#ndiyoBP').is(':checked')){
            BP='N';
        }else{
            alert('Jaza shinikizo la damu(BP)');
            return false;
        }

        var mkojosukari;
        if($('#hapanasukari').is(':checked')){
          mkojosukari='H';
        }else if($('#ndiyosukari').is(':checked')){
           mkojosukari='N';
        }else{
            alert('Jaza kama ana sukari kwenye mkojo');
            return false;
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
        var maoni=$('#maoni').val();
        $.ajax({
            type: 'POST',
            url: 'requests/save_wajawazito.php',
            data: 'action=save&patient_ID=' + patient_ID + '&leo_Date=' + leo_Date + '&kijiji_jina='+kijiji_jina+'&mwenza='+mwenza+'&mwenyekitijina='+mwenyekitijina+'&anakadi='+anakadi+'&TT1date='+TT1date+'&TT2date='+TT2date+'&mimbaNo='+mimbaNo+'&mimbaumri='+mimbaumri+'&umezaaNo='+umezaaNo+'&watotohai='+watotohai+'&abortions='+abortions+'&FSB='+FSB+
                    '&mtotowamwishoAge='+mtotowamwishoAge+'&damuKiwango='+damuKiwango+'&BP='+BP+'&urefu='+urefu+'&mkojosukari='+mkojosukari+'&kufungaCS='+kufungaCS+'&under_20='+under_20+'&under_35='+under_35+'&ksmatokeoke='+ksmatokeoke+'&ksmatokeome='+ksmatokeome+'&kstibake='+kstibake+'&kstibame='+kstibame+'&ngmatokeoke='+ngmatokeoke+'&ngmatokeome='+ngmatokeome+
                    '&ngtibake='+ngtibake+'&ngtibame='+ngtibame+'&marudio_2='+marudio_2+'&marudio_3='+marudio_3+'&marudio_4='+marudio_4+'&marudio_5='+marudio_5+'&marudio_6='+marudio_6+'&marudio_7='+marudio_7+'&marudio_8='+marudio_8+'&marudio_9='+marudio_9+'&tayariVVUke='+tayariVVUke+'&tayariVVUme='+tayariVVUme+'&unasihike='+unasihike+'&unasihime='+unasihime+
                    '&amepimaVVUke='+amepimaVVUke+'&amepimaVVUme='+amepimaVVUme+'&kimpimotareheke='+kimpimotareheke+'&kimpimotareheme='+kimpimotareheme+'&matokeoVVU1ke='+matokeoVVU1ke+'&matokeoVVU1me='+matokeoVVU1me+'&unasihibaadayakupmake='+unasihibaadayakupmake+'&unasihibaadayakupmame='+unasihibaadayakupmame+'&matokeoVVU2='+matokeoVVU2+'&ushauriulishaji='+ushauriulishaji+
                    '&mrdt='+mrdt+'&hatipunguzo='+hatipunguzo+'&ipt1='+ipt1+'&ipt2='+ipt2+'&ipt3='+ipt3+'&ipt4='+ipt4+'&aina_1='+aina_1+'&aina_2='+aina_2+'&aina_3='+aina_3+'&aina_4='+aina_4+'&idadi_1='+idadi_1+'&idadi_2='+idadi_2+'&idadi_3='+idadi_3+'&idadi_4='+idadi_4+'&amebendazole='+amebendazole+'&rufaatarehe='+rufaatarehe+'&alikopelekwa='+alikopelekwa+'&rufaasababu='+rufaasababu+'&kituoalikotoka='+kituoalikotoka+'&maoni='+maoni,
            cache: false,
            success: function (html){
                alert(html);
            }
        });
    });
</script>
