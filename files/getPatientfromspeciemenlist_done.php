<link rel="stylesheet" href="table.css" media="screen">
<?php
// header("Content-type: text/xml");
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
                                          
    include("./includes/connection.php");

 $select_Filtered_Patients = mysqli_query($conn,
                                "SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,
                                        pp.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,
                                        il.Process_Status as Status,'Revenue Center' as Doctors_Name,il.Item_ID as Item_ID
                                        FROM tbl_patient_payment_item_list as il
                                        join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                                        join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                                         WHERE Check_In_Type ='Laboratory' and pr.Patient_Name like '%$Patient_Name%'
                                          and pp.Receipt_Date between  '$DateGiven_From' and '$DateGiven_To'
                                            and il.Process_Status !='Despensed'
                                                     and il.Process_Status ='Result Submited'
                                                             GROUP BY pp.Receipt_Date DESC
                                        union all

                                    SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,pc.Payment_Cache_ID as payment_id,pr.Gender as Gender,
                                       pc.Sponsor_Name as Sponsor_Name,pr.Registration_ID as registration_number,pr.Phone_Number as Phone_Number,pc.Receipt_Date as Required_Date,
                                       il.Process_Status as Status,il.Consultant as Doctors_Name,i.Item_ID as Item_ID
                                          FROM tbl_item_list_cache as il
                                          JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                          JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                          JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                              WHERE Check_In_Type ='Laboratory' 
                                              and pc.Receipt_Date  between  '$DateGiven_From' and '$DateGiven_To'
                                                  and pr.Patient_Name like '%$Patient_Name%' 
                                                        and il.Process_Status !='Despensed'
                                                        and il.Process_Status ='Result Submited'
                                                          GROUP BY pc.Receipt_Date DESC
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
  $htm .="<tr id='thead'><td width = 3%><b>SN</b></td>
    
    <td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td width=13%><b>Age</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>Phone No.</b></td>
                                <td><b>Reg No.</b></td></tr>";

 $temp = 1;
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




        $htm.="<tr>";
        $htm.="<td>".$temp."<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";
      $htm.="</tr>";
       $temp++;   
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
