<?php 
    include("./includes/header.php");
?>
<style>
    .art_td{
        text-align: right;
        width:20%;
    }
    input[type="radio"] {
        -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
        -moz-appearance: checkbox;    /* Firefox */
        -ms-appearance: checkbox;     /* not currently supported */
    }

    .rows_list{ 
        cursor: pointer; 
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
</style>
<?php 
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
        $Patient_Payment_Item_List_ID = 0;
    }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
            if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Registration_ID = $_GET['Registration_ID'];
    $therapist = $_SESSION['userinfo']['Employee_Name'];
    $Registration_ID = $_GET['Registration_ID'];
    $consultation_ID = $_GET['consultation_ID'];
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
?>
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<center>
<br>
    <table width='100%' style='background: #006400 !important;color: white;'>
        <tr>
            <td>
        <center>
            <b>DOCTORS OUTPATIENT WORKPAGE: ADULT ASSESMENT</b>
        </center>
        </td>
        </tr>
        <tr>
        </tr>
    </table>
<br>
</center>
<fieldset id="assement_informations">
  <?php 
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
        <th>'.$date .'</th>
        <td>Therapist:</td>
        <th>'.$therapist.'</th>
    </tr>
</table>
    ';

    }
}
  ?>
    <!--Assement form-->
    <div style="padding:2% 10% 2% 10%; overflow-y: show;">
        <form action="">
        <center><legend>--Assement Form--</legend></center>
            <table class="table table-bordered" >
            <tr>
                    <td class="art_td">Cheif Complain</td>
                    <th><textarea name="Chief_complain" id="Chief_complain"  rows="1" class="form-control"></textarea></th>
                </tr>
                <tr>
                    <td class="art_td">Current Presentation</td>
                    <th><textarea name="current_presentation" id="current_presentation"  rows="1" class="form-control"></textarea></th>
                </tr>
                <tr>
                    <td >Medical History</td>
                    <th><textarea name="medical_history" id="medical_history"  rows="1" class="form-control"></textarea></th>
                </tr>
                <tr>
                    <td >Social History/Living Environment</td>
                    <th><textarea name="social_history" id="social_history"  rows="1" class="form-control"></textarea></th>
                </tr>
              
            </table>
            <!--Physical Assement-->
            <center><legend>--Physical Assement--</legend></center>
            <table class="table table-bordered">
                <tr></tr>
               <thead>
                <tr>
                    <th>Motor Skills</th>
                    <th>Ability, endurance and strategies used</th>
                </tr>
               </thead>
               <tbody>
                   <tr>
                   <td class="art_td">Bed Mobility<br><i>(rolling in lying, lying to sit)</i></td>
                   <th><textarea name="bed_mobility" id="bed_mobility"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Sitting Balance<br><i>(dynamic, static)</i></td>
                   <th><textarea name="sitting_balance" id="sitting_balance"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Transfer Skills<br><i>(push self up, weight shift, sit to stand, sit to sit)</i></</td>
                   <th><textarea name="transfer_skills" id="transfer_skills"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Standing<br><i>(endurance, positioning)</i></</td>
                   <th><textarea name="standing" id="standing"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Mobility<br><i>(walking gait, w/c propulsion)</i></</td>
                   <th><textarea name="mobility" id="mobility"  rows="1"></textarea></th>
                   </tr>
                </tbody>
            </table>
            <center><legend>--Perfomance Area--</legend></center>
            <table class="table table-bordered">
                <tr></tr>
               <tbody>
                   <tr>
                   <td class="art_td">Feeding</td>
                   <th><textarea name="feeding" id="feeding"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Grooming/Washing</td>
                   <th><textarea name="groming_washing" id="groming_washing"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Toileting</td>
                   <th><textarea name="toileting" id="toileting"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <td>Dressing</td>
                   <th><textarea name="dressing" id="dressing"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                    <td >Leisures</td>
                    <th><textarea name="leisures" id="leisures"  rows="1" class="form-control"></textarea></th>
                    </tr>
                   <tr>
                    <td >Productivity<br>(<i>works, roles at home<i>)</td>
                    <th><textarea name="productivity" id="productivity"  rows="1" class="form-control"></textarea></th>
                    </tr>
               </tbody>
            </table>
            <!--Perfomance Components-->
            <center><legend>--Perfomance Components--</legend></center>
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th rowspan="2">UE Movement</th>
                    <th colspan="2">WNL/Impaired</th>
                    <th rowspan="2">Notes</th>
                    </tr>
                    <tr>
                        <th>L</th>
                        <th>R</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="art_td">Shoulder Abduction</td>
                        <th> <input type="radio" name="shoulder_abduction" value="right"> </th>
                        <th> <input type="radio" name="shoulder_abduction" value="left"> </th>
                        <th rowspan="5"><textarea name="shoulder_notes" id="shoulder_notes"  rows="10"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Shoulder flexion</td>
                        <th> <input type="radio" name="shoulder_flexion" value="right"> </th>
                        <th> <input type="radio" name="shoulder_flexion" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">Shoulder extension</td>
                        <th> <input type="radio" name="shoulder_extension" value="right"> </th>
                        <th> <input type="radio" name="shoulder_extension" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">Internal rotation</td>
                        <th> <input type="radio" name="internal_rotaion" value="right"> </th>
                        <th> <input type="radio" name="internal_rotaion" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">External rotation</td>
                        <th> <input type="radio" name="external_abduction" value="right"> </th>
                        <th> <input type="radio" name="external_abduction" value="left"> </th>
                    </tr>
                    <!--__________________elbow_____-->
                    <tr>
                        <td class="art_td">Elbow flexion</td>
                        <th> <input type="radio" name="elbow_flexion" value="right"> </th>
                        <th> <input type="radio" name="elbow_flexion" value="left"> </th>
                        <th rowspan="2"><textarea name="elbow_notes" id="elbow_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Elbow extension</td>
                        <th> <input type="radio" name="elbow_extension" value="right"> </th>
                        <th> <input type="radio" name="elbow_extension" value="left"> </th>
                    </tr>
                    <!--____________________________-->
                    <tr>
                        <td class="art_td">Pronation</td>
                        <th> <input type="radio" name="pronation" value="right"> </th>
                        <th> <input type="radio" name="pronation" value="left"> </th>
                        <th rowspan="2"><textarea name="pronation_notes" id="pronation_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Supination</td>
                        <th> <input type="radio" name="supination" value="right"> </th>
                        <th> <input type="radio" name="supination" value="left"> </th>
                    </tr>
                    <!--______________________-->
                    <tr>
                        <td class="art_td">Wrist extension</td>
                        <th> <input type="radio" name="wrist_extension" value="right"> </th>
                        <th> <input type="radio" name="wrist_extension" value="left"> </th>
                        <th rowspan="2"><textarea name="wrist_notes" id="wrist_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Wrist flexion</td>
                        <th> <input type="radio" name="wrist_flexion" value="right"> </th>
                        <th> <input type="radio" name="wrist_flexion" value="left"> </th>
                    </tr>
                      <!--______________________-->
                      <tr>
                        <td class="art_td">Finger flexion</td>
                        <th> <input type="radio" name="finger_flexion" value="right"> </th>
                        <th> <input type="radio" name="finger_flexion" value="left"> </th>
                        <th rowspan="3"><textarea name="finger_notes" id="finger_notes"  rows="1"></textarea></th>
                    </tr>
                      <!--______________________-->
                      <tr>
                        <td class="art_td">Finger extension</td>
                        <th> <input type="radio" name="finger_extension" value="right"> </th>
                        <th> <input type="radio" name="finger_extension" value="left"> </th>
                    </tr>
                      <!--______________________-->
                      <tr>
                        <td class="art_td">Finger opposition</td>
                        <th> <input type="radio" name="finger_opposition" value="right"> </th>
                        <th> <input type="radio" name="finger_opposition" value="left"> </th>
                    </tr>
                </tbody>
            </table>
            <!--LE MOVEMENT-->
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th rowspan="2">LE Movement</th>
                    <th colspan="2">WNL/Impaired</th>
                    <th rowspan="2">Notes</th>
                    </tr>
                    <tr>
                        <th>L</th>
                        <th>R</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="art_td">Hip abduction</td>
                        <th> <input type="radio" name="hip_abduction" value="right"> </th>
                        <th> <input type="radio" name="hip_abduction" value="left"> </th>
                        <th rowspan="3"><textarea name="hip_notes" id="hip_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Hip flexion</td>
                        <th> <input type="radio" name="hip_flexion" value="right"> </th>
                        <th> <input type="radio" name="hip_flexion" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">Hip extension</td>
                        <th> <input type="radio" name="hip_extension" value="right"> </th>
                        <th> <input type="radio" name="hip_extension" value="left"> </th>
                    </tr>
                    <!--kneel_________________________________________-->
                    <tr>
                        <td class="art_td">Knee extension</td>
                        <th> <input type="radio" name="knee_extension" value="right"> </th>
                        <th> <input type="radio" name="knee_extension" value="left"> </th>
                        <th rowspan="2"><textarea name="knee_notes" id="knee_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Knee flexion</td>
                        <th> <input type="radio" name="nkee_flexion" value="right"> </th>
                        <th> <input type="radio" name="nkee_flexion" value="left"> </th>
                    </tr>
                    <!--Ankle________________________________-->
                    <tr>
                        <td class="art_td">Ankle dorsiflexion</td>
                        <th> <input type="radio" name="ankle_dorsiflexion" value="right"> </th>
                        <th> <input type="radio" name="ankle_dorsiflexion" value="left"> </th>
                        <th rowspan="4"><textarea name="ankle_notes" id="ankle_notes"  rows="1"></textarea></th>
                    </tr>
                    <tr>
                        <td class="art_td">Ankle plantar flexion</td>
                        <th> <input type="radio" name="ankle_plantar_flexion" value="right"> </th>
                        <th> <input type="radio" name="ankle_plantar_flexion" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">Inversion</td>
                        <th> <input type="radio" name="inversion" value="right"> </th>
                        <th> <input type="radio" name="inversion" value="left"> </th>
                    </tr>
                    <tr>
                        <td class="art_td">Eversion</td>
                        <th> <input type="radio" name="eversion" value="right"> </th>
                        <th> <input type="radio" name="eversion" value="left"> </th>
                    </tr>
                </tbody>
            </table>
            <center><legend>--Sensation--</legend></center>
            <table class="table table-bordered">
                <tr>
                    <td class="art_td">Vision, Hearing</td>
                    <th colspan="4"><textarea name="vision_hearing" id="vision_hearing"  rows="1" class="form-control"></textarea></th>
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
                    <td><input type="text" name="ue_touch" id="ue_touch" class="form-control"> </td>
                    <td><input type="text" name="ue_pain" id="ue_pain" class="form-control"> </td>
                    <td><input type="text" name="ue_temparature" id="ue_temparature" class="form-control"> </td>
                    <td><input type="text" name="ue_proprioception" id="ue_proprioception" class="form-control"> </td>
                </tr>
                <tr>
                    <th>LE</th>
                    <td><input type="text" name="le_touch" id="le_touch" class="form-control"> </td>
                    <td><input type="text" name="le_pain" id="le_pain" class="form-control"> </td>
                    <td><input type="text" name="le_temparature" id="le_temparature" class="form-control"> </td>
                    <td><input type="text" name="le_proprioception" id="le_proprioception" class="form-control"> </td>
                </tr>
            </table>
            <center><legend>--Cognitive Assement--</legend></center>
            <table class="table table-bordered">
                <tr>
                   <td class="art_td">Memory<br><i>(daily task, faces, names)</i></td>
                   <th><textarea name="memory" id="memory"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Attention<br><i>(attention span, divided attention)</i></td>
                   <th><textarea name="attention" id="attention"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Problem Solving</td>
                   <th><textarea name="problem_solving" id="problem_solving"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Insight<br><i>(self-awareness)</i></td>
                   <th><textarea name="insight" id="insight"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Communication<br><i>(receptive, expressive abilities, word finding)</i></td>
                   <th><textarea name="communication" id="communication"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Psycho-emotion Changes<br><i>(personality, mood, emotion lability, behavior)</i></td>
                   <th><textarea name="psycho_emotion_changes" id="psycho_emotion_changes"  rows="1"></textarea></th>
                </tr>
                <tr>
                    <td >Perfomance Context</td>
                    <th><textarea name="perfomance_context" id="perfomance_context"  rows="1" class="form-control"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Goals</td>
                   <th><textarea name="goals" id="goals"  rows="1"></textarea></th>
                </tr>
                <tr>
                <td>PROPOSED PROCEDURE</td>                
                <td>
                    <span class="col-md-8">
                        <textarea class='form-control'id="proposed_procedure"></textarea>
                    </span>
                    <span class="col-md-4">
                        <a href="proceduredocotorpatientinfo.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Registration_id=<?php echo $Registration_ID; ?>&ProcedureWorks=ProcedureWorksThisPage">
                            <input type="button" value="PERFORM PROCEDURE" class="art-button-green">
                        </a>
                        <input type="button" name="proposed_procedure"  value="ORDER PROCEDURE" class="btn btn-sm btn-danger pull-right" onclick="ajax_procedure_dialog_open()">
                    </span>
                </td>
                </tr>
                <tr>
                   <td class="art_td">Procedure Remarks</td>
                   <th><textarea name="Procedure_remarks" id="Procedure_remarks"   rows="1"><?= $Procedure_remarks ?></textarea>
                </th>
                </tr>
                <tr>
                   <td class="art_td">Progress Notes</td>
                   <th><textarea name="progress" id="progress"  rows="1"></textarea></th>
                </tr>
            </table>
            <table>
                <tr><input type="button" class='art-button pull-right' onclick="save_assement_result(<?php echo $Registration_ID;?>,'<?php echo $therapist?>')" value="Save Adult Assement Informasions"></tr>
            </table>
        </form>
    </div>
</fieldset>
<input id="Registration_ID" type="text" style="display: none;" value="<?= $Registration_ID ?>">
<input id="Patient_Payment_ID" type="text" style="display: none;" value="<?php echo $Patient_Payment_ID; ?>">
<input  id="consultation_ID" type="text" style="display: none;" value="<?= $consultation_ID; ?>">
<div id="opendialogue"></div>
<div id="patientprocedure"></div>
<div id="procedure_list"></div>
<script src="js/JsFunctions.js"></script>

<script>


    function save_assement_result(Registration_ID,therapist){
        var Chief_complain = $("#Chief_complain").val();
        var current_presentation = $('#current_presentation').val();
        var Procedure_remarks =$("#Procedure_remarks").val();
        var medical_history = $('#medical_history').val();
        var social_history = $('#social_history').val();
        var productivity = $('#productivity').val();
        var bed_mobility = $('#bed_mobility').val();
        var sitting_balance = $('#sitting_balance').val();
        var transfer_skills = $('#transfer_skills').val();
        var standing = $('#standing').val();
        var mobility = $('#mobility').val();
        var feeding = $('#feeding').val();
        var groming_washing = $('#groming_washing').val();
        var toileting = $('#toileting').val();
        var dressing = $('#dressing').val();

        var shoulder_abduction = $("input[name='shoulder_abduction']:checked").val();
        var shoulder_notes = $('#shoulder_notes').val();
        var shoulder_flexion = $("input[name='shoulder_flexion']:checked").val();
        var shoulder_extension = $("input[name='shoulder_extension']:checked").val();
        var internal_rotaion = $("input[name='internal_rotaion']:checked").val();
        var external_abduction = $("input[name='external_abduction']:checked").val();
        var elbow_flexion = $("input[name='elbow_flexion']:checked").val();
        var elbow_extension = $("input[name='elbow_extension']:checked").val();
        var pronation = $("input[name='pronation']:checked").val();
        var supination = $("input[name='supination']:checked").val();
        var wrist_extension = $("input[name='wrist_extension']:checked").val();
        var wrist_flexion = $("input[name='wrist_flexion']:checked").val();
        var finger_flexion = $("input[name='finger_flexion']:checked").val();
        var finger_extension = $("input[name='finger_extension']:checked").val();
        var finger_opposition = $("input[name='finger_opposition']:checked").val();
        var hip_abduction = $("input[name='hip_abduction']:checked").val();
        var hip_flexion = $("input[name='hip_flexion']:checked").val();
        var hip_extension = $("input[name='hip_extension']:checked").val();
        var knee_extension = $("input[name='knee_extension']:checked").val();
        var nkee_flexion = $("input[name='nkee_flexion']:checked").val();
        var ankle_dorsiflexion = $("input[name='ankle_dorsiflexion']:checked").val();
        var ankle_plantar_flexion = $("input[name='ankle_plantar_flexion']:checked").val();
        var inversion = $("input[name='inversion']:checked").val();
        var eversion = $("input[name='eversion']:checked").val();
        var hip_notes = $('#hip_notes').val();
        var knee_notes = $('#knee_notes').val();
        var ankle_notes = $('#ankle_notes').val();
        var elbow_notes = $('#elbow_notes').val();
        var pronation_notes = $('#pronation_notes').val();
        var wrist_notes = $('#wrist_notes').val();
        var finger_notes = $('#finger_notes').val();

        var vision_hearing = $('#vision_hearing').val();
        var ue_touch = $('#ue_touch').val();
        var ue_pain = $('#ue_pain').val();
        var ue_temparature = $('#ue_temparature').val();
        var ue_proprioception = $('#ue_proprioception').val();
        var le_touch = $('#le_touch').val();
        var le_pain = $('#le_pain').val();
        var le_temparature = $('#le_temparature').val();
        var le_proprioception = $('#le_proprioception').val();

        var memory = $('#memory').val();
        var attention = $('#attention').val();
        var problem_solving = $('#problem_solving').val();
        var insight = $('#insight').val();
        var communication = $('#communication').val();
        var psycho_emotion_changes = $('#psycho_emotion_changes').val();
        var goals = $('#goals').val();
        var perfomance_context = $('#perfomance_context').val();
        var leisures = $('#leisures').val();
        var progress = $('#progress').val();
        //alert(Registration_ID);
        $.ajax({
            url: 'save_assement_informations.php',
            type: 'POST',
            data: {
                Chief_complain:Chief_complain,
                Procedure_remarks:Procedure_remarks,
                    Registration_ID:Registration_ID,
                    therapist:therapist,
                    current_presentation:current_presentation,
                    medical_history:medical_history,
                    social_history:social_history,
                    productivity:productivity,
                    bed_mobility:bed_mobility,
                    sitting_balance:sitting_balance,
                    transfer_skills:transfer_skills,
                    standing:standing,
                    mobility:mobility,
                    feeding:feeding,
                    groming_washing:groming_washing,
                    toileting:toileting,
                    dressing:dressing,
                    
                    shoulder_abduction:shoulder_abduction,
                    shoulder_notes:shoulder_notes,
                    shoulder_flexion:shoulder_flexion,
                    shoulder_extension:shoulder_extension,
                    internal_rotaion:internal_rotaion,
                    external_abduction:external_abduction,
                    elbow_flexion:elbow_flexion,
                    elbow_extension:elbow_extension,
                    pronation:pronation,
                    supination:supination,
                    wrist_extension:wrist_extension,
                    wrist_flexion:wrist_flexion,
                    finger_flexion:finger_flexion,
                    finger_extension:finger_extension,
                    finger_opposition:finger_opposition,
                    hip_abduction:hip_abduction,
                    hip_flexion:hip_flexion,
                    hip_extension:hip_extension,
                    knee_extension:knee_extension,
                    nkee_flexion:nkee_flexion,
                    ankle_dorsiflexion:ankle_dorsiflexion,
                    ankle_plantar_flexion:ankle_plantar_flexion,
                    inversion:inversion,
                    eversion:eversion,
                    hip_notes:hip_notes,
                    knee_notes:knee_notes,
                    ankle_notes:ankle_notes,
                    elbow_notes:elbow_notes,
                    pronation_notes:pronation_notes,
                    wrist_notes:wrist_notes,
                    finger_notes:finger_notes,
                    
                    vision_hearing:vision_hearing,
                    ue_touch:ue_touch,
                    ue_pain:ue_pain,
                    ue_temparature:ue_temparature,
                    ue_proprioception:ue_proprioception,
                    le_touch:le_touch,
                    le_pain:le_pain,
                    le_temparature:le_temparature,
                    le_proprioception:le_proprioception,

                    memory:memory,
                    attention:attention,
                    problem_solving:problem_solving,
                    insight:insight,
                    communication:communication,
                    psycho_emotion_changes:psycho_emotion_changes,
                    goals:goals,
                    perfomance_context:perfomance_context,
                    leisures:leisures,
                    progress:progress,
                },
            success:function(data){                
                $('#assement_informations').empty();
                $('#assement_informations').html(data);
            }
        });
        
    }
</script>
<?php
include("./includes/footer.php");
?>