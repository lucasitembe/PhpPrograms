<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
$temp = 1;
$select_now =mysqli_query($conn,"SELECT NOW() as DateGiven");
$row5 = mysqli_fetch_array($select_now);
//get sub department id
if($_GET['Sub_Department_ID'] !=''){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID='';
}
if($_GET['DateFrom'] != ''){
    $DateFrom=date('Y-m-d',strtotime(mysqli_real_escape_string($conn,$_GET['DateFrom'])));
    $Service_From=date('Y-m-d H:i:s',strtotime(mysqli_real_escape_string($conn,$_GET['DateFrom'])));
}else{
    $DateFrom='';
    $Service_From='';
}

if($_GET['DateTo'] != ''){
    $DateTo=date('Y-m-d',strtotime(mysqli_real_escape_string($conn,$_GET['DateTo'])));
    $Service_To=date('Y-m-d H:i:s',strtotime(mysqli_real_escape_string($conn,$_GET['DateTo'])));
}else{
    $DateTo='';
    $Service_To='';
}

if($_GET['Patient_Name'] != ''){
    $Patient_Name=mysqli_real_escape_string($conn,$_GET['Patient_Name']);
}else{
    $Patient_Name='';
}


//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
echo '<center><table width =100% border=0><tr id="thead">';
echo ' <td STYLE="width:3%"><b>SN</b></td>
	     <td><b>PATIENT NAME</b></td>
               <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                        <td><b>DIRECTED FROM</f></b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>REGISTRATION NUMBER</b></td>
                                <td><b>STATUS</b></td></tr>';
if($DateFrom !='' || $DateTo !=''){
    $select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,
                  pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,pp.Sponsor_Name as Sponsor_Name,
                  pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,
                  il.Process_Status as Status,'Revenue Center' as Doctors_Name FROM tbl_patient_payment_item_list as il
                  join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
                  join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
                 WHERE Check_In_Type ='Radiology' AND pp.Required_Date BETWEEN '$DateFrom' AND '$DateTo' AND pr.Patient_Name LIKE '%$Patient_Name%'

                union all

                SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,
                pc.Payment_Cache_ID as payment_id,pr.Gender as Gender, pc.Sponsor_Name as Sponsor_Name,
                pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,il.Service_Date_And_Time as Service_Date_And_Time,
                il.Process_Status as Status,il.Consultant as Doctors_Name FROM tbl_item_list_cache as il
                 JOIN tbl_items as i ON i.Item_ID = il.Item_ID
                 JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                 JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                 WHERE Check_In_Type ='Radiology' AND il.Service_Date_And_Time BETWEEN '$Service_From' AND '$Service_To' AND pr.Patient_Name LIKE '%$Patient_Name%'
                 AND il.Sub_Department_ID='$Sub_Department_ID' ")
    or die(mysqli_error($conn));
}else{
    $select_Filtered_Patients = mysqli_query($conn,"SELECT 'payment' as Status_From,pr.Patient_Name as Patient_Name,
              pr.Date_Of_Birth as Date_Of_Birth,pp.Patient_Payment_ID as payment_id,pr.Gender as Gender,pp.Sponsor_Name as Sponsor_Name,
              pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,pp.Receipt_Date as Required_Date,
              il.Process_Status as Status,'Revenue Center' as Doctors_Name FROM tbl_patient_payment_item_list as il
              join tbl_patient_payments as pp on pp.Patient_Payment_ID = il.Patient_Payment_ID
              join tbl_patient_registration as pr on pr.Registration_ID =pp.Registration_ID
              WHERE Check_In_Type ='Radiology' AND pr.Patient_Name LIKE '%$Patient_Name%'

            union all

            SELECT 'cache' as Status_From,pr.Patient_Name as Patient_Name,pr.Date_Of_Birth as Date_Of_Birth,
            pc.Payment_Cache_ID as payment_id,pr.Gender as Gender, pc.Sponsor_Name as Sponsor_Name,
            pr.Registration_ID as Registration_ID,pr.Phone_Number as Phone_Number,il.Service_Date_And_Time as Service_Date_And_Time,
            il.Process_Status as Status,il.Consultant as Doctors_Name FROM tbl_item_list_cache as il
             JOIN tbl_items as i ON i.Item_ID = il.Item_ID
             JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
             JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
             WHERE Check_In_Type ='Radiology' AND il.Sub_Department_ID='$Sub_Department_ID' AND pr.Patient_Name LIKE '%$Patient_Name%' ")
    or die(mysqli_error($conn));
}


//date manipulation to get the patient age
$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age ='';
}


while($row = mysqli_fetch_array($select_Filtered_Patients)){


    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
// if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";


    $Date_Of_Birth = $row['Date_Of_Birth'];
    echo "<tr><td style='text-align: center;'>".$temp."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']."'target='_parent' style='text-decoration:none;'>".$row['Patient_Name']."</a></td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']." ' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']." ' target='_parent' style='text-decoration: none;'>".$age."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']."'target='_parent' style='text-decoration: none;'>".$row['Gender']."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']." ' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']." 'target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']." 'target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</td>";

    echo "<td><a href='radiologyviewimage.php?Registration_ID=".$row['Registration_ID']."'target='_parent' style='text-decoration: none;'>".$row['Status_From']."</td>";

    $temp++;
    echo "</tr>";
}

?>
</table>
</center>