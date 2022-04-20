<?php

include("includes/connection.php");
$temp = 1;

$Today = mysqli_query($conn,"select now() as today");

$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];

if(isset($_GET['employee_ID'])){
    $employee_ID=$_GET['employee_ID'];
     $query=mysqli_query($conn,"SELECT Employee_Name from tbl_employee WHERE Employee_ID='$employee_ID'");
    $result=  mysqli_fetch_assoc($query);
    $employee_name=$result['Employee_Name'];
    
    if($employee_ID==''){
      $employee_name='ALL';
      $employee_ID_Query='';    
    }else{
      $employee_ID_Query="AND anayependekeza='$employee_ID'";
      $employee_name=$result['Employee_Name'];
    }
}  else {
  $employee_ID_Query='';  
}

if ($Date_From != '' && $Date_To != '') {
    $datebtn = "Attendance_Date BETWEEN '$Date_From' AND '$Date_To'";
} else {
    $datebtn = "Attendance_Date='$Today'";
}


$disp = '<table width ="100%" height="30px">
		<tr>
		    <td>
			<img src="./branchBanner/branchBanner.png" width=100%>
		    </td>
		</tr>
		<tr>
		   <td style="text-align: center;"><b>WAGONJWA WA MSAMAHA</b></td>
		</tr>
                <tr>
		   <td style="text-align: center;"><b>EMPLOYEE: '.$employee_name.'</b></td>
		</tr>
               
                <tr>
		   <td style="text-align: center;"><b>KUANZIA ' . $Date_From . ' HADI ' . $Date_To . '</b></td>
		</tr>
                
                <tr>
                    <td style="text-align: center;"><hr></td>
                </tr>
          </table>';

$disp.='<table width ="100%" border="1" style="border-collapse: collapse;">';
$disp.= '<thead><tr><td style="width:5%;"><b>SN</b></td>
   <th style="text-align:left"><b>AINA YA MSAMAHA</b></th>
   <th style="text-align:center"><b>MALE</b></th>
   <th style="text-align:center"><b>FEMALE</b></th>
   <th style="text-align:center"><b>TOTAL</b></th> 
   </tr></thead>';


$select_msamaha = "SELECT distinct msamaha_aina FROM tbl_msamaha_items";
$TotalMale = 0;
$Totalfemale = 0;
$GrandTotalMale = 0;
$GrandTotalFeMale = 0;
$TotalBoth = 0;
$GrandTotal = 0;
$msamaha = mysqli_query($conn,$select_msamaha);
while ($row = mysqli_fetch_array($msamaha)) {
    $aina_ya_msamaha = $row['msamaha_aina'];
    $selectAll = mysqli_query($conn,"SELECT Attendance_Date,Gender,aina_ya_msamaha,anayependekeza FROM tbl_msamaha tm,tbl_patient_registration tpr WHERE aina_ya_msamaha='$aina_ya_msamaha' AND $datebtn $employee_ID_Query AND tm.Registration_ID=tpr.Registration_ID");
    while ($result = mysqli_fetch_assoc($selectAll)) {
        if ($result['Gender'] == 'Male') {
            $TotalMale++;
            $GrandTotalMale++;
        } elseif ($result['Gender'] == 'Female') {
            $Totalfemale++;
            $GrandTotalFeMale++;
        }
    }
    $GrandTotal = $TotalMale + $Totalfemale;

    $disp.= "<tr><td>$temp</td><td>".strtoupper($aina_ya_msamaha)."</td><td><center>$TotalMale</center></td><td><center>$Totalfemale</center></td><td><center>$GrandTotal</center></td></tr>";
    $TotalMale = 0;
    $Totalfemale = 0;
    $temp++;
}
$TotalBoth = $GrandTotalMale + $GrandTotalFeMale;
$disp.="<tr><td></td><td>TOTAL</td><td><center>$GrandTotalMale</center></td><td><center>$GrandTotalFeMale</center></td><td><center>$TotalBoth</center></td></tr>";
$GrandTotal = 0;


$disp.= "</table>";

include("MPDF/mpdf.php");

//$mpdf=new mPDF('','Letter-L',0,'',12.7,12.7,14,12.7,8,8); 
$mpdf = new mPDF('c', 'Letter-L');
$mpdf->SetFooter('{PAGENO}/{nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($disp);
$mpdf->Output();
exit;
?>