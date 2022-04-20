<link rel="stylesheet" href="table.css" media="screen">
<?php
// header("Content-type: text/xml");

    include("./includes/connection.php");

    //get dates
    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
    
    
    
 $select_Filtered_Patients = mysqli_query($conn,
                                "SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pp.Payment_Date_And_Time,pr.Gender as Gender,
                                        pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,i.Product_Name,
                                        il.Process_Status as Status,'Revenue Center' as Doctors_Name,il.Item_ID as Item_ID,il.Patient_Payment_Item_List_ID as Patient_Payment_Item_List_ID
                                        FROM tbl_patient_payment_item_list as il
                                        join tbl_items as i ON i.Item_ID = il.Item_ID
                                        join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                        join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                         WHERE Check_In_Type ='Laboratory'
                                            and il.Process_Status !='Despensed'
                                                     and il.Process_Status ='Sample Collected'
                                                     AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                             GROUP BY pp.Receipt_Date
                                        union all

                                    SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pc.Payment_Date_And_Time,pr.Gender as Gender,
                                       pc.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,i.Product_Name,
                                       il.Process_Status as Status,il.Consultant as Doctors_Name,i.Item_ID as Item_ID,il.Payment_Item_Cache_List_ID as Patient_Payment_Item_List_ID
                                          FROM tbl_item_list_cache as il
                                          JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                          JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                          JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                              WHERE Check_In_Type ='Laboratory' 
                                                        and il.Process_Status !='Despensed'
                                                        and il.Process_Status ='Sample Collected'
                                                        AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                          GROUP BY il.Payment_Cache_ID 
                                          ") or die(mysqli_error($conn));
              


    
            //date manipulation to get the patient age
            $Today_Date = mysqli_query($conn,"select now() as today");
                while($row = mysqli_fetch_array($Today_Date)){
                    $original_Date = $row['today'];
                    $new_Date = date("Y-m-d", strtotime($original_Date));
                    $Today = $new_Date;
                    $age ='';
                }

// $dom = new DOMDocument();

// $data =$dom->createElement('data');
// $dom ->appendChild($data);
// $Results = $dom->createElement('Results');

 $htm="<center><table width =100% border=0>";
  $htm .="<tr id='thead'><td style='width:5%;'><b>SN</b></td>
    
    <td><b>PATIENT NAME</b></td>
		<td><b>REG NO</b></td>
                <td><b>SPONSOR</b></td>
                    <td width=15%><b>Age</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                </tr>";

 $temp = 1;
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
              if(strtolower($Product_Name) == 'registration and consultation fee'){
              }else{
                    $htm.="<tr>";
                    $htm.="<td id='thead'>".$temp."<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
					
 $htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";

$htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
			
$htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
					
                    $htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                    $htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
                    $htm.="<td><a href='laboratory_main.php?Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
					
                   
                    $htm.="</tr>";
                     $temp++;
              }
      
         
    }

    $htm .="</table></center>";

//   $ItemInfo = $dom->createElement('ItemInfo');
//   $ItemInfoText =$dom ->createTextNode($htm);
//   $ItemInfo ->appendChild($ItemInfoText);



//   $Results->appendChild($ItemInfo);


// $data->appendChild($Results);

// $xmlString =$dom->saveXML();
// echo $xmlString;
    echo $htm;

?>
