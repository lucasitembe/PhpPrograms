
<!--<script src="../css/scripts.js"></script>-->
<script>
       $('.edit').click(function(e){
        e.preventDefault();
         $('#editappointment').dialog({
            modal:true,
            title:'Edit Appointment',
            width:600,
            resizable:true,
            draggable:true,
        }); 
        
        var id=$(this).attr('id');
        $.ajax({
        type:'POST',
        url:'requests/loadEditAppointments.php',
        data:'action=EditApp&id='+id,
        success:function(html){
            $('#showDiv').html(html);
        }
        });

    });
    
    
    
     $('.delete').click(function(e){
      e.preventDefault();
      var id=$(this).attr('id');
      if(confirm('Are you sure you want to remove this ?')){
      $.ajax({
         type:'POST', 
         url:'requests/saveAppointment.php',
         data:'action=delete&id='+id,
         cache:false,
         success:function(html){
          alert(html);
//          window.location.href='viewappointmentPage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage';
         
           }
     
         }); 
     }
    });
    
    $('#AppointmentList').DataTable({
        "bJQueryUI":true
    });

    
</script>

<?php

include("../includes/connection.php");

if(isset($_POST['action'])){
    if($_POST['action']=='viewAppointment'){
    $fromDate=  mysql_real_escape_string($_POST['fromDate']);
    $toDate=  mysql_real_escape_string($_POST['toDate']);
    $numberSpecimen="SELECT * FROM tbl_appointment LEFT JOIN tbl_employee ON Employee_ID=doctor JOIN tbl_patient_registration ON Registration_ID=patient_No LEFT JOIN tbl_clinic ON Clinic=Clinic_ID WHERE date_time BETWEEN '$fromDate' AND '$toDate' AND tbl_appointment.Status='1' LIMIT 200";     
   
      $totalrevenue= mysql_query($numberSpecimen);
         echo "<table class='display' id='AppointmentList' style='width:100%'> 
         <thead>
            <tr>
                <th style='text-align:left'>S/n</th>
                <th style='text-align:left'>Appointment Date</th>
                <th style='text-align:left'>Set By</th>
                <th style='text-align:left'>Patient</th>
                <th style='text-align:left'>Reg. No</th>
                <th style='text-align:left'>Appointment reason</th>
                <th style='text-align:left'>Clinic</th>
                <th style='text-align:left'>Doctor</th>
               <th style='text-align:left'>Edit</th>
            </tr>
        </thead>";             
        $sn=1;  
        $grandTotal=0;
        while ($row2 = mysql_fetch_assoc($totalrevenue)){
        echo '<tr>';
        echo '<td>'.$sn++.'.</td>';
        echo '<td style="text-align:left;">'.$row2['date_time'].'</td>';
        $getemployee=  mysql_query("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='".$row2['Set_BY']."'");
        $result=  mysql_fetch_assoc($getemployee);
        echo '<td style="text-align:left;">'.$result['Employee_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Patient_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Registration_ID'].'</td>';
        echo '<td style="text-align:left;">'.$row2['appointment_reason'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Clinic_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Employee_Name'].'</td>';
        echo '<td style="text-align:left;"><a class="edit art-button" id="'.$row2['appointment_id'].'" href="#">Edit</a> <a class="delete art-button" id="'.$row2['appointment_id'].'"  href="">Remove</a></td>';
        echo '</tr>';
      }    
      echo '</table>';    
   
    }else if($_POST['action']=='viewAppointmentName'){
        $patientName= mysql_real_escape_string($_POST['patientName']);
        $numberSpecimen="SELECT * FROM tbl_appointment LEFT JOIN tbl_employee ON Employee_ID=doctor JOIN tbl_patient_registration ON Registration_ID=patient_No LEFT JOIN tbl_clinic ON Clinic=Clinic_ID WHERE Patient_Name LIKE '%$patientName%' AND tbl_appointment.Status='1'";     
   
      $totalrevenue= mysql_query($numberSpecimen);
         echo "<table class='display' id='AppointmentList' style='width:100%'> 
         <thead>
            <tr>
                <th style='text-align:left'>S/n</th>
                <th style='text-align:left'>Appointment Date</th>
                <th style='text-align:left'>Set By</th>
                <th style='text-align:left'>Patient</th>
                <th style='text-align:left'>Reg. No</th>
                <th style='text-align:left'>Appointment reason</th>
                <th style='text-align:left'>Clinic</th>
                <th style='text-align:left'>Doctor</th>
               <th style='text-align:left'>Edit</th>
            </tr>
        </thead>";             
        $sn=1;  
        $grandTotal=0;
        while ($row2 = mysql_fetch_assoc($totalrevenue)){
        echo '<tr>';
        echo '<td>'.$sn++.'.</td>';
        echo '<td style="text-align:left;">'.$row2['date_time'].'</td>';
        $getemployee=  mysql_query("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='".$row2['Set_BY']."'");
        $result=  mysql_fetch_assoc($getemployee);
        echo '<td style="text-align:left;">'.$result['Employee_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Patient_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Registration_ID'].'</td>';
        echo '<td style="text-align:left;">'.$row2['appointment_reason'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Clinic_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Employee_Name'].'</td>';
        echo '<td style="text-align:left;"><a class="edit art-button" id="'.$row2['appointment_id'].'" href="#">Edit</a> <a class="delete art-button" id="'.$row2['appointment_id'].'"  href="">Remove</a></td>';
        echo '</tr>';
      }    
      echo '</table>'; 
        
    }else if($_POST['action']=='viewAppointmentNumber'){
              $patientNumber= mysql_real_escape_string($_POST['patientNumber']);
        $numberSpecimen="SELECT * FROM tbl_appointment LEFT JOIN tbl_employee ON Employee_ID=doctor JOIN tbl_patient_registration ON Registration_ID=patient_No LEFT JOIN tbl_clinic ON Clinic=Clinic_ID WHERE Registration_ID LIKE '%$patientNumber%' AND tbl_appointment.Status='1'";     
   
      $totalrevenue= mysql_query($numberSpecimen);
         echo "<table class='display' id='AppointmentList' style='width:100%'> 
         <thead>
            <tr>
                <th style='text-align:left'>S/n</th>
                <th style='text-align:left'>Appointment Date</th>
                <th style='text-align:left'>Set By</th>
                <th style='text-align:left'>Patient</th>
                <th style='text-align:left'>Reg. No</th>
                <th style='text-align:left'>Appointment reason</th>
                <th style='text-align:left'>Clinic</th>
                <th style='text-align:left'>Doctor</th>
               <th style='text-align:left'>Edit</th>
            </tr>
        </thead>";             
        $sn=1;  
        $grandTotal=0;
        while ($row2 = mysql_fetch_assoc($totalrevenue)){
        echo '<tr>';
        echo '<td>'.$sn++.'.</td>';
        echo '<td style="text-align:left;">'.$row2['date_time'].'</td>';
        $getemployee=  mysql_query("SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='".$row2['Set_BY']."'");
        $result=  mysql_fetch_assoc($getemployee);
        echo '<td style="text-align:left;">'.$result['Employee_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Patient_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Registration_ID'].'</td>';
        echo '<td style="text-align:left;">'.$row2['appointment_reason'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Clinic_Name'].'</td>';
        echo '<td style="text-align:left;">'.$row2['Employee_Name'].'</td>';
        echo '<td style="text-align:left;"><a class="edit art-button" id="'.$row2['appointment_id'].'" href="#">Edit</a> <a class="delete art-button" id="'.$row2['appointment_id'].'"  href="">Remove</a></td>';
        echo '</tr>';
      }    
      echo '</table>';   
    }
}
?>
