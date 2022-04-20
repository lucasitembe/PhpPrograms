<?php
 include('header.php');
 include('../includes/connection.php');
 if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
   $registration_id = $_GET['Registration_ID'];
   $admission_id = $_GET['Admision_ID'];
   $consultation_id = $_GET['consultation_ID'];

  }

  ?>

 <a href="neonatal_record.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

 <style media="screen">
   table{
     border-collapse: collapse;
     border: none !important;
   }
   table, tr, td{
     border:none !important;
   }

   .label{
     color:black !important;
     float: right;
     text-align: right !important ;
   }
   .input-label{
     text-align:right !important;
   }
   .input{
     width: 300px !important;
   }
   .title{
     border-bottom: 1px solid black !important;
   }
 </style>
  <center>
    <fieldset>
      <legend align=center style="font-weight:bold">ASESSMENT</legend>
      <center>
        <form class="" id="myform" method="post">


      <table style="width:100%">
        <tr>
          <td style="background:#fff" rowspan='2'>Observations</td>
          <td class="input-label">Weight</td>
          <td><input type="text" name="weight" placeholder="Weight" value=""> </td>
          <td class="input-label">Length</td>
          <td><input type="text" name="length" placeholder="Length" value=""> </td>
          <td class="input-label">Head Circumference</td>
          <td><input type="text" name="head_circumference" value=""> </td>
        </tr>

        <tr>
          <td class="input-label">Temperature</td>
          <td><input type="text" name="temperature" placeholder="Temperature" value=""> </td>
          <td class="input-label">Heart Rate</td>
          <td><input type="text" name="heart_rate"  placeholder="Heart Rate"value=""> </td>
          <td class="input-label">Respiration Rate</td>
          <td><input type="text" name="respiration_rate"  placeholder="Respiration Rate" value=""> </td>
        </tr>


        <tr>
          <td>First Urine Passed</td>
          <td class="input-label">Date And Time</td>
          <td ><input type="text" name="date_and_urine" placeholder="Date And Time" value="" class="date"> </td>
          <td class="input-label">Signature</td>
          <td><input type="text" name="Signature_urine" placeholder="Signature" value=""> </td>

         </tr>
        <tr>
          <td>First Meconium Passed</td>
          <td class="input-label">Date And Time</td>
          <td ><input type="text" name="date_and_time_meconium" placeholder="Date And Time" value="" class="date"> </td>
          <td class="input-label">Signature</td>
          <td class="input-label">
            <input type="text" name="signature_meconium" placeholder="signature" value=""> </td>

        </tr>
       </table>

       <table width="100%">
         <thead>
           <th colspan='10'>Tick As Appropriate</th>
         </thead>
         <tbody>
           <tr>
               <td rowspan='5'>HEAD</td>

           </tr>
           <tr >
             <td>Shape</td>
             <td class="input-label">Normal</td><td> <input type="checkbox" name="normal_shape_head" value="normal"> </td>

             <td class="input-label">Asymmetrical </td><td><input type="checkbox" name="asymmetrical_shape_head" value="asymmetrical"> </td>
             <td class="input-label">Caput succedanem</td><td> <input type="checkbox" name="caput_succedanem" value="caput_succedanem"> </td>
             <td class="input-label">Cephalohaematoma</td><td> <input type="checkbox" name="cephalohaematoma" value="cephalohaematoma"> </td>
           </tr>

           <tr>
             <td>Fontanels</td>
             <td class="input-label">Normal </td> <td><input type="checkbox" name="normal_fontanels" value="normal"> </td>
             <td class="input-label">Sunken </td> <td><input type="checkbox" name="sunken_fontanels" value="sunken"> </td>
             <td class="input-label">Bulging</td> <td> <input type="checkbox" name="bulging" value="bulging"> </td>
           </tr>

           <tr>
             <td>Sutures</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_suture" value="normal"> </td>
             <td class="input-label">Moulding </td><td><input type="checkbox" name="mouldng" value="mouldng"> </td>
           </tr>
           <tr class="title">
             <td>Skin Injuries</td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_skin" value="no"> </td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_skin" value="yes"> </td>
           </tr>

           <tr>
             <td rowspan="5">FACE</td>
           </tr>
           <tr >
             <td>Shape</td>
             <td class="input-label">Symmetrical</td> <td><input type="checkbox" name="symetrical_shape_face" value="symetric"> </td>
             <td class="input-label">Asymmetrical</td> <td><input type="checkbox" name="asymetric_shape_face" value="asymetric"> </td>
           </tr>
           <tr>
             <td>Eye Discharged</td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_eye" value="no"> </td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_eye" value="yes"> </td>
           </tr>
           <tr>
             <td>Nose Discharged</td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_nose" value="no"> </td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_nose" value="yes"> </td>
           </tr>
           <tr class="title">
             <td>Mouth</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_mouth" value="normal"> </td>
             <td class="input-label">Cleft Palete</td> <td><input type="checkbox" name="cleft_palete" value="cleft_palete"> </td>
           </tr>


           <tr>
             <td rowspan="3">THORAX</td>
           </tr>
           <tr >
             <td>Shape</td>
             <td class="input-label">symetric</td> <td><input type="checkbox" name="symetric_shape_thorax" value="symetric">
              </td>
                <td class="input-label">Asymetric</td> <td><input type="checkbox" name="asymetric_shape_thorax" value="asymetric"></td>
                  <td class="input-label">Rib Retraction</td> <td><input type="checkbox" name="rib_retraction" value="rib_retraction"></td>
                  <td class="input-label">Stern Retraction</td> <td><input type="checkbox" name="stern_retraction" value="stern_retraction"></td>
           </tr>
           <tr class="title">
             <td>Respiration</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_respiration" value="normal"></td>
             <td class="input-label">Difficult</td> <td><input type="checkbox" name="difficult" value="difficult"></td>
             <td class="input-label">Strider</td> <td><input type="checkbox" name="strider" value="strider"></td>
             <td class="input-label">Grunting</td> <td><input type="checkbox" name="grunting" value="grunting"></td>
           </tr>

           <tr>
             <td rowspan="3">Abdomen</td>
           </tr>
           <tr >
             <td>Shape</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_shape_abdomen" value="normal"></td>
             <td class="input-label">Distened</td> <td><input type="checkbox" name="distened" value="distened"></td>
             <td class="input-label">Sunken</td> <td><input type="checkbox" name="sunken" value="sunken"></td>
             <td class="input-label">Visible Peristalisi</td> <td><input type="checkbox" name="visible_peristalisis" value="visible_peristalisis"></td>
           </tr>
           <tr class="title">
             <td>Umbilical Cord</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_umblicord" value="normal"></td>
             <td class="input-label">Bleeding</td> <td><input type="checkbox" name="bleeding" value="bleeding"></td>
           </tr>
           <tr>
             <td rowspan="3">LIMBS</td>
           </tr>
           <tr >
             <td>Upper</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_upper_limb" value="normal"></td>
             <td class="input-label">Abnormal</td> <td><input type="checkbox" name="abdomal_upper_limb" value="abdomal"></td>
           </tr>
           <tr class="title">
             <td>Lower</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_lower_limb" value="normal"></td>
             <td class="input-label">Abdomal</td> <td><input type="checkbox" name="abdomal_lower_limb" value="abdomal"></td>
           </tr>
           <tr>
             <td rowspan="2">Spinal Column</td>
           </tr>
           <tr class="title">
               <td></td>
               <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_spinal_column" value="normal"></td>
               <td class="input-label">Spina Bifida</td> <td><input type="checkbox" name="spina_benifida" value="spina_benifida"></td>
               <td class="input-label">Meningocele</td> <td><input type="checkbox" name="meningocele" value="meningocele"></td>
           </tr>
           <tr>
             <td rowspan="3">UNUS</td>
           </tr>
           <tr >
             <td></td>
             <td class="input-label">Present</td> <td><input type="checkbox" name="present_unus" value="present"></td>
             <td class="input-label">Absent</td> <td><input type="checkbox" name="absent_unus" value="absent"></td>
           </tr>
           <tr class="title">
             <td>Perforated</td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_perforated" value="yes"></td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_perforated" value="no"></td>
           </tr>
           <tr>
             <td rowspan="3">GENITALS</td>
           </tr>
           <tr>
             <td >Boy</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_boy_genital" value="normal"></td>
             <td class="input-label">Abnormal</td> <td><input type="checkbox" name="abdomal_boy_genetal" value="abdomal"></td>
           </tr>
           <tr class="title">
             <td >Girl</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_girl_genital" value="normal"></td>
             <td class="input-label">Abnormal</td> <td><input type="checkbox" name="abdomal_girl_genital" value="abdomal"></td>
           </tr>
           <tr class="title">
             <td class="title" rowspan="5">NEURALOGICAL</td>
           </tr>
           <tr >
             <td >Grip Reflex</td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_grip_reflex" value="yes"></td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_grip_reflex" value="no"></td>

           </tr>
           <tr >
             <td >Moro Reflix</td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_moro_reflex" value="yes"></td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_moro_reflex" value="no"></td>
           </tr>
           <tr >
             <td >Suck Reflex</td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_suck_refelex" value="yes"></td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_suck_reflex" value="no"></td>
           </tr>
           <tr >
             <td >Muscle Tone</td>
             <td class="input-label">Yes</td> <td><input type="checkbox" name="yes_muscle_tone" value="yes"></td>
             <td class="input-label">No</td> <td><input type="checkbox" name="no_muscle_tone" value="no"></td>
           </tr>

           <tr class="title">
             <td rowspan="3">SKIN</td>
           </tr>
           <tr >
             <td>Colour</td>
             <td class="input-label">Normal</td> <td><input type="checkbox" name="normal_colour_skin" value="normal"></td>
             <td class="input-label">Pale</td> <td><input type="checkbox" name="pale" value="pale"></td>
             <td class="input-label">Juandice</td> <td><input type="checkbox" name="Juandice" value="Juandice"></td>
             <td class="input-label">Cynosis</td> <td><input type="checkbox" name="cynosis" value="cynosis"></td>
           </tr>
           <tr class="title">
             <td>Condition</td>
             <td class="input-label">Intact</td> <td><input type="checkbox" name="intact" value="intact"></td>
             <td class="input-label">Rash</td> <td><input type="checkbox" name="rash" value="rash"></td>
             <td class="input-label">Broken</td> <td><input type="checkbox" name="broken" value="broken"></td>
             <td class="input-label">Bruised</td> <td><input type="checkbox" name="bruised" value="bruised"></td>
           </tr>

           <tr>
             <td class="input-label"><b>Assessed By</b></td>
             <td><input type="text" name="assessed_by" value=""> </td>
             <td class="input-label"><b>Date And Time</b></td>
             <td><input type="text" name="data_time_assessed"  value="" class="date"> </td>
           </tr>
         </tbody>
       </table>
       <table width="100%;">
         <thead>
           <th colspan="4">IDENTIFICATION</th>
           <tbody>
             <tr>
               <td class="input-label">Signature:Mother</td>
               <td><input type="text" name="signature_mother" value=""> </td>
             <!-- </tr>
             <tr> -->
               <td class="input-label">Signature:Nurse</td>
               <td><input type="text" name="signature_nurse" value=""> </td>
             </tr>
             <tr>
               <td class="input-label">Date And Time</td>
               <td><input type="text" name="date_time_mother" value="" class="date"> </td>
               <td class="input-label">Date And Time</td>
               <td><input type="text" name="date_time_nurse" value=""> </td>
             </tr>
           </tbody>
         </thead>
       </table>
       <div class="">
         <input type="submit" class="art-button-green" name="submit" value="Save">
         <input type="submit" class="art-button-green" name="preview" value="Preview">
       </div>
       </form>
     </center>
    </fieldset>
   </center>



<script src="../css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

$("#myform").submit(function(e){
  e.preventDefault()
  var form_data=$("#myform").serialize();

  alert(form_data)
  $.ajax({
    url:"save_assessment.php",
    type:"POST",
    data:form_data,
    success:function(data){
      alert(data)
    }
  })

})


$(document).ready(function(e){

  $('.date').datetimepicker({value: '', step: 2});

})
</script>
