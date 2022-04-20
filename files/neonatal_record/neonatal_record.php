<?php
include('header.php');
include('../includes/connection.php');
session_start();
if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  //$employee_ID = $_GET['Employee_ID'];

}


if (isset($_SESSION['userinfo'])) {
  $employee_ID = $_SESSION['userinfo']['Employee_ID'];
}


// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $registration_id . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
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

$age = date_diff(date_create($DOB), date_create('today'))->y;


 ?>

 <a href="../nursecommunicationpage.php?consultation_ID=<?= $consultation_id;?>&Employee_No=<?= $employee_ID;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<style media="screen">
  table{
    border-collapse: collapse;
    border: none !important;
  }
  table, tr, td{
    border:none !important;
  }



  .btn-title{
    width: 100%;

  }
</style>
<center>
 <fieldset >
   <legend style="font-weight:bold"align=center>
    <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
      <p style="margin:0px;padding:0px; text-align:center">NEONATAL AND POSTNATAL RECORDS</p>
      <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
    </div>
</legend>

     <table width="60%"  style="text-align:center">
       <!-- <tr>
         <td>
           <a href="neonatal_record_font_page.php?consultation_ID=<?php //=$consultation_id;?>&Employee_ID=<?php //= $employee_ID;?>&Admision_ID=<?php //=$admission_id;?>&Registration_ID=<?php //=$registration_id;?>">
             <button type="button" class="btn-title" name="button">
               NEONATAL RECORD
             </button>
           </a>
         </td>
       </tr> -->
       <tr>
         <!-- POST NATAL BY ABDUL -->
         <td>
          <a href="postnatal_record_font_page.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
              <button type="button" class="btn-title" name="button">POSTNATAL CHECKLIST
           </button>
           </a>
         </td>
       </tr>

       <tr>
         <!-- POST NATAL BY ABDUL -->
         <td>
            <a href='../Postnatal_Rejester.php?consultation_ID=<?= $consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>&section=Rch&RchWorks=RchWorksThisPage' target="_blank">
              <button type="button" class="btn-title" name="button">
                REJESTA YA MAMA NA MTOTO BAADA YA KUJIFUNGUA
           </button>
           </a>
         </td>
       </tr>
       <!-- END BY ABDUL -->
       <tr>
         <!-- NEWBORN TRIAGE CHECKLIST BY ABDUL -->
         <td>
          <a href="newborn_triage_checklist_font_page.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
              <button type="button" class="btn-title" name="button">NEWBORN TRIAGE CHECKLIST
           </button>
           </a>
         </td>
       </tr>
       <!-- END BY ABDUL -->

       <!-- <tr>
         <td>
          <a href="infant_labour_room.php?consultation_ID=<?php //=$consultation_id;?>&Employee_ID=<?php //= $employee_ID;?>&Admision_ID=<?php //=$admission_id;?>&Registration_ID=<?php //=$registration_id;?>">
              <button type="button" class="btn-title" name="button">INFANT LABOUR ROOM ADMISSION RECORD TO NEONATAL WARD
           </button>
           </a>
         </td>
       </tr> -->
       <tr>
         <td>
           <a href="neonatal_care_admission.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
               <button type="button" class="btn-title" name="button">NEONATAL CARE ADMISSION FORM
            </button>
            </a>
           <!-- <a href="assessment.php?consultation_ID=<?php //$consultation_id;?>
             &Employee_ID=<?php //$employee_ID;?>&Admision_ID=<?php //$admission_id;?>&Registration_ID=<?php //$registration_id;?>">
           <button type="button" class="btn-title" name="button">
             ASSESSMENT1<?php //$admission_id?>
           </button>
           </a> -->
         </td>
       </tr>
       <tr>
         <td>
           <a href="hypoxic_ischaemic_encephalopath.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
               <button type="button" class="btn-title" name="button">HYPOXIC ISCHAEMIC ENCEPHALOPATHY SCORE
            </button>
          </a>
         </td>
       </tr>
       <tr>
         <td>
           <a href="silverman_anderson_score_chart.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
               <button type="button" class="btn-title" name="button">SILVERMAN-ANDERSON SCORE CHART
            </button>
          </a>
         </td>
       </tr>
       <tr>
         <td>
           <a href="assessment2.php?consultation_ID=<?=$consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id;?>&Registration_ID=<?=$registration_id;?>">
           <button type="button" class="btn-title" name="button">
             ASSESSMENT2
           </button>
           </a>
         </td>
       </tr>

          </tbody>

      </table>
</center>
   </fieldset>
<center>
