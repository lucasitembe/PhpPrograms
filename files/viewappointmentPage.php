<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Registration_ID'])){
      $patientID=$_GET['Registration_ID'];
    }

    $frompage = "";
    if(isset($_GET['frompage']) && $_GET['frompage'] == "reception") {
        $frompage = $_GET['frompage'];
    } else {
        $frompage = "";
    }

?>
        
<?php
$from_procedure="no";
if(isset($_GET['from_procedure'])){
    
    $from_procedure='yes';
     ?>

<a href="searchpatientprocedurelist.php" class='art-button-green'>
            BACK
        </a>
    <?php
}else if(isset($_SESSION['userinfo'])){
        
    if(isset($_GET['this_page_from']) && $_GET['this_page_from'] == "reception_report") {
?>

    <a href='./receptionReports.php?Section=Reception&ReceptionReportThisPage' class='art-button-green'>
        BACK
    </a>

<?php

    } else if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<!--    <a href='appointment_configuration.php?appointmentWork=appointmentWorkThisPage' class='art-button-green'>
     APPOINTMENT CONFIGURATION
    </a>-->

   
    <a href='./searchappointmentPatient.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage&frompage=<?php echo $frompage; ?>' class='art-button-green'>
        BACK
    </a>
<?php  } }

       //get today's date
  $sql_date_time = mysqli_query($conn,"SELECT now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>


<br/><br/>
<!--popup window -->
    <div id="D_Details" style="width:50%;" >
        <center id='Pop_Patients_Details'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
    </div>

<div id="editappointment" style="display: none">
    <div id="showDiv">
        
        
    </div>
    <div><input type="button" id="saveeditAppointment" value="Save Changes" class="art-button-green" /></div>
</div>

<fieldset>
    <legend align=center><b>PATIENT APPOINTMENT LIST</b></legend>
    <center>
        <!--<form action="viewappointmentPage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage" method="POST">-->
        <table width=100%>
            <tr>
                <td width=19% style="text-align: center;">
                  <select style="text-align: center;width:100%;" id="clinic" onchange="disableDoctor();">
                  <option value="">~~ Select clinic ~~</option>
                  <option value="All">All Clinic</option>
                  
                   <?php
                  $query=mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic");
                  while ($result=  mysqli_fetch_assoc($query)){
                      $Clinic_Name = ucwords(strtolower($result['Clinic_Name']));
                      echo '<option value="'.$result['Clinic_ID'].'">'.$Clinic_Name.'</option>';
                  }
                  ?>  
                  </select>
                </td>
                <td width=19% style="text-align: center;">
                 <select style="text-align: center;width:100%;" id="doctor" onchange="disableClinic();">
                  <option value="">~~ Select Doctor ~~</option>
                  <option value="All">All Doctors</option>
                  <?php
                  $query=mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status='active'");
                  while ($result=  mysqli_fetch_assoc($query)){
                      $Employee_Name = ucwords(strtolower($result['Employee_Name']));
                      echo '<option value="'.$result['Employee_ID'].'">'.$Employee_Name.'</option>';
                  }
                  ?>
                 
                 </select>  
                </td>
                <td width=7% style="text-align: right;">
                   <b> From Date</b>
                </td>
                <td width=18% style="text-align: center;">
                    <input type="text" id="fromDate" required="true" value="<?= $Start_Date ?>" name="fromDate" style="width: 100%;text-align: center;">
                </td>
                <td width=4% style="text-align: right;">
                    <b>To Date</b>
                </td>
                <td width=18% style="text-align: center;">
                    <input type="text" id="toDate" required="true" value="<?= $End_Date ?>" name="toDate" style="width: 100%;text-align: center;">
                </td>
                
                <td width=5% style="text-align: center;">
                    <input style="text-align: center;" type="submit" id="filter" onclick="Filter_appointment()" name="filter" class="art-button-green" value="Filter">
                </td>
                
                 <!-- <td width=5% style="text-align: center;">
                    <input style="text-align: center;" type="submit" id="printDoc" name="filter" class="art-button-green" value="Preview">
                </td> -->
                <!-- <td width=5% style="text-align: center;">
                    <input style="text-align: center;" type="submit" id="printDoc2" name="filter2" class="art-button-green" value="Excel Preview">
                </td> -->
            </tr>
           
        </table>
        <!--</form>-->


</fieldset>

<fieldset id='Patients_Fieldset_List'>
    <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden;" id='scroll_bar'>
        <div id="load_data"></div>
    </div>
</fieldset>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
<script>

    function disableDoctor() {
        var clinic = $("#clinic").val();
        var clinic = $("#clinic").val();
        if(clinic != "") {
            // $("#doctor").html("<option value=''>~~ Select Doctor ~~</option>");
        } else {
            var section = "doctor";
            $.ajax({
                type:'POST', 
                url:'load_clinic_or_doctor.php',
                data:{section: section},
                cache:false,
                success:function(html){
                    $("#doctor").html(html);
                }
            }); 
        }
    }

    function disableClinic() {
        var doctor = $("#doctor").val();
        var doctor = $("#doctor").val();
        if(doctor != "") {
            // $("#clinic").html("<option value=''>~~ Select Clinic ~~</option>");
        } else {
            var section = "clinic";

            $.ajax({
                type:'POST', 
                url:'load_clinic_or_doctor.php',
                data: {section: section},
                cache:false,
                success:function(html){
                    $("#clinic").html(html);
                }
            });  
        }
    }
    $("#select_all_checkbox").click(function (e){
        $(".Patient_Registration_ID").not(this).prop('checked', this.checked); 
        
    });    
     $("#select_all_checkbox1").click(function (e){
         $(".diagnosis_id").not(this).prop('checked', this.checked); 
         
     }); 
      function sendsms_to_patient(){

        var selected_Registration_ID = []; 
        $(".Patient_Registration_ID:checked").each(function() {
		selected_Registration_ID.push($(this).val());
	});
        
          alert(selected_Registration_ID);
          
        $.ajax({
         type:'POST', 
         url:'loadAppointments_and_send_sms.php',
//         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{selected_Registration_ID:selected_Registration_ID},
         cache:false,
         success:function(html){
             console.log(html);
             alert("sms send successfully");
           }
     
         });      
      }
   
    $('#fromDate').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#fromDate').datetimepicker({value:'',step:1});
    
    $('#toDate').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#toDate').datetimepicker({value:'',step:1});
    
    $('#editappointdate').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#editappointdate').datetimepicker({value:'',step:1});

</script>
<script>
    function popAppointments(id) {
        var doctor_id = id;
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;

        if (window.XMLHttpRequest) {
            myObjectDisplayDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplayDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplayDetails.overrideMimeType('text/xml');
        }

        myObjectDisplayDetails.onreadystatechange = function () {
            data779 = myObjectDisplayDetails.responseText;
            if (myObjectDisplayDetails.readyState == 4) {
                document.getElementById('Pop_Patients_Details').innerHTML = data779;
                $("#D_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectDisplayDetails.open('GET', 'Appoinment_Patients_Display_Details.php?fromDate=' + fromDate + '&toDate=' + toDate + '&doctor_id=' + doctor_id, true);
        myObjectDisplayDetails.send();
    }

    document.getElementById('no_opening').onclick = function() { 
        return false;
    };
</script>
<script>
    $(document).ready(function () {
        $("#D_Details").dialog({autoOpen: false, width: '70%', height: 500, title: 'APPOINTMENT PATIENT DETAILS', modal: true});
        Filter_appointment();
    });
</script>
<script>
// editappointment
    $('#saveeditAppointment').on('click',function(){
       var appdate=$('#editappointdate').val();
       var reason=$('#editreason').val();
       var doctor=$('#editdoctor').val();
       var clinic=$('#editclinic').val();
       if(doctor!='' && clinic!=''){
           alert('Select either clinic or doctor');
           return false;
       }
       if(doctor=='' && clinic==''){
           alert('Select either clinic or doctor');
           return false;
       }
       var id=$('#editableId').val();
        $.ajax({
         type:'POST', 
         url:'requests/saveAppointment.php',
         data:'action=Edit&appdate='+appdate+'&reason='+reason+'&doctor='+doctor+'&id='+id+'&clinic='+clinic,
         cache:false,
         success:function(html){
          alert(html);
         
           }
         }); 
    });

    function Filter_appointment(){
       var fromDate = $('#fromDate').val();
       var toDate = $('#toDate').val();
       var clinic = $('#clinic').val();
       var doctor = $('#doctor').val();

	    document.getElementById('load_data').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       var check = $('#myList1').length;
       $.ajax({
            type:'POST', 
            url:'load_Appointments.php',
            data:{
             fromDate:fromDate,
             toDate:toDate,
             clinic:clinic,
             doctor:doctor
            },
            cache:false,
            success:function(data){
                
                document.getElementById("load_data").innerHTML = data;
                // var check2 = $('#myList1').length;
                // if (check > 0) {
                //     $('#myList1').DataTable({
                //         "bJQueryUI": true
                //     });
                // } else if(check2 > 0) {
                //     $('#myList1').DataTable({
                //         "bJQueryUI": true
                //     });
                // }
            }
     
        }); 
        
    }
    
    function seach_patient(){
        
       var fromDate=$('#fromDate').val();
       var toDate=$('#toDate').val();
       var clinic=$('#clinic').val();
      
       var doctor=$('#doctor').val();
       
       var regID=$('#regID').val();
       var PatientName=$('#PatientName').val();
       
       
       
        $.ajax({
         type:'POST', 
         url:'search_patient_appointment.php',
        //         data:'action=viewAppointment&appointment='+appointment+'&fromDate='+fromDate+'&toDate='+toDate+'&from_procedure='+from_procedure+'&clinic='+clinic+'&doctor='+doctor,
         data:{fromDate:fromDate,toDate:toDate,clinic:clinic,doctor:doctor,regID:regID,PatientName:PatientName},
         cache:false,
         success:function(html){
             console.log(html);
         $('#viewAppointment').html(html);
           }
     
         });
        
    }
    
    
    $('#clinic').change(function(){
      var doctor=$('#doctor').val();
      if(doctor!=''){
          alert('Select either clinic or doctor');
          $('#clinic').val('');
      }
    });
    
    $('#doctor').change(function(){
      var clinic=$('#clinic').val();
      if(clinic!=''){
          alert('Select either clinic or doctor');
         $('#doctor').val('');
      }
    });
    
    $('#printDoc').click(function(){
        var doctor=$('#doctor').val();
        var clinic=$('#clinic').val();
        var fromDate=$("#fromDate").val();
        var toDate=$("#toDate").val();
        if(fromDate == '' && toDate == ''){
            alert('Select Date to continue!');
            return false;
        }
    //   window.open("print_patient_Appointment.php?fromDate="+fromDate+"&toDate="+toDate+"&doctor="+doctor+"&clinic="+clinic);  
        window.open("print_doctors_appointment_for_patients.php?fromDate="+fromDate+"&toDate="+toDate+"&doctor="+doctor+"&clinic="+clinic);  
    });

    

    $('#printDoc2').click(function(){
        var doctor=$('#doctor').val();
        var clinic=$('#clinic').val();
        var fromDate=$("#fromDate").val();
        var toDate=$("#toDate").val();
        if(fromDate == '' || toDate == ''){
            alert('Select Date to continue!');
            return false;
        }
    //   window.open("print_patient_Appointment.php?fromDate="+fromDate+"&toDate="+toDate+"&doctor="+doctor+"&clinic="+clinic);  
        window.open("print_doctors_appointment_for_patients_excel.php?fromDate="+fromDate+"&toDate="+toDate+"&doctor="+doctor+"&clinic="+clinic);  
    });

    function printData(fromDate,toDate,doctor) {
        
            var fromDate = fromDate;
            var toDate = toDate;
            var doctor = doctor;
            
            window.open('print_doctors_appointment_for_patients.php?fromDate='+fromDate+'&toDate='+toDate+'&doctor='+doctor);  
            
    }
    function printData3() {
            
        var clinic=$('#clinic').val();
        var fromDate=$("#fromDate").val();
        var toDate=$("#toDate").val();
            
        window.open('print_doctors_appointment_for_patients.php?fromDate='+fromDate+'&toDate='+toDate+'&clinic='+clinic);  
            
    }

    function print_details(fromDate,toDate,doctor) {
        
            var fromDate = fromDate;
            var toDate = toDate;
            var doctor = doctor;
            
            window.open('print_doctors_appointment_patients_detaileees.php?fromDate='+fromDate+'&toDate='+toDate+'&doctor='+doctor);  
     
            
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        
        $('select').select2();

        $('#myList1').DataTable({
            "bJQueryUI": true
        });

        $('#myList2').DataTable({
            "bJQueryUI": true
        });


        $('#fromDate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
        });
        $('#fromDate').datetimepicker({value:'',step:1});
        
        $('#toDate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
        });
        $('#toDate').datetimepicker({value:'',step:1});
        
        $('#editappointdate').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
        });
        $('#editappointdate').datetimepicker({value:'',step:1});
        });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="css/jquery-ui.js"></script>


<?php
    include("./includes/footer.php");
?>