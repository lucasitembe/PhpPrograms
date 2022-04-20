<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;

                              $select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
                                    $row5 = mysqli_fetch_array($select_now);

                                          if(isset($_GET['Patient_Name'])){

                                                        $Patient_Name = $_GET['Patient_Name'];


                                                        //if($_GET['Date_To'] == ''){
                                                        //      $Date_To = date('Y-m-d',strtotime($row5['Date_From']));
                                                        //          }else{
                                                        //              $Date_To = $_GET['Date_To'];
                                                        //                 }

                                                        //if($_GET['Date_From'] == ''){
                                                        //      $Date_From = date('Y-m-d',strtotime($row5['Date_From']));
                                                        //          }else{
                                                        //              $Date_From = $_GET['Date_From'];
                                                        //              } 

                                              
                                          }else{

                                                 $Patient_Name = '';
                                                 //$DateGiven_From = date('Y-m-d',strtotime($row5['DateGiven']));
                                                 //$DateGiven_To = date('Y-m-d',strtotime($row5['DateGiven']));
                                          }

    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    echo '<center><table width =100% border=0><tr id="thead">';
    echo '<td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td width=13%><b>AGE</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>REG NUMBER</b></td>
                                <td width=6%><b>STATUS</b></td></tr>';
                                    $Receipt_Date = date('Y-m-d');
                                    //if(isset($_POST['Date_From'])){
                                    //    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
                                    //    $Date_From=date('Y-m-d',strtotime($Date_From));
                                    //    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
                                    //    $Date_To=date('Y-m-d',strtotime($Date_To));
                                    //}
                                    //
                                    //if(isset($_GET['Date_From'])){
                                    //    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
                                    //    $Date_From=date('Y-m-d',strtotime($Date_From));
                                    //    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
                                    //    $Date_To=date('Y-m-d',strtotime($Date_To));
                                    //}
                                    
                                        $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
                                        //$Date_From=date('Y-m-d',strtotime($Date_From));
                                        $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
                                        //$Date_To=date('Y-m-d',strtotime($Date_To));

                                    $select_Filtered_Patients = mysqli_query($conn, 
                                                                             "SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pp.Payment_Date_And_Time,pr.Gender as Gender,
                                                                                pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
                                                                                il.Process_Status as Status,il.Status,'Revenue Center' as Doctors_Name
                                                                                FROM tbl_patient_payment_item_list as il
                                                                                JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                                                                  join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                                                                      join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                                                                          and pp.Payment_Date_And_Time between  '$Date_From' and '$Date_To'
                                                                                              WHERE Check_In_Type ='Laboratory' 
                                                                                              and i.Consultation_Type ='Laboratory'
                                                                                                and pr.Patient_Name like '%$Patient_Name%'
                                                                                                and il.Status= 'paid'
                                                                                                and il.Process_Status='inactive'
                                                                                                            GROUP BY payment_id 

                                                                            union all
                                                                            SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pc.Payment_Date_And_Time,pr.Gender as Gender,
                                                                                   pc.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
                                                                                   il.Process_Status as Status,il.Status,il.Consultant as Doctors_Name
                                                                                      FROM tbl_item_list_cache as il
                                                                                        JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                                                                          JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                                                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                                                                                WHERE Check_In_Type ='Laboratory' 
                                                                                                and i.Consultation_Type ='Laboratory'
                                                                                                    and pc.Payment_Date_And_Time  between  '$Date_From' and '$Date_To'
                                                                                                         and pr.Patient_Name like '%$Patient_Name%' 
                                                                                                             and il.Status= 'paid'
                                                                                                              and il.Process_Status='inactive'
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
                                                                                $Product_Name=$row['Product_Name'];

                                                                                $Date_Of_Birth = $row['Date_Of_Birth'];
                                                                                $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
                                                                                // if($age == 0){

                                                                                  $date1 = new DateTime($Today);
                                                                                  $date2 = new DateTime($Date_Of_Birth);
                                                                                  $diff = $date1 -> diff($date2);
                                                                                  $age = $diff->y." Years, ";
                                                                                  $age .= $diff->m." Months, ";
                                                                                  $age .= $diff->d." Days";

                                                                                if(strtolower($row['Product_Name']) == "registration and consultation fee"){
                                                                                }else{
                                                                                    
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
                                                                                                                            <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".strtoupper($row['Status'])."</a></td>";
                                                                                                                           $temp++;
                                                                                              echo "</tr>";
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                                                                }
                                                                        }   
                                                                        
                                                                    ?>
                                </table>
                            </center>