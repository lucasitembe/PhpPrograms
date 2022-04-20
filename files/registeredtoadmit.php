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
?>
<a href='admissionnewpatient.php?AdmissionNewPatient=AdmissionNewPatientThisPage' class='art-button-green'>
    ADD NEW PATIENT
</a>

<a href='msamahaToadmit.php?AdmissionNewPatient=AdmissionNewPatientThisPage' class='art-button-green'>
    ADD NEW COST SHARING PATIENT
</a>
<style>
    select{
        padding:5px;
    }
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
<a href='searchlistofoutpatientadmission.php' class='art-button-green'>
    BACK
</a>
<br/><br/> <fieldset>  
    <table width='100%'>
        <tr>
            <td style="text-align:center">    
                <input type="text" autocomplete="off" style='text-align: center;width:21%;display:inline' name='Search_Patient' oninput='filterPatient()' id='Search_Patient' placeholder="~~~~~~~Search Patient Name~~~~~~~"/>
                <input type="text" autocomplete="off" style='text-align: center;width:21%;display:inline' name='Patient_Number' oninput='filterPatient()' id='Patient_Number' placeholder="~~~~~~~Search Patient Number~~~~~~~"/>&nbsp;
                <input type="text" autocomplete="off" style='text-align: center;width:21%;display:inline' name='Phone_Number' oninput='filterPatient()' id='Phone_Number' placeholder="~~~~~~~Search Phone Number~~~~~~~"/>&nbsp;

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
            </td>

        </tr>

    </table>
</fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d')  ?></span></b></legend>-->
    <legend align=center><b id="dateRange">REGISTERED PATIENT LIST </b></legend>

    <center>
        <table width='100%' border='1'>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'registeredtoadmit_frame.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<script>
    function filterPatient() {
        var Search_Patient = document.getElementById('Search_Patient').value;
        var Patient_Number = document.getElementById('Patient_Number').value;
        var Phone_Number = document.getElementById('Phone_Number').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       
        $.ajax({
            type: "GET",
            url: "registeredtoadmit_frame.php",
            data: 'Search_Patient=' + Search_Patient + '&Patient_Number=' + Patient_Number + '&Phone_Number=' + Phone_Number + '&Sponsor=' + Sponsor ,
            success: function (html) {
                if (html != '') {

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
    
    function check_if_admited_or_in_admit_list(Registration_ID){
       

       $.ajax({
           type:'GET',
           url:'check_if_admited_or_in_admit_list.php',
           data:{Registration_ID:Registration_ID},
           success:function (data){
               console.log(data)
               if(data=="admit_list"){
                   alert("The Patient Is on PATIENTS TO ADMIT LIST \n Please select patient from the list and then admit to your Ward");
               }else if(data=="free_to_admit"){
                   Checkin_Patient(Registration_ID);
               }else{
                   alert("Patient Arleady Admitted to ~~"+data.toUpperCase());
               }
           },
           error:function(x,y,z){
              console.log(z); 
           }
       });
    }
  function Checkin_Patient(Registration_ID){
   if(confirm("Are you sure you want to check in this patient?")){   
     $.ajax({
            type: "GET",
            url: "checkin_patient_admint.php",
            data: 'Registration_ID=' + Registration_ID ,
            success: function (data) {
                if(parseInt(data)==1){
                    alert("Patient Checked in successifully.");
                     document.location = 'admissionvisitorform.php?Registration_ID='+Registration_ID+'&location=registered';
                }else if(parseInt(data)==0){
                     alert("Patient already checked.Look at the list of checked in");
                }else{
                    alert(data);
                    //alert("An error has occured.Try again");
                }
            }
        });
    }
  }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patientList').DataTable({
            "bJQueryUI": true
        });
    });
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
