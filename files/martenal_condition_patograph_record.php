<?php
include("./includes/header.php");
include("./includes/connection.php");
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
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

 ?>


<!-- <a href="Labour_atenal_neonatal_record.php?consultation_id=< ?= $consultation_id;?>&patient_id=< ?=$patient_id;?>&admission_id=< ?=$admision_id?>" class="art-button-green">BACK</a> -->

 <center>
   <fieldset>
     <legend style="font-weight:bold"align=center>
       <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
         <p style="margin:0px;padding:0px;">Martenal Condition</p>
         <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
       </div>
   </legend>
     <form class="" action="" method="post" id="labour_form">

       <input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id; ?>">
       <input type="hidden" name="admission_id" id="admission_id" value="<?=$admision_id; ?>">
       <input type="hidden" name="consultation_id" id="consultation_id" value="<?=$consultation_id; ?>">


<?php
  include('fetal_condition_patograph.php');
 ?>


 <div class="">

   <hr style="border:10px solid #C0C0C0; width:96vw;" />
 </div>
<!-- START LABOUR RECORD -->
<!-- <div class="">

<hr style="border:10px solid #C0C0C0; width:96vw;" />

</div> -->
<!-- END LABOUR RECORD -->
<!--start contrac  -->
       <div class="contraction">
         <h4>Contraction</h4>
         <center>
           <!--  side number-->
           <div class="arrange-table">
             <table id="side-number">
             <tr id="">
               <td>5</td>
             </tr>
             <tr id="">
               <td>4</td>
             </tr>

             <tr id="">
               <td>3</td>
             </tr>

             <tr id="">
               <td>2</td>
             </tr>

             <tr id="">
               <td>1</td>
             </tr>

             <tr id="">
               <td>0</td>
             </tr>

             </table>
           </div>
           <!-- end side number -->


           <!-- real table with data -->

           <div class="arrange-table">

             <table id="table" >
               <tr class="tr" id="five" >
                 <!-- <td  style="width:8%; font-weight:bold;" >PROTEIN</td> -->
                 <?php
                 for ($i=0; $i < 48; $i++) {
                   ?>
                   <td style="height:30px; margin:0px;padding:0px;" id="<?=($i/2);?>"></td>
                 <?php }
                  ?>
                  <br />
               </tr>

               <tr id="four">
                 <?php
                 for ($i=0; $i <48; $i++) {
                   ?>
                   <td style="height:30px; margin:0px;padding:0px;" id="<?=($i/2);?>"></td>
                 <?php }
                  ?>
               </tr>
               <tr id="three">

                 <?php
                 for ($i=0; $i <48; $i++) {
                   ?>
                   <td style="height:30px; margin:0px;padding:0px;" id="<?=($i/2);?>">
                     <!-- <div class="image">
                       <img src="images/dot.png" width="100%;" height="100%;" padding="0px;" margin="0px;">
                     </div> -->
                   </td>
                 <?php
               }
                  ?>
               </tr>
               <tr id="two">
                 <!-- <td style="width:8%; font-weight:bold;">VOLUME</td> -->
                 <?php
                 for ($i=0; $i <48; $i++) {
                   ?>
                   <td style="height:30px; margin:0px;padding:0px;" id="<?=($i/2);?>"></td>
                 <?php }
                  ?>
               </tr>

               <tr id="one">
                 <!-- <td style="width:8%; font-weight:bold;">VOLUME</td> -->
                 <?php
                 for ($i=0; $i <48; $i++) {
                   ?>
                   <td  style="height:30px; margin:0px;padding:0px;" id="<?=($i/2);?>"></td>
                 <?php }
                  ?>
               </tr>

               </table>

               <table class="number-time" id="number-time-side">
                 <tr id="time">

                   <?php
                   for ($i=0; $i <= 48; $i++) {
                     ?>
                     <td id="<?=($i/2);?>"><?=($i/2);?></td>
                   <?php }
                    ?>
                 </tr>
               </table>


           </div>

           <!--  end real table with data-->


         </center>
       </div>

       <!-- end contraction -->

       <div class="">
       <!-- <span>
         <input style="width:10%;" type="submit" class="art-button-green" name="" value="Contraction per min" 
         id="contraction">
        </span> -->
        <span>
          <!-- <input style="width:10%;" type="submit" class="art-button-green" name="" value="Audit" id="contraction_audit"> -->
        </span>
       </div>

       <div class="">
                <hr style="border:10px solid #C0C0C0; width:96vw;" />
       </div>



<div>
  <div class="">
       <table id="table">
         <tr class="cell-width" id="oxytocine_fill" >
           <td style="width:8%; font-weight:bold;">Oxyticin IU</td>
           <?php
           for ($i=0; $i < 48; $i++) {
             ?>
             <td id="<?=($i/2);?>"></td>
           <?php }
            ?>
            <br />
         </tr>
         <tr class="cell-width" id="drop_fill" >
           <td style="width:8%; font-weight:bold;">Drops/Minute Pulse</td>
           <?php
           for ($i=0; $i <48; $i++) {
             ?>
             <td id="<?=($i/2);?>"></td>
           <?php }
            ?>
         </tr>
         </table>
         <table id="number-time">
           <tr id="time">

             <?php
             for ($i=0; $i <= 48; $i++) {
               ?>
               <td id="<?=($i/2);?>"><?=($i/2);?></td>
             <?php }
              ?>
           </tr>
         </table>

       </div>
         <div class="">
           <br>
           <br />

           <!-- <span><input style="width:10%;"  type="submit" class="art-button-green" name="" value="Oxytocin" id="oxyticin"> </span>

           <span>
             <input id="drop" style="width:10%;" type="submit" class="art-button-green"  name="" value="Drops/mm" id="drop">

           </span> -->

           <span>
             <!-- <input style="width:10%;" type="submit" class="art-button-green"  name="" value="Audit" id="oxytocine_audit"> -->

           </span>
           </div>

</div>



<div class="">
<!-- <hr style="border:10px solid #C0C0C0; width:96vw;" /> -->
</div>


<!--  pulse and bp -->
<div style="margin-top: 70px; margin-left:20px; width:100%"  >
  <!-- <h4>drug Given and IV Fluids</h4>
<table id='med'   border="none" width="95%;"  style=" height:130px;">
  <tr rowspan="2" height='100px;' style="background:#fff;">
    < ?php

    for($i=1; $i <= 24 ; $i++){
      echo "<td  class='td' style='width:4%; height:110px;
       box-sizing:border-box !important; vertical-align:bottom; '>
        <p class='_$i' class='medicine-name' style='margin-bottom:20px;width:100%; box-sizing:border-box; 
        word-wrap:break-word'>

        </p>
      </td>";
    }

     ?>
  </tr>
  <tr id="medicine_number" style='border:none !important; background:none !important; '>
    < ?php
    for ($i=1; $i<=24 ; $i++) {
      echo "<td style='width:3%; text-align:center: border:none !important' >$i</td>";
    }

     ?>
  </tr>
</table>
<div class="">
  <span>
    <input type="submit" class="art-button-green" name="" id="add_medicine" class="art-button-green" value="Medicine"> -->
  </span>
  <span>
    <!-- <input type="submit" class="art-button-green" name="" id="medicine_audit" class="art-button-green" value="Audit"> -->
  </span>
</div>
</div>
<!-- DONT DELETE IS FOR PULSE GRAPHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH -->
<!-- <div class="">

  <hr style="border:10px solid #C0C0C0; width:96vw;" />
</div> -->

  <!-- <h4>Pulse</h4>
  <!-- <h4>Pulse And Bp</h4>
<div id="pulse" style="margin:0px; padding:0px;"> -->

<!-- </div>

<div class="">
  <span> pulse </span>
  <span>
    <input id="pulse_no" style="width:10%;" type="text" name="" value="">
  </span>
  <span> Time </span>
  <span>
    <input id='pulse_time'style="width:10%;" type="text" name="" value=""> </span>
  <span>
    <input type="submit" class="art-button-green" name="" id="pulsed" value="PULSE">
  </span>


    <span>
      <input type="submit" class="art-button-green" name="" id="add_bp" class="art-button-green" value="Blood Pressure">
     </span>

  <span>
    <input type="submit" class="art-button-green" name="" id="add_medicine" class="art-button-green" value="Medicine">
   </span>

   <span>
     <input type="submit" class="art-button-green" name="" id="pulse_bp_audit" class="art-button-green" value="Audit">
    </span>
</div> -->

<div class="">

  <hr style="border:10px solid #C0C0C0; width:96vw;" />
</div>


<!-- tempearature -->
<table id="table">
<tr class="bpressure" id="bpressure">
    <td  style="width:8%; font-weight:bold;" >BLOOD PRESSURE</td>
    <?php
    for ($i=0; $i < 24; $i++) {
      ?>
      <td id="bloodbp<?=($i);?>"></td>
    <?php }
     ?>
     <br />
  </tr>
  <tr id="temp_fill">
    <td style="width:8%; font-weight:bold;">TEMP</td>
    <?php
    for ($i=0; $i <=24; $i++) {
      ?>
      <td id="<?=($i);?>"></td>
    <?php }
     ?>
     <br />
  </tr>
  <tr id="res_fill">
    <td style="width:8%; font-weight:bold;">RESP.</td>
    <?php
    for ($i=0; $i <24; $i++) {
      ?>
      <td id="<?=($i);?>"></td>
    <?php }
     ?>
  </tr>
  </table>
  <table id="number-timet">
    <tr id="time">

      <?php
      for ($i=0; $i <=24; $i++) {
        ?>
        <td id="<?=($i);?>"><?=($i);?></td>
      <?php }
       ?>
    </tr>

  </table>


  <div class="">
    <!-- <span>
     <button style="color:#fff !important; height:30px !important;width:70px;"class="art-button-green" id="temperature" >
      Temperature</button> </span>
    <span>
       <button style="color:#fff !important; height:30px !important;width:70px;" class="art-button-green" id="resp">Resp </button >
       </span>
    <span> <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="bp">Blood Pressure</button> </span> -->
       <span>
          <!-- <button style="color:#fff !important; height:30px !important;width:70px;" class="art-button-green" id="resp_temp_audit">Audit</button > -->
      </span>
  </div>

  <div class="">
    <hr style="border:10px solid #C0C0C0; width:96vw;" />
  </div>

<!-- urine  -->
<h4 style="margin:0px; padding:0px;">Urine</h4>
<table id="table">
  
  <tr class="urine">
    <td  style="width:8%; font-weight:bold;" >PROTEIN</td>
    <?php
    for ($i=0; $i < 24; $i++) {
      ?>
      <td id="pro<?=($i);?>"></td>
    <?php }
     ?>
     <br />
  </tr>
  <tr id="urine">
    <td style="width:8%; font-weight:bold;">ACETONE</td>
    <?php
    for ($i=0; $i <24; $i++) {
      ?>
      <td id="ace<?=($i);?>"></td>
    <?php }
     ?>
  </tr>
  <tr id="urinev">
    <td style="width:8%; font-weight:bold;">VOLUME</td>
    <?php
    for ($i=0; $i <24; $i++) {
      ?>
      <td id="vol<?=($i);?>"></td>
    <?php }
     ?>
  </tr>
  </table>
  <table id="number-timet">
    <tr id="time">

      <?php
      for ($i=0; $i <=24; $i++) {
        ?>
        <td id="<?=($i);?>"><?=($i);?></td>
      <?php }
       ?>
    </tr>

  </table>
  <div class="">
    <!-- <span><button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="protein"> Protein </button></span>
    <span><button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="acetone"> Acetone</button> </span>
    <span> <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="volume">Volume</button> </span>
    <span> -->
    <span>
      <!-- <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="volume_audit">Audit -->
      </button>
     </span>
  </div>

</form>
</fieldset>

<center>
  <fieldset>
          <legend style="font-weight:bold"align=center>SUMMARY OF LABOUR</legend>
          <!-- <h4 style="font-weight:bold;color:red;"><i>**This Part Is Filled After Complete Of The Above Part**</i></h4> -->
          <!-- <center><h3 style='background-color:#bdb5ac'>CHILD</h3></center> -->
          <!-- ******************************************************************************************************* -->
          <?php
            $select_summary=mysqli_query($conn,"SELECT  sl.date_birth, sl.weight, sl.sex, sl.apgar, sl.method_delivery, sl.first_stage, sl.second_stage, sl.third_stage, sl.fourth_stage, sl.placenta_membrane, sl.blood_loss, sl.reason_pph, sl.perineum, sl.repair_by, sl.delivery_by,sl.supervision_by FROM summary_labour as sl,tbl_admission as ad,tbl_patient_registration as pr where sl.Registration_ID='$patient_id' and sl.admission_id='$admision_id' and sl.admission_id=ad.Admision_ID and sl.Registration_ID=pr.Registration_ID");
            if(mysqli_num_rows($select_summary)>0){
            while($summary=mysqli_fetch_array($select_summary)){ 
                $date_birth=$summary['date_birth'];
                $weight=$summary['weight'];
                $sex=$summary['sex'];
                $apgar=$summary['apgar'];
                $method_delivery=$summary['method_delivery'];
                $first_stage=$summary['first_stage'];
                $second_stage=$summary['second_stage'];
                $third_stage=$summary['third_stage'];
                $fourth_stage=$summary['fourth_stage'];
                $placenta_membrane=$summary['placenta_membrane'];
                $blood_loss=$summary['blood_loss'];
                $reason_pph=$summary['reason_pph'];
                $perineum=$summary['perineum'];
                $repair_by=$summary['repair_by'];
                $delivery_by=$summary['delivery_by'];
                $supervision_by=$summary['supervision_by'];
            }
        ?>
          <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr 1fr; gap:1em;margin-top:20px;">
              <div class="one">
                Date Of Birth<td><input type='date' id='date_birth' name="date_birth" value="<?php echo $date_birth;?>" class="form-control" >
              </div>
              <div class="two">
                Weight<input type="text" id="weight" name="weight" value="<?php echo $date_birth;?>" class="form-control">
              </div>
              <div class="two">
                Sex<input type="text" id="sex" name="sex" value="<?php echo $sex;?>" class="form-control">
              </div>
              <div class="four">
                Apgar<td><input type='text' id='apgar' name="apgar"  value="<?php echo $apgar;?>" class="form-control" >
              </div>
        
              <div class="five">
                Method Of Delivery<input type="text" id="method_delivery" name="method_delivery"  value="<?php echo $method_delivery;?>" class="form-control">
              </div>
              <div class="six">
                Method Of Resuscition<input type="text" id="method_resuscition" name="method_resuscition"  value="<?php echo $method_resuscition;?>" class="form-control">
              </div>
          </div>
          <!-- ****************************************************************************************** -->

          <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr 1fr; gap:1em;margin-top:20px;">
              <div class="one">
                  First Stage :Duration<td><input type='text' id='first_stage' name="first_stage" value="<?php echo $first_stage;?>" class="form-control" >
              </div>
        
              <div class="two">
                Second Stage :Duration<input type="text" id="second_stage" name="second_stage" value="<?php echo $second_stage;?>"  class="form-control">
              </div>
              <div class="four">
                Third Stage :Duration<td><input type='text' id='third_stage' name="third_stage" value="<?php echo $third_stage;?>"  class="form-control" >
              </div>
        
              <div class="five">
              Fourth Stage :Duration<input type="text" id="fourth_stage" name="fourth_stage"  value="<?php echo $fourth_stage;?>" class="form-control">
              </div>
              <div class="six">
                Placenta And Membranes<input type="text" id="placenta_membrane"  value="<?php echo $placenta_membrane;?>" name="placenta_membrane" class="form-control">
              </div>
              <div class="seven">
                Blood Loss<input type="text" id="blood_loss" name="blood_loss"  value="<?php echo $blood_loss;?>" class="form-control">
              </div>
              <div class="eight">
                Reason For-PPH<td><input type='text' id='reason_pph' name="reason_pph" value="<?php echo $reason_pph;?>"  class="form-control" >
              </div>
        
              <div class="nine">
                Perineum<input type="text" id="perineum" name="perineum"  value="<?php echo $perineum;?>" class="form-control">
              </div>
              <div class="ten">
                Repair By<input type="text" id="repair_by" name="repair_by" value="<?php echo $repair_by;?>"  class="form-control">
              </div>
              <div class="ten">
                Delivery By<input type="text" id="delivery_by" name="delivery_by" value="<?php echo $delivery_by;?>"  class="form-control">
              </div>
              <div class="ten">
                Supervision By<input type="text" id="supervision_by" name="supervision_by"  value="<?php echo $supervision_by;?>" class="form-control supervision_by">
              </div>
          </div>
          <!-- <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:15px;width:80px;">
              <div class="one">
                  <input type='button' id='save_btn' name="save_btn" value="Save" style="width:80px; height:30px !important; color:#fff !important " id="save_labour" class="art-button-green" onclick="save_labour()">
              </div> -->
              <?php
            }else{
                echo "<td><h4 style='font-weight:bold;color:red;'><i>**No Data Found**</i></h4></td>";
            }
              ?>
  </fieldset>
  </center>
  

<center>

  <div id="show_dialogp">
    <center>
      <br />
    <table style="border:none;width:100%;">
     <tr style="height:35px; width:60%;">
       <td style="text-align:center" >Protein</td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">Select Time</td>
     </tr>

       <tr style="height:35px; width:60%;" >
       <td style="text-align:center" ><input type="text" name="protein_remark" value="" id="protein_remark" > </td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">
         <select class="show-time" id="show-timepro" name="time" required>
           <option value="">--Select Time--</option>
           <?php
           for ($i=0; $i <= 24 ; $i++){
             ?>
             <option value="<?=($i); ?>"><?=($i) ;?></option>
           <?php
           }
            ?>
         </select>
       </td>
     </tr>
    </table>
    <br />
    <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savep" value="Save">
  </center>
  </div>

  

  



  <!-- acetone -->

  <div id="show_dialoga">
    <center>
      <br />
    <table style="border:none;width:100%;">
     <tr style="height:35px; width:60%;">
       <td style="text-align:center" >Acetone</td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">Select Time</td>
     </tr>

       <tr style="height:35px; width:60%;">
       <td style="text-align:center" ><input id="acetone_remark" type="text" name="id="acetone_remark"" value=""> </td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">
         <select id="show-timea" name="time" required>
           <option value="">--Select Time--</option>
           <?php
           for ($i=0; $i <= 24 ; $i++){
             ?>
             <option value="<?=($i); ?>"><?=($i) ;?></option>
           <?php
           }
            ?>
         </select>
       </td>

     </tr>
    </table>
    <br />
    <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savea" value="Save">
  </center>
  </div>

  <!-- Volume -->


  <div id="show_dialogv">
    <center>
      <br />
    <table style="border:none;width:100%;">
     <tr style="height:35px; width:60%;">
       <td style="text-align:center" >Volume</td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">Select Time</td>
     </tr>

       <tr style="height:35px; width:60%;">
       <td style="text-align:center">
         <input type="text" name="volume_remark" id="volume_remark" value=""> </td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">
         <select id="show-timev" name="timev" required>
           <option value="">--Select Time--</option>
           <?php
           for ($i=0; $i <= 24 ; $i++){
             ?>
             <option value="<?=($i); ?>"><?=($i) ;?></option>
           <?php
           }
            ?>
         </select>
       </td>
     </tr>
    </table>
    <br />
    <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savev" value="Save">
  </center>
  </div>




  <!-- //////////////////////////////////////////////////////////////////////////////// -->

  <!-- acetone -->


  <!-- ////////////////////////////////////////////////////////////////////////////////////// -->


<!--  end volume-->

<!-- start BLOOD PRESSURE -->

<div id="show_dialogbp">
    <center>
      <br />
    <table style="border:none;width:100%;">
     <tr style="height:35px; width:60%;">
       <td style="text-align:center" >Blood Pressure</td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">Select Time</td>
     </tr>
       <tr style="height:35px; width:60%;">
       <td style="text-align:center">
         <input type="text" name="bp_remark" id="bp_remark"> </td>
       <!-- <td  style="width:30%; border:none;"></td> -->
       <td style="text-align:center">
         <select id="show-timebp" name="timebp" required>
           <option value="">--Select Time--</option>
           <?php
           for ($i=0; $i <= 24 ; $i++){
             ?>
             <option value="<?=($i); ?>"><?=($i) ;?></option>
           <?php
           }
            ?>
         </select>
       </td>
     </tr>
    </table>
    <br />
    <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savebp" value="Save">
  </center>
  </div>
<!-- END BLOOD PRESSURE -->


<!--  start temperature-->

<div id="show_dialogt">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Temperature</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour"><input type="text" name="" id="temp_remark" value=""> </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="show-timet" name="timet" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="<?=($i); ?>"><?=($i) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savet" value="Save">
</center>
</div>


<!--  end temperature -->


<!-- start resp -->


<div id="show_dialogr">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Resp</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input type="text" name="" value="" id="res_remark"> </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="show-timer" name="timer" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="<?=($i); ?>"><?=($i) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="saver" value="Save">
</center>
</div>



<!-- end resp -->

<!-- start oxytocine -->
<div id="show_dialogo">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Resp</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input type="text" name="" value="" id="oxytocine_remark"> </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="show-timeo" name="timeo" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 48 ; $i++){
           ?>
           <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="saveo" value="Save">
</center>
</div>


<!-- start Drops -->
<div id="show_dialogd">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Drops</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input type="text" name="" id="drop_remark" value="">
     </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="show-timed" name="timed" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 48 ; $i++){
           ?>
           <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="saved" value="Save">
</center>
</div>

<!--  end drops-->



<!-- start bp -->


<div id="show_dialogbp">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" colspan='2' >Blood Pressure</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input type="text" name="" placeholder="start time" id="bp_start" value="">
     </td>
     <td style="text-align:center" >
       <input type="text" name="" placeholder="end time" id="bp_end" value="">
     </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="bp_time" name="timebp" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 48 ; $i++){
           ?>
           <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="add_bp_no" value="Add Bp">
</center>
</div>


<!--  end bp-->
<!-- pulse Dialog -->

<div id="show_dialogpulse">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Pulse</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input type="text" name="" id="pulse_no" value="">
     </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="pulse_time" name="timepulse" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 48 ; $i++){
           ?>
           <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="add_pulse" value="Add Pulse">
</center>
</div>


<!-- end pulse -->
<!-- start contraction -->

<div id="show_dialogc">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Contraction Per 10 Minutes</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">Select Time</td> -->
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
      <div>
        <label>
       <span>
         <img src="images/dot.png" alt=""  width="34px" height="34px">
       </span>
       <span style="margin-right:9px;">0-20 sec</span>
       <span>
         <input type="radio" class="contraction_per_min" name="contraction_per_min" value="2">  </span>
       </label>
       <label>
       <span>
         <img src="images/slash.png" alt="" width="34px" height="34px" >
       </span>
       <span style="margin-right:9px;">20-40 sec</span>
       <span>
         <input type="radio" class="contraction_per_min" name="contraction_per_min" value="4">
         </span>
       </label>

       <label>
       <span >
         <img src="images/full_black.png" alt=""  width="34px" height="34px">
       </span>
       <span>More Than 40 sec</span>
       <span>
         <input type="radio" class="contraction_per_min" name="contraction_per_min" value="5">
       </span>
     </label>
       <div>
       <!-- <input id="contraction_per_min" type="text" name="" value=""> </td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
   </tr>
   <tr style="height:50px;">
     <td style="text-align:center">
       <select class="show-timec" name="time" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 48 ; $i++){
           ?>
           <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savec" value="Save">
</center>
</div>


<!-- end contraction -->



<!-- start medicine dialog -->

<div id="show_dialogmedic">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Medicine</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" >
       <input id="medicine_val" type="text" placeholder="medicine short name" name="" value=""> </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select class="show-timemedic" name="time" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="<?=($i); ?>"><?=($i) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>

   <tr >
     <td colspan="2" style="text-align:center; height:30px;">
       <span><select class="official_med_name" name="" style="width:75%;">
        <option value="">--Select Medicine--</option>
        <?php
        if ($result = mysqli_query($conn,"SELECT item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='Pharmacy' order by Product_Name")) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value=".$row['item_ID'].">".$row['Product_Name']."</option>";
          }
        }else {
          echo mysqli_error($conn);
        }
         ?>
       </select></span><span><button type="button" class="art-button-green" style="color:#fff !important; height:25px!important;" name="button" id='add_officeial_name'>Add</button> </span>
     </td>
   </tr>
  </table>
  <table id='added_medicine' width="100%;">
    <tbody>

    </tbody>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savemedic" value="Save">
</center>
</div>
<!-- end medicine dialog -->

<!-- contraction audit dialog -->
<div id="contraction-audit-dialog">

</div>
<!-- end audit  -->

<!-- oxytocine  -->
<div id="oxytocine_drops-audit-dialog">

</div>
<!-- end  -->

<div id="medicine-audit-dialog">

</div>

<div id="pulse_bp-audit-dialog">

</div>


<div id="temp_resp-audit-dialog">

</div>

<div id="temp_resp-audit-dialog-volume">

</div>

</center>
<style media="screen">
  #chart1{
    height: 480px;
    margin:15px auto;
    width: 93vw !important;
  }

  #table{
    width: 90vw;
    background: white;
    float: left;
    margin-left: 25px;
  }

  table{
    border-collapse: collapse;

  }

  table,tr,td{
    border: 1px solid grey;
    box-sizing: border-box;
  }
#table td{
  width: 1.9%;
}
#table #time{
  height: 20px;
}

.number-time{
  border-collapse: collapse;
  border: none !important;
  width: 90vw;
  margin: 0px !important;
  padding: 0px !important;

}

.number-time #time td{
  border-collapse: collapse;
  border: none !important;
  width: 3%;


}
#number-time-side{
  margin-left: 25px !important;
}
#number-time-side #time td{
  /* width: : 8% !important; */
}

#table-input
{
  background: #fff;
  width: 20%;
  margin-top:20px;
}


.image{
  margin: 0px !important;
  padding: 0px !important;
  height: 100% !important;
  width: 100% !important;
}

.image img{
  padding: 0px;
  margin:0px;
}

#table .urine td{
  width: 2%;
}
#table-input td{
  text-align: center !important;
}
#table-input td:hover{
  background: grey;
}
.btn-inputl{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-inputm{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-input:hover{
  background: grey;
}
.show-time{
  text-align: center;
}
#remark td{
  box-sizing: border-box;
}

#number-time{
  float: left;
  margin-left: 119px;
}

.contraction #table{
  height: 150px !important;
  width: 90vw ;
}

.contraction #table .tr td{
  width: 2%;
}

.contraction #table{
  height: 100px;
  width: 90vw ;
}

#number-timet{
  width:80vw;
  margin-left: 141px;
}

#number-timet, #time{
border: none !important;
}
#number-timet #time td{
  width: 4%  !important;
  text-align: center;
  border: none !important;
}

#table #moulding{
  height: 20px !important;
}
#liqour_remark{
  height: 20px !important;
}

#time td {
  padding-left: ;
  border: none;
}

#number-time{
  width: 84vw;
}
#number-time tr td{
  border: none !important;
  width: 2.7% !important;
  box-sizing: border-box;
  font-size: 10px;
  text-align: left;
}
#number-time ,tr{
  border: none !important;
}
#time{
  border: none !important;
}
#table .cell-width td{
  width: 1.9%;
}

.arrange-table{
  display: inline-block;
  vertical-align: top;
}

#side-number{
  margin-top: 18px;
  border-collapse: collapse;
  border: none !important;
}

#side-number tr{
  height: 27px !important;
}
.arrange-table #side-number tr td{
  border-collapse: collapse;
  border: none !important;
  margin-left: 0px;
  padding-left: 0px;
  overflow: hidden;
  box-sizing: border-box !important;
}

.arrange-table #table .tr td{
  border-collapse: collapse;
  border: none !important;
  margin-left: 0px;
  padding-left: 0px;
  width: 2% !important;
  height: 3% !important;
  box-sizing: border-box !important;
  object-fit: td;
}

.arrange-table #table{
  table-layout: fixed;
}

.medicine-name{
  transform: rotate(-90deg);
  text-align: center;
  /* float: left; */
	/* transform-origin: right top 0; */
}

#med{
table-layout: fixed;
}

#med .td{
  box-sizing: border-box !important;
}
table #medicine_number td{
  border: none !important;
  text-align: center;
  background: none !important;
  vertical-align:bottom;
}

table #medicine_number{
  border: none !important;
}

.official_med_name option {
    width:400px;
    text-overflow:ellipsis;
    overflow:hidden;
    height: 25px;
}

</style>


<!-- <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css"> -->
<script type="text/javascript" src="jquery.min.js"></script>
<!-- <script src="js/jquery-ui-1.8.23.custom.min.js"></script> -->
<!-- <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script> -->
<script src="./booststrapcollapse/jquery-ui.min.js" ></script>
<script type="text/javascript" src="jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="plugins/jqplot.pointLabels.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />

<script type="text/javascript">

var time1;
var bp1;
var bp2;
var powPoints1= [[]];
var pulsePoints1= [[]];
var bpPoints1 = [[]];
var bpPoints2 = [[]];
var bpPoints3 = [[]];
var bpPoints4 = [[]];
var bpPoints5 = [[]];
var bpPoints6 = [[]];
var bpPoints7 = [[]];
var bpPoints8 = [[]];


// start ploting bp graph

options =
{
    seriesColors: [ "#4bb2c5", "#c5b47f", "#EAA228", "#579575", "#839557", "#958c12",
        "#953579", "#4b5de4"],  // colors that will
         // be assigned to the series.  If there are more series than colors, colors
         // will wrap around and start at the beginning again.

    stackSeries: false, // if true, will create a stack plot.
                        // Currently supported by line and bar graphs.

    title: '',      // Title for the plot.  Can also be specified as an object like:

    title: {
        text: 'malopa',   // title for the plot,
        show: true,
    },
  }

function plotBp(){


  var plot3 = $.jqplot('pulse', [pulsePoints1,bpPoints1,bpPoints2,bpPoints3,bpPoints4,bpPoints5,bpPoints6,
    bpPoints7,bpPoints8],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:.5,
      label:"Hour Time"
    },
    yaxis:{
      max:180,
      min:60,
      tickInterval:20,
      label:" Contraction per 1o mins ",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
                  }
    },

  },
  grid: {
              drawBorder: false,
              shadow: false,
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },
      seriesDefaults: {
          showMarker:false,
          pointLabels: {
              show: true,
              escapeHTML: false,
              ypadding: -15
           }
      },
      series:[
        // {fillAndStroke: true, color: '#000', fillColor: '#000' },
        {
            // Change our line width and use a diamond shaped marker.
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: { style:'x' }
          },
          {
            // Don't show a line, just show markers.
            // Make the markers 7 pixels with an 'x' style
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            showLine:true,
            markerOptions: { size: 7, style:"circle" }
          },
          {
            // Use (open) circlular markers.
            showLine:true,
            lineWidth:5,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: { sizi:7,style:"circle" }
          },
      ]
      // Series options are specified as an array of objects, one object
      // for each series.
      // series:[
      //     {
      //       // Change our line width and use a diamond shaped marker.
      //       lineWidth:3,
      //       fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
      //       markerOptions: { style:'x' }
      //     },
      //     {
      //       // Don't show a line, just show markers.
      //       // Make the markers 7 pixels with an 'x' style
      //       showLine:true,
      //       markerOptions: { size: 7, style:"circle" }
      //     },
      //     {
      //       // Use (open) circlular markers.
      //       showLine:true,
      //       lineWidth:5,
      //       markerOptions: { sizi:7,style:"circle" }
      //     },
      //     {
      //       // Use (open) circlular markers.
      //       showLine:true,
      //       lineWidth:5,
      //       markerOptions: { sizi:7,style:"circle" }
      //     },
      //     {
      //       // Use a thicker, 5 pixel line and 10 pixel
      //       // filled square markers.
      //       showLine:true,
      //       lineWidth:3,
      //       markerOptions: {size:10 }
      //     },
      //
      //
      // ]
    }
  );



}
// end bp grap

function plotPulseUrine()
{


  var plot3 = $.jqplot('pulse', [pulsePoints1,bpPoints1,bpPoints2,bpPoints3,bpPoints4,bpPoints5,bpPoints6,bpPoints7,
    bpPoints8],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:.5,
      label:"Hour Time"
    },
    yaxis:{

      max:180,
      min:60,
      tickInterval:20,
      label:" Contraction per 1o mins ",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
                  }
    },

  },
  grid: {
              drawBorder: false,
              shadow: false,
              // background: 'rgba(0,0,0,0)'  works to make transparent.
              background: 'white'
          },

      seriesDefaults: {

        pointLabels: {
            show: true,
            escapeHTML: false,
            ypadding: -15
         }
      },
      // Series options are specified as an array of objects, one object
      // for each series.
      series:[



          {
              // Change our line width and use a diamond shaped marker.
              lineWidth:3,
              fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              // markerOptions: { style:'x' }
            },
            {
                // Change our line width and use a diamond shaped marker.
                lineWidth:3,
                fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
                // markerOptions: { style:'x' }
              },
              {
                  // Change our line width and use a diamond shaped marker.
                  lineWidth:3,
                  fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
                  // markerOptions: { style:'x' }
                },

            {

              fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              showLine:true,
              // markerOptions: { size: 7, style:"circle" }
            },
            {
              // Use (open) circlular markers.
              showLine:true,
              lineWidth:5,
              fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              markerOptions: { sizi:7,style:"circle" }
            },
          // {
          //   // Change our line width and use a diamond shaped marker.
          //
          //   lineWidth:3,
          //   markerOptions: { style:'x' }
          // },
          // {
          //   // Don't show a line, just show markers.
          //   // Make the markers 7 pixels with an 'x' style
          //   showLine:true,
          //   markerOptions: { size: 7, style:"circle" }
          // },
          // {
          //   // Use (open) circlular markers.
          //   showLine:true,
          //   lineWidth:5,
          //   lineColor:"#000",
          //   markerOptions: { sizi:7,style:"circle" }
          // },
          // {
          //   // Use a thicker, 5 pixel line and 10 pixel
          //   // filled square markers.
          //   showLine:true,
          //   lineWidth:3,
          //   markerOptions: {size:10 }
          // },
          //

      ]
    }
  );

}

$(document).ready(function(){
// Some simple loops to build up data arrays.
// protein
// $(".official_med_name").select2();




$.ajax({
  url:"get_medicine_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    var jsonData = JSON.parse(data);
    
    for (var i = 0;i < jsonData.length; i++) {
    // for (var i = 0;i < 100; i++) {
      var current = jsonData[i]
        // console.log(current)  

        $("#med ._"+current.medicine_time).append(current.med_short_name+",")
    };
  }

})


$.ajax({
  url:"get_contraction_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    // console.log(data)
    var jsonData = JSON.parse(data)

    // console.log(jsonData)
    for (var i = 0; i < jsonData.length; i++) {
      var counter = jsonData[i];

      // console.log((counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" ))

      if (counter.contraction >=1 && counter.contraction <= 2) {
        $("#two #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'  style='height:30px;'>"+
          "<img src='images/dot.png' width='25.5px;' height='32px;;' padding='0px;' margin='0px;'></div>");

          $("#one #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/dot.png' width='25.5px;' height='32px;' padding='0px;' margin='0px;'></div>");

      }else if(counter.contraction >2 && counter.contraction <= 4){

        $("#four #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/slash.png' width='25.5px;;' height='32px;' padding='0px;' margin='0px;'></div>");

        $("#three #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/slash.png' width='25.5px;' height='32px;' padding='0px;' margin='0px;'></div>");

        $("#two #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/slash.png' width='25.5px;' height='32px;' padding='0px;' margin='0px;'></div>");

          $("#one #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/slash.png' width='25.5px;' height='32px;' padding='0px;' margin='0px;'></div>");

      }else if (counter.contraction >4 && counter.contraction <= 6) {

        $("#five #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/full_black.png' width='25.5px;' height='34px;' padding='0px;' margin='0px;'></div>");


        $("#four #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/full_black.png' width='25.5px;' height='34px;' padding='0px;' margin='0px;'></div>");

        $("#three #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'>"+
          "<img src='images/full_black.png' width='25.5px;;' height='34px;' padding='0px;' margin='0px;'></div>");

        $("#two #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
          "<div class='image'style='margin:0px; padding:0px;' >"+
          "<img src='images/full_black.png' width='25.5px;' style='mar.5gin:0px; padding:0px;' height='34px;' padding='0px;' margin='0px;'></div>");

          $("#one #"+(counter.time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/full_black.png' width='25.5px;;' height='34px;' padding='0px;' margin='0px;'></div>");

      }



    }
    // $("#contraction-audit-dialog").html(data)
    // $("#contraction-audit-dialog").dialog("open");
  }
})


// get drops 
$.ajax({
  url:"get_oxytocine_drops_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    // console.log(data);
    var jsonData = JSON.parse(data);
    for (var i = 0; i < jsonData.length; i++) {
      var counter = jsonData[i];
      // console.log(counter.oxytocine_time +" " + counter.oxytocine );

      // $("#oxytocine_fill #"+counter.oxytocine_time).html(counter.oxytocine );
      if(counter.drops == 0) {
        counter.drops = "";
        $("#drop_fill #"+(counter.oxytocine_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(counter.drops );
      }else {
        $("#drop_fill #"+(counter.oxytocine_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(counter.drops );
      }
    }
  }
})
// get oxytocine
// oxytocine
$.ajax({
  url:"get_oxytocine.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    // console.log(data);
    var jsonData = JSON.parse(data);
    for (var i = 0; i < jsonData.length; i++) {
      var counter = jsonData[i];
      // console.log(counter.oxytocine_time +" " + counter.oxytocine );

      $("#oxytocine_fill #"+(counter.oxytocine_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(counter.oxytocine );

    }
  }
})


// pulse db medicene

$.ajax({
  url:"get_pulse_bp_medicine_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    // console.log(data);

    var jsonData = JSON.parse(data);
    var point=[];
    var bp=[];

    for (var i = 0; i <jsonData.length; i++) {
    var counter = jsonData[i];

    point = [parseFloat(counter.pulse_time),parseFloat(counter.pulse)];

    pulsePoints1.push(point)
    plotPulseUrine();

    // if (i<=1) {
    //   bp = [counter.pulse_time,counter.bp_start];
    //   bp = [counter.pulse_time,counter.bp_end];
    //
    //   bpPoints1.push(bp);
    //
    //   console.log(bpPoints1);
    //
    //   plotBp();
    // }

    }
  }
})


$.ajax({
  url:"get_bp_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
    // console.log(data);
    var jsonData = JSON.parse(data);
    
    var point=[];
    var bp=[];

    for (var i = 0; i <jsonData.length; i++) {
    var counter = jsonData[i];

    point = [parseFloat(jsonData[i].bp_time),parseFloat(jsonData[i].bp_start)];
    bp = [parseFloat(jsonData[i].bp_time),parseFloat(jsonData[i].bp_end)];
    if (i==0) {
      bpPoints1 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData[i].bp_time,jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      bpPoints1.push(point)
      bpPoints1.push(bp)
    }else if (i==1) {

      bpPoints2 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="14%" width="10%" src="down.png" />'] ];

      // bpPoints2.push(point)
      // bpPoints2.push(bp)
    }
    else if (i==3) {
      bpPoints3 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      // bpPoints3.push(point)
      // bpPoints3.push(bp)
    }else if (i==4) {
      bpPoints4 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      // bpPoints4.push(point)
      // bpPoints4.push(bp)
    }else if (i==5) {

      bpPoints5 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      // bpPoints5.push(point)
      // bpPoints5.push(bp)
    }else if (i==6) {
      bpPoints6 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      // bpPoints6.push(point)
      // bpPoints6.push(bp)
    }else if (i==7) {
      bpPoints7 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];
      
      // bpPoints7.push(point)
      // bpPoints7.push(bp)
    }else if (i==8) {

      bpPoints8 = [ [parseFloat(jsonData[i].bp_time),jsonData[i].bp_start, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [parseFloat(jsonData[i].bp_time),jsonData[i].bp_end, '<img height="15px" width="18px" src="down.png" />'] ];

      // bpPoints8.push(point)
      // bpPoints8.push(bp)
    }

    }
// console.log("malopa")
      plotBp();
  }
})


$.ajax({
  url:"get_temp_resp_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    // console.log(jsonData);
    for (var i = 0; i < jsonData.length; i++) {
      if (jsonData[i] !="" && jsonData[i] != "") {
          var counter = jsonData[i];
      }


        $("#temp_fill #"+counter.tr_time).html(counter.temp+"c");
        // $("#res_fill #"+counter.tr_time).html(counter.resp);

    }
  }
})

// get respiratory data
$.ajax({
  url:"get_resp_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    // console.log(jsonData);
    for (var i = 0; i < jsonData.length; i++) {
      if (jsonData[i] !="" && jsonData[i] != "") {
          var counter = jsonData[i];
      }

        // $("#temp_fill #"+counter.tr_time).html(counter.temp+"c");
        $("#res_fill #"+counter.resp_time).html(counter.resp);

    }
  }
})


$.ajax({
  url:"get_urine_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    for (var i = 0; i < jsonData.length; i++) {

      var counter = jsonData[i];
      
      $(".urine #pro"+counter.urine_time).html(counter.protein);
      // $("#urine #ace"+parseInt(counter.urine_time)).html(counter.acetone);
      // $("#urinev #vol"+parseInt(counter.urine_time)).html(counter.volume);
    }

  }
})

// get acetone
$.ajax({
  url:"get_acetone_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    for (var i = 0; i < jsonData.length; i++) {

      var counter = jsonData[i];
      
      $("#urine #ace"+parseInt(counter.acetone_time)).html(counter.acetone);
      
    }

  }
})

// get volume
$.ajax({
  url:"get_volume_data.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    for (var i = 0; i < jsonData.length; i++) {

      var counter = jsonData[i];
      
      $("#urinev #vol"+parseInt(counter.volume_time)).html(counter.volume);
    }

  }
})


// get pressure
$.ajax({
  url:"get_pressure.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){

    var jsonData = JSON.parse(data)
    for (var i = 0; i < jsonData.length; i++) {

      var counter = jsonData[i];
      
      $("#bpressure #bloodbp"+parseInt(counter.pressure_time)).html(counter.pressure);
    }

  }
})




$('#show_dialogp').dialog({
             autoOpen: false,
             modal: true,
             width: 550,
             height:300,
             title: 'Protein'
         });


         $('#show_dialoga').dialog({
                      autoOpen: false,
                      modal: true,
                      width: 550,
                      height:300,
                      title: 'Acetone'
                  });

                  $('#show_dialogv').dialog({
                               autoOpen: false,
                               modal: true,
                               width: 550,
                               height:300,
                               title: 'Acetone'
                           });

                           $('#show_dialogbp').dialog({
                               autoOpen: false,
                               modal: true,
                               width: 550,
                               height:300,
                               title: 'Blood Pressure'
                           });

                           $('#show_dialogt').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Temperature'
                                    });

                                    $('#show_dialogr').dialog({
                                                 autoOpen: false,
                                                 modal: true,
                                                 width: 550,
                                                 height:300,
                                                 title: 'Resp'
                                             });

                                             $('#show_dialogo').dialog({
                                                          autoOpen: false,
                                                          modal: true,
                                                          width: 550,
                                                          height:300,
                                                          title: 'Oxytocine'
                                                      });


                                                      $('#show_dialogd').dialog({
                                                                   autoOpen: false,
                                                                   modal: true,
                                                                   width: 550,
                                                                   height:300,
                                                                   title: 'Drops/Minute Pulse'
                                                               });


                                    $('#show_dialogc').dialog({
                                              autoOpen: false,
                                                  modal: true,
                                                  width:550,                                               height:300,
                                                  title: 'Contgraction in 10 min'
                                                });


                                                $('#show_dialogmedic').dialog({
                                                          autoOpen: false,
                                                              modal: true,
                                                              width:750,                         height:500,
                                                              title: 'Add Medicine'
                                                            });

                                          $('#show_dialogpulse').dialog({
                                                   autoOpen: false,
                                                   modal: true,
                                                   width: 550,
                                                   height:300,
                                                   title: 'Pulse'
                                               });

                                     $('#show_dialogbp').dialog({
                                              autoOpen: false,
                                              modal: true,
                                              width: 550,
                                              height:300,
                                              title: 'Blood Pressure'
                                      });

                                      $('#contraction-audit-dialog').dialog({
                                               autoOpen: false,
                                               modal:true,                      width: 950,
                                               height:500,
                                               title: 'Contraction Audit'
                                               });


                                    $('#oxytocine_drops-audit-dialog').dialog({
                                                    autoOpen: false,
                                                    modal:true,                 width: 950,
                                                    height:500,
                                                    title: 'oxytocine_dropss-audit-dialog Audit'
                                                    });

                                    $('#medicine-audit-dialog').dialog({
                                                autoOpen: false,
                                                modal:true,                 width: 950,
                                                height:500,
                                                title: 'medicine Audit'
                                                                    });

                                        $('#pulse_bp-audit-dialog').dialog({
                                                autoOpen: false,
                                                modal:true,                 width: 950,
                                                height:500,
                                                title: 'Pulse Bp Medicine '
                                          });



                  $('#temp_resp-audit-dialog').dialog({
                        autoOpen: false,
                        modal:true,
                        width: 950,
                        height:500,
                        title: 'Temp   '
                                                                                                });


                                                                                                $('#temp_resp-audit-dialog-volume').dialog({
                                                                                                      autoOpen: false,
                      modal:true,
                      width: 950,
                      height:500,
                      title: 'Urine  '
              });
                                                                                                $('#volume_audit_dialog_one').dialog({
                                                                                                autoOpen: false,
                modal:true,
                width: 950,
                height:500,
                title: 'Urine'
              });


plotPulseUrine();

mytitle = $('<div class="my-jqplot-title" style="position:absolute;text-align:center;padding-top: 15px;width:100%">My Custom Chart Title</div>').insertAfter('.jqplot-grid-canvas');

var plot3 = $.jqplot('chart1', [powPoints1],
  {

axes:{
  xaxis:{
    max:24,
    min:0,
    tickInterval:.5,
    label:"Hour Time"
  },
  yaxis:{

    max:5,
    min:0,
    tickInterval:1,
    label:" Contraction per 1o mins ",
    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
    tickOptions: {
                    angle: 180
                }
  },

},
grid: {
            drawBorder: false,
            shadow: false,
            // background: 'rgba(0,0,0,0)'  works to make transparent.
            background: 'white'
        },
highlighter: {
      show: true,
      sizeAdjust: 10.5
    },
legend:{
  show: true,
  placement: 'outsideGrid',
  location: 'ne',
  rowSpacing: '0px'
},
    cursor: {
      show: false
    },

    // Set default options on all series, turn on smoothing.
    seriesDefaults: {
        rendererOptions: {
            smooth: true
        }
    },
    // Series options are specified as an array of objects, one object
    // for each series.
    series:[
        {
          // Change our line width and use a diamond shaped marker.

          lineWidth:3,
          markerOptions: { style:'x' }
        },
        {
          // Don't show a line, just show markers.
          // Make the markers 7 pixels with an 'x' style
          showLine:true,
          markerOptions: { size: 7, style:"circle" }
        },
        {
          // Use (open) circlular markers.
          showLine:true,
          lineWidth:5,
          lineColor:"#000",
          markerOptions: { sizi:7,style:"circle" }
        },
        {
          // Use a thicker, 5 pixel line and 10 pixel
          // filled square markers.
          showLine:true,
          lineWidth:3,
          markerOptions: {size:10 }
        },


    ]
  }
);

});

var patient_id = $("#patient_id").val();
var admission_id = $("#admission_id").val();

$("#add_contraction").click(function(e){
  e.preventDefault();

  $.ajax({
    type:"POST",
    url:"add_contraction.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      console.log(data)
    }
  })

})

$("#add_drops").click(function(e){
  e.preventDefault();

  $.ajax({
    type:"POST",
    url:"add_drops.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      console.log(data)
    }
  })
})

$("#add_pulse").click(function(e){
  e.preventDefault();

  var pulse = $("#pulse_no").val();
  var pulse_time = $("#pulse_time").val()
  var point=[];
  var p=  [];
  $.ajax({
    type:"POST",
    url:"add_pulse.php",
    data:{patient_id:patient_id,admission_id:admission_id,pulse:pulse,
      pulse_time:pulse_time},
    success:function(data){

      // console.log(data)
      var pa = JSON.parse(data);

      point = [parseFloat(pa.x),parseFloat(pa.y)];

      // console.log(point);

      pulsePoints1.push(point)
      plotPulseUrine();

      $("#show_dialogpulse").dialog("close");
  }
  })


})

$("#add_medicine").click(function(e){
  e.preventDefault();

  // $.ajax({
  //   type:"POST",
  //   url:"add_medicine.php",
  //   data:{patient_id:patient_id,admission_id:admission_id},
  //   success:function(data){
  //     alert(data)
  //   }
  // })

$("#show_dialogmedic").dialog("open");

})

$("#add_temp").click(function(e){
  e.preventDefault();

  $.ajax({
    type:"POST",
    url:"add_temp.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      console.log(data)
    }
  })

})

$("#add_prot").click(function(e){
  e.preventDefault();

  $.ajax({
    type:"POST",
    url:"add_protein.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      console.log(data)
    }
  })

})

// start dialog
$("#protein").click(function(e){
  e.preventDefault();
$("#show_dialogp").dialog("open");
  // alert("preotein")
})
$("#bp").click(function(e){
  e.preventDefault();
$("#show_dialogbp").dialog("open");
  // alert("preotein")
})


$("#acetone").click(function(e){
  e.preventDefault();

  $('#show_dialoga').dialog("open")
})


$("#volume").click(function(e){
  e.preventDefault();
  $('#show_dialogv').dialog("open")
})

$("#pressurebp").click(function(e){
  e.preventDefault();
  $('#show_dialogbp').dialog("open")
})


$("#temperature").click(function(e){
  e.preventDefault();
    $('#show_dialogt').dialog("open")

})


$("#resp").click(function(e){
  e.preventDefault();
  $('#show_dialogr').dialog("open")

})


$("#oxyticin").click(function(e){
  e.preventDefault();
  $('#show_dialogo').dialog("open");
})


$("#drop").click(function(e){
  e.preventDefault();
    $('#show_dialogd').dialog("open");
})
$("#caput_open").click(function(e){
  e.preventDefault();
    $('#show_dialog_caput').dialog("open");
})
$("#thin_open").click(function(e){
  e.preventDefault();
    $('#show_dialog_thin').dialog("open");
})
$("#presenting_open").click(function(e){
  e.preventDefault();
    $('#show_dialog_presenting').dialog("open");
})
$("#soft_open").click(function(e){
  e.preventDefault();
    $('#show_dialog_soft').dialog("open");
})
$("#position_open").click(function(e){
  e.preventDefault();
    $('#show_dialog_position').dialog("open");
})
$("#contraction").click(function(e){
  e.preventDefault();
  $('#show_dialogc').dialog("open");
})

$("#savebp").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timebp").val();
  var bp_remark = $("#bp_remark").val();
  // alert(time)
  $.ajax({
    url:"add_pressure.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      bp:bp_remark,time:time},
    success:function(data){
      $(".bpressure #bloodbp"+time).html(bp_remark);
    $("#show_dialogbp").dialog("close");
    }
})
})

$("#savep").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timepro").val();
  var protein_remark = $("#protein_remark").val();
  // alert(time)
  $.ajax({
    url:"add_protein.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      protein:protein_remark,time:time},
    success:function(data){


      // var jsonData = JSON.parse(data)
      // console.log(data);
      $(".urine #pro"+time).html(protein_remark);


    $("#show_dialogp").dialog("close");
    }
})
})



$("#savea").click(function(e){

  e.preventDefault();
  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timea").val();
  var acetone_remark = $("#acetone_remark").val();


  $.ajax({
    url:"add_acetone.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      acetone:acetone_remark,time:time},
    success:function(data){


      // var jsonData = JSON.parse(data)
      console.log(data);
      $("#urine #ace"+time).html(acetone_remark);


    $("#show_dialoga").dialog("close");
    }
})




})

$("#savev").click(function(e){
  e.preventDefault();
  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var time = $("#show-timev").val();
  var volume_remark = $("#volume_remark").val();
  $.ajax({
    url:"add_volume.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      volume:volume_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      $("#urinev #vol"+time).html(volume_remark);
      // console.log(data);
      // $("#urine #ace"+time).html(acetone_remark);
    $("#show_dialogv").dialog("close");
    }
})
})

// $("#savev").click(function(e){
//   e.preventDefault();
//   var patient_id = $("#patient_id").val();
//   var admission_id = $("#admission_id").val();
//   var time = $("#show-timev").val();
//   var volume_remark = $("#volume_remark").val();
//   $.ajax({
//     url:"add_pressure.php",
//     type:"POST",
//     data:{patient_id:patient_id,admission_id:admission_id,
//       volume:volume_remark,time:time},
//     success:function(data){
//       // var jsonData = JSON.parse(data)
//       $("#urinev #vol"+time).html(volume_remark);
//       // console.log(data);
//       // $("#urine #ace"+time).html(acetone_remark);
//     $("#show_dialogv").dialog("close");
//     }
// })
// })



$("#savet").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timet").val();
  var temp_remark = $("#temp_remark").val();



  $.ajax({
    url:"add_temp.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      temp:temp_remark,time:time},
    success:function(data){


      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#temp_fill #"+time).html(temp_remark);

      $("#show_dialogr").dialog("close");
    }
})


  $("#show_dialogt").dialog("close");
})



$("#saver").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timer").val();
  var res_remark = $("#res_remark").val();

  $("#res_fill #"+time).html(res_remark);


  $.ajax({
    url:"save_respiratory.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      resp:res_remark,time:time},
    success:function(data){


      // var jsonData = JSON.parse(data)
      // console.log(data);

      $("#show_dialogr").dialog("close");
      console.log("saved")
    }


  })


})




$("#saveo").click(function(e){
  e.preventDefault();


  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#show-timeo").val();
  var oxytocine_remark = $("#oxytocine_remark").val();
  // alert(time + oxytocine_remark )

  $.ajax({
    url:"add_oxytocine.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      oxytocine:oxytocine_remark,time:time},
    success:function(data){

      // console.log(data);
      $("#oxytocine_fill #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(oxytocine_remark);


    $("#show_dialogo").dialog("close");
    }
})


})

$("#saved").click(function(e){
  e.preventDefault();

  var time = $("#show-timed").val();
  var drop_remark = $("#drop_remark").val();
  // alert(time + drop_remark )
  // $("#drop_fill #"+time).html(drop_remark);

  $.ajax({
    url:"add_drops.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      drops:drop_remark,time:time},
    success:function(data){

      // console.log(data);
      $("#drop_fill #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(drop_remark);


    $("#show_dialogd").dialog("close");
    }
})


})




$("#savec").click(function(e){
  e.preventDefault();

  var time = $(".show-timec").val();
  // alert(time)
    var contraction_per_min = $(".contraction_per_min:checked").val();
    // console.log(contraction_per_min)
    $.ajax({
      url:"add_contraction.php",
      type:"POST",
      data:{patient_id:patient_id,admission_id:admission_id,
        contraction:contraction_per_min,time:time},
      success:function(data){

        // console.log(data);

        if (contraction_per_min >=1 && contraction_per_min <= 2) {
          $("#two #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'  style='height:30px;'>"+
            "<img src='images/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

            $("#one #"+time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
              "<div class='image'>"+
              "<img src='images/dot.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

        }else if(contraction_per_min >2 && contraction_per_min <= 4){

          $("#four #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

          $("#three #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

          $("#two #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

            $("#one #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
              "<div class='image'>"+
              "<img src='images/slash.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

        }else if (contraction_per_min >4 && contraction_per_min <= 6) {

          $("#five #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");


          $("#four #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

          $("#three #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

          $("#two #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
            "<div class='image'>"+
            "<img src='images/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");

            $("#one #"+time.replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(
              "<div class='image'>"+
              "<img src='images/full_black.png' width='100%;' height='100%;' padding='0px;' margin='0px;'></div>");
              
        }


        $("#show_dialogc").dialog("close");




      }
  })





})


$("#pulsed").click(function(e){
    e.preventDefault()
      $('#show_dialogpulse').dialog("open");

})

var medicine = [];
var med_id = [];
$("#add_officeial_name").click(function(e){

  var official_med_name = $(".official_med_name option:selected").text();
  // $( "#myselect option:selected" ).text();
  var official_med_id = $(".official_med_name").val();
  medicine.push(official_med_name)
  med_id.push(official_med_id)

var num = 1;
var l;
var med
    for (var i = 0; i < medicine.length; i++) {
      l=medicine.length
      var med = medicine[(l-1)];

    }
    $.ajax({
      type:"POST",
      url:"add_medicine.php",
      data:{patient_id:patient_id,admission_id:admission_id,med:medicine},
      success:function(data){
// console.log(data)
          $("#added_medicine tbody").append("<tr style='height:27px;padding-left:5px;'><td>"+(l)+"</td><td>"+med+"</td></tr>")

      }
    })

// console.log(medicine);
})

$("#savemedic").click(function(e){
    e.preventDefault()
    var time = $(".show-timemedic").val()
    var medicine_val = $("#medicine_val").val();
    // alert(medicine_val)
    console.log(medicine_val);
    $.ajax({
      url:"add_medicine.php",
      type:"POST",
      data:{patient_id:patient_id,admission_id:admission_id,
        med_name:medicine_val,medicine:med_id,time:time},
      success:function(data){
        // alert(data)
        console.log(data);
      }
    })
    console.log(time)
    $("#med ._"+time).html(medicine_val)
      $('#show_dialogmedic').dialog("close");

})




$("#add_bp").click(function(e){
    e.preventDefault()
    $('#show_dialogbp').dialog("open");
})

$("#add_bp_no").click(function(e){
    e.preventDefault()

    var bp_start = $("#bp_start").val();
    var bp_end = $("#bp_end").val();
    var time = $("#bp_time").val();


  // if(validateBpTime(time)){




    d1=[];
    d2=[];
    $.ajax({
      url:"add_bp.php",
      type:"POST",
      data:{bp_start:bp_start,bp_end:bp_end,patient_id:patient_id,
        admission_id:admission_id,time:time},
      success:function(data){

        var jsonData = JSON.parse(data);
          d1=[ parseInt(jsonData.t) ,parseInt(jsonData.x)];
          d2=[ parseInt(jsonData.t) ,parseInt(jsonData.y)];

        if (( bpPoints1=="") ) {

          bpPoints1 = [[jsonData.t,jsonData.x, '<img height="15px" width="15px" src="up.png"/>'], [jsonData.t,jsonData.y, '<img height="30px" width="15px" src="down.png"/>']];
          // bpPoints1.push(d1)
          // bpPoints1.push(d2)
          d1=[];
          d2=[];
        }

        else if (
          (bpPoints1.length > 0) && (bpPoints2.length==1)  )
         {

           bpPoints2 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];

          // bpPoints2.push(d1);
          // bpPoints2.push(d2);
          // // console.log(bpPoints2)
          d1=[];
          d2=[];
        }

        else if (
          (bpPoints2.length > 0) && (bpPoints3.length==1)
        ) {

          bpPoints3 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];

          // bpPoints3.push(d1)
          // bpPoints3.push(d2)
          d1=[];
          d2=[];
        }

        else if (
           (bpPoints3.length > 0) && (bpPoints4.length==1)
         ) {

           bpPoints4 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];

           // bpPoints4.push(d1)
           // bpPoints4.push(d2)
           d1=[];
           d2=[];
         }

         else if (
            (bpPoints4.length > 0) && (bpPoints5.length==1)
          ) {

            bpPoints5 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];


            // bpPoints5.push(d1)
            // bpPoints5.push(d2)
            d1=[];
            d2=[];
          }

          else if (
             (bpPoints5.length > 0) && (bpPoints6.length==1)
           ) {

             bpPoints6 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];

             // bpPoints6.push(d1)
             // bpPoints6.push(d2)
             d1=[];
             d2=[];
           }

           else if (
              (bpPoints6.length > 0) && (bpPoints7.length==1)
            ) {

              bpPoints7 = [ [jsonData.t,jsonData.x, '<img height="15px" width="15px" src="juu.png" style=" transform: rotate(90deg);" />'], [jsonData.t,jsonData.y, '<img height="15px" width="18px" src="down.png" />'] ];

              // bpPoints7.push(d1)
              // bpPoints7.push(d2)
              d1=[];
              d2=[];
            }


        plotBp();
      }
    })

  // }else {
  //   alert("Time not Correct")
  //   return false
  // }
    $('#show_dialogbp').dialog("close");
})


function validateBpTime(time){

  var result;
  $.ajax({

    url:"validate_bp_time.php",
    type:"POST",
     async: false,
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      if (parseInt(time) > parseInt(data)) {

        result = true;
      }else {
        result = false;
      }


    }
  })

return result;
}



$("#contraction_audit").click(function(e){
  e.preventDefault();

  $.ajax({
    url:"get_contraction.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      // console.log(data)
      $("#contraction-audit-dialog").html(data);
      $("#contraction-audit-dialog").dialog("open");
    }
  })
})


$("#oxytocine_audit").click(function(e){
  e.preventDefault();

  $.ajax({
    url:"get_oxytocine_drops.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      // console.log(data);
      $("#oxytocine_drops-audit-dialog").html(data);
      $("#oxytocine_drops-audit-dialog").dialog("open");
    }
  })


})



$("#pulse_bp_audit").click(function(e){
  e.preventDefault();

  $.ajax({
    url:"get_pulse_bp.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      $("#pulse_bp-audit-dialog").html(data);
      $("#pulse_bp-audit-dialog").dialog("open");
    }
  })
})

$("#resp_temp_audit").click(function(e){
  e.preventDefault();

  $.ajax({
    url:"get_temp_resp.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      // console.log(data);
      $("#temp_resp-audit-dialog").html(data);
      $("#temp_resp-audit-dialog").dialog("open");
    }
  })
})




$("#volume_audit").click(function(e){
  e.preventDefault();

  $.ajax({
    url:"get_urine.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      // console.log(data);
      // $("#volume-audit-dialog").html(data);
      $("#temp_resp-audit-dialog-volume").html(data);
      $("#temp_resp-audit-dialog-volume").dialog("open");
    }
  })

})
function save_labour(){
    var Registration_ID=$("#patient_id").val();
    var admission_id=$("#admission_id").val();
    var consultation_id=$("#consultation_id").val();
    var date_birth=$("#date_birth").val();
    var weight=$("#weight").val();
    var sex=$("#sex").val();
    var apgar=$("#apgar").val();
    var method_delivery=$("#method_delivery").val();
    var method_resuscition=$("#method_resuscition").val();
    var first_stage=$("#first_stage").val();
    var second_stage=$("#second_stage").val();
    // var second_stage=$("#second_stage").val();
    var third_stage=$("#third_stage").val();
    var fourth_stage=$("#fourth_stage").val();
    var placenta_membrane=$("#placenta_membrane").val();
    var blood_loss=$("#blood_loss").val();
    var reason_pph=$("#reason_pph").val();
    var perineum=$("#perineum").val();
    var repair_by=$("#repair_by").val();
    var delivery_by=$("#delivery_by").val();
    var supervision_by=$(".supervision_by").val();
    var supervision_by=$("#supervision_by").val();
    // alert(supervision_by);
    if(date_birth ==''){
      $("#date_birth").css("border","2px solid red");
      return false;
    }
    if(sex ==''){
      $("#sex").css("border","2px solid red");
      return false;
    }
    if(method_delivery==''){
      $("#method_delivery").css("border","2px solid red");
      return false;
    }
    if(method_resuscition==''){
      $("#method_resuscition").css("border","2px solid red");
      return false;
    }
    if(first_stage==''){
      $("#first_stage").css("border","2px solid red");
      return false;
    }
    if(second_stage==''){
      $("#second_stage").css("border","2px solid red");
      return false;
    }
    if(third_stage==''){
      $("#third_stage").css("border","2px solid red");
      return false;
    }
    if(fourth_stage==''){
      $("#fourth_stage").css("border","2px solid red");
      return false;
    }
    if(blood_loss==''){
      $("#blood_loss").css("border","2px solid red");
      return false;
    }
    if(confirm("Are You Sure You want To Save ")){
    $.ajax({
      url:'save_summary_of_labour.php',
      type:'POST',
      data:{
        Registration_ID:Registration_ID,
        admission_id:admission_id,
        consultation_id:consultation_id,
        // date_birth:date_birth,
        // weight:weight,
        // sex:sex,
        // apgar:apgar,
        // method_delivery:method_delivery,
        first_stage:first_stage,
        second_stage:second_stage,
        third_stage:third_stage,
        fourth_stage:fourth_stage,
        placenta_membrane:placenta_membrane,
        blood_loss:blood_loss,
        reason_pph:reason_pph,
        perineum:perineum,
        repair_by:repair_by,
        delivery_by:delivery_by,
        supervision_by:supervision_by
      },
      success:function(response){
        // alert(response);
        // $("#date_birth").css("border","1px solid black");
        // $("#sex").css("border","1px solid black");
        // $("#method_delivery").css("border","1px solid black");
        // $("#method_resuscition").css("border","1px solid black");
        $("#first_stage").css("border","1px solid black");
        $("#second_stage").css("border","1px solid black");
        $("#third_stage").css("border","1px solid black");
        $("#fourth_stage").css("border","1px solid black");
        $("#blood_loss").css("border","1px solid black");
        $("#supervision_by").css("border","1px solid black");
        $("#supervision_by").css("border","1px solid black");
        $("#delivery_by").css("border","1px solid black");
        $("#repair_by").css("border","1px solid black");
        $("#perineum").css("border","1px solid black");
        $("#reason_pph").css("border","1px solid black");
        $("#placenta_membrane").css("border","1px solid black");
        // $("#apgar").css("border","1px solid black");
        // $("#weight").css("border","1px solid black");

        $("#date_birth").val("");
        $("#sex").val("");
        $("#method_delivery").val("");
        $("#method_resuscition").val("");
        $("#first_stage").val("");
        $("#second_stage").val("");
        $("#third_stage").val("");
        $("#fourth_stage").val("");
        $("#blood_loss").val("");
        $("#supervision_by").val("");
        $("#supervision_by").val("");
        $("#delivery_by").val("");
        $("#repair_by").val("");
        $("#perineum").val("");
        $("#reason_pph").val("");
        $("#placenta_membrane").val("");
        $("#apgar").val("");
        $("#weight").val("");
      }

    });
  }
  }

$("#medicine_audit").click(function(e){
  e.preventDefault();

  $.ajax({

    url:"get_medicine.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      // alert(data)
      // console.log(data);
      // var jsonData = JSON.parse(data)
      // console.log(jsonData);


// var header = "<table width='98%'>"+
//       "<tr><th style='height:25px;''>Time</th>"+
//       "<th style='height:25px;'>Medicine Short Name</th>"+
//       "<th>Medicine Name</th><th style='height:25px;'>Actual Time</th>"+
//       "<th style='height:25px;'>Prepared By</th></tr><tr>";



//        for (var i = 0; i < jsonData.length; i++) {
//           var counter = jsonData[i];
// var content +="<td style='height:30px;'>"+data[i].medicine_time+"</td><td style='height:30px;'>"+ jsonData[i].nick_name+"</td><td style='height:30px;'>"+jsonData[i].actual_time+"</td><td style='height:30px;'>"+jsonData[i].product_name+"</td><td style='height:30px;'>"+jsonData[i].prepared_by+"</td></tr>"
// }


// var footer = "</table>");

        $("#medicine-audit-dialog").html(data);


            $("#medicine-audit-dialog").dialog("open");
    }

  })


})

</script>

