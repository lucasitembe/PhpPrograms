<?php
include("./includes/header.php");
include("./includes/connection.php");	
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Admission_Supervisor'])) {
                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
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
    $select_Corpse = mysqli_query($conn,"SELECT ma.*,
                            Title,Patient_Name,Gender,Registration_Date_And_Time,
                            pr.Registration_ID, pr.Date_Of_Birth, ad.Admission_Date_Time, ad.Admission_Employee_ID 
                                      FROM tbl_patient_registration pr,tbl_admission ad,tbl_mortuary_admission ma
                                        WHERE ma.Admision_ID = ad.Admision_ID AND 
                                              pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn)."corpseInfo");
    $no = mysqli_num_rows($select_Corpse);
 
   '<img src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=http://www.mrc-productivity.com/techblog/?p=1172"/>';

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Corpse)) {
            $Registration_ID = $row['Registration_ID'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Gender = $row['Gender'];
            $Date_In = $row['Admission_Date_Time'];
            $Place_Of_Death = $row['Place_Of_Death'];
            $Ward = $row['Ward'];
            $Mortuary_Deadline = $row['Mortuary_Deadline'];
            $Corpse_Brought_By = $row['Corpse_Brought_By'];
            $Corpse_Received_By = $row['Corpse_Received_By'];
            $Corpse_Kin_Relationship = $row['Corpse_Kin_Relationship'];
            $Corpse_Kin_Phone = $row['Corpse_Kin_Phone'];
            $Corpse_Kin_Address = $row['Corpse_Kin_Address'];
            $Vehicle_No_In = $row['Vehicle_No_In'];
            $Other_Details = $row['Other_Details'];
            $case_type = $row['case_type'];
            $Police_No = $row['Police_No'];
            $Police_Place = $row['Police_Place'];
            $Police_Phone = $row['Police_Phone'];
            $Postmortem_Done_By = $row['Postmortem_Done_By'];
            $Postmortem_No = $row['Postmortem_No'];
            $Police_Station = $row['Police_Station'];
            $Police_Title = $row['Police_Title'];
            $Police_Name = $row['Police_Name'];
            $Police_Postal_Box = $row['Police_Postal_Box'];
            $Date_Of_Death = $row['Date_Of_Death'];
			$Date_Of_Birth = $row['Date_Of_Birth'];
			//echo $Date_Of_Birth; exit();
        }
        $age = floor((strtotime($Date_Of_Death) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($age == 0) {
            $date1 = new DateTime($Date_Of_Death);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->m . " Months";
        }
        if ($age == 0) {
            $date1 = new DateTime($Date_Of_Death);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->d . " Days";
        }
    } else {
        $Registration_ID = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Date_In = ''; 
        $Place_Of_Death = '';  
        $Ward = '';
		$Date_Of_Birth = '';
        $Mortuary_Deadline ='';
        $Corpse_Brought_By = '';
        $Corpse_Received_By = '';
        $Corpse_Kin_Relationship = '';
        $Corpse_Kin_Phone = '';
        $Corpse_Kin_Address = '';
        $Vehicle_No_In = '';
        $Other_Details = '';
        $case_type = '';
        $Police_No = '';
        $Police_Place = '';
        $Police_Phone = '';
        $Postmortem_Done_By = '';
        $Postmortem_No = '';
        $Police_Station = '';
        $Police_Title = '';
        $Police_Name = '';
        $Police_Postal_Box = '';
		$age = 0;
   }
} else {
		$Registration_ID = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Date_In = ''; 
        $Place_Of_Death = '';  
        $Ward = '';
		$Date_Of_Birth = '';
        $Mortuary_Deadline ='';
        $Corpse_Brought_By = '';
        $Corpse_Received_By = '';
        $Corpse_Kin_Relationship = '';
        $Corpse_Kin_Phone = '';
        $Corpse_Kin_Address = '';
        $Vehicle_No_In = '';
        $Other_Details = '';
        $case_type = '';
        $Police_No = '';
        $Police_Place = '';
        $Police_Phone = '';
        $Postmortem_Done_By = '';
        $Postmortem_No = '';
        $Police_Station = '';
        $Police_Title = '';
        $Police_Name = '';
		$Kin_Out_Address='';
        $Police_Postal_Box = '';
		$age = 0;
}
?>


<!--GET ADMISSION INFORMATION-->
<?php
if (isset($_GET['Registration_ID'])) {

    $select_from_admission = "SELECT ma.* FROM tbl_mortuary_admission ma JOIN tbl_admission ad ON ma.Admision_ID=ad.Admision_ID WHERE ad.Discharge_Clearance_Status = 'cleared'  AND ad.Admission_Status != 'Discharged' AND ma.Admision_ID='$Admision_ID' AND ma.Corpse_ID ='$Registration_ID' AND ad.Admision_ID='$Admision_ID' AND ad.Registration_ID ='$Registration_ID'";
   
	$result = mysqli_query($conn,$select_from_admission) OR die(mysqli_error($conn).'admissinInfo');
    $row = mysqli_fetch_assoc($result);
    //while($row){
    //$Registration_ID = $row['Corpse_ID'];
	$Corpse_ID = $row['Corpse_ID'];
	//$Date_Of_Birth =$row['Corpse_ID'];
	//$Gender = $row['Gender'];
	//$Date_In = $row['Date_In']; 
	$Place_Of_Death = $row['Place_Of_Death'];
    $Hospital_Ward_ID = $row['Hospital_Ward_ID'];  
	$Ward = $row['Ward'];
    $Bed_ID = $row['Bed_ID'];
	//$Date_Of_Birth = $row['Date_Of_Birth'];
	//$Mortuary_Deadline =$row['Mortuary_Deadline'];
	$Corpse_Brought_By = $row['Corpse_Brought_By'];
	$Corpse_Received_By = $row['Discharge_Employee_ID'];
	$Corpse_Kin_Relationship = $row['Corpse_Kin_Relationship'];
	$Corpse_Kin_Phone = $row['Corpse_Kin_Phone'];
	$Corpse_Kin_Address = $row['Corpse_Kin_Address'];
	$Vehicle_No_In = $row['Vehicle_No_In'];
	$Other_Details = $row['Other_Details'];
	$case_type = $row['case_type'];
	$Police_No = $row['Police_No'];
	$Police_Place = $row['Police_Place'];
	$Police_Phone = $row['Police_Phone'];
	$Postmortem_Done_By = $row['Postmortem_Done_By'];
	$Postmortem_No = $row['Postmortem_No'];
	$Police_Station = $row['Police_Station'];
	$Police_Title = $row['Police_Title'];
	$Police_Name = $row['Police_Name'];
	$Police_Postal_Box = $row['Police_Postal_Box'];
	

    //}
} else {
    $Registration_ID = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Date_In = ''; 
        $Place_Of_Death = '';  
        $Ward = '';
		$Bed_ID = '';
		$Date_Of_Birth = '';
        $Mortuary_Deadline ='';
        $Corpse_Brought_By = '';
       // $Corpse_Received_By = '';
        $Corpse_Kin_Relationship = '';
        $Corpse_Kin_Phone = '';
        $Corpse_Kin_Address = '';
        $Vehicle_No_In = '';
        $Other_Details = '';
        $case_type = '';
        $Police_No = '';
        $Police_Place = '';
        $Police_Phone = '';
        $Postmortem_Done_By = '';
        $Postmortem_No = '';
        $Police_Station = '';
        $Police_Title = '';
        $Police_Name = '';
        $Police_Postal_Box = '';
		$age = 0;
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
$count_patients = "SELECT COUNT(ma.Admision_ID) as patients FROM tbl_mortuary_admission ma JOIN tbl_admission ad ON ma.Admision_ID=ad.Admision_ID WHERE Discharge_Clearance_Status = 'cleared'  AND Admission_Status != 'Discharged'";
$counted_patients = mysqli_query($conn,$count_patients) or die(mysqli_error($conn));
while ($patientscount = mysqli_fetch_assoc($counted_patients)) {
    $patients = $patientscount['patients'];
}

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}
// $get_rcvd_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Corpse_Received_By'";
						// $get_rcvd_name = mysql_result(mysqli_query($conn,$get_rcvd_name) OR die(mysqli_error($conn).'admissinInfo'));
						// echo $get_rcvd_name; exit();

?>


<?php
if (isset($_POST['send_admition_form'])) {
    $Admision_ID = $_POST['Admision_ID'];
    $Time_Out = '';
    $Kin_Out_Address = $_POST['Kin_Out_Address'];
    $Kin_Out_Relationship = $_POST['Kin_Out_Relationship'];
    $Taken_By = $_POST['Taken_By'];
    $Death_Certificate = $_POST['Death_Certificate'];
    $Kin_Out = $_POST['Kin_Out'];
    $Kin_Out_Phone = $_POST['Kin_Out_Phone'];
    $Vehicle_No_Out = $_POST['Vehicle_No_Out'];
    $City_Staff = $_POST['City_Staff'];
    $Discharged_By = $_SESSION['userinfo']['Employee_ID'];
    $Admission_Status = 'Discharged';
    $Discharge_Claim_Form_Number = $_POST['Discharge_Claim_Form_Number'];
    $Discharge_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Discharge_Supervisor_ID = $_SESSION['Admission_Supervisor']['Employee_ID'];
    $Discharge_Date_Time = '';
    //$Discharge_Reason_ID = $_POST['Discharge_Reason_ID'];
    $Bed_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Bed_ID FROM tbl_admission WHERE Admision_ID = $Admision_ID"))['Bed_ID'];

    $update_query = "UPDATE tbl_admission SET Discharge_Employee_ID='$Discharge_Employee_ID',
    Discharge_Supervisor_ID='$Discharge_Supervisor_ID',Discharge_Date_Time=(SELECT NOW()),
    Discharge_Claim_Form_Number='$Discharge_Claim_Form_Number',
    Admission_Status='$Admission_Status' WHERE Admision_ID = $Admision_ID";
    if (mysqli_query($conn,$update_query)) {
		$mortuary_update_query =mysqli_query($conn,"UPDATE tbl_mortuary_admission SET Time_Out=(SELECT NOW()),Kin_Out_Address='$Kin_Out_Address',Taken_By='$Taken_By',
								Kin_Out_Relationship='$Kin_Out_Relationship',Death_Certificate='$Death_Certificate', Kin_Out_Phone='$Kin_Out_Phone', 
								Vehicle_No_Out='$Vehicle_No_Out',Kin_Out='$Kin_Out',Discharged_By='$Discharged_By' WHERE Admision_ID = $Admision_ID") or die(mysqli_error($conn));
        $set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];

        if ($set_duplicate_bed_assign == '0') {
            $change_bed_status = "UPDATE tbl_beds SET Status = 'available' WHERE Bed_ID = '$Bed_ID'";
            $bed_available = mysqli_query($conn,$change_bed_status) or die(mysqli_error($conn));
        } else {
           
            $select_bed_status = "SELECT Admision_ID FROM tbl_admission WHERE Bed_ID=$Bed_ID AND Admission_Status != 'Discharged' AND Admision_ID !='$Admision_ID'";
            $qry_check = mysqli_query($conn,$select_bed_status) or die(mysqli_error($conn));
            if (mysqli_num_rows($qry_check) == 0) {
                $change_bed_status = "UPDATE tbl_beds SET Status = 'available' WHERE Bed_ID = '$Bed_ID'";
                $bed_available = mysqli_query($conn,$change_bed_status) or die(mysqli_error($conn));
            }
        }
        //Releasing a Bed
        ?>
        <script>
            alert('PATIENT DISCHARGED SUCCESSFULLY !');
			window.open('mortuary_discharge_report.php?intent=print&Registration_ID=<?php echo $Registration_ID; ?>');
            document.location = "./searchlistofmortuaryadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage";
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
        
<!--//        KUNTAKINTE CODE TO GENERATE QR CODE
//include('../var/www/html/Final_One/files/phpqrcode/qrlib.php');
//    
//    // outputs image directly into browser, as PNG stream
//  
//
//   
//    include('config.php');
//
//    // how to build raw content - QRCode to send SMS
//    
//    $tempDir = EXAMPLE_TMP_SERVERPATH;
//    
//    // here our data
//    $phoneNo = '(049)012-345-678';
//    
//    // we building raw data
//    $codeContents = 'sms:'.$phoneNo;
//    
//    // generating
//    QRcode::png($codeContents, $tempDir.'021.png', QR_ECLEVEL_L, 3);
//   
//    // displaying
//    echo '<img src="'.EXAMPLE_TMP_URLRELPATH.'021.png" />'; 
////    oyiii
//    -->
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
<a href='searchlistofmortuaryadmited.php?section=<?php echo $section; ?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
    DISCHARGE AWAIT&nbsp;&nbsp;<span id='alert_here' style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;<?php if ($patients == 0) echo "display:none;"; ?> '> <?php echo $patients; ?> </span>
</a>
<a href='./searchlistofmortuaryadmited.php?section=<?php echo $section; ?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
    BACK
</a>

    <br>
	
    <fieldset> 
        <legend align="right"><b>DISCHARGE CORPSE</b></legend>
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
                                <td width='13%' style="text-align:right;">Date In</td>
                                <td width='16%' style="text-align:right;"><input type='text' name='Date_In' disabled='disabled' id='Date_In' value='<?php echo $Date_In; ?>'></td> 
                                <td width='13%' style="text-align:right;">Discharge Date And Time</td>
                                <td width='16%'><input type='text' name='Admission_Date_And_Time' id='Admission_Date_And_Time' value='<?php echo date('Y-m-d H:i:s'); ?>' disabled='disabled'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Gender</td><td width='16%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>

                                <td style="text-align:right;">Date of death</td>
                                <td><input type='text' name='Date_Of_Death' id='Date_Of_Death' disabled="disabled" value='<?php echo $Date_Of_Death; ?>'></td>
					<?php
						$date = $row['Admission_Date_Time'];
						$date = strtotime($date);
						$date = strtotime("+7 day", $date);
						//echo date('d/m/Y', strtotime('+7 days'));
						$deadline = date('Y-M-d', strtotime('+7 days'));
						//echo date('M d, Y', $date);
											?>
					<td style="text-align:right;">Mortuary Deadline</td>
                    <td><input type='text' name='Mortuary_Deadline'  disabled='disabled' value='<?php echo $deadline; ?>'></td>
                                <!--td style="text-align:right;">Admission Number</td>
                                <td>
                                    <input type='text' disabled='disabled' value='<?php echo $Admision_ID; ?>'>
                                    <input type='hidden' name='Admision_ID' id='Admision_ID' value='<?php echo $Admision_ID; ?>'>
                                </td-->
                            </tr>
                            <tr>
                                <td style="text-align:right;">Brought By</td>
                                <td><input type='text' name='Corpse_Brought_By' disabled='disabled' id='Corpse_Brought_By' value='<?php echo $Corpse_Brought_By; ?>'></td>
                                <td style="text-align:right;">Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                            <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID; ?>'>
                            <!--td style="text-align:right;">Ward</td>
                            <td>
                                <?php
                                if (isset($_SESSION['userinfo']['Branch_ID']) && $Hospital_Ward_ID != '') {
                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                                    $select_ward = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_ID = $Hospital_Ward_ID") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($select_ward)) {
                                        $Ward_Name = $row['Hospital_Ward_Name'];
                                    }

                                    $select_bed = mysqli_query($conn,"SELECT * FROM tbl_beds WHERE Bed_ID = $Bed_ID") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($select_bed)) {
                                        $Bed_Name = $row['Bed_Name'];
                                    }
                                    ?>
                                    <input type='text' disabled='disabled' value='<?php echo $Ward_Name; ?>'>
                                    <input type='text' disabled='disabled' value='<?php echo $Bed_Name; ?>'>
                                <?php } else { ?>
                                    <input type='text' disabled='disabled' value='Unknown'>
                                <?php } ?>
                            </td-->
                                <td style="text-align:right;">Admission Number</td>
                                <td>
                                    <input type='text' disabled='disabled' value='<?php echo $Admision_ID; ?>'>
                                    <input type='hidden' name='Admision_ID' id='Admision_ID' value='<?php echo $Admision_ID; ?>'>
                </tr>
                <tr>
                    <td style="text-align:right;">Relation</td>
                    <td>
                        <input type='text' name='Corpse_Kin_Relationship' id='Corpse_Kin_Relationship' disabled='disabled' value='<?php echo $Corpse_Kin_Relationship; ?>'>
                    </td>
                    <td style="text-align:right;">Relation Phone Number</td>
                    <td><input type='text' name='Corpse_Kin_Phone' id='Corpse_Kin_Phone' disabled='disabled' value='<?php echo $Corpse_Kin_Phone; ?>'></td>
					<td style="text-align:right;">Discharged By</td>
                    <td><input type='text' name='Discharged_By' id='Discharged_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                    <!--td style="text-align:right;">Received By</td>
					
					<?php 
//						$get_rcvd_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Corpse_Received_By'";
//						$get_rcvd_name = mysqli_query($conn,$get_rcvd_name) OR die(mysqli_error($conn).'admissinInfo');
                                        
                               $Taken_By ="";
                               $Kin_Out_Relationship = "";
                               $Kin_Out = "";
                               $Kin_Out_Phone = "";
                               $Kin_Out_Address = "";
                               $Death_Certificate = "";
                               $Vehicle_No_Out = "";
                       $select_Filtered_Mortuary = "
				SELECT 
					pr.Patient_Name,ad.Discharge_Employee_ID,
					pr.Gender,pr.Registration_ID,ma.Discharged_By,
					ma.Kin_Out_Phone,ma.Corpse_ID, ad.Admission_Date_Time, ma.Time_Out, ma.Taken_By, ma.Kin_Out,
					ma.City_Staff,ma.Vehicle_No_Out,ma.Discharged_By,ma.Kin_Out_Address,ma.Kin_Out_Relationship,
					ma.Mortuary_Admission_ID,Death_Certificate
				FROM 	
					tbl_admission ad,
					tbl_patient_registration pr,
					tbl_mortuary_admission ma
					WHERE 
						ma.Corpse_ID = pr.registration_id AND ma.Admision_ID = ad.Admision_ID AND
                        ma.Corpse_ID ='$Registration_ID' ORDER BY ma.Time_Out DESC LIMIT 1";
                       
                       $result_query= mysqli_query($conn,$select_Filtered_Mortuary);
                       
                       if($row = mysqli_fetch_assoc($result_query)){
                               $Taken_By = $row['Taken_By'];
                               $Kin_Out_Relationship = $row['Kin_Out_Relationship'];
                               $Kin_Out = $row['Kin_Out'];
                               $Kin_Out_Phone = $row['Kin_Out_Phone'];
                               $Kin_Out_Address = $row['Kin_Out_Address'];
                               $Death_Certificate = $row['Death_Certificate'];
                               $Vehicle_No_Out = $row['Vehicle_No_Out'];
                           
                              }
					?>
                    <td><input type='text' disabled='disabled' value='<?php echo $get_rcvd_name; ?>'-->
                    </td>
                </tr>
                <tr>
                    <!--td style="text-align:right;">Relation Phone Number</td>
                    <td>
                        <input type='text' name='Corpse_Kin_Phone' id='Corpse_Kin_Phone' disabled='disabled' value='<?php echo $Corpse_Kin_Phone; ?>'>
                    </td-->
                    <td style="text-align:right;">Registration Number</td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
                        <input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
                    </td>
				<!--
					<td style="text-align:right;">Discharged By</td>
                    <td><input type='text' name='Discharged_By' id='Discharged_By' disabled='disabled' value='<?php // echo $deadline; ?>'></td>
                    -->
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
                <td colspan=2><center><b>DISCHARGE INFO</b></center></td>
            <td colspan=2><center><b>CITY STAFF / NEXT OF KIN NAME AND CONTACT</b></center></td>
            </tr>
            <tr>
                <td width='16%' style="text-align:right;">Vehicle N<u>o</u> :</td>
                <td width='26%'><input type='text' id='Vehicle_No_Out' name='Vehicle_No_Out'></td>

                <td width='13%' style="text-align:right;">Taken By :</td>
				<td width='26%'>
				<select name="Taken_By" id="Taken_By" style='width:100%;padding: 5px' required="required">
					<option value="<?php echo $Taken_By; ?>"><?php echo $Taken_By; ?></option>
					<option value="Relative">RELATIVE </option>
					<option value="CITY STAFF">CITY STAFF</option>
				</select>
				</td>
            </tr>
            <tr>
                    <td style="text-align:right;">Death Certificate N<u>o</u></td>
                    <td>
                        <input type='text' name='Death_Certificate' id="Death_Certificate"  value="<?php echo $Death_Certificate; ?>">
                    </td>

            <td style="text-align:right;">Relationship :</td>
            <td><input type='text' id='Kin_Out_Relationship' name='Kin_Out_Relationship' value="<?php echo $Kin_Out_Relationship; ?>"></td>
            </tr>
            <tr>
                   <td></td> 
				   <td></td>

                <td style="text-align:right;">Name:</td>
				<td><input type='text' id='Kin_Out' name='Kin_Out' value="<?php echo $Kin_Out; ?>"></td>
            </tr>
			
            <tr> 
					<td></td>
					<td></td>
                     <td style="text-align:right;">Phone</td>
                    <td>
                        <input type='text' id="Kin_Out_Phone" name='Kin_Out_Phone' value="<?php echo $Kin_Out_Phone; ?>">
                    </td>
            </tr> 
			<tr> 
					<td></td>
					<td></td>
                     <td style="text-align:right;">Address</td>
                    <td>
                        <input type='text' name='Kin_Out_Address' id="Kin_Out_Address" value="<?php echo $Kin_Out_Address; ?>">
                    </td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <table width='100%'>
                <td colspan=2 style="text-align:center;">
                    <button type='submit'  class='art-button-green' onclick="update_discharge_mortuary()">DISCHARGE</button>
                    <!--<input type='reset' value='CLEAR' class='art-button-green' >-->
                    <!--<input type='hidden' id='send_admition_form' name='send_admition_form'>-->
                </td>
            </tr>
        </table>
    </fieldset>

<?php
include("./includes/footer.php");
?>

 <script>
     function update_discharge_mortuary(){
           var Taken_By = $('#Taken_By').val();
           var Death_Certificate = $('#Death_Certificate').val();
           var Kin_Out_Relationship = $('#Kin_Out_Relationship').val();
           var Kin_Out = $('#Kin_Out').val();
           var Kin_Out_Phone = $('#Kin_Out_Phone').val();
           var Kin_Out_Address = $('#Kin_Out_Address').val();
           var Registration_ID = '<?= $Registration_ID  ?>';
         
          $.ajax({
                type: 'POST',
                url: 'Ajax_update_mortuary_discharge.php',
                data: {Taken_By:Taken_By,Death_Certificate:Death_Certificate,Kin_Out_Relationship:Kin_Out_Relationship,Kin_Out:Kin_Out,Kin_Out_Phone:Kin_Out_Phone,Kin_Out_Address:Kin_Out_Address,Registration_ID:Registration_ID},
                cache: false,
                success: function (html) {
                    
                    alert(html);
                    window.open('mortuary_discharge_report.php?intent=print&Registration_ID=<?php echo $Registration_ID; ?>');
                
                }
            });

        }
    </script>