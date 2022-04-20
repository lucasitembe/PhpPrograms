<?php

@session_start();
include("./includes/connection.php");

if (isset($_GET['rad_Type'])) {
    $rad_Type = $_GET['rad_Type'];

    $displ = "  <option value='All'>All Radiology Employee</option>";

    $select_rad_emp_type = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Employee_Job_Code LIKE '%$rad_Type%'";
    $select_rad_emp_type_qry = mysqli_query($conn,$select_rad_emp_type) or die(mysqli_error($conn));

    while ($emp = mysqli_fetch_assoc($select_rad_emp_type_qry)) {
        $empname = $emp['Employee_Name'];
        $empid = $emp['Employee_ID'];

        $displ.= "<option value='" . $empid . "'>" . $empname . "</option>";
    }
    
    echo $displ;
}

