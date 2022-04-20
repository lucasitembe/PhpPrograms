<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
  $admision_id = $_GET['admission_id'];
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

// select first stage of labour
$today = Date("Y-m-d");

$select_first_stage = "SELECT * FROM tbl_first_stage_of_labour 
        WHERE patient_id='$patient_id' 
        AND admission_id='$admision_id' 
        AND Date(set_of_labour_time_and_date)='$today'";

if ($result_first_stage = mysqli_query($conn,$select_first_stage)) {
  while ($row_first_stage = mysqli_fetch_assoc($result_first_stage)) {

    $set_labour_time_and_date = $row_first_stage['set_of_labour_time_and_date'];
    $admitted_at = $row_first_stage['admitted_at'];
    $if_ruptured_date_and_time = $row_first_stage['time_and_date_of_rupture'];
    $total_time_elapsed_since_rupture = $row_first_stage['time_elapsed_since_rupture'];
    $yes_reasons = $row_first_stage['induction_reason'];
    $state_of_membrane = $row_first_stage['state_of_membrane'];
    $duration_of_first_labour = $row_first_stage['total_duration_of_first_stage_labour'];
    $abdomalities = $row_first_stage['abdomalities_of_first_stage'];
    $drug_given = $row_first_stage['drugs_given'];
    $remark = $row_first_stage['remarks'];
    $arm = $row_first_stage['arm'];
    $no_of_vaginal_examination = $row_first_stage['no_of_vaginal_examination'];
    $induction_of_labour = $row_first_stage['induction_of_labour'];
  }
}


?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id ?>" class="art-button-green">BACK</a>
<br />
<br />
<style media="screen">
  table tr,td{
    border:none !important;
  }

  .input{
    width:30% !important;
  }

  .label-input{
      width: 0% !important ;
      text-align: right !important;

  }
</style>
<center>
  <fieldset>
    <legend align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
      <p style="margin:0px;padding:0px;">Fisrt Stage Of Labour</p>
      <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
    </div>
    </legend>
    <form class="" action="index.html" method="post" id="first_stage">
      <input type="hidden" id="patient_id" name="patient_id" value="<?= $patient_id ?>">
      <input type="hidden" name="admission_id" value="<?= $admision_id ?>">
      <input type="hidden" name="consultaion_id" value="<?= $consultation_id ?>">

      <table width="100%">


      <tr>
        <td class="label-input">On Set Labour Time And Date</td>
        <td>
        <input type="text" name="set_labour_time_and_date" class="date" value="<?php if (!empty($set_labour_time_and_date)) echo $set_labour_time_and_date; ?>"> </td>
      <!-- </tr>
      <tr> -->
        <td class="label-input">Admitted At</td>
        <td><input type="text" name="admitted_at" value="<?php if (!empty($admitted_at)) echo $admitted_at; ?>">CxDilation </td>
      </tr>
      <tr>
        <td  class="label-input">State Of Membrane</td>
        <td class="input">
        <?php 
        if (!empty($state_of_membrane) && $state_of_membrane == 'Intact') {
          ?>
          <span><input type="radio" name="state_of_membrane" value="Intact" checked></span>
          <span>Intact </span>
          <span><input type="radio" name="state_of_membrane" value="Rapture"></span>
          <span>Rapture</span>  
          <?php

        } else if (!empty($state_of_membrane) && $state_of_membrane == 'Rapture') {
          ?>
          <span>
          <input type="radio" name="state_of_membrane" value="Intact">
          </span>
          <span>Intact </span>
          <span>
          <input type="radio" name="state_of_membrane" value="Rapture" checked>
          </span>
          <span>Rapture</span>
          <?php

        } else {
          ?>
          <span><input type="radio" name="state_of_membrane" value="Intact"></span>
          <span>Intact </span>
          <span><input type="radio" name="state_of_membrane" value="Rapture"></span>
          <span>Rapture</span>
          <?php 
        }
        ?>
        </td>
      <!-- </tr>
      <tr> -->

        <td  class="label-input">If Rupture: Date And Time</td>
        <td class="input"><input type="text" class="date" name="if_rupture_date_and_time" value="<?php if (!empty($if_ruptured_date_and_time)) echo $if_ruptured_date_and_time; ?>"> </td>

      </tr>

      <tr>
        <td class="label-input">Total Time elapsed since Rupture</td>
        <td class="input"><input type="text" name="total_time_elapsed_since_rupture" value="<?php if (!empty($total_time_elapsed_since_rupture)) echo $total_time_elapsed_since_rupture ?>"> </td>
      <!-- </tr>

      <tr> -->
        <td class="label-input">ARM</td>
        <td class="input">
        <?php
        if (!empty($arm) && $arm === "Done") {
          ?>
          <input type="radio" name="arm" value="Done" checked>Done 
          <input type="radio" name="arm" value="Not Done ">Not Done
            
          <?php 
        } else if (!empty($arm) && $arm === "Not Done") {
          ?>
            <input type="radio" name="arm" value="Done" checked>Done 
          <input type="radio" name="arm" value="Not Done" checked>Not Done
          <?php 
        } else {
          ?>
            <input type="radio" name="arm" value="Done">Done 
        <input type="radio" name="arm" value="Not Done ">Not Done
          <?php

        }
        ?>
          
         </td>
      </tr>

      <tr>
        <td class="label-input">Number Of varginal Examination</td>
        <td class="input"><input type="text" name="no_of_vaginal_examination" value="<?php if (!empty($no_of_vaginal_examination)) echo $no_of_vaginal_examination; ?>"> </td>

        <td class="label-input">Induction Of Labour</td>

        <td class="input">
        <?php
        if (!empty($induction_of_labour) && $induction_of_labour == 'YES') {
          ?>
            <input type="radio" name="induction_of_labour" value="YES" checked> YES 
            <input type="radio" name="induction_of_labour" value="No"> No  
          <?php 
        } else if (!empty($induction_of_labour) && $induction_of_labour == 'No') { ?>
            <input type="radio" name="induction_of_labour" value="YES" > YES 
            <input type="radio" name="induction_of_labour" value="No"checked> No 
          <?php 
        } else {
          ?>
          
          <input type="radio" name="induction_of_labour" value="YES" > YES 
          <input type="radio" name="induction_of_labour" value="No"> No

          <?php

        }
        ?>
         
        
        </td>
      </tr>
      <tr>
      <td class="label-input">If Yes Reasons:</td>
      <td class="input"><input type="text" name="yes_reason" value="<?php if (!empty($yes_reasons)) echo $yes_reasons; ?>"> </td>

        <td class="label-input">Total Duration of First Stage Labour</td>
        <td class="input"><input type="text" name="duration_of_first_stage_labour" value="<?php if (!empty($duration_of_first_labour)) echo $duration_of_first_labour; ?>"> </td>
      </tr>
      <tr>
        <td class="label-input">Any Abdomalities of First Stage</td>
        <td class="input"><textarea name="abdomalities_first_stage" rows="3" cols="40"><?php if (!empty($abdomalities)) {
                                                                                        echo $abdomalities;
                                                                                      } ?></textarea> </td>

        <td class="label-input">Drug Given</td>
        <td class="input"><textarea name="drug_given" rows="3" cols="40">
          <?php
          if (!empty($drug_given))
            echo $drug_given;
          ?>
        </textarea> </td>
      </tr>
      <tr>
        <td class="label-input">Remarks</td>
        <td class="input"><textarea name="remarks" rows="3" cols="40"><?php if (!empty($remark)) {
                                                                        echo $remark;
                                                                      } ?></textarea> </td>
      </tr>
        </table>
    </form>
  </fieldset>

  <span><button type="button" class="art-button-green" name="button" style="color:#fff !important; width:70px!important; height:25px !important;"
  id="save">Save</button>
</span>
<span>
  <button type="button" class="art-button-green" name="button" style="color:#fff !important; width:70px!important; height:25px !important;"
  id="preview">Preview</button>
</span>
</center>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

// save labour record
$("#save").click(function(e){
e.preventDefault();
var labour_data = $("#first_stage").serialize();

  $.ajax({
    url:"save_first_stage.php",
    type:"POST",
    data:labour_data,
    success:function(data){
      alert(data);
    }

  })

})

$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})


$("#preview").click(function(){
  var patientId = $("#patient_id").val();

  window.open("print_first_stage_of_labour.php?patient_id="+patientId,'_blank');
})
</script>
