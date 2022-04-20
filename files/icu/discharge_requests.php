<?php
include("includes/connection.php");

        //SELECT ADMISSION WITH DETAILS
    $select_details = mysqli_query($conn, "SELECT Admision_ID, Admission_Date_Time, Patient_Bill_ID FROM tbl_admission ad WHERE hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND DATE(Admission_Date_Time) <= '2021-08-30' AND ad.Hospital_Ward_ID NOT IN('1', '12', '13', '41') AND ad.Admission_Status <> 'Discharged'") or die(mysqli_error($conn));
    $num = 1;
        if(mysqli_num_rows($select_details) > 0){
            while($row = mysqli_fetch_assoc($select_details)){
                $Admision_ID = $row['Admision_ID'];
                $Patient_Bill_ID = $row['Patient_Bill_ID'];
                $Admission_Date_Time = $row['Admission_Date_Time'];

                $UPDATE_ADMISION = mysqli_query($conn, "UPDATE tbl_admission SET Admission_Status = 'Discharged', Discharge_Clearance_Status = 'cleared', Clearance_Date_Time = NOW(), Cash_Bill_Status = 'cleared', Credit_Bill_Status = 'cleared' WHERE Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($UPDATE_ADMISION)>0){
                        $pt_bill = mysqli_query($conn,"UPDATE tbl_patient_bill SET Status='cleared' WHERE Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));

                        if($pt_bill){
                            echo $num."Admission ID ".$Admision_ID." ==> Of Date ".$Admission_Date_Time." ==> Has Been Discarged.<br>";
                        }else{
                            echo $num."Admission ID ".$Admision_ID." ==> Of Date ".$Admission_Date_Time." ==> failed to Update Boooooooooooooooooooooooooooooooo.<br>";
                        }
                    }
            }
        }
?>