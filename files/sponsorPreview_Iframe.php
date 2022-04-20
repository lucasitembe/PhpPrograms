<!--<link rel="stylesheet" href="table.css" media="screen"> -->
<?php

    include("./includes/connection.php");
    session_start();
?>

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style> 
<center>
            <?php
		echo '<center><table width =100% border=0 style="background-color:white;">';
    //run the query to select all data from the database according to the branch id
    $select_sponsor_query ="SELECT * FROM tbl_sponsor";
    $select_sponsor_result=mysqli_query($conn,$select_sponsor_query);
    
   $GRAND_TOTAL=0;
    while($sponsorRow=mysqli_fetch_array($select_sponsor_result)){//loop to select all sponsors
	$count=0;
	$sponsorID=$sponsorRow['Sponsor_ID'];
	$sponsorName=$sponsorRow['Guarantor_Name'];
	echo "<tr><td style='text-align:left; width:10%' colspan='8'><b style='color: blue;font-size:20px;'>".$sponsorName."</b></td></tr>";
	echo "<tr><td colspan='8'><hr></td></tr>"; 
	
	echo "<tr >
			    <td style='width:5%'><b>SN</b></td>
			    <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
			    <td style='text-align: left;' width=10%><b>PATIENT NO</b></td>
				<td style='text-align: left;' width=10%><b>GENDER</b></td>
			    <td style='text-align: left;width:20%;'><b>AGE</b></td>
			    <td style='text-align: left;' width=10%><b>REGION</b></td>
			    <td style='text-align: left;' width=10%><b>DISTRICT</b></td>
			    <td style='text-align: left;width:10%;'><b>REG DATE</b></td>
		     </tr>";
			echo "<tr><td colspan='8'><hr></td></tr>"; 
	
	//run the query to select all details for each returned sponsor
	$select_data="SELECT * FROM tbl_patient_registration pr WHERE pr.Sponsor_ID='$sponsorID'
	ORDER BY Patient_Name,pr.Registration_ID,Gender ASC";
	$select_data_result=mysqli_query($conn,$select_data);
	
	
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
		$Registration_Date=$row['Registration_Date'];
		
		//these codes are here to determine the age of the patient
		$date1 = new DateTime(date('Y-m-d'));
		$date2 = new DateTime($dob);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
		echo "<tr><td id='thead'>".($count+1)."</td>";
		echo "<td style='text-align:left;'>".ucwords(strtolower($patientName))."</td>";
		echo "<td style='text-align:left;'>".$Registration_ID."</td>";
		echo "<td style='text-align:left;'>".$Gender."</td>";
		echo "<td style='text-align:left;'>".$age."</td>";
		echo "<td style='text-align:left; '>".$Region."</td>";
		echo "<td style='text-align:left;'>".$District."</td>";
		echo "<td style='text-align:left;'>".$Registration_Date."</td></tr>";
		
		//run the query to select malle and female
		$query="SELECT sp.Sponsor_ID,sp.Guarantor_Name,
			(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr  
			WHERE pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male' AND pr.Sponsor_ID='$sponsorID'   
			) as male,
			(
			SELECT COUNT(Gender) FROM tbl_patient_registration pr  
			WHERE    pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female' AND pr.Sponsor_ID='$sponsorID'    
			) as female
			FROM tbl_sponsor sp WHERE Sponsor_ID='$sponsorID' ORDER BY sp.Sponsor_ID ASC
			";
		$select_demograph = mysqli_query($conn,$query);
		$total_Male=0;
		$total_Female=0;
		$res=mysqli_num_rows($select_demograph);
		for($i=0;$i<$res;$i++){
		$row=mysqli_fetch_array($select_demograph);
		//return rows
		$sponsorID=$row['Sponsor_ID'];
		$sponsorName=$row['Guarantor_Name'];
		$male=$row['male'];
		$female=$row['female'];
		$total_Male=$total_Male + $male;
		$total_Female=$total_Female + $female;
		$total_Male_Female=$total_Male+$total_Female;
		$count++;
		
		
	    }//end for loop	
		
	}//end loop to select data
	
	echo "<tr><td colspan='8' style='text-align:center;'><b style='font-size:12px;font-family:verdana;color:black;'><i>Total Patients &nbsp;</i></b><b>".$total_Male_Female."</b></td></tr>";
	echo "<tr><td><br></td></tr>";
	
	$GRAND_TOTAL=$GRAND_TOTAL+$total_Male_Female;
	//$GRAND_TOTAL=$total_Male_Female+$total_Male_Female;
    }//end loop to select all sponsors
	
	echo "<tr><td colspan='8'><hr></td></tr>"; 
    echo "</table></center>";
	echo "<p ><b><span style='color:blue;font-size:20px;'>  ALL REGISTERED PATIENTS/ CUSTOMERS: </span><span style='color:red;font-size:20px;'>" .$GRAND_TOTAL."</span></b></p>";
	echo "<tr><td colspan='8'><hr></td></tr>"; 
	echo "<tr><td><br></td></tr>";
	
	    ?>