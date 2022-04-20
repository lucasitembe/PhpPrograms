<?php
include('header.php');
include('../includes/connection.php');
if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admision_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}

 ?>

<a href="neonatal_record.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>
  &Admision_ID=<?=$admision_id?>" class="art-button-green">BACK</a>


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
    font-size: 1em;
    letter-spacing: 0.5px;
    text-align: right !important ;
  }
  .input-label{
    /* width:  !important; */
  }
  .input{
    width: 350px !important;
  }
</style>
 <center>
   <fieldset>
     <legend align=center style="font-weight:bold">INFANT LABOUR ROOM ADMISSION RECORD TO NEONATAL WARD</legend>
     <form class="" id="myform"  method="post">
     <table>
       <tr>
         <td class="label">Baby Of:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="Baby Of" class="input-label" name="baby_of">
         </td>
         <td class="label">
           Address
         </td>
           <td class="input">
             <input type="text" class="form-control" name="address" placeholder="Address" class="input-label">
         </td>
         <td class="label">Foetal Heart Beas At Delivery</td>
         <td class="input">
           <input type="text" class="form-control" placeholder="Foetal Heart Beas At Delivery" name="foetal_heart_rate_at_delivery" class="input-label">
       </td>
       </tr>
       <tr>
         <td class="label">

           Religion:</td>
           <td class="input">
             <input type="text" class="form-control" name="religion" placeholder="Religion" class="input-label">

         </td>
         <td class="label">Tribe</td>
         <td class="input">
           <input type="text" name="tribe" class="form-control" placeholder="Tribe" class="input-label">
       </td>
       <td class="label">Indication</td>
       <td class="input">
         <input type="text" name="indication" class="form-control" placeholder="Indication" class="input-label">
     </td>
       </tr>
       <tr>
         <td class="label">
          Ten Cell Leader</td>
           <td class="input">
             <input type="text" name="ten_cell_leader" class="form-control" placeholder="Ten cell leader" class="input-label">

         </td>
         <td class="label">Case File No</td>
          <td class="input">
             <input type="text" name="case_file_no" class="form-control" placeholder="Case File Number" class="input-label">
       </td>
       <td class="label">Type Of Delivery</td>
        <td class="input">
          <input type="text" name="type_of_delivery" class="form-control" placeholder="Type Of Delivery" class="input-label">
     </td>

       </tr>
       <tr>
         <td class="label">
           Date And Time Of Birth:</td>
           <td class="input">
             <input type="text" name="date_and_time_of_birth" class="form-control input-label date" placeholder="Date And Time Of Birth" >

         </td>
         <td class="label">SEX:</td>
         <td class="label">SEX:</td>
           <td class="input">
             <select class="gender" name="sex" style="height:30px; width:100%;">
               <option value="">--Select Gender--</option>
               <option value="ME">Male</option>
               <option value="FE">Female</option>
             </select>

       </td>
       <td class="label">
         Matenal Disease And Complication
       </td>
       <td>
         <input type="text" name="matenal_disease_and_contraction" class="form-control" placeholder="Matenal Diseases And Contraction" class="input-label">
     </td>
       <td></td>
       </tr>
       <tr>
         <td class="label">
           Weight At Birth:</td>
           <td>
             <input type="text" name="weight_at_birth" class="form-control" placeholder="Weight At Birth" class="input-label">
         </td>
         <td class="label">Apga Score:</td>
           <td class="input">
             <input type="text" name="apga_score" class="form-control" placeholder="Apga Score" class="input-label">
       </td>
       <td class="label">Ante-Natal Care:</td>
         <td class="input">
           <label><span><input type="radio"  class="input-label" name='antenatalcare' value="attended">Attended</span></label>
           <label><span><input type="radio"  placeholder="" class="input-label" name='antenatalcare' value="not attended">Not Attended</span></label>
     </td>
       </tr>

       <tr>
         <td class="label">Maturity By Dates:</td>
           <td class="input">
             <input type="text" name="maturity_by_dates" class="form-control" placeholder="Maturity By Dates" class="input-label">

         </td>
         <td class="label">Membrane Rupture For:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="Membrane Rupture For:" name="membrane_rupture_for" class="input-label">
       </td>
       <td class="label">Type Of Amniotic Fluid:</td>
         <td class="input">
           <input type="text" name="type_of_amnionic_fluid" class="form-control" placeholder="Type Of Amniotic Fluid" class="input-label">
     </td>

       </tr>

       <tr>
         <td class="label">Placenta</td>
         <td>
           <label><span>
             <input type="radio" name="placenta" value="normal"> Normal</span>
           <label><span></label>
             <label><input type="radio" name="placenta" value="abnormal">abnormal </span></label>
         </td>
         <td class="label">Weight Of Placenta:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="Weight Of Placenta" name="weight_of_placenta" class="input-label">
       </td>
       <td class="label">Baby Abdomalities:</td>
         <td class="input">

           <label><span>
             <input type="radio" placeholder="" class="input-label"  name="baby_abdomalities">
           </span><span>Cord clamped</span></label>
          <label><span>
             <input type="radio"  placeholder="" class="input-label" name="baby_abdomalities"></span>
          <span>Three Blood Vessels</span></label>
     </td>
       </tr>
       <tr>
         <td class="label">Resucitation Method:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="Resucitation Method" class="input-label" name="resucitation_method">
           </td>
      <td class="label">Drug Given To the Baby:</td>
      <td>
        <input type="text" name="" value="">
      </td>
      <td class="label">Prophylactic Eye Drops:</td>
      <td>
        <label><span><input type="radio" name="prophylactic_eye_drops" value="given"> Given</span></label>
      <label><span>
        <input type="radio" name="prophylactic_eye_drops" value="not given">Not Given
       </span>
     </label>
      </td>
       </tr>
       <tr>
         <td class="label">Delivered By:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="Delivery By" name="delivery_by" class="input-label">
       </td>
       <td class="label">Sent To Premium Unit By:</td>
         <td class="input">
           <input type="text" name="sent_to_premium" class="form-control" placeholder="Sent To Premium Unit By" class="input-label">
     </td>
       </tr>
       <tr>
         <td class="label">Received By:</td>
           <td class="input"><input type="text" class="form-control" placeholder="Received By" name="received_by"class="input-label">
       </td>
       <td class="label">Condition On Arrival:</td>
         <td class="input"><input type="text" class="form-control" placeholder="Condition on Arrival" name="condition_on_arrival" class="input-label">
     </td>
     <td class="label">Time:</td>
       <td class="input">
         <input type="text" class="form-control input-label date" name="time" placeholder="Time" >
   </td>
       </tr>
       <tr>
         <td class="label">Prophylactic Eye Drops:</td>
           <td class="input">
           <span>
             <label><input type="radio" placeholder="" class="input-label" name='radio' value="given">Given</span></label>
         <label>
           <span><input type="radio" placeholder="" class="input-label" name='radio' value="not given">Not Given</span></label>

       </td>

       <td class="label">Sent To Neonatal Ward Because Of:</td>
         <td colspan='4' class="input">
           <input type="text" class="form-control" placeholder="Sent To Neonatal Ward Because Of" name="sent_to_neonatal_ward_ward_becauseo_of" class="input-label">
     </td
       </tr>
     </table>

     <input type="submit" name="save" value="save" class="art-button-green">
     <input type="submit" name="save" value="Preview" class="art-button-green">


     </form>
   </fieldset>
 </center>


 <script src="../css/jquery.datetimepicker.js"></script>
<script type="text/javascript">
  $("select > option:first").hide()


  $("#myform").submit(function(e){
    e.preventDefault()
    var form_data=$("#myform").serialize();

    alert(form_data)
    $.ajax({
      url:"save_infant_labour_room.php",
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
