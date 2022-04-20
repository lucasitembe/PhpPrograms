<!---<link rel="stylesheet" href="table.css" media="screen"> --->
<?php
include("./includes/connection.php");
session_start();
?>
<center>
<?php

//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

echo '<center><table width =100% id="performancedetails" class="display">';
echo "<thead><tr>
<th style='width:5%;'>SN</th>
<th >PATIENT NAME</th>
<th >PATIENT NO</th>
<th >GENDER</th>
<th >AGE</th>
<th >PHONE NO</th>
<th >CHECK IN DATE AND TIME</th>
</tr></thead>";
echo "<tr>
<td colspan=4></td></tr>";
		$employeeID=mysqli_real_escape_string($conn,$_GET['Employee_ID']);
$Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
$Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
		
		//run the query to select all data from the database according to the branch id
		$select_employee_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE emp.Employee_ID='$employeeID'");
		
		while($select_employee_query_row=mysqli_fetch_array($select_employee_query)){//return employee details
			//return data
			$employeeID=$select_employee_query_row['Employee_ID'];
			$Employee_Name=$select_employee_query_row['Employee_Name'];
			
			//run the query to select patients from the check in table
			$select_patient_list=mysqli_query($conn,"SELECT * FROM tbl_check_in ci,tbl_patient_registration pr,tbl_employee emp
											 WHERE ci.Registration_ID=pr.Registration_ID
											 AND ci.Employee_ID=emp.Employee_ID
											 AND ci.Employee_ID='$employeeID'
											 AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
											 ");
			//loop to find details
			$PatientSN=1;
			while($select_patient_list_row=mysqli_fetch_array($select_patient_list)){
			
			//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($select_patient_list_row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($select_patient_list_row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
			
			
				//return data
				$Registration_ID=$select_patient_list_row['Registration_ID'];
				$Gender=$select_patient_list_row['Gender'];
				$Phone_Number=$select_patient_list_row['Phone_Number'];
				$employeeID=$select_patient_list_row['Employee_ID'];
				$Patient_Name=$select_patient_list_row['Patient_Name'];
				$Check_In_Date_And_Time=$select_patient_list_row['Check_In_Date_And_Time'];
				
				//display data
				echo "<tr><td id='thead'>".($PatientSN)."</td>";
			   
				echo "<td>".ucwords(strtolower($Patient_Name))."</td>";
				 echo "<td>".$Registration_ID."</td>";
				 echo "<td>".$Gender."</td>";
				 echo "<td>".$age."</td>";
				 echo "<td>".$Phone_Number."</td>";
				echo "<td>".$Check_In_Date_And_Time."</td>";
				echo "</tr>";
				$PatientSN++;
			}
			
		}

?>

</table>
</center>

