<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include("./includes/connection.php");
session_start();

$filter = '';
$query = '';
$data = array();
$v_column = array();
$h_column = array();
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$lowerage = $_POST['lowerage'];
$higherage = $_POST['higherage'];
$clinics = $_POST['clinics'];
$y_axis = $_POST['y_axis'];
$visittype = $_POST['visittype'];
$wards = $_POST['wards'];
$ipdstatus = $_POST['ipdstatus'];
$agetype=$_POST['agetype'];
$District=$_POST['District'];
$region=$_POST['region'];
$html = '';
$clinicfilter = '';
$visitfilter = '';
$ipdstatusfilter = '';
$Address='';
if($District != ''){
    $Address = " AND  pr.District='$District' ";
}else if($region !=''){
    $Address .=" AND pr.Region='$region' ";
}
if(($lowerage != '') && ($higherage != '' )){
    $lowerage = $lowerage ;
    $higherage = $higherage ;
   // $filter .= " AND datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth)) between '$lowerage' and '$higherage'";
   
    $filter .="  $Address AND TIMESTAMPDIFF($agetype,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$lowerage."' AND '".$higherage."'";
}

if (!empty($y_axis)) {

    if ($y_axis == 'Medication') {
        if($clinics != ''){
            $clinicfilter .= " AND itl.Clinic_ID = '$clinics'";
        }
       
        $query = "SELECT 
                    i.Product_Name,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age,
                    pc.Payment_Date_And_Time
                FROM
                    tbl_item_list_cache AS itl,
                    tbl_items i,
                    tbl_payment_cache pc,
                    tbl_patient_registration pr
                WHERE
                    itl.Item_ID = i.Item_ID AND 
                    itl.Payment_Cache_ID = pc.Payment_Cache_ID AND
                    pc.Registration_ID = pr.Registration_ID AND 
                    itl.Check_In_Type = 'Pharmacy' and 
                    date(pc.Payment_Date_And_Time) between '$start_date' and '$end_date' $filter $clinicfilter
                    order by i.Product_Name";
        
    } else if ($y_axis == 'Clinic') {
        if($clinics != ''){
            $clinicfilter .= " AND ptl.Clinic_ID = '$clinics'";
        }
       
        if($visittype == "new"){
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Afresh' and ck.Referral_Status = 'no'";
        }
        else if($visittype == "return"){
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Continuous' and ck.Referral_Status = 'no'";
        }else if($visittype == "referral"){
            $visitfilter .= " AND ck.Referral_Status = 'yes'";
        }else{
            $visitfilter.="AND ck.Type_Of_Check_In not in ('PATIENT FROM OUTSIDE','') ";
        }
        $query = "SELECT 
                    c.Clinic_Name as Product_Name,
                    c.Clinic_ID,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                FROM
                    tbl_patient_payment_item_list ptl,
                    tbl_patient_payments pp,
                    tbl_patient_registration pr,
                    tbl_clinic c,
                     tbl_check_in ck
                WHERE
                    ck.Check_In_ID = pp.Check_In_ID and 
                    ptl.Patient_Payment_ID = pp.Patient_Payment_ID and 
                    pp.Registration_ID = pr.Registration_ID and 
                    c.Clinic_ID = ptl.Clinic_ID and ptl.Check_In_Type = 'Doctor Room' and
                    DATE(pp.Payment_Date_And_Time) between '$start_date' AND '$end_date' $filter $visitfilter $clinicfilter
                    group by ptl.Patient_Payment_ID order by c.Clinic_Name";
        
    } else if ($y_axis == 'Diagnosis') {
        if($clinics != ''){
            $clinicfilter .= " AND ptl.Clinic_ID = '$clinics'";
        }
        if($visittype == "new"){
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Afresh' and ck.Referral_Status = 'no'";
        }
        else if($visittype == "return"){
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Continuous' and ck.Referral_Status = 'no'";
        }else if($visittype == "referral"){
            $visitfilter .= " AND ck.Referral_Status = 'yes'";
        }

        $query = "SELECT 
                    d.disease_name as Product_Name,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                FROM
                    tbl_disease_consultation dc,
                    tbl_consultation cs,
                    tbl_disease d,
                    tbl_patient_payment_item_list ptl,
                    tbl_patient_payments pp,
                    tbl_patient_registration pr,
                    tbl_clinic c,
                     tbl_check_in ck
                WHERE
                    cs.Patient_Payment_Item_List_ID = ptl.Patient_Payment_Item_List_ID and
                    dc.consultation_ID = cs.consultation_ID and
                    d.disease_ID = dc.disease_ID and
                    ck.Check_In_ID = pp.Check_In_ID and 
                    ptl.Patient_Payment_ID = pp.Patient_Payment_ID and 
                    pp.Registration_ID = pr.Registration_ID and 
                    c.Clinic_ID = ptl.Clinic_ID and ptl.Check_In_Type = 'Doctor Room' and
                    DATE(pp.Payment_Date_And_Time) between '$start_date' AND '$end_date' $filter $visitfilter $clinicfilter
                    group by ptl.Patient_Payment_ID order by d.disease_name";
        
    }else if ($y_axis == 'Ward') {
        if($wards != ''){
            $wardfilter .= " AND ad.Hospital_Ward_ID= '$wards'";
        }
        
        if($ipdstatus == 'admitted'){
            $ipdstatusfilter .= " AND date(ad.Admission_Date_Time) BETWEEN '$start_date' AND '$end_date'";
        }
        if($ipdstatus == 'normal'){
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Normal') ";
        }
        if($ipdstatus == 'Absconded'){
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Absconded') ";
        }
        if($ipdstatus == 'Refferal'){
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Refferal') ";
        }
        if($ipdstatus == 'Death'){
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Death') ";
        }
        
        $query = "SELECT 
                        hw.Hospital_Ward_Name as Product_Name,
                        pr.Gender,
                        (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                    FROM
                        tbl_admission ad,
                        tbl_hospital_ward hw,
                        tbl_patient_registration pr
                    where 
                        pr.Registration_ID = ad.Registration_ID and
                        ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $ipdstatusfilter $filter $wardfilter"
                . "order by hw.Hospital_Ward_Name";
    }
}

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    while ($rows = mysqli_fetch_assoc($result)) {
        $female = 0;
        $male = 0;
        $name = $rows['Product_Name'];
        $Gender = $rows['Gender'];
        if (strtolower($Gender) == "female") {
            $female++;
        } else if (strtolower($Gender) == "male") {
            $male++;
        }
        $total = $female + $male;

        if (array_key_exists((string) $name, $data)) {
            $data[(string) $name][0] += $female;
            $data[(string) $name][1] += $male;
            $data[(string) $name][2] += $total;
        } else {
            $data[(string) $name] = array($female, $male, $total);
        }
        $Grandtotal += $total;
        $Totalfemale += $female;
        $Totalmale +=$male;

    }
    
    $index = 1;
    foreach ($data as $key => $values){
        array_push($v_column, $values[2]);
        array_push($h_column, "Row ".$index);
        $html .= '<tr><td>'.$index.'</td><td>'.$key.'</td><td>'.$values[1].'</td><td>'.$values[0].'</td><td>'.$values[2].'</td></tr>';
        
        $index++;
    }
    $html .= '<tr><th colspan="2">Total</th><th>'.$Totalmale.'</th><th>'.$Totalfemale.'</th><th>'.$Grandtotal.'</th></tr>';
}


echo json_encode(array(
    "x_axis" => $h_column,
    "y_axis" => $v_column,
    "table_data" => $html,
    "query"=>$query
));

