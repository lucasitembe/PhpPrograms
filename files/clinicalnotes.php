<style>
    .otherdoclinks:hover {
        text-decoration: underline;
        color: #000000;
        cursor: pointer;
    }
</style>
<style>
    #table_iterms input,
    select {
        padding: 4px;
        font-size: 14px;
    }

    #saved {
        color: green;
    }

    .previous-notes {
        color: #8718AA;
        background-color: #FFF;
        margin: 4px;
        padding: 5px;
        border: 1px solid rgb(204, 204, 204);
        border-radius: 4px;
        font-weight: bold;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
include("./includes/_tants.php");
// session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
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
    $Patient_Payment_Item_List_ID_consultation = $Patient_Payment_Item_List_ID;
} else {
    //header("Location: ./index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
    $Patient_Payment_Item_List_ID_consultation = 0;
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    $Patient_Payment_ID_consultation = $Patient_Payment_ID;
} else {
    //header("Location: ./index.php?InvalidPrivilege=yes");
    $Patient_Payment_ID = 0;
    $Patient_Payment_ID_consultation = 0;
}

if ($Patient_Payment_Item_List_ID == 0 || empty($Patient_Payment_Item_List_ID)) {
    header("Location: ./doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage");
}
if ($Patient_Payment_ID == 0 || empty($Patient_Payment_ID)) {
    header("Location: ./doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage");
}
?>

<div id="select_clinic" style="display:none;">
    <style type="text/css">
        #spu_lgn_tbl {
            width: 100%;
            border: none !important;
        }

        #spu_lgn_tbl tr,
        #spu_lgn_tbl tr td {
            border: none !important;
            padding: 15px;
            font-size: 14PX;
        }
    </style>
    <table id="spu_lgn_tbl">
        <tr id="select_clinic">
            <td style="text-align:right">
                Select Your working Clinic
            </td>
            <td>
                <select style='width: 100%;height:30%' name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);update_clinic_id()' onclick='clearFocus(this)' required='required'>
                    <option selected='selected'></option>
                    <?php
                    $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                    $result = mysqli_query($conn, $Select_Consultant);
                    ?>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                        <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        <tr>
            <td style="text-align:right">
                Select Your working Department
            </td>
            <td style="width:60%">
                <select id="working_department" style="width:100%">
                    <option value=""></option>
                    <?php
                    $sql_select_working_department_result = mysqli_query($conn, "SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_working_department_result) > 0) {
                        while ($finance_dep_rows = mysqli_fetch_assoc($sql_select_working_department_result)) {
                            $finance_department_id = $finance_dep_rows['finance_department_id'];
                            $finance_department_name = $finance_dep_rows['finance_department_name'];
                            $finance_department_code = $finance_dep_rows['finance_department_code'];
                            echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        </tr>
        <td colspan="2" align="right">
            <input type="button" onclick="post_clinic_id()" class="art-button-green" value="Open" />
        </td>
        </tr>
    </table>
</div>
<script>
    function post_clinic_id() {
        var Clinic_ID = $("#Clinic_ID").val();
        var working_department = $("#working_department").val();
        if (Clinic_ID == '' || Clinic_ID == null) {
            alert("select clinic first")
            exit
        }
        if (working_department == '' || working_department == null) {
            alert("select your working department first")
            exit
        }
        document.location = "doctorspage_select_clinic.php?Clinic_ID=" + Clinic_ID + '&finance_department_id=' + working_department;
    }

    function select_clinic_dialog() {
        $("#select_clinic").dialog({
            title: 'SELECT CLINIC',
            width: '40%',
            height: 200,
            modal: true,
        });
    }
</script>
<?php

if (isset($_SESSION['doctors_selected_clinic']) && ($_SESSION['doctors_selected_clinic'] != null || $_SESSION['doctors_selected_clinic'] != 0 || $_SESSION['doctors_selected_clinic'] != "")) {
    $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
    $finance_department_id = $_SESSION['finance_department_id'];
    $clinic_location_id = $_SESSION['clinic_location_id'];
    // echo "<script>alert('$doctors_selected_clinic');</script>";
} else {
    echo "<script>select_clinic_dialog();</script>";
    die();
}


$excludedCheckType = array('Pharmacy', 'Optical');

$req_op_prov_dign = $_SESSION['hospitalConsultaioninfo']['req_op_prov_dign'];
$req_op_final_dign = $_SESSION['hospitalConsultaioninfo']['req_op_final_dign'];
$mandatory_comments = $_SESSION['hospitalConsultaioninfo']['mandatory_comments'];
$doctor_admits_patient = $_SESSION['hospitalConsultaioninfo']['doctor_admits_patient'];
$Consultation_Date_And_Time = Date('Y-m-d h:i:s');
$employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Registration_ID = $_GET['Registration_ID'];


//echo $employee_ID;exit;
//$Patient_Payment_ID;


$Consultation_Type = '';
$consultation_ID = 0;
$Check_In_ID = 0;
$Type_Of_Check_In = '';
?>
<!--START HERE-->
<link rel="stylesheet" type="text/css" href="jquerytabs/jquery-ui.theme.css" />
<?php
//Get check-in ID
//+--------------------------------------------------------------------------------------------------+
//|check if the patient has been checked in for the current date,if not insert into tbl_checkin table|
//|__________________________________________________________________________________________________+

$sql_check_if_checked_in_today = "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' AND DATE(Check_In_Date_And_Time)=CURDATE()";
$sql_check_if_checked_in_today_result = mysqli_query($conn, $sql_check_if_checked_in_today) or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_if_checked_in_today_result) > 0) {
    //patient arleady checked in today 
    $Type_of_patient_case = "";
} else {
    $sql_check_in_patient = "";
    $Type_of_patient_case = "continue_case";
}
$consultation_type = "";
if (isset($_GET['from_consulted']) && $_GET['from_consulted'] == 'yes') {
    $Type_of_patient_case = "continue_case";
    $consultation_type = "result_consultation";
    $from_consulted = $_GET['from_consulted'];
} else {
    $consultation_type = "new_consultation";
    $from_consulted = "no";
}


$select_checkin = "SELECT Check_In_ID,Type_Of_Check_In,Type_of_patient_case FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1";
//echo $select_checkin;exit;
$select_checkin_qry = mysqli_query($conn, $select_checkin) or die(mysqli_error($conn));
$checkin = mysqli_fetch_assoc($select_checkin_qry);
$Check_In_ID = $checkin['Check_In_ID'];
$Type_Of_Check_In = $checkin['Type_Of_Check_In'];



$Check_In_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Check_In_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Registration_ID='$Registration_ID'"))['Check_In_ID'];





//@meshack
$cons_query = mysqli_query($conn,"SELECT consulted_patient_display_max_time FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));
$cons= mysqli_fetch_array($cons_query);
$consulted_patient_display_max_time=$cons['consulted_patient_display_max_time'];
$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];

//+++++++++++++++++++++++================+ FROM CONSULTED muga++++++++++++++++++++++++++++++++++++=======================+
if(isset($_GET['from_consulted']) && $_GET['from_consulted']=='yes'){
    $consultation_query_result_cons = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID' AND employee_ID='$Employee_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($consultation_query_result_cons)>0){
        //===================+ IF IS CONSULTED BY SAME DOCTOR +========================+
        $consultation_ID = mysqli_fetch_assoc($consultation_query_result_cons)['consultation_ID'];
       
    }else{
        //===================+ IF IS CONSULTED BY ANOTHER DOCTOR +========================+
        $consultation_another_dr_result = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
        if(mysqli_num_rows($consultation_another_dr_result)>0){
            $consultation_ID = mysqli_fetch_assoc($consultation_another_dr_result)['consultation_ID'];
        }else{
            $insert_consultation= mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID, Registration_ID,Patient_Payment_Item_List_ID,Consultation_Date_And_Time,Clinic_ID,consultation_type, Clinic_consultation_date_time, Check_InID) VALUES ('$employee_ID', '$Registration_ID','$Patient_Payment_Item_List_ID',NOW(),'$doctors_selected_clinic','$consultation_type', NOW(), '$Check_In_ID')") or die(mysqli_error($conn));
            $consultation_query_result = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID' AND DATE(cons_hist_Date)=CURDATE() AND employee_ID='$employee_ID'") or die(mysqli_error($conn));
            if(@mysqli_num_rows($consultation_query_result) > 0){
            //die("I am here");
            }else{
                mysqli_query($conn,"INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date,consultation_type) VALUES ('$consultation_ID','$employee_ID',NOW(),'$consultation_type')") or die(mysqli_error($conn));
            }
        }
    }
}else{
   
    $consultation_query_result_cons = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
    if (mysqli_num_rows($consultation_query_result_cons) > 0) {
    //die('i AM HERE');
    }else {
        mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID, Registration_ID,Patient_Payment_Item_List_ID,Consultation_Date_And_Time,Clinic_ID,consultation_type, Clinic_consultation_date_time, Check_InID) VALUES ('$employee_ID', '$Registration_ID','$Patient_Payment_Item_List_ID',NOW(),'$doctors_selected_clinic','$consultation_type', NOW(), '$Check_In_ID')") or die(mysqli_error($conn));
    } 


    //end of check if continue
    //GET CONSULATATION ID IF IS SET/AVAILABLE
    $consultation_query = "SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'";
    $consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));

    if (@mysqli_num_rows($consultation_query_result) > 0) {
        $row = mysqli_fetch_assoc($consultation_query_result);
        $consultation_ID = $row['consultation_ID'];
        if ($consultation_ID == NULL) {
            $consultation_ID = 0;
        }
    } else {
        $consultation_ID = 0;
    }

    //echo $consultation_ID;exit;   

    $consultation_query_result = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID' AND DATE(cons_hist_Date)=CURDATE() AND employee_ID='$employee_ID'") or die(mysqli_error($conn));

    if (@mysqli_num_rows($consultation_query_result) > 0) {
    //die("I am here");
    } else {
    //echo $insert_query;
        mysqli_query($conn,"INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date,consultation_type) VALUES ('$consultation_ID','$employee_ID',NOW(),'$consultation_type')") or die(mysqli_error($conn));
    }

}
// ========END OF CHECKING  CONSULTATION MUGA================

//Get Patient Check-in Details
$select_checkin_details = "SELECT * FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND Admit_Status<>'admitted'";
//die($select_checkin_details);
$select_checkin_details_qry = mysqli_query($conn, $select_checkin_details) or die(mysqli_error($conn));
if (mysqli_num_rows($select_checkin_details_qry) > 0) {
    $checkin_exists = true;
    while ($checkdetails = mysqli_fetch_assoc($select_checkin_details_qry)) {
        $ToBe_Admitted = $checkdetails['ToBe_Admitted'];
        $ToBe_Admitted_Reason = $checkdetails['ToBe_Admitted_Reason'];
    }
} else {
    $ToBe_Admitted = '';
    $ToBe_Admitted_Reason = '';
    $checkin_exists = false;
}

//get the current date
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn, "select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
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
    $age = 0;
}

?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
?>
        <?php if (isset($_GET['Registration_ID'])) { ?>
            <!--            <a href="Patientfile_Record_Detail.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm&position=in" class='art-button-green' target="_blank">PATIENT FILE</a>
             <a href='Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm' class='art-button-green'>PATIENT FILE</a>-->
            <!--<input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File()' class='art-button-green' />-->
            <?php
            $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
            $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
            $Registration_ID = $_GET['Registration_ID'];
            $previous_notes = $_GET['previous_notes'];
            $from_consulted = $_GET['from_consulted'];

            ?>

            <a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&this_page_from=out_patient_clinical_notes&previous_notes=<?= $previous_notes ?>&from_consulted=<?= $from_consulted ?>" class='art-button-green'>PATIENT FILE</a>

            <input type='button' name='patient_file' style="display:none" id='patient_file' value='SUMMARY PATIENT FILE' onclick='showSummeryPatientFile()' class='art-button-green' />

            <!--<input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File()' class='art-button-green' />-->
            <?php
            $allegies = mysqli_query($conn, "SELECT Allegies FROM tbl_nurse WHERE Registration_ID='$Registration_ID' AND Allegies <>''") or die(mysqli_error($conn));
            $allergy_count = mysqli_num_rows($allegies);
            ?>

            <button style="height:27px!important;color: #FFFFFF!important" name='patient_allege' id='patient_allege' onclick='showPatientAllegies()' class='art-button-green'>PATIENT ALLERGIES <span class="badge" style="background:red"><?= $allergy_count ?></span></button>

        <?php }
        ?>
        <!--Lab results-->
        <a href='laboratory_result_details.php?<?php
                                                if ($Registration_ID != '') {
                                                    echo "Registration_ID=$Registration_ID&";
                                                }
                                                if (isset($_GET['Patient_Payment_ID'])) {
                                                    echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
                                                }
                                                if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                                                    echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
                                                }
                                                ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            LABORATORY RESULTS
        </a>
        <a href='RadiologyPatientTests_Doctor.php?<?php
                                                    if ($Registration_ID != '') {
                                                        echo "Registration_ID=$Registration_ID&";
                                                    }
                                                    if (isset($_GET['Patient_Payment_ID'])) {
                                                        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
                                                    }
                                                    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                                                        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
                                                    }
                                                    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            RADIOLOGY RESULTS
        </a>
<?php      
    $referral_letter =mysqli_fetch_assoc(mysqli_query($conn, "SELECT referral_letter FROM tbl_check_in WHERE Check_In_ID='$Check_In_ID' AND Registration_ID='$Registration_ID'"))['referral_letter'];
    if($referral_letter !=NULL || $referral_letter !=''){
        echo "<a href='excelfiles/$referral_letter'  target='_blank' ><input type='button' class='btn btn-danger btn-sm' value='REFFERAL LETTER'></a>";
    }

$check = mysqli_query($conn,"SELECT Signed_at, consent_amputation from tbl_consert_blood_forms_details where Registration_ID = '$Registration_ID' AND consultation_id='$consultation_ID' AND Consent_ID NOT IN(SELECT Consent_ID FROM tbl_blood_transfusion_requests WHERE Process_Status <> 'processed')") or die(mysqli_error($conn));
$num = mysqli_num_rows($check);

while($data = mysqli_fetch_assoc($check)){
    $Operative_Date_Time = $data['Signed_at'];
    $consent_amputation = $data['consent_amputation'];
}
if($num > 0){
    if($consent_amputation == 'Agree'){
        echo "<a href='blood_request_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green'>BLOOD REQUEST FORM</a>";

	}elseif($consent_amputation == 'Disagree'){
        echo "<a href='blood_request_consent_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green' style='background: #bd0d0d;'>BLOOD CONSENT FORM (REJECTED)</a>";
    }
}else{
    echo "<a href='blood_request_consent_form.php?Registration_ID=".$Registration_ID."&consultation_id=".$consultation_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' class='art-button-green'>BLOOD CONSENT FORM</a>";
}

        $tb_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tb_screen_ID FROM tbl_tb_diagnosis WHERE Registration_ID = '$Registration_ID' AND Consultation_ID = '$consultation_ID' AND status='saved'"))['tb_screen_ID'];
    if ($tb_results > 0){
    echo "<a href='tb_results_details.php?tb_screen_ID=" .$tb_results. "&Registration_ID=" .$Registration_ID. "&TbReportThisPage=TbReport' class='art-button-green' target='_blank'>TB RESULTS</a>";
}
?>
        <hr>
        <input type='button' name='patientFileByFolio' id='patientFileByFolio' value='PATIENT FILE BY FOLIO' onclick='patientFileByFolio(98)' class='art-button-green' />

        <!--<a href='Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm' class='art-button-green'>PATIENT FILE</a>-->
        <?php
        if (isset($_GET['from_consulted']) && $_GET['from_consulted'] == 'yes') {
            $from_consulted = "&from_consulted=yes";
        } else {
            $from_consulted = "";
        }
        ?>
        <a href="newpateientfile_summary.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm&position=in" style="display:none" class='art-button-green'>COMPREHENSIVE PATIENT FILE</a>
        <a href="#" class="art-button-green" onclick="open_patient_sum_prev_hist()">
            PATIENT SUMMARIZE PREVIOUS HISTORY
        </a>
       
        <a href="Cancer_Patient.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>" class='art-button-green'> CANCER FORM</a>

        <a target="_blank" href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>" class='art-button-green'>
            VITAL SIGNS
        </a>
        <a href="#" onclick='Show_Preview(<?php echo $Registration_ID; ?>)' class='art-button-green' />
        RECENT VITAL SIGNS
        </a>
        <!-- Rdtc patch test  -->
        <!-- <a href="#" class="art-button-green hide" onclick="open_rdtc_dialogy()">RDTC PACTCH TEST</a> -->
        <!-- Rdtc patch test -->
        
        <!-- Dose Monitoring -->
        <!-- <input type='submit' id="dose_button hide" class='art-button-green' value='DOSE MONITORING'> -->
        <!-- <a href="#" class="art-button-green hide" onclick="open_orthopedic_template()">ORTHOPEDIC</a> -->

        <!-- Dose Monitoring -->
        <!-- emergency_nursing_notes.php?Registration_ID=160242&Admision_ID=&consultation_ID=770947 -->
        <a href='othermodules.php?Registration_ID=<?=$Registration_ID?>&consultation_ID=<?=$consultation_ID?>&Payment_Item_Cache_List_ID=52943&Patient_Payment_Item_List_ID=<?=$Patient_Payment_Item_List_ID?>&Location=Clinical&Admision_ID=&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage' class='art-button-green' style='background:  #FFC300; color: #000'>ALL TEMPLATES FILES</a>

        <?php
            $Select_Previous = mysqli_query($conn, "SELECT EMD_nursing_ID FROM tbl_emd_nursing_care WHERE consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($Select_Previous);
                    if($num > 0){
                        echo "<a href='emergency_nursing_notes.php?Registration_ID=<?=$Registration_ID?>&consultation_ID=<?=$consultation_ID?>&Payment_Item_Cache_List_ID=52943&Patient_Payment_Item_List_ID=<?=$Patient_Payment_Item_List_ID?>&Location=Clinical&Admision_ID=&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage' class='art-button-green' style='background:  #FFC300; color: #000'>EMD DOCUMENTATION</a>";
                    }
        ?>



        <a href="#" class="art-button-green  hide" onclick="open_consultation_form_dialogy(<?php echo $Registration_ID; ?>)">REQUEST FOR CONSULTATION</a>
        <a href='doctorspageoutpatientwork.php?<?php
                                                if ($Registration_ID != '') {
                                                    echo "Registration_ID=$Registration_ID&";
                                                }
                                                ?><?php
            if (isset($_GET['Patient_Payment_ID'])) {
                echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
            }
            if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
            }
            ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage<?= $from_consulted ?>' class='art-button-green'>
            BACK
        </a>



<?php
    }
}


?>


<div id="patient_summarize_previous_history" style="display:none">
    <div class="row">
        <div class="col-md-12">
            <textarea class="form-control" id='patient_previous_sum_hist_content' placeholder="Write the patient summarize history Here" rows="4"></textarea>
        </div>
        <div class="col-md-12">
            <br />
            <img src="images/ajax-loader_1.gif" id='ajax_loder' width="" style="border-color:white;display: none"> <input type="button" onclick="sum_prv_pt_hist_save()" value="SAVE" class="art-button-green pull-right" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br />
            <table class="table table-bordered" style="background:#FFFFFF">
                <tr style="background:#D3DEDE">
                    <th colspan="4">PREVIOUS SUMMARIZE PATIENT HISTORY</th>
                </tr>

                <tbody id='prev_hist_sum_contect_display'>
                    <tr>
                        <th>S/No.</th>
                        <th>Notes</th>
                        <th>Saved Date And Time</th>
                        <th>Saved By</th>
                    </tr>
                    <?php
                    $sql_select_saved_result = mysqli_query($conn, "SELECT summarize_prev_hist,saved_date_n_time,Employee_Name FROM tbl_patient_summarize_prev_hist psph INNER JOIN tbl_employee e ON  psph.Employee_ID =e.Employee_ID WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_select_saved_result) > 0) {
                        $count = 1;
                        while ($hist_rows = mysqli_fetch_assoc($sql_select_saved_result)) {
                            $patient_previous_sum_hist_content = $hist_rows['summarize_prev_hist'];
                            $saved_date_n_time = $hist_rows['saved_date_n_time'];
                            $Employee_Name = $hist_rows['Employee_Name'];
                            echo "<tr>
                                                <td style='width:50px'>$count</td>
                                                <td>$patient_previous_sum_hist_content</td>
                                                <td>$saved_date_n_time</td>
                                                <td>$Employee_Name</td>
                                          </tr>";
                            $count++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div id="orthopedic_template_section"></div>
    <!--  -->

    <div id="rdtc_dialogy"></div>
    <!-- dose monitoring -->
    <div id="dose_monitoring"></div>
    <input type="hidden" id="patient_name" value="<?=$Patient_Name?>">
    <input type="hidden" id="patient_gender" value="<?=$Gender?>">
    <input type="hidden" id="patient_age" value="<?=$age?>">
    <input type="hidden" id="patient_id" value="<?=$_GET['Registration_ID']?>">
    <input type="hidden" id="employee_id" value="<?php echo $_SESSION['userinfo']['Employee_ID'] ?>"/>
    <!-- dose monitoring -->

<script type="text/javascript">
    function sum_prv_pt_hist_save() {
        var Registration_ID = '<?= $Registration_ID ?>';
        var patient_previous_sum_hist_content = $("#patient_previous_sum_hist_content").val();
        if (patient_previous_sum_hist_content == "") {
            $("#patient_previous_sum_hist_content").focus()
            $("#patient_previous_sum_hist_content").css("border", "1px solid red");
            exit;
        } else {
            $("#patient_previous_sum_hist_content").css("border", "");
        }
        if (confirm("Are You Sure You Want To Save")) {
            $("#ajax_loder").show();
            $.ajax({
                type: 'GET',
                url: 'save_summarize_prev_hist.php',
                data: {
                    patient_previous_sum_hist_content: patient_previous_sum_hist_content,
                    Registration_ID: Registration_ID
                },
                success: function(data) {
                    //alert(data)
                    $("#prev_hist_sum_contect_display").html(data);
                    $("#ajax_loder").hide();
                }
            });
        }
    }

    function open_orthopedic_template(){
        var patient_id = $('#patient_id').val();
        var employee_id = $('#employee_id').val();
        var patient_name = $('#patient_name').val();
        var patient_age = $('#patient_age').val();
        var patient_gender = $('#patient_gender').val();
        $.ajax({
            type : 'post',
            url : 'orthopedic_templates.php',
            data: { 
                patient_id : patient_id,
                employee_id : employee_id,
                patient_name: patient_name,
                patient_age : patient_age,
                patient_gender : patient_gender
            },
            success : (data)=>{
                $("#orthopedic_template_section").dialog({
                    title: 'ORTHOPEDIC',
                    width: '95%',
                    height: 800,
                    modal: true,
                }); 
                $("#orthopedic_template_section").html(data);
                $("#orthopedic_template_section").dialog("open");
            }
        }); 
    }

    function open_patient_sum_prev_hist() {
        $("#patient_summarize_previous_history").dialog("open");

    }
</script>
<br /><br />

<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
                        where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "
                        and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn, $qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Sponsor_ID = $row['Sponsor_ID'];
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
?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_Name = 'Unknown Employee';
    $employee_ID = 0;
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }

    function getItem(Consultation_Type) {
        var mainComplain = document.getElementById('maincomplain').value;
        
        if(mainComplain ==''){
            alert("Please Enter main Complain");
            $("#maincomplain").css("border","2px solid red");
            $("#maincomplain").focus(); exit;
        }
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }



        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        var frm = document.getElementById("clinicalnotes");
        var url = './clinicalautosave.php?Consultation_Type=' + Consultation_Type + '&<?php
                                                                                        if ($Registration_ID != '') {
                                                                                            echo "Registration_ID=$Registration_ID&";
                                                                                        }
                                                                                        ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('POST', url, true);
        mm.send();

        function AJAXP2() {
            var data1 = mm.responseText;
            //alert(data1);
            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }

        //alert(Consultation_Type); 

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
                                                                if ($Registration_ID != '') {
                                                                    echo "Registration_ID=$Registration_ID&";
                                                                }
                                                                ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        //  alert(url2);
        $.ajax({
            type: 'GET',
            url: 'doctoritemselectajax.php',
            data: url2,
            cache: false,
            success: function(html) {
                //alert(html);
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }

    function getDisease(Consultation_Type) {
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }
        var frm = document.getElementById("clinicalnotes");
        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        // alert('gsmmm');
        var ul = 'doctoritemselectajax.php';
        if (Consultation_Type == 'diagnosis') {
            ul = 'doctordiagnosisselect.php';
        }

        var url = './clinicalautosave_todisease.php?Consultation_Type=' + Consultation_Type + '&<?php
                                                                                                if ($Registration_ID != '') {
                                                                                                    echo "Registration_ID=$Registration_ID&";
                                                                                                }
                                                                                                ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();
        // return false;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('POST', url, true);
        mm.send();

        function AJAXP2() {
            var data1 = mm.responseText;
            //alert(data1);
            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
                                                                if ($Registration_ID != '') {
                                                                    echo "Registration_ID=$Registration_ID&";
                                                                }
                                                                ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        //alert(ul);
        $.ajax({
            type: 'GET',
            url: ul,
            data: url2,
            success: function(html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }
</script>
<script>
    //function to confirm provisional diagnosis before test name selection 
    function confirmDiagnosis(Consultation_Type) {
        var mainComplain = document.getElementById('maincomplain').value;
              
        if(mainComplain ==''){
            alert("Please Enter main Complain");
            $("#maincomplain").css("border","2px solid red");
            $("#maincomplain").focus(); exit;
        }
        <?php
        if ($req_op_prov_dign == '1') {
        ?>
            var testnameSelection = confirm("You about to specify laboratory test names for this patient.\nClick Ok to continue without provisional diagnosis.");
            if (testnameSelection) {
                getItem(Consultation_Type);
                return true;
            } else {
                location.href = "#";
                return false;
            }
        <?php
        } else {
        ?>
            getItem(Consultation_Type);
        <?php
        }
        ?>


    }
    //}
</script>
<script>
    function getDiseaseFinal(Consultation_Type) {
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }
        var mainComplain = document.getElementById('maincomplain').value;
        
        if(mainComplain ==''){
            alert("Please Enter main Complain Before any thing please");  
            $("#maincomplain").css("border","2px solid red");
            $("#maincomplain").focus(); exit;
        }
        var frm = document.getElementById("clinicalnotes");
        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        //alert('gsmmm');
        var ul = 'doctoritemselectajax.php';
        if (Consultation_Type == 'diagnosis' || Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis') {
            ul = 'doctordiagnosisselect.php';
        }

        var url = './clinicalautosave_todisease.php?Consultation_Type=' + Consultation_Type + '&<?php
                                                                                                if ($Registration_ID != '') {
                                                                                                    echo "Registration_ID=$Registration_ID&";
                                                                                                }
                                                                                                ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();
        // return false;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('POST', url, true);
        mm.send();

        function AJAXP2() {
            var data1 = mm.responseText;
            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
                                                                if ($Registration_ID != '') {
                                                                    echo "Registration_ID=$Registration_ID&";
                                                                }
                                                                ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        $.ajax({
            type: 'GET',
            url: ul,
            data: url2,
            success: function(html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }
    //tb screening function
    function btScreening(){
        $("#TB_Screening").dialog("open");
        $('html,body').animate({
        scrollTop: $("#TB_Screening").offset().top},
        'slow');
    }
</script>

<?php

if (isset($_POST['submit_submit'])) {

    //GET last date
    //    $_POST = sanitize_input($_POST);

    $get_date_query = "SELECT cons_hist_Date FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID' AND employee_ID='$employee_ID'  AND DATE(cons_hist_Date)=DATE(NOW()) ";

    $cons_date = "";
    $Consultation_Date_And_Time = ", Consultation_Date_And_Time=NOW()";

    $get_date_query_result = mysqli_query($conn, $get_date_query) or die(mysqli_error($conn));

    if (mysqli_num_rows($get_date_query_result) > 0) {
        $cons_date = ", cons_hist_Date=NOW()";
        $Consultation_Date_And_Time = ", Consultation_Date_And_Time=NOW()";
    }

    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    //die($employee_ID);
    $maincomplain = mysqli_real_escape_string($conn, $_POST['maincomplain']);
    $firstsymptom_date = mysqli_real_escape_string($conn, $_POST['firstsymptom_date']);
    $history_present_illness = mysqli_real_escape_string($conn, $_POST['history_present_illness']);
    $review_of_other_systems = mysqli_real_escape_string($conn, $_POST['review_of_other_systems']);
    $general_observation = mysqli_real_escape_string($conn, $_POST['general_observation']);
    $systemic_observation = mysqli_real_escape_string($conn, $_POST['systemic_observation']);
    $Comment_For_Laboratory = mysqli_real_escape_string($conn, $_POST['Comment_For_Laboratory']);
    $Comment_For_Radiology = mysqli_real_escape_string($conn, $_POST['Comment_For_Radiology']);
    $investigation_comments = mysqli_real_escape_string($conn, $_POST['investigation_comments']);
    $ProcedureComments = mysqli_real_escape_string($conn, $_POST['Comments_For_Procedure']);
    $SugeryComments = mysqli_real_escape_string($conn, $_POST['Comments_For_Surgery']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $course_of_injuries = mysqli_real_escape_string($conn, $_POST['course_of_injuridiees']);
    $Type_of_patient_case = mysqli_real_escape_string($conn, $_POST['Type_of_patient_case']);
    $past_medical_history = mysqli_real_escape_string($conn, $_POST['past_medical_history']);
    $doctor_plan_suggestion = mysqli_real_escape_string($conn, $_POST['doctor_plan_suggestion']);
    $family_social_history = mysqli_real_escape_string($conn, $_POST['family_social_history']);
    $Gynocological_history = mysqli_real_escape_string($conn, $_POST['Gynocological_history']);
    $to_come_again_reason = mysqli_real_escape_string($conn, $_POST['to_come_again_reason']);
    $local_examination = mysqli_real_escape_string($conn, $_POST['local_examination']);
    $maincomplain_incrmnt = $_POST['maincomplain_incrmnt'];


    if (isset($_POST['Patient_Type'])) {
        $Patient_Type = $_POST['Patient_Type'];
    } else {
        $Patient_Type = '';
    }
    $Process_Status = "served";

    $ToBeAdmittedReason = $_POST['ToBeAdmittedReason'];
    $ToBeAdmitted = $_POST['ToBeAdmitted'];

    if (isset($_POST['Ward_suggested'])) {
        $Ward_suggested = $_POST['Ward_suggested'];
    } else {
        $Ward_suggested = Null;
    }
    if (isset($_POST['Kin_Name'])) {
        $Kin_Name = $_POST['Kin_Name'];
    } else {
        $Kin_Name = Null;
    }
    if (isset($_POST['Kin_Relationship'])) {
        $Kin_Relationship = $_POST['Kin_Relationship'];
    } else {
        $Kin_Relationship = Null;
    }
    if (isset($_POST['Kin_Phone'])) {
        $Kin_Phone = $_POST['Kin_Phone'];
    } else {
        $Kin_Phone = Null;
    }

    //check if patient selected from consulted and is of the same day
    if (isset($_GET['from_consulted']) && $_GET['from_consulted'] == 'yes') {
        $sql_select_Type_of_patient_case = "SELECT Type_of_patient_case FROM tbl_consultation WHERE consultation_ID='$consultation_ID'";
        $sql_select_Type_of_patient_case_result = mysqli_query($conn, $sql_select_Type_of_patient_case) or die(mysqli_error($conn));
        if (mysqli_num_rows($sql_select_Type_of_patient_case_result) > 0) {
            $case_row = mysqli_fetch_assoc($sql_select_Type_of_patient_case_result);
            $Type_of_patient_case = $case_row['Type_of_patient_case'];
        }
    }

    //die($Type_of_patient_case);

    //    echo $Ward_suggested.'mimi hapa';
    //    exit();
    $Check_In_Date = Date('Y-m-d');
    $add_checkin_details = '';


    if ($course_of_injuries == 'None') {
        $course_of_injuries = 'NULL';
    }
    //Patient Admission
    if ($checkin_exists) {
        $add_checkin_details = "
            UPDATE tbl_check_in_details 
                SET ToBe_Admitted = '$ToBeAdmitted', ToBe_Admitted_Reason = '$ToBeAdmittedReason',Employee_ID='$Employee_ID',consultation_ID='$consultation_ID',Ward_suggested='$Ward_suggested'
                    WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'";
        if ($ToBeAdmitted == "yes") {
            $sql_check_if_arleady_addmited_result = mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_check_if_arleady_addmited_result) <= 0) {
                $sql_insert_into_admision_tbl_result = mysqli_query($conn, "INSERT INTO tbl_admission (Hospital_Ward_ID,Registration_ID,Admission_Employee_ID,Admission_Date_Time,Admission_Status,Kin_Name,Kin_Relationship,Kin_Phone)
                        VALUES('$Ward_suggested','$Registration_ID','$Employee_ID',(select now()),'Admitted','$Kin_Name','$Kin_Relationship','$Kin_Phone')
                    ") or die(mysqli_error($conn));
            } else {
                $Admision_ID = mysqli_fetch_assoc($sql_check_if_arleady_addmited_result)['Admision_ID'];
                $sql_update_tbl_check_in_details_result = mysqli_query($conn, "UPDATE tbl_check_in_details SET Admission_ID='$Admision_ID' WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
            }
        }
        // echo 'Update ';exit;                 
    } else {
        $add_checkin_details = " INSERT INTO  tbl_check_in_details(Registration_ID, Check_In_ID, Folio_Number, Sponsor_ID, ToBe_Admitted, ToBe_Admitted_Reason, Employee_ID, consultation_ID,Ward_suggested)    VALUES('$Registration_ID', '$Check_In_ID', '$Folio_Number', '$Sponsor_ID', '$ToBeAdmitted', '$ToBeAdmittedReason', '$Employee_ID','$consultation_ID','$Ward_suggested')
                    ";
        if ($ToBeAdmitted == "yes") {
            $sql_check_if_arleady_addmited_result = mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status='Admitted' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_check_if_arleady_addmited_result) <= 0) {
                $sql_insert_into_admision_tbl_result = mysqli_query($conn, "INSERT INTO tbl_admission (Hospital_Ward_ID,Registration_ID,Admission_Employee_ID,Admission_Date_Time,Admission_Status,Kin_Name,Kin_Relationship,Kin_Phone)
                        VALUES('$Ward_suggested','$Registration_ID','$Employee_ID',(select now()),'Admitted','$Kin_Name','$Kin_Relationship','$Kin_Phone')
                    ") or die(mysqli_error($conn));
            } else {
                $Admision_ID = mysqli_fetch_assoc($sql_check_if_arleady_addmited_result)['Admision_ID'];
                $sql_update_tbl_check_in_details_result = mysqli_query($conn, "UPDATE tbl_check_in_details SET Admission_ID='$Admision_ID' WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
            }
        }
    }

    //die($add_checkin_details);

    $check_added = mysqli_query($conn, $add_checkin_details) or die(mysqli_error($conn));
    //echo $add_checkin_details;exit;
    if ($check_added) {
        //echo "Check Added";
    } else {
        //echo "ERROR CHECKING";            
    }

    if ($consultation_ID != 0) {
        $maincomplain_duration_incrmnt = $_POST['maincomplain_duration_incrmnt'];

        $sql_clear_previous_history_of_this_patient_by_this_doctor_under_this_consultation_result = mysqli_query($conn, "DELETE FROM tbl_main_complain WHERE consultation_ID='$consultation_ID' AND consultant_id='$employee_ID'") or die(mysqli_error($conn));
        //        if(mysqli_num_rows($sql_clear_previous_history_of_this_patient_by_this_doctor_under_this_consultation_result)>0){
        $index_count = 0;
        foreach ($maincomplain_incrmnt as $main_complain_incr) {

            //THEN SAVE EACH ROW
            if ($main_complain_incr != "") {
                echo $duration = $maincomplain_duration_incrmnt[$index_count];
                mysqli_query($conn, "INSERT INTO tbl_main_complain(main_complain,duration,consultation_ID,consultant_id) VALUES('$main_complain_incr','$duration','$consultation_ID','$employee_ID')") or die(mysqli_error($conn));
                $index_count++;
            }
            //                echo "$index_count";
        }
        //        }
        $hpi_complain = $_POST['hpi_complain'];
        $hpi_duration = $_POST['hpi_duration'];
        $hpi_onset = $_POST['hpi_onset'];
        $hpi_periodicity = $_POST['hpi_periodicity'];
        $hpi_aggravating_factor = $_POST['hpi_aggravating_factor'];
        $hpi_relieving_factor = $_POST['hpi_relieving_factor'];
        $hpi_associated_with = $_POST['hpi_associated_with'];

        //clear prevous hpi history
        mysqli_query($conn, "DELETE FROM tbl_history_of_present_illiness WHERE consultation_ID='$consultation_ID' AND consultant_id='$employee_ID'") or die(mysqli_error($conn));
        $index_count = 0;
        foreach ($hpi_complain as $complain) {
            if ($complain != "") {
                $duration = $hpi_duration[$index_count];
                $onset = $hpi_onset[$index_count];
                $periodicity = $hpi_periodicity[$index_count];
                $aggrevating_factor = $hpi_aggravating_factor[$index_count];
                $relieving_factor = $hpi_relieving_factor[$index_count];
                $associated_with = $hpi_associated_with[$index_count];
                //save hpi
                mysqli_query($conn, "INSERT INTO tbl_history_of_present_illiness(complain,duration,onset,periodicity,aggrevating_factor,relieving_factor,associated_with,consultant_id,consultation_ID,Clinic_consultation_date_time) VALUES('$complain','$duration','$onset','$periodicity','$aggrevating_factor','$relieving_factor','$associated_with','$employee_ID','$consultation_ID',NOW())") or die(mysqli_error($conn));
                $index_count++;
            }
        }
        $update_query = '';
        // if ($Type_of_patient_case != 'continue_case') {

         //Update here
            //++++++++++++++++++ @meshack clinical notes 2020/08/22 THIS DISABLE DELETING PATIENT NOTES ONLY UPDATES ++++++++++++++++++++++++++=
            $doctor_notice_display_max_time= mysqli_fetch_assoc(mysqli_query($conn,"SELECT consulted_patient_display_max_time FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'"))['consulted_patient_display_max_time'];

            $Employee_ID =$_SESSION['userinfo']['Employee_ID'];
            
            if(isset($_GET['from_consulted'])&&$_GET['from_consulted']=='yes'){
            $doctor_consultation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID' AND employee_ID='$Employee_ID' ORDER BY consultation_ID DESC LIMIT 1" ))['consultation_ID'];

                $local_examination1 ="";
                $to_come_again_reason1 ="";
                $doctor_plan_suggestion1 = "";
                $past_medical_history1 ="";
                $family_social_history1 ="";
                $Gynocological_history1 ="";
                $maincomplain1 ="";
                $firstsymptom_date1 ="";
                $Type_of_patient_case1 ="";
                $history_present_illness1 ="";
                $review_of_other_systems1 ="";
                $general_observation1 ="";
                $systemic_observation1 ="";
                $Comment_For_Laboratory1 ="";
                $Comment_For_Radiology1 ="";
                $Comments_For_Procedure1 ="";
                $investigation_comments1 ="";
                $Patient_Type1 ="";
                $remarks1 ="";
                $course_of_injuries1 ="";

                $select_notes_from_history = mysqli_query($conn, "SELECT * FROM tbl_consultation_history WHERE consultation_ID = '$doctor_consultation' ORDER BY consultation_histry_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
                if(mysqli_num_rows($select_notes_from_history)>0){
                    while ($notes_rw = mysqli_fetch_assoc($select_notes_from_history)) {
                        $local_examination1 = $notes_rw['local_examination'];
                        $to_come_again_reason1 = $notes_rw['to_come_again_reason'];
                        $doctor_plan_suggestion1 = $notes_rw['doctor_plan_suggestion'];
                        $past_medical_history1 = $notes_rw['past_medical_history'];
                        $family_social_history1 = $notes_rw['family_social_history'];
                        $Gynocological_history1 = $notes_rw['Gynocological_history'];
                        $maincomplain1 = $notes_rw['maincomplain'];
                        $firstsymptom_date1 = $notes_rw['firstsymptom_date'];
                        $Type_of_patient_case1 = $notes_rw['Type_of_patient_case'];
                        $history_present_illness1 = $notes_rw['history_present_illness'];
                        $review_of_other_systems1 = $notes_rw['review_of_other_systems'];
                        $general_observation1 = $notes_rw['general_observation'];
                        $systemic_observation1 = $notes_rw['systemic_observation'];
                        $Comment_For_Laboratory1 = $notes_rw['Comment_For_Laboratory'];
                        $Comment_For_Radiology1 = $notes_rw['Comment_For_Radiology'];
                        $Nuclearmedicinecomments = $notes_rw['Nuclearmedicinecomments'];
                        $Comments_For_Procedure1 = $notes_rw['Comments_For_Procedure'];
                        $investigation_comments1 = $notes_rw['investigation_comments'];
                        $Patient_Type1 = $notes_rw['Patient_Type'];
                        $remarks1 = $notes_rw['remarks'];
                        $course_of_injuries1 = $notes_rw['course_of_injuries'];

                    }
                }
                $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
                // $insertconsult =  mysqli_query($conn,"INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date,consultation_type,local_examination, to_come_again_reason, doctor_plan_suggestion, past_medical_history, family_social_history, Gynocological_history, maincomplain, firstsymptom_date,  history_present_illness, review_of_other_systems, general_observation, systemic_observation, Comment_For_Laboratory, Comment_For_Radiology,Nuclearmedicinecomments, Comments_For_Procedure, investigation_comments,   remarks, course_of_injuries, Clinic_ID ) VALUES ('$consultation_ID','$employee_ID',NOW(),'$consultation_type', '$local_examination $local_examination1', '$to_come_again_reason $to_come_again_reason1', '$doctor_plan_suggestion $doctor_plan_suggestion1', '$past_medical_history $past_medical_history1', '$family_social_history $family_social_history1', '$Gynocological_history $Gynocological_history1', '$maincomplain $maincomplain1', '$firstsymptom_date $firstsymptom_date1', '$history_present_illness $history_present_illness1', '$review_of_other_systems $review_of_other_systems1', '$general_observation $general_observation1', '$systemic_observation $systemic_observation1', '$Comment_For_Laboratory $Comment_For_Laboratory1', '$Comment_For_Radiology $Comment_For_Radiology1', '$Nuclearmedicinecomments $Nuclearmedicinecomments1', '$Comments_For_Procedure $Comments_For_Procedure1', '$investigation_comments $investigation_comments1',  '$remarks $remarks1', '$course_of_injuries $course_of_injuries1','$doctors_selected_clinic')") or die(mysqli_error($conn));

                $insertconsult =  mysqli_query($conn,"INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date,consultation_type,local_examination, to_come_again_reason, doctor_plan_suggestion, past_medical_history, family_social_history, Gynocological_history, maincomplain, firstsymptom_date,  history_present_illness, review_of_other_systems, general_observation, systemic_observation, Comment_For_Laboratory, Comment_For_Radiology,Nuclearmedicinecomments, Comments_For_Procedure, investigation_comments,   remarks, course_of_injuries, Clinic_ID ) VALUES ('$consultation_ID','$employee_ID',NOW(),'$consultation_type', '$local_examination', '$to_come_again_reason', '$doctor_plan_suggestion', '$past_medical_history', '$family_social_history', '$Gynocological_history', '$maincomplain', '$firstsymptom_date', '$history_present_illness', '$review_of_other_systems', '$general_observation', '$systemic_observation', '$Comment_For_Laboratory', '$Comment_For_Radiology', '$Nuclearmedicinecomments', '$Comments_For_Procedure', '$investigation_comments',  '$remarks', '$course_of_injuries','$doctors_selected_clinic')") or die(mysqli_error($conn));
                if($insertconsult){
                //     echo "yes";
                // }
                // if (mysqli_query($conn,$update_query)) {
                    //$hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
                    // $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
                    
                    // $update_query_hist = "UPDATE tbl_consultation_history SET local_examination='$local_examination',to_come_again_reason='$to_come_again_reason',Clinic_ID='$doctors_selected_clinic',maincomplain='$maincomplain',firstsymptom_date='$firstsymptom_date',
                    // history_present_illness='$history_present_illness',review_of_other_systems='$review_of_other_systems',doctor_plan_suggestion='$doctor_plan_suggestion',past_medical_history='$past_medical_history',family_social_history='$family_social_history',Gynocological_history='$Gynocological_history',
                    // general_observation='$general_observation',systemic_observation='$systemic_observation',
                    // Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',Comments_For_Procedure='$ProcedureComments',Comments_For_Surgery='$SugeryComments',
                    // investigation_comments='$investigation_comments',remarks='$remarks',course_of_injuries='$course_of_injuries' $cons_date, Saved='yes'
                    // WHERE consultation_ID = '$consultation_ID' AND employee_ID='$employee_ID'";
    
                    // mysqli_query($conn,$update_query_hist) or die(mysqli_error($conn));
                    //End Update histry comments
                    //   }  
                    $checkforupdate = mysqli_query($conn,"SELECT Sub_Department_ID,Payment_Item_Cache_List_ID,Check_In_Type,Item_ID,Discount,Price,Quantity,il.Transaction_Type,payment_type,Billing_Type,Sponsor_ID FROM tbl_item_list_cache il JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID WHERE consultation_id='$consultation_ID' AND Status='notsaved'") or die(mysqli_error($conn));
    
                    while ($rowup = mysqli_fetch_array($checkforupdate)) {
                        $Payment_Item_Cache_List_ID = $rowup['Payment_Item_Cache_List_ID'];
                        $Item_ID = $rowup['Item_ID'];
                        $Sponsor_ID = $rowup['Sponsor_ID'];
                        $Check_In_Type = $rowup['Check_In_Type'];
                        $Discount = $rowup['Discount'];
                        $Price = $rowup['Price'];
                        $Quantity = $rowup['Quantity'];
                        $Sub_Department_ID = $rowup['Sub_Department_ID'];
                        $billingType = strtolower($rowup['Billing_Type']);
                        $transactionType = strtolower($rowup['Transaction_Type']);
                        $paymentType = strtolower($rowup['payment_type']);
    
                        $transStatust = false;
                        $checkIfAutoBilling = null;
    
                        if (($billingType == 'outpatient cash' ) || ($billingType == 'outpatient credit' && $transactionType == "cash")) {
                            $transStatust = false;
                        } elseif ($billingType == 'outpatient credit') {
                            $transStatust = true;
    
                            $checkIfAutoBilling = mysqli_query($conn,"SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' and  enab_auto_billing='yes'") or die(mysqli_error($conn));
                        }
    
                        $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
                        $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));
                        //die($finance_department_id);
                       // $finance_department_id=$_SESSION['finance_department_id='];
                        
                        //die($finance_department_id);
                        if ($transStatust && mysqli_num_rows($checkIfAutoBilling) > 0 && !in_array($Check_In_Type, $excludedCheckType)) {
                            
                            if (mysqli_num_rows($check_if_covered2) > 0) {
                                mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                            } else {
                                bill($Sub_Department_ID,$Payment_Item_Cache_List_ID, $Item_ID, $Sponsor_ID, $Check_In_Type, $Discount, $Price, $Quantity, $rowup['Billing_Type']);
                            }
                        } else {
                            if (mysqli_num_rows($check_if_covered2) > 0) {
                                mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                            } else {
                                mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                            }
                        }
                    }


    /****========START FREE ITEMS 26-01-2019 ***/ 
        $Check_In_ID1='';
        $Patient_Bill_ID1='';
        $Payment_Cache_ID1='';
        $Employee_ID1='';
        $Patient_Payment_ID1 ='';
        $Check_In_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID'];       
        $Patient_Bill_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Bill_ID from tbl_patient_bill WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1"))['Patient_Bill_ID'];
        $Payment_Cache_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
        $Employee_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID,Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Employee_ID'];
        $get_items =mysqli_query($conn,"SELECT li.Sub_Department_ID,li.Item_ID,ca.Folio_Number,ca.Sponsor_ID,ca.Sponsor_Name,ca.branch_id,li.Check_In_Type,li.Discount,li.Price,li.Quantity,li.Consultant,li.Consultant_ID,li.Clinic_ID,li.finance_department_id,li.Payment_Item_Cache_List_ID FROM tbl_item_list_cache li,tbl_payment_cache ca WHERE li.Payment_Cache_ID=ca.Payment_Cache_ID AND li.Payment_Cache_ID='$Payment_Cache_ID1'");
        
        $count=0;
        while($rows=mysqli_fetch_assoc($get_items)){
                $Item_ID1 =$rows['Item_ID'];
                $Folio_Number1 =$rows['Folio_Number'];
                $Sponsor_ID1 =$rows['Sponsor_ID'];
                $Sponsor_Name1 =$rows['Sponsor_Name'];
                $branch_id1 =$rows['branch_id'];
                $Check_In_Type1 =$rows['Check_In_Type'];
                $Discount1 =$rows['Discount'];
                $Price1 =$rows['Price'];
                $Quantity1 =$rows['Quantity'];
                $Consultant1 =$rows['Consultant'];
                $Consultant_ID1 =$rows['Consultant_ID'];
                $Clinic_ID1 =$rows['Clinic_ID'];
                $finance_department_id1 =$rows['finance_department_id'];
                $Payment_Item_Cache_List_ID1 =$rows['Payment_Item_Cache_List_ID'];
                $Sub_Department_ID =$rows['Sub_Department_ID'];
    
            $sql_fetch_free_items = mysqli_query($conn,"SELECT * FROM tbl_free_items WHERE item_id='$Item_ID1' AND sponsor_id='$Sponsor_ID1'"); 
            
          
            if(mysqli_num_rows($sql_fetch_free_items)>0){
                            while($itemrow = mysqli_fetch_assoc($sql_fetch_free_items)){
                                
                            $select_patient_payment = mysqli_query($conn,"SELECT Patient_Payment_ID from tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND Payment_Date_And_Time=CURDATE()   AND Billing_Type='Free Item' ORDER BY Patient_Payment_ID  DESC LIMIT 1");
                            if(mysqli_num_rows($select_patient_payment)>0){
                                while($rows = mysqli_fetch_assoc($select_patient_payment)){
                                    $Patient_Payment_ID1 = $rows['Patient_Payment_ID'];
                                    
                                }
                            }else{
                                
                                $create_receipt = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Employee_ID, Payment_Date_And_Time,Folio_Number, Sponsor_ID,Sponsor_Name,Billing_Type, Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)values('$Registration_ID','$Employee_ID1',(select now()),'$Folio_Number1','$Sponsor_ID1','$Sponsor_Name1','Free Item',(select now()),'$branch_id1','$Patient_Bill_ID1','$Check_In_ID1')") or die(mysqli_error($conn)); 
                                $Patient_Payment_ID1 = mysqli_insert_id($conn);
                            }
                            
                            $add_sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity, Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,    Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)values('$Check_In_Type1','$Item_ID1','$Discount1','$Price1','$Quantity1',   'Others','$Consultant1','$Consultant_ID1','$Patient_Payment_ID1',(select now()),'$Clinic_ID1','$finance_department_id1','$Sub_Department_ID')") or die(mysqli_error($conn));
                
                            
                            if($add_sql){
                                $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'paid', Patient_Payment_ID = '$Patient_Payment_ID1', Payment_Date_And_Time =now() where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID1'") or die(mysqli_error($conn));
                            }
                           
                           // }
                            
                        }
            }
                    
    
            // exit;
    
        }
    
    /***   ===================== END FREE ITEMS Eng. meshack ===============**/
                    
    ////  start of msamaha code
                    
                    $age_id="";
                    $start_age="";
                    $end_age="";
                    $Product_Name="";
                    $Item_ID="";
                    $Product_Name="";
    //                $Item_ID="";
                    $laboratory_test_id="";
                    $age_range_id="";
                    $date_of_birth="";
                    
                    $sql_laboratory_test_and_age_range = mysqli_query($conn,"SELECT ae.age_id,ae.start_age,ae.end_age,it.Product_Name,it.Item_ID,at.laboratory_test_id,at.age_range_id FROM tbl_attach_age_laboratory_test at,tbl_age_range ae,tbl_items it WHERE at.laboratory_test_id=it.Item_ID AND it.Item_ID='$Item_ID' AND at.age_range_id=ae.age_id") or die(mysqli_error($conn));
                    
                    while($row=mysqli_fetch_assoc($sql_laboratory_test_and_age_range)){
                          $age_id=$row['age_id'];
                         $start_age=$row['start_age'];
                          $end_age=$row['end_age'];
                          $Product_Name=$row['Product_Name'];
    //                      $Item_ID4=$row['Item_ID'];
                          $laboratory_test_id=$row['laboratory_test_id'];
                          $age_range_id=$row['age_range_id'];
                        
                    }
    //                 echo $Item_ID4;
    //                   echo $Item_ID4;
    
   
                       $sql_check_if_exception = mysqli_query($conn,"SELECT re.Registration_ID,sp.Sponsor_ID FROM tbl_patient_registration re, tbl_sponsor sp WHERE re.Sponsor_ID=sp.Sponsor_ID AND re.Registration_ID='$Registration_ID' AND Exemption='yes'");
                  
                         if(mysqli_num_rows($sql_check_if_exception)>0){
    //                           echo "hjd9ihoijlkd";
                             $date_of_birth=mysqli_fetch_assoc(mysqli_query($conn,"select TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age from tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['age'];
                              
                              $Check_In_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC"))['Check_In_ID'];
                              $female_pregnant=mysqli_fetch_assoc(mysqli_query($conn,"select female_pregnant from tbl_check_in WHERE Registration_ID='$Registration_ID'"))['female_pregnant'];
                             
                             $Patient_Bill_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill WHERE staus ='active' AND Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1"))['Patient_Bill_ID'];
                             $Payment_Cache_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
                            
                             $Employee_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Employee_ID,Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Employee_ID'];
                              
                               
                              $sql_items =mysqli_query($conn,"SELECT li.Item_ID,ca.Folio_Number,ca.Sponsor_ID,ca.Sponsor_Name,ca.branch_id,li.Check_In_Type,li.Discount,li.Price,li.Quantity,li.Consultant,li.Consultant_ID,li.Clinic_ID,li.finance_department_id,li.Payment_Item_Cache_List_ID FROM tbl_item_list_cache li,tbl_payment_cache ca WHERE li.Payment_Cache_ID=ca.Payment_Cache_ID AND li.Payment_Cache_ID='$Payment_Cache_ID'");
                              
                               $count=0;
                                while($rows=mysqli_fetch_assoc($sql_items)){                                              
                                    $Item_ID =$rows['Item_ID'];
                                    $Folio_Number =$rows['Folio_Number'];
                                    $Sponsor_ID =$rows['Sponsor_ID'];
                                    $Sponsor_Name =$rows['Sponsor_Name'];
                                    $branch_id =$rows['branch_id'];
                                    $Check_In_Type =$rows['Check_In_Type'];
                                    $Discount =$rows['Discount'];
                                    $Price =$rows['Price'];
                                    $Quantity =$rows['Quantity'];
                                    $Consultant =$rows['Consultant'];
                                    $Consultant_ID =$rows['Consultant_ID'];
                                    $Clinic_ID =$rows['Clinic_ID'];
                                    $finance_department_id =$rows['finance_department_id'];
                                    $Payment_Item_Cache_List_ID4 =$rows['Payment_Item_Cache_List_ID'];
                                    
                                    if($female_pregnant=="Yes"){                                  
                                        $sql_fetch_items = mysqli_query($conn,"SELECT at.laboratory_test_id,ae.start_age,ae.end_age,cn.female_pregnant FROM tbl_attach_age_laboratory_test at,tbl_age_range ae,tbl_check_in cn WHERE at.laboratory_test_id='$Item_ID' AND at.age_range_id=ae.age_id AND start_age='mjamzito' AND end_age='mjamzito' AND cn.female_pregnant='yes' AND cn.Check_In_ID='$Check_In_ID'");     
                                    }else{
                                        $sql_fetch_items = mysqli_query($conn,"SELECT at.laboratory_test_id,ae.start_age,ae.end_age FROM tbl_attach_age_laboratory_test at,tbl_age_range ae  WHERE at.laboratory_test_id='$Item_ID' AND at.age_range_id=ae.age_id AND $date_of_birth>=start_age AND $date_of_birth<=end_age"); 
                                    }
                                    $value_able = mysqli_num_rows($sql_fetch_items);
    //                                echo $value_able;
                                    if(mysqli_num_rows($sql_fetch_items)>0){//                                 
                                       if($count<=0){
                                         $create_receipt = mysqli_query($conn,"INSERT into tbl_patient_payments(Registration_ID,Employee_ID, Payment_Date_And_Time,Folio_Number,  Sponsor_ID,Sponsor_Name,Billing_Type, Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)    values('$Registration_ID','$Employee_ID',(select now()),'$Folio_Number','$Sponsor_ID','$Sponsor_Name','Outpatient Credit',(select now()),'$branch_id','$Patient_Bill_ID','$Check_In_ID')") or die(mysqli_error($conn)); 
                                         
                                       }
                                    $Patient_Payment_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Payment_ID DESC"))['Patient_Payment_ID'];  
                                         
                                    $sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,    Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,    Transaction_Date_And_Time,Clinic_ID,finance_department_id)values('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity',    'Others','$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()),'$Clinic_ID','$finance_department_id')");
                                    
                                    $Patient_Payment_Item_List_ID3=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' ORDER BY Patient_Payment_ID DESC"))['Patient_Payment_Item_List_ID'];
                                         $Payment_Item_Cache_List_ID4;
                                    if($sql){
                                        $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'paid',  Patient_Payment_ID = '$Patient_Payment_ID',  Payment_Date_And_Time =(select now()) where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID4'") or die(mysqli_error($conn));
                                    }
                                      
                                  }
                                
                                  $count++;
                               }
                              
                         }
    //                
    //                
    ////  end of age msamaha code
    
                    if (isset($_SESSION['curr_receiptNumber']) && $_SESSION['curr_receiptNumber'] == true) {
                        unset($_SESSION['curr_receiptNumber']);
                    }
                    //check if the patient has already consulted and avoid to change the first consulted clinic
                    $sql_check_if_consulted="SELECT already_consulted FROM tbl_patient_payment_item_list WHERE already_consulted='yes' AND Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . " AND  Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') ";
    
                $sql_check_if_consulted_result = mysqli_query($conn,$sql_check_if_consulted) or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_check_if_consulted_result) > 0) {
                    $set_clinic_id = "";
                } else {
                    $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
                    $set_clinic_id = "Clinic_ID=$doctors_selected_clinic,";
                }
    
                $finance_department_id = $_SESSION['finance_department_id'];
                $clinic_location_id = $_SESSION['clinic_location_id'];
                $update_payment = "UPDATE tbl_patient_payment_item_list SET $set_clinic_id finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id',already_consulted='yes',Process_Status = 'served',Status = 'served' WHERE Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . " AND  Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') ";
                    mysqli_query($conn,$update_payment) or die(mysqli_error($conn));
                     
            //=== hii inaweka appointment mandatory for assigned clinic =====
            $url3 = "./clinicpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
            $url2 = "./appointmentPage.php?Registration_ID=$Registration_ID&consultation_ID=$consultation_ID";
            //                }
            //                admit.php?Registration_ID=174867&Check_In_ID=74489&AdmitPatient=AdmitPatientThisPage


            $Clinic_Mandate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Appointment_mandate FROM `tbl_clinic` WHERE Clinic_ID = '$doctors_selected_clinic'"))['Appointment_mandate'];
            $appointment_date = mysqli_query($conn, "SELECT Clinic, doctor FROM tbl_appointment WHERE Set_BY = '$Employee_ID' AND DATE(date_time_transaction) = '$Today' AND patient_No = '$Registration_ID'");
            $appointments = mysqli_num_rows($appointment_date);
            if($appointments > 0){
                while($select = mysqli_fetch_assoc($appointment_date)){
                    $Clinic = $select['Clinic'];
                    $doctor = $select['doctor'];
                    if($Clinic > 0){
                        $cliniccc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic'"))['Clinic_Name'];
                        $location = "To Clinic ".$cliniccc;
                    }else{
                        $cliniccc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctor'"))['Employee_Name']; 
                        $location = "To Doctor ".strtoupper($cliniccc);
                    }
                }
            }

            
    ?>
            <script type='text/javascript'>
                    var Clinic_Mandate = '<?= $Clinic_Mandate; ?>';
                    var appointments = '<?= $appointments; ?>';
                    var Check_In_ID = '<?= $Check_In_ID; ?>';
                    var Registration_ID = '<?= $Registration_ID; ?>';
                    var consultation_ID = '<?= $consultation_ID; ?>';
                    var location_data = '<?= $location; ?>';

                    $.ajax({
                        type: "GET",
                        url: "check_admision_checkin.php",
                        data: {consultation_ID:consultation_ID,Registration_ID:Registration_ID,Check_In_ID:Check_In_ID},
                        success: function (response) {
                            if(response == 200){
                                document.location = '<?php echo $url3; ?>';
                            }else{
                                if(Clinic_Mandate === 'Yes'){
                                    if(appointments > 0){
                                        if(confirm("You've Already added an appointment Today "+ location_data + ", Do you want to add another one? If Not Click Cancel to Proceed with another Patient")){
                                       document.location = '<?php echo $url2; ?>';
                                        }else{
                                       document.location = '<?php echo $url3; ?>';

                                        }
                                    }else{
                                       document.location = '<?php echo $url2; ?>';
                                    }
                                } else {
                                    if (confirm(" Make Appointment To This Patient?")) {
                                        document.location = '<?php echo $url2; ?>';
                                    } else {
                                        alert('Patient Details Updated!');
                                        document.location = '<?php echo $url3; ?>';
                                    }
                                }
                            }
                            
                        }
                    });

    
            </script>
            
        <?php
       
         //===End of Checking  hii inaweka appointment mandatory for assigned clinic =====
                } else {
                   echo "<script>alert('Did not insert into consultation histrory')</script>";
                }
             
            }else {  
                
                /// ==== Not from consulted list by Eng. Muga. 04/10/2021 ========            
            $update_query = "UPDATE tbl_consultation SET local_examination='$local_examination',to_come_again_reason='$to_come_again_reason',doctor_plan_suggestion='$doctor_plan_suggestion',past_medical_history='$past_medical_history',family_social_history='$family_social_history',Gynocological_history='$Gynocological_history',maincomplain='$maincomplain',firstsymptom_date='$firstsymptom_date',Type_of_patient_case='$Type_of_patient_case',
                                history_present_illness='$history_present_illness',review_of_other_systems='$review_of_other_systems',
                                general_observation='$general_observation',systemic_observation='$systemic_observation',
                                Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',Nuclearmedicinecomments='$Nuclearmedicinecomments',Comments_For_Procedure='$ProcedureComments',Comments_For_Surgery='$SugeryComments',
                                investigation_comments='$investigation_comments',Patient_Type='$Patient_Type',remarks='$remarks',course_of_injuries='$course_of_injuries',Process_Status='$Process_Status' $Consultation_Date_And_Time
                                WHERE consultation_ID = '$consultation_ID'";

            if (mysqli_query($conn,$update_query)) {
                //$hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
                $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
                
                $update_query_hist = "UPDATE tbl_consultation_history SET local_examination='$local_examination',to_come_again_reason='$to_come_again_reason',Clinic_ID='$doctors_selected_clinic',maincomplain='$maincomplain',firstsymptom_date='$firstsymptom_date',
                history_present_illness='$history_present_illness',review_of_other_systems='$review_of_other_systems',doctor_plan_suggestion='$doctor_plan_suggestion',past_medical_history='$past_medical_history',family_social_history='$family_social_history',Gynocological_history='$Gynocological_history',
                general_observation='$general_observation',systemic_observation='$systemic_observation',
                Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',Nuclearmedicinecomments='$Nuclearmedicinecomments', Comments_For_Procedure='$ProcedureComments',Comments_For_Surgery='$SugeryComments',
                investigation_comments='$investigation_comments',remarks='$remarks',course_of_injuries='$course_of_injuries' $cons_date, Saved='yes'
                WHERE consultation_ID = '$consultation_ID' AND employee_ID='$employee_ID'";

                mysqli_query($conn,$update_query_hist) or die(mysqli_error($conn));
                //End Update histry comments
                //   }  
                $checkforupdate = mysqli_query($conn,"SELECT Sub_Department_ID,Payment_Item_Cache_List_ID,Check_In_Type,Item_ID,Discount,Price,Quantity,il.Transaction_Type,payment_type,Billing_Type,Sponsor_ID FROM tbl_item_list_cache il JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID WHERE consultation_id='$consultation_ID' AND Status='notsaved'") or die(mysqli_error($conn));

                while ($rowup = mysqli_fetch_array($checkforupdate)) {
                    $Payment_Item_Cache_List_ID = $rowup['Payment_Item_Cache_List_ID'];
                    $Item_ID = $rowup['Item_ID'];
                    $Sponsor_ID = $rowup['Sponsor_ID'];
                    $Check_In_Type = $rowup['Check_In_Type'];
                    $Discount = $rowup['Discount'];
                    $Price = $rowup['Price'];
                    $Quantity = $rowup['Quantity'];
                    $Sub_Department_ID = $rowup['Sub_Department_ID'];
                    $billingType = strtolower($rowup['Billing_Type']);
                    $transactionType = strtolower($rowup['Transaction_Type']);
                    $paymentType = strtolower($rowup['payment_type']);

                    $transStatust = false;
                    $checkIfAutoBilling = null;

                    if (($billingType == 'outpatient cash' ) || ($billingType == 'outpatient credit' && $transactionType == "cash")) {
                        $transStatust = false;
                    } elseif ($billingType == 'outpatient credit') {
                        $transStatust = true;

                        $checkIfAutoBilling = mysqli_query($conn,"SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' and  enab_auto_billing='yes'") or die(mysqli_error($conn));
                    }

                    $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
                    $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));
                    
    /****========START FREE ITEMS 26-01-2019 ***/ 
        $Check_In_ID1='';
        $Patient_Bill_ID1='';
        $Payment_Cache_ID1='';
        $Employee_ID1='';
        $Patient_Payment_ID1 ='';
        $Check_In_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID'];       
        $Patient_Bill_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Bill_ID from tbl_patient_bill WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1"))['Patient_Bill_ID'];
        $Payment_Cache_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
        $Employee_ID1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID,Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Employee_ID'];
        $get_items =mysqli_query($conn,"SELECT li.Sub_Department_ID,li.Item_ID,ca.Folio_Number,ca.Sponsor_ID,ca.Sponsor_Name,ca.branch_id,li.Check_In_Type,li.Discount,li.Price,li.Quantity,li.Consultant,li.Consultant_ID,li.Clinic_ID,li.finance_department_id,li.Payment_Item_Cache_List_ID FROM tbl_item_list_cache li,tbl_payment_cache ca WHERE li.Payment_Cache_ID=ca.Payment_Cache_ID AND li.Payment_Cache_ID='$Payment_Cache_ID1'");
        
        $count=0;
        while($rows=mysqli_fetch_assoc($get_items)){
                $Item_ID1 =$rows['Item_ID'];
                $Folio_Number1 =$rows['Folio_Number'];
                $Sponsor_ID1 =$rows['Sponsor_ID'];
                $Sponsor_Name1 =$rows['Sponsor_Name'];
                $branch_id1 =$rows['branch_id'];
                $Check_In_Type1 =$rows['Check_In_Type'];
                $Discount1 =$rows['Discount'];
                $Price1 =$rows['Price'];
                $Quantity1 =$rows['Quantity'];
                $Consultant1 =$rows['Consultant'];
                $Consultant_ID1 =$rows['Consultant_ID'];
                $Clinic_ID1 =$rows['Clinic_ID'];
                $finance_department_id1 =$rows['finance_department_id'];
                $Payment_Item_Cache_List_ID1 =$rows['Payment_Item_Cache_List_ID'];
                $Sub_Department_ID =$rows['Sub_Department_ID'];
    
            $sql_fetch_free_items = mysqli_query($conn,"SELECT * FROM tbl_free_items WHERE item_id='$Item_ID1' AND sponsor_id='$Sponsor_ID1'"); 
            
          
            if(mysqli_num_rows($sql_fetch_free_items)>0){
                            while($itemrow = mysqli_fetch_assoc($sql_fetch_free_items)){
                                
                            $select_patient_payment = mysqli_query($conn,"SELECT Patient_Payment_ID from tbl_patient_payments WHERE Registration_ID='$Registration_ID' AND Payment_Date_And_Time=CURDATE()   AND Billing_Type='Free Item' ORDER BY Patient_Payment_ID  DESC LIMIT 1");
                            if(mysqli_num_rows($select_patient_payment)>0){
                                while($rows = mysqli_fetch_assoc($select_patient_payment)){
                                    $Patient_Payment_ID1 = $rows['Patient_Payment_ID'];
                                    
                                }
                            }else{
                                
                                $create_receipt = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Employee_ID, Payment_Date_And_Time,Folio_Number, Sponsor_ID,Sponsor_Name,Billing_Type, Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)values('$Registration_ID','$Employee_ID1',(select now()),'$Folio_Number1','$Sponsor_ID1','$Sponsor_Name1','Free Item',(select now()),'$branch_id1','$Patient_Bill_ID1','$Check_In_ID1')") or die(mysqli_error($conn)); 
                                $Patient_Payment_ID1 = mysqli_insert_id($conn);
                            }
                            
                            $add_sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity, Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,    Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)values('$Check_In_Type1','$Item_ID1','$Discount1','$Price1','$Quantity1',   'Others','$Consultant1','$Consultant_ID1','$Patient_Payment_ID1',(select now()),'$Clinic_ID1','$finance_department_id1','$Sub_Department_ID')") or die(mysqli_error($conn));
                
                            
                            if($add_sql){
                                $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'paid', Patient_Payment_ID = '$Patient_Payment_ID1', Payment_Date_And_Time =now() where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID1'") or die(mysqli_error($conn));
                            }
                           
                           // }
                            
                        }
            }
                    
    
            // exit;
    
        }
    
    /***   ===================== END FREE ITEMS Eng. meshack ===============**/
    
                    if ($transStatust && mysqli_num_rows($checkIfAutoBilling) > 0 && !in_array($Check_In_Type, $excludedCheckType)) {
                        
                        if (mysqli_num_rows($check_if_covered2) > 0) {
                            mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                        } else {
                            bill($Sub_Department_ID,$Payment_Item_Cache_List_ID, $Item_ID, $Sponsor_ID, $Check_In_Type, $Discount, $Price, $Quantity, $rowup['Billing_Type']);
                        }
                    } else {
                        if (mysqli_num_rows($check_if_covered2) > 0) {
                            mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                        } else {
                            mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active',Clinic_ID='$doctors_selected_clinic',finance_department_id='$finance_department_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));
                        }
                    }
                }
       
////  start of msamaha code
                
                $age_id="";
                $start_age="";
                $end_age="";
                $Product_Name="";
                $Item_ID="";
                $Product_Name="";
//                $Item_ID="";
                $laboratory_test_id="";
                $age_range_id="";
                $date_of_birth="";
                
                $sql_laboratory_test_and_age_range = mysqli_query($conn,"SELECT ae.age_id,ae.start_age,ae.end_age,it.Product_Name,it.Item_ID,at.laboratory_test_id,at.age_range_id FROM tbl_attach_age_laboratory_test at,tbl_age_range ae,tbl_items it WHERE at.laboratory_test_id=it.Item_ID AND at.age_range_id=ae.age_id");
                
                while($row=mysqli_fetch_assoc($sql_laboratory_test_and_age_range)){
                      $age_id=$row['age_id'];
                     $start_age=$row['start_age'];
                      $end_age=$row['end_age'];
                      $Product_Name=$row['Product_Name'];
//                      $Item_ID4=$row['Item_ID'];
                      $laboratory_test_id=$row['laboratory_test_id'];
                      $age_range_id=$row['age_range_id'];
                    
                }
//                 echo $Item_ID4;
//                   echo $Item_ID4;
                   $sql_check_if_exception = mysqli_query($conn,"SELECT re.Registration_ID,sp.Sponsor_ID FROM tbl_patient_registration re, tbl_sponsor sp WHERE re.Sponsor_ID=sp.Sponsor_ID AND re.Registration_ID='$Registration_ID' AND Exemption='yes'");
              
                     if(mysqli_num_rows($sql_check_if_exception)>0){
//                           echo "hjd9ihoijlkd";
                         $date_of_birth=mysqli_fetch_assoc(mysqli_query($conn,"select TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age from tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['age'];
                          
                          $Check_In_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Check_In_ID from tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC"))['Check_In_ID'];
                          $female_pregnant=mysqli_fetch_assoc(mysqli_query($conn,"select female_pregnant from tbl_check_in WHERE Registration_ID='$Registration_ID'"))['female_pregnant'];
                         
                         $Patient_Bill_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill WHERE staus ='active' AND Registration_ID='$Registration_ID' ORDER BY Patient_Bill_ID DESC LIMIT 1"))['Patient_Bill_ID'];
                         $Payment_Cache_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC"))['Payment_Cache_ID'];
                        
                         $Employee_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Employee_ID,Payment_Cache_ID from tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Employee_ID'];
                          
                           
                          $sql_items =mysqli_query($conn,"SELECT li.Item_ID,ca.Folio_Number,ca.Sponsor_ID,ca.Sponsor_Name,ca.branch_id,li.Check_In_Type,li.Discount,li.Price,li.Quantity,li.Consultant,li.Consultant_ID,li.Clinic_ID,li.finance_department_id,li.Payment_Item_Cache_List_ID FROM tbl_item_list_cache li,tbl_payment_cache ca WHERE li.Payment_Cache_ID=ca.Payment_Cache_ID AND li.Payment_Cache_ID='$Payment_Cache_ID'");
                          
                           $count=0;
                            while($rows=mysqli_fetch_assoc($sql_items)){                                              
                                $Item_ID =$rows['Item_ID'];
                                $Folio_Number =$rows['Folio_Number'];
                                $Sponsor_ID =$rows['Sponsor_ID'];
                                $Sponsor_Name =$rows['Sponsor_Name'];
                                $branch_id =$rows['branch_id'];
                                $Check_In_Type =$rows['Check_In_Type'];
                                $Discount =$rows['Discount'];
                                $Price =$rows['Price'];
                                $Quantity =$rows['Quantity'];
                                $Consultant =$rows['Consultant'];
                                $Consultant_ID =$rows['Consultant_ID'];
                                $Clinic_ID =$rows['Clinic_ID'];
                                $finance_department_id =$rows['finance_department_id'];
                                $Payment_Item_Cache_List_ID4 =$rows['Payment_Item_Cache_List_ID'];
                                
                                if($female_pregnant=="Yes"){                                  
                                    $sql_fetch_items = mysqli_query($conn,"SELECT at.laboratory_test_id,ae.start_age,ae.end_age,cn.female_pregnant FROM tbl_attach_age_laboratory_test at,tbl_age_range ae,tbl_check_in cn WHERE at.laboratory_test_id='$Item_ID' AND at.age_range_id=ae.age_id AND start_age='mjamzito' AND end_age='mjamzito' AND cn.female_pregnant='yes' AND cn.Check_In_ID='$Check_In_ID'");     
                                }else{
                                    $sql_fetch_items = mysqli_query($conn,"SELECT at.laboratory_test_id,ae.start_age,ae.end_age FROM tbl_attach_age_laboratory_test at,tbl_age_range ae  WHERE at.laboratory_test_id='$Item_ID' AND at.age_range_id=ae.age_id AND $date_of_birth>=start_age AND $date_of_birth<=end_age"); 
                                }
                                $value_able = mysqli_num_rows($sql_fetch_items);
//                                echo $value_able;
                                if(mysqli_num_rows($sql_fetch_items)>0){//                                 
                                   if($count<=0){
                                     $create_receipt = mysqli_query($conn,"INSERT into tbl_patient_payments(Registration_ID,Employee_ID, Payment_Date_And_Time,Folio_Number,  Sponsor_ID,Sponsor_Name,Billing_Type, Receipt_Date,Branch_ID,Patient_Bill_ID,Check_In_ID)    values('$Registration_ID','$Employee_ID',(select now()),'$Folio_Number','$Sponsor_ID','$Sponsor_Name','Outpatient Credit',(select now()),'$branch_id','$Patient_Bill_ID','$Check_In_ID')") or die(mysqli_error($conn)); 
                                     
                                   }
                                $Patient_Payment_ID=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Payment_ID DESC"))['Patient_Payment_ID'];  
                                     
                                $sql = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,    Patient_Direction,Consultant,Consultant_ID,Patient_Payment_ID,    Transaction_Date_And_Time,Clinic_ID,finance_department_id)values('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity',    'Others','$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()),'$Clinic_ID','$finance_department_id')");
                                
                                $Patient_Payment_Item_List_ID3=mysqli_fetch_assoc(mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' ORDER BY Patient_Payment_ID DESC"))['Patient_Payment_Item_List_ID'];
                                     $Payment_Item_Cache_List_ID4;
                                if($sql){
                                    $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set Status = 'paid',  Patient_Payment_ID = '$Patient_Payment_ID',  Payment_Date_And_Time =(select now()) where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID4'") or die(mysqli_error($conn));
                                }
                                  
                              }
                            
                              $count++;
                           }
                          
                     }
//                
//                
////  end of age msamaha code

                if (isset($_SESSION['curr_receiptNumber']) && $_SESSION['curr_receiptNumber'] == true) {
                    unset($_SESSION['curr_receiptNumber']);
                }
                //check if the patient has already consulted and avoid to change the first consulted clinic
                $sql_check_if_consulted="SELECT already_consulted FROM tbl_patient_payment_item_list WHERE already_consulted='yes' AND Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . " AND  Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') ";

            $sql_check_if_consulted_result = mysqli_query($conn,$sql_check_if_consulted) or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_check_if_consulted_result) > 0) {
                $set_clinic_id = "";
            } else {
                $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
                $set_clinic_id = "Clinic_ID=$doctors_selected_clinic,";
            }

            $finance_department_id = $_SESSION['finance_department_id'];
            $clinic_location_id = $_SESSION['clinic_location_id'];
            $update_payment = "UPDATE tbl_patient_payment_item_list SET $set_clinic_id finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id',already_consulted='yes',Process_Status = 'served',Status = 'served' WHERE Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . " AND  Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') ";
                mysqli_query($conn,$update_payment) or die(mysqli_error($conn));
               
            //=== hii inaweka appointment mandatory for assigned clinic =====
            $url3 = "./clinicpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
            $url2 = "./appointmentPage.php?Registration_ID=$Registration_ID&consultation_ID=$consultation_ID";
            //                }
            //                admit.php?Registration_ID=174867&Check_In_ID=74489&AdmitPatient=AdmitPatientThisPage


            $Clinic_Mandate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Appointment_mandate FROM `tbl_clinic` WHERE Clinic_ID = '$doctors_selected_clinic'"))['Appointment_mandate'];
            $appointment_date = mysqli_query($conn, "SELECT Clinic, doctor FROM tbl_appointment WHERE Set_BY = '$Employee_ID' AND DATE(date_time_transaction) = '$Today' AND patient_No = '$Registration_ID'");
            $appointments = mysqli_num_rows($appointment_date);
            if($appointments > 0){
                while($select = mysqli_fetch_assoc($appointment_date)){
                    $Clinic = $select['Clinic'];
                    $doctor = $select['doctor'];

                    if($Clinic > 0){
                        $cliniccc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic'"))['Clinic_Name'];
                        $location = "To Clinic ".$cliniccc;
                    }else{
                        $cliniccc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctor'"))['Employee_Name']; 
                        $location = "To Doctor ".strtoupper($cliniccc);
                    }
                }
            }
    ?>
            <script type='text/javascript'>
                    var Clinic_Mandate = '<?= $Clinic_Mandate; ?>';
                    var appointments = '<?= $appointments; ?>';
                    var Check_In_ID = '<?= $Check_In_ID; ?>';
                    var Registration_ID = '<?= $Registration_ID; ?>';
                    var consultation_ID = '<?= $consultation_ID; ?>';
                    var location_data = '<?= $location; ?>';

                    $.ajax({
                        type: "GET",
                        url: "check_admision_checkin.php",
                        data: {consultation_ID:consultation_ID,Registration_ID:Registration_ID,Check_In_ID:Check_In_ID},
                        success: function (response) {
                            if(response == 200){
                                document.location = '<?php echo $url3; ?>';
                            }else{
                                if(Clinic_Mandate === 'Yes'){
                                    if(appointments > 0){
                                        if(confirm("You've Already added an appointment Today "+ location_data + ", Do you want to add another one? If Not Click Cancel to Proceed with another Patient")){
                                        document.location = '<?php echo $url2; ?>';
                                        }else{
                                        document.location = '<?php echo $url3; ?>';

                                        }
                                    }else{
                                        document.location = '<?php echo $url2; ?>';
                                    }
                                } else {
                                    if (confirm(" Make Appointment To This Patient?")) {
                                    document.location = '<?php echo $url2; ?>';
                                    } else {
                                        alert('Patient Details Updated!');
                                        document.location = '<?php echo $url3; ?>';
                                    }
                                }
                            }
                            
                        }
                    });

    
            </script>
            
        <?php
         //===End of Checking  hii inaweka appointment mandatory for assigned clinic =====
            } else {
                die(mysqli_error($conn));
            }
        }  
        /***** END OF CHECK ELSE STATEMENT FROM CONSULTED NO*****/
        }
    }
  
    function bill($Sub_Department_ID, $Payment_Item_Cache_List_ID, $Item_ID, $Sponsor_ID, $Check_In_Type, $Discount, $Price, $Quantity, $billingType)
    {
        global $conn;
        $has_no_folio = false;
        $Folio_Number = '';
        $Registration_ID = $_GET['Registration_ID'];
        $sql_check = mysqli_query($conn, "select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

        $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];


        $select = mysqli_query($conn, "select Folio_Number,Guarantor_Name,Claim_Form_Number from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

        $rows = mysqli_fetch_array($select);
        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Folio_Number = $rows['Folio_Number'];
        $Sponsor_Name = $rows['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rows['Claim_Form_Number'];

        if (!isset($_SESSION['curr_receiptNumber'])) {
            include("./includes/Get_Patient_Transaction_Number.php");
            $sql = "INSERT into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                              (select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID',
                                              '$Sponsor_Name','$billingType',(select now()),'$Branch_ID','$Patient_Bill_ID')";

            $insert = mysqli_query($conn, $sql) or die(mysqli_error($conn));

            $_SESSION['curr_receiptNumber'] = true;
        }

        $select_details = mysqli_query($conn, "SELECT Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
        $num_row = mysqli_num_rows($select_details);
        if ($num_row > 0) {
            $details_data = mysqli_fetch_row($select_details);
            $Patient_Payment_ID = $details_data[0];
            $Receipt_Date = $details_data[1];
        } else {
            $Patient_Payment_ID = 0;
            $Receipt_Date = '';
        }

        $Consultant = '';
        $Consultant_ID = $Employee_ID;

        //insert data to tbl_patient_payment_item_list
        if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
            $stat = 'active';

            if ($Check_In_Type == 'Others') {
                $stat = 'served';
            }
            $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
            $finance_department_id = $_SESSION['finance_department_id'];
            $clinic_location_id = $_SESSION['clinic_location_id'];
            mysqli_query($conn, "UPDATE tbl_item_list_cache SET Status='$stat',finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id',Clinic_ID='$doctors_selected_clinic',Patient_Payment_ID='$Patient_Payment_ID',Payment_Date_And_Time=NOW() WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='notsaved'") or die(mysqli_error($conn));

            $insert = mysqli_query($conn, "insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,ItemOrigin,Clinic_ID,finance_department_id,Sub_Department_ID)
                                                        values('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW(),'Doctor','$doctors_selected_clinic','$finance_department_id','$Sub_Department_ID')") or die(mysqli_error($conn));
        }
    }

    //Retrieve doctors participated
    $num_doctors = 0;
    //if ($hospitalConsultType == 'One patient to many doctor') {
    $rsDoc = mysqli_query($conn, "SELECT COUNT(ch.consultation_histry_ID)AS docCount FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID WHERE c.consultation_ID=$consultation_ID AND c.Registration_ID=$Registration_ID AND ch.employee_ID != $employee_ID") or die(mysqli_error($conn));
    $num_doctors = mysqli_fetch_assoc($rsDoc)['docCount'];
    //}
            ?>
        <center>
            <table width='100%' style='background: #006400 !important;color: white;'>
                <tr>
                    <td>
                        <center>
                            <b>DOCTORS OUTPATIENT WORKPAGE: CLINICAL NOTES</b>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>
                        <center>
                            <?php echo strtoupper($Patient_Name) . ', ' . strtoupper($Gender) . ', (' . $age . '), ' . strtoupper($Guarantor_Name); ?>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
        <?php
        $check_box = "";
        if (isset($_GET['previous_notes'])) {
            $previous_notes = $_GET['previous_notes'];
            if ($previous_notes == "yes") {
                $check_box = "checked='checked'";
            } else {
                $check_box = "";
            }
        }
        if (isset($_GET['from_consulted']) && $_GET['from_consulted'] == 'yes') {
            $display_prv_nots_opt = "";
        } else {
            $display_prv_nots_opt = "style='display:none'";
        }
        //echo $display_prv_nots_opt;

        
        ?>

        <fieldset <?= $display_prv_nots_opt ?>>
            <center>
                <div class="row">
                    <div class="col-xs-4"></div>
                    <div class="col-xs-3">
                        <table>
                            <tr>
                                <td>Display My Previous Notes</td>
                                <td style="background: #CCCCCC">
                                    <input class="pull-left" style="height: 30px;width:30px;" type="checkbox" <?= $check_box ?> name="allow_previous_notes_btn" id="allow_previous_notes_btn">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </center>
        </fieldset>
        <?php
        if (isset($_GET['Registration_ID'])) {
            $Registration_ID_ = $_GET['Registration_ID'];
        }
        if (isset($_GET['Patient_Payment_ID'])) {
            $Patient_Payment_ID_ = $_GET['Patient_Payment_ID'];
        }
        if (isset($_GET['Patient_Payment_Item_List_ID'])) {
            $Patient_Payment_Item_List_ID_ = $_GET['Patient_Payment_Item_List_ID'];
        }
        ?>
        <script>
            $("#allow_previous_notes_btn").click(function() {
                if ($("#allow_previous_notes_btn").is(':checked')) {
                    document.location = "clinicalnotes.php?Registration_ID=<?= $Registration_ID_ ?>&Patient_Payment_ID=<?= $Patient_Payment_ID_ ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID_ ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&previous_notes=yes&from_consulted=yes";
                } else {
                    document.location = "clinicalnotes.php?Registration_ID=<?= $Registration_ID_ ?>&Patient_Payment_ID=<?= $Patient_Payment_ID_ ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID_ ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&previous_notes=no&from_consulted=yes";
                }
            });
        </script>
        <fieldset>
            <form action='#' name='clinicalnotes' id='clinicalnotes' method='post'>
                <input type="text" name="submit_submit" class="hide" />
                <?php
                    $select_payment_cache = "SELECT * FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
                                             WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID
                                             AND i.Item_ID = ilc.Item_ID AND pc.consultation_id = '$consultation_ID' 
                                             AND ilc.Consultant_ID=$employee_ID";

                    $cache_result = mysqli_query($conn, $select_payment_cache);
                    $Radiology = '';
                    $Laboratory = '';
                    $Pharmacy = "";
                    $Procedure = "";
                    $Surgery = "";
                    $Others = "";

                    if (mysqli_num_rows($cache_result) > 0) {
                        while ($cache_row = mysqli_fetch_assoc($cache_result)) {
                            if ($cache_row['Check_In_Type'] == 'Radiology') {
                                $Radiology .= ' ' . $cache_row['Product_Name'] . ';';
                            }
                            if ($cache_row['Check_In_Type'] == 'Laboratory') {
                                $Laboratory .= ' ' . $cache_row['Product_Name'] . ';';
                            }
                            if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                                if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                                    $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                                } else {
                                    $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                                }
                            }
                            if ($cache_row['Check_In_Type'] == 'Procedure') {
                                $Procedure .= ' ' . $cache_row['Product_Name'] . ';';
                            }
                            if ($cache_row['Check_In_Type'] == 'Surgery') {
                                $Surgery .= ' ' . $cache_row['Product_Name'] . ';';
                            }
                            if ($cache_row['Check_In_Type'] == 'Others') {
                                $Others .= ' ' . $cache_row['Product_Name'] . ';';
                            }
                        }
                    }
                    

                $filter_consultation_id = $consultation_ID;
                if ($hospitalConsultType == 'One patient to many doctor') {
                    $Employ_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    
                    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
                    $Registration_ID = $_GET['Registration_ID'];
                    $consultation_hisory_query = "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)<>CURDATE() AND employee_ID='$employee_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'";
                    $consultation_hisory_query_result = mysqli_query($conn, $consultation_hisory_query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($consultation_hisory_query_result) > 0) {
                        $cons_id_row = mysqli_fetch_assoc($consultation_hisory_query_result);
                        $filter_consultation_id = $cons_id_row['consultation_ID'];
                    }
                    if (isset($_GET['previous_notes'])) {
                        $previous_notes = $_GET['previous_notes'];
                        if ($previous_notes == "yes") {
                            $filter_cond =  " c.consultation_ID='$filter_consultation_id' AND maincomplain != '' AND DATE(cons_hist_Date)<>CURDATE() AND ";
                        } else {
                            $filter_cond =  " c.consultation_ID='$consultation_ID' AND maincomplain != '' AND DATE(cons_hist_Date)=CURDATE() AND ";
                            $filter_consultation_id = $consultation_ID;
                        }
                    } else {
                        $filter_cond =  " c.consultation_ID='$consultation_ID' AND maincomplain != '' AND DATE(cons_hist_Date)=CURDATE() AND ";
                        $filter_consultation_id = $consultation_ID;
                    }

                    ///////////////////////////

                    ///////////////////////////////////////////////////////////////////
                    $select_consultation = "SELECT * FROM tbl_consultation_history  c WHERE $filter_cond employee_ID='$Employ_ID' ORDER BY consultation_histry_ID DESC ";
                    $diagnosis_qr = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d
            WHERE dc.disease_ID = d.disease_ID AND dc.consultation_ID = '$filter_consultation_id'";
                    //           $select_consultation = "SELECT * FROM tbl_consultation  c WHERE c.consultation_ID=$consultation_ID ";
                } else {
                    $diagnosis_qr = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d
            WHERE dc.employee_ID='$employee_ID' AND 
            dc.disease_ID = d.disease_ID AND dc.consultation_ID ='$consultation_ID'";
                    $select_consultation = "SELECT * FROM tbl_consultation_history  c WHERE c.consultation_ID=$consultation_ID AND c.employee_ID='$employee_ID'";
                }

                $consultation_result = mysqli_query($conn, $select_consultation) or die(mysqli_error($conn));


                if (mysqli_num_rows($consultation_result) > 0) {
                    $consultation_row = @mysqli_fetch_assoc($consultation_result);
                    $maincomplain = $consultation_row['maincomplain'];
                    $firstsymptom_date = $consultation_row['firstsymptom_date'];
                    $history_present_illness = $consultation_row['history_present_illness'];
                    $review_of_other_systems = $consultation_row['review_of_other_systems'];
                    $general_observation = $consultation_row['general_observation'];
                    $systemic_observation = $consultation_row['systemic_observation'];
                    $Comment_For_Laboratory = $consultation_row['Comment_For_Laboratory'];
                    $Comment_For_Radiology = $consultation_row['Comment_For_Radiology'];
                    $Comments_For_Procedure = $consultation_row['Comments_For_Procedure'];
                    $Comments_For_Surgery = $consultation_row['Comments_For_Surgery'];
                    $investigation_comments = $consultation_row['investigation_comments'];
                    $remarks = $consultation_row['remarks'];
                    $past_medical_history = $consultation_row['past_medical_history'];
                    $doctor_plan_suggestion = $consultation_row['doctor_plan_suggestion'];
                    $family_social_history = $consultation_row['family_social_history'];
                    $Gynocological_history = $consultation_row['Gynocological_history'];
                    $to_come_again_reason = $consultation_row['to_come_again_reason'];
                    $local_examination = $consultation_row['local_examination'];
                    // $Patient_Type = $consultation_row['Patient_Type'];
                } else {
                    $maincomplain = '';
                    $firstsymptom_date = '';
                    $history_present_illness = '';
                    $review_of_other_systems = '';
                    $general_observation = '';
                    $systemic_observation = '';
                    $Comment_For_Laboratory = '';
                    $Comment_For_Radiology = '';
                    $investigation_comments = '';
                    $remarks = '';
                    $Patient_Type = '';
                    $doctor_plan_suggestion = "";
                    $past_medical_history = "";
                    $family_social_history = "";
                    $Gynocological_history = "";
                    $to_come_again_reason = "";
                    $local_examination = "";
                }

                $emp = '';

                //selecting diagnosois

                $result = mysqli_query($conn, $diagnosis_qr) or die(mysqli_error($conn));
                $provisional_diagnosis = '';
                $diferential_diagnosis = '';
                $diagnosis = '';
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $disease_code = $row['disease_code'];
                        if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                            $provisional_diagnosis .= ' ' . $row['disease_name'] . '('.$disease_code.'); ';
                        }
                        if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                            $diferential_diagnosis .= ' ' . $row['disease_name'] . '('.$disease_code.'); ';
                        }
                        if ($row['diagnosis_type'] == 'diagnosis') {
                            $diagnosis .= ' ' . $row['disease_name'] . '('.$disease_code.'); ';
                        }
                    }
                }

                $courseinjury = mysqli_query($conn, "SELECT * FROM tbl_hospital_course_injuries WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));
                $opt = '';
                while ($row = mysqli_fetch_array($courseinjury)) {

                    $opt .= "<option value='" . $row['hosp_course_injury_ID'] . "'>" . $row['course_injury'] . "</option>";
                }

                //procedure info
                $proce_payment_cache_ID = '';
                $Status_proc = '';
                $Proce_Billing_Type = '';
                $transaction_Type_proc = '';
                $statusMsg_proc = '';

                $qr = "select pc.Billing_Type,ilc.Transaction_Type,pc.payment_cache_ID, ilc.Status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sub_department sd where
                pc.payment_cache_id = ilc.payment_cache_id and sd.sub_department_id = ilc.sub_department_id  and consultation_id = '$consultation_ID'
              ORDER BY ilc.Payment_Item_Cache_List_ID DESC LIMIT 1";
                //$select_payment_cache_ID = "SELECT ilc.status,pc.Billing_Type,ilc.Transaction_Type,pc.payment_cache_ID FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc WHERE consultation_id = $consultation_ID ORDER BY payment_cache_ID DESC LIMIT 1";
                $cache_result2 = mysqli_query($conn, $qr) or die(mysqli_error($conn));
                $proce_payment_cache_ID = 0;

                if (mysqli_num_rows($cache_result2) > 0) {

                    $resultproc = mysqli_fetch_array($cache_result2);
                    // print_r($resultproc);
                    $proce_payment_cache_ID = $resultproc['payment_cache_ID'];
                    $Status_proc = $resultproc['Status'];
                    $Proce_Billing_Type = $resultproc['Billing_Type'];
                    $transaction_Type_proc = $resultproc['Transaction_Type'];
                    $Sub_Department_ID = $resultproc['Sub_Department_ID'];

                    if ($Proce_Billing_Type == 'Outpatient Cash' && $Status_proc == "active") {
                        $statusMsg_proc = "Not Paid";
                    } elseif ($Proce_Billing_Type == 'Outpatient Cash' && $Status_proc == "paid") {
                        $statusMsg_proc = "Paid";
                    } elseif ($Proce_Billing_Type == 'Outpatient Credit' && $transaction_Type_proc == "cash" && $Status_proc == "paid") {
                        $statusMsg_proc = "Paid";
                    } elseif ($Proce_Billing_Type == 'Outpatient Credit' && $transaction_Type_proc == "cash" && $Status_proc == "active") {
                        $statusMsg_proc = "Not Paid";
                    } elseif ($Proce_Billing_Type == 'Outpatient Credit' && $transaction_Type_proc == "credit" && $Status_proc == "active") {
                        $statusMsg_proc = "Not Bill";
                    } elseif ($Proce_Billing_Type == 'Inpatient Cash' || $Proce_Billing_Type == 'Inpatient Credit') {
                        $statusMsg_proc = "Not Bill";
                    }
                }

                $is_date_chooser = '';

                if ($_SESSION['hospitalConsultaioninfo']['enable_doct_date_chooser'] == '1') {
                    $is_date_chooser = "id='firstsymptom_date'";
                }

                $auto_save_option = "";
                if ($_SESSION['hospitalConsultaioninfo']['set_doctors_auto_save'] == '1') {
                    $auto_save_option = 'oninput="savedoctorinfos(this)"';
                }

                $num_saved_pat_histr = 0;
                ?>



                <!--statrt here-->
                <?php
                $allow_display_prev = $_SESSION['hospitalConsultaioninfo']['allow_display_prev'];

                $consultation_id_patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)<>CURDATE() ORDER BY Consultation_Date_And_Time DESC LIMIT 1"))['consultation_ID'];

                $select_patient = "SELECT * FROM tbl_consultation_history WHERE  consultation_ID='$consultation_id_patient'";


                $patient_result = mysqli_query($conn, $select_patient) or die(mysqli_error($conn));
                $last_saved_consultation_ID = 0;

                if (mysqli_num_rows($patient_result) > 0) {
                    $patient_row = mysqli_fetch_assoc($patient_result);
                    $last_saved_consultation_ID = $patient_row['consultation_histry_ID'];
                } else {
                    $last_saved_consultation_ID = 0;
                }
                
                if (mysqli_num_rows($patient_result) > 0) {

                    if ($allow_display_prev == '1') {

                        $clinical_main_complain = (!empty($patient_row['maincomplain'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['maincomplain']) . '</div>' : '';
                        $history_present_illness2 = (!empty($patient_row['history_present_illness'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['history_present_illness']) . '</div>' : '';
                        $firstsymptom_date2 = (!empty($patient_row['firstsymptom_date'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['firstsymptom_date']) . '</div>' : '';;
                        $review_of_other_systems2 = (!empty($patient_row['review_of_other_systems'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['review_of_other_systems']) . '</div>' : '';;
                        $past_medical_history2 = (!empty($patient_row['past_medical_history'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['past_medical_history']) . '</div>' : '';
                        $doctor_plan_suggestion2 = (!empty($patient_row['doctor_plan_suggestion'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['doctor_plan_suggestion']) . '</div>' : '';
                        $family_social_history2 = (!empty($patient_row['family_social_history'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['family_social_history']) . '</div>' : '';
                        $Gynocological_history2 = (!empty($patient_row['Gynocological_history'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['Gynocological_history']) . '</div>' : '';
                        $general_observation2 = (!empty($patient_row['general_observation'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['general_observation']) . '</div>' : '';;
                        $local_examination2 = (!empty($patient_row['local_examination'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['local_examination']) . '</div>' : '';;
                        $systemic_observation2 = (!empty($patient_row['systemic_observation'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['systemic_observation']) . '</div>' : '';;
                        $Comment_For_Laboratory2 = (!empty($patient_row['Comment_For_Laboratory'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['Comment_For_Laboratory']) . '</div>' : '';;
                        $Comment_For_Radiology2 = (!empty($patient_row['Comment_For_Radiology'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['Comment_For_Radiology']) . '</div>' : '';;
                        $Comments_For_Procedure2 = (!empty($patient_row['Comments_For_Procedure'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['Comments_For_Procedure']) . '</div>' : '';;
                        $Comments_For_Surgery2 = (!empty($patient_row['Comments_For_Surgery'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['Comments_For_Surgery']) . '</div>' : '';;
                        $investigation_comments2 = (!empty($patient_row['investigation_comments'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['investigation_comments']) . '</div>' : '';;
                        $remarks2 = (!empty($patient_row['remarks'])) ? '<div class="previous-notes">' . htmlspecialchars($patient_row['remarks']) . '</div>' : '';;
                    } else {
                        $clinical_main_complain = '';
                        $history_present_illness2 = '';
                        $firstsymptom_date2 = '';
                        $review_of_other_systems2 = '';
                        $past_medical_history2 = '';
                        $family_social_history = '';
                        $Gynocological_history = '';
                        $general_observation2 = '';
                        $local_examination2 = '';
                        $systemic_observation2 = '';
                        $Comment_For_Laboratory2 = '';
                        $Comment_For_Radiology2 = '';
                        $Comments_For_Procedure2 = '';
                        $Comments_For_Surgery2 = '';
                        $investigation_comments2 = '';
                        $remarks2 = '';
                    }
                } else {
                    $clinical_main_complain = '';
                    $history_present_illness2 = '';
                    $firstsymptom_date2 = '';
                    $review_of_other_systems2 = '';
                    $past_medical_history2 = '';
                    $family_social_history = '';
                    $Gynocological_history = '';
                    $general_observation2 = '';
                    $local_examination2 = '';
                    $systemic_observation2 = '';
                    $Comment_For_Laboratory2 = '';
                    $Comment_For_Radiology2 = '';
                    $Comments_For_Procedure2 = '';
                    $Comments_For_Surgery2 = '';
                    $investigation_comments2 = '';
                    $remarks2 = '';
                }

                $diagnosis_patient = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d
            WHERE dc.disease_ID=d.disease_ID AND dc.consultation_ID ='$consultation_id_patient'";

                $result2 = mysqli_query($conn, $diagnosis_patient) or die(mysqli_error($conn));
                $provisional_diagnosis2 = '';
                $diferential_diagnosis2 = '';
                $diagnosis2 = '';
                if (mysqli_num_rows($result2) > 0) {
                    while ($row_one = mysqli_fetch_assoc($result2)) {
			            $disease_code = $row_one['disease_code'];

                        if ($row_one['diagnosis_type'] == 'provisional_diagnosis') {
                            $provisional_diagnosis2 .= ' ' . $row_one['disease_name'] . '('.$disease_code.'); ';
                        }
                        if ($row_one['diagnosis_type'] == 'diferential_diagnosis') {
                            $diferential_diagnosis2 .= ' ' . $row_one['disease_name'] . '('.$disease_code.'); ';
                        }
                        if ($row_one['diagnosis_type'] == 'diagnosis') {
                            $diagnosis2 .= ' ' . $row_one['disease_name'] . '('.$disease_code.'); ';
                        }
                    }
                }

                if ($allow_display_prev == '1') {
                    $provisional_diagnosis2 = (!empty($provisional_diagnosis2)) ? '<div class="previous-notes">' . $provisional_diagnosis2 . '</div>' : '';
                    $diferential_diagnosis2 = (!empty($diferential_diagnosis2)) ? '<div class="previous-notes">' . $diferential_diagnosis2 . '</div>' : '';
                    $diagnosis2 = (!empty($diagnosis2)) ? '<div class="previous-notes">' . $diagnosis2 . '</div>' : '';
                } else {
                    $provisional_diagnosis2 = '';
                    $diferential_diagnosis2 = '';
                    $diagnosis2 = '';
                }

                //Selecting Submitted Tests,Procedures, Drugs
                $select_payment_cache2 = "SELECT  Check_In_Type,Product_Name ,Doctor_Comment 
        FROM 
            tbl_payment_cache ipc,
            tbl_item_list_cache ilc,
            tbl_items i
            WHERE 
                
                ipc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
                i.Item_ID = ilc.Item_ID AND 
                                ipc.consultation_id = '$consultation_id_patient'
                ";



                $cache_result = mysqli_query($conn, $select_payment_cache2) or die(mysqli_error($conn));

                $Radiology2 = '';
                $Laboratory2 = '';
                $Pharmacy2 = "";
                $Procedure2 = "";
                $Surgery2 = "";
                $Others2 = "";
                $Nuclearmedicine2 = "";//nuclear msk

                if (mysqli_num_rows($cache_result) > 0) {

                    while ($cache_row = mysqli_fetch_array($cache_result)) {
                        if ($cache_row['Check_In_Type'] == 'Radiology') {
                            $Radiology2 .= ' ' . $cache_row['Product_Name'] . ';';
                        }
                        if ($cache_row['Check_In_Type'] == 'Laboratory') {
                            $Laboratory2 .= ' ' . $cache_row['Product_Name'] . ';';
                        }
                        if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                            //                        if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                            $Pharmacy2 .= ' ' . $cache_row['Product_Name'] . '[ ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                            //                        } else {
                            //                            $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                            //                        }
                            //$Pharmacy.= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                        }
                        if ($cache_row['Check_In_Type'] == 'Procedure') {
                            $Procedure2 .= ' ' . $cache_row['Product_Name'] . ';';
                        }
                        if ($cache_row['Check_In_Type'] == 'Surgery') {
                            $Surgery2 .= ' ' . $cache_row['Product_Name'] . ';';
                        }
                        if ($cache_row['Check_In_Type'] == 'Nuclearmedicine') {
                            $Nuclearmedicine .= 'dssdsd ' . $cache_row['Product_Name'] . ';';
                        }
                        if ($cache_row['Check_In_Type'] == 'Others') {
                            $Others2 .= ' ' . $cache_row['Product_Name'] . ';';
                        }
                        if($cache_row['Check_In_Type']=='Nuclearmedicine'){
                            $Nuclearmedicine2 .=' ' .$cache_row['Product_Name'] .';';//nuclear msk
                        }
                    }
                }


                if ($allow_display_prev == '1') {
                    $Radiology2 = (!empty($Radiology2)) ? '<div class="previous-notes">' . $Radiology2 . '</div>' : '';
                    $Laboratory2 = (!empty($Laboratory2)) ? '<div class="previous-notes">' . $Laboratory2 . '</div>' : '';
                    $Pharmacy2 = (!empty($Pharmacy2)) ? '<div class="previous-notes">' . $Pharmacy2 . '</div>' : '';
                    $Procedure2 = (!empty($Procedure2)) ? '<div class="previous-notes">' . $Procedure2 . '</div>' : '';
                    $Surgery2 = (!empty($Surgery)) ? '<div class="previous-notes">' . $Surgery2 . '</div>' : '';
                    $Others2 = (!empty($Others2)) ? '<div class="previous-notes">' . $Others2 . '</div>' : '';
                    $Nuclearmedicine2 = (!empty($Nuclearmedicine2)) ? '<div class="previous-notes">' . $Nuclearmedicine2 . '</div>' : ''; //nuclear msk

                } else {
                    $Radiology2 = '';
                    $Laboratory2 = '';
                    $Pharmacy2 = '';
                    $Procedure2 = '';
                    $Surgery2 = '';
                    $Others2 = '';
                    $Nuclearmedicine2='';
                }
               



                ?>
                <!--end here-->

                <div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
                    <div id="myConsult">
                    </div>
                </div>
                <div id="otherdoctorStaff" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
                    <div id="doctorsInfo">
                    </div>
                </div>
                <div id="summerypatientfile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
                    <div id="summpatfileInfo">
                    </div>
                </div>


                <div id="summerypatientallegies" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
                    <div id="summpatallegiesInfo">
                    </div>
                </div>
                <div id="getFileByFolio" style="display: none;">
                    <div align="center" style="" id="getFileByFolioprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id="containerFileFolio">

                    </div>
                </div>
                <?php
                if ($_SESSION['hospitalConsultaioninfo']['enable_clinic_not_scroll'] == '1') {
                    include './clinic_scroll_frame.php';
                } else {
                    include './clinic_tabs_frame.php';
                }
                //hapa ndo kuna unainclude files za clinical notes
                ?>
                <input type='hidden' id='formsubmtt' name='formsubmtt'>
                <input type='hidden' id='recentConsultaionTyp' value=''>
                <br>

                <div style="text-align: center">
             <input type='button' onclick="on_call_claim_form_dialogy()" value='ON CALL CLAIM'  class='btn btn-sm btn-danger' style="width:15%;">

                    <?php if (isset($_GET['Patient_Payment_ID'])) { ?>
                        <!-- <a href="powercharts_paediatric.php?Registration_ID=<?php echo $Registration_ID ?>&Powercharts_PaediatricPage=ThisPage" class="art-button-green" style="padding:0 5px 0 5px">Paediatric Work</a> -->
                    <?php }


                    ?>

                    <?php
                    if (isset($_GET['Registration_ID'])) {
                        $Registration_ID = $_GET['Registration_ID'];
                    }
                   
                    $Select_Patient = mysqli_query($conn, "SELECT *
                        from
                        tbl_patient_registration reg,tbl_pre_operative_checklist chk,tbl_nurse n,
                        tbl_admission ad
                        where 
                        chk.Registration_ID=reg.Registration_ID AND n.Registration_ID = chk.Registration_ID AND ad.Registration_ID=chk.Registration_ID AND
                        n.Registration_ID = '$Registration_ID'
                        ORDER BY Nurse_DateTime DESC limit 1 ") or die(mysqli_error($conn));
                    $number = mysqli_num_rows($select_Patient);
                    $Nurse_DateTime = '';
                    if ($number > 0) {

                        while ($checkthis = mysqli_fetch_assoc($Select_Patient)) {
                            $Nurse_DateTime = $checkthis['Nurse_DateTime'];
                        }
                    }
                    if (isset($_GET['Patient_Payment_ID'])) {
                    ?>
                        <a href="Pre_OperativeCompleted.php?Registration_ID=<?php echo $Registration_ID; ?>&&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Nurse_DateTime=<?php echo $Nurse_DateTime; ?>&From=doctor" class="art-button-green" style="padding:0 5px 0 5px">Pre-Operative Checklist</a>
                    <?php }
                    if (isset($_GET['Patient_Payment_ID'])) {
                    ?>
                        <input type='submit' class='art-button-green' value='TB SCREENING' onclick="btScreening();">
                        <!-- <input type='submit' class='art-button-green' value='RADIOTERAPY PRESCRIPTION' onclick="radiotherapy_request();"> -->
                        <input type='submit' class='art-button-green' value='RADICAL TREATMENT PRESCRIPTION' onclick="radiotherapy_request();">

                        <!-- <a href="optic/optic.php?patientId=<?= $Registration_ID ?>&guarantorName=<?= $Guarantor_Name ?>&this_page_from=doctor_outpatient&consultation_ID=<?= $consultation_ID ?>&Sponsor_ID=<?= $Sponsor_ID ?>" target="_blank">
                            <input type='button' name='patient_transfer_referral' id='patient_transfer_referral' value='OPTICAL ' class='art-button-green' />
                        </a> -->
                    <?php } ?>

                    <!-- start @dnm 22/08/2019 -->
                    <!-- <input type="button" value="Fertility Assessment" class="btn btn-success" onclick="fertility_assessment('<?= $Gender ?>')"> -->
                    <!--                <input type="button" value="Speech Therapy" class="btn btn-primary" onclick="open_speech_therapy_form(<?= $Registration_ID ?>)" >-->
                    <input type="hidden" value="<?= $Registration_ID ?>" id="P_ID">
                    <div id="fertilityAssessment" style="width:100%;display:none;">
                        <div id="fertilityAssessmentHTML" style="background-color:#fff;overflow-x:hidden;height:550px;overflow-y:scroll">
                            <!-- from AJAX -->
                        </div>
                    </div>
                    <!--<a href='diabetes_clinic.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target="_blank" class="art-button-green" >DIABETES CLINIC</a>-->
                    <!-- <a href="choose_assement_form.php?patient_id=<?php echo $Registration_ID; ?>&&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>"><input type="button" class="art-button-green" value="OCCUPATIONAL THERAPY"></a> -->
                    <div id="assement_type" style=" overflow-x: hidden;"> </div>
                    <div id="speech_therapy_dialogy"></div>
                    <script>
                        function open_speech_therapy_form(Registration_ID) {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_open_speech_therapy_form.php',
                                data: {
                                    Registration_ID: Registration_ID
                                },
                                success: function(data) {
                                    $("#speech_therapy_dialogy").html(data);
                                    $("#speech_therapy_dialogy").dialog("open");
                                }
                            });
                        }

                        function fertility_assessment(gender) {
                            var Registration_ID = $('#P_ID').val();
                            var dt = 'gender=' + gender + '&Registration_ID=' + Registration_ID;
                            $.ajax({
                                type: 'GET',
                                url: 'fertility_assessment.php',
                                data: dt,
                                success: function(html) {
                                    $('#fertilityAssessmentHTML').html(html);
                                    $("#fertilityAssessment").dialog("open");
                                }
                            });
                        }

                        $(document).ready(function() {
                            $("#fertilityAssessment").dialog({
                                autoOpen: false,
                                width: '60%',
                                title: 'FERTILITY ASSESSMENT (<?= $Gender ?>)',
                                modal: true,
                                position: 'top'
                            });
                            $("#speech_therapy_dialogy").dialog({
                                autoOpen: false,
                                width: '90%',
                                title: 'SPEECH THERAPY',
                                modal: true
                            });
                        });
                    </script>

                    <!-- end @dnm 22/08/2019 -->

                    <!--<a href='theatherpageworks.php?<?php
                                                        if ($Registration_ID != '') {
                                                            echo "Registration_ID=$Registration_ID&";
                                                        }
                                                        ?><?php
                if (isset($_GET['Patient_Payment_ID'])) {
                    echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
                }
                if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                    echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&From=doctor&";
                }
                ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
           Post-Operative (Surgery)
        </a>
            -->


                    <?php if (isset($_GET['Patient_Payment_ID'])) { ?>
                        <!--                <a href="#?Registration_ID=<?php echo $Registration_ID ?>&Powercharts_PaediatricPage=ThisPage" class="art-button-green" style="padding:0 15px 0 15px"  >
                            Ear Works </a>-->
                    <?php }
                    ?>

                    <?php if (isset($_GET['Patient_Payment_ID'])) { ?>
                        <!--                <a href="#?Registration_ID=<?php echo $Registration_ID ?>&Powercharts_PaediatricPage=ThisPage" class="art-button-green" style="padding:0 5px 0 5px"  >
                            Optical(Eye) Works  </a>-->
                    <?php }
                    ?>

                    <?php if (isset($_GET['Patient_Payment_ID'])) { ?>
                        <!--                <a href="#?Registration_ID=<?php echo $Registration_ID ?>&Powercharts_PaediatricPage=ThisPage" class="art-button-green" style="padding:0 5px 0 5px"  >
                            Physiotherapy Works </a>-->
                    <?php }
                    ?>

                    <?php if (isset($_GET['Patient_Payment_ID'])) { ?>
                        <!--                <a href="#?Registration_ID=<?php echo $Registration_ID ?>&Powercharts_PaediatricPage=ThisPage" class="art-button-green" style="padding:0 15px 0 15px"  >
                            Dental Works  </a>-->
                    <?php }
                    ?>



                    <?php
                    //$num_doctors=9;
                    // $saveData ="non_exeption()";
                    $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];

                    // $select_diagnosis_no  =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Diagnosis FROM tbl_clinic c WHERE c.Clinic_ID='$doctors_selected_clinic'"))['Diagnosis'];
                    //echo $select_diagnosis_no;
                    // if($select_diagnosis_no =="No"){
                    $saveData = "Save_Cancer_data()";
                    //  }

                    //check for php script on function onclick
                    if (isset($_GET['Patient_Payment_ID'])) {
                        if (isset($_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist']) && $_SESSION['hospitalConsultaioninfo']['enable_pat_medic_hist'] == '1') {
                            if ($num_saved_pat_histr == 0) {
                                echo "<input type='button' id='send' name='send'   value='SAVE ' onclick=\"alert('Save patient history first before continue')\" class='art-button-green'  
                   style='width:8%;float:right;'>";
                            } else {
                                echo "<input type='button' id='send' name='send'  value='SAVE ' onclick='check_if_consultation_is_procedure(), Save_Cancer_data()' class='art-button-green'  
                   style='width:8%;float:right;'>";
                            }
                        } else {
                    ?>
                            <input type='button' id='send' name='send' value='SAVE ' onclick="check_if_consultation_is_procedure(), Save_Cancer_data()" class='art-button-green' style="width:8%;float:right;">
                        <?php
                        }
                        if (isset($_SESSION['hospitalConsultaioninfo']['Enable_Save_And_Transfer_Button']) && $_SESSION['hospitalConsultaioninfo']['Enable_Save_And_Transfer_Button'] == '1') {
                        ?>
                            <input type="button" name="Save_Transfer" style="display:none" id="Save_Transfer" class="art-button-green" value="SAVE AND TRANSFER" onclick="validateInfos2(), Save_Cancer_data()">
                        <?php
                        }
                    }
                    if ($num_doctors > 0) {
                        ?>
                        <button type="button" onclick="showOthersDoctorsStaff()" class="art-button-green">Previous Doctor's Notes <span id='alert_here' style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;<?php if ($num_doctors == 0) echo "display:none;"; ?> '> <?php echo $num_doctors; ?> </span></button>
                    <?php
                    }
                    ?>
                </div>
                <?php
                // if (empty($diagnosis)) {
                // 
                ?>
                <!-- <span type="button" id="nofinalDiagnosis" onclick="" style="color:red;float:  right;padding-right:5px; ">No Final diagnosis for this patient yet</span>    <!--<img src="images/alerte.gif" width="20" height="20" style="background-color:transparent;float:  right">-->
                <?php
                // }
                ?>




            </form>
            <script>
                function non_exeption() {
                    alert("Nothing saved to cancer");
                }
                $(function() {
                    $("#tabs").tabs('option', 'active', <?php
                                                        if (isset($_GET['Consultation_Type'])) {
                                                            $Consultation_Type = $_GET['Consultation_Type'];
                                                            if ($Consultation_Type == 'provisional_diagnosis' || $Consultation_Type == 'diferential_diagnosis') {
                                                                //add script for setting tab focus
                                                                echo 1;
                                                            } else if ($Consultation_Type == 'diagnosis' || $Consultation_Type == 'Treatment' || $Consultation_Type == 'Pharmacy' || $Consultation_Type == 'Procedure') {
                                                                echo 3;
                                                            } else if ($Consultation_Type == 'Laboratory' || $Consultation_Type == 'Radiology' || $Consultation_Type == 'Nuclearmedicine') {
                                                                echo 2;
                                                            } else if ($Consultation_Type == 'Laboratory') {
                                                                echo 2;
                                                            } else {
                                                                echo 0;
                                                            }
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>);
                });
                $('#tabs').tabs();
            </script>
            <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
            <script src="js/jquery-1.8.0.min.js"></script>
            <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
            <script src="css/jquery.datetimepicker.js"></script>
            <!--<script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>-->

            <script>
                $(document).ready(function() {
                    var ToBeAdmitted_val = $("#ToBeAdmitted").val();
                    if (ToBeAdmitted_val == "yes") {
                        $('#show_ward').show();
                    }
                });

                function ShowReason(ToBeAdmitted) {
                    var doctor_admits_patient = '<?=$doctor_admits_patient?>';
                    if (ToBeAdmitted == 'yes') {

                        if (!confirm('Are you sure you want to admit this patient')) {
                            $('#ToBeAdmitted').val('no');
                        } else {
                            //                  document.getElementById('').
                            if (doctor_admits_patient === 'yes') {
                                $('#show_ward').show();
                            } else {
                                $('#show_ward').hide();
                            }
                        }

                    } else {
                        $('#show_ward').hide();
                    }


                }
            </script>
            <script>
                $('#firstsymptom_date').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
                    startDate: 'now'
                });
                $('#firstsymptom_date').datetimepicker({
                    value: '',
                    step: 30
                });
                //        $('#firstsymptom_date').multiDatesPicker({
                //  dateFormat: "y-m-d", 
                //  defaultDate:"now"
                //        });$Patient_Payment_Item_List_ID 
                //        
                //        $('#firstsymptom_date').multiDatesPicker({
                //            dateFormat: "yy-mm-dd", 
                //            defaultDate:new Date()
                //        });
                $('#dateserviceDate').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
                    startDate: 'now'
                });
                $('#dateserviceDate').datetimepicker({
                    value: '',
                    step: 30
                });
            </script>

        </fieldset>

        <div id="Transfer_Patient_Dialog">
            <table width="100%" class="art-article">
                <tr>
                    <td style="text-align: right;">Patient Name</td>
                    <td><input type="text" readonly="readonly" id="Patient_Name" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
                    <td style="text-align: right;">Gender</td>
                    <td width="10%"><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
                    <td style="text-align: right;">Patient Age</td>
                    <td width="10%"><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
                    <td style="text-align: right;">Sponsor Name</td>
                    <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
                </tr>
            </table>
            <br />Transfer Details
            <hr>
            <table width="100%" class="art-article">
                <tr>
                    <td style="text-align: right;" width="10%">Transfer Type</td>
                    <td width="10%">
                        <select id="Transfer_Type" onchange="Get_Transfer_Destination()">
                            <option selected="selected"></option>
                            <option>Transfer To Doctor</option>
                            <option>Transfer To Clinic</option>
                        </select>
                    </td>
                    <td id="Transfer_Process_Area" style="text-align: center;">
                        <span style="color: #037CB0;"><b>SELECT TRANSFER TYPE</b></span>
                        <input type="hidden" name="Destination_ID" id="Destination_ID" value="">
                    </td>
                    <td width="8%"><input style="display:none" type="button" value="SAVE AND TRANSFER" class="art-button-green" onclick="Transfer_Selected_Patient()">
                </tr>
            </table>
        </div>

        <div id="Transfer_Selected_Patient_Dialog">
            <table width="100%" class="art-article">
                <tr>
                    <td style="text-align: center;">Are you sure you want to save & transfer selected patient?<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" value="YES" class="art-button-green" onclick="Transfer_Selected_Patient_Process()">
                        <input type="button" value="CANCEL" class="art-button-green" onclick="Close_Confirm_Dialog()">
                    </td>
                </tr>
            </table>
        </div>
        <div id="TB_Screening1" class="hide">
            <table width="100%" class="art-article" style="font-size:18px;background-color:#fff;">
                <tr>
                    <th>SN</th>
                    <th>Diagosis</th>
                    <th>Yes</th>
                    <th>No</th>
                </tr>
                <tr>
                    <td>1. </td>
                    <td>Cough for two weeks or more.</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="cough" id="cough_yes" value="yes" data-value="2" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="cough" id="cough_no" value="no" class="tb-screen-no" data-value="2"></td>
                </tr>
                <tr>
                    <td>2. </td>
                    <td>Cough for less than two weeks.</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="cough_less" id="cough_less_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="cough_less" id="cough_less_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>

                <tr>
                    <td>3. </td>
                    <td>Sputum production.</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="sputum" id="sputum_yes" value="yes" data-value="2" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="sputum" id="sputum_no" value="no" class="tb-screen-no" data-value="2"></td>
                </tr>

                <tr>
                    <td>4. </td>
                    <td>Coughing up blood</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="cough_blood" id="cough_blood_yes" value="yes" data-value="2" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="cough_blood" id="cough_blood_no" value="no" class="tb-screen-no" data-value="2"></td>
                </tr>
                <tr>
                    <td>5. </td>
                    <td>History for household contact with TB.</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="household_history" id="household_history_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="household_history" id="household_history_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>6. </td>
                    <td>Fever of any duration.</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="fever" id="fever_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="fever" id="fever_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>7. </td>
                    <td>Radical activities or irritability for two weeks or more.</td>
                    <td style='text-align:center;'><input type="radio" name="irritability" id="irritability_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="irritability" id="irritability_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>8. </td>
                    <td>Inadequate weight gain,faltering or loss </td>
                    <td style='text-align:center;'>
                        <input type="radio" name="weight_change" id="weight_change_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="weight_change" id="weight_change_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>9. </td>
                    <td>Past history of TB treatment</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="past_treatment" id="past_treatment_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="past_treatment" id="past_treatment_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>10. </td>
                    <td>Excessive night sweats</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="excessive_sweat" id="excessive_sweat_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="excessive_sweat" id="excessive_sweat_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>
                <tr>
                    <td>11. </td>
                    <td>Any other symptoms (chest pain, chest tightness).</td>
                    <td style='text-align:center;'>
                        <input type="radio" name="other_symptoms" id="other_symptoms_yes" value="yes" data-value="1" class="tb-screen"></td>
                    <td style='text-align:center;'><input type="radio" name="other_symptoms" id="other_symptoms_no" value="no" class="tb-screen-no" data-value="1"></td>
                </tr>

                <tr>
                    <td> </td>
                    <td><b>
                            <center>Total Score</center>
                        </b></td>
                    <td style='text-align:center;'>
                        <input type="text" name="yes_score" id="yes_score" value="" class="score" style="text-align:center"></td>
                    <td style='text-align:center;'><input type="text" name="no_score" id="no_score" value="0" style="text-align:center"></td>
                </tr>
            </table>
            <div style="color:red; text-align:center; font-weight:bold" id="pressumed_tb"></div>
            <br>
            <br><br>
            <center>
                <input type="button" value="SAVE" class="art-button-green" onclick="saveTbScreening();">
                <input type="button" value="CANCEL" class="art-button-green" onclick="TB_Screening_Close_Confirm_Dialog()">
            </center>
        </div>
        <div id='TB_Screening'>

<table width="100%" class="art-article" style="font-size:18px; background-color:#fff; line-height: 2.2; border-size: none;">
<center>
<div style="font-size: 20px;">
<b>TB SCREENING FOR:- <?php echo $Patient_Name ." : ". $Guarantor_Name ." : ". $age ?></b>
<div>
</center>
    <tr>
        
        <td style="text-align: right;">
            <b>Physical Address</b>
        </td>
        <td>
            <input type="text" name="adress" id="adress" placeholder="Street">
        </td>
    </tr>
    <tr>

        <td style="text-align: right;">
            <b>Area Leader / Neighbor Phone Number: </b>
        </td>
        <td>
            <input type="text" name="area_leader" id="area_leader" placeholder="Area Leader / Neighbor Phone Number">
        </td>
    </tr>
    <tr>

        <td style="text-align: right;">
            <b>TB District Number: </b>
        </td>
        <td>
            <input type="text" name="tb_district" id="tb_district" placeholder="TB District Number">
        </td>
    </tr>
    <tr>

        <td rowspan="2" style="text-align: right;">
            <b>Reason for Examination</b>
        </td>
        <td>
            <input type="radio" name="reason" id="Diagnosis" value="Diagnosis">Diagnosis, If Diagnosis, TB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="reason" id="MDR" value="MDR">MDR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="reason" id="Leprosy" value="Leprosy">Leprosy &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="reason" id="FollowUp" value="Follow Up">Follow-Up, if follow-Up, Month of Treatment <input type="text" placeholder="Month of Treatment" name="month" id="month" class="month" style="display: none; width: 50%;">
    </tr>
    <tr>
        <td style="text-align: right;">
            <b>HIV Status</b>
        </td>
        <td>
            <input type="radio" name="HIV" id="Reactive" value="Reactive">Reactive &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="HIV" id="NonReactive" value="Non Reactive">Non Reactive &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="HIV" id="Uknown" value="Uknown">Uknown &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <b>Previously Treated for TB?</b>
        </td>
        <td>
            <input type="radio" name="previous" id="Yes" value="Yes">Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="previous" id="No" value="No">No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </tr>
    <tr>
        <td style="text-align: right;">
            <b>Specimen Type</b>
        </td>
        <td>
            <input type="radio" name="specimen" id="Sputum" value="Sputum">Sputum &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="specimen" id="CSF" value="CSF">CSF &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="specimen" id="Peritoneal" value="Peritoneal Fluid">Peritoneal Fluid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="specimen" id="Skin" value="Skin Smear">Skin Smear &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="specimen" id="Pleural" value="Pleural Fluid">Pleural Fluid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="specimen" id="Lymph" value="Lymph Node">Lymph Node &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <b>Test(s) Request</b>
        </td>
        <td>
            <input type="checkbox" name="Microbiology" id="Microbiology" value="Microbiology">
            <label for="Microbiology" style="font-weight: none !important;">Microbiology</label>
            <input type="checkbox" name="Xpert" id="Xpert" value="Xpert MTB/RIF">
            <label for="Xpert" style="font-weight: none !important;">Xpert MTB/RIF</label>
        </td>
    </tr>
    </table>
    <table width="100%" class="art-article" style="font-size:18px; background-color:#fff; line-height: 2.2; border-size: none;">
    <tr>
    <td colspan="2" style="text-align: center;">
        <b>Contacts for Results Feedback (If RR for Xpert MTB/RIF) DTLC / RTLC</b>
    </td>
    </tr>
    <tr>
        <td>
            <b>RTLC Name : </b><input type="text" name="RTLC_name" id="RTLC_name" placeholder="RTLC Name" style="display: inline; width: 82%;">
        </td>
        <td>
            <b>Email Contact : </b><input type="email" name="RTLC_email" id="RTLC_email" placeholder="Email Contact" style="display: inline; width: 82%;">
        </td>
    </tr>
    <tr>
        <td>
            <b>DTLC Name : </b><input type="text" name="DTLC_name" id="DTLC_name" placeholder="DTLC Name" style="display: inline; width: 82%;">
        </td>
        <td>
            <b>Email Contact : </b><input type="email" name="DTLC_email" id="DTLC_email" placeholder="Email Contact" style="display: inline; width: 82%;">
        </td>
    </tr>
        <tr>
        <td colspan="2">
        <center>
            <input type="button" value="SAVE" class="art-button-green" onclick="savingOfTbScreening()" >
            <input type="button" value="CANCEL" class="art-button-green" onclick="TB_Screening_Close_Confirm_Dialog()" >
        </center>
        </td>
    </tr>
</table>


</div>


<!-- END OF TB SCREENING -->

<div id="Submit_data">
    <center id='Appointment_area'>
    </center>
</div>
                    <script>

                        $(document).ready(function () {
                            $("#Submit_data").dialog({autoOpen: false, width: '50%', height: 700, title: 'RADICAL TREATMENT REQUEST FORM', modal: true});
                        });
                        function radiotherapy_request() {
                            Current_Employee_ID = '<?= $Employee_ID ?>';
                            consultation_ID = '<?= $consultation_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';

                            if (window.XMLHttpRequest) {
                                myObjectPost = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectPost.overrideMimeType('text/xml');
                            }

                            myObjectPost.onreadystatechange = function () {
                                dataPost = myObjectPost.responseText;
                                if (myObjectPost.readyState == 4) {
                                    document.getElementById('Appointment_area').innerHTML = dataPost;
                                    $("#Submit_data").dialog("open");
                                    check_phase(consultation_ID);
                                    check_Tumor_Dose(consultation_ID);
                                    check_Number_Of_Fraction(consultation_ID);
                                    check_Dose_per_Fraction(consultation_ID);
                                }
                            }; 
                            //specify name of function that will handle server response........
                            
                            myObjectPost.open('GET', 'radiotherapy_request_form.php?Current_Employee_ID='+Current_Employee_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID, true);
                            myObjectPost.send();
                            // $("#Submit_data").dialog("open");
                        }

                        function Select_Radical_Type(consultation_ID){
                            Registration_ID = '<?= $Registration_ID ?>';
                            Current_Employee_ID = '<?= $Employee_ID ?>';
                            Radical_Type = $("#Radical_Type").val();
                                if(Radical_Type == 'RADIOTHERAPY'){
                                    if (window.XMLHttpRequest) {
                                        myObjectPost = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObjectPost.overrideMimeType('text/xml');
                                    }

                                    myObjectPost.onreadystatechange = function () {
                                        dataPost = myObjectPost.responseText;
                                        if (myObjectPost.readyState == 4) {
                                            document.getElementById('radical_panel').innerHTML = dataPost;
                                            // $("#Submit_data").dialog("open");
                                            check_phase(consultation_ID);
                                            check_Tumor_Dose(consultation_ID);
                                            check_Number_Of_Fraction(consultation_ID);
                                            check_Dose_per_Fraction(consultation_ID);
                                        }
                                    }; 
                                    //specify name of function that will handle server response........
                                    
                                    myObjectPost.open('GET', 'radiotherapy_form.php?Current_Employee_ID='+Current_Employee_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID, true);
                                    myObjectPost.send();
                                }else if(Radical_Type == 'BRACHYTHERAPY'){
                                    if (window.XMLHttpRequest) {
                                        myObjectPost = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObjectPost.overrideMimeType('text/xml');
                                    }

                                    myObjectPost.onreadystatechange = function () {
                                        dataPost = myObjectPost.responseText;
                                        if (myObjectPost.readyState == 4) {
                                            document.getElementById('radical_panel').innerHTML = dataPost;
                                            check_phase_brachy(consultation_ID);

                                        }
                                    }; 
                                    //specify name of function that will handle server response........
                                    
                                    myObjectPost.open('GET', 'brachytherapy_form.php?Current_Employee_ID='+Current_Employee_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID, true);
                                    myObjectPost.send();
                                }else{
                                    alert("PLEASE SELECT RADICAL TYPE TO CONSULT THIS PATIENT");
                                    exit();
                                }

                        }

                        function check_phase(consultation_ID) {
                            Current_Employee_ID = '<?= $Employee_ID ?>';
                            consultation_ID = '<?= $consultation_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';
                            Treatment_Phase = $("#Treatment_Phase").val();

                            if (window.XMLHttpRequest) {
                                myObjectPost = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectPost.overrideMimeType('text/xml');
                            }

                            myObjectPost.onreadystatechange = function () {
                                dataPost = myObjectPost.responseText;
                                if (myObjectPost.readyState == 4) {
                                    document.getElementById('previous_data').innerHTML = dataPost;
                                    // $("#Submit_data").dialog("open");
                                    check_Tumor_Dose(consultation_ID);
                                    check_Number_Of_Fraction(consultation_ID);
                                    check_name_of_site(consultation_ID);
                                }
                            }; //specify name of function that will handle server response........

                            myObjectPost.open('GET', 'ajax_check_radiotherapy_request.php?Current_Employee_ID='+Current_Employee_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID+'&Treatment_Phase='+Treatment_Phase, true);
                            myObjectPost.send();                          
                        }
                        function add_Phase(consultation_ID) {
                            Dose_per_Fraction = $("#Dose_per_Fraction").val();
                            Number_of_Fraction = $("#Number_of_Fraction").val();
                            Number_of_Fields = $("#Number_of_Fields").val();
                            Tumor_Dose = $("#Tumor_Dose").val();
                            Intent_of_Treatment = $("#Intent_of_Treatment").val();
                            Treatment_Phase = $("#Treatment_Phase").val();
                            name_of_site = $("#name_of_site").val();
                            Employee_ID = '<?= $Employee_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';

                            $.ajax({
                                type: "POST",
                                url: "ajax_update_radiotherapy_requests.php",
                                data: {
                                    consultation_ID:consultation_ID, 
                                    Dose_per_Fraction:Dose_per_Fraction, 
                                    Number_of_Fraction:Number_of_Fraction, 
                                    Tumor_Dose:Tumor_Dose,
                                    Intent_of_Treatment:Intent_of_Treatment, 
                                    Employee_ID:Employee_ID, 
                                    Treatment_Phase:Treatment_Phase, 
                                    Registration_ID:Registration_ID,
                                    name_of_site:name_of_site,
                                    Number_of_Fields:Number_of_Fields
                                },
                                cache: false,
                                success: function (response) {
                                    Calculate_Dosage();
                                    check_phase(consultation_ID);
                                }
                            });
                        }

                        function add_Phase_brachy(consultation_ID){
                            Dose_per_Fraction = $("#Dose_per_Fraction").val();
                            Number_of_Fraction = $("#Number_of_Fraction").val();
                            Type_of_brachytherapy = $("#Type_of_brachytherapy").val();
                            Employee_ID = '<?= $Employee_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';

                            $.ajax({
                                type: "POST",
                                url: "ajax_update_brachytherapy_requests.php",
                                data: {
                                    consultation_ID:consultation_ID, 
                                    Dose_per_Fraction:Dose_per_Fraction, 
                                    Number_of_Fraction:Number_of_Fraction,
                                    Employee_ID:Employee_ID, 
                                    Registration_ID:Registration_ID,
                                    Type_of_brachytherapy:Type_of_brachytherapy
                                },
                                cache: false,
                                success: function (response) {
                                    check_phase_brachy(consultation_ID);
                                }
                            });
                        }

                        function check_phase_brachy(consultation_ID){
                            Current_Employee_ID = '<?= $Employee_ID ?>';
                            consultation_ID = '<?= $consultation_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';

                            if (window.XMLHttpRequest) {
                                myObjectPost = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectPost.overrideMimeType('text/xml');
                            }

                            myObjectPost.onreadystatechange = function () {
                                dataPost = myObjectPost.responseText;
                                if (myObjectPost.readyState == 4) {
                                    document.getElementById('previous_data_brachytherapy').innerHTML = dataPost;
                                    // // $("#Submit_data").dialog("open");
                                    // check_Tumor_Dose(consultation_ID);
                                    // check_Number_Of_Fraction(consultation_ID);
                                    // check_name_of_site(consultation_ID);
                                }
                            }; //specify name of function that will handle server response........

                            myObjectPost.open('GET', 'ajax_check_brachytherapy_request.php?Current_Employee_ID='+Current_Employee_ID+'&consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID+'&Treatment_Phase='+Treatment_Phase, true);
                            myObjectPost.send();   
                        }
                        function Submit_Radiotherapy(consultation_ID) {
                            if(confirm("You'are about to Save This Radiotherapy Prescription, click OK to Proceed, Cancel to Edit/Review it")){
                                $.ajax({
                                    type: "POST",
                                    url: "ajax_check_data_radiotherapy.php",
                                    data: {consultation_ID:consultation_ID, action:'Submit'},
                                    cache: false,
                                    success: function (response) {
                                        $("#Submit_data").dialog("close");
                                    }
                                });
                            }

                        }

                        function Submit_Brachytherapy(consultation_ID){
                            if(confirm("You'are about to Save This Brachytherapy Prescription, click OK to Proceed, Cancel to Edit/Review it")){
                                $.ajax({
                                    type: "POST",
                                    url: "ajax_update_brachytherapy_requests.php",
                                    data: {consultation_ID:consultation_ID, action:'Submit'},
                                    cache: false,
                                    success: function (response) {
                                        if(response == 200){
                                            alert("Brachytherapy Request has been Submitted Successfully");
                                            $("#Submit_data").dialog("close");
                                        }else{
                                            alert("Brachytherapy Request Failed to be Submitted");
                                            exit();
                                        }
                                    }
                                });
                            }
                        }
                        function check_Tumor_Dose(consultation_ID){
                            Tumor_Dose = $("#Tumor_Dose").val();
                            Treatment_Phase = $("#Treatment_Phase").val();
                            $.ajax({
                                type: "POST",
                                url: "ajax_check_data_radiotherapy.php",
                                data: {Treatment_Phase:Treatment_Phase,consultation_ID:consultation_ID,details:'Tumor_Dose'},
                                success: function (response) {
                                    var results = response;
                                    document.getElementById('Tumor_Dose').value = results;
                                }
                            });
                        }
                        function check_name_of_site(consultation_ID){
                            name_of_site = $("#name_of_site").val();
                            Treatment_Phase = $("#Treatment_Phase").val();
                            $.ajax({
                                type: "POST",
                                url: "ajax_check_data_radiotherapy.php",
                                data: {Treatment_Phase:Treatment_Phase,consultation_ID:consultation_ID,details:'name_of_site'},
                                success: function (response) {
                                    var results = response;
                                    document.getElementById('name_of_site').value = results;
                                }
                            });
                        }
                        function check_Number_Of_Fraction(consultation_ID){
                            var Number_of_Fraction = $("#Number_of_Fraction").val();
                            var Treatment_Phase = $("#Treatment_Phase").val();
                            $.ajax({
                                type: "POST",
                                url: "ajax_check_data_radiotherapy.php",
                                data: {consultation_ID:consultation_ID,Treatment_Phase:Treatment_Phase,details:'Number_of_Fraction'},
                                success: function (response) {
                                    var results = response;
                                    document.getElementById('Number_of_Fraction').value = results;
                                }
                            });
                        }
                        function Calculate_Dosage(){
                            // Dose_per_Fraction = $("#Dose_per_Fraction").val();
                            Number_of_Fraction = $("#Number_of_Fraction").val();
                            Tumor_Dose = $("#Tumor_Dose").val();

                            results = (Tumor_Dose)/(Number_of_Fraction);
                            let num = results.toFixed(2)
                            if (!isNaN(num)) {
                            // results = results.toFixed(2);
                            document.getElementById('Dose_per_Fraction').value = num;
                            //    alert(results);
                            }

                        }
                        function check_Dose_per_Fraction(consultation_ID){
                            var Dose_per_Fraction = $("#Dose_per_Fraction").val();
                            var Treatment_Phase = $("#Treatment_Phase").val();
                            $.ajax({
                                type: "POST",
                                url: "ajax_check_data_radiotherapy.php",
                                data: {consultation_ID:consultation_ID,Treatment_Phase:Treatment_Phase,details:'Dose_per_Fraction'},
                                success: function (response) {
                                    var results = response;
                                    document.getElementById('Dose_per_Fraction').value = results;
                                }
                            });
                        }
</script>


        <div id="Transfer_Error">
            Process Fail! Please try again
        </div>
        <div id="open_consultation_form_dialogy_div" style="display:none">
            <div class="col-md-6">
                <a class="btn btn-default btn-block" name="request_type" onclick="display_request_form(<?php echo $Registration_ID; ?>)">WRITE REQUEST</a>
            </div>
            <!-- <div class="col-md-4"> 
                            <a class="btn btn-default btn-block" >NEW REQUEST</a>
                        </div> -->
            <div class="col-md-6">
                <a class="btn btn-default btn-block" name="pre_request" onclick="display_request_forms(<?php echo $Registration_ID; ?>)">PREVIOUS REQUEST</a>
            </div>
            <div class="col-md-12" id="select_diagnosis_div">
            <hr>
            </div>
            <div class="col-md-12" style="height:50vh;overflow-y: scroll;" id="consultation_form_request_body">

            </div>
        </div>

<script>
function savingOfTbScreening(){
        var reason=$('input[name=reason]:checked').val();
        var previous=$('input[name=previous]:checked').val();
        var specimen=$('input[name=specimen]:checked').val();
        var Microbiology=$('input[name=Microbiology]:checked').val();
        var HIV=$('input[name=HIV]:checked').val();
        var Xpert=$('input[name=Xpert]:checked').val();
        var month=$('input[name=month]').val();
        var RTLC_name=$('input[name=RTLC_name]').val();
        var RTLC_email=$('input[name=RTLC_email]').val();
        var DTLC_email=$('input[name=DTLC_email]').val();
        var DTLC_name=$('input[name=DTLC_name]').val();
        var adress=$('input[name=adress]').val();
        var area_leader=$('input[name=area_leader]').val();
        var tb_district=$('input[name=tb_district]').val();
        var Registration_ID="<?= $Registration_ID; ?>"
        var Consultation_ID="<?= $consultation_ID; ?>"
        var Employee_ID="<?= $employee_ID; ?>"
        var doctors_selected_clinic="<?= $doctors_selected_clinic; ?>"
        if(Registration_ID == null || Registration_ID == '' || Consultation_ID == null || Consultation_ID == '') {
            alert('CHECK ALL QUESTIONS AND SAVE ');
        }else{
            $.ajax({
                url:'new_tb_screening_ajax.php',
                type:'post',
                data:{bt_screenig:'yes',Registration_ID:Registration_ID,consultation_ID:Consultation_ID,reason:reason,HIV:HIV,previous:previous,specimen:specimen,Microbiology:Microbiology,Xpert:Xpert,month:month,RTLC_name:RTLC_name,RTLC_email:RTLC_email,DTLC_name:DTLC_name,DTLC_email:DTLC_email,Employee_ID:Employee_ID,doctors_selected_clinic:doctors_selected_clinic,adress:adress,area_leader:area_leader,tb_district:tb_district},
                success:function(result){
                    alert(result);
                    $("#TB_Screening").dialog("close");

                }
            });
        }
    }
</script>

        <script type="text/javascript">
            function display_request_form(Registration_ID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_display_request_form.php',
                    data: {
                        request_type: '',
                        Registration_ID: Registration_ID
                    },
                    success: function(data) {
                        $("#consultation_form_request_body").html(data);
                        $('select').select2();
                    }
                });
            }

            function display_request_forms(Registration_ID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_display_request_form.php',
                    data: {
                        pre_request: '',
                        Registration_ID: Registration_ID
                    },
                    success: function(responce) {
                        $("#consultation_form_request_body").html(responce);
                        // $('select').select2();
                    }
                });
            }

            

            function reply_request_consultation(Request_Consultation_ID, Registration_ID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_display_request_form.php',
                    data: {
                        preview_request: '',
                        Registration_ID: Registration_ID,
                        Request_Consultation_ID: Request_Consultation_ID
                    },
                    success: function(responce) {
                        $("#consultation_form_request_body").html(responce);
                        // $('select').select2();
                        display_replay()
                    }
                });
            }

            function Replay_consultation_request(Registration_ID, Request_Consultation_ID) {
                var consultation_request_replay = $("#consultation_request_replay").val();
                if (consultation_request_replay == "") {
                    $("#consultation_request_replay").css("border", "2px solid red");
                } else {
                    $("#consultation_request_replay").css("border", "");
                    $.ajax({
                        type: 'POST',
                        url: 'Ajax_save_burn_unit.php',
                        data: {
                            consultation_request_replay: consultation_request_replay,
                            Registration_ID: Registration_ID,
                            Request_Consultation_ID: Request_Consultation_ID,
                            replay_btn: ''
                        },
                        success: function(success) {
                            display_replay()
                            $("#consultation_request_replay").val("");
                        }
                    })
                }
            }

            function display_replay() {
                var Request_Consultation_ID = $("#Request_Consultation_ID").val();
                var Registration_ID = $("#Registration_ID").val()
                $.ajax({
                    type: 'POST',
                    url: 'Ajax_display_burn.php',
                    data: {
                        Request_Consultation_ID: Request_Consultation_ID,
                        Registration_ID: Registration_ID,
                        reply_body: ''
                    },
                    success: function(responce) {
                        $("#reply_body").html(responce);
                    }
                })
            }

            function update_consultation_request(Request_Consultation_ID) {
                var Request_type = "";
                if ($("#Emergency").is(":checked")) {
                    Request_type += "Emergency" + ','
                }
                if ($("#Urgent").is(":checked")) {
                    Request_type += "Urgent" + ','
                }
                if ($("#Routine").is(":checked")) {
                    Request_type += "Routine" + ','
                }

                var Brief_case_summary = $("#Brief_case_summary").val();
                var Question = $("#Question").val();
                // var Request_from = $("#Request_from").val();
                var Request_to = $("#Request_to").val();
                var Diagnosis = $("#Diagnosis").val();
                if (Brief_case_summary == "") {
                    $("#Brief_case_summary").css("border", "1px solid red");
                } else if (Diagnosis == "") {
                    $("#Diagnosis").css("border", "1px solid red");
                } else if (Request_to == "") {
                    $("#Request_to").css("border", "1px solid red");

                } else {
                    $("#Diagnosis").css("border", "");
                    $("#Request_to").css("border", "");
                    $("#Brief_case_summary").css("border", "");
                    $.ajax({
                        type: 'POST',
                        url: 'Ajax_save_burn_unit.php',
                        data: {
                            Request_type: Request_type,
                            Request_Consultation_ID: Request_Consultation_ID,
                            Request_to: Request_to,
                            Diagnosis: Diagnosis,
                            Brief_case_summary: Brief_case_summary,
                            Question: Question,
                            request_btn_update: ''
                        },
                        success: function(responce) {
                            alert("Updated successful");
                        }
                    });
                }
            }

            function save_request_form_data(Registration_ID) {
                var Request_type = "";
                if ($("#Emergency").is(":checked")) {
                    Request_type += "Emergency" + ','
                }
                if ($("#Urgent").is(":checked")) {
                    Request_type += "Urgent" + ','
                }
                if ($("#Routine").is(":checked")) {
                    Request_type += "Routine" + ','
                }

                var Brief_case_summary = $("#Brief_case_summary").val();
                var Question = $("#Question").val();
                var Request_from = $("#Request_from").val();
                var Request_to = $("#Request_to").val();
                var Diagnosis = $("#Diagnosis").val();
                if (Brief_case_summary == "") {
                    $("#Brief_case_summary").css("border", "1px solid red");
                } else if (Diagnosis == "") {
                    $("#Diagnosis").css("border", "1px solid red");
                } else if (Request_to == "") {
                    $("#Request_to").css("border", "1px solid red");
                } else {
                    $("#Diagnosis").css("border", "");
                    $("#Request_to").css("border", "");
                    $("#Brief_case_summary").css("border", "");
                    $.ajax({
                        type: 'POST',
                        url: 'Ajax_save_burn_unit.php',
                        data: {
                            Registration_ID: Registration_ID,
                            Request_type: Request_type,
                            Request_from: Request_from,
                            Request_to: Request_to,
                            Diagnosis: Diagnosis,
                            Brief_case_summary: Brief_case_summary,
                            Question: Question,
                            request_btn: ''
                        },
                        success: function(responce) {
                            alert("Saved successful");
                             $("#Brief_case_summary").val("");
                             $("#Question").val("");
                         $("#Request_from").val("");
                            $("#Request_to").val("");
                             $("#Diagnosis").val("");

                        }
                    });
                }
            }

            function open_consultation_form_dialogy(Registration_ID) {
                var Patient_Name = $("#Patient_Name").val();
                $("#open_consultation_form_dialogy_div").dialog({
                    title: 'CONSULTATION FORM  ---' + Patient_Name + " " + Registration_ID,
                    width: '90%',
                    height: 700,
                    modal: true,
                });
            }

            function select_diagnosis_for_consultation(Registration_ID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_display_request_form.php',
                    data: {
                        Registration_ID: Registration_ID,
                        select_diagnosis: ''
                    },
                    success: function(responce) {
                        $("#select_diagnosis_div").dialog({
                            title: 'SELECT DIAGNOSIS ',
                            width: '60%',
                            height: 400,
                            modal: true,
                        });
                        $("#select_diagnosis_div").html(responce);
                    }
                });
            }


            function add_main_complain_txt_area() {

            }

            function Transfer_Selected_Patient() {
                var Destination_ID = document.getElementById("Destination_ID").value;
                var Transfer_Type = document.getElementById("Transfer_Type").value;
                if (Destination_ID != null && Destination_ID != '' && Transfer_Type != null && Transfer_Type != '') {
                    document.getElementById("Destination_ID").style = 'border: 1px solid black';
                    document.getElementById("Transfer_Type").style = 'border: 1px solid black';
                    $("#Transfer_Selected_Patient_Dialog").dialog("open");
                } else {
                    if (Destination_ID == null || Destination_ID == '') {
                        document.getElementById("Destination_ID").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Destination_ID").style = 'border: 1px solid black';
                    }
                    if (Transfer_Type == null || Transfer_Type == '') {
                        document.getElementById("Transfer_Type").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Transfer_Type").style = 'border: 1px solid black';
                    }
                }
            }

            function Close_Confirm_Dialog() {
                $("#Transfer_Selected_Patient_Dialog").dialog("close");
            }

            function TB_Screening_Close_Confirm_Dialog() {
                $("#TB_Screening").dialog("close");
            }
            // handle tb value clicked
            var total_score = 0;
            var input_name = [];
            $(".tb-screen").click(function() {
                input_name.push($(this).attr('name'))

                var check_tb = $('#cough_yes').is(":checked");
                if (check_tb) {
                    $("#pressumed_tb").html("This patient was found with Pressumptive TB -> Consider Diagnosis and test")
                }
                var data_value = $(this).attr("data-value");
                total_score = total_score + parseInt(data_value);
                $("#yes_score").val(total_score);

                if (total_score >= 4) {
                    $("#pressumed_tb").html("This patient was found with Pressumptive TB -> Consider Diagnosis and test")
                }
            })

            // remove total score
            $(".tb-screen-no").click(function() {
                var data_value = $(this).attr("data-value");
                var attr_name = $(this).attr('name');

                if (total_score < 5) {
                    $("#pressumed_tb").html("")
                }

                var boxes = $('input[name=' + attr_name + ']').is(":checked");

                var value_name = input_name.find(getInputName);
                if (value_name !== undefined) {
                    total_score = total_score - parseInt(data_value);
                    $("#yes_score").val(total_score);
                } else {

                }

                if (attr_name == "cough") {
                    var check_tb = $(this).is(":checked");
                    if (check_tb) {
                        $("#pressumed_tb").html("")
                    }
                }

                // check if input already checked
                function getInputName(name) {
                    return name === attr_name;
                }

            })


            // save tb screening
            function saveTbScreening() {
                var cough = $('input[name=cough]:checked').val();
                var cough_less = $('input[name=cough_less]:checked').val();
                var sputum_yes = $('input[name=sputum]:checked').val();
                var cough_blood = $('input[name=cough_blood]:checked').val();
                var household_history = $('input[name=household_history]:checked').val();
                var fever = $('input[name=fever]:checked').val();
                var irritability = $('input[name=irritability]:checked').val();
                var weight_change = $('input[name=weight_change]:checked').val();
                var past_treatment = $('input[name=past_treatment]:checked').val();
                var excessive_sweat = $('input[name=excessive_sweat]:checked').val();
                var other_symptoms = $('input[name=other_symptoms]:checked').val();
                var total_score = $("#yes_score").val();
                var Registration_ID = "<?= $Registration_ID; ?>"
                var Consultation_ID = "<?= $consultation_ID; ?>"
                if (cough === undefined || household_history === undefined || fever === undefined || irritability === undefined || weight_change === undefined || past_treatment === undefined || excessive_sweat === undefined || other_symptoms === undefined) {
                    alert('CHECK ALL QUESTIONS AND SAVE ');
                } else {
                    $.ajax({
                        url: 'doctor_tb_screening_ajax.php',
                        type: 'post',
                        data: {
                            tb_screenig: 'yes',
                            Registration_ID: Registration_ID,
                            consultation_ID: Consultation_ID,
                            cough: cough,
                            household_history: household_history,
                            fever: fever,
                            irritability: irritability,
                            weight_change: weight_change,
                            past_treatment: past_treatment,
                            excessive_sweat: excessive_sweat,
                            other_symptoms: other_symptoms,
                            sputum_yes: sputum_yes,
                            cough_blood: cough_blood,
                            cough_less: cough_less,
                            total_score: total_score
                        },
                        success: function(result) {
                            alert(result);
                        }
                    });
                }
            }
        </script>

        <script type="text/javascript">
            function Transfer_Selected_Patient_Process() {
                var Patient_Payment_Item_List_ID = "<?php echo $Patient_Payment_Item_List_ID; ?>";
                var Destination_ID = document.getElementById("Destination_ID").value;
                var Transfer_Type = document.getElementById("Transfer_Type").value;
                if (Destination_ID != null && Destination_ID != '' && Transfer_Type != null && Transfer_Type != '') {
                    document.getElementById("Destination_ID").style = 'border: 1px solid black';
                    document.getElementById("Transfer_Type").style = 'border: 1px solid black';
                    if (window.XMLHttpRequest) {
                        myObjectProc = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectProc = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectProc.overrideMimeType('text/xml');
                    }

                    myObjectProc.onreadystatechange = function() {
                        dataTran = myObjectProc.responseText;
                        if (myObjectProc.readyState == 4) {
                            var feedback = dataTran;
                            if (feedback == 'yes') {
                                document.getElementById('clinicalnotes').submit();
                            } else {
                                $("#Transfer_Selected_Patient_Dialog").dialog("close");
                                $("#Transfer_Error").dialog("open");
                            }
                        }
                    };
                    myObjectProc.open('GET', 'Transfer_Selected_Patient.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Destination_ID=' + Destination_ID + '&Transfer_Type=' + Transfer_Type, true);
                    myObjectProc.send();
                } else {
                    if (Destination_ID == null || Destination_ID == '') {
                        document.getElementById("Destination_ID").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Destination_ID").style = 'border: 1px solid black';
                    }
                    if (Transfer_Type == null || Transfer_Type == '') {
                        document.getElementById("Transfer_Type").style = 'border: 2px solid red';
                    } else {
                        document.getElementById("Transfer_Type").style = 'border: 1px solid black';
                    }
                }
            }
        </script>

        <script type="text/javascript">
            function Get_Transfer_Destination() {
                var Transfer_Type = document.getElementById("Transfer_Type").value;
                var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
                if (Transfer_Type == '' || Transfer_Type == null) {
                    document.getElementById("Transfer_Process_Area").innerHTML = '<span style="color: #037CB0;"><b>SELECT TRANSFER TYPE</b></span>';
                } else {
                    if (window.XMLHttpRequest) {
                        myObjectTransfer = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectTransfer = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectTransfer.overrideMimeType('text/xml');
                    }

                    myObjectTransfer.onreadystatechange = function() {
                        dataTransfer = myObjectTransfer.responseText;
                        if (myObjectTransfer.readyState == 4) {
                            document.getElementById('Transfer_Process_Area').innerHTML = dataTransfer;
                        }
                    };
                    myObjectTransfer.open('GET', 'Get_Transfer_Destination.php?Transfer_Type=' + Transfer_Type + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
                    myObjectTransfer.send();
                }
            }
        </script>

        <script>
            $(document).ready(function() { //
                $("#patient_summarize_previous_history").dialog({
                    autoOpen: false,
                    width: '90%',
                    height: 520,
                    title: 'PATIENT SUMMARIZE PREVIOUS HISTORY',
                    modal: true,
                    position: 'middle'
                });
                $("#showdataConsult").dialog({
                    autoOpen: false,
                    width: '90%',
                    title: 'SELECT  ITEM FROM THIS CONSULTATION',
                    modal: true,
                    position: 'middle'
                });
                $("#otherdoctorStaff").dialog({
                    autoOpen: false,
                    width: '90%',
                    height: 520,
                    title: 'OTHER DOCTOR\'S REVIEWS',
                    modal: true,
                    position: 'middle'
                });
                $("#summerypatientfile").dialog({
                    autoOpen: false,
                    width: '95%',
                    height: 620,
                    title: 'PATIENT FILE',
                    modal: true,
                    position: 'middle'
                });
                $("#summerypatientallegies").dialog({
                    autoOpen: false,
                    width: '95%',
                    height: 620,
                    title: 'PATIENT ALLEGIES',
                    modal: true,
                    position: 'middle'
                });
                $("#Transfer_Patient_Dialog").dialog({
                    autoOpen: false,
                    width: '70%',
                    height: 220,
                    title: 'eHMS 4.0 ~ SAVE AND TRANSFER PATIENT',
                    modal: true,
                    position: 'middle'
                });
                $("#Transfer_Selected_Patient_Dialog").dialog({
                    autoOpen: false,
                    width: '42%',
                    height: 140,
                    title: 'eHMS 4.0 ~ CONFIRM PATIENT TRANSFER',
                    modal: true,
                    position: 'middle'
                });
                $("#Transfer_Error").dialog({
                    autoOpen: false,
                    width: '30%',
                    height: 120,
                    title: 'eHMS 4.0 ~ Process Fail',
                    modal: true,
                    position: 'middle'
                });
                $("#TB_Screening").dialog({
                    autoOpen: false,
                    width: '85%',
                    height: 600,
                    title: 'eHMS 4.0 ~ TB SCREENING',
                    modal: true,
                    position: 'middle'
                });
                $(".ui-icon-closethick").click(function() {
                    var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
                    //alert(Consultation_Type);
                    if (Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis' || Consultation_Type == 'diagnosis') {
                        updateDoctorConsult();
                    } else {
                        updateConsult();
                    }
                });
            });
        </script>
        <script>
            function updateConsult() {
                //alert('I am here');
                var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
                //alert(Consultation_Type);
                var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo $Registration_ID ?>&Patient_Payment_ID='<?php echo $_GET['Patient_Payment_ID'] ?>'&consultation_ID='<?php echo $consultation_ID ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                //alert(url2);
                $.ajax({
                    type: 'GET',
                    url: 'requests/itemselectupdate.php',
                    data: url2,
                    cache: false,
                    success: function(html) {
                        //alert(html);
                        html = html.trim();
                        var departs = html.split('tenganisha');
                        for (var i = 0; i < departs.length; i++) {
                            var Consultation_Type = departs[i].split('<$$$&&&&>');
                            //alert(Consultation_Type[0]);
                            if (Consultation_Type[0].trim() == 'Radiology') {
                                $('.Radiology').html(Consultation_Type[1]);
                            } else if (Consultation_Type[0].trim() == 'Treatment') {
                                $('.Treatment').html(Consultation_Type[1]);
                            } else if (Consultation_Type[0].trim() == 'Laboratory') {
                                $('#laboratory').html(Consultation_Type[1]);
                                //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                            } else if (Consultation_Type[0].trim() == 'Procedure') {
                                $('.Procedure').html(Consultation_Type[1]);
                            }else if (Consultation_Type[0].trim() == 'Nuclearmedicine') {
                                $('.Nuclearmedicine').html(Consultation_Type[1]); // nuclear msk
                            } else if (Consultation_Type[0].trim() == 'Surgery') {
                                $('.Surgery').html(Consultation_Type[1]);
                            } else if (Consultation_Type[0].trim() == 'Others') {
                                $('.otherconstype').html(Consultation_Type[1]);
                            }
                        }
                    }
                });
            }
        </script>
        <?php
        $sql_check_for_requirement_for_final_diagnosis_result = mysqli_query($conn, "SELECT require_final_diagnosis_before_select_treatment FROM tbl_hospital_consult_type") or die(mysqli_error($conn));
        if (mysqli_num_rows($sql_check_for_requirement_for_final_diagnosis_result) > 0) {
            $require_final_diagnosis = mysqli_fetch_assoc($sql_check_for_requirement_for_final_diagnosis_result)['require_final_diagnosis_before_select_treatment'];
        } else {
            $require_final_diagnosis = "no";
        }
        ?>
        <script>
            function updateDoctorConsult() {
                //alert('I am here');
                var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
                //alert(Consultation_Type);
                var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo $Registration_ID ?>&Patient_Payment_ID='<?php echo $_GET['Patient_Payment_ID'] ?>'&consultation_ID='<?php echo $consultation_ID ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                //alert(url2);
                $.ajax({
                    type: 'GET',
                    url: 'requests/itemdoctorselectupdate.php',
                    data: url2,
                    cache: false,
                    success: function(html) {
                        //alert(html);
                        html = html.trim();
                        var Consultation_Type = html.split('<$$$&&&&>');
                        if (Consultation_Type[0].trim() == 'provisional_diagnosis') {
                            $('.provisional_diagnosis').attr('value', Consultation_Type[1]);
                            if ($('.provisional_diagnosis').val() != '') {
                                $('.confirmGetItem').attr("onclick", "getItem('Laboratory')");
                            } else {
                                $('.confirmGetItem').attr("onclick", "confirmDiagnosis('Laboratory')");
                            }
                        } else if (Consultation_Type[0].trim() == 'diferential_diagnosis') {
                            //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                            $('.diferential_diagnosis').attr('value', Consultation_Type[1]);
                        } else if (Consultation_Type[0].trim() == 'diagnosis') {
                            $('.final_diagnosis').attr('value', Consultation_Type[1]);
                        }
                    }
                });
            }

            function confirm_final_diagnosis(Consultation_Type) {
                var mainComplain = document.getElementById('maincomplain').value;
        
                if(mainComplain ==''){
                    alert("Please Enter main Complain");
                    $("#maincomplain").css("border","2px solid red");
                    $("#maincomplain").focus(); exit;
                }
                var require_final_diagnosis = '<?= $require_final_diagnosis ?>';
                if ($('.final_diagnosis').val() == "" && require_final_diagnosis == "yes") {
                    alert("YOU HAVE TO SELECT FINAL DIAGNOSIS FIRST,BEFORE ADD ANY TREATMENT");
                } else {
                    getItem(Consultation_Type);
                }
            }
        </script>
        <script>
            $(document).ready(function() {
                select_oncology_primary_code()
            });

            function doneDiagonosisselect() {
                //  alert("hhfrkr");
                var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
                //alert(Consultation_Type);
                if (Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis' || Consultation_Type == 'diagnosis') {
                    updateDoctorConsult();

                    select_oncology_primary_code()

                } else {
                    updateConsult();
                    select_oncology_primary_code()

                }

                $("#showdataConsult").dialog("close");
            }

            function select_oncology_primary_code() {
                var Registration_ID = "<?= $Registration_ID; ?>"
                $.ajax({
                    type: 'POST',
                    url: 'update_clinic_remark.php',
                    data: {
                        Registration_ID: Registration_ID,
                        baseline_breast: ''
                    },
                    success: function(responce) {
                        $("#baseline_breast").html(responce);
                    }
                });
            }
        </script>
        <script>
            function showOthersDoctorsStaff() {
                document.getElementById('doctorsInfo').innerHTML = '';
                if (window.XMLHttpRequest) {
                    ajaxTimeObjt = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                    ajaxTimeObjt.overrideMimeType('text/xml');
                }
                ajaxTimeObjt.onreadystatechange = function() {
                    var data = ajaxTimeObjt.responseText;
                    document.getElementById('doctorsInfo').innerHTML = data;
                    $("#otherdoctorStaff").dialog("open");
                }; //specify name of function that will handle server response....
                ajaxTimeObjt.open("GET", "get_other_doc_staff.php?consultation_ID=<?php echo $consultation_ID ?>&Registration_ID=<?php echo $Registration_ID ?>", true);
                ajaxTimeObjt.send();
            }
        </script>
        <script>
            function showSummeryPatientFile() {
                document.getElementById('summpatfileInfo').innerHTML = '';
                if (window.XMLHttpRequest) {
                    ajaxTimeObjt = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                    ajaxTimeObjt.overrideMimeType('text/xml');
                }
                ajaxTimeObjt.onreadystatechange = function() {
                    var data = ajaxTimeObjt.responseText;
                    document.getElementById('summpatfileInfo').innerHTML = data;
                    $("#summerypatientfile").dialog("open");
                }; //specify name of function that will handle server response....
                ajaxTimeObjt.open("GET", "get_summery_pat_file.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID ?>&Registration_ID=<?php echo $Registration_ID ?>", true);
                ajaxTimeObjt.send();
            }


            function showPatientAllegies() {
                document.getElementById('summpatallegiesInfo').innerHTML = '';
                if (window.XMLHttpRequest) {
                    ajaxTimeObjt = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                    ajaxTimeObjt.overrideMimeType('text/xml');
                }
                ajaxTimeObjt.onreadystatechange = function() {
                    var data = ajaxTimeObjt.responseText;
                    document.getElementById('summpatallegiesInfo').innerHTML = data;
                    $("#summerypatientallegies").dialog("open");
                }; //specify name of function that will handle server response....
                ajaxTimeObjt.open("GET", "get_summery_pat_allege.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID ?>&Registration_ID=<?php echo $Registration_ID ?>", true);
                ajaxTimeObjt.send();
            }
        </script>
        <div id="procedure_dialog_info" style="display:none">
            <table class="table">
                <tr>
                    <td>Type of procedure</td>
                    <td>
                        <select style="padding:3px;width: 100%" id="type_of_procedure">
                            <option value="">Select type of procedure</option>
                            <option>Specialized</option>
                            <option>Major</option>
                            <option>Minor</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Duration Of procedure</td>
                    <td>
                        <input type="text" style="padding:3px;width: 100%" id="duration_of_procedure" placeholder="Duration of procedure" />
                    </td>
                </tr>
                <tr>
                    <td>Type of Anesthetic</td>
                    <td>
                        <select style="padding:3px;width: 100%" id='Type_Of_Anesthetic'>
                            <option value="">Select type of Anesthetic</option>
                            <option>GA</option>
                            <option>REGIONAL</option>
                            <option>LOCAL</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="SAVE" class="art-button-green pull-right" onclick='save_procedure_info()' onclick="SAVE" />
                    </td>
                </tr>
            </table>
        </div>
        <input type="text" id="Patient_Payment_Item_List_ID_consultation" style="display:none" />

        <div id="claim_form" style="display:none">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                          <div class="form-group">
                            <label class="col-md-3">Select Location</label>
                            <div class="col-md-9">
                            <select  class='form-control' style='text-align: center;width:90%!important' id="ward_id" required="required">
                                    <?php
                                        $Select_location=mysqli_query($conn,"SELECT * FROM tbl_hospital_ward ORDER BY Hospital_Ward_Name ASC");
                                        while($location_Row=mysqli_fetch_assoc($Select_location)){
                                            $clinic_Ward_id=$location_Row['Hospital_Ward_ID'];
                                            $clinic_Ward_name=$location_Row['Hospital_Ward_Name'];
                                            ?>
                                    
                                            <option value="<?php echo $clinic_Ward_id?>"><?php echo $clinic_Ward_name?></option>
                                            <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <form class="form-horzontal">
                        <div class="form-group">
                            <label class="col-md-3">Nurse on Duty</label>
                            <div class="col-md-9">
                       <select style='text-align: center;width:90%!important' id="emp_id">                           
                                    <?php
                                        $Select_employee=mysqli_query($conn,"select Employee_ID,Employee_Name from tbl_employee where Employee_Type = 'Nurse'");
                                        while($emp_Row=mysqli_fetch_assoc($Select_employee)){
                                            $emp_id=$emp_Row['Employee_ID'];
                                            $emp_name=$emp_Row['Employee_Name'];
                                            ?>
                                            <option value="<?php echo $emp_id?>"> <?php echo $emp_name?></option>
                                        <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    
                    </form>

                    
                        <p class="text-danger text-center">I declare and confirm that I have reviewed this patient during my call.</p>
                    <br>
                    
                    <?php 
                      echo $clinic_location_id;
                        $data = '{"Registration_ID":"'.$Registration_ID.'","doctor_id":"'.$employee_ID.'","sponsor_id":"'.$Sponsor_ID.'","dept_id":"'.$finance_department_id.'","clinic_id":"'.$clinic_location_id.'"}';
                    ?>
                    <input type="button" value="SUBMIT" class="art-button-green pull-right" onclick='submit_oncall_claim(<?=$data?>)'/>

                </div>
            </div>
        </div>
    <!-- <div class="row">
        <div class="col-md-12">
            <input type="button" value="SUBMIT" class="art-button-green pull-right" onclick="close_associated_doctor_dialog1()"/>
        </div>
    </div> -->
</div>

        <script>
            function on_call_claim_form_dialogy(){
                if(confirm('Are you sure you what to submit this as on call claim?')){
                    $("#claim_form").dialog({
                                    title: 'ON CALL CLAIM',
                                    width: '50%',
                                    height: 250,
                                    modal: true, 
                                }); 
                }
            }

            function submit_oncall_claim(data) {
                var nurse_id = $('#emp_id').val();
                var ward_id = $('#ward_id').val();
                var Registration_ID= data.Registration_ID; 
                var doctor_id= data.doctor_id;
                var sponsor_id= data.sponsor_id;
                var dept_id= data.dept_id;


            if(ward_id == "" || nurse_id ==""){           
                alert("fill all required space first");
            }else{
                    $.ajax({
                        type:'POST',
                        url:'on_call_claim.php',
                        data:{nurse_id:nurse_id,ward_id:ward_id,Registration_ID:Registration_ID,doctor_id:doctor_id,sponsor_id:sponsor_id,dept_id:dept_id},
                        success:function(data){                
                            alert(data);
                            $("#claim_form").dialog("close");               
                        }
                    });
                }
            }


        </script>
        <script>
            function save_procedure_info() {
                var Patient_Payment_Item_List_ID = $("#Patient_Payment_Item_List_ID_consultation").val();

                var type_of_procedure = $("#type_of_procedure").val();
                var duration_of_procedure = $("#duration_of_procedure").val();
                var Type_Of_Anesthetic = $("#Type_Of_Anesthetic").val();
                var validate = 0;
                if (type_of_procedure == "") {
                    $("#type_of_procedure").css("border", "2px solid red");
                    validate++;
                } else {
                    $("#type_of_procedure").css("border", "")
                }
                if (duration_of_procedure == "") {
                    $("#duration_of_procedure").css("border", "2px solid red");
                    validate++;
                } else {
                    $("#duration_of_procedure").css("border", "")
                }
                if (Type_Of_Anesthetic == "") {
                    $("#Type_Of_Anesthetic").css("border", "2px solid red");
                    validate++;
                } else {
                    $("#Type_Of_Anesthetic").css("border", "")
                }
                if (validate <= 0) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_save_procedure_info.php',
                        data: {
                            Patient_Payment_Item_List_ID: Patient_Payment_Item_List_ID,
                            type_of_procedure: type_of_procedure,
                            duration_of_procedure: duration_of_procedure,
                            Type_Of_Anesthetic: Type_Of_Anesthetic
                        },
                        success: function(data) {
                            if (data == "success") {
                                validateInfos();
                            } else {
                                alert("Process fail please try again " + data);
                            }
                        }
                    });
                }
            }

            function check_if_consultation_is_procedure() {
                var Patient_Payment_Item_List_ID = '<?= $Patient_Payment_Item_List_ID_consultation ?>';
                var Patient_Payment_ID = '<?= $Patient_Payment_ID_consultation ?>';
                $.ajax({
                    type: 'POST',
                    url: 'ajax_check_if_consultation_is_procedure.php',
                    data: {
                        Patient_Payment_Item_List_ID: Patient_Payment_Item_List_ID,
                        Patient_Payment_ID: Patient_Payment_ID
                    },
                    success: function(data) {
                        if (data != "item_haipo") {
                            $("#Patient_Payment_Item_List_ID_consultation").val(data);
                            $("#procedure_dialog_info").dialog({
                                title: 'SAVE PROCEDURE INFO',
                                width: '50%',
                                height: 300,
                                modal: true,
                            });
                        } else {
                            validateInfos();
                        }
                    }
                });
            }

            function validateInfos() {
                var ToBeAdmitted = $('#ToBeAdmitted').val();
                var Ward_suggested = $('#Ward_suggested').val();
                var doctor_admits_patient = '<?php echo $doctor_admits_patient; ?>';
                var mainComplain = document.getElementById('maincomplain').value;
                var provisional_diagnosis = document.getElementById('provisional_diagnosis_comm').value;
                var diagnosis = $('.final_diagnosis').val(); //document.getElementById('diagnosis').value;
                var Comment_For_Radiology = document.getElementById('Comment_For_Radiology').value;
                var Comment_For_Laboratory = document.getElementById('Comment_For_Laboratory').value;
                var mandatory_comments = '<?php echo $mandatory_comments; ?>';
                if (doctor_admits_patient === 'yes' && ToBeAdmitted === 'yes') {

                    if (Ward_suggested == '' || Ward_suggested == null) {
                        $('#Ward_suggested').css({
                            'border': '2px solid red'
                        });
                        $('#ward_warning').show();
                        return false;
                    }
                }
                //    ======10 characters for the doctor notes =====
                   var from_consulted='<?$from_consulted ?>';
                  var maincomplain_chr_lngth=$("#maincomplain").val().length;
                  if(maincomplain_chr_lngth<10){
                    alert("Main Complain must be atleast 10 character");  
                    $("#maincomplain").css("border","2px solid red");
                    $("#maincomplain").focus();
                    return false;
                  }else{
                    $("#maincomplain").css("border","");  
                  }
                  var Type_of_patient_case=$("#Type_of_patient_case").val();
                  
                //   if(Type_of_patient_case=='new_case'){
                //     var history_present_illness_chr_lngth=$("#history_present_illness").val().length;
                //     if(history_present_illness_chr_lngth<10){
                //         alert("History of present illness must be atleast 10 character");  
                //         $("#history_present_illness").css("border","2px solid red");
                //         $("#history_present_illness").focus();
                //         return false;
                //     }else{
                //         $("#history_present_illness").css("border","");  
                //     }
                //     var review_of_other_systems_chr_lngth=$("#review_of_other_systems").val().length;
                // if(review_of_other_systems_chr_lngth<10){
                //     alert("Review of other system must be atleast 10 character");  
                //     $("#review_of_other_systems").css("border","2px solid red");
                //     $("#review_of_other_systems").focus();
                //     return false;
                // }else{
                //     $("#review_of_other_systems").css("border","");  
                // }
                // var local_examination_chr_lngth=$("#local_examination").val().length;
                // if(local_examination_chr_lngth<5){
                //     alert("Local examination must be atleast 5 character");  
                //     $("#local_examination").css("border","2px solid red");
                //     $("#local_examination").focus();
                //     return false;
                // }else{
                //     $("#local_examination").css("border","");  
                // }
                // var systemic_observation_chr_lngth=$("#systemic_observation").val().length;
                // if(systemic_observation_chr_lngth<10){
                //     alert("Systematic Examination must be atleast 10 character");  
                //     $("#systemic_observation").css("border","2px solid red");
                //     $("#systemic_observation").focus();
                //     return false;
                // }else{
                //     $("#systemic_observation").css("border","");  
                // }
                // ////////////////////////////////////////
                //   }

                //   var history_present_illness_chr_lngth=$("#firstsymptom_date").val().length;
                //   if(history_present_illness_chr_lngth<15){
                //     alert("first symptom date must be atleast 16 character");  
                //     $("#firstsymptom_date").css("border","2px solid red");
                //     $("#firstsymptom_date").focus();
                //     return false;
                //   }else{
                //     $("#firstsymptom_date").css("border","");  
                //   }
                  
                //   var general_observation_chr_lngth=$("#general_observation").val().length;
                //   if(general_observation_chr_lngth<10){
                //     alert("General observation must be atleast 10 character");  
                //     $("#general_observation").css("border","2px solid red");
                //     $("#general_observation").focus();
                //     return false;
                //   }else{
                //     $("#general_observation").css("border","");  
                //   }



                if (ToBeAdmitted === 'yes' && (diagnosis == '' || diagnosis == null)) {
                    alert('Please add final diagnosis before admitting the patient');
                    return false;
                }




                if (mainComplain == '' || mainComplain == null) {
                    alert('Please enter patient main complain(s)');
                   
                    $("#maincomplain").css("border","2px solid red");
                    $("#maincomplain").focus(); exit;
                    $("#ui-id-1").click();
                }

                <?php
                if ($req_op_prov_dign == '1') {
                ?>
                    else if (provisional_diagnosis == '' || provisional_diagnosis == null) {
                        alert('Please enter patient provisional diagnosis(s)');
                        $("#ui-id-2").click();
                    }
                <?php
                } else if ($req_op_final_dign == '1') {
                ?>
                    else if (diagnosis == '' || diagnosis == null) {
                        alert('Please enter patient final diagnosis(s)');
                    }
                <?php
                }
                ?>
                else {



                    if (mandatory_comments == 1) {
                        if ((Comment_For_Radiology == '' || Comment_For_Radiology == null || Comment_For_Laboratory == '' || Comment_For_Laboratory == null)) {
                            alert('Comments for radiology and laboratory must be filled!');
                        } else {
                            if (confirm('Are you sure you want to save info(s)?')) {
                                check_if_afya_card_config_is_on();
                                //                                  document.getElementById('clinicalnotes').submit();
                            }
                        }
                    } else {
                        if (confirm('Are you sure you want to save info(s)?')) {
                            //                              document.getElementById('clinicalnotes').submit();
                            check_if_afya_card_config_is_on();
                        }
                    }


                }
            }

            function check_if_afya_card_config_is_on() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_check_if_afya_card_config_is_on.php',
                    data: {
                        function_module: "afya_card_module"
                    },
                    success: function(data) {
                        if (data == "enabled") {
                            test_connection_to_ehms_mr_online();
                        } else {
                            document.getElementById('clinicalnotes').submit();
                        }
                    }
                });
            }

            function test_connection_to_ehms_mr_online() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_test_connection_to_ehms_mr_online.php',
                    data: {
                        check_server_connection: "check"
                    },
                    success: function(data) {
                        //              alert(data+"kkkkkkkkkkkkkkkkkkk");
                        if (data == "connection_available") {
                            send_patient_data_to_ehms_mr_online()
                        } else {
                            document.getElementById('clinicalnotes').submit();
                        }
                    }
                });
            }

            function send_patient_data_to_ehms_mr_online() {
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var consultation_ID = '<?php echo $consultation_ID; ?>';
                $.ajax({
                    type: 'POST',
                    url: 'ajax_send_patient_data_to_ehms_mr_online.php',
                    data: {
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    },
                    success: function(data) {
                        console.log(data);
                        document.getElementById('clinicalnotes').submit();
                    }
                });
            }
        </script>
        <script>
            function validateInfos2() {
                var mainComplain = document.getElementById('maincomplain').value;
                var provisional_diagnosis = document.getElementById('provisional_diagnosis_comm').value;
                var diagnosis = $('.final_diagnosis').val(); //document.getElementById('diagnosis').value;

                if (mainComplain == '' || mainComplain == null) {
                    alert('Please enter patient main complain(s)');
                    $("#maincomplain").css("border","2px solid red");
                    $("#maincomplain").focus(); exit;
                    $("#ui-id-1").click();
                }

                <?php
                if ($req_op_prov_dign == '1') {
                ?>
                    else if (provisional_diagnosis == '' || provisional_diagnosis == null) {
                        alert('Please enter patient provisional diagnosis(s)');
                        $("#ui-id-2").click();
                    }
                <?php
                } else if ($req_op_final_dign == '1') {
                ?>
                    else if (diagnosis == '' || diagnosis == null) {
                        alert('Please enter patient final diagnosis(s)');
                    }
                <?php
                }
                ?>
                else {
                    $("#Transfer_Patient_Dialog").dialog("open");
                    //document.getElementById('clinicalnotes').submit();
                }
            }
        </script>
        <script>
            //copy this function below 
            function Show_Preview(obj) {
                window.open(
                    'vital_report.php?patient_id=' + obj,
                    '_blank'
                );
            }
        </script>
        <script>
            function Show_Patient_File() {
                // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
                //winClose.close();
                //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

            }

            function popupwindow(url, title, w, h) {
                var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                var wTop = window.screenTop ? window.screenTop : window.screenY;
                var left = wLeft + (window.innerWidth / 2) - (w / 2);
                var top = wTop + (window.innerHeight / 2) - (h / 2);
                var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left); //'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                return mypopupWindow;
            }
        </script>
        <script>
            function savedoctorinfos(instance) {
                var fieldName = $(instance).attr('name');
                var fieldValue = $(instance).val();
                var consultation_ID = '<?php echo $consultation_ID ?>';
                var from_consulted = '<?php echo $_GET['from_consulted'] ?>';
                $.ajax({
                    type: 'GET',
                    url: 'clinicalautosave.php',
                    data: 'fieldName=' + fieldName + '&fieldValue=' + fieldValue + '&consultation_ID=' + consultation_ID+'&from_consulted='+from_consulted,
                    cache: false,
                    success: function(result) {
                        //alert(result);  
                    }
                });
                //alert('Name=' + fieldName + '  value=' + fieldValue+ '  consultation_ID=' + consultation_ID);
            }
        </script>
        <script>
            function savePatientHist() {
                var famscohist = $('#famscohist').val();
                var pastobshist = $('#pastobshist').val();
                var pastpaedhist = $('#pastpaedhist').val();
                var pastmedhist = $('#pastmedhist').val();
                var pastdenthist = $('#pastdenthist').val();
                var pastsurghist = $('#pastsurghist').val();
                var Registration_ID = '<?php echo $_GET['Registration_ID'] ?>';
                if (famscohist == '' || pastobshist == '' || pastpaedhist == '' || pastdenthist == '' || pastsurghist == '' || pastmedhist == '') {
                    alert('All field must be filled first.');
                    exit;
                }

                if (!confirm('Are you sure you want to save patient history?')) {
                    exit;
                }

                $.ajax({
                    type: 'GET',
                    url: 'savepatienthistry.php',
                    data: 'famscohist=' + famscohist + '&pastobshist=' + pastobshist + '&pastpaedhist=' + pastpaedhist + '&pastmedhist=' + pastmedhist + '&pastdenthist=' + pastdenthist + '&pastsurghist=' + pastsurghist + '&Registration_ID=' + Registration_ID,
                    cache: false,
                    success: function(result) {
                        if (result == '1') {
                            alert('Saved successifully');
                            window.location = window.location.href;
                        } else {
                            alert('An error has occured!Please try again later.');
                        }
                    }
                });
            }
        </script>

        <script type="text/javascript">
            function Pequire_Spectacle(Registration_ID) {
                var Employee_ID = '<?php echo $Employee_ID; ?>';
                var Status = '';
                var consultation_ID = '<?php echo $consultation_ID; ?>';
                if (document.getElementById("Optical_Option").checked) {
                    Status = "Checked";
                } else {
                    Status = "Not_checked";
                }
                if (window.XMLHttpRequest) {
                    myObjectRequire = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectRequire = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectRequire.overrideMimeType('text/xml');
                }
                myObjectRequire.onreadystatechange = function() {
                    data = myObjectRequire.responseText;
                    if (myObjectRequire.readyState == 4) {

                    }
                }; //specify name of function that will handle server response........

                myObjectRequire.open('GET', 'Require_Spectacle.php?Registration_ID=' + Registration_ID + '&Status=' + Status + '&consultation_ID=' + consultation_ID + '&Employee_ID=' + Employee_ID, true);
                myObjectRequire.send();
            }
        </script>

        <script type="text/javascript">
            function Perform_Procedure() {
                var Patient_Payment_ID = '<?php echo $Patient_Payment_ID; ?>';
                var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var consultation_ID = '<?php echo $consultation_ID; ?>';
                if (window.XMLHttpRequest) {
                    myObjectPerform = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectPerform = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectPerform.overrideMimeType('text/xml');
                }
                myObjectPerform.onreadystatechange = function() {
                    dataP = myObjectPerform.responseText;
                    if (myObjectPerform.readyState == 4) {
                        window.open("proceduredocotorpatientinfo.php?Session=Outpatient&sectionpatnt=doctor_with_patnt&Patient_Payment_ID=" + Patient_Payment_ID + "&Patient_Payment_Item_List_ID=" + Patient_Payment_Item_List_ID + "&Registration_id=" + Registration_ID + "&ProcedureWorks=ProcedureWorksThisPage", "_parent");
                    }
                }; //specify name of function that will handle server response........

                myObjectPerform.open('GET', 'Perform_Procedure.php?Registration_ID=' + Registration_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&consultation_ID=' + consultation_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
                myObjectPerform.send();
            }
        </script>

        <script>
            function consultChange(consultation_type) {
                document.getElementById("recentConsultaionTyp").value = consultation_type;
                var url2 = 'Consultation_Type=' + consultation_type + '&<?php
                                                                        if ($Registration_ID != '') {
                                                                            echo "Registration_ID=$Registration_ID&";
                                                                        }
                                                                        ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
                //  alert(url2);
                $.ajax({
                    type: 'GET',
                    url: 'doctoritemselectajax.php',
                    data: url2,
                    cache: false,
                    success: function(html) {
                        $('#myConsult').html(html);
                    }
                });
            }
        </script>

        <script>
            function ward_nature(Hospital_Ward) {
                if (window.XMLHttpRequest) {
                    myObjectPreview = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectPreview.overrideMimeType('text/xml');
                }
                myObjectPreview.onreadystatechange = function() {
                    data = myObjectPreview.responseText;
                    if (myObjectPreview.readyState == 4) {
                        //                    document.getElementById('Ward_nature').value=data;
                        var gender = '<?php echo $Gender; ?>';
                        if (gender === data) {
                            // document.getElementById('Ward_suggested').value=data;
                        } else if (gender !== data) {
                            if (data === 'Both') {
                                // document.getElementById('Ward_suggested').value=data;   
                            } else {
                                alert('This patient cannot be admitted in the ' + data + ' ward');
                                document.getElementById('Ward_suggested').value = '';
                                return false;
                            }
                        }
                    }
                }; //specify name of function that will handle server response........

                myObjectPreview.open('GET', 'Get_Ward_Nature.php?Hospital_Ward=' + Hospital_Ward, true);
                myObjectPreview.send();
            }
        </script>
        <!--   <script>
    function patientFileByFolio() {
//        $("#getFileByFolio").dialog('option', 'title', 'PATIENT FILE BY FOLIO FOR <?php echo $Patient_Name ?>);
        $("#getFileByFolio").dialog("open");
         var Start_Date='000-00-00';
         var End_Date='<?= $Today ?>';
         var Billing_Type='All';
         var Sponsor_ID='<?= $Sponsor_ID ?>';
         var Patient_Number='<?= $Registration_ID ?>';
         var Patient_Type='All';


        $.ajax({
            type: 'GET',
            url: 'Revenue_Collection_BY_Folio_Report_Filtered.php',
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
</script> -->
        <input type="text" id="hpi_row_count" value="1" class="hide" />
        <input type="text" id="main_complain_row_count" value="1" class="hide" />
        <script>
            function add_hpi_row() {
                var row_count = parseInt($("#hpi_row_count").val()) + 1;
                $("#hpi_tbl_body").append("<tr id='this_row" + row_count + "'><td><input type='text' placeholder='Complain' name='hpi_complain[]'/></td><td><input type='text' placeholder='Duration' name='hpi_duration[]'/></td><td><input type='text' placeholder='Onset' name='hpi_onset[]'/></td><td><input type='text' placeholder='Periodicity' name='hpi_periodicity[]'/></td><td><input type='text' placeholder='Aggravating Factor' name='hpi_aggravating_factor[]'/></td><td><input type='text' placeholder='Relieving Factor' name='hpi_relieving_factor[]'/></td><td><input type='text' placeholder='Associated with' name='hpi_associated_with[]'/></td><td><input type='button' value='X' onclick='remove_this_hpi_row(" + row_count + ")'/></td></tr>");
                $("#hpi_row_count").val(row_count);
            }

            function remove_this_hpi_row(this_row) {
                $("#this_row" + this_row).remove().fadeOut();
            }

            function remove_this_main_complain_row(this_row) {
                $("#complain_row" + this_row).remove().fadeOut();
            }

            function add_main_complain_row_area() {
                var row_count = parseInt($("#main_complain_row_count").val()) + 1;
                $("#main_complain_add_tbl_body").append("<tr id='complain_row" + row_count + "'><td><textarea style='resize:none;padding-left:5px;height: 40px'required='required'  name='maincomplain_incrmnt[]'></textarea></td> <td style='width:20%'><textarea placeholder='Duration' style='text-align: center;resize:none;padding-left:5px;height: 40px' name='maincomplain_duration_incrmnt[]'></textarea></td><td><input type='button' value='X' onclick='remove_this_main_complain_row(" + row_count + ")'/></td></tr>");
                $("#main_complain_row_count").val(row_count);
            }
            $('#death_date_time').datetimepicker({
                dayOfWeekStart: 1,
                lang: 'en',
                //startDate:    'now'
            });
            $('#death_date_time').datetimepicker({
                value: '',
                step: 1
            });

            // RDTC PATCH TEST FUNCTION
            function open_rdtc_dialogy() {
                var patient_id = $('#patient_id').val();
                var employee_id = $('#employee_id').val();
                var patient_name = $('#patient_name').val();
                var patient_age = $('#patient_age').val();
                var patient_gender = $('#patient_gender').val();
                $.ajax({
                    type: 'post',
                    url: 'rdtc_record.php',
                    data: {
                        patient_id: patient_id,
                        employee_id: employee_id,
                        patient_name: patient_name,
                        patient_age: patient_age,
                        patient_gender: patient_gender
                    },
                    success: (data) => {
                        $("#rdtc_dialogy").dialog({
                            title: 'EUROPEAN BASELINE SERIES S-1000 TEST',
                            width: '90%',
                            height: 700,
                            modal: true,
                        });
                        $("#rdtc_dialogy").html(data);
                        $("#rdtc_dialogy").dialog("open");
                    }
                });
            }
            // RDTC PATCH TEST FUNCTION


            // Dose monitoring function
            var dose_button = document.getElementById('dose_button');
                dose_button.addEventListener('click',()=>{

                    var patient_name = $('#patient_name').val();
                    var patient_gender = $('#patient_gender').val();
                    var patient_age = $('#patient_age').val();
                    var patient_id = $('#patient_id').val();
                    var employee_id = $('#employee_id').val();
                    var diagnosis = $('.final_diagnosis').val(); 

                    $.ajax({
                        type: 'post',
                        url: 'dose_monitoring_dermatology.php',
                        data:{ 
                            patient_name : patient_name, 
                            patient_gender : patient_gender, 
                            patient_age : patient_age, 
                            patient_id : patient_id,
                            employee_id : employee_id,
                            diagnosis: diagnosis                                   
                        },
                        success: (data)=>{
                            $('#dose_monitoring').dialog({
                                autoOpen: false,
                                width: '90%',
                                title: 'Dermatology Dose Monitoring ',
                                modal: true
                            });
                                $("#dose_monitoring").html(data);
                                $("#dose_monitoring").dialog("open");
                            }
                        });
                });
                // Dose Monitoring function
        </script>

<script>
var radio = document.getElementById('FollowUp');
radio.addEventListener('change',function(){
    var month = document.querySelector('.month');
    if(this.checked){
        month.style.display='inline';
    }else{
        month.style.display='none';
    }
})
</script>
        <!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
 
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>-->
        <?php
        include("./includes/footer.php");
        ?>
