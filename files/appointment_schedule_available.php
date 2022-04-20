<?php
include("./includes/connection.php");
if (isset($_POST['Clinic_ID'])) {
    $Clinic_ID = $_POST['Clinic_ID'];
} else {
    $Clinic_ID = "";
}
if (isset($_POST['status'])) {
    $status= $_POST['status'];
} else {
    $status = "";
}
//status:status

  if($status == "clinics"){
      
       $select_result = mysqli_query($conn,"SELECT * FROM tbl_date_appointment WHERE Clinic_ID='$Clinic_ID' AND Status='$status' AND Enable_disable='yes' AND DATE(Date)>CURDATE() ORDER BY DATE(Date) ASC");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                      $Date=$row['Date'];
                      $date_to_appointment=$Date;
                      $filter_ap_date=$Date;
                      $date_to_appointment = date("Y-m-d", strtotime($date_to_appointment));
                      $new_date = $Date;
                         $new_date= date("d M Y", strtotime($new_date));
                         $new_date = date('dS F Y', strtotime($new_date));
//               echo $new_date;
                      $Date= $new_date."   "."<b>".date("l", strtotime($Date))."</b>";
                      $Clinic_ID=$row['Clinic_ID'];
                      $App_ID=$row['App_ID'];
                      echo "<tr>
                                        <td colspan='7' style='text-align:center; background:#dedede;'>
                                          $Date
                                              
                                        </td>
                                   </tr>";
                      $select_time_appointment= mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID'");
                         echo "<tr>
                           <td>SNo</td>
                           <td>Time From</td>
                           <td>Time To</td>
                           <td>Available</td>
                           <td>Covered</td>
                           <td>Total</td>
                           <td>Action</td>
                      </tr>";
                         $count=1;
                        while($time = mysqli_fetch_assoc($select_time_appointment)){
                                   $time_from =$time['time_from'];
                                   $time_to =$time['time_to'];
                                   $Appointment_Idadi =$time['Appointment_Idadi'];
                                   
                                   $available_appointment_coverd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(patient_No) as idaditayar FROM tbl_appointment WHERE Clinic='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to' AND DATE(date_time)=DATE('$filter_ap_date') AND Status='1'"))['idaditayar'];
                                   
//                                   $total_number = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Appointment_Idadi FROM tbl_time_appointment WHERE Clinic='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to'"))['Appointment_Idadi'];
                                   
                                   $availabe_number = $Appointment_Idadi - $available_appointment_coverd;
                                   
                                    if($availabe_number < 0){
                                        
                                        $availabe_number=0;
                                        
                                    }
                            
                                   echo "<tr>
                                       <td>
                                          $count
                                       </td>
                                        <td>
                                         $time_from
                                        </td>
                                             <td>
                                         $time_to
                                            </td>
                                        <td>
                                        $availabe_number
                                        </td>
                                        <td>
                                           $available_appointment_coverd
                                        </td>
                                        <td>
                                          $Appointment_Idadi
                                        </td>
                                        <td>
                                               <input type='radio' name='date' onclick='date_display_to_appointment(\"$date_to_appointment\",\"$time_from\",\"$time_to\",\"$status\")' >
                                        </td>
                                   </tr>";
                            $count++;
                        }

    }

  

      
  }else{
      
             $select_result = mysqli_query($conn,"SELECT * FROM tbl_date_appointment WHERE Clinic_ID='$Clinic_ID' AND Status='$status' AND Enable_disable='yes' AND DATE(Date)>CURDATE()");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                      $Date=$row['Date'];
                      $date_to_appointment=$Date;
                      $filter_ap_date=$Date;
                      $date_to_appointment = date("Y-m-d", strtotime($date_to_appointment));
                      $new_date = $Date;
                         $new_date= date("d M Y", strtotime($new_date));
                         $new_date = date('dS F Y', strtotime($new_date));
//               echo $new_date;
                      $Date= $new_date."   "."<b>".date("l", strtotime($Date))."</b>";
                      $Clinic_ID=$row['Clinic_ID'];
                      $App_ID=$row['App_ID'];
                      echo "<tr>
                                        <td colspan='7' style='text-align:center; background:#dedede;'>
                                          $Date
                                              
                                        </td>
                                   </tr>";
                      $select_time_appointment= mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID'");
                         echo "<tr>
                           <td>SNo</td>
                           <td>Time From</td>
                           <td>Time To</td>
                           <td>Available</td>
                           <td>Covered</td>
                           <td>Total</td>
                           <td>Action</td>
                      </tr>";
                         $count=1;
                        while($time = mysqli_fetch_assoc($select_time_appointment)){
                                   $time_from =$time['time_from'];
                                   $time_to =$time['time_to'];
                                   $Appointment_Idadi =$time['Appointment_Idadi'];
                                   
                                   $available_appointment_coverd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(patient_No) as idaditayar FROM tbl_appointment WHERE doctor='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to' AND DATE(date_time)=DATE('$filter_ap_date')  AND Status='1'"))['idaditayar'];
                                   
//                                   $total_number = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Appointment_Idadi FROM tbl_time_appointment WHERE Clinic='$Clinic_ID' AND timefrom='$time_from' AND timeto='$time_to'"))['Appointment_Idadi'];
                                   
                                   $availabe_number = $Appointment_Idadi - $available_appointment_coverd;
                                   
                                    if($availabe_number < 0){
                                        
                                        $availabe_number=0;
                                        
                                    }
                            
                                   echo "<tr>
                                       <td>
                                          $count
                                       </td>
                                        <td>
                                         $time_from
                                        </td>
                                             <td>
                                         $time_to
                                            </td>
                                        <td>
                                        $availabe_number
                                        </td>
                                        <td>
                                           $available_appointment_coverd
                                        </td>
                                        <td>
                                          $Appointment_Idadi
                                        </td>
                                        <td>
                                               <input type='radio' name='date' onclick='date_display_to_appointment(\"$date_to_appointment\",\"$time_from\",\"$time_to\",\"$status\")' >
                                        </td>
                                   </tr>";
                            $count++;
                        }

    }
      
  }

  if(isset($_POST['diableapointment'])){
    $App_ID = $_POST['App_ID'];

    $disable_appointment = mysqli_query($conn, "UPDATE tbl_date_appointment SET Enable_disable='No' WHERE App_ID='$App_ID'") or die(mysqli_error($conn));
    if($disable_appointment){
      echo "Disabled";
    }else{
      echo "Failed to Disable";
    }
  }

  if(isset($_POST['enableapointment'])){
    $App_ID = $_POST['App_ID'];

    $disable_appointment = mysqli_query($conn, "UPDATE tbl_date_appointment SET Enable_disable='Yes' WHERE App_ID='$App_ID'") or die(mysqli_error($conn));
    if($disable_appointment){
      echo "Enabled";
    }else{
      echo "Failed to enable";
    }
  }
    