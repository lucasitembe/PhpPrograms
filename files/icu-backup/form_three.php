<?php
include('header.php');
include('../includes/connection.php');
include('get_form_three_dada_hours.php');



if(isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
}

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

// select patient info if not exist
$select_patient_inf = "SELECT * FROM tbl_form_three_intro WHERE patient_id = '$registration_id'";

if ($result_selected = mysqli_query($conn,$select_patient_inf)) {
  while ($row_result = mysqli_fetch_assoc($result_selected)) {
    $primary_attending=$row_result['primary_attending'];
    $icu_attending=$row_result['icu_attending'];
    $other_attending=$row_result['other_attending'];
    $reg_no=$row_result['reg_no'];
    $surname=$row_result['surname'];
    $name=$row_result['name'];
    $age=$row_result['age'];
    $allegies=$row_result['allegies'];
    $date_of_attendance=$row_result['date_of_attendance'];
    $day_in_unit=$row_result['day_in_unit'];
    $weight=$row_result['weight'];
    $height=$row_result['height'];
    $date_and_time_of_intubation=$row_result['date_and_time_of_intubation'];
    $doctor_name=$row_result['doctor_name'];
    $nurse=$row_result['nurse'];
    $size=$row_result['size'];
    $Fixation=$row_result['Fixation'];
    $Fixation=$row_result['Fixation'];
    $pt_spokes_person=$row_result['pt_spokes_person'];
    $relationship_to_pt=$row_result['relationship_to_pt'];
    $cell=$row_result['cell'];
  }
// echo $relationship_to_pt;
}else {
  echo mysqli_error($conn);
}

// echo "$registration_id";
// echo json_encode($response = getMedication($registration_id));

 ?>

 <a href="icu.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<style media="screen">
  table tr,td{
    border:none !important;

  }
  table tr td span{
    text-align: right !important;

  }
  table tr td input{
    width: 3%;
  }

  .label-td{
    width: 1%;
    text-align: right !important;
  }
  .input{
    width: 6%;

  }
  .alignment{
      margin: 0px;
      padding: 0px;
  }

  #table-form{
    background: #fff;
  }
  #table-form tr td{
    table-layout: fixed;
    border: 1px solid grey !important ;
    height: 30px;
    box-sizing: border-box;
  }

  #table-inside{
    margin: 0px !important;
    border: none !important;
  }
  #table-inside tr td{
    height: 33px !important;
  }

  #table-form tr:hover{

    background: grey;
    color:#fff;
  }
</style>

<center>
  <fieldset>
    <legend align=center style="font-weight:bold">Form Three</legend>
    <form  method="post" id="third_form">


      <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id;?>">


      <div class="">
        <span>Primary Attending</span>
        <span><input class="input_form_third_intro" type="text" name="primary_attending" value="<?php if(!empty($primary_attending))
        echo $primary_attending; ?>" placeholder="Primary attending" style="width:20%;">

        </span>

        <span>
          ICU Ateending
        </span>
        <span>
          <input class="input_form_third_intro" type="text" name="icu_attending" value="<?php if(!empty($icu_attending))
          echo $icu_attending; ?>" placeholder="ICU Attending" style="width:20%;">
        </span>
        <span>
          Other attending
        </span>
        <span>
          <input class="input_form_third_intro" type="text" name="other_attending" value="<?php if(!empty($other_attending))
          echo $other_attending; ?>" placeholder="Other  Attending" style="width:20%;">
        </span>
      </div>

      <table width=100%"">
        <tr>
          <td class="label-td">Reg No</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="reg_no" value="<?=$registration_id; ?>" placeholder="Registratio number"> </td>

          <td class="label-td">Surname</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="surname" value="<?php if(!empty($surname))
          echo $surname; ?>" placeholder="Surname"> </td>

          <td class="label-td">Name</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="name" value="<?php if(!empty($name))
          echo $name; ?>" placeholder="Name">
           </td>


          <td class="label-td">Age</td>
             <td class="input">
               <input class="input_form_third_intro" type="text" name="age" value="<?php if(!empty($age))
               echo $age; ?>" placeholder="Age">
             </td>

             <td class="label-td">sex</td>
             <td class="input">
               <label><span><input class="input_form_third_intro" type="radio" name="sex" value="ME">Male </span></label>
               <label><span><input class="input_form_third_intro" type="radio" name="sex" value="FE">Female </span>
               </label>
              </td>
           </tr>
        </tr>

        <tr>
          <td class="label-td">Allergies</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="allegies" value="<?php if(!empty($allegies))
          echo $allegies; ?>" placeholder="Allergies"> </td>

          <td class="label-td">DOA</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="date_of_attendance" value="<?php if(!empty($date_of_attendance))
          echo $date_of_attendance; ?>" placeholder="Date of attendance"> </td>

          <td class="label-td">Days in unit</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="day_in_unit" value="<?php if(!empty($day_in_unit))
          echo $day_in_unit; ?>" placeholder="Days in unit">
           </td>


             <td class="label-td">WT</td>
             <td class="input">
               <input class="input_form_third_intro" type="text" name="weight" value="<?php if(!empty($weight))
               echo $weight; ?>" placeholder="Weight">
             </td>

             <td class="label-td">HT</td>
             <td class="input">
               <span><input class="input_form_third_intro" type="text" name="height" value="<?php if(!empty($height))
               echo $height; ?>" placeholder="Height">               </label>
              </td>
           </tr>
        </tr>

        <tr>
          <td class="label-td">Date and Time of intubation</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="date_and_time_of_intubation" value="<?php if(!empty($date_and_time_of_intubation))
          echo $date_and_time_of_intubation; ?>" placeholder="Date and time ot intubation"> </td>

          <td class="label-td" style="width:4%">
            <span>
            <p class="alignment">inubated by</p>
            <p class="alignment">Extubated by</p>
            </span>

          </td>

          <td class="input"><input class="input_form_third_intro" type="text" name="doctor_name" value="<?php if(!empty($doctor_name))
          echo $doctor_name; ?>" placeholder="Doctor name"> </td>

          <td class="label-td">Nurse</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="nurse" value="<?php if(!empty($nurse))
          echo $nurse; ?>" placeholder="Nurse">
           </td>


             <td class="label-td">Size</td>
             <td class="input" >
               <input class="input_form_third_intro" type="text" name="size" value="<?php if(!empty($size))
               echo $size; ?>" style="width:50%"  placeholder="Size">
             </td>

             <td class="label-td">Fixation</td>
             <td class="input">
               <span><input class="input_form_third_intro" type="text" name="fixation" value="<?php if(!empty($fixation))
               echo $fixation; ?>" placeholder="fixation">               </label>
              </td>
              <td class="label-td">cuff p:</td>
              <td class="input">
                <span>
                  <input class="input_form_third_intro" type="text" name="fixation" style="width:80%" value="<?php if(!empty($fixation))
                  echo $fixation; ?>" placeholder="cuff p">
                </label>
               </td>
           </tr>
        </tr>
      </table>
        <table>
        <tr>
          <td class="label-td">pt Spkes person</td>
          <td  class="input">
            <input class="input_form_third_intro" type="text" name="pt_spokes_person" value="<?php if(!empty($pt_spokes_person))
            echo $pt_spokes_person; ?>" placeholder="Pt spokes person">
           </td>

          <td class="label-td">Relationship to pt</td>
          <td class="input"><input class="input_form_third_intro" type="text" name="relationship_to_pt" value="<?php if(!empty($relationship_to_pt))
          echo $relationship_to_pt; ?>" placeholder="Relationship to pt">
          </td>

          <td class="label-td">Cell</td>
          <td class="input">
            <input class="input_form_third_intro" type="text" name="cell" value="<?php if(!empty($cell))
            echo $cell; ?>" placeholder="Cell">
          </td>

        </tr>
      </table>

      <table width="100%" id="table-form">
        <tr>
          <th width="20%;">Medication</th>
          <th>0700</th>
          <th>0800</th>
          <th>0900</th>
          <th>1000</th>
          <th>1100</th>
          <th>1200</th>
          <th>1300</th>
          <th>1400</th>
          <th>1500</th>
          <th>1600</th>
          <th>1700</th>
          <th>1800</th>
          <th>1900</th>
          <th>2000</th>
          <th>2100</th>
          <th>2200</th>
          <th>2300</th>
          <th>0000</th>
          <th>0100</th>
          <th>0200</th>
          <th>0300</th>
          <th>0400</th>
          <th>0500</th>
          <th>0600</th>
        </tr>
        <?php

        $name_list = array('hr_one','hr_two','hr_three','hr_four','hr_five','hr_six',
        'hr_seven',
        'hr_eight','hr_nine','hr_ten','hr_eleven','hr_twelve',
        'hr_thirteen','hr_fourteen','hr_fifteen','hr_sixteen',
        'hr_seventeen','hr_eighteen','hr_nineteen','hr_twenty',
        'hr_twenty_one','hr_twenty_two','hr_twenty_three',
        'hr_twenty_four');

        // $data =getMedication($registration_id);
        for ($i=0; $i < 22; $i++) {
          // echo $data['medic_id'];
        ?>
        <tr class="content-medication">
          <td width="20%;">
            <input style="font-weight:bold" class="medic _<?=$i;?>" id="<?=$i; ?>" type="text" name="medical<?=$i?>" value="">
          </td>
          <?php

          $response = returnMedicationHours($registration_id,$i);
          // echo $response["$name_list[$i]"]
           ?>

           <?php
           for ($j=0; $j < 24; $j++) {
             ?>

             <td><input type="text" name="<?=$name_list[$j]?>" id="<?=$i;?>" class="time-input "
               value="<?php if(!empty($response[$name_list[$j]]))
             {
               echo $response[$name_list[$j]];
             } ?>"> </td>


           <?php } ?>




        </tr>
        <?php
        }
         ?>

           <th width="20%;">IV infusion</th>
           <?php
           for ($i=0; $i<7; $i++){

             $response = returnIvFusionHours($registration_id,$i);

             ?>
             <tr>

               <td class="content-infusion ">
                 <input style="text-align:bold; font-weight:bold" type="text" name="infusion<?=$i?>" id="<?=$i?>" class="iv-infusion _<?=$i?>" value="">
              </td>


            <?php
            $hour_name_list = array('hr_one','hr_two','hr_three','hr_four','hr_five','hr_six',
          'hr_seven','hr_eight','hr_nine','hr_ten','hr_eleven','hr_twelve','hrhr_thirteen','hr_fourteen','hr_fifteen','hr_sixteen','hr_seventeen','hr_eightteen','hr_nineteen','hr_twenty','hr_twenty_one','hr_twenty_two','hr_twenty_three','hr_twenty_four');
            for ($j=0; $j < 24; $j++) {
              ?>

              <td>
                <input type="text" name="<?=$hour_name_list[$j]?>"  class="iv-infusionhrs" id="<?=$i?>" value="<?php if(!empty($response[$hour_name_list[$j]])){
                  echo  $response[$hour_name_list[$j]];

                }?>">
              </td>

            <?php
          }
            ?>

            </tr>
           <?php
           }
            ?>



          <th width="20%;">Blood Product</th>


        </tr>
        <tr>
        <td width="20%;" rowspan='12'>
          <table width="100%" id="table-inside">

            <tr>
              <td rowspan="4"></td>
              <td>Total IV</td>
            </tr>
            <tr>

              <td>Ng/Oral</td>
            </tr>
            <tr>

              <td>Others</td>
            </tr>
            <tr>

              <td>Total</td>
            </tr>

            <tr>
              <td rowspan="8"></td>
              <td>Urine</td>
            </tr>
            <tr>

              <td>Ng/Oral</td>
            </tr>
            <tr>

              <td>Vomitus</td>
            </tr>
            <tr>

              <td>Stool</td>
            </tr>
            <tr>

              <td>Chest tube</td>
            </tr>
            <tr>

              <td>Insensible loss</td>
            </tr>
            <tr>

              <td>Total</td>
            </tr>
            <tr>

              <td>Hourly balance</td>
            </tr>

          </table>

        </td>

        <?php
        $response = returnBloodProductHours($registration_id,1);


        for ($i=0; $i < 24; $i++) {
          // echo $response[$name_list[$i]];
          ?>
          <td><input style="text-align:center; font-weight:bold;" type="text" name="<?=$name_list[$i]?>" class="blood-product" id="1" value="<?php if(!empty($response[$name_list[$i]]))
          {
            echo $response[$name_list[$i]];
          } ?>"> </td>
        <?php
        }
         ?>

      </tr>
      <tr>
      <!-- <td></td> -->

      <?php
      $response = returnBloodProductHours($registration_id,2);
      for ($i=0; $i < 24; $i++) {
        ?>
        <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="2" value="<?php if(!empty($response[$name_list[$i]]))
        {
          echo $response[$hour_name_list[$i]];
        } ?>"> </td>
      <?php
      }
       ?>
    </tr>
    <tr>
    <!-- <td></td> -->
    <?php
    $response = returnBloodProductHours($registration_id,3);

    for ($i=0; $i < 24; $i++) {
      ?>
      <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="3" value="<?php if(!empty($response[$name_list[$i]]))
      {
        echo $response[$name_list[$i]];
      } ?>"> </td>
    <?php
    }
     ?>
  </tr>
  <tr>
  <!-- <td></td> -->
  <?php

  $response = returnBloodProductHours($registration_id,4);
  for ($i=0; $i < 24; $i++) {
    ?>
    <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="4" value="<?php if(!empty($response[$name_list[$i]]))
    {
      echo $response[$name_list[$i]];
    } ?>"> </td>
  <?php
  }
   ?>

</tr>
<tr>
<!-- <td></td> -->
<?php
$response = returnBloodProductHours($registration_id,5);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="5" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>

</tr>
<tr>
<!-- <td></td> -->
<?php
$response = returnBloodProductHours($registration_id,6);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="6" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>
<td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="7" value="<?php if(!empty($response[$name_list[$i]]))
{
  echo $response[$name_list[$i]];
} ?>"></td>
</tr>
<tr>
<!-- <td></td> -->
<?php
$response = returnBloodProductHours($registration_id,7);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="8" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>

</tr>
<tr>
<!-- <td></td> -->
<?php
$response = returnBloodProductHours($registration_id,8);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="9" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>
</tr>
<tr>
<!-- <td></td> -->

<?php
$response = returnBloodProductHours($registration_id,9);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="10" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>
</tr>
<tr>
<!-- <td></td> -->

<?php
$response = returnBloodProductHours($registration_id,10);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="11" value="<?php if(!empty($response[$name_list[$i]]))
  {
    echo $response[$name_list[$i]];
  } ?>"> </td>
<?php
}
 ?>

<td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="12" value="<?php if(!empty($response[$name_list[$i]]))
{
  echo $response[$name_list[$i]];
} ?>"></td>
</tr>
<tr>

  <?php
  $response = returnBloodProductHours($registration_id,11);
  for ($i=0; $i < 24; $i++) {
    ?>
    <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$hour_name_list[$i]?>" class="blood-product" id="13" value=""> </td>
  <?php
  }
   ?>
</tr>
<tr>
<!-- <td></td> -->
<?php
$response = returnBloodProductHours($registration_id,12);
for ($i=0; $i < 24; $i++) {
  ?>
  <td><input style="text-align:center; font-weight:bold" type="text" name="" value=""> </td>
<?php
}
 ?>
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

<script type="text/javascript">

function getDataStatus(){
  var registration_id = $("#registration_id").val();
  $.ajax({
    url:"get_data_status_three.php",
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
        url:"submit_form_three.php",
        type:"POST",
        data:{status:status,patient_id:registration_id},
        success:function(data){
          alert(data)
          getDataStatus()
        }
      })
    })




    $("#third_form").submit(function(e){
      e.preventDefault();

      var form_seven_data  = $(this).serialize();

      alert(form_seven_data)
    })

    $(".medic").keyup(function(e){
      e.preventDefault();
      var input_name = $(this).attr("name");
      // alert(input_name)
      var id = $(this).attr('id');
      // alert(id);
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);
      $.ajax({
        url:"save_form_three_medical.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,
          registration_id:registration_id,medic_id:id},
        success:function(data){
          console.log(data)


        }
      })
    })

    $(".time-input").keyup(function(e){
      e.preventDefault()
      var input_name = $(this).attr("name");
      // alert(input_name)
      var id = $(this).attr('id');
      // alert(id);
      var name_data = $(this).val();
      var registration_id = $("#registration_id").val()
      // alert(name_data);
      $.ajax({
        url:"save_form_three_medical_hour.php",
        type:"POST",
        data:{field_data:name_data,field_name:input_name,
          registration_id:registration_id,medic_id:id},
        success:function(data){
          console.log(data)
        }
      })

    })


    $(document).ready(function(e){
      // e.preventDefault()
      var registration_id = $("#registration_id").val();
      // alert(registration_id)
      $.ajax({
        url:"get_user_form_three_data.php",
        type:"POST",
        data:{registration_id:registration_id},
        success:function(data){
            console.log(data)
        }
      })

      $.ajax({
        url:"get_form_three_data.php",
        type:"POST",
        data:{registration_id:registration_id},
        success:function(data){

            var json_data = JSON.parse(data);
            for (var i = 0; i < json_data.length; i++) {
              var current_data  = json_data[i]
              // console.log(current_data.medic_id+ " " + current_data.medication);

              $(".content-medication ._"+current_data.medic_id).val(current_data.medication)
            }

        }
      })

      $.ajax({
        url:"get_form_three_infusion_data.php",
        type:"POST",
        data:{registration_id:registration_id},
        success:function(data){

            var json_data = JSON.parse(data);
            for (var i = 0; i < json_data.length; i++) {
              var current_data  = json_data[i]
              console.log(current_data.medic_id+ " " + current_data.medication);

              $(".content-infusion ._"+current_data.medic_id).val(current_data.medication)
            }

        }
      })


    })



$(".iv-infusion").keyup(function(e){
  var input_name = $(this).attr("name");
  // alert(input_name)
  var id = $(this).attr('id');
  // alert(id);
  var name_data = $(this).val();
  var registration_id = $("#registration_id").val()
  // alert(name_data);
  $.ajax({
    url:"save_form_three_infusion.php",
    type:"POST",
    data:{field_data:name_data,field_name:input_name,
      registration_id:registration_id,medic_id:id},
    success:function(data){
      console.log(data)
    }
  })

})

$(".iv-infusionhrs").keyup(function(e){
  var input_name = $(this).attr("name");
  // alert(input_name)
  var id = $(this).attr('id');
  // alert(id);
  var name_data = $(this).val();
  var registration_id = $("#registration_id").val()
  // alert(name_data);
  $.ajax({
    url:"save_form_three_infusion_update_hrs.php",
    type:"POST",
    data:{field_data:name_data,field_name:input_name,
      registration_id:registration_id,medic_id:id},
    success:function(data){
      console.log(data)
    }
  })

})


$(".blood-product").keyup(function(e){
  var id = $(this).attr('id');
  var registration_id = $("#registration_id").val();
  var field_name = $(this).attr('name');
  var field_data = $(this).val();
  // alert(field_name)

  $.ajax({
    url:"save_form_three_blood_product.php",
    type:"post",
    dataType:"text",
    data:{registration_id:registration_id,field_data:field_data,
      field_name:field_name,item_id:id},
    success:function(data)
    {
      console.log(data)
    }
  })
})

</script>
