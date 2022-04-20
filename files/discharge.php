<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        } else {
//            @session_start();
//            if (!isset($_SESSION['Admission_Supervisor'])) {
//                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
//            }
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


$section = '';
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    
}
$Admission_Number = '';
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
$Admision_ID = '';
if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,Claim_Number_Status,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                     
                                      FROM tbl_patient_registration pr, tbl_sponsor sp 
                                        WHERE pr.Sponsor_ID = sp.Sponsor_ID AND 
                                              pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID']; /*
              $Second_Name = $row['Second_Name'];
              $Last_Name = $row['Last_Name']; */
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
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
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->m . " Months";
        }
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->d . " Days";
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = ''; /*
          $Second_Name = '';
          $Last_Name = ''; */
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
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Sponsor_ID = ''; /*
      $Second_Name = '';
      $Last_Name = ''; */
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
    $age = 0;
}
?>

<!--GET ADMISSION INFORMATION-->
<?php
if (isset($_GET['Registration_ID'])) {
    $select_from_admission = "SELECT * FROM tbl_admission WHERE Discharge_Clearance_Status = 'cleared'  AND Admission_Status != 'Discharged' AND Admision_ID='$Admision_ID' AND Registration_ID ='$Registration_ID'";
    $result = mysqli_query($conn,$select_from_admission) OR die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    //while($row){
    $Admision_ID = $row['Admision_ID'];
    $Registration_ID = $row['Registration_ID'];
    $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
    $Bed_ID = $row['Bed_ID'];
    $Admission_Employee_ID = $row['Admission_Employee_ID'];
    $Admission_Supervisor_ID = $row['Admission_Supervisor_ID'];
    $Admission_Date_Time = $row['Admission_Date_Time'];
    $District = $row['District'];
    $Ward = $row['Ward'];
    $Admission_Claim_Form_Number = $row['Admission_Claim_Form_Number'];
    $Folio_Number = $row['Folio_Number'];
    $Office_Area = $row['Office_Area'];
    $Office_Plot_Number = $row['Office_Plot_Number'];
    $Office_Street = $row['Office_Street'];
    $Office_Phone = $row['Office_Phone'];
    $Kin_Name = $row['Kin_Name'];
    $Kin_Relationship = $row['Kin_Relationship'];
    $Kin_Phone = $row['Kin_Phone'];
    $Kin_Area = $row['Kin_Area'];
    $Kin_Street = $row['Kin_Street'];
    $Kin_Plot_Number = $row['Kin_Plot_Number'];
    $Kin_Address = $row['Kin_Address'];
    $Discharge_Clearance_Status = $row['Discharge_Clearance_Status'];

    //}
} else {
    $Admision_ID = '';
    $Registration_ID = '';
    $Hospital_Ward_ID = '';
    $Bed_ID = '';
    $Admission_Employee_ID = '';
    $Admission_Supervisor_ID = '';
    $Admission_Date_Time = '';
    $District = '';
    $Ward = '';
    $Admission_Claim_Form_Number = '';
    $Folio_Number = '';
    $Office_Area = '';
    $Office_Plot_Number = '';
    $Office_Street = '';
    $Office_Phone = '';
    $Kin_Name = '';
    $Kin_Relationship = '';
    $Kin_Phone = '';
    $Kin_Area = '';
    $Kin_Street = '';
    $Kin_Plot_Number = '';
    $Kin_Address = '';
    $Discharge_Clearance_Status = '';
}
?>
<!--END OF ADMISSION INFORMATION-->


<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}

//Count Patients to be DISCHARGE
$count_patients = "SELECT COUNT(*) as patients FROM tbl_admission WHERE Discharge_Clearance_Status = 'cleared'  AND Admission_Status != 'Discharged'";
$counted_patients = mysqli_query($conn,$count_patients) or die(mysqli_error($conn));
while ($patientscount = mysqli_fetch_assoc($counted_patients)) {
    $patients = $patientscount['patients'];
}

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}
?>


<?php
if (isset($_POST['send_admition_form'])) {
    //exit;
    $Admision_ID = $_POST['Admision_ID'];
    $Registration_ID = $_POST['Registration_ID'];
    $Admission_Status = 'Discharged';
    $Discharge_Claim_Form_Number = $_POST['Discharge_Claim_Form_Number'];
    $Discharge_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
   // $Discharge_Supervisor_ID = $_SESSION['Admission_Supervisor']['Employee_ID'];
    $Discharge_Supervisor_ID=$Discharge_Employee_ID; 
    $Discharge_Date_Time = '';
    //$Discharge_Reason_ID = $_POST['Discharge_Reason_ID'];
    
   // $Bed_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Bed_ID FROM tbl_admission WHERE Admision_ID = $Admision_ID"))['Bed_ID'];

    $update_query = "UPDATE tbl_admission SET Discharge_Employee_ID='$Discharge_Employee_ID',
    Discharge_Supervisor_ID='$Discharge_Supervisor_ID',Discharge_Date_Time=(SELECT NOW()),
    Discharge_Claim_Form_Number='$Discharge_Claim_Form_Number',
    Admission_Status='$Admission_Status' WHERE Admision_ID = $Admision_ID OR Registration_ID='$Registration_ID'";
    $result_query=mysqli_query($conn,$update_query) or die(mysqli_error($conn));
    if ($result_query) {
//        $set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
//
//        if ($set_duplicate_bed_assign == '0') {
//            $change_bed_status = "UPDATE tbl_beds SET Status = 'available' WHERE Bed_ID = '$Bed_ID'";
//            $bed_available = mysqli_query($conn,$change_bed_status) or die(mysqli_error($conn));
//        } else {
//           
//            $select_bed_status = "SELECT Admision_ID FROM tbl_admission WHERE Bed_ID=$Bed_ID AND Admission_Status != 'Discharged' AND Admision_ID !='$Admision_ID'";
//            
//            $qry_check = mysqli_query($conn,$select_bed_status) or die(mysqli_error($conn));
//            if (mysqli_num_rows($qry_check) == 0) {
//                $change_bed_status = "UPDATE tbl_beds SET Status = 'available' WHERE Bed_ID = '$Bed_ID'";
//                $bed_available = mysqli_query($conn,$change_bed_status) or die(mysqli_error($conn));
//            }
//        }
        //Releasing a Bed
        ?>
        <script>
            alert('PATIENT DISCHARGED SUCCESSFULLY !');
            document.location = "./searchlistofoutpatientadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('PATIENT NOT DISCHARGED TRY AGAIN !');
        </script>
        <?php
    }
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<script>
    window.setInterval(function () {
        //function GetNotificationz() {
        var tbl = 'tbl_check_in_details';
        if (window.XMLHttpRequest) {
            notif = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            notif = new ActiveXObject('Micrsoft.XMLHTTP');
            notif.overrideMimeType('text/xml');
        }

        notif.onreadystatechange = NOTIFY; //specify name of function that will handle server response....
        notif.open('GET', 'GetNotificationdischarge.php', true);
        notif.send();

        //setTimeout('GetNotificationz()', 5000); // Every 15 seconds.
        //}
    }, 10000);
    function NOTIFY() {
        var count = notif.responseText;
        var sound_alert = document.getElementById('sound_alert');
        var alert_here = document.getElementById('alert_here');
        var alert_control = document.getElementById('alert_control');
        //
        if (count != '') {
            //alert(count);
            if (count > 0) {
                alert_here.style.display = '';
                alert_here.innerHTML = count;
                if (parseInt(count) > parseInt(alert_control.value)) {
                    var audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', 'sound/double_tone.mp3');
                    audioElement.play();
                }
                alert_control.value = count;

            }
        }

    }
</script>

<a href='admissionworkspage.php?section=<?php echo $section; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
    ADMISSION MAIN WORKPAGE
</a>
<input type='hidden' id='alert_control' value='' />
<a href='searchlistofoutpatientadmited.php?section=<?php echo $section; ?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
    DISCHARGE AWAIT&nbsp;&nbsp;<span id='alert_here' style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;<?php if ($patients == 0) echo "display:none;"; ?> '> <?php echo $patients; ?> </span>
</a>
<a href='./searchlistofoutpatientadmited.php?section=<?php echo $section; ?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
    BACK
</a>

<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <br>
    <fieldset> 
        <legend align="right"><b>DISCHARGE PATIENT</b></legend>
        <center>
        </center>
        <!--<br/>-->
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='16%' style="text-align:right;">Patient Name</td>
                                <td width='26%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo ucwords(strtolower($Patient_Name)); ?>'></td>
                                <td width='13%' style="text-align:right;">Card Id Expire Date</td>
                                <td width='16%' style="text-align:right;"><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='13%' style="text-align:right;">Discharge Date And Time</td>
                                <td width='16%'><input type='text' name='Admission_Date_And_Time' id='Admission_Date_And_Time' value='<?php echo date('Y-m-d H:i:s'); ?>' disabled='disabled'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Gender</td><td width='16%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>

                                <td style="text-align:right;">Claim Form Number</td>
                                <td><input type='text' name='Discharge_Claim_Form_Number' id='Discharge_Claim_Form_Number' value='<?php echo $Admission_Claim_Form_Number; ?>'<?php if ($Claim_Number_Status == 'Mandatory') { ?> required='required'<?php } ?>></td>
                                <td style="text-align:right;">Admission Number</td>
                                <td>
                                    <input type='text' disabled='disabled' value='<?php echo $Admision_ID; ?>'>
                                    <input type='hidden' name='Admision_ID' id='Admision_ID' value='<?php echo $Admision_ID; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                            <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                            <td style="text-align:right;">Ward</td>
                            <td>
                                <?php
                                if (isset($_SESSION['userinfo']['Branch_ID']) && $Hospital_Ward_ID != '') {
                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                                    $select_ward = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($select_ward)) {
                                        $Ward_Name = $row['Hospital_Ward_Name'];
                                    }

//                                    $select_bed = mysqli_query($conn,"SELECT * FROM tbl_beds WHERE Bed_ID = '$Bed_ID'") or die(mysqli_error($conn));
//                                    while ($row = mysqli_fetch_array($select_bed)) {
//                                        $Bed_Name = $row['Bed_Name'];
//                                    }
                                    $Bed_Name="";
                                    ?>
                                    <input type='text' disabled='disabled' value='<?php echo $Ward_Name; ?>'>
                                    <input type='text' disabled='disabled' value='<?php echo $Bed_Name; ?>'>
                                <?php } else { ?>
                                    <input type='text' disabled='disabled' value='Unknown'>
                                <?php } ?>
                            </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Previous Number</td>
                    <td>
                        <input type='text' name='Old_Registration_Number' id='Old_Registration_Number' disabled='disabled' value='<?php echo $Old_Registration_Number; ?>'>
                    </td>
                    <td style="text-align:right;">Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                    <td style="text-align:right;">Folio Number</td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Folio_Number; ?>'>
                        <input type='hidden' name='Folio_Number' id='Folio_Number' value='<?php echo $Folio_Number; ?>'>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Region</td>
                    <td>
                        <input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'>
                    </td>
                    <td style="text-align:right;">Registration Number</td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
                        <input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
                    </td>
                    <td style="text-align:right;">Prepared By</td>
                    <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>
                    <td style="text-align:right;">District</td>
                    <td>
                        <input type='text'disabled='disabled' value='<?php echo $District; ?>'>
                        <input type='hidden' name='District' id='District' value='<?php echo $District; ?>'>
                        <input type='hidden' name='Ward' id='Ward' value='<?php echo $Ward; ?>'>
                    </td>
                    <td style="text-align:right;">Member Number</td>
                    <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
                    <td style="text-align:right;">Supervised By</td>
                    <?php
                    if (isset($_SESSION['admissionsupervisor'])) {
                        if (isset($_SESSION['admissionsupervisor']['Session_Master_Priveleges'])) {
                            if ($_SESSION['admissionsupervisor']['Session_Master_Priveleges'] = 'yes') {
                                $Supervisor = $_SESSION['admissionsupervisor']['Employee_Name'];
                                ?>
                            <input type='hidden' id='Admission_Supervisor_ID' name='Admission_Supervisor_ID' value='<?php echo $_SESSION['admissionsupervisor']['Employee_ID']; ?>'>
                            <?php
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                } else {
                    $Supervisor = "Unknown Supervisor";
                }
                ?>
                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                </tr> 
            </table>
            </td> 
            </tr>
            </table>
        </center>
    </fieldset>
    <fieldset>
        <table  width=100%>
            <tr>
                <td colspan=2><center><b>OFFICE/BUSSINESS/WORK ADDRESS</b></center></td>
            <td colspan=2><center><b>NEXT OF KIN NAME AND CONTACT</b></center></td>
            <td colspan=2><center><b>NEXT OF KIN ADDRESS</b></center></td>
            </tr>
            <tr>
                <td width='16%' style="text-align:right;">Area/Office :</td>
                <td width='26%'><input type='text' id='Office_Area' name='Office_Area' disabled='disabled' value='<?php echo $Office_Area; ?>'></td>

                <td width='13%' style="text-align:right;">Name :</td>
                <td width='16%'><input type='text' id='Kin_Name' name='Kin_Name' disabled='disabled' value='<?php echo ucwords(strtolower($Kin_Name)); ?>'></td>

                <td width='13%' style="text-align:right;">Area :</td>
                <td width='16%'><input type='text' id='Kin_Area' name='Kin_Area' disabled='disabled' value='<?php echo $Kin_Area; ?>'></td>
            </tr>
            <tr>
                <td style="text-align:right;">Plot N<u>o</u> :</td>
            <td><input type='text' id='Office_Plot_Number' name='Office_Plot_Number' disabled='disabled' value='<?php echo $Office_Plot_Number; ?>'></td>

            <td style="text-align:right;">Relationship :</td>
            <td><input type='text' id='Kin_Relationship' name='Kin_Relationship' disabled='disabled' value='<?php echo $Kin_Relationship; ?>'></td>

            <td style="text-align:right;">Street :</td>
            <td><input type='text' id='Kin_Street' name='Kin_Street' disabled='disabled' value='<?php echo $Kin_Street; ?>'></td>
            </tr>
            <tr>
                <td style="text-align:right;">Street :</td>
                <td><input type='text' id='Office_Street' name='Office_Street' disabled='disabled' value='<?php echo $Office_Street; ?>'></td>

                <td style="text-align:right;">Phone :</td>
                <td><input type='text' id='Kin_Phone' name='Kin_Phone' disabled='disabled' value='<?php echo $Kin_Phone; ?>'></td>

                <td style="text-align:right;">Plot N<u>o</u> :</td>
            <td><input type='text' id='Kin_Plot_Number' name='Kin_Plot_Number' disabled='disabled' value='<?php echo $Kin_Plot_Number; ?>'></td>
            </tr>
            <tr>
                <td style="text-align:right;">Phone :</td>
                <td><input type='text' id='Office_Phone' name='Office_Phone' disabled='disabled' value='<?php echo $Office_Phone; ?>'></td>

                <td></td>
                <td></td>

                <td style="text-align:right;">Address :</td>
                <td><input type='text' id='Kin_Address' name='Kin_Address' disabled='disabled' value='<?php echo $Kin_Address; ?>'></td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
	<?php 
	$discharge=mysqli_query($conn,"SELECT Discharge_Reason FROM tbl_discharge_reason dis,tbl_admission ad WHERE dis.Discharge_Reason_ID=ad.Discharge_Reason_ID AND ad.Admision_ID = $Admision_ID") or die(mysqli_error($conn));
	while($dis_reason=mysqli_fetch_assoc($discharge)){
	$discharge_reason1=$dis_reason['Discharge_Reason'];
	}
	 ?>
        <table width='100%'>
            <tr>
               <td width='16%' style="text-align:right;">Discharge Reason :</td><td>
   <input style='width:31%' type='text' id=''  disabled='disabled' value='<?php echo $discharge_reason1; ?>'>
    </tr>
<tr>
                <td colspan=2 style="text-align:center;">
                    <?php
                    if (isset($_GET['Registration_ID'])) {
                        if ($Discharge_Clearance_Status == 'cleared') {
                            ?>
                            <input type='submit' value='DISCHARGE' class='art-button-green' onclick="return confirm('Are you sure you want to discharge the patient?')">
                            <?php
                        } else {
                            echo ' <input type="button" value="DISCHARGE" class="art-button-green" onclick="alert(\'The patient is not yet cleared his/her billing(s). Please communicate with billing DEPT for assistance!\')">';
                        }
                    } else {
                        ?>
                        <input type='button' value='DISCHARGE' class='art-button-green' onclick="alert('Choose Patient To Discharge!');">
                    <?php } ?>
                    <input type='reset' value='CLEAR' class='art-button-green' >
                    <input type="text"hidden="hidden" value="<?= $Registration_ID ?>" name="Registration_ID">
                    <input type='hidden' id='send_admition_form' name='send_admition_form'>
                </td>
            </tr>
        </table>
    </fieldset>
</form>
<?php
include("./includes/footer.php");
?>