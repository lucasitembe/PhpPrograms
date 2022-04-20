<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style> 
<!--Dialog div-->
    <div id="viewFile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
        <div id="fileInfo">
        </div>
    </div>  
<?php
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";

$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';
$ptn_type = '';
$mil_rank = '';

$filter = "   AND Admission_Status != 'Discharged'";

if (isset($_GET['Sponsor'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
    $ptn_type= filter_input(INPUT_GET, 'patient_type');
    // $mil_rank = filter_input(INPUT_GET, 'mil_rank');
    // $unit = filter_input(INPUT_GET, 'unit');
    $Registration_ID=filter_input(INPUT_GET,'Registration_ID');
}

if(!empty($unit) && $unit!='All'){
    $filter .="  AND pr.military_unit='$unit'";
}
if(!empty($mil_rank) && $mil_rank!='All'){
    $filter .="  AND pr.rank='$mil_rank'";
}
if (!empty($ptn_type) && $ptn_type != 'All') {
    $filter .="  AND a.Admission_Status='$ptn_type'";
}
if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}else{
    $filter .= "AND  a.Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))";
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
					pr.Gender,pr.Region,
					pr.Registration_ID,
					pr.Date_Of_Birth,
					pr.patient_type,
					pr.rank,
                    Bed_Name,
                    room_name,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Bed_ID,
					a.Admission_Status,
					a.Kin_Name,
					a.Admission_Date_Time,
					a.Kin_Phone,a.Admission_Status,
                    a.Admision_ID
				FROM 	
					tbl_hospital_ward hw,
					tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_admission a, tbl_ward_rooms wr
					WHERE a.registration_id = pr.registration_id AND pr.Sponsor_ID = sp.Sponsor_ID AND a.ward_room_id=wr.ward_room_id AND   a.ward_room_id<>'0' AND  Admission_Status <> 'Discharged' AND 	hw.Hospital_Ward_ID = a.Hospital_Ward_ID $filter AND ward_type='ordinary_ward'";

echo '<center><table width ="100%" border="0" style="background-color:white;" id="patients-list" class="fixTableHead">';
echo '<thead>
		<tr>
			<td width="3%"><b>Sn</b></td>
			<td width="15%"><b>Patient Name</b></td>
			
			<td width="9%" style="text-align: left;"><b>Patient #</b></td>
			<td width="8%" style="text-align: left;"><b>Sponsor</b></td>
            <td width="8%" style="text-align: left;"><b>Admission_ID</b></td>
			<td width="9%" style="text-align: left;"><b>Ward</b></td>
            <td style="text-align: left; width:9%;">Room</td>
			<td width="7%" style="text-align: left;"><b>Bed</b></td>
			<td width="6%" style="text-align: left;"><b>Gender</b></td>
			<td width="6%" style="text-align: center;"><b>Age</b></td>
			<td width="10%" style="text-align: center;"><b>Admitted</b></td>
			<td width="11%" style="text-align: left;"><b>Next of kin</b></td>
			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
			<td width="10%" style="text-align: left;"><b>Region</b></td>
            <td width="10%" style="text-align: left;"><b>Admission Status</b></td>;
		</tr>
                </thead>';
                //<td width="15%"><b>Patient Type</b></td>
$results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn)."PUMBAVUU");
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
    $get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = '$bed_id'";
    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
    $bed_name = '';
    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $rowb['Bed_Name'];
    }
    if($row['Admission_Status']=="pending"){
       $background="background:green;color:white;padding:5px;";
    }else{
        $background="";
        // $background="background:green;color:white;padding:5px;";
    }
    echo '<tr>';
    echo '<td style="text-align:center;">' . $temp . '</td>';
    echo "<td style='font-size:13px;'> <a target='_blank' class='viewmre' href='individual_admission_report.php?intent&Registration_ID=".$row['Registration_ID']."' style='text-decoration:none;$background'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    // echo "<td style='text-align: left; font-size:12px;'>" . $row['patient_type'] . "</td>";
    
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Guarantor_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Admision_ID'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['room_name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Bed_Name'] . "</td>";
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
    echo "<td style='text-align: left; font-size:12px;'>" . ucwords(strtolower($row['Kin_Name'])) . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Region'] . "</td>";
    
    echo "<td style='text-align: left; font-size:12px;'>" . $row['Admission_Status'] . "</td>";
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
                    
<script>
    $('.viewmore').click(function (e) {
        e.preventDefault();
        $("#viewFile").dialog('open');
        $('#fileInfo').html('');
        var datastring = $(this).attr('href');
        //alert(datastring);
        $.ajax({
            type: "GET",
            url: datastring,
            beforeSend: function () {
                $('#progressStatus').show();
            },
            success: function (data) {
                $('#fileInfo').html(data);
            },
            complete: function () {
                $('#progressStatus').hide();
            },
            error: function (data) {
                $('#progressStatus').hide();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $("#viewFile").dialog({
            title: 'Admission Details',
            autoOpen: false,
            resizable: false,
            height: 500,
            width: 900,
            show: {drop: 'slide', direction: "up"},
            //hide:{effect: 'slide', direction: "right"},
            modal: true,
            draggable: true
        });
    });
</script>

