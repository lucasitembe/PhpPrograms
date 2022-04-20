<?php

require_once('./includes/connection.php');

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
}

if (isset($_GET['end'])) {
    $end_date = $_GET['end'];
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

$filter = " em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Service_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "' AND DATE(sg.Time_Given)=DATE(NOW()) ORDER BY sg.Time_Given DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = "  em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Service_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultation_ID . "' AND sg.Time_Given BETWEEN '$start_date' AND '$end_date' ORDER BY sg.Time_Given DESC";
}


$select_services = "
	SELECT 
		it.Product_Name,
		em.Employee_Name,
		sg.Time_Given,
		sg.Discontinue_Status,
		sg.Discontinue_Reason,
		sg.Nurse_Remarks
			FROM 
				tbl_inpatient_services_given sg,
				tbl_items it,
				tbl_employee em
					WHERE 
						$filter ";


$selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
echo "<table width='100%' id='inpa_service'>";
echo "<thead>"
 . "<tr>";
echo "<th style='text-align:center;width:5%;'>SN</th>";
echo "<th> Service Name </th>";
echo "<th width='11%'> Time Given </th>";
echo "<th>Nurse Remarks </th>";
echo "<th width='13%'> Discontinue Status </th>";
echo "<th> Discontinue Reason </th>";
echo "<th> Given by </th>";
echo "</tr>"
 . "</thead>";
$sn = 1;
while ($service = mysqli_fetch_assoc($selected_services)) {
    $Product_Name = $service['Product_Name'];
    $Time_Given = $service['Time_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $Employee_Name = $service['Employee_Name'];
    echo "<tr>";
    echo "<td id='thead'>" . $sn . "</td>";
    echo "<td>" . $Product_Name . "</td>";
    echo "<td>" . $Time_Given . "</td>";
    echo "<td>" . $Nurse_Remarks . "</td>";
    echo "<td>" . $Discontinue_Status . "</td>";
    echo "<td>" . $Discontinue_Reason . "</td>";
    echo "<td>" . $Employee_Name . "</td>";
    echo "</tr>";
    $sn++;
}
echo "</table>";
?>