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

<fieldset style="background-color: #f7f7f7;padding:6px;">
    <legend>OCCUPATIONAL THERAPY NOTES FOR PAEDIATRIC</legend>

    <center>
        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">CARE GIVER INFORMATION</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Home Situations</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Parent Works</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

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
                <td style="padding: 8px;" width='28%'>Current Medical History</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Past Medical History</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Birth History</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>
        </table>

        <br>

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

        <br>

        <table width='76%'>
            <tr style="background-color: #eee;">
                <td style="padding: 8px;font-weight:500" colspan="2">PERFOMANCE COMPONENTS</td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Gross Motor Skills</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Fine Motor Skills</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Cognition</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Sensory Skills</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Perception Skills</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Behavior / Psychological</td>
                <td style="padding: 2px;">
                    <textarea name="" id="main_complain" cols="30" rows="1.5"></textarea>
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
                <td style="padding: 8px;" width='28%'>Bathing</td>
                <td style="padding: 2px;">
                    <textarea name="" id="productivity_works" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Leisures</td>
                <td style="padding: 2px;">
                    <textarea name="" id="leisures" cols="30" rows="1.5"></textarea>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;" width='28%'>Productivity or Schooling</td>
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

<div id="diagnosis_space"></div>

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
</script>

<?php 
    include './includes/footer.php'; 
?>