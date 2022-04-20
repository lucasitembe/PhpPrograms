<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    .idadi:hover{
        background: #eee;
        cursor: pointer;
        width: 100% important;
    }
	.idadi tr:nth-child(even){
		background: #f2f2f2;
	}
</style>

<?php
include("./includes/connection.php");
$satisfy_id = '';
$Employee_Name = '';
$end_date = '';
$start_date = '';


if (isset($_GET['filter_works'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $satisfy_id = filter_input(INPUT_GET, 'satisfy_id');
    $Employee_Name = filter_input(INPUT_GET, 'Employee_Name');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter .= " AND en.date_of_requisition BETWEEN '$Date_From'  AND  '$Date_To'  ";
}

if (!empty($satisfy_id) && $satisfy_id != 'All') {
    $filter .=" AND en.satisfy = '$satisfy_id'";
}

if (!empty($Employee_Name) && $Employee_Name != 'All') {
    $filter .= " AND en.assigned_engineer  = '$Employee_Name'";
}

// if (!empty($Branch_ID) && $Branch_ID != 'All') {
//     $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
// }



//die($select_Filtered_Patients);


echo '<center><table width ="100%" style="background-color:white;" id="patients-list">';
echo '<thead>
                <tr style="font-size:13px;">
                    <td width=2%><b>Sn</b></td>
                    <td width="4%" style="text-align: center;"><b>MRV</b></td>
                    <td width="6%" style="text-align: center;"><b>Reporting Date</b></td>
                    <td width="8%" style="text-align: center;"><b>Approved Date</b></td>
                    <td width="7%" style="text-align: center;"><b>Jobcard No:</b></td>
                    <td width="6%" style="text-align: center;"><b>Section</b></td>
                    <td width="9%" style="text-align: center;"><b>Department</b></td>
                    <td width="10%" style="text-align: center;"><b>Description</b></td>
                    <td width="10%" style="text-align: center;"><b>Sent To PSO</b></td>
                    <td width="6%" style="text-align: center;"><b>Status</b></td>
                    <td width="6%" style="text-align: center;"><b>Technician</b></td>
                    <td width="6%" style="text-align: center;"><b>Remarks</b></td>
                    <td width="8%" style="text-align: center;"><b>Satisfaction</b></td>
		        </tr>
                </thead>
                ';
//die("SELECT en.requisition_ID, en.date_of_requisition, en.select_dept, dp.Department_Name, en.section_required, en.description_works_to_done, en.job_progress, en.assigned_engineer, en.recommendations, en.satisfy, st.satisfaction FROM tbl_engineering_requisition en, tbl_satisfaction st, tbl_department dp WHERE st.satisfy_id = en.satisfy AND dp.Department_ID = en.select_dept $filter GROUP BY en.requisition_ID ASC");
$results = mysqli_query($conn, "SELECT en.requisition_ID, en.date_of_requisition, en.select_dept, dp.Department_Name, en.section_required, en.description_works_to_done, en.job_progress, en.assigned_engineer, en.recommendations, en.satisfy FROM tbl_engineering_requisition en, tbl_department dp WHERE dp.Department_ID = en.select_dept $filter GROUP BY en.requisition_ID ASC") or die(mysqli_error($conn));
$temp = 1;
//st.satisfy_id = en.satisfy AND
//$Today_Date = mysqli_query($conn,"SELECT NOW() AS today");

//while ($rows = mysqli_fetch_array($Today_Date)) {
  //  $original_Date = $row['today'];
    //$new_Date = date("Y-m-d", strtotime($original_Date));
    //$Today = $new_Date;
    //$age = '';
//}

while ($row = mysqli_fetch_array($results)) {
    $partdate = $row['approved_at'];
    $Quantity = $row['Quantity'];
    $Price = $row['Price'];
	$Requisition_ID = $row['requisition_ID'];
	$satisfy = $row['satisfy'];
    $requested_date = '';
	if(!empty($satisfy)){
		$satisfaction = mysqli_fetch_assoc(mysqli_query($conn, "SELECT satisfaction FROM tbl_satisfaction WHERE satisfy_id = '$satisfy'"))['satisfaction'];
	}else{
		$satisfaction = '';
	}
    
    if ($partdate == '0000-00-00 00:00:00'){
        $requested_date = 'Not Yet Approved';
    }else{
        $requested_date = $partdate;
    }
	
//$jobcard = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jobcard_ID, approved_at, part_date FROM tbl_jobcards WHERE Requisition_ID = '$Requisition_ID'")) or die(mysli_error($conn));



     $Subtotal = $Price * $Quantity;
     $Total_amount = $Total_amount + $Subtotal;
    

    echo "<tr class='idadi'><td>" . $temp . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['requisition_ID'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['date_of_requisition'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $requested_date . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $data['jobcard_ID'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['section_required'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['Department_Name'] . "</td>";
    echo "<td style='text-align: left; font-size:12px;'>" . $row['description_works_to_done'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $data['part_date'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['job_progress'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['assigned_engineer'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $row['recommendations'] . "</td>";
    echo "<td style='text-align: center; font-size:12px;'>" . $satisfaction . "</td>";
    $temp++;

}

echo '</table>'
 . '</center>';
//echo "<tr><td colspan=14><hr></td></tr>";
//echo "<tr><td colspan=14 style='text-align: right;'><b> TOTAL DISCHARGED : " . number_format($temp - 1) . "</td></tr>";
//echo "<tr><td colspan=14><hr></td></tr>";
?>
