
<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Blood_Group'])){
        $Blood_Group = $_GET['Blood_Group'];   
    }else{
        $Blood_Group = '';
    }
	
	if(isset($_GET['Blood_Status'])){
        $Blood_Status = $_GET['Blood_Status'];   
    }else{
        $Blood_Status = '';
    }
	
	
	
    echo '<center><table width =100% border=0>';
	
    echo '<tr id="thead"><td width=5%><b>SN</b></td>';
	if($Blood_Status=='USED'){
	  echo     '<td width=30%><b>PATIENT NAME</b></td>';
		   }
		echo   '<td width=15%><b>BLOOD BATCH</b></td>
	       <td width=15%><b>BLOOD GROUP</b></td>
	       <td width=15%><b>BLOOD VOLUME(ml)</b></td>
		   <td width=10%><b>BLOOD ID</b></td>
		   <td width=25%><b>DATE ISSUED</b></td>
              
                                
								</tr>';
    $select_Filtered_Patient = mysqli_query($conn,
            "Select Blood_Checked_ID,Blood_Group,Blood_Batch,BloodRecorded,Blood_Status,Blood_ID,Patient_Given,Reason,
			Date_Taken,Registered_Date_And_Time from tbl_blood_checked where Blood_Status like '%$Blood_Status%' and
			Blood_Group like '%$Blood_Group%' order by BloodRecorded desc ") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Patient)){
	echo "<tr><td>".$temp."</a></td>";
	if($Blood_Status=='USED'){
	    echo "<td>".$row['Patient_Given']."</a></td>";
		}
		echo "<td>".$row['Blood_Batch']."</a></td>";
        echo "<td>".$row['Blood_Group']."</a></td>";
        echo "<td>".$row['BloodRecorded']."</a></td>";
		 echo "<td>".$row['Blood_ID']."</a></td>";
		echo "<td>".$row['Date_Taken']."</a></td>";
		
        
       $temp++; 
    }   echo "</tr>";
?></table></center>

