<?php
include("./includes/connection.php");
if (isset($_POST['Registration_ID']) && isset($_POST['consultation_ID']) ) {
  $Registration_ID = $_POST['Registration_ID'];
  $consultation_ID = $_POST['consultation_ID'];
    
  $select_pediatric_graph="SELECT pediatric_graph_ID, heart_rate,respiratory_rate, pso2, temperature, blood_pressure_sytolic, blood_pressure_diasotlic, pulse_pressure, map, saved_time, time_min, Registration_ID, Employee_ID, consultation_ID FROM pediatric_graph WHERE Registration_ID='$Registration_ID' and consultation_ID='$consultation_ID' AND DATE(saved_time)=CURDATE()";
    $data  = array();

  if ($result=mysqli_query($conn,$select_pediatric_graph)) {

    if (($num = mysqli_num_rows($result)) > 0) {
      $d = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $date = date('Y-m-d H:i',strtotime($row['time_min']));
        $time = date('H:i',strtotime($row['time_min']));
        $splitTimeStamp = explode(":",$time);
        $TimeStamp_hour = $splitTimeStamp[0];
        $TimeStamp_min = $splitTimeStamp[1];
        // $d['TimeStamp_date'] = $date;
        // $d['TimeStamp_min'] = $TimeStamp_min;
        // $d['TimeStamp_hour'] = $TimeStamp_hour;
        // $d['time_min'] = $row['time_min'];
        $final_time_systolic=$TimeStamp_hour+(($TimeStamp_min/60));
        $d['final_time_systolic'] = $final_time_systolic;
        $d['time'] = $time;
        $d['blood_pressure_sytolic'] = $row['blood_pressure_sytolic'];
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

 ?>
