<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Disease_Name'])){
        $Disease_Name = $_GET['Disease_Name'];   
    }else{
        $Disease_Name = '';
    }
    echo '<center><table width = 100%>';
    echo '<tr id="thead">
	    <td style = "width: 5%"><b>SN</b></td>
		<td><b>Disease Code</b></td>
		    <td><b>Disease Name</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,"SELECT * FROM tbl_disease where Disease_Name like '%$Disease_Name%' ORDER BY disease_name asc LIMIT 500") or die(mysqli_error($conn));

    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td>".$temp."<td><a href='editdisease.php?disease_ID=".$row['disease_ID']."&EditDisease=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['disease_code']."</a></td>";
        echo "<td><a href='editdisease.php?disease_ID=".$row['disease_ID']."&EditDisease=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['disease_name']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>