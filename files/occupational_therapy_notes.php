<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php';
    $Common = new CommonInterface();

    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $clinic = $_GET['clinic'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Patient_Information = $Common->createConsultationForPatient($Patient_Payment_Item_List_ID,$Registration_ID,$Employee_ID);
?>

<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?=$Patient_Payment_Item_List_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">
<input type="hidden" id="Registration_ID" value="<?=$Registration_ID?>">
<input type="hidden" id="Clinic_ID" value="<?=$clinic?>">
<input type="hidden" id="Sponsor_ID" value="<?=$Patient_Information[0]['Sponsor_ID']?>">
<input type="hidden" id="Consultation_ID" value="<?=$Patient_Information[0]['consultation_ID']?>">

<a href="physiotherapy_opd_patient_list.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">BACK</a>

<br>

<fieldset style="background-color: #f7f7f7;padding:6px;">
    <legend>OCCUPATIONAL NOTES</legend>

    <center>
        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">MEDICAL INFORMATION</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Chief Complain</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Current Presentation</td>
                <td style="padding: 2px;">
                    <textarea name="" id="current_presentation" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Medical History</td>
                <td style="padding: 2px;">
                    <textarea name="" id="medical_history" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Family History</td>
                <td style="padding: 2px;">
                    <textarea name="" id="social_history" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <center>
            <table width='76%'>
                <tr style="background-color: #ddd;">
                    <td style="padding:8px;font-weight:500" colspan="2">DIAGNOSIS</td>
                </tr>

                <tr>
                    <td style="padding: 8px;text-align:start;font-weight:500" width='28%'><input type='button' onclick="selectDiagnosis(1)" class="art-button-green" value='PROVISIONAL DIAGNOSIS' style="background: sienna;"></td>
                    <td style="padding: 3px;"><textarea id="" cols="30" rows="1.5"></textarea></td>
                </tr>

                <tr>
                    <td style="padding: 8px;text-align:start;font-weight:500" width='28%'><input type='button' onclick="selectDiagnosis(2)" class="art-button-green" value='DIFFERENTIAL DIAGNOSIS' style="background: #a02d53;"></td>
                    <td style="padding: 3px;"><textarea id="" cols="30" rows="1.5"></textarea></td>
                </tr>

                <tr>
                    <td style="padding: 8px;text-align:start;font-weight:500" width='28%'><input type='button' onclick="selectDiagnosis(3)" class="art-button-green" value='FINAL DIAGNOSIS' style="background: #247d6d;"></td>
                    <td style="padding: 3px;"><textarea id="" cols="30" rows="1.5"></textarea></td>
                </tr>
            </table>
        </center>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="3">PERFORMANCE COMPONENTS</td>
            </tr>

            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" rowspan="2" width='28%'>UE Movement</td>
                <td style="font-weight:500;padding: 8px" colspan="3">WNL/Impaired</td>
            </tr>

            <tr style="background-color: #eee;">
                <td style="font-weight:500;padding: 8px">Right</td>
                <td style="font-weight:500;padding: 8px">Left</td>
            </tr>

            <tr>
                <td style="padding: 8px">Shoulder Abduction</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Shoulder Flexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Shoulder Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Internal Rotation</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">External Rotation</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Elbow Flexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Elbow Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Pronation</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Supination</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Wrist Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Wrist Flexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Finger Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Finger Opposition</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 2px;" colspan="3">
                    <textarea name="" id="ue_movements" cols="30" rows="2"></textarea>
                </td>
            </tr>

            <!-- LE MOVEMENTS -->
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" rowspan="2" width='28%'>LE Movement</td>
                <td style="font-weight:500;padding: 8px" colspan="3">WNL/Impaired</td>
            </tr>

            <tr style="background-color: #eee;">
                <td style="font-weight:500;padding: 8px">Right</td>
                <td style="font-weight:500;padding: 8px">Left</td>
            </tr>

            <tr>
                <td style="padding: 8px">Hip Abduction</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Hip Flexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Hip Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Knee Extension</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Ankle Dorsiflexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Ankle Plantar Flexion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Inversion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px">Eversion</td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
                <td style="padding: 8px"><input type="radio" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 2px;" colspan="3">
                    <textarea name="" id="ue_movements" cols="30" rows="2"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">PHYSICAL ASSESSMENT</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Bed Mobility (rolling in lying, lying to sit)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="bed_mobility" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Sitting Balance (dynamic, static)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="sitting_balance" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Transfer Skills (push self up, weight shift, sit to stand, sit to sit)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="transfer_skills" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Standing (endurance, positioning)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="standing_endurance" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Mobility (walking gait, w/c propulsion)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="mobility_walking" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="5">SENSATION</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Vision, Hearing</td>
                <td style="padding: 2px;font-weight:500" colspan="5">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr style="background-color: #eee;">
                <td style="padding: 8px;" width='28%'></td>
                <td style="padding: 8px;font-weight:500">Touch</td>
                <td style="padding: 8px;font-weight:500">Pain</td>
                <td style="padding: 8px;font-weight:500">Temperature</td>
                <td style="padding: 8px;font-weight:500">Proprioception</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>UE</td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>LE</td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
                <td style="padding: 8px;font-weight:500"><input type="text" name="" id=""></td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">COGNITIVE ASSESSMENT</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Memory (daily task, faces, names)</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Attention (attention span, divided attention)</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Problem Solving</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Memory (daily task, faces, names)</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Communication (receptive, expressive abilities, word finding)</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Psycho-emotion Changes (personality, mood, emotion lability, behavior)</td>
                <td style="padding: 5px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">PERFORMANCE AREA</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Feeding</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Grooming/Washing</td>
                <td style="padding: 2px;">
                    <textarea name="" id="grooming_washing" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Toileting</td>
                <td style="padding: 2px;">
                    <textarea name="" id="toileting" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Dressing</td>
                <td style="padding: 2px;">
                    <textarea name="" id="dressing" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Leisures</td>
                <td style="padding: 2px;">
                    <textarea name="" id="leisures" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Productivity (works, roles at home)</td>
                <td style="padding: 2px;">
                    <textarea name="" id="productivity_works" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>
        
        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">PERFOMANCE CONTEXT</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>.</td>
                <td style="padding: 2px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">TREATMENT</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Goals </td>
                <td style="padding: 5px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>PROPOSED PROCEDURE</td>
                <td style="padding: 5px;font-weight:500">
                    <input type='button' onclick="selectProcedure()" value='SELECT PROCEDURE' class="art-button-green">
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Procedure Remarks</td>
                <td style="padding: 5px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">EVALUATION</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Progress Notes </td>
                <td style="padding: 5px;font-weight:500">
                    <textarea name="" id="performance_area" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding: 8px;" width='28%'><a href="#" style="float: inline-end;" onclick="saveNote()" class="art-button-green">SAVE NOTES</a></td>
            </tr>
        </table>
    </center>
</fieldset>

<div id="procedure_space"></div>
<div id="diagnosis_space"></div>

<script>
    function saveNote(){
        var main_complain = $('#main_complain').val();
        var current_presentation = $('#current_presentation').val();
        var medical_history = $('#medical_history').val();
        var social_history = $('#social_history').val();
        var bed_mobility = $('#bed_mobility').val();

        var sitting_balance = $('#sitting_balance').val();
        var transfer_skills = $('#transfer_skills').val();
        var standing_endurance = $('#standing_endurance').val();

        var mobility_walking = $('#mobility_walking').val();
        var performance_area = $('#performance_area').val();
        var grooming_washing = $('#grooming_washing').val();
        var toileting = $('#toileting').val();

        var dressing = $('#dressing').val();
        var leisures = $('#leisures').val();
        var productivity_works = $('#productivity_works').val();



    }

    function selectProcedure(){
        var Patient_Payment_Item_List_ID = <?=$_GET['Patient_Payment_Item_List_ID']?>;
        var Consultation_ID = <?=$Patient_Information[0]['consultation_ID']?>;
        var Employee_ID = <?=$_SESSION['userinfo']['Employee_ID']?>;
        var Sponsor_ID = <?=$Patient_Information[0]['Sponsor_ID']?>;
        var Registration_ID = <?=$_GET['Registration_ID']?>;

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
</script>

<?php 
    include './includes/footer.php'; 
?>