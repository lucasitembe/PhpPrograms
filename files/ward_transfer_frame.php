<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style> 
<?php
include("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';

$filter = "   AND Admission_Status != 'Discharged'";

if(isset($_GET['Patient_Name'])){
    $Patient_Name = mysqli_real_escape_string($conn,str_replace(" ", "%", $_GET['Patient_Name']));
}else{
    $Patient_Name = '';
}
if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
}



if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

$select_Filtered_Patients = "SELECT sp.Guarantor_Name,pr.Gender,pr.Region,pr.Registration_ID,pr.Date_Of_Birth,hw.Hospital_Ward_Name,pr.Patient_Name,a.Bed_ID,a.Admission_Status,a.Kin_Name,a.Admission_Date_Time,a.Kin_Phone,a.Admision_ID
				FROM tbl_hospital_ward hw,tbl_patient_registration pr,tbl_sponsor sp,tbl_admission a
					WHERE a.registration_id = pr.registration_id AND a.registration_id = pr.registration_id AND pr.Sponsor_ID = sp.Sponsor_ID AND hw.Hospital_Ward_ID = a.Hospital_Ward_ID $filter order by a.Admision_ID desc limit 50";

echo '<center><table width ="100%" border="0" style="background-color:white;" id="patients-list">';
echo '<thead>
		<tr>
			<td width="3%"><b>Sn</b></td>
			<td width="15%"><b>Patient Name</b></td>
			<td width="9%" style="text-align: left;"><b>Patient #</b></td>
			<td width="8%" style="text-align: left;"><b>Sponsor</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
			<td width="7%" style="text-align: left;"><b>Bed</b></td>
			<td width="6%" style="text-align: left;"><b>Gend</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted</b></td>
			<td width="11%" style="text-align: left;"><b>Region</b></td>
			<td width="10%" style="text-align: left;"><b>ward Transfer</b></td>
			<td width="10%" style="text-align: left;"><b>Bed #</b></td>
		</tr>
                </thead>';

$results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

while ($row = mysqli_fetch_array($results)) {
    $admission_date = date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    $bed_id = $row['Bed_ID'];
    $Admision_ID=$row['Admision_ID'];
    $get_bed_name = "SELECT Bed_Name FROM tbl_beds WHERE Bed_ID = $bed_id";
    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
    $bed_name = '';
    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $rowb['Bed_Name'];
    }

    $ward = "<select required='required' name='Hospital_Ward_ID' id='Hospital_Ward_ID_".$Admision_ID."' onchange='Get_Ward_Beds(this.value,$Admision_ID)' >
                                    <option selected='selected'></option>";

    $Select_ward = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward");
    while ($row_ward = mysqli_fetch_array($Select_ward)) {
        $Ward_Name = $row_ward['Hospital_Ward_Name'];
        $Hospital_Ward_ID = $row_ward['Hospital_Ward_ID'];
        $Number_of_Beds = $row_ward['Number_of_Beds'];
        $ward .=" <option value='" . $Hospital_Ward_ID . "'>" . $Ward_Name . "</option>";
    }

    $ward .= " </select>";

    $beds = '<select name="Bed_ID" id="Bed_ID_'.$Admision_ID.'"  onchange="checkPatientNumber(this.value,'.$Admision_ID.')">
            <option selected="selected"></option>
         </select>';

    echo '<tr>';
    echo '<td style="text-align:center;">' . $temp . '</td>';
    echo "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Guarantor_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $bed_name . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Gender'] . "</td>";

    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->m . " Months";
    }
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->d . " Days";
    };
    echo "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Region'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" .$ward . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>".$beds."</td>";
    echo "</tr>";

    $temp++;
}
echo '</table></center>';
//echo '<table style="width:100%" border="0">';
//echo "<tr><td colspan='12'><hr></td></tr>";
//echo "<tr><td colspan='12' style='text-align: center;'><b> TOTAL ADMITTED : " . number_format($temp - 1) . "</td></tr>";
//echo "<tr><td colspan='12'><hr></td></tr>";
//echo '</table>';
?>
