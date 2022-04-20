<link rel="stylesheet" href="table.css" media="screen">
<link rel='stylesheet' href='fixHeader.css'>
<?php
    include("./includes/connection.php");
    $temp = 0;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        $is_hr = "&HRWork=true";   
    }
    echo '<table width =100% class="fixTableHead">';
    echo '
        <thead>
            <tr id="thead">
                <td width="3%"><b>SN</b></td>
                <td><b>EMPLOYEE NAME</b></td>
                <td><b>EMPLOYEE TYPE</b></td>
                <td><b>EMPLOYEE NUMBER</b></td>
                <td><b>EMPLOYEE TITLE</b></td>
                <td><b>JOB CODE</b></td>
                <td><b>BRANCH</b></td>
            </tr>
        </thead>';
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_employee where Employee_Name <> 'crdb' order by employee_name") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<tr><td>".++$temp."</td><td><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Name']))."</a></td>";
        echo "<td style='text-align:left'><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;text-align:left'>".$row['Employee_Type']."</a></td>";
        echo "<td><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".$row['Employee_Number']."</a></td>";
        echo "<td><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Title']))."</a></td>";
        echo "<td><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".$row['Employee_Job_Code']."</a></td>";
        echo "<td><a href='assignemployeesubdepartment.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm".$is_hr."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Branch_Name']))."</a></td>";
    }   echo "</tr>";
?></table>

