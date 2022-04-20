<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id


$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}


?>
<style>
#headerstyle {
    color: #00416a;
    background: #dedede;
    font-weight: bold;
}

#tdalign {
    text-align: right;
}

.rowlist {
    cursor: pointer;
}

.rowlist:active {
    color: #328CAF !important;
    font-weight: normal !important;
}

.rowlist:hover {
    color: #00416a;
    background: #88c9ff;
    font-weight: bold;
}
</style>
<a href="#" onclick="goBack()" class="art-button">BACK</a>
<script>
function goBack() {
    window.history.back();
}
</script>

<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#0079AE;color:white;padding:5px;"><b>MEDICATION
            REPORTS</b></legend>
    <center>
        <table class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td>
                    <b>Date From:</b>
                </td>
                <td>
                    <b>To: </b>
                </td>
                <td>
                    <b>Select Category :</b>
                </td>
                <!-- <td>
                    <b>From age </b>
                </td>
                <td>
                    <b>To age </b>
                </td>
                <td></td> -->
                <td>
                    <b>Sponsor : </b>
                </td>
                <td>
                    <b>Medication : </b>
                </td>
                <td>
                    <b>Select Store: </b>
                </td>
            </tr>
            <tr>
                <td style="width: 10rem;text-align:center">
                    <input type="text" autocomplete="off" style='text-align: center;display:inline' id="date_From"
                        placeholder="Start Date" />
                </td>
                <td style="width: 10rem;text-align:center">
                    <input type="text" autocomplete="off" style='text-align: center;display:inline' id="date_To"
                        placeholder="End Date" />&nbsp;
                </td>
                <td style="width: 5rem;text-align:center">
                    <select id='medication_category' style='text-align:center;padding:4px;display:inline'>
                        <option value='all'>ALL</option>
                        <option value='opd_medication'>OPD Medication</option>
                        <option value='ipd_medication'>IPD Medication</option>
                    </select>
                </td>
                <!-- <td style="width:5rem;text-align:center">
                    <input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="From age"
                        class="form-control numberonly"
                        style='text-align: center;width:100%;display:inline;padding: 4px' />
                </td>
                <td style="width:5rem;text-align:center">
                    <input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="To age"
                        class="form-control numberonly"
                        style='text-align: center;width:100%;display:inline;padding: 4px' />
                </td> -->
                <!-- <td style="width:5rem;text-align:center">
                    <select id='diagnosis_time' style='text-align:center;padding:4px; width:100%;display:inline'>
                        <option value='YEAR'>Year</option>
                        <option value='MONTH'>Month</option>
                        <option value='DAY'>Days</option>
                    </select>
                </td> -->
                <td style="width:10rem;text-align:center">
                    <select id="sponsor" style='text-align: center;width:100%;display:inline' class='show_diagnosis'>
                        <option></option>
                        <?php
                        $result = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['Sponsor_ID']."'>{$row['Guarantor_Name']}</option>";
                        }
                    ?>
                    </select>
                </td>
                <td style=" width:10rem;text-align:center">
                    <select id="Medication_ID" style='text-align: center;width:100%;display:inline'
                        class='show_diagnosis' onchange="change_category(this.value)">
                        <option value='all'>All Medications</option>
                        <?php
                        $result = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Consultation_Type='Pharmacy' AND Status='Available' order by Product_Name") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['Item_ID']."'>{$row['Product_Name']}</option>";
                        }
                    ?>
                    </select>
                </td>
                <td style="width:10rem;text-align:center">
                    <select id="Clinic_ID" style='text-align: center;display:inline' class='show_diagnosis'>
                        <option value='all'>All</option>
                        <?php
                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    $select_sub_departments = mysqli_query($conn,"SELECT Sub_Department_Name,ed.Sub_Department_ID, privileges from tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed where dep.department_id = sdep.department_id and ed.Employee_ID = '$Employee_ID' and ed.Sub_Department_ID = sdep.Sub_Department_ID and        Department_Location IN ('Pharmacy','Storage And Supply') and sdep.Sub_Department_Status = 'active'");
                        $result = mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic order by Clinic_ID") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($select_sub_departments)){
                            echo "<option value='".$row['Sub_Department_ID']."'>{$row['Sub_Department_Name']}</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>

        </table>

        <input type="button" name="filter" value="FILTER" class="art-button" onclick="Filter_medication_report();">
        <!-- <input type="button" name="filter" value="IN EXCEL " class="art-button"
            onclick="Filter_medication_report_excel();">
        <input type="button" name="filter" value="IN PDF " class="art-button" onclick="Filter_medication_report_pdf();"> -->
    </center>
    <center>
        <table class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"
                        id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>
<div id="displayPatientList"
    style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
<br />
<center>
    <!--<input type="submit"  onclick="Excel_Report();" class="art-button" value='DOWNLOAD EXCEL REPORT'>-->
</center>

<br />
<div id="patientinservice"></div>
<div id="patientinservices"></div>
<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
$('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
});
$('#date_From').datetimepicker({
    value: '',
    step: 1
});
$('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
});
$('#date_To').datetimepicker({
    value: '',
    step: 1
});

function Filter_medication_report() {

    var fromDate = $("#date_From").val();
    var toDate = $("#date_To").val();;
    // var start_age = $("#start_age").val();
    // var end_age = $("#end_age").val();
    var Clinic_ID = $("#Clinic_ID").val();
    // var diagnosis_time = $('#diagnosis_time').val();
    var Medication_ID = $("#Medication_ID").val();
    var medication_category = $("#medication_category").val();
    var sponsor = $("#sponsor").val();
    if (sponsor.trim() == "") {
        alert("Select sponsor")
        exit()
    }
    if (fromDate.trim() !== '' && toDate.trim() !== '') {
        // if (checkAge(start_age, end_age)) {
        $('#Search_Iframe').html(
            '<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader-focus.gif" width="" style="border-color:white "></div>'
        );
        $.ajax({
            url: 'Ajax_hospital_medication_report1.php',
            type: 'POST',
            data: {
                Filter_Category: 'yes',
                Medication_ID: Medication_ID,
                fromDate: fromDate,
                toDate: toDate,
                medication_category: medication_category,
                Clinic_ID: Clinic_ID,
                sponsor: sponsor,
            },


            // start_age: start_age,
            // end_age: end_age,
            // diagnosis_time: diagnosis_time,
            beforeSend: function() {
                $("#load_image").show();
            },
            success: function(result) {
                if (result != '') {
                    console.log(result);
                    $('#Search_Iframe').html(result);
                }
            },
            complete: function() {
                $("#load_image").hide();
            }
        });
        // }
    } else {
        alert('FILL THE START DATE AND END DATE');
    }
}

function checkAge(start_age, end_age) {
    if (start_age === '' || end_age === '') {
        alert('Select the Age range');
        return false;
    }
    return true;
}

function viewPatientList(disease_name, disease_ID, fromDate, toDate, medication_category, start_age, end_age,
    diagnosis_time) {
    $.ajax({
        url: 'fetch_diagnosis_patient.php',
        type: 'post',
        data: {
            disease_name: disease_name,
            disease_ID: disease_ID,
            fromDate: fromDate,
            toDate: toDate,
            medication_category: medication_category,
            start_age: start_age,
            end_age: end_age,
            diagnosis_time: diagnosis_time
        },
        success: function(result) {
            console.log(result);
            $('#displayPatientList').html(result);
        }
    });
    $("#displayPatientList").dialog('open');

}

function change_category(disease_category_ID) {
    // alert(disease_category_ID)
    $.ajax({
        type: 'POST',
        url: 'Ajax_diagnosisi_home_report.php',
        data: {
            disease_category_ID: disease_category_ID,
            subcategory: ''
        },
        success: function(responce) {
            $("#Disease_id_subcategory").html(responce);
        }
    })
}

function view_patent_dialog(Item_ID, Product_name, Gender) {

    var fromDate = $("#date_From").val();
    var toDate = $("#date_To").val();;
    var start_age = $("#start_age").val();
    var end_age = $("#end_age").val();
    var Clinic_ID = $("#Clinic_ID").val();
    var diagnosis_time = $('#diagnosis_time').val();
    var medication_category = $("#medication_category").val();
    var sponsor = $("#sponsor").val();
    $.ajax({
        type: 'POST',
        url: 'Ajax_hospital_medication_report.php',
        data: {
            Medication_ID: Item_ID,
            Gender: Gender,
            fromDate: fromDate,
            toDate: toDate,
            start_age: start_age,
            end_age: end_age,
            medication_category: medication_category,
            diagnosis_time: diagnosis_time,
            Clinic_ID: Clinic_ID,
            sponsor: sponsor
        },
        success: function(responce) {
            $("#patientinservice").dialog({
                title: 'PATIENTS GIVEN ' + Product_name,
                width: '80%',
                height: 550,
                modal: true,
            });
            $("#patientinservice").html(responce);
        }
    });
}

function open_investigation_result(Registration_ID, consultation_id) {
    var fromDate = $("#date_From").val();
    var toDate = $("#date_To").val();;
    var start_age = $("#start_age").val();
    var end_age = $("#end_age").val();
    var Clinic_ID = $("#Clinic_ID").val();
    var diagnosis_time = $('#diagnosis_time').val();
    var medication_category = $("#medication_category").val();
    $.ajax({
        type: 'POST',
        url: 'Ajax_hospital_medication_report2.php',
        data: {
            fromDate: fromDate,
            toDate: toDate,
            start_age: start_age,
            end_age: end_age,
            medication_category: medication_category,
            diagnosis_time: diagnosis_time,
            Clinic_ID: Clinic_ID,
            Registration_ID: Registration_ID,
            consultation_id: consultation_id
        },
        success: function(responce) {
            $("#patientinservices").dialog({
                title: 'PATIENTS WITH REGISTRATION NO. ' + Registration_ID,
                width: '80%',
                height: 550,
                modal: true,
            });
            $("#patientinservices").html(responce);
        }
    });
}

function Filter_medication_report_pdf() {

    var fromDate = $("#date_From").val();
    var toDate = $("#date_To").val();;
    var start_age = $("#start_age").val();
    var end_age = $("#end_age").val();
    var Clinic_ID = $("#Clinic_ID").val();
    var diagnosis_time = $('#diagnosis_time').val();
    var Medication_ID = $("#Medication_ID").val();
    var medication_category = $("#medication_category").val();
    var sponsor = $("#sponsor").val();
    if (sponsor.trim() == "") {
        alert("Select sponsor")
        exit()
    }

    if (fromDate.trim() !== '' && toDate.trim() !== '') {
        if (checkAge(start_age, end_age)) {
            window.open('Ajax_hospital_medication_report_pdf.php?fromDate=' + fromDate + '&toDate=' + toDate +
                '&start_age=' + start_age + '&end_age=' + end_age + '&sponsor=' + sponsor + '&Clinic_ID=' +
                Clinic_ID + '&diagnosis_time=' +
                diagnosis_time + '&Medication_ID=' + Medication_ID + '&medication_category=' + medication_category +
                '&medication=medicationpage', '_blank');

        }
    } else {
        alert('FILL THE START DATE AND END DATE');
    }
}

function Filter_medication_report_excel() {
    var fromDate = $("#date_From").val();
    var toDate = $("#date_To").val();;
    var start_age = $("#start_age").val();
    var end_age = $("#end_age").val();
    var Clinic_ID = $("#Clinic_ID").val();
    var diagnosis_time = $('#diagnosis_time').val();
    var Medication_ID = $("#Medication_ID").val();
    var medication_category = $("#medication_category").val();
    var sponsor = $("#sponsor").val();
    if (sponsor.trim() == "") {
        alert("Select sponsor")
        exit()
    }

    if (fromDate.trim() !== '' && toDate.trim() !== '') {
        if (checkAge(start_age, end_age)) {
            window.open('Ajax_hospital_medication_report_excel.php?fromDate=' + fromDate + '&toDate=' + toDate +
                '&start_age=' + start_age + '&end_age=' + end_age + '&sponsor=' + sponsor + '&Clinic_ID=' +
                Clinic_ID + '&diagnosis_time=' +
                diagnosis_time + '&Medication_ID=' + Medication_ID + '&medication_category=' + medication_category +
                '&medication=medicationpage', '_blank');

        }
    } else {
        alert('FILL THE START DATE AND END DATE');
    }
}
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#displayPatientList").dialog({
        autoOpen: false,
        width: '90%',
        height: '550',
        title: 'PATIENTS LIST',
        modal: true,
        position: 'middle'
    });
    /*$('.numberTests').dataTable({
        "bJQueryUI": true
    });*/
    $('select').select2();
});
</script>