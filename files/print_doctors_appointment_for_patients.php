<?php
session_start();
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";
$Printed_by_Name = $_SESSION['userinfo']['Employee_Name'];
$Printed_by_Name = ucwords(strtolower($Printed_by_Name));

$filter_date = "";
$filter_clinic = "";
$filter_doctor = "";      
$clinic = "";
$doctor = "";

if (isset($_GET['fromDate']) && $_GET['fromDate'] != "" && isset($_GET['toDate']) && $_GET['toDate'] != "") {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $filter_date = " a.date_time BETWEEN '$fromDate' AND '$toDate' ";
} else {
    $fromDate = '';
    $toDate = '';
}

if (isset($_GET['clinic']) && $_GET['clinic'] != "" && $_GET['clinic'] != NULL) {
    $clinic = $_GET['clinic'];
} else {
    $clinic = "";
}

if(isset($_GET['doctor']) && $_GET['doctor'] != "" && $_GET['doctor'] != NULL) {
    $doctor = $_GET['doctor'];
} else {
    $doctor = "";
}


if($clinic == "All") {
    $htm1 =  "
            <center>
                <table width ='100%' height = '30px'>
                    <tr>
                        <td>
                            <img src='./branchBanner/branchBanner.png' width=100%>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align: center;'><b>CLINICS APPOINTMENT REPORT</b></td>
                    </tr>
                    <tr>
                        <td style='text-align: center;'><b>FROM: </b>".$fromDate." <b>TO: </b>".$toDate."</td>
                    </tr>
                </table>
            </center>";

    $htm1 .= "
        
        <div id='load_data'></div>
        <center>
            <table width=99% style='background: white;' id='myList2' class='fixTableHead'>
                <thead>
                    <tr style='background: #ccc;'>
                        <td width=5% style='text-align: center;'><b>SN</b></td>
                        <td width=20%><b>PATIENT NAME</b></td>
                        <td width=10%><b>REGISTRATION#</b></td>
                        <td width=15%><b>PATIENT PHONE#</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td width=5%><b>AGE</b></td>
                        <td width=15%><b>DOCTOR NAME</b></td>
                        <td width=15%><b>APPOINTMENT DATE</b></td>
                    </tr>
                </thead>";
} else {
    $htm1 =  "<center>
            <table width ='100%' height = '30px'>
                <tr>
                    <td>
                        <img src='./branchBanner/branchBanner.png' width=100%>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>CLINICS APPOINTMENT REPORT</b></td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>FROM: </b>".$fromDate." <b>TO: </b>".$toDate."</td>
                </tr>
            </table>
        </center>";

    $htm1 .= "
        
        <div id='load_data'></div>
        <center>
            <table width=99% style='background: white;' id='myList2' class='fixTableHead'>
                <thead>
                    <tr style='background: #ccc;'>
                        <td width=5% style='text-align: center;'><b>SN</b></td>
                        <td width=20%><b>PATIENT NAME</b></td>
                        <td width=10%><b>REGISTRATION#</b></td>
                        <td width=15%><b>PATIENT PHONE#</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td width=5%><b>AGE</b></td>
                        <td width=15%><b>DOCTOR NAME</b></td>
                        <td width=15%><b>APPOINTMENT DATE</b></td>
                    </tr>
                </thead>
                ";
}

$htm2 =  "<center>
            <table width ='100%' height = '30px'>
                <tr>
                    <td>
                        <img src='./branchBanner/branchBanner.png' width=100%>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>DOCTORS APPOINTMENT SUMMARY REPORT</b></td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>FROM: </b>".$fromDate." <b>TO: </b>".$toDate."</td>
                </tr>
            </table>
        </center>";

$htm2 .= "
        <div id='load_data'></div>
        <center>        
            <table width=100% id='myList'>
                <thead>
                    <tr style='background: #ccc;'>
                        <td width=10% style='text-align: center;'><b>SN</b></td>
                        <td width=35%><b>DOCTOR NAME</b></td>
                        <td width=25%><b>DOCTOR PHONE NUMBER</b></td>
                        <td width=30% style='text-align: center;'><b>NUMBER OF APPOINTMENTS</b></td>
                    </tr>
                </thead>";




$select_appointment_details = "";
if($doctor == "All") {
    $select_appointment_details = mysqli_query($conn, "SELECT DISTINCT(a.doctor) FROM tbl_appointment a WHERE $filter_date AND a.Clinic = 0  GROUP BY doctor");
} else if($doctor != "All") {
    $select_appointment_details = mysqli_query($conn, "SELECT DISTINCT(a.doctor) FROM tbl_appointment a WHERE $filter_date AND a.Clinic = 0 AND a.doctor = '$doctor'  GROUP BY doctor");
}

$select_appointment_details3 = "";
if($clinic == "All") {
    $select_appointment_details3 = mysqli_query($conn, "SELECT DISTINCT(a.Clinic) FROM tbl_appointment a WHERE $filter_date AND a.doctor = 0 GROUP BY a.Clinic");
} else if($clinic != "All") {
    $select_appointment_details3 = mysqli_query($conn, "SELECT DISTINCT(a.Clinic) FROM tbl_appointment a WHERE $filter_date AND a.doctor = 0 AND a.Clinic = '$clinic' GROUP BY a.Clinic");
}

$no_of_doctor_appointment = 0;
$Employee_Name = "";
$Phone_Number = "";

if($clinic != "" && $doctor == "") {
    $sn = 1;
    while($row_clinic = mysqli_fetch_array($select_appointment_details3)) {
        $clinic = $row_clinic['Clinic'];
        $select_appointment_details5 = mysqli_query($conn, "SELECT a.Clinic,a.Set_BY,a.date_time,a.patient_No,e.Employee_Name FROM tbl_appointment a, tbl_employee e
                                                            WHERE $filter_date AND e.Employee_ID = a.Set_BY AND a.doctor = 0 AND a.Clinic = '$clinic'    
                                                            ORDER BY a.date_time ASC");

        $my_query = "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$clinic'";
        $clinic_name = mysqli_fetch_assoc(mysqli_query($conn, $my_query))['Clinic_Name'];  
        $clinicName = ucwords(strtolower($clinic_name));

        $htm1 .= "
                <tr>
                    <td colspan='8' style='text-align: left;font-size: 18px;padding-left: 5px;color: #0072AE;'><b>".$clinicName."</b></td>
                </tr>
            ";
            

            if($clinic != 0) {
                $select_appointment_details2 = mysqli_query($conn, "SELECT a.Clinic,a.Set_BY,a.date_time,a.patient_No,e.Employee_Name FROM tbl_appointment a, tbl_employee e
                                                                    WHERE $filter_date $filter_clinic AND e.Employee_ID = a.Set_BY  AND a.Clinic = '$clinic' AND a.doctor = 0     
                                                                    ORDER BY a.date_time ASC");

                While($rows = mysqli_fetch_assoc($select_appointment_details2)) {

                    $Doctor_Name = $rows['Employee_Name'];  
                    $doctorName = ucwords(strtolower($Doctor_Name));
                    $Registration_ID = $rows['patient_No'];  
                    $Appointment_date_time = date("Y-m-d", strtotime($rows['date_time']));

                    // $select_appointment_details4 = mysqli_query($conn, "");

                    $patient_details = mysqli_query($conn, "SELECT Patient_Name,Gender,Phone_Number,Date_Of_Birth FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'");
                    
                    while($data = mysqli_fetch_array($patient_details)) {
                        $Patient_Name = $data['Patient_Name'];
                        $Gender = $data['Gender'];
                        $Phone_Number = $data['Phone_Number'];
                        $num = "";
                        if($Phone_Number == "") {

                            $num .= "<td width=15% style='color: red;'><b>No Phone Number</b></td> ";
                        } else {
                            $num .= "
                                    <td width=15%>".$Phone_Number."</td>";
                        }

                        //AGE FUNCTION
                        $age = floor((strtotime(date('Y-m-d')) - strtotime($toadmit['Date_Of_Birth'])) / 31556926) . " Years";
                        // if($age == 0){

                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($data['Date_Of_Birth']);
                        $diff = $date1->diff($date2);
                        $age = $diff->y;
                        // $age .= $diff->m . " Months, ";
                        // $age .= $diff->d . " Days";
                    }

                    $Patient = ucwords(strtolower($Patient_Name));
                    
                    
                    $htm1 .= "
                        <tr>
                            <td width=5% style='text-align: center;'>".$sn++."</td>
                            <td width=25%>".$Patient."</td>
                            <td width=5%>".$Registration_ID."</td>
                            ".$num."
                            <td width=5%>".$Gender."</td>
                            <td width=5%>".$age."</td>
                            <td width=25%>".$doctorName."</td>
                            <td width=15%>".$Appointment_date_time."</td>
                        </tr>
                    ";
                }
            }
        

    }
} else if($doctor != "" && $clinic == "") {
    $sn = 1;
    while($row_doctor = mysqli_fetch_array($select_appointment_details)) {
        $doctor = $row_doctor['doctor'];
        
            
            if($doctor != 0) {
                $select_appointment_details2 = mysqli_query($conn, "SELECT a.doctor,e.Employee_Name,e.Phone_Number FROM tbl_appointment a, tbl_employee e WHERE $filter_date AND a.doctor = '$doctor' AND a.doctor=e.Employee_ID");

             
                $Employee_Name = mysqli_fetch_assoc($select_appointment_details2)['Employee_Name'];  
                $Phone_Number = mysqli_fetch_assoc($select_appointment_details2)['Phone_Number']; 
                
                $no_of_doctor_appointment = mysqli_num_rows($select_appointment_details2);
                
                $htm2 .= "
                    <tr>
                        <td style='text-align: center;'>".$sn++."</td>
                        <td>".$Employee_Name."</td>";
                    if($Phone_Number == "") {
                        $Phone_Number = "No Phone Number Available";
                        $htm2 .= "<td style='color: red;'>".$Phone_Number."</td>";
                    } else {
                        $htm2 .= "<td>".$Phone_Number."</td>";
                    }       
                $htm2 .= "
                        <td style='text-align: center;'>".$no_of_doctor_appointment."</td>
                    </tr>";
            }
        

    }
}

if($clinic != "" && $doctor == "") {
    $htm1 .= "
                </table>
            </center>
        ";
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Printed_by_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm1,2);

    $mpdf->Output('mpdf.pdf','I');
} else if($doctor != "" && $clinic == "") {
    $htm2 .= "
                </table>
            </center>
        ";
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Printed_by_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm2,2);

    $mpdf->Output('mpdf.pdf','I');
}

exit; 



mysqli_close($conn);
?>




