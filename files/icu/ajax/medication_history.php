<?php

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
}

if (isset($_GET['end'])) {
    $end_date = $_GET['end'];
}


//get last consultation id
$sql_select_consultation_result = mysqli_query($conn, "SELECT consultation_ID,Consultation_Date_And_Time FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_select_consultation_result) > 0) {
    $rows_cons = mysqli_fetch_assoc($sql_select_consultation_result);
    $consultID = $rows_cons['consultation_ID'];
    $Consultation_Date_And_Time = $rows_cons['Consultation_Date_And_Time'];
    //clear missing drug
}

$filter = " em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultID . "' AND DATE(sg.Time_Given)=DATE(NOW()) ORDER BY sg.Time_Given DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = "  em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='" . $consultID . "' AND sg.Time_Given BETWEEN '$start_date' AND '$end_date' ORDER BY sg.Time_Given DESC";
}

//$select_services = "
//	SELECT Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name
//			FROM
//				tbl_inpatient_medicines_given sg,
//				tbl_item_list_cache il,
//                                tbl_items i,
//				tbl_employee em
//					WHERE
//						$filter ";
$select_services = "
	SELECT medication_time,given_time,sg.route_type,drip_rate,Medication_type, Product_Name,Time_Given,Amount_Given,Nurse_Remarks,From_outside_amount,Discontinue_Status,Discontinue_Reason,Employee_Name
			FROM 
				tbl_inpatient_medicines_given sg,
				tbl_items it,
				tbl_employee em
					WHERE Discontinue_Status='no' AND 
						$filter ";


$selected_services = mysqli_query($conn, $select_services) or die(mysqli_error($conn));
echo "<table width='100%' id='nurse_medicine' class='table table-striped table-bordered'>";
echo "<thead class='table-light'>"
    . "
<tr><th colspan='30' class='text-center border-1 border fs-6'>Medication Chart History</th></tr>
<tr>";
echo "<th width='2%'> SN </th>";
echo "<th style='width: 15%'> Medicine Name </th>";
echo "<th> Dose </th>";
echo "<th> Route </th>";
echo "<th> Amount Given </th>";
echo "<th width='11%'> saved time</th>";
echo "<th width='11%'> Time Given</th>";
echo "<th>Significant Events and Interventions </th>";
echo "<th width='5%'> Discontinued?</th>";
echo "<th > From Outside Amount</th>";
echo "<th > Medicine type</th>";
echo "<th> Given by </th>";
echo "</tr>";
echo "</thead>";

$sn = 1;
while ($service = mysqli_fetch_assoc($selected_services)) {
    $Product_Name = $service['Product_Name'];
    $given_time = $service['given_time'];
    $route_type = $service['route_type'];
    $Time_Given = $service['Time_Given'];
    $medication_time = $service['medication_time'];
    $Medication_type = $service['Medication_type'];
    $Amount_Given = $service['Amount_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $From_outside_amount = $service['From_outside_amount'];
    $Employee_Name = $service['Employee_Name'];
    echo "<tr style='border-bottom: 1px solid #bfbcbc !important;'>";
    echo "<td id='thead' style='text-align: center;'>" . $sn . "</td>";
    echo "<td>" . $Product_Name . "</td>";
    echo "<td class='text-center'>" . $given_time . "</td>";
    echo "<td>" . $route_type . "</td>";
    echo "<td class='text-center'>" . $Amount_Given . "</td>";
    echo "<td class='text-center'>" . $Time_Given . "</td>";
    echo "<td class='text-center'>" . $medication_time . "</td>";
    echo "<td class='text-center'>" . $Nurse_Remarks . "</td>";
    echo "<td class='text-center'>" . $Discontinue_Status . "</td>";
    echo "<td class='text-center'>" . $From_outside_amount . "</td>";
    echo "<td class='text-center'>" . $Medication_type . "</td>";
    echo "<td>" . $Employee_Name . "</td>";
    echo "</tr>";
    $sn++;
}
echo "</table>";
?>