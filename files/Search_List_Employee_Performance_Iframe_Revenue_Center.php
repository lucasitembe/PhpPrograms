<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Employee_Name'])){
        $Employee_Name = $_GET['Employee_Name'];   
    }else{
        $Employee_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>EMPLOYEE NAME</b></td>
                <td><b>EMPLOYEE TYPE</b></td>
                    <td><b>EMPLOYEE NUMBER</b></td>
                        <td><b>EMPLOYEE TITLE</b></td>
                            <td><b>JOB CODE</b></td>
                                <td><b>BRANCH</b></td></tr>';
    
    
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_Employee where
                Employee_Name like '%$Employee_Name%' order by employee_number"); 

		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<tr><td>".$temp."<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
        echo "<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Type']."</a></td>";
        echo "<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Number']."</a></td>";
        echo "<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Title']."</a></td>";
        echo "<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Job_Code']."</a></td>";
        echo "<td><a href='employeerevenueperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Branch_Name']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>

