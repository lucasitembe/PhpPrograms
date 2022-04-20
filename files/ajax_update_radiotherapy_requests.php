<?php
include("includes/connection.php");
$Employee_ID = $_POST['Employee_ID'];
$consultation_ID = $_POST['consultation_ID'];
$Registration_ID = $_POST['Registration_ID'];
$Treatment_Phase = $_POST['Treatment_Phase'];
$Intent_of_Treatment = $_POST['Intent_of_Treatment'];
$Tumor_Dose = $_POST['Tumor_Dose'];
$Number_of_Fraction = $_POST['Number_of_Fraction'];
$Dose_per_Fraction = $_POST['Dose_per_Fraction'];
$name_of_site = $_POST['name_of_site'];
$display = '';

if($Treatment_Phase == 'Phase I'){
    $Ordered_No = 1;
}elseif($Treatment_Phase == 'Phase II'){
    $Ordered_No = 2;
}elseif($Treatment_Phase == 'Phase III'){
    $Ordered_No = 3;
}elseif($Treatment_Phase == 'Phase IV'){
    $Ordered_No = 4;
}

    $Radiotherapy_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Radiotherapy_ID FROM tbl_radiotherapy_requests WHERE consultation_ID = '$consultation_ID'"))['Radiotherapy_ID'];
    if($Radiotherapy_ID > 0){

        $Radiotherapy_Phase_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Radiotherapy_Phase_ID FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND Treatment_Phase = '$Treatment_Phase'"))['Radiotherapy_Phase_ID'];
        if($Radiotherapy_Phase_ID > 0){
            $Update_Phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Tumor_Dose = '$Tumor_Dose', Treatment_Phase = '$Treatment_Phase', Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', name_of_site = '$name_of_site', Ordered_No = '$Ordered_No' WHERE Radiotherapy_Phase_ID = '$Radiotherapy_Phase_ID'");
            
        }else{
            $insert_Phase = mysqli_query($conn, "INSERT INTO tbl_radiotherapy_phases(Radiotherapy_ID, Date_Time) VALUES('$Radiotherapy_ID', NOW())") or die(mysqli_error($conn));
                if($insert_Phase){
                    $Radiotherapy_Phase_ID = mysqli_insert_id($conn);
                    $Update_Phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Tumor_Dose = '$Tumor_Dose', Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', name_of_site = '$name_of_site', Treatment_Phase = '$Treatment_Phase', Ordered_No = '$Ordered_No' WHERE Radiotherapy_Phase_ID = '$Radiotherapy_Phase_ID'");
                }
        }
    }else{
        $Insert_Radiotherapy = mysqli_query($conn, "INSERT INTO tbl_radiotherapy_requests(consultation_ID, Registration_ID, Employee_ID, Intent_of_Treatment, Request_Status, Date_Time) VALUES('$consultation_ID', '$Registration_ID', '$Employee_ID', '$Intent_of_Treatment', 'pending', NOW())") or die(mysqli_error($conn));
        if($Insert_Radiotherapy){
            $Radiotherapy_ID = mysqli_insert_id($conn);
            $Radiotherapy_Phase_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Radiotherapy_Phase_ID FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND Treatment_Phase = '$Treatment_Phase'"))['Radiotherapy_Phase_ID'];
            if($Radiotherapy_Phase_ID > 0){
                $Update_Phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Tumor_Dose = '$Tumor_Dose', Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', name_of_site = '$name_of_site', Ordered_No = '$Ordered_No', Treatment_Phase = '$Treatment_Phase' WHERE Radiotherapy_Phase_ID = '$Radiotherapy_Phase_ID'");
                
            }else{
                $insert_Phase = mysqli_query($conn, "INSERT INTO tbl_radiotherapy_phases(Radiotherapy_ID, Date_Time) VALUES('$Radiotherapy_ID', NOW())") or die(mysqli_error($conn));
                    if($insert_Phase){
                        $Radiotherapy_Phase_ID = mysqli_insert_id($conn);
                        $Update_Phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Tumor_Dose = '$Tumor_Dose', Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', name_of_site = '$name_of_site', Ordered_No = '$Ordered_No', Treatment_Phase = '$Treatment_Phase' WHERE Radiotherapy_Phase_ID = '$Radiotherapy_Phase_ID'");
                    }
            }
        }
    }

    mysqli_close($conn);
?>