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
        echo "<a href='searchvisitorsLabourrch.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
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
				select pr.Patient_Name,pr.Phone_Number,pr.Emergence_Contact_Name,pr.Ward,pr.Date_Of_Birth,pr.Member_Number,pr.Gender,pr.registration_id,pr.Sponsor_ID from
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
        $age = $diff->m;
        $age = $diff->d;

        $fname = explode(' ', $row2['Patient_Name'])[0];

        $mname = '';

        if (sizeof(explode(' ', $row2['Patient_Name'])) >= 3) {

            $mname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 2];

            $lname = explode(' ', $row2['Patient_Name'])[sizeof(explode(' ', $row2['Patient_Name'])) - 1];
        } else {
            $lname = explode(' ', $row2['Patient_Name'])[1];
        }
        $name = $row2['Patient_Name'];
        $gende = $row2['Gender'];
        $regNo = $row2['registration_id'];
        $DOB = $row2['Date_Of_Birth'];
        $ward = $row2['Ward'];
        $kin = $row2['Emergence_Contact_Name'];
        $phone = $row2['Phone_Number'];
        $sponsor_id = $row2['Sponsor_ID'];
    }

    // echo $sponsor_id;

    $sponsor_name = getSponsorName($sponsor_id);
    

//    Hospital address
    $hospital_details = mysqli_query($conn,"SELECT Hospital_Name,Tin FROM tbl_system_configuration");
    while ($result = mysqli_fetch_assoc($hospital_details)) {
        $hospital_name = $result['Hospital_Name'];
        $hospital_no = $result['Tin'];
    }

//    Admission details
    $admission_data = mysqli_query($conn,"SELECT Admission_Date_Time,Admission_Employee_ID,Registration_ID,Employee_ID,Employee_Name FROM tbl_admission JOIN tbl_employee ON Admission_Employee_ID=Employee_ID WHERE Registration_ID='$pn'");
    while ($results = mysqli_fetch_assoc($admission_data)) {
        $admission = $results['Admission_Date_Time'];
        $employee = $results['Employee_Name'];
    }

// get patient saved data
    include("get_labour_data.php");
}

function getSponsorName($sponsor_id)
{
    $sql = "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$sponsor_id'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $Guarantor_Name;
}

// print_r(getLabTestItems($pn));
function getLabTestItems($pn)
{
    $today = Date("Y-m-d");
    $response = array();
    $sql = "SELECT ilc.Item_ID FROM tbl_payment_cache pc JOIN tbl_item_list_cache ilc ON pc.Payment_Cache_ID = ilc.Payment_Cache_ID WHERE pc.Registration_ID='$pn' AND Date(ilc.Transaction_Date_And_Time)='$today'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        array_push($response, getItemName($Item_ID));
    }

    return $response;
}

function getItemName($item_id)
{
    $product_name = "";
    $sql = "SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            extract($row);
            $product_name = $Product_Name;
        }
    } else {
        $product_name = "";
    }
    return $product_name;
}
?>  
<fieldset style="margin-top:1px;overflow-y:hidden; height:475px">  
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>LABOUR,DELIVERY,POSTNATAL CASE NOTES</b></legend>
    <div class="tabcontents" style="height:400px;overflow-x:hidden;overflow-y:auto">
        <!--first step-->  

        <div id="tabs-1">
            <center> 
                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="50%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">ADMISSION INFORMATION </td> <td style="font-weight:bold; background-color:#006400;color:white" width="50%">PERSONAL INFORMATION</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Name of health facility:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="mother_name" value="<?php echo $hospital_name; ?>" readonly="true">

                                            </td>


                                        </tr>

                                        <tr>
                                            <td >
                                                Hospital Reg. no:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="hospital_no" readonly="true" value="<?php echo $hospital_no; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Date of Admission:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="admissionDate" value="" readonly="true">

                                            </td>

                                        </tr>
            <input type="hidden"  id="sponsor_id" value="<?= $sponsor_id ?>">
                                        <tr>
                                            <td >
                                                Admitting Nurse/doctor(name):
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="admitting_doctor" value="" readonly="true">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Admitted from:
                                            </td>
                                            <td>
                                                <span id="admitedhome"> <input type="radio" name="admitted" id="home">Home</span>
                                                <span id="admitedward"> <input type="radio" name="admitted" id="ward">Antenatal Ward</span>
                                            </td>

                                        </tr>


                                        <tr>
                                            <td >
                                                Referrred from:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="reffered_from" value="<?php
                                                                                                                echo $reffered_from = !empty($reffered_from) ? $reffered_from : ""; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Reason for referral/management received:
                                            </td>
                                            <td>
                                                <textarea style="width:400px" id="referral_reasons">
                                                   
                                                </textarea>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Danger signs/management received:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="danger_signs">

                                            </td>

                                        </tr>

                                    </table>


                                </td>


                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Surname:</td>
                                            <td> 
                                                <input type="text" style="width:400px;" id="patient_no">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Other Names:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" id="" value="<?php echo $name; ?>" readonly="true">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Date of Birth:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" id="birth_datex" value="<?php echo $DOB; ?>" readonly="true">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Husband's/Partners's name:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" id="partner_name" >

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Residence(Village/street):
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" value="<?php echo $ward; ?>" readonly="true">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Permanent Address:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" value="<?php echo $ward; ?>" readonly="true">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Next of kin:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" id="next_kin" value="<?php echo $kin; ?>" readonly="true" >

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Cell phone
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px;" name="mimbanamba" value="<?php echo $phone; ?>" readonly="true">

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
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">OBSTETRIC HISTORY</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">OBSTETRIC HISTORY</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">PAST OBSTETRIC HISTORY</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                GRAVIDA:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="gravida" value="<?php echo $gravida = !empty($gravida) ? $gravida : ''; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                PARITY:
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="parity" value="<?php echo $parity = !empty($parity) ? $parity : ''; ?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                LIVING CHILDREN
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="current_children" value="<?php echo $current_children = !empty($current_children) ? $current_children : ''; ?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td><td >           
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                ABORTION:
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="abortion" value="<?php echo $abortion = !empty($abortion) ? $abortion : ''; ?>">

                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                LNMP:
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="lnmp" value="<?php echo $lnmp = !empty($lnmp) ? $lnmp : ''; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                EDD
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="edd" value="<?php echo $edd = !empty($edd) ? $edd : ''; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                GA:
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="ga" value="<?php echo $ga = !empty($ga) ? $ga : ''; ?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">1</td>
                                            <td width="25%"> 
                                                <input type="text" style="width:370px;" id="obste_history_1" value="<?php echo $obste_history_1 = !empty($obste_history_1) ? $obste_history_1 : ''; ?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:370px;" name="mimbanamba" id="obste_history_2" value="<?php echo $obste_history_2 = !empty($obste_history_2) ? $obste_history_2 : ''; ?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                3
                                            </td>
                                            <td>
                                                <input type="text" style="width:370px;" name="mimbanamba" id="obste_history_3" value="<?php echo $obste_history_3 = !empty($obste_history_3) ? $obste_history_3 : ''; ?>">

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
                                    <!--<td width=' 100 % '>CURRENT PREGNANCY</td>-->
                                    <td width="50%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">CURRENT PREGNANCY</td> <td style="font-weight:bold; background-color:#006400;color:white" width="50%"></td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td><b><center>ANTENATAL CLINIC FINDINGS </center></b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                1.Number of visits:
                                            </td>
                                            <td>
                                                <input type="text" style="width:300px" id="visits" value="<?php echo $visits = !empty($visits) ? $visits : ''; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                2.IPT doses:
                                            </td>
                                            <td>
                                                <input type="text" style="width:300px" id="IPT_doses" value="<?php echo $IPT_doses = !empty($IPT_doses) ? $IPT_doses : ''; ?>">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                3.TT doses:
                                            </td>
                                            <td>
                                                <input type="text" style="width:300px" id="TT_doses" value="<?php echo $TT_doses = !empty($TT_doses) ? $TT_doses : ''; ?>">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                4.ITN used:
                                            </td>
                                            <td>
                                                <input type="text" style="width:300px" id="ITN_doses" value="<?php echo $ITN_doses = !empty($ITN_doses) ? $ITN_doses : ''; ?>">
                                            </td>

                                        </tr>

                                    </table>
                                </td><td >           
                                    <table width="100%">
                                        <tr> 
                                            <td><b>BLOOD GROUP:</b></td>
                                            <td><input type="text" id="bloodgroup" value="<?php echo $bloodgroup = !empty($bloodgroup) ? $bloodgroup : ''; ?>"> </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;">
                                                5.Last measured Hb:
                                            </td>
                                            <td>
                                                <input type="text" style="width:280px" id="last_Hb" value="<?php echo $last_Hb = !empty($last_Hb) ? $last_Hb : ''; ?>">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                6.PMTCT:
                                            </td>
                                            <td>
                                                <input type="text" style="width:280px" id="pmtct" value="<?php echo $pmtct = !empty($pmtct) ? $pmtct : ''; ?>">
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                7.ART/Prophylaxis:
                                            </td>
                                            <td>
                                                <input type="text" style="width:280px" id="art" value="<?php echo $art = !empty($art) ? $art : ''; ?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                8.VDRL:
                                            </td>
                                            <td>
                                                <input type="text" style="width:280px" id="vdrl" value="<?php echo $vdrl = !empty($vdrl) ? $vdrl : ''; ?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td>       
                    </tr>
                </table>       
                </td>
                </tr>
                </table>


                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~last but one plzzzzzzzzzzzzzzzzzzzzz~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">CURRENT LABOUR(HISTORY)</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%"></td></tr>

                                <td >           
                                    <table width="100%">
                                        <tr>
                                            <td>Labour onset</td>
                                            <td width="70%"> 
                                                <input type=' text '  style="width:100%" name='labour_onset' id="labour_onset" value="<?php echo $labour_onset = !empty($labour_onset) ? $labour_onset : ''; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Membranes ruptured:</td>
                                            <td width="70%">
                                                <span id="radioNo" style="font-weight:bold"> <input type="radio" name="membranes" id="No" value="No" <?php echo $membrane_rapture_no = !empty($membrane_rapture_no) ? $membrane_rapture_no : ''; ?> 
                                                > <label for="No">No</label></span>
                                                <span id="radioYes" style="font-weight:bold"> <input type="radio" name="membranes" id="Yes" value="Yes" 
                                                <?php echo $membrane_rapture_yes = !empty($membrane_rapture_yes) ? $membrane_rapture_yes : ''; ?>
                                                ><label for="Yes">Yes</label></span>

                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Fetal movements</td>
                                            <td width="70%"> 
                                                <span id="absnt" style="font-weight:bold"> <input type="radio" name="movement" id="Absent" value="Absent"  
                                                <?php echo $fetal_mvt_absent = !empty($fetal_mvt_absent) ? $fetal_mvt_absent : ''; ?>
                                                ><label for="Absent">Absent</label></span>
                                                <span id="presnt" style="font-weight:bold"> <input type="radio" name="movement" id="Present" value="Present" 
                                                <?php echo $fetal_mvt_present = !empty($fetal_mvt_present) ? $fetal_mvt_present : ''; ?>
                                                ><label for="Present">Present</label></span>

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
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Rufaa</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Maelezo mengineyo/maoni</td></tr>

                                <td >           
                                    <table width="100%">
                                        <tr>
                                            <td>General condition:</td>
                                            <td width="70%"> 
                                                <input type="text" name="general" id="general" style="width:100%" value="<?php echo $general = !empty($general) ? $general : ''; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pulse rate:</td>
                                            <td width="70%"> 
                                                <input type="text" id="pulse_rate"  name="pulse_rate" style="width:100%" value="<?php echo $pulse_rate = !empty($pulse_rate) ? $pulse_rate : ''; ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Sababu ya rufaa</td>
                                            <td width="70%"> 
                                                <input type="text" name="rufaasababu" id="rufaasababu" style="width:100%"  value="<?php echo $rufaasababu = !empty($rufaasababu) ? $rufaasababu : ''; ?>">
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>

                                            <td width="100%"> 
                                                <textarea style="width:100%;height:90px;text-align:left" id="maoni" >
                                                <?php echo $maoni = !empty($maoni) ? $maoni : ''; ?>
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

                <!-- select investigations -->

                <div id="investigation">
        <h3 style="margin-left: 100px">Investigation & Results</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr><td style="text-align:right;">Laboratory</td><td>
                <textarea style='width:88%;resize:none;padding-left:5px;' readonly='readonly' id='laboratory' name='laboratory'><?php $n = sizeof(getLabTestItems($pn));
                                                                                                                                for ($i = 0; $i < $n; ++$i) {
                                                                                                                                    echo getLabTestItems($pn)[$i] . ', ';
                                                                                                                                }
                                                                                                                                ?>                                                                                                            </textarea>

                <input type='button'  id='select_Laboratory' name='select_Laboratory'  value='Select'  class='art-button confirmGetItem' onclick="getItem()" >       
                 </td>
        </tr>
</table>
</center>
</div>

                
                 <!-- end investigations -->
                    <tr>
                        <td>
                    <center> 
                        <input type="button" value="Save" id="save_data" class="art-button-green" style="width:200px"> 
                    </center>
                    <input type="hidden" id="patient_ID" value="<?php echo $_GET[' pn']; ?>">
                    </td>
                    </tr>
                </table>


 

        </div>

   

    </div>

    
</fieldset>

<div id="showdataConsult" style="width:100%;overflow-x:hidden;height:800px;">
            <div id="myConsult">
            </div>
        </div>
<?php
include("./includes/footer.php");
?>

<link href="css/jquery-ui.css" rel="stylesheet">
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.form-validator.min.js"></script>
<style>
    #admitedhome:hover{
        cursor:pointer;
    }
    #admitedward:hover{
        cursor: pointer;
    }
    #myConsult{
        margin-top:15px;
    }
</style>

<script>
$(document).ready(function(){
    // getLabTestItem();

    $("#showdataConsult").dialog({autoOpen: false, width: '90%',title: 'SELECT  LAB ITEMS', modal: true});    
})
    $(".tabcontents").tabs();
    $('#admissionDate').datepicker({
                                                                                                            dateFormat : 'yy-mm-dd' ,
                                                                                                                changeMonth : true,
                                                                                                                changeYear : true
                                                                                                        }
                                                                                                        );

                                                                                                        $('#admitedhome') . on('click', function () {
                                                                                                            $('#home') . prop('checked', true);
                                                                                                        });

                                                                                                        $('#admitedward') . on('click', function () {
                                                                                                            $('#ward') . prop('checked', true);
                                                                                                        });


                                                                                                        $('#absnt') . on('click', function () {
                                                                                                            $('#Absent') . prop('checked', true);
                                                                                                        });

                                                                                                        $('#presnt') . on('click', function () {
                                                                                                            $('#Present') . prop('checked', true);
                                                                                                        });

                                                                                                        $('#radioYes') . on('click', function () {
                                                                                                            $('#Yes') . prop('checked', true);
                                                                                                        });

                                                                                                        $('#radioNo') . on('click', function () {
                                                                                                            $('#No') . prop('checked', true);
                                                                                                        });


        $('#save_data') . click(function () {
            var hospital_no = $ ('#hospital_no') . val();
            var admissionDate = $('#admissionDate') . val();
            var admitting_doctor = $('#admitting_doctor') . val();
            var admitted_from;
              if ($('#home') . is(':checked')) {
                     admitted_from = 'Home';
              } else if ($('#ward') . is(':checked')) {
                    admitted_from = 'ward';
              } else {
                 alert('Chagua mahali alipotoka(Admitted from:)');
                   return false;
                   }                                                                                                   var reffered_from = $('#reffered_from') . val();
                   var danger_signs = $('#danger_signs') . val();                                                     var patient_ID = $('#patient_ID') . val();                                                          var partner_name = $('#partner_name') . val();                                                      var gravida = $('#gravida') . val();                                                                var parity = $('#parity') . val();                                                                  var current_children = $('#current_children') . val();
                   var abortion = $('#abortion') . val();                                                             var lnmp = $('#lnmp') . val();
                   var edd = $('#edd') . val();
                   var ga = $('#ga') . val();
                   var obste_history_1 = $('#obste_history_1') . val();
                   var obste_history_2 = $('#obste_history_2') . val();
                   var obste_history_3 = $('#obste_history_3') . val();
                   var visits = $('#visits') . val();
                   var IPT_doses = $('#IPT_doses') . val();
                   var TT_doses = $('#TT_doses') . val();
                   var ITN_doses = $('#ITN_doses') . val();
                   var bloodgroup = $('#bloodgroup') . val();
                   var last_Hb = $('#last_Hb') . val();
                   var pmtct = $('#pmtct') . val();
                   var art = $('#art') . val();
                   var vdrl = $('#vdrl') . val();
                   var labour_onset = $('#labour_onset') . val();
                   if (gravida . trim() == "") {                                                                                             $('#gravida') . css("border", "red solid 2px");
                   } else if (gravida . trim() != "") {
                     $('#gravida') . css("border", "solid 1px");
                    }
                   if (partner_name . trim() == "") {
                       $('#partner_name') . css("border", "red solid 2px");
//            return false;
                  } else if (partner_name . trim() != "") {
                        $('#partner_name') . css("border", "solid 1px");
                  }

                var membranes_rapture;
                var fetal_mvt;
                  if ($('#No') . is(':checked')) {
                     membranes_rapture = 'No';
                  } else if ($('#Yes') . is(':checked')) {
                     membranes_rapture = 'Yes';
                  } else {
                    alert('Check membranes rapture to continue!');
                  return false;
                }
               if ($('#Absent') . is(':checked')) {
                   fetal_mvt = 'Absent';
               } else if ($('#Present') . is(':checked')) {
                   fetal_mvt = 'Present';
               } else {
                  alert('Select fetal movements');
                return false;
               }
              var general = $('#general') . val();
            var pulse_rate = $('#pulse_rate') . val();
            var rufaasababu = $('#rufaasababu') . val();
            var maoni = $('#maoni') . val();
             $ . ajax({
                 type :'POST' ,
                 url : 'requests/save_labour.php' ,
                 data : 'action=save&hospital_no=' + hospital_no + '&admissionDate=' + admissionDate + '&admitting_doctor=' + admitting_doctor + '&admitted_from=' + admitted_from + '&reffered_from=' + reffered_from + '&danger_signs=' + danger_signs +'&patient_ID=' + patient_ID + '&partner_name=' + partner_name + '&gravida=' + gravida + '&parity=' + parity + '&current_children=' + current_children + '&abortion=' + abortion + '&lnmp=' + lnmp + '&edd=' + edd + '&ga=' + ga + '&obste_history_1=' + obste_history_1+ '&obste_history_2=' + obste_history_2 + '&obste_history_3=' + obste_history_3 + '&visits=' + visits + '&IPT_doses=' + IPT_doses + '&TT_doses=' + TT_doses + '&ITN_doses=' + ITN_doses + '&bloodgroup=' + bloodgroup + '&last_Hb=' + last_Hb + '&pmtct=' + pmtct + '&art=' +art + '&vdrl=' + vdrl + '&labour_onset=' + labour_onset + '&membranes_rapture=' + membranes_rapture + '&fetal_mvt=' + fetal_mvt + '&general=' + general + '&pulse_rate=' + pulse_rate + '&rufaasababu=' + rufaasababu + '&maoni=' + maoni,
                 cache : false,
                 success : function (html) {
                   alert(html);
                 }
                 });
                });




    //start lab module
    var lab_test=[];
    $("#select_Laboratory").click(function(){
        getItem();
    })


function getItem(){
    
    $("#myConsult").html(`<?php include('select_lab_item_labour.php'); ?>`);

    $("#showdataConsult").position({
        my:"center",
        at:"center",
        of:window,
    })
    
    $("#showdataConsult").dialog("open")
// when item clicked do below
    $(".item").click(function(){
        var item_id= $(this).attr("id");
        var item_name = $(this).attr("item-name")
        $("#item_name").val(item_name);
        $("#price").val(get_lab_item_price(item_id))
        $("#item-id").val(item_id);
    })

// get lab item price
    function get_lab_item_price(item_id){
        var sponsor_id =  $("#sponsor_id").val();
        // alert(sponsor_id)
        var data={
            sponsor_id:sponsor_id,
            item_id:item_id,
        }

        var item_price="";
        $.ajax({
            url:"get_lab_item_price.php",
            method:"GET",
            data:data,
            async:false,
            success:function(data){
                
                item_price = data;
                
            }
        })

        if(item_price==0){
            $("#error_msg").html("<h3 style=\"color:red\">Item price is not set,Please contact system admin for this item</h3>")
            $("#price").css("border","1px solid red")
        }else{
            $("#price").css("border","1px solid grey")
            $("#error_msg").html("")
        }
        return item_price;
    }

// add item to cache table for payment
    $("#add").click(function(){
       
        var price = $("#price").val();
        var item_name = $("#item_name").val()
        var item_id = $("#item-id").val();
    
        if(price==0){
            $("#error_msg").html("<h4 style=\"color:red\">You can not add this item, it has no price Please contact system admin</h4>")
            $("#price").css("border","1px solid red")
        }else{

            $("#selected-item").append('<div style="margin-left:5px;margin-right:5px;margin-bottom:10px;color:#000;background:#f2f4f6; border-radius:3px solid #f2f3f4 !important">'+
    +'<span style="padding-left:10px; width:500px">'+item_name+'</span><span style="margin-left:15px">'+price+'</span><span style="margin-left:10px"><button style="height:30px !important;">remove</button></span></div>')
        
        insertItemInCacheTable(item_id)
        }
        
        
    })

    $("#search_item").keyup(function(){
        
        var key_search = $("#search_item").val().trim()
    
        var key = $("#sec2");
        var key1 = $("#sec1");
        $.ajax({
            url:"select_lab_item_laboratory.php",
            post:"GET",
            data:{key_search:key_search},
            success:function(data){
                var response = JSON.parse(data.trim())
                key1.html("")
                for(var i=0; i<response.length;++i){
                    key1.append('<div>'+
                        '<label><span></span><input class="item" id="'+response[i]['Item_ID']+'" type="radio" name="'+response[i]['product_name']+'">'+response[i]['product_name']+'</label></div>');
    
                }

        $(".item").click(function(){

            var item_id= $(this).attr("id");
            
            var item_name = $(this).attr("name")
        
            $("#item_name").val(item_name);
            $("#price").val(get_lab_item_price(item_id))
            $("#item-id").val(item_id);
            // $("#status").val(item_id);
        })
        
                
            },erro:function(xhl,ajaxOptions,thrownerror){
                console.log(throwerror)
            }
 
        })

       
    })




    function insertItemInCacheTable(item_id){
        
        var location = $("#location").val();
        var patient_id =<?= $pn ?>;
        var sponsor_id =  $("#sponsor_id").val();
        var item_id = $("#item-id").val();
        var sponsor_name =" <?= $sponsor_name ?>";
        
        var billing_type = getBillingType(sponsor_id);
        var employee_id = <?= $_SESSION['userinfo']['Employee_ID'] ?>;
        if(billing_type=="Outpatient Cash"){
        
        var transaction_type="Cash";
        }else if(billing_type=="Outpatient Credit"){
            
        var transaction_type="Credit";
        }
        
        var transaction_status="active";
        var price=$("#price").val();
        var doctor_comment=$("#relevent-notes").val();
        var quantity = $("#quantity").val();
        
        var data={
            doctor_comment:doctor_comment,
            patient_id:patient_id,
            sponsor_id:sponsor_id,
            billing_type:billing_type,
            sponsor_name:sponsor_name,
            transaction_type:transaction_type,
            transaction_status:transaction_status,
            employee_id:employee_id,
            location:location,
            item_id:item_id,
            price:price,
            quantity:quantity,
        }

        $.ajax({
            url:"add_lab_item_to_cache_table.php",
            method:"GET",
            data:data,
            success:function(data){
              var lab = JSON.parse(data);
              console.log(lab)
              lab_test.push(lab);  
            }
        })
    }

    $("#done").click(function(){
        $("#showdataConsult").dialog("close");
        
        lab_test.forEach(function(data){
            $("#laboratory").append(lab_test+",  ");    
        })
        console.log(lab_test);
    })
}



function getBillingType(sponsor_id){
        var billing_type="";

        $.ajax({
            url:"get_billing_type.php",
            method:"GET",
            data:{sponsor_id:sponsor_id},
            async:false,
            success:function(data){
                if(data==="credit"){
                    billing_type="Outpatient Credit";
                }else{
                    billing_type="Outpatient Cash";
                }
                
            }
        })

        return (billing_type);
    }

    function getLabTestItem(){
        var patient_id=<?= $pn ?>;
        alert(patient_id)
    }
</script>