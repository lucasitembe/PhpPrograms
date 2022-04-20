<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
	
	 if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }else{
        $Registration_ID = '';
    }

		
	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

	
//table for technical instruction
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">	<td style="width:5%;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td><b>PATIENT NO</b></td>
				<td><b>AGE</b></td>
				<td><b>SPONSOR</b></td>
                 <td><b>GENDER</b></td>
                            </tr>';
							
	$select_theather = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, 
				Gender, Date_Of_Birth
				FROM 
						tbl_patient_registration pr, tbl_sponsor sp
				WHERE 
						pr.Patient_Name like '%$Patient_Name%' AND
						pr.sponsor_id = sp.sponsor_id   
						group by pr.Registration_ID limit 100") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_theather)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
       
	   echo "<tr><td id='thead'>".$temp."</td><td><a href='theatherpageworks.php?Registration_ID=".$row['Registration_ID']."&theather=theatherThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		echo "<td><a href='theatherpageworks.php?Registration_ID=".$row['Registration_ID']."&theather=theatherThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		
		echo "<td><a href='theatherpageworks.php?Registration_ID=".$row['Registration_ID']."&theather=theatherThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        
		echo "<td><a href='theatherpageworks.php?Registration_ID=".$row['Registration_ID']."&theather=theatherThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        
        echo "<td><a href='theatherpageworks.php?Registration_ID=".$row['Registration_ID']."&theather=theatherThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    $temp++;
	echo "</tr>";
    }   
?></table></center>

