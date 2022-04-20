<?php
include("./includes/header.php");
include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    session_start();
?>
<a href="list_of_patient_cheked_in_n_from_inpatient.php" class="art-button-green">BACK</a>
<?php
if(isset($_GET['Ward_suggested']) && $_GET['Ward_suggested']!=0){
    
    $Ward_suggested= $_GET['Ward_suggested'];
}  else {
    $Ward_suggested=0;  
}

$set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
$bedStat="";      
if ($set_duplicate_bed_assign == '0') {
    $bedStat=" AND Status = 'available'";
}

if(isset($_GET['fromDoctorPage'])){
 $fromDoctorPage=$_GET['fromDoctorPage'];     
}else{
 $fromDoctorPage='';   
}



$set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
$Can_admit_before_discharge = $_SESSION['systeminfo']['Can_admit_before_discharge'];

$section = '';
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    
}

if (!isset($_GET['Check_In_ID'])) {
 $select_checkin = "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '" . $_GET['Registration_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
            //echo $select_checkin;exit;
            $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
            $checkin = mysqli_fetch_assoc($select_checkin_qry);
            $_GET['Check_In_ID'] = $checkin['Check_In_ID'];
}

$Admission_Number = '';
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

if (isset($_GET['Registration_ID'])) {
  $Registration_ID = $_GET['Registration_ID'];  
}  else {
   $Registration_ID =''; 
}


// die("SELECT Admission_Status FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted'");
$check_admitted=mysqli_query($conn,"SELECT Admission_Status FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted'");
$num_rows=mysqli_num_rows($check_admitted);


//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,pr.Patient_Name,Title,pr.Sponsor_ID,
                                Date_Of_Birth,Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
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
            $Sponsor_ID = $row['Sponsor_ID'];
            $Patient_Name = $row['Patient_Name'];
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
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Second_Name = '';
        $Last_Name = '';
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
    $First_Name = '';
    $Sponsor_ID = '';
    $Second_Name = '';
    $Last_Name = '';
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
//Calculate age
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $years=$diff->y;
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";

?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    button {
        height:28px!important;
        color:#FFFFFF!important;
    }
</style> 
<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php

if (isset($_GET['Registration_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key
    $sql_Select_Current_Patient = mysqli_query($conn,"SELECT pp.Patient_Payment_ID,pp.Claim_Form_Number, ppl.Patient_Direction,
					pp.Folio_Number, PP.Payment_Date_And_Time, ppl.Check_In_Type, ppl.Consultant,
					    pp.Billing_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    $Patient_Direction = $row['Patient_Direction'];
    $Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    $Patient_Direction = '';
    $Consultant = '';
}

// exit();

if (empty($Folio_Number)) {

    $q = "SELECT Folio_Number,Claim_Form_Number FROM tbl_check_in_details cd WHERE cd.Registration_ID='" . $_GET['Registration_ID'] . "' AND cd.Check_In_ID='" . $_GET['Check_In_ID'] . "'";
    $query_data = mysqli_query($conn,$q) or die(mysqli_error($conn));

    if (mysqli_num_rows($query_data) > 0) {
        $query_data = mysqli_query($conn,$q) or die(mysqli_error($conn));

        $rowda = mysqli_fetch_array($query_data);
        $Folio_Number = $rowda['Folio_Number'];
        $Claim_Form_Number = $rowda['Claim_Form_Number'];
    }
}

?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}

$folion

?>





<!--========================= Mortuary INSERT=================================-->
<?php
if (isset($_POST['mortuary_send_admition_form'])) {
    $Registration_ID = $_POST['Registration_ID'];
    $Hospital_Ward_ID = $_POST['Hospital_Ward_ID'];
    $ward_room_id = $_POST['ward_room'];
    if (isset($_POST['room_bed'])) {
        $Bed_Name = $_POST['room_bed'];
    } else {
        $Bed_Name = 0;
    }
    $Admission_Employee_ID = $_POST['Admission_Employee_ID'];
    if (isset($_POST['Admission_Supervisor_ID'])) {
        $Admission_Supervisor_ID = $_POST['Admission_Supervisor_ID'];
    } else {
        $Admission_Supervisor_ID = 0;
    }
    //KUNTA YONGO code to add admimitted from post   and add inalala_bilakulala
    $inalala_bilakulala='inalala_bilakulala';
    $admitted_from='admitted_from';
    $admitted_from=$_POST['admitted_from'];
    $inalala_bilakulala=$_POST['inalala_bilakulala'];
    if(isset($_POST['inalala_bilakulala'])){
        $inalala_bilakulala=$_POST['inalala_bilakulala'];
    }
 else {
       $inalala_bilakulala=0; 
    }
    if (isset($_POST['admitted_from'])){
        $admitted_from=$_POST['admitted_from'];
              
    }
    else {
        $admitted_from=0;
    }
    

    //echo "<h1>;;;$Admission_Supervisor_ID</h1>";
    $Admission_Status = 'Admitted';
    $Admission_Claim_Form_Number = $_POST['Admission_Claim_Form_Number'];
    //$Folio_Number = $_POST['Folio_Number'];
    $Corpse_Brought_By = str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $_POST['Corpse_Brought_By']));
    $Nurse_On_Duty = $_POST['Nurse_On_Duty'];
    $Date_Of_Death = $_POST['Date_Of_Death'];
    $Corpse_Kin_Relationship = $_POST['Corpse_Kin_Relationship'];
    $Corpse_Received_By = $_POST['Corpse_Received_By'];
    $Vehicle_No_In = $_POST['Vehicle_No_In'];
    $Corpse_Kin_Phone = $_POST['Corpse_Kin_Phone'];
    $Other_Details = $_POST['Other_Details'];
    $Case_Type = $_POST['Case_Type'];
    $admitted_from=$_POST['admitted_from'];
    $inalala_bilakulala=$_POST['inalala_bilakulala'];
    $Place_Of_Death = $_POST['Place_Of_Death'];
    $Corpse_Kin_Address = $_POST['Corpse_Kin_Address'];
    $Police_No = $_POST['Police_No'];
    $Police_Title = $_POST['Police_Title'];
    $Police_Place  = $_POST['Police_Place'];
    $Police_Phone  = $_POST['Police_Phone'];
    $Postmortem_Done_By  = $_POST['Postmortem_Done_By'];
    $Postmortem_No  = $_POST['Postmortem_No'];
    $Police_Station  = $_POST['Police_Station'];
    $Police_Name  = str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $_POST['Police_Name']));
    $Police_Postal_Box  = $_POST['Police_Postal_Box'];
    $Admission_Reason_ID = $_POST['Admission_Reason_ID'];
    $Death_Certificate = $_POST['Death_Certificate'];
    $new_sponsor_id = $_POST['new_sponsor_id'];
    $name_of_doctor_confirm_death = mysqli_real_escape_string($conn,str_replace("'", "&#39;", $_POST['name_of_doctor_confirm_death']));
    $body_condition = $_POST['body_condition'];
    $course_of_death = $_POST['course_of_death'];
	$corpse_properties = $_POST['corpse_properties'];


	
    $sql_update_sponsor_id="UPDATE tbl_patient_registration SET Sponsor_ID='$new_sponsor_id' WHERE Registration_ID='$Registration_ID'";
    $sql_update_sponsor_id_result=mysqli_query($conn,$sql_update_sponsor_id) or die(mysqli_error($conn));
    if($sql_update_sponsor_id_result){
        echo "sponsor changed";
    }
    if($admitted_from == ''){
        echo "<script>
        alert('Please Specify Where the Corpse Came from');
        document.location = './motuary_admission.php?Registration_ID=$Registration_ID';
        $('#admitted_from').focus();
        document.getElementById('admittted_from').style.border = '1px solid red';
        </script>";

    }else{
        $insert_query = "INSERT INTO tbl_admission(Registration_ID,Hospital_Ward_ID,Bed_Name,
        Admission_Employee_ID,Admission_Supervisor_ID,
        Admission_Date_Time,Admission_Status,District,
        Ward,Admission_Claim_Form_Number,Folio_Number,
        Office_Area,Office_Plot_Number,Office_Street,Office_Phone,
        Kin_Name,Kin_Relationship,Kin_Phone,Kin_Area,Kin_Street,Kin_Plot_Number,Kin_Address,Admission_Reason_ID,ward_room_id)
        
        VALUE('$Registration_ID','$Hospital_Ward_ID','$Bed_Name','$Admission_Employee_ID','$Admission_Supervisor_ID',
        (SELECT NOW()),'$Admission_Status','$District',
        '$Ward','$Admission_Claim_Form_Number','$Folio_Number',
        '$Office_Area','$Office_Plot_Number','$Office_Street','$Office_Phone',
        '$Kin_Name','$Kin_Relationship','$Kin_Phone','$Kin_Area','$Kin_Street','$Kin_Plot_Number','$Kin_Address','$Admission_Reason_ID','$ward_room_id')";
    }

	if (mysqli_query($conn,$insert_query)) {

        $My_Admision_ID = mysqli_insert_id($conn);
	
    $mortuary_insert_query = "INSERT INTO tbl_mortuary_admission(
        name_of_doctor_confirm_death,body_condition,course_of_death,
	Corpse_ID,
		    case_type,Place_Of_Death,Nurse_On_Duty,Date_Of_Death,Corpse_Brought_By,Corpse_Received_By,
			Corpse_Kin_Relationship,Corpse_Kin_Phone,Corpse_Kin_Address,Vehicle_No_In,Other_Details,
			Police_No,Police_Title,Police_Place,Police_Phone,Postmortem_Done_By,Postmortem_No,
			Police_Station,Police_Name,Police_Postal_Box,Admision_ID,Death_Certificate,admitted_from,inalala_bilakulala,corpse_properties)
		    VALUES (
                        '$name_of_doctor_confirm_death','$body_condition','$course_of_death',
			'$Registration_ID',
			'$Case_Type',
			'$Place_Of_Death', 
			'$Nurse_On_Duty',
			'$Date_Of_Death',
			'$Corpse_Brought_By',
			'$Corpse_Received_By',
                        '$Corpse_Kin_Relationship',
			'$Corpse_Kin_Phone',
			'$Corpse_Kin_Address',
			'$Vehicle_No_In',
			'$Other_Details',
			'$Police_No',
			'$Police_Title',
			'$Police_Place',
			'$Police_Phone',
			'$Postmortem_Done_By',
			'$Postmortem_No',
			'$Police_Station',
			'$Police_Name',
			'$Police_Postal_Box','$My_Admision_ID','$Death_Certificate','$admitted_from','$inalala_bilakulala','$corpse_properties')";
    
mysqli_query($conn,$mortuary_insert_query) or die(mysqli_error($conn)."hhhhhh");
//echo $Police_Postal_Box."box ".$Police_Station." st".$Police_Name."n ".$Postmortem_No." #".$Police_Place." p ".$Police_Title." t ".$Police_No." ggg".$Police_Phone." ggg"; exit;
// if (!isset($_GET['Check_In_ID'])) {
//  $select_checkin = "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '" . $_GET['Registration_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
//             //echo $select_checkin;exit;
//             $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
//             $checkin = mysqli_fetch_assoc($select_checkin_qry);
//             $_GET['Check_In_ID'] = $checkin['Check_In_ID'];
// }

//echo $_GET['Check_In_ID'];

        //Return check in details

        // $lastCheck_In_Details_ID = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details
		// 	WHERE Registration_ID = '$Registration_ID' AND Check_In_ID=" . $_GET['Check_In_ID'] . " ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));

        // $Check_In_Details_ID = mysqli_fetch_assoc($lastCheck_In_Details_ID)['Check_In_Details_ID'];
        
//        $Check_In_ID = $_GET['Check_In_ID'];
        $New_Check_IN = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID,Employee_ID,Visit_Date,Check_In_Date_And_Time) VALUES('$Registration_ID','$Admission_Employee_ID',NOW(),(select now()))") or die(mysqli_error($conn));
        
        if($New_Check_IN){
            $select_chkin_id=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_chkin_id)>0){
              $Check_In_ID = mysqli_fetch_assoc($select_chkin_id)['Check_In_ID']; 
             
                 $Inseer_Checi_IN_Details = mysqli_query($conn,"INSERT INTO tbl_check_in_details(Registration_ID,Check_In_ID,Sponsor_ID,ToBe_Admitted,Admission_ID,Employee_ID) VALUES('$Registration_ID','$Check_In_ID','$new_sponsor_id','yes','$My_Admision_ID','$Admission_Employee_ID')") or die(mysqli_error($conn));
            }
        }
        //update check in details Admision_ID
       // mysqli_query($conn,"UPDATE tbl_check_in_details SET Admission_ID='$Admision_ID' WHERE Check_In_Details_ID='$Check_In_Details_ID'") or die(mysqli_error($conn));
        if($Inseer_Checi_IN_Details){

            $change_admit_status = mysqli_query($conn, "UPDATE tbl_check_in_details SET Admit_Status = 'admitted', Admission_ID = '$My_Admision_ID' WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'");
            // $status_changed = $change_admit_status);
            $admited = true;
        }

        $select_admission_ID = "SELECT Admision_ID FROM tbl_admission
	     WHERE Registration_ID = $Registration_ID AND Admission_Status = '$Admission_Status'";
        if ($result = mysqli_query($conn,$select_admission_ID)) {
            $row = mysqli_fetch_assoc($result);
            //$row=last_inserted_id()
            $Query= mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_Details_ID DESC LIMIT 1");
            $result= mysqli_fetch_assoc($Query);
            $consultation_ID=$result['consultation_ID'];
            
            $Query1= mysqli_query($conn,"SELECT itc.Payment_Cache_ID FROM tbl_item_list_cache itc JOIN tbl_payment_cache tpc ON itc.Payment_Cache_ID=tpc.Payment_Cache_ID WHERE tpc.consultation_id='$consultation_ID' AND itc.Status='active'");
            $number=  mysqli_num_rows($Query1);
            if($number>0){
                while($rp=mysqli_fetch_assoc($Query1)){
                       $update=  mysqli_query($conn,"UPDATE tbl_payment_cache SET Billing_Type='Inpatient Credit' WHERE consultation_id='$consultation_ID' AND Payment_Cache_ID='".$rp['Payment_Cache_ID']."'") or die(mysqli_error($conn));
                }
             }
            // exit();
            ?>
            <script>
                alert('PATIENT ADMITED SUCCESSFULLY <?php //,\n ADMISSION NUMBER echo $row['Admision_ID']; ?> !');
				window.open('individual_admission_report.php?mortuary=print&Registration_ID=<?= $Registration_ID; ?>');
                var fromDoctorPage='<?= $fromDoctorPage;?>';
                if(fromDoctorPage==='fromDoctorPage'){
                            //document.location = "./doctorspageoutpatientwork.php";
                }else{
                 //document.location = "./searchlistofoutpatientadmission.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage";
               }
           </script>
            <?php
        }
    } else {
        die(mysqli_error($conn));
        ?>
        <script>
            alert('PATIENT NOT ADMITTED TRY AGAIN !');
        </script>
        <?php
    }
}
// exit();
?>
<!--====================== /Mortuary INSERT=================================-->


<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>


<input type='hidden' id='alert_control' value=''/>
<?php
//Count Patients to be Admitted
$count_patients = "SELECT COUNT(Check_In_Details_ID) as patients FROM tbl_check_in_details WHERE ToBe_Admitted = 'yes' AND Admit_Status = 'not admitted'";
$counted_patients = mysqli_query($conn,$count_patients) or die(mysqli_error($conn));
while ($patientscount = mysqli_fetch_assoc($counted_patients)) {
    $patients = $patientscount['patients'];
}

// exit();
?>
			<!-- ==================MORTUARY ===========================================================-->
	<div  id="morgue_form">
		<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

			<fieldset>
				<legend align='right'><b>ADMIT DECEASED PATIENT</b></legend>
				<!--<br/>-->

				<center> 
                                    <table width=100%> 
						<tr> 
							<td>
								<table width=100%>
									<tr>
										<td width='16%'style='text-align:right;'>Deceased Name</td>
										<td width='26%'style='text-align:right;'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php if (isset($Patient_Name)) echo ucwords(strtolower($Patient_Name)); ?>'></td>
										<td width='13%'style='text-align:right;'>Card Id Expire Date</td>
										<td width='16%'style='text-align:right;'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?= $Member_Card_Expire_Date; ?>'></td> 
										<td width='13%'style='text-align:right;'>Admission Date And Time</td>
										<td width='16%'style='text-align:right;'><input type='text' name='Admission_Date_And_Time' id='Admission_Date_And_Time' value='<?= date('Y-m-d H:i:s'); ?>' disabled='disabled'></td>
									</tr> 
									<tr>
										<td style='text-align:right;'>Gender</td><td width='16%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?= $Gender; ?>'></td>

										<td style='text-align:right;'>Claim Form Number</td>
										<td><input type='text' name='Admission_Claim_Form_Number' id='Admission_Claim_Form_Number'></td>
										<td style='text-align:right;'>Admission Number</td>
										<td>
											<input type='text' disabled='disabled' value='<?= $Admission_Number; ?>'>
											<input type='hidden' name='Admission_Number' id='Admission_Number' value='<?= $Admission_Number; ?>'>
										</td>
									</tr>
									<tr>
										<td style='text-align:right;'>Sponsor Name</td>
										<td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?= $Guarantor_Name; ?>'></td>
										<td style='text-align:right;'>Patient Age</td>
										<td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?= $age; ?>'></td>
									<input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?= $Employee_ID; ?>'>
                                    <td style='text-align:right;'><b style="color:red;font-size:14px">Select Mortuary</b></td>
							<td id='Ward_Area_'>

								<select class="form-control" name='Hospital_Ward_ID' id='Hospital_Ward_ID' onchange="ward_nature(this.value)" >
									<option></option>
									 <?php
									if($Ward_suggested!=0){
										$Select_Department = mysqli_query($conn,"SELECT Hospital_Ward_Name, Hospital_Ward_ID FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Ward_suggested' AND ward_type='mortuary_ward'");
										while ($row = mysqli_fetch_array($Select_Department)) {
										$Ward_Name = $row['Hospital_Ward_Name'];
										$Hospital_Ward_ID = $row['Hospital_Ward_ID'];
                                        $Number_of_Beds = $row['Number_of_Beds'];

                                        echo "<option selected='selected' value='".$Hospital_Ward_ID."'>".$Ward_Name."</option>";

						                        }
									}else{
									  $Select_Department = mysqli_query($conn,"SELECT Hospital_Ward_Name, Hospital_Ward_ID  FROM tbl_hospital_ward WHERE ward_type='mortuary_ward'"); 
                                        
                                        while ($row = mysqli_fetch_assoc($Select_Department)) {
                                            $Ward_Name = $row['Hospital_Ward_Name'];
                                            $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
                                            $Number_of_Beds = $row['Number_of_Beds'];
                                            echo "<option value='".$Hospital_Ward_ID."'>".$Ward_Name."</option>";
                                        }
                                    }

                                    // exit();
									?>
								</select>

								<span id='bedNumber'>

								</span>
							</td>
						</tr>
                        <tr>
                            <td style='text-align:right;' colspan="5"><b style="color:red;font-size:14px">Block</b></td>
                            <td>
                                <select name="ward_room"  id="ward_room" onchange="get_ward_room_bed(this.value)">
                                    <option selected='selected'></option>
                                    <?php 
                                    // $sql_select_ward_rooms=mysqli_query($conn,"SELECT ward_room_id, room_name FROM tbl_ward_rooms WHERE ward_id='$Ward_suggested'") or die(mysqli_error($conn));
                                    // if(mysqli_num_rows($sql_select_ward_rooms)>0){
                                    //     while($room_rows=mysqli_fetch_assoc($sql_select_ward_rooms)){
                                    //         $ward_room_id=$room_rows['ward_room_id'];
                                    //         $room_name=$room_rows['room_name'];
                                    //         echo "<option value='$room_name'>$room_name</option>";
                                    //     }
                                    // }
                                    ?>
                                </select>
                            </td>
                        </tr>
        <tr>
                                    <td style='text-align:right;' colspan="5"><b style="color:red;font-size:14px">Select Cabinet</b></td>
                                    <td>
                                        <select name="room_bed" id="room_bed" onchange="checkPatientNumber(this.value)">
                                                 <option selected='selected'></option>

                                        </select>
                                    </td>
                                </tr>
						<tr> 
							<td style='text-align:right;'>Previous Number</td>
							<td>
								<input type='text' name='Old_Registration_Number' id='Old_Registration_Number' disabled='disabled' value='<?= $Old_Registration_Number; ?>'>
							</td>
							<td style='text-align:right;'>Phone Number</td>
							<td><input type='text' name='Phone_Number1' id='Phone_Number1' disabled='disabled' value='<?= $Phone_Number; ?>'></td>
							<td style='text-align:right;'>Folio Number</td>
							<td><input type='text' disabled='disabled' value='<?= $Folio_Number; ?>'>
								<input type='hidden' name='Folio_Number' id='Folio_Number' value='<?= $Folio_Number; ?>'>
							</td>
						</tr>
						<tr>
							<td style='text-align:right;'>Region</td>
							<td>
								<input type='text' name='Region' id='Region' disabled='disabled' value='<?= $Region; ?>'>
							</td>
							<td style='text-align:right;'>Registration Number</td>
							<td><input type='text' disabled='disabled' value='<?= $Registration_ID; ?>'>
								<input type='hidden' name='Registration_ID' id='Registration_ID'value='<?= $Registration_ID; ?>'>
							</td>
							<td style='text-align:right;'>Prepared By</td>
							<td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?= $Employee_Name; ?>'></td>
						</tr>
						<tr>
							<td style='text-align:right;'>District</td>
							<td>
								<input type='text'disabled='disabled' value='<?= $District; ?>'>
								<input type='hidden' name='District' id='District' value='<?= $District; ?>'>
								<input type='hidden' name='Ward' id='Ward' value='<?= $Ward; ?>'>
							</td>
							<td style='text-align:right;'>Member Number</td>
							<td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?= $Member_Number; ?>'></td> 
							<td style='text-align:right;'>Supervised By</td>
							<?php
		//                                    echo '<pre>';
		//                                    print_r($_SESSION);
		//                                    exit;
							if (isset($_SESSION['Admission_Supervisor'])) {
								if (isset($_SESSION['Admission_Supervisor']['Session_Master_Priveleges'])) {
									if ($_SESSION['Admission_Supervisor']['Session_Master_Priveleges'] = 'yes') {
										$Supervisor = $_SESSION['Admission_Supervisor']['Employee_Name'];
										?>
									<!--<input type='hidden' id='Admission_Supervisor_ID' name='Admission_Supervisor_ID' value='<?= $_SESSION['Admission_Supervisor']['Employee_ID']; ?>'>-->
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
                                                 <input type='text' hidden="hidden" id='Admission_Supervisor_ID' name='Admission_Supervisor_ID' value='<?= $_SESSION['userinfo']['Employee_ID']; ?>'>
                                                 
						<td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?= $Supervisor; ?>'></td>
						</tr> 
						 <tr>
						<td width='16%' style="text-align:right;">Admission Reason :</td><td>
							<select id='Admission_Reason_ID' name='Admission_Reason_ID' style='width:100%;padding: 5px'>
									<option value='3'>Death</option>    
									
							</select>
						</td>
                                                <td style="text-align:right;">
                                                    <b style="color:red">New Sponsor</b>
                                                </td>
                                                <td>
                                                    <select id="new_sponsor" required="" name="new_sponsor_id">
                                                        <?php 
														echo "<option></option>";
                                                            $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                                                            if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                                                while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                                                   $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                                                   $Guarantor_Name=$sponsor_rows['Guarantor_Name'];
                                                                   echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>"; 
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
					</tr>
					</table>
					</td> 
					</tr>
					</table>
				</center>
			</fieldset>
                            <script>
                                    $(document).ready(function (){
                                       $("#new_sponsor").select2(); 
                                    });
                            </script>
                            <?php 
                            
                                ///FROM LIST OF CHECKED IN DECESED PATIENT...SELECT DECEASED DETAILS/KUNTACODE FROM SELECTED OUTPATIENT
                                $sql_select_decesed_informatio_result=mysqli_query($conn,"SELECT relative_name, relationship_type, relative_phone_number, relative_Address, death_date, deceased_reasons, doctor_confirm_death, place_of_death, dead_after_before FROM tbl_diceased_patients dp, tbl_deceased_reasons dr WHERE Patient_ID='$Registration_ID' AND dp.death_reason=dr.deceased_reasons_ID") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_decesed_informatio_result)>0){
                                    while($dsp_rows=mysqli_fetch_assoc($sql_select_decesed_informatio_result)){
                                        $relative_name=$dsp_rows['relative_name'];
                                        $relationship_type=$dsp_rows['relationship_type'];
                                        $relative_phone_number=$dsp_rows['relative_phone_number'];
                                        $relative_Address=$dsp_rows['relative_Address'];
                                        $death_date=$dsp_rows['death_date'];
                                        $deceased_reasons=$dsp_rows['deceased_reasons'];
                                        $Docto_confirm_death_name=$dsp_rows['doctor_confirm_death'];
                                        $place_of_death=$dsp_rows['place_of_death'];
                                        $dead_after_before=$dsp_rows['dead_after_before'];
//                                        die("---------------------------");
                                       $case_type='outside_from_hospital';
                                    }
                                }else{
                                    //from ward
                                    $select_desesed_from_ward_info_result=mysqli_query($conn,"SELECT Kin_Name, Kin_Relationship, Kin_Phone, Kin_Address, course_of_death, Docto_confirm_death_name, death_date_time, Hospital_Ward_Name, pending_setter FROM tbl_admission ad INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID WHERE ad.Registration_ID='$Registration_ID' AND discharge_condition='dead'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($select_desesed_from_ward_info_result)>0){
                                        while($disease_from_ward_rows=mysqli_fetch_assoc($select_desesed_from_ward_info_result)){
                                              $relative_name=str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $disease_from_ward_rows['Kin_Name']));
                                              $relationship_type=$disease_from_ward_rows['Kin_Relationship'];
                                              $relative_phone_number=$disease_from_ward_rows['Kin_Phone'];
                                              $relative_Address=$disease_from_ward_rows['Kin_Address'];
                                              $deceased_reasons=$disease_from_ward_rows['course_of_death'];
                                              $Docto_confirm_death_name=str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $disease_from_ward_rows['Docto_confirm_death_name']));
                                              $death_date=$disease_from_ward_rows['death_date_time'];  
                                              $place_of_death=$disease_from_ward_rows['Hospital_Ward_Name'];  
                                              $Nurse_On_Dutying=$disease_from_ward_rows['pending_setter'];  
                                              $case_type='hospital';
                                        }
                                        
                                    }

                                }
                                  
                               
                                   $select_pf3_taarifa=mysqli_query($conn,"SELECT pf3_ID, Registration_ID, Police_Station, Check_In_ID, pf3_attachment_file, pf3_Date_And_Time, pf3_Description  FROM tbl_pf3_patients  WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                   if (mysqli_num_rows($select_pf3_taarifa)>0){
                                       while ($from_pf3_rows=mysqli_fetch_assoc($select_pf3_taarifa)){
                                           $pf3_ID=$from_pf3_rows['pf3_ID'];
                                           $Registration_ID=$from_pf3_rows['Registration_ID'];
                                           $Police_Station=$from_pf3_rows['Police_Station'];
                                           $Check_In_ID=$from_pf3_rows['Check_In_ID'];
                                           $pf3_attachment_file=$from_pf3_rows['pf3_attachment_file'];
                                           $pf3_Date_And_Time=$from_pf3_rows['pf3_Date_And_Time'];
                                           $pf3_Description=$from_pf3_rows['pf3_Description'];
                                           $Police_Station=$from_pf3_rows[''];
                                           $Relative=$from_pf3_rows[''];
                                           $Phone_No_Relative=$from_pf3_rows[''];
                                           $Phone_No_Relative=$from_pf3_rows[''];
                                           $Reason_ID=$from_pf3_rows[''];
                                           $case_type='police';

                                             
                                           
                                       }
                                   }
                                   
                               $select_if_pf3_yes=mysqli_query($conn,"SELECT pf3, Check_In_ID FROM tbl_check_in WHERE Check_In_ID='$Check_In_ID' AND pf3='yes'") or die(mysqli_error($conn));
                               if (mysqli_num_rows($select_if_pf3_yes)>0){
                                   
                                   while($pf3data_rows=mysqli_fetch_assoc($select_if_pf3_yes)){
                                       $pf3=$pf3data_rows['pf3'];
                                      $Check_In_ID=$pf3data_rows['Check_In_ID'];
                                      $pf3='yes';
                                   }
                               }
                               
//                               print_r($pf3data_rows);
//                              die("oyoooooo---$pf3");

                               if(!empty($place_of_death)){
                                   $Stylessss = "readonly='readonly'";
                               }else{
                                   $Stylessss = '';
                               }
                            ?>
			<fieldset>
				<table  width='100%'>
					<tr>
						<td colspan=2><center><b>DETAILS</b></center></td>
					<td id='home_case'  colspan="2" ><center><b>NEXT OF KIN NAME AND CONTACT</b></center></td>
					<td id='police_case'  colspan="4"><center><b>POLICE INFORMATION (FOR POLICE CASE ONLY)</b></center></td>
					</tr>
					<tr>
                                            <td width='13%' style='text-align:right;'>Admitted From /Case :</td>
						<td width='16%'>
							<select name='admitted_from' id='admitted_from' style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<option <?php if($case_type!="hospital"){echo "selected='selected'";}?>></option>
                                                                <option value="from_ward"<?php if($case_type=="hospital"){echo "selected='selected'";}?>>INPATIENT</option>
								<option value="from_outside_traffic">OUTSIDE-TRAFFIC CASE</option>
                                                                <option value="from_outside_police" >OUTSIDE-POLICE CASE</option>
                                                                <option value="from_outside_home" >OUTSIDE-HOME CASE</option>
                                                                <option value="from_foreigners" >FOREIGNERS CASE</option>
																 <option value="from_foreigners_home" >FOREIGNERS-HOME CASE</option>

								
							</select> 
						</td>
                                                
						<td width='13%'style='text-align:right;'>Date Of Death :</td>
						<td>
							<input type="text"  name="Date_Of_Death" id="Date_Of_Death" value="<?= $death_date; ?>" style="text-align: center;" placeholder = "~~~ ~~~ Date Of Death ~~~ ~~~" required>
						</td>
						
						<td id='Police_Name1' style='text-align:right;'>Police Name</td>
						<td width='16%' ><input type='text'  name='Police_Name' id='Police_Name'></td>
					</tr>
					<tr>
						<td width='13%' style='text-align:right;'>Case Type :</td>
						<td width='16%'>
							<select name='Case_Type' id='Case_Type'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
                                                                
								<option value="">---SELECT CASE---</option>
                                                                <option value="police"<?php if($pf3=="yes"){echo "selected='selected'";}?>>POLICE CASE</option>
                                                                <option value="hospital"<?php if($case_type=="hospital"){echo "selected='selected'";}?>>HOSPITAL CASE</option>
								<option value="home" <?php if($case_type=="outside_from_hospital"){echo "selected='selected'";}?>>Dead before Arrive </option>
                                                                <option value="hospital">Dead after Arrive </option>
                                                               
                                                                
                                                                
							</select> 
						</td>
                                                <td   id='Corpse_Brought_By1' style='text-align:right;'>Brought By :</td>
                                                <td width='16%' ><input type='text'  name='Corpse_Brought_By' value="<?= $relative_name ?>" id='Corpse_Brought_By' readonly='readonly'></td>
						
						<td id='Police_Title1' style='text-align:right;'>Title :</td>
						<td width='16%'   ><input type='text'  name='Police_Title' id='Police_Title' ></td>

					
					</tr>
					<tr>
                                            <td id='Corpse_Kin_Relationship1' style='text-align:right;'>Relationship :</td>
                                                <td width='16%'   ><input type='text'  name='Corpse_Kin_Relationship' value="<?= $relationship_type ?>" id='Corpse_Kin_Relationship' ></td>
					<!--span id="Nurse_On_Duty" style="display:none"-->
						<td style='text-align:right;'>Place Of Death</u> :</td>
                                                <td width='16%'><input type='text' id='Place_Of_Death' value="<?= $place_of_death ?>"name='Place_Of_Death' <?= $Stylessss; ?>></td>
					<!--/span-->
						
						<td id='Police_Station1' style='text-align:right;'>Police Station :</td>
						<td width='16%' ><input type='text'  name='Police_Station' id='Police_Station' ></td>
						
					</tr>
					<tr>

                    
                                                <td width='13%' style='text-align:right;'>OUT BEFORE OR AFTER 24HRS?:</td>
						<td width='16%'>
							<select name='inalala_bilakulala' id='inalala_bilakulala'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<!--<option value=""></option>-->
                                                                <option value="inalala"<?php if($case_type=="hospital"){echo "selected='selected'";}?>>Maiti inayolala</option>
								<option value="bilakulala">Maiti inayochukuliwa billakulala</option>
								
							</select> 
						</td>
						<td style='text-align:right;'>Vehicle N<u>o</u> :</td>
						<td width='16%'><input type='text' id='Vehicle_No_In' name='Vehicle_No_In'></td>
						</td>
						
						
						<td id='Police_No1' style='text-align:right;' >Police N<u>o</u> :</td>
						<td width='16%'  ><input type='text'  name='Police_No' id='Police_No' ></td>
					</tr>
					<tr>
						<td width='13%' id='Nurse_On_Duty1' style='text-align:right; '>Nurse On Duty</td>
						<td width='16%' >
							<select   name='Nurse_On_Duty'id='Nurse_On_Duty' onchange="filterNurse()"  style='width:100%;padding: 5px;text-align: center;'>
                            <?php
                                if(!empty($Nurse_On_Dutying)){
                                    $nursing_office = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name AS Nursing_Officer FROM tbl_employee WHERE Employee_ID = '$Nurse_On_Dutying'"))['Nursing_Officer'];
                                        echo "<option value='".$Nurse_On_Dutying."' selected='selected'>".$nursing_office."</option>";
                                }
                            ?>
							<option value="">---SELECT NURSE---</option>
								<?php
                                                                        //WHERE Employee_Type='Nurse'
                                                    
                                                                        
									$select_nurse = "SELECT Employee_ID, Employee_Name FROM  tbl_employee WHERE Account_Status = 'active' AND Employee_Type='Nurse'";
									$reslt = mysqli_query($conn,$select_nurse);
									while ($output = mysqli_fetch_assoc($reslt)) {
										?>
                                        <option value='<?= $output['Employee_ID']; ?>'<?php if($output['Employee_ID']==$Nurse_On_Duty){echo "selected='selected'";}?> ><?= $output['Employee_Name']; ?></option>    
										<?php
									}
									?>
							</select>
						</td>
						<td id='Corpse_Kin_Phone1' style='text-align:right;'>Phone :</td>
                                                <td width='16%' ><input type='text' id='Kin_Phone'value="<?= $relative_phone_number ?>" name='Corpse_Kin_Phone' onkeyup="numberOnly(this)" ></td>
						
						<td id='Police_Postal_Box1' style='text-align:right;'>Police Phone :</td>
						<td width='16%'   ><input type='text'  name='Police_Phone' id='Police_Phone' ></td>
					</tr>
					<tr>
						<td width='13%' style='text-align:right;'>Received By</td>
						<td width='16%'>
							<input  type='hidden' id='Corpse_Received_By' name='Corpse_Received_By' value='<?= $Employee_ID; ?>'>
							<input type='text'   disabled='disabled' value='<?= $Employee_Name; ?>'>
						</td>
						<td id='Corpse_Kin_Address1' style='text-align:right;'>Address :</td>
                                                <td width='16%' ><input type='text'  name='Corpse_Kin_Address'value="<?= $relative_Address ?>" id='Corpse_Kin_Address'></td>
                                                
						 
						<td id='Police_Postal_Box1' style='text-align:right;'>Postal Box :</td>
						<td width='16%'  ><input type='text'  name='Police_Postal_Box' id='Police_Postal_Box' ></td>
					</tr>
					<tr>
						<td style='text-align:right;'>Cause of death:</td>
                                                <td><input type='text' required="" name='course_of_death'value="<?= $deceased_reasons ?>" id='course_of_death' readonly='readonly'></td>
						<!-- <th colspan='2'>DOCTOR'S DETAILS</th> -->
						<!-- <td></td> -->
                        <td></td>
                        <td></td>
						<td id='Police_Place1' style='text-align:right;'>Police Place :</td>
						<td width='16%' ><input type='text'  name='Police_Place' id='Police_Place'></td>
					</tr>
					<tr>
						<td style='text-align:right;'>Body Condition:</td>
						<td>
						<select name='body_condition' id='body_condition'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<!--<option value=""></option>-->
                                                                <option value="Good Condition">Good Condition</option>
                                                                <option value="Fresh Dead Body">Fesh Dead Body</option>
																<option value="Bad Condition">Bad Condition</option>
								
							</select>
						</td>
						<!-- <td style='text-align: right'>Doctor Confirmed Death :</td>
						<td><input type='text' name=''></td> -->
                        <td></td>
                        <td></td>
						<td id='Postmortem_Done_By1' style='text-align:right;'>Postmortem Done By :</td>
						<td width='16%'  style='padding: 5px;text-align: center;'>
							<select  name='Postmortem_Done_By' id='Postmortem_Done_By'  style='text-align: center;'>
							<option value="">---SELECT DOCTOR---</option>
								<?php
									// $select_doctor = "SELECT Employee_ID, Employee_Name FROM  tbl_employee WHERE Employee_Type='Doctor' AND Account_Status = 'active'";
                                    
									$reslt = mysqli_query($conn,$select_doctor);
									while ($output = mysqli_fetch_assoc($reslt)) {
										?>
										<option value='<?= $output['Employee_ID']; ?>'><?= $output['Employee_Name']; ?></option>    
										<?php
									}
									?>
							</select>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>Name of doctor confirm dearth:</td>
                                                <td><input type='text'  required=""name='name_of_doctor_confirm_death' value="<?= $Docto_confirm_death_name ?>" id='name_of_doctor_confirm_death' readonly='readonly'></td>
						<!-- <td style='text-align: right'>Doctor Confirmed Death :</td>
						<td><input type='text' name=''></td> -->
                        <td></td>
                        <td></td>
						<td style='text-align:right;' id='Postmortem_No1' >Postmortem N<u>o</u> :</td>
						<td width='16%' ><input type='text'  name='Postmortem_No' id='Postmortem_No'></td>
					</tr>
					<tr>
						<td style='text-align:right;'>Deadbody's Properties:</td>
						<td colspan="5">
							<textarea name="corpse_properties" id="corpse_properties" style="float:right; font-size:16px;"  > </textarea>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>Other Details:</td>
						<td colspan="5">
							<textarea name="Other_Details" id="Other_Details" style="float:right; font-size:16px;"  > </textarea>
						</td>
					</tr>
				</table>
                                            

			</fieldset>
			
			<table width='100%'>
				<tr>
					<td style='text-align:center'>
						 
						<input type='reset' value='CLEAR' class='art-button-green' >
						<input type='hidden' id='mortuary_send_admition_form' name='mortuary_send_admition_form'>
						<button type='submit' class='art-button-green'>ADMIT</button>
						<!--<input type="button" name="Cancel_Adm" id="Cancel_Adm" value="CANCEL ADMISSION" onclick="Cancel_Admission();" class="art-button-green">-->
				</td>
				</tr>
			</table>
		</form>	

	</div>
<!-- ======== / Mortuary  =================================-->

<div id="Cancel_Admission_Confirm">
    Are you sure you want cancel admission?<br/>
    <table width="100%">
        <tr><td colspan="4"><hr></td></tr>
        <tr>
            <td width="50%"><b>Patient Name : </b><?= ucwords(strtolower($Patient_Name)); ?></td>
            <td><b>Sponsor Name : </b><?= $Guarantor_Name; ?></td>
        </tr>
        <tr>
            <td width="50%"><b>Registration Number : </b><?= $Registration_ID; ?></td>
            <td><b>Member Number : </b><?= $Member_Number; ?></td>
        </tr>
        <tr>
            <td width="50%"><b>Patient Age : </b><?= $age; ?></td>
            <td><b>Employee Cancel : </b><?= ucwords(strtolower($Employee_Name)); ?></td>
        </tr>
        <tr><td colspan="4"><hr></td></tr>
        <tr>
            <td colspan="4" style="text-align: right;">
                <input type="button" value="CANCEL THIS ADMISSION" class="art-button-green" onclick="Cancel_This_Admission()">
            </td>
        </tr>
    </table>
</div>
<style>
    .col-red{
        color:red;
    }

</style>

<script type="text/javascript">
    function Cancel_Admission(){
        $("#Cancel_Admission_Confirm").dialog("open");
    }
</script>

<script>
function admitexist(){
	alert('This patient is already admitted');
}
</script>

<script type="text/javascript">
    function Cancel_This_Admission(){
        var Check_In_ID = '<?= $Check_In_ID; ?>';
        var Registration_ID = '<?= $Registration_ID; ?>';
        
        if(window.XMLHttpRequest) {
            myObject_Cancel_Adm = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Cancel_Adm = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Cancel_Adm.overrideMimeType('text/xml');
        }
        
        myObject_Cancel_Adm.onreadystatechange = function (){
            data24 = myObject_Cancel_Adm.responseText;
            if (myObject_Cancel_Adm.readyState == 4) {
                alert("Admission cancelled successfully");
                document.location = "searchlistofoutpatientadmission.php?section=&Continuesection=Admission&PatientBilling=ContinuePatientBillingThisPage";
            }
        }; //specify name of function that will handle server response........
        myObject_Cancel_Adm.open('GET','Cancel_This_Admission.php?Check_In_ID='+Check_In_ID+'&Registration_ID='+Registration_ID,true);
        myObject_Cancel_Adm.send();
    }
</script>

<script>
     function check_if_admited(){
         var Registration_ID = '<?= $Registration_ID; ?>';
         $.ajax({
            type:'GET',
            url:'check_if_already_admited.php',
            data:{Registration_ID:Registration_ID},
            success:function(data){
               if(data=="yes"){
                   alert("The Selected Patient Arleady Admitted");
               }else{
                   admit();
               }
            }
        });
        return true;
    }
    function admit() {

        var Hospital_Ward = document.getElementById('Hospital_Ward_ID').value;
        var Bed_ID = document.getElementById('room_bed').value;
        var Kin_Name = document.getElementById('Kin_Name').value;
        var Kin_Relationship = document.getElementById('Kin_Relationship').value;
        var Kin_Phone = document.getElementById('Kin_Phone').value;

        if (Hospital_Ward == '') {
            alert('Please select ward.');
        } else {
            if (Bed_ID == '') {
                alert('There is no bed availlabe for the selected ward.Please discharge patient(s) to free up the bed.');
            } else {
                if (Kin_Name == '') {
                    alert('Please enter kin name.');
                    $('#Kin_Name').focus();
                    document.getElementById('Kin_Name').style.border = '1px solid red';
                    exit;
                }
                if (Kin_Relationship == '') {
                    alert('Please enter relationship.');
                    $('#Kin_Relationship').focus();
                    document.getElementById('Kin_Relationship').style.border = '1px solid red';
                    exit;
                }
                if (Kin_Phone == '') {
                    alert('Please kin phone.');
                    $('#Kin_Phone').focus();
                    document.getElementById('Kin_Phone').style.border = '1px solid red';
                    exit;
                }
                if (confirm('Are you sure you want to admit a patient?')) {
                    document.getElementById('myForm').submit();
					//Print_Barcode_Payment();
                }
            }
        }
		
		
    }
</script>

	
<script type="text/javascript" language="javascript">
    function Get_Ward_Beds(Hospital_Ward_ID) {
       if(Hospital_Ward_ID==null || Hospital_Ward_ID==''){
           alert('Please select hospital ward');
             $('#Hospital_Ward_ID').focus();
            document.getElementById('Hospital_Ward_ID').style.border = '1px solid red';
           exit;
       } 
         //var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = function(){
           var data = mm.responseText.split('#$%^$##%$&&');
        //if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Bed_ID').innerHTML = data[0];
            document.getElementById('bedNumber').innerHTML = data[1];
        //}
        }; //specify name of function that will handle server response....
        mm.open('GET', 'Get_Ward_Beds.php?Hospital_Ward_ID=' + Hospital_Ward_ID, true);
        mm.send();
   
    }
   function Get_Ward_Room(Hospital_Ward){
       $.ajax({
           type:'GET',
           url:'ward_room_selection_option.php',
           data:{ward_id:Hospital_Ward},
           success:function (data){
               $("#ward_room").html(data)
               $("#room_bed").html("")
           }
       });
   }
   function get_ward_room_bed(ward_room_id){
       var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
        $.ajax({
           type:'GET',
           url:'ward_bed_selection_option.php',
           data:{ward_id:Hospital_Ward_ID,ward_room_id:ward_room_id},
           success:function (data){
               $("#room_bed").html(data)
           }
       }); 
   }
   function ward_nature(Hospital_Ward){
     
     if (window.XMLHttpRequest) {
                myObjectPreview = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPreview.overrideMimeType('text/xml');
            }
            myObjectPreview.onreadystatechange = function () {
                data = myObjectPreview.responseText;
                if (myObjectPreview.readyState == 4) {
                   // document.getElementById('Ward_nature').value=data;
         var gender='<?= $Gender;?>';
        
         if(gender===data){
          Get_Ward_Room(Hospital_Ward);  
         } else if(gender!==data){
             if(data==='Both'){
              Get_Ward_Room(Hospital_Ward);   
             }else{
              alert('This patient cannot be admitted in the '+data+' ward');
              document.getElementById('bedNumber').innerHTML ='';
              document.getElementById('Hospital_Ward_ID').value ="";
              document.getElementById('Bed_ID').innerHTML ="<option selected='selected'></option>";
              return false;
             }
         }

        }    
            }; //specify name of function that will handle server response........
            
            myObjectPreview.open('GET','Get_Ward_Nature.php?Hospital_Ward='+ Hospital_Ward, true);
            myObjectPreview.send();
   }
</script>				
<script type="text/javascript" language="javascript">
    function checkPatientNumber(bed_id) {
       if(bed_id==null || bed_id==''){
           alert('Please select bed number');
            $('#Bed_ID').focus();
            document.getElementById('Bed_ID').style.border = '1px solid red';
           exit;
       } 
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'Get_bed_patients_number.php?bed_id=' + bed_id, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4 && mm.status == 200) {
          if(parseInt(data) > 0){
             alert('There are already '+data+' patient(s) in this bed.Do you still want to admit a patient in this bed.'); 
          }  
            
        }
    }
</script>

<script>
    function mortuary_admit() {

        var Hospital_Ward = document.getElementById('Hospital_Ward_ID').value;
        var Bed_ID = document.getElementById('Bed_ID').value;
        var Kin_Name = document.getElementById('Corpse_Brought_By').value;
        var Kin_Relationship = document.getElementById('Corpse_Kin_Relationship').value;
        var Kin_Phone = document.getElementById('Corpse_Kin_Phone').value;
        var Case_Type = document.getElementById('Case_Type').value;
        var admitted_from=document.getElementById('admitted_from').value;
        var inalala_bilakulala=document.getElementById('inalala_bilakulala').value;
//alert("inafika");

        if (Hospital_Ward == '') {
            alert('Please select ward.');
        } else {
            if (Bed_ID == '') {
                alert('There is no bed availlabe for the selected ward.Please discharge patient(s) to free up the bed.');
            } else {
                if (Kin_Name == '') {
                    alert('Please enter kin name.');
                    $('#Kin_Name').focus();
                    document.getElementById('Kin_Name').style.border = '1px solid red';
                    exit;
                }
                if ( Kin_Relationship== '') {
                    alert('Please enter relationship.');
                    $('#Kin_Relationship').focus();
                    document.getElementById('Kin_Relationship').style.border = '1px solid red';
                    exit;
                }
                if (Kin_Phone == '') {
                    alert('Please enter kin phone.');
                    $('#Kin_Phone').focus();
                    document.getElementById('Kin_Phone').style.border = '1px solid red';
                    exit;
                }
                if (Case_Type == '') {
                    alert('Please enter case type.');
                    $('#Case_Type').focus();
                    document.getElementById('Case_Type').style.border = '1px solid red';
                    exit;
                }
                if (admitted_from == '') {
                    alert('Please enter where body admitted from.');
                    $('#admitted_from').focus();
                    document.getElementById('admittted_from').style.border = '1px solid red';
                    exit;
                }
                if (inalala_bilakulala == '') {
                    alert('Tafadhali Ingiza kama mwili utalala au utahifadhiwa bila kula.');
                    $('#inalala_bilakulala').focus();
                    document.getElementById('inalala_bilakulala').style.border = '1px solid red';
                    exit;
                }
                if (confirm('Are you sure you want to admit a patient?')) {
                    document.getElementById('morgue_form').submit();
					//Print_Barcode_Payment();
                }
            }
        }
		
		
    }
</script>
<script>
    function numberOnly(myElement) {
        var reg = new RegExp('[^0-9]+$');
        var str = myElement.value;
        if (reg.test(str)) {
            if (!isNaN(parseInt(str))) {
                intval = parseInt(str);
            } else {
                intval = '';
            }
            myElement.value = intval;
        }
    }
</script>

<script src="js/select2.min.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Mortuary_Date_In').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Mortuary_Date_In').datetimepicker({value:'',step:01});
    $('#Mortuary_Deadline').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Mortuary_Deadline').datetimepicker({value:'',step:01});
    $('#Date_Of_Death').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_Of_Death').datetimepicker({value:'',step:01});
</script>
 <script>
            function Print_Barcode_Payment() {
			 var Hospital_Ward = document.getElementById('Hospital_Ward_ID').value;
             var Bed_ID = document.getElementById('Bed_ID').value;	
			if(Hospital_Ward=='' || Bed_ID==''){
				 alert('Ward and Bed Number must be filled first!');
				 return false;
			 }
                // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
			var winClose = popupwindow('barcode_admission/BCGcode39.php?Registration_ID=<?= $Registration_ID; ?>&Hospital_Ward='+Hospital_Ward+'&Bed_ID='+Bed_ID+'&LaboratoryTestSpecimenBacodedThisPage=ThisPage', 'Print Barcode', 330, 230);
                //winClose.close();
                //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

            }

            function popupwindow(url, title, w, h) {
                var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                var wTop = window.screenTop ? window.screenTop : window.screenY;

                var left = wLeft + (window.innerWidth / 2) - (w / 2);
                var top = wTop + (window.innerHeight / 2) - (h / 2);
                var mypopupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                return mypopupWindow;
            }

        </script>
<script>

  function Show_Morgue_Admit_Form() 
		{
			if (document.getElementById('patient')) {

                if (document.getElementById('patient').style.display == 'none') {
                    document.getElementById('patient').style.display = 'block';
                    document.getElementById('morgue_form').style.display = 'none';
                }
                else {
                    document.getElementById('patient').style.display = 'none';
                    document.getElementById('morgue_form').style.display = 'block';
                }
            }
		}	
</script>

<script type="text/javascript" language="javascript">
    function Get_Ward_Beds_M(Hospital_Ward_ID) {
       if(Hospital_Ward_ID==null || Hospital_Ward_ID==''){
           alert('Please select hospital ward');
             $('#Hospital_Ward_ID').focus();
            document.getElementById('Hospital_Ward_ID_M').style.border = '1px solid red';
            document.getElementById('Bed_ID_M').innerHTML ="<option selected='selected'></option>";
            document.getElementById('bedNumber_M').innerHTML ='';
           exit;
       } 
         //var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = function(){
           var data = mm.responseText.split('#$%^$##%$&&');
        //if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Bed_ID_M').innerHTML = data[0];
            document.getElementById('bedNumber_M').innerHTML = data[1];
        //}
        }; //specify name of function that will handle server response....
        mm.open('GET', 'Get_Ward_Beds.php?Hospital_Ward_ID=' + Hospital_Ward_ID, true);
        mm.send();
   
    }
   
   function ward_nature_M(Hospital_Ward){
     
     if (window.XMLHttpRequest) {
                myObjectPreview = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPreview.overrideMimeType('text/xml');
            }
            myObjectPreview.onreadystatechange = function () {
                data = myObjectPreview.responseText;
                if (myObjectPreview.readyState == 4) {
                   // document.getElementById('Ward_nature').value=data;
         var gender='<?= $Gender;?>';
        
         if(gender===data){
          Get_Ward_Beds_M(Hospital_Ward);  
         } else if(gender!==data){
             if(data==='Both'){
              Get_Ward_Beds_M(Hospital_Ward);   
             }else{
              alert('This patient cannot be admitted in the '+data+' ward');
              document.getElementById('bedNumber_M').innerHTML ='';
              document.getElementById('Hospital_Ward_ID_M').value ="";
              document.getElementById('Bed_ID_M').innerHTML ="<option selected='selected'></option>";
              return false;
             }
         }

        }    
            }; //specify name of function that will handle server response........
            
            myObjectPreview.open('GET','Get_Ward_Nature.php?Hospital_Ward='+ Hospital_Ward, true);
            myObjectPreview.send();
   }
</script>				
<script type="text/javascript" language="javascript">
    function checkPatientNumber_M(bed_id) {
       if(bed_id==null || bed_id==''){
           alert('Please select bed number');
            $('#Bed_ID_M').focus();
            document.getElementById('Bed_ID_M').style.border = '1px solid red';
           exit;
       } 
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'Get_bed_patients_number.php?bed_id=' + bed_id, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4 && mm.status == 200) {
          if(parseInt(data) > 0){
             alert('There are already '+data+' patient(s) in this bed.Do you still want to admit a patient in this bed.'); 
          }  
            
        }
    }
    
    function validateForm(){
        
        var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
        var Other_Details=$("#Other_Details").val();
        var Postmortem_No=$("#Postmortem_No").val();
        var Postmortem_Done_By=$("#Postmortem_Done_By").val();
        var Police_Place=$("#Police_Place").val();
        var Police_Postal_Box=$("#Police_Postal_Box").val();
        var Corpse_Received_By=$("#Corpse_Received_By").val();
        var Police_Phone=$("#Police_Phone").val();
        var Police_Station=$("#Police_Station").val();
        var Kin_Phone=$("#Kin_Phone").val();
        var Place_Of_Death=$("#Place_Of_Death").val();
        var Police_Title=$("#Police_Title").val();
        var Corpse_Kin_Relationship=$("#Corpse_Kin_Relationship").val();
        var Case_Type=$("#Case_Type").val();
        var admitted_from=$("#admitted_from").val();
        var inalala_bilakulala=$("#inalala_bilakulala").val();
        var Police_Name=$("#Police_Name").val();
        var Vehicle_No_In=$("#Vehicle_No_In").val();
        var Corpse_Kin_Address=$("#Corpse_Kin_Address").val();
        var Police_No=$("#Police_No").val();
        var Nurse_On_Duty=$("#Nurse_On_Duty").val();
        var room_bed=$("#room_bed").val();
        var ward_room=$("#ward_room").val();
        var Date_Of_Death=$("#Date_Of_Death").val();
        var new_sponsor=$("#new_sponsor").val();
        var Corpse_Brought_By=$("#Corpse_Brought_By").val();
        
        if(new_sponsor==""){
            $('#new_sponsor').focus();
            $("#new_sponsor").css("border","3px solid red!important");
            alert("Select Sponsor first")
            return false;
        }else{
            $("#new_sponsor").css("border",""); 
        }
        if(Hospital_Ward_ID==""){
            $('#Hospital_Ward_ID').focus();
            $("#Hospital_Ward_ID").css("border","3px solid red");
            return false;
        }else{
            $("#Hospital_Ward_ID").css("border",""); 
        }
//        if(Other_Details==""){
//            $('#Other_Details').focus();
//            $("#Other_Details").css("border","3px solid red");
//            return false;
//        }else{
//            $("#Other_Details").css("border",""); 
//        }
        if(Postmortem_No=="" && Case_Type=="police"){
            $('#Postmortem_No').focus();
            $("#Postmortem_No").css("border","3px solid red");
            return false;
        }else{
            $("#Postmortem_No").css("border",""); 
        }
        if(Postmortem_Done_By=="" && Case_Type=="police"){
            $('#Postmortem_Done_By').focus();
            $("#Postmortem_Done_By").css("border","3px solid red");
            return false;
        }else{
            $("#Postmortem_Done_By").css("border",""); 
        }
        if(Police_Place==""&&Case_Type=="police"){
            $('#Police_Place').focus();
            $("#Police_Place").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Place").css("border",""); 
        }
        if(Police_Postal_Box==""&&Case_Type=="police"){
            $('#Police_Postal_Box').focus();
            $("#Police_Postal_Box").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Postal_Box").css("border",""); 
        }
        if(Corpse_Received_By==""){
            $('#Corpse_Received_By').focus();
            $("#Corpse_Received_By").css("border","3px solid red");
            return false;
        }else{
            $("#Corpse_Received_By").css("border",""); 
        }
        if(Police_Phone==""&&Case_Type=="police"){
            $('#Police_Phone').focus();
            $("#Police_Phone").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Phone").css("border",""); 
        }
        if(Police_Station==""&&Case_Type=="police"){
            $('#Police_Station').focus();
            $("#Police_Station").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Station").css("border",""); 
        }
        if(Kin_Phone==""){
            $('#Kin_Phone').focus();
            $("#Kin_Phone").css("border","3px solid red");
            return false;
        }else{
            $("#Kin_Phone").css("border",""); 
        }
        if(Place_Of_Death==""){
            $('#Place_Of_Death').focus();
            $("#Place_Of_Death").css("border","3px solid red");
            return false;
        }else{
            $("#Place_Of_Death").css("border",""); 
        }
        if(Police_Title==""&&Case_Type=="police"){
            $('#Police_Title').focus();
            $("#Police_Title").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Title").css("border",""); 
        }
        if(Corpse_Kin_Relationship==""&&Case_Type=="home"){
            $('#Corpse_Kin_Relationship').focus();
            $("#Corpse_Kin_Relationship").css("border","3px solid red");
            return false;
        }else{
            $("#Corpse_Kin_Relationship").css("border",""); 
        }
        if(Case_Type==""){
            $('#Case_Type').focus();
            $("#Case_Type").css("border","3px solid red");
            return false;
        }else{
            $("#Case_Type").css("border",""); 
        }
        if(Police_Name=="" && Case_Type=="police"){
            $('#Police_Name').focus();
            $("#Police_Name").css("border","3px solid red");
            return false;
        }else{
            $("#Police_Name").css("border",""); 
        }
//        if(Vehicle_No_In==""){
//            $('#Vehicle_No_In').focus();
//            $("#Vehicle_No_In").css("border","3px solid red");
//            return false;
//        }else{
//            $("#Vehicle_No_In").css("border",""); 
//        }
        if(Corpse_Kin_Address==""){
            $('#Corpse_Kin_Address').focus();
            $("#Corpse_Kin_Address").css("border","3px solid red");
            return false;
        }else{
            $("#Corpse_Kin_Address").css("border",""); 
        }
        if(Police_No=="" && Case_Type=="police"){
            $('#Police_No').focus();
            $("#Police_No").css("border","3px solid red");
            return false;
        }else{
            $("#Police_No").css("border",""); 
        }
        if(Nurse_On_Duty==""&&Case_Type=="hospital"){
             
            $('#Nurse_On_Duty').focus();
            $("#Nurse_On_Duty").css("border","3px solid red");
            return false;
        }else{
            $("#Nurse_On_Duty").css("border",""); 
        }
        if(room_bed==""){
            $('#room_bed').focus();
            $("#room_bed").css("border","3px solid red");
            return false;
        }else{
            $("#room_bed").css("border",""); 
        }
        if(ward_room==""){
            $('#ward_room').focus();
            $("#ward_room").css("border","3px solid red");
            return false;
        }else{
            $("#ward_room").css("border",""); 
        }
        if(Date_Of_Death==""){
            $('#Date_Of_Death').focus();
            $("#Date_Of_Death").css("border","3px solid red");
            return false;
        }else{
            $("#Date_Of_Death").css("border",""); 
        }
        if(Corpse_Brought_By==""&&Case_Type=="home"){
            $('#Corpse_Brought_By').focus();
            $("#Corpse_Brought_By").css("border","3px solid red");
            return false;
        }else{
            $("#Corpse_Brought_By").css("border",""); 
        }
        return true;
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<script>
   $(document).ready(function(){
       $("#Nurse_On_Duty").select2();
      $("#Cancel_Admission_Confirm").dialog({autoOpen: false, width: '50%', height: 200, title: 'CONFIRM', modal: true});
   });
</script>
<?php
 
include("./includes/footer.php");
?>