
<!--<script src="../css/scripts.js"></script>-->
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link href="css/jquery-ui.css" rel="stylesheet" />
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
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
             $('.editappointdate').datetimepicker({
            dayOfWeekStart : 1,
            lang:'en',
            startDate:  'now'
            });
            $('.editappointdate').datetimepicker({value:'',step:1});
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


//if(isset($_POST['from_procedure'])){
//    $from_procedure=$_POST['from_procedure'];
//    if($from_procedure=="yes"){
//        $from_procedure_filter="and from_procedure='yes'";   
//    }else{
//        $from_procedure_filter="";
//    }
//    
//}else{
//  $from_procedure="no"; 
//  $from_procedure_filter="yes";
//}


    $fromDate=  mysqli_real_escape_string($conn,$_POST['fromDate']);
    $toDate=  mysqli_real_escape_string($conn,$_POST['toDate']);
    $clinic=  mysqli_real_escape_string($conn,$_POST['clinic']);
    $doctor=  mysqli_real_escape_string($conn,$_POST['doctor']);
//    $regID=  mysqli_real_escape_string($conn,$_POST['regID']);
//    $PatientName=  mysqli_real_escape_string($conn,$_POST['PatientName']);


   $filterclinic="";
	if($clinic != ""){
    $filterclinic=" AND ap.Clinic='$clinic'";
    }else {
        $filterclinic="";
    }
    
   $filterdoctor="";
	if($doctor != ""){
    $filterdoctor=" AND ap.doctor='$doctor'";
    }else {
        $filterdoctor="";
    }
  
  
//    $numberSpecimen="SELECT * FROM tbl_appointment LEFT JOIN tbl_employee ON Employee_ID=doctor JOIN tbl_patient_registration ON Registration_ID=patient_No LEFT JOIN tbl_clinic ON Clinic=Clinic_ID WHERE date_time BETWEEN '$fromDate' AND '$toDate' AND tbl_appointment.Status='1' $from_procedure_filter LIMIT 200";  
//    regID:regID,PatientName:PatientName
    
    $numberSpecimen="SELECT reg.Phone_Number,reg.Registration_ID,ap.doctor,ap.Set_BY,ap.date_time,emp.Employee_Name,reg.Patient_Name,ap.appointment_reason,cl.Clinic_Name,ap.appointment_id FROM tbl_appointment ap,tbl_employee emp,tbl_patient_registration reg,tbl_clinic cl WHERE ap.patient_No=reg.Registration_ID AND ap.doctor=emp.Employee_ID $filterdoctor AND ap.Clinic=cl.Clinic_ID $filterclinic AND ap.Status='1' AND date_time BETWEEN '$fromDate' AND '$toDate' LIMIT 200  ";     
//   echo "inafika";
      $totalrevenue= mysqli_query($conn,$numberSpecimen);
         echo "<table class='display' id='AppointmentList' style='width:100%'> 
         <thead>
            <tr>
                <th style='text-align:left'>S/n</th>
                <th style='text-align:left'>Appointment Date</th>
                <th style='text-align:left'>Set By</th>
                <th style='text-align:left'>Patient</th>
                <th style='text-align:left'>Reg. No</th>
                <th style='text-align:left'>Phone. No</th>
                <th style='text-align:left'>Appointment reason</th>
                <th style='text-align:left'>Clinic</th>
                <th style='text-align:left'>Doctor</th>
               <th style='text-align:left'>Edit</th>
            </tr>
        </thead>";             
        $sn=1;  
        $grandTotal=0;
        while ($row2 = mysqli_fetch_assoc($totalrevenue)){
    
         
        echo '<tr>';
        echo '<td>'.$sn++.'.</td>';
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['date_time']."</a></td>";
        $getemployee=  mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='".$row2['Set_BY']."'");
        $result=  mysqli_fetch_assoc($getemployee);
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$result['Employee_Name']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['Patient_Name']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['Registration_ID']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['Phone_Number']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['appointment_reason']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['Clinic_Name']."</a></td>";
        echo "<td style='text-align:left;'><a href='$link_to_procedure'>".$row2['Employee_Name']."</a></td>";
        echo '<td style="text-align:left;"><a class="edit art-button" id="'.$row2['appointment_id'].'" href="#">Edit</a> <a class="delete art-button" id="'.$row2['appointment_id'].'"  href="">Remove</a></td>';
        echo "</tr>";
      }    
      echo '</table>';    
   

?>
