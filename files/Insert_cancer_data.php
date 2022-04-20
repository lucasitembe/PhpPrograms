<?php

require_once('includes/connection.php');
    session_start();
    $Employee_ID  = $_SESSION['userinfo']['Employee_ID'];
if (isset($_POST['Registration_ID'])) {
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
} else {
    $Registration_ID = '';
}
if (isset($_POST['HIV_sero_status'])) {
    $HIV_sero_status = mysqli_real_escape_string($conn, $_POST['HIV_sero_status']);
} else {
    $HIV_sero_status = '';
}

if (isset($_POST['Date_diagnosis_HIV'])) {
    $Date_diagnosis_HIV = mysqli_real_escape_string($conn, $_POST['Date_diagnosis_HIV']);
} else {
    $Date_diagnosis_HIV = '';
}

if (isset($_POST['Chronic_disease'])) {
    $Chronic_disease = mysqli_real_escape_string($conn, $_POST['Chronic_disease']);
} else {
    $Chronic_disease = '';
}

if (isset($_POST['Date_of_Incidence'])) {
    $Date_of_Incidence = mysqli_real_escape_string($conn, $_POST['Date_of_Incidence']);
} else {
    $Date_of_Incidence = '';
}
if (isset($_POST['symptoms_date'])) {
    $symptoms_date = mysqli_real_escape_string($conn, $_POST['symptoms_date']);
} else {
    $symptoms_date = '';
}
if (isset($_POST['Basis_of_diagnosis'])) {
    $Basis_of_diagnosis = mysqli_real_escape_string($conn, $_POST['Basis_of_diagnosis']);
} else {
    $Basis_of_diagnosis = '';
}
if (isset($_POST['Clinical_Only'])) {
    $Clinical_Only = mysqli_real_escape_string($conn, $_POST['Clinical_Only']);
} else {
    $Clinical_Only = '';
}
if (isset($_POST['Cytology_Hematology'])) {
    $Cytology_Hematology = mysqli_real_escape_string($conn, $_POST['Cytology_Hematology']);
} else {
    $Cytology_Hematology = '';
}
if (isset($_POST['FNAC'])) {
    $FNAC = mysqli_real_escape_string($conn, $_POST['FNAC']);
} else {
    $FNAC = '';
}
if (isset($_POST['Clinical_Investigation'])) {
    $Clinical_Investigation = mysqli_real_escape_string($conn, $_POST['Clinical_Investigation']);
} else {
    $Clinical_Investigation = '';
}
if (isset($_POST['Other'])) {
    $Other = mysqli_real_escape_string($conn, $_POST['Other']);
} else {
    $Other= '';
}
if (isset($_POST['ER_Status'])) {
    $ER_Status= mysqli_real_escape_string($conn, $_POST['ER_Status']);
} else {
    $ER_Status= '';
}

if (isset($_POST['PR_Status'])) {
    $PR_Status = mysqli_real_escape_string($conn, $_POST['PR_Status']);
} else {
    $PR_Status= '';
}

if (isset($_POST['Her_Status'])) {
    $Her_Status= mysqli_real_escape_string($conn, $_POST['Her_Status']);
} else {
    $Her_Status= '';
}

if (isset($_POST['Primary_site'])) {
    $Primary_site= mysqli_real_escape_string($conn, $_POST['Primary_site']);
} else {
    $Primary_site= '';
}
if (isset($_POST['Secondary_Site'])) {
    $Secondary_Site= mysqli_real_escape_string($conn, $_POST['Secondary_Site']);
} else {
    $Secondary_Site= '';
}
if (isset($_POST['Morphology'])) {
    $Morphology= mysqli_real_escape_string($conn, $_POST['Morphology']);
} else {
    $Morphology= '';
}
if (isset($_POST['M_code'])) {
    $M_code= mysqli_real_escape_string($conn, $_POST['M_code']);
} else {
    $M_code= '';
}
if (isset($_POST['Stage_T'])) {
    $Stage_T= mysqli_real_escape_string($conn, $_POST['Stage_T']);
} else {
    $Stage_T= '';
}
if (isset($_POST['Stage_N'])) {
    $Stage_N= mysqli_real_escape_string($conn, $_POST['Stage_N']);
} else {
    $Stage_N= '';
}
if (isset($_POST['Stage_M'])) {
    $Stage_M= mysqli_real_escape_string($conn, $_POST['Stage_M']);
} else {
    $Stage_M= '';
}
if (isset($_POST['Gleason_score'])) {
    $Gleason_score= mysqli_real_escape_string($conn, $_POST['SGleason_score']);
} else {
    $Gleason_score= '';
}
if (isset($_POST['Baseline_PSA'])) {
    $Baseline_PSA= mysqli_real_escape_string($conn, $_POST['Baseline_PSA']);
} else {
    $Baseline_PSA= '';
}
if (isset($_POST['Metastasis'])) {
    $Metastasis= mysqli_real_escape_string($conn, $_POST['Metastasis']);
} else {
    $Metastasis= '';
}

if (isset($_POST['Non_Metastasis'])) {
    $Non_Metastasis= mysqli_real_escape_string($conn, $_POST['Non_Metastasis']);
} else {
    $Non_Metastasis= '';
}

if (isset($_POST['Other_Staging'])) {
    $Other_Staging= mysqli_real_escape_string($conn, $_POST['Other_Staging']);
} else {
    $Other_Staging= '';
}
if (isset($_POST['A_Regimen'])) {
    $A_Regimen= mysqli_real_escape_string($conn, $_POST['A_Regimen']);
} else {
    $A_Regimen= '';
}
if (isset($_POST['A_Regimen_Start_Date'])) {
    $A_Regimen_Start_Date= mysqli_real_escape_string($conn, $_POST['A_Regimen_Start_Date']);
} else {
    $A_Regimen_Start_Date= '';
}
if (isset($_POST['A_Regimen_End_Date'])) {
    $A_Regimen_End_Date= mysqli_real_escape_string($conn, $_POST['A_Regimen_End_Date']);
} else {
    $A_Regimen_End_Date= '';
}

if (isset($_POST['A_Other_Condition'])) {
    $A_Other_Condition= mysqli_real_escape_string($conn, $_POST['A_Other_Condition']);
} else {
    $A_Other_Condition= '';
}
if (isset($_POST['B_Regimen'])) {
    $B_Regimen= mysqli_real_escape_string($conn, $_POST['B_Regimen']);
} else {
    $B_Regimen= '';
}
if (isset($_POST['B_Regimen_Start_Date'])) {
    $B_Regimen_Start_Date= mysqli_real_escape_string($conn, $_POST['B_Regimen_Start_Date']);
} else {
    $B_Regimen_Start_Date= '';
}
if (isset($_POST['B_Regimen_End_Date'])) {
    $B_Regimen_End_Date= mysqli_real_escape_string($conn, $_POST['B_Regimen_End_Date']);
} else {
    $B_Regimen_End_Date= '';
}
if (isset($_POST['A_condition'])) {
    $A_condition= mysqli_real_escape_string($conn, $_POST['A_condition']);
} else {
    $A_condition= '';
}
if (isset($_POST['B_condition'])) {
    $B_condition= mysqli_real_escape_string($conn, $_POST['B_condition']);
} else {
    $B_condition= '';
}

if (isset($_POST['B_Other_Condition'])) {
    $B_Other_Condition= mysqli_real_escape_string($conn,$_POST['B_Other_Condition']);
} else {
    $B_Other_Condition= '';
}
if (isset($_POST['C_Any_other_result'])) {
    $C_Any_other_result= mysqli_real_escape_string($conn,$_POST['C_Any_other_result']);
} else {
    $C_Any_other_result= '';
}

if (isset($_POST['General_progress_of_desease'])) {
    $General_progress_of_desease= mysqli_real_escape_string($conn,$_POST['General_progress_of_desease']);
} else {
    $General_progress_of_desease= '';
}


if (isset($_POST['Institution'])) {
    $Institution= mysqli_real_escape_string($conn,$_POST['Institution']);
} else {
    $Institution= '';
}
if (isset($_POST['Ward_Unit'])) {
    $Ward_Unitn= mysqli_real_escape_string($conn,$_POST['Ward_Unit']);
} else {
    $Ward_Unit= '';
}
if (isset($_POST['Lab_number'])) {
    $Lab_number= mysqli_real_escape_string($conn,$_POST['Lab_number']);
} else {
    $Lab_number= '';
}
if (isset($_POST['Adverse_events'])) {
    $Adverse_events= mysqli_real_escape_string($conn,$_POST['Adverse_events']);
} else {
    $Adverse_events= '';
}
if (isset($_POST['supportive_care'])) {
    $supportive_care= mysqli_real_escape_string($conn,$_POST['supportive_care']);
} else {
    $supportive_care= '';
}
if (isset($_POST['one_Line'])) {
    $one_Line= mysqli_real_escape_string($conn,$_POST['one_Line']);
} else {
    $one_Line= '';
}
if (isset($_POST['two_Line'])) {
    $two_Line= mysqli_real_escape_string($conn,$_POST['two_Line']);
} else {
    $two_Line= '';
}
if (isset($_POST['three_Line'])) {
    $three_Line= mysqli_real_escape_string($conn,$_POST['three_Line']);
} else {
    $three_Line= '';
}
if (isset($_POST['Date_at_last_Contact'])) {
    $Date_at_last_Contact= mysqli_real_escape_string($conn,$_POST['Date_at_last_Contact']);
} else {
    $Date_at_last_Contact= '';
}

if (isset($_POST['last_status_contact'])) {
    $last_status_contact= mysqli_real_escape_string($conn,$_POST['last_status_contact']);
} else {
    $last_status_contact= '';
}

if (isset($_POST['This_cancer'])) {
    $This_cancer= mysqli_real_escape_string($conn,$_POST['This_cancer']);
} else {
    $This_cancer= '';
}
if (isset($_POST['cause_of_death'])) {
    $cause_of_death= mysqli_real_escape_string($conn,$_POST['cause_of_death']);
} else {
    $cause_of_death= '';
}
if (isset($_POST['Unknown_causes'])) {
    $Unknown_causes= mysqli_real_escape_string($conn,$_POST['Unknown_causes']);
} else {
    $Unknown_causes= '';
}
if (isset($_POST['Other_causes'])) {
    $Other_causes= mysqli_real_escape_string($conn,$_POST['Other_causes']);
} else {
    $Other_causes= '';
}
if (isset($_POST['Intuition'])) {
    $Intuition= mysqli_real_escape_string($conn,$_POST['Intuition']);
} else {
    $Intuition= '';
}

if (isset($_POST['Surgery'])) {
    $Surgery= mysqli_real_escape_string($conn,$_POST['Surgery']);
} else {
    $Surgery= '';
}


if (isset($_POST['Surgery_Date'])) {
    $Surgery_Date= mysqli_real_escape_string($conn,$_POST['Surgery_Date']);
} else {
    $Surgery_Date= '';
}
if (isset($_POST['Radiotherapy'])) {
    $Radiotherapy= mysqli_real_escape_string($conn,$_POST['Radiotherapy']);
} else {
    $Radiotherapy= '';
}

if (isset($_POST['Radiotherapy_Date'])) {
    $Radiotherapy_Date= mysqli_real_escape_string($conn,$_POST['Radiotherapy_Date']);
} else {
    $Radiotherapy_Date= '';
}
if (isset($_POST['Immunotherapy'])) {
    $Immunotherapy= mysqli_real_escape_string($conn,$_POST['Immunotherapy']);
} else {
    $Immunotherapy= '';
}

if (isset($_POST['Immunotherapy_Date'])) {
    $Immunotherapy_Date= mysqli_real_escape_string($conn,$_POST['Immunotherapy_Date']);
} else {
    $Immunotherapy_Date= '';
}

if (isset($_POST['Targeted_therapy'])) {
    $Targeted_therapy= mysqli_real_escape_string($conn,$_POST['Targeted_therapy']);
} else {
    $Targeted_therapy= '';
}
if (isset($_POST['Targeted_therapy_Date'])) {
    $Targeted_therapy_Date= mysqli_real_escape_string($conn,$_POST['Targeted_therapy_Date']);
} else {
    $Targeted_therapy_Date= '';
}
if (isset($_POST['Chemotherapy'])) {
    $Chemotherapy= mysqli_real_escape_string($conn,$_POST['Chemotherapy']);
} else {
    $Chemotherapy= '';
}

if (isset($_POST['Chemotherapy_Date'])) {
    $Chemotherapy_Date= mysqli_real_escape_string($conn,$_POST['Chemotherapy_Date']);
} else {
    $Chemotherapy_Date= '';
}
if (isset($_POST['Hormone_therapy'])) {
    $Hormone_therapy= mysqli_real_escape_string($conn,$_POST['Hormone_therapy']);
} else {
    $Hormone_therapy= '';
}

if (isset($_POST['Hormone_therapy_Date'])) {
    $Hormone_therapy_Date= mysqli_real_escape_string($conn,$_POST['Hormone_therapy_Date']);
} else {
    $Hormone_therapy_Date= '';
}
if (isset($_POST['Chemotherapy_Date'])) {
    $Chemotherapy_Date= mysqli_real_escape_string($conn,$_POST['Chemotherapy_Date']);
} else {
    $Chemotherapy_Date= '';
}
if (isset($_POST['Chemo'])) {
    $Chemo= mysqli_real_escape_string($conn,$_POST['Chemo']);
} else {
    $Chemo= '';
}

if (isset($_POST['Other_chemo'])) {
   $Other_chemo= mysqli_real_escape_string($conn,$_POST['Other_chemo']);
} else {
    $Other_chemo= '';
}


                // $select_id = mysqli_query($conn, "SELECT  cancer_id FROM  tbl_cancer_registration WHERE Registration_ID='$Registration_ID' AND DATE(date_and_time)=CURDATE()") or die(mysqli_error($conn));
                
                // if((mysqli_num_rows($select_id))>0){
                //     echo "Information already exists ";
                // }else{
                if(mysqli_query($conn,  "INSERT INTO tbl_cancer_registration(Registration_ID,HIV_sero_status,Date_diagnosis_HIV,Chronic_disease,Date_of_Incidence,symptoms_date,Basis_of_diagnosis,Other,ER_Status,PR_Status,Her_Status,Primary_site,Secondary_Site,Morphology,M_code,Stage_T,Stage_N,Stage_M,Gleason_score,Baseline_PSA,Metastasis,Non_Metastasis,Other_Staging,date_and_time,saved_by)VALUES( '$Registration_ID','$HIV_sero_status','$Date_diagnosis_HIV','$Chronic_disease','$Date_of_Incidence','$symptoms_date','$Basis_of_diagnosis','$Other','$ER_Status','$PR_Status','$Her_Status','$Primary_site','$Secondary_Site','$Morphology','$M_code','$Stage_T','$Stage_N','$Stage_M','$Gleason_score','$Baseline_PSA','$Metastasis','$Non_Metastasis','$Other_Staging',NOW(),'$Employee_ID')") or die(mysqli_error($conn))):
                            $cancer_registration_id = mysqli_insert_id($conn);
                    
                              
                            $treat = mysqli_query($conn,"INSERT INTO tbl_cancer_treat(cancer_registration_id,Registration_ID,Intuition,Surgery,Surgery_Date,Radiotherapy,Radiotherapy_Date,Immunotherapy,Immunotherapy_Date,Targeted_therapy,Targeted_therapy_Date,Chemotherapy,Chemotherapy_Date,Hormone_therapy,Hormone_therapy_Date,Chemo,Other_chemo) VALUES('$cancer_registration_id','$Registration_ID','$Intuition','$Surgery','$Surgery_Date','$Radiotherapy','$Radiotherapy_Date','$Immunotherapy','$Immunotherapy_Date','$Targeted_therapy','$Targeted_therapy_Date','$Chemotherapy','$Chemotherapy_Date','$Hormone_therapy','$Hormone_therapy_Date','$Chemo','$Other_chemo')") or  die(mysqli_error($conn));
                            if(!$treat){
                                echo "Treatment not saved";
                            } 
                           
                            $sql_insert_tbl_therapy_regimen= mysqli_query($conn, "INSERT INTO tbl_therapy_regimen(cancer_registration_id,Registration_ID,A_Regimen,A_Regimen_Start_Date,A_Regimen_End_Date,A_condition,A_Other_Condition,B_Regimen,B_Regimen_Start_Date,B_Regimen_End_Date,B_condition,B_Other_Condition,C_Any_other_result,General_progress_of_desease,date_and_time)VALUES('$cancer_registration_id','$Registration_ID','$A_Regimen','$A_Regimen_Start_Date','$A_Regimen_End_Date','$A_condition','$A_Other_Condition','$B_Regimen','$B_Regimen_Start_Date','$B_Regimen_End_Date','$B_condition','$B_Other_Condition','$C_Any_other_result','$General_progress_of_desease',NOW())");
                            
                                if(!$sql_insert_tbl_therapy_regimen){
                                    echo "Therapy regiem coundn't insert";
                                }

                            $sql_insert_tbl_cancer_other_information= mysqli_query($conn,"INSERT INTO tbl_cancer_other_information(cancer_registration_id,Registration_ID,Institution,Ward_Unit,Lab_number,Adverse_events,supportive_care,one_Line,two_Line,three_Line,Date_at_last_Contact,last_status_contact,cause_of_death,Other_causes,date_and_time)VALUES('$cancer_registration_id','$Registration_ID','$Institution','$Ward_Unit','$Lab_number','$Adverse_events','$supportive_care','$one_Line','$two_Line','$three_Line','$Date_at_last_Contact','$last_status_contact','$cause_of_death','$Other_causes',NOW())")or die(mysqli_error($conn));
                                
                                if(!$sql_insert_tbl_cancer_other_information){
                                    echo "Other info didn't save";
                                }
                
                 
                endif;
                echo "Registration completed successful";
          //  }

