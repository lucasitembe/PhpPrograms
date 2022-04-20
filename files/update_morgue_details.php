<?php
include ("includes/connection.php");
$Action = $_POST['Action'];

if($Action == 'Update Morgue'){
    $Select_Admission = mysqli_query($conn, "SELECT Admision_ID, Registration_ID FROM tbl_admission WHERE Hospital_Ward_ID = 1 AND Admission_Status <> 'Discharged' ORDER BY Admision_ID DESC LIMIT 10") or die(mysqli_error($conn));
    if(mysqli_num_rows($Select_Admission) > 0){
        while($dts = mysqli_fetch_assoc($Select_Admission)){
            $This_Admision_ID = $dts['Admision_ID'];
            $This_Registration_ID = $dts['Registration_ID'];

                $Select_check_IN = mysqli_query($conn, "SELECT Admission_ID FROM tbl_check_in_details WHERE Admission_ID = '$This_Admision_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($Select_check_IN) > 0){
                        echo "ONE<br>";
                    }else{
                        echo "TWO<br>";
                        $Check_In_Details_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Check_In_Details_ID FROM tbl_check_in_details where Registration_ID = '$This_Registration_ID' ORDER BY Check_In_Details_ID DESC LIMIT 1"))['Check_In_Details_ID'];
                            if($Check_In_Details_ID > 0){
                                $Update_check_In = mysqli_query($conn, "UPDATE tbl_check_in_details SET Admission_ID = '$This_Admision_ID' where Check_In_Details_ID = '$Check_In_Details_ID'") or die(mysqli_error($conn));

                                if($Update_check_In){
                                    echo $Registration_ID." ===> UPDATED SUCCESSFULLY <br>";
                                }
                            }
                    }
        }
    }
}


mysqli_close($conn);

?>