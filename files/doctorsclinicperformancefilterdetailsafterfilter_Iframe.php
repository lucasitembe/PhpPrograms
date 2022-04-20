<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
		echo "<tr ID='thead'>
			    <td width=3% style='text-align:left'><b>SN</b></td>
			    <td style='text-align:left' width=15%><b>PATIENT NAME</b></td>
			    <td style='text-align: left;' width=15%><b>CHECK IN DATE AND TIME</b></td>
                            <td style='text-align: left;' width=15%><b>DOCTOR'S SAVED DATE AND TIME</b></td>
                            <td style='text-align: left;' width=15%><b>SIGN OFF DATE AND TIME</b></td>
			    <td style='text-align: left;' width=15%><b>RECEIPT NUMBER</b></td>
			    <td style='text-align: left;' width=20%><b>AMOUNT</b></td>
		     </tr>";
		    echo "<tr>
				<td colspan=4></td></tr>";
                            $employeeID=mysqli_real_escape_string($conn,$_GET['Employee_ID']);
			    $Date_From=$_GET['Date_From'];
			    $Date_To=$_GET['Date_To'];
                            //run the query to select all data from the database according to the branch id
                            $select_doctor_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' AND emp.Employee_ID='$employeeID'");
                            
                            while($select_doctor_query_row=mysqli_fetch_array($select_doctor_query)){//return employee details
                                //return data
                                $employeeID=$select_doctor_query_row['Employee_ID'];
                                $Employee_Name=$select_doctor_query_row['Employee_Name'];
                                
                                $select_patient_item_list=mysqli_query($conn,"SELECT * FROM tbl_consultation co,tbl_clinic c,tbl_clinic_employee ce,tbl_patient_payment_item_list ppl 
									WHERE ppl.Patient_Payment_Item_List_ID =co.Patient_Payment_Item_List_ID
									AND ppl.Consultant_ID=ce.Clinic_ID
									AND ce.Employee_ID=co.Employee_ID
									AND co.Employee_ID='$employeeID'
									AND ce.Clinic_ID=c.Clinic_ID
									AND ppl.Process_Status='signedoff' 
									AND ppl.Patient_Direction='Direct To Clinic' 
									AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                                      ");
                                
                                $patientSN=1;
                                while($select_patient_item_list_row=mysqli_fetch_array($select_patient_item_list)){//select patient item list
                                    //return data
                                     $Patient_Payment_ID=$select_patient_item_list_row['Patient_Payment_ID']."<br>";
                                     $Signoff_Date_And_Time=$select_patient_item_list_row['Signedoff_Date_And_Time'];
				     $Price=$select_patient_item_list_row['Price'];
				     $Quantity=$select_patient_item_list_row['Quantity'];
				     $Item_ID=$select_patient_item_list_row['Item_ID'];
				     
				     
				     //RUN the query to select item name
				     $select_item_name=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'");
				     $Product_Name=mysqli_fetch_array($select_item_name)['Product_Name'];
				     
				     
				     $Amount = $Price * $Quantity;
                                    
                                        $select_patient_payment=mysqli_query($conn,"SELECT * FROM tbl_patient_payments pp WHERE pp.Patient_Payment_ID='$Patient_Payment_ID'");
                                        
                                        
                                        while($select_patient_payment_row=mysqli_fetch_array($select_patient_payment)){
                                            //return data
                                            $Registration_ID=$select_patient_payment_row['Registration_ID'];
                                            
                                            $select_patient=mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID' ");
                                            
                                            
                                            while($select_patient_row=mysqli_fetch_array($select_patient)){//select patient details
                                                $RegID=$select_patient_row['Registration_ID'];
                                                $Patient_Name=$select_patient_row['Patient_Name']."<br>";
                                                
                                                
                                                $select_check_in_info=mysqli_query($conn, "SELECT * FROM tbl_check_in ci,tbl_consultation co,tbl_patient_registration pr
                                                                                    WHERE ci.Registration_ID=co.Registration_ID
                                                                                    AND pr.Registration_ID=co.Registration_ID
                                                                                    AND ci.Registration_ID='$RegID' ");
                                                while($select_check_in_info_row=mysqli_fetch_array($select_check_in_info)){//select check in info
                                                    //return data
                                                    $Check_In_Date_And_Time=$select_check_in_info_row['Check_In_Date_And_Time'];
                                                    $Consultation_Date_And_Time=$select_check_in_info_row['Consultation_Date_And_Time'];
                                                }
                                                
                                                echo "<tr><td>".($patientSN)."</td>";
                                                echo "<td>".$Patient_Name."</td>";
                                                echo "<td>".$Check_In_Date_And_Time."</td>";
                                                echo "<td>".$Consultation_Date_And_Time."</td>";
                                                echo "<td>".$Signoff_Date_And_Time."</td>";
						echo "<td>".$Patient_Payment_ID."</td>";
						//echo "<td>".$Product_Name."</td>";
						echo "<td>".number_format($Amount)."</td>";
                                                echo "</tr>";
                                                
                                            }
                                            $patientSN =$patientSN + 1;
                                        }
                                }  
                            }
		   
			    ?>
			    
			    </table>
			</center>