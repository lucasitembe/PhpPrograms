<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<?php
if($_GET['Date_From']!=''){
    $Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
}else{
    $Date_From='';
}
if($_GET['Date_To']!=''){
    $Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
}else{
    $Date_To='';
}

?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
    //run the query to select all data from the database according to the branch id
    $select_specimen_query ="SELECT * FROM tbl_laboratory_specimen";
    $select_specimen_result=mysqli_query($conn,$select_specimen_query);
    
   
    while($specimenrow=mysqli_fetch_array($select_specimen_result)){//loop to select all sponsors
	$count=0;
	$Specimen_ID=$specimenrow['Specimen_ID'];
	$Specimen_Name=$specimenrow['Specimen_Name'];
	echo "<tr><td style='text-align:left; width:10%' colspan='9'><b style='color: black;'>".strtoupper($Specimen_Name)."</b></td></tr>";
	echo "<tr id='thead'>
			    <td style='width:3%'><b>SN</b></td>
			    <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
			    <td style='text-align: left;' width=10%><b>PATIENT NUMBER</b></td>
			    <td style='text-align: left;' width=10%><b>REGION</b></td>
			    <td style='text-align: left;' width=10%><b>DISTRICT</b></td>
			    <td style='text-align: left;' width=10%><b>GENDER</b></td>
			    <td style='text-align: left;' width=10%><b>AGE</b></td>
			    <td style='text-align: left;' width=10%><b>REGISTRATION DATE</b></td>
			    <td style='text-align: left;' width=10%><b>DIRECTED FROM</b></td>
		     </tr>";
	
	//run the query to select all details for each returned sponsor
        $Current_Date=date('Y-m-d');
	$select_data="SELECT * FROM tbl_patient_registration pr,
                                    tbl_payment_cache pc,
                                    tbl_item_list_cache il,
                                    tbl_patient_cache_test_specimen pcts,
                                    tbl_laboratory_test_specimen lts,
                                    tbl_laboratory_specimen ls
                                    WHERE pr.Registration_ID=pc.Registration_ID
                                        AND pc.Payment_Cache_ID=il.Payment_Cache_ID
                                        AND il.Payment_Item_Cache_List_ID=pcts.Payment_Item_Cache_List_ID
                                        AND pcts.Laboratory_Test_Specimen_ID=lts.Laboratory_Test_specimen_ID
                                        AND lts.Specimen_ID=ls.Specimen_ID
                                        AND ls.Specimen_ID='$Specimen_ID'
                                        AND pcts.Time_Taken BETWEEN '$Date_From' AND '$Date_To'
                                        GROUP BY il.Payment_Cache_ID";
	$select_data_result=mysqli_query($conn,$select_data);
	$total_rows=mysqli_num_rows($select_data_result);
	 $total_Male=0;
    	 $total_Female=0;
	 $total_Male_Female=0;
	$sno=1;
	while($row=mysqli_fetch_array($select_data_result)){//loop to select data
	    //return rows
		$registration_ID=$row['Registration_ID'];
		$patientName=$row['Patient_Name'];
		$Registration_ID=$row['Registration_ID'];
		$Region=$row['Region'];
		$District=$row['District'];
		$Gender=$row['Gender'];
		$dob=$row['Date_Of_Birth'];
		$Registration_Date_And_Time=$row['Registration_Date_And_Time'];
		
		//these codes are here to determine the age of the patient
		$date1 = new DateTime(date('Y-m-d'));
		$date2 = new DateTime($dob);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
		echo "<tr><td>".($sno)."</td>";
		echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
		echo "<td style='text-align:left; width:10%'>".$row['Registration_ID']."</td>";
		echo "<td style='text-align:left; width:10%'>".$Region."</td>";
		echo "<td style='text-align:left; width:10%'>".$District."</td>";
		echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
		echo "<td style='text-align:left; width:15%'>".$age."</td>";
		echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
		echo "<td style='text-align:left; width:10%'>".$row['Consultant']."</td>";
		echo "</tr>";
		
		//run the query to select malle and female
		$query="SELECT ls.Specimen_ID,ls.Specimen_Name,
			(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr,
                                                                    tbl_payment_cache pc,
                                                                    tbl_item_list_cache il,
                                                                    tbl_patient_cache_test_specimen pcts,
                                                                    tbl_laboratory_test_specimen lts,
                                                                    tbl_laboratory_specimen ls
                                                                    WHERE pr.Registration_ID=pc.Registration_ID
                                                                        AND pc.Payment_Cache_ID=il.Payment_Cache_ID
                                                                        AND il.Payment_Item_Cache_List_ID=pcts.Payment_Item_Cache_List_ID
                                                                        AND pcts.Laboratory_Test_Specimen_ID=lts.Laboratory_Test_specimen_ID
                                                                        AND lts.Specimen_ID=ls.Specimen_ID
                                                                        AND pr.Gender='Male'
                                                                        AND pcts.Time_Taken BETWEEN '$Date_From' AND '$Date_To'
                                                                        GROUP BY il.Payment_Cache_ID  
			) as male,
			(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr,
                                                                    tbl_payment_cache pc,
                                                                    tbl_item_list_cache il,
                                                                    tbl_patient_cache_test_specimen pcts,
                                                                    tbl_laboratory_test_specimen lts,
                                                                    tbl_laboratory_specimen ls
                                                                    WHERE pr.Registration_ID=pc.Registration_ID
                                                                        AND pc.Payment_Cache_ID=il.Payment_Cache_ID
                                                                        AND il.Payment_Item_Cache_List_ID=pcts.Payment_Item_Cache_List_ID
                                                                        AND pcts.Laboratory_Test_Specimen_ID=lts.Laboratory_Test_specimen_ID
                                                                        AND lts.Specimen_ID=ls.Specimen_ID
                                                                        AND pr.Gender='Female'
                                                                        AND pcts.Time_Taken BETWEEN '$Date_From' AND '$Date_To'
                                                                        GROUP BY il.Payment_Cache_ID     
			) as female
			FROM tbl_laboratory_specimen ls WHERE Specimen_ID='$Specimen_ID' ORDER BY ls.Specimen_Name ASC
			";
		$select_patients = @mysqli_query($conn,$query);
		$total_Male=0;
		$total_Female=0;
		$res=@mysqli_num_rows($select_patients);
		for($i=0;$i<$res;$i++){
		$row=mysqli_fetch_array($select_patients);
		//return rows
		$Specimen_ID=$row['Specimen_ID'];
		$Specimen_Name=$row['Specimen_Name'];
		$male=$row['male'];
		$female=$row['female'];
		$total_Male=$total_Male + $male;
		$total_Female=$total_Female + $female;
		$total_Male_Female=$total_Male+$total_Female;
		$count++;
	    }//end for loop
	    $sno++;
		
	}//end loop to select data
	echo "<tr><td colspan='9' style='text-align:center;'><b style='font-size:12px;font-family:verdana;color:black;'><i>Total Patients &nbsp;</i></b><b>".$total_rows."</b></td></tr>";
    }//end loop to select all sponsors
    echo "</table></center>";
	    ?>