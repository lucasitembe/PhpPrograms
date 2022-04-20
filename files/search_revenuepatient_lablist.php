<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    } 
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
    <td><b>STATUS</b></td>
    <td><b>PATIENT NAME</b></td>
		<td><b>Reg No</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>Age</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                
                                <td>&nbsp;</td></tr>';


                                    $select_query=  "SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,
                                                                                pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pr.Member_Number as Member_Number,
                                                                                il.Process_Status as Status,pp.Receipt_Date as Required_Date
                                                                                FROM tbl_patient_payment_item_list as il
                                                                                join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                                                                join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                                                                WHERE Check_In_Type ='Laboratory' and pr.Patient_Name like '%$Patient_Name%' and ";

                                                        if(isset($_GET['from']))
                                                                    if($_GET['from'] =='result'){

                                                                            $select_query .=" il.Process_Status ='Sample Collected' ";

                                                                    }else if($_GET['from'] =='specimenCollection'){

                                                                             $select_query .=" il.Process_Status <> 'Despensed' "; 
                                                                    }

                                                                             $select_query .= "GROUP BY pp.Receipt_Date order by Status";

                                            $select_Filtered_Patients = mysqli_query($conn,$select_query)or die(mysqli_error($conn));
                                            
                                                                                                                 
    
                                                                                //date manipulation to get the patient age
                                                                                $Today_Date = mysqli_query($conn,"select now() as today");
                                                                                    while($row = mysqli_fetch_array($Today_Date)){
                                                                                        $original_Date = $row['today'];
                                                                                        $new_Date = date("Y-m-d", strtotime($original_Date));
                                                                                        $Today = $new_Date;
                                                                                        $age ='';
                                                                                    }

            
                                                                        while($row = mysqli_fetch_array($select_Filtered_Patients)){


                                                                                $Date_Of_Birth = $row['Date_Of_Birth'];
                                                                                $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
                                                                                // if($age == 0){

                                                                                  $date1 = new DateTime($Today);
                                                                                  $date2 = new DateTime($Date_Of_Birth);
                                                                                  $diff = $date1 -> diff($date2);
                                                                                  $age = $diff->y." Years, ";
                                                                                  $age .= $diff->m." Months, ";
                                                                                  $age .= $diff->d." Days";

                      if(isset($_GET['from']))
                                        if($_GET['from'] =='result'){

        echo "<tr><td id='thead'>".$temp."</td>
        <td></td>
        <td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		   echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";
		
        echo "<td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
		
        echo "<td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
		
        echo "<td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		
        echo "<td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
		
        echo "<td><a href='laboratory_main.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		
       

                                          }else if($_GET['from'] =='specimenCollection'){   
                                                                                    if($row['Status'] =='Sample Collected'){
                                                                                                   echo "<tr bgcolor='lightgreen'>
                                                                                                   <td id='thead'>".$temp."</td>
                                                                                                   <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>Done</a></td>
                                                                                                   <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>"; 

 echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";																								   
                                                                                                }else{
                                                                                                echo "<tr><td id='thead'>".$temp."</td>
                                                                                                <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>Not Taken</a></td>
                                                                                                <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
																						   echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";																							
                                                                                                }
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
                                                                                             
                                                                                                echo "<td style='font-size:16px;color:red;text-align:center;'>X</td>";
                                                                                                    
                                                                        } 
                                                                          $temp++; 
                                                                        } 
                                                                         echo "</tr>";
                                                                    ?>
                                </table>
                            </center>