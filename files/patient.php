<?php

function get_patient_info($patient_id, $conn)
{
    if (isset($patient_id) && $patient_id != 0) {
        $select_patient_details = "SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
                                    FROM
                                        tbl_patient_registration pr,
                                        tbl_sponsor sp
                                    WHERE
                                        pr.Registration_ID = ? AND sp.Sponsor_ID = pr.Sponsor_ID";
                                        
        $stmt = mysqli_prepare($conn, $select_patient_details);
        
        mysqli_stmt_bind_param($stmt, "i", $patient_id);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $DOB);

        mysqli_stmt_fetch($stmt);

        $age = date_diff(date_create($DOB), date_create('today'))->y;

        return array($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age);
    }
}

function get_patient_info_($patient_id, $conn)
{
    if (isset($patient_id) && $patient_id != 0) {
        $select_patient_details = "SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID, pr.Region, pr.District,Gender,Guarantor_Name,Date_Of_Birth, Tribe, ten_cell_leader_name, rg.Religion_Name
                                    FROM
                                        tbl_patient_registration pr,
                                        tbl_sponsor sp,
                                        tbl_religions rg
                                    WHERE
                                        pr.Registration_ID = ? AND sp.Sponsor_ID = pr.Sponsor_ID AND rg.Religion_ID = pr.Religion_ID";
                                        
        $stmt = mysqli_prepare($conn, $select_patient_details);
        
        mysqli_stmt_bind_param($stmt, "i", $patient_id);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $region, $district, $Gender, $Guarantor_Name, $DOB, $tribe, $ten_cell, $religion);

        mysqli_stmt_fetch($stmt);

        $age = date_diff(date_create($DOB), date_create('today'))->y;

        return array($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $region, $district, $Gender, $Guarantor_Name, $age, $tribe, $ten_cell, $religion);
    }
}

function get_labor_summary($patient_id, $conn)
{
    $data = array();

    $d = array();

    $select_labor_summary = "SELECT date(save_date), admission_id, consultation_id
                                FROM tbl_summary_labor 
                                WHERE Registration_ID = ?";

    $stmt = mysqli_prepare($conn, $select_labor_summary);

    mysqli_stmt_bind_param($stmt, "i", $patient_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $date, $admission_id, $consultation_id);

    while (mysqli_stmt_fetch($stmt)) {
        $d['date'] = $date;

        $d['admission_id'] = $admission_id;

        $d['consultation_id'] = $consultation_id;

        array_push($data, $d);
    }

    return $data;
}
