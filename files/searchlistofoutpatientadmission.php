<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }else{
	$section='Admission';
    }
    
    
    if(isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage']=='fromDoctorPage'){
      $fromDoctorPage="&fromDoctorPage=fromDoctorPage";  
        
    }else{
       $fromDoctorPage='';
    }
?>

 <a href='receptioncheckedinpatientslistinpatient.php?ReceptionCheckedInpatientsListInpatient=ReceptionCheckedInpatientsListInpatientThisPage' class='art-button-green'>
    LIST OF CHECKED IN PATIENT
</a>
 <!-- <a href='registeredtoadmit.php' class='art-button-green'>    REGISTERED PATIENT </a> -->
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<td><input type='button' onclick='list_of_patient_to_be_admitted()' value='PREVIEW' class='art-button-green'/></td>
<a href='admissionworkspage.php?section=Admission&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage<?php if(isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage']=='fromDoctorPage'){echo '&fromDoctorPage=fromDoctorPage';}?>' class='art-button-green'>
        BACK
    </a>

<?php  } } ?>

 

<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/>
<fieldset>  
    <table width='100%'>
        <tr>
            <td style="text-align: right;">Sponsor Name</td>
            <td>    
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()">
                    <option value="All">All Sponsors</option>
                    <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                        while ($sponsor_rows = mysqli_fetch_assoc($select)) {
                    ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
            <td style="text-align: right;">Ward Name</td>
            <td>    
                <select name='Ward_ID' id='Ward_ID' onchange="filterPatient()">
                    <option value="All">All Wards</option>
                    <?php
                        $select = mysqli_query($conn,"select Hospital_Ward_ID,Hospital_Ward_Name from tbl_hospital_ward") or die(mysqli_error($conn));
                        while ($sponsor_rows = mysqli_fetch_assoc($select)) {
                    ?>
                            <option value='<?php echo $sponsor_rows['Hospital_Ward_ID']; ?>'><?php echo $sponsor_rows['Hospital_Ward_Name']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
            <td width="30%">
                <input type='text' name='Search_Patient' style='text-align: center;' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Enter Patient Name~~~~~~~'>                
            </td>
            <td width="30%">
                <input type='text' name='Patient_Number' style='text-align: center;' id='Patient_Number' oninput="filter_Patient_Via_Phone()" placeholder='~~~~~~~Enter Patient Number~~~~~~~'>                
            </td>
        </tr>
    </table>
        </fieldset>  
</center>
<br/>
<fieldset> 
    <?php 
    //FIX INCOMPLETE admission
    //select all incomplete admission fro table ya admission
    $sql_select_all_incomplete_admision_result=mysqli_query($conn,"SELECT Registration_ID FROM tbl_admission WHERE ward_room_id='0'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_incomplete_admision_result)>0){
           while($incomplete_regid_rows=mysqli_fetch_assoc($sql_select_all_incomplete_admision_result)){
              $Registration_ID=$incomplete_regid_rows['Registration_ID'];
              //check if this patient_has a status already
              
                $sql_fix_incomplete_admision_result=mysqli_query($conn,"UPDATE tbl_check_in_details SET ToBe_Admitted='yes' WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));  
             
              }}
    ?>
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">PATIENTS TO ADMIT LIST </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                     
                        <?php include 'search_list_patient_admission_Iframe.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function filterPatient() {
        var fromDoctorPage='<?php echo $fromDoctorPage;?>';
        var Patient_Name = document.getElementById('Search_Patient').value;
        document.getElementById('Patient_Number').value = '';
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Ward_ID=document.getElementById('Ward_ID').value;
        var range='';
        
         document.getElementById('dateRange').innerHTML ="PATIENTS TO ADMIT LIST  "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "search_list_patient_admission_Iframe.php",
            data: 'Patient_Name=' + Patient_Name+ '&Sponsor=' + Sponsor+'&Ward_ID='+Ward_ID+fromDoctorPage,
            
            success: function (html) {
              if(html != ''){
               
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            }
            }
        });
    }
</script>
<script>
    function filter_Patient_Via_Phone() {
        var fromDoctorPage='<?php echo $fromDoctorPage;?>';
        var Patient_Number = document.getElementById('Patient_Number').value;
        document.getElementById('Search_Patient').value = '';        
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Ward_ID=document.getElementById('Ward_ID').value;
        var range='';
        
         document.getElementById('dateRange').innerHTML ="PATIENTS TO ADMIT LIST  "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "search_list_patient_admission_Iframe.php",
            data: 'Patient_Number=' + Patient_Number+ '&Sponsor=' +Sponsor+'&Ward_ID='+Ward_ID+fromDoctorPage,
            success: function (html) {
              if(html != ''){
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            }
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patientList').DataTable({
            "bJQueryUI": true
        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
    function list_of_patient_to_be_admitted(){

var fromDoctorPage='<?php echo $fromDoctorPage;?>';
var Patient_Name = document.getElementById('Search_Patient').value;
var Patient_Number = document.getElementById('Patient_Number').value;
var Sponsor = document.getElementById('Sponsor_ID').value;
var Ward_ID=document.getElementById('Ward_ID').value;

// var Patient_Number=$('#Patient_Number').val();
// var Patient_Name=$('#Search_Patient').val();
// var Sponsor=$('#Sponsor').val();
// var Ward_ID=$('#Ward_ID').val();

window.open('list_of_patient_to_be_admitted_pdf.php?Ward_ID='+Ward_ID+"&Patient_Number="+Patient_Number+"&Patient_Name="+Patient_Name+"&Sponsor="+Sponsor+"&fromDoctorPage="+fromDoctorPage, '_blank');
}
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>