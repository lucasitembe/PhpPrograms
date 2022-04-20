<?php 
include("./includes/connection.php");
include("middleware/dialysisi_function.php");
@session_start();
$prescription_id =  mysqli_real_escape_string($conn,$_POST['prescription_id']);

// echo $prescription_id;
//if(isset ($_POST['SaveObservationChartbtn'])){
//    $Time=  mysqli_real_escape_string($conn,$_POST['Time']);
//    $BP=  mysqli_real_escape_string($conn,$_POST['BP']);
//    $HR=  mysqli_real_escape_string($conn,$_POST['HR']);
//    $QB=  mysqli_real_escape_string($conn,$_POST['QB']);
//    $QD=  mysqli_real_escape_string($conn,$_POST['QD']);
//    $AP=  mysqli_real_escape_string($conn,$_POST['AP']);
//    $VP=  mysqli_real_escape_string($conn,$_POST['VP']);
//    $FldRmvd=  mysqli_real_escape_string($conn,$_POST['FldRmvd']);
//    $Heparin=  mysqli_real_escape_string($conn,$_POST['Heparin']);
//    $Saline=  mysqli_real_escape_string($conn,$_POST['Saline']);
//    $UFR=  mysqli_real_escape_string($conn,$_POST['UFR']);
//    $TMP=  mysqli_real_escape_string($conn,$_POST['TMP']);
//    $BVP=  mysqli_real_escape_string($conn,$_POST['BVP']);
//    $Access=mysqli_real_escape_string($conn,$_POST['Access']);
//    $Notes=  mysqli_real_escape_string($conn,$_POST['Notes']);
//    $Patient_reg=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
//    $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
//    $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
//    $AttendanceDate=  mysqli_real_escape_string($conn,$_POST['Attendance_Date']);
//    if(!empty($AttendanceDate)){
//       $Attendance_Date = $AttendanceDate; 
//    }
//  
//    $data_seve = array(array(
//      "Patient_reg"=>$Patient_reg,
//      "Attendance_Date"=>$AttendanceDate,
//      "Time"=>$Time,
//      "BP"=>$BP,
//      "HR"=>$HR,
//      "QB"=>$QB,
//      "QD"=>$QD,
//      "AP"=>$AP,
//      "VP"=>$VP,
//      "FldRmvd"=>$FldRmvd,
//      "Saline"=>$Saline,
//      "UFR"=>$UFR,
//      "TMP"=>$TMP,
//      "BVP"=>$BVP,
//      "Access"=>$Access,
//      "Heparin"=>$Heparin,
//      "Notes"=>$Notes,
//      "dialysis_details_ID"=>$dialysis_details_ID,
//      "Consultant_employee"=>$Consultant_employee
//      ) );
//
//    if(Save_Observation_Chart(json_encode($data_seve))>0){
//        $Registration_ID = $Patient_reg;
//    }
//}
if(isset($_POST['showpatientprescription'])){
   $prescription_id =  mysqli_real_escape_string($conn,$_POST['prescription_id']);
   get_prescription_details($prescription_id);
}

if($_POST['updatestatusdone']){
    $done_time=date('Y-m-d H:m:s');
    // echo $done_time;
  $prescription_id =  mysqli_real_escape_string($conn,$_POST['prescription_id']);
  $id =  mysqli_real_escape_string($conn,$_POST['id']);
  $Consultant_employee = $_SESSION['userinfo']["Employee_ID"];
  $upd = mysqli_query($conn, "INSERT INTO tbl_dialysis_cycle_status (status, prescription_id, session_id, employee) "
          . "VALUES('Done','$prescription_id','$id','$Consultant_employee')") or die(mysqli_error($conn));
  if($upd){
      $update_prescription=mysqli_query($conn,"UPDATE tbl_dialysis_inpatient_prescriptions SET status='Done',done_on='$done_time' WHERE prescription_id='$prescription_id'");
      if($update_prescription){
        //   echo "<script>location.reload(true);</script>";
          echo "Status updated";
        }else{
            echo "Status not updated";
        }
    
    // update_prescription_status($prescription_id);
      get_prescription_details($prescription_id);
  }else{
      echo 'fail';
  }
}

function get_prescription_details($prescription_id){
    global $conn;
     $query = "SELECT * FROM `tbl_dialysis_inpatient_prescriptions` WHERE prescription_id='$prescription_id'";
    $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
    $html = '
        <table class="table" style="background-color:#fff;">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>STATUS</th>
                    <th>INDICATION</th>
                    <th>DIAGNOSIS</th> 
                    <th>MEDICATION</th>
                    <th>ORDERED DATE</th> 
                    <th>SESSION CYCLE</th> 
                    <th>SESSION TIME</th> 
                    <th>DONE BY</th> 
                    <th>DONE DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        <tbody>';
    if(mysqli_num_rows($result) > 0){
    $index = 1;
    while($row = mysqli_fetch_assoc($result)){
        $sessioncicle = $row['sessioncicle']*$row['duartions'];
        // for($i =0; $i < $sessioncicle; $i++){
            for($i =0; $i < 1; $i++){
            $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee,date FROM tbl_dialysis_cycle_status WHERE prescription_id='$prescription_id' AND session_id='$index'"));
            $nd = $result['employee'];
            $donedate = $result['date'];
            if($nd > 0){
                $initianame = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$nd'"))['Employee_Name'];
                $html.='
                    <tr>
                        <td>'.$index.'</td>
                        <td>Done</td>
                        <td>'.$row['indication'].'</td>
                        <td>'.$row['diagnosis'].'</td>
                        <td>'.$row['medication'].'</td>
                        <td>'.$row['ordered_on'].'</td>
                        <td>'.$index.'</td>
                        <td>'.$row['sessiontime'].' '.$row['sessiontimeunits'].'</td>
                        <td>'.$initianame.'</td>
                        <td>'.$donedate.'</td>
                        <td><input type="button" class="btn btn-sm btn-primary" disabled value="DONE" onclick="updateStatus('.$index.','.$prescription_id.')"></td>
                    </tr>';
            }else{
                $html.='
                    <tr>
                        <td>'.$index.'</td>
                        <td>Not done</td>
                        <td>'.$row['indication'].'</td>
                        <td>'.$row['diagnosis'].'</td>
                        <td>'.$row['medication'].'</td>
                        <td>'.$row['ordered_on'].'</td>
                        <td>'.$index.'</td>
                        <td>'.$row['sessiontime'].' '.$row['sessiontimeunits'].'</td>
                        <td></td>
                        <td></td>
                        <td><input type="button" class="btn btn-sm btn-primary" value="DONE" onclick="updateStatus('.$index.','.$prescription_id.')"></td>
                    </tr>';
            }
            $index++;
        }
        //$html .= $sessioncicle ;
    }

    }else{
        $html .='NO RECORD FOUND';
    }

    $html .= "<tbody></table>";
    
    echo $html;
}
?>