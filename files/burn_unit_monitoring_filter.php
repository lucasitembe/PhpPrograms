<?php 

   include("./includes/connection.php");

   $employee_id = $_POST['employee_id'];
   $patient_registration = $_POST['registration_id'];
   $start = $_POST['monitoring_start_date_filter'];
   $end = $_POST['monitoring_end_date_filter'];

   $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee_id' ";
   $select_employee_name = mysqli_query($conn,$select_employee_name);
   while($employee_row = mysqli_fetch_array($select_employee_name)):
      $employee_name = $employee_row['Employee_Name'];
   endwhile;

   $filter_query = "SELECT * FROM tbl_monitoring_body_weight WHERE patient_registration_id = '$patient_registration' AND monitoring_date BETWEEN '$start' AND '$end' ";
   $result = mysqli_query($conn,$filter_query);
   if(mysqli_num_rows($result)<1) :
      echo "
      <tr>
         <td colspan='4' style='text-align:center;padding:18px;color:#B03703'><b>No data Found</b></td>
      </tr>";
   else:
      while($row = mysqli_fetch_array($result)) : 
         echo "
         <tr>
            <td style='padding: .8em'>".$row['monitoring_date']."</td>
            <td style='padding: .8em'>".$row['body_weight']." Kg</td>
            <td style='padding: .8em'>".$row['comment']."</td>
            <td style='padding: .8em'>".$employee_name."</td>
         </tr>";
      endwhile;
   endif;
?>