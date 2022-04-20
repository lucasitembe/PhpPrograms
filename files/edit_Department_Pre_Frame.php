<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Department_Name'])){
        $Department_Name = $_GET['Department_Name'];   
    }else{
        $Department_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td><b>SN</b></td><td><b>NATURE OF THE DEPARTMENT</b></td>
                <td><b>DEPARTMENT NAME</b></td></tr>';
    $select_sub_departments = mysqli_query($conn,"select * from tbl_department order by department_id") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_sub_departments)){
        echo "<tr>
		<td>".$temp."</td>
		<td width=50%><a href='editdepartment.php?Department_ID=".$row['Department_ID']."&EditDepartment=EditDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Department_Location']."</a></td>";
        echo "<td><a href='editdepartment.php?Department_ID=".$row['Department_ID']."&EditDepartment=EditDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Department_Name']."</a></td>";  
	$temp++;
    }   echo "</tr>";
?></table></center>

