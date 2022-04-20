<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Employee_Name'])){
        $Employee_Name = $_GET['Employee_Name'];   
    }else{
        $Employee_Name = '';
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>EMPLOYEE NAME</b></td>
		<td><b>EMPLOYEE TYPE</b></td>
		    <td><b>EMPLOYEE TITLE</b></td> 
                        <td><b>JOB CODE</b></td>
			    <td><b>DEPARTMENT</b></td></tr>';
    
    
    $select_Filtered_Employees = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dep where
                                                emp.department_id = dep.department_id and
                                                emp.Employee_Name <> 'crdb' and
                                                Employee_Name like '%$Employee_Name%' order by employee_name") or die(mysqli_error($conn)); 

		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        echo "<a href='#' ><tr><td id='thead'>".$temp."<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".ucwords(strtolower($row['Employee_Name']))."</td>";
        echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Employee_Type']."</td>";
        echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".ucwords(strtolower($row['Employee_Title']))."</td>";
        echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".$row['Employee_Job_Code']."</td>";
        echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='Click This Employee To Edit'>".ucwords(strtolower($row['Department_Name']))."</td>";
	$temp++;
	echo "</tr></a>";
    }
?></table></center>

