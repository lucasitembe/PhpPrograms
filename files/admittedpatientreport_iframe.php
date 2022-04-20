<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
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
$row_num = '100';

$filter = "   AND DATE(a.Admission_Date_Time)=DATE(NOW())";
echo "<link rel='stylesheet' href='fixHeader.css'>";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
    $row_num = filter_input(INPUT_GET, 'row_num');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND a.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
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

if (!empty($Patient_Number)) {
    $filter .="  AND a.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if(isset($row_num) && !empty($row_num)){
   
    if($row_num=='ALL'){
      $filter .="";
    } else {
        $filter .=" LIMIT $row_num";
    }
}


$select_Filtered_Patients = "SELECT sp.Guarantor_Name,pr.Gender,pr.Region,pr.Registration_ID,pr.Date_Of_Birth,hw.Hospital_Ward_Name,pr.Patient_Name,hw.Hospital_Ward_Name,a.Admision_ID,
					a.Admission_Status,
					a.Kin_Name,
                                        e.Employee_Name,
					a.Admission_Date_Time,
					a.Kin_Phone,
					a.Bed_Name
				FROM 	
					tbl_hospital_ward hw,tbl_patient_registration pr,tbl_sponsor sp,tbl_admission a,tbl_employee e
					WHERE a.Admision_ID NOT IN(SELECT ma.Admision_ID FROM tbl_mortuary_admission ma WHERE ma.Admision_ID=a.Admision_ID) AND 
						a.registration_id = pr.registration_id AND
						a.registration_id = pr.registration_id AND
						pr.Sponsor_ID = sp.Sponsor_ID AND 
						hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND a.Bed_Name <> '' and 
                                                a.Admission_Employee_ID = e.Employee_ID $filter";

echo '<center><table width ="100%" border="0" style="background-color:white;" id="patients-list" class="fixTableHead">';
echo '<thead>
		<tr>
			<td width="3%"><b>Sn</b></td>
			<td width="15%"><b>Patient Name</b></td>
			<td width="9%" style="text-align: left;"><b>Patient #</b></td>
                        <td width="9%" style="text-align: left;"><b>Admission ID</b></td>
			<td width="8%" style="text-align: left;"><b>Sponsor</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
			<td width="7%" style="text-align: left;"><b>Bed</b></td>
			<td width="6%" style="text-align: left;"><b>Gend</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted On</b></td>
			<td width="11%" style="text-align: left;"><b>Admitted By</b></td>
			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
			<td width="10%" style="text-align: left;"><b>Region</b></td>
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

while ($row = mysqli_fetch_assoc($results)) {
    $admission_date = $row['Admission_Date_Time'];// date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    //$bed_id = $row['Bed_ID'];
    //$get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = $bed_id";
   // $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
   // $bed_name = '';
    //while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $row['Bed_Name'];
   // }

    echo '<tr>';
    echo '<td style="text-align:center;">' . $temp . '</td>';
    echo "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Admision_ID'] . "</td>";
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
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Employee_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Region'] . "</td>";
    echo "</tr>";
    
    $temp++;
}
echo '</table>';
?></center>
