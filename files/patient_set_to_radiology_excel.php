<style>
/*    table,tr,td{
        border: 10px solid #ccc;
        padding:2px;
        width: 100%
    }
    
    html,body{
        // font-family: monospace,courier;
    }*/
</style>
<?php

include("./includes/connection.php");
session_start();
$Sponsor='';
$filter = '';
   

if (isset($_GET['date_From'])) {
    $date_From = $_GET['date_From'];
    $date_To = $_GET['date_To'];
    $Sponsor = $_GET['sponsorID'];
    $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$date_From' AND '$date_To' AND ilc.Check_In_Type='Laboratory' ";
   
}
$Guarantor_Name="All";
if ($Sponsor != 'All') {
     $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    
    $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'"))['Guarantor_Name'] ;
}
?>

<?php

$htm="<p align='center'><b>LABORATORY PATIENTS REPORT <br/>FROM</b> <b style=''>" . $date_From . "</b> <b>TO</b> <b style=''>" . $date_To . "</b><br/> "
        . "<span><b>SPONSOR $Guarantor_Name</b></span>"
        . "</p>";

$htm.= '<table>';
$htm.= "<thead>
                <tr>
			    <th width='3%'>SN</th>
			    <th style='text-align: left;' width='20%'>PATIENT NAME</td>
			    <th style='text-align: left;' width='18%'>PAT. #</th>
                <th style='text-align: left;' width='8%'>SPONSOR</th>
			    <th style='text-align: left;' width='10%'>REGION</th>
			    <th style='text-align: left;' width='10%'>DISTRICT</th>
			    <th style='text-align: left;' width='8%'>GENDER</th>
			    <th style='text-align: left;' width='15%'>AGE</th>
                            <th style='text-align: left;' width='10%'>PHONE</th>
                            <th style='text-align: left;' width='16%'>ITEMS</th>
			  
                </tr>
                <tr>
                  <td colspan='10'><hr width='100%'/></td>
                <tr/>
            </thead>";
$count = 0;

  $select_data = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,pr.Gender,
                           pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant
                    FROM tbl_item_list_cache ilc 
                    JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID 
                    JOIN tbl_sponsor sp ON sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
  //die($select_data);
    $select_data_result = mysqli_query($conn,$select_data);
    while ($row = mysqli_fetch_array($select_data_result)) {
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Region = $row['Region'];
        $District = $row['District'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $Phone_Number= $row['Phone_Number'];
        $Guarantor_Name= $row['Guarantor_Name'];
        $sentDate= $row['Transaction_Date_And_Time'];
        $Consultant=$row['Consultant'];
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
        
        //Get items
            $select_data_items = "SELECT i.Product_Name,isc.Item_Subcategory_Name,ilc.Doctor_Comment,ilc.Consultant_ID,ilc.Transaction_Date_And_Time "
         . "FROM tbl_item_list_cache ilc "
         . "INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID " 
         . "JOIN tbl_items i ON i.Item_ID=ilc.Item_ID "
         . "JOIN tbl_item_subcategory isc ON i.Item_Subcategory_ID=isc.Item_Subcategory_ID "
         . "JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID "
         . " WHERE ilc.Transaction_Date_And_Time BETWEEN '$date_From' AND '$date_To' AND ilc.Check_In_Type='Laboratory'  AND pr.Registration_ID='$Registration_ID' ORDER BY ilc.Transaction_Date_And_Time ASC";
 
  // die($select_data);
    $select_data_result_items = mysqli_query($conn,$select_data_items) or die(mysqli_error($conn));
    $labItems='';
    $numberOfItem=  mysqli_num_rows($select_data_result_items);
    $track=1;
    while ($row = mysqli_fetch_array($select_data_result_items)) {
        $Product_Name = $row['Product_Name'];
//        $Subcategory_Name = $row['Item_Subcategory_Name'];
//        $Doctor_Comment = $row['Doctor_Comment'];
//        $Consultant_ID = $row['Consultant_ID'];
//        $Transaction_Date_And_Time= $row['Transaction_Date_And_Time'];
        if($numberOfItem==1){
             $labItems =$track.". ".$Product_Name;
        }else {
            if($track<$numberOfItem){
                $labItems .=$track.". ".$Product_Name.'<br> ';
            }else{
                $labItems .=$track.". ".$Product_Name;
            }
        }
        
        $track++;
       
    }
        //End get Items

        $htm.= "<tr><td>" . ($count + 1) . "</td>";
        $htm.= "<td style='text-align:left '>" . $patientName . "</td>";
        $htm.= "<td style='text-align:left'>" . $Registration_ID . "</td>";
        $htm.= "<td style='text-align:left '>" . $Guarantor_Name . "</td>";
        $htm.= "<td style='text-align:left '>" . $Region . "</td>";
        $htm.= "<td style='text-align:left '>" . $District . "</td>";
        $htm.= "<td style='text-align:left '>" . $Gender . "</td>";
        $htm.= "<td style='text-align:left'>" . $age . "</td>";
        $htm.= "<td style='text-align:left'>" . $Phone_Number . "</td>";
        $htm.= "<td style='text-align:left'>" . $labItems . "</td>";
        //$htm.= "<td style='text-align:left '>" . $row['Consultant'] . "</td>
        $htm.= "</tr>";
        $htm.= "<tr>
                  <td colspan='10'><hr width='100%'/></td>
                <tr/>";
        $count++;
    }

$htm.= "</table>";


header("Content-Type:application/xls");
header("content-Disposition: attachement; filename=Patients_Sent_To_Laboratory_excell.xls");
echo $htm;

?>



