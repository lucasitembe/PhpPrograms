<?php 
include("./includes/connection.php");
$therapist = $_SESSION['userinfo']['Employee_Name'];
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
    $therapist = $_GET['therapist'];
    $Psychatric_assessment_ID = $_GET['Psychatric_assessment_ID'];
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
            $data.= '
            <center><img src="branchBanner/branchBanner.png" width="100%" ></center>
            <table class="table table-borderd" style="background-color:#fff; width:100%">
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
            </table>';

            //select assement to preview 
            $assement_result = mysqli_query($conn,"SELECT * FROM tbl_psychatric_assement WHERE Registration_ID = '$Registration_ID' AND Psychatric_assessment_ID = '$Psychatric_assessment_ID'") or die(mysqli_error($conn));
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

                $memory = $assement_result_rows['memory']; 
                $attention = $assement_result_rows['attention'];
                $problem_solving = $assement_result_rows['problem_solving'];
                $insight = $assement_result_rows['insight'];
                $communication = $assement_result_rows['communication'];
                $psycho_emotion = $assement_result_rows['psycho_emotion'];
                $goals = $assement_result_rows['goals'];
                $leasure = $assement_result_rows['leasure'];
                $perfomance_context = $assement_result_rows['perfomance_context'];
                $progress = $assement_result_rows['progress'];
                $created_at = $assement_result_rows['created_at'];
            
            }


            $data.= '
            <center><legend>--Assement Form--</legend></center>
            <table class="table table-borderd" style="background-color:#fff; width:100%">
            <tr>
            <th class="art_td">Cheif Complain</th>
            <td>'.$Cheif_complain.'</td>
                </tr>
                <tr>
                    <th >Past Medical History</th>
                    <td>'.$past_medical_history.'</td>
                </tr>
                <tr>
                    <th >Past Psychatric History</th>
                    <td>'.$past_psychatric_history.'</td>
                </tr>
                <tr>
                <th>history_of_present_illness</i></</th>
                <td>'.$history_of_present_illness.'</td>
                </tr>
                <tr>
                <th class="art_td">Mental Status Examination</th>
                <td>'.$Mental_status_examination.'</td>
                </tr>
                <tr>
                <th class="art_td">Occupational Assesment<br><i>(self-awareness)</i></th>
                <td>'.$occupational_assesment.'</td>
                </tr>
            </table>';

            $data.= '<div><center><legend>--Physical Assement--</legend></center>
            <table class="table table-borderd" style="background-color:#fff; width:100%">
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
                <th>Transfer Skills<br><i>(push self up, weight shift, sit to stand, sit to sit)</i></th>
                <td>'.$transfer_skills.'</td>
                </tr>
                <tr>
                <th>Standing<br><i>(endurance, positioning)</i></th>
                <td>'.$standing.'</td>
                </tr>
                <tr>
                <th>Mobility<br><i>(walking gait, w/c propulsion)</i></th>
                <td>'.$mobility.'</td>
                </tr>
                </tbody>
                </table>
                <center><legend>--Perfomance Area--</legend></center>
                <table class="table table-borderd" style="background-color:#fff; width:100%">
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
                <tr>
                <th>Leisures</th>
                <td>'.$leasure.'</td>
                </tr>
                <tr>
                <th >Productivity(<i>works, roles at home<i>)</th>
                <td>'.$productivity.'</td>
                </tr>
            </tbody>
            </table>
                <center><legend>--Cognitive Assement--</legend></center>
                <table class="table table-borderd" style="background-color:#fff; width:100%">
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
                <tr><input type="button" class="art-button-green pull-right" value="Preview Assement Informasions In PDF"></tr>
                </table></div>
            ';
            include("MPDF/mpdf.php");
            $mpdf = new mPDF('', 'A4');
            $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
            $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
            // LOAD a stylesheet
            $stylesheet = file_get_contents('patient_file.css');
            $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->WriteHTML($data, 2);

            $mpdf->Output('mpdf.pdf','I');
}
else {
    echo "fail";
}
?>