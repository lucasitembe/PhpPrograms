<?php
    include("./includes/connection.php");
    session_start();
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works']) && $_SESSION['userinfo']['Management_Works'] == 'yes'){
	    
	}else if(isset($_SESSION['userinfo']['Mtuha_Reports']) && $_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>

<?php
    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
?>
 
<?php
    $htm="<img src='branchBanner/branchBanner1.png'>";
    $htm.="<fieldset>";
    $htm.="<p align='center'><b>DOCTORS WITH NO FINAL DIAGNOSIS REPORT SUMMARY  <br/>FROM</b> <b style=''>".date('j F,Y H:i:s',strtotime($Date_From))."</b> <b>TO</b> <b style=''>".date('j F,Y H:i:s',strtotime($Date_To))."</b> </legend>";
        $htm.="<center>";
        $htm.="<center><table width =100% border=0>";
        $htm.="<br><br>";
		 $htm.= "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=12%>PATIENTS</th>
		     </tr>
                     <tr>
                       <td colspan='3'><hr width='100%' /></td>
                     </tr> 
                    </thead>";
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
                        $patient_no_number=0;
                       $getConsultationResult=mysqli_query($conn,"SELECT ch.consultation_ID FROM tbl_consultation_history ch JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employeeID'") or die(mysqli_error($conn));
                        
                       while ($row = mysqli_fetch_array($getConsultationResult)) {
                           
                       
                          $result_patient=   mysqli_query($conn,"SELECT dc.Disease_Consultation_ID FROM tbl_disease_consultation dc WHERE dc.consultation_ID ='".$row['consultation_ID']."' AND dc.diagnosis_type='diagnosis'") or die(mysqli_error($conn));
                          
                          if(mysqli_num_rows($result_patient) > 0){
                              
                          }  else {
                              $patient_no_number +=1;
                          }
                       }
			
			 $empSN ++;
			 $htm.= "<tr><td>".($empSN)."</td>";
			 $htm.= "<td style='text-align:left'>".$employeeName."</td>";
			 $htm.= "<td style='text-align:center'>".number_format($patient_no_number)."</td></tr>";
                         $htm.= "<tr>
                                <td colspan='3'><hr width='100%' /></td>
                              </tr> ";
                    }
			    
			    $htm.="</table>";
			$htm.="</center>";
$htm.="</fieldset>";



	include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 




?>