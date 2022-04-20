
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
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));

    //display all items
    while ($row2 = mysqli_fetch_array($select_Patient_Details)) {

        $Today = Date("Y-m-d");
        $Date_Of_Birth = $row2['Date_Of_Birth'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y;

        $fname = explode(' ', $row2['Patient_Name'])[0];

        $mname = '';

        if (sizeof(explode(' ', $row2['Patient_Name'])) >= 3) {

            $mname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 2];

            $lname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 1];
        } else {

            $lname = explode(' ', $row2['Patient_Name'])[1];
        }
    }
}
?>  
<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">  
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA MTOTO NA MAMA BAADA YA KUJIFUNGUA</b></legend>
    <div class="tabcontents" style="height:400px;overflow:auto" >
        <ul style="">
            <li style=""><a href="#tabs-1">Hatua ya 1</a></li>
            <li style=""><a href="#tabs-2">Hatua ya 2</a></li>
        </ul>

        <!--first step-->  

        <div id="tabs-1">
            <center> 
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Namba ya Kadi RCH-4
                        </td>
                        <td width="40%">
                            <input  type="text" id="rchNo" name="tarehe1" value="">

                        </td>
                        <td  style="text-align:right;" width="20%">Tarehe ya kuandikishwa postnatal</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;" id="postnatal_Date" name="" type="text" >
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Jina la mama</td><td ><input name="jinakamili" value="" type="text"></td>
                        <td  colspan="" align="right" style="text-align:right;">Jina la Kitongoji/Mtaa</td><td>
                            <input name="" id="mtaa_jina" type="text" value="" style="width:240px;"></td> 
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Umri</td><td><input name="name" id="kijiji" type="text"></td>
                        <td  colspan="" align="right" style="text-align:right;">Amejifungua Mara Ngapi(Para)</td><td>
                            <input name="baloz" id="para" type="text" style="width:240px;"></td> 
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">Tarehe ya Kujifungua</td>
                        <td>
                            <input type="text" id="birth_date">
                        </td>
                    </tr>					
                </table> 

                <table align="left" style="width:100%">	   					
                    <tr><td>
                            <table  class="" border="0" style=" width:65%;margin-top:0px; " align="right" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Hali ya mama,mtoto</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">PMTCT</td></tr>
                                <td width="40%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Mama
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanhali_mamaH"><input type="radio" name='hali_mama' id='hali_mamaH'>Hai</span>
                                                <span class="pointer" id="spanhali_mamaA"><input type="radio" name='hali_mama' id='hali_mamaA'>Amekufa</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td >
                                                Mtoto/Watoto
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanhali_mtotoH"><input type="radio" name='hali_mtoto' id='hali_mtotoH'>Hai</span>
                                                <span class="pointer" id="spanhali_mtotoA"><input type="radio" name='hali_mtoto' id='hali_mtotoA'>Amekufa</span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td >
                                                Unyonyeshaji ndani ya saa moja
                                            </td>
                                            <td>

                                                <span class="pointer" id="spansaa_1_nyonyeshaH"><input type="radio" name='saa_1_nyonyesha' id='saa_1_nyonyeshaH'>Hapana</span>
                                                <span class="pointer" id="spansaa_1_nyonyeshaN"><input type="radio" name='saa_1_nyonyesha' id='saa_1_nyonyeshaN'>Ndiyo</span>

                                            </td>
                                        </tr>
                                    </table>


                                </td><td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Hali ya VVU kama inavyoonekana kwenye kadi </td>
                                            <td width="40%">
                                                <span class="pointer" id="spanvvu_haliN"><input type="radio" name='vvu_hali' id='vvu_haliN'>Negative</span>
                                                <span class="pointer" id="spanvvu_haliP"><input type="radio" name='vvu_hali' id='vvu_haliP'>Positive</span>
                                                <span class="pointer" id="spanvvu_haliU"><input type="radio" name='vvu_hali' id='vvu_haliU'>U</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Kipimo cha VVU wakati wa Post natal
                                            </td>
                                            <td>
                                                <span class="pointer" id="spanvvuPostnatalN"><input type="radio" name='vvuPostnatal' id='vvuPostnatalN'>Negative</span>
                                                <span class="pointer" id="spanvvuPostnatalP"><input type="radio" name='vvuPostnatal' id='vvuPostnatalP'>Positive</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td></tr>
                </table>



                <table style="margin-top:0px;">
                    <tr style="background-color:#006400;color:white">
                        <th  class="powercharts_td_left">Mahali alipojifungulia</th>
                    </tr>
                    <tr>
                        <td  colspan="" align="right" style="text-align:right;">Mahali alipojifungulia</td>
                        <td>
                            <span class="pointer" id="spanalipojifunguliaHF"><input type="radio" name='alipojifungulia' id='alipojifunguliaHF'>HF</span>
                            <span class="pointer" id="spanalipojifunguliaBBA"><input type="radio" name='alipojifungulia' id='alipojifunguliaBBA'>BBA</span>
                            <span class="pointer" id="spanalipojifunguliaH"><input type="radio" name='alipojifungulia' id='alipojifunguliaH'>H</span>
                            <span class="pointer" id="spanalipojifunguliaTBA"><input type="radio" name='alipojifungulia' id='alipojifunguliaTBA'>TBA</span>
                        </td> 
                    </tr>
                    <tr>
                        <td  colspan="" align="right" style="text-align:right;">Kada ya aliyemzalisha</td><td>
                            <input name="" id="mzalishaji_kada" type="text" style="width:240px;"></td> 
                    </tr>
                </table>


                </td>
                </tr>
                </table>

                <!--Last phase Starats here-->   

                <table align="left" style="width:100%">	   					
                    <tr><td>
                            <table  class="" border="0" style=" width:65%;margin-top:0px; " align="right" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Vipimo (mama)</td>
                                    <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Matiti</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tumbo la uzazi &amp;&amp; Hali ya Lochia</td></tr>
                                <td width="40%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                BP (mm Hg)
                                            </td>
                                            <td><input id="BP" type="text" name="tt1" style="width:140px;"></td>
                                        </tr>

                                        <tr>
                                            <td >
                                                HB (g/dl au % )
                                            </td>
                                            <td>
                                                <input id="HB" type="text" name="tt1" style="width:140px;" class="nn">
                                            </td>
                                        </tr>
                                    </table>


                                </td>
                                <td >
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Matiti 
                                            </td>
                                            <td> 
                                                <select style="width:200px;" id="Matiti" >
                                                    <option value="">

                                                    </option>
                                                    <option value="Chuchu zimechanika">
                                                        Chuchu zimechanika
                                                    </option>
                                                    <option value="Yana uambukizo">
                                                        Yana uambukizo(Mastitis)
                                                    </option>
                                                    <option value="Jipu">
                                                        Jipu
                                                    </option>
                                                    <option value="Limenywea vizuri(Normal)">
                                                        Limenywea vizuri(Normal)
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>


                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Tumbo la uzazi</td>
                                            <td width="25%">
                                                <select style="width:140px;" id="uzazi_tumbo">
                                                    <option value=""></option>
                                                    <option value="Kawaida">Kawaida</option>
                                                    <option value="Maumivu">Maumivu</option>
                                                    <option value="Kutonywea vizuri">Kutonywea vizuri</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Hali ya Lochia:Rangi
                                            </td>
                                            <td>
                                                <input type="text" style="width:140px;" name="mimbanamba" class="nn" id="Lochia">
                                            </td>
                                        </tr>
                                    </table>
                                </td>



                    </tr>
                </table>						
                <table style="margin-top:0px;">
                    <tr style="background-color:#006400;color:white">
                        <th  class="powercharts_td_left">Hudhurio</th>
                    </tr>
                    <tr>
                        <td>
                            Hudhurio
                        </td>
                        <td>
                            <select style="width:240px;" id="Hudhurio">
                                <option value="">

                                </option>
                                <option value="Masaa 48">
                                    Masaa 48
                                </option>
                                <option value="Siku 3-7">
                                    Siku 3-7
                                </option>
                                <option value="Siku 8-28">
                                    Siku 8-28
                                </option>
                                <option value="Siku 29-42">
                                    Siku 29-42 
                                </option>
                            </select>
                        </td>
                    </tr> 

                    <tr>
                        <td>
                            Tarehe ya Hudhurio
                        </td>
                        <td>
                            <input type="text" style="width:240px" readonly="true" id="tarehe_hudhurio" value="<?php echo date('d/m/y'); ?>">
                        </td>
                    </tr>
                </table>
                </td>
                </tr>
                <tr><td align="center" colspan="2" style="padding-left:400px;"> 
                        <!--<button id="next_button" style="cursor:pointer; width:60px" class='art-button-green' >Save</button>-->  
                    </td></tr>
                </table>

        </div>


        <!--step 2-->

        <div id="tabs-2">
            <table  class="" border="0"  align="left" style="width:48%">
                <tr>
                    <td  class="powercharts_td_left">

                        <table style="width:400px;">
                            <tr >
                                <td width="35%">Hali ya msamba</td>
                                <td width="35%">
                                    <select id="msamba" name="" style="width:200px">
                                        <option value="">~~~~~~~~~Select~~~~~~~~~</option>
                                        <option value="Mshono umeunga">Mshono umeunga</option>
                                        <option value="Umeachia">Umeachia</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Fistula</td>
                                <td >
                                    <select id="fistula" name="" style="width:200px">
                                        <option value="">~~~~~~~~~Select~~~~~~~~~</option>
                                        <option value="N">N</option>
                                        <option value="H">H</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Akili Timamu</td>
                                <td>
                                    <select id="Akili" name="" style="width:200px">
                                        <option value="">~~~~~~~~Select~~~~~~~~~</option>
                                        <option value="N">N</option>
                                        <option value="H">H</option>
                                    </select>
                                </td>
                            </tr>

                        </table>
                    </td>


                </tr>

<!--<tr><td colspan="2">&nbsp;</td></tr>-->
                <tr>
                    <td colspan="2" style="text-align:center; font-weight:bold; background-color:#006400;color:white">Uzazi wa mpango</td>
                </tr>

                <td  colspan="" align="right" style="text-align:right;">

                    <table>
                        <tr>
                            <td>
                                Uzazi wa mpango
                            </td>
                            <td>
                                <select id="uzazi_mpango">
                                    <option value="">

                                    </option>
                                    <option value="Ushauri umetolewa">Ushauri umetolewa</option>
                                    <option value="Amepatiwa kielelezo(IEC material)">Amepatiwa kielelezo(IEC material)</option>
                                    <option value="Amepata njia ya uzazi wa mpango wakati wa PPC">Amepata njia ya uzazi wa mpango wakati wa PPC</option>
                                    <option value="Amepata rufaa kupata njia ya uzazi wa mpango">Amepata rufaa kupata njia ya uzazi wa mpango</option>



                                </select>
                            </td>

                        </tr>

                        <tr>
                            <td>
                                Hudhurio la mtoto
                            </td>
                            <td>
                                <select id="mtoto_hudhurio">
                                    <option value="">

                                    </option>
                                    <option value="Masaa 48">
                                        Masaa 48
                                    </option>
                                    <option value="Siku 3-7">
                                        Siku 3-7
                                    </option>

                                    <option value="Siku 8-24">
                                        Siku 8-24
                                    </option>

                                    <option value="Siku 29-42">
                                        Siku 29-42
                                    </option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Tarehe ya hudhurio la mtoto
                            </td>
                            <td>
                                <input type="text" id="hudhurio_date">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Jinsi
                            </td>
                            <td>
                                <select id="jinsi_mtoto">
                                    <option value="">

                                    </option>
                                    <option value="Me">Me</option>
                                    <option value="Ke">Ke</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>

                <td>
            </table>

            <table  class="" border="0" style=" width:50%; height:430px; margin-top:-36px; " align="right" >
                <tr>
                    <td width="50%" class="powercharts_td_left" style="font-weight:bold; background-color:#006400;color:white">Dawa za nyongeza alizopewa mama</td></tr>
                <td width="40%"  colspan="" align="right" style="text-align:right;">
                    <table style="width:100%">
                        <tr>
                            <td>
                                Aina ya dawa
                            </td>
                            <td>
                                <select id="dawa_aina">
                                    <option value="">~~~~~~Chagua~~~~~~</option>
                                    <option value="I">Iron I</option>
                                    <option value="F">Folic Acid F</option>
                                    <option value="IFA">IFA</option>
                                </select>
                            </td>
                            <td>
                                Vitamin A
                            </td>
                            <td>
                                <input type="text" id="vitamin_A" placeholder="Idadi ya vitamin A">
                            </td>
                        </tr>


                        <tr>
                            <td>
                                Idadi ya dawa
                            </td>
                            <td>
                                <input  type="text" id="idadi_dawa" placeholder="Idadi ya dawa Iron/Folic" >
                            </td>

                            <td>
                                Chanjo ya TT
                            </td>
                            <td>
                                <input  type="text" id="ttchanjo">
                            </td>
                        </tr>      
                        <tr>

                            <td colspan="7">
                                <table width="100%">
                                    <tr>
                                        <td style="font-weight:bold; background-color:#006400;color:white" colspan="10">Vipimo/Huduma kwa mtoto &nbsp;&nbsp;&nbsp;

                                            Uambukizo wa mtoto
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Joto la mtoto
                                        </td>
                                        <td> 
                                            <input type="text" id="mtoto_jina">
                                        </td>


                                        <td>
                                            Kitovu
                                        </td>
                                        <td>
                                            <select id="kitovu">
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            Chanjo
                                        </td>
                                        <td> 
                                            <input type="text" id="chanjo">
                                        </td>


                                        <td>
                                            Ngozi
                                        </td>
                                        <td>
                                            <select id="Ngozi">
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Uzito wa mtoto(g/Kg)
                                        </td>
                                        <td> 
                                            <input type="text" id="uzito_mtoto" >
                                        </td>


                                        <td>
                                            Mdomo
                                        </td>
                                        <td>
                                            <select id="mdomo"> 
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            HB(g/dl au %)
                                        </td>
                                        <td> 
                                            <input type="text" id="HB_mtoto">
                                        </td>
                                        <td>
                                            Macho
                                        </td>
                                        <td>
                                            <select id="macho">
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>KMC</td>
                                        <td>
                                            <select id="kmc" style="width:220px">
                                                <option value=""></option>
                                                <option value="H">H</option>
                                                <option value="N">N</option>
                                            </select>
                                        </td>

                                        <td>
                                            Jaundice
                                        </td>
                                        <td>
                                            <select id="Jaundice">
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>

                                        <td>
                                            Uambukizo mkali
                                        </td>
                                        <td>
                                            <select id="uambukizo_mkali">
                                                <option value="">

                                                </option>
                                                <option value="N">
                                                    N
                                                </option>
                                                <option value="H">
                                                    H
                                                </option>
                                            </select>
                                        </td>
                                    </tr>

                                </table>
                            </td>


                        </tr>
                    </table>
                    <br />
                <center> <input type="button" id="save_data" value="Save Data" class="art-button-green"></center>
                <input type="hidden" id="motherID" value="<?php echo $_GET['pn']; ?>">
                </td></tr>
                </td> 
                </tr>

                <tr>

                </tr>

                <br><BR>
                <table style="margin-top:10px; float:left; width:48%;">
                    <tr style="background-color:#006400;color:white">
                        <th >ARV Prophlaxis &amp;&amp;Ulishaji wa mtoto</b></th>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        ARV
                                    </td>
                                    <td>
                                        <input type="text" id="ARV">
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Muda
                                    </td>
                                    <td>
                                        <input type="text" id="muda">
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Ulishaji wa mtoto
                                    </td>
                                    <td>
                                        <select id="ulishaje_mtoto" style="width:200px">
                                            <option value="">
                                                ~~~~~~~~~~~~~~~Select~~~~~~~~~~~
                                            </option>
                                            <option value="EBF">
                                                EBF
                                            </option>
                                            <option value="RF">
                                                RF
                                            </option>
                                            <option value="MF">
                                                MF
                                            </option>
                                        </select>
                                    </td>
                                </tr>

                            </table>

                        </td>
                        <td>

                        </td>

                    </tr>
                </table>

                <table style="margin-top:10px; float:left; width:48%;">
                    <tr>
                        <td width="50%" class="powercharts_td_left" style="font-weight:bold; background-color:#006400;color:white">Rufaa</td>
                    </tr>
                    <tr>
                        <td>
                            Alikopelekwa
                        </td>
                        <td>
                            <input type="text" id="alikopelekwa">
                        </td>


                    </tr>
                    <tr>
                        <td>
                            Alikotokea
                        </td>
                        <td><input type="text" id="alikotokea"></td>

                    </tr>
                    <tr>
                        <td>
                            Sababu ya rufaa/Maoni
                        </td>
                        <td>
                            <input type="text" id="rufaa_sababu">
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


       $('#spanalipojifunguliaHF').on('click', function () {
           $('#alipojifunguliaHF').prop('checked', true);
       });

       $('#spanalipojifunguliaBBA').on('click', function () {
           $('#alipojifunguliaBBA').prop('checked', true);
       });

       $('#spanalipojifunguliaH').on('click', function () {
           $('#alipojifunguliaH').prop('checked', true);
       });

       $('#spanalipojifunguliaTBA').on('click', function () {
           $('#alipojifunguliaTBA').prop('checked', true);
       });

       $('#spanhali_mamaH').on('click', function () {
           $('#hali_mamaH').prop('checked', true);
       });

       $('#spanhali_mamaA').on('click', function () {
           $('#hali_mamaA').prop('checked', true);
       });

//    

       $('#spanhali_mtotoH').on('click', function () {
           $('#hali_mtotoH').prop('checked', true);
       });

       $('#spanhali_mtotoA').on('click', function () {
           $('#hali_mtotoA').prop('checked', true);
       });


       $('#spansaa_1_nyonyeshaH').on('click', function () {
           $('#saa_1_nyonyeshaH').prop('checked', true);
       });

       $('#spansaa_1_nyonyeshaN').on('click', function () {
           $('#saa_1_nyonyeshaN').prop('checked', true);
       });



       $('#spanvvu_haliN').on('click', function () {
           $('#vvu_haliN').prop('checked', true);
       });

       $('#spanvvu_haliP').on('click', function () {
           $('#vvu_haliP').prop('checked', true);
       });

       $('#spanvvu_haliU').on('click', function () {
           $('#vvu_haliU').prop('checked', true);
       });



       $('#spanvvuPostnatalN').on('click', function () {
           $('#vvuPostnatalN').prop('checked', true);
       });

       $('#spanvvuPostnatalP').on('click', function () {
           $('#vvuPostnatalP').prop('checked', true);
       });

       $('#save_data').click(function () {
           var motherID = $('#motherID').val();
           var rchNo = $('#rchNo').val();
           var postnatal_Date = $('#postnatal_Date').val();
           var mtaa_jina = $('#mtaa_jina').val();
           var para = $('#para').val();
           var birth_date = $('#birth_date').val();
           var hali_mama;
           var hali_mtoto;
           var saa_1_nyonyesha;
           var vvu_hali;
           var vvuPostnatal;
           var alipojifungulia;
           if ($('#hali_mamaH').is(':checked')) {
               hali_mama = 'H';
           } else if ($('#hali_mamaA').is(':checked')) {
               hali_mama = 'A';
           } else {
               alert('Jaza hali ya mama');
               return false;
           }

           if ($('#hali_mtotoH').is(':checked')) {
               hali_mtoto = 'H';
           } else if ($('#hali_mtotoA').is(':checked')) {
               hali_mtoto = 'A';
           } else {
               alert('Jaza hali ya mtoto');
               return false;
           }
           if ($('#alipojifunguliaHF').is(':checked')) {
               alipojifungulia = 'HF';
           } else if ($('#alipojifunguliaBBA').is(':checked')) {
               alipojifungulia = 'BBA';
           } else if ($('#alipojifunguliaH').is(':checked')) {
               alipojifungulia = 'H';
           } else if ($('#alipojifunguliaTBA').is(':checked')) {
               alipojifungulia = 'TBA';
           } else {
               alert('Jaza mahali alipojifungulia');
               return false;
           }

           if ($('#saa_1_nyonyeshaH').is(':checked')) {
               saa_1_nyonyesha = 'H';
           } else if ($('#saa_1_nyonyeshaN').is(':checked')) {
               saa_1_nyonyesha = 'N';
           } else {
               alert('Jaza unyonyeshaji ndani ya saa 1');
               return false;
           }


           if ($('#vvu_haliN').is(':checked')) {
               vvu_hali = 'N';
           } else if ($('#vvu_haliP').is(':checked')) {
               vvu_hali = 'P';
           } else if ($('#vvu_haliU')) {
               vvu_hali = 'U';

           } else {
               alert('Jaza hali ya VVU');
               return false;
           }

           if ($('#vvuPostnatalN').is(':checked')) {
               vvuPostnatal = 'N';
           } else if ($('#vvuPostnatalP').is(':checked')) {
               vvuPostnatal = 'P';
           } else {
               alert('Jaza kama amepata kipimo cha VVU wakati wa Post natal');
               return false;
           }
           var mzalishaji_kada = $('#mzalishaji_kada').val();
           var BP = $('#BP').val();
           var HB = $('#HB').val();
           var Matiti = $('#Matiti').val();
           var uzazi_tumbo = $('#uzazi_tumbo').val();
           var Lochia = $('#Lochia').val();
           var Hudhurio = $('#Hudhurio').val();
           var tarehe_hudhurio = $('#tarehe_hudhurio').val();
           var msamba = $('#msamba').val();
           var fistula = $('#fistula').val();
           var Akili = $('#Akili').val();
           var uzazi_mpango = $('#uzazi_mpango').val();
           var mtoto_hudhurio = $('#mtoto_hudhurio').val();
           var hudhurio_date = $('#hudhurio_date').val();
           var jinsi_mtoto = $('#jinsi_mtoto').val();
           var dawa_aina = $('#dawa_aina').val();
           var vitamin_A = $('#vitamin_A').val();
           var idadi_dawa = $('#idadi_dawa').val();
           var ttchanjo = $('#ttchanjo').val();
           var mtoto_jina = $('#mtoto_jina').val();
           var kitovu = $('#kitovu').val();
           var chanjo = $('#chanjo').val();
           var Ngozi = $('#Ngozi').val();
           var uzito_mtoto = $('#uzito_mtoto').val();
           var mdomo = $('#mdomo').val();
           var HB_mtoto = $('#HB_mtoto').val();
           var macho = $('#macho').val();
           var kmc = $('#kmc').val();
           var Jaundice = $('#Jaundice').val();
           var uambukizo_mkali = $('#uambukizo_mkali').val();
           var ARV = $('#ARV').val();
           var muda = $('#muda').val();
           var ulishaje_mtoto = $('#ulishaje_mtoto').val();
           var alikopelekwa = $('#alikopelekwa').val();
           var alikotokea = $('#alikotokea').val();
           var rufaa_sababu = $('#rufaa_sababu').val();
           $.ajax({
               type: 'POST',
               url: 'requests/saverch_edit.php',
               data: 'action=save&rchNo=' + rchNo + '&postnatal_Date=' + postnatal_Date + '&mtaa_jina=' + mtaa_jina + '&para=' + para + '&birth_date=' + birth_date + '&hali_mama=' + hali_mama + '&hali_mtoto=' + hali_mtoto + '&saa_1_nyonyesha=' + saa_1_nyonyesha + '&vvu_hali=' + vvu_hali + '&vvuPostnatal=' + vvuPostnatal + '&alipojifungulia=' + alipojifungulia + '&mzalishaji_kada=' + mzalishaji_kada + '&BP=' + BP + '&HB=' + HB + '&Matiti=' + Matiti + '&uzazi_tumbo=' + uzazi_tumbo + '&Lochia=' + Lochia + '&Hudhurio='
                       + Hudhurio + '&tarehe_hudhurio=' + tarehe_hudhurio + '&msamba='
                       + msamba + '&fistula=' + fistula + '&Akili=' + Akili + '&uzazi_mpango=' + uzazi_mpango + '&mtoto_hudhurio='
                       + mtoto_hudhurio + '&hudhurio_date=' + hudhurio_date + '&jinsi_mtoto=' + jinsi_mtoto
                       + '&dawa_aina=' + dawa_aina + '&vitamin_A=' + vitamin_A + '&idadi_dawa=' + idadi_dawa + '&ttchanjo='
                       + ttchanjo + '&mtoto_jina=' + mtoto_jina + '&kitovu=' + kitovu + '&chanjo=' + chanjo + '&Ngozi='
                       + Ngozi + '&uzito_mtoto=' + uzito_mtoto + '&mdomo=' + mdomo + '&HB_mtoto=' + HB_mtoto + '&macho='
                       + macho + '&kmc=' + kmc + '&Jaundice=' + Jaundice + '&uambukizo_mkali=' + uambukizo_mkali + '&ARV=' + ARV + '&muda='
                       + muda + '&ulishaje_mtoto=' + ulishaje_mtoto + '&alikopelekwa=' + alikopelekwa + '&alikotokea=' + alikotokea + '&rufaa_sababu=' + rufaa_sababu + '&motherID=' + motherID,
               cache: false,
               success: function (html) {
                   alert(html);
               }
           });
       });

</script>