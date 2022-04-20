<?php

@session_start();
include './includes/connection.php';

if (isset($_GET['ToBeAdmitted'])) {
    $ToBeAdmitted = $_GET['ToBeAdmitted'];
}

if (isset($_GET['ToBeAdmittedReason'])) {
    $ToBeAdmittedReason = $_GET['ToBeAdmittedReason'];
}

if (isset($_GET['remark'])) {
    $remark = $_GET['remark'];
}

if (isset($_GET['claim_form_number'])) {
    $Claim_Form_Number = $_GET['claim_form_number'];
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}


//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

if (isset($_SESSION['Admission_Supervisor']['Employee_ID'])) {
    $Supervisor_ID = $_SESSION['Admission_Supervisor']['Employee_ID'];
} else {
    $Supervisor_ID = 0;
}
$Sponsor_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'"))['Sponsor_ID'];
$Consultation_Date_And_Time = Date('Y-m-d h:i:s');
if(isset($_GET['AuthorizationNo'])){
    $AuthorizationNo =mysqli_real_escape_string($conn, $_GET['AuthorizationNo']);
}

if(isset($_GET['package_id'])){
    $package_id = $_GET['package_id'];
}
//include("./includes/Get_Patient_Transaction_Number.php");
//include("./includes/Folio_Number_Generator_Emergency.php");
    $cons_query = mysqli_query($conn,"SELECT consulted_patient_display_max_time FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));
    $cons= mysqli_fetch_array($cons_query);
    $consulted_patient_display_max_time=$cons['consulted_patient_display_max_time'];
    $hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];

    $selectlastconsultation = mysqli_query($conn, "SELECT  Check_In_ID, Type_Of_Check_In,Check_In_Status  FROM tbl_check_in c, tbl_clinic tc WHERE Registration_ID='$Registration_ID' AND TIMESTAMPDIFF(HOUR, Check_In_Date_And_Time,CURRENT_TIMESTAMP())<=$consulted_patient_display_max_time ORDER BY Check_In_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    // if(mysqli_num_rows($selectlastconsultation)>0){
        
    // }
    // $sql_check = mysqli_query($conn,"SELECT Check_In_ID, Type_Of_Check_In,Check_In_Status from tbl_check_in     where Registration_ID = '$Registration_ID'     order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

$no = mysqli_num_rows($selectlastconsultation);
if ($no > 0) {
    while ($row = mysqli_fetch_array($selectlastconsultation)) {
        $Check_In_ID = $row['Check_In_ID'];
        $Type_Of_Check_In = $row['Type_Of_Check_In'];
        $Check_In_Status = $row['Check_In_Status'];
    }
} else {
    //create new check in
    $inserts = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID,   Check_In_Date_And_Time, Branch_ID,     Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, AuthorizationNo,  package_id)    VALUES ('$Registration_ID',(select now()),'$Employee_ID',    (select now()),'$Branch_ID',      (select now()),(select now()),'Afresh', '$AuthorizationNo', '$package_id')") or die(mysqli_error($conn));
    if($inserts){
        $select = mysqli_query($conn,"select Check_In_ID, Type_Of_Check_In, Check_In_Status from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($select)) {
            $Check_In_ID = $rows['Check_In_ID'];
            $Type_Of_Check_In = $rows['Type_Of_Check_In'];
            $Check_In_Status = $rows['Check_In_Status'];
        }   
    }else{
        $Check_In_ID = '';
        $Type_Of_Check_In = '';
        $Check_In_Status = '';
    }
}

$check_details_sql = " SELECT * FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'";

$check_details_result = mysqli_query($conn,$check_details_sql) or die(mysqli_error($conn));

//echo ($check_details_sql);exit;

if (mysqli_num_rows($check_details_result) < 1) {

   
     $Folio_Number = 'NULL';
    $select_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments where Registration_ID = '" . $_GET['Registration_ID'] . "' AND Check_In_ID='$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    if (mysqli_num_rows($select_folio) > 0) {
        $row_claim = mysqli_fetch_assoc($select_folio);

        $Folio_Number = $row_claim['Folio_Number'];
    }


    
    $sql_cd = "INSERT into tbl_check_in_details(Registration_ID,Check_In_ID,Claim_Form_Number,Sponsor_ID)  values('$Registration_ID','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID')";


    mysqli_query($conn,$sql_cd) or die(mysqli_error($conn));
    
    if (strtolower($Check_In_Status) == 'pending') {
        mysqli_query($conn,"UPDATE tbl_check_in set Check_In_Status = 'saved',,AuthorizationNo='$AuthorizationNo' Saved_Date_And_Time=NOW(), , package_id='$package_id' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID' and Check_In_ID='$Check_In_ID'");
    }

    $insert_query = "INSERT INTO tbl_consultation(employee_ID, Registration_ID,Consultation_Date_And_Time)
        VALUES ('$Employee_ID', '$Registration_ID','$Consultation_Date_And_Time')";

    //echo $insert_query;
    mysqli_query($conn,$insert_query) or die(mysqli_error($conn));

//GET CONSULATATION ID IF IS SET/AVAILABLE
//$Patient_Payment_ID;
    $consultation_query = "SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation WHERE  Registration_ID = '$Registration_ID'";
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

    //Admit a person

    $add_checkin_details = "UPDATE tbl_check_in_details  	SET ToBe_Admitted = '$ToBeAdmitted', ToBe_Admitted_Reason = '$ToBeAdmittedReason',Employee_ID='$Employee_ID',consultation_ID='$consultation_ID' 	WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'";

    $check_added = mysqli_query($conn,$add_checkin_details) or die(mysqli_error($conn));
    //echo $add_checkin_details;exit;
    if ($check_added) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    $rowDetails = mysqli_fetch_array($check_details_result);

    $consultation_ID = $rowDetails['consultation_ID'];


    if (strtolower($Check_In_Status) == 'pending') {
        mysqli_query($conn,"UPDATE tbl_check_in set Check_In_Status = 'saved', AuthorizationNo='$AuthorizationNo', package_id='$package_id' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID' and Check_In_ID='$Check_In_ID'");
    }

    // is_null($Folio_Status)


    if (is_null($consultation_ID) || !is_numeric($consultation_ID) || $consultation_ID == 0 || empty($consultation_ID)) {
        //create consultation here.
        //Get patient payment item list ID

        $insert_query = "INSERT INTO tbl_consultation(employee_ID, Registration_ID,Consultation_Date_And_Time)
        VALUES ('$Employee_ID', '$Registration_ID','$Consultation_Date_And_Time')";

        mysqli_query($conn,$insert_query) or die(mysqli_error($conn));

        $consultation_query = "SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation WHERE Registration_ID = '$Registration_ID'";
        $consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));

        if (@mysqli_num_rows($consultation_query_result) > 0) {
            $row = mysqli_fetch_assoc($consultation_query_result);
            $consultation_ID = $row['consultation_ID'];
        } else {
            $consultation_ID = 0;
        }
    }


    // update checking details

    $Folio_Number = 'NULL';
    $select_folio = mysqli_query($conn,"SELECT Folio_Number from tbl_patient_payments where Registration_ID = '" . $_GET['Registration_ID'] . "' AND Check_In_ID='$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    if (mysqli_num_rows($select_folio) > 0) {
        $row_claim = mysqli_fetch_assoc($select_folio);

        $Folio_Number = $row_claim['Folio_Number'];
    }

    $add_checkin_details = " 	UPDATE tbl_check_in_details 	SET Claim_Form_Number='$Claim_Form_Number',Folio_Number='$Folio_Number', ToBe_Admitted = '$ToBeAdmitted', ToBe_Admitted_Reason = '$ToBeAdmittedReason',Employee_ID='$Employee_ID',consultation_ID='$consultation_ID' 	WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID'";



    $check_added = mysqli_query($conn,$add_checkin_details) or die(mysqli_error($conn));
    //echo $add_checkin_details;exit;
    if ($check_added) {
        echo 1;
    } else {
        echo 0;
    }
}



