<?php
 include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     @session_start();
    
    if(!isset($_SESSION['Procedure_Supervisor'])){ 
      header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
    }
    
    ?>
   
 
        
   <?php
    
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
    
   $Date_From='';
   $Date_To='';
    
 $data= '<center><table width =100% border=0>';
    $data .= '<tr id="thead" style="width:5%;"><td><b>SN</b></td>
	    <td width><b>STATUS</b></td>
		<td><b>PATIENT NAME</b></td>
		    <td><b>PATIENT NUMBER</b></td>
			<td><b>SPONSOR</b></td>
			    <td><b>AGE</b></td>
				<td><b>GENDER</b></td>
				    <td><b>SUB DEPARTMENT</b></td>
					</tr>';
					    //<td><b>DATE</b></td>
    $qr='';
    if(isset($_POST['submitFilter'])){//between '2011-03-17 06:42:10' and '2011-03-17 06:42:50';
        
        $Date_From=$_POST['Date_From'];
         $Date_To=$_POST['Date_To'];
        
        $qr="(select 'DOCTOR_CONSULT' AS typeconsultant, pc.Registration_ID,pc.Billing_Type, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID,
	    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,ilc.remarks,
		preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
		    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
		        pc.payment_cache_id = ilc.payment_cache_id and
			    preg.registration_id = pc.registration_id and
				    sd.sub_department_id = ilc.sub_department_id and
					ilc.status IN ('Pending') and
                                            ilc.Check_In_Type = 'Procedure' AND ilc.Transaction_Date_And_Time between '$Date_From' and '$Date_To'
					    group by pc.payment_cache_id,ilc.sub_department_id order by pc.payment_cache_id)
               UNION                              
                  ( select 'OTHERS_CONSULT' AS typeconsultant, pc.Registration_ID,pc.Billing_Type, pc.Transaction_Status as General_Transaction_Status, pc.Patient_Payment_ID,
                         preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,ilc.remarks,
                            preg.Member_Number,'processAnywhere' as 'Transaction_Type', ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                                tbl_patient_payments pc, tbl_patient_payment_item_list ilc, tbl_patient_registration preg, tbl_sub_department sd where
                                     pc.Patient_Payment_ID = ilc.Patient_Payment_ID and
                                        preg.registration_id = pc.registration_id and
                                           	ilc.status IN ('Pending') and
                                                     ilc.Check_In_Type = 'Procedure' AND ilc.Transaction_Date_And_Time between '$Date_From' and '$Date_To'
                                               group by pc.Patient_Payment_ID,ilc.sub_department_id order by pc.Patient_Payment_ID)
               ";
    }else{
        $qr="(select 'DOCTOR_CONSULT' AS typeconsultant, pc.Registration_ID,pc.Billing_Type, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID,
	    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,ilc.remarks,
		preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
		    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
		        pc.payment_cache_id = ilc.payment_cache_id and
			    preg.registration_id = pc.registration_id and
				    sd.sub_department_id = ilc.sub_department_id and
					ilc.status IN ('Pending') and
                                            ilc.Check_In_Type = 'Procedure'
					    group by pc.payment_cache_id,ilc.sub_department_id order by pc.payment_cache_id)
               UNION                              
                  ( select 'OTHERS_CONSULT' AS typeconsultant, pc.Registration_ID,pc.Billing_Type, pc.Transaction_Status as General_Transaction_Status, pc.Patient_Payment_ID,
                         preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,ilc.remarks,
                            preg.Member_Number,'processAnywhere' as 'Transaction_Type', ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                                tbl_patient_payments pc, tbl_patient_payment_item_list ilc, tbl_patient_registration preg, tbl_sub_department sd where
                                     pc.Patient_Payment_ID = ilc.Patient_Payment_ID and
                                        preg.registration_id = pc.registration_id and
                                           	ilc.status IN ('Pending') and
                                                     ilc.Check_In_Type = 'Procedure'
                                               group by pc.Patient_Payment_ID,ilc.sub_department_id order by pc.Patient_Payment_ID)
               ";
    }
				
    
					
    //echo $qr;
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    $temp=1;
    $statusMsg='';
    
    if(mysqli_num_rows($select_Filtered_Patients)>0){
      while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
        $billing_type=$row['Billing_Type'];
        $transaction_type=strtolower($row['Transaction_Type']);
        $status=strtolower($row['status']);
//        $billing_type='Outpatient Cash';
//        $status="paid";
        
         if($row['typeconsultant']=="OTHERS_CONSULT"){
              $statusMsg="Paid";
         }  else {
             
         
            if($billing_type=='Outpatient Cash' && $status=="pending"){
                $statusMsg="Not Paid";
            }elseif($billing_type=='Outpatient Cash' && $status=="paid"){
                $statusMsg="Paid";
            }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="paid"){
                $statusMsg="Paid";
           }elseif($billing_type=='Outpatient Credit' && $transaction_type=="cash" && $status=="active"){
                $statusMsg="Not Paid";
           }elseif($billing_type=='Outpatient Credit' && $transaction_type=="credit" && $status=="pending"){
                $statusMsg="Bill";
           }elseif($billing_type=='Inpatient Cash' || $billing_type=='Inpatient Credit'){
                $statusMsg="Bill";
           }
       
       }
        
        
	$data .= "<tr><td id='thead' style='width:5%;' >".$temp."</td>";
	    $data .= "<td><b>".$statusMsg."</b></td>";
	
	
	//GENERATE PATIENT YEARS, MONTHS AND DAYS
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years"; 		
	$date1 = new DateTime($Today);
	$date2 = new DateTime($row['Date_Of_Birth']);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
	
        if($statusMsg=='Not Paid'){
            
	
            $data .= "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";

            $data .= "<td>".$row['Registration_ID']."</td>";

            $data .= "<td>".$row['Sponsor_Name']."</td>";

            $data .= "<td>".$age."</td>";

            $data .= "<td>".$row['Gender']."</td>";

            $data .= "<td>".$row['Sub_Department_Name']."</td>";

            //$data .= "<td>".$row['Member_Number']."</td>";

        //echo "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&ProcedureWorks=ProcedureWorksThisPage'  >".$row['Transaction_Date_And_Time']."</td>";
	
        }else{
            if($row['typeconsultant']=="OTHERS_CONSULT"){ 
                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";

/*                  $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>"; */

            }else{
                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";

                 $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";

                /*  $data .= "<td><a href='pendingprocedurepatientinfo.php?section=Procedure&typeconsultant=".$row['typeconsultant']."&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$row['Billing_Type']."&Sub_Department_ID=".$Sub_Department_ID."&statusMsg=".$statusMsg."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>"; */

            }
        }
        
        $data .= "</tr>
                   
                "; 
	$temp++;
     }
    }else{
        $data .= "<tr><td style='font-weight:bold;font-size:18px;color:red;text-align:center' colspan='9'>No Item Found</td></tr>";
    }
    
       $data.='</table></center>';
    ?>
<a href='Procedure.php' class='art-button-green'>
        BACK
    </a>
	
 <style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
 </style>
 
<fieldset style="margin-top:10px;margin-top:20px">
    <legend align="center" style="padding:10px;background:#006400;color:white;text-align: center "><b>  ALL PATIENTS PENDING PROCEDURE LIST</b><?php  echo (isset($Date_From) && !empty($Date_From))?'<br/>From '.$Date_From.' To '.$Date_To:''?>  </legend>
                             

        <form action="procedurelist.php" method="POST">
            <center>
                 <table  class="hiv_table" style="width:90%;margin-top:5px;">
                <tr> 

                    <td style="text-align:right;width:80px"><b>Date From<b></td>
                    <td width="150px"><input type='text' name='Date_From' id='date_From' required="required"></td>
                    <td style="text-align:right;width:80px"><b>Date To<b></td>
                    <td width="150px"><input type='text' name='Date_To' id='date_To' required="required"></td>
                    <td width="50px"><input type="submit" name="submitFilter" value="Filter" class="art-button-green" /></td>
                    <td width="50px"><a href="procedurelist.php" ><input type="button" name="refresh" value="All" class="art-button-green" /></a></td>

                </tr> 
            </table>
            </center>
        </form>


 
                    <hr width="100%">
                    <div style="width:100%;height:400px;overflow-y:scroll;overflow-x:hidden  ">
                        <?php echo $data;?>
                    </div>
					<hr>
					<p align="center">
					   <a href="procedurelist.php"><button class="art-button-green" type="button">Current Procedures</button>
					</p>
     <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
   
     <script src="css/jquery.datetimepicker.js"></script>
      <script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
     <script src="css/jquery-ui.js"></script>
    <script src="css/scripts.js"></script>
    <script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
    </script>
