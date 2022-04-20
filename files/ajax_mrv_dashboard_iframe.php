

<?php
    include("./includes/connection.php");

    if (isset($_GET['filter_works'])) {
        $Date_From = filter_input(INPUT_GET, 'Date_From');
        $Date_To = filter_input(INPUT_GET, 'Date_To');
        $satisfy_id = filter_input(INPUT_GET, 'satisfy_id');
        $Employee_Name = filter_input(INPUT_GET, 'Employee_Name');
    }
    
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = " AND DATE(en.date_of_requisition) BETWEEN '$Date_From'  AND  '$Date_To' ";
    }
    
    if (!empty($satisfy_id) && $satisfy_id != 'All') {
        $filter .=" AND en.satisfy = '$satisfy_id'";
    }
    
    if (!empty($Employee_Name) && $Employee_Name != 'All') {
        $filter .= " AND en.assigned_engineer  = '$Employee_Name'";
    }

    if(isset($_POST['load_data'])){
       
        $results = mysqli_query($conn,"SELECT en.requisition_ID, e.Employee_Name, en.date_of_requisition, en.part_date, en.assistance_engineer, en.assigned_at, en.equipment_name, en.employee_name, en.equipment_code, en.client_info, en.procurement_order, en.type_of_work, en.job_notes, en.spare_required, en.select_dept, dp.Department_Name, jb.jobcard_ID, jb.approved_at, en.section_required, en.description_works_to_done, en.helpdesk, en.floor, en.job_progress, jb.part_date, en.assigned_engineer, en.recommendations, en.satisfy FROM tbl_engineering_requisition en, tbl_jobcards jb, tbl_department dp, tbl_employee e WHERE dp.Department_ID = en.select_dept AND e.Employee_ID = en.employee_name $filter GROUP BY en.requisition_ID ASC") or die(mysqli_error($conn));
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
            $requested_date = '';
            $Requisition = $row['requisition_ID'];
            $satisfy = $row['satisfy'];
            $helpdesk = $row['helpdesk'];
            $helpdesk_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$helpdesk'"))['Employee_Name'];

            if ($partdate == '0000-00-00 00:00:00'){
                $requested_date = 'Not Yet Approved';
            }else{
                $requested_date = $partdate;
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
            

            echo "<tr class='idadi'><td>" . $temp . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $Requisition . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['jobcard_ID'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $helpdesk_name . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['assigned_at'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['Department_Name'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['floor'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['Employee_Name'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['equipment_name'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['equipment_code'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['type_of_work'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['description_works_to_done'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['section_required'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['assigned_engineer'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['assistance_engineer'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['assigned_at'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['part_date'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['job_notes'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['spare_required'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['part_date'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['procurement_order'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['client_info'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['recommendations'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $row['assigned_at'] . "</td>";
            echo "<td style='text-align: center; font-size:12px;'>" . $satisfaction . "</td>";
            echo "</tr>";

            $temp++;
        }
    }