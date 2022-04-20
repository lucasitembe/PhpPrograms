<?php
include('header.php');
include('../includes/connection.php');
include('get_form_five_data.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}

$response = returnFormFiveData($registration_id);
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
 ?>

 <a href="icu.php?consultation_ID=<?=$consultation_id;?>
   &Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<style media="screen">
  table tr td{
    height: 20px !important;
    font-size: 16px;
    background-color: #fff;
  }
</style>

 <center>
   <fieldset>
     <legend align=center style="font-weight:bold">Form Three</legend>
     <form class=""  method="post" id="fifth_form">

       <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id   ?>">


       <table width="100%" style="background-color: #fff;">
         <th style="width:10% !important; ">Handover issues</th>
         <th style="width:10% !important; ">Time</th>
         <th style="width:28% !important; ">Evants</th>
         <th style="width:25% !important;">
           <span>Time <input style="width:30%;" type="text" name="" value=""> </span>
           <span>HandOver <input style="width:30%;" type="text" name="" value=""> </span>
           <span>Summary</span>
         </th>

         <?php
            $list = array('Sensation','ECG(Rate Rythm)','BP','Urine Output','Temperature','Breathing','Activity','Diet And Elimination',
            'Skin','Infection','Comfort','Bleeding','Patient Complaint','Family Concern','Cocio culture issues','Fluid and Electrolyte','Labs / Ivestigation', 'Leaming Needs');

            $list_name = array(
              'sensation','ecg_rate_rthm','bp',
              'urine_output','temperature','breathing','activity',
              'diet_And_elimination','Skin','infection','comfort','bleeding',
              'patient_complaint','family_Concern','cocio_culture_issues',
              'fluid_and_electrolyte','labs_ivestigation', 'leaming_needs'
            );
          ?>



      <tr>
        <td>LOC/Mood</td>
        <td><input class="input_form_five" type="text" name="local_mood_time" value="" style="padding-left:5px;"> </td>
         <td>
           <input type="text" class="input_form_five" name="local_mood_evants" value="" style="padding-left:5px;"> </td>

         <td rowspan="18">
           <textarea class="input_form_five" name="comment" rows="30" style="padding-left:10px;" cols="18"><?php if(!empty($response['comment'])){
             echo $response["comment"];
           }?></textarea>
         </td>
       </tr>

            <?php for ($i=0; $i < 18; $i++) { ?>
            <tr>
                <td><?=$list[$i]?></td>
                 <td>
                   <input class="input_form_five" type="text" name="<?=$list_name[$i]?>time" value="<?php if(!empty($response[$list_name[$i]."time"])){
                     echo $response[$list_name[$i].'time'];
                   }?>" style="padding-left:5px;">
                 </td>
                <td>
                  <input class="input_form_five" type="text" name="<?=$list_name[$i]?>evants" value="<?php if(!empty($response[$list_name[$i]."evants"])){
                    echo $response[$list_name[$i].'evants'];
                  }?>" style="padding-left:5px;">
                 </td>

              </tr>
              <?php
           }
            ?>
            </tr>
       </table>

        <br>

        <div class="">
         <span>
           <input style="font-size: 15px;padding:10px 20px;font-family:sans-serif;font-weight:500;border-radius:5px" type="submit" id="submit" name="submit" value="SAVE" class="art-button-green" />
         </span>

         <span>
           <input style="font-size: 15px;padding:10px 20px;font-family:sans-serif;font-weight:500;border-radius:5px" type="submit" id="preview" name="submit" value="PREVIEW" class="art-button-green" />
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
    url:"get_data_status_five.php",
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
        url:"submit_form_five.php",
        type:"POST",
        data:{status:status,patient_id:registration_id},
        success:function(data){
          alert(data)
          getDataStatus()
        }
      })
    })




    $("#fifth_form").submit(function(e){
      e.preventDefault();

      var form_seven_data  = $(this).serialize();

      alert(form_seven_data)
    })

    $(".input_form_five").focusout(function(e){
      e.preventDefault();


      var input_name = $(this).attr("name");
      // alert(input_name)
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);

      $.ajax({
        url:"save_form_five.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,registration_id:registration_id},
        success:function(data){
          console.log(data)
        }
      })

    })

</script>
