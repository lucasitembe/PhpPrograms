<?php

include("./includes/connection.php");
session_start();
$Sponsor='';
$filter = '';
   

if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $Sponsor = $_GET['sponsorID'];
}
$Guarantor_Name="All";
if ($Sponsor != 'All') {
    $filter =" AND tbl_sponsor.Sponsor_ID='$Sponsor'";
    
    $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'"))['Guarantor_Name'] ;
}
?>

<?php

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>LABORATORY PATIENTS REPORT <br/>FROM</b> <b style=''>" . $fromDate . "</b> <b>TO</b> <b style=''>" . $toDate . "</b><br/> "
        . "<span><b>SPONSOR $Guarantor_Name</b></span>"
        . "</p>";

$htm.= '<center><table width ="100%" border="0" id="specimencollected" class="display">';
$htm.= "<thead>
                <tr>
			    <th>SN</th>
			    <th width='25%'> NAME</th>
			    <th style='text-align: left;' width='5%'>PATIENT #</th>
                            <th style='text-align: left;' width='8%'>SPONSOR</th>
			    <th style='text-align: left;' width='10%'>REGION</th>
			    <th style='text-align: left;' width='10%'>DISTRICT</th>
			    <th style='text-align: left;' width='8%'>GENDER</th>
			    <th style='text-align: left;' width='15%'>AGE</th>
                           <!-- <th style='text-align: left;' width='13%'>SPECIMEN COLL.</th>-->
			    <th style='text-align: left;' width='11%'>TIME CONS.</th>
                            <th style='text-align: left;' width='15%'>BY</th>
                </tr>
            </thead>";
$count = 0;
    $select_data = "SELECT TimeCollected, Guarantor_Name, tbl_patient_registration.Date_Of_Birth, Gender, tbl_patient_registration.District, tbl_patient_registration.Region, tbl_patient_registration.Registration_ID, Patient_Name FROM tbl_specimen_results INNER JOIN tbl_item_list_cache ON payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN tbl_sponsor ON tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID JOIN tbl_laboratory_specimen ON tbl_specimen_results.Specimen_ID=tbl_laboratory_specimen.Specimen_ID WHERE TimeCollected BETWEEN '$fromDate' AND '$toDate' $filter GROUP BY tbl_patient_registration.Registration_ID";
     //die($select_data);
    $select_data_result = mysqli_query($conn,$select_data);
    while ($row = mysqli_fetch_array($select_data_result)) {
        $registration_ID = $row['Registration_ID'];
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Region = $row['Region'];
        $District = $row['District'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $TimeCollected= $row['TimeCollected'];
        $Guarantor_Name= $row['Guarantor_Name'];
        $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        $htm.= "<tr><td>" . ($count + 1) . "</td>";
        $htm.= "<td style='text-align:left '>" . $patientName . "</td>";
        $htm.= "<td style='text-align:left'>" . $row['Registration_ID'] . "</td>";
        $htm.= "<td style='text-align:left '>" . $Guarantor_Name . "</td>";
        $htm.= "<td style='text-align:left '>" . $Region . "</td>";
        $htm.= "<td style='text-align:left '>" . $District . "</td>";
        $htm.= "<td style='text-align:left '>" . $Gender . "</td>";
        $htm.= "<td style='text-align:left'>" . $age . "</td>";
        // $htm.= "<td style='text-align:left; width:15%'>".$row['Specimen_Name']."</td>";
        $htm.= "<td style='text-align:left '>" . $TimeCollected . "</td>";
        $htm.= "<td style='text-align:left '>" . $row['Consultant'] . "</td>
                </tr>";
        $count++;
    }

$htm.= "</table></center>";


 include("MPDF/mpdf.php");
       // $mpdf=new mPDF(); 
        $mpdf=new mPDF('c','A4-L','','',32,25,27,25,16,13); 

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 


?>



