<?php

include("./includes/connection.php");
@session_start();

 $Employee_Name_Print = $_SESSION['userinfo']['Employee_Name'];

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

$data = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";

$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = trim($_GET['Registration_ID']);
}
$Admission_ID = $_GET['Admission_ID'];
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information

    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Sponsor_ID,pr.Registration_ID,pr.Date_Of_Birth, pr.Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward, pr.Member_Number,pr.Member_Card_Expire_Date, pr.Phone_Number, pr.Religion_ID, pr.Tribe,sp.Guarantor_Name,sp.Claim_Number_Status, sp.Postal_Address,sp.Benefit_Limit from tbl_patient_registration pr, tbl_sponsor sp where pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
   // $no = mysqli_num_rows($select_Patient);
    if (mysqli_num_rows($select_Patient)> 0) {
        while ($row = mysqli_fetch_assoc($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
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
            $Religion_Name = $row['Religion_Name'];
            $Tribe = $row['Tribe'];
            // $data .= $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = 'NO DATA';
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
        $Tribe = '';
        $Religion_Name = '';
        $age = 0;
    }


$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
//die($consultation_ID);
$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ' ';
}


$showItemStatus = true;
 $display = "";

if (isset($_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file']) && $_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file'] == '1') {
    $showItemStatus = false;
    $display = "style='display:none' class='display-remove'";
}

$discharger = mysqli_fetch_Assoc(mysqli_query($conn,"SELECT wr.Employee_ID, em.Employee_Name FROM tbl_ward_round wr, tbl_employee em WHERE Registration_ID = '$Registration_ID'AND wr.Employee_ID=em.Employee_ID ORDER BY wr.Round_ID DESC LIMIT 1"))['Employee_Name'];

if($Tribe > 0){
    $Tribe = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tribe_name FROM tbl_tribe WHERE tribe_id = '$Tribe'"))['tribe_name'];
}

$data .= '<fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">PATIENT DISCHARGE SUMMARY</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="">' . $Patient_Name . '</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">' . $Country . '</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">' . $Region . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>' . $Registration_ID . '</td><td style="text-align:right"><b>Phone #:</b></td><td style="">' . $Phone_Number . '</td>
                <td style="text-align:right"><b>Address:</b></td><td style="">' . $District . ',&nbsp;'. $Ward .'</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">' . date("j F, Y", strtotime($Date_Of_Birth)) . ' </td>
                <td style="width:10%;text-align:right" ><b>Tribe:</b></td><td colspan=""> ' . $Tribe . '</td>
                <td style="text-align:right"><b>Gender:</b></td><td style="">' . $Gender . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> ' . $Guarantor_Name . $sponsoDetails . '</td>
                <td style="width:10%;text-align:right" ><b>Discharge Doctor:</b></td><td colspan="3">' . ucfirst($discharger) . '</td>
            </tr>
        </table>
    </div>';

$data .= displayInpantientInfo($hasInpatientDetails, $rsDoc, $Admission_ID, $Registration_ID, $consultation_ID, $display);

$data .= '
</fieldset>';


function displayInpantientInfo($hasInpatientDetails, $rsDoc, $Admission_ID, $Registration_ID, $consultation_ID, $display) {
    global $conn;
    $patientInfo_qr = mysqli_query($conn,"SELECT Gender, TIMESTAMPDIFF(YEAR, Date_Of_Birth,CURDATE()) AS age FROM  tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

    $patInfo = mysqli_fetch_assoc($patientInfo_qr);

    $patgender = $patInfo['Gender'];
    $patage = (int) $patInfo['age'];
    // if ($hasInpatientDetails) {
    //get patient info Dicharged
    $result = mysqli_query($conn,"SELECT ad.Bed_Name,Admission_Date_Time,Admission_Employee_ID,Employee_Name,Admission_Status,Hospital_Ward_Name,ad.Bed_Name,Cash_Clearer_ID,Credit_Clearer_ID,Kin_Name,Kin_Address,ToBe_Admitted_Reason,TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_Time) AS no_of_days FROM tbl_admission ad
                    INNER JOIN tbl_check_in_details cd ON cd.Admission_ID = ad.Admision_ID 
                    JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID=ad.Hospital_Ward_ID 
                    
                    JOIN tbl_employee e ON e.Employee_ID=ad.Admission_Employee_ID
                    WHERE ad.Registration_ID='$Registration_ID' AND cd.Admit_Status='admitted' AND ad.Admision_ID = '$Admission_ID' ORDER BY ad.Admision_ID") or die(mysqli_error($conn));


    $info = mysqli_fetch_array($result);
    $Admission_Date_Time = $info['Admission_Date_Time'];
    $Admission_Employee_ID = $info['Admission_Employee_ID'];
    $Admission_Status = $info['Admission_Status'];
    $Hospital_Ward_Name = $info['Hospital_Ward_Name'];
    $Bed_Name = $info['Bed_Name'];
    $Kin_Name = $info['Kin_Name'];
    $Kin_Address = $info['Kin_Address'];
    $Admit_Employee_Name = $info['Employee_Name'];
    $no_of_days = $info['no_of_days'];
    $continuation_sheet = $info['ToBe_Admitted_Reason'];
    $doctor_discharge = $info['doctor_discharge'];
    $doctor_discharge_time = $info['doctor_discharge_time'];
	$Credit_Clearer_ID = $info['Credit_Clearer_ID'];
	$Cash_Clearer_ID = $info['Cash_Clearer_ID'];
	
	if(!empty($Cash_Clearer_ID)){
		$Clearer = $Cash_Clearer_ID;
	}else{
		$Clearer = $Credit_Clearer_ID;
	}

    if ($Admission_Status == 'pending') {
        $Admission_Status = 'Discharge State';
    }

    $discharge_details = '';
    $doctor_discharge=0;
  
    if (trim($Admission_Status) == 'Discharged') {
        // die( 'basdfasdf' );
        $result_disc = mysqli_query($conn,"SELECT Discharge_Date_Time,Employee_Name,Discharge_Reason FROM tbl_admission ad
                    INNER JOIN tbl_discharge_reason dr ON dr.Discharge_Reason_ID = ad.Discharge_Reason_ID
                    JOIN tbl_employee e ON e.Employee_ID=ad.Discharge_Employee_ID
                    JOIN tbl_check_in_details cd ON cd.Admission_ID = ad.Admision_ID 
                    WHERE ad.Registration_ID='" . $Registration_ID . "' AND cd.Admit_Status='admitted' ORDER BY ad.Admision_ID DESC LIMIT 1
                    ") or die(mysqli_error($conn));

        $info_disc = mysqli_fetch_array($result_disc);
        $Discharge_Date_Time = $info_disc['Discharge_Date_Time'];
        $Discharge_Employee_Name = $info_disc['Employee_Name'];
        $Discharge_Reason = $info_disc['Discharge_Reason'];
        $doctor_discharge = $info_disc['doctor_discharge'];
        $doctor_discharge_time = $info_disc['doctor_discharge_time'];
        
        $discharge_details = '<tr>
                <td style="width:10%;text-align:right "><b># Days</b></td>
                <td>' . $no_of_days . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Reason</b></td>
                <td>' . $Discharge_Reason . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Date</b></td>
                <td>' . $Discharge_Date_Time . '</td>
            </tr>';
    }

    $data .= '<div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center " id="inpatient">
        <b align="center">INPATIENT DETAILS</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" width="100%" border="0" style="border:none !important">
            <tr>
                <td style="width:15%;text-align:right "><b>Admission Date</b></td>
                <td>' . $Admission_Date_Time . '</td>
                <td style="width:10%;text-align:right "><b>Admitted By</b></td>
                <td>' . $Admit_Employee_Name . '</td>
                <td style="width:10%;text-align:right "><b>Patient Status</b></td>
                <td>' . $Admission_Status . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Ward/Bed</b></td>
                <td>' . $Hospital_Ward_Name . ', <br/>' . $Bed_Name . '</td>
                <td style="width:10%;text-align:right "><b>Kin&#39;s Name</b></td>
                <td>' . $Kin_Name . '</td>
                <td style="width:10%;text-align:right "><b>Kin&#39;s Address:</b></td>
                <td>' . $Kin_Address . '</td>
            </tr>
            ' . $discharge_details . '
             
        </table>
    </div>';
    $sn = 1;



    $subqr = "SELECT ilc.Employee_Created,ilc.Status,Product_Name,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.item_ID = ilc.item_ID
           	   
           JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
	   JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
	   WHERE   ilc.Status != 'notsaved' AND  Check_In_Type='Pharmacy' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND DATE(Transaction_Date_And_Time)= DATE('$doctor_discharge_time')";


    $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));

    $sn = 1;

    if (mysqli_num_rows($result) > 0) {
        $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>PHARMACY</b>
            </div>";
        $data .= '<table width="100%" style="margin-left:2px">';
        $data .= '<tr style="font-weight:bold;" id="thead">';
        $data .= '<td style="width:3%;">SN</td>';
        $data .= '<td style="text-align:left">Medication Name</td>';
        $data .= '</tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            $Pharmacy = $row['Product_Name'];
            $data .= '<tr>';
            $data .= '<td style="width:3%;">' . $sn . '</td>';
            $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
            $data .= '</tr>';

            $sn++;
        }

        $data .= '</table></div>';
    }
    $added_disease = '';

    $diagnosis = mysqli_query($conn,"SELECT disease_name, disease_code FROM tbl_disease d, tbl_ward_round_disease wrd, tbl_ward_round wr WHERE d.disease_ID = wrd.disease_ID AND wrd.round_ID = wr.round_ID AND wr.Registration_ID = '$Registration_ID' AND wrd.diagnosis_type = 'diagnosis' order by wrd.round_ID DESC");
    $data .= "
        <div width='100%' id='diagonsis' style='margin:2px;border:1px solid #000'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>DISCHARGED DIAGNOSIS</b>
        </div>";
    if(mysqli_num_rows($diagnosis) > 0) {
      while ($rw = mysqli_fetch_assoc($diagnosis)){
         $ugonjwa = $rw['disease_name']."(".$rw['disease_code']."), ";        
         $added_disease .= "$ugonjwa";
      }
       
    }
    $data .="<p style='display:inline;font-size:12px;'> $added_disease</p>";

    $rm_qr = "SELECT  remarks FROM tbl_ward_round wd WHERE  wd.Registration_ID ='$Registration_ID' order by Ward_Round_Date_And_Time desc limit 1 
		    ";
    $result_rm = mysqli_query($conn,$rm_qr) or die(mysqli_error($conn));
    if (mysqli_num_rows($result_rm) > 0) {
        $row_rm = mysqli_fetch_assoc($result_rm);
        $data .= "
        <div width='100%' id='diagonsis'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>REMARKS AND FOLLOW UP</b>
        </div>";
        $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td style="width: 10%;text-align:right"><b>Remarks/Doctors Plan</b></td><td>' . $row_rm['remarks'] . '</td></td>
            </tr>';

        $data .= '</table></div>';
    }
    $Round_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Round_ID FROM tbl_ward_round WHERE Registration_ID = '$Registration_ID' ORDER BY Round_ID DESC LIMIT 1"))['Round_ID'];

        $Payment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Round_ID = '$Round_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
       $subqr = mysqli_query($conn,"SELECT pc.Payment_Cache_ID,ilc.Doctor_Comment,i.Product_Name FROM tbl_item_list_cache ilc, tbl_items i, tbl_payment_cache pc WHERE  ilc.Item_ID = i.Item_ID  AND  Check_In_Type = 'Pharmacy' AND pc.Registration_ID = '$Registration_ID' AND ilc.Payment_Cache_ID='$Payment_Cache_ID' AND pc.Payment_Cache_ID=ilc.Payment_Cache_ID") or die(mysqli_error($conn));

    $sn = 1;

    if (mysqli_num_rows($subqr) > 0) {
        $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>DISCHARGED MEDICATIONS</b>
            </div>";
        $data .= '<table width="100%" style="margin-left:2px">';
        $data .= '<tr style="font-weight:bold; background: #dedede;" id="thead">';
        $data .= '<th style="width:3%;">SN</th>';
        $data .= '<th style="text-align:left">Medication Name</th>';
        $data .= '<th style="text-align: left">Dosage</th>';
        $data .= '</tr>';

        while ($row = mysqli_fetch_assoc($subqr)) {
            $Pharmacy = $row['Product_Name'];
            $maelezo = $row['Doctor_Comment'];

            $data .= '<tr>';
            $data .= '<td style="width:3%;">' . $sn . '</td>';
            $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
            $data .= '<td style="text-align:left">' . $maelezo . '</td>';
            $data .= '</tr>';

            $sn++;
        }

        $data .= '</table></div>';
    }

    
    ///////////////////////////


    $discharger_query = mysqli_query($conn,"SELECT wr.Employee_ID, em.employee_signature, em.Employee_Name FROM tbl_ward_round wr, tbl_employee em WHERE Registration_ID = '$Registration_ID'AND wr.Employee_ID=em.Employee_ID ORDER BY wr.Round_ID DESC LIMIT 1");
    while($clear = mysqli_fetch_assoc($discharger_query)){
        $discharger = $clear['Employee_Name'];
        $Roctor_signature = $clear['employee_signature'];
        if(!empty($Roctor_signature)){
            $witnessignature="Signature: <img src='../esign/employee_signatures/$Roctor_signature' style='height:25px'>";
        }else{
            $witnessignature ="Signature:______________________ ";
        }
    }
        $data .="<table width='100%' style='border:1px solid white'>
                    <tr>
                        <td style='border:1px solid white; text-align:left; width:100%'>
                            <table width='100%' style='border:1px solid white'>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white; text-align:left;'><b>DISCHARGED DR. </b></td>
                                </tr>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white; text-align:left;'>".$discharger."</td>
                                </tr>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white'>".$witnessignature."</td>
                                </tr>                                
                            </table>
                        </td>
                        
                    </tr>
                </table>
                ";
				
		$Bill_Crearer_query = mysqli_query($conn, "SELECT employee_signature, Employee_Name FROM tbl_employee WHERE Employee_ID = '$Clearer'");
        while($bill = mysqli_fetch_assoc($Bill_Crearer_query)){
            $Bill_Crearer = $bill['Employee_Name'];
            $employee_signature = $bill['employee_signature'];
            if(!empty($employee_signature)){
                $employee_signature_p="Signature:<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
            }else{
                $employee_signature_p ="Signature:______________________ ";
            }
        }
		$data .="<table width='100%' style='border:1px solid white'>
                    <tr>
                        <td style='border:1px solid white; text-align:left; width:100%'>
                            <table width='100%' style='border:1px solid white'>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white; text-align:left;'><b>BILL CLEARED BY </b></td>
                                </tr>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white; text-align:left;'>".ucfirst($Bill_Crearer)."</td>
                                </tr>
                                <tr style='border:1px solid white'>
                                    <td style='border:1px solid white'>".$employee_signature_p."</td>
                                </tr>                                
                            </table>
                        </td>
                        
                    </tr>
                </table>
                ";

    //////////////////
    
    
    return $data;
}




include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name_Print)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');

$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();
