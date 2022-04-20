

<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Date_Choosen'])){
        $Date_Choosen = $_GET['Date_Choosen'];   
    }else{
        $Date_Choosen = '';
    }
	
	
	if(isset($_GET['Blood_Group'])){
        $Blood_Group = $_GET['Blood_Group'];   
    }else{
        $Blood_Group = '';
    }
	
	
	
    echo '<center><table width =100% >';
	
    echo '<tr id="thead"><td width=5%><b>SN</b></td>
	       <td><b>BLOOD BATCH</b></td>
		   <td><b>BLOOD GROUP</b></td>
		   <td><b>BLOOD VOLUME(ml)</b></td>
		    <td><b>BLOOD_ID</b></td>
		   <td><b>EXPIRED DATE</b></td>
              
                                
								</tr>';
    $select_Filtered_Donors = mysqli_query($conn,
            "Select Blood_Group,Blood_Batch,Blood_Volume,Blood_ID, Blood_Expire_Date,Transfusion_Date_Time 
			from 
			tbl_patient_blood_data where Blood_Volume>0 and Blood_Group like '%$Blood_Group%' && Blood_Expire_Date like '%$Date_Choosen%' order by Blood_Volume desc") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
	echo "<tr><td>".$temp."</a></td>";
        echo "<td>".$row['Blood_Batch']."</a></td>";
        echo "<td>".$row['Blood_Group']."</a></td>";
		echo "<td>".$row['Blood_Volume']."</a></td>";
		echo "<td>".$row['Blood_ID']."</a></td>";
		echo "<td>".$row['Blood_Expire_Date']."</a></td>";
        
       $temp++; 
    }   echo "</tr>";
?></table></center>

