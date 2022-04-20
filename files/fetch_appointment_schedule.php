<?php
//status:status
include("./includes/connection.php");
if (isset($_POST['Clinic_ID'])) {
    $Clinic_ID = $_POST['Clinic_ID'];
} else {
    $Clinic_ID = "";
}
if (isset($_POST['status'])) {
    $status = $_POST['status'];
} else {
    $status = "";
} 

if($status == "clinics"){
    
    $select_result = mysqli_query($conn,"SELECT * FROM tbl_date_appointment WHERE Clinic_ID='$Clinic_ID' AND Status='$status'");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                      $Date=$row['Date'];
                      $new_date = $Date;
                        $new_date= date("d M Y", strtotime($new_date)); 
                        $new_date = date('dS F Y', strtotime($new_date));
                      $Date= $new_date."   "."<b>".date("l", strtotime($Date))."</b>";
                      $Enable_disable = $row['Enable_disable'];
                      $Clinic_ID=$row['Clinic_ID'];
                      $App_ID=$row['App_ID'];
                      echo "<tr>
                                        <td colspan='6' style='text-align:center; background:#dedede;'>
                                          $Date
                                              
                                        </td>
                                   </tr>";
                      $select_time_appointment= mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID'");
                         echo "<tr>
                           <td>SNo</td>
                           <td>Time From</td>
                           <td>Time To</td>
                           <td>Total Number</td>
                           <td>Action</td>
                           <td>Edit</td>
                      </tr>";
                         $count=1;
                        while($time = mysqli_fetch_assoc($select_time_appointment)){
                                   $time_from =$time['time_from'];
                                   $Time_ID =$time['Time_ID'];
                                   $time_to =$time['time_to'];
                                   $Appointment_Idadi =$time['Appointment_Idadi'];
                                   
                                   
                            
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
                                        $Appointment_Idadi
                                        </td>
                                        <td>";
                                        if($Enable_disable == 'No'){
                                              echo " <input type='button' class='btn btn-danger' value='Enable' onclick='enable_schedule(\"$App_ID\")''>";
                                        }else{
                                            echo " <input type='button' class='btn btn-info' value='Disiable' onclick='disiable_schedule(\"$App_ID\")''>";

                                        }
                                        echo "
                                        </td>
                                        <td>
                                               <input type='button' class='btn btn-info' value='Edit' onclick='editThisParam(\"$Time_ID\")''>
                                        </td>
                                   </tr>";
                            $count++;
                        }

    }

  

    
}else{
    
    $select_result = mysqli_query($conn,"SELECT * FROM tbl_date_appointment WHERE Clinic_ID='$Clinic_ID' AND Status='$status'");
            
                   while($row = mysqli_fetch_assoc($select_result)){
                      $Date=$row['Date'];
                      $new_date = $Date;
                         $new_date= date("d M Y", strtotime($new_date));
                         $new_date = date('dS F Y', strtotime($new_date));
                      $Enable_disable = $row['Enable_disable'];
                      $Date= $new_date."   "."<b>".date("l", strtotime($Date))."</b>";
                      $Clinic_ID=$row['Clinic_ID'];
                      $App_ID=$row['App_ID'];
                      echo "<tr>
                                        <td colspan='5' style='text-align:center; background:#dedede;'>
                                          $Date
                                              
                                        </td>
                                   </tr>";
                      $select_time_appointment= mysqli_query($conn,"SELECT * FROM tbl_time_appointment WHERE App_ID='$App_ID'");
                         echo "<tr>
                           <td>SNo</td>
                           <td>Time From</td>
                           <td>Time To</td>
                           <td>Total Number</td>
                           <td>Action</td>
                      </tr>";
                         $count=1;
                        while($time = mysqli_fetch_assoc($select_time_appointment)){
                                   $time_from =$time['time_from'];
                                   $time_to =$time['time_to'];
                                   $Appointment_Idadi =$time['Appointment_Idadi'];
                                   
                                   
                            
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
                                        $Appointment_Idadi
                                        </td>
                                        <td>";
                                        if($Enable_disable == 'No'){
                                              echo " <input type='button' class='btn btn-danger' value='Enable' onclick='enable_schedule(\"$App_ID\")''>";
                                        }else{
                                            echo " <input type='button' class='btn btn-info' value='Disiable' onclick='disiable_schedule(\"$App_ID\")''>";

                                        }
                                        echo "
                                        </td>
                                   </tr>";
                            $count++;
                        }

    }
   
}
     