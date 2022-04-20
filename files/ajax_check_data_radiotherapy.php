<?php
    include("./includes/connection.php");
        session_start();
        
            $details= $_POST['details'];
            $Treatment_Phase= $_POST['Treatment_Phase'];
            $consultation_ID= $_POST['consultation_ID'];
            $action= $_POST['action'];

            if(isset($_POST['details']) && !empty($Treatment_Phase)){

                $check_data = mysqli_query($conn, "SELECT $details as Results FROM tbl_radiotherapy_phases rp, tbl_radiotherapy_requests rr WHERE consultation_ID = '$consultation_ID' AND Treatment_Phase = '$Treatment_Phase' AND rp.Radiotherapy_ID = rr.Radiotherapy_ID");
                    while($row = mysqli_fetch_assoc($check_data)){
                        $Results = $row['Results'];
                    }
                echo $Results; 
            }elseif($action == 'Submit'){
                $Submit_radiptherapy = mysqli_query($conn, "UPDATE tbl_radiotherapy_requests SET Request_Status = 'Submitted' WHERE consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
            }

mysqli_close($conn);
?>