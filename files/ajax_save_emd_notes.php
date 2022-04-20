<?php
    include("./includes/connection.php");
        session_start();
        
            $Registration_ID= $_GET['Registration_ID'];
            $consultation_ID= $_GET['consultation_ID'];
            $Nursing_Diagnosis= $_GET['Nursing_Diagnosis'];
            $Airway= $_GET['Airway'];
            $Breathing= $_GET['Breathing'];
            $Circulation= $_GET['Circulation'];
            $Deformity= $_GET['Deformity'];
            $Exposure= $_GET['Exposure'];
            $Nursing_Interverntion= $_GET['Nursing_Interverntion'];
            $General_Remarks= $_GET['General_Remarks'];
            $Main_Complain= $_GET['Main_Complain'];
            $History_of_Present_Illness= $_GET['History_of_Present_Illness'];
            $Employee_ID= $_GET['Employee_ID'];

            if(!empty($consultation_ID) && !(empty($Registration_ID))){
                $creating_emd_notes_file = mysqli_query($conn, "INSERT INTO tbl_emd_nursing_care (consultation_ID, Registration_ID, Employee_ID, History_of_Present_Illness, Main_Complain, Documented_At) VALUES('$consultation_ID', '$Registration_ID', '$Employee_ID', '$History_of_Present_Illness', '$Main_Complain', NOW())");


                if($creating_emd_notes_file){
                    $EMD_nursing_ID = mysqli_insert_id(($conn));

                    $inserting_datas = mysqli_query($conn, "INSERT INTO tbl_emd_nursing_notes (Employee_ID_Updated, EMD_nursing_ID, Airway, Breathing, Circulation, Deformity, Exposure, Nursing_Interverntion, General_Remarks, Main_Complain, Saved_Date_Time) VALUES('$Employee_ID', '$EMD_nursing_ID', '$Airway', '$Breathing', '$Circulation', '$Deformity', '$Exposure', '$Nursing_Interverntion', '$General_Remarks', '$Nursing_Diagnosis', NOW())");

                    if($inserting_datas){
                        echo 200;
                    }else{
                        echo 201;
                    }
                }
            }

mysqli_close($conn);