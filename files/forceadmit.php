<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$section = $_GET['section'];
?>

<a href='forceadmit.php?section=<?php echo $section; ?>' class='art-button-green'>
   DISCHARGE ON REQUEST
</a>
<?php
if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
     if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){
      echo "<a href='billingwork.php?BillingWork=BillingWorkThisPage' class='art-button-green'>
               CLEAR PATIENT BILL
            </a>";
            
     }
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        if ($section == 'Admission') {
            ?>
            <a href='admissionworkspage.php?section=Admission&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
                BACK
            </a>
            <?php
        }
    }
}
$deceased_reasons="";
if(isset($_POST['save_death_reason_btn'])){
   $deceased_reasons=$_POST['deceased_reasons'];
   $sql_insert_d_course_result=mysqli_query($conn,"INSERT INTO tbl_deceased_reasons(deceased_reasons) VALUES('$deceased_reasons')") or die(mysqli_error($conn));

   if($sql_insert_d_course_result){
   ?>
    <script>
        alert("Course of death saved successfully");
    </script>
       <?php    
   }else{
     ?>
    <script>
        alert("Fail  save caurse of death");
    </script>
       <?php   
   }
}
 $get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysqli_error($conn));
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }
?>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/> <fieldset>  
    <table width='100%'>
        <tr>
            <td style="text-align:center">    
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:17%;display:inline'>
                    <option value="All">All Sponsors</option>
                    <?php
                    $qr = "SELECT * FROM tbl_sponsor";
                    $sponsor_results = mysqli_query($conn,$qr);
                    while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                        ?>
                        <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <select width="20%"  name='Ward_id' style='text-align: center;width:17%;display:inline' onchange="filterPatient()" id="Ward_id">
                      <?php
                        $SubDepWardID = $_SESSION['Admission_Sub_Department_ID'];
                        $check_sub_department_ward = mysqli_query($conn,"SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$SubDepWardID'");
                            if (mysqli_num_rows($check_sub_department_ward)>0) {
                                $data = mysqli_fetch_assoc($check_sub_department_ward);
                                $WardID = $data['ward_id'];
                            }
                        
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))");
                        while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                            $ward_id=$Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                            if($WardID==$ward_id){$selected="selected='selected'";}else{$selected="";}
                            ?>
                            <option value="<?php echo $ward_id?>" <?= $selected ?>><?php echo $Hospital_Ward_Name?></option>
                        <?php }
                    ?>
                </select>
                <input type='text' name='Search_Patient' style='text-align: center;width:21%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                <input type='text' name='Search_Patient_number' style='text-align: center;width:21%;display:inline' id='Search_Patient_number' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Number~~~~~~~'>
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                
            </td>

        </tr>

    </table>
        </fieldset>  
</center>
<br/>
<fieldset> 
    
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">ADMITTED PATIENT LIST </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
 
                        <?php // include 'search_list_patient_discahrge_admited_Iframe_force.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function filterPatient() {
      document.getElementById('Date_From').style.border="1px solid #C0C1C6";
      document.getElementById('Date_To').style.border="1px solid #C0C1C6";
      
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var ward = document.getElementById('Ward_id').value;
		var Search_Patient_number = document.getElementById('Search_Patient_number').value;
        var range='';
        
        if(Date_From !='' && Date_To !=''){
              range="FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
        }
        
        if(Date_From =='' && Date_To !=''){
             alert("Please enter start date");
             
             document.getElementById('Date_From').style.border="2px solid red";
             exit;
        }if(Date_From !='' && Date_To ==''){ 
             alert("Please enter end date");
             document.getElementById('Date_To').style.border="2px solid red";
             exit;
        }
        
        
         document.getElementById('dateRange').innerHTML ="ADMITTED PATIENT LIST "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "search_list_patient_discahrge_admited_Iframe_force.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+ '&Sponsor=' + Sponsor+ '&ward=' + ward+'&Search_Patient_number='+Search_Patient_number,
            
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
<div id="store_death_discharged_info" style="display:none">
        <table class="table">
            <tr>
                <td style="width:50%">
                    Enter Death Time
                </td>
                <td colspan="2" >
                    <input type="text"readonly="readonly" id="death_date_time" placeholder="Enter Death Time"/>
                </td>
            </tr>
            
            <tr>
                <td style="height:230px!important;overflow: scroll">
                    <table class="table table-condensed" style="width:100%!important">
                        <tr>
                            <td>
                                <table style="width: 100%">
                                    <td>
                                        <input type="text"id="disease_name" onkeyup="search_disease_c_death(),clear_other_input('disease_code')" placeholder="----Search Disease Name----" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" id="disease_code" onkeyup="search_disease_c_death(),clear_other_input('disease_name')" placeholder="----Search Disease Code----" class="form-control">
                                    </td>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=""><b>Select Disease Caused Death</b></td>
                        </tr>
                        <tbody id="disease_suffred_table_selection">
                        <?php
                            $deceased_diseases=mysqli_query($conn,"SELECT * FROM tbl_disease WHERE disease_version='$configvalue_icd10_9' LIMIT 5");
                            while($row=mysqli_fetch_assoc($deceased_diseases)){
                                extract($row);
                                                    $disease_id="{$disease_ID}";
                            echo "<tr><td><label style='font-weight:normal'><input type='checkbox' onclick='add_death_reason(\"$disease_id\")' value='{$disease_name}'>{$disease_name} ~~<b>{$disease_code}</b></label></td></tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </td>	
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <td colspan="4"><b>Disease Suffered From/ Leave Blank for others</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>S/No.</b></td>
                            <td><b>Disease name</b></td>
                            <td><b>Disease code</b></td>
                            <td><b>Remove</b></td>
                        </tr>
                        <tbody id="disease_suffred_table">
                            
                        </tbody>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    Select cause of Death
                </td>
                <td>
                    
                    <select id="course_of_death" style="width:100%!important">
                        <option value=""></option>
                        <?php 
                            $sql_select_course_of_death_result=mysqli_query($conn,"SELECT * FROM tbl_deceased_reasons") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_course_of_death_result)>0){
                                while($d_course_rows=mysqli_fetch_assoc($sql_select_course_of_death_result)){
                                    
                                    $deceased_reasons2=$d_course_rows['deceased_reasons'];
                                    
                                            if($deceased_reasons==$deceased_reasons2){
                                                $selected_course="selected='selected'";
                                            }else{
                                                $selected_course=" ";
                                            }
                                    echo "<option value='$deceased_reasons2' $selected_course>$deceased_reasons2</option>";
                                }
                            }
                         ?>
                    </select>
                    </td>
                    <td style="width:100px">
                        <input type="button" value="ADD" onclick="open_add_reason_dialogy()" class="art-button-green">
                    </td>
            </tr>
            <tr>
                <td>
                    Select Doctor Confirm Death
                </td>
                <td colspan="2" >
                    <select id="Docto_confirm_death_name" style="width:100%!important">
                        <option value=""></option>
                        <?php 
                            $sql_select_doctors_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_doctors_result)>0){
                                while ($doctors_rows=mysqli_fetch_assoc($sql_select_doctors_result)){
                                    $doctor_cd_name=$doctors_rows['Employee_Name'];
                                    $doctor_cd_id=$doctors_rows['Employee_ID'];
                                    echo "<option value='$doctor_cd_name'>$doctor_cd_name</option>";
                                }
                            }
                          ?>
                    </select>
                </td>
                
            </tr>
            <tr>
                <td colspan="3">
                    <input type="text" id="Discharge_Reason_txt" hidden="hidden">
                    <input type="button" value="Allow Discharge/Comfirm Dearth" class="art-button-green pull-right" onclick="close_this_dialog()">
                </td>
            </tr>
        </table>
    </div>
<div id="add_death_course_dialogy" style="display:none">
    <form action="" method="POST">
        <table class="table">
            <tr>
                <td>Enter Course Of Death</td>
                <td><input type="text" name="deceased_reasons"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="SAVE" name="save_death_reason_btn" class="art-button-green pull-right" >
                </td>
            </tr>
        </table>
    </form>
</div>
<input type="text" id="Registration_ID" hidden="hidden">
<input type="text" id="Admision_ID" hidden="hidden">
<script>
    function clear_other_input(disease_namedisease_code){
        $("#"+disease_namedisease_code).val("");
    }
    function search_disease_c_death(){
        var disease_code=$("#disease_code").val();
        var disease_name=$("#disease_name").val();
        var disease_version='<?= $configvalue_icd10_9 ?>';
       $.ajax({
           type:'GET',
           url:'search_disease_c_death.php',
           data:{disease_code:disease_code,disease_name:disease_name,disease_version:disease_version},
           success:function (data){
               //console.log(data);
               $("#disease_suffred_table_selection").html(data);
           },
           error:function (x,y,z){
               console.log(z);
           }
       }); 
    }
    function remove_added_death_disease(disease_death_ID,Registration_ID){
        $.ajax({
           type:'GET',
           url:'remove_death_reason_to_catch.php',
           data:{disease_death_ID:disease_death_ID,Registration_ID:Registration_ID},
           success:function (data){
               //console.log(data);
               $("#disease_suffred_table").html(data);
           },
           error:function (x,y,z){
               console.log(z);
           }
       }); 
    }
    function refresh_death_reason(){
       var Registration_ID=$("#Registration_ID").val();
       $.ajax({
           type:'GET',
           url:'refresh_death_reason.php',
           data:{Registration_ID:Registration_ID},
           success:function (data){
               //console.log(data);
               $("#disease_suffred_table").html(data);
           },
           error:function (x,y,z){
               console.log(z);
           }
       });  
    }
    function add_death_reason(disease_ID){
       var Registration_ID=$("#Registration_ID").val();
       $.ajax({
           type:'GET',
           url:'add_death_reason_to_catch.php',
           data:{disease_ID:disease_ID,Registration_ID:Registration_ID},
           success:function (data){
               console.log(data);
               $("#disease_suffred_table").html(data);
               search_disease_c_death();
           },
           error:function (x,y,z){
               console.log(z);
           }
       }); 
    }
    function open_add_reason_dialogy(){
        
        $("#add_death_course_dialogy").dialog({
                        title: 'ADD COURSE OF DEATH',
                        width: '50%',
                        height: 200,
                        modal: true,
                    }); 
    }
    function close_this_dialog(){
        var Admision_ID=$("#Admision_ID").val();
        forceadmit(Admision_ID);
        $("#store_death_discharged_info").dialog("close");
    }
    function check_if_dead_reasons(Registration_ID,Admision_ID){
        var Discharge_Reason_ID = $("reason_"+Admision_ID).val();
       
        $("#Registration_ID").val(Registration_ID);
        $("#Admision_ID").val(Admision_ID);
        // var Discharge_Reason_ID=$(rischarge_reason_id).val();
        $("#Docto_confirm_death_name").val(" ");
        $("#death_date_time").val("");
        $.ajax({
            type:'GET',
            url:'check_discharge_reason.php',
            data:{Discharge_Reason_ID:Discharge_Reason_ID},
            success:function(data){ 
                $("#Discharge_Reason_txt").val(data)
               if(data=="dead"){
                   refresh_death_reason();
                  $("#store_death_discharged_info").dialog({
                        title: 'FILL DEATH INFOMATION',
                        width: '70%',
                        height: 550,
                        modal: true,
                    }); 
               }
            }
        });
    }
  function forceadmit(admission_ID){
  //alert('Select discharge reason');
    var death_date_time=$("#death_date_time").val();
    var Docto_confirm_death_name=$("#Docto_confirm_death_name").val();
    var Discharge_Reason_txt=$("#Discharge_Reason_txt").val();
    var course_of_death=$("#course_of_death").val();
    var deceased_diseases=$("#deceased_diseases").val();
    var url="";
    var Registration_ID=$("#Registration_ID").val();
  var resId=$('#reason_'+admission_ID);
  var Discharge_Reason=resId.val();
	deceased_diseases=(deceased_diseases==null)?'others':deceased_diseases;
  
  if(Discharge_Reason=='')
  {
  alert('Select discharge reason');
  resId.css('border','3px solid red');
  exit;
  }
  if(Discharge_Reason_txt=="dead"){
      if(death_date_time==""){
          alert('FIll death time'); exit();
      }else if(Docto_confirm_death_name==""){
        alert("Fill Docotor confirmed Death"); exit();
      }else if(course_of_death==''){
        alert("Fill Course of death")
        exit();
      }
    //   if(death_date_time==""||Docto_confirm_death_name==""||course_of_death==""){
    //       alert("Fill death infomation first"); 
    //       $("#store_death_discharged_info").dialog({
    //                     title: 'FILL DEATH INFOMATION',
    //                     width: '70%',
    //                     height: 550,
    //                     modal: true,
    //                 });
    //                 exit;
    //   }
       url='doctor_discharge_release_force.php?admission_ID=' + admission_ID + '&Discharge_Reason=' + Discharge_Reason+'&death_date_time='+death_date_time+'&Docto_confirm_death_name='+Docto_confirm_death_name+'&course_of_death='+course_of_death+'&deceased_diseases='+deceased_diseases+'&Registration_ID='+Registration_ID+'&fromnurse=fromnurse';
  }else{
        url='doctor_discharge_release_force.php?admission_ID=' + admission_ID + '&Discharge_Reason=' + Discharge_Reason+'&fromnurse=fromnurse';
    }
      if(confirm("Are you sure you want to dischage this patient.The patient will not be visible to the doctor. Continue?")){
            if (window.XMLHttpRequest) {
                myobj = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myobj = new ActiveXObject('Micrsoft.XMLHTTP');
                myobj.overrideMimeType('text/xml');
            }

            myobj.onreadystatechange = function () {
              var  data = myobj.responseText;
                if (myobj.readyState == 4) {
                   if(data == '1'){
                       console.log(data);
                      alert("Processed successifully.Patient is in discharge state now!");
                       filterPatient();
                   }else{
                     alert('An error has occured try again or contact system administrator');
                   }
                   // document.getElementById('Patients_Fieldset_List').innerHTML = data6;
                }
            }; //specify name of function that will handle server response........

            myobj.open('GET', url , true);
            myobj.send();
        }
  }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        filterPatient();
        $("#Docto_confirm_death_name").select2();
       // $("#deceased_diseases").select2();
        $("#course_of_death").select2();
        
        $('#patientList').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 1});
        $('#death_date_time').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#death_date_time').datetimepicker({value: '', step: 1});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 1});
    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
 
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
 


<?php
include("./includes/footer.php");
?>


