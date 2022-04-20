<?php include ("./includes/connection.php");
include ("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $patientID = $_GET['Registration_ID'];
}
if (isset($_SESSION['userinfo'])) {
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;
$numberSpecimen = mysqli_query($conn, "SELECT ap.Clinic,reg.Phone_Number,reg.Registration_ID,ap.doctor,ap.Set_BY,ap.date_time,reg.Patient_Name,ap.appointment_reason,ap.appointment_id FROM tbl_appointment ap,tbl_patient_registration reg WHERE ap.patient_No=reg.Registration_ID AND Set_BY='$employee_ID' AND ap.Status='1' AND date_time_transaction BETWEEN '$Start_Date' AND '$End_Date' ");
$number_of_appointment = mysqli_num_rows($numberSpecimen);;
echo '<!--  <a href="viewnumberofappointmen.php?section=AppointmentWorks=AppointmentWorksThisPage"  class="art-button-green">
           Number of Appointment<span style="background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;">
          ';
echo $number_of_appointment;;
echo '    </a>-->
';
$from_procedure = "no";
if (isset($_GET['from_procedure'])) {
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    $Sponsor = $_GET['Sponsor'];
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
    $from_procedure = 'yes';;
    echo '
<a href="procedurepatientinfo.php?section=Procedure&Registration_id=';
    echo $patientID;
    echo '&Date_From=';
    echo $Date_From;
    echo '&Date_To=';
    echo $Date_To;
    echo '&Sponsor=';
    echo $Sponsor;
    echo '&Sub_Department_ID=';
    echo $Sub_Department_ID;
    echo '" class="art-button-green">
            BACK
        </a>
    ';
} else {
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {;
            echo '
        <a href="./searchappointmentPatient.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage" class="art-button-green">
            BACK
        </a>
    ';
        }
    }
};
echo '<script src="css/jquery.js"></script>
<link href="css/jquery-ui.css" rel="stylesheet" />
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<br/><br/>
<center><fieldset>
    <legend align=center><b>ASSIGN PATIENT APPOINTMENT</b></legend>
    <legend align=center><b>CHOOSE DOCTOR AND CLINIC FRO APPOINTMENT</b></legend>
    <table border="1"  style="width:90%;" class="">
        <tr>
            ';
$patient_id = $_GET['Registration_ID'];
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
$dob = '';
$age = '';
$sql1 = mysqli_query($conn, "SELECT * FROM tbl_patient_registration where Registration_ID='$patient_id'");
$disp = mysqli_fetch_array($sql1);
$Date_Of_Birth = $disp['Date_Of_Birth'];
$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years ";
$age.= $diff->m . ' Months ';
$age.= $diff->d . ' Days ';
$patient_phone_no = 0;
$sql_slct_phn_no_result = mysqli_query($conn, "SELECT Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$patientID'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_slct_phn_no_result) > 0) {
    $patient_phone_no = mysqli_fetch_assoc($sql_slct_phn_no_result) ['Phone_Number'];
}
if ($patient_phone_no == "") {
    $patient_phone_no = 0;
};
echo '            <td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;">
        <center><b>';
echo $disp['Patient_Name'] . "," . $disp['Gender'] . "," . $age;
echo '                <input type="text" id="phonenumber" onkeyup="validate_number();numberOnly()"style="width:190px" value="';
echo $patient_phone_no;;
echo '" > <input type="button"  onclick="update_phone_number()" style="width: 40px" value="Update" class="art-button-green" /></b></center></td>
        </tr>
    </table>

    <div class="row">
        <div class="col-md-1"></div>
           <div class="col-md-5">
               <br><br><br>
                   <table>


        <tr>
            <td>
                <b> Reason for appointment</b>
            </td>
            <td>
                <textarea id="reason"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <b> Select Doctor</b>
            </td>
            <td>
                <select id="doctor" style="width: 250px" onchange="get_available_appointment(this.value,"doctors")">
                    <option value="" >--Select--</option>
                    ';
$query = mysqli_query($conn, "SELECT * FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status='active'");
while ($result = mysqli_fetch_assoc($query)) {
    echo '<option value="' . $result['Employee_ID'] . '">' . $result['Employee_Name'] . '</option>';
};
echo '
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <b> Select Clinic</b>
            </td>
            <td>
                <select id="clinic" style="width: 250px" onchange="get_available_appointment(this.value,"clinics")">
                    <option value="">--Select clinic--</option>

                    ';
$query = mysqli_query($conn, "SELECT * FROM tbl_clinic");
while ($result = mysqli_fetch_assoc($query)) {
    echo '<option value="' . $result['Clinic_ID'] . '">' . $result['Clinic_Name'] . '</option>';
};
echo '
                </select>
            </td>
        </tr>
         <tr>
            <td>
                <b> Appointment Date </b>
            </td>
            <td>
                <!--<input type="text" id="appointdate">-->
                <input type="text" id="appointdate" readonly="">
                <div id="timefrom" hidden="hidden"></div>
                <div id="timeto" hidden="hidden"></div>
                <div id="status" hidden="hidden"></div>
            </td>
        </tr>

        <tr>

            <td></td>
            <td>
                <input type="hidden" id="patientID" value="';
echo $patientID;;
echo '">
        <center><input type="button" id="saveAppointment" style="width: 200px" value="Save appointment" class="art-button-green" /></center>
        </td>
        </tr>
    </table>
    </div>


       <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">

                    <!--<div class="col-md-12"><button type="button" style="color:white !important;" class="art-button-green" onclick="addorganism();">Add Organism</button></div></div>-->
                </div>
                <div class="box-body" style="height: 300px;overflow-y: auto;overflow-x: hidden">
                    <table class="table">

                        <tbody id="list_of_appointment_schedule">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>


</fieldset></center><br/>
';
include ("./includes/footer.php");;
echo '
<script>

        function get_available_appointment(Clinic_ID,status){
              $.ajax({
            type:"post",
            url:"appointment_schedule_available.php",
            data:{Clinic_ID:Clinic_ID,status:status},
            success:function(result){
//                fetch_appointment_schedule.php
                 $("#list_of_appointment_schedule").html(result);

            }
        });
    }

    function date_display_to_appointment(date,time_from,time_to,status){

           $("#dateappointment").val(date);
         $("#timefrom").val(time_from);
         $("#timeto").val(time_to);
         $("#status").val(status);
    }
    function update_phone_number(){
        var number = $("#phonenumber").val();
        var patient_ID = "';
echo $patientID;
echo '";

        $.ajax({
        type:"POST",
        url:"ajax_patient_phone_number.php",
        data:{number:number,patient_ID:patient_ID},
        success:function(data){
             alert("data saved successfully");
              console.log(data);
        }
    });

    }

    function numberOnly(){

        var reg = new RegExp("[^0-9]+$");
        var str = myElement.value;
        if(reg.test(str)){
                  if(!isNaN(parseInt(str))){
                      intval = parseInt(str);
                  }else{
                      intval = "";
                  }
                  myElement.value= intval;
        }
    }
    function validate_number(){
        var patient_phone_number = $("#phonenumber").val();
        var patient_phone_number = patient_phone_number.replace(/^\s+/, "").replace(/\s+$/, "");
        if(patient_phone_number =="")patient_phone_number=255;
        $("#phonenumber").val("+"+parseInt(patient_phone_number));
    }

      var patient_phone_number = $("#phonenumber").val();
       var patient_phone_number = patient_phone_number.replace(/^\s+[^1-9]/, "").replace(/\s+$/, "");
       if(patient_phone_number == ""){
           $("#phonenumber").css("border","2px solid red");
           $("#phonenumber").focus();
           validate++;

       }else{
           $("#phonenumber").css("border","");
       }
    $("#appointdate").datetimepicker({
        dayOfWeekStart: 1,
        lang: "en",
        startDate: "now"
    });
    $("#appointdate").datetimepicker({value: "", step: 30});




    $("#saveAppointment").click(function () {
       var appdate = $("#appointdate").val();
        var reason = $("#reason").val();
        var doctor = $("#doctor").val();
        var clinic = $("#clinic").val();
        var patientID = $("#patientID").val();
        var timefrom = $("#timefrom").val();
        var timeto = $("#timeto").val();
        var status_clinics_doctors = $("#status").val();

        //var appdate = $("#dateappointment").val();
        if (appdate == "") {
            alert("Select appointment date");
            return false;
        } else if (reason == "") {
            alert("Give reason for this appointment");
            return false;
        }
        var doctor = $("#doctor").val();
        var clinic = $("#clinic").val();
        if (doctor == "" && clinic == "") {
            alert("Select either clinic or doctor");
            return false;
        }
        var from_procedure="';
echo $from_procedure;
echo '";

//        var chec_appointment = validate_appointment(clinic,timefrom,timeto);
//
//         alert("yy"+chec_appointment+"hh");clinic
var return_data = function () {
            var status="null";
           $.ajax({
                "async": false,
                "type": "POST",
                url:"ajax_check_appointment.php",
                data:{clinic:clinic,timefrom:timefrom,timeto:timeto,appdate:appdate,status_clinics_doctors:status_clinics_doctors},
                success:function(data){
                    console.log(data);
                   status = data;
                }
            });
          return status;
    }();


            var status= return_data;
//            alert(status);
            if(status >= 1){
//                 alert("truehugy");
                    alert("Idadi ya tarehe hii imekamilika, chagua tarehe nyingine");
//                if(confirm("idadi imefika unataka kuongeza? OK kuongeza na CANCEL kusitisha")){
//                    $.ajax({
//                    type: "POST",
//                    url: "requests/saveAppointment.php",
//                    data: "action=save&appdate=" + appdate + "&reason=" + reason + "&doctor=" + doctor + "&clinic=" + clinic + "&patientID=" + patientID+"&from_procedure="+from_procedure+"&timefrom="+timefrom+"&timeto="+timeto,
//                    cache: false,
//                    success: function (html) {
//                        alert(html);
//                        $("#appointdate").val("");
//                        $("#reason").val("");
//                        $("#doctor").val("");
//        //                console.log(html);
//
//        //                alert("Appointment saved successfuly");
//                        document.location = "doctorspageoutpatientwork.php";
//                    }
//                });
//                }
            }else{
//                  alert("false");
                    $.ajax({
                    type: "POST",
                    url: "requests/saveAppointment.php",
                    data: "action=save&appdate=" + appdate + "&reason=" + reason + "&doctor=" + doctor + "&clinic=" + clinic + "&patientID=" + patientID+"&from_procedure="+from_procedure+"&timefrom="+timefrom+"&timeto="+timeto,
                    cache: false,
                    success: function (html) {
                        alert(html);
                        $("#appointdate").val("");
                        $("#reason").val("");
                        $("#doctor").val("");
        //                console.log(html);

        //                alert("Appointment saved successfuly");
                        document.location = "doctorspageoutpatientwork.php";
                    }
                });


            }


    });

     function validate_appointment(clinic,timefrom,timeto){
         var status="";
        $.ajax({
        type:"POST",
        url:"ajax_check_appointment.php",
        data:{clinic:clinic,timefrom:timefrom,timeto:timeto},
        success:function(data){
//           $("#idvalidate").val(data);
           alert("gg"+data+"kk");
           status = data;

        }
    });

       return  status;

     }

    $("#clinic").change(function () {
        var doctor = $("#doctor").val();

    });

    $("#doctor").change(function () {
        var clinic = $("#clinic").val();
    });

    $(document).ready(function () {
         $("select").select2();
    });
</script>
   <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="css/jquery-ui.js"></script>';

