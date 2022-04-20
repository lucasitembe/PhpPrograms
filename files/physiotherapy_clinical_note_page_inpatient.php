<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php';
    
    $Common = new CommonInterface();
    $Admission_ID = $_GET['Admission_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $Consultation_ID = $_GET['Consultation_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Patient_Information = $Common->getPatientInformationByRegistrationNumber($Registration_ID);
    $Patient_Age = $Common->getCurrentPatientAge($Patient_Information[0]['Date_Of_Birth']);
?>

<a href="physiotherapy_ipd_patient_list.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">BACK</a>

<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?=$Patient_Payment_Item_List_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">
<input type="hidden" id="Registration_ID" value="<?=$Registration_ID?>">
<input type="hidden" id="Clinic_ID" value="<?=$clinic?>">
<input type="hidden" id="Sponsor_ID" value="<?=$Patient_Information[0]['Sponsor_ID']?>">
<input type="hidden" id="Consultation_ID" value="<?=$_GET['Consultation_ID']?>">
<span style="float: inline-end;background-color:#384f47;padding:5px;color:#fff"><b>PATIENT INFO</b> : <?=$Patient_Information[0]['Patient_Name']?>, <?=$Patient_Information[0]['Gender']?>, <?=$Patient_Age?></span>

<br><br>

<fieldset style="background-color: #f7f7f7;padding:6px;">
    <legend align='left' style="font-weight: 500;">PHYSIOTHERAPY CLINICAL NOTES</legend>

    <center>
        <table width='80%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">FINDINGS</td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'>FINDINGS</td>
                <td style="padding: 3px;"><textarea cols="30" rows="2" onkeyup="autoSaveInputs('Findings')" id="Findings"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'>NEUROVASCULAR</td>
                <td style="padding: 2px;"><textarea onkeyup="autoSaveInputs('neurovascular')" id="neurovascular" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'>FUNCTIONAL</td>
                <td style="padding: 2px;"><textarea onkeyup="autoSaveInputs('functional')" id="functional" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'>VITALS</td>
                <td style="padding-top: 15px;">
                    <input type='button' onclick="takeVitals()" class="art-button-green" style="background:#7d2b7c;" value="TAKE VITALS"/>
                    <!-- <input type='button' onclick="takeVitals()" class="art-button-green" value="PREVIOUS VITALS"/></td> -->
                </td>
            </tr>
        </table>
    </center>

    <br><br>

    <center>
        <table width='80%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">DIAGNOSIS</td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'><input type='button' onclick="selectDiagnosis(1)" class="art-button-green" value='PROVISIONAL DIAGNOSIS' style="background: sienna;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'><input type='button' onclick="selectDiagnosis(2)" class="art-button-green" value='DIFFERENTIAL DIAGNOSIS' style="background: #a02d53;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'><input type='button' onclick="selectDiagnosis(3)" class="art-button-green" value='FINAL DIAGNOSIS' style="background: #247d6d;"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='80%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">TREATMENT</td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'><input type='button' onclick="selectProcedure()" value='SELECT PROCEDURE' class="art-button-green"></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'><input type='button' onclick="pharmacyDialogue()" class="art-button-green" value="PHARMACY"/></td>
                <td style="padding: 3px;"><textarea id="" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='80%'>
            <tr style="background-color: #ddd;">
                <td style="padding:8px;font-weight:500" colspan="2">EVALUATION</td>
            </tr>

            <tr>
                <td style="padding: 20px;" width='20%'>REMARK</td>
                <td style="padding: 3px;"><textarea onkeyup="autoSaveInputs('remarks')" id="remarks" cols="30" rows="2"></textarea></td>
            </tr>
        </table>
    </center>

    <br>

    <center>
        <table width='80%' border="0">
            <tr style="background-color: #fff;">
                <td style="padding:8px;font-weight:500;"><a href="#" onclick="saveNotes()" style="float:right" class="art-button-green">SAVE NOTES</a></td>
            </tr>
            </tr>
        </table>
    </center>
</fieldset>

<div id="dialogue"></div>
<div id="diagnosis_space"></div>

<script>
    function pharmacyDialogue(){
        var Consultation_ID = $('#Consultation_ID').val();
        var Employee_ID = $('#Employee_ID').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var Registration_ID = $('#Registration_ID').val();

        let Patient_Details_For_Transaction = {
            Consultation_ID:Consultation_ID,
            Registration_ID:Registration_ID,
            Employee_ID:Employee_ID,
            Sponsor_ID:Sponsor_ID
        };

        $.ajax({
            type: "GET",
            url: "inpatient.pharmacy.dialogue.php",
            data: {
                Patient_Details_For_Transaction:Patient_Details_For_Transaction
            },
            success: (response) => {
                $("#dialogue").dialog({
                    autoOpen: false,
                    width: '90%',
                    height: 650,
                    title: 'PHARMACY',
                    modal: true
                });
                $("#dialogue").html(response);
                $("#dialogue").dialog("open");
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
            url: "inpatient_diagnosis.php",
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

    function autoSaveInputs(param){
        var input_value = $('#'+param).val();
        var Consultation_ID = <?=$_GET['Consultation_ID']?>;
        var Registration_ID = <?=$_GET['Registration_ID']?>;
        var Employee_ID = <?=$_SESSION['userinfo']['Employee_ID']?>;
        var Admission_ID = <?=$_GET['Admission_ID']?>;
        var column_name = param;

        let inputObject = {
            column_name:param,
            column_value:input_value,
            Consultation_ID:Consultation_ID,
            Registration_ID:Registration_ID,
            Employee_ID:Employee_ID,
            Admission_ID:Admission_ID
        }

        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {autoSavePatientNotes:'autoSavePatientNotes',inputObject:inputObject},
            success: (response) => {
                alert(response);
            }
        });
    }

    function saveNotes(){
        var main_complain = $('#main_complain').val();
        var findings = $('#findings').val();
        var neurovascular = $('#neurovascular').val();
        var functional = $('#functional').val();
        var remarks = $('#remarks').val();

        var Admission_ID = <?=$_GET['Admission_ID']?>;
        var Consultation_ID = <?=$_GET['Consultation_ID']?>;
        var Registration_ID = <?=$_GET['Registration_ID']?>;
        var Employee_ID = <?=$_SESSION['userinfo']['Employee_ID']?>;


        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                Admission_ID:Admission_ID,
                Consultation_ID:Consultation_ID,
                Registration_ID:Registration_ID,
                Employee_ID:Employee_ID,
                main_complain:main_complain,
                findings:findings,
                neurovascular:neurovascular,
                functional:functional,
                remarks:remarks,
                save_inpatient_note_for_physiotherapy:'save_inpatient_note_for_physiotherapy'
            },
            success: (response) => {
                if(response == 100){
                    alert('Notes Saved Successful');
                }
            }
        });
    }
</script>
