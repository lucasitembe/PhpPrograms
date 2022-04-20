<?php
session_start();
include("./includes/connection.php");
// echo "<link rel='stylesheet' href='fixHeader.css'>";

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $TodayDate = $Today.' 00:00';
    // $age ='';
}
$filter_date = "";
$filter_clinic = "";
$filter_doctor = "";      
$clinic = "";
$doctor = "";
$total_pt = '';
$doctor = $_POST['doctor'];
$clinic = $_POST['clinic'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];

if (!empty($fromDate) && !empty($toDate)) {
    $filter_date .= " AND a.date_time BETWEEN '$fromDate' AND '$toDate'";
} else {
    $filter_date .= " AND DATE(a.date_time) = CURDATE()";
}

if (!empty($clinic) && $clinic != 'All') {
    $filter_date .=" AND a.Clinic = '$clinic'";
} else {
    $clinic = "";
}

if(!empty($doctor) && $doctor != 'All') {
    $filter_date .=" AND a.doctor = '$doctor'";
} else {
    $doctor = "";
}

$htm = '';
// die("SELECT DISTINCT(a.Clinic) AS Clinic_ID, Clinic_Name FROM tbl_appointment a, tbl_clinic cl WHERE cl.Clinic_ID = a.Clinic AND a.Clinic <> 0 $filter_date");
$select_appointment_details3 = mysqli_query($conn, "SELECT DISTINCT(a.Clinic) AS Clinic_ID, Clinic_Name FROM tbl_appointment a, tbl_clinic cl WHERE cl.Clinic_ID = a.Clinic AND a.Clinic <> 0 $filter_date");



    if(mysqli_num_rows($select_appointment_details3)>0){

    $htm .= "<table class='table table-collapse table-striped' style='width: 100%; border-collapse: collapse;border:1px solid black;'>";

        while($row = mysqli_fetch_assoc($select_appointment_details3)){
            $Clinic_Name = $row['Clinic_Name'];
            $Clinic_ID = $row['Clinic_ID'];

            // die("SELECT pr.Patient_Name, patient_No, pr.Date_Of_Birth, pr.Phone_Number, pr.Gender, pr.District, pr.Ward, sp.Guarantor_Name, a.date_time, a.appointment_reason, em.Employee_Name AS Consultant, a.sms_status FROM tbl_appointment a, tbl_employee em, tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Registration_ID = a.patient_No AND a.Clinic = $Clinic_ID AND a.Clinic <> 0 AND sp.Sponsor_ID = pr.Sponsor_ID AND a.appointment_reason NOT IN (',.', '.',',') $filter_date ORDER BY a.date_time");
            $nums = 1;
            $Select_patient = mysqli_query($conn, "SELECT pr.Patient_Name, patient_No, pr.Date_Of_Birth, pr.Phone_Number, a.doctor, pr.Gender, pr.District, pr.Ward, sp.Guarantor_Name, a.date_time, a.appointment_reason, a.sms_status FROM tbl_appointment a, tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Registration_ID = a.patient_No AND a.Clinic = $Clinic_ID AND sp.Sponsor_ID = pr.Sponsor_ID AND a.appointment_reason NOT IN (',.', '.',',') $filter_date GROUP BY a.appointment_id ORDER BY a.date_time") or die(mysqli_error($conn));

            if(mysqli_num_rows($Select_patient)>0){
                $htm .="<tr>
                            <td style='width: 100%;'><h4>CLINIC NAME: <b>".strtoupper($Clinic_Name)."</b></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <div class='box box-primary'>
                                <table class='table table-collapse table-striped' style='width: 100%; border-collapse: collapse;border:1px solid black;'>
                                    <tr style='background: #dedede;'>
                                        <td width=3% style='text-align: center;'><b>SN</b></td>
                                        <td><b>PATIENT NAME</b></td>
                                        <td><b> PATIENT#</b></td>
                                        <td><b>PHONE #</b></td>
                                        <td><b>GENDER</b></td>
                                        <td><b>AGE</b></td>
                                        <td><b>DOCTOR NAME</b></td>
                                        <td><b>APPOINTMENT DATE</b></td>
                                        <td><b>REASON</b></td>
                                        <td><b>SMS STATUS</b></td>
                                    </tr>";

                                    while($list = mysqli_fetch_assoc($Select_patient)){
                                        $Patient_Name = $list['Patient_Name'];
                                        $patient_No = $list['patient_No'];
                                        $Date_Of_Birth = $list['Date_Of_Birth'];
                                        $Gender = $list['Gender'];
                                        $District = $list['District'];
                                        $Ward = $list['Ward'];
                                        $Guarantor_Name = $list['Guarantor_Name'];
                                        $date_time = $list['date_time'];
                                        $doctor = $list['doctor'];
                                        $appointment_reason = $list['appointment_reason'];
                                        $sms_status = $list['sms_status'];
                                        $Phone_Number = $list['Phone_Number'];

                                        $date1 = new DateTime($Today);
                                        $date2 = new DateTime($Date_Of_Birth);
                                        $diff = $date1->diff($date2);
                                        $age = $diff->y . " Years, ";
                                        $age .= $diff->m . " Months, ";
                                        $age .= $diff->d . " Days";

                                        if($doctor != 0){
                                            $Consultant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name AS Consultant FROM tbl_employee WHERE Employee_ID = $doctor"))['Consultant'];
                                        }else{
                                            $Consultant = "<b>Not Specified</b>";
                                        }

                                        if($sms_status != 'Sent'){
                                            $sms_status = 'Not Sent';
                                        }

                                        $htm .="<tr>
                                                    <td>".$nums."</td>
                                                    <td>".$Patient_Name."</td>
                                                    <td>".$patient_No."</td>
                                                    <td>".$Phone_Number."</td>
                                                    <td>".$Gender."</td>
                                                    <td>".$age."</td>
                                                    <td>".ucwords(strtolower($Consultant))."</td>
                                                    <td>".$date_time."</td>
                                                    <td>".$appointment_reason."</td>
                                                    <td>".$sms_status."</td>
                                                </tr>";

                                        $nums++;
                                    }
                                    $numsss = $nums -1;
                                $htm .= "<tr style='background: #dedede;'>
                                            <td colspan='11'>TOTAL APPOINTMENT PATIENTS: <b>".$numsss."</b></td>
                                        </tr>
                                        </table>
                                        </div>";

                                $htm .="</td>
                                </tr>";
                                $total_pt += $numsss;
            }
        }
        $htm .= "<h3>TOTAL APPOINTED PATIENT <b> $total_pt</b></h3>";
        $htm .="<br>";

    }else{
        $htm .= "<center><h3>NO APPOINTMENT FOUND BETWEEN $fromDate AND $toDate</h3></center>";
    }


    echo $htm;

mysqli_close($conn);
?>




