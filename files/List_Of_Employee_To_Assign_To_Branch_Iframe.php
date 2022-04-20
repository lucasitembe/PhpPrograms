<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 0;
    if(isset($_GET['Employee_Name'])){
        $Employee_Name = $_GET['Employee_Name'];   
    }else{
        $Employee_Name = '';
    }
	$is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        $is_hr = "&HRWork=true";   
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead">
            <td width="3%"><b>SN</b></td>
            <td><b>EMPLOYEE NAME</b></td>
            <td><b>EMPLOYEE TYPE</b></td>
            <td><b>EMPLOYEE NUMBER</b></td>
            <td><b>EMPLOYEE TITLE</b></td>
            <td><b>JOB CODE</b></td>
            <td><b>ASSIGNED BRANCHES</b></td></tr>';
    
	$select_Filtered_Employees = mysqli_query($conn,
            "SELECT e.Employee_ID,e.Employee_Name,e.Employee_Type,e.Employee_Number,e.Employee_Title,e.Employee_Job_Code,(SELECT COUNT(be.Employee_ID) FROM tbl_branch_employee be WHERE e.Employee_ID = be.Employee_ID) as branches from tbl_employee e WHERE e.Employee_Name LIKE '%$Employee_Name%' and Employee_Name <> 'crdb' order by e.Employee_Name") or die(mysqli_error($conn));

    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<tr><td>".++$temp."</td><td style='text-align: left;'><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'')."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Name']))."</a></td>";
        echo "<td><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".$row['Employee_Type']."</a></td>";
        echo "<td><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".$row['Employee_Number']."</a></td>";
        echo "<td><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Title']))."</a></td>";
        echo "<td><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".$row['Employee_Job_Code']."</a></td>";
        echo "<td><a href='assignemployeebranch.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['branches']))."</a></td>";
    }   echo "</tr>";
?></table></center>