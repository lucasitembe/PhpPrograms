<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
// header("Content-type: text/xml");
$select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
$row5 = mysqli_fetch_array($select_now);


$select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,ppr.Item_ID,ppr.Patient_Payment_ID
                                      FROM  tbl_patient_payment_results ppr
                                      JOIN tbl_patient_registration pr ON ppr.Patient_ID = pr.Registration_ID
                                      JOIN tbl_patient_payments pp ON ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                      JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                      WHERE ppr.Patient_ID = pr.Registration_ID
                                      AND ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                      AND pr.Sponsor_ID = sp.Sponsor_ID

                                       UNION ALL
                                       SELECT 'cache' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,pcr.Item_ID,pcr.Payment_Cache_ID
                                      FROM  tbl_patient_cache_results pcr
                                      JOIN tbl_patient_registration pr ON pcr.Patient_ID = pr.Registration_ID
                                      JOIN tbl_payment_cache pc ON pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                      JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                      WHERE pcr.Patient_ID = pr.Registration_ID
                                      AND pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                      AND pr.Sponsor_ID = sp.Sponsor_ID  "



) or die(mysqli_error($conn));

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
	<td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td width='15%'><b>AGE</b></td>
                        <td width=5%><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                </tr>";

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
    $htm.="<td id='thead'>".$temp."<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
	
	$htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
	
    $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
    $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
    $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    $htm.="<td><a href='laboratory_main_privew.php?Status_From=".$row['Status_From']."&item_id=".$row['Item_ID']."&patient_id=".$row['Registration_ID']."&payment_id=".$row['Patient_Payment_ID']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    
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
