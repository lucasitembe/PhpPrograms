<?php

  /*+++++++++++++++++++ Designed and implimented ++++++++++++++++++++++++=++++++++
     ++++++++++++++++   by  Eng. Muga moscow Since 2019-11-13++++++++++++++++++++++++++*/
    session_start();
    include("./includes/connection.php");
        // if (!isset($_SESSION['userinfo'])) {
        //     @session_destroy();
        //     header("Location: ../index.php?InvalidPrivilege=yes");
        // }
        if (isset($_POST['consultation_ID'])) {
            $consultation_ID = $_POST['consultation_ID'];
        } else {
            $consultation_ID = 0;
        }
        
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        
        


            if(isset($_POST['Registration_ID'])){
                $Registration_ID= $_POST['Registration_ID'];
            }else{
            $Registration_ID="";    
            }
            if(isset($_POST['Payment_Cache_ID'])){
                $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
            }else{
                $Payment_Cache_ID= "";
            }
        if(isset($_POST['consent_signed_pre'])){
            $Registration_ID=mysqli_real_escape_string($conn, $_POST['Registration_ID']);
            $consent_signed=mysqli_real_escape_string($conn, $_POST['consent_signed']);
            $significant_history = mysqli_real_escape_string($conn, $_POST['significant_history']);
            $past_history = mysqli_real_escape_string($conn, $_POST['past_history']);
            $family_history =mysqli_real_escape_string($conn, $_POST['family_history']);
            $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
            $social_history =mysqli_real_escape_string($conn, $_POST['social_history']);
            $dental_status =mysqli_real_escape_string($conn, $_POST['dental_status']);
            $Cardiac_angina=mysqli_real_escape_string($conn, $_POST['Cardiac_angina']);
            $Cardiac_valvular_disease=mysqli_real_escape_string($conn, $_POST['Cardiac_valvular_disease']);
            $Cardiac_arrhythias=mysqli_real_escape_string($conn, $_POST['Cardiac_arrhythias']);            
            $Cardiac_heart_failure=mysqli_real_escape_string($conn, $_POST['Cardiac_heart_failure']);
            $Cardiac_ph_vascular_disease=mysqli_real_escape_string($conn, $_POST['Cardiac_ph_vascular_disease']);
            $Cardiac_htn=mysqli_real_escape_string($conn, $_POST['Cardiac_htn']);
            $Cardiac_valvular_disease = mysqli_real_escape_string($conn, $_POST['Cardiac_valvular_disease']);
            $consertform =mysqli_real_escape_string($conn, $_POST['consertform']);
            $Cardiac_other_details =mysqli_real_escape_string($conn, $_POST['Cardiac_other_details']);
            $nutritional_status = mysqli_real_escape_string($conn, $_POST['nutritional_status']);
            $past_anaesthesia_history = mysqli_real_escape_string($conn, $_POST['past_anaesthesia_history']);
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                if(mysqli_num_rows($anasthesia_record_result)>0){
                    $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                }else{
                    $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                    $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                    $anasthesia_record_id=mysqli_insert_id($conn);
                    
                } 
   
            $sql_save_information_result=mysqli_query($conn,"INSERT INTO  anasthesia_combined_assessment(significant_history,past_history,family_history,consertform, nutritional_status, consent_signed,allergies,social_history,dental_status,
            Cardiac_angina, Cardiac_valvular_disease, Cardiac_arrhythias, Cardiac_heart_failure, Cardiac_ph_vascular_disease, Cardiac_htn,Cardiac_other_details, Employee_ID,Registration_ID, anasthesia_record_id, past_anaesthesia_history ) VALUES('$significant_history', '$past_history','$family_history','$consertform','$nutritional_status', '$consent_signed','$allergies','$social_history','$dental_status','$Cardiac_angina',
            '$Cardiac_valvular_disease','$Cardiac_arrhythias','$Cardiac_heart_failure','$Cardiac_ph_vascular_disease','$Cardiac_htn','$Cardiac_other_details',
            '$Employee_ID','$Registration_ID','$anasthesia_record_id', '$past_anaesthesia_history')") or die(mysqli_error($conn));
            

            if(!$sql_save_information_result){
                echo "Ooops Data not served";
            }else{
                echo "ok";
            }
        }
            if(isset($_POST['intraop_record'])){
                $patient_fasted = mysqli_real_escape_string($conn, $_POST['patient_fasted']);
                $IV_sites = mysqli_real_escape_string($conn, $_POST['IV_sites']);
                $IV_size = mysqli_real_escape_string($conn, $_POST['IV_size']);
                $Cental_line_size = mysqli_real_escape_string($conn, $_POST['Cental_line_size']);
                $induction = mysqli_real_escape_string($conn, $_POST['induction']);
                $intubation = mysqli_real_escape_string($conn, $_POST['intubation']);
                $Nasal = mysqli_real_escape_string($conn, $_POST['Nasal']);
                $Ett_type = mysqli_real_escape_string($conn, $_POST['Ett_type']);
                $Ett_size = mysqli_real_escape_string($conn, $_POST['Ett_size']);
                $Airway = mysqli_real_escape_string($conn, $_POST['Airway']);
                $Circuit = mysqli_real_escape_string($conn, $_POST['Circuit']);
                $Ventilation = mysqli_real_escape_string($conn, $_POST['Ventilation']);
                $Agent = mysqli_real_escape_string($conn, $_POST['Agent']);
                $RR = mysqli_real_escape_string($conn, $_POST['RR']);
                $TV = mysqli_real_escape_string($conn, $_POST['TV']);
                $Press = mysqli_real_escape_string($conn, $_POST['Press']);
                $PEEP = mysqli_real_escape_string($conn, $_POST['PEEP']);
                $I_E = mysqli_real_escape_string($conn, $_POST['I_E']);
                $Type = mysqli_real_escape_string($conn, $_POST['Type']);
                $Conc = mysqli_real_escape_string($conn, $_POST['Conc']);
                $Other_anasth = mysqli_real_escape_string($conn, $_POST['Other_anasth']);
                $Amount = mysqli_real_escape_string($conn, $_POST['Amount']);
                $Position = mysqli_real_escape_string($conn, $_POST['Position']);
                $Comments = mysqli_real_escape_string($conn, $_POST['Comments']);
                $Maintainance = mysqli_real_escape_string($conn, $_POST['Maintainance']);
                $others_intubation = mysqli_real_escape_string($conn, $_POST['others_intubation']);
                $IV_fluid = mysqli_real_escape_string($conn, $_POST['IV_fluid']);


                $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                if(mysqli_num_rows($anasthesia_record_result)>0){
                    $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                }else{
                    $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                    $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                    $anasthesia_record_id=mysqli_insert_id($conn);
                    
                }
   
                $sql_save_information_result=mysqli_query($conn,"INSERT INTO  tbl_intraop_record(patient_fasted,IV_sites,IV_size,Cental_line_size, induction, intubation,others_intubation,IV_fluid, Nasal,Ett_type,Ett_size,
                Airway, Circuit, Ventilation,  RR, TV,Press, PEEP,I_E,Types,Agent,Conc,Amount,Position,Comments,Maintainance, Employee_ID,Registration_ID, anasthesia_record_id, Other_anasth ) VALUES('$patient_fasted', '$IV_sites','$IV_size','$Cental_line_size','$induction','$intubation','$others_intubation','$IV_fluid', '$Nasal','$Ett_type','$Ett_size','$Airway',
                '$Circuit','$Ventilation','$RR','$TV','$Press','$PEEP','$I_E', '$Type','$Agent','$Conc','$Amount','$Position','$Comments','$Maintainance',
                '$Employee_ID','$Registration_ID','$anasthesia_record_id', '$Other_anasth')") or die(mysqli_error($conn));
                

                if(!$sql_save_information_result){
                   echo "Oooops!!!. Data not saved";
                }else{
                    echo "
                    <script>
                        alert('Saved Successfully');
                        document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
                    </script>
                ";
                }
            }


        if(isset($_POST['pulmonary'])){
            $asthma = mysqli_real_escape_string($conn,  $_POST["asthma"]);
            $copd = mysqli_real_escape_string($conn,  $_POST["copd"]);
            $smoking = mysqli_real_escape_string($conn,  $_POST["smoking"]);
            $recent_urti = mysqli_real_escape_string($conn,  $_POST["recent_urti"]);
            $pulmonary_details = mysqli_real_escape_string($conn,  $_POST["pulmonary_details"]);

            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

            //select anesthesia record id
                $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                if(mysqli_num_rows($anasthesia_record_result)>0){
                    $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                }else{
                    $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                    $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                    $anasthesia_record_id=mysqli_insert_id($conn);
                    
            }
            $send_follow_up_visit = mysqli_query($conn, "INSERT INTO tbl_pulmonary_disease (created_at, asthma, copd, smoking, recent_urti, pulmonary_details, anasthesia_record_id, Registration_ID, Employee_ID) 
            VALUES (NOW(), '$asthma', '$copd', '$smoking','$recent_urti', '$pulmonary_details','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
            if(!$send_follow_up_visit){
                echo "Couldn't insert data ".mysqli_error($conn);
            }else{
                echo "Saved successful";
            }
        }

            if(isset($_POST['endocrine_metabolic'])){
                $diabetes_mellitus = mysqli_real_escape_string($conn,  $_POST["diabetes_mellitus"]);
                $pregnancy = mysqli_real_escape_string($conn,  $_POST["pregnancy"]);
                $gestation_week = mysqli_real_escape_string($conn,  $_POST["gestation_week"]);
                $metabolic_details = mysqli_real_escape_string($conn,  $_POST["metabolic_details"]);

                $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

                //select anesthesia record id
                    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                    if(mysqli_num_rows($anasthesia_record_result)>0){
                        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                    }else{
                        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID,', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                        $anasthesia_record_id=mysqli_insert_id($conn);
                        
                }
                $send_follow_up_visit = mysqli_query($conn, "INSERT INTO tbl_metabolic_disease (created_at, diabetes_mellitus, pregnancy, gestation_week, metabolic_details, anasthesia_record_id, Registration_ID, Employee_ID) 
                VALUES (NOW(),  '$diabetes_mellitus', '$pregnancy','$gestation_week', '$metabolic_details','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
                if(!$send_follow_up_visit){
                    echo "Couldn't insert data ".mysqli_error($conn);
                }else{
                    echo "Saved successful";
                }

            } 

            if(isset($_POST['gastrointestinal'])){
                $liver_desease = mysqli_real_escape_string($conn,  $_POST["liver_desease"]);
                $alcohol_consumption = mysqli_real_escape_string($conn,  $_POST["alcohol_consumption"]);
                $gastrointestinal_details = mysqli_real_escape_string($conn,  $_POST["gastrointestinal_details"]);

                $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

                //select anesthesia record id
                    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                    if(mysqli_num_rows($anasthesia_record_result)>0){
                        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                    }else{
                        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID,', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                        $anasthesia_record_id=mysqli_insert_id($conn);
                        
                }
                $gastrointestinal = mysqli_query($conn, "INSERT INTO tbl_anasthesial_gastrointestinal_disease (created_at, liver_desease, alcohol_consumption, gastrointestinal_details, anasthesia_record_id, Registration_ID, Employee_ID) 
                VALUES (NOW(),  '$liver_desease', '$alcohol_consumption','$gastrointestinal_details','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
                if(!$gastrointestinal){
                    echo "Couldn't insert data ".mysqli_error($conn);
                }else{
                    echo "Saved successful";
                }
            }

    if(isset($_POST['pediatric'])){
        $pediaric_derivery_term = mysqli_real_escape_string($conn,  $_POST["pediaric_derivery_term"]);
        $gestation_week = mysqli_real_escape_string($conn,  $_POST["gestation_week"]);
        $resuscitation_done = mysqli_real_escape_string($conn,  $_POST["resuscitation_done"]);
        $pediatric_details = mysqli_real_escape_string($conn,  $_POST["pediatric_details"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
       
        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $pediatric = mysqli_query($conn, "INSERT INTO tbl_anasthesia_pediatric (created_at, pediaric_derivery_term, gestation_week, resuscitation_done,pediatric_details, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES (NOW(),  '$pediaric_derivery_term', '$gestation_week','$resuscitation_done','$pediatric_details','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$pediatric){
            echo "Couldn't insert data ".mysqli_error($conn);
        } else{
            echo "Saved successful";        
        }
    }

    if(isset($_POST['drug_surgical'])){
        $renal_disease = mysqli_real_escape_string($conn,  $_POST["renal_disease"]);
        $renal_other_details = mysqli_real_escape_string($conn,  $_POST["renal_other_details"]);
        $cns_desease = mysqli_real_escape_string($conn,  $_POST["cns_desease"]);
        $musculoskeletal_diseases = mysqli_real_escape_string($conn,  $_POST["musculoskeletal_diseases"]);
        $cns_musculoskeletal_diseases = mysqli_real_escape_string($conn,  $_POST["cns_musculoskeletal_diseases"]);
        $clotting_disorders = mysqli_real_escape_string($conn,  $_POST["clotting_disorders"]);
        $clotting_disorders_details = mysqli_real_escape_string($conn,  $_POST["clotting_disorders_details"]);

        $significant_family_history = mysqli_real_escape_string($conn,  $_POST["significant_family_history"]);
        $drug_reaction = mysqli_real_escape_string($conn,  $_POST["drug_reaction"]);
        $drug_reaction_yes = mysqli_real_escape_string($conn,  $_POST["drug_reaction_yes"]);
        $surgical_hisory = mysqli_real_escape_string($conn,  $_POST["surgical_hisory"]);
        $surgical_history_ifyes = mysqli_real_escape_string($conn,  $_POST["surgical_history_ifyes"]);
        $current_medication = mysqli_real_escape_string($conn,  $_POST["current_medication"]);
        
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anasthesia_renalto_medication (created_at, renal_disease, renal_other_details, 
        cns_desease,musculoskeletal_diseases, cns_musculoskeletal_diseases, clotting_disorders, clotting_disorders_details,
        significant_family_history,drug_reaction, drug_reaction_yes, surgical_hisory,surgical_history_ifyes, current_medication, 
        anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES (NOW(),  '$renal_disease', '$renal_other_details', '$cns_desease', '$musculoskeletal_diseases', '$cns_musculoskeletal_diseases',
        '$clotting_disorders', '$clotting_disorders_details', '$significant_family_history', '$drug_reaction', '$drug_reaction_yes',
        '$surgical_hisory', '$surgical_history_ifyes', '$current_medication', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')") or die("Couldn't insert data ".mysqli_error($conn));
        if(!$renalto_medication){
            echo "<script>alert('Ooops didn't  save!!! Try again!')</script>";            
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>
        ";
        }
    }

    //insert general exemination data
    if(isset($_POST['general_exemination'])){
        $patient_state = implode(",",  $_POST["patient_state"]);
        $bp = mysqli_real_escape_string($conn,  $_POST["bp"]);
        $hr_pr = mysqli_real_escape_string($conn,  $_POST["hr_pr"]);
        $temp = mysqli_real_escape_string($conn,  $_POST["temp"]);
        $wt = mysqli_real_escape_string($conn,  $_POST["wt"]);
        $ht = mysqli_real_escape_string($conn,  $_POST["ht"]);
        $bmi = mysqli_real_escape_string($conn,  $_POST["bmi"]);
        $Dyspnoea= mysqli_real_escape_string($conn, $_POST['Dyspnoea']);
        $rbg = mysqli_real_escape_string($conn,  $_POST["rbg"]);
        $mouth_opening = mysqli_real_escape_string($conn,  $_POST["mouth_opening"]);
        $micrognathia = mysqli_real_escape_string($conn,  $_POST["micrognathia"]);
        $neck_extension = mysqli_real_escape_string($conn,  $_POST["neck_extension"]);
        $thyromental_distance = mysqli_real_escape_string($conn,  $_POST["thyromental_distance"]);
        $mallampati = mysqli_real_escape_string($conn,  $_POST["mallampati"]);
        $teeth = implode(",",  $_POST["teeth"]);
        $cvs = mysqli_real_escape_string($conn,  $_POST["cvs"]);
        $lungs = mysqli_real_escape_string($conn,  $_POST["lungs"]);
        $other_systems = mysqli_real_escape_string($conn,  $_POST["other_systems"]);
        $LL_EDEMA = mysqli_real_escape_string($conn, $_POST['LL_EDEMA']);
        $last_oral_intake = mysqli_real_escape_string($conn, $_POST['last_oral_intake']);
        $ASA_physical_status =mysqli_real_escape_string($conn, $_POST['ASA_physical_status']);
        
        $pre_anaesthetic_orders = mysqli_real_escape_string($conn, $_POST['pre_anaesthetic_orders']);
        $Anaesthetic_technique = mysqli_real_escape_string($conn, $_POST['Anaesthetic_technique']);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }

        $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anasthesia_genaral_examination (created_at, patient_state,Dyspnoea,LL_EDEMA, bp, hr_pr,temp, wt, ht, bmi,
        rbg,mouth_opening, micrognathia, neck_extension,thyromental_distance, mallampati, teeth, cvs,lungs, other_systems,last_oral_intake,ASA_physical_status,Anaesthetic_technique,pre_anaesthetic_orders,
        anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES (NOW(),  '$patient_state','$Dyspnoea','$LL_EDEMA', '$bp', '$hr_pr', '$temp', '$wt',
        '$ht', '$bmi', '$rbg', '$mouth_opening', '$micrognathia',
        '$neck_extension', '$thyromental_distance', '$mallampati','$teeth',
        '$cvs', '$lungs', '$other_systems', '$last_oral_intake','$ASA_physical_status', '$Anaesthetic_technique','$pre_anaesthetic_orders','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$renalto_medication){
            die("Couldn't insert data ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script> ";
        }
    }

    //Insert into anaesthesia_nerve_block_procedure
    if(isset($_POST['anaesthesia_nerve_block_procedure'])){
        $consent_confirmed = mysqli_real_escape_string($conn,  $_POST["consent_confirmed"]);
        $surgical_site = mysqli_real_escape_string($conn,  $_POST["surgical_site"]);
        $Mornitoring_used = mysqli_real_escape_string($conn,  $_POST["Mornitoring_used"]);
        $working_IV = mysqli_real_escape_string($conn,  $_POST["working_IV"]);
        $Prep_type = mysqli_real_escape_string($conn,  $_POST["Prep_type"]);
        $Liquid_emulsion_available = mysqli_real_escape_string($conn,  $_POST["Liquid_emulsion_available"]);
        $needle_type_length = mysqli_real_escape_string($conn,  $_POST["needle_type_length"]);
        $needle_length = mysqli_real_escape_string($conn, $_POST['needle_length']);
        $pre_medication_given= mysqli_real_escape_string($conn, $_POST['pre_medication_given']);
        $time = mysqli_real_escape_string($conn,  $_POST["time"]);
        $Simulator = mysqli_real_escape_string($conn,  $_POST["Simulator"]);
        $Simulator_amplitude = mysqli_real_escape_string($conn,  $_POST["Simulator_amplitude"]);
        $local_anaesthesia_type = mysqli_real_escape_string($conn,  $_POST["local_anaesthesia_type"]);
        $local_anaesthesia_type_mls = mysqli_real_escape_string($conn,  $_POST["local_anaesthesia_type_mls"]);

        $Paresthesia_Pain_on_Injection = mysqli_real_escape_string($conn,  $_POST["Paresthesia_Pain_on_Injection"]);
        $Aspiration = mysqli_real_escape_string($conn,  $_POST["Aspiration"]);
        $Injection_pressure = mysqli_real_escape_string($conn,  $_POST["Injection_pressure"]);
        $Anaesthetic_plan = mysqli_real_escape_string($conn,  $_POST["Anaesthetic_plan"]);
        $Utrasound_used = mysqli_real_escape_string($conn,  $_POST["Utrasound_used"]);
        $Nerver_stimulator_used = mysqli_real_escape_string($conn, $_POST['Nerver_stimulator_used']);
        $time_block_performed = mysqli_real_escape_string($conn,  $_POST["time_block_performed"]);
        $time_into_theatre = mysqli_real_escape_string($conn, $_POST['time_into_theatre']);
        $time_to_post_op_ward = mysqli_real_escape_string($conn, $_POST['time_to_post_op_ward']);
        $type_of_block = mysqli_real_escape_string($conn, $_POST['type_of_block']);
 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }

        $narve_block_procedure = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_nerve_block_procedure ( consent_confirmed,pre_medication_given,time_into_theatre, surgical_site, Mornitoring_used,working_IV, Prep_type, Liquid_emulsion_available, needle_type_length,needle_length,
        time,Simulator, Simulator_amplitude, local_anaesthesia_type,local_anaesthesia_type_mls,Paresthesia_Pain_on_Injection, Aspiration, Injection_pressure, Anaesthetic_plan,Utrasound_used,Nerver_stimulator_used, time_block_performed,time_to_post_op_ward,type_of_block, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES (  '$consent_confirmed','$pre_medication_given','$time_into_theatre', '$surgical_site', '$Mornitoring_used', '$working_IV', '$Prep_type',
        '$Liquid_emulsion_available', '$needle_type_length','$needle_length', '$time', '$Simulator', '$Simulator_amplitude',
        '$local_anaesthesia_type','$local_anaesthesia_type_mls', '$Paresthesia_Pain_on_Injection', '$Aspiration','$Injection_pressure',
        '$Anaesthetic_plan', '$Utrasound_used','$Nerver_stimulator_used', '$time_block_performed', '$time_to_post_op_ward','$type_of_block','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$narve_block_procedure){
            die("Couldn't insert data ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script> ";        
        }
    }

    //insert premedication
    if(isset($_POST['premedication'])){
        $fasting_for = mysqli_real_escape_string($conn,  $_POST["fasting_for"]);
        $medication_at_night = mysqli_real_escape_string($conn,  $_POST["medication_at_night"]);
        $medication_morning = mysqli_real_escape_string($conn,  $_POST["medication_morning"]);
        $orders_standby_blood = mysqli_real_escape_string($conn,  $_POST["orders_standby_blood"]);
        $nurse_name_id = mysqli_real_escape_string($conn,  $_POST["nurse_name_id"]);
        $dispensing_time = mysqli_real_escape_string($conn,  $_POST["dispensing_time"]);
        $anticipated_anesthetic_risks = mysqli_real_escape_string($conn,  $_POST["anticipated_anesthetic_risks"]);
        $proposed_anasthesia = mysqli_real_escape_string($conn,  $_POST["proposed_anasthesia"]);       
        $anesthesiologist_opinion = mysqli_real_escape_string($conn,  $_POST["anesthesiologist_opinion"]);
        $anesthesiologist_nameby_id = mysqli_real_escape_string($conn, $_POST['anesthesiologist_nameby_id']);
        $blood_unit =mysqli_real_escape_string($conn, $_POST['blood_unit']);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anasthesia_premedication (blood_unit, fasting_for, medication_at_night, medication_morning,orders_standby_blood, nurse_name_id, dispensing_time,
        anticipated_anesthetic_risks,proposed_anasthesia, anesthesiologist_opinion, anesthesiologist_nameby_id,
        anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ('$blood_unit', '$fasting_for', '$medication_at_night', '$medication_morning', '$orders_standby_blood', '$nurse_name_id',
         '$dispensing_time',  '$anticipated_anesthetic_risks', '$proposed_anasthesia', 
        '$anesthesiologist_opinion','$anesthesiologist_nameby_id', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$renalto_medication){
            die("Couldn't insert data ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>";       
         }
    }

     


    //Never block outcome
    if(isset($_POST['never_block_outcomes'])){
        $block_set_up_at_30min = mysqli_real_escape_string($conn,  $_POST["block_set_up_at_30min"]);
        $sedation_need_for_operation = mysqli_real_escape_string($conn,  $_POST["sedation_need_for_operation"]);
        $plan_for_ga_for_op = mysqli_real_escape_string($conn,  $_POST["plan_for_ga_for_op"]);
        $conversation_to_ga_for_op = mysqli_real_escape_string($conn,  $_POST["conversation_to_ga_for_op"]);
        $Complication = mysqli_real_escape_string($conn,  $_POST["Complication"]);
        $Anaethetic_coplication_treatment = mysqli_real_escape_string($conn,  $_POST["Anaethetic_coplication_treatment"]);      
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_never_block_outcome ( block_set_up_at_30min, sedation_need_for_operation, plan_for_ga_for_op,conversation_to_ga_for_op, Complication, Anaethetic_coplication_treatment,
        anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$block_set_up_at_30min', '$sedation_need_for_operation', '$plan_for_ga_for_op', '$conversation_to_ga_for_op', '$Complication',
         '$Anaethetic_coplication_treatment',   '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$renalto_medication){
            die("Couldn't insert data nerver block".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>
        ";        }
    }

    //=====================Pre and Post Vitals CRUD==========================

    if(isset($_POST['Pre_vitals'])){
        $Pre_bp = mysqli_real_escape_string($conn,  $_POST["Pre_bp"]);
        $Pre_bp_time = mysqli_real_escape_string($conn,  $_POST["Pre_bp_time"]);
        $Pre_PR = mysqli_real_escape_string($conn,  $_POST["Pre_PR"]);
        $Pre_PR_time = mysqli_real_escape_string($conn,  $_POST["Pre_PR_time"]);
        $Pre_SPO2 = mysqli_real_escape_string($conn,  $_POST["Pre_SPO2"]);
        $Pre_SPO2_time = mysqli_real_escape_string($conn,  $_POST["Pre_SPO2_time"]);      
        $Post_RR = mysqli_real_escape_string($conn,  $_POST["Post_RR"]);
        $Post_RR_time = mysqli_real_escape_string($conn,  $_POST["Post_RR_time"]);
        $Pre_SPO2 = mysqli_real_escape_string($conn,  $_POST["Pre_SPO2"]);
        $Pre_RR = mysqli_real_escape_string($conn,  $_POST["Pre_RR"]);      
        $Pre_ECG = mysqli_real_escape_string($conn,  $_POST["Pre_ECG"]);
        $Pre_ECG_time = mysqli_real_escape_string($conn,  $_POST["Pre_ECG_time"]);
        $Pre_SPO2 = mysqli_real_escape_string($conn,  $_POST["Pre_SPO2"]);
        $Pre_vitals_remarks = mysqli_real_escape_string($conn,  $_POST["Pre_vitals_remarks"]); 
        $Pre_RR_time = mysqli_real_escape_string($conn, $_POST['Pre_RR_time']);     
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $renalto_medication = mysqli_query($conn, "INSERT INTO Pre_post_vitals ( Pre_bp, Pre_bp_time, Pre_PR,Pre_PR_time, Pre_SPO2,Pre_SPO2_time, Pre_RR,Pre_RR_time,Pre_ECG,Pre_ECG_time,Pre_vitals_remarks, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$Pre_bp', '$Pre_bp_time', '$Pre_PR', '$Pre_PR_time', '$Pre_SPO2','$Pre_SPO2_time',
         '$Pre_RR', '$Pre_RR_time','$Pre_ECG','$Pre_ECG_time',  '$Pre_vitals_remarks','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$renalto_medication){
            die("Couldn't insert data vitals ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>
        ";
        }
    }

    //===========Post vitals ====================
    if(isset($_POST['Post_vitals'])){

        $Vitals_ID = mysqli_real_escape_string($conn, $_POST['Vitals_ID']);
        $Post_bp = mysqli_real_escape_string($conn,  $_POST["Post_bp"]);
        $Post_bp_time = mysqli_real_escape_string($conn,  $_POST["Post_bp_time"]);
        $Post_PR = mysqli_real_escape_string($conn,  $_POST["Post_PR"]);
        $Post_PR_time = mysqli_real_escape_string($conn,  $_POST["Post_PR_time"]);
        $Post_SPO2 = mysqli_real_escape_string($conn,  $_POST["Post_SPO2"]);
        $Post_SPO2_time = mysqli_real_escape_string($conn,  $_POST["Post_SPO2_time"]); 
        $Post_RR_time = mysqli_real_escape_string($conn,  $_POST["Post_RR_time"]);
        $Post_RR = mysqli_real_escape_string($conn,  $_POST["Post_RR"]);      
        $Post_ECG = mysqli_real_escape_string($conn,  $_POST["Post_ECG"]);
        $Post_ECG_time = mysqli_real_escape_string($conn,  $_POST["Post_ECG_time"]);
        $Post_vitals_remarks = mysqli_real_escape_string($conn,  $_POST["Post_vitals_remarks"]); 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
        
        $update_post_vitals = mysqli_query($conn, "UPDATE Pre_post_vitals SET Post_bp='$Post_bp', Post_bp_time='$Post_bp_time', Post_PR='$Post_PR', Post_PR_time='$Post_PR_time',Post_SPO2='$Post_SPO2',Post_SPO2_time='$Post_SPO2_time', Post_RR_time='$Post_RR_time' ,Post_RR='$Post_RR',  Post_ECG='$Post_ECG', Post_ECG_time='$Post_ECG_time',Post_vitals_remarks='$Post_vitals_remarks', Updated_by='$Employee_ID', Updated_at =NOW()  WHERE  Vitals_ID='$Vitals_ID' ") or die(mysqli_error($conn));

        if(!$update_post_vitals){
            die("Couldn't update vitals ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>
        ";
        }

    }
    //============= cannulation =============
    if(isset($_POST['save_cannulation'])){
        $technic = mysqli_real_escape_string($conn,  $_POST["technic"]);
        $Respiration = mysqli_real_escape_string($conn,  $_POST["Respiration"]);
        $B_circle = mysqli_real_escape_string($conn,  $_POST["B_circle"]);
        $intubation =  implode(",",  $_POST["intubation"]);
        // $intubation =implode(",",$intubations);
        $Cannulation_on = mysqli_real_escape_string($conn,  $_POST["Cannulation_on"]);
        $view_grade = mysqli_real_escape_string($conn,  $_POST["view_grade"]);      
        $size = mysqli_real_escape_string($conn,  $_POST["size"]);
        $Depth = mysqli_real_escape_string($conn,  $_POST["Depth"]);
        $Blade = mysqli_real_escape_string($conn,  $_POST["Blade"]);
        $G_cannulation = mysqli_real_escape_string($conn,  $_POST["G_cannulation"]);      
        $Pre_ECG = mysqli_real_escape_string($conn,  $_POST["Pre_ECG"]);
       $Cannulation_side = mysqli_real_escape_string($conn,  $_POST["Cannulation_side"]);
       $Cent_L = mysqli_real_escape_string($conn,  $_POST["Cent_L"]);
        $Cannulation_No = mysqli_real_escape_string($conn,  $_POST["Cannulation_No"]);  
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
            $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_cannulation_technic_intubation ( technic, Respiration, B_circle,intubation, view_grade, size,Depth,Blade,G_cannulation,Cannulation_No,Cannulation_on,Cannulation_side,Cent_L, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$technic', '$Respiration', '$B_circle', '$intubation', '$view_grade',
         '$size', '$Depth','$Blade','$G_cannulation',  '$Cannulation_No','$Cannulation_on','$Cannulation_side','$Cent_L','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$renalto_medication){
            die("Couldn't insert data vitals ".mysqli_error($conn));
        } else{
            echo "
            <script>
                alert('Saved Successfully');
                document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
            </script>
        ";
        }
    }
    //================Vent data=============
    if(isset($_POST['vent'])){
        $Mode = mysqli_real_escape_string($conn, $_POST['Mode']);
        $V_1_I_E = mysqli_real_escape_string($conn, $_POST['V_1_I_E']);
        $RR = $_POST['RR'];
        $F1o2_RR = mysqli_real_escape_string($conn, $_POST['F1o2_RR']);
        $Air_02 = mysqli_real_escape_string($conn, $_POST['Air_02']);
        $Pressure_control_pc = mysqli_real_escape_string($conn, $_POST['Pressure_control_pc']);
        $peep = mysqli_real_escape_string($conn, $_POST['peep']);
        $Pressure_limit = mysqli_real_escape_string($conn, $_POST['Pressure_limit']);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
           
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
    }
    
    $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_vent ( Mode, V_1_I_E,RR, F1o2_RR,Air_02, Pressure_control_pc, peep,Pressure_limit, anasthesia_record_id, Registration_ID, Employee_ID) 
    VALUES ( '$Mode', '$V_1_I_E','$RR', '$F1o2_RR', '$Air_02','$Pressure_control_pc','$peep','$Pressure_limit', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
    if(!$renalto_medication){
        die("Couldn't insert Vent data  ".mysqli_error($conn));
    } else{
        echo "
       saved successful;
    ";
    }

    }
    //=====================End Vent =============
    //investigation post
    if(isset($_POST['Investigation'])){

        $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $biochemistry_ca = mysqli_real_escape_string($conn, $_POST['biochemistry_ca']);
        $biochemistry_cr = mysqli_real_escape_string($conn, $_POST['biochemistry_cr']);
        $biochemistry_urea = mysqli_real_escape_string($conn, $_POST['biochemistry_urea']);
        $biochemistry_gl = mysqli_real_escape_string($conn, $_POST['biochemistry_gl']);
        $biochemistry_k = mysqli_real_escape_string($conn, $_POST['biochemistry_k']);
        $biochemistry_na = mysqli_real_escape_string($conn, $_POST['biochemistry_na']);
        $biochemistry_cl = mysqli_real_escape_string($conn, $_POST['biochemistry_cl']);
        $fbp_hb = mysqli_real_escape_string($conn, $_POST['fbp_hb']);
        $fbp_hct = mysqli_real_escape_string($conn, $_POST['fbp_hct']);
        $fbp_platelets = mysqli_real_escape_string($conn, $_POST['fbp_platelets']);
        $fbp_wbc = mysqli_real_escape_string($conn, $_POST['fbp_wbc']);
        $inr_pt = mysqli_real_escape_string($conn, $_POST['inr_pt']);
        $inr_ptt = mysqli_real_escape_string($conn, $_POST['inr_ptt']);
        $inr_fibrinogen = mysqli_real_escape_string($conn, $_POST['inr_fibrinogen']);
        $inr_bleeding_time = mysqli_real_escape_string($conn, $_POST['inr_bleeding_time']);
        $lft = mysqli_real_escape_string($conn, $_POST['lft']);
        $other_hormones = mysqli_real_escape_string($conn, $_POST['other_hormones']);
        $blood_gas_sao2 = mysqli_real_escape_string($conn, $_POST['blood_gas_sao2']);
        $blood_gas_be = mysqli_real_escape_string($conn, $_POST['blood_gas_be']);
        $blood_gas_bic = mysqli_real_escape_string($conn, $_POST['blood_gas_bic']);
        $blood_gas_pco2 = mysqli_real_escape_string($conn, $_POST['blood_gas_pco2']); 
        $blood_gas_ph = mysqli_real_escape_string($conn, $_POST['blood_gas_ph']);
        $cxr_findings = mysqli_real_escape_string($conn, $_POST['cxr_findings']);
        $ecg_findings = mysqli_real_escape_string($conn, $_POST['ecg_findings']);
        $echo_findings = mysqli_real_escape_string($conn, $_POST['echo_findings']);
        $ct_scan_findings = mysqli_real_escape_string($conn, $_POST['ct_scan_findings']); 
        $MRI = $_POST['MRI'];
        
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
         //select anesthesia record id
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
            }
        $investigation = mysqli_query($conn, "INSERT INTO tbl_anasthesia_investigation(blood_group,MRI, biochemistry_ca, biochemistry_cr, biochemistry_urea, biochemistry_gl, biochemistry_k, biochemistry_na, biochemistry_cl, fbp_hb, fbp_hct, fbp_platelets, fbp_wbc, inr_pt, inr_ptt, inr_fibrinogen, inr_bleeding_time, lft, other_hormones, blood_gas_sao2, blood_gas_be, blood_gas_bic, blood_gas_pco2, blood_gas_ph, cxr_findings, ecg_findings, echo_findings, ct_scan_findings,  anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ('$blood_group','$MRI','$biochemistry_ca','$biochemistry_cr','$biochemistry_urea','$biochemistry_gl', '$biochemistry_k', '$biochemistry_na', '$biochemistry_cl', '$fbp_hb', '$fbp_hct', '$fbp_platelets', '$fbp_wbc','$inr_pt','$inr_ptt','$inr_fibrinogen','$inr_bleeding_time','$lft','$other_hormones','$blood_gas_sao2','$blood_gas_be', '$blood_gas_bic', '$blood_gas_pco2','$blood_gas_ph', '$cxr_findings','$ecg_findings','$echo_findings', '$ct_scan_findings', '$anasthesia_record_id','$Registration_ID','$Employee_ID')");

        if(!$investigation){
            die("Couldn't insert data ".mysqli_error($conn));
        }else{
            echo "
                <script>
                    alert('Saved Successfully');
                    document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
                </script>
            ";
        }
    }

    //asa classification saving checkbox
    if(isset($_POST['asa_classification'])){
        $selected_val="";
       foreach($_POST['asa_classfication'] as $selected){
        $selected_val .=$selected.",";
        }
            $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

            //select anesthesia record id
                $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                if(mysqli_num_rows($anasthesia_record_result)>0){
                    $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                }else{
                    $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                    $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                    $anasthesia_record_id=mysqli_insert_id($conn);
                    
            }
            $gastrointestinal = mysqli_query($conn, "INSERT INTO tbl_anasthesia_asa_classfication (created_at, asa_classfication, 
             anasthesia_record_id, Registration_ID, Employee_ID) 
            VALUES (NOW(),  '$selected_val', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
            if(!$gastrointestinal){
                die("Couldn't insert data ".mysqli_error($conn));
            }else{
                echo "
                    <script>
                        alert('Saved Successfully');
                        document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
                    </script>
                ";
            }
        
    }





    // ================End of anaesthesia query===================
    if(isset($_POST['end_of_anasthesia'])){
        $duration_of_anaesthesia = mysqli_real_escape_string($conn,  $_POST["duration_of_anaesthesia"]);
        $duration_of_operation = mysqli_real_escape_string($conn,  $_POST["duration_of_operation"]);
        $blood_loss = mysqli_real_escape_string($conn,  $_POST["blood_loss"]);
        $total_input = mysqli_real_escape_string($conn,  $_POST["total_input"]);
        $total_output = mysqli_real_escape_string($conn,  $_POST["total_output"]);      
        $fluid_balance = mysqli_real_escape_string($conn,  $_POST["fluid_balance"]);
        $Anaesthesia_notes = mysqli_real_escape_string($conn,  $_POST["Anaesthesia_notes"]);
        $Comments = mysqli_real_escape_string($conn,  $_POST["Comments"]);
        $starting_time = mysqli_real_escape_string($conn,  $_POST["starting_time"]); 
        $finishing_time = mysqli_real_escape_string($conn,  $_POST["finishing_time"]);
        $opstarting_time = mysqli_real_escape_string($conn, $_POST['opstarting_time']);
        $opfinishing_time = mysqli_real_escape_string($conn,  $_POST["opfinishing_time"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        $anasthesia_record_id = $_POST['anasthesia_record_id'];
        $insert_end_of_anaesthesia = mysqli_query($conn, "INSERT INTO tbl_end_of_anaesthesia (  duration_of_anaesthesia, duration_of_operation,blood_loss, total_output, fluid_balance,Anaesthesia_notes,Comments,starting_time,total_input,finishing_time,opstarting_time,opfinishing_time, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$duration_of_anaesthesia', '$duration_of_operation', '$blood_loss', '$total_output','$fluid_balance', '$Anaesthesia_notes','$Comments','$starting_time', '$total_input','$finishing_time','$opstarting_time','$opfinishing_time','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')") or die(mysqli_error($conn));
        if(!$insert_end_of_anaesthesia){
            echo "Failed to save";
        } else{
            echo "Saved successful";          
            
        }
    }

   //ALTER TABLE `tbl_anasthesia_genaral_examination` CHANGE `patient_state` `patient_state` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 

   