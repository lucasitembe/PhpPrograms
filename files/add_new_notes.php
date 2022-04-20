<?php
    include("./includes/connection.php");
        session_start();
        
            $Registration_ID= $_GET['Registration_ID'];
            $EMD_nursing_ID= $_GET['EMD_nursing_ID'];
            $Nursing_Diagnosis= $_GET['Nursing_Diagnosis'];
            $Airway= $_GET['Airway'];
            $Breathing= $_GET['Breathing'];
            $Circulation= $_GET['Circulation'];
            $Deformity= $_GET['Deformity'];
            $Exposure= $_GET['Exposure'];
            $Nursing_Interverntion= $_GET['Nursing_Interverntion'];
            $General_Remarks= $_GET['General_Remarks'];
            $Employee_ID= $_GET['Employee_ID'];


                if(!empty($Employee_ID) && !empty($EMD_nursing_ID)){

                    $inserting_datas = mysqli_query($conn, "INSERT INTO tbl_emd_nursing_notes (Employee_ID_Updated, EMD_nursing_ID, Airway, Breathing, Circulation, Deformity, Exposure, Nursing_Interverntion, General_Remarks, Main_Complain, Saved_Date_Time) VALUES('$Employee_ID', '$EMD_nursing_ID', '$Airway', '$Breathing', '$Circulation', '$Deformity', '$Exposure', '$Nursing_Interverntion', '$General_Remarks', '$Nursing_Diagnosis', NOW())");

                    if($inserting_datas){
                        echo 200;
                    }else{
                        echo 201;
                    }
                
            }

mysqli_close($conn);