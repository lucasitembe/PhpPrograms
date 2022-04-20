<?php
@session_start();
include("./includes/connection.php"); 

$htm = "";

if(isset($_POST['section']) && $_POST['section'] != "") {
    $section = $_POST['section'];

    if($section == "clinic") {
        $htm .= " <option value=''>~~ Select Clinic ~~</option>
                  <option value='All'>All Clinic</option>";
        $query=mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic");
        while ($result=  mysqli_fetch_assoc($query)){
            $Clinic_Name = ucwords(strtolower($result['Clinic_Name']));
            $htm .= '<option value='.$result['Clinic_ID'].'">'.$Clinic_Name.'</option>';
        }
    } else if($section == "doctor") {
        $htm .= " <option value=''>~~ Select Doctor ~~</option>
                  <option value='All'>All Doctors</option>";
        $query=mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status='active'");
        while ($result=  mysqli_fetch_assoc($query)){
            $Employee_Name = ucwords(strtolower($result['Employee_Name']));
            $htm .= '<option value="'.$result['Employee_ID'].'">'.$Employee_Name.'</option>';
        }
    }
    echo $htm;
}

mysqli_close($conn);
?>