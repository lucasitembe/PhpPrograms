<?php

require_once('includes/connection.php');

$field_ID = $_POST['field_ID'];
$BSF = $_POST['BSF'];
$Inverse_square_factor = $_POST['Inverse_square_factor'];
$Re_Employee_ID = $_POST['Re_Employee_ID'];
if (isset($_POST['Eq_Square'])) {
    $Eq_Square = $_POST['Eq_Square'];
} else {
    $Eq_Square = '';
}
if (isset($_POST['cGY_SSD'])) {
    $cGY_SSD = $_POST['cGY_SSD'];
} else {
    $cGY_SSD = '';
}
if (isset($_POST['cGY_SAD'])) {
    $cGY_SAD = $_POST['cGY_SAD'];
} else {
    $cGY_SAD = '';
}
if (isset($_POST['PDD'])) {
    $PDD = $_POST['PDD'];
} else {
    $PDD = '';
}
if (isset($_POST['TAR'])) {
    $TAR = $_POST['TAR'];
} else {
    $TAR = '';
}
if (isset($_POST['Couch_Factor'])) {
    $Couch_Factor = $_POST['Couch_Factor'];
} else {
    $Couch_Factor = '';
}
if (isset($_POST['Wedge_Factor'])) {
    $Wedge_Factor = $_POST['Wedge_Factor'];
} else {
    $Wedge_Factor = '';
}
if (isset($_POST['Inhomogy_Tray'])) {
    $Inhomogy_Tray = $_POST['Inhomogy_Tray'];
} else {
    $Inhomogy_Tray = '';
}
if (isset($_POST['Tumour_Dose'])) {
    $Tumour_Dose = $_POST['Tumour_Dose'];
} else {
    $Tumour_Dose = '';
}
if (isset($_POST['Dose_Fraction'])) {
    $Dose_Fraction = $_POST['Dose_Fraction'];
} else {
    $Dose_Fraction = '';
}
if (isset($_POST['Treatment_Time'])) {
    $Treatment_Time = $_POST['Treatment_Time'];
} else {
    $Treatment_Time = '';
}
if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID = '';
}
if (isset($_POST['Employee_ID'])) {
    $Employee_ID = $_POST['Employee_ID'];
} else {
    $Employee_ID = '';
}

if(!empty($Re_Employee_ID)){
    $Re_Employee_ID = $_POST['Re_Employee_ID'];
}

 $mysqli_check_simulation_data=mysqli_query($conn,"SELECT calculation_parameter_ID FROM tbl_calculation_parameter WHERE field_ID='$field_ID' AND date(date_time)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
       $calculation_parameter_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT calculation_parameter_ID FROM tbl_calculation_parameter WHERE field_ID='$field_ID'"))['calculation_parameter_ID'];
       
        $sql_save_data = mysqli_query($conn,"UPDATE tbl_calculation_parameter SET Registration_ID = '$Registration_ID', Employee_ID = '$Employee_ID', Eq_Square = '$Eq_Square', cGY_SSD = '$cGY_SSD', cGY_SAD = '$cGY_SAD', PDD = '$PDD', TAR = '$TAR', BSF = '$BSF', Couch_Factor = '$Couch_Factor', Wedge_Factor = '$Wedge_Factor', Inhomogy_Tray = '$Inhomogy_Tray', Tumour_Dose = '$Tumour_Dose', Dose_Fraction = '$Dose_Fraction', Treatment_Time = '$Treatment_Time', Inverse_square_factor = '$Inverse_square_factor', Re_Employee_ID = '$Re_Employee_ID', date_time = NOW() WHERE calculation_parameter_ID = '$calculation_parameter_ID'");
       
   }else{
       
        $sql_save_data = mysqli_query($conn,"INSERT INTO tbl_calculation_parameter(Registration_ID, field_ID, Employee_ID, BSF, Eq_Square, cGY_SSD, cGY_SAD, PDD, TAR, Couch_Factor, Wedge_Factor, Inhomogy_Tray, Tumour_Dose, Dose_Fraction, Treatment_Time, Inverse_square_factor, date_time) VALUES('$Registration_ID', '$field_ID', '$Employee_ID', '$BSF', '$Eq_Square', '$cGY_SSD', '$cGY_SAD', '$PDD', '$TAR', '$Couch_Factor', '$Wedge_Factor', '$Inhomogy_Tray', '$Tumour_Dose', '$Dose_Fraction', '$Treatment_Time', '$Inverse_square_factor', NOW())");
       
   }

   mysqli_close($conn);
   ?>
