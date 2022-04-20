<?php
    include("./includes/connection.php");
        session_start();
        
            $ward_ID= $_GET['ward_ID'];
            $select_round= $_GET['select_round'];
            $Ward_Type= $_GET['Ward_Type'];

            if(isset($_GET['transferOut'])){
                $transferOut = $_GET['transferOut'];

                $transferOut = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transferOut FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['transferOut'];

                echo $transferOut; 
            }
            if(isset($_GET['current_inpatient'])){
                $current_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT current_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['current_inpatient'];

                echo $current_inpatient; 
            }
            if(isset($_GET['received_inpatient'])){
                $received_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT received_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['received_inpatient'];
                     
                echo $received_inpatient; 
            }
            if(isset($_GET['discharged_inpatient'])){
                $discharged_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT discharged_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['discharged_inpatient'];
                     
                echo $discharged_inpatient; 
            }
            if(isset($_GET['Refugees'])){
                $Refugees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Refugees FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['Refugees'];
                     
                echo $Refugees; 
            }
            if(isset($_GET['serious_inpatient'])){
                $serious_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT serious_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['serious_inpatient'];
                     
                echo $serious_inpatient; 
            }
            if(isset($_GET['lodgers'])){
                $lodgers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT lodgers FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['lodgers'];
                     
                echo $lodgers; 
            }
            if(isset($_GET['debt_inpatient'])){
                $debt_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT debt_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['debt_inpatient'];
                     
                echo $debt_inpatient; 
            }
            if(isset($_GET['death_inpatient'])){
                $death_inpatient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT death_inpatient FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['death_inpatient'];
                     
                echo $death_inpatient; 
            }
            if(isset($_GET['Abscondees'])){
                $Abscondees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Abscondees FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['Abscondees'];
                     
                echo $Abscondees; 
            }
            if(isset($_GET['Prisoner'])){
                $Prisoner = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Prisoner FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['Prisoner'];
                     
                echo $Prisoner; 
            }
            if(isset($_GET['transferIn'])){
                $transferIn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transferIn FROM tbl_opd_nurse_duties WHERE Ward_Type = '$Ward_Type' AND select_round = '$select_round' AND duty_ward = '$ward_ID' AND Process_Status = 'pending' AND DATE(duty_handled) = CURDATE()"))['transferIn'];
                     
                echo $transferIn; 
            }
            if(isset($_GET['duty_nurse'])){
                $transferIn = mysqli_query($conn, "SELECT nd.duty_nurse, em.Employee_Name FROM tbl_opd_nurse_duties nd, tbl_employee em WHERE nd.Ward_Type = '$Ward_Type' AND nd.select_round = '$select_round' AND nd.duty_ward = '$ward_ID' AND nd.Process_Status = 'pending' AND DATE(nd.duty_handled) = CURDATE() AND em.Employee_ID = nd.duty_nurse");
                if(mysqli_num_rows($transferIn)> 0){
                    while($data = mysqli_fetch_assoc($transferIn)){
                        $duty_nurse = $data['duty_nurse'];
                        $Employee_Name = $data['Employee_Name'];

                        $transferIn = "<option selected='selected' value='".$duty_nurse."'>".$Employee_Name."</option>";
                    }
                }
                     
                echo $Employee_Name; 
            }


mysqli_close($conn);