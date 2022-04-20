<?php
    @session_start();
    include("./includes/connection.php");
  

    $Date_From = mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_GET['Date_To']);
    $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
    $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND ir.event_date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    $display = 'True';
}else{
    $display = 'False';
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND ir.Registration_ID = '$Patient_Number'";
}
  
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    ?>

    <?php
    $html = '<center><table width ="100%" style="background-color:white;" id="patients-list">';
    $html .= "<thead>
                <tr >
                    <td><b>SN</b></td>
                    <td><b>REG NO</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>DATE</b></td>
                    <td><b>INCIDENT/EVENT</b></td>
                    <td><b>ACTION TAKEN </b></td>
                    <td><b>REPORTED BY</b></td>
                </tr>
          </thead>
          <tbody>";
    if($display=='True'){
        $sn=1;

        $mysql_select_patient=mysqli_query($conn,"SELECT incident_record_id, ir.Registration_ID, ir.Employee_ID, `event`,`action`, event_date,pr.Patient_Name,e.Employee_Name"
                . " FROM tbl_dialysis_incident_records AS ir, tbl_patient_registration AS pr,tbl_employee as e WHERE pr.Registration_ID =ir.Registration_ID AND e.Employee_ID =ir.Employee_ID $filter ") or die(mysqli_error($conn));
        
            while($row_value = mysqli_fetch_assoc($mysql_select_patient)){         
            $html .= '
                <tr>
                    <td>'.$sn.'</td>
                    <td>'.$row_value["Registration_ID"].'</td>
                    <td>'.ucwords(strtolower($row_value["Patient_Name"])).'</td>
                    <td>'.$row_value["event_date"].'</td>
                    <td width="30%">'.$row_value["event"].'</td>
                    <td width="20%">'.$row_value["action"].'</td>
                    <td>'.ucwords(strtolower($row_value["Employee_Name"])).'</td>
                </tr>';
            $sn++;
        } 
    }
$html .= '</tbody></table></center>';
echo $html;
?>

