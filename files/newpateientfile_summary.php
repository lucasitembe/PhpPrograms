<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_SESSION['userinfo'])) {
 
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<!----------------------------------------------------------------------------------------------------------------------->
<link rel="stylesheet" href="tablew.css" media="screen">
<style>
    table th {
        text-align: left!important;
    }
</style>
<?php
//include("./includes/connection.php");
//session_start();
if (isset($_GET['name'])) {
    $Patient_Name = $_GET['name'];
} else {
    $Patient_Name = "";
}

if (isset($_GET['fromPatientFile'])) {
    $fromPatientFile ='&fromPatientFile=true';
} else {
    $fromPatientFile = '';
}

if (isset($_GET['position'])) {
    $position ='&position='.$_GET['position'].'';
} else {
    $position = '';
}

if (isset($_GET['fromPatientFile'])) {
    $fromPatientFile ='&fromPatientFile=true';
} else {
    $fromPatientFile = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

//Find the current date to filter check in list     
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//$Patient_Payment_ID=$row['Patient_Payment_ID'];
//$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
if(isset($_GET['from_consulted'])){
   $from_consulted=$_GET['from_consulted'];
}else{
   $from_consulted=""; 
}
if(isset($_GET['previous_notes'])){
   $previous_notes=$_GET['previous_notes'];
}else{
   $previous_notes=""; 
}


?>
<input type="button" value="BACK" class="art-button-green" onclick="history.go(-1)"/>

<!-- <a href="all_patient_file_link_station.php?Registration_ID=<?=$Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&this_page_from=<?= $this_page_from ?>&previous_notes=<?= $previous_notes ?>&from_consulted=<?= $from_consulted ?>" class="art-button-green">BACK</a> -->
<!--<a href="clinicalnotes.php?Registration_ID=<?=$Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage" class="art-button-green">BACK</a>-->
<div class="row">
    <div class="col-md-12" style="text-align: center">
            <div class="pagination">
                
        <?php
       
     $select_patients = "SELECT ppl.Patient_Payment_Item_List_ID, ppl.Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c, tbl_patient_payment_item_list ppl WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
			    c.Registration_ID = '$Registration_ID' 
               
                       UNION 
                       
                     SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c WHERE
			    c.Patient_Payment_Item_List_ID IS NULL and
			    c.Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 100
              
     ";
        
        
        $result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($result);

        
         
        $resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $pat = mysqli_fetch_array($resultPat);
        $patientName = $pat['Patient_Name'];
        $count=1;
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $href = "consultation_ID=" . $row['consultation_ID'];
            $Consultation_Date_And_Time=$row['Consultation_Date_And_Time'];
            if($Consultation_Date_And_Time==$_GET['Consultation_Date_And_Time']){
                $active_class="active";
            }else{
                $active_class="";
            }
            if (isset($_GET['doctorsworkpage'])) {
                $href.='doctorsworkpage=yes';
            }
            $href .="&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "";

            $Review = $ViewAttachments = $Attach = '';

            if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
                $Review = "<a href='newpateientfile_summary.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                 $Consultation_Date_And_Time
		</a>";

                
 
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";

                
                 

            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
 
                

             } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
                $Review = "<a href='newpateientfile_summary.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                        $Consultation_Date_And_Time
		</a>";

               
            } else {
                if (isset($_GET['section']) && strtolower($_GET['section']) == 'patient') {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time' href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                         $count
		    </a>";
 
                } else {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time'href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                             $count
		    </a>";

                  
                }
            }
            ?>
                <li class="<?= $active_class ?>">
                <?php
                      echo $Review;
                ?>
            </li>
           
            <?php
            $i++;
            $count++;
        }
        ?> 
   </div>
</div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="well" style="text-align:center">
            <h3><?= $_GET['Consultation_Date_And_Time'] ?></h3>
        </div>
    </div>
</div>

<!----------------------------------------------------------------------------------------------------------------------->
<!----------------------------------now to the patient file-------------------------------------------------------------->
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
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID,sp.Postal_Address,sp.Benefit_Limit
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
            $Country = $row['Country'];
            $Patient_Picture = $row['Patient_Picture'];
            $Deseased = ucfirst(strtolower($row['Diseased']));
            $Sponsor_Postal_Address = $row['Postal_Address'];
            $Benefit_Limit = $row['Benefit_Limit'];
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

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
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
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Deseased = '';
    $Sponsor_Postal_Address = '';
    $Benefit_Limit = '';
    $Patient_Picture = '';
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

$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
//die($consultation_ID);
$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$data = '';

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}

$showItemStatus = false;
$display = "style='display:none' class='display-remove'";



if (isset($_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file']) && $_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file'] == '1') {
    $showItemStatus = true;
    $display = "";
}

$hasOutpatientDetails = false;
$hasInpatientDetails = false;


$check_was_inpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE consultation_ID = '" . $_GET['consultation_ID'] . "' AND Registration_ID='" . $_GET['Registration_ID'] . "' AND Admit_Status='admitted'") or die(mysqli_error($conn));

if (mysqli_num_rows($check_was_inpatient) > 0) {
    $hasInpatientDetails = true;
}

$check_was_outpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE consultation_ID = '" . $_GET['consultation_ID'] . "' AND Patient_Payment_Item_List_ID IS NULL") or die(mysqli_error($conn));
$check_was_outpatient_ct = mysqli_query($conn,"SELECT c.consultation_ID FROM tbl_check_in_details cd JOIN tbl_consultation c ON cd.consultation_ID=c.consultation_ID WHERE c.consultation_ID = '" . $_GET['consultation_ID'] . "' AND Patient_Payment_Item_List_ID IS NULL") or die(mysqli_error($conn));

if (mysqli_num_rows($check_was_outpatient) == 0) {
    $hasOutpatientDetails = true;
} elseif (mysqli_num_rows($check_was_outpatient_ct) == 0) {
    $hasOutpatientDetails = true;
}

if(isset($_GET['Consultation_Date_And_Time'])){
?>
<!------------------------------------------------------------------------------------------------------------------------------>
<?php 
//get consultation details
$consultation_ID=$_GET['consultation_ID'];
$Registration_ID=$_GET['Registration_ID'];

$sql_select_consultation_detail_result=mysqli_query($conn,"SELECT *FROM tbl_consultation WHERE consultation_ID='$consultation_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_consultation_detail_result)>0){
    $consultation_row=mysqli_fetch_assoc($sql_select_consultation_detail_result);
    $maincomplain=$consultation_row['maincomplain'];
    $firstsymptom_date=$consultation_row['firstsymptom_date'];
    $history_present_illness=$consultation_row['history_present_illness'];
    $review_of_other_systems=$consultation_row['review_of_other_systems'];
    $general_observation=$consultation_row['general_observation'];
    $systemic_observation=$consultation_row['systemic_observation'];
    $provisional_diagnosis=$consultation_row['provisional_diagnosis'];
    $diferential_diagnosis=$consultation_row['diferential_diagnosis'];
    $Comment_For_Laboratory=$consultation_row['Comment_For_Laboratory'];
    $Comment_For_Radiology=$consultation_row['Comment_For_Radiology'];
    $Comments_For_Procedure=$consultation_row['Comments_For_Procedure'];
    $Comments_For_Surgery=$consultation_row['Comments_For_Surgery'];
    $investigation_comments=$consultation_row['investigation_comments'];
    $diagnosis=$consultation_row['diagnosis'];
    $remarks=$consultation_row['remarks'];
    $course_of_injuries=$consultation_row['course_of_injuries'];
    $Type_of_patient_case=$consultation_row['Type_of_patient_case'];
}



//$sql_select_consultation_hist_result=mysqli_query($conn,"SELECT *FROM tbl_consultation INN") or die(mysqli_error($conn));
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                PATIENT MEDICATION RECORD   <b style="color:#000000"><?= $Consultation_Date_And_Time ?></b>
            </div>
            <div class="panel-body">
                <div class="row" style="height:80vh; overflow-x:scroll;overflow-y:scroll">
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                        <div class="row">
                            <div class="col-md-3">
                                <b>Patient Name:</b><?= $Patient_Name ?><br/><br/>
                                <b>Registration #:</b><?= $Registration_ID ?><br/><br/>
                                <b>Date of Birth:</b><?= date("j F, Y", strtotime($Date_Of_Birth)) ?><br/><br/>
                                <b>Insurance Details:</b><?= $Guarantor_Name ?>,  <b>Address:</b><?= $Sponsor_Postal_Address ?> ,  <b>Benefit Limit:</b><?= $Benefit_Limit ?></b><br/>
                            </div>
                            <div class="col-md-3">
                                <b>Country:</b><?= $Country ?><br/><br/>
                                <b>Phone #:</b><?= $Phone_Number ?><br/><br/>
                                <b>Gender:</b><?= $Gender ?><br/><br/>
                                <b>Consultation Date:</b><?= $Consultation_Date_And_Time ?><br/>
                            </div>
                            <div class="col-md-3">
                                <b>Region:</b><?= $Region ?><br/><br/>
                                <b>District #:</b><?= $District ?><br/><br/>
                                <b>Diseased:</b><?= $Deseased ?><br/><br/>

                            </div>
                            <div class="col-md-3">
                                <img width="80%" height="150" name="Patient_Picture" id="Patient_Pictured" src="./patientImages/'<?= $Patient_Picture ?>'">
                            </div>
                        </div>
                            <div class="row">
                                <div class='col-md-12'>
                                    <b>Consultants: </b>
                                    <?php 
                                       $patient_consultant="";
                                       $Employee_Name_old="";
                                       
                                        $sql_select_consultant=mysqli_query($conn,"SELECT Employee_Name,Employee_Title,kada FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID' GROUP BY ch.employee_ID ") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_consultant)>0){
                                            while($consultant_rows=mysqli_fetch_assoc($sql_select_consultant)){
                                                
                                                $Employee_Name=$consultant_rows['Employee_Name'];
                                                
                                                 $kada=$consultant_rows['kada'];
                                                 if($kada=="general_practitioner"){
                                                           $kada_name="General Practitioner"; 
                                                        }else if($kada=="specialist"){
                                                           $kada_name="Specialist"; 
                                                        }else if($kada=="super_specialist"){
                                                           $kada_name="Super Specialist";  
                                                        }else{
                                                           $kada_name= $kada;
                                                        }
                                                       
                                                
                                               
                                                 echo $Employee_Name." <b>($kada_name)</b> ,";   
   
                                            }
                                        }
                                        
                                        
                                        ?>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Complain</h3>
                    </div>
                    
                    <div class="col-md-12">
                        <br/>
                        <table class="table table-bordered">
                            <tr style="background:#E8E8E8;">
                                <th>#</th>
                                <th>Doctor's Name</th>
                                <th>Main Complain</th>
                                <th>Type Of Patient Case</th>
                                <th>Course Injuries</th>
                                <th>First Date Of Symptoms</th>
                                <th>History Of Present Illness</th>
                            </tr>
                            <?php 
                                $count_row=1;
                                $sql_select_patient_complains_result=mysqli_query($conn,"SELECT e.Employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.history_present_illness,Type_of_patient_case,ch.course_of_injuries,Employee_Name FROM tbl_consultation_history ch,tbl_consultation c,tbl_employee e
                                        WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.employee_ID AND c.consultation_ID='$consultation_ID' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                 $doctors_ID=[];
                                if(mysqli_num_rows($sql_select_patient_complains_result)>0){
                                     $doctors_ID=[];
                                    while($complain_rows=mysqli_fetch_assoc($sql_select_patient_complains_result)){
                                        $Employee_ID=$complain_rows['Employee_ID'];
                                        $maincomplain=$complain_rows['maincomplain'];
                                        $firstsymptom_date=$complain_rows['firstsymptom_date'];
                                        $history_present_illness=$complain_rows['history_present_illness'];
                                        $Type_of_patient_case=$complain_rows['Type_of_patient_case'];
                                        $course_of_injuries=$complain_rows['course_of_injuries'];
                                        $Employee_Name=$complain_rows['Employee_Name'];
                                        array_push($doctors_ID, $Employee_ID);
                                         
                                    $sql_select_course_of_injuries_result=mysqli_query($conn,"SELECT course_injury FROM tbl_hospital_course_injuries WHERE hosp_course_injury_ID='$course_of_injuries'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_course_of_injuries_result)>0){
                                       $injuary_course_rows=mysqli_fetch_assoc($sql_select_course_of_injuries_result); 
                                       $course_injury=$injuary_course_rows['course_injury'];
                                    }else{
                                       $course_injury="";
                                    }
                                 
                                        if($Type_of_patient_case=="new_case"){
                                            $Type_of_patient_case="New Case";
                                        }else{
                                           $Type_of_patient_case="Continues Case"; 
                                        }
                                        echo "<tr>
                                                <td>$count_row</td>
                                                <td>$Employee_Name</td>
                                                <td>$maincomplain</td>
                                                <td>$Type_of_patient_case</td>
                                                <td>$course_injury</td>
                                                <td>$firstsymptom_date</td>
                                                <td>$history_present_illness</td>
                                             </tr>";
                                    }
                                }
                                ?>
                        </table>
                        <?php 
                        foreach($doctors_ID as $doctorsID) {
                                       
                         $data .= "<table class='table table-condensed'>"
                    . "<tr style='background:#DEDEDE'><td style='width:50%'><b>Complain</b></td><td><b>Duration</b></td></tr>";
            //select detail from patient file
             //select previous main complain
                                $sql_select_main_complain_detail_result=mysqli_query($conn,"SELECT main_complain,duration FROM tbl_main_complain WHERE consultation_ID='$consultation_ID' AND consultant_id='$doctorsID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_main_complain_detail_result)>0){
                                    while($previous_mai_complain_rows=mysqli_fetch_assoc($sql_select_main_complain_detail_result)){
                                        $main_complain=$previous_mai_complain_rows['main_complain'];
                                        $duration=$previous_mai_complain_rows['duration'];
                                        $data .="<tr><td>$main_complain</td><td>$duration</td></tr>";
                                    }
                                }
            $data .="</table><table class='table table-condensed'><caption><b>HISTORY OF  PRESENT ILLNESS<b></caption>";
            $data .="<tr style='background:#DEDEDE'>
                                <td><b>
                                    Complain</b>
                                </td>
                                <td><b>
                                    Duration
                                </td>
                                <td><b>
                                    Onset</b>
                                </td>
                                <td><b>
                                    Periodicity</b>
                                </td>
                                <td><b>
                                    Aggravating Factor</b>
                                </td>
                                <td><b>
                                    Relieving Factor</b>
                                </td>
                                <td><b>Associated with</b></td>
                            </tr>";
             $sql_select_previous_hpi_history_result=mysqli_query($conn,"SELECT complain,duration,onset,periodicity,aggrevating_factor,relieving_factor,associated_with FROM tbl_history_of_present_illiness WHERE consultation_ID='$consultation_ID' AND consultant_id='$doctorsID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_previous_hpi_history_result)>0){
                                    while($hpi_rows=mysqli_fetch_assoc($sql_select_previous_hpi_history_result)){
                                        $complain=$hpi_rows['complain'];
                                        $duration=$hpi_rows['duration'];
                                        $onset=$hpi_rows['onset'];
                                        $periodicity=$hpi_rows['periodicity'];
                                        $aggrevating_factor=$hpi_rows['aggrevating_factor'];
                                        $relieving_factor=$hpi_rows['relieving_factor'];
                                        $associated_with=$hpi_rows['associated_with'];
                                       
                                         $data .="<tr>
                                            <td>
                                                $complain
                                            </td>
                                            <td>
                                                $duration
                                            </td>
                                            <td>
                                                 $onset
                                            </td>
                                            <td>
                                               $periodicity
                                            </td>
                                            <td>
                                                $aggrevating_factor
                                            </td>
                                            <td>
                                                $relieving_factor
                                            </td>
                                            <td>$associated_with</td>
                                        </tr> ";  
                                      
                                    }
                        }}
            $data .="</table>"; 
            echo $data;
            ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Physical Examinations</h3>
                    </div>
                    <div class="col-md-12">
                        <br/>
                        <table class="table table-bordered">
                            <tr style="background:#E8E8E8;">
                                <th>#</th>
                                <th>Doctor's Name</th>
                                <th>General Examination Observation</th>
                                <th>Systemic Examination Observation</th>
                                <th>Review Of Other Systems</th>
                            </tr>
                            <?php 
                                $count_row=1;
                                $sql_select_patient_complains_result=mysqli_query($conn,"SELECT ch.review_of_other_systems,ch.general_observation,ch.systemic_observation,Employee_Name FROM tbl_consultation_history ch,tbl_consultation c,tbl_employee e
                                        WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.employee_ID AND c.consultation_ID='$consultation_ID' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_patient_complains_result)>0){
                                    while($complain_rows=mysqli_fetch_assoc($sql_select_patient_complains_result)){
                                        $review_of_other_systems=$complain_rows['review_of_other_systems'];
                                        $general_observation=$complain_rows['general_observation'];
                                        $systemic_observation=$complain_rows['systemic_observation'];
                                        $Employee_Name=$complain_rows['Employee_Name'];
                                        echo "<tr>
                                                <td>$count_row</td>
                                                <td>$Employee_Name</td>
                                                <td>$general_observation</td>
                                                <td>$review_of_other_systems</td>
                                                <td>$systemic_observation</td>
                                                
                                             </tr>";
                                        $count_row++;
                                    }
                                }
                                ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Diagnosis</h3>
                    </div>
                    <div class="col-md-12">
                        <div class='row'>
                            <div class='col-md-12'>
                                <br/>
                                <br/>
                                 <h5><b>1.Provisional Diagnosis</b></h5>
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th>#</th>
                                        <th>Doctor's Name</th>
                                        <th>Provisional Diagnosis</th>
                                        <th>Disease Code</th>

                                    </tr>
                                    <?php 
                                        $count_diadnosis=1;
                                       $provisional_diagnosis="";
                                        $sql_select_provisional_result=mysqli_query($conn,"SELECT Employee_Name,disease_name,disease_code FROM tbl_disease td INNER JOIN tbl_disease_consultation tdc ON td.disease_ID=tdc.disease_ID INNER JOIN tbl_employee e ON tdc.employee_ID=e.employee_ID WHERE tdc.consultation_ID='$consultation_ID' AND tdc.diagnosis_type='provisional_diagnosis'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_provisional_result)>0){
                                                while($p_diagnosis_rows=mysqli_fetch_assoc($sql_select_provisional_result)){
                                                $disease_code=$p_diagnosis_rows['disease_code'];
                                                $provisional_diagnosis=$p_diagnosis_rows['disease_name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                                $Employee_Name=$p_diagnosis_rows['Employee_Name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                              echo "<tr>
                                                        <td>$count_diadnosis</td>
                                                        <td>$Employee_Name</td>
                                                        <td>$provisional_diagnosis</td>
                                                        <td>$disease_code</td>
                                                    </tr>";
                                              $count_diadnosis++;
                                                }
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <br/>
                                <br/>
                                        <h5><b>2.Differential Diagnosis</b></h5>
                                    
                                <table class="table table-bordered">
                                    
                                    <tr style="background:#E8E8E8;">
                                        <th>#</th>
                                        <th>Doctor's Name</th>
                                        <th>Differential Diagnosis</th>
                                        <th>Disease Code</th>

                                    </tr>
                                    <?php 
                                        $count_diadnosis=1;
                                       $provisional_diagnosis="";
                                        $sql_select_provisional_result=mysqli_query($conn,"SELECT Employee_Name,disease_name,disease_code FROM tbl_disease td INNER JOIN tbl_disease_consultation tdc ON td.disease_ID=tdc.disease_ID INNER JOIN tbl_employee e ON tdc.employee_ID=e.employee_ID WHERE tdc.consultation_ID='$consultation_ID' AND tdc.diagnosis_type='diferential_diagnosis'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_provisional_result)>0){
                                                while($p_diagnosis_rows=mysqli_fetch_assoc($sql_select_provisional_result)){
                                                $disease_code=$p_diagnosis_rows['disease_code'];
                                                $provisional_diagnosis=$p_diagnosis_rows['disease_name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                                $Employee_Name=$p_diagnosis_rows['Employee_Name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                              echo "<tr>
                                                        <td>$count_diadnosis</td>
                                                        <td>$Employee_Name</td>
                                                        <td>$provisional_diagnosis</td>
                                                        <td>$disease_code</td>
                                                    </tr>";
                                              $count_diadnosis++;
                                                }
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <br/>
                                <br/>
                                <h5><b>3.Final Diagnosis</b></h5>
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th>#</th>
                                        <th>Doctor's Name</th>
                                        <th>Final Diagnosis</th>
                                        <th>Disease Code</th>

                                    </tr>
                                    <?php 
                                       $count_diadnosis=1;
                                       $provisional_diagnosis="";
                                        $sql_select_provisional_result=mysqli_query($conn,"SELECT Employee_Name,disease_name,disease_code FROM tbl_disease td INNER JOIN tbl_disease_consultation tdc ON td.disease_ID=tdc.disease_ID INNER JOIN tbl_employee e ON tdc.employee_ID=e.employee_ID WHERE tdc.consultation_ID='$consultation_ID' AND tdc.diagnosis_type='diagnosis'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_provisional_result)>0){
                                                while($p_diagnosis_rows=mysqli_fetch_assoc($sql_select_provisional_result)){
                                                $disease_code=$p_diagnosis_rows['disease_code'];
                                                $provisional_diagnosis=$p_diagnosis_rows['disease_name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                                $Employee_Name=$p_diagnosis_rows['Employee_Name'];//." <b>( $disease_code ) </b>;&nbsp;&nbsp;&nbsp;";
                                              echo "<tr>
                                                        <td>$count_diadnosis</td>
                                                        <td>$Employee_Name</td>
                                                        <td>$provisional_diagnosis</td>
                                                        <td>$disease_code</td>
                                                    </tr>";
                                              $count_diadnosis++;
                                                }
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Investigation & Results</h3>
                    </div>
                    <div class="col-md-12">
                        <h4>Laboratory:</h4>
                        <h6><b>Comments For Laboratory</b></h6>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th style="width:10px">#</th>
                                        <th style="width:30%">Doctor's Name</th>
                                        <th>Comments For Laboratory</th>
                                    </tr>
                                    <?php 
                                        $count_comment_row=1;
                                        $sql_select_doctors_comments_result=mysqli_query($conn,"SELECT Employee_Name,Comment_For_Laboratory FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_doctors_comments_result)>0){
                                            while($comment_rows=mysqli_fetch_assoc($sql_select_doctors_comments_result)){
                                                $Employee_Name=$comment_rows['Employee_Name'];
                                                $Comment_For_Laboratory=$comment_rows['Comment_For_Laboratory'];
                                                echo "<tr>
                                                            <td>$count_comment_row</td>
                                                            <td>$Employee_Name</td>
                                                            <td>$Comment_For_Laboratory</td>
                                                </tr>";
                                                $count_comment_row++;
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>
                       
                                <b>Laboratory Result</b> 
                                        <!-----------------------------LAB RESULTS---------------------------------------------->
                                        <?php
                                        //lab with results
                                                $qrLab = "SELECT * FROM tbl_item_list_cache 
        INNER JOIN tbl_test_results AS trs ON Payment_Item_Cache_List_ID=payment_item_ID 
		INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
         JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id		
		WHERE tc.Registration_ID='" . $Registration_ID . "' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND tbl_item_list_cache.Check_In_Type='Laboratory'";

        $qrLabWithoutResult = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache 
        INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
        JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
        JOIN tbl_employee e ON e.Employee_ID=tbl_item_list_cache.Consultant_ID
        JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id      
        WHERE  tbl_item_list_cache.Status != 'notsaved' AND tc.Registration_ID='" . $Registration_ID . "' AND tbl_item_list_cache.Status !='notsaved'  AND Billing_Type LIKE '%Outpatient%' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND tbl_item_list_cache.Check_In_Type='Laboratory'";


        $result = mysqli_query($conn,$qrLab) or die(mysqli_error($conn));
        $resultWithout = mysqli_query($conn,$qrLabWithoutResult) or die(mysqli_error($conn));
        $tempIlc = array();
        $temp = 1;
        if (mysqli_num_rows($result) > 0) {
            $data .= "";


            $data1 = '';
            while ($row = mysqli_fetch_array($result)) {
                $tempIlc[] = $row['Payment_Item_Cache_List_ID'];
                $st = '';
                $ppil = $row['Payment_Item_Cache_List_ID'];
                $item_ID = $row['Item_ID'];

                $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));


                if (mysqli_num_rows($RS) > 0) {
                    $rowSt = mysqli_fetch_assoc($RS);


                    $doctorsName = $row['Employee_Name'];
                    $Submitted = $rowSt['Submitted'];
                    $Validated = $rowSt['Validated'];


                    $remarks = $row['remarks'];
                    $validator = $rowSt['ValidatedBy'];
                    $SavedBy = $rowSt['SavedBy'];

                    $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $validator . "'");

                    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);


                    $submitor_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $SavedBy . "'");
                    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);

                    if ($Validated == 'Yes' && $Submitted == 'Yes') {
                        $resultLab = '<span style="color:blue;text-align:center;">Done</span>';
                    } else if (in_array($Validated, array('No', '')) && in_array($Submitted, array('No', ''))) {
                        $resultLab = "No result";
                    } else {
                        $resultLab = '<span style="text-align:center;color: red;">Provisional</span>';
                    }

                    //retrieve attachment info
//                $query = mysqli_query($conn,"select Attachment_Url,Description from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
//                $attach = mysqli_fetch_assoc($query);
//                $image = 'No';
//                $remarks = $attach['Description'];
//                if ($attach['Attachment_Url'] != '') {
//                    $image = "<b>Yes</b>";
//                }

                    if ($resultLab != "No result") {



                        //$getParameters ="SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'"
                        $getParameters = "SELECT tpr.TimeSubmitted as testResult FROM tbl_tests_parameters_results tpr JOIN tbl_test_results tr ON test_result_ID=ref_test_result_ID JOIN tbl_item_list_cache ON Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_employee te ON te.Employee_ID=tsr.specimen_results_Employee_ID JOIN tbl_laboratory_specimen tls ON tls.Specimen_ID=tsr.Specimen_ID WHERE tr.payment_item_ID='" . $row['Payment_Item_Cache_List_ID'] . "'";
                        $number++;
                        $myparameters1 = mysqli_query($conn,$getParameters);
                        $totalParm = mysqli_num_rows($myparameters1);
                        $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $positive = mysqli_num_rows($postvQry);
                        $get_submitor_Name3 = mysqli_fetch_assoc($postvQry);


                        $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $negative = mysqli_num_rows($negveQry);

                        $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $abnormal = mysqli_num_rows($abnormalQry);


                        $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE  ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $normal = mysqli_num_rows($normalQry);
                        //$result= mysqli_fetch_array($normalQry);




                        $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $high = mysqli_num_rows($highQry);

                        $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $low = mysqli_num_rows($lowQry);


                        $resultQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND  ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
                        $no_results = mysqli_num_rows($lowQry);

                        if ($totalParm > 0) {


                            if ($positive == $totalParm) {
                                $resultLab = "positive";
                            } elseif ($negative == $totalParm) {

                                $resultLab = "Negative";
                            } elseif ($no_results == $totalParm) {

                                $resultLab = "Normal";
                            } elseif ($abnormal == $totalParm) {
                                $resultLab = "Abnormal";
                            } elseif ($normal == $totalParm) {
                                $resultLab = $resultNomal['result'];
                            } elseif ($high == $totalParm) {
                                $resultLab = "High";
                            } elseif ($low == $totalParm) {
                                $resultLab = "Low";
                            } else {



                                $resultLab = "No Result";
                                $get_submitor_Name['Employee_Name'] = '';
                                $get_Validator_Name['Employee_Name'] = '';
                            }
                        }
                    }
                } else {


                    $resultLab = 'No result';

                    $get_submitor_Name['Employee_Name'] = '';
                    $get_Validator_Name['Employee_Name'] = '';
                }

                $image = '';
                $remarks = '';
                //if($resultLab != "No result"){   
                $query = mysqli_query($conn,"select Attachment_Url,Description from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
                $attachment_url="";
                while ($attach = mysqli_fetch_array($query)) {

                    if ($attach['Attachment_Url'] != '') {
                        if ($resultLab == "No result" && empty($attach['Description'])) {
                            
                        } else {
                           $Attachment_Url=$attach['Attachment_Url'];
                           $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/attachement.png' width='50' height='50' alt='Not Image File' /></a>&nbsp;&nbsp;";
                            
                                        
                             $attachment_url="<object data='patient_attachments/$Attachment_Url' width='100%' height='1600'>
                                  alt :  $image 
                                </object>";
                                
                        }
                    }

                    if (!empty($attach['Description'])) {
                        $remarks = $attach['Description'];
                    }
                }
                //}

                $srDisp = '';
                if (!empty($resultLab)) {
                    $srDisp = '<td style="text-align:center"><b>Result</b></td>';
                }
$hide_btn="";
                $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$ppil' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)<=0){
                    $hide_btn="class='hide'";
                }else{
                    $hide_btn="class='art-button-green'";
                }
$Product_Name=$row['Product_Name'];
              
?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $row['Product_Name'] ?> <?php echo "<input type='button' $hide_btn  onclick='preview_lab_result(\"$Product_Name\",\"$ppil\")' value='View Result'>";?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor's Ordered:</b> <?= $doctorsName ?>  <b style='font-size:15px'>Performer:</b> <?= $get_submitor_Name['Employee_Name'] ?> <b style='font-size:15px'>Validator:</b> <?= $get_Validator_Name['Employee_Name'] ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor's Notes: </b><?= $row['Doctor_Comment'] ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Lab Remarks: </b><?= $remarks ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Result: </b><?= $resultLab ?> 
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h1>Attachment</h1>
                                                        <div class="col-md-12">
                                                            
                                                            <?= $attachment_url ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    <?php
            }
        } //echo $data;
                                        ?>
                                        <!-------------------------------------------------------------------------------------->
                                        <!--------------   WITHOUT LAB RESULT------------------------------------------------------------------------>
                                        <?php 
                                                    if (mysqli_num_rows($resultWithout) > 0) {

            $data1 = '';
            while ($row = mysqli_fetch_array($resultWithout)) {
                if (!in_array($row['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $st = '';
                    $ppil = $row['Payment_Item_Cache_List_ID'];
                    $item_ID = $row['Item_ID'];

                    $RS = mysqli_query($conn,"SELECT Submitted,Validated,ValidatedBy,SavedBy FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID=''")or die(mysqli_error($conn));
                    $rowSt = mysqli_fetch_assoc($RS);

                    $doctorsName = $row['Employee_Name'];
                    $Submitted = $rowSt['Submitted'];
                    $Validated = $rowSt['Validated'];
                    $remarks = $row['remarks'];
                    $validator = $rowSt['ValidatedBy'];
                    $SavedBy = $rowSt['SavedBy'];

                    $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $validator . "'");
                    $get_Validator_Name = mysqli_fetch_assoc($validator_Name);

                    $submitor_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $SavedBy . "'");
                    $get_submitor_Name = mysqli_fetch_assoc($submitor_Name);

                    if ($Validated == 'Yes') {
                        $st = '<span style="color:blue;text-align:center;">Done</span>';
                    } else {
                        $st = '<span style="text-align:center;color: red;">No Results</span>';
                    }

                    //retrieve attachment info
                    $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
                    $attach = mysqli_fetch_assoc($query);
                    $image = 'No';
                    if ($attach['Attachment_Url'] != '') {
                        $image = "<b>Yes</b>";
                    }
                   
                  

                 ?>
                                  <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $row['Product_Name'] ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor's Ordered:</b> <?= $doctorsName ?> <b style='font-size:15px'>Performer:</b> <?= $get_submitor_Name['Employee_Name'] ?> <b style='font-size:15px'>Validator:</b> <?= $get_Validator_Name['Employee_Name'] ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor's Notes: </b><?= $row['Doctor_Comment'] ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Lab Remarks: </b><?= $remarks ?> 
                                                            </li>
                                                            <li>
                                                                <b style='font-size:15px'>Result: </b><i style="color:red">No Result</i>
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                     <?php

              
                }
            }
           
        }
                                        ?>
                                        <!------------------------------------------------------------------------------------------> 
                            
                    </div>
                    <div class="col-md-12">
                        <h4>Radiology</h4>
                        <h6><b>Comments For Radiology</b></h6>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th style="width:10px">#</th>
                                        <th style="width:30%">Doctor's Name</th>
                                        <th>Comments For Radiology</th>
                                    </tr>
                                    <?php 
                                        $count_comment_row=1;
                                        $sql_select_doctors_comments_result=mysqli_query($conn,"SELECT Employee_Name,Comment_For_Radiology FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_doctors_comments_result)>0){
                                            while($comment_rows=mysqli_fetch_assoc($sql_select_doctors_comments_result)){
                                                $Employee_Name=$comment_rows['Employee_Name'];
                                                $Comment_For_Radiology=$comment_rows['Comment_For_Radiology'];
                                                echo "<tr>
                                                            <td>$count_comment_row</td>
                                                            <td>$Employee_Name</td>
                                                            <td>$Comment_For_Radiology</td>
                                                </tr>";
                                                $count_comment_row++;
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>
                       
                                <b>Radiology Result</b> 
                                <?php 
        $qr = "SELECT rpt.Status,pc.Registration_ID,i.Product_Name,rpt.Remarks,
                          rpt.Date_Time,Radiologist_ID,Sonographer_ID,Patient_Payment_Item_List_ID,ilc.Payment_Item_Cache_List_ID,i.Item_ID,ilc.Transaction_Date_And_Time FROM
			tbl_radiology_patient_tests rpt INNER JOIN tbl_items i
			ON rpt.Item_ID = i.Item_ID 
                        JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
                        JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
			WHERE rpt.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND
                        Billing_Type LIKE '%Outpatient%' ";
        $qrnotdone = "SELECT ilc.Status,ilc.Transaction_Date_And_Time,ilc.Doctor_Comment,pc.Registration_ID,i.Product_Name
                          ,Payment_Item_Cache_List_ID,i.Item_ID FROM
			tbl_item_list_cache ilc 
                        JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
                        INNER JOIN tbl_items i ON ilc.Item_ID = i.Item_ID 
			WHERE   ilc.Status != 'notsaved' AND pc.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND ilc.Check_In_Type='Radiology' AND
                        Billing_Type LIKE '%Outpatient%'  
			
			";
        $select_patients_qry = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        $select_patients_notdone_qry = mysqli_query($conn,$qrnotdone) or die(mysqli_error($conn));

        $sn = 1;
        $tempIlc = array();
        //note done yet
             if (mysqli_num_rows($select_patients_notdone_qry) > 0) {

            while ($patient = mysqli_fetch_assoc($select_patients_notdone_qry)) {

                if (!in_array($patient['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $status = $patient['Status'];
                    $patient_numeber = $patient['Registration_ID'];
                    $test_name = $patient['Product_Name'];
                    $Doctor_Comment = $patient['Doctor_Comment'];
                    $remarks = 'NONE';
                    $Registration_ID = $patient['Registration_ID'];
                    $sent_date = $patient['Transaction_Date_And_Time'];
                    $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                    $Item_ID = $patient['Item_ID'];
                    $comm = '';

                    $st = '<span style="text-align:center;color: red;">Not done</span>';

                    $imaging = '';

                    $view_results = $imaging;

                    //Getting Radiologist Name

                    $Radiologist_Name = 'N/A';

                    //Getting Sonographer Name

                    $Sonographer_Name = 'N/A';

                    //Getting Doctors Comments


                    $style = 'style="text-decoration:none;"';
                }
                 ?>
                                  <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $test_name ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                               <b style='font-size:15px'>Sent Date:</b>  <b style='font-size:15px'>Served Date:</b>  <b style='font-size:15px'>Status:</b> <?= $st ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor Comment: </b><?= $Doctor_Comment ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Radiology Remarks: </b><?= $remarks ?> 
                                                            </li>
                                                            <li>
                                                                <b style='font-size:15px'>Radiologist: </b><?= $Radiologist_Name ?>
                                                            </li>
                                                            <li>
                                                               	<b style='font-size:15px'>Sonographer:</b> <?= $Sonographer_Name ?> 
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                     <?php

            }
           
        }
        ///////with results
        if (mysqli_num_rows($select_patients_qry) > 0) {
            while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
                $status = $patient['Status'];
                $patient_numeber = $patient['Registration_ID'];
                $test_name = $patient['Product_Name'];
                $remarks = '' . $patient['Remarks'] . '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }
                $Registration_ID = $patient['Registration_ID'];
                $sent_date = $patient['Transaction_Date_And_Time'];
                $served_date = $patient['Date_Time'];
                $Radiologist = $patient['Radiologist_ID'];
                $Sonographer = $patient['Sonographer_ID'];
                $Patient_Payment_Item_List_ID = $patient['Payment_Item_Cache_List_ID'];
                $tempIlc[] = $patient['Payment_Item_Cache_List_ID'];
                $Item_ID = $patient['Item_ID'];


                $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

                $ppID = mysqli_fetch_assoc($rs);
                $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
                if (mysqli_num_rows($rs) == 0) {
                    $Patient_Payment_ID = 0;
                }

                if ($status == 'done') {
                    $st = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $st = '<span style="text-align:center;color: red;">' . ucfirst($status) . '</span>';
                }
                $imaging_display="";
                $listtype = '';
                $PatientType = '';
                $Doctor = '';
                $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . "&RI=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=" . $listtype;

                /* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
                $photo = "SELECT * FROM tbl_radiology_image WHERE Registration_ID='$Registration_ID' AND Item_ID = '$Item_ID'";
                $result = mysqli_query($conn,$photo) or die(mysqli_error($conn));
                if (mysqli_num_rows($result) > 0) {
                    $list = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $list++;
                        // extract($row);
                        $Radiology_Image = $row['Radiology_Image'];
                        if (preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $Radiology_Image)) {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank'><img height='20' alt=''  src='RadiologyImage/" . $Radiology_Image . "'  alt=''/></a>";
                        
                            $imaging_display.="<object data='RadiologyImage/$Radiology_Image'  width='100%' height='500'>
                                  alt :  $imaging 
                                </object>";
                        } else {
                            $imaging .= "<a href='RadiologyImage/" . $Radiology_Image . "' title='" . $test_name . "' class='fancyboxRadimg' target='_blank' ><img height='20' alt=''  src='patient_attachments/attachement.png'  alt=''/></a>";
                        
                            $imaging_display.="<object data='RadiologyImage/$Radiology_Image'  width='100%' height='500'>
                                  alt :  $imaging 
                                </object>";
                        }
                    }
                } else {
                    $imaging .= "<b style='text-align: center;color:red'></b>";
                }

                $comm = "<a class='no_color' href='RadiologyTests_Print.php?RI=" . $Registration_ID . "&II=" . $Item_ID . "&PPILI=" . $patient['Payment_Item_Cache_List_ID'] . "' title='Click to view comment added by radiologist' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";

//                              $imaging='<button style="width:74%;" class="art-button-green" onclick="radiologyviewimage(\''.$href.'\',\''.$test_name.'\')">Imaging</button>';
//				$commentsDescription='<button style="width:74%;" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">Report</button>';
                $results_url = "radiologyviewimage_Doctor.php?II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&RI=" . $Registration_ID . "&Doctor=" . $Doctor;

                $view_results = $imaging;

                //Getting Radiologist Name
                $select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
                $select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_radi_qry) > 0) {
                    while ($radist = mysqli_fetch_assoc($select_radi_qry)) {
                        $Radiologist_Name = $radist['Employee_Name'];
                    }
                } else {
                    $Radiologist_Name = 'N/A';
                }

                //Getting Sonographer Name
                $select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
                $select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_sono_qry) > 0) {
                    while ($sonog = mysqli_fetch_assoc($select_sono_qry)) {
                        $Sonographer_Name = $sonog['Employee_Name'];
                    }
                } else {
                    $Sonographer_Name = 'N/A';
                }

                //Getting Doctors Comments
                $select_docomments = "SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List_ID'";
                $select_docomments_qry = mysqli_query($conn,$select_docomments) or die(mysqli_error($conn));

                if (mysqli_num_rows($select_docomments_qry) > 0) {
                    while ($docom = mysqli_fetch_assoc($select_docomments_qry)) {
                        $thecomm = $docom['Doctor_Comment'];
                        if ($thecomm == '') {
                            $newcom = 'NONE';
                        } else {
                            $newcom = $thecomm;
                        }
                        $Doctor_Comment = $newcom;
                    }
                } else {
                    // $Doctor_Comment = "<input type='text' style='color:#000;' disabled='disabled' value='NONE' />";
                    $Doctor_Comment = "NONE";
                }

                $style = 'style="text-decoration:none;"';

                 ?>
                                  <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $test_name ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                               <b style='font-size:15px'>Sent Date:</b> <?= $sent_date ?>  <b style='font-size:15px'>Served Date:</b> <?= $served_date ?> <b style='font-size:15px'>Status:</b> <?= $st ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor Comment: </b><?= $Doctor_Comment ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Radiology Remarks: </b><?= $remarks ?> 
                                                            </li>
                                                            <li>
                                                                <b style='font-size:15px'>Radiologist: </b><?= $Radiologist_Name ?>
                                                            </li>
                                                            <li>
                                                               	<b style='font-size:15px'>Sonographer:</b> <?= $Sonographer_Name ?> 
                                                            </li>
                                                            <li>
                                                               
                                                            </li>
                                                            <li>
                                                               
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>
                                                 <div class="row">                                                        
                                                        <div class="col-md-12">
                                                            <h1>Test Report</h1>
                                                            <?php 
                                                                require "RadiologyTests_report.php";
                                                            ?>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <h1>Attachment</h1>
                                                            <?= $imaging_display ?>
                                                        </div>
                                                 </div>
                                            </div>
                                        </div>
                     <?php
            }
        }

                                ?>
                           
                    </div>
                    <div class="col-md-12">
                         <h6><b>Doctor's Investigation Comments</b></h6>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th style="width:10px">#</th>
                                        <th style="width:30%">Doctor's Name</th>
                                        <th>Doctor's Investigation Comments</th>
                                    </tr>
                                    <?php 
                                        $count_comment_row=1;
                                        $sql_select_doctors_comments_result=mysqli_query($conn,"SELECT Employee_Name,investigation_comments FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_doctors_comments_result)>0){
                                            while($comment_rows=mysqli_fetch_assoc($sql_select_doctors_comments_result)){
                                                $Employee_Name=$comment_rows['Employee_Name'];
                                                $investigation_comments=$comment_rows['investigation_comments'];
                                                echo "<tr>
                                                            <td>$count_comment_row</td>
                                                            <td>$Employee_Name</td>
                                                            <td>$investigation_comments</td>
                                                </tr>";
                                                $count_comment_row++;
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Treatment</h3>
                    </div>
                    <div class="col-md-12">
                        <h4>Surgery</h4>
                         <h6><b>Comments For Surgery</b></h6>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th style="width:10px">#</th>
                                        <th style="width:30%">Doctor's Name</th>
                                        <th>Comments For Surgery</th>
                                    </tr>
                                    <?php 
                                        $count_comment_row=1;
                                        $sql_select_doctors_comments_result=mysqli_query($conn,"SELECT Employee_Name,Comments_For_Surgery FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_doctors_comments_result)>0){
                                            while($comment_rows=mysqli_fetch_assoc($sql_select_doctors_comments_result)){
                                                $Employee_Name=$comment_rows['Employee_Name'];
                                                $Comments_For_Surgery=$comment_rows['Comments_For_Surgery'];
                                                echo "<tr>
                                                            <td>$count_comment_row</td>
                                                            <td>$Employee_Name</td>
                                                            <td>$Comments_For_Surgery</td>
                                                </tr>";
                                                $count_comment_row++;
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>
                       

                                <b>Surgery Result</b>
                                <?php     
        $qr = "SELECT  ilc.Payment_Item_Cache_List_ID,ilc.Status,Product_Name,Doctor_Comment,ilc.Remarks,i.Item_ID,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.Consultant_ID) AS sentby,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.ServedBy) AS servedby,ilc.Transaction_Date_And_Time AS sentOn,ServedDateTime  AS servedOn FROM
                tbl_item_list_cache ilc 
                JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
                JOIN tbl_items i
                ON ilc.Item_ID = i.Item_ID 
                WHERE pc.Registration_ID = '$Registration_ID' AND
                Billing_Type LIKE '%Outpatient%' AND ilc.Status !='notsaved'  AND
                pc.consultation_id ='$consultation_ID' AND ilc.Check_In_Type='Surgery'";
                $select_qr = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_qr) > 0) {
            // die();

            $sn = 1;
            while ($patient = mysqli_fetch_assoc($select_qr)) {
                $test_name = $patient['Product_Name'];
                $Doctor_Comment = $patient['Doctor_Comment'];
                // $data .= $test_name;
                $Consultant = $remarks = '';
                if (empty($patient['Remarks'])) {
                    $remarks = 'NONE';
                }

                $Payment_Item_Cache_List_ID = $patient['Payment_Item_Cache_List_ID'];


                $Item_ID = $patient['Item_ID'];
                $served_date = $patient['Transaction_Date_And_Time'];
                $sentby = $patient['sentby'];
                $sentOn = $patient['sentOn'];
                $servedby = $patient['servedby'];
                $servedOn = $patient['servedOn'];
                $Status = $patient['Status'];

                if (strtolower($Status) == 'served') {
                    $st = '<span style="color:blue;text-align:center;">Done</span>';
                    $comm = "<a class='no_color' href='previewpostoperativereport.php?Registration_ID=$Registration_ID&Payment_Item_Cache_List_ID=$Payment_Item_Cache_List_ID' title='Click to view comment added by Post Operative Notes' target='_blank'><img height='50' alt=''  src='patient_attachments/report.png'  alt='Comments'/></a>";
                } else {
                     $comm ="";
                    $st = '<span style="color:red;text-align:center;">Not done</span>';
                }
                ?>
                     <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $test_name ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                              <b style='font-size:15px'>Ordered Date: </b><?= $sentOn ?>  <b style='font-size:15px'>Served Date:</b> <?= $servedOn ?> <b style='font-size:15px'>Served By:</b> <?= $servedby ?> <b style='font-size:15px'>Status:</b> <?= $st ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor Comment: </b><?= $Doctor_Comment ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Ordered By: </b><?= $sentby ?> 
                                                            </li>
                                                           
                                                            <li>
                                                               <h1>Notes</h1> 
                                                               <div class="col-md-12">
                                                                    <?php 
                                                                     if(!empty($comm)){
                                                                       require "previewpostoperativereportnew.php";  
                                                                     }
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                   
                    <?php
            }   
        }
                                ?>
                            
                    </div>
                    <div class="col-md-12">
                        <h4>Procedure:</h4>
                           <h6><b>Comments For Procedure</b></h6>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr style="background:#E8E8E8;">
                                        <th style="width:10px">#</th>
                                        <th style="width:30%">Doctor's Name</th>
                                        <th>Comments For Procedure</th>
                                    </tr>
                                    <?php 
                                        $count_comment_row=1;
                                        $sql_select_doctors_comments_result=mysqli_query($conn,"SELECT Employee_Name,Comments_For_Procedure FROM tbl_consultation_history ch INNER JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_doctors_comments_result)>0){
                                            while($comment_rows=mysqli_fetch_assoc($sql_select_doctors_comments_result)){
                                                $Employee_Name=$comment_rows['Employee_Name'];
                                                $Comments_For_Procedure=$comment_rows['Comments_For_Procedure'];
                                                echo "<tr>
                                                            <td>$count_comment_row</td>
                                                            <td>$Employee_Name</td>
                                                            <td>$Comments_For_Procedure</td>
                                                </tr>";
                                                $count_comment_row++;
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>
                       
                        
                                <b>Procedure Result</b>
                                <?php 
                                        $qry = "SELECT ilc.Payment_Item_Cache_List_ID,ilc.Status,tit.Product_Name,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin 
                FROM tbl_item_list_cache ilc 
                LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
		JOIN tbl_items tit ON tit.Item_ID=ilc.Item_ID 
		JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
		JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
		WHERE  ilc.Status != 'notsaved'  AND 
                Billing_Type LIKE '%Outpatient%'  AND 
                pc.consultation_id ='$consultation_ID' AND 
                ilc.Check_In_Type='Procedure' 
	
		";


        $rs = mysqli_query($conn,$qry)or die(mysqli_error($conn));

        $sn = 1;

        if (mysqli_num_rows($rs) > 0) {
      
           
            while ($row = mysqli_fetch_assoc($rs)) {
                $test_name = $row['Product_Name'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $remarks = $row['remarks'];
                $Emp_name = $row['Employee_Name'];
                $title = $row['Employee_Type'];
                $served_date = $row['DateTime'];
                $ServedDateTime = $row['ServedDateTime'];
                $ServedBy = $row['ServedBy'];
                $Status = $row['Status'];
                $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];

                $procedure_Status=$Status;
                if (strtolower($Status) == 'served') {
                    $Status = '<span style="color:blue;text-align:center;">Done</span>';
                } else {
                    $Status = '<span style="color:red;text-align:center;">Not done</span>';
                }

                $validator_Name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $ServedBy . "'");
                $employee_served = mysqli_fetch_assoc($validator_Name)['Employee_Name'];

            
                 ?>
                     <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3><?= $test_name ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul>
                                                            <li>
                                                               <b style='font-size:15px'>Status:</b> <?= $Status ?> 
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Ordered By: </b><?= $Emp_name ?>   <b style='font-size:15px'>Performed By:</b><?= $employee_served ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Title:</b> <?= $title ?>
                                                            </li>
                                                            <li>
                                                               <b style='font-size:15px'>Doctor Comment: </b><?= $Doctor_Comment ?>
                                                            </li>
                                                            
                                                            <li>
                                                               	<b style='font-size:15px'>Proc Remarks:</b> <?= $remarks ?> 
                                                            </li>
                                                            
                                                            <li>
                                                               <b style='font-size:15px'>Date:</b> <?= $served_date ?>
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <?php 
                                                        $Registration_ID=$_GET['Registration_ID'];
                                                        
                                                        if($procedure_Status=="served"){
                                                            $select = mysqli_query($conn,"select pos.Surgery_Date,pos.summary_of_assessment_bfr_procedure, pos.Indication_Of_Procedure,pos.Others,pos.Comorbidities,ilc.Doctor_Comment, pos.Management, pos.Recommendations, pos.Medication_Used, pos.Biopsy_Tailen, pos.Git_Post_operative_ID, emp.Employee_Name
									from tbl_git_post_operative_notes pos, tbl_item_list_cache ilc, tbl_items i, tbl_employee emp where
									emp.Employee_ID = pos.Employee_ID and
									pos.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and
									ilc.Item_ID = i.Item_ID and
									ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                                                                $no = mysqli_num_rows($select);
                                                                if($no>0){
                                                        ?>
                                                        <object data='previewuppergitscopereport.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&Payment_Item_Cache_List_ID=<?= $Payment_Item_Cache_List_ID ?>&PreviewUpperGitScopeReport=PreviewUpperGitScopeReportThisPage' width='100%' height='1600'>
                                                           Procedure attachment
                                                        </object>
                                                        <?php 
                                                                }
                                                        
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                   
                    <?php
                
            }

           
        }
       
                                ?>
                           
                    </div>
                    <div class="col-md-12">
                        <h4>Pharmacy</h4>
                        <?php 
                            
        $subqr = "SELECT  ilc.Employee_Created,ilc.Status,Product_Name,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.item_ID = ilc.item_ID
	   JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
	   JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
	   WHERE  ilc.Status != 'notsaved' AND Check_In_Type='Pharmacy' AND 
             Billing_Type LIKE '%Outpatient%'AND 
             pc.Registration_ID='$Registration_ID' AND "
                . "pc.consultation_id ='$consultation_ID'";


        $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));



        $sn = 1;

        if (mysqli_num_rows($result) > 0) {
          
            $data .= '<table width="100%"class="table table-striped table-bordered" style="margin-left:2px">';
            $data .= '<tr style="font-weight:bold;" id="thead">';
            $data .= '<td style="width:3%;">SN</td>';
            $data .= '<td style="text-align:left">Medication Name</td>';
            $data .= (empty($display)) ? '<td><b>Status</b></td>' : '';
            $data .= '<td style="text-align:center">Qty Ordered</td>';
            $data .= '<td style="text-align:center">Qty Issued</td>';
            $data .= '<td style="text-align:left">Dosage</td>';
            $data .= '<td style="text-align:left">Dispensor</td>';
            $data .= '<td style="text-align:left">Date Dispensed</td>';
            $data .= '<td style="text-align:left">Ordered By</td>';
            $data .= '<td style="text-align:left">Date Ordered</td>';
            $data .= '</tr>';

            while ($row = mysqli_fetch_assoc($result)) {
                $qr = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $row['Dispensor'] . "'")or die(mysqli_error($conn));
                $Disponsor = mysqli_fetch_assoc($qr)['Employee_Name'];

                //get employee ordered
                $Employee_Created = $row['Employee_Created'];
                if ($Employee_Created != null) {
                    $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_Created'") or die(mysqli_error($conn));
                    $Employee_Created = mysqli_fetch_assoc($slct)['Employee_Name'];
                } else {
                    $Employee_Created = $row['Employee_Name'];
                }

                $Pharmacy = $row['Product_Name'];
                $orderedQty = $row['Quantity'];
                $disepnsedQty = $row['Edited_Quantity'];
                $Employee_Name = $row['Employee_Name'];
                $status = $row['Status'];
                $Doctor_Comment = $row['Doctor_Comment'];
                $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                $Status = $row['Status'];
                $Dispense_Date_Time = $row['Dispense_Date_Time'];
                $qty = 0;

                if ($disepnsedQty > 0) {
                    $qty = $disepnsedQty;
                } else {
                    $qty = $orderedQty;
                }

                if (strtolower($Status) == 'dispensed') {
                    $Status = '<span style="color:blue;text-align:center;font-size: 14px;">Dispensed</span>';
                } else {
                    if (strtolower($Status) == 'removed') {
                        $Status = '<span style="color:red;text-align:center;">Removed</span>';
                    } else {
                        $Status = '<span style="color:red;text-align:center;">Not given</span>';
                    }
                }

                $data .= '<tr>';
                $data .= '<td style="width:3%;">' . $sn . '</td>';
                $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
                $data .= (empty($display)) ? '<td>' . $Status . '</td>' : '';
                $data .= '<td style="text-align:center">' . $orderedQty . '</td>';
                $data .= '<td style="text-align:center">' . $qty . '</td>';
                $data .= '<td style="text-align:left">' . $Doctor_Comment . '</td>';
                $data .= '<td style="text-align:left">' . $Disponsor . '</td>';
                $data .= '<td style="text-align:left">' . $Dispense_Date_Time . '</td>';
                $data .= '<td style="text-align:left">' . ucwords(strtolower($Employee_Created)) . '</td>';
                $data .= '<td style="text-align:left">' . $Transaction_Date_And_Time . '</td>';
                $data .= '</tr>';

                $sn++;
            }

            $data .= '</table>';
        }
           echo $data;             ?>
                    </div>
                </div>
            </div>
            </div>
            
            <div class="row" >
            <br><br>
                <?php 
                    include 'newpatientfile_summary_iframe.php';
                    echo displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);
                ?>
            </div>
        </div>
    </div>
</div>

<?php
}else{
    ?>
        

<div class="row">
    <div class="col-md-12" style="text-align: center">
            <div class="pagination">
                
        <?php
       
     $select_patients = "SELECT ppl.Patient_Payment_Item_List_ID, ppl.Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c, tbl_patient_payment_item_list ppl WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
			    c.Registration_ID = '$Registration_ID' 
               
                       UNION 
                       
                     SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c WHERE
			    c.Patient_Payment_Item_List_ID IS NULL and
			    c.Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1
              
     ";
        
        
        $result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($result);

        
         
        $resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $pat = mysqli_fetch_array($resultPat);
        $patientName = $pat['Patient_Name'];
        $count=1;
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $href = "consultation_ID=" . $row['consultation_ID'];
            $Consultation_Date_And_Time=$row['Consultation_Date_And_Time'];
            if($Consultation_Date_And_Time==$_GET['Consultation_Date_And_Time']){
                $active_class="active";
            }else{
                $active_class="";
            }
            if (isset($_GET['doctorsworkpage'])) {
                $href.='doctorsworkpage=yes';
            }
            $href .="&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "";

            $Review = $ViewAttachments = $Attach = '';

            if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
                $Review = "<a href='newpateientfile_summary.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                 $Consultation_Date_And_Time
		</a>";

                
 
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile &this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";

                
                 

            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
 
                

             } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
                $Review = "<a href='newpateientfile_summary.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile &this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                        $Consultation_Date_And_Time
		</a>";

               
            } else {
                if (isset($_GET['section']) && strtolower($_GET['section']) == 'patient') {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time' href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                         $count
		    </a>";
 
                } else {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time'href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                             $Consultation_Date_And_Time
		    </a>";

                  
                }
            }
            ?>
                <li class="<?= $active_class ?>">
                            
                <?php
                      echo $Review;
                      header("Location:newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=<?= $this_page_from ?>&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted");
                ?>
            </li>
           
            <?php
            $i++;
            $count++;
        }
        ?> 
   </div>
</div>
</div>
<?php 
}


?>

<!----------------------------------------------------------------------------------------------------------------------->
<!-------------------------------PATIENT FILE NUMBER OPTION FOR BOTTOM OF THE PAGE------------------------------------------>

<div class="row">
    <div class="col-md-12" style="text-align: center">
            <div class="pagination">
                
        <?php
       
     $select_patients = "SELECT ppl.Patient_Payment_Item_List_ID, ppl.Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c, tbl_patient_payment_item_list ppl WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
			    c.Registration_ID = '$Registration_ID' 
               
                       UNION 
                       
                     SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c WHERE
			    c.Patient_Payment_Item_List_ID IS NULL and
			    c.Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 20
              
     ";
        
        
        $result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($result);

        
         
        $resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $pat = mysqli_fetch_array($resultPat);
        $patientName = $pat['Patient_Name'];
        $count=1;
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $href = "consultation_ID=" . $row['consultation_ID'];
            $Consultation_Date_And_Time=$row['Consultation_Date_And_Time'];
            if($Consultation_Date_And_Time==$_GET['Consultation_Date_And_Time']){
                $active_class="active";
            }else{
                $active_class="";
            }
            if (isset($_GET['doctorsworkpage'])) {
                $href.='doctorsworkpage=yes';
            }
            $href .="&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "";

            $Review = $ViewAttachments = $Attach = '';

            if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
                $Review = "<a href='newpateientfile_summary.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                 $Consultation_Date_And_Time
		</a>";

                
 
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";

                
                 

            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
                $Review = "<a href='newpateientfile_summary.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                    $Consultation_Date_And_Time
		</a>";
 
                

             } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
                $Review = "<a href='newpateientfile_summary.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                        $Consultation_Date_And_Time
		</a>";

               
            } else {
                if (isset($_GET['section']) && strtolower($_GET['section']) == 'patient') {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time' href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                         $count
		    </a>";
 
                } else {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a title='$Consultation_Date_And_Time'href='newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&previous_notes=$previous_notes&from_consulted=$from_consulted' target='_parent' style='text-decoration: none'>
                             $Consultation_Date_And_Time
		    </a>";

                  
                }
            }
            ?>
                <li class="<?= $active_class ?>">
                <?php
                      echo $Review;
                      //header("Location:newpateientfile_summary.php?Consultation_Date_And_Time=$Consultation_Date_And_Time&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position");
                ?>
            </li>
           
            <?php
            $i++;
            $count++;
        }
        ?> 
   </div>
</div>
</div>

<!----------------------------------------------------------------------------------------------------------------------->
<script>
 function preview_lab_result(Product_Name,Payment_Item_Cache_List_ID){
    window.open("preview_ntergrated_lab_result.php?Product_Name="+Product_Name+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID,"_blank");
 }
</script>
        <?php

include("./includes/footer.php");
?>