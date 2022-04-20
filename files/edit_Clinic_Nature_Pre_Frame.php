<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Clinic_Name'])){
        $Clinic_Name = $_GET['Clinic_Name'];   
    }else{
        $Clinic_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:10%;"><b>SN</b></td>
                <td><b>CLINIC NATURE NAME</b></td><td ><b>DESCRIPTION</b></td></tr>';
    $select_sub_clinics = mysqli_query($conn,"select * from tbl_clinic_nature") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_sub_clinics)){
        echo "<tr>
		<td id='thead'>".$temp."</td>";
        echo "<td><a href='editclinicnature.php?Clinic_Nature_ID=".$row['Clinic_Nature_ID']."&EditClinicNature=EditClinicNatureThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Nature_Name']."</a></td>";  
        echo "<td><a href='editclinicnature.php?Clinic_Nature_ID=".$row['Clinic_Nature_ID']."&EditClinicNature=EditClinicNatureThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Nature_Description']."</a></td>";  
      /*  echo "<td><a href='editclinicnature.php?Clinic_Nature_ID=".$row['Clinic_Nature_ID']."&EditClinic=EditClinicThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Status']."</a></td>";  */
        $temp++;
    }   echo "</tr>";
?>
</table></center>

