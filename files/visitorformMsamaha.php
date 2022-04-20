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



if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

$pf3_ID = '';

if (isset($_POST['pf3_Description'])) {
    $pf3_Description = mysqli_real_escape_string($conn,$_POST['pf3_Description']);
    $Police_Station = mysqli_real_escape_string($conn,$_POST['Police_Station']);
    $Pf3_Number = mysqli_real_escape_string($conn,$_POST['Pf3_Number']);
    $Relative = mysqli_real_escape_string($conn,$_POST['Relative']);
    $Phone_No_Relative = mysqli_real_escape_string($conn,$_POST['Phone_No_Relative']);
    $Reason_ID = mysqli_real_escape_string($conn,$_POST['P_Reason']);

    $file_name = $_FILES['pf3attachment']['name'];
    $file_size = $_FILES['pf3attachment']['size'];
    $file_type = $_FILES['pf3attachment']['type'];
    $file_tmp_name = $_FILES['pf3attachment']['tmp_name'];

    if (!empty($file_name)) {
        $file_name = $Registration_ID . date('Ymdhsi') . $file_name;
    } else {
        $file_name = '';
    }

    $mysql = mysqli_query($conn,"INSERT INTO tbl_pf3_patients VALUES('','$Registration_ID','','$file_name',NOW(),'$pf3_Description','$Police_Station','$Pf3_Number','$Relative','$Phone_No_Relative','$Reason_ID')") or die(mysqli_error($conn));

    if (!empty($file_name)) {
        move_uploaded_file($file_tmp_name, 'pf3_attachment/' . $file_name);
    }

    $rs = mysqli_query($conn,"SELECT pf3_ID FROM tbl_pf3_patients ORDER BY pf3_ID DESC LIMIT 1");
    $row = mysqli_fetch_assoc($rs);

    $pf3_ID = $row['pf3_ID'];
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            REGISTERED PATIENTS
        </a>
    <?php
    }
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='msamaha.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
            ADD NEW PATIENT
        </a>
    <?php
    }
}
?>


<?php
    if(isset($_SESSION['userinfo']) && isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != null && $_GET['Registration_ID']!= ''){  
?>
    <a href='editpatientMsamaha.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormThisPatient=VisitorFormThisPatientThisPage' class='art-button-green'>
        EDIT PATIENT
    </a>
<?php  } ?>

<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='./receptionpricelist.php?PriceList=PriceListThisPage' class='art-button-green'>
        PRICE LIST
    </a>
<?php } ?>

<?php
if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
    ?>
    <input type="button" name="Adhock_Search" id="Adhock_Search" value="Adhock SEARCH" class="art-button-green" onclick="Search_ePayment_Details()">
    <?php
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
        <a href='msamahapanel.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
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
//    select patient information to perform check in process
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,
                                    pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID,sp.Sponsor_ID, sp.Exemption
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) { 
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Region = $row['Region'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Tribe = $row['Tribe'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Exemption = strtolower($row['Exemption']);
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
        /* $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->m." Months";
          }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Tribe = '';
        $Sponsor_ID = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Exemption = 'no';
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Tribe = '';
    $Sponsor_ID = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Exemption = 'no';
}

    //Check if Exemption details available
    $Exemption_Details = 'available';
    if($Exemption == 'yes'){
        $verify = mysqli_query($conn,"select msamaha_ID from tbl_msamaha where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($verify);
        if($nm < 1){
            $Exemption_Details = 'not available';
        }
    }
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

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
    <legend align=center><b>VISITORS PAGE</b></legend>
    <center>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">           
            <table width=100%>
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'>Patient Name</td>
                                <td><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo $Patient_Name; ?>'></td>
                                <td style='text-align: right'>Visit Date</td>
                                <td><input type='text' name='Visit_Date' readonly='readonly' id='dateee' value='<?php echo $Today; ?>'></td> 
                            </tr> 
                            <tr>
                                <td style='text-align: right'>Gender</td>
                                <td><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'></td>
                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' id='Occupation' disabled='disabled' value='<?php echo $Occupation; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Age</td>
                                <td><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right'>Telephone</td>
                                <td><input type='text' name='Phone_Number' disabled='disabled' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'>Patient Number</td>
                                <td><input type='text' name='Patient_Number' disabled='disabled' id='Patient_Number' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right'>Country</td>
                                <td><input type='text' name='Country' disabled='disabled' id='Country' value='<?php echo $Country; ?>'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Patient Old Number</td>
                                <td><input type='text' name='Old_Registration_Number' disabled='disabled' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'></td>
                                <td style='text-align: right'>Region</td>
                                <td><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'>Sponsor</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right'>District</td>
                                <td><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td> 
                            </tr>
                            <tr>
                                <?php
                                unset($_SESSION['NHIF_Member']);
                                if (isset($_GET['Registration_ID'])) {
                                    if (strtolower($Guarantor_Name) == 'nhif') {
                                        ?>
                                        <!--        NHIF VERIFICATION FUNCTION      -->
                                    <script src="js/token.js"></script>
    <?php
    }
}
?>
                            <td style='text-align: right'>Membership Number</td>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; border:0px;" height="80%">
                                    <tr>
                                        <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Member_Number; ?>'>
                                        </td>
                                        <td width="10%">
                                            <img src="images/emptycheck.png" id="check"  style="height:15px; float:right"/>
                                        </td>
                                        <td width="40">
                                            <?php
                                            if (isset($_GET['Registration_ID'])) {
                                                if (strtolower($Guarantor_Name) == 'nhif') {
                                                    ?>
                                                    <input type="button" value="NHIF-Authorize" onclick="verifyNHIF('<?php echo $Member_Number; ?>');" class="art-button-green" />
    <?php
    }
}
?> 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style='text-align: right'>Ward</td>
                            <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Ward; ?>'></td> 
                </tr>

                <tr>
                    <td style='text-align: right'>Emergency Contact Number</td>
                    <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Emergence_Contact_Number; ?>'></td>
                    <td style='text-align: right'>Tribe</td>
                    <td><input type='text' name='Tribe' id='Tribe' disabled='disabled' value='<?php echo $Tribe; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Emergency Contact Name</td>
                    <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Emergence_Contact_Name; ?>'></td>
                    <td style='text-align: right'>Pf3</td>
                    <td>
                        <input type='hidden' name='pf3_ID' id='pf3_ID' value="<?php echo $pf3_ID; ?>">
                        <input type='checkbox' name='pf3' id='pf3'>
                        Type Of Check In
                        <?php
                        //check if new patient then block continue option
                        $check_status = mysqli_query($conn,"select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                        $num_rows = mysqli_num_rows($check_status);
                        if ($num_rows > 0) {
                            ?>
                            <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                                <!--<option selected='selected'></option>-->
                                <option>Afresh</option>
                                <option>Continuous</option>
                            </select>

                        <?php } else { ?>
                            <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                                <option selected="selected">Afresh</option>
                                <option>Continuous</option>
                            </select>                    
<?php } ?>
                    </td>                                
                </tr>
                <tr>
                    <td style='text-align: right'>Employee Vote</td>
                    <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number' value="<?php echo $Employee_Vote_Number; ?>" readonly='readonly'></td>
                   
                    <?php
                    $mysql_check_female_patient=mysqli_query($conn,"SELECT Gender FROM tbl_patient_registration WHERE Gender='Female' AND Registration_ID='$Registration_ID'");
                    $mysql_number_of_rows=mysqli_num_rows($mysql_check_female_patient);
                    if($mysql_number_of_rows>0){
                     ?>
                     <td style='text-align: right'>Pregnant</td>
                    <td> <input type='radio' name='female' id='female' value='Yes'> Yes <input type='radio' name='female' id='female' value='No'> No</td>
                    <?php } ?>
                    </tr>
                    
                <?php
                    //Get Type of exemption if Msamaha patient
                    if($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes'){
                ?>
                     <tr>
                        <td style="text-align:right;"><b style='color: red'>AINA YA MSAMAHA</b></td>
                        <td width="35%">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <select name="msamaha_Items" id="msamaha_Items" required="required">
                                            <option selected="selected"></option>
                                            <?php
                                                $Query= mysqli_query($conn,"select *, (select aina_ya_msamaha from tbl_msamaha where Registration_ID = '$Registration_ID' limit 1) as Msamaha from tbl_msamaha_items") or die(mysqli_error($conn));
                                                while ($row=  mysqli_fetch_assoc($Query)){
                                            ?>
                                                    <option value="<?php echo $row['msamaha_Items']; ?>" <?php if($row['msamaha_aina'] == $row['Msamaha']){ echo "selected='selected'";} ?>><?php echo $row['msamaha_aina']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        Anayependekeza : <b><?php echo ucwords(strtolower($Employee_Name)); ?></b>
                                    </td>
                                </tr>
                            </table>
                       </td>
                <?php
                    }else{
                ?>
                        <input type="hidden" name="msamaha_Items" id="msamaha_Items" value="yes">
                <?php
                    }
                ?>
                </tr>
            </table>
            </td>
            <td>
                <table width=100%>
                    <tr>
                        <td>
                            <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=90% height=90%>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: center;'>
                            <?php
                            $check_patient_limit = mysqli_query($conn,
                                    "SELECT ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,Process_Status,Transaction_Date_And_Time FROM tbl_patient_payment_item_list ppl 
                                         JOIN tbl_patient_payments pp  ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                         WHERE pp.Registration_ID = '$Registration_ID' AND 
                                             pp.Sponsor_ID = '$Sponsor_ID' AND 
                                             ppl.Process_Status = 'signedoff' AND
                                             DATE(Transaction_Date_And_Time) = DATE (NOW()) AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
                                     GROUP BY ppl.Patient_Payment_ID
                                     ORDER BY ppl.Patient_Payment_ID DESC
                                     
                                     ") or die(mysqli_error($conn));

                            $no_limit_check = mysqli_num_rows($check_patient_limit);

                            $select_limit = mysqli_query($conn,"SELECT consult_per_day FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));

                            $consult_per_day = mysqli_fetch_assoc($select_limit)['consult_per_day'];

                            //echo 'no_lmt='.$no_limit_check.' and cons='.$consult_per_day;exit;
                            //echo $is_const_per_day_count;
                            if (!empty($consult_per_day) && ($no_limit_check >= $consult_per_day)  && $is_const_per_day_count == '1') {
                                $fetch2 = mysqli_fetch_assoc($check_patient_limit);

                                if ($fetch2['Patient_Direction'] == 'Direct To Doctor' || $fetch2['Patient_Direction'] == 'Direct To Doctor Via Nurse Station') {
                                    $doctorsName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $fetch2['Consultant_ID'] . "'"))['Employee_Name'];
                                } else if ($fetch2['Patient_Direction'] == 'Direct To Clinic' || $fetch2['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                                    $doctorsName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee e JOIN tbl_clinic_employee ce ON ce.Employee_ID=e.Employee_ID WHERE Clinic_ID='" . $fetch2['Clinic_ID'] . "' GROUP BY e.Employee_ID"))['Employee_Name'];
                                }

                                echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN' style='width: 40%' class='art-button-green' onclick='Check_In_Limit_SignedOff(\"" . str_replace("'", "", $doctorsName) . "\",\"" . $fetch['Transaction_Date_And_Time'] . "\",\"" . $consult_per_day . "\")'>";
                            } else {

                                $check_patient_if_signedoff = mysqli_query($conn,
                                        "SELECT ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,Process_Status FROM tbl_patient_payment_item_list ppl 
                                         JOIN tbl_patient_payments pp  ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID 
                                         WHERE pp.Registration_ID = '$Registration_ID' AND 
                                             ppl.Process_Status IN ('not served','served') AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
                                             
                                    GROUP BY ppl.Patient_Payment_ID") or die(mysqli_error($conn));

                                $num_check_signedoff = mysqli_num_rows($check_patient_if_signedoff);
                                if ($num_check_signedoff > 0 && $is_perf_by_signe_off == '1') {
                                    $fetch = mysqli_fetch_assoc($check_patient_if_signedoff);

                                    if ($fetch['Patient_Direction'] == 'Direct To Doctor' || $fetch['Patient_Direction'] == 'Direct To Doctor Via Nurse Station') {
                                        $doctorsName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $fetch['Consultant_ID'] . "'"))['Employee_Name'];
                                    } else if ($fetch['Patient_Direction'] == 'Direct To Clinic' || $fetch['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                                        $doctorsName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee e JOIN tbl_clinic_employee ce ON ce.Employee_ID=e.Employee_ID WHERE Clinic_ID='" . $fetch['Clinic_ID'] . "' GROUP BY e.Employee_ID"))['Employee_Name'];
                                    }
                                    // die("consult".$doctorsName);

                                    echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN' style='width: 40%' class='art-button-green' onclick='Check_In_Warning_SignedOff(\"" . str_replace("'", "", $doctorsName) . "\",\"" . $fetch['Process_Status'] . "\")'>";
                                } else {

                                    if($Exemption == 'yes' && $Exemption_Details == 'not available' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes'){
                                        echo "<input type='button' name='Exemption_Details_Button' id='Exemption_Details_Button' value='CHECK IN' style='width: 40%' class='art-button-green' onclick='Exemption_Details_Dialog()'>";
                                    }else{
                                        if (isset($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) && strtolower($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) == 'no') {
                                            //check if patient exists as inpatient
                                            $check_patient = mysqli_query($conn,"select Admision_ID from tbl_admission where Registration_ID = '$Registration_ID' and Admission_Status <> 'Discharged'") or die(mysqli_error($conn));
                                            $num_check = mysqli_num_rows($check_patient);
                                            if ($num_check > 0) {
                                                echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN' style='width: 40%' class='art-button-green' onclick='Check_In_Warning()'>";
                                            } else {
                                                echo "<input type='submit' name='submit' id='submit' value='CHECK IN' style='width: 40%' class='art-button-green'>";
                                            }
                                        } else {
                                            echo "<input type='submit' name='submit' id='submit' value='CHECK IN' style='width: 40%' class='art-button-green'>";
                                        }
                                    }
                                }
                            }
                            ?>
                            <input type='hidden' name='submittedCheckInPatientForm' value='true'/> 
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: center;'>
                            <a href='./barcode/BCGcode39.php?Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' target='_blank' style='width: 40%'>PRINT  CARD</a>
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
            </table>
    </center>
</fieldset>

<fieldset> 
    <legend align=center><b>PATIENT'S VERIFIED SPONSOR DETAILS</b></legend>
    <center>
        <table width=90%>
            <tr>
                <td style="text-align: right;" width='14%'>Authorization Status</td>
                <td><input type='text' name='CardStatus' readonly="readonly" id='CardStatus' value=''></td>
                <td style='text-align: right'>Authorization Number</td>
                <td><input type='text' name='AuthorizationNo' id='AuthorizationNo' readonly="readonly" value='' ></td> 
            </tr>
            <tr>
                <td style='text-align: right'>Remarks</td>
                <td colspan=3><textarea name="Remarks" rows="1" id="Remarks" readonly="readonly" style='resize: none;'></textarea></td> 
            </tr>
        </table>
    </center>
</fieldset>
</form>
<div id="pf3dialog" style="width:100%;overflow:hidden;display: none;" >
    <form action="" id="pf3form" method="post" enctype="multipart/form-data">
        <center>
            <table width=90%>
                <tr>
                    <td style="text-align: right;" width='14%'>Pf3 Attachment</td>
                    <td><input type='file' name='pf3attachment' id='pf3attachment' value=''></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Pf3 Description</td>
                    <td>
                        <textarea name='pf3_Description' id='pf3_Description' rows="2" cols="20">
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">Pf3 Reason</td>
                    <td>
                        <select name="P_Reason" id="P_Reason" required="required">
                            <option value="">Select Reason</option>
                            <?php
                            $select = mysqli_query($conn,"select Reason_ID, Reason_Name from tbl_pf3_reasons") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if ($num > 0) {
                                while ($data = mysqli_fetch_array($select)) {
                                    ?>
                                    <option value="<?php echo $data['Reason_ID']; ?>"><?php echo $data['Reason_Name']; ?></option>
        <?php
    }
}
?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Police Station</td>
                    <td colspan=3><input type='text' name="Police_Station" rows="1" id="Police_Station"  style='resize: none;' required='required'/></td> 
                </tr>
                <tr>
                    <td style='text-align: right'>Pf3 Number</td>
                    <td colspan=3><input type='text' name="Pf3_Number" rows="1" id="Pf3_Number"  style='resize: none;'/></td> 
                </tr>
                <tr>
                    <td style='text-align: right'>Relative</td>
                    <td colspan=3><input type='text' name="Relative" rows="1" id="Relative"  style='resize: none;'/></td> 
                </tr>
                <tr>
                    <td style='text-align: right'>Relative Phone Number</td>
                    <td colspan=3><input type='text' name="Phone_No_Relative" rows="1" id="Phone_No_Relative"  style='resize: none;'/></td> 
                </tr>
                <tr>
                    <td style='text-align:center ' colspan="2">
                        <input type="submit" name="pf3submit" id="pf3submit" class="art-button-green" value="Save Information"/>
                    </td>

                </tr>
            </table>
        </center>
    </form>
</div>
<div width="100%" style="text-align:center;">
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        ?>
            <!-- WILL BE USED WHEN LAN TO MSAMAHA IS DONE
            <a href='wagongwawamsamaha.php' class='art-button-green'>
                WAGONJWA WA MSAMAHA
             </a>-->
        <?php
        }
    }
    ?>
    <?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
            ?>

        <?php
        }
    }
    ?>

    <?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
            ?>
            <a href="departmentpatientbillingpage.php?Section=Reception&DepartmentalPayment=DepartmentalPaymentThisPage" class="art-button-green">DEPARTMENTAL PAYMENTS</a>

            <?php //if(isset($Registration_ID)){ ?>
            <button class="art-button-green" onClick="SendSMS('Registration', '<?php echo $Phone_Number; ?>', '<?php echo $Registration_ID; ?>')" >SEND SMS ALERT</button>
            <span id="SMSRespond"></span>
        <?php //}    ?>
    <?php } else { ?>
            <input type="button" name="Direct_Department" id="Direct_Department" value="DIRECT DEPARTMENTAL PAYMENTS" class="art-button-green" onclick="Get_Selected_Patient()">
    <?php
    }
}
?>

</div>

<div id="No_Patient_Available">
    <center>NO PATIENT SELECTED</center>
</div>

<div id="Check_In_Warning">
    <center>
        <b>Please note, the selected patient already has INPATIENT STATUS and not yet discharged</b>
    </center>
</div>
<div id="Check_In_Warning_SignedOff">
    <center>
        <div id="consultedbydoctor"></div>
    </center>
</div>

<div id="Check_In_Limit">
    <center>
        <div id="consultation_limit"></div>
    </center>
</div>

<div id="Exemption_Details_Dialog">
    
</div>

<div id="Update_Error">
    <b>Hazijahifadhiwa sawa. Tafadhali jaribu tena</b>
</div>

<div id="ePayment_Details_Area">
    <center id="Details_Area">
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Patient Name</b></td>
                    <td width="25%"><input type="text" name="P_Name" id="P_Name" readonly="readonly" value="" placeholder="Patient Name"></td>
                    <td style="text-align: right;" width="22%"><b>Patient Number</b></td>
                    <td><input type="text" name="Patient_No" id="Patient_No" readonly="readonly" value="" placeholder="Patient Number"></td>
                </tr>
                <tr>
                    <td width="20%"><b>Gender</b></td>
                    <td width="25%"><input type="text" name="P_Gender" id="P_Gender" readonly="readonly" value="" placeholder="Gender"></td>
                    <td style="text-align: right;"><b>Phone Number</b></td>
                    <td><input type="text" name="Phone_No" id="Phone_No" readonly="readonly" value="" placeholder="Phone Number"></td>
                </tr>
                <tr>
                    <td width="20%"><b>Age</b></td>
                    <td width="25%"><input type="text" name="Patient_Age" id="Patient_Age" readonly="readonly" value="" placeholder="Patient Age"></td>
                    <td style="text-align: right;"><b>Occupation</b></td>
                    <td><input type="text" name="Patient_Occupation" id="Patient_Occupation" readonly="readonly" value="" placeholder="Patient Occupation"></td>
                </tr>

            </table>
        </fieldset>
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Invoice Number</b></td>
                    <td width="25%"><input type="text" name="Invoice_No" id="Invoice_No" readonly="readonly" value="" placeholder="Invoice Number"></td>
                    <td style="text-align: right;" width="22%"><b>Amount Required</b></td>
                    <td><input type="text" name="Amount_Required" id="Amount_Required" readonly="readonly" value="" placeholder="Amount"></td>
                </tr>
                <tr>
                    <td width="20%"><b>Transaction Reference</b></td>
                    <td width="25%"><input type="text" name="Transaction_Ref" id="Transaction_Ref" readonly="readonly" value="" placeholder="Transaction Reference"></td>
                    <td style="text-align: right;" width="22%"><b>Reference Date</b></td>
                    <td><input type="text" name="Reference_Date" id="Reference_Date" readonly="readonly" value="" placeholder="Reference Date"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Transaction Status</b></td>
                    <td width="25%"><input type="text" name="Transaction_Status" id="Transaction_Status" readonly="readonly" value="" placeholder="Transaction Status"></td>
                    <td style="text-align: center;" id="ePayment_Button_Area">

                    </td>
                </tr>
            </table>
        </fieldset>
    </center>

    <br/><br/>
    <fieldset>
        <table width="100%">
            <tr>
                <td width="20%"><b>Enter Invoice Number</b></td>
                <td>
                    <input type="text" name="Invoice_Number" id="Invoice_Number" autocomplete="off" placeholder="~~~ ~~~ ~~~ ~~~ Enter Invoice Number ~~~ ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Clear_Current_Contents()" oninput="Clear_Current_Contents()">
                </td>
                <td width="20%"style="text-align: center;">
                    <input type="button" name="Search_Details" id="Search_Details" class="art-button-green" value="SEARCH DETAILS" onclick="Get_ePayment_Details()">
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<!--check in process-->
<?php
if (isset($_POST['submittedCheckInPatientForm'])) {
    if ($Registration_ID != '') {
        $Visit_Date = mysqli_real_escape_string($conn,$_POST['Visit_Date']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Type_Of_Check_In = mysqli_real_escape_string($conn,$_POST['Type_Of_Check_In']);
        $CardStatus = mysqli_real_escape_string($conn,$_POST['CardStatus']);
        $AuthorizationNo = mysqli_real_escape_string($conn,$_POST['AuthorizationNo']);
        $pf3_ID = $_POST['pf3_ID'];
        if(isset($_POST['female'])){
            $female1 = $_POST['female'];
        }else{
            $female1 = "";
        }
          
         
        //get exemption details if needed
        if($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes'){
            $msamaha_Items = mysqli_real_escape_string($conn,$_POST['msamaha_Items']);
            $Anayependekeza_Msamaha = $Employee_ID;
        }else{
            $msamaha_Items = NULL;
            $Anayependekeza_Msamaha = NULL;

        }
        //check if patient checked in
        $select = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = '$Today'") or die(mysqli_error($conn));
        $num_check_in = mysqli_num_rows($select);

        if ($num_check_in < 1) {
            if($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes'){
                $Check_In_Process = mysqli_query($conn,"insert into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In,
                                    AuthorizationNo,CardStatus,msamaha_Items,Anayependekeza_Msamaha,female_pregnant)                                
                                values(
                                    '$Registration_ID',(select now()),'$Visit_Date',
                                    '$Employee_ID','$Branch_ID',(select now()),'$Type_Of_Check_In',
                                    '$AuthorizationNo','$CardStatus','$msamaha_Items','$Anayependekeza_Msamaha','$female1')") or die(mysqli_error($conn));
            }else{
                $Check_In_Process = mysqli_query($conn,"insert into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In,
                                    AuthorizationNo,CardStatus,female_pregnant)                                
                                values(
                                    '$Registration_ID',(select now()),'$Visit_Date',
                                    '$Employee_ID','$Branch_ID',(select now()),'$Type_Of_Check_In',
                                    '$AuthorizationNo','$CardStatus','$female1'
                                )") or die(mysqli_error($conn));
            }
        }




        if (strtolower($Type_Of_Check_In) == 'afresh') {
            $insert_bill_info = mysqli_query($conn,"INSERT INTO tbl_patient_bill(
                                            Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        }

        $rs = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1");
        $row = mysqli_fetch_assoc($rs);
        $Check_In_ID = $row['Check_In_ID'];

        mysqli_query($conn,"UPDATE tbl_pf3_patients SET Check_In_ID='$Check_In_ID'WHERE pf3_ID='$pf3_ID'");
        if (strtolower($Reception_Picking_Items) == 'yes') {
            //echo 'imetumbukaaa';
            if (strtolower($Guarantor_Name) != 'cash') {
                echo '<script>
                    alert("Patient Checked In Successfully");
                    document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&CreditPatientBilling=CreditPatientBillingThisPage";
                    </script>';
            } else {
                if (strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes') {
                    if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
                        echo '<script>
                        alert("Patient Checked In Successfully");
                        document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
                        </script>';
                    } else {
                        echo '<script>
                        alert("Patient Checked In Successfully");
                        document.location = "./patientbillingprepare.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
                        </script>';
                    }
                } else {
                    echo '<script>
                    alert("Patient Checked In Successfully");
                    document.location = "./patientbillingprepare.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
                    </script>';
                }
                /* echo '<script>
                  alert("Patient Checked In Successfully");
                  document.location = "./patientbillingprepare.php?Registration_ID='.$Registration_ID.'&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
                  </script>'; */
            }
        } else {
            if (strtolower($Guarantor_Name) != 'cash') {
                echo '<script>
                    alert("Patient Checked In Successfully");
                    document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&CreditPatientBilling=CreditPatientBillingThisPage";
                    </script>';
            } else {
                echo '<script>
                    alert("Patient Checked In Successfully");
                    document.location = "./visitorform.php?Visitor=VisitorThisPage";
                    </script>';
            }
        }
    }
}
?>

<script type="text/javascript">
//     $("#female").click(function (e){
//             var female_pregnant = $('#female').val();
//               alert("data taken"+female_pregnant);
//     }); 
    function Update_Msamaha_Details(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Na_ya_Wodi = document.getElementById("Na_ya_Wodi").value;
        var Name_Balozi = document.getElementById("Name_Balozi").value;
        var Aina_ya_msamaha = document.getElementById("Aina_ya_msamaha").value;
        var Education_Level = document.getElementById("Education_Level").value;
        var Work_Wife = document.getElementById("Work_Wife").value;
        var Mahudhulio = document.getElementById("Mahudhulio").value;
        var Idadi_Mahudhulio = document.getElementById("Idadi_Mahudhulio").value;
        var Ni_ndugu_yako_yupi = document.getElementById("Ni_ndugu_yako_yupi").value;
        var amewahi_kutibiwa_mahali = document.getElementById("amewahi_kutibiwa_mahali").value;
        var amevaa_nguo_za_thamani = document.getElementById("amevaa_nguo_za_thamani").value;
        var mengineyo_yanayoonyesha_uwezo_wa_kuchangia = document.getElementById("mengineyo_yanayoonyesha_uwezo_wa_kuchangia").value;
        var Mapendekezo_ya_msamaha = document.getElementById("Mapendekezo_ya_msamaha").value;
        var anastahili_kupata_msamaha = document.getElementById("anastahili_kupata_msamaha").value;
        var sahihi_anayependekeza_msamaha = document.getElementById("sahihi_anayependekeza_msamaha").value;
        var Imehadhimishwa = document.getElementById("Imehadhimishwa").value;
        var Jina_la_anayehadhimisha = document.getElementById("Jina_la_anayehadhimisha").value;
        var cheo_anayehadhimisha = document.getElementById("cheo_anayehadhimisha").value;
        var sahihi_anayehadhimisha = document.getElementById("sahihi_anayehadhimisha").value;
        var Namba_katika_Rejista_ya_kupatiwa_msamaha = document.getElementById("Namba_katika_Rejista_ya_kupatiwa_msamaha").value;

        if(Aina_ya_msamaha != null && Aina_ya_msamaha != '' && Jina_la_anayehadhimisha != null && Jina_la_anayehadhimisha != '' && cheo_anayehadhimisha != null && cheo_anayehadhimisha != ''){
            if (window.XMLHttpRequest) {
                myObjectUpdate = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdate.overrideMimeType('text/xml');
            }
            //alert(data);

            myObjectUpdate.onreadystatechange = function () {
                dataUpdate = myObjectUpdate.responseText;
                if (myObjectUpdate.readyState == 4) {
                    var feedback = dataUpdate;
                    if(feedback == 'yes'){
                        alert("Taarifa zimehifadhiwa vyema");
                        document.location = 'visitorform.php?Registration_ID='+Registration_ID+'&PatientBilling=PatientBillingThisForm';
                    }else{
                        $("#Update_Error").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectUpdate.open('GET', 'Update_Msamaha_Details.php?Registration_ID='+Registration_ID+'&Na_ya_Wodi='+Na_ya_Wodi+'&Name_Balozi='+Name_Balozi+
                                    '&Aina_ya_msamaha='+Aina_ya_msamaha+'&Education_Level='+Education_Level+'&Work_Wife='+Work_Wife+'&Mahudhulio='+Mahudhulio+
                                    '&Idadi_Mahudhulio='+Idadi_Mahudhulio+'&Ni_ndugu_yako_yupi='+Ni_ndugu_yako_yupi+'&amewahi_kutibiwa_mahali='+amewahi_kutibiwa_mahali+'&amevaa_nguo_za_thamani='+amevaa_nguo_za_thamani+
                                    '&mengineyo_yanayoonyesha_uwezo_wa_kuchangia='+mengineyo_yanayoonyesha_uwezo_wa_kuchangia+'&Mapendekezo_ya_msamaha='+Mapendekezo_ya_msamaha+'&anastahili_kupata_msamaha='+anastahili_kupata_msamaha+
                                    '&sahihi_anayependekeza_msamaha='+sahihi_anayependekeza_msamaha+'&Imehadhimishwa='+Imehadhimishwa+'&Jina_la_anayehadhimisha='+Jina_la_anayehadhimisha+'&cheo_anayehadhimisha='+cheo_anayehadhimisha+
                                    '&sahihi_anayehadhimisha='+sahihi_anayehadhimisha+'&Namba_katika_Rejista_ya_kupatiwa_msamaha='+Namba_katika_Rejista_ya_kupatiwa_msamaha, true);
            myObjectUpdate.send();
        }else{
            document.getElementById("Error_Message").innerHTML = '<span style="color: #037CB0;"><b>SAMAHANI JAZA SEHEMU ZOTE ZENYE RANGI NYEKUNDU</b></span>';
            if(Aina_ya_msamaha == null || Aina_ya_msamaha == ''){
                document.getElementById("Aina_ya_msamaha").style = 'border: 2px solid red';
            }
            if(Jina_la_anayehadhimisha == null || Jina_la_anayehadhimisha == ''){
                document.getElementById("Jina_la_anayehadhimisha").style = 'border: 2px solid red';
            }
            if(cheo_anayehadhimisha == null || cheo_anayehadhimisha == ''){
                document.getElementById("cheo_anayehadhimisha").style = 'border: 2px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Exemption_Details_Dialog(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
        var age = '<?php echo $age; ?>';
        var Gender = '<?php echo $Gender; ?>';

        if (window.XMLHttpRequest) {
            myObjectMissingDets = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMissingDets = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMissingDets.overrideMimeType('text/xml');
        }
        //alert(data);

        myObjectMissingDets.onreadystatechange = function () {
            dataMsamaha = myObjectMissingDets.responseText;
            if (myObjectMissingDets.readyState == 4) {
                document.getElementById('Exemption_Details_Dialog').innerHTML = dataMsamaha;
                $("#Exemption_Details_Dialog").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectMissingDets.open('GET', 'Exemption_Details_Dialog.php?Registration_ID='+Registration_ID+'&Patient_Name='+Patient_Name+'&age='+age+'&Gender='+Gender, true);
        myObjectMissingDets.send();
    }
</script>

<script type="text/javascript">
    function Close_Exemption_Details_Dialog(){
        $("#Exemption_Details_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Get_ePayment_Details() {
        var Invoice_Number = document.getElementById("Invoice_Number").value;
        if (Invoice_Number != null && Invoice_Number != '') {
            document.getElementById("Invoice_Number").style = 'border: 1px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObject_Get_Details = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject_Get_Details = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject_Get_Details.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject_Get_Details.onreadystatechange = function () {
                data24 = myObject_Get_Details.responseText;
                if (myObject_Get_Details.readyState == 4) {
                    document.getElementById('Details_Area').innerHTML = data24;
                }
            }; //specify name of function that will handle server response........
            myObject_Get_Details.open('GET', 'Get_ePayment_Details.php?Invoice_Number=' + Invoice_Number, true);
            myObject_Get_Details.send();
        } else {
            if (Invoice_Number == null || Invoice_Number == '') {
                document.getElementById("Invoice_Number").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("Invoice_Number").focus();
            } else {
                document.getElementById("Invoice_Number").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>
<script type='text/javascript'>

    function SendSMS(department, receiver, RegNo = 0){
        //alert(department + receiver);
        //exit;
        if (window.XMLHttpRequest) {
            sms = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            sms = new ActiveXObject('Micrsoft.XMLHTTP');
            sms.overrideMimeType('text/xml');
        }
        sms.onreadystatechange = AJAXSMS;
        sms.open('GET', 'SendSMS.php?Department=' + department + '&Receiver=' + receiver + '&Registration_ID=' + RegNo, true);
        sms.send();

        function AJAXSMS() {
            var smsrespond = sms.responseText;
            document.getElementById('SMSRespond').innerHTML = smsrespond;
        }
    }


    $(document).ready(function () {
        $("#pf3dialog").dialog({autoOpen: false, width: 900, height: 560, title: 'Fill Pf3 Details For <?php echo $Patient_Name; ?>', modal: true});
//       $(".ui-widget-header").css("background-color","blue");  

        $("#pf3").live("click", function () {
            //alert("chosen");
            if ($(this).is(':checked')) {
                $("#pf3dialog").dialog("open");
            }
        });

        $(".ui-icon-closethick").click(function () {
//         $(this).hide();
            $("#pf3").attr("checked", false);
        });

        $("#pf3submit").click(function (e) {
            e.preventDefault();
            if ($("#Police_Station").val() !== '' && $("#P_Reason").val() !== '') {
                $("#pf3form").submit();
            } else {

                alert("Police station name and Reason are required");
                if ($("Police_Station").val() == '') {
                    $("#Police_Station").focus();
                } else if ($("P_Reason").val() == '') {
                    $("#P_Reason").focus();
                }
                $("#Police_Station").css("border-color", "red");
                $("#P_Reason").css("border-color", "red");

            }

        });

    });
</script>
<script type="text/javascript">
    function Get_Selected_Patient() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (Registration_ID != null && Registration_ID != '') {
            document.location = 'receptiondepartmentalothersworkspage.php?Registration_ID=' + Registration_ID + '&ReceptionDepartmentalPatientBilling=ReceptionDepartmentalPatientBillingThisForm';
        } else {
            $("#No_Patient_Available").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Search_ePayment_Details() {
        document.getElementById("P_Name").value = '';
        document.getElementById("Patient_No").value = '';
        document.getElementById("P_Gender").value = '';
        document.getElementById("Phone_No").value = '';
        document.getElementById("Patient_Age").value = '';
        document.getElementById("Patient_Occupation").value = '';
        document.getElementById("Invoice_No").value = '';
        document.getElementById("Amount_Required").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Ref").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Status").value = '';
        document.getElementById("Invoice_Number").value = '';
        document.getElementById("ePayment_Button_Area").innerHTML = '&nbsp';
        $("#ePayment_Details_Area").dialog("open");
    }
</script>


<script type="text/javascript">
    function Clear_Current_Contents() {
        document.getElementById("P_Name").value = '';
        document.getElementById("Patient_No").value = '';
        document.getElementById("P_Gender").value = '';
        document.getElementById("Phone_No").value = '';
        document.getElementById("Patient_Age").value = '';
        document.getElementById("Patient_Occupation").value = '';
        document.getElementById("Invoice_No").value = '';
        document.getElementById("Amount_Required").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Ref").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Status").value = '';
        document.getElementById("ePayment_Button_Area").innerHTML = '&nbsp;';
    }
</script>

<script type="text/javascript">
    function Print_Payment_Code(Payment_Code) {
        var winClose = popupwindow('paymentcodepreview.php?Payment_Code=' + Payment_Code + '&PaymentCodePreview=PaymentCodePreviewThisPage', 'INVOICE NUMBER', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>


<script type="text/javascript">
    function Print_Receipt_Payment(Patient_Payment_ID) {
        var winClose = popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID=' + Patient_Payment_ID + '&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script type="text/javascript">
    function Check_In_Warning() {
        $("#Check_In_Warning").dialog("open");
    }
</script>
<script>
    function Check_In_Limit_SignedOff(doctorname, sentday, consult_per_day) {
        $("#consultation_limit").html("<b>Can't checking, <?php echo $Guarantor_Name ?> checking is " + consult_per_day + " times a day. </b><br/><b>Last sent to doctor is on " + sentday + ". <br/>Consulant: " + doctorname + "</b>");
        $("#Check_In_Limit").dialog("open");
    }
</script>

<script type="text/javascript">
    function Check_In_Warning_SignedOff(doctorname, status) {
        var st, ns;
        if (status == 'not served') {
            st = 'ATTENDED BY THE DOCTOR';
            ns = 'NO SHOW';
        } else if (status == 'served') {
            st = 'SIGNED OFF';
            ns = 'SIGNE OFF';
        }
        //alert(doctorname);
        $("#consultedbydoctor").html("<b>Please note, the selected patient is not " + st + ". Doctor should " + ns + " the patient before checking again!</b><br/><b>Consulant: " + doctorname + "</b>");
        $("#Check_In_Warning_SignedOff").dialog("open");
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    $(document).ready(function () {
        $("#No_Patient_Available").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0 ~ Information!', modal: true});
        $("#Update_Error").dialog({autoOpen: false, width: '40%', height: 120, title: 'eHMS 2.0 ~ Error!', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Check_In_Warning,#Check_In_Warning_SignedOff,#Check_In_Limit").dialog({autoOpen: false, width: '35%', height: 180, title: 'eHMS 2.0 ~ Information!', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#ePayment_Details_Area").dialog({autoOpen: false, width: '60%', height: 450, title: 'ePAYMENT DETAILS', modal: true});
        $("#Exemption_Details_Dialog").dialog({autoOpen: false, width: '80%', height: 550, title: 'Taarifa za <?php echo strtoupper($Patient_Name); ?> za msamaha zinahitajika', modal: true});
    });
</script>

<!--end of ceck in process-->
<?php
include("./includes/footer.php");
?>