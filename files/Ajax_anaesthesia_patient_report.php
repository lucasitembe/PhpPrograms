
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
    $filter = "  AND anasthesia_created_at BETWEEN '$fromDate' AND '$toDate' ";
   
}

if ($Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID='$Sponsor'";
}


echo '<center><table width =100% border=0 id="patient-sent-to-lab" class="display">';
echo "<thead>
                <tr>
                    <th>SN</th>
                    <th width=17%>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</td>
                    <th style='text-align: left;' width=5%>REG #:</th>
                    <th style='text-align: left; width:20%;' width=8%>SPONSOR</th>
                    <th style='text-align: left;' width=10%>REGION</th>
                    <th style='text-align: left;' width=10%>DISTRICT</th>
                    <th style='text-align: left;' width=8%>GENDER</th>
                    <th style='text-align: left;' width=15%>AGE</th>
                    <th style='text-align: left;' width=13%>DATE STARTED</th>
                <th style='text-align: left;' width=15%>STATUS</th>
                <th style='text-align: left;' width=11%> PRINT PDF</th>                           
                </tr>
            </thead>";
$count = 0;
if (isset($_POST['action'])) {
    $select_data = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Region,pr.District,Anaesthesia_status,anasthesia_record_id, pr.Gender,pr.Date_Of_Birth,pr.Phone_Number,sp.Guarantor_Name,anasthesia_employee_id , anasthesia_created_at  FROM    tbl_anasthesia_record_chart arc, tbl_sponsor sp,  tbl_patient_registration pr WHERE pr.Registration_ID=arc.Registration_ID AND sp.Sponsor_ID =pr.Sponsor_ID $filter GROUP BY pr.Registration_ID";
    $select_data_result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_data_result)) {
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Region = $row['Region'];
        $District = $row['District'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $Phone_Number= $row['Phone_Number'];
        $Guarantor_Name= $row['Guarantor_Name'];
        $sentDate= $row['anasthesia_created_at'];
        $anasthesia_record_id=$row['anasthesia_record_id'];
        $Anaesthesia_status = $row['Anaesthesia_status'];
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        echo "<tr><td>" . ($count + 1) . "</td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $patientName . "</span></td>";
        echo "<td style='text-align:left'><span  class='linkstyle' >" . $row['Registration_ID'] . "</span></td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $Guarantor_Name . "</span></td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $Region . "</span></td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $District . "</span></td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $Gender . "</span></td>";
        echo "<td style='text-align:left'><span  class='linkstyle' >" . $age . "</span></td>";
        // echo "<td style='text-align:left; width:15%'>".$row['Specimen_Name']."</td>";
        //echo "<td style='text-align:left '><span  class='linkstyle' >" . $Phone_Number . "</span></td>";
        echo "<td style='text-align:left '><span  class='linkstyle' >" . $sentDate . "</span></td>";
       echo "<td style='text-align:left '><span  class='linkstyle' >" . $Anaesthesia_status . "</span></td>
       <td><a class='art-button-green' href='anesthesia_record_preveiw.php?Registration_ID=$Registration_ID&anasthesia_created_at=$sentDate&anasthesia_record_id=$anasthesia_record_id' target='_blank' >Preview form </a>
                                        </td>
               </tr>";
        $count++;
    }
}
echo "</table></center>";
?>


