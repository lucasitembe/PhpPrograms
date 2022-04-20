<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Disease_Group_Name'])){
        $Disease_Group_Name = $_GET['Disease_Group_Name'];   
    }else{
        $Disease_Group_Name = '';
    }
    echo '<center><table width = 100%>';
    echo '<tr id="thead">
                <td style = "width: 5%"><b>SN</b></td>
                <td><b>Disease Name</b></td>
                <td><b>Gender Type</b></td>
                <td><b>Below 1 Month</b></td>
                <td><b>1 Month Or Below 1 Year</b></td>
                <td><b>1 Year Or Below 5 Years</b></td>
                <td><b>5 Years Or Below 60 Years</b></td>
                <td><b>60 Years Or Above</b></td>
                <td><b>DHIS2 Report </b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,"SELECT * FROM tbl_disease_group where disease_group_name like '%$Disease_Group_Name%' ORDER BY disease_group_name asc ") or die(mysqli_error($conn));

    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td id='thead'>".$temp."</td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['disease_group_name']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender_Type']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Age_Below_1_Month']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Age_Between_1_Month_But_Below_1_Year']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Age_Between_1_Year_But_Below_5_Year']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Five_Years_Or_Below_Sixty_Years']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Age_60_Years_And_Above']."</a></td>";
        echo "<td><a href='editdiseasegroup.php?disease_group_ID=".$row['disease_group_id']."&EditDiseaseGroup=EditDiseaseThisForm' target='_parent' style='text-decoration: none;'>".$row['Opd_Report']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>