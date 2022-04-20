<?php 
include("./includes/connection.php");
$therapist = $_SESSION['userinfo']['Employee_Name'];
if(isset($_POST['Registration_ID'])){
    $Registration_ID = $_POST['Registration_ID'];
    $therapist = $_POST['therapist'];

    //value
    $diagnosis = mysqli_real_escape_string($conn,$_POST['diagnosis']);
    $medication = mysqli_real_escape_string($conn,$_POST['medication']);
    $vision = mysqli_real_escape_string($conn,$_POST['vision']);
    $hearing = mysqli_real_escape_string($conn,$_POST['hearing']);
    $birth_history = mysqli_real_escape_string($conn,$_POST['birth_history']);
    $feeding = mysqli_real_escape_string($conn,$_POST['feeding']);
    $groming = mysqli_real_escape_string($conn,$_POST['groming']);
    $washing = mysqli_real_escape_string($conn,$_POST['washing']);
    $toileting = mysqli_real_escape_string($conn,$_POST['toileting']);
    $dressing = mysqli_real_escape_string($conn,$_POST['dressing']);
    $school = mysqli_real_escape_string($conn,$_POST['school']);
    $household_activities = mysqli_real_escape_string($conn,$_POST['household_activities']);
    $physical_motor_Skills = mysqli_real_escape_string($conn,$_POST['physical_motor_Skills']);
    $cognition = mysqli_real_escape_string($conn,$_POST['cognition']);
    $communication_skills = mysqli_real_escape_string($conn,$_POST['communication_skills']);
    $emotional_psychological = mysqli_real_escape_string($conn,$_POST['emotional_psychological']);
    $home_situation = mysqli_real_escape_string($conn,$_POST['home_situation']);
    $parent_works = mysqli_real_escape_string($conn,$_POST['parent_works']);
    $main_concern = mysqli_real_escape_string($conn,$_POST['main_concern']);
    $current_medication = mysqli_real_escape_string($conn,$_POST['current_medication']);
    $perfomance_context = mysqli_real_escape_string($conn,$_POST['perfomance_context']);
    $goals = mysqli_real_escape_string($conn,$_POST['goals']);
    $progress = mysqli_real_escape_string($conn,$_POST['progress']);


    $insert_sql = "INSERT INTO `tbl_pediatric_assesment_informations`(`Registration_ID`, `therapist`, 
    `diagnosis`, `medication`, `vision`, `hearing`, `birth_history`, `feeding`, `groming`, `washing`, 
    `toileting`, `dressing`, `school`, `household_activities`, `physical_motor_Skills`, `cognition`, 
    `communication_skills`, `emotional_psychological`, `home_situation`, `parent_works`, `main_concern`,`current_medication`,`perfomance_context`,`goals`,`progress`) 
    VALUES ('$Registration_ID', '$therapist', '$diagnosis', '$medication', '$vision', '$hearing', 
    '$birth_history', '$feeding', '$groming', '$washing', '$toileting', '$dressing', '$school', '$household_activities',
    '$physical_motor_Skills', '$cognition','$communication_skills', '$emotional_psychological','$home_situation', '$parent_works', '$main_concern','$current_medication','$perfomance_context','$goals','$progress')";
    $save = mysqli_query($conn,$insert_sql) or die(mysqli_error($conn));
    if($save){
        $last_id = $conn->insert_id;
        //select assesment details
        $result = mysqli_query($conn,"SELECT * FROM tbl_pediatric_assesment_informations WHERE Registration_ID = $Registration_ID AND pediatric_id = $last_id") or die(mysqli_error($conn));
        if((mysqli_num_rows($result))>0){
            while($pediatric_data_rows = mysqli_fetch_assoc($result)){
                    $pediatric_id = $pediatric_data_rows['pediatric_id'];
                    $diagnosis = $pediatric_data_rows['diagnosis'];
                    $medication = $pediatric_data_rows['medication'];
                    $vision = $pediatric_data_rows['vision'];
                    $hearing = $pediatric_data_rows['hearing'];
                    $birth_history = $pediatric_data_rows['birth_history'];
                    $feeding = $pediatric_data_rows['feeding'];
                    $groming = $pediatric_data_rows['groming'];
                    $washing = $pediatric_data_rows['washing'];
                    $toileting = $pediatric_data_rows['toileting'];
                    $dressing = $pediatric_data_rows['dressing'];
                    $school = $pediatric_data_rows['school'];
                    $household_activities = $pediatric_data_rows['household_activities'];
                    $physical_motor_Skills = $pediatric_data_rows['physical_motor_Skills'];
                    $cognition = $pediatric_data_rows['cognition'];
                    $communication_skills = $pediatric_data_rows['communication_skills'];
                    $emotional_psychological = $pediatric_data_rows['emotional_psychological'];
                    $home_situation = $pediatric_data_rows['home_situation'];
                    $parent_works = $pediatric_data_rows['parent_works'];
                    $main_concern = $pediatric_data_rows['main_concern'];
                    $current_medication = $pediatric_data_rows['current_medication'];
                    $perfomance_context = $pediatric_data_rows['perfomance_context'];
                    $goals = $pediatric_data_rows['goals'];
                    $created_at = $pediatric_data_rows['created_at'];
                    $progress = $pediatric_data_rows['progress'];
            //select patent details
                $patient_detail = mysqli_query($conn,"SELECT Patient_Name, Registration_ID, Date_of_Birth, Tribe, Region, Email_Address, Phone_Number FROM tbl_patient_registration WHERE Registration_ID=$Registration_ID") or die(mysqli_error($conn));
                if((mysqli_num_rows($patient_detail))>0){
                while($patient_infor_row = mysqli_fetch_assoc($patient_detail)){
                    $patient_name = $patient_infor_row['Patient_Name'];
                    $patient_number = $patient_infor_row['Registration_ID'];
                    $date_of_birth = $patient_infor_row['Date_of_Birth'];
                    $tribe = $patient_infor_row['Tribe'];
                    $region = $patient_infor_row['Region'];
                    $Email_Address = $patient_infor_row['Email_Address'];
                    $Phone_Number = $patient_infor_row['Phone_Number'];
                    $date = date('m/d/Y h:i:s a', time());
                    echo '
                        <div style="padding:2% 10% 2% 10%; overflow-y: show;">
                        <table class="table table-borderd" style="background-color:#fff">
                        <tr><center><th colspan="10">Personal Informations</th></center></tr>
                        <tr>
                            <td>Child Name:</td>
                            <th>'.$patient_name.'</th>
                            <td>Hospital Number:</td>
                            <th>'.$patient_number.'</th>
                            <td>Dob:</td>
                            <th>'.$date_of_birth.'</th>
                            <td>Tribe:</td>
                            <th>'.$tribe.'</th>
                        </tr>
                        <tr>
                            <td>Religion:</td>
                            <th>'.$region.'</th>
                            <td>Address:</td>
                            <th>'.$Email_Address.'</th>
                            <td>Contact:</td>
                            <th>'.$Phone_Number.'</th>
                            <td>Date of Assement</td>
                        <th>'.$date .'</th>
                        </tr>
                        <tr>
                        <td>Diagnosis:</td>
                        <th>Donee</th>
                        <td>Medications:</td>
                        <th>Donee</th>
                        <td>Therapist:</td>
                        <th>'.$therapist.'</th>

                        </tr>
                        </table>
                    ';

                        }
                    }
                    echo  '
                    <center><legend>--Social situation --</legend></center>
                    <table class="table table-bordered" style="background-color:#fff">
                    <tr>
                        <th>Home Situations</th>
                        <th>Parent Works</th>
                    </tr>
                    <tr>
                        <td>'.$home_situation.'</td>
                        <td>'.$parent_works.'</td>
                    </tr>
                    </table>
                        <center><legend>--Medical Information--</legend></center>
                            <table class="table table-bordered" style="background-color:#fff">
                                <tr>
                                    <th class="art_td">Main Concerns of Parents <br><i>(caregiver/child)</i></th>
                                    <td >'.$main_concern.'</td>
                                </tr>
                                <tr>
                                    <th class="art_td">Diagnosis</th>
                                    <td >'.$diagnosis.'</td>
                                   
                                </tr>
                                <tr>
                                    <th class="art_td">Medication</th>
                                    <td >'.$medication.'</td>
                                    
                                </tr>
                                <tr>
                                    <th class="art_td">Vision</th>
                                    <td >'.$vision.'</td>
                                    
                                </tr>
                                <tr>
                                    <th class="art_td">Hearing</th>
                                    <td >'.$hearing.'</td>
                                
                                </tr>
                                <tr>
                                    <th class="art_td">Birth History</th>
                                    <td >'.$birth_history.'</td>
                                    
                                </tr>
                                <tr>
                                <th class="art_td">Current Medication Condition</th>
                                <td >'.$current_medication.'</td>
                                
                            </tr>

                            </table>

                            <!--Physical Assement-->
                            <center><legend>--Self care Skills--</legend></center>
                            <table class="table table-bordered" style="background-color:#fff">
                                <tr>
                                <th class="art_td">Feeding</th>
                                <td >'.$feeding.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Grooming</th>
                                <td>'.$groming.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Washing</th>
                                <td>'.$washing.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Toileting</th>
                                <td>'.$toileting.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Dressing</th>
                                <td>'.$dressing.'</td>
                                </tr>
                            </table>
                            <center><legend>--Play/Leisure--</legend></center>
                            <table class="table table-bordered" style="background-color:#fff">
                                <tr>
                                <th class="art_td">School <br><i>(if not school how does child spend day)</i></th>
                                <td>'.$school.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Household Activities</th>
                                <td>'.$household_activities.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Physical/Motor Skills <br><i>(tone, GM/FM, abnormal MVT and patterns etc)</i></th>
                                <td>'.$physical_motor_Skills.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Cognition/perception/sensory skills</th>
                                <td>'.$cognition.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Communication/social skills</th>
                                <td>'.$communication_skills.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Emotional/Psychological/Behaviour</th>
                                <td>'.$emotional_psychological.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Perfomance Context</th>
                                <td>'.$perfomance_context.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Treatment Goal</th>
                                <td>'.$goals.'</td>
                                </tr>
                                <tr>
                                <th class="art_td">Progress Notes</th>
                                <td>'.$progress.'</td>
                                </tr>
                            </table>
                            <table>
                            <tr><a target="_blank" href="pediatric_assement_pdf_preview.php?Registration_ID='.$Registration_ID.'&therapist='.$therapist.'&pediatric_id='.$pediatric_id.'"><input type="button"  class="art-button-green pull-right" value="Preview Informasions In PDF"></a></tr>
                            </table>
                        </div>'; 
                    
            }
        }else{
            echo "NO DATA AVAILABLE";
        }
    }else{
        echo "fail";
    }
}
mysqli_close($conn);
?>