<?php
     session_start();
     include("./includes/connection.php");
     if(isset($_POST["recovery_predata"])){ ?>

     <?php }


if(isset($_POST['BP'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $fx = $_POST['fx'];
    $fy = $_POST['fy'];
    $fz = $_POST['fz'];
    $insert_BP = mysqli_query($conn, "INSERT INTO tbl_recovery_bp_readings(Registration_ID,anasthesia_record_id,saved_by, fx,fy, fz)
    VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$fx','$fy', '$fz')") or die(mysqli_error($conn));
    if(!$insert_BP){
        echo "Could not insert BP readings";
    }else{
        echo "BP readings saved";
    }
 
}

if(isset($_POST['HR'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
   
    $sx = $_POST['sx'];
    $sy = $_POST['sy'];    
    $insert_HR = mysqli_query($conn, "INSERT INTO tbl_recovery_hr_readings(Registration_ID,anasthesia_record_id,Employee_ID, sx,sy)VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$sx','$sy')") or die(mysqli_error($conn));
    if(!$insert_HR){
        echo "Could not insert HR readings";
    }else{
        echo "HR readings saved";
    }
}

if(isset($_POST['MAP_insert'])){
    $Registration_ID = $_POST['Registration_ID'];    
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $zx = $_POST['zx'];
    $zy = $_POST['zy'];  
    
    $insert_MAP = mysqli_query($conn, "INSERT INTO tbl_recovery_map_readings(Registration_ID,anasthesia_record_id,Employee_ID, zx,zy) VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$zx','$zy')") or die(mysqli_error($conn));
    if(!$insert_MAP){
        echo "Could not insert MAP readings";
    }else{
        echo "MAP readings saved";
    }
}
if(isset($_POST['temp_insert'])){
    $Registration_ID = $_POST['Registration_ID'];    
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $tx = $_POST['tx'];
    $ty = $_POST['ty'];  
    
    $insert_MAP = mysqli_query($conn, "INSERT INTO tbl_recovery_temp_readings(Registration_ID,anasthesia_record_id,Employee_ID, tx,ty) VALUES('$Registration_ID','$anasthesia_record_id','$Employee_ID','$tx','$ty')") or die(mysqli_error($conn));
    if(!$insert_MAP){
        echo "Could not insert Temperature readings";
    }else{
        echo "Temperature readings saved";
    }
}

if(isset($_POST['HRreadings'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $select_hr_readings  = "SELECT sx,sy FROM tbl_recovery_hr_readings WHERE
                               Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($result=mysqli_query($conn,$select_hr_readings)) {

        if (($num = mysqli_num_rows($result)) > 0) {
            $d = array();
            while ($row = mysqli_fetch_assoc($result)) {
            $d['sx'] = $row['sx'];
            $d['sy'] = $row['sy'];
            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}

if(isset($_POST['MAPReading'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];

    $select_hr_readings  = "SELECT zx,zy FROM tbl_recovery_map_readings WHERE
                               Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($result=mysqli_query($conn,$select_hr_readings)) {

        if (($num = mysqli_num_rows($result)) > 0) {
            $d = array();
            while ($row = mysqli_fetch_assoc($result)) {
            $d['zx'] = $row['zx'];
            $d['zy'] = $row['zy'];
            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}


if(isset($_POST['BPreadings'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
  
    $select_bp_readings  = "SELECT fy,fx, fz FROM tbl_recovery_bp_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
                              $data  = array();

  if ($resultsbp=mysqli_query($conn,$select_bp_readings)) {

        if (($num = mysqli_num_rows($resultsbp)) > 0) {
            $d = array();
            while ($rowbp = mysqli_fetch_assoc($resultsbp)) {
            $d['fx'] = $rowbp['fx'];
            $d['fy'] = $rowbp['fy'];
            $d['fz'] = $rowbp['fz'];

            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}



if(isset($_POST['tempreadings'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
  
    $select_bp_readings  = "SELECT ty,tx FROM tbl_recovery_temp_readings WHERE Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'";
    $data  = array();
  if ($resultsbp=mysqli_query($conn,$select_bp_readings)) {
        if (($num = mysqli_num_rows($resultsbp)) > 0) {
            $d = array();
            while ($rowbp = mysqli_fetch_assoc($resultsbp)) {
            $d['tx'] = $rowbp['tx'];
            $d['ty'] = $rowbp['ty'];

            array_push($data,$d);
        }    

    echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }else {
    echo mysqli_error($conn);
  }
}

if(isset($_POST['Arrival_date'])){
    $Arrival_date  = mysqli_real_escape_string($conn, $_POST['Arrival_date']);
    $Time_in = mysqli_real_escape_string($conn, $_POST['Time_in']);

    $ventilated  = mysqli_real_escape_string($conn, $_POST['ventilated']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $Airway  = mysqli_real_escape_string($conn, $_POST['Airway']);
    $ETTs = mysqli_real_escape_string($conn, $_POST['ETTs']);
    $Ettsize = mysqli_real_escape_string($conn, $_POST['Ettsize']);
    $ventilateddata  = mysqli_real_escape_string($conn, $_POST['ventilateddata']);
    $anasthesia_ID = mysqli_real_escape_string($conn, $_POST['anasthesia_ID']);

    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $o2by = $_POST['o2by'];
    $save_arrival_details = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_recovery_data(Arrival_date,Time_in, ventilated, condition_state, Airway,ETTs, o2by, ventilateddata,Ettsize, anasthesia_record_id, Saved_by) VALUES('$Arrival_date', '$Time_in', '$ventilated', '$condition', '$Airway', '$ETTs', '$o2by', '$ventilateddata', '$Ettsize','$anasthesia_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($save_arrival_details){
        echo "Saved successful";
    }else{
        echo "Failed to save";
    }
}

if(isset($_POST['patient_ondischarge'])){
    $others_meds  = mysqli_real_escape_string($conn, $_POST['others_meds']);
    $Analgesia = mysqli_real_escape_string($conn, $_POST['Analgesia']);
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    $consultation_ID =$_POST['consultation_ID'];
    $vitalsondischarge  = mysqli_real_escape_string($conn, $_POST['vitalsondischarge']);
    $dataondischarge = mysqli_real_escape_string($conn, $_POST['dataondischarge']);
    $o2by = $_POST['o2by'];

    $anasthesia_ID = mysqli_real_escape_string($conn, $_POST['anasthesia_ID']);
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    
    $save_arrival_details = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_recovery_discharge (others_meds, Analgesia_state, vitalsondischarge,dataondischarge,   anasthesia_record_id, Saved_by) VALUES('$others_meds', '$Analgesia', '$vitalsondischarge', '$dataondischarge',   '$anasthesia_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($save_arrival_details){
        
        $updated_anaesthesia_record_chart = mysqli_query($conn, "UPDATE tbl_anasthesia_record_chart SET Anaesthesia_status='Completed' WHERE anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
        if($updated_anaesthesia_record_chart){
            echo "Saved successful";
        }else{
            echo "Saved but Anaesthesia not complete please try again";
        }
    }else{
        echo "Failed to save";
    }
}

if(isset($_POST['view_mntainance_vitals'])){
    $Registration_ID = $_POST['Registration_ID'];
    $anasthesia_record_id = $_POST['anasthesia_record_id'];
    
    $select_maintanance_vital = mysqli_query($conn, "SELECT * FROM tbl_recovery_maintanance_vital av WHERE  anasthesia_record_id='$anasthesia_record_id' AND Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
    if((mysqli_num_rows($select_maintanance_vital))>0){
         while($Vitals_meaintanance_rw= mysqli_fetch_assoc($select_maintanance_vital)){?>
            <tr>
            <td> <input type="text"  class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['SPO2']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['ETCO2']; ?>"> </td>
            <td> <input type="text"  class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['FIO2']; ?>"> </td>
            <td> <input type="text"   class="form-control" readonly="readonly" value=" <?php echo $Vitals_meaintanance_rw['Temp']; ?>"> </td>
            <td><input type="text" class="form-control" value="<?php echo $Vitals_meaintanance_rw['created_at']; ?>"></td>
        </tr><?php
         }
    } ?>
        <tr>
            <td> <input type="text" id="SPO2normal"  class="form-control" value=""> </td>
            <td> <input type="text" id="ETCO2"  class="form-control" value=""> </td>
            <td> <input type="text" id="FIO2"  class="form-control" value=""> </td>
            <td> <input type="text" id="Temp"  class="form-control" value=""> </td>
            <td><input class="art-button-green" style="width:25%;" type="button" id='Vitals_meaintanance' onclick="add_Vitals_meaintanance()" value="Save"></td>
        </tr>
    <?php
}

//insert maintanance vitals
if(isset($_POST['Vitals_meaintanance_add'])){
    $SPO2 = mysqli_real_escape_string($conn,  $_POST["SPO2"]);
    $ETCO2 = mysqli_real_escape_string($conn,  $_POST["ETCO2"]);
    $FIO2 = mysqli_real_escape_string($conn,  $_POST["FIO2"]);
    $Temp = mysqli_real_escape_string($conn,  $_POST["Temp"]);
    // $ECG = mysqli_real_escape_string($conn,  $_POST["ECG"]);
    // $MAC = mysqli_real_escape_string($conn,  $_POST["MAC"]);
    $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
    $anasthesia_record_id_Post = $_POST['anasthesia_record_id'];
    $Registration_ID = $_POST['Registration_ID'];
    //select anesthesia record id
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' ORDER BY anasthesia_record_id DESC LIMIT 1";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }else{
        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
        $anasthesia_record_id=mysqli_insert_id($conn);
        
    }
    
    $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_recovery_maintanance_vital( SPO2, ETCO2, FIO2,Temp,   anasthesia_record_id, Registration_ID, saved_by) VALUES ( '$SPO2', '$ETCO2', '$FIO2', '$Temp', '$anasthesia_record_id_Post', '$Registration_ID', '$Employee_ID')") or die("Couldn't insert data ".mysqli_error($conn));
    if(!$renalto_medication){
        echo  "fail";
    } else{        
        echo "success";
    }
}
