<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<?php
if($_GET['Sub_Department_ID']!=''){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID='';
}
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
    $select_item_query ="SELECT * FROM tbl_items it,tbl_item_list_cache il
                                        WHERE it.Item_ID=il.Item_ID
                                                AND il.Service_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' GROUP BY il.Item_ID";
    $select_item_result=mysqli_query($conn,$select_item_query);
    
   
    while($item_row=mysqli_fetch_array($select_item_result)){//loop to select all sponsors
	$count=0;
	$Item_ID=$item_row['Item_ID'];
	$Item_Name=$item_row['Product_Name'];
	echo "<tr><td style='text-align:left; width:10%' colspan='9'><b style='color: black;'>".strtoupper($Item_Name)."</b></td></tr>";
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
                                    tbl_items it
                                     WHERE il.Item_ID=it.Item_ID
                                        AND il.Payment_Cache_ID=pc.Payment_Cache_ID
                                        AND pc.Registration_ID=pr.Registration_ID
                                        AND il.Service_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                        AND il.Sub_Department_ID='$Sub_Department_ID'
                                        AND il.Item_ID='$Item_ID' GROUP BY il.Payment_Cache_ID";
	$select_data_result=mysqli_query($conn,$select_data);
	$total_rows=mysqli_num_rows($select_data_result);
	 $total_Male=0;
    	 $total_Female=0;
	 $total_Male_Female=0;
	
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
		
		echo "<tr><td>".($count+1)."</td>";
		echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
		echo "<td style='text-align:left; width:10%'>".$row['Registration_ID']."</td>";
		echo "<td style='text-align:left; width:10%'>".$Region."</td>";
		echo "<td style='text-align:left; width:10%'>".$District."</td>";
		echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
		echo "<td style='text-align:left; width:15%'>".$age."</td>";
		echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
                echo "<td style='text-align:left; width:10%'>".$row['Consultant']."</td>
                </tr>";
			
		
	}//end loop to select data
	echo "<tr><td colspan='9' style='text-align:center;'><b style='font-size:12px;font-family:verdana;color:black;'><i>Total Patients &nbsp;</i></b><b>".$total_rows."</b></td></tr>";
    }//end loop to select all sponsors
    echo "</table></center>";
	    ?>