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
<a href="dailypatientdiagnosis.php" class='art-button-green'>DIAGNOSIS CASE</a>
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>DAILY ATTANDANCE REPORTS</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td width="100%">
                             Type Of Patient
                        
                             <select name='Type_Of_patient' id='Type_Of_patient' required='required' title="Select type of check in" style='text-align: center;width:10%;display:inline'>
                                <option value="all">All</option>
                                <option  value="Afresh">New Patient</option>
                                <option value="Continuous">Return Patient</option>
                            </select>
                  
                       
                            Visit Type
                        
                          <select style="height:27px" name='Type_Of_visit' id='Type_Of_visit'  required="" name="visity_type_id" id="VisitTypeID" style='text-align: center;width:10%;display:inline'>
                                <option value="all">All</option>
                                <option value="1">Normal Visit</option>
                                <option value="2">Emergency</option>
                                <option value="3">Referral</option>
                                <option value="4">Follow up Visit</option> 
                                <option value="5">Start</option> 
                                <option value='6'>Self Referral</option>
                                
                            </select>
                            Sponsor: <select id="Sponsor" style='text-align: center;padding:4px; width:15%;display:inline' onchange='Filter_Attendance();'>
                    <?php 
                        echo $dataSponsor;
                    ?>
                    </select>
                    Date from: <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    To: <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    
                    <input type="text" name="ageFrom" id="ageFrom"  placeholder="Age From " style='text-align: center;width:5%;display:inline' required="required" autocomplete='off'>

                    <input type="text" name="ageTo" id="ageTo"  placeholder="Age To" style='text-align: center;width:5%;display:inline' required="required" autocomplete='off' >

                    <select id='agetype' style='text-align:center;padding:4px; width:10%;display:inline'>
                             
                        <option value='YEAR'>Year</option>
                        <option value='MONTH'>Month</option>
                        <option value='DAY'>Days</option>
                    </select>
                    <input type='button' name='Filter' value='Filter' class='art-button-green' onclick='Filter_Attendance();'>
                    <input type="button" name="preview" id="preview" value="Preview" class="art-button-green" onclick="Preview_PDF_Report();">
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
 <div id="displayPatientListattendence" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
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
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 30});

    function Filter_Attendance(){
        var Sponsor=$("#Sponsor").val();
        var Type_Of_patient=$("#Type_Of_patient").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var Type_Of_visit=$("#Type_Of_visit").val();
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
            url:'fetch_daily_patient_attendance.php',
            type:'post',
            beforeSend:function(xhr){
                        $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
            },
            data:{Sponsor:Sponsor,fromDate:fromDate,toDate:toDate,Type_Of_patient:Type_Of_patient,Type_Of_visit:Type_Of_visit, agetype:agetype, ageFrom:ageFrom, ageTo:ageTo},
            success:function(results){
                $('#Search_Iframe').html(results);
            }
        });
        }
    }  
    
     function viewpatientsattendence(Registration_ID,Type_Of_patient,fromDate,toDate,Sponsor_ID,given_date,Type_Of_visit){
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        var agetype = $("#agetype").val();
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
       $.ajax({
            url:'fetch_attendence_patients.php',
            type:'post',
            data:{given_date:given_date,Registration_ID:Registration_ID,Type_Of_patient:Type_Of_patient,fromDate:fromDate,toDate:toDate,Sponsor_ID:Sponsor_ID,Type_Of_visit:Type_Of_visit, agetype:agetype, ageFrom:ageFrom, ageTo:ageTo},
            success:function(result){
                console.log(result);
                $('#displayPatientListattendence').html(result);
            }
        });
        $("#displayPatientListattendence").dialog('open');
    }
    
    function printviewpatientsattendence(Registration_ID,Type_Of_patient,fromDate,toDate,Sponsor_ID,given_date,Type_Of_visit){
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        var agetype = $("#agetype").val();
        if (ageFrom == null || ageFrom == '') {
            document.getElementById("ageFrom").focus();
            document.getElementById("ageFrom").style = 'border: 3px solid red; width:5%;';
            return false;
        } else {
            document.getElementById("ageFrom").style = 'border: 3px white; width:5%;';
        }
        if(ageTo == null || ageTo == '') {
            document.getElementById("ageTo").focus();
            document.getElementById("ageTo").style = 'border: 3px solid red; width:5%;';
            return false;
        }else{
            document.getElementById("ageTo").style = 'border: 3px white; width:5%;';
        }
        window.open("printviewpatientsattendence.php?fromDate="+fromDate+"&toDate="+toDate+"&Sponsor_ID="+Sponsor_ID+"&Type_Of_visit="+Type_Of_visit+ '&ageFrom=' + ageFrom + '&ageTo=' + ageTo +"&Type_Of_patient="+Type_Of_patient+"&agetype="+agetype+'&given_date='+given_date);

    }
    $(document).ready(function () {
        $("#displayPatientListattendence").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS ATTENDENCE', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
//         $('#radiology').show();
         $('select').select2();
    });

    function Preview_PDF_Report(){
        var Sponsor=$("#Sponsor").val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var Type_Of_visit=$("#Type_Of_visit").val();
        var Type_Of_patient=$("#Type_Of_patient").val();
        var agetype= $("#agetype").val()
        var ageFrom = document.getElementById("ageFrom").value;
        var ageTo = document.getElementById("ageTo").value;
        alert(ageTo); exit();
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
        window.open("preview_daily_patient_attendance.php?fromDate="+fromDate+"&toDate="+toDate+"&Sponsor="+Sponsor+"&Type_Of_visit="+Type_Of_visit+ '&ageFrom=' + ageFrom + '&ageTo=' + ageTo +"&Type_Of_patient="+Type_Of_patient+"&agetype="+agetype);
    }
</script>
<script src="js/select2.min.js"></script>
<!--<script src="css/jquery-ui.js"></script>-->
