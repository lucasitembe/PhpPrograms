<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
require_once './includes/ehms.function.inc.php';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}  

#get patient info
$patient_registration_id = $_GET['Registration_ID'];
$Registration_ID =$_GET['Registration_ID'];
 $Admision_ID = $_GET['Admision_ID'];
//  echo $Admision_ID; SELECT * FROM `tbl_admission` WHERE `Registration_ID`='17009' ORDER BY `Admision_ID` DESC LIMIT 1
// die("SELECT Patient_Name,Date_Of_Birth,Gender,Admission_Date_Time,Region,pr.District,a.Ward FROM tbl_admission a, tbl_patient_registration pr WHERE pr.Registration_ID = '$patient_registration_id' AND pr.Registration_ID=a.Registration_ID ORDER BY Admision_ID DESC LIMIT 1  ");
$select_patient_registration_info = mysqli_query($conn, "SELECT Patient_Name,Date_Of_Birth,Gender,Admission_Date_Time,Region,pr.District,a.Ward FROM tbl_admission a, tbl_patient_registration pr WHERE pr.Registration_ID = '$patient_registration_id' AND pr.Registration_ID=a.Registration_ID ORDER BY Admision_ID DESC LIMIT 1  ");
while ($patient_details = mysqli_fetch_array($select_patient_registration_info)) :
   $hospital_number = $patient_details['Registration_ID'];
   $name = $patient_details['Patient_Name'];
   $gender = $patient_details['Gender'];
   $DOB = $patient_details['Date_Of_Birth'];
   $district = $patient_details['District'];
   $Admission_Date_Time = $patient_details['Admission_Date_Time'];
   $Ward = $patient_details['Ward'];
   $Region = $patient_details['Region'];

endwhile;
$age = floor((strtotime(date('Y-m-d')) - strtotime($DOB)) / 31556926) . " Years";
// if($age == 0){

$date1 = new DateTime($Today);
$date2 = new DateTime($DOB);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, ";
$age .= $diff->m . " Months, ";
$age .= $diff->d . " Days";
$address = $Region."  ".$district. " ".$Ward;
#get admission details
$select_patient_registration_info = mysqli_query($conn, "");
$type_burn="";
$select_diagnosis = mysqli_query($conn, "SELECT type_burn FROM tbl_burn_type bt, tbl_burn_unit_receiving_notes urn WHERE urn.Burn_ID=bt.Burn_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($select_diagnosis)>0){
   while($diagnosis_rw = mysqli_fetch_assoc($select_diagnosis)){
      $type_burn = $diagnosis_rw['type_burn'];
   }
}

$consultation_ID = 0;
$select_consultation_ID = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation c WHERE Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
if((mysqli_num_rows($select_consultation_ID))>0){
   while($cos_rw = mysqli_fetch_assoc($select_consultation_ID)){
      $consultation_ID = $cos_rw['consultation_ID'];
   }
}

      $diagnosis_qr = mysqli_query($conn,"SELECT disease_name,Disease_Consultation_Date_And_Time,disease_code FROM tbl_disease_consultation dc,tbl_disease d
      WHERE dc.consultation_ID =$consultation_ID  AND 
      dc.disease_ID = d.disease_ID AND diagnosis_type='diagnosis'") or die(mysqli_error($conn));
     
      $diagnosis = '';
      if((mysqli_num_rows($diagnosis_qr))>0){
          while($d_rw = mysqli_fetch_assoc($diagnosis_qr)){
              $disease_name = $d_rw['disease_name'];
              $disease_code = $d_rw['disease_code'];
              $diagnosis = $disease_name. " (".$disease_code. " )<br>"; 
          }
      }
   
   
      $Weight= 0;
      $select_body_weight = mysqli_query($conn, " SELECT Vital_Value FROM tbl_nurse_vital nv, tbl_vital v, tbl_nurse n WHERE n.Nurse_ID=nv.Nurse_ID AND nv.Vital_ID=v.Vital_ID AND n.Registration_ID = '$Registration_ID' AND Vital LIKE '%WEIGHT%' ORDER BY n.Nurse_ID DESC LIMIT 1" ) or die(mysqli_error($conn));

      if(mysqli_num_rows($select_body_weight)>0){
         while($weight_rw = mysqli_fetch_assoc($select_body_weight)){
            $Weight = $weight_rw['Vital_Value'];
         }
      }
?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<style>
   .actions {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
      margin-bottom: .5em;
      gap: 1em;
   }

   .btn-back {
      border: none;
      font-weight: 600;
      border-radius: 4px;
      cursor: pointer;
      font-family: sans-serif;
      padding: 1em;
      color: #fbfbfb;
      font-size: 15px;
      background-color: #006400;
   }

   table {
      width: 100%;
      padding: 2em;
      background-color: #ececec; 
      margin: 0;
      display: grid;
      grid-template-columns: 1fr 1fr;
   }

   .btn-burn-unit {
      width: 100%;
      font-weight: 500;
      font-size: 16px;
      border-radius: 4px;
      transition: .25s;
      border: 1px solid #ccc;
   }

   .main-section {
      margin: 4em;
      margin-left: 25em;
      margin-right: 25em
   }

   .btns-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1em;
   }

   .btns-group {
      padding: 1em;
      border: none;
      background-color: #ececec;
      border-radius: 5px;
   }

   .btns-group button:hover {
      background-color: #006400;
      color: white;
   }
   .btns-group a:hover {
      background-color: #006400;
      color: white;
   }
   .title_info {
      background-color: #006400;
      color: #ccc;
      position: absolute;
      padding: 1em;
      align-items: center;
      width: 20%;
      margin-top: -2em;
      font-weight: 500;
      border-radius: 5px;
   }

   legend {
      padding: 5em;
      text-align: center;
      font-size: 14px;
      border-radius: 4px;
   }

   /* form popup model */
   .view_convulation,
   .monitoring-body-weight {
      display: none;
      position: fixed;
      border-radius: 5px;
      z-index: 1;
      left: 0;
      height: 100vh;
      top: 0;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.712);
      padding-top: 50px;
   }

   .data-view {
      background-color: #ececec;
      padding: .5em;
      border-radius: 5px;
      margin-left: 6em;
      margin-right: 6em;
      height: 85vh
   }

   .title-header {
      background-color: #006400;
      padding: .8em;
      color: #fff;
      border-radius: 4px;
      font-weight: 500;
      margin-bottom: 1em
   }

   .title-header .close-form {
      color: white;
   }

   /* table to display info */
   .table-1 {
      overflow: scroll;
      height: 50vh
   }

   table {
      width: 100%;
      background-color: #fff;
      display: inline-table
   }

   th,
   td,
   table {
      border: 1px solid #ccc;
      text-align: start;
      border-collapse: collapse;
      padding: .5em;
   }

   th,
   td {
      padding: 1em;
   }

   .data {
      font-weight: 600;
   }

   .form .form-section {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
      gap: 1em;
   }

   .form input,
   .actions input {
      padding: .8em;
      transition: .25s;
   }

   .convulsion #btn-add-section {
      padding: 11px;
   }

   .convulsion #btn-add-section button {
      width: 80%;
   }

   #btn-add-section button {
      background-color: #006400;
      color: #fff;
      margin-top: .8em;
      border: none;
      font-weight: 500;
      border-radius: 4px;
      width: 17%;
      transition: .25s;
   }

   #btn-add-section button:hover {
      background-color: #036f9e;
   }

   .monitoring-body-weight #btn-add-section {
      padding: 10px;
   }

   textarea {
      padding: 4px;
      transition: .25s;
   }

   input[type='date'] {
      width: 100%;
   }

   .art-article table,
   table.art-article {
      margin: 0px;
   }

   select {
      width: 100%;
      padding: 14px;
      border: 1px solid #B9B59D;
      transition: .25s;
      border-radius: 5px;
   }

   input:focus,
   select:focus,
   textarea:focus {
      box-shadow: 1px -1px 19px -2px rgba(3, 124, 176, 0.62);
   }
</style>
<a href="nursecommunicationpage.php?Registration_ID=<?= $Registration_ID; ?>&Admision_ID=<?= $Admision_ID ?>" class="art-button-green">BACK</a>

<div class="main-section">
   <fieldset class="" align="center">
      <legend align="center" class="burn_unit_section" style="padding:15px">
         <b><span>Burn Unit</span></b>
         <br>
         <?php  ?>
         <span><b><?php echo $name . " | " . $gender . " | " . $age . " Years |" ?> CASH-PATIENT GRADE III</b></span>
      </legend>

      <center>
         <div class="btns-group">
            <div>
               <button class="btn-burn-unit" onclick="document.getElementById('convulsion_chart').style.display='block'">Convulsion</button>
            </div>
            <div>
               <button class="btn-burn-unit" onclick="document.getElementById('body-weight').style.display='block'">Monitoring Of Body Weight</button>
            </div>
            <div>
                <a href="Nurse_assessment_burn.php?Registration_ID=<?=$Registration_ID?>&Admision_ID=<?=$Admision_ID?>">
               <button class="btn-burn-unit">ASSESSMENT </button>
               </a>
            </div>
         </div>
      </center>
   </fieldset>
</div>

<div class="convulsion view_convulation" id="convulsion_chart">
   <div class="data-view">
      <div class="title-header">
         <span>CONVULSION DOCUMENTATION CHART</span>
         <div class="close">
            <span class="close-form" style="color: #fff" id="close-form" onclick="document.getElementById('convulsion_chart').style.display='none'">&times;</span>
         </div>
      </div>

      <div class="table">
         <table>
            <tbody>
               <tr>
                  <td class="data" style="padding: .8em">PATIENT NAME</td>
                  <td style="padding: .8em"><?php echo $name; ?></td>
                  <td class="data" style="padding: .8em">HOSPITAL NUMBER</td>
                  <td style="padding: .8em"><?php echo $patient_registration_id; ?></td>
                  <td class="data" style="padding: .8em">AGE</td>
                  <td style="padding: .8em"><?php echo $age; ?> Years</td>
                  <td class="data" style="padding: .8em">ADDRESS</td>
                  <td style="padding: .8em">
                     <?php 
                     echo $address;
                     ?>
                  </td>
               </tr>

               <tr>

                  <td class="data" style="padding: .8em">DIAGNOSIS</td>
                  <td style="padding: .8em"><?php echo $diagnosis; ?></td>
                  <td class="data" style="padding: .8em">DATE OF ADMISSION</td>
                  <td style="padding: .8em"><?php echo $Admission_Date_Time; ?></td>
                  <td class="data" style="padding: .8em">SEX</td>
                  <td style="padding: .8em"><?php echo $gender ?></td>
                  <td class="data" style="padding: .8em">WEIGHT</td>
                  <td style="padding: .8em"><?php echo $Weight; ?></td>
               </tr>
            </tbody>
         </table>
      </div>

      <div class="form">
         <form action="" method="POST">
            <div class="form-section">
               <div class="input">
                  <label for="">Date Time</label>
                  <input type="text" autocomplete="off" style='width:100%;display:inline' id="Date_From" placeholder="Date and Time" />
               </div>
               <div class="input">
                  <label for="">Type of Seizures</label>
                  <select id="seizure">
                     <option value="tomic_clomic_seizure">Tomic Clomic Seizure</option>
                     <option value="focal_seizure">Focul Seizure</option>
                     <option value="absence_seizure">Absence Seizure</option>
                  </select>
               </div>
               <div class="input">
                  <label for="">Duration</label>
                  <input type="text" placeholder="Duration" id="duration">
               </div>
               <div class="input">
                  <label for="">Drugs Give</label>
                  <textarea name="" cols="30" rows="1.9" placeholder="Enter drugs given" id="drugs_given"></textarea>
               </div>
               <div class="input" id="btn-add-section">
                  <label for=""></label>
                  <button type="submit" id="btn_document_chat">ADD</button>
               </div>
            </div>
         </form>
      </div>

      <br>

      <div class="table-1">
         <table>
            <thead>
               <tr>
                  <td class="data" style="padding: .8em">Date</td>
                  <td class="data" style="padding: .8em">Type of Seizures</td>
                  <td class="data" style="padding: .8em">Duration</td>
                  <td class="data" style="padding: .8em">Drugs Given</td>
                  <td class="data" style="padding: .8em">Done By</td>
               </tr>
            </thead>
            <tbody id="display_info_test_1">
            </tbody>
         </table>
      </div>

   </div>
</div>

<div class="monitoring-body-weight" id="body-weight">
   <div class="data-view">
      <div class="title-header">
         <span>MONITORING BODY WEIGHT</span>
         <div class="close">
            <span class="close-form" style="color: #fff" id="close-form" onclick="document.getElementById('body-weight').style.display='none'">&times;</span>
         </div>
      </div>
      <div class="table">
         <table style="margin: 0px">
            <tbody>
               <tr>
                  <td class="data" style="padding: .8em">PATIENT NAME</td>
                  <td style="padding: .8em"><?php echo $name; ?></td>
                  <td class="data" style="padding: .8em">HOSPITAL NUMBER</td>
                  <td style="padding: .8em"><?php echo $patient_registration_id; ?></td>
                  <td class="data" style="padding: .8em">AGE</td>
                  <td style="padding: .8em"><?php echo $age; ?> Years</td>
                  <td class="data" style="padding: .8em">ADDRESS</td>
                  <td style="padding: .8em"> <?php 
                     echo $address;
                     ?></td>
               </tr>

               <tr>

                  <td class="data" style="padding: .8em">DIAGNOSIS</td>
                  <td style="padding: .8em" colspan="3"><?php echo $diagnosis; ?></td>
                  <td class="data" style="padding: .8em">DATE OF ADMISSION</td>
                  <td style="padding: .8em"><?php echo $Admission_Date_Time; ?></td>
                  <td class="data" style="padding: .8em">SEX</td>
                  <td style="padding: .8em"><?php echo $gender ?></td>
               </tr>
            </tbody>
         </table>
      </div>

      <div class="form">
         <form action="" method="POST">
            <div class="form-section">
               <input type="hidden" id="registration_id" value="<?php echo $patient_registration_id; ?>">
               <div class="input">
                  <label for="">Date Time</label>
                  <input type='date' placeholder="Enter Date" id="monitoring_date" />
               </div>
               <div class="input">
                  <label for="">Weight (in Kgs) </label>
                  <input type="text" placeholder="Enter Weight" id="body_weight">
               </div>
               <div class="input">
                  <label for="">Comments/Remarks</label>
                  <textarea name="" cols="30" rows="1.5" placeholder="Enter comment" id="comment"></textarea>
               </div>
               <div class="input" id="btn-add-section">
                  <label for=""></label>
                  <button type="submit" style="width:40%" id="add_motitoring_doc">ADD</button>
               </div>
               <input type="hidden" id="employee_id" value="<?php echo $_SESSION['userinfo']['Employee_ID'] ?>">
            </div>
         </form>
      </div>


      <!--  actions -->
      <div class="actions">
         <div>
            <label for="">Start Date</label>
            <input type="date" id="monitoring_start_date_filter" placeholder="Date and Time" />
         </div>
         <div>
            <label for="">End Date</label>
            <input type="date" id="monitoring_end_date_filter" placeholder="Date and Time" />
         </div>
         <div class="input" id="btn-add-section">
            <button type="submit" style="width:40%" id="filter_motitoring_doc">Filter</button>
         </div>
      </div>
      <!--  actions -->


      <div class="table-1">
         <table>
            <thead>
               <tr>
                  <td class="data" style="padding: .8em">Date </td>
                  <td class="data" style="padding: .8em">Weight</td>
                  <td class="data" style="padding: .8em">Comments</td>
                  <td class="data" style="padding: .8em">Sign</td>
               </tr>
            </thead>
            <tbody id="display_info">
            </tbody>
         </table>
      </div>


   </div>
</div>
</div>

</body>

<script src="./custom_js/burn_monitoring_wieght.js"></script>
<script src="./custom_js/convulation_documentation_chart.js"></script>
<script>
   var spee_therapy_form = document.getElementsByClassName("view_convulation");
   var close_form = document.getElementById('close-form');
   window.onclick = function(event) {
      if (event.target == spee_therapy_form) {
         spee_therapy_form.style.display = "none";
      }
   }

   $(document).ready(function() {
      $('#viewlabPatientList,#patientLabList').DataTable({
         "bJQueryUI": true
      });

      $('#Start_From').datetimepicker({
         dayOfWeekStart: 1,
         lang: 'en',
         //startDate:    'now'
      });
      $('#Start_From').datetimepicker({
         value: '',
         step: 30
      });

      $('#Date_From').datetimepicker({
         dayOfWeekStart: 1,
         lang: 'en',
         //startDate:    'now'
      });
      $('#Date_From').datetimepicker({
         value: '',
         step: 30
      });
      $('#Date_To').datetimepicker({
         dayOfWeekStart: 1,
         lang: 'en',
         //startDate:'now'
      });
      $('#Date_To').datetimepicker({
         value: '',
         step: 30
      });
   });
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

</html>