<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['assigned_engineer'])){
        $assigned_engineer = $_GET['assigned_engineer'];
    }else{
        $assigned_engineer = '';
    }
    if(isset($_GET['section_required'])){
        $section_required = $_GET['section_required'];
    }else{
        $section_required = '';
    }

    $Mrv_Number = $_GET['Mrv_Number'];

    if(isset($_GET['src'])){
	 $src = $_GET['src'];
    }else{
	 $src = '';
    }
  //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
	
    if ($section_required !='All'){
        $filter .= " AND section_required = '$section_required'";
    }else{
        $filter .= "";
    }

    if (!empty($assigned_engineer)){
        $filter .= " AND assigned_engineer LIKE '%" . $assigned_engineer . "%'";
    }
	
    if(!empty($Mrv_Number)){
        $filter .= " AND requisition_ID = '$Mrv_Number'";
    }
	

    // echo '<center><table  class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">';
    // echo '<thead>';
    // echo '<tr style="background: #dedede;">
    // </tr>
    //                 </thead>
    //                 <tbody>';


        $select_Filtered_Patients = mysqli_query($conn,"SELECT requisition_ID, assigned_at, employee_name, select_dept, date_of_requisition, title, equipment_name, floor, assigned_engineer, section_required, TIMESTAMPDIFF(DAY,assigned_at,NOW()) AS NoOfDay, Indicated_Days from tbl_engineering_requisition WHERE requisition_status = 'assigned' and completed='no' and job_progress='not perfomed' $filter order by requisition_ID Desc limit 200") or die(mysqli_error($conn));

        if(mysqli_num_rows($select_Filtered_Patients)>0){
            while($row = mysqli_fetch_array($select_Filtered_Patients)){
                $requisition_ID=$row['requisition_ID'];
                $employee_name=$row['employee_name'];
                $department_name=$row['select_dept'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['assigned_at']);
                $diff = $date1->diff($date2);
                $no_of_days = $diff->y . " Years ";
                $no_of_days .= $diff->m . " Months ";
                $no_of_days .= $diff->d . " Days";

                $NoOfDay = $row['NoOfDay'];
                $Indicated_Days = $row['Indicated_Days'];

                if($NoOfDay > $Indicated_Days){
                    $style = "background: #bd0d0d; color: #fff !important; font-weight: bold;";
                }else{
                    $style = "color: #000";
                }
                $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];
    
	
            echo "<tr style='$style'>
            <td width ='2%'><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$temp."</a></td>
            <td width ='2%'><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$requisition_ID."</a></td>
            <td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['date_of_requisition']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$employee."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['title']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['equipment_name']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['floor']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['assigned_engineer']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$row['section_required']."</a></td>";
            echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none; $style'>".$no_of_days."</a></td></tr>";
        $temp++;
        }
    }else{
        echo "<tr>
                    <td style='text-align: center;' colspan='10'><h5 style='color: #bd0d0d; font-weight: bold;'>THERE'S NO PENDING JOB FOR SELECTED CATEGORIES</h5></td></tr>";
    }
?></table>

