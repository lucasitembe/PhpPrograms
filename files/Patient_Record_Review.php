<style>
    .userinfo td,tr{
        height:20px ;
        border:none !important; 
    }
    .userinfo tr{
        border:none !important;
    }
    .headerTitle{
        background:#ccc;padding:5px;font-size: x-large;font-weight:bold;text-align:left;  
        width:100%;    
    }
    .modificationStats:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0);
    }

    .prevHistory:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0); 
    }
    .no_color{
        color:inherit;
        text-decoration:none;  
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include 'Patient_Record_Review_out_frame.php'; 
include 'Patient_Record_Review_in_frame.php';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
        
    } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
        
    } else {
        if (isset($_SESSION['userinfo']['Patient_Record_Works'])) {
            if ($_SESSION['userinfo']['Patient_Record_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


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
}
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
$Check_In_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Check_In_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Registration_ID='$Registration_ID'"))['Check_In_ID'];

$referral_letter =mysqli_fetch_assoc(mysqli_query($conn, "SELECT referral_letter FROM tbl_check_in WHERE Check_In_ID='$Check_In_ID' AND Registration_ID='$Registration_ID'"))['referral_letter'];
if($referral_letter !=NULL){
    echo "<a href='excelfiles/$referral_letter'  target='_blank' ><input type='button' class='btn btn-danger btn-sm' value='REFFERAL LETTER'></a>";
}
?>
<a href="print_patient_file.php?consultation_ID=<?php echo $_GET['consultation_ID'] ?>&Registration_ID=<?php echo $_GET['Registration_ID'] ?>" target="_blank" class="art-button-green">PRINT PATIENT FILE</a>
<input type="button" value="BACK" class="art-button-green" onclick="history.go(-1)"/>

<!-- <a href="#" onclick="goBack()"class="art-button-green">BACK</a> -->
<script>
// function goBack() {
//     window.history.back();
// }
</script>
<!-- get employee id-->
<?php
// if (isset($_GET['Section']) && $_GET['Section'] == 'Doctor') {
//     echo "<a href='Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab') {
//     echo "<a href='Patientfile_Record_Detail.php?Section=DoctorLab&Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad') {
//     echo "<a href='Patientfile_Record_Detail.php?Section=DoctorRad&Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&PatientType=&listtype=FromRec&Doctor=yes&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DoctorsPerformancePateintSummary') {
//     echo "<a href='Patientfile_Record_Detail.php?Section=DoctorsPerformancePateintSummary&Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&PatientType=&listtype=FromRec&Doctor=yes&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// } elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
//     echo "<a href='Patientfile_Record_Detail.php?Section=ManagementPatient&consultation_ID=" . $row['consultation_ID'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Employee_ID=" . $_GET['Employee_ID'] . "&Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// } else {

//     if (isset($_GET['fromPatientFile'])) {
//         if ($_GET['fromPatientFile'] == 'true') {

//             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
// //              echo "<a href='Patientfile_Record_Detail.php?section=Patient&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
// //  
//         }
//     } elseif (isset($_GET['Patient_Payment_ID']) && ($_GET['Patient_Payment_Item_List_ID'])) {
//         if ($_GET['position'] == 'out') {
//             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
//         } elseif ($_GET['position'] == 'in') {
//             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "&Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
//         }
//     } elseif (isset($_GET['Registration_ID']) && ($_GET['consultation_ID'])) {
//         if ($_GET['position'] == 'out') {
//             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "&consultation_ID=" . $_GET['consultation_ID'] . "&position=in&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
//         } else if ($_GET['position'] == 'in') {
//             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "&consultation_ID=" . $_GET['consultation_ID'] . "&item_ID=" . $GET['item_ID'] . "&position=in&this_page_from=$this_page_from' class='art-button-green'>BACK</a>";
//         }
//     }


    //echo "<a href='Patientfile_Record_Detail.php?Registration_ID=" . $_GET['Registration_ID'] . "' class='art-button-green'>BACK</a>";
//}
$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
//die($consultation_ID);
$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,c.Process_Status,e.Employee_Type FROM tbl_consultation c, tbl_employee e  WHERE e.Employee_ID=c.employee_ID AND c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
$Process_Status=$docResult['Process_Status'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury,ch.past_medical_history,ch.review_of_other_systems,ch.doctor_plan_suggestion,ch.family_social_history,ch.Gynocological_history FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
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
$check_was_outpatient_ct = mysqli_query($conn,"SELECT c.consultation_ID FROM tbl_check_in_details cd, tbl_consultation c WHERE cd.consultation_ID=c.consultation_ID AND c.consultation_ID = '" . $_GET['consultation_ID'] . "' AND Patient_Payment_Item_List_ID IS NULL") or die(mysqli_error($conn));

if (mysqli_num_rows($check_was_outpatient) == 0) {
    $hasOutpatientDetails = true;
} elseif (mysqli_num_rows($check_was_outpatient_ct) == 0) {
    $hasOutpatientDetails = true;
}


echo '<br/><br/><br/><fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">PATIENT MEDICATION RECORD</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="">' . $Patient_Name . '</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">' . $Country . '</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">' . $Region . '</td>
                <td rowspan="4" width="100">
                    <img width="120" height="90" name="Patient_Picture" id="Patient_Pictured" src="./patientImages/' . $Patient_Picture . '">
                </td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>' . $Registration_ID . '</td><td style="text-align:right"><b>Phone #:</b></td><td style="">' . $Phone_Number . '</td><td style="text-align:right"><b>District:</b></td><td style="">' . $District . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">' . date("j F, Y", strtotime($Date_Of_Birth)) . ' </td><td style="text-align:right"><b>Gender:</b></td><td style="">' . $Gender . '</td><td style="text-align:right"><b>Diseased:</b></td><td style="">' . $Deseased . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> ' . $Guarantor_Name . $sponsoDetails . '</td>
                <td style="width:10%;text-align:right" ><b>Consultation Date:</b></td><td colspan=""> ' . $Consultation_Date_And_Time . '<b style="color:red;"> Serving Status </b> <b >'.strtoupper($Process_Status).'</b></td>
                <td style="width:10%;text-align:right" ><b>Consultant :</b></td><td colspan=""> ' . $Employee_Title . ' . ' . ucfirst($Employee_Name) . '</td>
            </tr>
        </table>
    </div>';


echo displayOutpatientInfos($hasOutpatientDetails, $hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

echo '<br/><br/>';

echo displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

echo '<br/><br/>';
#malopa added : display patient tb info
$date = $_GET['tarehe'];
echo displayTbInfo($Registration_ID, $date);
echo '</fieldset>
<div style="margin-top:10px">';
if ($hasOutpatientDetails) {
    echo ' <a href="#outpatient" class="art-button-green " style="float:left;font-size:18px ">Go to outpatient details</a>';
}if ($hasInpatientDetails) {
    echo '<a href="print_patient_discharge_summery.php?consultation_ID='.$consultation_ID.'&Registration_ID='.$Registration_ID.'" target="_blank" class="art-button-green hide" style="float:right;font-size:18px">Print Discharge Summary</a>';
    echo '<a href="#inpatient" class="art-button-green" style="float:right;font-size:18px">Go to inpatient details</a>';
}

echo '</div>

<div id="showdata" style="width:100%;overflow-x:hidden;display:none;overflow-y:scroll">
    <div id="my">
    </div>
</div>
<div id="showdataComm" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myComm">
    </div>
</div>
<div id="historyResults1" style="display:none"></div>';
?>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>

<script>
    
 function preview_lab_result(Product_Name,Payment_Item_Cache_List_ID){
    window.open("preview_ntergrated_lab_result.php?Product_Name="+Product_Name+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID,"_blank");
 }
    
    
    //Results modifications
    $('.modificationStats').click(function () {
        $('#labGeneral').fadeOut();
        var parameter = $(this).attr('id');
        var paymentID = $(this).attr('paymentID');
        var parameterName = $(this).attr('paraname');

        // alert('parameter='+parameter+'&paymentID='+paymentID); 
        $("#historyResults1").dialog('option', 'title', parameterName);
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'parameter=' + parameter + '&paymentID=' + paymentID,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#historyResults1').html(html);
                $('#historyResults1').dialog('open');
            }
        });
    });


    $('#historyResults1').on("dialogclose", function ( ) {

        $('#labGeneral').fadeIn(1000);
    });


    $('.prevHistory').click(function () {
        var itemID = $('.productID').val();
        var patientID = $(this).attr('name');
        var parameterID = $(this).attr('id');
        var parameterName = $('.parameterName').val();
        $("#historyResults1").dialog('option', 'title', parameterName);
        var ppil = $(this).attr('ppil');
        //alert('action=history&itemID='+itemID+'&patientID='+patientID+'&parameterID='+parameterID+'&ppil='+ppil);        
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'action=history&itemID=' + itemID + '&patientID=' + patientID + '&parameterID=' + parameterID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
                $('#historyResults1').html(html);
                $('#historyResults1').dialog('open');
            }
        });



    });

</script>
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox();
        $('.fancyboxRadimg').fancybox();
        $('#historyResults1').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            minHeight: 400,
            resizable: true,
            draggable: true
        });

        $("#showdata").dialog({autoOpen: false, width: '90%', title: 'PATIENT RADIOLOGY IMAGING', modal: true, position: 'middle'});
        $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 500, title: 'PATIENT RESULT', modal: true, position: 'middle'});
        $("#showdataComm").dialog({autoOpen: false, width: '90%', height: 650, title: 'COMMENT AND DESCRIPTION', modal: true, position: 'center'});

        $('.display-remove').remove();
    });
</script>
<script>
    function CloseImage() {
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId, inVal) {
        if (inVal == 'in') {
            var zoomVal = 10;
        } else {
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue) + zoomVal;
        document.getElementById(imgId).style.width = Sizevalue + '%';
    }


</script>
<script >
    function radiologyviewimage(href, itemName) {
        var datastring = href;
        //alert(datastring);
        $("#showdata").dialog("option", "title", "PATIENT RADIOLOGY IMAGING ( " + itemName + " )");
        $.ajax({
            type: 'GET',
            url: 'requests/radiologyviewimage_doctor.php',
            data: datastring,
            success: function (result) {
                $('#my').html(result);
                $("#showdata").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });


    }
</script>

<script >
    function commentsAndDescription(href, itemName) {
        var datastring = href;
        $("#showdataComm").dialog("option", "title", "COMMENT AND DESCRIPTION ( " + itemName + " )");
        //alert(datastring);
        $.ajax({
            type: 'GET',
            url: 'requests/RadiologyPatientTestsComments_doctor.php',
            data: datastring,
            success: function (result) {
                //alert(result);
                $("#myComm").html(result);
                $("#showdataComm").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });


    }
</script>
<?php
include("./includes/footer.php"); 
?>