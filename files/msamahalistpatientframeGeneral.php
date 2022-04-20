<!--<link rel="stylesheet" href="table.css" media="screen">-->
<?php
    include("./includes/connection.php");
    $temp = 1;
   
$Today = mysqli_query($conn,"select now() as today");


if(isset($_GET['action'])){
    $Date_From=$_GET['Date_From'];
     $Date_To=$_GET['Date_To'];
   $datebtn="Attendance_Date BETWEEN '$Date_From' AND '$Date_To'"; 
}  else {
    $datebtn="Attendance_Date='$Today'";
}

if(isset($_GET['employee_ID'])){
    $employee_ID=$_GET['employee_ID'];
    if($employee_ID==''){
      $employee_ID_Query='';    
    }else{
      $employee_ID_Query="AND anayependekeza='$employee_ID'";
    }
}  else {
  $employee_ID_Query='';  
}

echo '<center><table id="" width =100% border=1>';
echo '<thead><tr id="thead"><td style="width:5%;"><b>SN</b></td>
   <th style="text-align:left"><b>AINA YA MSAMAHA</b></th>
   <th style="text-align:center"><b>MALE</b></th>
   <th style="text-align:center"><b>FEMALE</b></th>
   <th style="text-align:center"><b>TOTAL</b></th> 
   </tr></thead>';
    
if(isset($_GET['action'])){
if($_GET['action']=='Datefilter'){
$Date_From=$_GET['Date_From'];
$Date_To=$_GET['Date_To'];
$select_msamaha ="SELECT distinct msamaha_aina FROM tbl_msamaha_items";        
  } 
  }  else {
$Today=date('Y-m-d');
$select_msamaha ="SELECT distinct msamaha_aina FROM tbl_msamaha_items";
                  
}
$TotalMale=0;
$Totalfemale=0;
$GrandTotalMale=0;
$GrandTotalFeMale=0;
$TotalBoth=0;
$GrandTotal=0;


//  GROUP BY Gender
     $msamaha=  mysqli_query($conn,$select_msamaha);
     while($row = mysqli_fetch_array($msamaha)){
      $aina_ya_msamaha=$row['msamaha_aina'];
     $selectAll=mysqli_query($conn,"SELECT Attendance_Date,Gender,aina_ya_msamaha,anayependekeza FROM tbl_msamaha tm,tbl_patient_registration tpr WHERE aina_ya_msamaha='$aina_ya_msamaha' AND $datebtn $employee_ID_Query AND tm.Registration_ID=tpr.Registration_ID");
      while ($result=mysqli_fetch_assoc($selectAll)){
          if($result['Gender']=='Male'){
            $TotalMale++;
            $GrandTotalMale++;
          }elseif($result['Gender']=='Female'){
            $Totalfemale++;
            $GrandTotalFeMale++;
          }
      } 
      
//      $GrandTotalMale=$TotalMale++;
//      $GrandTotalFeMale=$Totalfemale++; 
//      $TotalBoth=$GrandTotalMale+$GrandTotalFeMale;
      $GrandTotal=$TotalMale+$Totalfemale;
     
      echo "<tr><td>$temp</td><td>".strtoupper($aina_ya_msamaha)."</td><td><center>$TotalMale</center></td><td><center>$Totalfemale</center></td><td><center>$GrandTotal</center></td></tr>"; 
       $TotalMale=0;
      $Totalfemale=0;
      $temp++;
    } 
    $TotalBoth=$GrandTotalMale+$GrandTotalFeMale;
    echo "<tr><td></td><td>TOTAL</td><td><center>$GrandTotalMale</center></td><td><center>$GrandTotalFeMale</center></td><td><center>$TotalBoth</center></td></tr>";
    $GrandTotal=0;

?>
</table>
</center>