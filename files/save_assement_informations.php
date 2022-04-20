<?php 
session_start();
include("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID = $_POST['Registration_ID'];
    $therapist = $_SESSION['userinfo']['Employee_ID'];

    //value
    $Chief_complain =mysqli_real_escape_string($conn, $_POST['Chief_complain']);
    $Procedure_remarks = mysqli_real_escape_string($conn, $_POST['Procedure_remarks']);
    $current_presentation = mysqli_real_escape_string($conn,$_POST['current_presentation']);
    $medical_history = mysqli_real_escape_string($conn,$_POST['medical_history']);
    $social_history = mysqli_real_escape_string($conn,$_POST['social_history']);
    $productivity = mysqli_real_escape_string($conn,$_POST['productivity']);
    $bed_mobility = mysqli_real_escape_string($conn,$_POST['bed_mobility']);
    $sitting_balance = mysqli_real_escape_string($conn,$_POST['sitting_balance']);
    $transfer_skills = mysqli_real_escape_string($conn,$_POST['transfer_skills']);
    $standing = mysqli_real_escape_string($conn,$_POST['standing']);
    $mobility = mysqli_real_escape_string($conn,$_POST['mobility']);
    $feeding = mysqli_real_escape_string($conn,$_POST['feeding']);
    $groming_washing = mysqli_real_escape_string($conn,$_POST['groming_washing']);
    $toileting = mysqli_real_escape_string($conn,$_POST['toileting']);
    $dressing = mysqli_real_escape_string($conn,$_POST['dressing']);

    $shoulder_abduction = mysqli_real_escape_string($conn,$_POST['shoulder_abduction']);
    $shoulder_notes = mysqli_real_escape_string($conn,$_POST['shoulder_notes']);
    $shoulder_flexion = mysqli_real_escape_string($conn,$_POST['shoulder_flexion']);
    $shoulder_extension = mysqli_real_escape_string($conn,$_POST['shoulder_extension']);
    $internal_rotaion = mysqli_real_escape_string($conn,$_POST['internal_rotaion']);
    $external_abduction = mysqli_real_escape_string($conn,$_POST['external_abduction']);
    $elbow_flexion = mysqli_real_escape_string($conn,$_POST['elbow_flexion']);
    $elbow_extension = mysqli_real_escape_string($conn,$_POST['elbow_extension']);
    $pronation = mysqli_real_escape_string($conn,$_POST['pronation']);
    $supination = mysqli_real_escape_string($conn,$_POST['supination']);
    $wrist_extension = mysqli_real_escape_string($conn,$_POST['wrist_extension']);
    $wrist_flexion = mysqli_real_escape_string($conn,$_POST['wrist_flexion']);
    $finger_flexion = mysqli_real_escape_string($conn,$_POST['finger_flexion']);
    $finger_extension = mysqli_real_escape_string($conn,$_POST['finger_extension']);
    $finger_opposition = mysqli_real_escape_string($conn,$_POST['finger_opposition']);
    $hip_abduction = mysqli_real_escape_string($conn,$_POST['hip_abduction']);
    $hip_flexion = mysqli_real_escape_string($conn,$_POST['hip_flexion']);
    $hip_extension = mysqli_real_escape_string($conn,$_POST['hip_extension']);
    $knee_extension = mysqli_real_escape_string($conn,$_POST['knee_extension']);
    $nkee_flexion = mysqli_real_escape_string($conn,$_POST['nkee_flexion']);
    $ankle_dorsiflexion = mysqli_real_escape_string($conn,$_POST['ankle_dorsiflexion']);
    $ankle_plantar_flexion = mysqli_real_escape_string($conn,$_POST['ankle_plantar_flexion']);
    $inversion = mysqli_real_escape_string($conn,$_POST['inversion']);
    $eversion = mysqli_real_escape_string($conn,$_POST['eversion']);
    $hip_notes = mysqli_real_escape_string($conn,$_POST['hip_notes']);
    $knee_notes = mysqli_real_escape_string($conn,$_POST['knee_notes']);
    $ankle_notes = mysqli_real_escape_string($conn,$_POST['ankle_notes']);
    $elbow_notes = mysqli_real_escape_string($conn,$_POST['elbow_notes']);
    $pronation_notes = mysqli_real_escape_string($conn,$_POST['pronation_notes']);
    $wrist_notes = mysqli_real_escape_string($conn,$_POST['wrist_notes']);
    $finger_notes = mysqli_real_escape_string($conn,$_POST['finger_notes']);
    $vision_hearing = mysqli_real_escape_string($conn,$_POST['vision_hearing']);
    $ue_touch = mysqli_real_escape_string($conn,$_POST['ue_touch']);
    $ue_pain = mysqli_real_escape_string($conn,$_POST['ue_pain']);
    $ue_temparature = mysqli_real_escape_string($conn,$_POST['ue_temparature']);
    $ue_proprioception = mysqli_real_escape_string($conn,$_POST['ue_proprioception']);
    $le_touch = mysqli_real_escape_string($conn,$_POST['le_touch']);
    $le_pain = mysqli_real_escape_string($conn,$_POST['le_pain']);
    $le_temparature = mysqli_real_escape_string($conn,$_POST['le_temparature']);
    $le_proprioception = mysqli_real_escape_string($conn,$_POST['le_proprioception']);
    $memory = mysqli_real_escape_string($conn,$_POST['memory']);
    $attention = mysqli_real_escape_string($conn,$_POST['attention']);
    $problem_solving = mysqli_real_escape_string($conn,$_POST['problem_solving']);
    $insight = mysqli_real_escape_string($conn,$_POST['insight']);
    $communication = mysqli_real_escape_string($conn,$_POST['communication']);
    $psycho_emotion_changes = mysqli_real_escape_string($conn,$_POST['psycho_emotion_changes']);
    $goals = mysqli_real_escape_string($conn,$_POST['goals']);
    $perfomance_context = mysqli_real_escape_string($conn,$_POST['perfomance_context']);
    $leisures = mysqli_real_escape_string($conn,$_POST['leisures']);
    $progress = mysqli_real_escape_string($conn,$_POST['progress']);

    $insert_sql = "INSERT INTO `tbl_adult_assement`(`Registration_ID`, `therapist`, `Procedure_remarks`,`Chief_complain`,`current_presentation`, `medical_history`, 
    `social_history`, `productivity`, `bed_mobility`, `siting_baance`, `transfer_skills`, `standing`, `mobility`,
    `feeding`, `groming_washing`, `toileting`, `dressing`, `shoulder_abduction`, `shoulder_flexion`, `sholder_extension`,
    `internal_rotation`, `external_rotation`, `elbow_flexion`, `elbow_extension`, `pronation`, `supination`, `wrist_extension`,
    `wrist_flexion`, `finger_flexion`, `finger_extension`, `finger_opposition`, `hip_abduction`, `hip_flexion`, `hip_extesnion`,
    `knee_extension`, `knee_flexion`, `ankle_dorsiflexion`, `ankel_plantar_flexion`, `inversion`, `eversion`, `sholder_notes`, 
    `elbow_notes`, `pronotion_notes`, `wrist_notes`, `finger_notes`, `hip_notets`, `knee_notes`, `ankle_notes`, `vision_and_hearing`,
    `ue_touch`, `ue_pain`, `ue_temp`, `ue_prop`, `le_touch`, `le_pain`, `le_temp`, `le_prop`, `memory`, `attention`, `problem_solving`, 
    `insight`, `communication`, `psycho_emotion`, `goals`,`leasure`,`perfomance_context`,`progress`)
    VALUES ('$Registration_ID','$therapist','$Procedure_remarks','$Chief_complain','$current_presentation ','$medical_history','$social_history','$productivity','$bed_mobility ','$sitting_balance','$transfer_skills','$standing',
    '$mobility','$feeding','$groming_washing','$toileting','$dressing','$shoulder_abduction','$shoulder_flexion','$shoulder_extension','$internal_rotaion','$external_abduction',
    '$elbow_flexion','$elbow_extension','$pronation','$supination','$wrist_extension','$wrist_flexion','$finger_flexion','$finger_extension','$finger_opposition','$hip_abduction',
    '$hip_flexion','$hip_extension','$knee_extension','$nkee_flexion','$ankle_dorsiflexion','$ankle_plantar_flexion','$inversion','$eversion','$shoulder_notes','$elbow_notes',
    '$pronation_notes','$wrist_notes','$finger_notes','$hip_notes','$knee_notes','$ankle_notes','$vision_hearing','$ue_touch','$ue_pain','$ue_temparature',
    '$ue_proprioception','$le_touch','$le_pain','$le_temparature','$le_proprioception','$memory','$attention','$problem_solving','$insight','$communication',
    '$psycho_emotion_changes ','$goals','$leisures','$perfomance_context','$progress')";

        if (mysqli_query($conn, $insert_sql)) {
            $last_id = $conn->insert_id;
            $Registration_ID = $_POST['Registration_ID'];
            $therapist = $_POST['therapist'];
            //select patient details
            $patient_detail = mysqli_query($conn,"SELECT Patient_Name, Registration_ID, Date_of_Birth, Tribe, Region, Email_Address, Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            while($patient_preview_infor_row = mysqli_fetch_assoc($patient_detail)){
                $patient_name = $patient_preview_infor_row['Patient_Name'];
                $patient_number = $patient_preview_infor_row['Registration_ID'];
                $date_of_birth = $patient_preview_infor_row['Date_of_Birth'];
                $tribe = $patient_preview_infor_row['Tribe'];
                $region = $patient_preview_infor_row['Region'];
                $Email_Address = $patient_preview_infor_row['Email_Address'];
                $Phone_Number = $patient_preview_infor_row['Phone_Number'];
            } 
            //select assement to preview 
            $assement_result = mysqli_query($conn,"SELECT * FROM tbl_adult_assement WHERE Registration_ID = $Registration_ID AND assement_id = $last_id") or die(mysqli_error($conn));
            while($assement_result_rows = mysqli_fetch_assoc($assement_result)){
                $current_presentation = $assement_result_rows['current_presentation'];  
                $medical_history = $assement_result_rows['medical_history']; 
                $social_history = $assement_result_rows['social_history'];
                $productivity = $assement_result_rows['productivity'];
                $bed_mobility = $assement_result_rows['bed_mobility'];
                $siting_baance = $assement_result_rows['siting_baance'];
                $transfer_skills = $assement_result_rows['transfer_skills'];
                $standing = $assement_result_rows['standing'];
                $mobility = $assement_result_rows['mobility'];
                $feeding = $assement_result_rows['feeding'];
                $groming_washing = $assement_result_rows['groming_washing'];
                $toileting = $assement_result_rows['toileting'];
                $dressing = $assement_result_rows['dressing'];
                $assement_id = $assement_result_rows['assement_id'];

                $shoulder_abduction = $assement_result_rows['shoulder_abduction'];  
                $shoulder_flexion = $assement_result_rows['shoulder_flexion']; 
                $sholder_extension = $assement_result_rows['sholder_extension'];
                $internal_rotation = $assement_result_rows['internal_rotation'];
                $external_rotation = $assement_result_rows['external_rotation'];
                $elbow_flexion = $assement_result_rows['elbow_flexion'];
                $elbow_extension = $assement_result_rows['elbow_extension'];
                $pronation = $assement_result_rows['pronation'];
                $supination = $assement_result_rows['supination'];
                $wrist_extension = $assement_result_rows['wrist_extension'];
                $wrist_flexion = $assement_result_rows['wrist_flexion'];
                $finger_flexion = $assement_result_rows['finger_flexion'];
                $finger_extension = $assement_result_rows['finger_extension'];
                $finger_opposition = $assement_result_rows['finger_opposition'];  
                $hip_abduction = $assement_result_rows['hip_abduction']; 
                $hip_flexion = $assement_result_rows['hip_flexion'];
                $hip_extesnion = $assement_result_rows['hip_extesnion'];
                $knee_extension = $assement_result_rows['knee_extension'];
                $knee_flexion = $assement_result_rows['knee_flexion'];
                $ankle_dorsiflexion = $assement_result_rows['ankle_dorsiflexion'];
                $ankel_plantar_flexion = $assement_result_rows['ankel_plantar_flexion'];
                $inversion = $assement_result_rows['inversion'];
                $eversion = $assement_result_rows['eversion'];
                $sholder_notes = $assement_result_rows['sholder_notes'];
                $elbow_notes = $assement_result_rows['elbow_notes'];
                $pronotion_notes = $assement_result_rows['pronotion_notes'];
                $wrist_notes = $assement_result_rows['wrist_notes'];  
                $finger_notes = $assement_result_rows['finger_notes']; 
                $hip_notes = $assement_result_rows['hip_notets'];
                $knee_notes = $assement_result_rows['knee_notes'];
                $ankle_notes = $assement_result_rows['ankle_notes'];
                $vision_and_hearing = $assement_result_rows['vision_and_hearing'];
                $ue_touch = $assement_result_rows['ue_touch'];
                $ue_pain = $assement_result_rows['ue_pain'];
                $ue_temp = $assement_result_rows['ue_temp'];
                $ue_prop = $assement_result_rows['ue_prop'];
                $le_touch = $assement_result_rows['le_touch'];
                $le_pain = $assement_result_rows['le_pain'];
                $le_temp = $assement_result_rows['le_temp'];
                $le_prop = $assement_result_rows['le_prop'];  
                $memory = $assement_result_rows['memory']; 
                $attention = $assement_result_rows['attention'];
                $problem_solving = $assement_result_rows['problem_solving'];
                $insight = $assement_result_rows['insight'];
                $communication = $assement_result_rows['communication'];
                $psycho_emotion = $assement_result_rows['psycho_emotion'];
                $therapist = $assement_result_rows['therapist'];
                $goals = $assement_result_rows['goals'];
                $leasure = $assement_result_rows['leasure'];
                $perfomance_context = $assement_result_rows['perfomance_context'];
                $progress = $assement_result_rows['progress'];
                $created_at = $assement_result_rows['created_at'];
               
            }
            echo '
            <div style="padding:2% 10% 2% 10%; overflow-y: show;">

            <table class="table table-borderd" style="background-color:#fff">
            <tr><center><th colspan="10">Personal Informations</th></center></tr>
            <tr>
                <td>Name:</td>
                <th>'.$patient_name.'</th>
                <td>Hospital Number:</td>
                <th>'.$patient_number.'</th>
                <td>Dob:</td>
                <th>'.$date_of_birth.'</th>
                <td>Tribe:</td>
                <th>'.$tribe.'</th>
                <td>Religion:</td>
                <th>'.$region.'</th>
            </tr>
            <tr>
                <td>Address:</td>
                <th>'.$Email_Address.'</th>
                <td>Diagnosis:</td>
                <th>Donee</th>
                <td>Contact:</td>
                <th>'.$Phone_Number.'</th>
                <td>Date of Assement</td>
                <th>'.$created_at .'</th>
                <td>Therapist:</td>
                <th>'.$therapist.'</th>
            </tr>
            </table>

            <center><legend>--Assement Form--</legend></center>
            <table class="table table-borderd" style="background-color:#fff">
                <tr>
                    <th class="art_td">Current Presentation</th>
                    <td>'.$current_presentation.'</td>
                </tr>
                <tr>
                    <th >Medical History</th>
                    <td>'.$medical_history.'</td>
                </tr>
                <tr>
                    <th >Social History/Living Environment</th>
                    <td>'.$social_history.'</td>
                </tr>
               
            </table>
            <!--Physical Assement-->
            <center><legend>--Physical Assement--</legend></center>
            <table class="table table-borderd" style="background-color:#fff">
                <tr></tr>
            <thead>
                <tr>
                    <th>Motor Skills</th>
                    <th>Ability, endurance and strategies used</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th class="art_td">Bed Mobility<br><i>(rolling in lying, lying to sit)</i></th>
                <td>'.$bed_mobility.'</td>
                </tr>
                <tr>
                <th>Sitting Balance<br><i>(dynamic, static)</i></th>
                <td>'.$siting_baance.'</td>
                </tr>
                <tr>
                <th>Transfer Skills<br><i>(push self up, weight shift, sit to stand, sit to sit)</i></</th>
                <td>'.$transfer_skills.'</td>
                </tr>
                <tr>
                <th>Standing<br><i>(endurance, positioning)</i></</th>
                <td>'.$standing.'</td>
                </tr>
                <tr>
                <th>Mobility<br><i>(walking gait, w/c propulsion)</i></</th>
                <td>'.$mobility.'</td>
                </tr>
                </tbody>
            </table>
            <center><legend>--Perfomance Area--</legend></center>
            <table class="table table-borderd" style="background-color:#fff">
                <tr></tr>
            <tbody>
                <tr>
                <th>Feeding</th>
                <td>'.$feeding.'</td>
                </tr>
                <tr>
                <th>Grooming/Washing</th>
                <td>'.$groming_washing.'</td>
                </tr>
                <tr>
                <th>Toileting</th>
                <td>'.$toileting.'</td>
                </tr>
                <tr>
                <th>Dressing</th>
                <td>'.$dressing.'</td>
                </tr>
                <th>Leisures</th>
                <td>'.$leasure.'</td>
                </tr>
                <tr>
                <th >Productivity(<i>works, roles at home<i>)</th>
                <td>'.$productivity.'</td>
            </tr>
            </tbody>
            </table>
            <!--Perfomance Components-->
            <center><legend>--Perfomance Components--</legend></center>
            <table class="table table-borderd" style="background-color:#fff">
                <thead>
                    <tr>
                    <th>UE Movement</th>
                    <th>WNL/Impaired</th>
                    <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="art_td">Shoulder Abduction</th>
                        <td>'.$shoulder_abduction.'</td>
                        <td rowspan="5">'.$sholder_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Shoulder flexion</th>
                        <td>'.$shoulder_flexion.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Shoulder extension</th>
                        <td>'.$sholder_extension.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Internal rotation</th>
                        <td>'.$internal_rotation.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">External rotation</th>
                        <td>'.$external_rotation.'</td>
                    </tr>
                    <!--__________________elbow_____-->
                    <tr>
                        <th class="art_td">Elbow flexion</th>
                        <td>'.$elbow_flexion.'</td>
                        <td rowspan="2">'.$elbow_notes.'</textarea></td>
                    </tr>
                    <tr>
                        <th class="art_td">Elbow extension</th>
                        <td>'.$elbow_extension.'</td>
                    </tr>
                    <!--____________________________-->
                    <tr>
                        <th class="art_td">Pronation</th>
                        <td>'.$pronation.'</td>
                        <td rowspan="2">'.$pronotion_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Supination</th>
                        <td>'.$supination.'</td>
                    </tr>
                    <!--______________________-->
                    <tr>
                        <th class="art_td">Wrist extension</th>
                        <td>'.$wrist_extension.'</td>
                        <td rowspan="2">'.$wrist_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Wrist flexion</th>
                        <td>'.$wrist_flexion.'</td>
                    </tr>
                    <!--______________________-->
                    <tr>
                        <th class="art_td">Finger flexion</th>
                        <td>'.$finger_flexion.'</td>
                        <td rowspan="3">'.$finger_notes.'</td>
                    </tr>
                    <!--______________________-->
                    <tr>
                        <th class="art_td">Finger extension</th>
                        <td>'.$finger_extension .'</td>
                    </tr>
                    <!--______________________-->
                    <tr>
                        <th class="art_td">Finger opposition</th>
                        <td>'.$finger_opposition.'</td>
                    </tr>
                </tbody>
                </table>
                <!--LE MOVEMENT-->
                <table class="table table-borderd" style="background-color:#fff">
                <thead>
                    <tr>
                    <td>LE Movement</td>
                    <td>WNL/Impaired</td>
                    <td>Notes</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="art_td">Hip abduction</th>
                        <td>'.$hip_abduction.'</td>
                        <td rowspan="3">'.$hip_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Hip flexion</th>
                        <td>'.$hip_flexion.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Hip extension</th>
                        <td>'.$hip_extesnion.'</td>
                    </tr>
                    <!--kneel_________________________________________-->
                    <tr>
                        <th class="art_td">Knee extension</th>
                        <td>'.$knee_extension.'</td>
                        <td rowspan="2">'.$knee_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Knee flexion</th>
                        <td>'.$knee_flexion.'</td>
                    </tr>
                    <!--Ankle________________________________-->
                    <tr>
                        <th class="art_td">Ankle dorsiflexion</th>
                        <td>'.$ankle_dorsiflexion.'</td>
                        <td rowspan="4">'.$ankle_notes.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Ankle plantar flexion</th>
                        <td>'.$ankel_plantar_flexion.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Inversion</th>
                        <td>'.$inversion.'</td>
                    </tr>
                    <tr>
                        <th class="art_td">Eversion</th>
                        <td>'.$eversion.'</td>
                    </tr>
                </tbody>
                </table>
                <center><legend>--Sensation--</legend></center>
                <table class="table table-borderd" style="background-color:#fff">
                <tr>
                    <th class="art_td">Vision, Hearing</th>
                    <td colspan="4">'.$vision_and_hearing.'</td>
                </tr>
                <tr>
                    <th></th>
                    <th>Touch</th>
                    <th>Pain</th>
                    <th>Temparature</th>
                    <th>Proprioception</th>
                </tr>
                <tr>
                    <th>UE</th>
                    <td>'.$ue_touch.'</td>
                    <td>'.$ue_pain.'</td>
                    <td>'.$ue_temp.'</td>
                    <td>'.$ue_prop.'</td>
                </tr>
                <tr>
                    <th>LE</th>
                    <td>'.$le_touch.'</td>
                    <td>'.$le_pain.'</td>
                    <td>'.$le_temp.'</td>
                    <td>'.$le_prop.'</td>
                </tr>
                </table>
                <center><legend>--Cognitive Assement--</legend></center>
                <table class="table table-borderd" style="background-color:#fff">
                <tr>
                <th class="art_td">Memory<br><i>(daily task, faces, names)</i></th>
                <td>'.$memory.'</td>
                </tr>
                <tr>
                <th class="art_td">Attention<br><i>(attention span, divided attention)</i></th>
                <td>'.$attention.'</td>
                </tr>
                <tr>
                <th class="art_td">Problem Solving</th>
                <td>'.$problem_solving.'</td>
                </tr>
                <tr>
                <th class="art_td">Insight<br><i>(self-awareness)</i></th>
                <td>'.$insight.'</td>
                </tr>
                <tr>
                <th class="art_td">Communication<br><i>(receptive, expressive abilities, word finding)</i></th>
                <td>'.$communication.'</td>
                </tr>
                <tr>
                <th class="art_td">Psycho-emotion Changes<br><i>(personality, mood, emotion lability, behavior)</i></th>
                <td>'.$psycho_emotion.'</td>
                </tr>
                <tr>
                <th class="art_td">Perfomance Context</th>
                <td>'.$perfomance_context.'</td>
                </tr>
                <tr>
                <th class="art_td">Goals</th>
                <td>'.$goals.'</td>
                </tr>
                <tr>
                <th class="art_td">Progress Notes</th>
                <td>'.$progress.'</td>
                </tr>
                </table>
                <table>
                <tr><a target="_blank" href="adult_assement_pdf_preview.php?Registration_ID='.$Registration_ID.'&therapist='.$therapist.'&assement_id='.$assement_id.'"><input type="button"  class="art-button-green pull-right" value="Preview Assement Informasions In PDF"></a>
                </tr>
                </table></div>
            ';
        }else{
            echo '<h4>data are not inserted</h4>';
        }
}
else {
    echo "fail";
}
?>