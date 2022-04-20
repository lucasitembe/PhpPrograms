<?php

function ret($conn,$query){
    $sql_query = mysqli_query($conn,$query);
    $result = array();
    while($data = mysqli_fetch_assoc($sql_query)){
        array_push($result,$data);
    }
    return json_encode($result);
}

function getDoctorRequests($conn,$Radiotherapy_ID,$Registration_ID){
    return ret($conn,"SELECT disease_name, disease_code FROM tbl_disease td, tbl_disease_consultation tdc, tbl_radiotherapy_requests rr WHERE rr.Registration_ID = '$Registration_ID' AND rr.Radiotherapy_ID = '$Radiotherapy_ID' AND tdc.consultation_ID = rr.consultation_ID AND tdc.diagnosis_type = 'diagnosis' AND td.disease_ID = tdc.disease_ID");
}


function getPatientInfomations($conn,$Registration_ID){
    return ret($conn,"SELECT Patient_Name, Date_Of_Birth, Gender,pr.Region, pr.District, Guarantor_Name, sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id, village FROM tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Sponsor_ID = sp.Sponsor_ID and Registration_ID = '$Registration_ID'");
}

function getDoctorRequestsBrachy($conn,$Brachytherapy_ID,$Registration_ID){
    return ret($conn,"SELECT disease_name, disease_code FROM tbl_disease td, tbl_disease_consultation tdc, tbl_brachytherapy_requests rr WHERE rr.Registration_ID = '$Registration_ID' AND rr.Brachytherapy_ID = '$Brachytherapy_ID' AND tdc.consultation_ID = rr.consultation_ID AND tdc.diagnosis_type = 'diagnosis' AND td.disease_ID = tdc.disease_ID");
}



?>