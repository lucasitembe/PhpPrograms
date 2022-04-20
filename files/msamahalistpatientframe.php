<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name= $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
//    
 //today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
////end

echo '<center><table id="" width =100% border=0>';
echo '<thead><tr id="thead"><td style="width:5%;"><b>SN</b></td>
   <th><b>Date</b></th>
   <th><b>Jina la mgojwa</b></th>
   <th><b>Umri</b></th>
   <th><b>Jinsia</b></th> 
   <th><b>Nambari ya simu</b></th>
   <th><b>Aina ya Msamaha</b></th>
   <th><b>Jina la mtu anayependekeza Msamaha</b></th>
   <th><b>Jina la Balozi</b></th>
   <th><b>Region</b></th>
   <th><b>District</b></th>
   <th><b>Ward</b></th>
   <th><b>kiwango cha elimu</b></th>		
   <th><b>Kazi ya mke/mlezi</b></th>
   <th><b>Prepared By</b></th>
   </tr></thead>';
    
if(isset($_GET['action'])){
if($_GET['action']=='Datefilter'){
$Date_From=$_GET['Date_From'];
$Date_To=$_GET['Date_To'];
$msamaha_aina=$_GET['msamaha_aina'];
$employee_ID=$_GET['employee_ID'];
$gender=$_GET['jinsi'];
if($gender==''){
   $gender_query=''; 
}  else {
  $gender_query="AND Gender='$gender'";  
}

if($employee_ID==''){
    $employee_ID_Query='';
}  else {
    $employee_ID_Query="AND anayependekeza='$employee_ID'";
}

 if($msamaha_aina=='' || $msamaha_aina=='NULL'){
 $select_msamaha ="SELECT
 msamaha_ID,
 Attendance_Date,
 Patient_Name,
 Date_Of_Birth,
 Gender,
 Occupation,
 Phone_Number,
 aina_ya_msamaha,
 anayependekeza,
 jina_la_balozi, 
 Region, 
 District, 
 Ward,
 kiwango_cha_elimu,
 kazi_mke, 
 idadi_mahudhurio,
 anayependekeza,
 Employee_Name,
 te.Employee_ID
 FROM tbl_msamaha tm,tbl_patient_registration tpr,tbl_employee te
 WHERE tm.Attendance_Date BETWEEN '$Date_From' AND '$Date_To' $gender_query $employee_ID_Query AND tm.Registration_ID=tpr.Registration_ID AND te.Employee_ID=anayependekeza AND Patient_Name LIKE '%$Patient_Name%' LIMIT 500";
                     
    }else{
$select_msamaha ="SELECT
 msamaha_ID,
 Attendance_Date,
 Patient_Name,
 Date_Of_Birth,
 Gender,
 Occupation,
 Phone_Number,
 aina_ya_msamaha,
 anayependekeza,
 jina_la_balozi, 
 Region, 
 District, 
 Ward,
 kiwango_cha_elimu,
 kazi_mke, 
 idadi_mahudhurio,
 anayependekeza,
 Employee_Name,
 te.Employee_ID
 FROM tbl_msamaha tm,tbl_patient_registration tpr,tbl_employee te
 WHERE tm.Attendance_Date BETWEEN '$Date_From' AND '$Date_To' $gender_query $employee_ID_Query AND tm.Registration_ID=tpr.Registration_ID AND te.Employee_ID=anayependekeza AND aina_ya_msamaha='$msamaha_aina' AND Patient_Name LIKE '%$Patient_Name%' LIMIT 500";
                    
                            
                            
  }
  } 
  }  else {
$Today=date('Y-m-d');
$select_msamaha ="SELECT
 msamaha_ID,
 Attendance_Date,
 Patient_Name,
 Date_Of_Birth,
 Gender,
 Occupation,
 Phone_Number,
 aina_ya_msamaha,
 anayependekeza,
 jina_la_balozi, 
 Region, 
 District, 
 Ward,
 kiwango_cha_elimu,
 kazi_mke, 
 idadi_mahudhurio,
 anayependekeza,
 Employee_Name,
 te.Employee_ID
 FROM tbl_msamaha tm,tbl_patient_registration tpr,tbl_employee te
 WHERE tm.Attendance_Date='$Today' AND tm.Registration_ID=tpr.Registration_ID AND te.Employee_ID=anayependekeza AND Patient_Name LIKE '%$Patient_Name%' LIMIT 500";
                  
}
    	 
//echo $select_msamaha;
     $msamaha=  mysqli_query($conn,$select_msamaha);
     while($row = mysqli_fetch_array($msamaha)){
        //AGE FUNCTION
        $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        echo "<tr><td style='text-align: center;' id='thead'>".$temp."</td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Attendance_Date']."</a></td>";	 
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";	 
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['aina_ya_msamaha']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['jina_la_balozi']."</a></td>";   
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Region']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['District']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Ward']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['kiwango_cha_elimu']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['kazi_mke']."</a></td>";
        echo "<td><a href='#.php?msamaha_ID=".$row['msamaha_ID']."&EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
	$temp++;
    }   echo "</tr>";
?>
</table>
</center>