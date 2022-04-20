<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php';
    
    $Common = new CommonInterface();
    $display_case_type = "";
    $id__ = "";
    $display_case_type = ""; 
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $clinic = $_GET['clinic'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Patient_Information = $Common->createConsultationForPatient($Patient_Payment_Item_List_ID,$Registration_ID,$Employee_ID);
    $Patient_Age = $Common->getCurrentPatientAge($Patient_Information[0]['Date_Of_Birth']);
?>

<a href="physiotherapy_opd_patient_list.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">BACK</a>

<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?=$Patient_Payment_Item_List_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">
<input type="hidden" id="Registration_ID" value="<?=$Registration_ID?>">
<input type="hidden" id="Clinic_ID" value="<?=$clinic?>">
<input type="hidden" id="Sponsor_ID" value="<?=$Patient_Information[0]['Sponsor_ID']?>">
<input type="hidden" id="Consultation_ID" value="<?=$Patient_Information[0]['consultation_ID']?>">
<span style="float: inline-end;background-color:#384f47;padding:5px;color:#fff"><b>PATIENT INFO</b> : <?=$Patient_Information[0]['Patient_Name']?>, <?=$Patient_Information[0]['Gender']?>, <?=$Patient_Age?></span>

<br><br>

<fieldset style="background-color: #f7f7f7;padding:6px;">
    <legend align='left' style="font-weight: 500;">PHYSIOTHERAPY CLINICAL NOTES</legend>
    
    <center>
    <table width='76%'>
        <tr style="background-color: #ddd;">
            <td style="padding:8px;font-weight:500" colspan="2">COMPLAIN</td>
        </tr>

        <tr>
            <td style="padding: 20px;font-weight:500" width='20%'>MAIN COMPLAIN</td>
            <td style="padding: 3px;"><textarea cols="30" rows="2" id="main_complain"><?=$Patient_Information[0]['maincomplain']?></textarea></td>
        </tr>

        <tr>
            <td style="padding: 20px;font-weight:500" width='20%'>HISTORY OF PRESENT ILLNESS</td>
            <td style="padding: 3px;"><textarea cols="30" rows="2" id='history_of_past_illness'><?=$Patient_Information[0]['history_present_illness']?></textarea></td>
        </tr>

        <tr>
            <td style="padding: 20px;font-weight:500" width='20%'>PAST MEDICAL HISTORY</td>
            <td style="padding: 3px;"><textarea cols="30" rows="2" id="past_medical_history"><?=$Patient_Information[0]['past_medical_history']?></textarea></td>
        </tr>

        <tr>
            <td style="padding: 20px;font-weight:500" width='20%'>RELEVANT,SOCIAL & FAMILY HISTORY</td>
            <td style="padding: 3px;"><textarea cols="30" rows="2" id="relevant_social_and_family_history"><?=$Patient_Information[0]['family_social_history']?></textarea></td>
        </tr>

        <tr>
            <td style="padding: 20px;font-weight:500" width='20%'>PATIENT CASE TYPE</td>
            <td style="padding: 10px;">
                <select id="case_type" style="width: 100%;padding:8px">
                    <option value="">Case Type</option>
                    <option value="new_case">New Case</option>
                    <option value="continue_case">Return Case</option>
                </select>
            </td>
        </tr>
    </table>
    </center>

    <br>

    <center>
        <table width='76%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">PHYSICAL EXAMINATION</td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>GENERAL OBSERVATION</td>
                <td style="padding: 3px;"><textarea cols="30" rows="2" id="general_observation"><?=$Patient_Information[0]['general_observation']?></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>LOCAL EXAMINATION</td>
                <td style="padding: 3px;"><textarea cols="30" rows="2" id="local_examination"><?=$Patient_Information[0]['local_examination']?></textarea></td>
            </tr>


            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>NEUROVASCULAR</td>
                <td style="padding: 2px;"><textarea id="neurovascular" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>FUNCTIONAL</td>
                <td style="padding: 2px;"><textarea id="functional" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>VITALS</td>
                <td style="padding-top: 15px;">
                    <input type='button' onclick="takeVitals()" class="art-button-green" style="background:#7d2b7c;" value="TAKE VITALS"/>
                    <input type='button' onclick="takeVitals()" class="art-button-green" value="PREVIOUS VITALS"/></td>
                </td>
            </tr>
        </table>
    </center>

    <br><br>

    <center>
        <table width='76%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">DIAGNOSIS</td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'><input type='button' onclick="selectDiagnosis(1)" class="art-button-green" value='PROVISIONAL DIAGNOSIS' style="background: sienna;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'><input type='button' onclick="selectDiagnosis(2)" class="art-button-green" value='DIFFERENTIAL DIAGNOSIS' style="background: #a02d53;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'><input type='button' onclick="selectDiagnosis(3)" class="art-button-green" value='FINAL DIAGNOSIS' style="background: #247d6d;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='76%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">TREATMENT</td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'><input type='button' onclick="selectProcedure()" value='SELECT PROCEDURE' class="art-button-green"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'><input type='button' onclick="pharmacyDialogue()" class="art-button-green" value="PHARMACY"/></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='76%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">EVALUATION</td>
            </tr>

            <tr>
                <td style="padding: 20px;font-weight:500" width='20%'>REMARK</td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='76%' border="0">
            <tr style="background-color: #fff;">
                <td style="padding:8px;font-weight:500;"><a href="#" onclick="saveNotes()" style="float:right" class="art-button-green">SAVE NOTES</a></td>
            </tr>
            </tr>
        </table>
    </center>
</fieldset>

<div id="vitals_space"></div>
<div id="diagnosis_space"></div>
<div id="procedure_space"></div>

<script>
    function selectDiagnosis(param){
        var Patient_Payment_Item_List_ID = $('#Patient_Payment_Item_List_ID').val();
        var Consultation_ID = $('#Consultation_ID').val();
        var Employee_ID = $('#Employee_ID').val();
        var title,other = "";
        if(param == 1){
            title = "PROVISIONAL DIAGNOSIS";
            other = 'addProvisional';
        }else if(param == 2){
            title = "DIFFERENTIAL DIAGNOSIS";
            other = 'addDifferential';
        }else if(param == 3){
            title = "FINAL DIAGNOSIS";
            other = 'addFinal';
        }

        $.ajax({
            type: "GET",
            url: "physiotherapy_diagnosis.php",
            data: {
                other:other,
                Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                Consultation_ID:Consultation_ID,
                other:other,
                Employee_ID:Employee_ID
            },
            success:  (response) => {
                $("#diagnosis_space").dialog({
                    autoOpen: false,
                    width: '80%',
                    top:0,
                    height: 550,
                    title: title,
                    modal: true
                });
                $("#diagnosis_space").html(response);
                $("#diagnosis_space").dialog("open");
            }
        });
    }

    function pharmacyDialogue(){
        var Patient_Payment_Item_List_ID = $('#Patient_Payment_Item_List_ID').val();
        var Consultation_ID = $('#Consultation_ID').val();
        var Employee_ID = $('#Employee_ID').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var Registration_ID = $('#Registration_ID').val();

        $.ajax({
            type: "GET",
            url: "pharmacy.dialogue.php",
            data: {
                Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                Consultation_ID:Consultation_ID,
                Employee_ID:Employee_ID,
                Sponsor_ID:Sponsor_ID,
                Registration_ID:Registration_ID
            },
            success: (response) => {
                $("#procedure_space").dialog({
                    autoOpen: false,
                    width: '90%',
                    height: 580,
                    title: 'SELECT PROCEDURE',
                    modal: true
                });
                $("#procedure_space").html(response);
                $("#procedure_space").dialog("open");
            }
        });
    }

    function selectProcedure(){
        var Patient_Payment_Item_List_ID = $('#Patient_Payment_Item_List_ID').val();
        var Consultation_ID = $('#Consultation_ID').val();
        var Employee_ID = $('#Employee_ID').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var Registration_ID = $('#Registration_ID').val();

        $.ajax({
            type: "GET",
            url: "select_procedure.php",
            data: {
                Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                Consultation_ID:Consultation_ID,
                Employee_ID:Employee_ID,
                Sponsor_ID:Sponsor_ID,
                Registration_ID:Registration_ID
            },
            success: (response) => {
                $("#procedure_space").dialog({
                    autoOpen: false,
                    width: '90%',
                    height: 580,
                    title: 'SELECT PROCEDURE',
                    modal: true
                });
                $("#procedure_space").html(response);
                $("#procedure_space").dialog("open");
            }
        });
    }

    function saveNotes(){
        var unique_id = $('#unique_id').val();
        var unique_id_ = $('#'+unique_id).val();

        var Patient_Payment_Item_List_ID = <?=$_GET['Patient_Payment_Item_List_ID']?>;
        var Registration_ID = <?=$_GET['Registration_ID']?>;
        var Employee_ID = <?=$_SESSION['userinfo']['Employee_ID']?>;
        var main_complain = $('#main_complain').val();
        var history_of_past_illness = $('#history_of_past_illness').val();
        var past_medical_history = $('#past_medical_history').val();
        var relevant_social_and_family_history = $('#relevant_social_and_family_history').val();
        var case_type = $('#case_type').val();
        var general_observation = $('#general_observation').val();
        var local_examination = $('#local_examination').val();
        var neurovascular = $('#neurovascular').val();
        var functional = $('#functional').val();
        var procedure_comments = $('#procedure_comments').val();
        var evolution = $('#evolution').val();


        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
                Registration_ID:Registration_ID,
                Employee_ID:Employee_ID,
                main_complain:main_complain,
                history_of_past_illness:history_of_past_illness,
                past_medical_history:past_medical_history,
                relevant_social_and_family_history:relevant_social_and_family_history,
                case_type:case_type,
                general_observation:general_observation,
                local_examination:local_examination,
                neurovascular:neurovascular,
                functional:functional,
                procedure_comments:procedure_comments,
                evolution:evolution,
                save_physiotherapy_notes_outpatient:'save_physiotherapy_notes_outpatient'
            },
            success: (response) => {
                alert(response);
            }
        });
        
    }

    function takeVitals(){
        var unique_id = $('#unique_id').val();
        var unique_id_ = $('#'+unique_id).val();
        $.ajax({
            type: "GET",
            url: "physiotherapy_vitals.php",
            data: {
                unique_id:unique_id,
                unique_id_:unique_id_
            },
            success:  (response) => {
                $("#vitals_space").dialog({
                    autoOpen: false,
                    width: '50%',
                    height: 550,
                    title: 'TAKE VITALS',
                    modal: true
                });
                $("#vitals_space").html(response);
                $("#vitals_space").dialog("open");
            }
        });
    }
</script>

<?php include './includes/footer.php'; ?>