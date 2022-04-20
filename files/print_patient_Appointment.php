<style>
   table {
    border-collapse: collapse;
 }

table, th, td {
    border: 1px solid black;
}
</style>
<?php

    include("./includes/connection.php"); 
	 $temp = 1;

	if(isset($_GET['starDate'])){
		$fromDate = $_GET['starDate'];
	} else {
		$fromDate = '';
	}
	
	if(isset($_GET['endDate'])){
		$toDate = $_GET['endDate'];
	} else {
		$toDate = '';
	}
        
        if(isset($_GET['doctor'])){
         $doctor = $_GET['doctor'];
	} 
        
        if(isset($_GET['clinic'])){
         $clinic = $_GET['clinic'];
	}
        
        
   $filterclinic="";
	if($clinic != ""){
    $filterclinic=" AND ap.Clinic='$clinic'";
    }else {
        $filterclinic="";
    }
    
   $filterdoctor="";
	if($doctor != ""){
    $filterdoctor=" AND ap.doctor='$doctor'";
    }else {
        $filterdoctor="";
    }
	
	
	   $htm= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>APPOINTMENT REPORT</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$start_date." TO ".$end_date."</b></td>
		</tr>
                

                
        <tr>";
		  if($doctor!=''){
            $doctorname=mysqli_query($conn,'SELECT * FROM tbl_employee WHERE Employee_ID="'.$doctor.'"');
            $result=  mysqli_fetch_assoc($doctorname);
          $htm.= "<td style='text-align: center;'><b>DR. NAME: ".strtoupper($result['Employee_Name'])."</b></td>";  
        }elseif ($clinic!='') {
          $clinicname=mysqli_query($conn,'SELECT * FROM tbl_clinic WHERE Clinic_ID="'.$clinic.'"');
          $result=  mysqli_fetch_assoc($clinicname);
          $htm.= "<td style='text-align: center;'><b>CLINIC NAME: ".strtoupper($result['Clinic_Name'])."</b></td>";
		 
        }
		   
		   
		   
		 $htm.= "</tr>
                <tr>
                   
                </tr>
            </table>";
	
			    $htm.= '<center><table border="1" id="doctorperformancereportsummarised" class="display" style="width:100%;border-collapse: collapse;">';
				$htm.= "<thead>
                 <tr>
			    <th width=3% style='text-align:left'>SN</th>
                            <th style='text-align:left'>APPOINTMENT DATE</th>
                            <th style='text-align:left'>APPOINTMENT SET BY</th>
                            <th style='text-align: left;'>PATIENT NAME</th>
                            <th style='text-align: left;'>PATIENT #</th>
                            <th style='text-align: left;'>PHONE No #</th>
                            <th style='text-align: left;'>APPOINTMENT REASON</th>
                            <th style='text-align: left;'>CLINIC</th>
                            <th style='text-align: left;'>DOCTOR</th>
                            
		</tr>
            </thead>";
	
				
				
				
				
//    $Transaction_Items_Qry="SELECT reg.Phone_Number,reg.Registration_ID,ap.doctor,ap.Set_BY,ap.date_time,emp.Employee_Name,reg.Patient_Name,ap.appointment_reason,cl.Clinic_Name,ap.appointment_id FROM tbl_appointment ap,tbl_employee emp,tbl_patient_registration reg,tbl_clinic cl WHERE ap.patient_No=reg.Registration_ID AND ap.doctor=emp.Employee_ID $filterdoctor AND ap.Clinic=cl.Clinic_ID $filterclinic AND ap.Status='1' AND date_time BETWEEN '$fromDate' AND '$toDate' LIMIT 200  ";
    
     $Transaction_Items_Qry="SELECT ap.Clinic,reg.Phone_Number,reg.Registration_ID,ap.doctor,ap.Set_BY,ap.date_time,reg.Patient_Name,ap.appointment_reason,ap.appointment_id FROM tbl_appointment ap,tbl_patient_registration reg WHERE ap.patient_No=reg.Registration_ID $filterclinic  AND ap.Status='1' $filterdoctor AND date_time BETWEEN '$fromDate' AND '$toDate' LIMIT 200  ";  
	
    $select_Transaction_Items = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn)); 
       $sn=1;
        while ($row = mysqli_fetch_assoc($select_Transaction_Items)){
          $Clinic  = $row['Clinic'];
          $doctor  = $row['doctor'];
          $clinic_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic'"))['Clinic_Name'];
          $doctor_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$doctor'"))['Employee_Name'];
          
        $htm.='<tr>';
        $htm.='<td>'.$sn++.'.</td>';
        $htm.='<td style="text-align:left;">'.$row['date_time'].'</td>';
        $getemployee=  mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='".$row['Set_BY']."'");
        $result=  mysqli_fetch_assoc($getemployee);
        $htm.='<td style="text-align:left;">'.$result['Employee_Name'].'</td>';
        $htm.='<td style="text-align:left;">'.$row['Patient_Name'].'</td>';
        $htm.='<td style="text-align:left;">'.$row['Registration_ID'].'</td>';
        $htm.='<td style="text-align:left;">'.$row['Phone_Number'].'</td>';
        $htm.='<td style="text-align:left;">'.$row['appointment_reason'].'</td>';
        $htm.='<td style="text-align:left;">'.$clinic_name.'</td>';
        $htm.='<td style="text-align:left;">'.$doctor_name.'</td>';
       }  
        $htm.="</tr>";
        $htm.="</table></center>";
        include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 
         $mpdf=new mPDF('c','A4'); 
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 

?>

