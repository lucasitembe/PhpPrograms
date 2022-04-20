<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Clinic_Nature_Name'])){
        $Clinic_Nature_Name = $_GET['Clinic_Nature_Name'];   
    }else{
        $Clinic_Nature_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:10%;"><b>SN</b></td>
                <td><b>CLINIC NATURE NAME</b></td><td><b>DESCRIPTION</b></td></tr>';
    $select_sub_clinic_natures = mysqli_query($conn,"select * from tbl_clinic_nature
                                            where Clinic_Nature_Name like '%$Clinic_Nature_Name%'
                                                order by Clinic_Nature_ID") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_sub_clinic_natures)){
        echo "<tr>
		<td id='thead'>".$temp."</td>";
        echo "<td><a href='editdepartment.php?Department_ID=".$row['Clinic_Nature_ID']."&EditDepartment=EditDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Nature_Name']."</a></td>";  
         echo "<td><a href='editdepartment.php?Department_ID=".$row['Clinic_Nature_ID']."&EditDepartment=EditDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Nature_Description']."</a></td>";
        $temp++;
    }   echo "</tr>";
?></table></center>

