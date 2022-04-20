<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    //get section value
    if(isset($_GET['Section'])){
	$Section = $_GET['Section'];
    }else{
	$Section = '';
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>EMPLOYEE NAME</b></td>
                <td><b>EMPLOYEE TYPE</b></td>
                    <td><b>EMPLOYEE NUMBER</b></td>
                        <td><b>EMPLOYEE TITLE</b></td>
                            <td><b>JOB CODE</b></td>
                                <td><b>BRANCH</b></td></tr>';
    
    $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_employee order by employee_number") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
	if($Section != ''){
	    echo "<tr><td id='thead'>".$temp."<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&employeeperfomancereport=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Name']))."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Type']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Number']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Title']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Job_Code']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Section=".$Section."&Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Branch_Name']."</a></td>";
	}else{
	    echo "<tr><td id='thead'>".$temp."<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&employeeperfomancereport=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Employee_Name']))."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Type']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Number']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Title']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Job_Code']."</a></td>";
	    echo "<td><a href='employeeperfomancereport.php?Employee_ID=".$row['Employee_ID']."&EmployeePerfomance=EmployeePerfomanceThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Branch_Name']."</a></td>";
	}
	$temp++;
    }   echo "</tr>";
?></table></center>

