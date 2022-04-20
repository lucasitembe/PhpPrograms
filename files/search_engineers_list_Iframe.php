<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['assigned_engineer'])){
        $assigned_engineer = str_replace(" ", "%", $_GET['assigned_engineer']);
    }else{
        $assigned_engineer = '';
    }
	
	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

	
   echo '<center><table width =100%>';
   echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
   <td><b>REQUISITION NO:</b></td>
       <td><b>REQUESTED DATE</b></td>
       <td><b>REQUESTED STAFF</b></td>
       <td><b>ADMINISTRATIVE RESP.</b></td>
           <td><b>DEPARTMENT</b></td>
           <td><b>EQUIPMENT NAME</b></td>
               <td><b>FLOOR / LOCATION</b></td>
               <td><b>ASSIGNED ENGINEER</b></td>
               <td><b>ASSIGNED SECTION</b></td>
               <td><b>NO OF DAYS</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_engineering_requisition where
		    requisition_status = 'assigned' and job_progress='not perfomed' order by requisition_ID Desc limit 200") or die(mysqli_error($conn));

			   
            while($row = mysqli_fetch_array($select_Filtered_Patients)){
                $requisition_ID = $row['requisition_ID'];
                $employee_name=$row['employee_name'];
                $department_name=$row['select_dept'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['date_of_requisition']);
                $diff = $date1->diff($date2);
                $no_of_days = $diff->d . " Days";
                $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];
    
	
        echo "<tr><td width ='2%' id='thead'>".$temp."</td>
        <td><center>".$requisition_ID."</center></a></td>
        <td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['date_of_requisition']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$employee."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['title']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$department."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['equipment_name']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['floor']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['assigned_engineer']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['section_required']."</a></td>";
        echo "<td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$no_of_days."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>


