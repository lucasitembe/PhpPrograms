<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Name_Of_Body'])){
        $Name_Of_Body= $_GET['Name_Of_Body'];   
    }else{
        $Name_Of_Body = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr><td style="text-align: center;"><b>SN</b></td>
	    <td><b>Name of dead body</b></td>
		<td><b>Date of death</b></td>
		    <td><b>Gender</b></td> 
            <td><b>Age</b></td>   			
				<td><b>Description</b></td>
					</tr>';
			
    $select_Filtered_Patients = mysqli_query($conn,"
				   SELECT Dead_ID, Name_Of_Body,Time_For_Dead,Gender,Age,Description_Death FROM tbl_dead_regisration
				    WHERE Name_Of_Body LIKE '%$Name_Of_Body%'") or die(mysqli_error($conn));
/// selecting deady body whoes registered in the system
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td style='text-align: center;'>".$temp."</td>";

	    echo "<td><a href='editemorgueregistrationForm.php?Dead_ID=".$row['Dead_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Name_Of_Body']."</a></td>";
	    echo "<td><a href='editemorgueregistrationForm.php?Dead_ID=".$row['Dead_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Time_For_Dead']."</a></td>";
        echo "<td><a href='editemorgueregistrationForm.php?Dead_ID=".$row['Dead_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='editemorgueregistrationForm.php?Dead_ID=".$row['Dead_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Age']."</a></td>";
		echo "<td><a href='editemorgueregistrationForm.php?Dead_ID=".$row['Dead_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Description_Death']."</a></td>";
	$temp++;
    }   echo "</tr>";
?>
</table>
</center>