<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    #idadi:hover{
        background: #fff;
        cursor: pointer;
    }
    #idadi tr:odd{
        background: #fff;
    }
    #idadi tr:even{
        background: #eee;
    }
    #mrv-datas{
        overflow-x: scroll important;
        width: 80% important;
        border-box: 5px solid red;
    }
</style>

<?php
include("./includes/connection.php");
$filter_works = '';
$satisfy_id = '';
$Employee_Name = '';
$end_date = '';
$start_date = '';

// $filter = " AND DATE(en.date_of_requisition)=DATE(NOW())";
if (isset($_GET['Date_From'])){
    $Date_From = $_GET['Date_From'];
}else{
    $Date_From = 0;
}

if (isset($_GET['Date_To'])){
    $Date_To = $_GET['Date_To'];
}else{
    $Date_To = 0;
}

if (isset($_GET['satisfy_id'])){
    $satisfy_id = $_GET['satisfy_id'];
}else{
    $satisfy_id = 0;
}

if (isset($_GET['Employee_Name'])){
    $Employee_Name = $_GET['Employee_Name'];
}else{
    $Employee_Name = '';
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " AND DATE(en.date_of_requisition) BETWEEN '$Date_From'  AND  '$Date_To' ";
}

if (!empty($satisfy_id) && $satisfy_id != 'All') {
    $filter .= " AND en.satisfy = '$satisfy_id'";
}

if (!empty($Employee_Name) && $Employee_Name != 'All') {
    $filter .= " AND en.assigned_engineer  = '$Employee_Name'";
}



//die($select_Filtered_Patients);
    echo"<fieldset style='width: 100%; autoflow-x: scroll;'><form><center>
    <div id='mrv_datas'>
    <table style='width: 100%; autoflow-x: scroll;'>
    <tr>
    <td><table><tr style='background: grey; font-weight: bold;'>
		<td style='width: 5%;'>SN</td>
        <td style='width: 5%;'>MRV NO</td>
        <td style='20%'>Helpdesk Staff</td>
        <td style='15%'>Date Opened</td>
        <td style='15%'>Time Opened</td>
        <td style='15%'>Place/Floor/Room</td>
        <td style='25%'>Name of Requesting Staff</td>
        <td style='15%'>Equipment Name</td>
        <td style='10'>Equipment Code</td>
        <td style='15%'>Type of Work</td>
        <td style='10%'>Section Required</td>
        <td style='30%'>Work Description</td>
        <td style='20%'>Assigned Engineer</td>
        <td style='20%'>Assistance Engineer</td>
        <td style='10%'>Assigned Date</td>
        <td style='10%'>Assigned Time</td>
        <td style='10%'>Requisition Date</td>
        <td style='10%'>Spare Required</td>
        <td style='10%'>Date Requested</td>
        <td style='10%'>Issued Date</td>
        <td style='30%'>Job Notes</td>
        <td style='8%'>Visual Testing</td>
        <td style='10%'>Electrical Testing</td>
        <td style='10%'>Funtional Test</td>
        <td style='10%'>Part Requested</td>
        <td style='10%'>Procurement Order Made</td>
        <td style='10%'>Client Informed</td>
        <td style='30%'>Recommendation</td>
        <td style='10%'>Closure Date</td>
        <td style='10%'>Closure Time</td>
        <td style='10%'>Job Status</td>
        <td style='20%'>Requesting Staff Feedback</td>
        <td style='10%'>Final Job Status</td>
        <td style='10%'>Satisfaction</td>
    </tr>";

    $results = mysqli_query($conn, "SELECT en.requisition_ID, e.Employee_Name, en.visual_test, en.electrical_test, en.functional_test, en.date_of_requisition, en.part_date, en.assistance_engineer, en.assigned_at, en.equipment_name, en.employee_name, en.equipment_code, en.client_info, en.procurement_order, en.type_of_work, en.job_notes, en.spare_required, en.select_dept, dp.Department_Name, jb.jobcard_ID, jb.approved_at, en.section_required, en.description_works_to_done, en.helpdesk, en.feedback, en.floor, en.job_progress, en.final_status, en.assigned_engineer, en.recommendations, en.satisfy, en.date_done FROM tbl_engineering_requisition en, tbl_jobcards jb, tbl_department dp, tbl_employee e WHERE dp.Department_ID = en.select_dept AND e.Employee_ID = en.employee_name $filter GROUP BY en.requisition_ID") or die(mysqli_error($conn));
$temp = 1;
$Today_Date = mysqli_query($conn,"SELECT NOW() AS today");

while ($rows = mysqli_fetch_array($Today_Date)) {
    $original_Date = $rows['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}



while ($row = mysqli_fetch_array($results)) {
    $partdate = $row['part_date'];
    $Quantity = $row['Quantity'];
    $Price = $row['Price'];
    $requested_date = '';
    $helpdesk = $row['helpdesk'];
    $date_done = $row['date_done'];
    
 
    if ($date_done == '0000-00-00 00:00:00'){
        $Completed_date = " ";
    }else{
        $Completed_date = $date_done;
    }


    $satisfy = $row['satisfy'];
    $satisfaction = '';
    $helpdesk = $row['helpdesk'];
    $Employee_Name = $row['employee_name'];
    $rowrequisition_ID = $row['requisition_ID'];

    $helpdesk_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$helpdesk'"))['Employee_Name'];

    $requesting_employee = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_Name'"))['Employee_Name'];

    $consumed_date = mysqli_fetch_assoc(mysqli_query($conn, "SELECT created_at FROM tbl_mrv_items WHERE Requisition_ID = '$rowrequisition_ID' GROUP BY created_at ASC LIMIT 1"))['created_at'];
    if ($partdate == '0000-00-00 00:00:00'){
        $requested_date = 'Not Ordered';
    }else{
        $requested_date = $partdate;
    }

    // $spare_date = substr($requested_date, 0,10);

    $satisfaction = 0;
    if ($satisfy == '5'){
        $satisfaction = 'Excellent';
    }elseif ($satisfy == '4'){
        $satisfaction = 'Above Expectation';
    }elseif ($satisfy == '3'){
        $satisfaction = 'Met Expectation';
    }elseif ($satisfy == '2'){
        $satisfaction = 'Below Expectation';
    }elseif ($satisfy == '1'){
        $satisfaction = 'Poor Service';
    }else{
        $satisfaction = 'No Score';
    }

    $open_date = substr($row['assigned_at'],0,10);
    $open_time = substr($row['assigned_at'], 11);
    $close_date = substr($Completed_date, 0,10);
    $close_time = substr($Completed_date, 11);
    $assign_date = substr($row['assigned_at'], 0,10);
    $assign_time = substr($row['assigned_at'], 11);
    $requisition_date = substr($row['date_of_requisition'], 0,10);

    
    
    $output.="<tr id='idadi'>
			<td>".$temp."</td>
            <td>".$row['requisition_ID']."</td>
            <td>".$helpdesk_name."</td>
            <td>".$open_date."</td>
            <td>".$open_time."</td>            
            <td>".$row['floor']."</td>
            <td>".$requesting_employee."</td>
            <td>".$row['equipment_name']."</td>
            <td>".$row['equipment_code']."</td>
            <td>".$row['type_of_work']."</td>
            <td>".$row['section_required']."</td>
            <td>".$row['description_works_to_done']."</td>
            <td>".$row['assigned_engineer']."</td>
            <td>".$row['assistance_engineer']."</td>
            <td>".$assign_date."</td>
            <td>".$assign_time."</td>
            <td>".$requisition_date."</td>
            <td>".$row['spare_required']."</td>
            <td>".$requested_date."</td>
            <td>".$consumed_date."</td>
            <td>".$row['job_notes']."</td>
            <td>".$row['visual_test']."</td>
            <td>".$row['electrical_test']."</td>
            <td>".$row['functional_test']."</td>
            <td>".$row['part_requested']."</td>
            <td>".$row['procurement_order']."</td>
            <td>".$row['client_info']."</td>
            <td>".$row['recommendation']."</td>
            <td>".$close_date."</td>
            <td>".$close_time."</td>
            <td>".$row['job_progress']."</td>
            <td>".$row['feedback']."</td>
            <td>".$row['final_status']."</td>
            <td>".$satisfaction."</td>
        </tr>";
    $temp++;
	}
	$output .="</table></div>";
	echo $output;



?>


