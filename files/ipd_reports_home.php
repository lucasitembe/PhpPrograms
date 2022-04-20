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
<a href="dailyPatientAttendance.php" class='art-button-green'>DAILY PATIENT ATTENDANCE</a>
<a href="ipd_report_new.php" class='art-button-green'>BED STATE</a>
<?php 
    if(isset($_GET['from_admission'])){
      echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports'  class='art-button-green'>BACK</a>";  
    }
    else if(isset($_GET['from_doctors'])){
         echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage'  class='art-button-green'>BACK</a>";  
    }
    else{
?>
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>
<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>IPD REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;

                    <b>Select Category :</b>
                        <select id='ipd_report_category' style='text-align:center;padding:4px; width:15%;display:inline'>
                            <option value='ipd_diagnosis'>IPD Diagnosis</option>
                            <option value='ipd_attendance'>IPD Admission</option>
                            <option value='ipd_statistics'>IPD Statistics</option>
                        </select>
                        <b >Select Ward: </b>
                    <select id="Ward_ID" style='padding:4px;' >
                        <option value="all">All</option>
                        <?php
                        $clinic_result = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward  WHERE ward_type != 'mortuary_ward' ORDER BY Hospital_Ward_Name ASC") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($clinic_result)){
                            echo "<option value='".$row['Hospital_Ward_ID']."'>{$row['Hospital_Ward_Name']}</option>";
                        }
                    ?>
                    </select>
                    <b>Age From </b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="Start age" class="numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 <b>Age To </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="End age" class="numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_IPD();">
                </td>
            </tr>

            <tr id="second_row">
                <td style="width: 20px;text-align:center " >
                <b>Disease Category: </b>
                <select id="disease_category"  style='text-align: center;width:10%;display:inline' class='show_diagnosis'>
                   <option value='all'>All Disease Category</option>
                    <?php
                        $result = mysqli_query($conn,"SELECT disease_category_ID,category_discreption FROM tbl_disease_category WHERE icd_10_or_icd_9='icd_10' order by disease_category_ID") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['disease_category_ID']."'>{$row['category_discreption']}</option>";
                        }
                    ?>
                </select>
                <b>Diagnosis Type:</b><select id="diagnosis_type"   style='text-align: center;width:10%;display:inline'>
                    <option value="all">All Diagnosis</option>
                    <option value="final">Final Diagnosis</option>
                    <option value="provisional">Provisional Diagnosis</option>
                    <option value="differential">Differetial Diagnosis</option>
                </select>

                <b>Report Type: </b>
                <select id="disease_report_type"  style='text-align: center;width:10%;display:inline'>
                    <option value="type1">Summurized Report</option>
                    <!-- <option value="type2">Extended Report</option> -->
                </select>

                <b>Find Top n<sup>th</sup> Diseases:</b><input type="number" min="0"  style='text-align: center;width:10%;display:inline'  id="search_top_n_diseases" style='padding:4px;'>
                <input type="button" class="art-button-green show_diagnosis" value="Search Top Diseases"  onclick="searchTopDiseases();">

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
<br>
<div id="displayPatientList" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
<br/>
<center>
    <input type="submit"  onclick="Excel_Report();" class="art-button-green" value='DOWNLOAD EXCEL REPORT'>
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
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
</script>
<script type="text/javascript">
    $("#ipd_report_category").on("change",function(){
        if($("#ipd_report_category").val()=='ipd_attendance'){
            $("#second_row").hide();
        }
        if($("#ipd_report_category").val()=='ipd_diagnosis'){
            $("#second_row").show();
        }
    });

    function Filter_IPD(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();;
        var start_age=$("#start_age").val();;
        var end_age=$("#end_age").val();
        var reportType =$("#reportType").val();
        var Ward_ID =$("#Ward_ID").val();
        var diagnosis_type =$("#diagnosis_type").val();
        var Disease_Cat_Id =$("#disease_category").val();
        var ipd_report_category =$("#ipd_report_category").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if(ipd_report_category == 'ipd_statistics'){
              $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
              $.ajax({
                  url:'hospital_statistics.php',
                  type:'post',
                  data:{Filter_Category:'yes',Ward_ID:Ward_ID,fromDate:fromDate,toDate:toDate},
                  beforeSend:function(){$("#load_image").show();},
                  success:function(result){
                      if (result != '') {
                          $('#Search_Iframe').html(result);
                      }
                  },
                  complete:function(){
                      $("#load_image").hide();
                  }
              });
            }else{ 
              if(checkAge(start_age,end_age)){
                  $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                  $.ajax({
                      url:'government_ipd_reports.php',
                      type:'post',
                      data:{Filter_Category:'yes',Ward_ID:Ward_ID,diagnosis_type:diagnosis_type,Disease_Cat_Id:Disease_Cat_Id,fromDate:fromDate,toDate:toDate,start_age:start_age,end_age:end_age,ipd_report_category:ipd_report_category},
                      beforeSend:function(){$("#load_image").show();},
                      success:function(result){
                          if (result != '') {
                              $('#Search_Iframe').html(result);
                          }
                      },
                      complete:function(){
                          $("#load_image").hide();
                      }
                  });
              }
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
    function checkType(type){
        var page='';
        if(type==='type1')
            page='print_disease_by_age_excel_report.php';
        if(type==='type2')
            page='print_disease_by_age_once_and _return_excel_report.php';
        return page;
    }
    $('#diagnosis_type,#disease_category').on('change',function(){
        var top_n =$('#search_top_n_diseases').val();
        if(top_n.trim() !== ''){return false;}
        Filter_IPD();
    });

    function searchTopDiseases(){
        var search_top_n_diseases=$('#search_top_n_diseases').val();
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var diagnosis_type=$('#diagnosis_type').val();
        var Ward_ID=$('#Ward_ID').val();
        var url='';

        url='government_ipd_reports.php';

        $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
        if(fromDate!=="" && toDate!==""){
        if(search_top_n_diseases.trim()!==''){
            $.ajax({
                url:url,
                type:'post',
                data:{filter_top_n_diseases:'yes',fromDate:fromDate,toDate:toDate,search_top_n_diseases:search_top_n_diseases,diagnosis_type:diagnosis_type,Ward_ID:Ward_ID},
                success:function(result){
                    if (result != '') {
                        $('#Search_Iframe').html(result);
                    }
                }
            });
        }else{
            alert("Enter the number of diseases");
            $("#progressStatus").hide();
        }
    }else{
        alert("FILL THE START AND END DATE");
        $("#progressStatus").hide();
    }}
</script>
<script type="text/javascript">
        function Excel_Report(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();;
        var start_age=$("#start_age").val();;
        var end_age=$("#end_age").val();
        var Ward_ID =$("#Ward_ID").val();
        var diagnosis_type =$("#diagnosis_type").val();
        var Disease_Cat_Id =$("#disease_category").val();
        var ipd_report_category =$("#ipd_report_category").val();
        if(ipd_report_category =='ipd_attendance' || ipd_report_category == 'ipd_statistics'){return false;}
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if($("#search_top_n_diseases").val().trim()!=='' && $("#search_top_n_diseases").val().trim()> 0){
                window.location.href='print_top_ipd_diseases_excel_report.php?fromDate=' + fromDate + '&toDate=' + toDate + '&bill_type=Outpatient' + '&start_age=' + start_age+ '&end_age=' + end_age+'&Disease_Cat_Id='+$('#disease_category').val()+'&Ward_ID='+$('#Ward_ID').val()+'&search_top_n_diseases='+$("#search_top_n_diseases").val()+'&Ward_ID='+$('#Ward_ID').val()+'&diagnosis_type='+diagnosis_type;

            }else if(checkAge(start_age,end_age) && ($("#search_top_n_diseases").val()==='' || $("#search_top_n_diseases").val() < 1)){
                window.location.href=checkType($("#disease_report_type").val())+'?fromDate=' + fromDate + '&toDate=' + toDate + '&start_age=' + start_age+ '&end_age=' + end_age+'&Disease_Cat_Id='+$('#disease_category').val()+'&Ward_ID='+$('#Ward_ID').val()+'&diagnosis_type='+diagnosis_type+'&From=Inpatient';
            }
        }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }

    function viewPatientList(disease){
        console.log(JSON.stringify(disease));
        var disease_data=JSON.stringify(disease);
        //$('#displayPatientList').text('disease appended is no '+disease);
        $.ajax({
            url:'fetch_disease_patient_list_ipd.php',
            type:'post',
            data:{disease_data:disease},
            success:function(result){
                $('#displayPatientList').html(result);
            }
        });
        $("#displayPatientList").dialog('open');

    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'700', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/

    });
</script>