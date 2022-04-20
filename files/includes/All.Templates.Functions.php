<?php
include("connection.php");
function ret($conn,$query){
    $sql_query = mysqli_query($conn,$query);
    $result = array();
    while($data = mysqli_fetch_assoc($sql_query)){
        array_push($result,$data);
    }
    return json_encode($result);
}


//GET PATIENT INFORMATIONS
function getPatientInfomations($conn,$Registration_ID){
    return ret($conn,"SELECT Patient_Name, Date_Of_Birth, Gender,pr.Region, pr.District, Guarantor_Name, pr.Occupation, sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id, village FROM tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Sponsor_ID = sp.Sponsor_ID and Registration_ID = '$Registration_ID'");
}



//GET BRACHYTHERAPY DATES
function getBrachtherapyConsultationDates($conn,$Registration_ID){
    return ret($conn, "SELECT DATE(Date_Time) AS Consultation_Dates, consultation_ID, Brachytherapy_ID FROM tbl_brachytherapy_requests WHERE Registration_ID = '$Registration_ID' AND Request_Status IN ('Submitted','Completed') ORDER BY Date_Time DESC");
}



//GET ICU FORMS DATES
function getICU_Dates_General($conn,$Registration_ID,$Form_Number){
    return ret($conn, "SELECT DATE(record_date) AS ICU_DATES, id, consultation_id FROM $Form_Number WHERE registration_id = '$Registration_ID' ORDER BY record_date DESC");
}



//GET RADIOTHERAPY DATES RECORDS
function getRadiotherapyConsultationDates($conn,$Registration_ID){
    return ret($conn, "SELECT DATE(Date_Time) AS Consultation_Dates, consultation_ID, Radiotherapy_ID FROM tbl_radiotherapy_requests WHERE Registration_ID = '$Registration_ID' AND Request_Status IN ('Submitted','Completed') ORDER BY Date_Time DESC");
}


//GET AUDIOLOGY DATES RECORDS
function getAudiologyDates($conn,$Registration_ID){
    return ret($conn, "SELECT date AS Consultation_Dates, payment_item_cache_list_id FROM tbl_audiogram WHERE registration_id = '$Registration_ID' ORDER BY date DESC");
}

?>