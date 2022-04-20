<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>

<center>
            <?php
		echo '<center><table width =100% border=0>';
		echo "<tr id='thead'>
			    <td width=3% style='text-align:left'><b>SN</b></td>
                            <td style='text-align:left' width=30%><b>PATIENT ID</b></td>
			    <td style='text-align:left' width=33%><b>PATIENT NAME</b></td>
			    <td style='text-align: left;' width=33%><b>CHECK IN DATE AND TIME</b></td>
		     </tr>";
		    echo "<tr>
				<td colspan=4></td></tr>";
                            $employeeID=mysqli_real_escape_string($conn,$_GET['Employee_ID']);
			    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
			    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
			    $branchID=mysqli_real_escape_string($conn,$_GET['branchID']);
                            
                            if($branchID == "All"){//no branch is selected
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
                                    //return data
                                    $Registration_ID=$select_patient_list_row['Registration_ID'];
                                    $employeeID=$select_patient_list_row['Employee_ID'];
                                    $Patient_Name=$select_patient_list_row['Patient_Name'];
                                    $Check_In_Date_And_Time=$select_patient_list_row['Check_In_Date_And_Time'];
                                    
                                    //display data
                                    echo "<tr><td>".($PatientSN)."</td>";
                                    echo "<td>".$Registration_ID."</td>";
                                    echo "<td>".$Patient_Name."</td>";
                                    echo "<td>".$Check_In_Date_And_Time."</td>";
                                    echo "</tr>";
                                    $PatientSN++;
                                }
                                
                            }
			    }else{
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
								 AND ci.Branch_ID='$branchID'
                                                                 AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                                 ");
                                //loop to find details
                                $PatientSN=1;
                                while($select_patient_list_row=mysqli_fetch_array($select_patient_list)){
                                    //return data
                                    $Registration_ID=$select_patient_list_row['Registration_ID'];
                                    $employeeID=$select_patient_list_row['Employee_ID'];
                                    $Patient_Name=$select_patient_list_row['Patient_Name'];
                                    $Check_In_Date_And_Time=$select_patient_list_row['Check_In_Date_And_Time'];
                                    
                                    //display data
                                    echo "<tr><td>".($PatientSN)."</td>";
                                    echo "<td>".$Registration_ID."</td>";
                                    echo "<td>".$Patient_Name."</td>";
                                    echo "<td>".$Check_In_Date_And_Time."</td>";
                                    echo "</tr>";
                                    $PatientSN++;
                                }
                                
                            }
			    }
		   
			    ?>
			    
			    </table>
			</center>