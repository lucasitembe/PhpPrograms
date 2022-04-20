<?php

include("./includes/connection.php");
session_start();
$temp = 1;
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
} if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
  }
  
  if (isset($_GET['patient_id'])) {
   $patient_id = $_GET['patient_id'];
  }
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }

$select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_patien_details);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_patien_details)) {
        $Member_Number = $row['Member_Number'];
        $Patient_Name = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Gender = $row['Gender'];
        $Sponsor = $row['Guarantor_Name'];
        $DOB = $row['Date_Of_Birth'];
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}
$age = date_diff(date_create($DOB), date_create('today'))->y;

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>PEDIATRIC RECORDS"
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";

$htm.= '<center><table width =100% id="nurse_obsv" class="table table-striped" border=1 style="border-collapse: collapse;" class="table table-striped">';
$htm.= '<thead>
        <tr></tr>
               <tr>
                <td style="font-size:20px;"><b>Time</b></td>
                <td style="font-size:20px;"><b>Heart Rate</b></td>
                <td style="font-size:20px;"><b>Respiratory Rate</b></td>
                <td style="font-size:20px;"><b>PSO2</b></td>
                <td style="font-size:20px;"><b>Temperature</b></td>
                <td style="font-size:20px;"><b>Blood Pressure Sytolic</b></td>
                <td style="font-size:20px;"><b>Blood Pressure Diasotlic</b></td>
                <td style="font-size:20px;"><b>Pulse Pressure</b></td>
                <td style="font-size:20px;"><b>Map</b></td>
                <td style="font-size:20px;"><b>Saved Time</b></td>
                <td style="font-size:20px;"><b>Saved By</b></td>
              </tr>
        </thead>';
        $select_date=mysqli_query($conn,"SELECT DISTINCT date(saved_time)  FROM pediatric_graph where Registration_ID='$Registration_ID' order by pediatric_graph_ID DESC");
    //    die("SELECT date(saved_time)  FROM pediatric_graph where Registration_ID='$Registration_ID'");
        while($takedate=mysqli_fetch_array($select_date)){
            $saved_time=$takedate[0];
        
            $select_pediatric_graph=mysqli_query($conn,"SELECT pd.pediatric_graph_ID, pd.heart_rate,pd.respiratory_rate, pd.pso2, pd.temperature, pd.blood_pressure_sytolic, pd.blood_pressure_diasotlic, pd.pulse_pressure, pd.map, pd.saved_time, pd.time_min, pd.Registration_ID, pd.Employee_ID, pd.consultation_ID,emp.Employee_Name FROM pediatric_graph as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.Registration_ID='$Registration_ID' AND date(saved_time)='$saved_time'");
            $htm.= "<tr><td style='font-size:20px;text-align:center;' colspan='11'><b> $saved_time</b></td></tr>";
        while ($row = mysqli_fetch_array($select_pediatric_graph)) {
            $date = date('Y-m-d H:i',strtotime($row['time_min']));
            $time = date('H:i',strtotime($row['time_min']));
            $splitTimeStamp = explode(":",$time);
            $TimeStamp_hour = $splitTimeStamp[0];
            $TimeStamp_min = $splitTimeStamp[1];
           
            $htm.= "<tr >";
            $htm.= "<td style='font-size:15px;'>" . $time . ".</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['heart_rate'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['respiratory_rate'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['pso2'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['temperature'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['blood_pressure_sytolic'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['blood_pressure_diasotlic'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['pulse_pressure'] . "</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['map']."</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['saved_time']."</td>";
            $htm.= "<td style='font-size:15px;'>" . $row['Employee_Name']."</td>";
            $htm.= "</tr>";
        }
    }
    
        $htm.= "</table></center>";

include("./MPDF/mpdf.php");
$mpdf=new mPDF('s','A4-L', 0, '', 15,15,20,40,15,35, 'L');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered by GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
