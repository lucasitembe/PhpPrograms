<?php  
    include './includes/header.php'; 
    include 'common/common.interface.php'; 
    $Interface = new CommonInterface();
?>

<a href="physiotherapy_opd_patient_list.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">OPD PATIENT LIST</a>
<a href="physiotherapy.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">BACK</a>

<br><br>

<center>
<fieldset>
    <table width='90%'>
        <tr>
            <td width='30%'><input type="text" style="padding: 6px;text-align:center" onkeyup="loadPatientList()" id="patient_name" placeholder="Patient Name"></td>
            <td width='30%'><input type="text" style="padding: 6px;text-align:center" onkeyup="loadPatientList()" id="patient_number" placeholder="Patient Number"></td>
            <td width='30%'>
                <select style="width: 100%;padding:7px" id="hospital_ward_id" onchange="loadPatientList()">
                    <?php foreach($Interface->getWardsAssignedToEmployee($_SESSION['userinfo']['Employee_ID']) as $ward) : ?>
                        <option value="<?=$ward['Hospital_Ward_ID']?>"><?=$ward['Hospital_Ward_Name']?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
</fieldset>
</center>

<fieldset style="height: 550px;overflow-y:scroll">
    <legend align='center'>PHYSIOTHERAPY IPD PATIENT LIST</legend>
    <table width='100%'>
        <tr style="background-color: #ddd;">
            <td style="font-weight:500;padding:8px" width='5%'><center>S/N</center></td>
            <td style="font-weight:500;padding:8px" width='15%'>PATIENT NAME</td>
            <td style="font-weight:500;padding:8px" width='15%'>PATIENT NUMBER</td>
            <td style="font-weight:500;padding:8px" width='15%'>AGE</td>
            <td style="font-weight:500;padding:8px" width='15%'>SPONSOR</td>
            <td style="font-weight:500;padding:8px" width='15%'>GENDER</td>
            <td style="font-weight:500;padding:8px" width='15%'>PHONE NUMBER</td>
        </tr>

        <tbody id="display_patients"></tbody>
    </table>
</fieldset>

<script>
    $(document).ready(() => {
        loadPatientList();
    });

    function loadPatientList(){
        var hospital_ward_id = $('#hospital_ward_id').val();
        var patient_name = $('#patient_name').val();
        var patient_number = $('#patient_number').val();
        $('#display_patients').html("<tr style='background-color:#fff'><td colspan='7' style='text-align:center;colspan='16'><center><img src='./images/ajax-loader_1.gif' style='text-align:center'/></center></td></tr>");

        $.ajax({
            type: "GET",
            url: "common/common.php",
            cache:false,
            data: {
                hospital_ward_id:hospital_ward_id,
                patient_name:patient_name,
                Clinic_ID:<?=$_GET['clinic']?>,
                patient_number:patient_number,
                load_inpatient_list:'load_inpatient_list'
            },
            success: (response) => {
                $('#display_patients').html(response);
            }
        });
    }

    function toPhysiotherapyNotes(Admission_ID,Registration_ID,Consultation_ID,Clinic_ID){
        window.location = 'physiotherapy_clinical_note_page_inpatient.php?Admission_ID='+Admission_ID+'&Registration_ID='+Registration_ID+'&Patient_Type=Inpatient&Consultation_ID='+Consultation_ID+'&clinic='+Clinic_ID;
    }
</script>

<?php include './includes/footer.php' ?>