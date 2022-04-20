<?php
include("./includes/connection.php");
$temp = 1;
$filter = "   AND Admission_Status IN ('Admitted','pending')";

if (isset($_GET['Sponsor'])) {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Search_Patient_by_number = filter_input(INPUT_GET, 'Search_Patient_by_number');

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = " AND ad.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
//echo $ward;exit;
    if (!empty($ward) && $ward != 'All') {
        $filter .= " AND ad.Hospital_Ward_ID  = $ward";
    }

    if (!empty($Patient_Name)) {
        $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
    }
    if (!empty($Search_Patient_by_number)) {
        $filter .="  AND pr.Registration_ID like '%$Search_Patient_by_number%'";
    }
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

echo '<center><table width ="100%" id="admittedpatientslist">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>NEXT OF KIN</b></th>
                <th><b>NEXT OF KIN NO</b></th>
                <th><b>ADMISSION DATE</b></th>
                <th><b>WARD</b></th>
             </tr>
         </thead>";

        //  die("SELECT Doctor_Status,Admission_Status,Admission_Date_Time,pr.Registration_ID,pr.Date_Of_Birth,ad.Admision_ID,cd.consultation_ID,pr.Patient_Name,pr.Gender,sp.Guarantor_Name,Kin_Name,Kin_Phone,ad.Hospital_Ward_ID,Bed_Name,ad.ward_room_id FROM 
        //  tbl_patient_registration pr,
        //  tbl_admission ad,
        //  tbl_check_in_details cd,
        //  tbl_sponsor sp,
        //  tbl_hospital_ward hw
        //      WHERE 
        //      pr.Registration_ID=ad.Registration_ID AND 
        //      pr.Sponsor_ID=sp.Sponsor_ID AND 
        //      ad.Admision_ID=cd.Admission_ID 
        //      AND ad.Registration_ID=cd.Registration_ID 
        //      AND ad.Hospital_Ward_ID = hw.Hospital_Ward_ID  
        //      AND Admission_Status IN ('Admitted','pending') $filter 
        //      AND ward_type='ordinary_ward' GROUP BY cd.Admission_ID ORDER BY  cd.Admission_ID DESC LIMIT 20");


$sql = "SELECT (SELECT consultation_ID FROM tbl_check_in_details WHERE Registration_ID = pr.Registration_ID GROUP BY Check_In_Details_ID ORDER BY Check_In_Details_ID DESC LIMIT 1 ) as un, Doctor_Status,Admission_Status,Admission_Date_Time,pr.Registration_ID,pr.Date_Of_Birth,ad.Admision_ID,cd.consultation_ID,pr.Patient_Name,pr.Gender,sp.Guarantor_Name,Kin_Name,Kin_Phone,ad.Hospital_Ward_ID,Bed_Name,ad.ward_room_id FROM tbl_patient_registration pr, tbl_admission ad, tbl_check_in_details cd, tbl_sponsor sp, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID AND pr.Sponsor_ID=sp.Sponsor_ID AND ad.Admision_ID=cd.Admission_ID AND ad.Registration_ID=cd.Registration_ID AND ad.Hospital_Ward_ID = hw.Hospital_Ward_ID AND Admission_Status IN ('Admitted','pending') AND Admission_Status IN ('Admitted','pending') $filter AND ward_type='ordinary_ward' GROUP BY un ORDER BY cd.Check_In_Details_ID DESC LIMIT 20 ";


$select_patient_ward = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_patient_ward)) {
    $ward_room_id = $row['ward_room_id'];
    //AGE FUNCTION
    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
   if($row['Admission_Status']=="pending"){
       $background="background:green;color:white;padding:5px;' title='THIS PATIENT IS WAITING FOR BILLING PROCESS'";
    }else{
        $background="'";
        $Admission_Date_Time = date("Y-m-d", strtotime($row['Admission_Date_Time']));
            if($Admission_Date_Time == $Today){
            $background="background: #fcebfc;padding:5px;font-weight:bold;font-size:15px;' title='THIS PATIENT WAS ADMITTED TODAY'";
            }
        // $background="background:green;color:white;padding:5px;";
    }
    $Doctor_Status=$row['Doctor_Status'];
if($Doctor_Status=="initial_pending"&&$row['Admission_Status']!="pending"){
  $back_color="greenyellow";
  $background="background:greenyellow;color:#000000;padding:5px;font-weight:bold;font-size:17px;' title='THIS PATIENT IS WAITING FOR NURSE DISCHARGE'";
}


    echo "<tr style='$background><td id='thead'>" . $temp . "</td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID']. "&consultation_ID=" . $row['consultation_ID']  . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un'] . "' target='_parent' style='text-decoration: none;$background'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un'] . "' target='_parent' style='text-decoration: none;$background'>" . $row['Registration_ID'] . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . $row['Gender'] . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . $age . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . $row['Guarantor_Name'] . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . ucwords(strtolower($row['Kin_Name'])) . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . $row['Kin_Phone'] . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . $row['Admission_Date_Time'] . "</a></td>";
    echo "<td><a href='nursecommunicationpage.php?Registration_ID=" . $row['Registration_ID'] . "&Admision_ID=" . $row['Admision_ID']. "&consultation_ID=" . $row['un']  . "' target='_parent' style='text-decoration: none;$background'>" . getWardName($row['Hospital_Ward_ID']) . "<br /> ". getRoomName($row['ward_room_id'],$row['Hospital_Ward_ID'])."<br />".$row['Bed_Name']."</a></td>";

    
    $temp++;
} echo "</tr>";


function getRoomName($ward_room_id){
    global $conn;
    // $room="";
    // $wardId = $wardId; 
    $sql="SELECT room_name 
          FROM tbl_ward_rooms
          WHERE ward_room_id = '$ward_room_id'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($result)){
        extract($row);
    }
    
    $roomName = $room_name;
    return $roomName;
}

function getWardName($wardId){
    global $conn;
    $sql = "SELECT Hospital_Ward_Name 
            FROM tbl_hospital_ward WHERE Hospital_Ward_ID = '$wardId'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($result)){
        extract($row);
    }
    
    $wardId = $Hospital_Ward_Name;
    return $wardId;
}

?>
</table>
</center>
