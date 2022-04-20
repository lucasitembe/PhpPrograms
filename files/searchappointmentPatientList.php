<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
     if(isset($_GET['Patient_Name_No'])){
        $Patient_Name_No = $_GET['Patient_Name_No'];  
        if(!empty($Patient_Name_No)){
            $patient_no="AND tpr.Registration_ID ='$Patient_Name_No'";
        }
    }else{
        $Patient_Name_No = '';
        $patient_no='';
    }
	
	
	if(isset($_GET['ward_id'])){
        $ward_id = $_GET['ward_id'];   
    }else{
        $ward_id = '';
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
    echo '<center><table class="fixTableHead" width =100% border=0>';
    echo '<thead><tr id="thead">
                    <th style="width:5%;"><b>SN</b></th>
                    <th style="width:30%;"><b>PATIENT NAME</b></th>
                    <th style="width:10%;"><b>REG#</b></th>
                    <th style="width:20%;"><b>SPONSOR</b></th>
                    <th style="width:20%;"><b>AGE</b></th>
                    <th style="width:5%;"><b>GENDER</b></th>
                    <th style="width:20%;"><b>PHONE#</b></th>
                   
         </tr></thead>';
							
			$select_patient_ward_qry = "SELECT Date_Of_Birth,tpr.Registration_ID,Guarantor_Name,Gender,tpr.Phone_Number,tpr.Patient_Name FROM tbl_patient_registration AS tpr  JOIN tbl_sponsor AS sp ON tpr.Sponsor_ID=sp.Sponsor_ID AND tpr.Patient_Name<>'' ORDER BY tpr.Patient_Name LIMIT 50";
                        if($Patient_Name != '' || $Patient_Name_No!= ''){                            
				$select_patient_ward_qry = "SELECT Date_Of_Birth,tpr.Registration_ID,Guarantor_Name,Gender,tpr.Phone_Number,tpr.Patient_Name FROM tbl_patient_registration AS tpr JOIN tbl_sponsor AS sp ON tpr.Sponsor_ID=sp.Sponsor_ID WHERE tpr.Patient_Name LIKE '%$Patient_Name%' $patient_no AND tpr.Patient_Name<>'' ORDER BY tpr.Patient_Name LIMIT 50";
			}
				
            $select_patient_ward = mysqli_query($conn,$select_patient_ward_qry) or die(mysqli_error($conn)); 
    $back_direction="";

    if(isset($_GET['frompage']) && $_GET['frompage'] == "reception"){
        $back_direction="&frompage=reception";
    }  
     while($row = mysqli_fetch_array($select_patient_ward)){
	 //AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		 
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
        
        echo "<tr><td id='thead'>".$temp."</td><td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
       
        echo "<td><a href='appointmentPage.php?Registration_ID=".$row['Registration_ID'].$back_direction."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
         $temp++;
    }   echo "</tr>";
?></table></center>
<link rel="stylesheet" href="table.css" media="screen"> 
<link rel="stylesheet" href="fixHeader.css">

