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
   <td><b>ANALYSIS ID NO:</b></td>
       <td><b>REQUISITION ID</b></td>
       <td><b>ANALYSIS DATE</b></td>
           <td><b>EQUIPMENT NAME</b></td>
               <td><b>FLOOR / LOCATION</b></td>
               <td><b>ASSIGNED ENGINEER</b></td>
               <td><b>ASSIGNED SECTION</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,"SELECT an.Analysis_ID, rq.equipment_name, rq.floor, rq.equipment_name, an.Requisition_ID, rq.section_required, an.Created_at, an.Employee_ID, em.Employee_Name FROM tbl_5_why_analysis an, tbl_employee em, tbl_engineering_requisition rq WHERE  an.status = 'saved' AND em.Employee_ID = an.Employee_ID  AND an.Requisition_ID = rq.requisition_ID Group by an.Analysis_ID Desc") or die(mysqli_error($conn));
			   
            while($row = mysqli_fetch_array($select_Filtered_Patients)){
                $requisition_ID = $row['Requisition_ID'];
                $Analysis_ID = $row['Analysis_ID'];
	
        echo "<tr><td width ='2%' id='thead'>".$temp."</td>
        <td><center>".$Analysis_ID."</center></a></td>

        <td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$requisition_ID."</a></td>";
        echo "<td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['Created_at']."</a></td>";
        echo "<td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['equipment_name']."</a></td>";
        echo "<td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['floor']."</a></td>";
        echo "<td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
        echo "<td><a href='usahihi_5_why_analysis.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."'target='_parent' style='text-decoration: none;'>".$row['section_required']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>


