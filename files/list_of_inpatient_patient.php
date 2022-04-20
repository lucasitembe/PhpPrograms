<?php 
    include './includes/header.php';
    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();

    $Current_Date_Time = mysqli_fetch_assoc(mysqli_query($conn,"SELECT now() as Date_Time"))['Date_Time'] or die(mysqli_error($conn));
    $duration = 0;
    $Filter_Value = substr($Current_Date_Time,0,11);

    $mod_date=date_create($Filter_Value);
    date_sub($mod_date,date_interval_create_from_date_string("$duration days"));
    $newdate =  date_format($mod_date,"Y-m-d");

    $Start_Date = $newdate.' 00:00';    
    $End_Date = $Current_Date_Time;
    $sub_department_name = $_SESSION['Pharmacy'];
?>
<style>
    #dispense_list tr:hover{ 
        color:#00416a;
        cursor: pointer;
        background-color: #dedede !important;
        font-weight:bold;
    }
</style>
<a href="pharmacyworks.php" class="art-button-green">BACK</a>

<fieldset>
    <legend align='center'>RETURN DISPENSED ITEM TO PHARMACY PATIENT LIST</legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date'  readonly="readonly" value="<?=$Start_Date?>" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date'  readonly="readonly" value="<?=$End_Date?>" placeholder="End Date"/></td>
                <td>
                    <select onchange="filterPatient()" id="billing_type" style="padding: 5px;">
                        <option value="all">Select Billing Type</option>
                        <option value="Inpatient Cash">Inpatient Cash</option>
                        <option value="Inpatient Credit">Inpatient Credit</option>
                        <option value="Outpatient Credit">Outpatient Credit</option>
                    </select>
                </td>
                <td><input type="text" style="text-align:center" onkeyup="filterPatient()" placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup="filterPatient()" placeholder="Patient Number" id="Patient_Number"/></td>
                <td><input type="button" value="FILTER" onclick="filterPatient()" style="font-family: Arial, Helvetica, sans-serif;" class="art-button-green"/></td>
            </tr>
        </table>
    </center>

    <div class="box box-primary" style="height: 600px;overflow-y: scroll;overflow-x: hidden">
        <table width='100%'>
            <tr style="background-color: #ddd;">
                <td style="padding: 6px;text-align:center" width='5%'>S/N</td>
                <td style="padding: 6px;" width=''>PATIENT NAME</td>
                <td style="padding: 6px;" width='20%'>REGISTRATION NUMBER</td>
                <td style="padding: 6px;" width='20%'>SPONSOR</td>
                <td style="padding: 6px;" width='20%'>BILL TYPE</td>
                <td style="padding: 6px;" width='20%'>DISPENSE DATE</td>
            </tr>

            <tbody id='dispense_list'></tbody>
        </table>
    </div>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
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

<script>
    $(document).ready(() => {
        filterPatient();
    });
</script>

<script>
    function filterPatient() {  
        var billing_type = $('#billing_type').val();
        var Start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var Patient_Name = $('#Patient_Name').val();
        var Patient_Number = $('#Patient_Number').val();

        if(Start_date == ""){
            document.getElementById('start_date').style = "border:1px solid red;text-align:center";
            exit();
        }

        if(end_date == ""){
            document.getElementById('start_date').style = "border:1px solid #ddd;text-align:center";
            document.getElementById('end_date').style = "border:1px solid red;text-align:center";
            exit();
        }

        document.getElementById('start_date').style = "border:1px solid #ddd;text-align:center";
        document.getElementById('end_date').style = "border:1px solid #ddd;text-align:center";
        $('#dispense_list').html("<tr><td style='text-align:center'colspan='6'><center><img src='./images/ajax-loader_1.gif' style='text-align:center'/></center></td></tr>");

        $.get('pharmacy-repo/common.php',{
            Start_date:Start_date,
            end_date:end_date,
            Patient_Name:Patient_Name,
            Patient_Number:Patient_Number,
            billing_type:billing_type,
            sub_department_id:<?=$_SESSION['Pharmacy_ID']?>,
            list_patients_to_return:'list_patients_to_return'
        },(response) => { $('#dispense_list').html(response); });
    }

    function return_medication(Registration_No,Payment_Cache_No) { 
        document.location = 'return_medication_selected_patient.php?Reg_No='+Registration_No+'&Payment_Cache_No='+Payment_Cache_No
    }
</script>

<?php include '/includes/footer.php'; ?>