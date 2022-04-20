<?php
include("./includes/connection.php");

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

if(isset($_GET['doctor_id']) && $_GET['doctor_id'] != "" && $_GET['doctor_id'] != NULL) {
    $doctor = $_GET['doctor_id'];
} else {
    $doctor = "";
}

$doctor_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctor'"))['Employee_Name'];
$doctor_name = ucwords(strtolower($doctor_name));

$htm = "
                <center>
                    <tr>
                        <td><b>Doctor Name: </b></td><td>".$doctor_name."</td><td>(<b>From: 
                        </b></td><td>".$fromDate."</td><td><b> To: </b></td><td>".$toDate.")
                        </td><td></td><td>";
                        
                         ?>

                        <input align='right' style='text-align: right; float: right;' type='button' id='view_doctor_appointments2' onclick='print_details("<?php echo $fromDate; ?>","<?php echo $toDate; ?>",<?php echo $doctor; ?>)' class="art-button-green" value="Preview"></td>
                    </tr>
                </center>   


<?php

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
                        $num = "";
                        if($Phone_Number == "") {

                            $num .= "<td width=15% style='color: red;'><b>No Phone Number</b></td> ";
                        } else {
                            $num .= "
                                    <td width=15%>".$Phone_Number."</td>";
                        }

                        //AGE FUNCTION
                        $age = floor((strtotime(date('Y-m-d')) - strtotime($data['Date_Of_Birth'])) / 31556926) . " Years";
                        // if($age == 0){

                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($data['Date_Of_Birth']);
                        $diff = $date1->diff($date2);
                        $age = $diff->y;
                        // $age .= $diff->m . " Months, ";
                        // $age .= $diff->d . " Days";
                    }
                    $Patient = ucwords(strtolower($Patient_Name));
                    
                    
                    $htm .= "
                        <tr>
                            <td width=5% style='text-align: center;'>".$sn++."</td>
                            <td width=25%>".$Patient."</td>
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



echo $htm;



mysqli_close($conn);
?>