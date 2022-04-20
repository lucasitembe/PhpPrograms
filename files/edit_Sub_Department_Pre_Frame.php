<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Sub_Department_Name'])){
        $Sub_Department_Name = $_GET['Sub_Department_Name'];   
    }else{
        $Sub_Department_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>DEPARTMENT NAME</b></td>
                <td><b>SUB DEPARTMENT NAME</b></td></tr>';
    $select_sub_departments = mysqli_query($conn,"select * from tbl_department dep, tbl_sub_department sdep
						where dep.department_id = sdep.department_id order by dep.department_id") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_sub_departments)){
        echo "<tr><td>".$temp."</td><td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Department_Name']."</a></td>";
        echo "<td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";  
	$temp++;
    }   echo "</tr>";
?></table></center>

