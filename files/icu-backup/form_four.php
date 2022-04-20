<?php
include('header.php');
include('../includes/connection.php');
include('get_form_four_data.php');
// include('get_form_four_data_second.php');
if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
}

$response_data = return_form_four($registration_id);
$response_data_second = return_form_four_second($registration_id);
// testing commented
// echo json_encode($response_data_second);
// echo $registration_id;
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

 <a href="icu.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>


<style media="screen">
  .title{
    width: 15%;
  }

  .section{
    width: 49%;
    display: inline-block;
    vertical-align: top;
  }
</style>
<center>
  <fieldset>
    <legend align="center">Form four</legend>

    <form method="post" id="fourth_form">

      <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id;?>">

    <table width="100%;">
      <tr>
        <th class="title">24 Hrs Fluid Balance</th>
        <td><input type="text" class="input_form_four" name="24_hrs_fluid_balance" value="<?php if(!empty($response_data['timeone'])){
          echo $response_data['timeone'];
        }?>" placeholder="24 Hrs Fluid Balance"> </td>
      </tr>
      <tr>
        <th class="title">Previous Accumlated Balance</th>
        <td><input type="text" class="input_form_four" name="previous_accumlated_balance" value="<?php if(!empty($response_data['potassiumone'])){
          echo $response_data['potassiumone'];
        }?>" placeholder="Previous Accumlated Balance"> </td>
      </tr>
      <tr>
        <th class="title input">Day Accumlated Balance</th>
        <td><input type="text" class="input_form_four" name="day_accumlated_balance" value="<?php if(!empty($response_data['day_accumlated_balance'])){
          echo $response_data['day_accumlated_balance'];
        }?>"
          placeholder="Day Accumlated Balance"> </td>
      </tr>
      <tr>
        <th class="title">Fluid Restriction</th>
        <td>
          <input type="text" class="input_form_four" name="fluid_restriction" value="<?php if(!empty($response_data['fluid_restriction'])){
          echo $response_data['fluid_restriction'];
        }?>" placeholder="Fluid Restriction">
      </td>
      </tr>
    </table>

    <div class="" style="text-align:left">
      <p>LAB REPORTS</p>
    </div>



<div class="section">

  <table width='100%'>

    <?php

    $first = array('Time' ,'Potassium','Sodium','Calcium','Magnesium','Chloride','Bicarbonate','BUN',
    'Cteatinine','bBlood Glucose','HB','HCT','RBC','WBC','Neutrophil','Basophil','Esinophil','Esr',
    'PT/Cont','APTT/Cont','INR','PLT','MP','','','' );

    $first_list = array('time','potassium','sodium','calcium','magnesium','chloride',
    'bicarbonate','bun','cteatinine','bblood_glucose','hb','hct','rbc','wbc',
    'neutrophil','basophil','esinophil','esr','pt_cont','aptt_cont','inr',
    'plt','pm','','','' );

    $second = array('','Total Blirrubin','Total Protein','Albumin','A/G Ratio','Amylase','AST','ALT','ALP','SGOT','SGPT','CK','CK - MB','LDH',
    'Troponin','Myglobin','D-Dinner','','','','','','','','','' );

    $second_list = array('','total_blirrubin','total_protein','albumin',
    'ag_ratio','amylase','ast','alt','alp','sgot','sgpt','ck','ck_mb','ldh',
    'troponin','myglobin','d_dinner','','','','','','','','','' );
    for ($i=0; $i < 23; $i++) {
      echo $response_data[$first_list[$i]."one"];
      ?>
      <tr>
        <td style="width:5%; text-align:right; "><?=$first[$i];?></td>
        <td style="width:4% !important">
          <input type="text"  class="input_form_four" name="<?=$first_list[$i];?>one" value="<?php if(!empty($response_data[$first_list[$i]."one"])){
            echo $response_data[$first_list[$i]."one"];
          }?>">
         </td>
        <td style="width:4% !important">
          <input type="text" class="input_form_four"  name="<?=$first_list[$i];?>second" value="<?php if(!empty($response_data[$first_list[$i]."second"])){
            echo $response_data[$first_list[$i]."second"];

          }?>">
        </td>
        <td style="width:4% !important">
          <input type="text" class="input_form_four"  name="<?=$first_list[$i];?>third" value="<?php if(!empty($response_data[$first_list[$i]."third"])){
            echo $response_data[$first_list[$i]."third"];
          }?>">
        </td>

        <td style="width:3% !important; text-align:right "><?=$second[$i];?></td>
        <td style="width:4% !important">
          <input type="text" class="input_form_four"  name="<?=$second_list[$i];?>one" value="<?php if(!empty($response_data_second[$second_list[$i]."one"])){
            echo $response_data_second[$second_list[$i]."one"];
          }?>">
        </td>

        <td style="width:4% !important">
          <input type="text"  class="input_form_four" name="<?=$second_list[$i];?>second" value="<?php if(!empty($response_data_second[$second_list[$i]."second"])){
            echo $response_data_second[$second_list[$i]."second"];
          }?>">
        </td>
        <td style="width:4% !important">
          <input type="text" class="input_form_four"  name="<?=$second_list[$i];?>third" value="<?php if(!empty($response_data_second[$second_list[$i]."third"])){
            echo $response_data_second[$second_list[$i]."third"];
          }?>">
        </td>
      </tr>
      <?php

    }
     ?>

  </table>

</div>


<div class="section">
<p style="color:#037DB0; font-weight:bold; "><span>Key for bedstore Management 1.</span>
  <span>Keep it clean and open 2.</span>
  <span>Wash with normal saline and cover with dry gauze.3.</span>
  <span>Specified</span>
</p>
<table width='100%;'>
<tr>
  <th rowspan="2" style="width:48%">
    <span> Management</span>
    <span>Location,Staging,Description,Treatment</span>
    <hr />
  </th>
  <th rowspan="2">Skin Assessment{Bedstore,LipsoredRash,Bruise,Blister,Edema,Ulcer}</th>
</tr>
<tr>

</tr>
</table>

<table width="100%">
  <tr>
<th>Time</th>
<th>Initials</th>
<th>Name/Signature/Desgnation</th>
</tr>
<tr>
  <th>0800-1400</th>
  <td><input type="text" name="" value=""></td>
  <td><input type="text" name="" value=""></td>
</tr>
<tr>
  <th>1400-2000</th>
  <td><input type="text" name="" value=""></td>
  <td><input type="text" name="" value=""></td>
</tr>
<tr>
  <th>2000-0000</th>
  <td><input type="text" name="" value=""></td>
  <td><input type="text" name="" value=""></td>
</tr>
</table>
</div>

<div class="">
  <span>
    <input type="submit" id="submit" name="submit" value="SAVE" class="art-button-green" />
  </span>

  <span>
    <input type="submit" id="preview" name="submit" value="PREVIEW" class="art-button-green" />
  </span>

</div>

</form>

<div class="file-list" style="margin-top:10px">

</div>
  </fieldset>
</center>

<script type="text/javascript">

function getDataStatus(){
  var registration_id = $("#registration_id").val();
  $.ajax({
    url:"get_data_status_four.php",
    type:"post",
    data:{patient_id:registration_id},
    success:function(data){

      var jsonData = JSON.parse(data);
      console.log(jsonData.date_time)
      if (jsonData.data_status == "saved") {
        $(".file-list").append("<a href='form_seven.php?Registration_ID=1142' class='art-button-green'>"+jsonData.date_time+"</a>")

      }
  }
  })
}

$(document).ready(function(data){

  getDataStatus()

})

    $("#submit").click(function(e){
      e.preventDefault();
      var status = "saved";
      registration_id = $("#registration_id").val();
      alert(registration_id)
      $.ajax({
        url:"submit_form_four.php",
        type:"POST",
        data:{status:status,patient_id:registration_id},
        success:function(data){
          alert(data)
          getDataStatus()
        }
      })
    })


    $("#fourth_form").submit(function(e){
      e.preventDefault();

      var form_seven_data  = $(this).serialize();

      alert(form_seven_data)
    })

    $(".input_form_four").focusout(function(e){save_form_four.php
      e.preventDefault();


      var input_name = $(this).attr("name");
      // alert(input_name)
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);

      $.ajax({
        url:"save_form_four.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,registration_id:registration_id},
        success:function(data){
          console.log(data)
        }
      })

    })

</script>
