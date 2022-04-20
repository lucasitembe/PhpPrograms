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

$doctorsquery = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status='active'") or die(mysqli_error($conn));
$doctors = '';
$doctors.='<option value="all">all doctors</option>';

while ($row = mysqli_fetch_array($doctorsquery)) {
    $doctors.= '<option value="' . $row['Employee_ID'] . '">' . $row['Employee_Name'] . '</option>';
}


?>
<style>
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
</style>
<!--<a href="dailypatientdiagnosis.php" class='art-button-green'>DIAGNOSIS CASE</a>-->
<a href='governmentReports.php' class='art-button-green'>
        BACK
    </a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>CLINIC ATTENDANCE REPORT</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td width="100%">
                    Patient new                        
                    <select name='Type_Of_patient' id='Type_patient_case' required='required' title="Select type of check in" style='text-align: center;width:6%;display:inline'>
                        <option value="all">All</option>
                        <option  value="continue_case">Return Patient</option>
                        <option value="new_case">New Patient</option>
                    </select>                  
               
                    Date From: <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    To: <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    Sponsor: <select id="Sponsor_ID" style='text-align: center;padding:4px; width:10%;display:inline' onchange='Filter_Attendance();'>
                    <?php 
                        echo $dataSponsor;
                    ?>
                    </select>
                    
                    Doctor: <select id="doctors" style='left-margin:10px;text-align: center;padding:4px; width:10%;display:inline' onchange='Filter_Attendance();'>
                    <?php 
                        echo $doctors;
                    ?>
             </select>
                    <b>Clinic: </b>
                <select id="Clinic_ID"  style='text-align: center;width:10%;display:inline' class='show_diagnosis'>
                   <option value='all'>All</option>
                    <?php
                        $result = mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic order by Clinic_ID") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['Clinic_ID']."'>{$row['Clinic_Name']}</option>";
                        }
                    ?>
                </select>
                <input type="text" name="ageFrom" id="ageFrom"  placeholder="Age From " style='text-align: center;width:5%;display:inline' required="required" autocomplete='off'>

                <input type="text" name="ageTo" id="ageTo"  placeholder="Age To" style='text-align: center;width:5%;display:inline' required="required" autocomplete='off' >

                <select id='agetype' style='text-align:center;padding:4px; width:6%;display:inline'>                             
                    <option value='YEAR'>Year</option>
                    <option value='MONTH'>Month</option>
                    <option value='DAY'>Days</option>
                </select>
                    <span class="hide"><b>Filter Report By </b>
                <select id="filter_report_by"  style='text-align: center;width:10%;display:inline' class='show_diagnosis'>
                   <option value='number_of_visit'>Number of visits</option>
                   <option value='number_of_patient'>Number of patient</option>
                </select></span>
                <input type='button' name='Filter' value='Filter' class='art-button-green' onclick='Filter_Attendance();'>
                    <input type="button" name="preview" id="preview" value="EXCEL" class="art-button-green" onclick="Preview_exel_Report();">
                </td>
            </tr>   

        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>
 <div id="displayPatientListdiagnosis" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
<div id="displayclinicPatientList"></div>
<center> 
    <!--a href="print_number_of_test_taken_radg.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a-->
    <!--a href="#" target="_blank" id="excelPreview" class="art-button-green">DOWNLOAD EXCEL REPORT</a-->
</center> 

<br/>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 01});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 01});
    
     function Filter_Attendance(){
        var Sponsor_ID=$("#Sponsor_ID").val();
        var doctors=$("#doctors").val();
        var Type_patient_case=$("#Type_patient_case").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var Clinic_ID=$("#Clinic_ID").val();
        var filter_report_by=$("#filter_report_by").val();
        var agetype = $("#agetype").val();
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        // alert(ageTo); exit();
        if (ageFrom == null || ageFrom == '') {
            document.getElementById("ageFrom").focus();
            document.getElementById("ageFrom").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageFrom").style = 'border: 3px white; width:5%;';
        }
        if (ageTo == null || ageTo == '') {
            document.getElementById("ageTo").focus();
            document.getElementById("ageTo").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageTo").style = 'border: 3px white; width:5%;';
        }

        if(fromDate.trim()=='' || toDate.trim()==''){
            alert("FILL THE DATE RANGE");
        }else{
        $.ajax({
            url:'fetch_daily_patient_cases.php',
            type:'post',
            beforeSend:function(xhr){
                        $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
            },
            data:{doctors:doctors,Sponsor_ID:Sponsor_ID,fromDate:fromDate,toDate:toDate,Type_patient_case:Type_patient_case,Clinic_ID:Clinic_ID,filter_report_by:filter_report_by, agetype:agetype,  ageFrom:ageFrom, ageTo:ageTo},
            success:function(results){
                $('#Search_Iframe').html(results);
            }
        });
        }
    }
    
    function Preview_exel_Report(){
        var Sponsor_ID=$("#Sponsor_ID").val();     
        var doctors=$("#doctors").val();
        var Type_patient_case=$("#Type_patient_case").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var Clinic_ID=$("#Clinic_ID").val();
        var filter_report_by=$("#filter_report_by").val();
        var agetype = $("#agetype").val();
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        // alert(ageTo); exit();
        if (ageFrom == null || ageFrom == '') {
            document.getElementById("ageFrom").focus();
            document.getElementById("ageFrom").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageFrom").style = 'border: 3px white; width:5%;';
        }
        if (ageTo == null || ageTo == '') {
            document.getElementById("ageTo").focus();
            document.getElementById("ageTo").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageTo").style = 'border: 3px white; width:5%;';
        }

        if(fromDate.trim()=='' || toDate.trim()==''){
            alert("FILL THE DATE RANGE");
        }else{
            window.open('fetch_daily_patient_cases_excel.php?ageFrom='+ageFrom+'&ageTo='+ageTo+'&toDate='+toDate+'&fromDate='+fromDate+'&Sponsor_ID='+Sponsor_ID+'&agetype='+agetype+'&Clinic_ID='+Clinic_ID+'&doctors='+doctors+'&Type_patient_case='+Type_patient_case+'&filter_report_by='+filter_report_by);  
        }
    }

    function patientAttandanceReport(Clinic_Name, Clinic_ID){
        var Sponsor_ID=$("#Sponsor_ID").val();
        var doctors=$("#doctors").val();
        var Type_patient_case=$("#Type_patient_case").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var filter_report_by=$("#filter_report_by").val();
        var agetype = $("#agetype").val();
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        if (ageFrom == null || ageFrom == '') {
            document.getElementById("ageFrom").focus();
            document.getElementById("ageFrom").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageFrom").style = 'border: 3px white; width:5%;';
        }
        if (ageTo == null || ageTo == '') {
            document.getElementById("ageTo").focus();
            document.getElementById("ageTo").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageTo").style = 'border: 3px white; width:5%;';
        }

        if(fromDate.trim()=='' || toDate.trim()==''){
            alert("FILL THE DATE RANGE");
        }else{
            $.ajax({
                url:'fetch_daily_patient_cases.php',
                type:'POST',
                data:{doctors:doctors,Sponsor_ID:Sponsor_ID,fromDate:fromDate,toDate:toDate,Type_patient_case:Type_patient_case,Clinic_ID:Clinic_ID,filter_report_byclinic:'', agetype:agetype,  ageFrom:ageFrom, ageTo:ageTo, Clinic_Name:Clinic_Name },
                success:function(results){
                    $("#displayclinicPatientList").dialog('open');
                    $('#displayclinicPatientList').html(results);
                }
            });
        }
    }
    function patientAttandanceExport(Clinic_Name, Clinic_ID){
        var Sponsor_ID=$("#Sponsor_ID").val();
        var doctors=$("#doctors").val();
        var Type_patient_case=$("#Type_patient_case").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var filter_report_by=$("#filter_report_by").val();
        var agetype = $("#agetype").val();
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        if (ageFrom == null || ageFrom == '') {
            document.getElementById("ageFrom").focus();
            document.getElementById("ageFrom").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageFrom").style = 'border: 3px white; width:5%;';
        }
        if (ageTo == null || ageTo == '') {
            document.getElementById("ageTo").focus();
            document.getElementById("ageTo").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageTo").style = 'border: 3px white; width:5%;';
        }

        if(fromDate.trim()=='' || toDate.trim()==''){
            alert("FILL THE DATE RANGE");
        }else{
            window.open('fetch_daily_patient_cases_excel.php?ageFrom='+ageFrom+'&ageTo='+ageTo+'&toDate='+toDate+'&fromDate='+fromDate+'&Sponsor_ID='+Sponsor_ID+'&agetype='+agetype+'&Clinic_ID='+Clinic_ID+'&doctors='+doctors+'&Type_patient_case='+Type_patient_case+'&Clinic_Name='+Clinic_Name+'&filter_report_byclinic'); 
        }
    }
    $(document).ready(function () {
        $("#displayclinicPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS DIAGNOSIS', modal: true, position: 'middle'});
       
         $('select').select2();
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="css/jquery-ui.js"></script>
