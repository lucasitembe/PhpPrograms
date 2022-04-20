<?php
    include("./includes/connection.php");
    $temp = 1;

                              $select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
                                    $row5 = mysqli_fetch_array($select_now);

                                          if(isset($_GET['Patient_Name']) and isset($_GET['DateGiven_From']) and isset($_GET['DateGiven_From'])){

                                                        $Patient_Name = $_GET['Patient_Name'];


                                                        if($_GET['DateGiven_To'] == ''){
                                                              $DateGiven_To = date('Y-m-d',strtotime($row5['DateGiven']));
                                                                  }else{
                                                                      $DateGiven_To = $_GET['DateGiven_To'];
                                                                         }

                                                        if($_GET['DateGiven_From'] == ''){
                                                              $DateGiven_From = date('Y-m-d',strtotime($row5['DateGiven']));
                                                                  }else{
                                                                      $DateGiven_From = $_GET['DateGiven_From'];
                                                                      } 

                                              
                                          }else{

                                                 $Patient_Name = '';
                                                 $DateGiven_From = date('Y-m-d',strtotime($row5['DateGiven']));
                                                 $DateGiven_To = date('Y-m-d',strtotime($row5['DateGiven']));
                                          }

    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    echo '<center><table width =100% border=1>';
    echo '<td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td width=13%><b>AGE</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>Phone No.</b></td>
                                <td><b>Reg No.</b></td>
                                <td width=6%><b>STATUS</b></td>
                                <td><b>ACTION</b></td></tr>';


                                    $select_Filtered_Patients = mysqli_query($conn, 
                                                                             "SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,
                                                                                pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,
                                                                                il.Process_Status as Status,'Revenue Center' as Doctors_Name
                                                                                FROM tbl_patient_payment_item_list as il
                                                                                JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                                                                  join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                                                                      join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                                                                          and pp.Receipt_Date between  '$DateGiven_From' and '$DateGiven_To'
                                                                                              WHERE Check_In_Type ='Laboratory' 
                                                                                              and i.Consultation_Type ='Laboratory'
                                                                                                and pr.Patient_Name like '%$Patient_Name%'
                                                                                                  and il.Process_Status !='Despensed' 
                                                                                                        and il.Process_Status !='Sample Collected'
                                                                                                        and il.Process_Status !='Result' 
                                                                                                        and il.Process_Status !='Result Submited'
                                                                                                            GROUP BY payment_id 

                                                                            union all
                                                                            SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pr.Gender as Gender,
                                                                                   pc.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,
                                                                                   il.Process_Status as Status,il.Consultant as Doctors_Name
                                                                                      FROM tbl_item_list_cache as il
                                                                                        JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                                                                          JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                                                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                                                                                WHERE Check_In_Type ='Laboratory' 
                                                                                                and i.Consultation_Type ='Laboratory'
                                                                                                    and pc.Receipt_Date  between  '$DateGiven_From' and '$DateGiven_To'
                                                                                                         and pr.Patient_Name like '%$Patient_Name%' 
                                                                                                             and il.Process_Status !='Despensed' 
                                                                                                                   and il.Process_Status !='Sample Collected'
                                                                                                                   and il.Process_Status !='Result'
                                                                                                                   and il.Process_Status !='Result Submited'
                                                                                                                      GROUP BY payment_id 
                                                                                  order by payment_id
                                                                                ")
                                                                                 or die(mysqli_error($conn));
                                                                                                 
    
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

                                              if($row['Status'] =='On Progress'){
                                                                       echo "<tr bgcolor='#FFA500'>
                                                                                <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";         
                                                                                  }else{
                                                                        echo "
                                                                                <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
                                                                                   }
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
                                                                                                echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>
                                                                                                       <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>Paid</a></td>
                                                                                                      <td style='text-align:center;'>X</td>";
                                                                                                      $temp++;
                                                                         echo "</tr>";
                                                                        }   
                                                                        
                                                                    ?>
                                </table>
                            </center>