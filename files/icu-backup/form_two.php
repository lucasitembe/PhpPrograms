<?php
include('header.php');
include('../includes/connection.php');
include('get_icu_eve_opening_data.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
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




 ?>

 <a href="icu.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<style media="screen">
  #second{
    background: #fff;
  }
  #second tr{
    table-layout: fixed;
    height: 25px;
  }

  #second tr td:first{
    width: 20px !important;
  }
  #second tr #last{
    text-align:center !important;
    box-sizing: border-box;
    padding-top: 30px;
    width: 10px !important;
  }
  #second tr #first{
    text-align:center !important;
    box-sizing: border-box;
    padding-top: 30px;
    /* width: 10px !important; */
  }

  #second tr .bottom{
    margin-top: 10px;
    padding-top:0px;
    transform: rotate(90deg);
  }
  #second tr td #first{
    margin: 0px;
    padding-top:0px;
    transform: rotate(90deg);
  }
  th[rowspan="25"] {
    text-align: center;
}

#second tr th{
  width: 15px !important;
}

h6{
  padding:0px;
  margin:0px;
}
.td{
  width:40px !important;
}

tr:hover{
  background: #E3E3E3;
  font-weight: bold;
}
</style>

<center>
  <fieldset>
    <legend align=center style="font-weight:bold">Form Second</legend>
    <form class=""  method="post" id="second_form">



      <input type="hidden" id="registration_id" name="registration_id" value="<?=$registration_id;?>">


      <table id="second">
        <tr style="height:30px">
          <th rowspan="21" id="first">
            <h6 class="bottom">
              GENERAL
            </h6>
            <h6 class="bottom" style="margin-top:60px;">
              NERVIOS
            </h6>
            <h6 class="bottom" style="margin-top:60px;">
              SYSTEM
            </h6>

          </th>
          <td colspan="2" >Glassgow Coma Scale (GCS)</td>
          <td class='td'>0700</td>
          <td class='td'>0800</td>
          <td class='td'>0900</td>
          <td class='td'>1000</td>
          <td class='td'>1100</td>
          <td class='td'>1200</td>
          <td class='td'>1300</td>
          <td class='td'>1400</td>
          <td class='td'>1500</td>
          <td class='td'>1600</td>
          <td class='td'>1700</td>
          <td class='td'>1800</td>
          <td class='td'>1900</td>
          <td class='td'>2000</td>
          <td class='td'>2100</td>
          <td class='td'>2200</td>
          <td class='td'>2300</td>
          <td class='td'>0000</td>
          <td class='td'>0100</td>
          <td class='td'>0200</td>
          <td class='td'>0300</td>
          <td class='td'>0400</td>
          <td class='td'>0500</td>
          <td class='td'>0600</td>
        </tr>
        <tr>
          <!-- <td></td> -->
          <td rowspan="4">
            <h6>Eve</h6>
            <h6>Opening</h6>
          </td>
          <td>4. Spontaneous</td>


          <?php

           $data = returnItem_data($registration_id,4);

            $name_list = array('hr_one','hr_two','hr_three','hr_four','hr_five','hr_six',
            'hr_seven',
            'hr_eight','hr_nine','hr_ten','hr_eleven','hr_twelve',
            'hr_thirteen','hr_fourteen','hr_fifteen','hr_sixteen',
            'hr_seventeen','hr_eighteen','hr_nineteen','hr_twenty',
            'hr_twenty_one','hr_twenty_two','hr_twenty_three',
            'hr_twenty_four');

          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" class="eve" name="<?=$name_list[$i]?>" id="4" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <!-- <td></td> -->
          <td>3. To Speech</td>


          <?php

           $data = returnItem_data($registration_id,3);

          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" class="eve" name="<?=$name_list[$i]?>" id="3" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>">

           </td>
          <?php
          }
           ?>
        </tr>
        <tr>
          <!-- <td></td> -->

          <td>2.To Pain</td>

          <?php
           $data = returnItem_data($registration_id,2);
           // echo json_encode($data);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" class="eve" name="<?=$name_list[$i]?>" id="2" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <!-- <td></td> -->
          <td>1. Nil</td>
          <?php

           $data = returnItem_data($registration_id,1);

          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" class="eve" name="<?=$name_list[$i]?>" id="1" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td rowspan="5">
            <h6>Best</h6>
            <h6>Verbal</h6>
            <h6>Response</h6>
          </td>
          <td>5. Oriented</td>

          <?php
          // echo json_encode(returnBestVerbalResponseOne($registration_id,5));
          $data = returnBestVerbalResponseOne($registration_id,5);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="verbal-one" id="5" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <!-- <td></td> -->
          <td>4. Confused</td>

          <?php
            $data = returnBestVerbalResponseOne($registration_id,4);

          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="verbal-one" id="4" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>3. Inappropriate Words</td>

          <?php
          $data = returnBestVerbalResponseOne($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="verbal-one" id="3" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <!-- <td></td> -->
          <td>2. Incomprehensive Sound</td>

          <?php
          $data = returnBestVerbalResponseOne($registration_id,2);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="verbal-one" id="2" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td>1. Nil/Tube</td>

          <?php
          $data = returnBestVerbalResponseOne($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="verbal-one" id="1" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td rowspan="6">
            <h6>Best</h6>
            <h6>Verbal</h6>
            <h6>Response</h6>
          </td>
          <td>6. Obeys</td>
          <?php
          $data = returnBestVerbalResponseTwo($registration_id,6);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="6" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td>5. Localizes</td>

          <?php
          $data = returnBestVerbalResponseTwo($registration_id,5);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="5" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>4. Withdraw</td>

          <?php
          $data = returnBestVerbalResponseTwo($registration_id,4);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="4" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td>3. Abdonormal Flexion</td>

          <?php
          $data = returnBestVerbalResponseTwo($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="3" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>
        </tr>
        <tr>
          <!-- <td></td> -->
          <td>2. Extension(Abnormal)</td>

          <?php
          $data = returnBestVerbalResponseTwo($registration_id,2);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="2" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>1. Nil</td>
          <?php
          $data = returnBestVerbalResponseTwo($registration_id,1);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold"type="text" name="<?=$name_list[$i]?>" class="verbal-two" id="1" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <!-- <td></td> -->
          <td>
            Glassgow Coma
          </td>
          <td>Scale Total</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="<?=$name_list[$i]?>" class="ability"
              id="5" value=""> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td rowspan="4">
            <h6>Ability</h6>
            <h6>To</h6>
            <h6>Move</h6>
          </td>
          <td>R. Arm</td>
          <?php
          $data = returnBestAbilityToMove($registration_id,4);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="ability"
              id="4" value="<?php if(!empty($data[$name_list[$i]]))
              {
                echo $data[$name_list[$i]];
              } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>L. Arm</td>
          <?php
          $data = returnBestAbilityToMove($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td>
              <input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="ability"
              id="3" value="<?php if(!empty($data[$name_list[$i]]))
              {
                echo $data[$name_list[$i]];
              } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>R. Leg</td>

          <?php
          $data = returnBestAbilityToMove($registration_id,2);

          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="ability"
              id="2" value="<?php if(!empty($data[$name_list[$i]]))
              {
                echo $data[$name_list[$i]];
              } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <!-- <td></td> -->
          <td>L. Leg</td>
          <?php
          $data = returnBestAbilityToMove($registration_id,2);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="ability"
              id="1" value="<?php if(!empty($data[$name_list[$i]]))
              {
                echo $data[$name_list[$i]];
              } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <th rowspan="4">
            <!-- <th rowspan="25" id="first"> -->
              <h6 class="bottom">
                PUPIL
              </h6>

          </th>
          <td rowspan="2">
            Pupil Size
          </td>
          <td>Right</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>Left</td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>


        </tr>

        <tr>

          <td rowspan="2">
            Reaction
          </td>

          <td>Right</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td>Left</td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>

        </tr>


        <tr>
          <!-- <td rowspan="14"> -->
            <th rowspan="14" id="first">
              <h6 class="bottom">
                LUNGS
              </h6>
              <h6 class="bottom" style="margin-top:60px;">
                MECHANICS
              </h6>


          </th>
          <td colspan="2">
            Air Entry
          </td>

          <?php

          $data = returnBestLungMechanism($registration_id,1);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="1" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            02 Therapy /FIO2
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,2);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="2" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Ventilator
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="3" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Mode
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,4);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="4" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Pressure Support
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,5);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="5" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            RR SET
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,6);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="6" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            RR Pt
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,7);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="7" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Peak Insipiratory Pressure(PIP)
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,8);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="8" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Minute Volume
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,9);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="9" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>

          <td colspan="2">
            Tidal Volume Set
          </td>
          <?php
          $data = returnBestLungMechanism($registration_id,10);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="10" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            Tidal Volume Pt
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,11);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="11" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            PEEP/CPAP
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,12);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="12" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            I.E RATIO
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,13);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="13" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            ETT Mark
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,14);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="14" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <td rowspan="6"></td>
          <td colspan='2'>POSITION</td>

          <?php
          $data = returnBestLungMechanism($registration_id,15);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="15" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            SPASMS
          </td>

          <?php
          $data = returnBestLungMechanism($registration_id,16);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="lungs" id="16" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            .
          </td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>


        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            .
          </td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <!-- <td></td> -->
          <td colspan="2">
            .
          </td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>

        </tr>
        <tr>
          <td colspan="2">
            .
          </td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input type="text" name="" value=""> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <th rowspan="15">
            <h6 class="bottom">
              ARTERIAL
            </h6>

            <h6 class="bottom" style="margin-top:50px">
              GASES
            </h6>
          </th>
          <td colspan='2'>PH</td>

          <?php
          $data = returnArteryGases($registration_id,1);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="1" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <td colspan="2">
            PCO2
          </td>

          <?php
          $data = returnArteryGases($registration_id,2);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="2" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <td colspan="2">
            PO2
          </td>

          <?php
          $data = returnArteryGases($registration_id,3);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="3" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>
        </tr>

        <tr>
          <td colspan="2">
            HCO2
          </td>

          <?php
          $data = returnArteryGases($registration_id,4);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="4" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <td colspan="2">
            K*
          </td>

          <?php
          $data = returnArteryGases($registration_id,5);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="5" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <td colspan="2">
            Na**
          </td>

          <?php
          for ($i=0; $i < 24; $i++) {
            $data = returnArteryGases($registration_id,6);
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="6" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <td colspan="2">
            Mq2
          </td>
          <?php
          $data = returnArteryGases($registration_id,7);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="7" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <td colspan="2">
            Cl*
          </td>

          <?php
          $data = returnArteryGases($registration_id,8);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="8" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <td colspan="2">
            HCO2
          </td>

          <?php
          $data = returnArteryGases($registration_id,9);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="9" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <td colspan="2">
            PO4
          </td>

          <?php
          $data = returnArteryGases($registration_id,10);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="10" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>

          <td colspan="2">
            BASE
          </td>

          <?php
          $data = returnArteryGases($registration_id,11);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="11" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>

          <td colspan="2">
            O2 SAT
          </td>

          <?php
          $data = returnArteryGases($registration_id,12);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="12" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>
        <tr>

          <td colspan="2">
            Blood Sugar
          </td>

          <?php
          $data = returnArteryGases($registration_id,13);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="13" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>

          <td colspan="2">
            Suction/Secretions
          </td>

          <?php
          $data = returnArteryGases($registration_id,14);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="14" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>
        <tr>

          <td colspan="2">
            Chest Physiotherapy
          </td>

          <?php
          $data = returnArteryGases($registration_id,15);
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="<?=$name_list[$i]?>" class="gases" id="15" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>


        </tr>

        <tr>
          <td id="last" rowspan="12">
            <h6 class="bottom">
              INFUSION
            </h6>
          </td>
          <td colspan='2'>.</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
            <td><input style="text-align:center; font-weight:bold" type="text" name="" value="<?php if(!empty($data[$name_list[$i]]))
            {
              echo $data[$name_list[$i]];
            } ?>"> </td>
          <?php
          }
           ?>

        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>
          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>

          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        <tr>
          <!-- <td></td> -->
          <td colspan="2">

          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

      </table>
    </form>
  </fieldset>
</center>
<?php
include("../includes/footer.php");
 ?>





<script type="text/javascript">

  $(".eve").keyup(function(){
    var id = $(this).attr('id');
    // alert(id)
    var registration_id = $("#registration_id").val();
    // alert(registration_id)
    var field_name = $(this).attr('name');
    // alert(field_name)
    var field_data = $(this).val();
    // alert(field_data)
    $.ajax({
      url:"save_form_two_eve_opening.php",
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

  $(".verbal-one").keyup(function(){
    var id = $(this).attr('id');
    // alert(id)
    var registration_id = $("#registration_id").val();
    // alert(registration_id)
    var field_name = $(this).attr('name');
    // alert(field_name)
    var field_data = $(this).val();
    // alert(field_data)
    $.ajax({
      url:"save_form_two_verbal_opening_one.php",
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



  $(".verbal-two").keyup(function(e){
    var id = $(this).attr('id');
    var registration_id = $("#registration_id").val();
    var field_name = $(this).attr('name');
    var field_data = $(this).val();

    // alert(field_name)

    $.ajax({
      url:"save_form_two_verbal_opening_two.php",
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



  $(".ability").keyup(function(e){
    var id = $(this).attr('id');
    var registration_id = $("#registration_id").val();
    var field_name = $(this).attr('name');
    var field_data = $(this).val();

    // alert(field_name)

    $.ajax({
      url:"save_form_two_ability_to_move.php",
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


  $(".lungs").keyup(function(e){
    var id = $(this).attr('id');
    var registration_id = $("#registration_id").val();
    var field_name = $(this).attr('name');
    var field_data = $(this).val();

    // alert(field_name)

    $.ajax({
      url:"save_form_two_lungs.php",
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


  $(".gases").keyup(function(e){
    var id = $(this).attr('id');
    var registration_id = $("#registration_id").val();
    var field_name = $(this).attr('name');
    var field_data = $(this).val();

    // alert(field_name)

    $.ajax({
      url:"save_form_two_artery_mechanism.php",
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



// select form eve opening table
$(document).ready(function(e){
  var registration_id = $("#registration_id").val()
  // alert(registration_id)


  // console.log(hours[0])
  $.ajax({
    url:"get_icu_eve_opening_data.php",
    type:"POST",
    data:{registration_id:registration_id},
    success:function(data){
      var time = ['hr_one','hr_two','hr_three','hr_four','hr_four','hr_six'];

      var json_data = JSON.parse(data)

      // console.log(json_data[0]);
      var t = JSON.stringify(time);
      // console.log(time)

      for (var i = 0; i < json_data.length; i++) {
        // var hr = time[i]

        for (var key in t) {
          console.log(t)
          // $("#"+json_data[i].item_id).val(json_data[i].key);
        }

        // for (var j = 0; t < time.length; j++) {
          // var hr = t[j];
          // console.log(hr)

          // console.lo g(json_data[0].hr)
          // $("input[name="+hr+"").val();
        }
        // console.log(hr)
      }
  })
})

</script>
