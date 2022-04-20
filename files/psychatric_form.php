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

    .pre_asses{
        font-size: 10px;
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
    a{
        text-decoration: none;
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
    $consultation_ID = $_GET['consultation_ID'];
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    $therapist = $_SESSION['userinfo']['Employee_Name'];
    $therapists = $_SESSION['userinfo']['Employee_ID'];
    if(isset($_GET['Psychatric_assessment_ID'])){
        $Psychatric_assessment_ID = $_GET['Psychatric_assessment_ID'];
        $therapistID =$_GET['therapist'];
        
        if($therapistID ==$therapists){ 
            $updates='yes';
        }else{
            $updates='no';
        }
    }else{
        $Psychatric_assessment_ID=0;

    }
?>
    <input type="button" value="PREVIOUS RECORD" class="art-button-green" onclick="previousRecord()">
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
<center>
<br>
    <table width='100%' style='background: #006400 !important;color: white;'>
        <tr>
            <td>
        <center>
            <b>OCCUPATIONAL THERAPY FORM</b>
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

        $assement_result = mysqli_query($conn,"SELECT * FROM tbl_psychatric_assement WHERE Registration_ID = '$Registration_ID' AND Psychatric_assessment_ID = '$Psychatric_assessment_ID'") or die(mysqli_error($conn));
        while($assement_result_rows = mysqli_fetch_assoc($assement_result)){
            $Cheif_complain = $assement_result_rows['Cheif_complain'];  
            $past_medical_history = $assement_result_rows['past_medical_history']; 
            $past_psychatric_history = $assement_result_rows['past_psychatric_history'];
            $productivity = $assement_result_rows['productivity'];
            $Mental_status_examination = $assement_result_rows['Mental_status_examination'];
            $siting_balance = $assement_result_rows['siting_balance'];
            $transfer_skills = $assement_result_rows['transfer_skills'];
            $history_of_present_illness = $assement_result_rows['history_of_present_illness'];
            $mobility = $assement_result_rows['mobility'];
            $feeding = $assement_result_rows['feeding'];
            $groming_washing = $assement_result_rows['groming_washing'];
            $toileting = $assement_result_rows['toileting'];
            $dressing = $assement_result_rows['dressing'];
            $Psychatric_assessment_ID = $assement_result_rows['Psychatric_assessment_ID'];

            $memory = $assement_result_rows['memory']; 
            $attention = $assement_result_rows['attention'];
            $problem_solving = $assement_result_rows['problem_solving'];
            $occupational_assesment = $assement_result_rows['occupational_assesment'];
            $communication = $assement_result_rows['communication'];
            $psycho_emotion_changes = $assement_result_rows['psycho_emotion_changes'];
            $therapist = $assement_result_rows['therapist'];
            $Plan = $assement_result_rows['Plan'];
            $leasure = $assement_result_rows['leasure'];
            $perfomance_context = $assement_result_rows['perfomance_context'];
            $Procedure_remarks = $assement_result_rows['Procedure_remarks'];
            $created_at = $assement_result_rows['created_at'];
        
        }

  ?>
    <!--Assement form-->
    <div style="padding:2% 10% 2% 10%; overflow-y: show;">
        <form action="">
        <center><legend>--Assement Form--</legend></center>
            <table class="table table-bordered" >
                <tr>
                    <td class="art_td">Cheif Complain</td>
                    <th><textarea name="Cheif_complain" id="Cheif_complain"  rows="1" class="form-control"><?= $Cheif_complain ?></textarea></th>
                </tr>
                <tr>
                    <td >Past Medical History</td>
                    <th><textarea name="past_medical_history" id="past_medical_history"  rows="1" class="form-control"><?= $past_medical_history ?></textarea></th>
                </tr>
                <!-- <tr>
                    <td >Past Psychatric History</td>
                    <th><textarea name="past_psychatric_history" id="past_psychatric_history"  rows="1" class="form-control"></textarea></th>
                </tr> -->
            
                   <tr>
                   <td class="art_td">History of present illiness</td>
                   <th><textarea name="history_of_present_illness" id="history_of_present_illness"  rows="1"><?= $history_of_present_illness ?></textarea></th>
                   </tr>
                   <tr>
                   <td>Occupational Assesment <br> <i>(Self-care/ Productive / Leisure)</i></td>
                   <th><textarea name="occupational_assesment" id="occupational_assesment"  rows="1"><?= $occupational_assesment ?></textarea></th>
                   </tr>
                   <tr>
                   <td>Mental Status Examination</td>
                   <th><textarea name="Mental_status_examination" id="Mental_status_examination"  rows="1"><?= $Mental_status_examination ?></textarea></th>
                   </tr>
                   <tr>
                   
                </tbody>
            </table>
            <center><legend>--self-care Area--</legend></center>
            <table class="table table-bordered">
                <tr></tr>
               <tbody>
                   <tr>
                   <td class="art_td">Feeding</td>
                   <th><textarea name="feeding" id="feeding"  rows="1"><?= $feeding ?></textarea></th>
                   </tr>
                   <tr>
                   <td>Grooming/Washing</td>
                   <th><textarea name="groming_washing" id="groming_washing"  rows="1"><?= $groming_washing ?></textarea></th>
                   </tr>
                   <tr>
                   <td>Toileting</td>
                   <th><textarea name="toileting" id="toileting"  rows="1"><?= $toileting ?></textarea></th>
                   </tr>
                   <tr>
                   <td>Dressing</td>
                   <th><textarea name="dressing" id="dressing"  rows="1"><?= $dressing ?></textarea></th>
                   </tr>
                   <tr>
                    <td >Leisures</td>
                    <th><textarea name="leisures" id="leisures"  rows="1" class="form-control"><?= $leisures ?></textarea></th>
                    </tr>
                   <tr>
                    <td >Productivity<br>(<i>works, roles at home<i>)</td>
                    <th><textarea name="productivity" id="productivity"  rows="1" class="form-control"><?= $productivity ?></textarea></th>
                    </tr>
               </tbody>
            </table>
           
            <center><legend>--Cognitive Assement--</legend></center>
            <table class="table table-bordered">
                <tr>
                   <td class="art_td">Memory<br><i>(daily task, faces, names)</i></td>
                   <th><textarea name="memory" id="memory"  rows="1"></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Attention<br><i>(attention span, divided attention)</i></td>
                   <th><textarea name="attention" id="attention"  rows="1"><?= $memory ?></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Problem Solving</td>
                   <th><textarea name="problem_solving" id="problem_solving"  rows="1"> <?= $problem_solving ?></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Insight<br><i>(self-awareness)</i></td>
                   <th><textarea name="insight" id="insight"  rows="1"><?= $insight ?></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Communication<br><i>(receptive, expressive abilities, word finding)</i></td>
                   <th><textarea name="communication" id="communication"  rows="1"><?= $communication ?></textarea></th>
                </tr>
                <tr>
                   <td class="art_td">Psycho-emotion Changes<br><i>(personality, mood, emotion lability, behavior)</i></td>
                   <th><textarea name="psycho_emotion_changes" id="psycho_emotion_changes"  rows="1"><?= $psycho_emotion_changes ?></textarea></th>
                </tr>
                <tr>
                    <td >Perfomance Context</td>
                    <th><textarea name="perfomance_context" id="perfomance_context"  rows="1" class="form-control"><?= $perfomance_context ?></textarea></th>
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
                   <td class="art_td">Plan</td>
                   <th><textarea name="Plan" id="Plan"  rows="1"><?= $Plan ?></textarea></th>
                </tr>
            </table>
            <table>
                <?php 
                    if($updates=='yes'){ ?>
                        <tr><input type="button" class='art-button pull-right' onclick="update_assement_result('<?php echo $Registration_ID;?>','<?php echo $Psychatric_assessment_ID?>')" value="Update Therapy Notes"></tr>
                    <?php }else{ ?>
                        <tr><input type="button" class='art-button pull-right' onclick="save_assement_result(<?php echo $Registration_ID;?>,'<?php echo $therapist?>')" value="Save Therapy Notes"></tr>
                  <?php  }
                ?>
                
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
        var Cheif_complain = $('#Cheif_complain').val();
        var past_medical_history = $('#past_medical_history').val();
        var past_psychatric_history = $('#past_psychatric_history').val();
        var productivity = $('#productivity').val();
        var history_of_present_illness = $('#history_of_present_illness').val();
        var occupational_assesment = $('#occupational_assesment').val();
        var Mental_status_examination = $('#Mental_status_examination').val();
        var consultation_ID ='<?php echo $consultation_ID; ?>';
        
        // var mobility = $('#mobility').val();
        var feeding = $('#feeding').val();
        var groming_washing = $('#groming_washing').val();
        var toileting = $('#toileting').val();
        var dressing = $('#dressing').val();

        var memory = $('#memory').val();
        var attention = $('#attention').val();
        var problem_solving = $('#problem_solving').val();
        var insight = $('#insight').val();
        var communication = $('#communication').val();
        var psycho_emotion_changes = $('#psycho_emotion_changes').val();
        var Plan = $('#Plan').val();
        var perfomance_context = $('#perfomance_context').val();
        var leisures = $('#leisures').val();
        var Procedure_remarks = $('#Procedure_remarks').val();
        //alert(Registration_ID);
        $.ajax({
            url: 'psychatric_form_sv.php',
            type: 'POST',
            data: {
                    Registration_ID:Registration_ID,consultation_ID:consultation_ID,
                    therapist:therapist,
                    Cheif_complain:Cheif_complain,
                    past_medical_history:past_medical_history,
                    past_psychatric_history:past_psychatric_history,
                    productivity:productivity,
                    history_of_present_illness:history_of_present_illness,
                    occupational_assesment:occupational_assesment,
                    Mental_status_examination:Mental_status_examination,
                    
                    feeding:feeding,
                    groming_washing:groming_washing,
                    toileting:toileting,
                    dressing:dressing,

                    memory:memory,
                    attention:attention,
                    problem_solving:problem_solving,
                    insight:insight,
                    communication:communication,
                    psycho_emotion_changes:psycho_emotion_changes,
                    Plan:Plan,
                    perfomance_context:perfomance_context,
                    leisures:leisures,
                    Procedure_remarks:Procedure_remarks,
                },
            success:function(data){                
                $('#assement_informations').empty();
                $('#assement_informations').html(data);
            }
        });
        
    }

    function previousRecord(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        $.ajax({
            url:'psychatric_form_open.php',
            type:'post',
            data:{ Registration_ID:Registration_ID, opendialogue:''},
            success:function(responce){
                $("#opendialogue").dialog({
                        title: 'THERAPY TREATMENT RECORD ',
                        width: '60%',
                        height: 500,
                        modal: true,
                });
                $("#opendialogue").html(responce);
            }
        });
    }
</script>
<?php
include("./includes/footer.php");
?>