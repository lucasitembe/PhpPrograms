<?php
include("./includes/header.php");
include("./includes/connection.php");

//$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
  @session_destroy();
  header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {

} else {
  @session_destroy();
  header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {

  if ($_SESSION['outpatient_nurse_com'] == "no") {
    ?>
        <!-- <a href='searchpatientinward.php?Registration_ID=<?php echo filter_input(INPUT_GET, 'Registration_ID'); ?>&BackTonurseCommunication=BackTonurseCommunicationPage' class='art-button-green'>
            PATIENT LIST
        </a> -->
        <?php

      } else {
        ?>
       <!-- <a href='searchnurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage' class='art-button-green'>
            PATIENT LIST
        </a> -->
      <?php

    }
  }

  $nav = '';


  if (isset($_GET['discharged'])) {
    $nav = '&discharged=discharged';
  }

  if (isset($_SESSION['userinfo'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
  }

  if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
  }

  if (isset($_GET['admision_id'])) {
    $Admision_id = $_GET['admision_id'];
  }
  if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
  }
  if (isset($_GET['this_page_from'])) {
    $this_page_from = $_GET['this_page_from'];
  }

  // get patient details
  if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
  			FROM
  				tbl_patient_registration pr,
  				tbl_sponsor sp
  			WHERE
  				pr.Registration_ID = '" . $patient_id . "' AND
  				sp.Sponsor_ID = pr.Sponsor_ID
  				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
      while ($row = mysqli_fetch_array($select_patien_details)) {
        $Member_Number = $row['Member_Number'];
        $Patient_Name = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $DOB = $row['Date_Of_Birth'];
      }
    } else {
      $Guarantor_Name = '';
      $Member_Number = '';
      $Patient_Name = '';
      $Gender = '';
      $Registration_ID = 0;
    }
  } else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
  }

  // $age = date_diff(date_create($DOB), date_create('today'))->y;
  $age = date_diff(date_create($DOB), date_create('today'))->y;
  $checkgender = '';
  if (strtolower($Gender) == 'male') {
    $checkgender = "onclick='notifyUser(this)'";
  }

  ?>
  <?php
  if($this_page_from=='ThisPageFromInpatientclinicalnotes'){

  }else{?>
		<a href="nursecommunicationpage.php?consultation_ID=<?= $consultation_id; ?>&Employee_No=<?= $Employee_ID; ?>&Registration_ID=<?= $patient_id; ?>&Admision_ID=<?= $Admision_id ?>" class="art-button-green">BACK</a>
<?php
  }
  ?>


<div id="result"></div>
<br />
<br />
<fieldset>
  <legend style="font-weight:bold" align=center>
    <div class="">
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
        <p style="margin:0px;padding:0px;">LABOUR ANTENATAL NEONAATAL RECORD</p>
        <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
      </div>

    </div>
    <input type="hidden" id="patient_gender" value="<?=$Gender?>">
    <input type="hidden" id="employee_id" value="<?=$_SESSION['userinfo']['Employee_ID']?>">
    <input type="hidden" id="patient_age" value="<?=$age?>">
    <input type="hidden" id="patient_id" value="<?=$Registration_ID?>">
    <input type="hidden" id="patient_name" value="<?=$Patient_Name?>">
      </legend>
 <center>
     <table width = 80%>
     <!-- ############################DONT DELETE ANY COMMENTED CODE###################################### -->
         <tr>
             <td style='text-align: center; height: 40px; width: 33%;'>
                 <a href="demographic.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $Admision_id ?>">
                   <button style='width: 100%; height:100%'>
                     Labour Admission
                   </button>
                 </a>

             </td>
             <td style='text-align: center; height: 40px; width: 33%;'>
               <!-- <a href="labour_record.php?consultation_id=<?php//= $consultation_id; ?>&Employee_ID=<?php//= $Employee_ID;?>&Registration_ID=<?php//= $patient_id; ?>&admission_id=<?php//= $Admision_id; ?>">
               -->
               <a href="general_examination.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&admission_id=<?= $Admision_id; ?>">
                 <button style='width: 100%; height: 100%'>
                            General examination form
                            </button>
                          </a>

             </td>
             <td style='text-align: center; height: 40px; width: 33%;'>
                         <a href="observation_chart.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&admision_id=<?= $Admision_id ?>">
                           <button style='width: 100%; height: 100%'>
                            Observation Chart
                            </button>
                          </a>

             </td>

         </tr>
         <tr>

           <!-- <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="Patograph_record.php?consultation_id=&Employee_ID=&patient_id=&admission_id=
                         "><button style='width: 100%; height: 100%'> Patograph Record   </button></a>
           </td> -->
           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="martenal_condition.php?consultation_id=<?= $consultation_id; ?>&&patient_id=<?= $patient_id; ?>&&admission_id=<?= $Admision_id; ?>"><button style='width: 100%; height: 100%'> Patograph Chart Records</button></a>
           </td>
<!-- ############################DONT DELETE ANY COMMENTED CODE###################################### -->
        <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='postnatal_antenatal_checklist.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&admision_id=<?= $Admision_id; ?>'><button style='width: 100%; height: 100%'>Antenatal Checklist </button></a>

        </td>
           <!-- <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="first_stage_of_labour.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>
                         &admission_id=<?= $Admision_id; ?>">
                         <button style='width: 100%; height: 100%'> First Stage Of Labour   </button></a>

           </td>

           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="second_stage_of_labour.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&admision_id=<?= $Admision_id; ?>">
                         <button style='width: 100%; height: 100%'> Second Stage Of Labour   </button></a>

           </td> -->
           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="third_stage_of_labour.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&&admision_id=
                         <?= $Admision_id; ?>"><button style='width: 100%; height: 100%'> Third Stage Of Labour   </button></a>

           </td>

         </tr>
         <!-- ############################DONT DELETE ANY COMMENTED CODE###################################### -->
         <tr>



           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href="fourt_stage_of_labour.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&admission_id=
                         <?= $Admision_id; ?>"><button style='width: 100%; height: 100%'> Fourth Stage Of Labour Observation Chart</button></a>

           </td>

           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href='nursecommunication_babycarechart.php?consultation_ID=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID;?>&Registration_ID=<?= $patient_id; ?>&Admision_ID=<?= $Admision_id ?>'><button style='width: 100%; height: 100%'> Baby Care Chart   </button></a>

           </td>

           <td style='text-align: center; height: 40px; width: 33%;'>
                       <a><input type="button" style='width: 100%; height: 100%' onclick="open_patograph('','','')"value="Patograph Record Report"></a>

          </td>



         </tr>
         <tr>
<!-- ############################  DONT DELETE ANY COMMENTED CODE###################################### -->
           <!-- <td style='text-align: center; height: 40px; width: 33%;'>
                    <button style='width: 100%; height: 100%'>PATIENT FILE</button></a>

                       <a href="Patientfile_Record.php?Registration_ID=&Employee_ID=&consultation_ID="><button style='width: 100%; height: 100%'> Doctor Notes And Patient File   </button></a>

           </td> -->

           <!-- <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href=nurse_care_report.php'?Registration_ID=&Employee_ID='><button style='width: 100%; height: 100%'>Nurse Care Report </button></a>

           </td> -->



         </tr>

        <tr>
        <!-- ############################ DONT DELETE ANY COMMENTED CODE###################################### -->
         <!-- <td style='text-align: center; height: 40px;'>
                 <a href='powercharts_Labour_real.php?pn=&Employee_ID=&Admision_ID=&consultation_ID=&LabourWardNurseNotes=LabourWardNurseNotesThisPage'><button style='width: 100%; height: 100%'>Labour,Delivery,Postnatal Case Notes</button></a>
             </td>

             <td style='text-align: center; height: 40px;'>
                 <a href='labourwardnursenotes.php?Registration_ID=&Employee_ID=&Admision_ID=&consultation_ID=&LabourWardNurseNotes=LabourWardNurseNotesThisPage'><button style='width: 100%; height: 100%'>Labour Ward Nurses Notes</button></a>
             </td> -->

         </tr>
         <tr>
         <!-- <td style='text-align: center; height: 40px; width: 33%;'> -->
               <?php
              // $sql_select_baby_info = mysqli_query($conn,"select babywho from tbl_baby_care where parent_id = '$patient_id' AND DATE(saved_date)=CURDATE() ORDER BY baby_care_id DESC") or die(mysqli_error($conn));
              // if (mysqli_num_rows($sql_select_baby_info) > 0) {
              //   $baby_row = mysqli_fetch_assoc($sql_select_baby_info);
              //   $babywho = $baby_row['babywho'];
              // }
              // $sql_select_baby_info = mysqli_query($conn,"select babywho from tbl_baby_care where parent_id = '$patient_id' ORDER BY baby_care_id DESC") or die(mysqli_error($conn));
              // $count_baby_care_chart = mysqli_num_rows($sql_select_baby_info);
              ?>

               <!-- <a href="nursecommunication_babycarechart.php?Registration_ID=&Employee_ID=&admission_id=">
                 <button style='width: 100%; height: 100%' >Baby Care Chart   <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'></span></button>
               </a>

           </td> -->

         </tr>
         <tr>
         <!-- <td style='text-align: center; height: 40px; width: 33%;'>
                       <a><input type="button" style='width: 100%; height: 100%' onclick="open_patograph('','','')"value="Patograph Record Report"></a>

           </td> -->
         </tr>

     </table>
 </center>
 </fieldset>

 <!-- mother details -->
<input type="hidden" name="">
<!-- mother details -->
<div id="result2"></div>


 <?php
include("./includes/footer.php");
?>
<script>


  function open_patograph(consultation_id,patient_id,Admision_id){
            var patient_age = $('#patient_age').val();
            var patient_gender = $('#patient_gender').val();
            var patient_name = $('#patient_name').val();
         $.ajax({
                type:'GET',
                url: 'maternal_condition_previous_result.php',
                data : {
                  patient_id:patient_id,
                  Admision_id:Admision_id,
                  patient_age:patient_age,
                  patient_name:patient_name,
                  patient_gender:patient_gender
               },
               success : function(data){
                $('#result').html(data);
                    $('#result').dialog({
                        autoOpen:true,
                        width:'90%',
                        height: 500,
                        position: ['center',0],
                        title:'PATOGRAPH RECORD:',
                        modal:true
                    });
                    $('#result').html(data);

               }
           });
  }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<!-- <script>


  function open_patograph(consultation_id,patient_id,Admision_id){
            var patient_age = $('#patient_age').val();
            var patient_gender = $('#patient_gender').val();
            var patient_name = $('#patient_name').val();
         $.ajax({
                type:'GET',
                url: 'maternal_condition_previous_result.php',
                data : {
                  patient_id:patient_id,
                  Admision_id:Admision_id,
                  patient_age:patient_age,
                  patient_name:patient_name,
                  patient_gender:patient_gender
               },
               success : function(data){
                $('#result').html(data);
                    $('#result').dialog({
                        autoOpen:true,
                        width:'90%',
                        height: 500,
                        position: ['center',0],
                        title:'PATOGRAPH RECORD:',
                        modal:true
                    });
                    $('#result').html(data);

               }
           });
  }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> -->
