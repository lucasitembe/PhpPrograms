
<?php

include("../includes/connection.php");

if(isset($_POST['action'])){
    if($_POST['action']=='EditApp'){
     
$id=$_POST['id'];
$query= mysqli_query($conn,"SELECT * FROM tbl_appointment LEFT JOIN tbl_employee ON Employee_ID=doctor LEFT JOIN tbl_clinic ON Clinic=Clinic_ID WHERE appointment_id='$id'");
$result=  mysqli_fetch_assoc($query);
        
echo'
   <table style="width: 100%" border="0">
   <input type="hidden" value="'.$id.'" id="editableId">
      <tr>
          <td>
              Appointment Date &amp; time   
          </td>
          <td>
              <input type="text" id="editappointdate" class="editappointdate" readonly="readonly"value="'.$result['date_time'].'"> 
          </td>
      </tr>
      
      <tr>
          <td>
              Reason for appointment
          </td>
          <td>
              <textarea id="editreason">'.$result['appointment_reason'].'</textarea>
          </td>
      </tr>
      <tr>
          <td>
              Select Doctor
          </td> 
          <td>
              <select id="editdoctor">';
                 echo'<option value="'.$result['Employee_ID'].'">'.$result['Employee_Name'].'</option>';
                 echo'<option value="">~~~~~~~Select new doctor~~~~~~~</option>';
                  
                  $query=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_Type='Doctor' AND Account_Status='active'");
                  while ($results=  mysqli_fetch_assoc($query)){
                      echo '<option value="'.$results['Employee_ID'].'">'.$results['Employee_Name'].'</option>';
                  }
                  
                 
             echo' </select>
          </td>
      </tr>
      
      <tr>
          <td>
              Select Clinic
          </td> 
          <td>
              <select id="editclinic">';
                 echo'<option value="'.$result['Clinic_ID'].'">'.$result['Clinic_Name'].'</option>';
                  echo'<option value="">~~~~~~~Select new clinic~~~~~~~</option>';
                  $query=mysqli_query($conn,"SELECT * FROM tbl_clinic");
                  while ($results=  mysqli_fetch_assoc($query)){
                      echo '<option value="'.$results['Clinic_ID'].'">'.$results['Clinic_Name'].'</option>';
                  }
                  
                 
             echo' </select>
          </td>
      </tr>

  </table>

';   

 }
}
?>
