<?php
include("./includes/connection.php");
if(isset($_GET['Current_Employee_ID'])){
  $Current_Employee_ID=$_GET['Current_Employee_ID'];
  $filter = " AND DATE(ilc.Service_Date_And_Time) = CURDATE()";

  $pendingtransaction = 0;
  $complete = 0;
  
  echo '
  <div class="box box-primary" style="height: 560px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">
            <tr  style="background: #dedede;">
                <td style="width:30px"><b>S/No</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Number</b></td>
                <td><b>Gender</b></td>
                <td><b>Age</b></td>
                <td><b>Surgery Name</b></td>
                <td><b>Surgeon</b></td>
                <td><b>Theater</b></td>
                <td><b>Room</b></td>
            </tr>
            <tbody id="patient_sent_to_cashier_tbl">
                
            ';
    
  $query = "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
  pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, 
  ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Priority, 
  ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, 
  tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND 
  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid', 'served') AND 
  i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status = 'completed') $filter ORDER BY ilc.Service_Date_And_Time ASC";

  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Gender=$patient_list_rows['Gender'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
         $Service_Date_And_Time=$patient_list_rows['Service_Date_And_Time'];   
         $Product_Name=$patient_list_rows['Product_Name'];
         $Sub_Department_ID=$patient_list_rows['Sub_Department_ID'];
        $my_theater_room_id = $patient_list_rows['theater_room_id'];
        $Payment_Item_Cache_List_ID = $patient_list_rows['Payment_Item_Cache_List_ID'];
        $Priority = $patient_list_rows['Priority'];
        // $Sub_Department_Name =$patient_list_rows['Sub_Department_Name'];

                            
        $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];
    $My_theater_Room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT theater_room_name FROM tbl_theater_rooms WHERE theater_room_id = '$my_theater_room_id' AND room_status = 'active'"))['theater_room_name'];


             $date1 = new DateTime($Today);
            $date2 = new DateTime($patient_list_rows['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

         
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Gender</td>
                        <td>$age</td>
                        <td>$Product_Name</td>
                        <td>$Employee_Name</td>
                        <td>$Sub_Department_Name</td>
                        <td>$My_theater_Room</td>
                </tr>
                ";
        $count_sn++;
          
      }

  }else{
      echo "
      <tr class='rows_list'>
        <td colspan='9' style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;'>NO SURGERY PERFOMED TODAY</td>
    </tr>
      ";
  }
  echo "<tr>
  <td colspan='11'><hr></td>
</tr>
</tbody>
</table>
</div>";
}else{
    echo "FAILED, NOTHING TO SHOW";
}

mysqli_close($conn);
?>