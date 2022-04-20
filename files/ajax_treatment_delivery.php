<?php
include("includes/connection.php");

$Employee_ID = $_POST['Employee_ID'];
$field_name = $_POST['field_name'];
$dosage = $_POST['dosage'];
$position_immobilization_ID = $_POST['position_immobilization_ID'];
$unit = $_POST['unit'];
$wedge = $_POST['wedge'];
$block = $_POST['block'];
$total_tumour_dose = $_POST['total_tumour_dose'];
$number_of_fraction = $_POST['number_of_fraction'];
$action = $_POST['action'];
$number_phases = $_POST['number_phases'];
$Treatment_Time = $_POST['Treatment_Time'];
$Dose_per_Fraction = $_POST['Dose_per_Fraction'];
$Radiotherapy_ID = $_POST['Radiotherapy_ID'];

    if(!empty($Radiotherapy_ID) && $action == 'update'){
            $check_Machine_setup = mysqli_query($conn, "SELECT treatment_delivery_ID, Treatment_Status FROM tbl_treatment_delivery WHERE Date_field = CURDATE() AND Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND setup_devery_ID = '$field_name'") or die(mysqli_error($conn));
            if(mysqli_num_rows($check_Machine_setup)>0){
                while($rows = mysqli_fetch_assoc($check_Machine_setup)){
                    $treatment_delivery_ID = $rows['treatment_delivery_ID'];
                    $Treatment_Status = $rows['Treatment_Status'];

                    $Update_taarifa = mysqli_query($conn, "UPDATE tbl_treatment_delivery SET Cummutive_Dose1 = '$dosage' WHERE treatment_delivery_ID = '$treatment_delivery_ID' AND  Treatment_Status = 'pending'");
                }
            }else{
                $Insert_Delivery = mysqli_query($conn, "INSERT INTO tbl_treatment_delivery (Cummutive_Dose1, Employee_ID, Date_field, Dose_per_Fraction1, Time1, Treatment_Status, Radiotherapy_ID, number_phases, date_and_time, setup_devery_ID) VALUES('$dosage', '$Employee_ID', CURDATE(), '$Dose_per_Fraction', '$Treatment_Time', 'pending', '$Radiotherapy_ID', '$number_phases', NOW(), '$field_name')") or die(mysqli_error($conn));
            }
    }elseif(!empty($Radiotherapy_ID) && $action == 'save'){
        $update_all = mysqli_query($conn, "UPDATE tbl_treatment_delivery SET Treatment_Status = 'delivered' WHERE  Date_field = CURDATE() AND Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND Treatment_Status = 'pending'");
    }

mysqli_close($conn);
?>