<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style>
<link rel='stylesheet' href='fixHeader.css'>

<?php
include("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';

$filter = "   AND a.Admission_Status IN ('pending','Discharged')  AND DATE(a.pending_set_time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Discharge_Reason = filter_input(INPUT_GET,'Discharge_Reason_ID');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " AND a.Admission_Status IN ('pending','Discharged') AND DATE(a.pending_set_time) BETWEEN '$Date_From' AND '$Date_To'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID='$Sponsor'";
}
if (!empty($Discharge_Reason) && $Discharge_Reason != 'All') {
    $filter .="  AND a.Discharge_Reason_ID='$Discharge_Reason'";
}
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = '$ward'";
}else{
    $filter .= "";
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

$select_Filtered_Patients = "SELECT  
					sp.Guarantor_Name, 
                                        a.Discharge_Reason_ID,
					pr.Gender,pr.Region,
					pr.Registration_ID,
					pr.Date_Of_Birth,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Admission_Status,
					a.Kin_Name,
					a.Admission_Date_Time,
					a.pending_set_time,
					a.Kin_Phone,
                    a.Discharge_Date_Time,
                    cd.consultation_ID,
                    em.Employee_Name
				FROM 	
					tbl_hospital_ward hw,
					tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_discharge_reason dr,
					tbl_admission a,
                    tbl_employee em,
                    tbl_check_in_details cd
					WHERE 
						a.registration_id = pr.registration_id AND
						pr.Sponsor_ID = sp.Sponsor_ID AND 
                        em.Employee_ID = a.pending_setter AND
						hw.Hospital_Ward_ID = a.Hospital_Ward_ID AND
						a.Discharge_Reason_ID = dr.Discharge_Reason_ID AND
                                                cd.Admission_ID = a.Admision_ID
                                                $filter
                                                GROUP BY a.Admision_ID ORDER BY a.pending_set_time ASC";

//die($select_Filtered_Patients);


echo '<center><table width ="100%" style="background-color:white;" id="patients-list" class="fixTableHead">';
echo '  <thead>
            <tr style="font-size:13px;">
                <td width=2%><b>Sn</b></td>
                <td width="11%"><b>Patient Name</b></td>
                <td width="4%" style="text-align: center;"><b>MRN</b></td>
                <td width="6%" style="text-align: center;"><b>Sponsor</b></td>
                <td width="8%" style="text-align: center;"><b>Ward</b></td>
                <td width="4%" style="text-align: center;"><b>Gender</b></td>
                <td width="6%" style="text-align: center;"><b>Age</b></td>
                <td width="9%" style="text-align: center;"><b>Admitted</b></td>
                <td width="10%" style="text-align: center;"><b>Discharged</b></td>
                <td width="10%" style="text-align: center;"><b>Discharged By</b></td>
                <td width="10%" style="text-align: center;"><b>Bill Status</b></td>
                <td width="10%" style="text-align: center;"><b>Clearance Date</b></td>
                <td width="8%" style="text-align: center;"><b>Reason</b></td>
                <td width="10%" style="text-align: left;"><b>Next of kin</b></td>
                <td width="9%" style="text-align: left;"><b>Kin phone</b></td>
                <td width="10%" style="text-align: left;"><b>Region</b></td>
		    </tr>
        </thead>
                ';

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
    
    $Discharge_Reason_ID = $row['Discharge_Reason_ID'];
    $Admission_Status = $row['Admission_Status'];
    $get_Discharge_Reason = "SELECT * FROM tbl_discharge_reason WHERE Discharge_Reason_ID = '$Discharge_Reason_ID'";
    $got_Discharge_Reason = mysqli_query($conn,$get_Discharge_Reason) or die(mysqli_error($conn));
    while ($rowb = mysqli_fetch_assoc($got_Discharge_Reason)) {
        $Discharge_Reason = $rowb['Discharge_Reason'];
    }

    $admission_date = $row['Admission_Date_Time']; // date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));//
    $discharge_date = date("j/m/Y. g:i a", strtotime($row['pending_set_time'])); //date("j/m/Y. g:i a", strtotime($row['Discharge_Date_Time']));
    $Discharge_Date_Time = $row['Discharge_Date_Time'];
    $Employee_Name = $row['Employee_Name'];

    if($Discharge_Date_Time != NULL) $Cleared = date("j/m/Y g:i a", strtotime($Discharge_Date_Time));
    else $Cleared = "<b>Not Cleared</b>";
        

    
    echo '<tr><td>' . $temp . '</td>';
    echo "<td style='font-size:13px;'>" . ucfirst($row['Patient_Name']) . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Guarantor_Name'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    //echo "<td style='text-align: center; font-size:12px;'>" . $Bed_ID . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Gender'] . "</td>";

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
    // $Bill_Status = 0;
    if ($Admission_Status == 'Discharged'){
        $Bill_Status = '<b>Bill Cleared</b>';
    }else{
        $Bill_Status = '<span style="background: yellow; padding: 3px 10px;">Bill Pending</span>';
    };

    echo "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $discharge_date . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . ucwords($Employee_Name) . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $Bill_Status . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $Cleared . "</td>";
    echo "<td  style='text-align: center; font-size:12px;'>" . $Discharge_Reason . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    echo "<td style='font-size:12px;'>" . $row['Region'] . "</td>";

    $temp++;
}

echo '</table>'
 . '</center>';
//echo "<tr><td colspan=14><hr></td></tr>";
//echo "<tr><td colspan=14 style='text-align: right;'><b> TOTAL DISCHARGED : " . number_format($temp - 1) . "</td></tr>";
//echo "<tr><td colspan=14><hr></td></tr>";
?>
