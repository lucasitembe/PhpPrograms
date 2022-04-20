<?php
include('get_form_five_data.php');
include('header.php');
include('../includes/connection.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}


$response = returnFormSixData($registration_id);
// echo json_encode($response);

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
  table tr td{
    height: 20px !important;
  }
</style>

 <center>
   <fieldset>
     <legend align=center style="font-weight:bold">Form Three</legend>
     <form class=""  method="post" id="six_form">

        <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id   ?>">

        <table width="100%">
         <th style="width:10% !important; ">Assessment</th>
         <th style="width:10% !important; ">Am</th>
         <th style="width:28% !important; ">PM</th>
         <th style="width:25% !important;">
           Night
         </th>

         <?php
         $list = array('Respiratory','Air entry','Breath Sound','Chest Expansion','Use of Accessory Muscle','Ability To Cough','cv','Rythm','Daily Weight','Capillary Refil',
         'Skin Condition','Colour:Pink/Pale/Cynotic/Juandice','Turgor:Normal/Loose/Tight/Shiny','Texture Dry/Moist','Odema[Sites]','GI', 'Abdomen:Soft/Hard/Distended/Tender','Bowel Sound:Normal hyperactive','Hypoactive/Absent','*NG Tube Insertion Date NA/Clamped/Cont Suction/INT. Suction Gravity','Diet(Restricted/Regular)','<b>Activity</b>','Level Of Mobility','(**CBR/up To Washroom)','Activity(Assisted/Self)','Drains NA/Type/Location','Character','Vomitus: Amount/Colour','Stool: Consistency/Clour/Pattern','Amount Small,Medium,Large,Nil',
         '<b>GU</b>',
         'Urine Colour /Sediments /Haematuria','Foleys Isertion Date',
         'Dialysis',
         '<b>Pulse Code</b>',
         '0 = Absent Radial:R/L','+1 = Weak  Femoral: R/L','+2 = Normal Dor Ped/R/L',
         '+3 = Strong Post tip R/L',
         '+4 = Bounding','<b>Nurse - Family Interaction</b>'
       );

       $list_name = array('respiratory','air_entry',
       'breath_sound','chest_expansion',
       'use_of_accessory_muscle','ability_to_cough','cv','rythm',
       'daily_weight','capillary_refil','skin_condition',
       'colour_pink_pale_cynotic_juandice',
       'turgor_normal_loose_ight_shiny','texture_dry_moist',
       'odema_sites','gi', 'abdomen_soft_hard_distended_tender',
       'bowel_sound_normal_hyperactive','hypoactive_absent',
       'ng_tube_insertion_date_na_clamped_cont_suction_int',
       'diet_restricted','activity','level_of_mobility',
       'cbr_up_to_washroom','activity_assisted_self',
       'drains_na_type_location','character',
       'vomitus_amount_colour',
       'stool_consistency','amount_small_m_l_nil','gu',
       'urine_colour','foleys_isertion_date','dialysis','pulse_code',
       'absent_radial','weak_femoral','normal_dor_ped',
       'strong_post_tip','bounding','nurse_family_interaction'
     );
          ?>

           <?php

           // echo count($list_name);
           for ($i=0; $i < count($list); $i++) {
             // echo $response[$list_name[$i].'am'];
              ?>
              <tr>
                <td><?=$list[$i]?></td>
                <td><input class="input_form_six" type="text" name="<?=$list_name[$i]?>am" value="<?php if(!empty($response[$list_name[$i].'am'])){
                  echo $response[$list_name[$i].'am'];
                }?>"> </td>
                <td><input class="input_form_six" type="text" name="<?=$list_name[$i]?>pm" value="<?php if(!empty($response[$list_name[$i].'pm'])){
                  echo $response[$list_name[$i].'pm'];
                }?>"> </td>
                <td><input class="input_form_six" type="text" name="<?=$list_name[$i]?>night" value="<?php if(!empty($response[$list_name[$i].'night'])){
                  echo $response[$list_name[$i].'night'];
                }?>"> </td>
              </tr>
              <?php
           }
            ?>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

            <tr>
              <td>
                Psychosocial
                Family Support
              </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
       </table>

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
    url:"get_data_status_six.php",
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
        url:"submit_form_six.php",
        type:"POST",
        data:{status:status,patient_id:registration_id},
        success:function(data){
          alert(data)
          getDataStatus()
        }
      })
    })

    $(".input_form_six").focusout(function(e){
      e.preventDefault();


      var input_name = $(this).attr("name");
      // alert(input_name)
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);

      $.ajax({
        url:"save_form_six.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,registration_id:registration_id},
        success:function(data){
          console.log(data)
        }
      })

    })

</script>
