<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    $consultation_ID = 0;
}

if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = 0;
}


$check = mysqli_query($conn,"SELECT Signed_at, consent_amputation from tbl_consert_blood_forms_details where Registration_ID = '$Registration_ID' AND consultation_id='$consultation_ID' AND Consent_ID NOT IN(SELECT Consent_ID FROM tbl_blood_transfusion_requests WHERE Process_Status <> 'processed')") or die(mysqli_error($conn));
$num = mysqli_num_rows($check);

while($data = mysqli_fetch_assoc($check)){
    $Operative_Date_Time = $data['Signed_at'];
    $consent_amputation = $data['consent_amputation'];
}
if($num > 0){
    if($consent_amputation == 'Agree'){
        $blood_request = "<a href='blood_request_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green'>BLOOD REQUEST FORM</a>";

	}elseif($consent_amputation == 'Disagree'){
        $blood_request = "<a href='blood_request_consent_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green' style='background: #bd0d0d;'>BLOOD CONSENT FORM (REJECTED)</a>";
    }
}else{
    $blood_request = "<a href='blood_request_consent_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green'>BLOOD CONSENT FORM</a>";
}
?>


<!--START HERE-->
<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,Registration_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,payment_method,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Payment_Method = $row['payment_method'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Registration_Date = $row['Registration_Date'];
            $Patient_Picture = $row['Patient_Picture'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        //$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
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
        $Registration_Date = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
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
    $Registration_Date = '';
    $age = 0;
}


if($Admision_ID > 0 && (strpos($Guarantor_Name, 'NHIF') !== false)){

    $Select_inpatient_details = mysqli_query($conn, "SELECT ad.Admission_Date_Time, ad.ward_room_id, wr.Room_Type, TIMESTAMPDIFF(DAY,ad.Admission_Date_Time,NOW()) AS NoOfDay FROM tbl_ward_rooms wr, tbl_admission ad WHERE Admision_ID = '$Admision_ID' AND wr.ward_room_id = ad.ward_room_id");
    while($list = mysqli_fetch_assoc($Select_inpatient_details)){
        $Admission_Date_Time = $list['Admission_Date_Time'];
        $Room_Type = $list['Room_Type'];
        $NoOfDay = $list['NoOfDay'];
        $ward_room_id = $list['ward_room_id'];

        if($Room_Type == 'General' && $NoOfDay > 10){
            $Select_previous_form = mysqli_query($conn, "SELECT TIMESTAMPDIFF(DAY,Signed_Date_Time,NOW()) AS Lapsed_Time FROM tbl_inpatient_overstaying WHERE Admision_ID = '$Admision_ID' AND ward_room_id = '$ward_room_id' ORDER BY Overstay_Form_ID DESC LIMIT 1");
            if(mysqli_num_rows($Select_previous_form)>0){
                while($taarifa = mysqli_fetch_assoc($Select_previous_form)){
                    $Lapsed_Time = $taarifa['Lapsed_Time'];
                    if($Lapsed_Time > 10){
                        header("Location: inpatient_overstay_form.php?Registration_ID=".$Registration_ID."&consultation_ID=".$consultation_ID."&Admision_ID=".$Admision_ID."&ward_room_id=".$ward_room_id."");
                    }
                }
            }else{
                header("Location: inpatient_overstay_form.php?Registration_ID=".$Registration_ID."&consultation_ID=".$consultation_ID."&Admision_ID=".$Admision_ID."&ward_room_id=".$ward_room_id."");
            }
        }elseif($Room_Type == 'HDU' && $NoOfDay > 5){
            
            $Select_previous_form = mysqli_query($conn, "SELECT TIMESTAMPDIFF(DAY,Signed_Date_Time,NOW()) AS Lapsed_Time FROM tbl_inpatient_overstaying WHERE Admision_ID = '$Admision_ID' AND ward_room_id = '$ward_room_id' ORDER BY Overstay_Form_ID DESC LIMIT 1");
            if(mysqli_num_rows($Select_previous_form)>0){
                while($taarifa = mysqli_fetch_assoc($Select_previous_form)){
                    $Lapsed_Time = $taarifa['Lapsed_Time'];
                        if($Lapsed_Time > 5){
                            header("Location: inpatient_overstay_form.php?Registration_ID=".$Registration_ID."&consultation_ID=".$consultation_ID."&Admision_ID=".$Admision_ID."&ward_room_id=".$ward_room_id."");
                        }
                }
            }else{
                header("Location: inpatient_overstay_form.php?Registration_ID=".$Registration_ID."&consultation_ID=".$consultation_ID."&Admision_ID=".$Admision_ID."&ward_room_id=".$ward_room_id."");
            }
        }
    }

}
//fix admission
$Registration_ID = $_GET['Registration_ID'];

$sql_result=mysqli_query($conn,"SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted'") or die(mysqli_error($conn));

if(mysqli_num_rows($sql_result)>0){
    $Admision_ID_= mysqli_fetch_assoc($sql_result)['Admision_ID'];
    $consultation_ID = $_GET['consultation_ID'];
    $sql_fix_incomplete_admision_result=mysqli_query($conn,"UPDATE tbl_check_in_details SET ToBe_Admitted='yes',Admit_Status='admitted',Admission_ID='$Admision_ID_',consultation_ID='$consultation_ID' WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));           
}

?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') {
        ?>
        <!--Script to display patient optional photo-->
        <!--PATIENT PHOTO SCRIPT START-->

        <script>
            function displayPatientPhoto() {
                document.getElementById('photo').onclick = function () {
                    if (document.getElementById('photo').checked) {
                        //use css style to display the photo
                        document.getElementById("PatientPhoto").style.display = "block";
                    } else {
                        document.getElementById("PatientPhoto").style.display = "none";
                    }
                };
                //hide on initial page load
                document.getElementById("PatientPhoto").style.display = "block";
            }

            window.onload = function () {
                displayPatientPhoto();
            };
        </script>


        <!--PATIENT PHOTO SCRIPT END-->
        <script type="text/javascript">
            function gotolink() {
                var url = "<?php
        if ($Registration_ID != '') {
            echo "Registration_ID=$Registration_ID&";
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById('patientlist').value;

                if (patientlist == 'PATIENT LIST') {
                    document.location = "admittedpatientlist.php?" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

        <label style='border: 1px ;padding: 8px;margin-right: 7px;height:40px!important' class='art-button-green'>
            <select id='patientlist' name='patientlist'>
                <!--	<option></option>-->
                <option>
                    PATIENT LIST
                </option>
            </select>
            <input type='button' value='VIEW' onclick='gotolink()'>
        </label>
        <?php
    }
}
?>
        <style>
            button{
                height:27px!important;
                color:#FFFFFF!important;
            }
        </style>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes') {
        ?>
        <a href='performsurgery.php?Section=Inpatient&consultation_ID=<?php echo $consultation_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Admision_ID=<?= $Admision_ID ?>&PerformSurgery=PerformSurgeryThisPage' target="_parent" class="art-button-green">PERFORM SURGERY(Post Operative Report)</a>
        <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&position=out" class='art-button-green'  target="_blank">PATIENT FILE-DETAIL</a>

                                              <!--<input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File()' class='art-button-green'/>-->
        <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE-SUMMARY' onclick='showSummeryPatientFile()' class='art-button-green' />
<hr>
<?php if (isset($_GET['Registration_ID'])) { ?>
    <input type='button' name='patientFileByFolio' id='patientFileByFolio' value='PATIENT FILE BY FOLIO' onclick='patientFileByFolio()' class='art-button-green' />

                                                                                                                 <!--<a href='Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm' class='art-button-green'>PATIENT FILE</a>-->
    <?php
    $Registration_ID=$_GET['Registration_ID'];
    $consultation_ID=$_GET['consultation_ID'];
    
    $sql_select_Patient_Payment_Item_List_ID="SELECT Patient_Payment_Item_List_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID'";
                $sql_select_Patient_Payment_Item_List_ID_result=mysqli_query($conn,$sql_select_Patient_Payment_Item_List_ID) or die(mysqli_error($conn));
                $rows_cons=mysqli_fetch_assoc($sql_select_Patient_Payment_Item_List_ID_result);
                         $Patient_Payment_Item_List_IDD=$rows_cons['Patient_Payment_Item_List_ID'];
}?>
        <a href='admittedpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            BACK
        </a>
    <a  target="_blank"  href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>"     class='art-button-green' >
            VITAL SIGNS / NURSE DOCUMENTATION
    </a>
        <?php
    }
}
?>
<br/><br/>
<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<?php
if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['Registration_ID']) && isset($Patient_Payment_ID)) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select Patient_Payment_ID,Payment_Date_And_Time,Folio_Number,Claim_Form_Number,Billing_Type from tbl_patient_payments pp  where pp.registration_id = '$Registration_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}

if (empty($Billing_Type)) {
    if (strtolower($Payment_Method) == 'cash') {
        $Billing_Type = 'Inpatient Cash';
    } else {
        $Billing_Type = 'Inpatient Credit';
    }
}

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}

//GET LAST CHECLING PAYMENTS

$sql = "SELECT Item_ID,Price FROM tbl_patient_payment_item_list ppl
       JOIN tbl_ward_round wr ON ppl.Patient_Payment_ID=wr.Patient_Payment_ID
       WHERE wr.Process_Status='served' AND wr.consultation_ID='" . $_GET['consultation_ID'] . "' AND Registration_ID='" . $_GET['Registration_ID'] . "'  AND DATE(Ward_Round_Date_And_Time)=DATE(NOW())  ORDER BY wr.Round_ID DESC LIMIT 1";
//die($sql);
$getResultPay = mysqli_query($conn,$sql) or die(mysqli_error($conn));

$lasPayID = '';
$lasPayPrice = '';
$thereIsPay = false;
$clinicalDisplay = "style='display:none'";

if (mysqli_num_rows($getResultPay) > 0) {
    $thereIsPay = true;
    $lastPay = mysqli_fetch_assoc($getResultPay);
    $lasPayID = $lastPay['Item_ID'];
    $lasPayPrice = number_format($lastPay['Price']);
    $clinicalDisplay = "";
}

//style='display:none'
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<fieldset>
    <center style='background: #006400 !important;color: white;'>
         <?php
         $Clinic_Name='';
            $doctors_selected_clinic=$_SESSION['doctors_selected_clinic']; 
             $Select_Consultant = "select * from tbl_clinic where Clinic_ID='$doctors_selected_clinic'";
            $result = mysqli_query($conn,$Select_Consultant);

            while ($row = mysqli_fetch_array($result)) {
                 $Clinic_Name=$row['Clinic_Name']; 
            } 
        ?>
        <b>DOCTORS WORKPAGE INPATIENT <b style="font-size: 17px">~~~<?php echo $Clinic_Name; ?></b><br>&nbsp;</b>
    </center>
    <br/>
    <center> 
        <table width=100%>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width='16%' style='text-align: right'>Patient Name</td>
                            <td width='26%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php
                                if (isset($Patient_Name)) {
                                    echo $Patient_Name;
                                }
                                ?>'></td>
                        </tr>
                        <tr>
                            <td width='13%' style='text-align: right'>Card Id Expire Date</td>
                            <td width='16%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                        </tr>
                        <tr>
                            <td width='13%' style='text-align: right'>D.O.B</td>
                            <td width='16%'><input type='text' name='Date_Of_Birth' id='Date_Of_Birth' value='<?php echo $Date_Of_Birth; ?>' disabled='disabled'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Phone Number</td>
                            <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Region</td>
                            <td>
                                <input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Receipt No</td>
                            <td>
                                <input type='text' name='Patient_Payment_ID' id='Patient_Payment_ID' disabled='disabled' value='<?php echo $Patient_Payment_ID; ?>'>
                            </td>
                        </tr>
                    </table>
                </td>

                <td>
                    <table width="100%">
                        <tr>
                            <td style='text-align: right'>Sponsor Name</td>
                            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Member Number</td>
                            <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
                        </tr>
                        <tr>
                        <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                        <td style='text-align: right'>Folio Number</td>
                        <td><input type='text' disabled='disabled' value='<?php
                            if (isset($_GET['Folio_Number'])) {
                                echo $_GET['Folio_Number'];
                            } else {
                                echo $Folio_Number = '';
                            }
                            ?>'>
                            <input type='hidden' name='Folio_Number' id='Folio_Number' value='<?php echo $Folio_Number; ?>'>
                        </td>
            </tr>
            <tr>
                <td style='text-align: right'>Registration Number</td>
                <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
                    <input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
                </td>
            </tr>
            <tr>
                <td style='text-align: right'>Ward</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Ward; ?>'></td>


            </tr>
            <tr><td style='text-align: right'>Registered Date</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Registration_Date; ?>'></td>
            </tr>

        </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style='text-align: right'>Gender</td>
                    <td><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Claim Form Number</td>
                    <td><input type='text' name='Admission_Claim_Form_Number' disabled='disabled'  id='Admission_Claim_Form_Number'<?php if ($Claim_Number_Status == "Mandatory") { ?> required='required'<?php } ?> value='<?php echo $Claim_Form_Number; ?>'></td> 
                </tr>
                <tr>
                <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                <td style='text-align: right'>Bill Type</td>
                <td><input type='text' name='Billing_Type' disabled='disabled' id='Billing_Type' value='<?php echo $Billing_Type; ?>'>

                </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Consulting/Doctor</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>

                    <td style='text-align: right'>Admitted Date</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Admission_Date_Time; ?>'></td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style='text-align: center;width: 100%;'>
                        <fieldset id="PatientPhoto">
                            <legend>Patient Photo</legend>
                            <div>
                                <?php
                                if (file_exists('patientImages/' . $Patient_Picture)) {
                                    echo '<img src="patientImages/' . $Patient_Picture . '" title="Click To View Image" alt="PatientPhoto" style="margin-left:0;width:180px; height:180px; " onclick="viewPatientPhoto(' . $Registration_ID . ')"/>';
                                } else {
                                    echo '<img src="patientImages/default.png" alt="PatientPhoto" width="100%"/>';
                                }
                                ?>
                            </div>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        </tr>
        </table>
    </center>
</fieldset>
<br><br>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<div id="Previous_Records">

</div>
<script type="text/javascript">
    function Preview_Previous_Records() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPrevious = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPrevious.overrideMimeType('text/xml');
        }

        myObjectPrevious.onreadystatechange = function () {
            dataPrev = myObjectPrevious.responseText;
            if (myObjectPrevious.readyState == 4) {
                document.getElementById("Previous_Records").innerHTML = dataPrev;
                $("#Previous_Records").dialog("open");
            }
        };
        myObjectPrevious.open('GET', 'Pre_Operative_Preview_Previous_Records.php?Registration_ID=' + Registration_ID, true);
        myObjectPrevious.send();
    }
</script>
<script type="text/javascript">
    function Preview_Report(Pre_Operative_ID) {
        window.open("previewpreorerative.php?Pre_Operative_ID=" + Pre_Operative_ID + "&PreviewPreOperative=PreviewPreOperativeThisPage", "_blank");
    }
</script>
<script type='text/javascript'>
    function substitute(Item_ID_location) {
        var Item_ID = document.getElementById('Item_ID_' + Item_ID_location + "").value;
        var Patient_Payment_Item_List_ID = '';
        var quantity = document.getElementById('Quantity_' + Item_ID_location + "").value;
        var responce = confirm('Are You Sure You Want To Substitute This Item');
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        if (responce) {
            if (window.XMLHttpRequest) {
                mm_sbst_object = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                mm_sbst_object = new ActiveXObject('Micrsoft.XMLHTTP');
                mm_sbst_object.overrideMimeType('text/xml');
            }
            mm_sbst_object.onreadystatechange = function () {
                if (mm_sbst_object.readyState == 4) {
                    var ajax_responce = mm_sbst_object.responseText;
                    if (ajax_responce == 'sent') {
                        location.reload();
                    }
                }
            }; //specify name of function that will handle server response....
            mm_sbst_object.open('GET', 'doctocr_ajax_substitute.php?Item_ID=' + Item_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&quantity=" + quantity + "&Billing_Type=" + Billing_Type + "&Guarantor_Name=" + Guarantor_Name, true);
            mm_sbst_object.send();
        } else {
        }
    }

    function changeItem(Item_ID, Item_ID_local) {
        if (Item_ID != '') {
            var Billing_Type = '<?php echo $Billing_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }
            mm.onreadystatechange = function () {
                if (mm.readyState == 4) {
                    var data4 = mm.responseText;

                    document.getElementById('price_' + Item_ID_local + "").value = data4;

                    var price = document.getElementById('price_' + Item_ID_local + "").value;

                    var quantity = document.getElementById('Quantity_' + Item_ID_local + "").value;
                    var ammount = 0;
                    ammount = price * quantity;
                    document.getElementById('amount_' + Item_ID_local + "").value = ammount;
                }
            }; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Item_price.php?Product_Name=' + Item_ID + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name, true);
            mm.send();
        }
    }
</script>
<?php
$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$emp = '';

if ($hospitalConsultType == 'One patient to one doctor') {
    $emp = "AND tlc.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
}
?>
<script>
    function getAmount(quantity, price, sn) {
        var amount;
        var theid = sn + 'amount';
        amount = quantity * price;
        document.getElementById(theid).value = amount;
    }
</script>
<!--     </table>
</fieldset> -->

<table width='100%' border='0'>
    <tr>
        <td <?php if (!isset($_GET['Registration_ID'])) echo "style='display:none;'"; ?> >
            <fieldset>
                <div style="display:block;text-align: center">
                    <strong>CHECK IN AS:</strong>
                    <select name='doctor_level' style='width:auto;padding:5px;font-size:18px; text-align: left;' onChange='AllowDoctor(this.value);'>
                        <?php
                        $sponsor_item_filter = '';
                        $sponsor_payment_method = "";
                        if (isset($Guarantor_Name)) {
                            $sp_query = mysqli_query($conn,"SELECT Guarantor_name,payment_method,Sponsor_ID,item_update_api,auto_item_update_api FROM tbl_sponsor WHERE LOWER(Guarantor_name)='" . strtolower($Guarantor_Name) . "'") or die(mysqli_error($conn));

                            if (mysqli_num_rows($sp_query) > 0) {
                                $rowSp = mysqli_fetch_assoc($sp_query);
                                $Guarantor_name = $rowSp['Guarantor_name'];
                               // $Sponsor_ID = $rowSp['Sponsor_ID'];
                                $auto_item_update_api = $rowSp['auto_item_update_api'];
                                $sponsor_payment_method = $rowSp['payment_method'];

                                if ($auto_item_update_api == '1') {
                                    $sponsor_item_filter = ''; //" AND sponsor_id='$Sponsor_ID'";
                                }
                            }
                        }

                        $docs_items = "SELECT Items_Price,i.Item_ID,Product_Name FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID WHERE Ward_Round_Item = 'yes' AND Status='Available' AND ip.Sponsor_ID='$Sponsor_ID' AND ip.Items_Price<>0";
                        $docs_items_qry = mysqli_query($conn,$docs_items) or die(mysqli_error($conn));

                        echo "<option value=''></option>";
                                                        //if($sponsor_payment_method == 'credit'){
                                                            $free_round = mysqli_query($conn,"SELECT i.Item_ID,Product_Name, '0' AS Items_Price FROM tbl_items i WHERE Product_Name = 'Free Round'");
                                                            while ($row = mysqli_fetch_assoc($free_round)) {
                                                              echo "<option value='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</option>";
                                                            } 
                                                       // }
                        if(mysqli_num_rows($docs_items_qry)>0){
                                while ($docs_item = mysqli_fetch_assoc($docs_items_qry)) {
                                    $ItemCode = $docs_item['Item_ID'];
                                    $ItemName = $docs_item['Product_Name'];
                                    $Items_Price = $docs_item['Items_Price'];

                                    if ($auto_item_update_api == '1') {
                                        $queryPrice = mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$ItemCode' AND  Sponsor_ID='$Sponsor_ID'");

                                        if (mysqli_num_rows($queryPrice) < 1) {
                                            continue;
                                        }
                                    }

                                    if ($lasPayID == $ItemCode) {
                                        echo "<option selected value='" . $ItemCode . "'>" . $ItemName . "</option>";
                                    } else {
                                        echo "<option value='" . $ItemCode . "'>" . $ItemName . "</option>";
                                    }
                                }
                                
                        
                            }
                        ?>
                    </select>
                    <?php if (isset($_GET['Registration_ID'])) {
                        ?>
                        <input type="text" class="" id="checkingPrice" style="width:10%; " value="<?php echo $lasPayPrice ?>" readonly/>
                        <a href='inpatientclinicalnotes.php?<?php
                        if ($Registration_ID != '') {
                            echo "Registration_ID=$Registration_ID&";
                        }
                        if (isset($_GET['consultation_ID'])) {
                            echo "consultation_ID=" . $_GET['consultation_ID'] . "";
                        }
                        echo "&Admision_ID=$Admision_ID"
                        ?>' class='art-button-green' id='clinicalnotes' <?php echo $clinicalDisplay; ?>>
                            CLINICAL NOTES 
                        </a>
                        <button  class='art-button-green' onclick="getTodayLastTime(<?php echo $_GET['Registration_ID'] ?>,<?php echo $_GET['consultation_ID'] ?>)">TODAY LAST CHECKING</button>            
                    <?php } else {
                        ?>
                        <input type='button' value='CLINICAL NOTES' class='art-button-green' onclick="alert('Choose Patient!');">
                    <?php } ?>
                    <input type='hidden' id='send_admition_form' name='send_admition_form'>
                    <?php if (isset($_GET['Registration_ID'])) { ?>
                        <b style="font-size: 12px;">Display Patient Picture yes</b> <input type='checkbox' name='photo' id='photo'/>
                    <?php }
                    ?>  
                </div>

                <div style="display:block;text-align: center;margin-top:10px ">
                    <!--A Button into Referral Form: By AdeK, June 2016-->
                    <?php
                    if (isset($_GET['Registration_ID'])) {
                        $getPatientinfo = mysqli_query($conn,"SELECT ad.Admision_ID FROM tbl_admission ad  WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        $getinfp = mysqli_fetch_assoc($getPatientinfo);
                            $Admision_ID = $getinfp['Admision_ID'];
                        ?>
                         <!--                IMEAMISHIWA NURSE COMMUNICATION
                        <a href="anethesia.php?Registration_ID=<?=$Registration_ID?>&this_page_from=doctor_inpatient&consultation_ID=<?=$consultation_ID?>">
                        <input type='button' name='patient_transfer_referral'  value='ANETHESIA ' class='art-button-green' />
                        </a>-->
                            <?php $Admision_ID; $consultation_ID= $_GET['consultation_ID']; $Registration_ID=$_GET['Registration_ID'];?>
                      <?php echo  "<input type='button' name='patient_transfer_referral' id='patient_transfer_referral' value='REFERRAL' onclick='showReferralForm($Registration_ID,$Admision_ID,$consultation_ID)' class='art-button-green' />"; ?>
                    <?php } ?>
                    <a href='PatientRadiology_Served_Doctor_Inpatient.php?<?php echo $_SERVER['QUERY_STRING'] ?>' style="padding:1 1px 0 1px; " class='art-button-green'>
                        RADIOLOGY RESULTS </span>
                    </a>

                    <a href='laboratoryresult_inpatient.php?<?php echo $_SERVER['QUERY_STRING'] ?>' style="padding:1 1px 0 1px;" class='art-button-green'>
                        LABORATORY RESULTS </span>
                    </a>
                    <a href='doctorprocedurelistinpatient.php?Section=Doctor&<?php echo $_SERVER['QUERY_STRING'] ?>' style="">
                        <button class="art-button-green">PROCEDURES and anetheia</button>
                    </a>
                         <a href="icu_standard_progress_note.php?consultation_ID=<?= $consultation_ID ?>&Registration_ID=<?= $Registration_ID ?>" class="art-button-green" id="icu_prog_not_btn" style="display:none">ICU STANDARD PROGRESS NOTE</a>
                         <a href="anesthesia_record_chart.php?consultation_ID=<?= $consultation_ID ?>&Registration_ID=<?= $Registration_ID ?>" class="art-button-green">ANESTHESIA RECORD CHART</a>
                         <a href="anesthesia_record_chart.php?consultation_ID=<?= $consultation_ID ?>&Registration_ID=<?= $Registration_ID ?>" class="art-button-green">ANESTHESIA RECORD CHART</a>
                         <?= $blood_request ?>
                </div>
            </fieldset>
        </td>
    </tr>

                </table>
                <div id="otherdoctorStaff" style="width:100%;overflow-x:hidden;height:320px;display:none;overflow-y:scroll">
                    <div id="doctorsInfo">
                    </div>
                </div>
                <div id="summerypatientfile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
                    <div align="center" style="display:none" id="summeryProgressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id="summpatfileInfo">
                    </div>
                </div>
                <div id="showdataMedics" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
                    <div align="center"  id="medicsprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                    <div id="myMedics">
                    </div>
                </div>
                <div id="getreferralform" style="display: none;height:340px;overflow-x:hidden;overflow-y:scroll;background-color: white">
                    <div align="center"  id="referalprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id="container" style="display:none">
                        <div id="default">
                            <h1>#{title}</h1>
                            <p>#{text}</p>
                        </div>
                    </div>
                    <div id="referralData"></div>

                    <div style="text-align:center;margin: 20px 0">

                        <input type="button" onclick="referral_patient(<?php echo $Registration_ID; ?>,<?php echo $Admision_ID; ?>,<?php echo $_GET['consultation_ID']; ?>)" class="art-button-green" value="Referral Patient">
                    </div>
                    <div style="text-align:center;margin: 20px 0">
                        <a href="">
                        <input type='button' name='patient_transfer_referral'  value='ANETHESIA' onclick='showReferralForm(<?php echo $Registration_ID; ?>,<?php echo $Admision_ID; ?>,<?php echo $_GET['consultation_ID']; ?>)' class='art-button-green' />
                    </a>
                    </div>

                </div>
    <div id="getFileByFolio" style="display: none;">
        <div align="center" id="getFileByFolioprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
        <div id="containerFileFolio">

        </div>
    </div>

                <script>
                    //Show or Hide the Clinical Notes button
                    function AllowDoctor(Item_ID) {
                        var ClinicalButton = document.getElementById('clinicalnotes');
                        var hrefCheck = $('#clinicalnotes').attr('href').indexOf('item_ID');
                        var str = 'nothing';
                        $("#icu_prog_not_btn").show();
                        if (Item_ID == '') {
                            // ClinicalButton.style = "display:none;";
                            //            $('#clinicalnotes').attr('style', 'display:none;');
                            //            document.getElementById('checkingPrice').value = '';
                            //
                            //            if (hrefCheck > -1) {
                            //                str = $('#clinicalnotes').attr('href').substring(0, hrefCheck - 1);
                            //            }
                            //
                            //            var href = str + '&item_ID=' + Item_ID;
                            //            $('#clinicalnotes').attr('href', href);
                            window.location = window.location.href;
                        } else {

                            //alert(hrefCheck.indexOf('item_ID'));

                            if (hrefCheck == -1) {
                                str = $('#clinicalnotes').attr('href');
                            } else {
                                str = $('#clinicalnotes').attr('href').substring(0, hrefCheck - 1);
                            }

                            // alert(str);

                            var href = str + '&item_ID=' + Item_ID;
                            $('#clinicalnotes').attr('href', href);

                            getItemPrice(Item_ID, '<?php echo $Billing_Type; ?>', '<?php echo $Guarantor_Name; ?>','<?= $Sponsor_ID ?>');
                            // ClinicalButton.style = "display:inherit;";
                            $('#clinicalnotes').attr('style', '');
                            // $('#clinicalnotes').hide();
                        }
                    }
                </script>
                <script>
                    function getItemPrice(Item_ID, Billing_Type, Guarantor_Name,Sponsor_ID) {
                        $.ajax({
                            type: 'GET',
                            url: 'Get_Item_price.php',
                            data: 'Item_ID=' + Item_ID + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID,
                            cache: false,
                            success: function (html) {
                                //alert(html);
                                document.getElementById('checkingPrice').value = html;
                            }
                        });
                    }
                </script>
                <script>
                    function getTodayLastTime(redID, consID) {
                        if (window.XMLHttpRequest) {
                            ajaxTimeObjt = new XMLHttpRequest();
                        } else if (window.ActiveXObject) {
                            ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                            ajaxTimeObjt.overrideMimeType('text/xml');
                        }
                        ajaxTimeObjt.onreadystatechange = function () {
                            var data = ajaxTimeObjt.responseText;
                            document.getElementById('doctorsInfo').innerHTML = data;
                            $("#otherdoctorStaff").dialog("open");
                        }; //specify name of function that will handle server response....
                        ajaxTimeObjt.open("GET", "get_today_round_bills.php?consultation_ID=" + consID + "&Registration_ID=" + redID + "", true);
                        ajaxTimeObjt.send();


                    }
                </script>
                <link rel="stylesheet" href="css/select2.min.css" media="screen">
                <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
                <script src="js/select2.min.js"></script>
                <script src="css/jquery.js"></script>
                <script src="css/jquery.datetimepicker.js"></script>
                <script src="js/jquery-1.8.0.min.js"></script>
                <script src="js/jquery-ui-1.8.23.custom.min.js"></script>

                <script>
                    $(document).ready(function () {
                        $("#Previous_Records").dialog({autoOpen: false, width: '85%', height: 400, title: 'eHMS 2.0 : Previous Pre Operatives ~ <?php echo ucwords(strtolower($Patient_Name)); ?>', modal: true});
                    });
                </script>

                <script>
                    $(document).ready(function () {

                        $("#otherdoctorStaff").dialog({autoOpen: false, width: '60%', height: 320, title: 'TODAY\'S ROUND CHARGES FOR <?php echo str_replace("'", '', $Patient_Name) ?>', modal: true});

                    });
                </script>
                <script>
                    function Show_Patient_File() {
                        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $_GET['Registration_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
                        //winClose.close();
                        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

                    }

                    function popupwindow(url, title, w, h) {
                        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                        var wTop = window.screenTop ? window.screenTop : window.screenY;

                        var left = wLeft + (window.innerWidth / 2) - (w / 2);
                        var top = wTop + (window.innerHeight / 2) - (h / 2);
                        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                        return mypopupWindow;
                    }

                </script>
                <script>
                    function showSummeryPatientFile() {
                        $.ajax({
                            type: 'GET',
                            url: 'get_summery_pat_file.php',
                            data: 'consultation_ID=<?php echo $consultation_ID ?>&Registration_ID=<?php echo $Registration_ID ?>',
                            cache: false,
                            beforeSend: function (xhr) {
                                $('#summpatfileInfo').html('');
                                $('#summeryProgressStatus').show();
                            },
                            success: function (html) {
                                $('#summpatfileInfo').html(html);
                                $("#summerypatientfile").dialog("open");
                            }, complete: function (jqXHR, textStatus) {
                                $('#summeryProgressStatus').hide();
                            }, error: function (jqXHR, textStatus, errorThrown) {
                                $('#summeryProgressStatus').hide();
                            }
                        });


                    }
                </script>
                <script>
                    $(document).ready(function () {//
                        $("#summerypatientfile").dialog({autoOpen: false, width: '95%', height: 620, title: 'PATIENT FILE', modal: true, position: 'middle'});
                        $("#getreferralform").dialog({autoOpen: false, width: '95%', height: 620, title: 'PATIENT REFERRAL FORM', modal: true, position: 'middle'});
                        $("#showdataMedics").dialog({autoOpen: false, width: '85%', height: 450, title: 'SELECT CHRONIC MEDICATIONS', modal: true, position: 'middle'});
                        $container = $("#container").notify();
                    });
                </script>
                    <!--<div align="center" style="" id="referalprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>-->
                <script>

                    function showReferralForm(regid, admID, consID) {
                       
                       $('#referralData').html('');
                        $("#getreferralform").dialog('option', 'title', 'PATIENT REFERRAL FORM FOR <?php echo $Patient_Name ?>' + '  ' + '#.' + regid);
                        $("#getreferralform").dialog("open");
                        var dataString = 'src=inpat&regid=' + regid + '&admID=' + admID + '&consID=' + consID;

                        $.ajax({
                            type: 'GET',
                            url: 'requests/get_referral_form.php',
                            data: dataString,
                            beforeSend: function (xhr) {
                                $('#referalprogress').show();
                            },
                            success: function (result) {
                                $('#referralData').html(result);
                            }, complete: function (jqXHR, textStatus) {
                                $('#referalprogress').hide();
                            }
                        });
                        //alert(redid+' '+Patient_Payment_ID+' '+Patient_Payment_Item_List_ID);
                    }
                </script>
                <script>
                    function referral_patient(regid, admID, consID)
                    {
                        var referral_from = $('#referral_from').val();
                        var referral_to = $('#referral_to').val();
                        var transfer_date = $('#transfer_date').val();
                        var diagnosis = $('#diagnosis').val();
                        var temp = $('#temp').val();
                        var heatrate = $('#heatrate').val();
                        var resprate = $('#resprate').val();
                        var bloodpressure = $('#bloodpressure').val();
                        var mental_status = $('#mental_status').val();
                        var alert = $('#alert').val();
                        var patienthist = $('#patienthist').val();
                        var chrnicmed = $('#chrnicmed').val();
                        var medalergy = $('#medalergy').val();
                        var pertnetfindings = $('#pertnetfindings').val();
                        var labresult = $('#labresult').val();
                        var radresult = $('#radresult').val();
                        var treatmentrendered = $('#treatmentrendered').val();
                        var reasonfortransfer = $('#reasonfortransfer').val();
                        var doct_phone_number = $('#doct_phone_number').val();
                        var call_phone_number = $('#call_phone_number').val();

                        var valerr = false;

                        if (referral_to == '') {
                            $('#referral_to').css('border', '2px solid red');
                            valerr = true;
                        } else {
                            $('#referral_to').css('border', '2px solid #ccc')
                        }
                        if (reasonfortransfer == '') {
                            $('#reasonfortransfer').css('border', '2px solid red');
                            valerr = true;
                        } else {
                            $('#reasonfortransfer').css('border', '2px solid #ccc')
                        }
                        if (doct_phone_number == '') {
                            $('#doct_phone_number').css('border', '2px solid red');
                            valerr = true;
                        } else {
                            $('#doct_phone_number').css('border', '2px solid #ccc')
                        }

                        if (valerr) {
                            exit;
                        }

                        if (!confirm('Are you sure your want to referral this patient?')) {
                            exit;
                        }
                        var dataString = 'src=inpat&regid=' + regid + '&admID=' + admID + '&consID=' + consID +
                                '&referral_from=' + referral_from + '&referral_to=' + referral_to + '&transfer_date=' + transfer_date +
                                '&diagnosis=' + diagnosis + '&temp=' + temp + '&heatrate=' + heatrate +
                                '&resprate=' + resprate + '&bloodpressure=' + bloodpressure + '&mental_status=' + mental_status +
                                '&alert=' + alert + '&patienthist=' + patienthist + '&chrnicmed=' + chrnicmed +
                                '&medalergy=' + medalergy + '&pertnetfindings=' + pertnetfindings + '&labresult=' + labresult +
                                '&radresult=' + radresult + '&treatmentrendered=' + treatmentrendered + '&reasonfortransfer=' + reasonfortransfer +
                                '&doct_phone_number=' + doct_phone_number + '&call_phone_number=' + call_phone_number;
                        var st = '';
                        $.ajax({
                            type: 'POST',
                            url: 'requests/get_referral_form.php',
                            data: dataString,
                            beforeSend: function (xhr) {
                                $('#referalprogress').show();
                            },
                            success: function (result) {
                                if (result == '1') {
                                    st = 'Patient Reffered Successfully';
                                } else if (result == '0') {
                                    st = 'An error has occured.Please try again letter!';
                                } else {
                                    st = 'Fail to process.If problem persits contact administrator';
                                }
                                create("default", {title: 'Success', text: st});

                                if (result == '1') {
                                    document.location = 'admittedpatientlist.php';
                                }
                            }, complete: function (jqXHR, textStatus) {
                                $('#referalprogress').hide();
                            }
                        });
                        //alert(redid+' '+Patient_Payment_ID+' '+Patient_Payment_Item_List_ID);
                    }
                </script>
                <script>
                    function getChrnMedics() {
                        $('#myMedics').html('');
                        $("#showdataMedics").dialog('option', 'title', 'SELECT CHRONIC MEDICATIONS FOR <?php echo $Patient_Name ?>' + '  ' + '#.' + <?php echo $Registration_ID ?>);
                        $("#showdataMedics").dialog("open");
                        $.ajax({
                            type: 'GET',
                            url: 'getitemfromlistselect.php',
                            data: 'Consultation_Type=Pharmacy',
                            beforeSend: function (xhr) {
                                $('#medicsprogress').show();
                            },
                            success: function (result) {
                                $('#myMedics').html(result);
                            }, complete: function (jqXHR, textStatus) {
                                $('#medicsprogress').hide();
                            }
                        });
                    }
                </script>
                <script>
                    function append_item(item_id) {
                        var item_name = $.trim($('#item_' + item_id).text());
                        var has_data = $('#chrnicmed').val();
                        if (has_data == '') {
                            $('#chrnicmed').val(item_name);
                        } else {
                            $('#chrnicmed').val(has_data + '; ' + item_name);
                        }

                    }
                </script>
   <script>
    function patientFileByFolio() {
//        $("#getFileByFolio").dialog('option', 'title', 'PATIENT FILE BY FOLIO FOR <?php echo $Patient_Name ?>);
        $("#getFileByFolio").dialog("open");
         var Start_Date='000-00-00';
         var End_Date='<?=$Today?>';
         var Billing_Type='All';
         var Sponsor_ID='<?=$Sponsor_ID?>';
         var Patient_Number='<?=$Registration_ID?>';
         var Patient_Type='All';


        $.ajax({
            type: 'GET',
            url: 'Revenue_Collection_By_Folio_Report_Filtered.php',
            data: {Start_Date:Start_Date,End_Date:End_Date,Billing_Type:Billing_Type,Sponsor_ID:Sponsor_ID,Patient_Number:Patient_Number,Patient_Type:Patient_Type},
            beforeSend: function (xhr) {
                $('#getFileByFolioprogress').show();
            },
            success: function (result) {
                $('#containerFileFolio').html(result);
            }, complete: function (jqXHR, textStatus) {
                $('#getFileByFolioprogress').hide();
            }
        });


        //alert(redid+' '+Patient_Payment_ID+' '+Patient_Payment_Item_List_ID);
    }
    $(document).ready(function () {
        $("#getFileByFolio").dialog({autoOpen: false, width: '80%', height: 450, title: 'PATIENT FILE BY FOLIO', modal: true});
    })
</script>             
                <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

                <link rel="stylesheet" href="css/ui.notify.css" media="screen">
                <script src="js/jquery-1.8.0.min.js"></script>
                <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
                <script src="css/jquery.datetimepicker.js"></script>
                <script src="js/jquery.notify.min.js"></script> 

                <script>
                    function create(template, vars, opts) {
                        return $container.notify("create", template, vars, opts);
                    }
                </script>

                <script>
                    $('#datatable2').DataTable({
                        'bJQueryUI': true
                    });
                </script>
                <?php
                include("./includes/footer.php");
                ?>
