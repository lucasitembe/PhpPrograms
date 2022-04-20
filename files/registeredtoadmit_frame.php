<?php

include("./includes/connection.php");
$filter = '';
echo "<link rel='stylesheet' href='fixHeader.css'>";

if (isset($_GET['Sponsor'])) {

    $Search_Patient = filter_input(INPUT_GET, 'Search_Patient');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
    $Phone_Number = filter_input(INPUT_GET, 'Phone_Number');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter = "  AND sp.Sponsor_ID=$Sponsor";
    }

    if (!empty($Search_Patient)) {
        $filter .="  AND pr.Patient_Name like '%$Search_Patient%'";
    }

    if (!empty($Patient_Number)) {
        $filter .="  AND pr.Registration_ID like '%$Patient_Number%'";
    }

    if (!empty($Phone_Number)) {
        $filter .="  AND pr.Phone_Number like '%$Phone_Number%'";
    }
}
?>
<style>
    .linkstyle{
        color:#000000;
    }
</style>
    <?php
//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}


echo '<center><table width ="100%" id="patientList" class="fixTableHead">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NUMBE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>AGE</b></th>
                <th><b>GENDER</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>MEMBER NUMBER</b></th>
                <th>&nbsp;</th>
             </tr>
         </thead>";

$select_Filtered_Patients = mysqli_query($conn,
        "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id $filter order by Registration_ID Desc limit 10") or die(mysqli_error($conn));
$temp = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

    //AGE FUNCTION
    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";


    echo "<tr>"
    . "<td width ='2%' id='thead'  class='linkstyle'>" . $temp . "</td>";
    echo "<td  class='linkstyle'>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
    echo "<td  class='linkstyle'>" . $row['Registration_ID'] . "</td>";
    echo "<td  class='linkstyle'>" . $row['Guarantor_Name'] . "</td>";
    echo "<td  class='linkstyle'>" . $age . "</td>";
    echo "<td  class='linkstyle'>" . $row['Gender'] . "</td>";
    echo "<td  class='linkstyle'>" . $row['Phone_Number'] . "</td>";
    echo "<td  class='linkstyle'>" . $row['Member_Number'] . "</td>";
    echo "<td  style='text-align: center;'><button style='text-align: center;' type='button' class='art-button-green' onclick='check_if_admited_or_in_admit_list(" . $row['Registration_ID'] . ")' ><span style='color: white;'><b>Check In</b></span></button></td>";
    echo "</tr>";

    $temp++;
}
echo '</table></center>';
