<?php
    @session_start();
    include("./includes/connection.php");
   $Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';

if(isset($_GET['Sponsor'])) {
}
if(isset($_GET['Date_From'])&&isset($_GET['Date_To'])){
    $Sponsor=$_GET['Sponsor'];
    $Date_From =$_GET['Date_From'];
    $Date_To =$_GET['Date_To'];
    $Patient_Name =$_GET['Patient_Name'];
    $Sponsor =$_GET['Sponsor'];
    $Patient_Number =$_GET['Patient_Number'];
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($Patient_Name)) {
    $filter .=" AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .=" AND pr.Registration_ID = '$Patient_Number'";
}

    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    ?>
    <?php
    
    $getPatientsSQL = "SELECT * FROM tbl_dialysis_details dd, tbl_patient_registration pr, tbl_sponsor sp WHERE dd.Patient_reg = pr.Registration_ID AND Attendance_Date BETWEEN '$Date_From' AND '$Date_To'$filter AND "
        . " pr.Sponsor_ID = sp.Sponsor_ID LIMIT 100";

    echo '<center><table width ="100%" style="background-color:white;" id="patients-list">';
    echo "<thead>
        <tr>
       <td><b>SN</b></td>
       <td><b>PATIENT NAME</b></td>
       <td><b>REG NO</b></td>
               <td><b>SPONSOR</b></td>
                   <td><b>DATE OF BIRTH</b></td>
                   <td><b>AGE</b></td>
                       <td><b>GENDER</b></td>
                           <td><b>PHONE NUMBER</b></td>
                               </tr></thead>";
    
     $sn=1;
     $select_Filtered_Patients = mysqli_query($conn,$getPatientsSQL) or die(mysqli_error($conn));

    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";

        echo "<tr>"
        . "<td>".$sn++."</td> "
        . "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$age."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Gender']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_Dates.php?Registration_ID=".$row['Registration_ID']."'>".$row['Phone_Number']."</a></td>";
        echo "</tr>";
    }   
    
?>
</table>
</center>