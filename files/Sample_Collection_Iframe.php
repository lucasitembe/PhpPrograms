<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
<link rel='stylesheet' href='fixHeader.css'>


<?php
include("./includes/connection.php");
$Sponsor='';
$filter = '';
if (isset($_POST['action'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $Sponsor = $_POST['sponsorID'];
}

if ($Sponsor != 'All') {
    $filter =" AND tbl_sponsor.Sponsor_ID='$Sponsor'";
}

echo '<center><table width =100% border=0 id="specimencollected" class="display fixTableHead">';
echo "
    <thead>
        <tr style='background-color: #ccc;'>
            <th>SN</th>
            <th width=17%>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</td>
            <th style='text-align: left;' width=5%>PATIENT NUMBER</th>
            <th style='text-align: left;' width=8%>SPONSOR</th>
            <th style='text-align: left;' width=10%>REGION</th>
            <th style='text-align: left;' width=10%>DISTRICT</th>
            <th style='text-align: left;' width=8%>GENDER</th>
            <th style='text-align: left;' width=15%>AGE</th>
            <!-- <th style='text-align: left;' width=13%>SPECIMEN COLLECTED</th>-->
            <th style='text-align: left;' width=11%>TIME CONSULTED</th>
            <th style='text-align: left;' width=15%>DIRECTED BY</th>
        </tr>
    </thead>";
$count = 0;
if (isset($_POST['action'])) {
    $select_data = "SELECT * FROM tbl_specimen_results INNER JOIN tbl_item_list_cache ON payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN tbl_sponsor ON tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID JOIN tbl_laboratory_specimen ON tbl_specimen_results.Specimen_ID=tbl_laboratory_specimen.Specimen_ID WHERE TimeCollected BETWEEN '$fromDate' AND '$toDate' $filter GROUP BY tbl_patient_registration.Registration_ID";
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

        echo "<tr><td>" . ($count + 1) . "</td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $patientName . "</span></td>";
        echo "<td style='text-align:left'><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $row['Registration_ID'] . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Guarantor_Name . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Region . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $District . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Gender . "</span></td>";
        echo "<td style='text-align:left'><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $age . "</span></td>";
        // echo "<td style='text-align:left; width:15%'>".$row['Specimen_Name']."</td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $TimeCollected . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $row['Consultant'] . "</span></td>
                </tr>";
        $count++;
    }
}
echo "</table></center>";
?>


