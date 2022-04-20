<?php
    include("./includes/connection.php");
    session_start();
?>

            <?php
            $html="<img src='branchBanner/branchBanner1.png'>";
            $html.="<fieldset>";
                    $html.="<legend><b>My Performance Report From ".date('j F,Y H:i:s',strtotime($_GET['Date_From']))." TO ".date('j F,Y H:i:s',strtotime($_GET['Date_To']))."</b></legend>";
                        $html.="<center>";
                             $html.= '<center><table width =100% border=0>';
                             $html.= "<tr id='thead'>
                                         <td style='text-align:left;WIDTH:3%'><b>SN</b></td>
                                         <td style='text-align:left' width=20%><b>PATIENT NAME</b></td>
                                         <td style='text-align: left;' width=20%><b>CHECK IN DATE AND TIME</b></td>
                                         <td style='text-align: left;' width=20%><b>DOCTOR'S SAVED DATE AND TIME</b></td>
                                         <td style='text-align: left;' width=20%><b>SIGN OFF DATE AND TIME</b></td>
                                         <td style='text-align: left;' width=15%><b>RECEIPT NUMBER</b></td>
                                         <td style='text-align: left;' width=20%><b>AMOUNT</b></td>
                                  </tr>";
                                 $html.= "<tr>
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
                                             
                                             $select_patient_item_list=mysqli_query($conn,"SELECT * FROM tbl_consultation co,tbl_patient_payment_item_list ppl
                                                                           WHERE co.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
                                                                           co.employee_ID=ppl.Consultant_ID AND
                                                                           co.Employee_ID='$employeeID' AND ppl.Process_Status='signedoff'
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
                                                             
                                                             $html.= "<tr><td>".($patientSN)."</td>";
                                                             $html.= "<td>".$Patient_Name."</td>";
                                                             $html.= "<td>".$Check_In_Date_And_Time."</td>";
                                                             $html.= "<td>".$Consultation_Date_And_Time."</td>";
                                                             $html.= "<td>".$Signoff_Date_And_Time."</td>";
                                                             $html.= "<td>".$Patient_Payment_ID."</td>";
                                                            //echo "<td>".$Product_Name."</td>";
                                                            $html.= "<td>".number_format($Amount)."</td>";
                                                            $html.= "</tr>";
                                                             
                                                         }
                                                         $patientSN =$patientSN + 1;
                                                     }
                                             }  
                                         }
                                
                                         
                                         
                                         $html.="</table>";
                                     $html.="</center>";
                        $html.="</fieldset>";
                        
                    $html.= "<table align='center' width='60%'>
                        <tr>
                            <td style='text-align:center;font-size: large;font-weight: bold'>Total Collection</td>
                            <td style='text-align:center;font-size: large;font-weight: bold'>50% Of Revenue</td>
                            <!--<td style='text-align:center;font-size: medium;font-weight: bold'>40% Of Revenue</td>
                            <td style='text-align:center;font-size: medium;font-weight: bold'>Remainder</td>-->
                        </tr>";
                            $employeeID=mysqli_real_escape_string($conn,$_GET['Employee_ID']);
			    $Date_From=$_GET['Date_From'];
			    $Date_To=$_GET['Date_To'];
                            //run the query to select all data from the database according to the branch id
                            $select_doctor_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' AND emp.Employee_ID='$employeeID'");
                            
                            $TotalRevenue=0;
			    
			    
			    $Receipt_Date=date('Y-m-d');
			    while($select_doctor_query_row=mysqli_fetch_array($select_doctor_query)){//return employee details
                                //return data
                                $employeeID=$select_doctor_query_row['Employee_ID'];
                                $Employee_Name=$select_doctor_query_row['Employee_Name'];
                                
                                $select_patient_item_list=mysqli_query($conn," SELECT SUM((ppl.Price - ppl.Discount) * Quantity) AS TotalRevenue FROM tbl_consultation co,tbl_patient_payment_item_list ppl,tbl_employee e,tbl_patient_payments pp 
									    WHERE ppl.Patient_Payment_Item_List_ID =co.Patient_Payment_Item_List_ID
									    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
									    AND ppl.Consultant_ID=e.Employee_ID
									    AND e.Employee_ID=co.Employee_ID
									    AND co.Employee_ID='$employeeID'
									    AND ppl.Process_Status='signedoff' 
									    AND ppl.Patient_Direction = 'Direct To Doctor'
									    AND pp.Receipt_Date <= '$Receipt_Date'
									    AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
								      ");
				
				$TotalRevenue=mysqli_fetch_array($select_patient_item_list)['TotalRevenue'];
                            }
			    //perform the calculations
			    //1. (12% Of the collection goes to the doctors) 
			    $Twelve_Percent=(12/100)*$TotalRevenue;
			    
			    //2. (40% Percent of the collection goes to hospital)
			    $Fourty_Percent=(40/100)*$TotalRevenue;
			    
			    //Some of 12% and 40%
			    $Sum_Twelve_Fourty = $Twelve_Percent + $Fourty_Percent;
			    
			    $Remainder=$TotalRevenue - $Sum_Twelve_Fourty;
			    
			    $Fifty_Percent=(50/100) * $TotalRevenue;
			    
		//display
		$html.= "<tr>
		    <td style='text-align:center;font-size: small;font-weight: bold'>".number_format($TotalRevenue)."</td>
		    <td style='text-align:center;font-size: small;font-weight: bold'>".number_format($Fifty_Percent)."</td>
		</tr>";
            $html.= "</table>";
            
            //print PDF
                   include("MPDF/mpdf.php");
                        $mpdf=new mPDF(); 
                        $mpdf->WriteHTML($html);
                        $mpdf->Output();
                        exit;   
                        
                        
                    ?>