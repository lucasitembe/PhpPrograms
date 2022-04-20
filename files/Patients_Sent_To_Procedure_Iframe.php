<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>


<?php
include("./includes/connection.php");
$Sponsor='';
$filter = '';
if (isset($_POST['action'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $Sponsor = $_POST['sponsorID'];
    $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Procedure' AND ilc.Status IN ('active','paid')";
   
}

if ($Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID='$Sponsor'";
}

echo '<center><table width =100% border=0 id="patient-sent-to-proc" class="display">';
echo "<thead>
                <tr>
			    <th>SN</th>
			    <th width=17%>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</td>
			    <th style='text-align: left;' width=5%>PATIENT NUMBER</th>
                            <th style='text-align: left;' width=8%>SPONSOR</th>
			    <th style='text-align: left;' width=10%>REGION</th>
			    <th style='text-align: left;' width=10%>DISTRICT</th>
			    <th style='text-align: left;' width=8%>GENDER</th>
			    <th style='text-align: left;' width=15%>AGE</th>
                            <th style='text-align: left;' width=13%>PHONE</th>
			   <!-- <th style='text-align: left;' width=11%>SENT DATE</th>
                            <th style='text-align: left;' width=15%>DIRECTED BY</th>-->
                </tr>
            </thead>";
$count = 0;
if (isset($_POST['action'])) {
    $select_data = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,pr.Gender,
                           pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,ilc.Transaction_Date_And_Time,ilc.Consultant
                    FROM tbl_item_list_cache ilc 
                    INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                    JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID 
                    JOIN tbl_sponsor sp ON sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
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

        echo "<tr><td>" . ($count + 1) . "</td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $patientName . "</span></td>";
        echo "<td style='text-align:left'><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $row['Registration_ID'] . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Guarantor_Name . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Region . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $District . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Gender . "</span></td>";
        echo "<td style='text-align:left'><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $age . "</span></td>";
        // echo "<td style='text-align:left; width:15%'>".$row['Specimen_Name']."</td>";
        //echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $Phone_Number . "</span></td>";
        echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $sentDate . "</span></td>";
       // echo "<td style='text-align:left '><span onclick='Show_Items_Taken(" . $Registration_ID . ",\"" . $patientName . "\",\"" . $fromDate . "\",\"" . $toDate . "\")' class='linkstyle' >" . $row['Consultant'] . "</span></td>
               echo " </tr>";
        $count++;
    }
}
echo "</table></center>";
?>


