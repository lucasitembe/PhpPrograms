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
    $therapist = $_SESSION['userinfo']['Employee_Name'];
    $Registration_ID = $_GET['Registration_ID'];
    $consultation_ID = $_GET['consultation_ID'];
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
?>
<input type="button" value="PREVIOUS RECORD" class="art-button-green">
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<center>
<br>
    <table width='100%' style='background: #006400 !important;color: white;'>
        <tr>
            <td>
        <center>
            <b>PEDIATRIC ASSESMENT FORM</b>
        </center>
        </td>
        </tr>
        <tr>
        </tr>
    </table>
<br>
</center>
<fieldset id="assement_informations">

    <!--Assement form-->
    <div style="padding:2% 10% 2% 10%; overflow-y: show;">
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
    <td>Therapist:</td>
    <th>'.$therapist.'</th>

</tr>
</table>
    ';

    }
}
  ?>
        <form action="">
        <center><legend>--Social situation --</legend></center>
        <table class="table table-bordered">
            <tr>
                <th>Home Situations</th>
                <th>Parent Works</th>
            </tr>
            <tr>
                <td><input type="text" name="home_situation" id="home_situation" style="border-radius:0px"></td>
                <td><input type="text" name="parent_works" id="parent_works" style="border-radius:0px"></td>
            </tr>
        </table>
        <center><legend>--Medical Information--</legend></center>
            <table class="table table-bordered">
                <tr>
                    <th class="art_td">Main Concerns of Parents <br><i>(caregiver/child)</i></th>
                    <td colspan="2"><textarea name="main_concern" id="main_concern"  rows="1" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <th class="art_td">Diagnosis</th>
                    <td ><textarea name="diagnosis" id="diagnosis"  rows="1" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <th class="art_td">Medication</th>
                    <td ><textarea name="medication" id="medication"  rows="1" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <th class="art_td">Vision</th>
                    <td colspan="2"><textarea name="vision" id="vision"  rows="1" class="form-control"></textarea></td>
                    
                </tr>
                <tr>
                    <th class="art_td">Hearing</th>
                    <td colspan="2"><textarea name="hearing" id="hearing"  rows="1" class="form-control"></textarea></td>
                   
                </tr>
                <tr>
                    <th class="art_td">Birth History</th>
                    <td colspan="2"><textarea name="birth_history" id="birth_history"  rows="1" class="form-control"></textarea></td>
                    
                </tr>
                <tr>
                    <th class="art_td">Current Medication Condition</th>
                    <td colspan="2"><textarea name="current_medication" id="current_medication"  rows="1" class="form-control"></textarea></td>
                    
                </tr>

            </table>

            <!--Physical Assement-->
            <center><legend>--Self care Skills--</legend></center>
            <table class="table table-bordered">
                   <tr>
                   <th class="art_td">Feeding</th>
                   <th><textarea name="feeding" id="feeding"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Grooming</th>
                   <th><textarea name="groming" id="groming"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Washing</th>
                   <th><textarea name="washing" id="washing"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Toileting</th>
                   <th><textarea name="toileting" id="toileting"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Dressing</th>
                   <th><textarea name="dressing" id="dressing"  rows="1"></textarea></th>
                   </tr>
            </table>
            <center><legend>--Play/Leisure--</legend></center>
            <table class="table table-bordered">
                   <tr>
                   <th class="art_td">School <br><i>(if not school how does child spend day)</i></th>
                   <th><textarea name="school" id="school"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Household Activities</th>
                   <th><textarea name="household_activities" id="household_activities"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Physical/Motor Skills <br><i>(tone, GM/FM, abnormal MVT and patterns etc)</i></th>
                   <th><textarea name="physical_motor_Skills" id="physical_motor_Skills"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Cognition/perception/sensory skills</th>
                   <th><textarea name="cognition" id="cognition"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Communication/social skills</th>
                   <th><textarea name="communication_skills" id="communication_skills"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Emotional/Psychological/Behaviour</th>
                   <th><textarea name="emotional_psychological" id="emotional_psychological"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Perfomance Context</th>
                   <th><textarea name="perfomance_context" id="perfomance_context"  rows="1"></textarea></th>
                   </tr>
                   <tr>
                   <th class="art_td">Treatment Goals</th>
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
                   <th class="art_td">Progress Notes</th>
                   <th><textarea name="progress" id="progress"  rows="1"></textarea></th>
                    </tr>
            </table>
            <table>
                <tr><input type="button" class='art-button pull-right' onclick="save_pediatric_assement_result(<?php echo $Registration_ID;?>,'<?php echo $therapist?>')" value="Save Pediatric Assement Informasions"></tr>
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


    function save_pediatric_assement_result(Registration_ID,therapist){
        var diagnosis = $('#diagnosis').val();
        var medication = $('#medication').val();
        var vision = $('#vision').val();
        var hearing = $('#hearing').val();
        var birth_history = $('#birth_history').val();
        var feeding = $('#feeding').val();
        var groming = $('#groming').val();
        var washing = $('#washing').val();
        var toileting = $('#toileting').val();
        var dressing = $('#dressing').val();
        var school = $('#school').val();
        var household_activities = $('#household_activities').val();
        var physical_motor_Skills = $('#physical_motor_Skills').val();
        var cognition = $('#cognition').val();
        var communication_skills = $('#communication_skills').val();
        var emotional_psychological = $('#emotional_psychological').val();
        var home_situation = $('#home_situation').val();
        var parent_works = $('#parent_works').val();
        var main_concern = $('#main_concern').val();
        var current_medication = $('#current_medication').val();
        var perfomance_context = $('#perfomance_context').val();
        var goals = $('#goals').val();
        var progress = $('#progress').val();
        //console.log(vision,hearing,birth_history,feeding,groming,washing);
    
        //alert(therapist);
        $.ajax({
            url: 'save_pediatric_assement_informations.php',
            type: 'POST',
            data: {
                    Registration_ID:Registration_ID,
                    therapist,therapist,
                    diagnosis:diagnosis,
                    medication:medication,
                    vision:vision,
                    hearing:hearing,
                    birth_history:birth_history,
                    feeding:feeding,
                    groming:groming,
                    washing:washing,
                    toileting:toileting,
                    dressing:dressing,
                    school:school,
                    household_activities:household_activities,
                    physical_motor_Skills:physical_motor_Skills,
                    cognition:cognition,
                    communication_skills:communication_skills,
                    emotional_psychological:emotional_psychological,
                    home_situation:home_situation,
                    parent_works:parent_works,
                    main_concern:main_concern,
                    current_medication:current_medication,
                    perfomance_context:perfomance_context,
                    goals:goals,
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