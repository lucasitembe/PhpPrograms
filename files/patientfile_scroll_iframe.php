<?php
    include  'Patient_Record_Review_out_frame.php';
    include 'Patient_Record_Review_in_frame.php';
$select_patients = "SELECT ppl.Patient_Payment_Item_List_ID, ppl.Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c, tbl_patient_payment_item_list ppl WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
			    c.Registration_ID = '$Registration_ID' LIMIT 100";
$result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));

$num = mysqli_num_rows($result);

if ($num == 0) {
    $select_patients = "SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID, c.consultation_ID, c.Consultation_Date_And_Time, c.Registration_ID
			    FROM tbl_consultation c, tbl_check_in_details cd WHERE
			    c.consultation_ID = cd.consultation_ID and
			    c.Registration_ID = '$Registration_ID' LIMIT 100";
}

//die($select_patients);

$result = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));

$num = mysqli_num_rows($result);

$resultPat = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$pat = mysqli_fetch_array($resultPat);
$patientName = $pat['Patient_Name'];
$showItemStatus = true;

$display = "";

if (isset($_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file']) && $_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file'] == '1') {
    $showItemStatus = false;
    $display = "style='display:none' class='display-remove'";
}

$consultation_ID = 0;
$i = 1;
while ($row = mysqli_fetch_array($result)) {
    $consultation_ID =$_GET['consultation_ID']= $row['consultation_ID'];
    $Consultation_Date_And_Time = date('d, M y', strtotime($row['Consultation_Date_And_Time']));
    
    $rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
    
    $doctorsNm = mysqli_fetch_array($rsDoc) ;
   $doctorsNames = $doctorsNm['Employee_Name'];

    $hasOutpatientDetails = false;
    $hasInpatientDetails = false;


    $check_was_inpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE consultation_ID = '" . $consultation_ID . "' AND Registration_ID='" . $Registration_ID . "' AND Admit_Status='admitted'") or die(mysqli_error($conn));

    if (mysqli_num_rows($check_was_inpatient) > 0) {
        $hasInpatientDetails = true;
    }

    $check_was_outpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE consultation_ID = '" . $consultation_ID . "' AND Patient_Payment_Item_List_ID IS NULL") or die(mysqli_error($conn));

    if (mysqli_num_rows($check_was_outpatient) == 0) {
        $hasOutpatientDetails = true;
    }

    echo "<br/><div class='headerPage'>$Consultation_Date_And_Time,  &nbsp;&nbsp;<span>DR.&nbsp;&nbsp;".$doctorsNames."</span></div><br/>";

    echo displayOutpatientInfos($hasOutpatientDetails,$hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

    echo '<br/><br/>';

    echo displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

    echo '<br/><br/>';
}