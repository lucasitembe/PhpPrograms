<?php 
    include("./includes/connection.php");
    $start_date=$_GET['start_date'];
    $end_date=$_GET['end_date'];
    $Employee_Name=$_GET['Employee_Name'];
    $satisfy_id=$_GET['satisfy_id'];

    if($Employee_Name!="All"){
        $filter .=" AND en.assigned_engineer='$Employee_Name'"; 
     }
     
     if($satisfy_id != "All"){
      $filter .=" AND en.satisfy='$satisfy_id'";
     }else{
		 $filter .='';
	 }
     $filter .=" AND en.date_of_requisition BETWEEN '".$start_date."' AND '".$end_date."'";


    // $excel_query = mysqli_query($conn,"SELECT en.requisition_ID, e.Employee_Name, en.visual_test, en.electrical_test, en.functional_test, en.date_of_requisition, en.part_date, en.assistance_engineer, en.assigned_at, en.equipment_name, en.employee_name, en.equipment_code, en.client_info, en.procurement_order, en.type_of_work, en.job_notes, en.spare_required, en.select_dept, dp.Department_Name, jb.jobcard_ID, jb.approved_at, en.section_required, en.description_works_to_done, en.helpdesk, en.feedback, en.floor, en.job_progress, jb.part_date, en.final_status, en.assigned_engineer, en.recommendations, en.satisfy, en.date_done FROM tbl_engineering_requisition en, tbl_jobcards jb, tbl_department dp, tbl_employee e WHERE dp.Department_ID = en.select_dept AND e.Employee_ID = en.employee_name $filter GROUP BY en.requisition_ID ASC ");
    // $temp = 1;

    $output.="
        <table>
        <tr style='font-size:13px;'>
        <td><b>Sn</b></td>
        <td width='10%'><b>MRV</b></td>
        <td width='10%'><b>Reporting Date</b></td>
        <td width='10%'><b>Approved Date</b></td>
        <td width='10%'><b>Jobcard No:</b></td>
        <td width='10%'><b>Section</b></td>
        <td width='10%'><b>Department</b></td>
        <td width='10%'><b>Description</b></td>
        <td width='10%'><b>Sent To PSO</b></td>
        <td width='10%'><b>Status</b></td>
        <td width='10%'><b>Technician</b></td>
        <td width='10%'><b>Remarks</b></td>
        <td width='10%'><b>Satisfaction</b></td>
    </tr>";

    $results = mysqli_query($conn, "SELECT en.requisition_ID, en.date_of_requisition, en.select_dept, dp.Department_Name, en.section_required, en.description_works_to_done, en.job_progress, en.assigned_engineer, en.recommendations, en.satisfy FROM tbl_engineering_requisition en, tbl_department dp WHERE dp.Department_ID = en.select_dept $filter GROUP BY en.requisition_ID ASC") or die(mysqli_error($conn));
    $temp = 1;
    
    $Today_Date = mysqli_query($conn,"SELECT NOW() AS today");
    
    while ($rows = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
    
    while ($row = mysqli_fetch_array($results)) {
        $partdate = $row['approved_at'];
        $Quantity = $row['Quantity'];
        $Price = $row['Price'];
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
                $output.="
                <tr class='idadi'><td>" . $temp . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['requisition_ID'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['date_of_requisition'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $requested_date . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['jobcard_ID'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['section_required'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['Department_Name'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['description_works_to_done'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['part_date'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['job_progress'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['assigned_engineer'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $row['recommendations'] . "</td>
                <td style='text-align: center; font-size:12px;'>" . $satisfaction . "</td>
                    </tr>";
                    $temp++;
            }

            $output .= "</table>";

    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename= KaziMbalimbaliExcellReport.xls");
    echo $output;
?>