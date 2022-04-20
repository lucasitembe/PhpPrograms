<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php'; 
    
    $Result = new CommonInterface();
    $duration = 1;
    $Filter_Value = substr($Result->getCurrentDateTime(),0,11);

    $mod_date=date_create($Filter_Value);
    date_sub($mod_date,date_interval_create_from_date_string("$duration days"));
    $newdate =  date_format($mod_date,"Y-m-d");

    $Start_Date = $newdate.' 00:00';
    $End_Date = $Result->getCurrentDateTime();
?>

<a href="physiotherapy_ipd_patient_list.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">IPD PATIENT LIST</a>
<a href="Procedure.php?ProcurementWorkPage=ProcurementWorkPageThisPage" class="art-button-green">PERFORM PROCEDURES</a>
<a href="physiotherapy.php?clinic=<?=$_GET['clinic']?>" class="art-button-green">BACK</a>

<input type="hidden" value="<?=$_GET['clinic']?>" id="Clinic_ID">
<br><br>
<fieldset>
    <table width='100%'>
        <tr>
            <td><input type="text" style="padding: 6px;text-align:center" id="start_date" width='20%' placeholder="Start Date" value="<?=$Start_Date?>"></td>
            <td><input type="text" style="padding: 6px;text-align:center" id="end_date" onchange="loadPatientList()" width='20%' placeholder="End Date" value="<?=$End_Date?>"></td>
            <td><input type="text" style="padding: 6px;text-align:center" id='patient_name' onkeyup="loadPatientList()" width='20%' placeholder="Patient Name"></td>
            <td><input type="text" style="padding: 6px;text-align:center" id="patient_number" onkeyup="loadPatientList()" width='20%' placeholder="Patient Number"></td>
            <td><input type="text" style="padding: 6px;text-align:center" id="patient_phone_number" onkeyup="loadPatientList()" width='20%' placeholder="Patient Phone Number"></td>
            <td style="text-align: center;padding:4px"><a href="#" onclick="loadPatientList()" class="art-button-green">FILTER</a></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 550px;overflow-y:scroll">
    <legend align='center'>OPD PATIENT LIST</legend>
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

        <tbody id="display_patients" style="cursor: pointer;"></tbody>
    </table>
</fieldset>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(() => {
        loadPatientList();
    });

    function loadPatientList(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var patient_name = $('#patient_name').val();
        var patient_number = $('#patient_number').val();
        var patient_phone_number = $('#patient_phone_number').val();
        var Clinic_ID = $('#Clinic_ID').val();
        $('#display_patients').html("<tr style='background-color:#fff'><td colspan='7' style='text-align:center;colspan='16'><center><img src='./images/ajax-loader_1.gif' style='text-align:center'/></center></td></tr>");

        $.ajax({
            type: "GET",
            url: "common/common.php",
            data: {
                clinic_id: Clinic_ID,
                start_date:start_date,
                end_date:end_date,
                patient_name:patient_name,
                patient_number:patient_number,
                patient_phone_number:patient_phone_number,
                status:'active',
                load_out_patient_list:'load_out_patient_list'
            },
            success: (response) => {
                $('#display_patients').html(response);
            }
        });
    }

    function toPhysiotherapyNotes(Patient_Payment_Item_List_ID,Registration_ID,Clinic_ID){
        window.location = 'patient_particulars_.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Registration_ID='+Registration_ID+'&Patient_Type=Outpatient&Clinic='+Clinic_ID;
    }
</script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>

<?php include './includes/footer.php' ?>