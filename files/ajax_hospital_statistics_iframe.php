<?php 

include("./includes/connection.php");
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
if(isset($_POST['ward_id'])){
    $ward_id = $_POST['ward_id'];
     //first select all ward
   $ward=mysqli_query($conn,"SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'") or die(mysqli_error($conn));
   if(mysqli_num_rows($ward)>0){
       while($ward_rows=mysqli_fetch_assoc($ward)){
          $ward_name=$ward_rows['Hospital_Ward_Name'];
          $ward_id=$ward_rows['Hospital_Ward_ID'];
          $ward_nature = $ward_rows['ward_nature'];
          $ward_type = $ward_rows['ward_type'];
        }
    }
               
    //room number
        echo '
        <div class="row" style="padding:10px">
        <h3 style="text-align:center">Total Amount Of Rooms and Beds Available</h3>
        <h4 style="text-align:center">For</h4>
        <h3 style="text-align:center">'.$ward_name.'</h3>
        </div>
        <table class="table table-bordered" style="background: #FFFFFF">
            <caption><b>Ward Information</b></caption>
            <tr>
                <td><b> WARD NAME</b></td>
                <td><b>WARD NATURE</b></td>
                <td><b>WARD TYPE</b></td>
            </tr> 
            <tr>
                <td>'.$ward_name.'</td>
                <td>'.$ward_nature.'</td>
                <td>'.$ward_type.'</td>
            </tr> 
        </table>
        ';
        echo '
        <table class="table table-bordered" style="background: #FFFFFF">
        <caption><b>Rooms Information</b></caption>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Room Name</th>
                <th>Number of Beds</th>
            </tr>
        </thead>
        <tbody>
        ';
          $room=mysqli_query($conn,"SELECT * FROM tbl_ward_rooms WHERE ward_id='$ward_id'") or die(mysqli_error($conn));
              while($room_number=mysqli_fetch_assoc($room)){
                $room_name=$room_number['room_name'];
                $ward_room_id=$room_number['ward_room_id'];
                $number_of_bed=$room_number['no_of_beds'];
                
                echo '
                <tr>
                    <td>'.$ward_room_id.'</td>
                    <td>'.$room_name.'</td>
                    <td>'.$number_of_bed.'</td>
                </tr>
                
            ';
            }
        echo '
        </tbody>
        </table>
        ';                            
}

//Admission isset post
if(isset($_POST['Ward_id_for_admission'])){
    $ward_name = $_POST['ward_name'];
    $ward_id = $_POST['Ward_id_for_admission'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $filter_gender=$_POST['filter_gender'];
    $start_age = $_POST['start_age'];
    $end_age = $_POST['end_age'];
    $end_age = $_POST['end_age'];
    $agetype = $_POST['agetype'];
    if($start_age != "" && $end_age != ""){
      $filter_age = "AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) BETWEEN  $start_age  AND  $end_age";
    }
    else{
      $filter_age = "";
    }
    $starting_date = date("Y-m-d",strtotime($start_date));
    $ending_date = date("Y-m-d",strtotime($end_date));
    $admission=mysqli_query($conn,"SELECT Patient_Name,Gender,Admission_Date_Time, ad.Registration_ID,Date_Of_Birth, Admission_Date_Time, Discharge_Date_Time,Bed_Name, Admission_Status,  ward_room_id FROM tbl_admission ad, tbl_patient_registration  pr WHERE  ad.Registration_ID=pr.Registration_ID  AND ad.Registration_ID=pr.Registration_ID AND Hospital_Ward_ID='$ward_id' AND ward_room_id <> '' AND Admission_Date_Time BETWEEN  '$start_date' AND '$end_date' $filter_gender $filter_age") or die(mysqli_error($conn));

    echo '
    <div class="row" style="padding:10px">
    <h3 style="text-align:center">Patients Admitted From '.$starting_date.' to '.$ending_date.'</h3>
    <h4 style="text-align:center">For</h4>
    <h3 style="text-align:center">'.$ward_name.'</h3>
    </div>
    <table class="table table-bordered" style="background: #FFFFFF">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Patient Name</th>
                <th>Reg No.</th>
                <th>Gender</th>
                <th>Age</th>                
                <th>Room Name</th>
                <th>Bed Number</th>
                <th>Admission Time</th>
                <th>Admission Status</th>
                <th>Discharge Time</th>
                
            </tr>
        </thead>
        
    <tbody>
    ';
    //get data from admission table
    $count = 1;
    while($admission_data=mysqli_fetch_assoc($admission)){
      $Registration_ID=$admission_data['Registration_ID'];
      $Admission_Date_Time=$admission_data['Admission_Date_Time'];
      $Discharge_time=$admission_data['Discharge_Date_Time'];
      $Bed_Name=$admission_data['Bed_Name'];
      $Admission_Status=$admission_data['Admission_Status'];
      $Discharge_reason=$admission_data['Discharge_Reason'];
      $ward_room_id=$admission_data['ward_room_id'];
      $Date_Of_Birth = $admission_data['Date_Of_Birth'];
      $patient_name=$admission_data['Patient_Name'];
      $Gender=$admission_data['Gender'];
      $Sponsor_ID=$admission_data['Sponsor_ID'];
      $patient_type=$admission_data['patient_type'];
      $Admission_Date_Time = $admission_data['Admission_Date_Time'];
      $date1 = new DateTime($Today);
      $date2 = new DateTime($Date_Of_Birth);
      $diff = $date1->diff($date2);
      $age = $diff->y . " Years, ";
      $age .= $diff->m . " Months, ";
      $age .= $diff->d . " Days";
        //get patient room number and ward name
        $patient_room_name=mysqli_query($conn,"SELECT room_name, Hospital_Ward_Name FROM tbl_ward_rooms wr, tbl_hospital_ward hw WHERE Hospital_Ward_ID='$ward_id' AND hw.Hospital_Ward_ID=wr.ward_id AND  ward_room_id='$ward_room_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($patient_room_name)>0){
          while($patient_room_rows=mysqli_fetch_assoc($patient_room_name)){
            $room_name=$patient_room_rows['room_name'];
            $hospital_ward_name=$patient_room_rows['Hospital_Ward_Name'];
          }
        }
    echo '
        <tr>
            <td>'.$count.'</td>
            <td>'.$patient_name.'</td>
            <td>'.$Registration_ID.'</td>
            <td>'.$Gender.'</td>
            <td>'.$age.'</td>
            <td>'.$room_name.'</td>
            <td>'.$Bed_Name.'</td>
            <td>'.$Admission_Date_Time.'</td>
            <td>'.$Admission_Status.'</td>
            <td>'.$Discharge_time.'</td>
            
        </tr> 
    '; 
    $count++;
  }
    echo '
        <tbody></table>
    ';
} 



//Alive discharged isset post
if(isset($_POST['Ward_id_for_alive_discharg'])){
    $ward_name = $_POST['ward_name'];
    $ward_id = $_POST['Ward_id_for_alive_discharg'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];   
    $filter_gender=$_POST['filter_gender'];
    $start_age = $_POST['start_age'];
    $end_age = $_POST['end_age'];
    $agetype = $_POST['agetype'];
    if($start_age != "" && $end_age != ""){
      $filter_age = "AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) BETWEEN  $start_age  AND  $end_age";
    }
    else{
      $filter_age = "";
    }
    $starting_date = date("Y-m-d",strtotime($start_date));
    $ending_date = date("Y-m-d",strtotime($end_date));
    $admission=mysqli_query($conn,"SELECT Patient_Name,Gender,Admission_Date_Time, admission.Registration_ID,Date_Of_Birth, Admission_Date_Time, Discharge_Date_Time,Bed_Name, Admission_Status, Discharge_Reason, ward_room_id FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='alive' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));
    echo '
    <div class="row" style="padding:10px">
    <h3 style="text-align:center">Patients Discharged Alive From '.$starting_date.' to '.$ending_date.'</h3>
    <h4 style="text-align:center">For</h4>
    <h3 style="text-align:center">'.$ward_name.'</h3>
    </div>
    <table class="table table-bordered" style="background: #FFFFFF">
        <thead>
            <tr>
              <th>S/N</th>
              <th>Patient Name</th>
              <th>Reg No.</th>
              <th>Gender</th>
              <th>Age</th>                
              <th>Room Name</th>
              <th>Bed Number</th>
              <th>Admission Time</th>
              <th>Discharge Time</th>
              <th>Discharge Resoan</th>
            </tr>
        </thead>
        
    <tbody>
    ';
    //get data from admission table
    $count = 1;
    while($admission_data=mysqli_fetch_assoc($admission)){
      $Registration_ID=$admission_data['Registration_ID'];
      $Admission_Date_Time=$admission_data['Admission_Date_Time'];
      $Discharge_time=$admission_data['Discharge_Date_Time'];
      $Bed_Name=$admission_data['Bed_Name'];
      $Admission_Status=$admission_data['Admission_Status'];
      $Discharge_reason=$admission_data['Discharge_Reason'];
      $ward_room_id=$admission_data['ward_room_id'];
      $Date_Of_Birth = $admission_data['Date_Of_Birth'];
      $patient_name=$admission_data['Patient_Name'];
      $Gender=$admission_data['Gender'];
      $Sponsor_ID=$admission_data['Sponsor_ID'];
      $patient_type=$admission_data['patient_type'];
      $Admission_Date_Time = $admission_data['Admission_Date_Time'];
      $date1 = new DateTime($Today);
      $date2 = new DateTime($Date_Of_Birth);
      $diff = $date1->diff($date2);
      $age = $diff->y . " Years, ";
      $age .= $diff->m . " Months, ";
      $age .= $diff->d . " Days";
        //get patient room number and ward name
        $patient_room_name=mysqli_query($conn,"SELECT room_name, Hospital_Ward_Name FROM tbl_ward_rooms wr, tbl_hospital_ward hw WHERE Hospital_Ward_ID='$ward_id' AND hw.Hospital_Ward_ID=wr.ward_id AND  ward_room_id='$ward_room_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($patient_room_name)>0){
          while($patient_room_rows=mysqli_fetch_assoc($patient_room_name)){
            $room_name=$patient_room_rows['room_name'];
            $hospital_ward_name=$patient_room_rows['Hospital_Ward_Name'];
          }
        }
    echo '
        <tr>
            <td>'.$count.'</td>
            <td>'.$patient_name.'</td>
            <td>'.$Registration_ID.'</td>
            <td>'.$Gender.'</td>
            <td>'.$age.'</td>
            <td>'.$room_name.'</td>
            <td>'.$Bed_Name.'</td>
            <td>'.$Admission_Date_Time.'</td>
            <td>'.$Discharge_time.'</td>
            <td>'.$Discharge_reason.'</td>
            
        </tr> 
    '; 
    $count++;
      // }
  }
    echo '
        <tbody></table>
    ';
} 



//Dead discharged isset post
if(isset($_POST['Ward_id_for_dead_admission'])){
    $ward_name = $_POST['ward_name'];
    $ward_id = $_POST['Ward_id_for_dead_admission'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $filter_gender=$_POST['filter_gender'];
    $start_age = $_POST['start_age'];
    $end_age = $_POST['end_age'];
    $agetype = $_POST['agetype'];
    if($start_age != "" && $end_age != ""){
      $filter_age = "AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) BETWEEN  $start_age  AND  $end_age";
    }
    else{
      $filter_age = "";
    }
    $starting_date = date("Y-m-d",strtotime($start_date));
    $ending_date = date("Y-m-d",strtotime($end_date));
    $admission=mysqli_query($conn,"SELECT Patient_Name,Gender, admission.Registration_ID,Date_Of_Birth, Admission_Date_Time, Discharge_Date_Time,Bed_Name, Admission_Status, Discharge_Reason, ward_room_id  FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='dead' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));
    echo '
    <div class="row" style="padding:10px">
    <h3 style="text-align:center">Patients Discharged Dead From '.$starting_date.' to '.$ending_date.'</h3>
    <h4 style="text-align:center">For</h4>
    <h3 style="text-align:center">'.$ward_name.'</h3>
    </div>
    <table class="table table-bordered" style="background: #FFFFFF">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Patient Name</th>
                <th>Reg No.</th>
                <th>Gender</th>
                <th>Age</th>                
                <th>Room Name</th>
                <th>Bed Number</th>
                <th>Discharge Time</th>
                <th>Discharge Resoan</th>
            </tr>
        </thead>
        
    <tbody>
    ';
    //get data from admission table
    $count = 1;
    while($admission_data=mysqli_fetch_assoc($admission)){
      $Registration_ID=$admission_data['Registration_ID'];
      $Admission_Date_Time=$admission_data['Admission_Date_Time'];
      $Discharge_time=$admission_data['Discharge_Date_Time'];
      $Bed_Name=$admission_data['Bed_Name'];
      $Admission_Status=$admission_data['Admission_Status'];
      $Discharge_reason=$admission_data['Discharge_Reason'];
      $ward_room_id=$admission_data['ward_room_id'];
      $Date_Of_Birth = $admission_data['Date_Of_Birth'];
      $patient_name=$admission_data['Patient_Name'];
      $Gender=$admission_data['Gender'];
      $Sponsor_ID=$admission_data['Sponsor_ID'];
      $patient_type=$admission_data['patient_type'];
      $date1 = new DateTime($Today);
      $date2 = new DateTime($Date_Of_Birth);
      $diff = $date1->diff($date2);
      $age = $diff->y . " Years, ";
      $age .= $diff->m . " Months, ";
      $age .= $diff->d . " Days";
        //get patient room number and ward name
        $patient_room_name=mysqli_query($conn,"SELECT room_name, Hospital_Ward_Name FROM tbl_ward_rooms wr, tbl_hospital_ward hw WHERE Hospital_Ward_ID='$ward_id' AND hw.Hospital_Ward_ID=wr.ward_id AND  ward_room_id='$ward_room_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($patient_room_name)>0){
          while($patient_room_rows=mysqli_fetch_assoc($patient_room_name)){
            $room_name=$patient_room_rows['room_name'];
            $hospital_ward_name=$patient_room_rows['Hospital_Ward_Name'];
          }
        }
    echo '
        <tr>
            <td>'.$count.'</td>
            <td>'.$patient_name.'</td>
            <td>'.$Registration_ID.'</td>
            <td>'.$Gender.'</td>
            <td>'.$age.'</td>
            <td>'.$room_name.'</td>
            <td>'.$Bed_Name.'</td>
            <td>'.$Discharge_time.'</td>
            <td>'.$Discharge_reason.'</td>
        </tr> 
    '; 
    $count++;
      // }
  }
    echo '
        <tbody></table>
    ';
}
//discharged patient with absocondee status
if(isset($_POST['ward_id_for_absoncond'])){
  $ward_id_for_absoncond = $_POST['ward_id_for_absoncond'];
  $ward_name = $_POST['ward_name'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $filter_gender=$_POST['filter_gender'];
  $start_age = $_POST['start_age'];
  $end_age = $_POST['end_age'];
  $agetype = $_POST['agetype'];
  if($start_age != "" && $end_age != ""){
    $filter_age = "AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) BETWEEN  $start_age  AND  $end_age";
  }
  else{
    $filter_age = "";
  }
  $starting_date = date("Y-m-d",strtotime($start_date));
  $ending_date = date("Y-m-d",strtotime($end_date));
  $admission=mysqli_query($conn,"SELECT Patient_Name,Gender, admission.Registration_ID,Date_Of_Birth, Admission_Date_Time, Discharge_Date_Time,Bed_Name, Admission_Status, Discharge_Reason, ward_room_id  FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='abscond' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') $filter_gender $filter_age") or die(mysqli_error($conn));
  echo '
  <div class="row" style="padding:10px">
  <h3 style="text-align:center">Patients Discharged Alive From '.$starting_date.' to '.$ending_date.'</h3>
  <h4 style="text-align:center">For</h4>
  <h3 style="text-align:center">'.$ward_name.'</h3>
  </div>
  <table class="table table-bordered" style="background: #FFFFFF">
      <thead>
        <tr>
          <th>S/N</th>
          <th>Patient Name</th>
          <th>Reg No.</th>
          <th>Gender</th>
          <th>Age</th>                
          <th>Room Name</th>
          <th>Bed Number</th>
          <th>Discharge Time</th>
          <th>Discharge Resoan</th>
      </tr>
      </thead>
      
  <tbody>
  ';
  //get data from admission table
  $count = 1;
  while($admission_data=mysqli_fetch_assoc($admission)){
      
      $Registration_ID=$admission_data['Registration_ID'];
      $Admission_Date_Time=$admission_data['Admission_Date_Time'];
      $Discharge_time=$admission_data['Discharge_Date_Time'];
      $Bed_Name=$admission_data['Bed_Name'];
      $Admission_Status=$admission_data['Admission_Status'];
      $Discharge_reason=$admission_data['Discharge_Reason'];
      $ward_room_id=$admission_data['ward_room_id'];
      $Date_Of_Birth = $admission_data['Date_Of_Birth'];
      $patient_name=$admission_data['Patient_Name'];
      $Gender=$admission_data['Gender'];
      $Sponsor_ID=$admission_data['Sponsor_ID'];
      $patient_type=$admission_data['patient_type'];
      $date1 = new DateTime($Today);
      $date2 = new DateTime($Date_Of_Birth);
      $diff = $date1->diff($date2);
      $age = $diff->y . " Years, ";
      $age .= $diff->m . " Months, ";
      $age .= $diff->d . " Days";
        //get patient room number and ward name
        $patient_room_name=mysqli_query($conn,"SELECT room_name, Hospital_Ward_Name FROM tbl_ward_rooms wr, tbl_hospital_ward hw WHERE Hospital_Ward_ID='$ward_id' AND hw.Hospital_Ward_ID=wr.ward_id AND  ward_room_id='$ward_room_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($patient_room_name)>0){
          while($patient_room_rows=mysqli_fetch_assoc($patient_room_name)){
            $room_name=$patient_room_rows['room_name'];
            $hospital_ward_name=$patient_room_rows['Hospital_Ward_Name'];
          }
        }
  echo '
          <tr>
            <td>'.$count.'</td>
            <td>'.$patient_name.'</td>
            <td>'.$Registration_ID.'</td>
            <td>'.$Gender.'</td>
            <td>'.$age.'</td>
            <td>'.$room_name.'</td>
            <td>'.$Bed_Name.'</td>
            <td>'.$Discharge_time.'</td>
            <td>'.$Discharge_reason.'</td>
          </tr> 
  '; 
  $count++;
    
}
  echo '
      <tbody></table>
  ';


}


//obd report popup
if(isset($_POST['Ward_id_for_obd'])){
  $ward_name = $_POST['ward_name'];
  $ward_id = $_POST['Ward_id_for_obd'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $filter_gender=$_POST['filter_gender'];
  $start_age = $_POST['start_age'];
  $end_age = $_POST['end_age'];
  $end_age = $_POST['end_age'];
  $agetype = $_POST['agetype'];
  if($start_age != "" && $end_age != ""){
    $filter_age = "AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) BETWEEN  $start_age  AND  $end_age";
  }
  else{
    $filter_age = "";
  }
  $starting_date = date("Y-m-d",strtotime($start_date));
  $ending_date = date("Y-m-d",strtotime($end_date));
  $admission_male=mysqli_query($conn,"SELECT count(Admision_ID) as male_count FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> '' AND Admission_Date_Time BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Male'  $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($admission_male)>0){
    while($admission_male_rows=mysqli_fetch_assoc($admission_male)){
      $male_admited=$admission_male_rows['male_count'];
    }
  }

  $admission_female=mysqli_query($conn,"SELECT count(Admision_ID) as female_count FROM tbl_admission as admi INNER JOIN tbl_patient_registration as reg_table ON admi.Registration_ID=reg_table.Registration_ID WHERE Hospital_Ward_ID='$ward_id' AND ward_room_id <> '' AND Admission_Date_Time BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Female'  $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($admission_female)>0){
    while($admission_female_rows=mysqli_fetch_assoc($admission_female)){
      $female_admited=$admission_female_rows['female_count'];
    }
  }

  //transferd into the ward male patient
  $transfer_in=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_in FROM tbl_transfer_out_in AS trasfer,tbl_admission as admi,tbl_patient_registration as reg_table WHERE admi.Admision_ID=trasfer.Admision_ID AND admi.Registration_ID=reg_table.Registration_ID AND trasfer.out_ward_id='$ward_id' AND trasfer.transfer_status='received' AND trasfer.transfer_out_date BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Male' $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($transfer_in)>0){
    while($transfer_in_patient=mysqli_fetch_assoc($transfer_in)){
      $ward_transfer_in_patient=$transfer_in_patient['count_transfer_in'];
    }
  }

    //transferd into the ward female patient
    $transfer_in_female=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_in FROM tbl_transfer_out_in AS trasfer,tbl_admission as admi,tbl_patient_registration as reg_table WHERE admi.Admision_ID=trasfer.Admision_ID AND admi.Registration_ID=reg_table.Registration_ID AND trasfer.out_ward_id='$ward_id' AND trasfer.transfer_status='received' AND trasfer.transfer_out_date BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Female' $filter_gender $filter_age") or die(mysqli_error($conn));
    if(mysqli_num_rows($transfer_in_female)>0){
      while($transfer_in_patient_female=mysqli_fetch_assoc($transfer_in_female)){
        $ward_transfer_in_patient_female=$transfer_in_patient_female['count_transfer_in'];
      }
    }

    //transferd Out the ward male patient
  $transfer_out=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_out FROM tbl_transfer_out_in AS trasfer,tbl_admission as admi,tbl_patient_registration as reg_table WHERE admi.Admision_ID=trasfer.Admision_ID AND admi.Registration_ID=reg_table.Registration_ID AND trasfer.in_ward_id='$ward_id' AND trasfer.transfer_status='received' AND trasfer.transfer_out_date BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Male' $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($transfer_out)>0){
    while($transfer_out_patient=mysqli_fetch_assoc($transfer_out)){
      $ward_transfer_out_patient=$transfer_out_patient['count_transfer_out'];
    }
  }

    //transferd Out the ward female patient
    $transfer_out_female=mysqli_query($conn,"SELECT count(transfer_id) as count_transfer_out FROM tbl_transfer_out_in AS trasfer,tbl_admission as admi,tbl_patient_registration as reg_table WHERE admi.Admision_ID=trasfer.Admision_ID AND admi.Registration_ID=reg_table.Registration_ID AND trasfer.in_ward_id='$ward_id' AND trasfer.transfer_status='received' AND trasfer.transfer_out_date BETWEEN  '$start_date' AND '$end_date' AND reg_table.Gender = 'Female' $filter_gender $filter_age") or die(mysqli_error($conn));
    if(mysqli_num_rows($transfer_out_female)>0){
      while($transfer_out_patient_female=mysqli_fetch_assoc($transfer_out_female)){
        $ward_transfer_out_patient_female=$transfer_out_patient_female['count_transfer_out'];
      }
    }

   //discharge live male
   $discharging_reason_male=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_male FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='alive' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Male' $filter_gender $filter_age") or die(mysqli_error($conn));
   if(mysqli_num_rows($discharging_reason_male)>0){
     while($discharging_male=mysqli_fetch_assoc($discharging_reason_male)){
       $no_alive_male=$discharging_male['count_condition_male'];
     }
   }

   //discharge live female
   $discharging_reason_female=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_female FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='alive' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Female' $filter_gender $filter_age") or die(mysqli_error($conn));
   if(mysqli_num_rows($discharging_reason_female)>0){
     while($discharging_female=mysqli_fetch_assoc($discharging_reason_female)){
       $no_alive_female=$discharging_female['count_condition_female'];
     }
   }

  //discharge dead male
  $discharging_reason_dead_male=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_male FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='dead' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Male' $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($discharging_reason_dead_male)>0){
    while($discharging_dead_male=mysqli_fetch_assoc($discharging_reason_dead_male)){
      $no_dead_male=$discharging_dead_male['count_condition_male'];
    }
  }

  //discharge dead female
  $discharging_reason_dead_female=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_female FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='dead' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Female' $filter_gender $filter_age") or die(mysqli_error($conn));
  if(mysqli_num_rows($discharging_reason_dead_female)>0){
    while($discharging_dead_female=mysqli_fetch_assoc($discharging_reason_dead_female)){
      $no_dead_female=$discharging_dead_female['count_condition_female'];
    }
  }

    //discharge abscondee male
    $discharging_reason_abscondee_male=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_male FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='absconde' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Male' $filter_gender $filter_age") or die(mysqli_error($conn));
    if(mysqli_num_rows($discharging_reason_abscondee_male)>0){
      while($discharging_abscondee_male=mysqli_fetch_assoc($discharging_reason_abscondee_male)){
        $no_abscondee_male=$discharging_abscondee_male['count_condition_male'];
      }
    }
  
    //discharge abscondee female
    $discharging_reason_abscondee_female=mysqli_query($conn,"SELECT count(Admision_ID) as count_condition_female FROM tbl_admission as admission,tbl_discharge_reason as reason,tbl_patient_registration as reg_table WHERE admission.Discharge_Reason_ID=reason.Discharge_Reason_ID AND  admission.Registration_ID=reg_table.Registration_ID AND reason.discharge_condition='absconde' AND admission.Hospital_Ward_ID='$ward_id' AND Discharge_Date_Time BETWEEN  '$start_date' AND '$end_date' AND Admission_Status IN('Discharged','Pending') AND reg_table.Gender = 'Female' $filter_gender $filter_age") or die(mysqli_error($conn));
    if(mysqli_num_rows($discharging_reason_abscondee_female)>0){
      while($discharging_abscondee_female=mysqli_fetch_assoc($discharging_reason_abscondee_female)){
        $no_abscondee_female=$discharging_abscondee_female['count_condition_female'];
      }
    }

//tota patient per day
$total_male = ($male_admited+$ward_transfer_in_patient)-($ward_transfer_out_patient+$no_alive_male+$no_dead_male+$no_abscondee_male);
$total_female = ($female_admited+$ward_transfer_in_patient_female)-($ward_transfer_out_patient_female+$no_alive_female+$no_dead_female+$no_abscondee_female);
$count = 1;
  echo '
 <div class="row" style="padding:10px">
 <h3 style="text-align:center">In-Patients occupied Bed days(OBD) Report from '.$starting_date.' to '.$ending_date.'</h3>
 <h4 style="text-align:center">For</h4>
 <h3 style="text-align:center">'.$ward_name.'</h3>
 </div>
  <table class="table table-bordered text-center" style="background: #FFFFFF">
    <thead>
      <tr>
        <th></th>
        <th colspan=2 >Admission</th>
        <th colspan=2>Transfer In</th>
        <th colspan=2>Transfer Out</th>
        <th colspan=2>Discharge Alive</th>
        <th colspan=2>Discharge Dead</th>
        <th colspan=2>Abscondee</th>
        <th colspan=2>Patient per Day</th>
      </tr>
    </thead>
    <tbody>
       <tr>
          <th>S/N</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
          <th>Male</th>
          <th>Female</th>
      </tr>
      <tr>
          <td>'.$count++.'</td>
          <td>'.$male_admited.'</td>
          <td>'.$female_admited.'</td>
          <td>'.$ward_transfer_in_patient.'</td>
          <td>'.$ward_transfer_in_patient_female.'</td>
          <td>'.$ward_transfer_out_patient.'</td>
          <td>'.$ward_transfer_out_patient_female.'</td>
          <td>'.$no_alive_male.'</td>
          <td>'.$no_alive_female.'</td>
          <td>'.$no_dead_male.'</td>
          <td>'.$no_dead_female.'</td>
          <td>'.$no_abscondee_male.'</td>
          <td>'.$no_abscondee_female.'</td>
          <td>'.$total_male.'</td>
          <td>'.$total_female.'</td>
      </tr>
    </tbody>
  </table>';
  $count++;
}
?>