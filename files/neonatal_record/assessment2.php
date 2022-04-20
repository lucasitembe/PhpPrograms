<?php
include('header.php');
include('../includes/connection.php');
if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];


}
 ?>

<a href="neonatal_record.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Employee_ID=<?= $employee_ID;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<style media="screen">
  table{
    border-collapse: collapse;
    border: none !important;
  }
  table, tr, td{
    border:none !important;
  }

  .title{
    text-align: right !important;
  }

  .btn-title{
    width: 70%;

  }
</style>




   <fieldset>
     <legend align=center style="font-weight:bold">NEONATAL PAGE</legend>
     <center align="center">
       <form class="" id="myform">


     <table style="width:100%">
        <tbdy>
          <thead>
            <th>PARENT TEACHING</th>
            <th>Demo</th>
            <th>Initial</th>
            <th>Comments</th>
            <th>Superv Demo</th>
            <th>Initials</th>
            <th>Comments</th>
          </thead>
          <tbody>
            <?php
            $breast_feeding_demo = "";
            $select2 = "SELECT * FROM tbl_assessment2 WHERE Registration_ID = '$registration_id' ORDER BY saved_time ASC LIMIT 1";
            $select2_execute = mysqli_query($conn,$select2);
            while ($r = mysqli_fetch_assoc($select2_execute)) {
                 $breast_feeding_demo = $r['breast_feeding_demo'];
                 $breast_feeding_initial = $r['breast_feeding_initial'];
                 $breast_feeding_comments = $r['breast_feeding_comments'];
                 $breast_feeding_superv_demo = $r['breast_feeding_superv_demo'];
                 $breast_feeding_initials = $r['breast_feeding_initials'];
                 $breast_feeding_comments_second = $r['breast_feeding_comments_second'];
                 $cup_feeding_demo = $r['cup_feeding_demo'];
                 $cup_feeding_initial = $r['cup_feeding_initial'];
                 $cup_feeding_comment = $r['cup_feeding_comment'];
                 $cup_feeding_superv_demo = $r['cup_feeding_superv_demo'];
                 $cup_feeding_initials = $r['cup_feeding_initials'];
                 $cup_feeding_comments_second = $r['cup_feeding_comments_second'];
                 $prepare_milk_demo = $r['prepare_milk_demo'];
                 $prepare_milk_initial = $r['prepare_milk_initial'];
                 $prepare_milk_comment = $r['prepare_milk_comment'];
                 $prepare_milk_superv_demo = $r['prepare_milk_superv_demo'];
                 $prepare_milk_initials = $r['prepare_milk_initials'];
                 $prepare_milik_comment_second = $r['prepare_milik_comment_second'];
                 $nappy_change_demo = $r['nappy_change_demo'];
                 $nappy_change_initial = $r['nappy_change_initial'];
                 $nappy_change_comment = $r['nappy_change_comment'];
                 $nappy_change_superv_demo = $r['nappy_change_superv_demo'];
                 $nappy_change_initials = $r['nappy_change_initials'];
                 $nappy_change_comment_second = $r['nappy_change_comment_second'];
                 $bath_initial = $r['bath_initial'];
                 $bath_demo = $r['bath_demo'];
                 $bath_comment = $r['bath_comment'];
                 $bath_superv_demo = $r['bath_superv_demo'];
                 $bath_initials = $r['bath_initials'];
                 $bath_comment_second = $r['bath_comment_second'];
                 $cord_care_second_demo = $r['cord_care_second_demo'];
                 $breast2_feeding = $r['breast2_feeding'];
                 $breast2_feeding_initials = $r['breast2_feeding_initials'];
                 $breast2_feeding_comments = $r['breast2_feeding_comments'];
                 $formular_feeding = $r['formular_feeding'];
                 $formular_feeding_initials = $r['formular_feeding_initials'];
                 $formular_feeding_comments = $r['formular_feeding_comments'];

               }
             ?>

             <?php
                      $select2b = "SELECT * FROM tbl_assessment2b WHERE Registration_ID = '$registration_id' ORDER BY saved_time ASC LIMIT 1";
                      $select2b_execute = mysqli_query($conn,$select2b);
                      while ($r2 = mysqli_fetch_assoc($select2b_execute)) {
                      $cord_care_initial = $r2['cord_care_initial'];
                      $cord_care_comment = $r2['cord_care_comment'];
                      $cord_care_superv_demo = $r2['cord_care_superv_demo'];
                      $cord_care_initials = $r2['cord_care_initials'];
                      $cord_care_comment_second = $r2['cord_care_comment_second'];
                      $give_medication_demo = $r2['give_medication_demo'];
                      $give_medication_initial = $r2['give_medication_initial'];
                      $give_medication_comments = $r2['give_medication_comments'];
                      $give_medication_superv_demo = $r2['give_medication_superv_demo'];
                      $give_medication_initials = $r2['give_medication_initials'];
                      $give_medication_comment_second = $r2['give_medication_comment_second'];
                      $behaviour_infant_demo = $r2['behaviour_infant_demo'];
                      $behaviour_infant_initial = $r2['behaviour_infant_initial'];
                      $behaviour_infant_behaviour_infant_comment = $r2['behaviour_infant_behaviour_infant_comment'];
                      $behaviour_infant_superv_demo = $r2['behaviour_infant_superv_demo'];
                      $behaviour_infant_initials = $r2['behaviour_infant_initials'];
                      $behaviour_infant_comment_second = $r2['behaviour_infant_comment_second'];
                      $bcg_one_top = $r2['bcg_one_top'];
                      $bcg_second_top = $r2['bcg_second_top'];
                      $oral_one = $r2['oral_one'];
                      $oral_second = $r2['oral_second'];
                      $injection_one = $r2['injection_one'];
                      $injection_second = $r2['injection_second'];
                      $hepatis_one = $r2['hepatis_one'];
                      $hepatis_second = $r2['hepatis_second'];
                      $date_and_time = $r2['date_and_time'];
                      $signature = $r2['signature'];
                      $bcg_vacination = $r2['bcg_vacination'];
                      $bcg_batch_no = $r2['bcg_batch_no'];
                      $bcg_data_given = $r2['bcg_data_given'];
                      $bcg_signature = $r2['bcg_signature'];
                      $oral_polio = $r2['oral_polio'];
                      $oral_polio_batch_no = $r2['oral_polio_batch_no'];
                      $oral_polio_data_given = $r2['oral_polio_data_given'];
                      $oral_polio_signature = $r2['oral_polio_signature'];
                      $hepatitisb = $r2['hepatitisb'];
                      $hepatitis_batch_no = $r2['hepatitis_batch_no'];
                      $hepatitis_data_given = $r2['hepatitis_data_given'];
                      $hepatitis_signature = $r2['hepatitis_signature'];
                      $yes_notification = $r2['yes_notification'];
                      $no_notification = $r2['no_notification'];
                      $birth_notification = $r2['birth_notification'];
                      $date_of_discharge = $r2['date_of_discharge'];
                      $discharge_weight = $r2['discharge_weight'];
                      $discharge_head_circumference = $r2['discharge_head_circumference'];

                    }

              ?>
            <tr>
              <td class="title">Breast Feeding</td>
              <td>
                <input type="text" name="breast_feeding_demo" value="<?= $breast_feeding_demo?>">
                <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?=$registration_id;?>">
                <input type="hidden" name="Admision_ID" id="Admision_ID" value="<?=$admission_id;?>">
                <input type="hidden" name="Employee_ID" id="Employee_ID" value="<?=$employee_ID;?>">
               </td>

              <td>
                <input type="text" name="breast_feeding_initial" value="<?= $breast_feeding_initial?>"> </td>
              <td>
                <input type="text" name="breast_feeding_comments" value="<?= $breast_feeding_comments?>"> </td>
              <td
              ><input type="text" name="breast_feeding_superv_demo" value="<?= $breast_feeding_superv_demo?>"> </td>
              <td>
                <input type="text" name="breast_feeding_initials" value="<?= $breast_feeding_initials?>"> </td>
              <td>
                <input type="text" name="breast_feeding_comments_second" value="<?= $breast_feeding_comments_second?>">
              </td>

            </tr>
            <tr>
              <td class="title">Cup Feeding</td>
              <td><input type="text" name="cup_feeding_demo" value="<?= $cup_feeding_demo?>"> </td>
              <td><input type="text" name="cup_feeding_initial" value="<?= $cup_feeding_initial?>"> </td>
              <td><input type="text" name="cup_feeding_comment" value="<?= $cup_feeding_comment?>"> </td>
              <td><input type="text" name="cup_feeding_superv_demo" value="<?= $cup_feeding_superv_demo?>"> </td>
              <td><input type="text" name="cup_feeding_initials" value="<?= $cup_feeding_initials?>"> </td>
              <td><input type="text" name="cup_feeding_comments_second" value="<?= $cup_feeding_comments_second?>"> </td>

            </tr>
            <tr>
              <td class="title">Preparation Of Milk</td>
              <td><input type="text" name="prepare_milk_demo" value="<?= $prepare_milk_demo?>"> </td>
              <td><input type="text" name="prepare_milk_initial" value="<?= $prepare_milk_initial?>"> </td>
              <td><input type="text" name="prepare_milk_comment" value="<?= $prepare_milk_comment?>"> </td>
              <td><input type="text" name="prepare_milk_superv_demo" value="<?= $prepare_milk_superv_demo?>"> </td>
              <td><input type="text" name="prepare_milk_initials" value="<?= $prepare_milk_initials?>"> </td>
              <td><input type="text" name="prepare_milik_comment_second" value="<?= $prepare_milik_comment_second?>"> </td>
            </tr>
            <tr>
              <td class="title">Nappy Change</td>
              <td><input type="text" name="nappy_change_demo" value="<?= $nappy_change_demo?>"> </td>
              <td><input type="text" name="nappy_change_initial" value="<?= $nappy_change_initial?>"> </td>
              <td><input type="text" name="nappy_change_comment" value="<?= $nappy_change_comment?>"> </td>
              <td><input type="text" name="nappy_change_superv_demo" value="<?= $nappy_change_superv_demo?>"> </td>
              <td><input type="text" name="nappy_change_initials" value="<?= $nappy_change_initials?>"> </td>
              <td><input type="text" name="nappy_change_comment_second" value="<?= $nappy_change_comment_second?>"> </td>
            </tr>
            <tr>
              <td class="title">Bath</td>
              <td><input type="text" name="bath_demo" value="<?= $bath_demo?>"> </td>
              <td><input type="text" name="bath_initial" value="<?= $bath_initial?>"> </td>
              <td><input type="text" name="bath_comment" value="<?= $bath_comment?>"> </td>
              <td><input type="text" name="bath_superv_demo" value="<?= $bath_superv_demo?>"> </td>
              <td><input type="text" name="bath_initials" value="<?= $bath_initials?>"> </td>
              <td><input type="text" name="bath_comment_second" value="<?= $bath_comment_second?>"> </td>
            </tr>
            <tr>
              <td class="title">Cord Care</td>
              <td><input type="text" name="cord_care_second_demo" value="<?= $cord_care_second_demo?>"> </td>
              <td><input type="text" name="cord_care_initial" value="<?= $cord_care_initial?>"> </td>
              <td><input type="text" name="cord_care_comment" value="<?= $cord_care_comment?>"> </td>
              <td><input type="text" name="cord_care_superv_demo" value="<?= $cord_care_superv_demo?>"> </td>
              <td><input type="text" name="cord_care_initials" value="<?= $cord_care_initials?>"> </td>
              <td><input type="text" name="cord_care_comment_second" value="<?= $cord_care_comment_second?>"> </td>
            </tr>
            <tr>
              <td class="title">Giving Medication</td>
              <td><input type="text" name="give_medication_demo" value="<?= $give_medication_demo?>"> </td>
              <td><input type="text" name="give_medication_initial" value="<?= $give_medication_initial?>"> </td>
              <td><input type="text" name="give_medication_comments" value="<?= $give_medication_comments?>"> </td>
              <td><input type="text" name="give_medication_superv_demo" value="<?= $give_medication_superv_demo?>"> </td>
              <td><input type="text" name="give_medication_initials" value="<?= $give_medication_initials?>"> </td>
              <td><input type="text" name="give_medication_comment_second" value="<?= $give_medication_comment_second?>"> </td>
            </tr>
            <tr>
              <td class="title">Discussion</td>
              <td style="text-align:center">Date</td>
              <td style="text-align:center">Initials</td>

              <th colspan="5" style="text-align:center">Comments</th>
            </tr>

            <tr>
              <td class="title">Behaviour of Infant</td>
              <td><input type="text" name="behaviour_infant_demo" value="<?= $behaviour_infant_demo?>"> </td>
              <td><input type="text" name="behaviour_infant_initial" value="<?= $behaviour_infant_initial?>"> </td>
              <td colspan="4"><input type="text" name="behaviour_infant_behaviour_infant_comment" value="<?= $behaviour_infant_behaviour_infant_comment?>"> </td>



            </tr>

            <tr>
              <td class="title">Breast Feeding</td>
              <td><input type="text" name="breast2_feeding" value="<?= $breast2_feeding ?>"> </td>
              <td><input type="text" name="breast2_feeding_initials" value="<?= $breast2_feeding_initials ?>"> </td>
              <td colspan="4"><input type="text" name="breast2_feeding_comments" value="<?= $breast2_feeding_comments ?>"> </td>
              <!-- <td><input type="text" name="" value=""> </td>
              <td><input type="text" name="" value=""> </td>
              <td><input type="text" name="" value=""> </td> -->
            </tr>

            <tr>
              <td class="title">Formula Feeding</td>
              <td><input type="text" name="formular_feeding" value="<?= $formular_feeding ?>"> </td>
              <td><input type="text" name="formular_feeding_initials" value="<?= $formular_feeding_initials ?>"> </td>
              <td colspan="4"><input type="text" name="formular_feeding_comments" value="<?= $formular_feeding_comments ?>"> </td>
              <!-- <td><input type="text" name="" value=""> </td>
              <td><input type="text" name="" value=""> </td>
              <td><input type="text" name="" value=""> </td> -->
            </tr>
            <tr>


              <td ><td colspan="7"><b style="font-size:16px;">I here by give concert to have my baby immunized against the following:</b></td>
            </tr>
            <tr>
              <td><td><b style="font-size:16px;">Tick Whatever Required</b></td>
            </tr>
            <tr>
              <td class="title">B.C.G</td>
              <td>
                <?php
                    if(!empty($bcg_one_top)){
                 ?>
                <input type="checkbox" name="bcg_one_top" value="bcg" checked>B.C.G1
              <?php }else{ ?>
                <input type="checkbox" name="bcg_one_top" value="bcg">B.C.G1
              <?php } ?>
              </td>
              <td>
                <?php
                    if(!empty($bcg_second_top)){
                 ?>
                      <input type="checkbox" name="bcg_second_top" value="bcg" checked>B.C.G2
                  <?php }else{ ?>
                      <input type="checkbox" name="bcg_second_top" value="bcg">B.C.G2
                  <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="title">Polio Vaccine</td>
            </tr>
            <tr>
              <td class="title">oral one</td>
              <td>
                <?php
                    if(!empty($oral_one)){
                 ?>
                   <input type="checkbox" name="oral_one" value="oral one" checked>
                  <?php }else{ ?>
                    <input type="checkbox" name="oral_one" value="oral one">
                  <?php } ?>
              </td>
              <td>
                <?php
                    if(!empty($oral_second)){
                 ?>
                    <input type="checkbox" name="oral_second" value="oral second" checked>Oral second
                <?php }else{ ?>
                    <input type="checkbox" name="oral_second" value="oral second">Oral second
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="title">Injection one</td>
              <td>
                <?php
                    if(!empty($injection_one)){
                 ?>
                    <input type="checkbox" name="injection_one" value="injection one" checked>
                <?php }else{ ?>
                    <input type="checkbox" name="injection_one" value="injection one">
                <?php } ?>
              </td>
              <td>
                <?php
                    if(!empty($injection_second)){
                 ?>
                    <input type="checkbox" name="injection_second" value="injection second" checked>Injection second
                <?php }else{ ?>
                    <input type="checkbox" name="injection_second" value="injection second">Injection second
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="title">Hepatitis one</td>
              <td>
                <?php
                    if(!empty($hepatis_one)){
                 ?>
                    <input type="checkbox" name="hepatis_one" value="hepatitis_one" checked>
                  <?php }else{ ?>
                    <input type="checkbox" name="hepatis_one" value="hepatitis_one">
                  <?php } ?>
              </td>
              <td>
                <?php
                    if(!empty($hepatis_second)){
                 ?>
                    <input type="checkbox" name="hepatis_second" value="hepatisis_second" checked>Hepatitis second
                <?php }else{ ?>
                    <input type="checkbox" name="hepatis_second" value="hepatisis_second">Hepatitis second
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="title">Date</td>
              <td><input type="text" name="date_and_time" value="" class="date" value="<?= $date_and_time?>"> </td>
              <td class="title">Signature</td>
              <td><input type="text" name="signature" value="" value="<?= $signature?>"> </td>
            </tr>
            <tr>

            </tr>


            <tr>
              <td colspan="2">Vaccinations</td>
              <td class="title">Batch No.</td>
              <td class="title">Date Given</td>
              <td class="title">Signature</td>
            </tr>
            <tr>
              <td class="title">B.C.G Vacination</td>
              <td>
                <?php
                    if(!empty($bcg_vacination)){
                 ?>
                   <input type="checkbox" name="bcg_vacination" checked>
                <?php }else{ ?>
                   <input type="checkbox" name="bcg_vacination">
                <?php } ?>
              </td>
              <td><input type="text" name="bcg_batch_no" value="<?= $bcg_batch_no?>"> </td>
              <td><input type="text" name="bcg_data_given" value="<?= $bcg_data_given?>"> </td>
              <td><input type="text" name="bcg_signature" value="<?= $bcg_signature?>"> </td>
            </tr>
            <tr>
              <td class="title">Oral Polio</td>
              <td>
                <?php
                    if(!empty($oral_polio)){
                 ?>
                   <input type="checkbox" name="oral_polio" value="oral polio" checked>
                <?php }else{ ?>
                   <input type="checkbox" name="oral_polio" value="oral polio">
                <?php } ?>
              </td>
              <td><input type="text" name="oral_polio_batch_no" value="<?= $oral_polio_batch_no?>"> </td>
              <td><input type="text" name="oral_polio_data_given" value="<?= $oral_polio_data_given?>"> </td>
              <td><input type="text" name="oral_polio_signature" value="<?= $oral_polio_signature?>"> </td>
            </tr>
            <tr>
              <td class="title">Hepatitis B</td>
              <td>
                <?php
                    if(!empty($hepatitisb)){
                 ?>
                  <input type="checkbox" name="hepatitisb" value="hepatitisb" checked>
                <?php }else{ ?>
                  <input type="checkbox" name="hepatitisb" value="hepatitisb">
                <?php } ?>
              </td>
              <td><input type="text" name="hepatitis_batch_no" value="<?= $hepatitis_batch_no?>"> </td>
              <td><input type="text" name="hepatitis_data_given" value="<?= $hepatitis_data_given?>"> </td>
              <td><input type="text" name="hepatitis_signature" value="<?= $hepatitis_signature?>"> </td>
            </tr>


            <tr>
              <td class="title">Notifiaction of Birth</td>
              <td class="title">YES</td>
              <td>
                <?php
                    if(!empty($yes_notification)){
                 ?>
                  <input type="checkbox" name="yes_notification" value="yes notification" checked>
                <?php }else{ ?>
                  <input type="checkbox" name="yes_notification" value="yes notification">
                <?php } ?>
              </td>
              <td class="title">No</td>
              <td>
                <?php
                    if(!empty($no_notification)){
                 ?>
                   <input type="checkbox" name="no_notification" value="no_notification" checked>
                <?php }else{ ?>
                   <input type="checkbox" name="no_notification" value="no_notification">
                <?php } ?>
              </td>
              <td class="title">Birth Notification(B)No.</td>
              <td><input type="text" name="birth_notification" value="<?= $birth_notification?>" placeholder="Birth Notification(B)No"> </td>
            </tr>

            <tr>
              <td class="title">Date of Discharge</td>
              <td><input type="text" name="date_of_discharge" value="<?= $date_of_discharge?>" placeholder="Date of Discharge"> </td>
            </tr>
            <tr>
              <td class="title">Discharge Weight</td>
              <td><input type="text" name="discharge_weight" placeholder="Discharge Head Circumference" value="<?= $discharge_weight?>"> </td>
            </tr>
            <tr>
              <td class="title">Discharge Head Circumference</td>
              <td><input type="text" name="discharge_head_circumference" value="<?= $discharge_head_circumference?>" placeholder="Discharge Head Circumference"> </td>
            </tr>
          </tbody>
        </tbody>
      </table>

      <div class="">
        <span>
          <input type="submit" class="art-button-green" name="submit" value="Save" id="savebtn">
          </span>
          <a href="save_assessment2.php?Registration_ID=<?= $registration_id?>&action=print_data" target="_blank" class="art-button-green" style="float:right" id="preview">Priview</a>
      </div>
      </form>
</center>
 </fieldset>
<?php //} }?>



   <script src="../css/jquery.datetimepicker.js"></script>

   <script type="text/javascript">

   $("#savebtn").click(function(e){
     e.preventDefault()
     var form_data=$("#myform").serialize();

     //alert(form_data)
    if (confirm('Are you sure want to save this?')) {
      $.ajax({
        url:"save_assessment2.php",
        type:"POST",
        data:form_data,
        success:function(res){
          alert(res)
        }
      })
    }

   })


   // $("#preview").click(function(e){
   //   e.preventDefault();
   //   //var assess = $("#myform").serialize();
   //    var patientId = $("#Registration_ID").val();
   //
   //
   //   $.ajax({
   //     url:"save_assessment2.php",
   //     type:"GET",
   //     data:(
   //       Registration_ID:patientId,
   //       action:"print_data"
   //     )
   //     success:function(data){
   //        alert("Reached")
   //       patientId = $("#Registration_ID").val();
   //       window.open("save_assessment2.php?Registration_ID="+patientId,'_blank');
   //       var w = window.open("save_assessment2.php");
   //       $(w.document.body).html(data);
   //
   //     }
   //   })
   //
   // })


   $(document).ready(function(e){

     $('.date').datetimepicker({value: '', step: 2});

   })
   </script>
