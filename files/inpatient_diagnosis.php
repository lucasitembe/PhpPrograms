<?php 
    $Consultation_ID = $_GET['Consultation_ID'];
    $other = $_GET['other'];
    $Employee_ID = $_GET['Employee_ID'];
?>

<input type="hidden" id="Consultation_ID" value="<?=$Consultation_ID?>">
<input type="hidden" id="other" value="<?=$other?>">
<input type="hidden" id="Patient_Payment_Item_List_ID" value="<?=$Patient_Payment_Item_List_ID?>">
<input type="hidden" id="Employee_ID" value="<?=$Employee_ID?>">

<fieldset style="display: flex;height:480px;border:1px solid #ccc !important">
    <fieldset style="flex: 30%;border:1px solid #ccc !important">
        <table width='100%'>
            <tr>
                <td><input type="text" id="diseases_name_" placeholder="Diseases Name" onkeyup="loadDisease()"></td>
            </tr>
        </table>

        <fieldset style="border: 1px solid #ccc !important;border:1px solid #ccc !important;height:420px;overflow-y:scroll">
            <table width='100%'>
                <tr><td style="padding:6px;font-weight:500" colspan="2">DISEASES NAME</td></tr>
                <tbody id="display_diseases"></tbody>
            </table>
        </fieldset>
    </fieldset>

    <fieldset style="flex:70%;border:1px solid #ccc !important">
        <table width='100%'>
            <tr>
                <td style="font-weight:500;padding: 6px;text-align:center">S/N</td>
                <td style="font-weight:500;padding: 6px;">DISEASES</td>
                <td style="font-weight:500;padding: 6px;">CODE</td>
                <td style="font-weight:500;padding: 6px;">ACTION</td>
            </tr>

            <tbody id="display_ready_added_diagnosis"></tbody>
        </table>
    </fieldset>
</fieldset>

<script>
    $(document).ready(() => {
        loadDisease();
        loadDiseaseIfAddedAlready();
    });

    function loadDisease(){
        var disease_name = $('#diseases_name_').val();
        var Consultation_ID = $('#Consultation_ID').val();
        var other = $('#other').val();

        $.ajax({
            type: "GET",
            url: "common/common.php",
            data: {
                disease_name:disease_name,
                Consultation_ID:Consultation_ID,
                other:other,
                load_diseases:'load_diseases'
            },
            success: (response) => {
                $('#display_diseases').html(response);
            }
        });
    }

    function addProvisional(Diseases_ID,Consultation_ID){
        var Employee_ID = $('#Employee_ID').val();
        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                Diseases_ID:Diseases_ID,
                Consultation_ID:Consultation_ID,
                Employee_ID:Employee_ID,
                diagnosis_type:'provisional_diagnosis',
                add_diagnosis_to_a_patient:'add_diagnosis_to_a_patient',
                patient_type:'inpatient'
            },
            success: (response) => {
                if(response ==  300){
                    alert('Diagnosis already added');
                }else if(response == 100){
                    loadDiseaseIfAddedAlready();
                    console.log('added')
                }else{
                    alert('Diagnosis was not added contact admin for support '+ response);
                }
            }
        });
    }

    function removeDiagnosis(disease_id,diagnosis_type){
        var Consultation_ID = $('#Consultation_ID').val();
        var type = "";

        if(diagnosis_type == 1){
            type = "provisional_diagnosis"
        }else{
            type = "diagnosis"
        }

        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                type:type,
                disease_id:disease_id,
                Consultation_ID:Consultation_ID,
                remove_disease_outpatient_list:'remove_disease_outpatient_list'
            },
            success: (response) => {
                loadDiseaseIfAddedAlready();
            }
        });
    }

    function addDifferential(Diseases_ID,Consultation_ID){
        // alert(Diseases_ID+' = '+Consultation_ID);
    }

    function addFinal(Diseases_ID,Consultation_ID){
        var Employee_ID = $('#Employee_ID').val();
        $.ajax({
            type: "POST",
            url: "common/common.php",
            data: {
                Diseases_ID:Diseases_ID,
                Consultation_ID:Consultation_ID,
                Employee_ID:Employee_ID,
                diagnosis_type:'diagnosis',
                add_diagnosis_to_a_patient:'add_diagnosis_to_a_patient'
            },
            success: (response) => {
                if(response ==  300){
                    alert('Diagnosis already added');
                }else if(response == 100){
                    loadDiseaseIfAddedAlready();
                    console.log('added')
                }else{
                    alert('Diagnosis was not added contact admin for support');
                }
            }
        });
    }

    function loadDiseaseIfAddedAlready(){
        var other = $('#other').val();
        var Consultation_ID = $('#Consultation_ID').val();
        var type = "";

        if(other == "addFinal"){
            type = "diagnosis";
        }else{
            type = "provisional_diagnosis";
        }

        $.ajax({
            type: "GET",
            url: "common/common.php",
            data: {
                Consultation_ID:Consultation_ID,
                patient_type:'inpatient',
                diagnosis_type:type,
                other:other,
                load_diagnosis_for_inpatient:'load_diagnosis_for_inpatient'
            },
            success: (response) => {
                $('#display_ready_added_diagnosis').html(response);
            }
        });
    }
</script>