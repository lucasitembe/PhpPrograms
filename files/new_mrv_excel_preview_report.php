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
     }
     $filter .=" AND en.date_of_requisition BETWEEN '".$start_date."' AND '".$end_date."'";


    $excel_query = mysqli_query($conn,"SELECT en.requisition_ID, e.Employee_Name, en.visual_test, en.electrical_test, en.functional_test, en.date_of_requisition, en.part_date, en.assistance_engineer, en.assigned_at, en.equipment_name, en.employee_name, en.equipment_code, en.client_info, en.procurement_order, en.type_of_work, en.job_notes, en.spare_required, en.select_dept, dp.Department_Name, jb.jobcard_ID, jb.approved_at, en.section_required, en.description_works_to_done, en.helpdesk, en.feedback, en.floor, en.job_progress, jb.part_date, en.final_status, en.assigned_engineer, en.recommendations, en.satisfy, en.date_done FROM tbl_engineering_requisition en, tbl_jobcards jb, tbl_department dp, tbl_employee e WHERE dp.Department_ID = en.select_dept AND e.Employee_ID = en.employee_name $filter GROUP BY en.requisition_ID ASC ");
    $temp = 1;

    $output.="
        <table>
            <tr style='background: grey;'>
                <td style='color:green'>MRV NO</td>
                <td style='color:green'>Helpdesk Staff</td>
                <td style='color:green'>Date Opened</td>
                <td style='color:green'>Place/Floor/Room</td>
                <td style='color:green'>Name of Requesting Staff</td>
                <td style='color:green'>Equipment Name</td>
                <td style='color:green'>Equipment Code</td>
                <td style='color:green'>Type of Work</td>
                <td style='color:green'>Section Required</td>
                <td style='color:green'>Work Description</td>
                <td style='color:green'>Assigned Engineer</td>
                <td style='color:green'>Assistance Engineer</td>
                <td style='color:green'>Assigned Date</td>
                <td style='color:green'>Requesting Date</td>
                <td style='color:green'>Spare Required</td>
                <td style='color:green'>Job Notes</td>
                <td style='color:green'>Visual Testing</td>
                <td style='color:green'>Electrical Testing</td>
                <td style='color:green'>Funtional Test</td>
                <td style='color:green'>Part Requested</td>
                <td style='color:green'>Procurement Order Made</td>
                <td style='color:green'>Client Informed</td>
                <td style='color:green'>Recommendation</td>
                <td style='color:green'>Job Status</td>
                <td style='color:green'>Closure Date</td>
                <td style='color:green'>Requesting Staff Feedback</td>
                <td style='color:green'>Final Job Status</td>
                <td style='color:green'>Satisfaction</td>
            </tr>";

            while($data = mysqli_fetch_assoc($excel_query)){
                $satisfy = $data['satisfy'];
                $satisfaction = '';
                $helpdesk = $data['helpdesk'];
                $Employee_Name = $data['employee_name'];
                $date_done = $data['date_done'];

                $helpdesk_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$helpdesk'"))['Employee_Name'];

                $requesting_employee = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_Name'"))['Employee_Name'];


                if ($partdate == '0000-00-00 00:00:00'){
                    $requested_date = 'Not Yet Approved';
                }else{
                    $requested_date = $partdate;
                }

                if ($date_done == '0000-00-00 00:00:00'){
                    $Completed_date = ' ';
                }else{
                    $Completed_date = $date_done;
                }

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
                $output.="
                    <tr>
                        <td>".$data['requisition_ID']."</td>
                        <td>".$helpdesk_name."</td>
                        <td>".$data['assigned_at']."</td>
                        <td>".$data['floor']."</td>
                        <td>".$requesting_employee."</td>
                        <td>".$data['equipment_name']."</td>
                        <td>".$data['equipment_code']."</td>
                        <td>".$data['type_of_work']."</td>
                        <td>".$data['section_required']."</td>
                        <td>".$data['description_works_to_done']."</td>
                        <td>".$data['assigned_engineer']."</td>
                        <td>".$data['assistance_engineer']."</td>
                        <td>".$data['assigned_at']."</td>
                        <td>".$data['date_of_requisition']."</td>
                        <td>".$data['spare_required']."</td>
                        <td>".$data['job_notes']."</td>
                        <td>".$data['visual_test']."</td>
                        <td>".$data['electrical_test']."</td>
                        <td>".$data['functional_test']."</td>
                        <td>".$data['part_requested']."</td>
                        <td>".$data['procurement_order']."</td>
                        <td>".$data['client_info']."</td>
                        <td>".$data['recommendation']."</td>
                        <td>".$data['job_progress']."</td>
                        <td>".$Completed_date."</td>
                        <td>".$data['feedback']."</td>
                        <td>".$data['final_status']."</td>
                        <td>".$satisfaction."</td>
                    </tr>";
                    $temp++;
            }

            $output .= "</table>";

    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename=download.xls");
    echo $output;
?>