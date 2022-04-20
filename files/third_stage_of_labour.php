<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
  $admision_id = $_GET['admision_id'];
}
if (isset($_GET['Registration_ID'])) {
  $patient_id = $_GET['Registration_ID'];
}

if (isset($_GET['Employee_ID'])) {
  $Employee_ID = $_GET['Employee_ID'];
}
// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
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

$age = date_diff(date_create($DOB), date_create('today'))->y;

$select_third_stage = "SELECT * FROM tbl_third_stage_of_labour
                      WHERE patient_id='$patient_id'
                      AND admission_id='$admision_id'";

if ($result_third_stage = mysqli_query($conn,$select_third_stage)) {
  while ($row_third_stage = mysqli_fetch_assoc($result_third_stage)) {

    $method_of_delivery_of_placenter = $row_third_stage['methodology_delivery_placenter'];
    $date_and_time = $row_third_stage['date_and_time'];
    $duration = $row_third_stage['duration'];
    $placenta_weight = $row_third_stage['placenter_weight'];
    $colour = $row_third_stage['colour'];
    $cord = $row_third_stage['cord'];
    $state_of_cervix = $row_third_stage['state_of_cervix'];
    $episiotomy_tear = $row_third_stage['episiotomy_tear'];
    $repaired_with_suture = $row_third_stage['repaired_with_sutures'];
    $total_blood_loss = $row_third_stage['total_blood_loss'];
    $lochia = $row_third_stage['lochia'];
    $state_of_uterus = $row_third_stage['state_of_uterus'];
    $t = $row_third_stage['t'];
    $p = $row_third_stage['p'];
    $bp = $row_third_stage['bp'];
    $r = $row_third_stage['r'];
    $disposal = $row_third_stage['disposal'];
    $membrane = $row_third_stage['membranes'];
    $stage_of_placent = $row_third_stage['stage_of_placent'];
    $remarks = $row_third_stage['remarks'];
  }

}
?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID; ?>&patient_id=<?= $patient_id; ?>&admision_id=<?= $admision_id ?>" class="art-button-green">BACK</a>
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

  input{
    padding-left: 5px !important;
  }
</style>
<center>
<fieldset>
  <legend align=center>
    <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="margin:0px;padding:0px;">Third Stage Of Labour</p>
    <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
  </div>
  </legend>
  <form class="" action="" method="post" id="third_stage" onsubmit="return validateForm();">
    <input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id ?>">
    <input type="hidden" name="admission_id" value="<?= $admision_id ?>">
    <input type="hidden" name="consultaion_id" value="<?= $consultation_id ?>">

    <center>
    <table width="90%">
    <tr>
      <td class="input-label">Method of Delivery Of The Placenter</td>
      <td class="input">
        <input type="text" name="method_of_delivery_of_placenter" class="input" value="<?php if (!empty($method_of_delivery_of_placenter)) echo $method_of_delivery_of_placenter; ?>">
      </td>
    <!-- </tr>
    <tr> -->
      <td class="input-label">Date And Time </td>
      <td class="input"><input type="text" name="date_and_time" class="date" value="<?php if (!empty($date_and_time)) echo $date_and_time; ?>"> </td>

      <td class="input-label">Duration</td>
      <td class="input"><input type="text" name="duration" value="<?php if (!empty($duration)) echo $duration ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Placenta Weight</td>
      <td class="input"><input type="text" name="placenta_weight" value="<?php if (!empty($placenta_weight)) echo $placenta_weight; ?>"> </td>
    <!-- </tr>
    <tr> -->
      <td class="input-label">Stage Of Placent</td>
      <td class="input">
      <?php
      if (!empty($stage_of_placent) && $stage_of_placent == 'Complete') {
        ?>
      <input type="radio" name="stage_of_placent" value="Complete" checked> Complete
      <input type="radio" name="stage_of_placent" value="InComplete"> InComplete
      <?php

    } else if (!empty($stage_of_placent) && $stage_of_placent == 'InComplete') {
      ?>
      <input type="radio" name="stage_of_placent" value="Complete" > Complete
      <input type="radio" name="stage_of_placent" value="InComplete" checked> InComplete
      <?php

    } else { ?>
        <input type="radio" name="stage_of_placent" value="Complete" > Complete
      <input type="radio" name="stage_of_placent" value="InComplete"> InComplete
      <?php

    }
    ?>

      </td>

      <td class="input-label">Colour</td>
      <td class="input"><input type="text" name="colour" value="<?php if (!empty($colour)) echo $colour ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Cord</td>
        <td class="input"><input type="text" name="cord" value="<?php if (!empty($cord)) echo $cord ?>"> </td>

        <td class="input-label">Membrane</td>
        <td class="input">
        <?php
        if (!empty($membrane) && $membrane == 'Complete') {
          ?>
        <input type="radio" name="membrane" value="Complete" checked>Complete
        <input type="radio" name="membrane" value="InComplete">InComplete </td>
        <?php
      } else if (!empty($membrane) && $membrane == 'InComplete') {
        ?>
        <input type="radio" name="membrane" value="Complete">Complete
        <input type="radio" name="membrane" value="InComplete" checked>InComplete </td>

        <?php

      } else {
        ?>
        <input type="radio" name="membrane" value="Complete">Complete
        <input type="radio" name="membrane" value="InComplete" >InComplete </td>

        <?php
      }
      ?>

        <td class="input-label">Disposal</td>
        <td>

        <?php
        if (!empty($disposal) && $disposal == 'LAB') {
          ?>
          <input type="radio" name="disposal" value="LAB" checked>LAB
          <input type="radio" name="disposal" value="Incinerator">Incinerator

        <?php

      } else if (!empty($disposal) && $disposal == 'Incinerator') {
        ?>
            <input type="radio" name="disposal" value="LAB" checked>LAB
            <input type="radio" name="disposal" value="Incinerator" checked>Incinerator
          <?php

        } else {
          ?>
            <input type="radio" name="disposal" value="LAB" checked>LAB
            <input type="radio" name="disposal" value="Incinerator" >Incinerator
          <?php

        }
        ?>
         </td>
      </tr>
      <tr>
        <td class="input-label">State Of cervix</td>
        <td><input type="text" name="state_of_cervix" value="<?php if (!empty($stage_of_placent)) echo $stage_of_placent ?>"> </td>

        <td class="input-label">Episiotomy/Tear</td>
        <td><input type="text" name="episiotomy_tear" value="<?php if (!empty($episiotomy_tear)) echo $episiotomy_tear; ?>"> </td>

        <td class="input-label">Repaired With satures</td>
        <td><input type="text" name="repaired_with_suture" value="<?php if (!empty($repaired_with_suture)) echo $repaired_with_suture ?>"> </td>
      </tr>
      <tr>
        <td class="input-label">Total Blood Loss</td>
        <td><input type="text" name="total_blood_loss" value="<?php if (!empty($total_blood_loss)) echo $total_blood_loss ?>"> </td>

        <td class="input-label">Lochia</td>
        <td><input type="text" name="lochia" value="<?php if (!empty($lochia)) echo $lochia; ?>"> </td>

        <td class="input-label">state_of_uterus</td>
        <td>
          <input type="text" name="state_of_uterus" value="<?php if (!empty($state_of_uterus)) echo $state_of_uterus; ?>">
         </td>
      </tr>

      <tr>

        <td class="input-label-inside">Post Delivery Observation</td>
        <td class="input-label-inside">T</td>
        <td><input style="width:80px;" type="text" name="t" value="<?php if (!empty($t)) echo $t; ?>"></td>

        <td class="input-label-inside">p</td>
        <td><input style="width:80px;" type="text" name="p" value="<?php if (!empty($p)) echo $p ?>"></td>

        <td class="input-label-inside">R</td>
        <td><input style="width:80px;" type="text" name="r" value="<?php if (!empty($r)) echo $r ?>"></td>

        <td class="input-label-inside">BP</td>
        <td><input style="width:80px;" type="text" name="bp" value="<?php if (!empty($bp)) echo $bp ?>"></td>
        <!-- </tr>
        </table>
        </td> -->
        </tr>


      <tr>
      <td class="input-label">Remarks</td>
      <td colspan="3" class="input"><textarea name="remarks" rows="2" cols="40"><?php if (!empty($remarks)) echo $remarks ?></textarea> </td>
    </tr>

    </table>
  </center>
  </form>
  <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="save">Save</button>
  </span>
  <!-- <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="preview">Preview</button> -->
  <a href="print_third_stage_of_labour.php?patient_id=<?= $patient_id?>" class="art-button-green" target="_blank">Preview</a>
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
var labour_data = $("#third_stage").serialize();

  $.ajax({
    url:"save_third_stage.php",
    type:"POST",
    data:labour_data,
    success:function(data){
      alert(data);
      //location.reload(true);
    }

  })


})

$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})

function validateForm(){
  var val = $(".input").val();
  if( val === ""){
    alert("Filled Data")
    return false;
  }
}

$("#preview").click(function(){

  var patientId = $("#patient_id").val();

  window.open("print_third_stage_of_labour.php?patient_id="+patientId,'_blank');
})
</script>
