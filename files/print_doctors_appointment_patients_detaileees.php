<?php
session_start();
include("./includes/connection.php");
$Printed_by_Name = $_SESSION['userinfo']['Employee_Name'];
$Printed_by_Name = ucwords(strtolower($Printed_by_Name));

$filter_date = "";
$doctor = "";

if (isset($_GET['fromDate']) && $_GET['fromDate'] != "" && isset($_GET['toDate']) && $_GET['toDate'] != "") {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $filter_date = " a.date_time BETWEEN '$fromDate' AND '$toDate'";
} else {
    $fromDate = '';
    $toDate = '';
}

if(isset($_GET['doctor']) && $_GET['doctor'] != "" && $_GET['doctor'] != NULL) {
    $doctor = $_GET['doctor'];
} else {
    $doctor = "";
}

$doctor_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctor'"))['Employee_Name'];
$doctor_name = ucwords(strtolower($doctor_name));
$htm =  "<center>
            <table width ='100%' height = '30px'>
                <tr>
                    <td>
                        <img src='./branchBanner/branchBanner.png' width=100%>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>DOCTOR</b>(".$doctor_name.") <b>APPOINTMENT REPORT</b></td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>FROM: </b>".$fromDate." <b>TO: </b>".$toDate."</td>
                </tr>
            </table>
        </center>";


$htm .= "
        
        <div id='load_data'  style='background: #ccc;'></div>
        <center>
            <table width=100% id='myList'>
                <thead>
                    <tr>
                        <td width=5% style='text-align: center;'><b>SN</b></td>
                        <td width=30%><b>PATIENT NAME</b></td>
                        <td width=15%><b>REGISTRATION#</b></td>
                        <td width=15%><b>PATIENT PHONE#</b></td>
                        <td width=10%><b>GENDER</b></td>
                        <td width=10%><b>AGE</b></td>
                        <td width=25%><b>APPOINTMENT DATE</b></td>
                    </tr>
                </thead>";

                
                $select_appointment_details2 = mysqli_query($conn, "SELECT a.Clinic,a.Set_BY,a.date_time,a.patient_No,e.Employee_Name FROM tbl_appointment a, tbl_employee e
                                                                    WHERE $filter_date AND e.Employee_ID = a.Set_BY AND a.doctor = '$doctor'     
                                                                    ORDER BY a.date_time ASC");
                $sn = 1;
                While($rows = mysqli_fetch_assoc($select_appointment_details2)) {

                    $Doctor_Name = $rows['Employee_Name'];  
                    $Registration_ID = $rows['patient_No'];  
                    $Appointment_date_time = date("Y-m-d", strtotime($rows['date_time']));

                    // $select_appointment_details4 = mysqli_query($conn, "");

                    $patient_details = mysqli_query($conn, "SELECT Patient_Name,Gender,Phone_Number,Date_Of_Birth FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'");
                    
                    while($data = mysqli_fetch_array($patient_details)) {
                        $Patient_Name = $data['Patient_Name'];
                        $Gender = $data['Gender'];
                        $Phone_Number = $data['Phone_Number'];

                        //AGE FUNCTION
                        // $age = floor((strtotime(date('Y-m-d')) - strtotime($data['Date_Of_Birth'])) / 31556926) . " Years";
                        // if($age == 0){

                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($data['Date_Of_Birth']);
                        $diff = $date1->diff($date2);
                        $age = $diff->y;
                        // $age .= $diff->m . " Months, ";
                        // $age .= $diff->d . " Days";
                    }
                    $num = "";
                    if($Phone_Number == "") {

                        $num .= "<td width=15% style='color: red;'><b>No Phone Number</b></td> ";
                    } else {
                        $num .= "
                                <td width=15%>".$Phone_Number."</td>";
                    }
                    $Patient_Name = ucwords(strtolower($Patient_Name));
                    
                    $htm .= "
                        <tr>
                            <td width=5% style='text-align: center;'>".$sn++."</td>
                            <td width=25%>".$Patient_Name."</td>
                            <td width=5%>".$Registration_ID."</td>
                            ".$num."
                            <td width=5%>".$Gender."</td>
                            <td width=5%>".$age."</td>
                            <td width=15%>".$Appointment_date_time."</td>
                        </tr>
                    ";
                }

                $htm .= "
                </table>
            </center>
        ";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.$Printed_by_Name.'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit; 
mysqli_close($conn);
?>
