<?php
    include("./includes/connection.php");
        session_start();
        
            $ward_ID= $_GET['ward_ID'];
            $select_round= $_GET['select_round'];
            $Ward_Type= $_GET['Ward_Type'];

            $taarifa ='';
            $duty_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT duty_ID FROM tbl_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['duty_ID'];
            if($duty_ID > 0){


                $select_notes = mysqli_query($conn, "SELECT Nurse_Notes, Updated_Date_Time, Employee_Name FROM tbl_employee em, tbl_nurse_duty_nurse nd WHERE nd.duty_ID = '$duty_ID' AND em.Employee_ID = nd.Employee_ID ORDER BY Notes_ID ASC");
                if(mysqli_num_rows($select_notes)){
                    while($data = mysqli_fetch_assoc($select_notes)){
                        $Employee_Name = $data['Employee_Name'];
                        $Nurse_Notes = $data['Nurse_Notes'];
                        $Updated_Date_Time = $data['Updated_Date_Time'];

                        $details = $Nurse_Notes."


Reported By: ".ucfirst($Employee_Name)."  Time: ".$Updated_Date_Time."
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";

                    $taarifa .= $details."
                    
";

                    }   
                }
            }else{
                $taarifa .= 'NO REPORT WRITTEN FOR THIS SHIFT ON THIS WARD';
            }
     
echo $taarifa; 

mysqli_close($conn);