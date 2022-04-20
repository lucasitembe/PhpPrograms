<?php
include("./includes/connection.php");
$filter="";
$search_assigned_doctor_patient=$_POST['search_assigned_doctor_patient'];
if($search_assigned_doctor_patient!=""){
    $filter=" AND (Employee_Name LIKE '%$search_assigned_doctor_patient%' OR Patient_Name LIKE '%$search_assigned_doctor_patient%')";
}
$sql_select_all_patient_assigned_doctor_result=mysqli_query($conn,"SELECT Employee_Name,Patient_Name,Hospital_Ward_Name FROM
            tbl_admission ad,
            tbl_employee emp,
            tbl_patient_registration pr,
            tbl_hospital_ward hw
            WHERE 
            ad.assigned_doctor=emp.Employee_ID AND 
            ad.Registration_ID=pr.Registration_ID AND 
            ad.Hospital_Ward_ID=hw.Hospital_Ward_ID
            $filter
            LIMIT 50
            
    ") or die(mysqli_error($conn));
echo "<table class='table' style='border:1px solid #dedede!important;te'>";
if(mysqli_num_rows($sql_select_all_patient_assigned_doctor_result)>0){
    while($rows=mysqli_fetch_assoc($sql_select_all_patient_assigned_doctor_result)){
        $Employee_Name=$rows['Employee_Name'];
        $Patient_Name=$rows['Patient_Name'];
        $Hospital_Ward_Name=$rows['Hospital_Ward_Name'];
        echo "
                <tr style='background:#CCCCCC'>
                    <td colspan='2'><b>Patient Name :-</b>$Patient_Name</td>
                </tr>
                <tr>
                    <td><b>Assigned Dr. :-</b>$Employee_Name</td>
                    <td><b>Ward :-</b>$Hospital_Ward_Name</td>
                </tr>
            ";
    }
}
echo "</table>";