<?php echo '
';
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
header("Location: ./index.php?InvalidPrivilege=yes");
}
}else {
header("Location: ./index.php?InvalidPrivilege=yes");
}
}else {
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}


/***************************  starting Payment id refinement         ********************/

$check_curr_date = mysqli_query($conn,"SELECT Transaction_Date_And_Time, NOW() AS curr_date from tbl_patient_payment_item_list  WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID");

$check_curr_date_data = mysqli_fetch_assoc($check_curr_date);
$trans_date = strtotime($check_curr_date_data['Transaction_Date_And_Time']);
$curr_date = strtotime($check_curr_date_data['curr_date']);
$hour_diff = floor(abs($curr_date - $trans_date)/(24*60*60));
// die($trans_date.' and '.$curr_date.' and '.$hour_diff);


if($hour_diff > 0){
    //die("SELECT ppl.Patient_Payment_Item_List_ID, ci.Check_In_ID, visit_date FROM tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pp.Check_In_ID = ci.Check_In_ID AND ci.Registration_ID = ".$_GET['Registration_ID']." AND ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND ppl.Patient_Direction IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') AND ppl.Process_Status = 'not served' GROUP by ci.Check_In_ID DESC LIMIT 1");


    $New_Details = mysqli_query($conn,"SELECT ppl.Patient_Payment_Item_List_ID, ci.Check_In_ID, visit_date FROM tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pp.Check_In_ID = ci.Check_In_ID AND ci.Registration_ID = ".$_GET['Registration_ID']." AND ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND ppl.Patient_Direction IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') AND ppl.Process_Status = 'not served' AND (TIMESTAMPDIFF( HOUR,`Check_In_Date_And_Time`,NOW())) < 24 GROUP by ci.Check_In_ID ");
    if(mysqli_num_rows($New_Details) > 0){
       error_log(''.$Patient_Payment_Item_List_ID.',  ',3,"/var/www/html/ehms/cons.log");

        $test_consult = mysqli_query($conn,"SELECT *  FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID AND Process_Status IN( 'served','pending') AND (TIMESTAMPDIFF( HOUR,`Consultation_Date_And_Time`,NOW())) < 24 ");
        if(mysqli_num_rows($test_consult) > 0){
            error_log(''.$Patient_Payment_Item_List_ID.',  ',3,"/var/www/html/ehms/cons1.log");
			$check_pending = [];
			$check_consulted = [];
			while($row = mysqli_fetch_assoc($test_consult)){
				if($row['Process_Status'] == 'served'){
					array_push($check_consulted, $row['Patient_Payment_Item_List_ID']);
				}else if($row['Process_Status'] == 'pending'){
					array_push($check_pending, $row['Patient_Payment_Item_List_ID']);
					if($Patient_Payment_Item_List_ID < $row['Patient_Payment_Item_List_ID']){
						$Patient_Payment_Item_List_ID = $row['Patient_Payment_Item_List_ID'];
					  }
				}

			}


			if(sizeof($check_consulted) < 1 && sizeof($check_pending) > 0){
				//$Patient_Payment_Item_List_ID = $check_pending[0];
                error_log(''.$Patient_Payment_Item_List_ID.',  ',3,"/var/www/html/ehms/cons2.log");
			}
            // $Patient_Payment_Item_List_ID = mysqli_fetch_assoc($test_consult)['Patient_Payment_Item_List_ID'];
            // $_GET['Patient_Payment_Item_List_ID'] = $Patient_Payment_Item_List_ID;
        }else{
        error_log(''.$Patient_Payment_Item_List_ID.',  ',3,"/var/www/html/ehms/cons3.log");
        $Patient_Payment_Item_List_ID = mysqli_fetch_assoc($New_Details)['Patient_Payment_Item_List_ID'];
        $_GET['Patient_Payment_Item_List_ID'] = $Patient_Payment_Item_List_ID;

		$test_consult = mysqli_query($conn,"SELECT *  FROM tbl_consultation WHERE Registration_ID  = ".$_GET['Registration_ID']."  AND Process_Status IN( 'served','pending') AND (TIMESTAMPDIFF( HOUR,`Consultation_Date_And_Time`,NOW())) < 24 ORDER BY `consultation_ID` DESC LIMIT 1");
		if(mysqli_num_rows($test_consult) > 0){
		$_GET['Patient_Payment_Item_List_ID'] = mysqli_fetch_assoc($test_consult)['Patient_Payment_Item_List_ID'];
		}
        }
     //die($Patient_Payment_Item_List_ID.' and '.$_GET['Patient_Payment_Item_List_ID']);
    }
}

/***************************  end of Payment id refinement         ********************/

;echo '<!--START HERE-->
';
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
$original_Date = $row['today'];
$new_Date = date("Y-m-d",strtotime($original_Date));
$Today = $new_Date;
}
if (isset($_GET['Registration_ID'])) {
$Registration_ID = $_GET['Registration_ID'];
$select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,Registration_Date,Patient_Picture,
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
if ($no >0) {
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
}
$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y ." Years, ".$diff->m ." Months, ".$diff->d ." Days";
}else {
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
}else {
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
$sql_check_if_patient_sponsor_configured_result=mysqli_query($conn,"SELECT *FROM tbl_consultation_items_configuration WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_if_patient_sponsor_configured_result)>0){
$Employee_ID_login= $_SESSION['userinfo']['Employee_ID'];
$sql_select_employee_kada_result=mysqli_query($conn,"SELECT kada FROM tbl_employee WHERE Employee_ID='$Employee_ID_login'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_employee_kada_result)>0){
$employee_kada_row=mysqli_fetch_assoc($sql_select_employee_kada_result);
$kada=$employee_kada_row['kada'];
$select_configured_consultation_item="SELECT tcic.Item_ID,Items_Price FROM tbl_consultation_items_configuration tcic INNER JOIN tbl_item_price tip ON tcic.Item_ID=tip.Item_ID WHERE kada='$kada' AND tip.Sponsor_ID='$Sponsor_ID'";
$select_configured_consultation_item_result=mysqli_query($conn,$select_configured_consultation_item) or die(mysqli_error($conn));
if(mysqli_num_rows($select_configured_consultation_item_result)){
$price_item_row=mysqli_fetch_assoc($select_configured_consultation_item_result);
$Items_Price=$price_item_row['Items_Price'];
$Item_ID=$price_item_row['Item_ID'];
$Update= mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Price='$Items_Price',Item_ID='$Item_ID' WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND Patient_Payment_Item_List_ID IN (SELECT Patient_Payment_Item_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID') AND Transaction_Type='Credit'");
}
}
}
;echo '<style>
button{
height:27px!important;
color: #FFFFFF!important;
}

</style>
';
if (isset($_SESSION['userinfo'])) {
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
;echo '        <!--Script to display patient optional photo-->
        <!--PATIENT PHOTO SCRIPT START-->

        <script>
            function displayPatientPhoto() {
                document.getElementById("photo").onclick = function () {
                    if (document.getElementById("photo").checked) {
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
                var url = "';
if ($Registration_ID != '') {
echo "Registration_ID=$Registration_ID&";
}
if (isset($_GET['Patient_Payment_ID'])) {
echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID'] ."&";
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID'] ."&";
}
;echo 'SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById("patientlist").value;

                if (patientlist == "MY PATIENT LIST") {
                    document.location = "doctorcurrentpatientlist.php?" + url;
                } else if (patientlist == "CLINIC PATIENT LIST") {
                    document.location = "clinicpatientlist.php?" + url;
                } else if (patientlist == "CONSULTED PATIENT LIST") {
                    document.location = "doctorconsultedpatientlist.php?" + url;
                } else if (patientlist == "FROM NURSE STATION") {
                    document.location = "patientfromnursestation.php?NurseStationPatientList=NurseStationPatientListThisPage" + url;
                } else if (patientlist == "OPD PATIENT LIST") {
                    document.location = "doctorOpdpatientlist.php?" + url;
                } else {
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

        <label style="border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF" class="btn-default">
            <select id="patientlist" name="patientlist">
                <!--	<option></option>-->
                <option >
                    MY PATIENT LIST
                </option>
                <option>
                    CLINIC PATIENT LIST
                </option>
                <option>
                    CONSULTED PATIENT LIST
                </option>

                <!-- <option>
                    OPD PATIENT LIST
                </option>-->

                <option>
                    FROM NURSE STATION
                </option>
            </select>
            <input type="button" value="VIEW" onclick="gotolink()">
        </label>

        ';if (isset($_GET['Registration_ID'])) {;echo '        <a href="Patientfile_Record_Detail.php?Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo $_GET['Patient_Payment_Item_List_ID'];;echo '&PatientFile=PatientFileThisForm&position=out" class="art-button" style="display:none"  target="_blank">PATIENT FILE</a>
                <!--<input type="button" name="patient_file" id="patient_file" value="PATIENT FILE" onclick="Show_Patient_File()" class="art-button" />-->
            <input type="button" name="patient_file" id="patient_file"style="display:none" value="SUMMARY PATIENT FILE" onclick="showSummeryPatientFile()" class="art-button" />

                                                                                                                                                                 <!--<a href="Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo $_GET['Patient_Payment_Item_List_ID'];;echo '&PatientFile=PatientFileThisForm" class="art-button">PATIENT FILE</a>-->
        ';}
;echo '        <!--<a href="Patientfile_Record.php?section=Patient&DialysisWorks=DialysisWorksThisPage" class="art-button=green">PATIENT FILE</a>-->
        ';
;echo '
        <a href="patientsignoff.php?';
if ($Registration_ID != '') {
echo "Registration_ID=$Registration_ID&";
}
if (isset($_GET['Patient_Payment_ID'])) {
echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID'] ."&";
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID'] ."&";
}
;echo 'SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" class="art-button">
            SIGN OFF
        </a>


        ';
}
}
;echo '
<a href="checkinpatientslist.php?Section=Doctor&CheckInPatient=CheckInPatientThisPage" class="art-button">CHECKED-IN</a>

<!--begin: MOVED DOWN TO LINE 640
';if (isset($_GET['Registration_ID'])) {;echo '                                        <input type="button" name="patient_transfer_referral" id="patient_transfer_referral" value="REFERRAL" onclick="showReferralForm(';echo $Registration_ID;;echo ',';echo $_GET['Patient_Payment_ID'];;echo ',';echo $_GET['Patient_Payment_Item_List_ID'];;echo ')" class="art-button" />
';};echo 'end-->
<hr>
';if (isset($_GET['Registration_ID'])) {;echo '    <input type="button" name="patientFileByFolio" id="patientFileByFolio" value="PATIENT FILE BY FOLIO" onclick="patientFileByFolio(98)" class="art-button" />

                                                                                                                         <!--<a href="Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo $_GET['Patient_Payment_Item_List_ID'];;echo '&PatientFile=PatientFileThisForm" class="art-button">PATIENT FILE</a>-->
    ';
$Registration_ID=$_GET['Registration_ID'];
$sql_select_cons_id="SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1";
$sql_select_cons_id_result=mysqli_query($conn,$sql_select_cons_id) or die(mysqli_error($conn));
$rows_cons=mysqli_fetch_assoc($sql_select_cons_id_result);
$consultation_ID=$rows_cons['consultation_ID'];
}
$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
$Registration_ID=$_GET['Registration_ID'];
;echo '<a href="checkedinpatientslist.php?Section=Section=Reception&CheckInPatient=CheckInPatientThisPage" class="art-button-green">
  PATIENT DIRECTION
</a>
<a href="all_patient_file_link_station.php?Registration_ID=';echo  $Registration_ID ;echo '&Patient_Payment_ID=';echo  $Patient_Payment_ID ;echo '&Patient_Payment_Item_List_ID=';echo  $Patient_Payment_Item_List_ID ;echo '&this_page_from=doctor_outpatient" class="art-button">PATIENT FILE</a>
    ';
if (isset($_SESSION['userinfo'])) {
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
;echo '    <a  target="_blank"  href="nursecommunicationpage.php?Registration_ID=';echo $Registration_ID;;echo '&consultation_ID=';echo  $consultation_ID;;echo '&Patient_Payment_Item_List_ID=';echo $Patient_Payment_Item_List_ID;;echo '"     class="art-button" >
            VITAL SIGNS
    </a>
    <a href="#" onclick="Show_Preview(';echo $Registration_ID;;echo ')" class="art-button"/>
            RECENT VITAL SIGNS
    </a>
    ';if (isset($_GET['Patient_Payment_Item_List_ID'])) {;echo '      <a style="display:none" href="newpateientfile_summary.php?Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo  $Patient_Payment_Item_List_ID ;echo '&PatientFile=PatientFileThisForm&position=in" class="art-button">COMPREHENSIVE PATIENT FILE</a>
    ';};echo '    <a href="./doctorsworkspage.php?DoctorsWorksPage=DoctorsWorksThisPage" class="art-button">
            BACK
        </a>
        ';
}
}
;echo '
      ';
$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
$Registration_ID=$_GET['Registration_ID'];
;echo '
<br/><br/>
<!-- get employee id-->
';
if (isset($_SESSION['userinfo']['Employee_ID'])) {
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
;echo '
<!-- get id, date, Billing Type,Folio number and type of chech in -->
';
if (isset($_GET['Patient_Payment_ID'])) {
$Patient_Payment_ID2 = $_GET['Patient_Payment_ID'];
}else {
$Patient_Payment_ID2 = 0;
}
if (isset($_GET['Registration_ID']) &&isset($_GET['Patient_Payment_ID'])) {
$qr = "select Patient_Payment_ID,Payment_Date_And_Time,Folio_Number,Claim_Form_Number,Billing_Type from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = '$Patient_Payment_ID2'
					    and pp.registration_id = '$Registration_ID'";
$sql_Select_Current_Patient2 = mysqli_query($conn,$qr);
$row = mysqli_fetch_array($sql_Select_Current_Patient2);
$Patient_Payment_ID = $row['Patient_Payment_ID'];
$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
$Folio_Number = $row['Folio_Number'];
$Claim_Form_Number = $row['Claim_Form_Number'];
$Billing_Type = $row['Billing_Type'];
}else {
$Patient_Payment_ID = '';
$Payment_Date_And_Time = '';
$Folio_Number = '';
$Claim_Form_Number = '';
$Billing_Type = '';
}
;echo '<!--Getting employee name -->
';
if (isset($_SESSION['userinfo']['Employee_Name'])) {
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else {
$Employee_Name = 'Unknown Employee';
}
;echo '
<script type="text/javascript">
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

</style>

<fieldset>
    <center style="background: #037CB0;color: white;">
         ';
$Clinic_Name='';
$doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
$Select_Consultant = "select Clinic_Name from tbl_clinic where Clinic_ID='$doctors_selected_clinic'";
$result = mysqli_query($conn,$Select_Consultant);
while ($row = mysqli_fetch_array($result)) {
$Clinic_Name=$row['Clinic_Name'];
}
;echo '        <b>DOCTORS WORKPAGE OUTPATIENT<b style="font-size: 17px">~~~';echo $Clinic_Name;;echo '</b><br>&nbsp;</b>
    </center>
    <br/>
    <center>
        <table width=100%>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="16%" style="text-align: right">Patient Name</td>
                            <td width="26%"><input type="text" name="Patient_Name" disabled="disabled" id="Patient_Name" value="';
if (isset($Patient_Name)) {
echo ucwords(strtolower($Patient_Name));
}
;echo '"></td>
                        </tr>
                        <tr>
                            <td width="13%" style="text-align: right">Card Id Expire Date</td>
                            <td width="16%"><input type="text" name="Card_ID_Expire_Date" disabled="disabled" id="Card_ID_Expire_Date" value="';echo $Member_Card_Expire_Date;;echo '"></td>
                        </tr>
                        <tr>
                            <td width="13%" style="text-align: right">D.O.B</td>
                            <td width="16%"><input type="text" name="Date_Of_Birth" id="Date_Of_Birth" value="';echo $Date_Of_Birth;;echo '" disabled="disabled"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Phone Number</td>
                            <td><input type="text" name="Phone_Number" id="Phone_Number" disabled="disabled" value="';echo $Phone_Number;;echo '"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Region</td>
                            <td>
                                <input type="text" name="Region" id="Region" disabled="disabled" value="';echo $Region;;echo '">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Receipt No</td>
                            <td>
                                <input type="text" name="Patient_Payment_ID" id="Patient_Payment_ID" disabled="disabled" value="';echo $Patient_Payment_ID;;echo '">
                            </td>

                        </tr>
                    </table>
                </td>

                <td>
                    <table width="100%">
                        <tr>
                            <td style="text-align: right">Sponsor Name</td>
                            <td><input type="text" name="Guarantor_Name" disabled="disabled" id="Guarantor_Name" value="';echo $Guarantor_Name;;echo '"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Member Number</td>
                            <td><input type="text" name="Supervised_By" id="Supervised_By" disabled="disabled" value="';echo $Member_Number;;echo '"></td>
                        </tr>
                        <tr>
                        <input type="hidden" id="Admission_Employee_ID" name="Admission_Employee_ID" value="';echo $Employee_ID;;echo '">
                        <td style="text-align: right">Folio Number</td>
                        <td><input type="text" disabled="disabled" value="';echo $Folio_Number;;echo '">
                            <input type="hidden" name="Folio_Number" id="Folio_Number" value="';echo $Folio_Number;;echo '">
                        </td>
            </tr>
            <tr>
                <td style="text-align: right">Registration Number</td>
                <td><input type="text" disabled="disabled" value="';echo $Registration_ID;;echo '">
                    <input type="hidden" name="Registration_ID" id="Registration_ID"value="';echo $Registration_ID;;echo '">
                </td>
            </tr>
            <tr>
                <td style="text-align: right">Ward</td>
                <td><input type="text" name="Prepared_By" id="Prepared_By" disabled="disabled" value="';echo $Ward;;echo '"></td>


            </tr>
            <tr><td style="text-align: right">Registered Date</td>
                <td><input type="text" name="Prepared_By" id="Prepared_By" disabled="disabled" value="';echo $Registration_Date;;echo '"></td>
            </tr>
        </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style="text-align: right">Gender</td>
                    <td><input type="text" name="Gender" disabled="disabled" id="Gender" value="';echo $Gender;;echo '"></td>
                </tr>
                <tr>
                    <td style="text-align: right">Claim Form Number</td>
                    <td><input type="text" name="Admission_Claim_Form_Number" disabled="disabled"  id="Admission_Claim_Form_Number"';if ($Claim_Number_Status == "Mandatory") {;echo ' required="required"';};echo ' value="';echo $Claim_Form_Number;;echo '"></td>
                </tr>
                <tr>
                <input type="hidden" id="Admission_Employee_ID" name="Admission_Employee_ID" value="';echo $Employee_ID;;echo '">
                <td style="text-align: right">Bill Type</td>
                <td><input type="text" name="Billing_Type" disabled="disabled" id="Billing_Type" value="';echo $Billing_Type;;echo '">

                </td>
                </tr>
                <tr>
                    <td style="text-align: right">Patient Age</td>
                    <td><input type="text" name="Patient_Age" id="Patient_Age"  disabled="disabled" value="';echo $age;;echo '"></td>
                </tr>
                <tr>
                    <td style="text-align: right">Consulting/Doctor</td>
                    <td><input type="text" name="Prepared_By" id="Prepared_By" disabled="disabled" value="';echo $Employee_Name;;echo '"></td>
                </tr>
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td style="text-align: center;width: 100%;">
                        <fieldset id="PatientPhoto" style="padding: 0;">
                            <legend>Patient Photo</legend>
                            <div style="padding: 0;">
                                ';
if (file_exists('patientImages/'.$Patient_Picture)) {
echo '<img src="patientImages/'.$Patient_Picture .'" title="Click To View Image" alt="PatientPhoto" style="margin-left:0;width:180px; height:180px; " onclick="viewPatientPhoto('.$Registration_ID .')"/>';
}else {
echo '<img src="patientImages/default.png" alt="PatientPhoto" width="100%"/>';
}
;echo '
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
<br/>

<script type="text/javascript">
    function substitute(Item_ID_location) {
        var Item_ID = document.getElementById("Item_ID_" + Item_ID_location + "").value;
        var Patient_Payment_Item_List_ID = "';echo $_GET['Patient_Payment_Item_List_ID'];;echo '";
        var quantity = document.getElementById("Quantity_" + Item_ID_location + "").value;
        var responce = confirm("Are You Sure You Want To Substitute This Item");
        var Billing_Type = "';echo $Billing_Type;;echo '";
        var Guarantor_Name = "';echo $Guarantor_Name;;echo '";
        if (responce) {
            if (window.XMLHttpRequest) {
                mm_sbst_object = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                mm_sbst_object = new ActiveXObject("Micrsoft.XMLHTTP");
                mm_sbst_object.overrideMimeType("text/xml");
            }
            mm_sbst_object.onreadystatechange = function () {
                if (mm_sbst_object.readyState == 4) {
                    var ajax_responce = mm_sbst_object.responseText;
                    if (ajax_responce == "sent") {
                        location.reload();
                    }
                }
            }; //specify name of function that will handle server response....
            mm_sbst_object.open("GET", "doctocr_ajax_substitute.php?Item_ID=" + Item_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&quantity=" + quantity + "&Billing_Type=" + Billing_Type + "&Guarantor_Name=" + Guarantor_Name, true);
            mm_sbst_object.send();
        } else {
        }
    }

    function changeItem(Item_ID, Item_ID_local) {
        if (Item_ID != "") {
            var Billing_Type = "';echo $Billing_Type;;echo '";
            var Guarantor_Name = "';echo $Guarantor_Name;;echo '";
            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                mm = new ActiveXObject("Micrsoft.XMLHTTP");
                mm.overrideMimeType("text/xml");
            }
            mm.onreadystatechange = function () {
                if (mm.readyState == 4) {
                    var data4 = mm.responseText;

                    document.getElementById("price_" + Item_ID_local + "").value = data4;

                    var price = document.getElementById("price_" + Item_ID_local + "").value;

                    var quantity = document.getElementById("Quantity_" + Item_ID_local + "").value;
                    var ammount = 0;
                    ammount = price * quantity;
                    document.getElementById("amount_" + Item_ID_local + "").value = ammount;
                }
            }; //specify name of function that will handle server response....
            mm.open("GET", "Get_Item_price.php?Product_Name=" + Item_ID + "&Billing_Type=" + Billing_Type + "&Guarantor_Name=" + Guarantor_Name, true);
            mm.send();
        }
    }
</script>
<script>
    function Show_Patient_File() {
        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow("Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo $_GET['Patient_Payment_Item_List_ID'];;echo '&PatientFile=PatientFileThisForm", "Patient File", 1300, 700);
        //winClose.close();
        //openPrintWindow("http://www.google.com", "windowTitle", "width=820,height=600");

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, "dialogWidth:" + w + "; dialogHeight:" + h + "; center:yes;dialogTop:" + top + "; dialogLeft:" + left);//"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left);

        return mypopupWindow;
    }

    function search_diseases(disease_version){
        var disease_code=$("#disease_code").val();
        var disease_name=$("#disease_name").val();

       $.ajax({
           type:"GET",
           url:"search_disease_c_death.php",
           data:{disease_code:disease_code,disease_name:disease_name,disease_version:disease_version},
           success:function (data){
               //console.log(data);
               $("#disease_suffred_table_selection").html(data);
           },
           error:function (x,y,z){
               console.log(z);
           }
       });
    }

    function add_death_reason(disease_ID){
        var diagnosis=$("#diagnosis").val();
        $.ajax({
        url:"search_disease_c_death.php",
        type:"post",
        data:{disease_ID:disease_ID, selected_disease:"added_disease",from_referal:"from_referal"},
        success:function(results){
            if(diagnosis == ""){
                diagnosis = results+",";
            }else{
                if (diagnosis.includes(results)){
                    diagnosis = diagnosis.replace(results+",","");
                }else{
                    diagnosis = diagnosis+results+",";
                }
            }
            $("#diagnosis").html(diagnosis);
        }
        });
    }

</script>
<div id="Add_Disease_Dialog">
  ';
$get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
if(mysqli_num_rows($get_icd_9_or_10_result)>0){
$configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
}
;echo '   <div style="height:350px;overflow-y: scroll;">

  <table class="table table-condensed">
    <tr>
      <td width="100%">
        <table class="table table-condensed" style="width:100%!important">
            <tr>
                <td>
                    <table style="width: 100%">
                        <td>
                            <input type="text"id="disease_name" onkeyup="search_diseases("';echo $configvalue_icd10_9;;echo '"),clear_other_input("disease_code")" placeholder="----Search Disease Name----" class="form-control">
                        </td>
                        <td>
                            <input type="text" id="disease_code" onkeyup="search_diseases("';echo $configvalue_icd10_9;;echo '"),clear_other_input("disease_name")" placeholder="----Search Disease Code----" class="form-control">
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=""><b>Select Diseases To Add</b></td>
            </tr>
            <tbody id="disease_suffred_table_selection">
            ';
$select_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
while($row=mysqli_fetch_assoc($select_diseases)){
extract($row);
$disease_id="{$disease_ID}";
echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\",\"$Registration_ID\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
}
;echo '            </tbody>
        </table>
      </td>
    </tr>
  </table>

  </div>
</div>
<fieldset style="overflow-y: scroll;height: 120px;">
    <div id="summerypatientfile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
        <div id="summpatfileInfo">
        </div>
    </div>
    <div id="showdataMedics" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
        <div align="center" style="" id="medicsprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

        <div id="myMedics">
        </div>
    </div>
    <div id="getreferralform"
    style="display: none;height:340px;overflow-x:hidden;overflow-y:scroll;background-color: white">
        <div align="center" style="" id="referalprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
        <div id="container" style="display:none">
            <div id="default">
                <h1>#{title}</h1>
                <p>#{text}</p>
            </div>
        </div>
        <div id="referralData"></div>

        <div style="text-align:center;margin: 20px 0"><input type="button" onclick="referral_patient(';echo $Registration_ID;;echo ',';echo $_GET['Patient_Payment_ID'];;echo ',';echo $_GET['Patient_Payment_Item_List_ID'];;echo ')" class="art-button" value="Referral Patient"></div>

    </div>
    <div id="getFileByFolio" style="display: none;">
        <div align="center" style="" id="getFileByFolioprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
        <div id="containerFileFolio">

        </div>
    </div>
    <table  width=100%>
        <tr>
            <td style="width: 50px;"><center><b>S/N</b></center></td>
        <td style="width: 150px;"><b>Item Code</b></td>
        <td><b>Item Description</b></td>
        <td><center><b>Price</b></center></td>
        <td><center><b>Discount</b></center></td>
        <td><center><b>Quantity</b></center></td>
        <td><center><b>Amount</b></center></td>
        </tr>
        ';
if (isset($_GET['Patient_Payment_ID'])) {
$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
$qr = "select it.Item_ID,it.Product_Name,Can_Be_Substituted_In_Doctors_Page,ppl.Quantity,ppl.Price,ppl.Discount from tbl_items it, tbl_patient_payment_item_list ppl,tbl_patient_payments pp
                                                    where ppl.Item_ID = it.Item_ID and
                                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                    ppl.Patient_Payment_ID = ".$Patient_Payment_ID ."
                                                    and pp.registration_id = '$Registration_ID' and
						    ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')";
$sql_Select_Current_Patient = mysqli_query($conn,$qr);
$i = 1;
while ($row = mysqli_fetch_array($sql_Select_Current_Patient)) {
;echo '                <tr>
                    <td><input type="text" value="';echo $i;;echo '" style="width: 50px; text-align: center;"></td>
                    <td><input type="text" id="Product_Code_';echo $row['Item_ID'];;echo '" name="Product_Code_';echo $row['Item_ID'];;echo '" style="width: 200px;" value="';echo $row['Item_ID'];;echo '"></td>
                    <td><input type="text" readonly="true" id="itemName_';echo $row['Item_ID'];;echo '" value="';echo $row['Product_Name'];;echo '" style="padding-left:5px;width: 82%">
                        ';
if (strtolower($row['Can_Be_Substituted_In_Doctors_Page']) == "yes") {
echo '&nbsp;&nbsp;<input type="button" style="border-radius:3px" id="'.$row['Item_ID'] .'" class="art-button substitute" value="SUBSTITUTE">';
}
;echo '                    </td>&nbsp;&nbsp;

                    <td width=5%><input type="text" style="text-align: center;" class="price" id="price_';echo $row['Item_ID'];;echo '" name="price_';echo $row['Item_ID'];;echo '" readonly="readonly" value="';echo $row['Price'];;echo '" style="width: 100px; text-align: right;"></td>
                    <td width=5%><input type="text" style="text-align: center;" class="price" id="price_';echo $row['Item_ID'];;echo '" name="price_';echo $row['Item_ID'];;echo '" readonly="readonly" value="';echo $row['Discount'];;echo '" style="width: 100px; text-align: right;"></td>

                    <td width=5%><input type="text" style="text-align: center;" class="Quantity" id="Quantity_';echo $row['Item_ID'];;echo '" name="Quantity_';echo $row['Item_ID'];;echo '" value="';echo $row['Quantity'];;echo '" style="width: 100px; text-align: right;"></td>
                    <td width=5%><input type="text" style="text-align: center;" class="amount" id="amount_';echo $row['Item_ID'];;echo '" name="amount_';echo $row['Item_ID'];;echo '" readonly="readonly" value="';echo ($row['Quantity'] * $row['Price'])-$row['Discount'];;echo '" style="width: 100px; text-align: right;">


                    </td>
                </tr>
                ';
$i++;
}
}
;echo '    </table>

    ';
$Patient_Payment_Item_List_ID = mysqli_real_escape_string($conn,@$_GET['Patient_Payment_Item_List_ID']);
$check_status = mysqli_query($conn,"SELECT Process_Status,p.Check_In_ID,Type_Of_Check_In,Type_of_patient_case from tbl_patient_payment_item_list pl JOIN tbl_patient_payments p ON pl.Patient_Payment_ID=p.Patient_Payment_ID JOIN tbl_check_in c ON c.Check_In_ID=p.Check_In_ID  where Process_Status='not served' AND Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
$num_rows_process = mysqli_num_rows($check_status);
$result_check = mysqli_fetch_assoc($check_status);
$Check_In_ID = $result_check['Check_In_ID'];
$Type_Of_Check_In = $result_check['Type_Of_Check_In'];
$Type_of_patient_case = $result_check['Type_of_patient_case'];
if ($num_rows_process >0) {
;echo '    <div style="display:none">
            <b style="color:red">Type Of Patient Case</b>
            <input type="hidden" value="';echo  $Check_In_ID ;echo '" id="last_check_id">
            <select name="Type_of_patient_case" id="Type_of_patient_case" required="required" onchange="updateTypeOfPatientCase(this.value)">
                <option selected="selected" value="">Select Checking Type</option>
                <option ';if (strtolower($Type_of_patient_case) == 'new_case') echo 'selected';;echo ' value="new_case">New Case</option>
                <option ';if (strtolower($Type_of_patient_case) == 'continue_case') echo 'selected';;echo ' value="continue_case">Continues Case</option>
            </select>
        </div>
        ';
}
;echo '</fieldset><br/><br/>
<table width="100%" border="0" >
    <tr>
        <td style="text-align:center;">


            ';if (isset($_GET['Registration_ID'])) {
if(isset($_GET['from_consulted'])&&$_GET['from_consulted']=='yes'){
$from_consulted="&from_consulted=yes";
}else{
$from_consulted="";
}
;echo '
                <a href="clinicalnotes.php?';
if ($Registration_ID != '') {
echo "Registration_ID=$Registration_ID&";
}
;echo '';
if (isset($_GET['Patient_Payment_ID'])) {
echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID'] ."&";
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID'] ."&";
}
;echo 'SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage';echo  $from_consulted ;echo '" class="art-button">
                    CLINICAL NOTES
                </a>
                
            ';}else {
;echo '
                    <input type="button" value="CLINICAL NOTES" class="art-button" onclick="alert("Choose Patient!");">
                 <!--                IMEAMISHIWA NURSE COMMUNICATION
                    <a href="#" onclick="alert("Choose Patient!");" class="art-button">
                    ANESTHESIA
                </a>-->
            ';};echo '            <a href="psychology_unit.php?Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $_GET['Patient_Payment_ID'];;echo '&Patient_Payment_Item_List_ID=';echo $_GET['Patient_Payment_Item_List_ID'];;echo '&PatientFile=PatientFileThisForm&position=out"><input type="button" value="PSYCHOLOGY UNIT" class="art-button"> </a>
            <input type="hidden" id="send_admition_form" name="send_admition_form">
            ';if (isset($_GET['Registration_ID'])) {
$Registration_ID=$_GET['Registration_ID'];
;echo '                <a href="optic/optic.php?patientId=';echo $Registration_ID;echo '&guarantorName=';echo $Guarantor_Name;echo '&this_page_from=doctor_outpatient&consultation_ID=';echo $consultation_ID;echo '&Sponsor_ID=';echo $Sponsor_ID;echo '">
                        <!--input type="button" name="patient_transfer_referral" id="patient_transfer_referral" value="OPTICAL " class="art-button" /-->
                        </a>
            <!--                IMEAMISHIWA NURSE COMMUNICATION
            <a href="anethesia.php?Registration_ID=';echo  $Registration_ID ;echo '&Patient_Payment_ID=';echo  $Patient_Payment_ID ;echo '&Patient_Payment_Item_List_ID=';echo  $Patient_Payment_Item_List_ID ;echo '&this_page_from=doctor_outpatient" class="art-button">
                     ANESTHESIA
                </a>-->
            ';}
;echo '
            <!--A Button into Referral Form: By AdeK, June 2016-->
            ';if (isset($_GET['Registration_ID'])) {;echo '                <input type="button" name="patient_transfer_referral" id="patient_transfer_referral" value="REFERRAL" onclick="showReferralForm(';echo $Registration_ID;;echo ',';echo $_GET['Patient_Payment_ID'];;echo ',';echo $_GET['Patient_Payment_Item_List_ID'];;echo ')" class="art-button" />
            ';};echo '

            ';
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
$original_Date = $row['today'];
$new_Date = date("Y-m-d",strtotime($original_Date));
$Today = $new_Date;
}
$Patient_Payment_Item_List_ID = 0;
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}
$select = mysqli_query($conn,"select Nurse_ID, Nurse_DateTime from tbl_nurse where Registration_ID = '$Registration_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' ") or die(mysqli_error($conn));
$num_rows = mysqli_num_rows($select);
if (empty($Registration_ID) ||$Registration_ID == 0) {
}else {
if ($num_rows == 0) {
;echo '                    <script>
                        function Vital_Alert() {
                            // var r = confirm("NO VITAL SIGNS performed for this patient today. To view previous VITAL SIGNS click OK!");
                            alert("NO VITAL SIGNS performed for this patient today. Please go to patient file to view previous result");
                            // if(r == true){
                            // document.location = "nursingstationvitals.php?Registration_ID=';echo $Registration_ID;;echo '&Nurse_DateTime=';echo $Today;;echo '&Patient_Payment_ID=';echo $Patient_Payment_ID;;echo '&Patient_Payment_Item_List_ID=';echo $Patient_Payment_Item_List_ID;;echo '";
                            // }
                        }
                    </script>
                    <input type="button" style="display: none" name="Vital_Button" id="Vital_Button" value="VIEW VITAL SIGNS" onclick="Vital_Alert()" class="art-button">
                    ';
}else {
;echo '                    <a  style="display: none" href="nursingstationvitals.php?Registration_ID=';echo $Registration_ID;;echo '&Patient_Payment_ID=';echo $Patient_Payment_ID;;echo '&Patient_Payment_Item_List_ID=';echo $Patient_Payment_Item_List_ID;;echo '" style="padding:1 1px 0 1px" class="art-button">VIEW VITAL SIGNS</a>
                    ';
}
}
;echo '            <!--Lab Results-->

            ';
$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$MaximumTimeToSeePatientHistory = $_SESSION['configData']['MaximumTimeToSeePatientHistory'];
$emp = '';
$empsnot = '';
if ($hospitalConsultType == 'One patient to one doctor') {
$emp = "AND tlc.Consultant_ID =".$_SESSION['userinfo']['Employee_ID'] ." ";
$empsnot = "AND tlc.Consultant_ID !=".$_SESSION['userinfo']['Employee_ID'] ." ";
}
$totalab = 0;
$totalrad = 0;
if ($hospitalConsultType == 'One patient to one doctor') {
$select_Filtered_Patients2 = mysqli_query($conn,"SELECT tbl_patient_registration.Registration_ID,tc.consultation_id FROM
					tbl_test_results as trs,tbl_tests_parameters_results as tprs,tbl_item_list_cache tlc,
					tbl_payment_cache,tbl_patient_registration,
					tbl_employee,tbl_consultation tc,tbl_patient_payment_item_list  tpipi WHERE
					payment_item_ID=Payment_Item_Cache_List_ID AND
					tlc.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND
					tc.consultation_id=tbl_payment_cache.consultation_id AND
					tc.Patient_Payment_Item_List_ID=tpipi.Patient_Payment_Item_List_ID AND tpipi.Process_Status !='signedoff' AND
				        trs.test_result_ID=tprs.ref_test_result_ID AND
				        tprs.Submitted='Yes' AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND
					tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID $empsnot
                                        AND DATE(tprs.TimeSubmitted) = DATE(NOW())
					GROUP BY tbl_patient_registration.Registration_ID
					ORDER BY test_result_ID ASC ") or die(mysqli_error($conn));
$arrayInLab = array(0);
while ($qrc = mysqli_fetch_array($select_Filtered_Patients2)) {
if (!in_array($qrc['consultation_id'],$arrayInLab)) {
$qrytrn = mysqli_query($conn,"SELECT c.Patient_Payment_Item_List_ID FROM tbl_consultation c
                                  JOIN tbl_patient_payment_item_list_transfer p
                                  ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID
                                  JOIN tbl_patient_payment_item_list pl
                                  ON pl.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID
                                  where c.consultation_id='".$qrc['consultation_id'] ."' AND pl.Consultant_ID='".$_SESSION['userinfo']['Employee_ID'] ."'
                                  ") or die(mysqli_error($conn));
if (mysqli_num_rows($qrytrn) >0) {
$totalab++;
}
}
$arrayInLab[] = $qrc['consultation_id'];
}
$select_radiologyOne = mysqli_query($conn,"
		                    SELECT tc.consultation_id FROM tbl_radiology_patient_tests tr
							JOIN  tbl_item_list_cache tlc ON tlc.Payment_Item_Cache_List_ID=tr.Patient_Payment_Item_List_ID
                            JOIN tbl_payment_cache pc ON tlc.Payment_Cache_ID= pc.Payment_Cache_ID
							JOIN tbl_consultation tc ON tc.consultation_ID= pc.consultation_id
							JOIN tbl_patient_payment_item_list tp ON tc.Patient_Payment_Item_List_ID= tp.Patient_Payment_Item_List_ID
							WHERE tr.Status='done'  AND DATE(tr.Date_Time) = DATE(NOW()) $empsnot AND tp.Process_Status !='signedoff' GROUP BY tr.Registration_ID
		                  ") or die(mysqli_error($conn));
$arrayInRad = array(0);
while ($qrc2 = mysqli_fetch_array($select_radiologyOne)) {
if (!in_array($qrc2['consultation_id'],$arrayInRad)) {
$qrytrn2 = mysqli_query($conn,"SELECT c.Patient_Payment_Item_List_ID FROM tbl_consultation c JOIN tbl_patient_payment_item_list_transfer p
                                  ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID
                                  JOIN tbl_patient_payment_item_list pl
                                  ON pl.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID
                                  where c.consultation_id='".$qrc2['consultation_id'] ."' AND pl.Consultant_ID='".$_SESSION['userinfo']['Employee_ID'] ."'
                                  ") or die(mysqli_error($conn));
if (mysqli_num_rows($qrytrn2) >0) {
$totalrad++;
}
}
$arrayInRad[] = $qrc2['consultation_id'];
}
}
$Registration_ID= isset($_GET['Registration_ID']) ? $_GET['Registration_ID'] : '';
$select_Filtered_Patients = mysqli_query($conn,"SELECT tbl_patient_registration.Registration_ID FROM
					tbl_test_results as trs,tbl_tests_parameters_results as tprs,tbl_item_list_cache tlc,
					tbl_payment_cache,tbl_patient_registration,
					tbl_employee,tbl_consultation tc,tbl_patient_payment_item_list  tpipi WHERE tbl_patient_registration.Registration_ID LIKE '$Registration_ID' AND
					payment_item_ID=Payment_Item_Cache_List_ID AND
					tlc.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND
					tc.consultation_id=tbl_payment_cache.consultation_id AND
					tc.Patient_Payment_Item_List_ID=tpipi.Patient_Payment_Item_List_ID AND tpipi.Process_Status !='signedoff' AND
				        trs.test_result_ID=tprs.ref_test_result_ID AND
				        tprs.Submitted='Yes' AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND
					tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID $emp
                                        AND DATE(tprs.TimeSubmitted) = DATE(NOW()) AND tc.consultation_id NOT IN (select consultation_id from tbl_consultation c join tbl_patient_payment_item_list_transfer p ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID)
					GROUP BY tbl_patient_registration.Registration_ID
					ORDER BY test_result_ID ASC ") or die(mysqli_error($conn));
$no_of_result = mysqli_num_rows($select_Filtered_Patients) +$totalab;
$Registration_ID= isset($_GET['Registration_ID']) ? $_GET['Registration_ID'] : '';
$select_radiology = mysqli_query($conn,"
		                    SELECT tr.Item_ID FROM tbl_radiology_patient_tests tr
							JOIN  tbl_item_list_cache tlc ON tlc.Payment_Item_Cache_List_ID=tr.Patient_Payment_Item_List_ID
                            JOIN tbl_payment_cache pc ON tlc.Payment_Cache_ID= pc.Payment_Cache_ID
							JOIN tbl_consultation tc ON tc.consultation_ID= pc.consultation_id
							JOIN tbl_patient_payment_item_list tp ON tc.Patient_Payment_Item_List_ID= tp.Patient_Payment_Item_List_ID
							WHERE tr.Registration_ID LIKE '$Registration_ID' AND tr.Status='done'  AND DATE(tr.Date_Time) = DATE(NOW())  $emp AND tp.Process_Status !='signedoff'  AND tc.consultation_id NOT IN (select consultation_id from tbl_consultation c join tbl_patient_payment_item_list_transfer p ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID) GROUP BY tr.Registration_ID
		                  ") or die(mysqli_error($conn));
$no_of_result_radiology = mysqli_num_rows($select_radiology) +$totalrad;
$no_of_result_procedure = 0;
$no_of_result_surgery = 0;
;echo '
            <a href="doctorprocedurelist.php?Section=Doctor" class="art-button">
                PERFORM PROCEDURES&nbsp;';
;echo '            </a>
            <a href="doctorsurgerylist.php" class="art-button">
                PERFORM SURGERIES&nbsp;';
;echo '            </a>

            <a href="laboratory_result_details.php?Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&Patient_Payment_Item_List_ID='.$Patient_Payment_Item_List_ID.'&Date_From=&Date_To=';
if ($Registration_ID != '') {
echo "Registration_ID=$Registration_ID&";
}
if (isset($_GET['Patient_Payment_ID'])) {
echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID'] ."&";
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID'] ."&";
}
;echo 'SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" style="padding:1 1px 0 1px" class="art-button">
                LAB RESULTS &nbsp;<span class="badge" style="background:red">';echo $no_of_result;echo '</span>
            </a>
            <!--Radiology Results-->

            ';
$Radiology_Results_URL = "RadiologyPatientTests_Doctor.php?Registration_ID=".$Registration_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Date_From=&Date_To=";
;echo '
            <a href="';echo $Radiology_Results_URL;;echo '" style="padding:1 1px 0 1px" class="art-button">
                RADIOLOGY RESULTS &nbsp;<span class="badge" style="background:red">';echo $no_of_result_radiology;;echo '</span>
            </a>


            ';
if (isset($_SESSION['userinfo'])) {
if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
;echo '
                    ';
}
}
;echo '        </td>
    </tr>
    <tr>
        <td style="text-align:center">
            ';
if(isset($_GET['Registration_ID'])){
;echo '                 <span style="font-size: 12px;"><label for="photo">Display Patient Picture</label></span> <input type="checkbox" name="photo" id="photo"/>
                ';
}
;echo '        </td>
    </tr>
</table>

';
if (isset($_GET['Patient_Payment_ID'])) {
$paymentID = mysqli_real_escape_string($conn,$_GET['Patient_Payment_ID']);
$Patient_Payment_Item_List_ID = mysqli_real_escape_string($conn,$_GET['Patient_Payment_Item_List_ID']);
$sponsorQry = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='$paymentID'");
$result = mysqli_fetch_assoc($sponsorQry);
echo '<input type="hidden" id="sponsor" payID="'.$Patient_Payment_Item_List_ID .'" value="'.$result['Sponsor_ID'] .'">';
}
;echo '
<div id="ItemsListSub" style="display: none">
    <div id="show">
        ';
echo '<table id="datatable2"><thead><tr><th></th></tr></thead>';
$qr2 = "select Item_ID,Product_Name from tbl_items WHERE Can_Be_Substituted_In_Doctors_Page='yes'";
$result_item = mysqli_query($conn,$qr2);
while ($row2 = mysqli_fetch_assoc($result_item)) {
echo '<tr class="tr" id="'.$row2['Item_ID'] .'"><td>'.$row2['Product_Name'] .'</td></tr>';
}
echo '</table>';
;echo '    </div>

</div>
<!--END HERE-->

';
include("./includes/footer.php");
;echo '<!--<script src="js/subItems.js"></script>-->
<!--subItems.js-->
<style>
    .tr:hover{
        cursor: pointer;
    }
</style>
<script>
    function display_add_disease_dialog(){
      $("#Add_Disease_Dialog").dialog("open");
    }

    $(".substitute").click(function () {

        $("#ItemsListSub").dialog({
            modal: true,
            width: 600,
            minHeight: 200,
            resizable: true,
            draggable: true,
            title: "SUBSTITUTE ITEMS",
        });
    });

    $(".tr").click(function () {
        var ItemID = $(".substitute").attr("id");
        $("#ItemsListSub").dialog("close");
        var sponsor = $("#sponsor").val();
        var id = $(this).attr("id");
        $.ajax({
            type: "POST",
            url: "requests/SubstituteItems.php",
            data: "action=ViewItem&item=" + id + "&sponsor=" + sponsor,
            success: function (html) {
                $("#itemName_" + ItemID).val(html);
            }
        });

    });

    $(".tr").click(function () {
        var ItemID = $(".substitute").attr("id");
        var sponsor = $("#sponsor").val();
        var id = $(this).attr("id");
        var payID = $("#sponsor").attr("payID");
        var Quantity = $("#Quantity_" + ItemID).val();

        $.ajax({
            type: "POST",
            url: "requests/SubstituteItems.php",
            data: "action=ViewItemPrice&item=" + id + "&sponsor=" + sponsor + "&payID=" + payID + "&Quantity=" + Quantity,
            success: function (html) {
                $("#price_" + ItemID).val(html);
                var price = $("#price_" + ItemID).val();
                var total = Quantity * price;
                $("#amount_" + ItemID).val(total);
            }
        });
    });

    $(".Quantity").on("input", function () {
        var ItemID = $(".substitute").attr("id");
        var Quantity = $(this).val();
        var price = $("#price_" + ItemID).val();
        var total = Quantity * price;
        $("#amount_" + ItemID).val(total);
    });


</script>
<script>
    function showSummeryPatientFile() {
        document.getElementById("summpatfileInfo").innerHTML = "";
        if (window.XMLHttpRequest) {
            ajaxTimeObjt = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            ajaxTimeObjt = new ActiveXObject("Micrsoft.XMLHTTP");
            ajaxTimeObjt.overrideMimeType("text/xml");
        }
        ajaxTimeObjt.onreadystatechange = function () {
            var data = ajaxTimeObjt.responseText;
            document.getElementById("summpatfileInfo").innerHTML = data;
            $("#summerypatientfile").dialog("open");
        }; //specify name of function that will handle server response....
        ajaxTimeObjt.open("GET", "get_summery_pat_file.php?Patient_Payment_ID=';echo $Patient_Payment_ID ;echo '&Patient_Payment_Item_List_ID=';echo $Patient_Payment_Item_List_ID ;echo '&Registration_ID=';echo $Registration_ID ;echo '", true);
        ajaxTimeObjt.send();


    }
</script>
<script>
    $(document).ready(function () {//
        $("#summerypatientfile").dialog({autoOpen: false, width: "95%", height: 620, title: "PATIENT FILE", modal: true, position: "middle"});
        $("#Add_Disease_Dialog").dialog({autoOpen: false, width: "60%", height: 450, title: "ADD DISEASES", modal: true});
        $("#getreferralform").dialog({autoOpen: false, width: "95%", height: 620, title: "PATIENT REFERRAL FORM", modal: true, position: "middle"});
        $("#showdataMedics").dialog({autoOpen: false, width: "85%", height: 450, title: "SELECT CHRONIC MEDICATIONS", modal: true, position: "middle"});
        $container = $("#container").notify();
    });
</script>
    <!--<div align="center" style="" id="referalprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>-->

<script>
    function showReferralForm(regid, ppID, pplID) {
        $("#referralData").html("");
        $("#getreferralform").dialog("option", "title", "PATIENT REFERRAL FORM FOR ';echo $Patient_Name ;echo '" + "  " + "#." + regid);
        $("#getreferralform").dialog("open");
        var dataString = "regid=" + regid + "&ppID=" + ppID + "&pplID=" + pplID;

        $.ajax({
            type: "GET",
            url: "requests/get_referral_form.php",
            data: dataString,
            beforeSend: function (xhr) {
                $("#referalprogress").show();
            },
            success: function (result) {
                $("#referralData").html(result);
            }, complete: function (jqXHR, textStatus) {
                $("#referalprogress").hide();
            }
        });


        //alert(redid+" "+Patient_Payment_ID+" "+Patient_Payment_Item_List_ID);
    }
</script>
<script>
    function referral_patient(regid, ppID, pplID)
    {
        var referral_from = $("#referral_from").val();
        var referral_to = $("#referral_to").val();
        var transfer_date = $("#transfer_date").val();
        var diagnosis = $("#diagnosis").val();
        var temp = $("#temp").val();
        var heatrate = $("#heatrate").val();
        var resprate = $("#resprate").val();
        var bloodpressure = $("#bloodpressure").val();
        var mental_status = $("#mental_status").val();
        var alert = $("#alert").val();
        var patienthist = $("#patienthist").val();
        var chrnicmed = $("#chrnicmed").val();
        var medalergy = $("#medalergy").val();
        var pertnetfindings = $("#pertnetfindings").val();
        var labresult = $("#labresult").val();
        var radresult = $("#radresult").val();
        var treatmentrendered = $("#treatmentrendered").val();
        var reasonfortransfer = $("#reasonfortransfer").val();
        var doct_phone_number = $("#doct_phone_number").val();
        var call_phone_number = $("#call_phone_number").val();

        var valerr = false;

        if (referral_to == "") {
            $("#referral_to").css("border", "2px solid red");
            valerr = true;
        } else {
            $("#referral_to").css("border", "2px solid #ccc")
        }
        if (reasonfortransfer == "") {
            $("#reasonfortransfer").css("border", "2px solid red");
            valerr = true;
        } else {
            $("#reasonfortransfer").css("border", "2px solid #ccc")
        }
        if (doct_phone_number == "") {
            $("#doct_phone_number").css("border", "2px solid red");
            valerr = true;
        } else {
            $("#doct_phone_number").css("border", "2px solid #ccc")
        }

        if (valerr) {
            exit;
        }

        if (!confirm("Are you sure your want to referral this patient?")) {
            exit;
        }
        var dataString = "regid=" + regid + "&ppID=" + ppID + "&pplID=" + pplID +
                "&referral_from=" + referral_from + "&referral_to=" + referral_to + "&transfer_date=" + transfer_date +
                "&diagnosis=" + diagnosis + "&temp=" + temp + "&heatrate=" + heatrate +
                "&resprate=" + resprate + "&bloodpressure=" + bloodpressure + "&mental_status=" + mental_status +
                "&alert=" + alert + "&patienthist=" + patienthist + "&chrnicmed=" + chrnicmed +
                "&medalergy=" + medalergy + "&pertnetfindings=" + pertnetfindings + "&labresult=" + labresult +
                "&radresult=" + radresult + "&treatmentrendered=" + treatmentrendered + "&reasonfortransfer=" + reasonfortransfer +
                "&doct_phone_number=" + doct_phone_number + "&call_phone_number=" + call_phone_number;

        var NHIFString = "regid=" + regid + "&ppID=" + ppID + "&pplID=" + pplID +
                "&referral_from=" + referral_from + "&referral_to=" + referral_to + "&transfer_date=" + transfer_date +
                "&diagnosis=" + diagnosis + "&temp=" + temp + "&heatrate=" + heatrate +
                "&resprate=" + resprate + "&bloodpressure=" + bloodpressure + "&mental_status=" + mental_status +
                "&alert=" + alert + "&patienthist=" + patienthist + "&chrnicmed=" + chrnicmed +
                "&medalergy=" + medalergy + "&pertnetfindings=" + pertnetfindings + "&labresult=" + labresult +
                "&radresult=" + radresult + "&treatmentrendered=" + treatmentrendered + "&reasonfortransfer=" + reasonfortransfer +
                "&doct_phone_number=" + doct_phone_number + "&call_phone_number=" + call_phone_number;
        var st = "";
        $.ajax({
            type: "POST",
            url: "requests/get_referral_form.php",
            data: dataString,
            beforeSend: function (xhr) {
                $("#referalprogress").show();
            },
            success: function (result) {
                $.ajax({
                    type: "POST",
                    url: "requests/get_referral_form.php",
                    data: dataString,
                    beforeSend: function (xhr) {
                        $("#referalprogress").show();
                    },
                    success: function (result) {
                        if (result == "1") {
                            st = "Patient Reffered Successfully";
                        } else if (result == "0") {
                            st = "An error has occured.Please try again letter!";
                        } else {
                            st = "Fail to process.If problem persits contact administrator";
                        }
                        create("default", {title: "Success", text: st});

                        if (result == "1") {
                            document.location = "doctorsworkspage.php?DoctorsWorksPage=DoctorsWorksThisPage";
                        }
                    }, complete: function (jqXHR, textStatus) {
                        $("#referalprogress").hide();
                    }
                });
            }, complete: function (jqXHR, textStatus) {
                $("#referalprogress").hide();
            }
        });
        //alert(redid+" "+Patient_Payment_ID+" "+Patient_Payment_Item_List_ID);
    }
</script>
<script>
    function updateTypeOfPatientCase(Type_of_patient_case) {
        if (Type_of_patient_case != "") {
            if (!confirm("Are you sure your want to change patinet case to " + Type_of_patient_case)) {
                exit;
            }
            var dataString = "check_id=" + $("#last_check_id").val() + "&Type_of_patient_case=" + Type_of_patient_case;

            $.ajax({
                type: "POST",
                url: "requests/update_checking_type.php",
                data: dataString,
                beforeSend: function (xhr) {
                },
                success: function (result) {
                    if (result == "1") {
                        window.location = window.location.href;
                    } else {
                        //  alert(result);
                    }
                }, complete: function (jqXHR, textStatus) {
                    //$("#referalprogress").hide();
                }
            });
        }
    }
</script>
<script>
    function getChrnMedics() {
        $("#myMedics").html("");
        $("#showdataMedics").dialog("option", "title", "SELECT CHRONIC MEDICATIONS FOR ';echo $Patient_Name ;echo '" + "  " + "#." + ';echo $Registration_ID ;echo ');
        $("#showdataMedics").dialog("open");
        $.ajax({
            type: "GET",
            url: "getitemfromlistselect.php",
            data: "Consultation_Type=Pharmacy",
            beforeSend: function (xhr) {
                $("#medicsprogress").show();
            },
            success: function (result) {
                $("#myMedics").html(result);
            }, complete: function (jqXHR, textStatus) {
                $("#medicsprogress").hide();
            }
        });
    }
</script>
<script>
    function append_item(item_id) {
        var item_name = $.trim($("#item_" + item_id).text());
        var has_data = $("#chrnicmed").val();
        if (has_data == "") {
            $("#chrnicmed").val(item_name);
        } else {
            $("#chrnicmed").val(has_data + "; " + item_name);
        }

    }
</script>
<script>
    function patientFileByFolio() {
//        $("#getFileByFolio").dialog("option", "title", "PATIENT FILE BY FOLIO FOR ';echo $Patient_Name ;echo ');
        $("#getFileByFolio").dialog("open");
        var Start_Date = "000-00-00";
        var End_Date = "';echo  $Today ;echo '";
        var Billing_Type = "All";
        var Sponsor_ID = "';echo  $Sponsor_ID ;echo '";
        var Patient_Number = "';echo  $Registration_ID ;echo '";
        var Patient_Type = "All";


        $.ajax({
            type: "GET",
            url: "Revenue_Collection_BY_Folio_Report_Filtered.php",
            data: {Start_Date: Start_Date, End_Date: End_Date, Billing_Type: Billing_Type, Sponsor_ID: Sponsor_ID, Patient_Number: Patient_Number, Patient_Type: Patient_Type},
            beforeSend: function (xhr) {
                $("#getFileByFolioprogress").show();
            },
            success: function (result) {
                $("#containerFileFolio").html(result);
            }, complete: function (jqXHR, textStatus) {
                $("#getFileByFolioprogress").hide();
            }
        });


        //alert(redid+" "+Patient_Payment_ID+" "+Patient_Payment_Item_List_ID);
    }
    $(document).ready(function () {
        $("#getFileByFolio").dialog({autoOpen: false, width: "80%", height: 450, title: "PATIENT FILE BY FOLIO", modal: true});
    })
</script>
<script>
    //copy this function below
    function Show_Preview(obj){
        window.open(
        "vital_report.php?patient_id="+obj,
        "_blank"
        );
    }
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
    $("#datatable2").DataTable({
        "bJQueryUI": true
    });
</script>
'; ?>

<!-- <a href="clinicalnotes.php?Registration_ID=251&Patient_Payment_ID=968&Patient_Payment_Item_List_ID=1384&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage">CLinical note</a> -->