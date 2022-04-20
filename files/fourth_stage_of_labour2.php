<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])){
  $admision_id = $_GET['admision_id'];
}
if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
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


// select fourth stage labour
$select_fourth_stage_labour = "SELECT * FROM tbl_fourth_stage_of_labour WHERE patient_id='$patient_id' AND admission_id='$admision_id'";
if ($result_fourth_stage = mysqli_query($conn,$select_fourth_stage_labour)) {
  while ($row_fourth_stage= mysqli_fetch_assoc($result_fourth_stage)) {

    $bp = $row_fourth_stage['bp'];
    $temp = $row_fourth_stage['temp'];
    $pr = $row_fourth_stage['pr'];
    $state_of_uterus = $row_fourth_stage['state_of_uterus'];
    $fundal_height = $row_fourth_stage['fundal_height'];
    $state_of_cervix = $row_fourth_stage['state_of_cervix'];
    $state_of_perinium = $row_fourth_stage['state_of_perinium'];
    $suture_type = $row_fourth_stage['type_of_sature'];
    $how_many_stiches = $row_fourth_stage['number_of_stiches'];
    $blood_loss = $row_fourth_stage['blood_loss'];
    $doctor_recommendation = $row_fourth_stage['doctor_midwife_recommendation'];


  }
}



?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a>
<br />
<br />
<style media="screen">
table{
  border: none !important;
}
table tr,td{
  border:none !important;
}
  .input-label{
    text-align:right !important;
    width: 16%;
  }
  .input{
    width: 20%;
  }
  .input-label-inside{
    width: 15px;
    text-align: right !important;
  }
</style>
<center>
<fieldset>
  <legend align=center>
    <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="margin:0px;padding:0px;">Third Stage Of Labour</p>
    <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
  </div>
  </legend>
  <form class="" action="" method="post" id="fourth_stage">

    <input type="hidden" name="patient_id" value="<?=$patient_id?>">
    <input type="hidden" name="admission_id" value="<?=$admision_id?>">
    <input type="hidden" name="consultaion_id" value="<?=$consultation_id?>">

    <center>
    <table width="90%">
      <tr>
        <th style="text-align:left" colspan="6">Post Delivery Recommendation</th>
      </tr>
    <tr>
      <td class="input-label">Temp</td>
      <td class="input">
        <input type="text" name="temp" class="date" value="<?php if(!empty($temp)) echo $temp; ?>">
      </td>
    <!-- </tr>
    <tr> -->
      <td class="input-label">PR </td>
      <td class="input"><input type="text" name="pr" value="<?php if(!empty($pr)) echo $pr; ?>"> </td>

      <td class="input-label">BP</td>
      <td class="input"><input type="text" name="bp" value="<?php if(!empty($bp)) echo $bp; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">State Of Uterus</td>
      <td class="input"><input type="text" name="state_of_uterus" value="<?php if(!empty($state_of_uterus)) echo $state_of_uterus; ?>"> </td>
    <!-- </tr>
    <tr> -->
      <td class="input-label">Fundal Height</td>
      <td class="input"><input type="text" name="fundal_height" value="<?php if(!empty($fundal_height)) echo $fundal_height; ?>"></td>

      <td class="input-label">State Of Cervix</td>
      <td class="input"><input type="text" name="state_of_cervix" value="<?php if(!empty($state_of_cervix)) echo $state_of_cervix; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">State Of Perinium</td>
        <td class="input"><input type="text" name="state_of_perinium" value="<?php if(!empty($state_of_perinium)) echo $state_of_perinium; ?>"> </td>

        <td class="input-label">If Repared type of Suture</td>
        <td class="input"><input type="text" name="suture_type" value="<?php if(!empty($suture_type)) echo $suture_type; ?>">
         </td>
         <td class="input-label">How Many Stiches</td>
         <td class="input"><input type="text" name="how_many_stiches" value="<?php if(!empty($how_many_stiches)) echo $how_many_stiches; ?>">
          </td>
      </tr>


      <tr>
        <td class="input-label">Blood Loss:</td>
        <td>
          <input type="text" name="blood_loss" value="<?php if(!empty($blood_loss)) echo $blood_loss; ?>">
        </td>

      <td class="input-label">Doctor/Midwife Recommendation:</td>
      <td colspan="3" class="input"><textarea name="doctor_recommandation" rows="2" cols="40"><?php if(!empty($doctor_recommendation)) echo $doctor_recommendation; ?></textarea> </td>
    </tr>

    </table>
  </center>
  </form>
  <br />
  <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="save">Save</button>
  </span>
  <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="save">Preview</button>
  </span>
</fieldset>
</center>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

// save labour record
$("#save").click(function(e){
e.preventDefault();
var labour_data = $("#fourth_stage").serialize();

  $.ajax({
    url:"save_fourth_stage.php",
    type:"POST",
    data:labour_data,
    success:function(result){
      alert(result);
    }

  });


})

$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})

</script>
