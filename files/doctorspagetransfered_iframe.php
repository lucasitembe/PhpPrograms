<link rel="stylesheet" href="table.css" media="screen"> 
<?php
	include("./includes/connection.php");
	//$Date_From='';
	//$date_To='';
  
   if(isset($_GET['date_From'])){
        $date_From = $_GET['date_From'];   
    }else{
        $date_From = '';
    }
	
	 if(isset($_GET['date_To'])){
        $date_To = $_GET['date_To'];   
    }else{
        $date_To = '';
    }
	
	if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
	
	if(isset($_GET['Patient_Name_No'])){
        $Patient_Name_No = $_GET['Patient_Name_No']; 
		$patient_no="AND pr.Registration_ID LIKE '%$Patient_Name_No%'";		
    }else{
        $Patient_Name_No = '';
		$patient_no='';
    }
	
	 $temp=1;

	
	
	
				
	echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
				<td width = 12%><b>PATIENT NAME</b></td>
				<td width = 9% ><b>PATIENT NO</b></td>
				  <td width = 13%><b>TRANSFERED FROM</b></td>
				<td width = 13%><b>TRANSFERED TO</b></td>
                <td width = 18%><b>PERSONNEL AUTHORIZE </b></td>
				 <td width = 13%><b>DATE OF TRANSFER</b></td>
                 <td width = 25%><b>REASONS</b></td>
            </tr>';
				//	GROUP BY tr.Registration_ID 
		if(isset($_GET['date_From']) && isset($_GET['date_To'])){			
			$select_Patient_Transfered = mysqli_query($conn,"SELECT * 
			FROM 
					tbl_Patient_Registration pr, tbl_transfer tr
			WHERE 
			 pr.Registration_ID = tr.Registration_ID 	AND 
			 Transfer_Date BETWEEN '$date_From' AND '$date_To'
					 ORDER BY Transfer_Date DESC ") or die(mysqli_error($conn));
				
		$trasnfered_patients = mysqli_num_rows($select_Patient_Transfered);
		
			}else{
			$select_Patient_Transfered = mysqli_query($conn,"SELECT * 
			FROM 
					tbl_Patient_Registration pr, tbl_transfer tr
			WHERE 
			 pr.Registration_ID = tr.Registration_ID  and Patient_Name like '%$Patient_Name%' $patient_no
					 ORDER BY Transfer_Date DESC LIMIT 50 ") or die(mysqli_error($conn));
				
		$trasnfered_patients = mysqli_num_rows($select_Patient_Transfered);
			
			
			}
		 if($trasnfered_patients > 0){
						while($row = mysqli_fetch_array($select_Patient_Transfered)){
							$Registration_ID = $row['Registration_ID'];
							$Employee_ID_Do_Transfer = ucwords(strtolower($row['Employee_ID_Do_Transfer']));
							$Employee_ID_From = $row['Employee_ID_From'];
							$Employee_ID_To = ucwords(strtolower($row['Employee_ID_To']));
							$Transfer_Date = $row['Transfer_Date'];
							$Reason = $row['Reason'];
							$Patient_Name = ucwords(strtolower($row['Patient_Name']));
							$Transfer_Date = $row['Transfer_Date'];
						
		echo "<tr><td id='thead'>".$temp."</td><td>" .$Patient_Name. "</td>";
		
		echo "<td style='text-align:center'>" .$Registration_ID. "</td>";
		
		?>
		<?php	
		//TRASFERED FROM WHICH DOCTOR
		$select_name=mysqli_query($conn,"select Employee_Name,Employee_ID
									FROM 
									tbl_Employee where Employee_ID='$Employee_ID_From'") or die (mysqli_error($conn));
					//		$Item_result = mysqli_query($conn,$select_name);
					
				$row1=mysqli_fetch_assoc($select_name);
				$empl_name=$row1['Employee_Name'];
			?>	
			<td> <?php echo $empl_name; ?></td>	
		
		
		
		<?php	
		
		//TRASFERED TO WHICH DOCTOR
		$select_name=mysqli_query($conn,"select Employee_Name,Employee_ID
									FROM 
									tbl_Employee where Employee_ID='$Employee_ID_To'
									") or die (mysqli_error($conn));
					//		$Item_result = mysqli_query($conn,$select_name);
					
				$row1=mysqli_fetch_assoc($select_name);
				$empl_name=$row1['Employee_Name'];
			?>	
			<td> <?php echo $empl_name; ?></td>
			
			
			<?php	
			
			//PERSONNEL AUTHORIZE TRASFERER
			$select_name=mysqli_query($conn,"select Employee_Name,Employee_ID
									FROM 
									tbl_Employee where Employee_ID='$Employee_ID_Do_Transfer'") or die (mysqli_error($conn));
					//		$Item_result = mysqli_query($conn,$select_name);
					
				$row1=mysqli_fetch_assoc($select_name);
				$empl_name=$row1['Employee_Name'];
			?>	
			<td> <?php echo $empl_name; ?></td>
			
			
			<?php
		echo "<td>" .$Transfer_Date. "</td>";
		echo "<td>" .$Reason. "</td>";
		
	
    $temp++;

	}
	echo "</tr>";
}
			
?>
</table></center>