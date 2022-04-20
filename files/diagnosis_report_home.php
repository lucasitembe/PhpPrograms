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

<a href="diagnosis_desease_configuration.php" class='art-button-green'>DIAGNOSIS CONFIGURATION</a>
<!-- <a href="dailyPatientAttendance.php" class='art-button-green'>DAILY PATIENT ATTENDANCE</a> -->
<!-- <a href="diagnosisreportBydeperment.php" class="art-button-green">DIAGNOSIS BY DEPERTMENT</a> -->
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>


<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DIAGNOSIS REPORTS</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;

                    <b>Select Category :</b>
                        <select id='diagnosis_report_category' style='text-align:center;padding:4px; width:15%;display:inline'>
                            <!-- <option value='all'>ALL</option> -->
                            <option value='opd_diagnosis'>OPD Diagnosis</option>
                            <option value='ipd_diagnosis'>IPD Diagnosis</option>
                        </select>
                        <select id='diagnosis_report_case' style='text-align:center;padding:4px; width:15%;display:inline'>
                            <option value='all'>All Cases </option>
                            <option value='newcase'>New Case</option>
                            <option value='oldcase'>Old Case</option>
                        </select>
                    <b>From age </b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="From age" class="form-control numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 <b>To age </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="To age" class="form-control numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 
                        <select id='diagnosis_time' style='text-align:center;padding:4px; width:15%;display:inline'>
<!--                            <option value='all'>ALL</option>-->
                            <option value='YEAR'>Year</option>
                            <option value='MONTH'>Month</option>
                            <option value='DAY'>Days</option>
                        </select>
                  
              
                 <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_OPD();"></br>
                      <b>Disease Category: </b>
                <select id="disease_category"  style='text-align: center;width:15%;display:inline' class='show_diagnosis'>
                   <option value='all'>All Disease Category</option>
                    <?php
                        $result = mysqli_query($conn,"SELECT disease_category_ID,category_discreption FROM tbl_disease_category WHERE icd_10_or_icd_9='icd_10' order by disease_category_ID") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['disease_category_ID']."'>{$row['category_discreption']}</option>";
                        }
                    ?>
                </select>
                <b>Diagnosis</b>  
                    <select name="" id="Diagnosis_ID" style='text-align: center;width:10%;display:inline;'>
                        <option value="All">All</option>
                        <?php
                           $sql_search_disease_code_result=mysqli_query($conn,"SELECT disease_ID,disease_code,disease_name FROM tbl_disease WHERE  disease_version='icd_10' ") or die(mysqli_error($conn));
                           if(mysqli_num_rows($sql_search_disease_code_result)>0){
                               $count_sn=1;
                               while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
                                   $disease_ID=$disease_rows['disease_ID'];
                                   $disease_code=$disease_rows['disease_code'];
                                   $disease_name=$disease_rows['disease_name'];
                                   echo "<option value='$disease_ID'>$disease_name ($disease_code)</option>";
                               }
                           }
                        ?>
                    </select>  
                       <b>Diagnosis Type:</b><select id="diagnosis_type"   style='text-align: center;width:10%;display:inline'>
                    <option value="all">All Diagnosis</option>
                    <option value="diagnosis">Final Diagnosis</option>
                    <option value="provisional_diagnosis">Provisional Diagnosis</option>
                    <option value="differential">Differetial Diagnosis</option>
                </select>
                               <b>Select Clinic: </b>
                <select id="Clinic_ID"  style='text-align: center;width:10%;display:inline' class='show_diagnosis'>
                   <option value='all'>All</option>
                    <?php
                        $result = mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic order by Clinic_ID") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['Clinic_ID']."'>{$row['Clinic_Name']}</option>";
                        }
                    ?>
                </select>
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
 <div id="displayPatientList" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
<br/>
<center>
    <!--<input type="submit"  onclick="Excel_Report();" class="art-button-green" value='DOWNLOAD EXCEL REPORT'>-->
</center>

<br/>

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
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
        function Filter_OPD(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();;
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var Clinic_ID=$("#Clinic_ID").val();
        var diagnosis_time =$('#diagnosis_time').val();
        var disease_category = $("#disease_category").val();
        var diagnosis_type = $("#diagnosis_type").val();
        var diagnosis_report_category =$("#diagnosis_report_category").val();
        var diagnosis_report_case =$("#diagnosis_report_case").val();
        var Diagnosis_ID = $("#Diagnosis_ID").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if(checkAge(start_age,end_age)){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'government_dignosis.php',
                    type:'post',
                    data:{Filter_Category:'yes',diagnosis_report_case:diagnosis_report_case, disease_category:disease_category,Diagnosis_ID:Diagnosis_ID, fromDate:fromDate,toDate:toDate,start_age:start_age,end_age:end_age,diagnosis_report_category:diagnosis_report_category,diagnosis_time:diagnosis_time,diagnosis_type:diagnosis_type,Clinic_ID:Clinic_ID},
                    beforeSend:function(){$("#load_image").show();},
                    success:function(result){
                        if (result != '') {
                            console.log(result);
                            $('#Search_Iframe').html(result);
                        }
                    },
                    complete:function(){
                        $("#load_image").hide();
                    }
                });
            }
        }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }
   
    function checkAge(start_age,end_age){
        if(start_age==='' || end_age===''){
            alert('Select the Age range');
            return false;
        }
        return true;
    }
    
      function viewPatientList(disease_name,disease_ID,fromDate,toDate,diagnosis_report_category, start_age,end_age,diagnosis_time, diagnosis_report_case){
        var diagnosis_report_case =$("#diagnosis_report_case").val();
        var Clinic_ID=$("#Clinic_ID").val();
        $.ajax({
            url:'fetch_diagnosis_patient.php',
            type:'post',
            data:{disease_name:disease_name,Clinic_ID:Clinic_ID, disease_ID:disease_ID,fromDate:fromDate,toDate:toDate,diagnosis_report_category:diagnosis_report_category, start_age:start_age,end_age:end_age,diagnosis_time:diagnosis_time ,diagnosis_report_case:diagnosis_report_case},
            success:function(result){
                console.log(result);
                $('#displayPatientList').html(result);
            }
        });
        $("#displayPatientList").dialog('open');

    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
 $('select').select2();
    });
</script>

