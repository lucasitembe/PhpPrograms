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
  select option{
    height: 25px;
    padding-top:4px;
  }
  .label{
    color:black !important;
    float: right;
    text-align: right !important ;
    font-size: 1em;
  }
  .input-label{
    width: 400px !important;
  }
  .input{
    width: 300px !important;
  }
</style>
 <center>
   <fieldset>
     <legend align=center style="font-weight:bold">NEONATAL RECORD</legend>
     <form class="" id="myform"  method="post">


     <table>
       <tr>
         <td class="label">DATE AND TIME OF BIRTH:</td>
           <td class="input">
             <input type="text"  name="date_and_time_of_birth" class="form-control input-label date" placeholder="Date and Time Of Birth" >
         </td>
         <td class="label">
           DATE Of DISCHARGE
         </td>
           <td class="input">
             <input type="text" name="date_of_discharge" class="form-control input-label date" placeholder="Date of Discharge" >
         </td>

       </tr>
       <tr>
         <td class="label">

           PAT NO:</td>
           <td class="input">
             <input type="text" name="patient_no" class="form-control" placeholder="Patient number" class="input-label">

         </td>
         <td class="label">WARD</td>
         <td class="input">
           <select class="" name="ward" style="height:30px; width:100%;">
             <option value="">Select Ward</option>
             <?php
             if ($result_ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward")) {
               while ($row=mysqli_fetch_assoc($result_ward)) {
                 echo "<option value=".$row['Hospital_Ward_ID'].">".$row['Hospital_Ward_Name']."</option>";
               }
             };

              ?>
           </select>
       </td>
       </tr>
       <tr>
         <td class="label">
          NAME</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="name" class="input-label" name="name">

         </td>
         <td class="label">ADDRESS</td>
          <td class="input">
             <input type="text" name="address" class="form-control" placeholder="Address" class="input-label">
       </td>
       </tr>
       <tr>
         <td class="label">
           AGE:</td>
           <td class="input"><input type="number"  name="age" class="form-control" placeholder="Age" class="input-label">

         </td>
         <td class="label">SEX:</td>
           <td class="input">
             <select class="gender" name="sex" style="height:30px; width:100%;">
               <option value="">--Select Sex--</option>
               <option value="ME">Male</option>
               <option value="FE">Female</option>
             </select>

       </td>
       </tr>
       <tr>
         <td class="label">
           DOCTOR:</td>
           <td><input type="text" class="form-control" placeholder="Doctor" class="input-label" name="doctor">

         </td>
         <td class="label">PEDISTRICIAN:</td>
           <td class="input">
             <input type="text" class="form-control" placeholder="pedistrician"  name="pedistrician" class="input-label">
       </td>
       </tr>

       <tr>
         <td class="label">SURGEON:</td>
           <td class="input"><input type="text" class="form-control" placeholder="surgeon" name="surgeon" class="input-label">

         </td>
         <td class="label">ANAESTHESIOLOGIST:</td>
           <td class="input"><input type="text" name="anaethesiologist" class="form-control" placeholder="anaesthesiologist" class="input-label">
       </td>
       </tr>
     </table>
     <div class="">


     <input type="submit" class="art-button-green" id="submit" name="submit" value="Save">
     <input type="submit" class="art-button-green" name="preview" value="Preview">
     </div>

     </form>
   </fieldset>
 </center>

<script src="../css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

$('select > option:first').hide();

$(".gender > option:first").hide()

$("#submit").click(function(e){
  e.preventDefault()
  var form_data=$("#myform").serialize();

  $.ajax({
    url:"save_neonatal_record.php",
    type:"POST",
    data:form_data,
    success:function(data){
      alert(data)
    }
  })

})

// $('.date').datetimepicker({value: '', step: 2});
$(document).ready(function(e){

  $('.date').datetimepicker({value: '', step: 2});
})

</script>
