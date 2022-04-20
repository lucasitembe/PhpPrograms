<?php
    include("includes/connection.php");
    $patient_ID = mysqli_real_escape_string($conn,$_GET['patient_ID']);
            $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>ANTE-NATAL RECORD</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    
   
    //$htm.=$Query;
    //$htm.= "SELECT * FROM tbl_patient_registration JOIN tbl_payment_cache ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN tbl_item_list_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID WHERE tbl_payment_cache.Registration_ID='".$patient_ID."' AND tbl_item_list_cache.Payment_Cache_ID='".$payment_ID."' AND Check_In_Type='Laboratory'";
    $patientData=mysqli_query($conn,"SELECT Date_Of_Birth,Patient_Name,Gender,Registration_ID FROM tbl_patient_registration WHERE Registration_ID='$patient_ID'");
    $myptntData=  mysqli_fetch_assoc($patientData);      
    $Date_Of_Birth = $myptntData['Date_Of_Birth'];
    $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age.= $diff->m." Months";
    $disp.="<center><div style='margin-left:200px'>
       <b>Patient Name:".$myptntData['Patient_Name']."</b><br />
       <b>Age:".$age."</b><br />
       <b>Sex:".$myptntData['Gender']."</b><br />
       <b>Patient No:".$myptntData['Registration_ID']."</b><br />
       
       </center></div><br /><br />";
     
      $disp.='<table style="width:100%;border:0">
               
        
        <tr style="text-weight:bold">
            <td>
                <b> DATE </b>
            </td>
            
            <td>
               <b> WT </b>
            </td>
            
            <td>
               <b> B.P </b>
            </td>
            
            <td>
               <b> URINE ALB </b>
            </td>
            <td>
                <b>URINE SUG</b>
            </td>
            
            <td>
               <b> UT FH </b>
            </td>
            
            <td>
              <b>  POS </b>
            </td>
            
            <td>
              <b>  FH </b>
            </td>
            
            <td>
               <b> OEDEMA </b>
            </td>
            <td>
              <b>  HB </b>
            </td>
            
            <td>
               <b> GA </b>
            </td>
            
            <td>
               <b> REMARKS </b>
            </td>
            <td>
               <b> SIGN </b>
            </td>
        </tr>';
         
                $select=mysqli_query($conn,"SELECT * FROM tbl_deliverychart WHERE Patient_ID='$patient_ID'");
                while ($row=mysqli_fetch_assoc($select)){
               $disp.='<tr><td>'.$row['Attend_Date'].'</td>
                <td>'.$row['wt'].'</td>
                <td>'.$row['bp'].'</td>
                <td>'.$row['urine_alb'].'</td>
               <td>'.$row['urine_sug'].'</td>
               <td>'.$row['ut_fh'].'</td>
                <td>'.$row['pos'].'</td>
                <td>'.$row['fh'].'</td>
               <td>'.$row['oedema'].'</td>
                <td>'.$row['hb'].'</td>
                <td>'.$row['ga'].'</td>
               <td>'.$row['remarks'].'</td>';
                $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='".$row['Employee']."'");
                $row2= mysqli_fetch_assoc($employee);
                $name=$row2['Employee_Name'];
                $disp.= '<td>'.$name.'</td></tr>';
            }

   $disp.='</table>'; 
  
include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
$mpdf = new mPDF('c', 'A3-L');

$mpdf->WriteHTML($disp);
$mpdf->Output();
exit;
?>