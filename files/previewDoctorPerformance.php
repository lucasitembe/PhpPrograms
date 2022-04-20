<?php
    include("./includes/connection.php");
    session_start();
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
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
    
?> 
    <script src="css/jquery.js"></script>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>
	<!--End datetimepicker-->


<?php
    $htm="<img src='branchBanner/branchBanner1.png'>";
    $htm.="<fieldset>";
    $htm.="<legend align=center><b>DOCTOR'S PERFORMANCE DETAILS</b></legend>";
        $htm.="<center>";
		$htm.="<center><table width =100% border=0>";
		$htm.="<br><br>";
		/*echo "<tr>
			    <td width=3% style='text-align:left'><b>SN</b></td>
			    <td style='text-align:left' width=20%><b>PATIENT NAME</b></td>
			    <td style='text-align: left;' width=20%><b>CHECK IN DATE AND TIME</b></td>
                            <td style='text-align: left;' width=20%><b>DOCTOR'S SAVED DATE AND TIME</b></td>
                            <td style='text-align: left;' width=20%><b>SIGN OFF DATE AND TIME</b></td>
		     </tr>";
		    echo "<tr>
				<td colspan=4></td></tr>";*/
                            //run the query to select employee with the title doctor
                            //run the query to select all data from the database according to the branch id
                            $select_doctor_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor'");
                            $doctorSN=1;
                            while($select_doctor_query_row=mysqli_fetch_array($select_doctor_query)){//return employee details
                                //return data
                                $employeeID=$select_doctor_query_row['Employee_ID'];
                                $Employee_Name=$select_doctor_query_row['Employee_Name'];
                                $htm.= "<tr>";
                                            $htm.="<td>$doctorSN</td>";
                                            $htm.="<td colspan='6' style=''>$Employee_Name</td>";
                                        $htm.="</tr>";
                                $select_patient_item_list=mysqli_query($conn,"SELECT * FROM tbl_consultation co,tbl_patient_payment_item_list ppl,tbl_patient_payments pp
                                                                      WHERE co.Patient_payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
                                                                      ppl.Patient_Payment_ID=pp.Patient_Payment_ID
                                                                      AND ppl.Consultant_ID='$employeeID' AND ppl.Process_Status='signedoff'
                                                                      ");
                                
                                $htm.= "<tr><td colspan=6></td><tr>";
				$htm.= "<tr>";
				$htm.="<td width=3% style='text-align:left;border: 0;'><b></b></td>";
				$htm.="<td style='text-align:left'><b>SN</b></td>";
					$htm.="<td style='text-align:left' width=15%><b>NAME</b></td>";
                                        $htm.="<td style='text-align:left' width=10%><b>NUMBER</b></td>";
					$htm.="<td style='text-align: left;' width=20%><b>CHECK IN</b></td>";
					$htm.="<td style='text-align: left;' width=20%><b>SAVED</b></td>";
					$htm.="<td style='text-align: left;' width=20%><b>SIGN OFF</b></td>";
					$htm.="<td style='text-align: left;' width=15%><b>EP. OF CARE(HRS)</b></td>";
					$htm.="</tr>";
                                
                                $patientSN=1;
				$totalPatients=mysqli_num_rows($select_patient_item_list);
                                while($select_patient_item_list_row=mysqli_fetch_array($select_patient_item_list)){//select patient item list
                                    //return data
                                     $Patient_Payment_ID=$select_patient_item_list_row['Patient_Payment_ID']."<br>";
                                     $Signoff_Date_And_Time=$select_patient_item_list_row['Signedoff_Date_And_Time'];
                                    
                                        $select_patient_payment=mysqli_query($conn,"SELECT * FROM tbl_patient_payments pp WHERE pp.Patient_Payment_ID='$Patient_Payment_ID'");
                                        
                                        
                                        while($select_patient_payment_row=mysqli_fetch_array($select_patient_payment)){
                                            //return data
                                            $Registration_ID=$select_patient_payment_row['Registration_ID'];
                                            
                                            $select_patient=mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID' ");
                                            
                                            
                                            while($select_patient_row=mysqli_fetch_array($select_patient)){//select patient details
                                                $RegID=$select_patient_row['Registration_ID'];
                                                $Patient_Name=$select_patient_row['Patient_Name']."<br>";
                                                
                                                
                                                $select_check_in_info=mysqli_query($conn, "SELECT * FROM tbl_check_in ci,tbl_consultation co
                                                                                  WHERE ci.Registration_ID=co.Registration_ID
                                                                                  AND ci.Registration_ID='$RegID' ");
                                                while($select_check_in_info_row=mysqli_fetch_array($select_check_in_info)){//select check in info
                                                    //return data
                                                    $Check_In_Date_And_Time=$select_check_in_info_row['Check_In_Date_And_Time'];
                                                    $Consultation_Date_And_Time=$select_check_in_info_row['Consultation_Date_And_Time'];
                                                }
                                                
                                                //calculate episode of care
                                                $Consult_Date_And_Time=new DateTime($Consultation_Date_And_Time);
                                                $Off_Date_And_Time=new DateTime($Signoff_Date_And_Time);
                                                
                                                
                                                //these codes are here to determine the age of the patient
                                                $diff = $Off_Date_And_Time -> diff($Consult_Date_And_Time);
                                                //$time = $diff->y." Years, ";
                                                //$time .= $diff->m." Months, ";
                                                //$time .= $diff->d." Days";
                                                $time = $diff->h." Hours and ";
                                                $time .= $diff->i." Minutes";
                                                
                                                
                                                $htm.= "<tr><td style='border: 0;width:5%'></td><td>".($patientSN)."</td>";
                                                $htm.= "<td width='50%'>".$Patient_Name."</td>";
                                                $htm.= "<td width='5%'>".$RegID."</td>";
                                                $htm.= "<td width='10%'>".$Check_In_Date_And_Time."</td>";
                                                $htm.= "<td width='10%'>".$Consultation_Date_And_Time."</td>";
                                                $htm.= "<td width='10%'>".$Signoff_Date_And_Time."</td>";
                                                $htm.= "<td width='10%'>".($time)."</td>";
                                                $htm.= "</tr>";
						
                                                
                                            }
                                            $patientSN =$patientSN + 1;
					    
                                        }
					
                                }
                                $doctorSN=$doctorSN + 1;
				$htm.="<tr><td colspan='5' style='font-size:14px;font-weight:bold;'> Total number of patients ".$totalPatients."</td></tr>";
				$htm.="<tr><td colspan='8'><hr></td></tr>";
                            }
			    
			    $htm.="</table>";
			$htm.="</center>";
			$htm.="</center>";
$htm.="</fieldset>";



	include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 




?>