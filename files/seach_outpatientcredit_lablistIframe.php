<link rel="stylesheet" href="table.css" media="screen">
<?php
if($_GET['Sub_Department_ID'] != ''){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID='';
}
if($_GET['Patient_Name'] != ''){
    $Patient_Name=$_GET['Patient_Name'];
}else{
    $Patient_Name='';
}
?>
<?php
    include("./includes/connection.php");
    $temp = 1;

                              $select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
                                    $row5 = mysqli_fetch_array($select_now);

                                          

    //Find the current date to filter check in list

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    echo '<center><table width =100% border=0><tr id="thead">';
    echo '<td style="width:5%">S/N</td>';
    echo '<td><b>PATIENT NAME</b></td>
			  <td><b>REG NO</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>BILLING TYPE</b></td>
                    <td width=13%><b>AGE</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>PHONE NUMBER</b></td>
                              
                                <td width=6%><b>STATUS</b></td></tr>';
                                    $Receipt_Date = date('Y-m-d');

                                    $select_Filtered_Patients = mysqli_query($conn,
                                                                             "SELECT 'payment' as Status_From,'Transaction_Type' AS Transaction_Type,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,
                                                                                pp.Sponsor_Name as Sponsor_Name,pp.Billing_Type,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
                                                                                il.Process_Status as Status,il.Status,il.Transaction_Date_And_Time,'Revenue Center' as Doctors_Name
                                                                                FROM tbl_patient_payment_item_list as il
                                                                                JOIN tbl_items as i ON i.Item_ID = il.Item_ID
                                                                                  join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                                                                      join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                                                                              WHERE Check_In_Type ='Laboratory'
                                                                                              and i.Consultation_Type ='Laboratory'
                                                                                              and il.Transaction_Date_And_Time LIKE '%$Receipt_Date%'
                                                                                                and pr.Patient_Name like '%$Patient_Name%'
                                                                                                        and il.Process_Status='inactive'
                                                                                                        AND il.Status='active' or il.Status='paid'
                                                                                                        AND pp.Billing_Type='Outpatient Credit'
                                                                                                            GROUP BY payment_id

                                                                            union all
                                                                            SELECT 'cache' as Status_From,il.Transaction_Type AS Transaction_Type,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pr.Gender as Gender,
                                                                                   pc.Sponsor_Name as Sponsor_Name,pc.Billing_Type,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,i.Product_Name as Product_Name,
                                                                                   il.Process_Status as Status,il.Status,il.Service_Date_And_Time as Service_Date_And_Time,il.Consultant as Doctors_Name
                                                                                      FROM tbl_item_list_cache as il
                                                                                        JOIN tbl_items as i ON i.Item_ID = il.Item_ID
                                                                                          JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                                                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                                                                                WHERE Check_In_Type ='Laboratory'
                                                                                                and i.Consultation_Type ='Laboratory'
                                                                                                 and il.Service_Date_And_Time LIKE '%$Receipt_Date%'
                                                                                                         and pr.Patient_Name like '%$Patient_Name%'
                                                                                                                   and il.Process_Status='inactive'
                                                                                                                   AND pc.Billing_Type='Outpatient Credit'
                                                                                                                      AND (il.Transaction_Type='credit' OR il.Transaction_Type='cash' AND il.Status='active' OR il.Status='paid')
                                                                                                                      and il.Sub_Department_ID='$Sub_Department_ID'
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
                                                                        $sn=1;
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
                                                                                                     <td id='thead'>".$sn."</td>
                                                                                                     <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;text-align: left'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
                                                                                                       }else{
                                                                                             echo " <td id='thead'>".$sn."</td>
                                                                                                     <td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;text-align: left'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
																									 
																									                                                                                                           echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>"; 
                                                                                                        }
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Billing_Type']."</a></td>";
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
                                                                                                                     echo "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
          
                                                                                                                            "<td><a href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>";?>

                                                                                    <?php
                                                                                        if(strtolower($row['Status'])=='paid' && strtolower($row['Transaction_Type'])=='cash'){
                                                                                            echo "PAID";
                                                                                        }elseif(strtolower($row['Status'])=='active' && strtolower($row['Transaction_Type'])=='cash'){
                                                                                            echo "NOT PAID";
                                                                                        }elseif(strtolower($row['Status'])=='active' && strtolower($row['Transaction_Type'])=='credit'){
                                                                                            echo "NOT BILLED";
                                                                                        }else{
                                                                                            echo "NOT PROCESSED";
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo "</a></td>
                                                                                                                           ";
                                                                                                                           $temp++;
                                                                                              echo "</tr>";
                                                                                    $sn++;


                                                                                }
                                                                        }

                                                                    ?>
                                </table>
                            </center>