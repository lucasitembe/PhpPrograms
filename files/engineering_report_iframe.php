<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
</style>

<?php
include("./includes/connection.php");
$Branch_ID = 0;
$satisfy_ID = '';
$Employee_ID = '';
$end_date = '';
$start_date = '';

$filter = " AND sp.completed='completed'  AND DATE(sp.date_done)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $satisfy_ID = filter_input(INPUT_GET, 'satisfy_ID');
    $Employee_ID = filter_input(INPUT_GET, 'Employee_ID');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND sp.completed='completed' AND DATE(sp.date_done) BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($satisfy_ID) && $satisfy_ID != 'All') {
    $filter .="  AND sp.satisfy='$satisfy_id'";
}

if (!empty($Employee_ID) && $Employee_ID != 'All') {
    $filter .= " AND e.Employee_ID  = '$Employee_ID'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
}


$select_Filtered_Patients = "
				SELECT  
                    sp.requisition_ID,
                    sp.employee_name,
                    sp.title,
                    sp.satisfy,
                    sp.completed,
                    sp.date_done,
                    sp.equipment_name,
                    sp.date_of_requisition,
                    sp.type_of_work,
                    sp.section_required,
                    e.Employee_Name,
                    e.Employee_ID,
                    p.satisfaction
                    FROM 	
					tbl_satisfaction p,
					tbl_employee e,
					tbl_engineering_requisition sp
					WHERE 
                        sp.employee_name = e.Employee_ID AND
                        sp.satisfy = p.satisfy_id
                                                $filter
                                                ";

//die($select_Filtered_Patients);


echo '<center><table width ="100%" style="background-color:white;" id="patients-list">';
echo '<thead>
                <tr style="font-size:13px;">
			<td width=2%><b>Sn</b></td>
			<td width="4%" style="text-align: center;"><b>MRV</b></td>
			<td width="6%" style="text-align: center;"><b>Equipment Name</b></td>
			<td width="8%" style="text-align: center;"><b>Department</b></td>
            <td width="7%" style="text-align: center;"><b>Requested by</b></td>
            <td width="7%" style="text-align: center;"><b>Admin. Resp.</b></td>
			<td width="4%" style="text-align: center;"><b>Date Reported</b></td>
			<td width="6%" style="text-align: center;"><b>Section</b></td>
			<td width="9%" style="text-align: center;"><b>Working Type</b></td>
			<td width="10%" style="text-align: center;"><b>Date Done</b></td>
                        <td width="10%" style="text-align: center;"><b>Perfomed By</b></td>
			<td width="8%" style="text-align: center;"><b>Satisfaction</b></td>
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



    $satisfy_id = $row['satisfy_id'];
    $get_satisfy_name = "SELECT satisfaction FROM tbl_satisfaction WHERE satisfy_ID = '$satisfy_id'";
    $got_satisfy_name = mysqli_query($conn,$get_satisfy_name) or die(mysqli_error($conn));
    while ($rowb = mysqli_fetch_assoc($got_satisfy_name)) {
        $satisfaction = $rowb['satisfaction'];
    }
    

    echo '<tr><td>' . $temp . '</td>';
    echo "<td style='text-align: center; font-size:12px;'>" . $row['requisition_ID'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['equipment_name'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['title'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $satisfaction . "</td>";

    $temp++;
}

echo '</table>'
 . '</center>';
//echo "<tr><td colspan=14><hr></td></tr>";
//echo "<tr><td colspan=14 style='text-align: right;'><b> TOTAL DISCHARGED : " . number_format($temp - 1) . "</td></tr>";
//echo "<tr><td colspan=14><hr></td></tr>";
?>
