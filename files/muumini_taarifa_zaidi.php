
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
        <!--<a href='registerpatient.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
            ADD NEW PATIENT
        </a>-->
        <?php
    }
}
?>


<?php
if (isset($_SESSION['userinfo']) && isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != null && $_GET['Registration_ID'] != '') {
    ?>
<!--    <a href='editpatient.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormThisPatient=VisitorFormThisPatientThisPage' class='art-button-green'>
        EDIT CUSTOMER
    </a>-->
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
if(isset($_GET['Registration_ID'])){
    $muumini_id=$_GET['Registration_ID'];
    $select_Filtered_Members = mysqli_query($conn,"SELECT * FROM tbl_taarifa_muumini WHERE muumini_id='$muumini_id'") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_Filtered_Members)){
    $muumini_id = $row['muumini_id'];
    $Full_Name = $row['jina_kamili'];
    $mahali_zaliwa =  $row['mahali_zaliwa'];
    $tarehe_zaliwa =  $row['tarehe_zaliwa'];
    $tarehe_batizwa = $row['tarehe_batizwa'];
    $mahali_batizwa =  $row['mahali_batizwa'];
    $mahali_kipaimara =  $row['mahali_kipaimara'];
    $tarehe_kipaimara =  $row['tarehe_kipaimara'];
    $ushirika_kipaimara = $row['ushirika_kipaimara'];
    $dayosis_kipaimara =  $row['dayosis_kipaimara'];
    $shiriki_meza = $row['chakula_cha_bwana'];
    $aina_za_ndoa =  $row['aina_ndoa'];
    $ndoa_taarifa =  $row['taarifa_ndoa'];
    $jina_mke_mme =  $row['mume_mke'];
    $watoto_idadi = $row['watoto_idadi'];	
    $kazi_yangu = $row['kazi'];
    $kikundi_kipindi =  $row['kikundi_aina'];
    $mzee_kanisa =  $row['mzee_kanisa'];     
    $namba_simu =  $row['namba_simu'];   
    }
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
                                    <input type='text' name='jina_kamili' value="<?=$Full_Name?>" disabled="disabled" id='jina_kamili'>
                                </td>
                                <td style='text-align: right'>Mahali ulipozaliwa</td>
                                <td><input type='text' name='mahali_zaliwa' value="<?=$mahali_zaliwa?>" disabled="disabled" id='tarehe_zaliwa' ></td> 
                            </tr> 
                            <tr>
                                <td style='text-align: right'>Tarehe ya kuzaliwa</td>
                                <td><input type='text' name='tarehe_zaliwa' value="<?=$tarehe_zaliwa?>" disabled="disabled" id='datepicker'  ></td>
                                <td style='text-align: right'>Mahali ulipobatizwa</td>
                                <td><input type='text' name='mahali_batizwa' value="<?=$mahali_batizwa?>" disabled="disabled" id='tarehe_batizwa' ></td> 

                            </tr>
                            <tr>
                                <td style='text-align: right'>Tarehe ya kubatizwa</td>
                                <td><input type='text' name='tarehe_batizwa' value="<?=$tarehe_batizwa?>" disabled="disabled" id='datepicker'  ></td>
                                <td style='text-align: right'>Mahali Nilipata Kipaimara</td>
                                <td><input type='text' name='mahali_kipaimara' value="<?=$mahali_kipaimara?>" disabled="disabled" id='mahali_kipaimara' ></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Tarehe ya kipaimara</td>
                                <td><input type='text' name='tarehe_kipaimara' value="<?=$tarehe_kipaimara?>" disabled="disabled" id='tarehe_kipaimara' ></td>
                                <td style='text-align: right'>Ushirika Nilipata Kipaimara</td>
                                <td><input type='text' name='ushirika_kipaimara' value="<?=$ushirika_kipaimara?>" disabled="disabled" id='ushirika_kipaimara'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Dayosis Nilipata Kipaimara</td>
                                <td><input type='text' name='dayosis_kipaimara' value="<?=$dayosis_kipaimara?>" disabled="disabled" id='tarehe_kipaimara' ></td>
                                <td style='text-align: right'>Ninashiriki meza ya bwana</td>
                                <td><select name="shiriki_meza" disabled="disabled" >
                                        <option <?php if($shiriki_meza=='Hapana') {echo "selected";} ?> value="Hapana">Hapana</option>
                                        <option <?php if($shiriki_meza=='Ndiyo') {echo "selected";} ?>  value="Ndiyo">Ndiyo</option>
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
                                        <option value="Hapana">Kanisani</option>
                                        <option value="Hapana">Msikitini</option>
                                        <option value="Ndiyo">Serikalini</option>
                                        <option value="Ndiyo">Kimila</option>
                                    </select></td></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Jina la Mke/Mume</td>
                                <td><input type='text' name='jina_mke_mme' value="<?=$jina_mke_mme?>"  disabled="disabled" id='mke_mme' ></td>
                                <td style='text-align: right'>Familia Yetu Ina Watoto(IDADI)</td>
                                <td><input type='text' name='watoto_idadi' value="<?=$watoto_idadi?>" disabled="disabled" id='mke_mme' id='watoto_idadi'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Kazi Yangu</td>
                                <td><input type='text' name='kazi_yangu' value="<?=$kazi_yangu?>"  disabled="disabled"  id='mke_mme' ></td>
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
                                <td><input type='text' name='mzee_kanisa' value="<?=$mzee_kanisa?>" disabled="disabled"  id='mke_mme' ></td>
                                <td style='text-align: right'>Namba Yangu Ya Simu</td>
                                <td><input type='text' name='simu_namba' value="<?=$namba_simu?>"  disabled="disabled"  id='mke_mme' ></td>
                            </tr> 
                            <tr>
                                <td style='text-align: right'></td>
                                <td><input type='hidden' name='' id='' ></td>
                                <td style='text-align: right'></td>
                                <td><a href="church_member_buy_item.php?Registration_ID=<?=$muumini_id?>" class="art-button-green" >NUNUA</a>
                                <a href="ahadi_za_muumini.php?Registration_ID=<?=$muumini_id?>" class="art-button-green" >AHADI</a>
                                </td>
                                
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
include("./includes/footer.php");
?>