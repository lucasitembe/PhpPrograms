<?php
	session_start();
	include("./includes/connection.php");
   
       $outcome_objective = $_POST['outcome_objective'];
       $balance = $_POST['balance'];
       $gait_analysis = $_POST['gait_analysis'];
       $functional_analysis = $_POST['functional_analysis'];
       $transfers_stairs = $_POST['transfers_stairs'];
       $cordination_ll = $_POST['cordination_ll'];
       $cordination_ul = $_POST['cordination_ul'];
       $proprioception_ll = $_POST['proprioception_ll'];
       $proprioception_ul = $_POST['proprioception_ul'];
       $sensation_l_l = $_POST['sensation_l_l'];
       $sensation_u_l = $_POST['sensation_u_l'];
       $feet_footwear = $_POST['feet_footwear'];
       $trunk_kyphosis = $_POST['trunk_kyphosis'];
       $trunk_impared = $_POST['trunk_impared'];
       $trunk_functional = $_POST['trunk_functional'];
       $lower_dizziness = $_POST['lower_dizziness'];
       $power_2 = $_POST['power_2'];
       $defomity_2 = $_POST['defomity_2'];
       $power = $_POST['power'];
       $defomity = $_POST['defomity'];
       $lower_impaired_2 = $_POST['lower_impaired_2'];
       $lower_functional_2 = $_POST['lower_functional_2'];
       $lower_impaired = $_POST['lower_impaired'];
       $lower_functional = $_POST['lower_functional'];
       $upper_impaired = $_POST['upper_impaired'];
       $upper_functional = $_POST['upper_functional'];
       $relevant_clinical_3 = $_POST['relevant_clinical_3'];
       $relevant_clinical_2 = $_POST['relevant_clinical_2'];
       $relevant_clinical = $_POST['relevant_clinical'];
       $haemodynamics = $_POST['haemodynamics'];
       $respiratory = $_POST['respiratory'];
       $contraindications = $_POST['contraindications'];
       $Hearing_impared = $_POST['Hearing_impared'];
       $Vision_impared = $_POST['Vision_impared'];
       $Speech_impared = $_POST['Speech_impared'];
       $cognition_impared = $_POST['cognition_impared'];
       $Hearing_infact = $_POST['Hearing_infact'];
       $Vision_infact = $_POST['Vision_infact'];
       $Speech_infact = $_POST['Speech_infact'];
       $Cognition_infact = $_POST['Cognition_infact'];
       $patient_subject = $_POST['patient_subject'];
       $other_investigation = $_POST['other_investigation'];
       $chest_x_ray = $_POST['chest_x_ray'];
       $specimen_na = $_POST['specimen_na'];
       $specimen_no = $_POST['specimen_no'];
       $specimen_yes = $_POST['specimen_yes'];
       $patient_problems = $_POST['patient_problems'];
       $patient_history = $_POST['patient_history'];
       $consent_interest = $_POST['consent_interest'];
       $consent_treatment = $_POST['consent_treatment'];
       $Registration_ID = $_POST['Registration_ID'];
       
       
                                $mysqli_check_erdely_data=mysqli_query($conn,"SELECT Erdely_ID FROM tbl_erdely_case_assessment WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_erdely_data) > 0){
       
               $Respiratory_ID= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Erdely_ID FROM tbl_erdely_case_assessment WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()"))['Erdely_ID'];
               
                echo $sql_save_data = mysqli_query($conn,"UPDATE tbl_erdely_case_assessment SET Registration_ID='$Registration_ID',outcome_objective='$outcome_objective',balance='$balance',gait_analysis='$gait_analysis',functional_analysis='$functional_analysis',transfers_stairs='$transfers_stairs',cordination_ll='$cordination_ll',cordination_ul='$cordination_ul',proprioception_ll='$proprioception_ll',proprioception_ul='$proprioception_ul',sensation_l_l='$sensation_l_l',sensation_u_l='$sensation_u_l',feet_footwear='$feet_footwear',trunk_kyphosis='$trunk_kyphosis',trunk_impared='$trunk_impared',trunk_functional='$trunk_functional',lower_dizziness='$lower_dizziness',power_2='$power_2',defomity_2='$defomity_2',power='$power',defomity='$defomity',lower_impaired_2='$lower_impaired_2',lower_functional_2='$lower_functional_2',lower_impaired='$lower_impaired',lower_functional='$lower_functional',upper_impaired='$upper_impaired',"
               . "                             upper_functional='$upper_functional',relevant_clinical_3='$relevant_clinical_3',relevant_clinical_2='$relevant_clinical_2',relevant_clinical='$relevant_clinical',haemodynamics='$haemodynamics',respiratory='$respiratory',contraindications='$contraindications',Hearing_impared='$Hearing_impared',Vision_impared='$Vision_impared',Speech_impared='$Speech_impared',cognition_impared='$cognition_impared',Hearing_infact='$Hearing_infact',Vision_infact='$Vision_infact',Speech_infact='$Speech_infact',Cognition_infact='$Cognition_infact',patient_subject='$patient_subject',other_investigation='$other_investigation',chest_x_ray='$chest_x_ray',specimen_na='$specimen_na',specimen_no='$specimen_no',specimen_yes='$specimen_yes',patient_problems='$patient_problems',patient_history='$patient_history',consent_interest='$consent_interest',consent_treatment='$consent_treatment',date_time_transaction=NOW()") or die(mysqli_error($conn));
                
   }else{ 
       
       $mysql_save_elderly_care = mysqli_query($conn,"INSERT INTO tbl_erdely_case_assessment(Registration_ID,outcome_objective,balance,gait_analysis,functional_analysis,transfers_stairs,cordination_ll,cordination_ul,proprioception_ll,proprioception_ul,sensation_l_l,sensation_u_l,feet_footwear,trunk_kyphosis,trunk_impared,trunk_functional,lower_dizziness,power_2,defomity_2,power,defomity,lower_impaired_2,lower_functional_2,lower_impaired,lower_functional,upper_impaired,"
               . "                             upper_functional,relevant_clinical_3,relevant_clinical_2,relevant_clinical,haemodynamics,respiratory,contraindications,Hearing_impared,Vision_impared,Speech_impared,cognition_impared,Hearing_infact,Vision_infact,Speech_infact,Cognition_infact,patient_subject,other_investigation,chest_x_ray,specimen_na,specimen_no,specimen_yes,patient_problems,patient_history,consent_interest,consent_treatment,date_time_transaction)VALUES('$Registration_ID','$outcome_objective','$balance','$gait_analysis','$functional_analysis','$transfers_stairs','$cordination_ll','$cordination_ul','$proprioception_ll','$proprioception_ul','$sensation_l_l','$sensation_u_l','$feet_footwear','$trunk_kyphosis','$trunk_impared','$trunk_functional','$lower_dizziness','$power_2','$defomity_2','$power','$defomity','$lower_impaired_2','$lower_functional_2','$lower_impaired','$lower_functional','$upper_impaired',"
               . "                             '$upper_functional','$relevant_clinical_3','$relevant_clinical_2','$relevant_clinical','$haemodynamics','$respiratory','$contraindications','$Hearing_impared','$Vision_impared','$Speech_impared','$cognition_impared','$Hearing_infact','$Vision_infact','$Speech_infact','$Cognition_infact','$patient_subject','$other_investigation','$chest_x_ray','$specimen_na','$specimen_no','$specimen_yes','$patient_problems','$patient_history','$consent_interest','$consent_treatment',NOW())") or die(mysqli_error($conn));
     
   }

             if($mysql_save_elderly_care){
                 echo "saved successfully";
             }else{
                 echo "not saved successfully";
             }