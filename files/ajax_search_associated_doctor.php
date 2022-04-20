<?php
session_start();
include("includes/connection.php");
if (isset($_POST['associated_doctor_name'])) {
   $associated_doctor_name=$_POST['associated_doctor_name']; 
   //select list of all doctor
   echo "<table class='table'>
        <tr style='background:#DEDEDE'>
             <td>S/No.</td>
             <td>DOCTOR NAME</td>
             <td>ACTION</td>
        </tr>
     ";
    $Select_Doctors = "select Employee_ID, Employee_Name from tbl_employee where employee_type IN ('Doctor', 'Intern Doctor') and Account_Status = 'active' AND Employee_Name LIKE '%$associated_doctor_name%' LIMIT 50";
    $result = mysqli_query($conn,$Select_Doctors);
    $count_sn=1;
    while ($row = mysqli_fetch_array($result)) {
       $Employee_Name= $row['Employee_Name'];
       $Employee_ID= $row['Employee_ID'];
          echo "<tr>
                    <td>$count_sn.</td>
                    <td>$Employee_Name</td>
                    <td><input type='button' value='SELECT' class='art-button-green' onclick='add_associated_doctor_to_catch($Employee_ID)'/></td>
                </tr>";
          $count_sn++;
    }
    echo "</table>";
}