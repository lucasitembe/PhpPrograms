<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    $filter='';

    if(isset($_GET['Department_Name'])){
        $Department_Name = $_GET['Department_Name'];  
        $filter=" WHERE Clinic_Department_Name  LIKE '%$Department_Name%'";
    }else{
        $Department_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:10%;"><b>SN</b></td>
                <td><b>CLINIC DEPARTMENT NAME</b></td></tr>';
    $select_sub_clinics = mysqli_query($conn,"select * from tbl_clinic_department $filter") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_sub_clinics)){
        echo "<tr>
		<td id='thead'>".$temp."</td>";
        echo "<td><a    href='editclinicdepartment.php?Clinic_Department_ID=".$row['Clinic_Department_ID']."&EditClinicDepartment=EditClinicDepartmentThisForm' target='_parent' style='text-decoration: none; display:block;'>". $row['Clinic_Department_Name']."</a></td>";  
        $temp++;
    }   echo "</tr>";
?>
</table></center>