
<?php
@session_start();
include("./includes/connection.php");

//$filter = "   AND Admission_Status='Admitted'  AND DATE(ad.Admission_Date_Time)=DATE(NOW())";
$filter = "   AND Admission_Status='Admitted'";

if(isset($_GET['Sponsor'])){
    
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Search_Patient_number = filter_input(INPUT_GET, 'Search_Patient_number');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$ward= filter_input(INPUT_GET, 'ward');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Admission_Status='Admitted' AND ad.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

//echo $ward;exit;
if (!empty($ward) && $ward != 'All') {
    $filter .= " AND ad.Hospital_Ward_ID  = $ward";
}

if (!empty($Patient_Name)) {
    //die("$Patient_Name");
    $filter .="  AND pr.Patient_Name LIKE '%$Patient_Name%'";
}
if (!empty($Search_Patient_number)) {
    //die("$Patient_Name");
    $filter .="  AND pr.Registration_ID ='$Search_Patient_number'";
}
//die($Patient_Name);
}

//echo $filter;

//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end

echo '<center><table width ="100%" id="admittedpatientslist">';
echo "<thead>
             <tr >
                <th style='width:2%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>WARD</b></th>
                <th><b>ADMISSION DATE</b></th>
             </tr>
         </thead>";

$sql= "SELECT pr.Registration_ID,ad.Admision_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,sp.Guarantor_Name,pr.Phone_Number,hw.Hospital_Ward_Name,ad.Admission_Date_Time ,consultation_ID  
				FROM 
				tbl_patient_registration pr,
				tbl_admission ad,
				tbl_check_in_details cd,
				tbl_sponsor sp,
				tbl_hospital_ward hw
					WHERE 
					pr.Registration_ID=ad.Registration_ID AND 
					pr.Sponsor_ID=sp.Sponsor_ID AND 
					ad.Admision_ID=cd.Admission_ID AND 
					ad.Hospital_Ward_ID = hw.Hospital_Ward_ID AND ward_room_id<>'0' $filter GROUP BY pr.Registration_ID  ORDER BY ad.Admission_Date_Time DESC LIMIT 10";


$select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$sn = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
   $Registration_ID=$row['Registration_ID'];
   $Admision_ID = $row['Admision_ID'];
    $sql_select_consultation_result=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC limit 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_consultation_result)>0){
        $consultation_ID=mysqli_fetch_assoc($sql_select_consultation_result)['consultation_ID'];
    }else{
        $consultation_ID="";
    }
    //AGE FUNCTION
    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";

    echo "<tr><td>" . $sn . "</td>";
    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" . $consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";

    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";

    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Hospital_Ward_Name'] . "</a></td>";

    echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_ID=" .$consultation_ID . "&Admision_ID=".$Admision_ID."' target='_parent' style='text-decoration: none;'>" . $row['Admission_Date_Time'] . "</a></td>";
    $sn++;
    ?>
    <?php
} echo "</tr>";
?></table></center>
