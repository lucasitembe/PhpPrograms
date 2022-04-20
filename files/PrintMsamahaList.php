<?php
    include("includes/connection.php");
    $temp = 1;
    if(isset($_GET['Search_Product'])){
        $Search_Product= $_GET['Search_Product'];   
    }else{
        $Search_Product = '';
    }
    
    $msamaha_aina=  mysqli_real_escape_string($conn,$_GET['msamaha_aina']);
    $Date_To=  mysqli_real_escape_string($conn,$_GET['Date_To']);
    $Date_From=  mysqli_real_escape_string($conn,$_GET['Date_From']);
    $jinsi=  mysqli_real_escape_string($conn,$_GET['jinsi']);
    $employee_ID=  mysqli_real_escape_string($conn,$_GET['employee_ID']);
    if($jinsi==''){
    $gender_query=''; 
    $gender_name='ALL';
    }  else {
     $gender_query="AND Gender='$jinsi'";
     $gender_name= strtoupper($jinsi);
    }   
    
    if($msamaha_aina==''){
       $msamaha='ALL'; 
    }  else {
      $msamaha=  strtoupper($msamaha_aina);  
    }
    
    if($employee_ID==''){
    $employee_ID_Query='';
    $employee_name='ALL';
}  else {
    $employee_ID_Query="AND anayependekeza='$employee_ID'";
    $query=mysqli_query($conn,"SELECT Employee_Name from tbl_employee WHERE Employee_ID='$employee_ID'");
    $result=  mysqli_fetch_assoc($query);
    $employee_name=$result['Employee_Name'];
}


//echo $employee_ID_Query;
//exit();
    
    $disp='<table width ="100%" height="30px">
		<tr>
		    <td>
			<img src="./branchBanner/branchBanner.png" width=100%>
		    </td>
		</tr>
		<tr>
		   <td style="text-align: center;"><b>WAGONJWA WA MSAMAHA</b></td>
		</tr>
                <tr>
		   <td style="text-align: center;"><b>MSAMAHA TYPE: '.$msamaha.'</b></td>
		</tr>
                <tr>
		   <td style="text-align: center;"><b>EMPLOYEE: '.$employee_name.'</b></td>
		</tr>
                <tr>
		   <td style="text-align: center;"><b>GENDER: '.$gender_name.'</b></td>
		</tr>
                
                <tr>
                    <td style="text-align: center;"><hr></td>
                </tr>
          </table>';
    $disp.='<center><table width =100% border="1" style="border-collapse: collapse;">';
    $disp.='<tr id="thead"><td style="width:5%;"><b>SN</b></td>
                <th><b>Date</b></th>
	        <td><b>Jina la mgojwa</b></td>
		<td><b>Umri</b></td>
		<td><b>Jinsia</b></td> 
		<td><b>Nambari ya simu</b></td>
                <td><b>Aina ya Msamaha</b></td>
		<td><b>Jina la mtu anayependekeza Msamaha</b></td>
		<td><b>Jina la Balozi</b></td>
		<td><b>Region</b></td>
		<td><b>District</b></td>
		<td><b>Ward</b></td>
                <td><b>kiwango cha elimu</b></td>		
		<td><b>Kazi ya mke/mlezi</b></td>
		<td><b>Prepared By</b></td>
		</tr>';
    
 if(($Date_From=='' || $Date_From=='NULL') || ($Date_To=='' || $Date_To=='NULL')){
 if($msamaha_aina=='' || $msamaha_aina=='NULL'){
 $select_msamaha = mysqli_query($conn,"SELECT
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
 WHERE tm.Registration_ID=tpr.Registration_ID $employee_ID_Query AND te.Employee_ID=anayependekeza AND Patient_Name LIKE '%$Search_Product%'") or die(mysqli_error($conn));
   
            
        }else{
            
$select_msamaha = mysqli_query($conn,"SELECT
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
 WHERE tm.Registration_ID=tpr.Registration_ID AND te.Employee_ID=anayependekeza AND aina_ya_msamaha='$msamaha_aina' $gender_query $employee_ID_Query AND Patient_Name LIKE '%$Search_Product%'") or die(mysqli_error($conn));
      
            
        }

    }  else {
        
         if($msamaha_aina=='' || $msamaha_aina=='NULL'){
                    $select_msamaha = mysqli_query($conn,"SELECT
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
 WHERE tm.Attendance_Date BETWEEN '$Date_From' AND '$Date_To' AND tm.Registration_ID=tpr.Registration_ID $gender_query $employee_ID_Query AND te.Employee_ID=anayependekeza AND Patient_Name LIKE '%$Search_Product%'") or die(mysqli_error($conn));
    
             
             
         }  else {
                     $select_msamaha = mysqli_query($conn,"SELECT
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
 WHERE tm.Attendance_Date BETWEEN '$Date_From' AND '$Date_To' AND tm.Registration_ID=tpr.Registration_ID $gender_query $employee_ID_Query AND te.Employee_ID=anayependekeza AND aina_ya_msamaha='$msamaha_aina' AND Patient_Name LIKE '%$Search_Product%'") or die(mysqli_error($conn));
   
             
             
         }
        
     
}
 /// selecting deady body whoes registered in the system
		    
    while($row = mysqli_fetch_array($select_msamaha)){
         //AGE FUNCTION
        $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
        
        $disp.="<tr><td style='text-align: center;' id='thead'>".$temp."</td>";
        $disp.= "<td>".$row['Attendance_Date']."</td>";
        $disp.= "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";	 
        $disp.= "<td>".$age."</td>";
        $disp.= "<td>".$row['Gender']."</td>";
        $disp.= "<td>".$row['Phone_Number']."</td>";
        $disp.= "<td>".$row['aina_ya_msamaha']."</td>";
        $disp.= "<td>".$row['Employee_Name']."</td>";
        $disp.= "<td>".$row['jina_la_balozi']."</td>";   
	$disp.= "<td>".$row['Region']."</td>";
	$disp.= "<td>".$row['District']."</td>";
	$disp.= "<td>".$row['Ward']."</td>";
	$disp.= "<td>".$row['kiwango_cha_elimu']."</td>";
	$disp.= "<td>".$row['kazi_mke']."</td>";
	$disp.= "<td>".$row['Employee_Name']."</td>";

	$temp++;
    }   $disp.= "</tr></table>";
       
   include("MPDF/mpdf.php");

    //$mpdf=new mPDF('','Letter-L',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf=new mPDF('c','A3-L'); 
    $mpdf->SetFooter('{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>