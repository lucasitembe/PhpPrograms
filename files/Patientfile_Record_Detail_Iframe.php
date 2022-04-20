<link rel="stylesheet" href="tablew.css" media="screen">
<style>
    button{
        color:#FFFFFF!important;
        height:27px!important;
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
$can_edit_claim_bill = $_SESSION['userinfo']['can_edit_claim_bill'];
//Find the current date to filter check in list     
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//$Patient_Payment_ID=$row['Patient_Payment_ID'];
//$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
<center>
    <table class="table table-hover">
        <tr id='thead' style="background:#E8E8E8;">
        <!--<tr><td colspan="9"><hr></td></tr>-->
        <td style="width:5%;"><center><b>SN</b></center></td>
        <td><center><b>DATE</b></center></td>
        <td><center><b>CLINIC</b></center></td>
        <td><center><b>DOCTORS REVIEW</b></center></td>
        <td><center><b>ATTACHMENT</b></center></td>
        <td><center><b>RESULTS/ VITALS</b></center></td>
        <td><center><b>TEMPLATES/MODULES</b></center></td>
        <!--<tr><td colspan="9"><hr></td></tr>-->
        </tr>
        <?php
       
     $select_patients = "SELECT Clinic_ID, pr.Patient_Name, c.Registration_ID, Consultation_Date_And_Time, consultation_ID, Patient_Payment_Item_List_ID FROM tbl_consultation c, tbl_patient_registration pr WHERE pr.Registration_ID = c.Registration_ID AND c.Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 100";
        
        
        $result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($result);

        
         
        // $resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        // $pat = mysqli_fetch_array($resultPat);
        // $patientName = $pat['Patient_Name'];

        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $Patient_Payment_ID=$row['Patient_Payment_ID'];       
            $consultation_ID=$row['consultation_ID'];      
            $Registration_ID=$row['Registration_ID'];      
            $Check_In_ID = $row['Check_InID'];
            $patientName = $row['Patient_Name'];

            $consultationDate = $row['Consultation_Date_And_Time'];
            $href = "consultation_ID=" . $row['consultation_ID']."&Check_In_ID=".$Check_In_ID;
            if (isset($_GET['doctorsworkpage'])) {
                $href.='doctorsworkpage=yes';
            }
            $vital = "<a target='_blank' class='art-button-green' href='nurseform.php?Registration_ID=" . $row['Registration_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";
            $href .="&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "";

            $Review = $ViewAttachments = $Attach = '';

            if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
                $Review = "<a href='Patient_Record_Review.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	         <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		</a>";

                $vital = "<a target='' class='art-button-green' href='nurseform.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";

                $ViewAttachments = "<a href='Patient_Attachment_Detail.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position'&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		</a>";

                $Attach = "<a href='File_Attach.php?Section=Doctor&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto height: 100%;display:inline'>Attach</button>
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
                $Review = "<a href='Patient_Record_Review.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	       <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		</a>";

                $vital = "<a target='' class='art-button-green' href='nurseform.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";

                $ViewAttachments = "<a href='Patient_Attachment_Detail.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		</a>";

                $Attach = "<a href='File_Attach.php?Section=DoctorLab&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
                $Review = "<a href='Patient_Record_Review.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		</a>";

                $vital = "<a target='' class='art-button-green' href='nurseform.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";

                $ViewAttachments = "<a href='Patient_Attachment_Detail.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		</a>";

                $Attach = "<a href='File_Attach.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
                $Review = "<a href='Patient_Record_Review.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		</a>";

                $vital = "<a target='' class='art-button-green' href='nurseform.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";

                $ViewAttachments = "<a href='Patient_Attachment_Detail.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		</a>";

                $Attach = "<a href='File_Attach.php?Section=DoctorsPerformancePateintSummary&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		</a>";
            } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
                $Review = "<a href='Patient_Record_Review.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		</a>";

                $vital = "<a target='' class='art-button-green' href='nurseform.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";

                $ViewAttachments = "<a href='Patient_Attachment_Detail.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		</a>";

                $Attach = "<a href='File_Attach.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage $fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>
	    <button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		</a>";
            } else {
                if (isset($_GET['section']) && strtolower($_GET['section']) == 'patient') {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a href='Patient_Record_Review.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . " $fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
		            <button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		            </a>";
            
                $vital = "<a target='' class='art-button-green' href='nurseform.php?section=Patient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";
            
                    $Patient_Payment_Item_List_ID=$row['Patient_Payment_Item_List_ID'];
                    $Registration_ID=$row['Registration_ID'];
                    $vital ="<a target='_blank' class='art-button-green' href='nurseregistration.php?Registration_ID=$Registration_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&from=patienFileRecord'>VITAL SIGNS</a>";
                
                    $ViewAttachments = "<a href='Patient_Attachment_Detail.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		    </a>";

                    $Attach = "<a href='File_Attach.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		    </a>";
                } else {
                    //echo $row['Patient_Payment_ID']; exit;
                    $Review = "<a href='Patient_Record_Review.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from&tarehe=$consultationDate' target='_parent' style='text-decoration: none'>
		<button class='art-button-green' style='width: 40%; height: 100%'>Review</button>
		    </a>";

                    // $vital = "<a target='' class='art-button-green' href='mergenhifreceipt.php?Section=DoctorRad&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&Nurse_DateTime=" . $row['Consultation_Date_And_Time'] . "&NurseWorks=NurseWorksThisPage $fromPatientFile $position' target='_parent' style='text-decoration: none;'>VITAL SIGNS</a>";
                    
                    $Patient_Payment_Item_List_ID=$row['Patient_Payment_Item_List_ID'];
                    $Registration_ID=$row['Registration_ID'];
                     $vital ="<a target='_blank' class='art-button-green' href='nurseregistration.php?Registration_ID=$Registration_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&from=patienFileRecord'>VITAL SIGNS</a>";
                     
                     
                    $ViewAttachments = "<a href='Patient_Attachment_Detail.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>View Attachments</button>
		    </a>";

                    $Attach = "<a href='File_Attach.php?consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "$fromPatientFile $position&this_page_from=$this_page_from' target='_parent' style='text-decoration: none;'>
		<button class='art-button-green' style='width: auto; height: 100%;display:inline'>Attach</button>
		    </a>";
            if($can_edit_claim_bill=='yes'){
                $Attach  .="<a target='_blank' class='art-button-green' href='mergenhifreceipt.php?$href' target='_parent' style='text-decoration: none;'>FILE</a>";

            }
                }
            }
            ?>
            <tr>
                <td id="thead"><center><?php echo $i . ". "; ?></center></td>
            <td><center><?php echo $row['Consultation_Date_And_Time']; ?></center></td>
            <td>
            <center>
                <?php
                $Clinic_ID = $row['Clinic_ID'];
                $select_CLINIc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'"))['Clinic_Name'];

                echo $select_CLINIc; 
              
                ?>
                </a>
            </center>
            </td>
            <td>
            <center>
                <?php
                echo $Review;
                ?>
                </a>
            </center>
            </td>
            
            <td>
            <center>
                <?php
               // echo $ViewAttachments;

                echo $Attach;
                ?>

            </center>
            </td>
            <td>
            <center>
                <?php echo ' <button class="art-button-green" onclick="parentResult(\'' . $href . '\',\'' . $patientName . '\',\'' . $row['Consultation_Date_And_Time'] . '\',\'' . $Registration_ID . '\')">RESULTS</button>';
                echo $vital; 

                $Select_Overstay = mysqli_query($conn, "SELECT Admision_ID, Overstay_Form_ID, Check_In_ID FROM tbl_inpatient_overstaying WHERE consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($Select_Overstay) > 0){
                    while($over = mysqli_fetch_assoc($Select_Overstay)){
                        $Admision_ID = $over['Admision_ID'];
                        $Overstay_Form_ID = $over['Overstay_Form_ID'];
                        $Check_In_ID = $over['Check_In_ID'];

                        echo "<a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' class= 'art-button' target='_blank' style='text-decoration: none; color: white;'>OVERSTAY FORM</a>";
                    }
                }
 //@mfoy dn
                ?>
                </center>
            </td>
            <td>
                <?php 
            echo "<center><a href='othermodules.php?Registration_ID=".$Registration_ID."&consultation_ID=".$consultation_ID."&Payment_Item_Cache_List_ID=52943&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Admision_ID=".$Admision_ID."&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage' class='art-button-green' target='_blank'>OTHER MODULES</a></center>";
?>

            </td>
            </tr>
            
            <?php
            $i++;
        }
        ?>
    </table>
</center>
