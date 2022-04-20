<?php
include('header.php');
include('../includes/connection.php');
include('get_form_five_data.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}


$response = returnFormSevenData($registration_id);
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



$select_form_seven_data = "SELECT * FROM tbl_icu_form_seven WHERE patient_id = '$registration_id'";

$result_form_seven = mysqli_query($conn,$select_form_seven_data);

// while ($row = mysqli_fetch_assoc($result_form_seven)) {

  // $batham=$row['batham'];
  // $bathpm=$row['bathpm'];
  // $bathnight=$row['bathnight'];
  // $back_caream=$row['back_caream'];
  // $back_carepm=$row['back_carepm'];
  // $back_carenight=$row['back_carenight'];
  // $mouth_caream=$row['mouth_caream'];
  // $mouth_carepm=$row['mouth_carepm'];
  // $mouth_carenight=$row['mouth_carenight'];
  // $eye_caream=$row['eye_caream'];
  // $eye_carepm=$row['eye_carepm'];
  // $eye_carenight=$row['eye_carenight'];
  // $cathete_caream=$row['cathete_caream'];
  // $cathete_carepm=$row['cathete_carepm'];
  // $cathete_carenight=$row['cathete_carenight'];
  // $perinial_caream=$row['perinial_caream'];
  // $perinial_carepm=$row['perinial_carepm'];
  // $perinial_carenight=$row['perinial_carenight'];
  // $ng_caream=$row['ng_caream'];
  // $ng_carepm=$row['ng_carepm'];
  // $ng_carenight=$row['ng_carenight'];
  // $nose_ear_eaream=$row['nose_ear_eaream'];
  // $nose_ear_earepm=$row['nose_ear_earepm'];
  // $nose_ear_earenight=$row['nose_ear_earenight'];
  // $physioam=$row['physioam'];
  // $physiopm=$row['physiopm'];
  // $physionight=$row['physionight'];
  // $deepbreath_cougham=$row['deepbreath_cougham'];
  // $deepbreath_coughpm=$row['deepbreath_coughpm'];
  // $deepbreath_coughnight=$row['deepbreath_coughnight'];
  // $oett_tt_caream=$row['oett_tt_caream'];
  // $oett_tt_carepm=$row['oett_tt_carepm'];
  // $oett_tt_carenight=$row['oett_tt_carenight'];
  // $line_caream=$row['line_caream'];
  // $line_carepm=$row['line_carepm'];
  // $line_carenight=$row['line_carenight'];
  // $locationam=$row['locationam'];
  // $locationpm=$row['locationpm'];
  // $locationnight=$row['locationnight'];
  // $insertion_dateam=$row['insertion_dateam'];
  // $insertion_datepm=$row['insertion_datepm'];
  // $insertion_datenight=$row['insertion_datenight'];
  // $status_of_siteam=$row['status_of_siteam'];
  // $status_of_sitepm=$row['status_of_sitepm'];
  // $status_of_sitenight=$row['status_of_sitenight'];
  // $redressedam=$row['redressedam'];
  // $redressedpm=$row['redressedpm'];
  // $redressednight=$row['redressednight'];
  // $location_2am=$row['location_2am'];
  // $location_2pm=$row['location_2pm'];
  // $location_2night=$row['location_2night'];
  // $insertion_date_2am=$row['insertion_date_2am'];
  // $insertion_date_2pm=$row['insertion_date_2pm'];
  // $insertion_date_2night=$row['insertion_date_2night'];
  // $status_of_site_2am=$row['status_of_site_2am'];
  // $status_of_site_2pm=$row['status_of_site_2pm'];
  // $status_of_site_2night=$row['status_of_site_2night'];
  // $redressed_2am=$row['redressed_2am'];
  // $redressed_2pm=$row['redressed_2pm'];
  // $redressed_2night=$row['redressed_2night'];
  // $location_3am=$row['location_3am'];
  // $location_3pm=$row['location_3pm'];
  // $location_3night=$row['location_3night'];
  // $insertion_date_3am=$row['insertion_date_3am'];
  // $insertion_date_3pm=$row['insertion_date_3pm'];
  // $insertion_date_3night=$row['insertion_date_3night'];
  // $status_of_site_3am=$row['status_of_site_3am'];
  // $status_of_site_3pm=$row['status_of_site_3pm'];
  // $status_of_site_3night=$row['status_of_site_3night'];
  // $redressed_3am=$row['redressed_3am'];
  // $redressed_3pm=$row['redressed_3pm'];
  // $redressed_3night=$row['redressed_3night'];
// }

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
     <form class=""  method="post" id="seven_form">

       <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id   ?>">
       <table width="100%">
         <th style="width:10% !important; ">Assessment</th>
         <th style="width:10% !important; ">Am</th>
         <th style="width:28% !important; ">PM</th>
         <th style="width:25% !important;">
           Night
         </th>

         <?php
         $list = array('Bath','Back Care','Mouth Care','Eye Care','Cathete Care ','Perinial Care','N/G Care','Nose /Ear Care','Physio','Deep Breath Cough',
         'OETT/TT Care','Line Care','<b>! Location</b>','Insertio Date','Status of Site','Redressed','<b>2. Location</b>','Insertion Date','Status of Site','Redressed','<b>3. Location</b>',
         'Insertion Date','Status of Site',
         'Redressed');


         $list_name = array('bath','back_care','mouth_care','eye_care',
         'cathete_care','perinial_care','ng_care',
         'nose_ear_eare','physio','deepbreath_cough',
         'oett_tt_care','line_care','location','insertion_date',
         'status_of_site','redressed','location_2','insertion_date_2',
         'status_of_site_2','redressed_2','location_3',
         'insertion_date_3','status_of_site_3',
         'redressed_3');
          ?>

           <?php



           for ($i=0; $i < count($list); $i++) {
             // echo $response[$list_name[$i].'am'];
              ?>
              <tr>
                <td><?=$list[$i]?></td>
                <td>
                  <input  class="input_form_seven" type="text" name="<?=$list_name[$i]?>am" value="<?php if(!empty($response[$list_name[$i].'am'])){
                    echo $response[$list_name[$i].'am'];
                  }?>"> </td>
                <td>
                  <input  class="input_form_seven" type="text" name="<?=$list_name[$i]?>pm" value="<?php if(!empty($response[$list_name[$i].'pm'])){
                    echo $response[$list_name[$i].'pm'];
                  }?>"> </td>
                <td>
                  <input  class="input_form_seven" type="text" name="<?=$list_name[$i]?>night" value="<?php if(!empty($response[$list_name[$i].'night'])){
                    echo $response[$list_name[$i].'night'];
                  }?>">
                </td>
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
           <input type="preview" id="preview" name="submit" value="PREVIEW" class="art-button-green" />
         </span>

       </div>
     </form>

     <div class="file-list" style="margin-top:10px">

     </div>
   </fieldset>

</center>

<script type="text/javascript">


$(document).ready(function(e){

  var registration_id = $("#registration_id").val();
  $.ajax({
    url:"get_data_status.php",
    type:"post",
    data:{patient_id:registration_id},
    success:function(data){

      var jsonData = JSON.parse(data);
      console.log(jsonData.date_time)

      $(".file-list").append("<a href='form_seven.php?Registration_ID=1142' class='art-button-green'>"+jsonData.date_time+"</a>")
    }
  })
})

    $("#seven_form").submit(function(e){
      e.preventDefault();
      var status = "saved";
      registration_id = $("#registration_id").val();
      alert(registration_id)
      $.ajax({
        url:"submit_form_seven.php",
        type:"POST",
        data:{status:status,patient_id:registration_id},
        success:function(data){
          alert(data)
        }
      })
    })




    $(".input_form_seven").focusout(function(e){
      e.preventDefault();


      var input_name = $(this).attr("name");
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);

      $.ajax({
        url:"save_form_seven.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,registration_id:registration_id},
        success:function(data){
          console.log(data)
        }
      })

    })

</script>
