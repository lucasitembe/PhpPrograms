<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Reception_Works'])){
//	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	    }
//	}else{
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	
	
	if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./receptionReports.php?Section=<?php echo $Section;?>&ReceptionReportThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
    $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
    while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
        $today_start=$start_dt_row['cast(current_date() as datetime)'];
    }
?>


<br/><br/>
<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:1px solid #999999 !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
        #filter_tbl,#filter_tbl tr,#filter_tbl tr td,#filter_tbl tr td table{
           border-collapse:collapse !important;
           border:none !important; 
        }
 </style> 

<center>
    <table width=100% style="background-color:white;" id="filter_tbl">
	<tr>
	    <td>
	    	<input type='text' id='Search_Value' name='Search_Value' style='text-align: center;' placeholder='Patient Name' autocomplete='off' onkeyup='Get_Filtered_Patients_Filter()' onclick="Get_Filtered_Patients_Filter()">
	    </td>
	    <td style='text-align: right;' width=7%>Sponsor</td>
	    <td width="10%">
	    	<select name="Sponsor_ID" id="Sponsor_ID">
	    		<option selected="selected" value="0">All</option>
	    	<?php
	    		$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
	    		$num = mysqli_num_rows($select);
	    		if($num > 0){
	    			while ($data = mysqli_fetch_array($select)) {
	    	?>
	    			<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
	    	<?php
	    			}
	    		}
	    	?>
	    	</select>
	    </td>
	    <td style='text-align: right;' width=7%><b>Start Date</b></td>
	    <td width=10%>
	    <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $today_start; ?>'>
	    </td>
	    <td style='text-align: right;' width=7%><b>End Date</b></td>
	    <td width=10%>
		<input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
	    </td>
	    <td width="6%" style="text-align: right;"><b>Destination</b></td>
	    <td width="7%">
	    	<select id="Destination" name="Destination" onchange="Get_Filtered_Patients_Filter()">
	    		<option value="x">All</option>
	    		<option value="y">Direct To Doctor</option>
	    		<option value="z">Direct To Doctor Via Nurse Station</option>
	    	<?php
	    		$select = mysqli_query($conn,"select Clinic_Name, Clinic_ID from tbl_clinic order by Clinic_Name") or die(mysqli_error($conn));
	    		$nm = mysqli_num_rows($select);
	    		if($nm > 0){
	    			while ($dt = mysqli_fetch_array($select)) {
	    	?>
	    		<option value="Clinic_ID<?php echo $dt['Clinic_ID']; ?>"><?php echo $dt['Clinic_Name']; ?></option>
	    	<?php
	    			}
	    		}
	    	?>
	    	</select>
	    </td>
	    <td style='text-align: center;' width=9%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Filtered_Patients()'></td>
	</tr>
        <tr>
            <td colspan="10">
                <table style="width:100%">
                    <tr>
                        <td>
                            <input type='text' id='Search_Patient_number' name='Search_Patient_number' style='text-align: center;' placeholder='Patient Number' autocomplete='off' oninput='Get_Filtered_Patients_Filter()' onclick="Get_Filtered_Patients_Filter()">
                        </td>
                        <td style="text-align:right">Visit Type</td>
                        <td>
                            <select id="visit_type">
                                <option value="All">All</option>
                                <option value="1">Normal Visit</option>
                                <option value="2">Emegency</option>
                                <option value="3">Refferal</option>
                                <option value="4">Follow up Visit</option> 
                                <option value='5'>Start</option>
                                <option value='6'>Self Referral</option>
                            </select>
                        </td>
                        <td style="text-align:right">Type of Check in</td>
                        <td>
                            <select id="Type_Of_Check_In">
                                <option selected="selected" value="All">All</option>
                                <option value="Afresh">New Patient</option>
                                <option value="Continuous">Return Patient</option>
                            </select>
                        </td>
                        <td style="text-align:right">Alive/Diceased</td>
                        <td>
                            <select id="alive_diceased">
                                <option selected="selected" value="All">All</option>
                                <option value="no">Alive</option>
                                <option value="yes">Diceased</option>
                            </select>
                        </td>
                        <td style="text-align:right">PF3</td>
                        <td>
                            <select id="pf3">
                                <option selected="selected" value="All">All</option>
                                <option value="yes">Pf3</option>

                            </select>
                        </td>
                        <td>Units</td>
                        <td>
                            <select id="military_unit">
                                <option value="All">All Units</option>
                                <?php 
                                    $sql_select_unit=mysqli_query($conn,"SELECT military_unit FROM tbl_patient_registration WHERE military_unit<>'' GROUP BY military_unit ORDER BY Registration_ID DESC") or die(mysqli_error($conn));
                                   if(mysqli_num_rows($sql_select_unit)>0){
                                      while($unit_rows=mysqli_fetch_assoc($sql_select_unit)){
                                          $military_unit=$unit_rows['military_unit'];
                                          echo "<option value='$military_unit'>$military_unit</option>";
                                      } 
                                   }
                                ?>
                            </select>
                        </td>
                        <td>Patient Type</td>
                        <td>
                            <?php 
                                $sql_check_if_military_result=mysqli_query($conn,"SELECT configname FROM tbl_config WHERE configname='Military' AND configvalue='Yes'") or die(mysqli_error($conn))
                            ?>
                            <select id="patient_type">
                                <option value="All">All Patient Type</option>
                                <?php if(mysqli_num_rows($sql_check_if_military_result)){ ?>
                                <option value="civilian">Civilian</option>
                                <option value="military">Military Personnel</option>
                                <option value="military_dependant">Dependant</option>
                                <?php } ?>
                            </select>
                        </td>
                        <!-- <td>Rank</td>
                        <td>
                             <select id="rank">
                                <option value="All">All Rank</option>
                                <?php 
                                //     $sql_select_unit=mysqli_query($conn,"SELECT rank FROM tbl_patient_registration WHERE rank<>'' GROUP BY rank ORDER BY Registration_ID DESC") or die(mysqli_error($conn));
                                //    if(mysqli_num_rows($sql_select_unit)>0){
                                //       while($unit_rows=mysqli_fetch_assoc($sql_select_unit)){
                                //           $rank=$unit_rows['rank'];
                                //           echo "<option value='$rank'>$rank</option>";
                                //       } 
                                //    }
                                ?>
                            </select>
                        </td> -->
                        <td>
                            <button type="button" style="display:none" class="art-button-green" onclick="get_opd_report()">OPD FILTER</button>
                        </td>
                        <td>
                            <button type="button" style="display:none"  class="art-button-green" onclick="get_ipd_report()">IPD FILTER</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="10">
                <table>
                    <tr>
                        <td>
                            Gender
                            <select name="patient_gender" id="patient_gender">
                                <option value="All">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                        <td>
                            Diagnosis Type
                            <select name="diagnosis_type" id="diagnosis_type">
                                <option value="All">All</option>
                                <option value="provisional_diagnosis">Provisional Diagnosis</option>
                                <option value="diferential_diagnosis">Differential Diagnosis</option>
                                <option value="diagnosis">Final Diagnosis</option>
                            </select>
                        </td>
                        <td>
                           Consultation Type
                           <select name="consultation_type" id="consultation_type">
                               <option value="All">All</option>
                                <option value="Doctor Room">Doctor Room</option>
                                <option value="others">Others</option>
                           </select>
                        </td>
                        <td>
                            Admission
                            <select name="Admit_Status" id="Admit_Status">
                               <option value="All">All</option>
                                <option value="admitted">Admitted</option>
                                <option value="not admitted">Not admitted</option>
                           </select>
                        </td>
                        <td>   Age
                             <input type="number" id="start_age" name="start_age" placeholder="Start age" class="numberonly" style='text-align: center;display:inline;padding: 4px'/>
                             <input type="number" id="end_age" name="end_age" placeholder="End age" class="numberonly" style='text-align: center;display:inline;padding: 4px'/>
                        </td>
                    </tr>
                </table>
            </td> 
        </tr>
    </table>
</center>
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:1});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:1});
    </script>
    <!--End datetimepicker-->
<br/>
<div class="row">
    <div class="col-md-12">
        <fieldset style='overflow: auto; height: 440px; background-color:white'class="table-responsive" id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>MASTER SHEET</b></legend>
    <table width=100% >
	<!--    <tr><td colspan="17"><hr></td></tr>-->
    <tr style="background:#cccccc">
            <td width=2%><b>SN</b></td>
            <td width=6%><b>PATIENT#</b></td>
            <td><b>PATIENT NAME</b></td>
            <td><b>AGE</b></td>
            <td><b>SEX</b></td>
            <td><b>TRIBE</b></td>
            <td><b>RESIDENT</b></td>	
            <td><b>REFEREED FROM </b></td>
            <td><b>REFEREED REASON </b></td>
            <td><b>REFERAL ATTACHMENT </b></td>
            <td><b>READ</b></td>
            <td><b>ADMITTED</b></td>
            <td><b>DISCHARGE</b></td>
            <td><b>DIED</b></td>
            <td width=6%><b>DIAGNOSIS</b></td>
            <td><b>REMARKS</b></td>
            <td><b>PATIENT DIRECTION</b></td>
            <td><b>PATIENT DESTINATION</b></td>
            <td><b>PHONE</b></td>
            <td><b>AUTHORIZATION NUMBER</b></td>
        </tr>
		<!--<tr><td colspan="17"><hr></td></tr>-->
	    <?php
		$temp = 0; $Destination = '';
		//get list of checked in patients
		$select = mysqli_query($conn,"select ci.AuthorizationNo,ci.referral_letter,ci.referral_reason,sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Tribe,pr.Region, pr.District, pr.village, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID
					from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where
					pr.Registration_ID = ci.Registration_ID and
					ci.Employee_ID = emp.Employee_ID and
					pp.Check_In_ID = ci.Check_In_ID and
					sp.Sponsor_ID = pr.Sponsor_ID and
					ci.Check_In_Date=CURDATE()
					group by ci.Check_In_ID order by ci.Check_In_Date_And_Time asc") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
		    while($row = mysqli_fetch_array($select)){
                        //get destination
                        //$select_location  = mysqli_query($conn,"select ") or die(mysqli_error($conn));
                        $Check_In_ID = $row['Check_In_ID'];
                        $AuthorizationNo = $row['AuthorizationNo'];
                        $get = mysqli_query($conn,"select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction,ppl.Clinic_ID, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                                tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                                    pp.Check_In_ID = '$Check_In_ID' and
                                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                                            group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
                        $num_rows = mysqli_num_rows($get);
                        if($num_rows > 0){
                            while($data = mysqli_fetch_array($get)){
                                $Patient_Direction = $data['Patient_Direction'];
                                $Consultant_ID = $data['Consultant_ID'];
                                $Check_In_Type = $data['Check_In_Type'];
                                $Consultant = $data['Consultant'];
                                $Clinic_ID = $data['Clinic_ID'];
                                $Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
                                
                                if(strtolower($Patient_Direction) == 'others'){
                                    $Patient_Direction = $Check_In_Type;
                                }
                                
                                if(strtolower($Check_In_Type) == 'doctor room'){
                                    if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){
                                        //get the doctor name
                                        $select_doctor = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                                        $no_of_rows = mysqli_num_rows($select_doctor);
                                        if($no_of_rows > 0){
                                            while($detail = mysqli_fetch_array($select_doctor)){
                                                $Destination= 'Dr. '.$detail['Employee_Name'];
                                            }
                                        }else{
                                            $Destination = $Consultant;
                                        }
                                        //$Destination.="...".$Clinic_ID;
                                        $sql_select_clinic="SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'";
                                        $sql_select_clinic_result=mysqli_query($conn,$sql_select_clinic) or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_clinic_result)>0){
                                            $clinic_name_rows=mysqli_fetch_assoc($sql_select_clinic_result);
                                            $Clinic_Name=$clinic_name_rows['Clinic_Name'];
                                            $Destination.="~~".$Clinic_Name;
                                        }
                                        
                                    }else{
                                        $Destination = $Consultant;
                                    }
                                }else{
                                    $Destination = $Check_In_Type;
                                }
                            }
                        }else{
                            $Destination = '';
                            $Patient_Direction = '';
                        }
                        
                        //$original_Date = $row['Date_Of_Birth'];
                        //$new_Date = date("Y-m-d", strtotime($original_Date));
                       // $Today = $new_Date;

                        //$age = $Today - $original_Date;
                        ////check for reffered patient.............///////////////////////$Patient_Payment_Item_List_ID
                        $Registration_ID=$row['Registration_ID'];
                        $referral_reason=$row['referral_reason'];
                        $referral_letter=$row['referral_letter'];
                        $hospital_name="";
                        $reffered_patient_from_result=mysqli_query($conn,"SELECT hospital_name FROM tbl_referred_from_hospital rfh INNER JOIN tbl_referred_patients rp ON rfh.referred_from_hospital_id=rp.Referral_Hospital_ID WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID' ") or die(mysqli_error($conn));
                        if(mysqli_num_rows($reffered_patient_from_result)>0){
                            $hospital_name=$hosp_row=mysqli_fetch_assoc($reffered_patient_from_result)['hospital_name'];
                        }
                        ///get diagnosis
                        $diagnosis="";
                        $sql_get_diagnosis=mysqli_query($conn,"SELECT disease_name FROM tbl_disease d INNER JOIN tbl_disease_consultation dc ON d.disease_ID=dc.disease_ID INNER JOIN tbl_consultation c ON dc.consultation_ID=c.consultation_ID WHERE c.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_get_diagnosis)>0){
                            while($diagnosis_rows=mysqli_fetch_assoc($sql_get_diagnosis)){
                                $disease_name=$diagnosis_rows['disease_name'];
                                $diagnosis=$diagnosis.$disease_name.",";
                            }
                        }
                        
                        /////check if admited
                        $sql_select_admission_status_result=mysqli_query($conn,"SELECT Admit_Status FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_admission_status_result)>0){
                           $row_admt_status=mysqli_fetch_assoc($sql_select_admission_status_result);
                           $Admit_Status=$row_admt_status['Admit_Status'];
                        }
                        
            
                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($row['Date_Of_Birth']);
                        $diff = $date1 -> diff($date2);
                        $age = $diff->y." Years, ";
                        $age .= $diff->m." Months, "; 
                        $age .= $diff->d." Days";
                         
                           $resident=   $row['village']." ".$row['District']." ".$row['Region'];
                        //pr.Date_Of_Birth,  pr.
                           
                        $register_AuthorizationNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Member_Number FROM tbl_patient_registration where Registration_ID='$Registration_ID'"))['Member_Number'];
			echo "<tr>
			    <td>".++$temp."</td>
			    <td>".$row['Registration_ID']."</td>
                            <td>".$row['Patient_Name']."</td>
			    <td>".$age."</td>
			    <td>".$row['Gender']."</td>
			    <td>".$row['Tribe']."</td>
			    <td>".$row['village']."</td>
			    <td>$hospital_name</td>
			    <td>$referral_reason</td>
			    <td>";
                            if($referral_letter!=""){
                                echo "<a href='excelfiles/$referral_letter' target='_blank' ><img src='attachment_icon/attachment.png' width='50px'/></a></td>";
                            }  
			   echo " <td>".$row['Check_In_Date_And_Time']."</td>
			    <td>$Admit_Status</td>
                            <td>"."</td>
                            <td>"."</td>
                            <td>$diagnosis</td>
                            <td>"."</td>
                            <td>".$Patient_Direction."</td>
                            <td>".$Destination."</td>
                            <td>".$row['Phone_Number']."</td>
                            <td>".$register_AuthorizationNo."</td>
			</tr>";
                        //<tr><td colspan='17'><hr style='border: .1px solid #ccc' /></td></tr>
		    }
		}
	    ?>
	</table>
</fieldset>
    </div>
</div>
<tabl >
    <tr>
        <td>
            <button style="float:right;margin-top:50px" onclick="print_to_pdf()"class='art-button-green'>PRINT TO PDF</button>
        </td>
    </tr>
</tabl>

<script>
    function print_to_pdf(){
        
        var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Search_Patient_number = document.getElementById("Search_Patient_number").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Destination = document.getElementById("Destination").value;
        var visit_type = document.getElementById("visit_type").value;
	var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
	var alive_diceased = document.getElementById("alive_diceased").value;
	var pf3 = document.getElementById("pf3").value;
	// var rank = document.getElementById("rank").value;
	var patient_type = document.getElementById("patient_type").value;
	var military_unit = document.getElementById("military_unit").value;
        var patient_gender = document.getElementById("patient_gender").value;
	var diagnosis_type = document.getElementById("diagnosis_type").value;
	var consultation_type = document.getElementById("consultation_type").value;
        var Admit_Status = document.getElementById("Admit_Status").value;
        var start_age = document.getElementById("start_age").value;
	var end_age = document.getElementById("end_age").value;
        
       // rank patient_type military_unit
        if (Search_Value) {
	    window.open('checkedinpatientslistpdf.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value+'&Sponsor_ID='+Sponsor_ID+'&Destination='+Destination+"&visit_type="+visit_type+"&Type_Of_Check_In="+Type_Of_Check_In+"&alive_diceased="+alive_diceased+"&pf3="+pf3+"&Search_Patient_number="+Search_Patient_number+"&patient_type="+patient_type+"&military_unit="+military_unit+"&patient_gender="+patient_gender+"&diagnosis_type="+diagnosis_type+"&consultation_type="+consultation_type+"&Admit_Status="+Admit_Status+"&start_age="+start_age+"&end_age="+end_age,true);
	}else{
	    window.open('checkedinpatientslistpdf.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Sponsor_ID='+Sponsor_ID+'&Destination='+Destination+"&visit_type="+visit_type+"&Type_Of_Check_In="+Type_Of_Check_In+"&alive_diceased="+alive_diceased+"&pf3="+pf3+"&Search_Patient_number="+Search_Patient_number+"&patient_type="+patient_type+"&military_unit="+military_unit+"&patient_gender="+patient_gender+"&diagnosis_type="+diagnosis_type+"&consultation_type="+consultation_type+"&Admit_Status="+Admit_Status+"&start_age="+start_age+"&end_age="+end_age,true);
	}
        
        
        //window.open("checkedinpatientslistpdf.php");
    }
    
    
    function Get_Filtered_Patients(){
	 document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Search_Patient_number = document.getElementById("Search_Patient_number").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Destination = document.getElementById("Destination").value;
	var visit_type = document.getElementById("visit_type").value;
	var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
	var alive_diceased = document.getElementById("alive_diceased").value;
	var pf3 = document.getElementById("pf3").value;
        // var rank = document.getElementById("rank").value;
	var patient_type = document.getElementById("patient_type").value;
	var military_unit = document.getElementById("military_unit").value;
	var patient_gender = document.getElementById("patient_gender").value;
	var diagnosis_type = document.getElementById("diagnosis_type").value; 
	var consultation_type = document.getElementById("consultation_type").value;
	var Admit_Status = document.getElementById("Admit_Status").value;
	var start_age = document.getElementById("start_age").value;
	var end_age = document.getElementById("end_age").value;
        //alert(rank+patient_type+military_unit)
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4 && My_Object_Filter_Patient.status == 200) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
	
	if (Search_Value) {
	    My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value+'&Sponsor_ID='+Sponsor_ID+'&Destination='+Destination+"&visit_type="+visit_type+"&Type_Of_Check_In="+Type_Of_Check_In+"&alive_diceased="+alive_diceased+"&pf3="+pf3+"&Search_Patient_number="+Search_Patient_number+"&patient_type="+patient_type+"&military_unit="+military_unit+"&patient_gender="+patient_gender+"&diagnosis_type="+diagnosis_type+"&consultation_type="+consultation_type+"&Admit_Status="+Admit_Status+"&start_age="+start_age+"&end_age="+end_age,true);
	}else{
	    My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Sponsor_ID='+Sponsor_ID+'&Destination='+Destination+"&visit_type="+visit_type+"&Type_Of_Check_In="+Type_Of_Check_In+"&alive_diceased="+alive_diceased+"&pf3="+pf3+"&Search_Patient_number="+Search_Patient_number+"&patient_type="+patient_type+"&military_unit="+military_unit+"&patient_gender="+patient_gender+"&diagnosis_type="+diagnosis_type+"&consultation_type="+consultation_type+"&Admit_Status="+Admit_Status+"&start_age="+start_age+"&end_age="+end_age,true);
	}
	
	My_Object_Filter_Patient.send();
    }
</script>


<script>
    function Get_Filtered_Patients_Filter(){
	 document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Search_Patient_number = document.getElementById("Search_Patient_number").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Destination = document.getElementById("Destination").value;
        var visit_type = document.getElementById("visit_type").value;
	var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
	var alive_diceased = document.getElementById("alive_diceased").value;
	var pf3 = document.getElementById("pf3").value;
        // var rank = document.getElementById("rank").value;
	var patient_type = document.getElementById("patient_type").value;
	var military_unit = document.getElementById("military_unit").value;
        
        var patient_gender = document.getElementById("patient_gender").value;
	var diagnosis_type = document.getElementById("diagnosis_type").value;
	var consultation_type = document.getElementById("consultation_type").value;
	var Admit_Status = document.getElementById("Admit_Status").value;
    var start_age = document.getElementById("start_age").value;
	var end_age = document.getElementById("end_age").value;
        
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4 && My_Object_Filter_Patient.status == 200) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
		
	My_Object_Filter_Patient.open('GET','Revenue_Get_Checked_Patients_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value+'&Sponsor_ID='+Sponsor_ID+'&Destination='+Destination+"&visit_type="+visit_type+"&Type_Of_Check_In="+Type_Of_Check_In+"&alive_diceased="+alive_diceased+"&pf3="+pf3+"&Search_Patient_number="+Search_Patient_number +"&patient_type="+patient_type+"&military_unit="+military_unit+"&patient_gender="+patient_gender+"&diagnosis_type="+diagnosis_type+"&consultation_type="+consultation_type+"&Admit_Status="+Admit_Status+"&start_age="+start_age+"&end_age="+end_age,true);
	My_Object_Filter_Patient.send();
    }
</script>
<script>
    function get_opd_report(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var Search_Patient_number = document.getElementById("Search_Patient_number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Destination = document.getElementById("Destination").value;
        var visit_type = document.getElementById("visit_type").value;
        var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
        var alive_diceased = document.getElementById("alive_diceased").value;
        var pf3 = document.getElementById("pf3").value;
        
        var url="opd_list_of_received_report.php";
        $.ajax({
            type:'GET',
            url:url,
            data:{Date_From:Date_From,Date_To:Date_To,Search_Value:Search_Value,Sponsor_ID:Sponsor_ID,Destination:Destination,visit_type:visit_type,Type_Of_Check_In:Type_Of_Check_In,alive_diceased:alive_diceased,pf3:pf3,Search_Patient_number:Search_Patient_number },
            success:function (data){
               document.getElementById('Patients_Fieldset_List').innerHTML = data; 
            },
            complete:function(){
                
            }
        });
    }
</script>
<?php
    include("./includes/footer.php");
?>