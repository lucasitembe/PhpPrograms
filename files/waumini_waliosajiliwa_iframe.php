<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name = '';
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

	
   echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>MEMBER NAME</b></td>
            <td><b>MEMBER NUMBER</b></td>
                        <td><b>Age</b></td>
                            <td><b>PHONE NUMBER</b></td>';
    $select_Filtered_Members = mysqli_query($conn,"SELECT * FROM tbl_taarifa_muumini") or die(mysqli_error($conn));
	   
    while($row = mysqli_fetch_array($select_Filtered_Members)){
	
	//AGE FUNCTION
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['tarehe_zaliwa']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
	
        echo "<tr><td width ='2%' id='thead'>".$temp."<td><a href='muumini_taarifa_zaidi.php?Registration_ID=".$row['muumini_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['jina_kamili']))."</a></td>";
        echo "<td><a href='muumini_taarifa_zaidi.php?Registration_ID=".$row['muumini_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['muumini_id']."</a></td>";
	echo "<td><a href='muumini_taarifa_zaidi.php?Registration_ID=".$row['muumini_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='muumini_taarifa_zaidi.php?Registration_ID=".$row['muumini_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['namba_simu']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>

