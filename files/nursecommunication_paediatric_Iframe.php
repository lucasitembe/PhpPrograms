<?php
include("./includes/connection.php");
$temp = 1;
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
}
if (isset($_GET['end'])) {
    $end_date = $_GET['end'];
} if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

$filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND DATE(date_time)=DATE(NOW()) ORDER BY date_time DESC";

if (!empty($start_date) && !empty($end_date)) {
    $filter = " Registration_ID='$Registration_ID' AND consultation_ID='" . $consultation_ID . "' AND date_time BETWEEN '$start_date' AND '$end_date' ORDER BY date_time DESC";
}




echo '<center><table width =100% border="0" id="nurse_paediatric">';
echo '<thead>
                 <tr>
                    <td style="width:5%;"><b>S/N</b></td>
                    <td><b>DATE &amp; TIME</b></td>
                    <td><b>TEMP(c)</b></td>
                    <td><b>PR</b></td>
                    <td><b>RESP(bpm)</b></td>
                    <td><b>SO2</b></td>
                    <td><b>PATIENT PROBLEM</b></td>
                    <td><b>NURSING DIAGNOSIS</b></td>
                    <td><b>EXPECTED OUTCOME</b></td>
                    <td><b>NURSING IMPLEMENTATION</b></td>               
                    <td><b>OUTCOME</b></td>
                    <td><b>INVESTIGATION</b></td>
                    <td><b>REMARKS</b></td>
                 </tr>
                </thead>  
                ';

$Transaction_Items_Qry = "SELECT * FROM tbl_nursecommunication_paediatric WHERE $filter";


$select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    echo "<tr ><td id='thead'>" . $temp . ".</td>";
    echo "<td>" . $row['date_time'] . "</td>";
    echo "<td>" . $row['temp'] . "</td>";
    echo "<td>" . $row['Pr'] . "</td>";
    echo "<td>" . $row['Resp'] . "</td>";
    echo "<td>" . $row['So'] . "</td>";
    echo "<td>" . $row['patient_Problem'] . "</td>";
    echo "<td>" . $row['nursing_diagnosis'] . "</td>";
    echo "<td>" . $row['expected_outcome'] . "</td>";
    echo "<td>" . $row['implementation'] . "</td>";
    echo "<td>" . $row['outcome'] . "</td>";
    echo "<td>" . $row['investigation'] . "</td>";
    echo "<td>" . $row['Remarks'] . "</td>";
    echo "</tr>";

    $temp++;
}
?>
</table></center>
