<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
	$temp=1;
    
	if(isset($_GET['Batch_Name'])){
        $Batch_Name = $_GET['Batch_Name'];   
    }else{
        $Batch_Name = '';
    }
    echo '<center><table width =90% border=0>';
    echo '<tr id="thead"><td width=2%><span style="font-size: small;"><b>SN</b></td>
	          <td width=50%><span style="font-size: small;"> <b>BATCH NAME</b></td>
                                
								</tr>';
    $select_Filtered_Batch = mysqli_query($conn,
            "select Batch_ID,Batch_Name
		from tbl_blood_batches where Batch_Name like '%$Batch_Name%' ") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Batch)){
        echo "<tr><td><a href='editbloodbatch.php?Batch_ID=".$row['Batch_ID']."&BloodBatch=BloodBatchThisForm' target='_parent' style='text-decoration: none;'>".$temp."</a></td>";
        echo "<td><a href='editbloodbatch.php?Batch_ID=".$row['Batch_ID']."&BloodBatch=BloodBatchThisForm' target='_parent' style='text-decoration: none;'>".$row['Batch_Name']."</td>";

        
        $temp++;
    }   echo "</tr>";
?></table></center>

