
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];
?>

<style type="text/css">
    .hiddenTag {
        display: none;
    }
</style>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
<a href='waumini_waliosajiliwa.php' class='art-button-green'>
            REGISTERED MEMBERS
        </a>
        <?php
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <!--<a href='searchvisitorsdeceasedpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            DECEASED PATIENTS
        </a>-->
        <?php
    }
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='church_member_buy_item.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
            ADD NEW COSTUMER
        </a>
        <?php
    }
}
?>


<?php
if (isset($_SESSION['userinfo']) && isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != null && $_GET['Registration_ID'] != '') {
    ?>
    <a href='editpatient.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormThisPatient=VisitorFormThisPatientThisPage' class='art-button-green'>
        EDIT CUSTOMER
    </a>
<?php } ?>
<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <!-- <a href='./receptionpricelist.php?PriceList=PriceListThisPage' class='art-button-green'>
        PRICE LIST
    </a> -->
<?php } ?>

<?php
if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
    ?>
    <input class='hiddenTag' type="button" name="Adhock_Search" id="Adhock_Search" value="Adhock SEARCH" class="art-button-green" onclick="Search_ePayment_Details()">
    <?php
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a class="hiddenTag" href='#' class='art-button-green' onClick="history.go(-1)">
            BACK
        </a>
        <?php
    }
}
?>

<?php
/*  $Today_Date = mysqli_query($conn,"select now() as today");
  while($row = mysqli_fetch_array($Today_Date)){
  $original_Date = $row['today'];
  $new_Date = date("Y-m-d", strtotime($original_Date));
  $Today = $new_Date;

  $age = $Today - $original_Date;
  } */
?>

<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->

<?php
//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

$get_reception_setting = mysqli_query($conn,"select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($get_reception_setting);
if ($no > 0) {
    while ($data = mysqli_fetch_array($get_reception_setting)) {
        $Reception_Picking_Items = $data['Reception_Picking_Items'];
    }
} else {
    $Reception_Picking_Items = 'no';
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

</style> 
<br>       
<fieldset style="margin-top:16px">  
    <legend align=center><b>TAARIFA ZA MUUMINI</b></legend>
    <center>         
        <table width=100%>
            <form method="post" action="#">
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'>Jina Kamili</td>
                                <td>
                                    <input type='text' name='jina_kamili' id='jina_kamili'>
                                </td>
                                <td style='text-align: right'>Mahali ulipozaliwa</td>
                                <td><input type='text' name='mahali_zaliwa'  id='tarehe_zaliwa' ></td> 
                            </tr> 
                            <tr>
                                <td style='text-align: right'>Tarehe ya kuzaliwa</td>
                                <td><input type='text' name='tarehe_zaliwa' id='datepicker'  ></td>
                                <td style='text-align: right'>Mahali ulipobatizwa</td>
                                <td><input type='text' name='mahali_batizwa'  id='tarehe_batizwa' ></td> 

                            </tr>
                            <tr>
                                <td style='text-align: right'>Tarehe ya kubatizwa</td>
                                <td><input type='text' name='tarehe_batizwa' id='date2'  ></td>
                                <td style='text-align: right'>Mahali Nilipata Kipaimara</td>
                                <td><input type='text' name='mahali_kipaimara' id='mahali_kipaimara' ></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Tarehe ya kipaimara</td>
                                <td><input type='text' name='tarehe_kipaimara' id='date2' ></td>
                                <td style='text-align: right'>Ushirika Nilipata Kipaimara</td>
                                <td><input type='text' name='ushirika_kipaimara' id='ushirika_kipaimara'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Dayosis Nilipata Kipaimara</td>
                                <td><input type='text' name='dayosis_kipaimara' id='tarehe_kipaimara' ></td>
                                <td style='text-align: right'>Ninashiriki meza ya bwana</td>
                                <td><select name="shiriki_meza">
                                        <option value="Hapana">Hapana</option>
                                        <option value="Ndiyo">Ndiyo</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Taarifa kuhusu ndoa</td>
                                <td><select name="ndoa_taarifa">
                                        <option value="">--Chagua taarifa ya ndoa--</option>
                                        <option value="Hapana">Hapana</option>
                                        <option value="Ndiyo">Ndiyo</option>
                                    </select></td></td>
                                <td style='text-align: right'>Aina Ya Ndoa</td>
                                <td><select name="aina_za_ndoa">
                                        <option value="">--Chagua aina ya ndoa--</option>
                                        <option value="Hapana">Hapana</option>
                                        <option value="Ndiyo">Ndiyo</option>
                                    </select></td></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Jina la Mke/Mume</td>
                                <td><input type='text' name='jina_mke_mme' id='mke_mme' ></td>
                                <td style='text-align: right'>Familia Yetu Ina Watoto(IDADI)</td>
                                <td><input type='text' name='watoto_idadi' id='watoto_idadi'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Kazi Yangu</td>
                                <td><input type='text' name='kazi_yangu' id='mke_mme' ></td>
                                <td style='text-align: right'>Napenda kujiunga na Kikundi/Kipindi</td>
                                <td><select name="kikundi_kipindi">
                                        <option value="">--Chagua Kikundi/Kipindi--</option>
                                        <option value="Vijana">Vijana</option>
                                        <option value="Wanawake">Wanawake</option>
                                        <option value="Wanaume">Wanaume</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Jina La Mzee Wangu wa Kanisa</td>
                                <td><input type='text' name='mzee_kanisa' id='mke_mme' ></td>
                                <td style='text-align: right'>Namba Yangu Ya Simu</td>
                                <td><input type='text' name='namba_simu' id='namba_simu' ></td>
                            </tr> 
                            <tr>
                                <td style='text-align: right'></td>
                                <td><input type='hidden' name='' id='' ></td>
                                <td style='text-align: right'></td>
                                <td><input name="submit" class="art-button-green" type="submit" value="Save Information"/></td>
                            </tr>                 
                        </table>
                    </td>
                    <td style="width: 60px">  
                    </td>
                </tr>
            </form>  
        </table>
    </center>
</fieldset>


<?php
if (isset($_POST['submit'])) {
    $Full_Name = mysqli_real_escape_string($conn,$_POST['jina_kamili']);
    $mahali_zaliwa = mysqli_real_escape_string($conn,$_POST['mahali_zaliwa']);
    $tarehe_zaliwa = mysqli_real_escape_string($conn,$_POST['tarehe_zaliwa']);
    $tarehe_batizwa = mysqli_real_escape_string($conn,$_POST['tarehe_batizwa']);
    $mahali_batizwa = mysqli_real_escape_string($conn,$_POST['mahali_batizwa']);
    $mahali_kipaimara = mysqli_real_escape_string($conn,$_POST['mahali_kipaimara']);
    $tarehe_kipaimara = mysqli_real_escape_string($conn,$_POST['tarehe_kipaimara']);
    $ushirika_kipaimara = mysqli_real_escape_string($conn,$_POST['ushirika_kipaimara']);
    $dayosis_kipaimara = mysqli_real_escape_string($conn,$_POST['dayosis_kipaimara']);
    $shiriki_meza = mysqli_real_escape_string($conn,$_POST['shiriki_meza']);
    $aina_za_ndoa = mysqli_real_escape_string($conn,$_POST['aina_za_ndoa']);
    $ndoa_taarifa = mysqli_real_escape_string($conn,$_POST['ndoa_taarifa']);
    $jina_mke_mme = mysqli_real_escape_string($conn,$_POST['jina_mke_mme']);
    $watoto_idadi = mysqli_real_escape_string($conn,$_POST['watoto_idadi']);
    $kazi_yangu = mysqli_real_escape_string($conn,$_POST['kazi_yangu']);
    $kikundi_kipindi = mysqli_real_escape_string($conn,$_POST['kikundi_kipindi']);
    $mzee_kanisa = mysqli_real_escape_string($conn,$_POST['mzee_kanisa']);
    $namba_simu=mysqli_real_escape_string($conn,$_POST['namba_simu']);
    $query = "INSERT INTO tbl_taarifa_muumini(namba_simu,jina_kamili,mahali_zaliwa,tarehe_zaliwa,tarehe_batizwa,mahali_batizwa,mahali_kipaimara,tarehe_kipaimara,ushirika_kipaimara,dayosis_kipaimara,chakula_cha_bwana,aina_ndoa,taarifa_ndoa,mume_mke,watoto_idadi,kazi,kikundi_aina,mzee_kanisa) "
            . "VALUES('$namba_simu','$Full_Name','$mahali_zaliwa','$tarehe_zaliwa','$tarehe_batizwa','$mahali_batizwa','$mahali_kipaimara','$tarehe_kipaimara','$ushirika_kipaimara','$dayosis_kipaimara','$shiriki_meza','$aina_za_ndoa','$ndoa_taarifa','$jina_mke_mme','$watoto_idadi','$kazi_yangu','$kikundi_kipindi','$mzee_kanisa')";
    $insert = mysqli_query($conn,$query) or die(mysqli_error($conn));
}
include("./includes/footer.php");
?>